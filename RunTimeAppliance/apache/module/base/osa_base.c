#include "osa_base.h"

// Alphabet for base64 encoding/decoding tables
static char encoding_table[] = {'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H',
								'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P',
								'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X',
								'Y', 'Z', 'a', 'b', 'c', 'd', 'e', 'f',
								'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n',
								'o', 'p', 'q', 'r', 's', 't', 'u', 'v',
								'w', 'x', 'y', 'z', '0', '1', '2', '3',
								'4', '5', '6', '7', '8', '9', '+', '/'};
char decoding_table [255];
int mod_table[] = {0, 2, 1};
char rfc3986[256] = {0};


//BHE----------
char *to_json_quota(request_rec *r, unsigned long reqSec, unsigned long reqDay, unsigned long reqMonth){
	json_object * quota = json_object_new_object();


	
	json_object_object_add(quota, "reqSec", json_object_new_int64(reqSec));
	json_object_object_add(quota, "reqDay", json_object_new_int64(reqDay));
	json_object_object_add(quota, "reqMonth", json_object_new_int64(reqMonth));
	char *rc =PSTRDUP(r->pool, json_object_to_json_string(quota)); 
	json_object_put(quota);

	return rc;
}
void from_json_quota(request_rec *r, char *jsonStr, unsigned long *reqSec, unsigned long *reqDay, unsigned long *reqMonth){
	json_object *jobj=json_tokener_parse(jsonStr);

	json_object *val;
	json_object_object_get_ex(jobj, "reqSec", &val);
	*reqSec = json_object_get_int64(val);
	json_object_object_get_ex(jobj, "reqDay", &val);
	*reqDay = json_object_get_int64(val);
	json_object_object_get_ex(jobj, "reqMonth", &val);
	*reqMonth = json_object_get_int64(val);

	json_object_put(jobj);

}

char *to_json_string(request_rec *r, stringKeyValList *list){
	json_object * user = json_object_new_object();
	json_object *jarray = json_object_new_array();

	for (int i=0;i<list->listCount;i++){
		json_object *attr = json_object_new_object();
		json_object *jstring = json_object_new_string(list->list[i].val);
		json_object_object_add(attr, list->list[i].key, jstring);
		json_object_array_add(jarray,attr);
	}
	json_object_object_add(user, "keval", jarray);
	char *rc =PSTRDUP(r->pool, json_object_to_json_string(user)); 
	json_object_put(user);

	return rc;
}

void from_json_string(request_rec *r, char *jsonStr, stringKeyValList *list){
	json_object *jobj=json_tokener_parse(jsonStr);

	json_object *attrs;
	json_object_object_get_ex(jobj, "keval", &attrs);


	list->listCount=json_object_array_length(attrs);

	for (int i = 0; i < json_object_array_length(attrs); i++) {
		json_object *tmp = json_object_array_get_idx(attrs, i);
		json_object_object_foreach(tmp, key, val) {
			list->list[i].key=PSTRDUP(r->pool, key);
			list->list[i].val=PSTRDUP(r->pool, json_object_get_string(val));		
		}
	}
	json_object_put(jobj);

}
int acquire(request_rec *r){
 	// if (socache_mutex) {
    //     apr_status_t status = apr_global_mutex_lock(socache_mutex);
    //     if (status != APR_SUCCESS) {
    //         ap_log_rerror(APLOG_MARK, APLOG_ERR, status, r, APLOGNO(02350)
    //                 "could not acquire lock, ignoring cache");
    //         return FALSE;
    //     }
    // }
	return  TRUE;

}

int release(request_rec *r){
	// if (socache_mutex) {
	// 	apr_status_t status = apr_global_mutex_unlock(socache_mutex);
	// 	if (status != APR_SUCCESS) {
	// 		ap_log_rerror(APLOG_MARK, APLOG_ERR, status, r, APLOGNO(02356)
	// 				"could not release lock, ignoring cache");
	// 		return FALSE;
	// 	}
    // }
	return TRUE;
}

// initialize mutex mgnt for chld processes
static void osa_child_init(apr_pool_t *p, server_rec *s)
{
    const char *lock;
    apr_status_t rv;
    if (!socache_mutex) {
        return; /* don't waste the overhead of creating mutex & cache */
    }
    lock = apr_global_mutex_lockfile(socache_mutex);
    rv = apr_global_mutex_child_init(&socache_mutex, lock, p);
    if (rv != APR_SUCCESS) {
        ap_log_error(APLOG_MARK, APLOG_CRIT, rv, s, APLOGNO(02394)
                "failed to initialise mutex in child_init");
    }
}

//Remove global mutex
static apr_status_t remove_lock(void *data)
{
    if (socache_mutex) {
        apr_global_mutex_destroy(socache_mutex);
        socache_mutex = NULL;
    }
    return APR_SUCCESS;
}

// Clean up on module shutdown: cache destoy
static apr_status_t destroy_cache(void *data)
{
    server_rec *s = data;
    if (osa_cache.provider && osa_cache.socache_instance) {
        osa_cache.provider->destroy(
                osa_cache.socache_instance, s);
        osa_cache.socache_instance = NULL;
    }
    return APR_SUCCESS;
}



// register mutex as soon a possible
static int osa_precfg(apr_pool_t *pconf, apr_pool_t *plog, apr_pool_t *ptmp)
{
    apr_status_t rv = ap_mutex_register(pconf, SOCACHE_ID, NULL,
            APR_LOCK_DEFAULT, 0);
    if (rv != APR_SUCCESS) {
        ap_log_perror(APLOG_MARK, APLOG_CRIT, rv, plog, APLOGNO(02390)
        "failed to register %s mutex", SOCACHE_ID);
        return 500; /* An HTTP status would be a misnomer! */
    }

    return OK;
}
static int cache_post_config(apr_pool_t *pconf, apr_pool_t *plog,
        apr_pool_t *ptmp, server_rec *base_server)
{
	const char *lock;
    apr_status_t rv;

	//BHE
	//rpdp_server_config_rec *conf = ap_get_module_config(base_server->module_config, &rpdp_module);
	osa_cache.cache_socache_id=SOCACHE_ID;

	if (!socache_mutex) {
    	rv = ap_global_mutex_create(&socache_mutex, NULL, osa_cache.cache_socache_id, NULL, base_server, pconf, 0);
		if (rv != APR_SUCCESS) {
			ap_log_perror(APLOG_MARK, APLOG_CRIT, rv, plog, APLOGNO(02391)
			"failed to create %s mutex", osa_cache.cache_socache_id);
			return 500; /* An HTTP status would be a misnomer! */
        }else{
			ap_log_perror(APLOG_MARK, APLOG_DEBUG, rv, plog, 
			"mutex created for %s", osa_cache.cache_socache_id);			
		}
	}
 	apr_pool_cleanup_register(pconf, NULL, remove_lock, apr_pool_cleanup_null);

	osa_server_config_rec *conf = ap_get_module_config(base_server->module_config, &osa_module);
		ap_log_perror(APLOG_MARK, APLOG_NOTICE, 0, plog,
			"cache file is: %s", conf->cache_filename);
	// static struct ap_socache_hints socache_hints =
    // { 64, 2048, 60000000 };

	char cache_prov[20];
	char *sep = strchr(conf->cache_filename, ':');
	if (sep == NULL){
		strcpy(cache_prov, "shmcb");
		ap_log_perror(APLOG_MARK, APLOG_NOTICE, 0, plog,
			"cache spec are: %s (default) -> %s", cache_prov, conf->cache_filename);
	}else{
		strncpy(cache_prov, conf->cache_filename, sep-conf->cache_filename);
		cache_prov[sep-conf->cache_filename]=0;
		conf->cache_filename=sep+1;
		ap_log_perror(APLOG_MARK, APLOG_NOTICE, 0, plog,
			"cache spec are: %s -> %s", cache_prov, conf->cache_filename);
	}	
	//"shmcb"

	osa_cache.provider = ap_lookup_provider(AP_SOCACHE_PROVIDER_GROUP, cache_prov, AP_SOCACHE_PROVIDER_VERSION);
	if (!osa_cache.provider){
		ap_log_perror(APLOG_MARK, APLOG_CRIT, 0, plog,
			"shmcb/dbm provider not found");
		return HTTP_INTERNAL_SERVER_ERROR;
		
	}

	const char *errmsg = osa_cache.provider->create(&(osa_cache.socache_instance), conf->cache_filename, ptmp, pconf);
	//BHE
	//const char *errmsg = osa_cache.provider->create(&(osa_cache.socache_instance), conf->cache_filename, ptmp, pconf);
	if (errmsg) {
		ap_log_perror(APLOG_MARK, APLOG_CRIT, 0, plog,
			"cache creation failure: %s", errmsg);
		return HTTP_INTERNAL_SERVER_ERROR;
	}

	rv = osa_cache.provider->init(osa_cache.socache_instance, osa_cache.cache_socache_id,
			NULL, base_server, pconf);
			//&socache_hints, base_server, pconf);
	if (rv != APR_SUCCESS) {
		ap_log_perror(APLOG_MARK, APLOG_CRIT, rv, plog, 
			"OSA cache %s initialization failure", osa_cache.cache_socache_id);
		return HTTP_INTERNAL_SERVER_ERROR; // An HTTP status would be a misnomer! 
	}
	
	apr_pool_cleanup_register(pconf, (void *) base_server, destroy_cache, apr_pool_cleanup_null);
		
	return OK;

}

int read_quota_from_cache(server_rec *server, request_rec *r, char * resource, char *user, unsigned long *reqSec, unsigned long *reqDay, unsigned long *reqMonth){
	int rc;


	if (!acquire(r)) return FALSE;



	unsigned char id[ strlen(resource) + strlen(user) + strlen(QUOTA_CACHE_ID_PATTERN)];
				ap_log_rerror(APLOG_MARK, APLOG_DEBUG, 0, r, 
				"%s", id);
	//int datalen = sizeof(grant);
	int datalen = MAX_STRING_LEN;
	char jsonStr[datalen];

	sprintf(id, QUOTA_CACHE_ID_PATTERN, resource, user); 

	apr_status_t rv = osa_cache.provider->retrieve(osa_cache.socache_instance, server, id,  strlen(id), jsonStr, &datalen, r->pool);
	if (rv != APR_SUCCESS) {
		switch (rv){
			case APR_NOTFOUND:
				ap_log_rerror(APLOG_MARK, APLOG_DEBUG, rv, r, 
				"failed to retreive object err=%s", "APR_NOTFOUND");
			break;;
			case APR_EGENERAL:
				ap_log_rerror(APLOG_MARK, APLOG_DEBUG, rv, r, 
				"failed to retreive object err=%s", "APR_EGENERAL");
			break;;
			case APR_ENOSPC:
				ap_log_rerror(APLOG_MARK, APLOG_DEBUG, rv, r, 
				"failed to retreive object err=%s", "APR_ENOSPC");
			break;;
			default:
				ap_log_rerror(APLOG_MARK, APLOG_DEBUG, rv, r, 
				"failed to retreive object err=%s", "DEFAULT");
			break;;

		}
		ap_log_rerror(APLOG_MARK, APLOG_DEBUG, rv, r, 
		"failed to retreive object from %s %d", osa_cache.cache_socache_id, rv);
		rc=FALSE;
	}else{
		from_json_quota(r, jsonStr, reqSec, reqDay, reqMonth);
		ap_log_rerror(
			APLOG_MARK, APLOG_DEBUG, rv, r, "got quota %s for U=%s r=%s from cache %s (%lu,%lu,%lu)",
			jsonStr, user, resource, osa_cache.cache_socache_id, *reqSec, *reqDay, *reqMonth
		);
		rc=TRUE;
	}

	if (!release(r)) return FALSE;
	return rc;
}
void store_quota_cache(request_rec *r, char * resource, char *user, unsigned long reqSec, unsigned long reqDay, unsigned long reqMonth, int ttl){

	if (!acquire(r)) return ;



	unsigned char id[strlen(resource) + strlen(user) + strlen(QUOTA_CACHE_ID_PATTERN)];
	char *jsonStr = to_json_quota(r, reqSec, reqDay, reqMonth);
	//rpdp_config_rec *conf = ap_get_module_config(r->per_dir_config, &rpdp_module);
	//ap_log_rerror(APLOG_MARK, APLOG_DEBUG, 0, r, "TTL=%lu", conf->cacheTTL);

	sprintf(id, QUOTA_CACHE_ID_PATTERN, resource, user); 
	apr_status_t rv = osa_cache.provider->store(osa_cache.socache_instance, r->server, id,  strlen(id), apr_time_now()+ (ttl * 1000000), jsonStr, strlen(jsonStr)+1, r->pool);
	if (rv != APR_SUCCESS){
		ap_log_rerror(APLOG_MARK, APLOG_CRIT, rv, r, "failed to store object err=%d", rv);
	}else{
		ap_log_rerror(APLOG_MARK, APLOG_DEBUG, rv, r, "object succesfully stored quota");
	}
	

	release(r);
	
}

static int read_keyval_from_cache(server_rec *server, request_rec *r, char *dataType, char * resource, char *user, stringKeyValList *list){
	int rc;

	if (!acquire(r)) return FALSE;



	unsigned char id[strlen(dataType) + strlen(resource) + strlen(user) + strlen(KEYVAL_CACHE_ID_PATTERN)];
	//int datalen = sizeof(grant);
	int datalen = MAX_STRING_LEN;
	char jsonStr[datalen];

	sprintf(id, KEYVAL_CACHE_ID_PATTERN, dataType, resource, user); 

	apr_status_t rv = osa_cache.provider->retrieve(osa_cache.socache_instance, server, id,  strlen(id), jsonStr, &datalen, r->pool);
	if (rv != APR_SUCCESS) {
		switch (rv){
			case APR_NOTFOUND:
				ap_log_rerror(APLOG_MARK, APLOG_DEBUG, rv, r, 
				"failed to retreive object err=%s", "APR_NOTFOUND");
			break;;
			case APR_EGENERAL:
				ap_log_rerror(APLOG_MARK, APLOG_DEBUG, rv, r, 
				"failed to retreive object err=%s", "APR_EGENERAL");
			break;;
			case APR_ENOSPC:
				ap_log_rerror(APLOG_MARK, APLOG_DEBUG, rv, r, 
				"failed to retreive object err=%s", "APR_ENOSPC");
			break;;
			default:
				ap_log_rerror(APLOG_MARK, APLOG_DEBUG, rv, r, 
				"failed to retreive object err=%s", "DEFAULT");
			break;;

		}
		ap_log_rerror(APLOG_MARK, APLOG_DEBUG, rv, r, 
		"failed to retreive object from %s %d", osa_cache.cache_socache_id, rv);
		rc=FALSE;
	}else{
		from_json_string(r, jsonStr, list);
		ap_log_rerror(APLOG_MARK, APLOG_DEBUG, rv, r, "got data %s for U=%s r=%s from cache %s", jsonStr, user, resource, osa_cache.cache_socache_id);
		rc=TRUE;
	}

	if (!release(r)) return FALSE;
	return rc;
}
static void store_keyval_cache(request_rec *r, char *dataType,  char * resource, char *user, stringKeyValList *list, int ttl){

	if (!acquire(r)) return ;



	unsigned char id[strlen(dataType) + strlen(resource) + strlen(user) + strlen(KEYVAL_CACHE_ID_PATTERN)];
	char *jsonStr = to_json_string(r, list);
	//rpdp_config_rec *conf = ap_get_module_config(r->per_dir_config, &rpdp_module);
	//ap_log_rerror(APLOG_MARK, APLOG_DEBUG, 0, r, "TTL=%lu", conf->cacheTTL);

	sprintf(id, KEYVAL_CACHE_ID_PATTERN, dataType, resource, user); 
	apr_status_t rv = osa_cache.provider->store(osa_cache.socache_instance, r->server, id,  strlen(id), apr_time_now()+ (ttl * 1000000), jsonStr, strlen(jsonStr)+1, r->pool);
	if (rv != APR_SUCCESS){
		ap_log_rerror(APLOG_MARK, APLOG_CRIT, rv, r, "failed to store object err=%d", rv);
	}else{
		ap_log_rerror(APLOG_MARK, APLOG_DEBUG, rv, r, "object succesfully stored %s", dataType);
	}
	

	release(r);
	
}
static int read_pw_from_cache(server_rec *server, request_rec *r, char *user, char *pw){
	int rc;

	if (!acquire(r)) return FALSE;



	unsigned char id[strlen(user) + strlen(USER_PW_CACHE_ID_PATTERN)];
	//int datalen = sizeof(grant);
	int datalen = MAX_STRING_LEN;
	char data[datalen];

	sprintf(id, USER_PW_CACHE_ID_PATTERN, user); 

	apr_status_t rv = osa_cache.provider->retrieve(osa_cache.socache_instance, server, id,  strlen(id), data, &datalen, r->pool);
	if (rv != APR_SUCCESS) {
		switch (rv){
			case APR_NOTFOUND:
				ap_log_rerror(APLOG_MARK, APLOG_DEBUG, rv, r, 
				"failed to retreive object err=%s", "APR_NOTFOUND");
			break;;
			case APR_EGENERAL:
				ap_log_rerror(APLOG_MARK, APLOG_DEBUG, rv, r, 
				"failed to retreive object err=%s", "APR_EGENERAL");
			break;;
			case APR_ENOSPC:
				ap_log_rerror(APLOG_MARK, APLOG_DEBUG, rv, r, 
				"failed to retreive object err=%s", "APR_ENOSPC");
			break;;
			default:
				ap_log_rerror(APLOG_MARK, APLOG_DEBUG, rv, r, 
				"failed to retreive object err=%s", "DEFAULT");
			break;;

		}
		ap_log_rerror(APLOG_MARK, APLOG_DEBUG, rv, r, 
			"failed to retreive object from %s %d", osa_cache.cache_socache_id, rv
		);
		rc=FALSE;
	}else{
		data[datalen]=0;
		ap_log_rerror(APLOG_MARK, APLOG_DEBUG, rv, r, "got pw %s for U=%s fromcache %s", data, user, osa_cache.cache_socache_id);
		strcpy(pw, data);
		rc=TRUE;
	}


	if (!release(r)) return FALSE;
	return rc;
}
static void store_pw_cache(request_rec *r, char *user, char *pw){

	if (!acquire(r)) return ;



	unsigned char id[strlen(user) + strlen(USER_PW_CACHE_ID_PATTERN)];
	//rpdp_config_rec *conf = ap_get_module_config(r->per_dir_config, &rpdp_module);
	//ap_log_rerror(APLOG_MARK, APLOG_DEBUG, 0, r, "TTL=%lu", conf->cacheTTL);

	sprintf(id, USER_PW_CACHE_ID_PATTERN, user); 
	apr_status_t rv = osa_cache.provider->store(osa_cache.socache_instance, r->server, id,  strlen(id), apr_time_now()+ (30 * 1000000), pw, strlen(pw)+1, r->pool);
	if (rv != APR_SUCCESS){
		ap_log_rerror(APLOG_MARK, APLOG_CRIT, rv, r, "failed to store object err=%d", rv);
	}else{
		ap_log_rerror(APLOG_MARK, APLOG_DEBUG, rv, r, "object succesfully stored: PASSWORD");
	}
	

	release(r);
	
}
static int read_tokens_clean_cache(server_rec *server, request_rec *r){
	int rc;

	if (!acquire(r)) return FALSE;



	unsigned char id[strlen(TOKEN_CLEAN_CACHE_ID_PATTERN)+1];
	//int datalen = sizeof(grant);
	int datalen = MAX_STRING_LEN;
	char data[1];

	sprintf(id, TOKEN_CLEAN_CACHE_ID_PATTERN); 

	apr_status_t rv = osa_cache.provider->retrieve(osa_cache.socache_instance, server, id,  strlen(id), data, &datalen, r->pool);
	if (rv != APR_SUCCESS) {
		switch (rv){
			case APR_NOTFOUND:
				ap_log_rerror(APLOG_MARK, APLOG_DEBUG, rv, r, 
				"failed to retreive object err=%s", "APR_NOTFOUND");
			break;;
			case APR_EGENERAL:
				ap_log_rerror(APLOG_MARK, APLOG_DEBUG, rv, r, 
				"failed to retreive object err=%s", "APR_EGENERAL");
			break;;
			case APR_ENOSPC:
				ap_log_rerror(APLOG_MARK, APLOG_DEBUG, rv, r, 
				"failed to retreive object err=%s", "APR_ENOSPC");
			break;;
			default:
				ap_log_rerror(APLOG_MARK, APLOG_DEBUG, rv, r, 
				"failed to retreive object err=%s", "DEFAULT");
			break;;

		}
		ap_log_rerror(APLOG_MARK, APLOG_DEBUG, rv, r, 
		"failed to retreive object from %s %d", osa_cache.cache_socache_id, rv);
		rc=FALSE;
	}else{
		rc=TRUE;
	}

	if (!release(r)) return FALSE;
	return rc;
}
static void store_tokens_clean(request_rec *r){

	if (!acquire(r)) return ;
	osa_config_rec *sec =(osa_config_rec *)ap_get_module_config (r->per_dir_config, &osa_module);
	



	unsigned char id[strlen(TOKEN_CLEAN_CACHE_ID_PATTERN)+1];
	//rpdp_config_rec *conf = ap_get_module_config(r->per_dir_config, &rpdp_module);
	//ap_log_rerror(APLOG_MARK, APLOG_DEBUG, 0, r, "TTL=%lu", conf->cacheTTL);

	sprintf(id, TOKEN_CLEAN_CACHE_ID_PATTERN); 
	apr_status_t rv = osa_cache.provider->store(osa_cache.socache_instance, r->server, id,  strlen(id), apr_time_now()+ (sec->cookieCacheTime * 1000000), "", 0, r->pool);
	if (rv != APR_SUCCESS){
		ap_log_rerror(APLOG_MARK, APLOG_CRIT, rv, r, "failed to store object err=%d", rv);
	}else{
		//ap_log_rerror(APLOG_MARK, APLOG_DEBUG, rv, r, "object succesfully stored: (tokens_cleaner)");
	}
	

	release(r);
	
}

//BHE----------------


void url_encoder_rfc_tables_init(){

    int i;

    for (i = 0; i < 256; i++){

        rfc3986[i] = isalnum( i) || i == '~' || i == '-' || i == '.' || i == '_' ? i : 0;
    }
}

char *url_encode(unsigned char *s, char *enc){

    for (; *s; s++){
        if (rfc3986[*s]) sprintf( enc, "%c", rfc3986[*s]);
        else sprintf( enc, "%%%02X", *s);
        while (*++enc);
    }

    return( enc);
}

/*------------------------------------------------------------------------*/
/*                 void build_decoding_table()                            */
/*------------------------------------------------------------------------*/
/*   Initialize base64 decoding table                                     */
/*------------------------------------------------------------------------*/
void build_decoding_table() {


	int i;
	for ( i = 0; i < 64; i++) {
		decoding_table[(unsigned char) encoding_table[i]] = i;
	}
}

/*--------------------------------------------------------------------------------*/
/* char *base64_encode(POOL *p, const unsigned char *data, size_t *output_length) */
/*--------------------------------------------------------------------------------*/
/*   Encode data in base64                                                        */
/*--------------------------------------------------------------------------------*/
/* IN:                                                                            */
/*     POOL *p: apache pool (for memory allocation)                               */
/*     const unsigned char *data: data to encode                                  */
/* OUT:                                                                           */
/*     site_t *output_length: encoded data length                                 */
/* RETURN:                                                                        */
/*        char *: encoded data                                                    */
/*--------------------------------------------------------------------------------*/
char *base64_encode(POOL *p, const unsigned char *data,
					size_t *output_length) {

	size_t input_length=strlen(data);

	*output_length = 4 * ((input_length + 2) / 3);

	char *encoded_data = PCALLOC(p, *output_length+1);
	//memset(encoded_data, 0, *output_length+1);
	if (encoded_data == NULL) return NULL;

	int i,j;
	for ( i = 0, j = 0; i < input_length;) {
		int octet_a = i < input_length ? data[i++] : 0;
		int octet_b = i < input_length ? data[i++] : 0;
		int octet_c = i < input_length ? data[i++] : 0;

		int triple = (octet_a << 0x10) + (octet_b << 0x08) + octet_c;

		encoded_data[j++] = encoding_table[(triple >> 3 * 6) & 0x3F];
		encoded_data[j++] = encoding_table[(triple >> 2 * 6) & 0x3F];
		encoded_data[j++] = encoding_table[(triple >> 1 * 6) & 0x3F];
		encoded_data[j++] = encoding_table[(triple >> 0 * 6) & 0x3F];
	}

	for ( i = 0; i < mod_table[input_length % 3]; i++)

	encoded_data[*output_length - 1 - i] = '=';
	encoded_data[*output_length]=0;
	return encoded_data;
}


/*--------------------------------------------------------------------------------*/
/* char *base64_decode(const char *data, size_t *output_length,                   */
/*                     unsigned char *decoded_data)                               */
/*--------------------------------------------------------------------------------*/
/*   Encode data in base64                                                        */
/*--------------------------------------------------------------------------------*/
/* IN:                                                                            */
/*     const char *data: data to decode                                           */
/* OUT:                                                                           */
/*     site_t *output_length: decoded data length                                 */
/*     unsigned char *decoded_data: decoded data                                  */
/* RETURN:                                                                        */
/*        char *: decoded data                                                    */
/*--------------------------------------------------------------------------------*/
unsigned char *base64_decode(const char *data,
							 size_t *output_length,
							 unsigned char *decoded_data) {

	size_t input_length=strlen(data);

	if (input_length % 4 != 0) return NULL;

	*output_length = input_length / 4 * 3;
	if (data[input_length - 1] == '=') (*output_length)--;
	if (data[input_length - 2] == '=') (*output_length)--;

	if (decoded_data == NULL) return NULL;

	int i,j;
	for ( i = 0, j = 0; i < input_length;) {

			int sextet_a = data[i] == '=' ? 0 & i++ : decoding_table[data[i++]];
			int sextet_b = data[i] == '=' ? 0 & i++ : decoding_table[data[i++]];
			int sextet_c = data[i] == '=' ? 0 & i++ : decoding_table[data[i++]];
			int sextet_d = data[i] == '=' ? 0 & i++ : decoding_table[data[i++]];

			int triple = (sextet_a << 3 * 6)
			+ (sextet_b << 2 * 6)
			+ (sextet_c << 1 * 6)
			+ (sextet_d << 0 * 6);

			if (j < *output_length) decoded_data[j++] = (triple >> 2 * 8) & 0xFF;
			if (j < *output_length) decoded_data[j++] = (triple >> 1 * 8) & 0xFF;
			if (j < *output_length) decoded_data[j++] = (triple >> 0 * 8) & 0xFF;
	}

	//base64_cleanup();
	decoded_data[*output_length]=0;
	return decoded_data;
}


void split(request_rec *r, char *str, char delimiter, spliting *s){

	int i=0;
	int wordLen=0;
	char *ptr=str;
	s->tokensCount=0;
	while (str[i]){
		if (str[i]==delimiter){

			char c=ptr[wordLen];
			ptr[wordLen]=0;
			s->tokens[s->tokensCount]=PSTRDUP(r->pool, ptr);
			ptr[wordLen]=c;

			ptr=str+i;
			ptr++;
			wordLen=0;
			s->tokensCount++;
		}else{
			wordLen++;
		}
		i++;
	}
	s->tokens[s->tokensCount]=PSTRDUP(r->pool, ptr);
	s->tokensCount++;
}

char *replace(char *st, char *orig, char *repl) {
	static char buffer[MAX_STRING_LEN];
	char *ch;
	if (!(ch = strstr(st, orig))){
		return st;
	}
	strncpy(buffer, st, ch-st);  
	buffer[ch-st] = 0;
	sprintf(buffer+(ch-st), "%s%s", repl, ch+strlen(orig));
	return buffer;
}




/*--------------------------------------------------------------------------------------------------*/
/*                 void dumpHTMLError(request_rec *r, char *errMSG)                                 */
/*--------------------------------------------------------------------------------------------------*/
/* Error management for Nursery's mediations                                                        */
/* Display error text in main body for request as HTML                                              */
/*--------------------------------------------------------------------------------------------------*/
/* IN:                                                                                              */
/*        request_rec *r: apache request                                                            */
/*        char *errMSG: message to diplay                                                           */
/*        int status: HTTP status to set for response                                               */
/*--------------------------------------------------------------------------------------------------*/
/* RETURN: effectiove HTTP status (i.e 500 for SOAP, else status parameter                          */
/*         DONE                                                                                     */
/*--------------------------------------------------------------------------------------------------*/
void dumpHTMLError(request_rec *r, char *errMSG){
	char *strHttpBody;

	strHttpBody=apr_psprintf(r->pool, "%s%s%s%s%s%s%s",
		"<h1>An error has occurred</h1>\n",
		"<table>\n",
		"	<tr><td>Error code:</td><td>-1</td></tr>\n",
		"	<tr><td>Error label:</td><td>",
		errMSG,
		"</td></tr>\n",
		"</table>\n"
	);

	r->content_type="text/html";
	ap_rputs(strHttpBody, r);
}

/*--------------------------------------------------------------------------------------------------*/
/*                 void dumpTextError(request_rec *r, char *errMSG)                                 */
/*--------------------------------------------------------------------------------------------------*/
/* Error management for Nursery's mediations                                                        */
/* Display error text in main body for request as HTML                                              */
/*--------------------------------------------------------------------------------------------------*/
/* IN:                                                                                              */
/*        request_rec *r: apache request                                                            */
/*        char *errMSG: message to diplay                                                           */
/*        int status: HTTP status to set for response                                               */
/*--------------------------------------------------------------------------------------------------*/
/* RETURN: effectiove HTTP status (i.e 500 for SOAP, else status parameter                          */
/*         DONE                                                                                     */
/*--------------------------------------------------------------------------------------------------*/
void dumpTextError(request_rec *r, char *errMSG){
	char *strHttpBody;

	strHttpBody=apr_psprintf(r->pool, "%s%s%s\n",
		"Error code: -1\n",
		"Error label: ",
		errMSG
	);

	r->content_type="text/plain";
	ap_rputs(strHttpBody, r);

}

/*--------------------------------------------------------------------------------------------------*/
/*                 void dumpJSONFault(request_rec *r, char *errMSG)                                 */
/*--------------------------------------------------------------------------------------------------*/
/* Error management for Nursery's mediations                                                        */
/* Display error text in main body for request as JSON                                              */
/*--------------------------------------------------------------------------------------------------*/
/* IN:                                                                                              */
/*        request_rec *r: apache request                                                            */
/*        char *errMSG: message to diplay                                                           */
/*        int status: HTTP status to set for response                                               */
/*--------------------------------------------------------------------------------------------------*/
/* RETURN: effectiove HTTP status (i.e 500 for SOAP, else status parameter                          */
/*         DONE                                                                                     */
/*--------------------------------------------------------------------------------------------------*/
void dumpJSONError(request_rec *r, char *errMSG){
	char *strHttpBody;

	char *errorMessage;
	errorMessage=apr_psprintf(r->pool, "%s", replace(errMSG,"\n","\\n"));
	errorMessage=apr_psprintf(r->pool, "%s", replace(errorMessage,"\"","\\\""));

	strHttpBody=apr_psprintf(r->pool, "%s%s%s%s%s%s",
		"{\n",
		"    \"code\": \"-1\",\n",
		"    \"label\": \"",
		errorMessage,
		"\"\n",
		"}\n"
	);

	r->content_type="application/json";
	ap_rputs(strHttpBody, r);

}

/*--------------------------------------------------------------------------------------------------*/
/*                 void dumpXMLFault(request_rec *r, char *errMSG){                                 */
/*--------------------------------------------------------------------------------------------------*/
/* Error management for Nursery's mediations                                                        */
/* Display error text in main body for request as XML                                               */
/*--------------------------------------------------------------------------------------------------*/
/* IN:                                                                                              */
/*        request_rec *r: apache request                                                            */
/*        char *errMSG: message to diplay                                                           */
/*        int status: HTTP status to set for response                                               */
/*--------------------------------------------------------------------------------------------------*/
/* RETURN: effectiove HTTP status (i.e 500 for SOAP, else status parameter                          */
/*         DONE                                                                                     */
/*--------------------------------------------------------------------------------------------------*/
void dumpXMLError(request_rec *r, char *errMSG){
	char *strHttpBody;

	strHttpBody=apr_psprintf(r->pool, "%s%s%s%s%s%s",
		"<?xml version='1.0' encoding='UTF-8'?>\n",
		"<osa>\n",
		"	<code>-1</code>\n",
		"	<label>",
		errMSG,
		"</osa>\n"
	);

	r->content_type="text/xml";
	ap_rputs(strHttpBody, r);

}

/*--------------------------------------------------------------------------------------------------*/
/*                 void dumpSOAPFault(request_rec *r, char *errMSG){                                */
/*--------------------------------------------------------------------------------------------------*/
/* Error management for Nursery's mediations                                                        */
/* Display error text in main body for request as SOAP Fault                                        */
/*--------------------------------------------------------------------------------------------------*/
/* IN:                                                                                              */
/*        request_rec *r: apache request                                                            */
/*        char *errMSG: message to diplay                                                           */
/*        int status: HTTP status to set for response                                               */
/*--------------------------------------------------------------------------------------------------*/
/* RETURN: effectiove HTTP status (i.e 500 for SOAP, else status parameter                          */
/*         DONE                                                                                     */
/*--------------------------------------------------------------------------------------------------*/
void dumpSOAPFault(request_rec *r, char *errMSG){
	char *strHttpBody;

	strHttpBody=apr_psprintf(r->pool, "%s%s%s%s%s%s%s%s%s%s%s%s",
		"<?xml version='1.0' ?>\n",
		"<env:Envelope xmlns:env='http://schemas.xmlsoap.org/soap/envelope/'>\n",
		"	<env:Body>\n",
		"		<env:Fault>\n",
		"			<faultcode>env:Server</faultcode>\n",
		"			<faultstring>",
		"                    	",
		errMSG,
		"                    </faultstring>\n",
		"		</env:Fault>\n",
		"	</env:Body>\n",
		"</env:Envelope>\n"
	);

	r->content_type="text/xml";


	ap_rputs(strHttpBody, r);

}




/*--------------------------------------------------------------------------------------------------*/
/*                 int renderErrorBody(request_rec *r, char *errMSG, int status)                    */
/*--------------------------------------------------------------------------------------------------*/
/* Error management for Nursery's mediations                                                        */
/* Display error text in main body for request depending on reqiested formet (SOAP, XML, JSON....)  */
/*--------------------------------------------------------------------------------------------------*/
/* IN:                                                                                              */
/*        request_rec *r: apache request                                                            */
/*        char *errMSG: message to diplay                                                           */
/*        int status: HTTP status to set for response                                               */
/*--------------------------------------------------------------------------------------------------*/
/* RETURN: effectiove HTTP status (i.e 500 for SOAP, else status parameter                          */
/*         DONE                                                                                     */
/*--------------------------------------------------------------------------------------------------*/
int renderErrorBody(request_rec *r, char *errMSG, int status){

	int rc = status;
	char *soapHeader = apr_pstrdup(r->pool, apr_table_get(r->headers_in, "SOAPAction"));
	char *acceptHeader  = apr_pstrdup(r->pool, apr_table_get(r->headers_in, "Accept"));


	if (soapHeader){
		rc = 500;
		dumpSOAPFault(r, errMSG);
	}else{
		spliting acceptList;
		split(r, acceptHeader,',', &acceptList);
		int i;
		int jobDone=0;
		for (i=0;i<acceptList.tokensCount && !jobDone;i++){
			if (strstr(acceptList.tokens[i],"html")){
				dumpHTMLError(r, errMSG);
				jobDone=1;
			}else if (strstr(acceptList.tokens[i],"json")){
				dumpJSONError(r, errMSG);
				jobDone=1;
			}else if (strstr(acceptList.tokens[i],"xml")){
				dumpXMLError(r, errMSG);
				jobDone=1;
			}else{
				dumpTextError(r, errMSG);
				jobDone=1;
			}
		}
	}
	return rc;
}





/*--------------------------------------------------------------------------------------------------*/
/*                 int osa_error(request_rec *r, char *errMSG, int status)                      */
/*--------------------------------------------------------------------------------------------------*/
/* Error management for OSA's mediations                                                        */
/* Display error text in main body for request and as a HTTP HEADER                                 */
/*--------------------------------------------------------------------------------------------------*/
/* IN:                                                                                              */
/*        request_rec *r: apache request                                                            */
/*        char *errMSG: message to diplay                                                           */
/*        int status: HTTP status to set for response                                               */
/*--------------------------------------------------------------------------------------------------*/
/* RETURN: apache processing status                                                                 */
/*         DONE                                                                                     */
/*--------------------------------------------------------------------------------------------------*/
int osa_error(request_rec *r, char *errMSG, int status){

	LOG_ERROR_1(APLOG_ERR, 0, r,"%s", errMSG);
	r->status= renderErrorBody(r, errMSG, status);
	apr_table_set(r->err_headers_out, OSA_ERROR_HEADER, errMSG);


	return DONE;
}




/*
 * Convert binary to hex
 */
char * bin2hex (POOL *pool, const char * bin, short len) {
	int i = 0;
	static char hexchars [] = {'0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F'};
	char * buffer = PCALLOC(pool, len * 2 + 1);
	for (i = 0; i < len; i++) {
		buffer[i*2] = hexchars[bin[i] >> 4 & 0x0f];
		buffer[i*2+1] = hexchars[bin[i] & 0x0f];
	}
	buffer[len * 2] = '\0';
	return buffer;
}

/*
 * Convert hexadecimal characters to character
 */

char hex2chr(char * in) {
	static const char * data = "0123456789ABCDEF";
	const char * offset;
	char val = 0;
	int i;

	for (i = 0; i < 2; i++) {
		val <<= 4;
		offset = strchr(data, toupper(in[i]));
		if (offset == NULL)
			return '\0';
		val += offset - data;
	}
	return val;
}

/* checks md5 hashed passwords */
short pw_md5(POOL * pool, const char * real_pw, const char * sent_pw, const char * salt) {
	
	return strcmp(real_pw,ap_md5(pool, (const unsigned char *) sent_pw)) == 0;
}

/* Checks crypted passwords */
short pw_crypted(POOL * pool, const char * real_pw, const char * sent_pw, const char * salt) {
	/* salt will contain either the salt or real_pw */
	return strcmp(real_pw, crypt(sent_pw, salt)) == 0;
}

#if _AES
/* checks aes passwords */
short pw_aes(POOL * pool, const char * real_pw, const char * sent_pw, const char * salt) {
	/* salt will contain the salt value */
	/* Encryption is in 16 byte blocks */
	char * encrypted_sent_pw = PCALLOC(pool, 16 * ((strlen(sent_pw) / 16) + 1));
	short enc_len = my_aes_encrypt(sent_pw, strlen(sent_pw), encrypted_sent_pw, salt, strlen(salt));
	return enc_len > 0 && memcmp(real_pw, encrypted_sent_pw, enc_len) == 0;
}
#endif

/* checks SHA1 passwords */
short pw_sha1(POOL * pool, const char * real_pw, const char * sent_pw, const char * salt) {
	char *scrambled_sent_pw, *buffer=PCALLOC(pool, 128);
	short enc_len = 0;
	apr_sha1_base64(sent_pw, strlen(sent_pw), buffer);
	buffer += 5;   /* go past {SHA1} eyecatcher */
	scrambled_sent_pw = PCALLOC(pool, apr_base64_decode_len(buffer) + 1);
	enc_len = apr_base64_decode(scrambled_sent_pw, buffer);
	scrambled_sent_pw[enc_len] = '\0';
	return  strcasecmp(bin2hex(pool, scrambled_sent_pw, enc_len), real_pw) == 0;
}

/* checks plain text passwords */
short pw_plain(POOL * pool, const char * real_pw, const char * sent_pw, const char * salt) {
	return strcmp(real_pw, sent_pw) == 0;
}


char * format_remote_host(request_rec * r, char ** parm) {
	return  ap_escape_logitem(r->pool, ap_get_remote_host(r->connection, r->per_dir_config, REMOTE_NAME, NULL));
}

char * format_remote_ip(request_rec * r, char ** parm) {
#if (AP_SERVER_MAJORVERSION_NUMBER==2) && (AP_SERVER_MINORVERSION_NUMBER>=4)
		return r->connection->client_ip;
#else
 		return r->connection->remote_ip;	
#endif
}

char * format_filename(request_rec * r, char ** parm) {
	return ap_escape_logitem(r->pool, r->filename);
}

char * format_server_name(request_rec * r, char ** parm) {
	return ap_escape_logitem(r->pool, ap_get_server_name(r));
}

char * format_server_hostname(request_rec * r, char ** parm) {
	return ap_escape_logitem(r->pool, r->server->server_hostname);
}

char * format_protocol(request_rec * r, char ** parm) {
	return ap_escape_logitem(r->pool, r->protocol);
}

char * format_method(request_rec * r, char ** parm) {
	return ap_escape_logitem(r->pool, r->method);
}

char * format_args(request_rec * r, char ** parm) {
	if (r->args)
		return ap_escape_logitem(r->pool, r->args);
	else
		return "";
}

char * format_request(request_rec * r, char ** parm) {
	return ap_escape_logitem(r->pool,
		(r->parsed_uri.password) ? STRCAT(r->pool, r->method, " ",
	apr_uri_unparse(r->pool, &r->parsed_uri, 0),
	r->assbackwards ? NULL : " ", r->protocol, NULL) :
		r->the_request);
}

char * format_uri(request_rec * r, char ** parm) {
	return ap_escape_logitem(r->pool, r->uri);
}

char * format_percent(request_rec * r, char ** parm) {
	return "%";
}

char * format_cookie(request_rec * r, char ** parm) {
	const char * cookies;
	char * start = *parm;
	char delim;
	char * end;
	char * cookiename;
	const char * cookiestart, *cookieend;
	char * cookieval;
	int len;

	delim = *start;
	end = strchr(++start, delim);
	if (end == NULL) {
		return "";
	}
	*parm = end + 1;   /* Point past end of data */
	if ((cookies = TABLE_GET(r->headers_in, "Cookie")) == NULL) {
		return "";
	}
	len = end - start;
	cookiename = PCALLOC(r->pool, len + 2);
	strncpy(cookiename, start, len);
	strcat(cookiename, "=");
	len++;

	cookiestart = cookies;
	while (cookiestart != NULL) {
		while (*cookiestart != '\0' && ISSPACE(*cookiestart))
			cookiestart++;
		if (strncmp(cookiestart, cookiename, len) == 0) {
			cookiestart += len;
			cookieend = strchr(cookiestart, ';');		/* Search for end of cookie data */
			if (cookieend == NULL)			/* NULL means this was the last cookie */
	cookieend = cookiestart + strlen(cookiestart);
			len = cookieend - cookiestart;
			cookieval = PSTRNDUP(r->pool, cookiestart, len);

			start = cookieval;
			while ((start = strchr(start, '%')) != NULL) {  /* Convert any hex data to char */
				 *start = hex2chr(start+1);
	 strcpy (start+1, start+3);
	 start++;
			}

			return cookieval;
		}
		cookiestart = strchr(cookiestart, ';');
		if (cookiestart != NULL)
			cookiestart++;
	}
	return "";
}





char * str_format(request_rec * r, char * input) {
	char * output = input, *pos, *start = input, *data, *temp;
	int i, len, found;

	while ((pos = strchr(start, '%')) != NULL) {
		len = pos - output;			/* Length of string to copy */
		pos++;				/* Point at formatting character */
		found = 0;
		for (i = 0; i < sizeof(formats)/sizeof(formats[0]); i++) {
			if (*pos == formats[i].pattern) {
	pos++;				/* Data following format char */
	data = formats[i].func(r, &pos);
	temp = PCALLOC(r->pool, len + strlen(data) + strlen(pos) + 1);
	if (temp == NULL) {
					LOG_ERROR_1(APLOG_ERR|APLOG_NOERRNO, 0, r, "str_format ERROR: Insufficient storage to expand format %c", *(pos-1));
		return input;
	}
	strncpy(temp, output, len);
	strcat (temp, data);
	start = temp + strlen(temp);
	strcat (temp, pos);
	output = temp;
	found = 1;
	break;
			}
		}
		if (!found) {
			LOG_ERROR_2(APLOG_ERR|APLOG_NOERRNO, 0, r, "str_format ERROR: Invalid formatting character at position %ld: \"%s\"",  (long int)pos-(long int)output, output);
			return input;
		}
	}
	return output;
}

char *trim(char *str){
	char *ibuf = str, *obuf = str;
	int i = 0, cnt = 0;

	/*
	**  Trap NULL
	*/

	if (str){
		/*
		**  Remove leading spaces (from RMLEAD.C)
		*/

		for (ibuf = str; *ibuf && isspace(*ibuf); ++ibuf);
		if (str != ibuf){
			memmove(str, ibuf, ibuf - str);
		}

		/*
		**  Collapse embedded spaces (from LV1WS.C)
		*/

		while (*ibuf){
			if (isspace(*ibuf) && cnt){
				ibuf++;
			}else{
				if (!isspace(*ibuf)){
					cnt = 0;
				}else{
					*ibuf = ' ';
					cnt = 1;
				}
				obuf[i++] = *ibuf++;
			}
		}
		obuf[i] = '\0';

		/*
		**  Remove trailing spaces (from RMTRAIL.C)
		*/

		while (--i >= 0){
			if (!isspace(obuf[i])){
				break;
			}
		}
		obuf[++i] = '\0';
	}
	return str;
}
int get_basic_auth_creds(request_rec *r, char **pwd){
	int rc=1;
	char *authorizationHeader;

	if ((authorizationHeader = (char*)TABLE_GET(r->headers_in, "Authorization")) == NULL) {
		return 0;
	}

	
	spliting authHeaderWords;
	split(r, authorizationHeader, ' ', &authHeaderWords);
	if (authHeaderWords.tokensCount == 2 && strcmp(authHeaderWords.tokens[0],"Basic")==0){
		unsigned char decoded[MAX_STRING_LEN];
		size_t len;
		base64_decode(authHeaderWords.tokens[1],&len, decoded);
		

		int i;
		for (i=0;i<len && decoded[i] != ':';i++);

		if (decoded[i]==':'){
			decoded[i]=0;


			r->user=(char *) PCALLOC(r->pool, i+1);

			strncpy(r->user,decoded, i);
			r->user[i]=0;


			(*pwd)=(char *) PCALLOC(r->pool,strlen(decoded+i+1)+1);
			strcpy(*pwd,decoded+i+1);
			(*pwd)[strlen(decoded+i+1)]=0;

		}else{
			rc=0;
		}
		return rc;
	}else{
		return 0;
	}
}

char *get_requested_server(request_rec *r, char *ret){
	const char *hostHeader = TABLE_GET(r->headers_in, "Host");
	char *hostname;
	if (hostHeader != NULL){
		hostname=PSTRDUP(r->pool, hostHeader);
	}else{
		hostname=r->server->server_hostname;
	}


	sprintf(ret, "%s://%s", ap_http_scheme(r), hostname);
	if ((strcmp(ap_http_scheme(r), "http")==0 && ap_default_port(r) != DEFAULT_HTTP_PORT) ||
	    (strcmp(ap_http_scheme(r), "https")==0 && ap_default_port(r) != DEFAULT_HTTPS_PORT)){

		sprintf(ret, "%s:%d", ret,  ap_default_port(r));
	}
	LOG_ERROR_1(APLOG_DEBUG, 0, r, "requested_server=%s", ret);
	return ret;
}

char *substitueURLParam(request_rec *r, char *str, char *varName, char *value){
		char *rc;
		char *var=strstr(str, varName);
		if (var != NULL){
			char encoded[strlen(value)*3];
			url_encode(value, encoded);

			rc=(char *)PCALLOC(r->pool, strlen(str)+strlen(encoded));

			char initial=varName[0];
			*var=0;


			sprintf(rc, "%s%s%s", str, encoded, var+strlen(varName));
			*var=initial;


		}else{
			rc=str;
		}
		return rc;
}


int redirectToLoginForm(request_rec *r, char *cause){

	osa_config_rec *sec =(osa_config_rec*)ap_get_module_config (r->per_dir_config, &osa_module);
	r->status=303;
	char *curUrl;

	char requestedServer[MAX_STRING_LEN];

	get_requested_server(r, requestedServer);
	// if (strncmp(sec->cookieAuthLoginForm, "http://", 7) && strncmp(sec->cookieAuthLoginForm, "https://", 8)){
	// 	/* If loginForm URL is not starting with "http://" or "https://": is not at absolute URL (i.e. on current server)
	// 	Use a relative URI for the requested URL */
 	// 	requestedServer=(char *) PSTRDUP(r->pool, "");
	// }else{
	// 	/* If loginForm URL is starting with "http://" or "https://": it's an absolute URL (i.e. probably not on current server)
	// 	Use an absolute URI for the requested URL */
 	// 	requestedServer=(char *) PSTRDUP(r->pool, sec->serverName);
	// }

	if (r->args==NULL){
		curUrl=(char *)PCALLOC(r->pool, strlen(r->uri)+1+strlen(requestedServer));
		sprintf(curUrl,"%s%s", requestedServer, r->uri);
	}else{
		curUrl=(char *)PCALLOC(r->pool, strlen(r->uri)+strlen(r->args)+2+strlen(requestedServer));
		sprintf(curUrl,"%s%s?%s", requestedServer, r->uri, r->args);
	}
	
	size_t encodedSize;
	char *b64EncodedCurUrl=base64_encode(r->pool, curUrl, &encodedSize);
	char *location;
	char urlPrm;
	char *redirect_uri;
	if (strstr(sec->cookieAuthLoginForm,"?") != NULL){
		urlPrm='&';
	}else{
		urlPrm='?';
	}

	redirect_uri = sec->cookieAuthLoginForm;
	redirect_uri = substitueURLParam(r, redirect_uri, REQUEST_URL_PARAM, curUrl);
	redirect_uri = substitueURLParam(r, redirect_uri, REQUEST_RESOURCE_PARAM, sec->resourceName);
	/*if (strstr(sec->cookieAuthLoginForm, REQUEST_URL_PARAM)){
		/*char *var=strstr(sec->cookieAuthLoginForm, REQUEST_URL_PARAM);
		char encoded[strlen(curUrl)*3];
		url_encode(curUrl, encoded);

		redirect_uri=(char *)PCALLOC(r->pool, strlen(sec->cookieAuthLoginForm)+strlen(encoded));


		*var=0;


		sprintf(redirect_uri, "%s%s%s", sec->cookieAuthLoginForm, encoded, var+strlen(REQUEST_URL_PARAM));
		*var='%';
		redirect_uri = substitueURLParam(r, sec->cookieAuthLoginForm, REQUEST_URL_PARAM,curUrl);


	}else{
		redirect_uri = sec->cookieAuthLoginForm;
	}*/
	LOG_ERROR_1(APLOG_DEBUG, 0, r, "redirect_uri=%s", redirect_uri);
		
	if (cause==NULL){
	
		location=(char *)PCALLOC(r->pool, encodedSize 
										  + strlen(redirect_uri) + 4 // 4=> "l="
										  + (sec->cookieAuthDomain!=NULL?strlen(sec->cookieAuthDomain)+4:0) // 4 => "d="
										  + strlen(requestedServer)
		);
		sprintf(location,"%s%cl=%s", redirect_uri, urlPrm, b64EncodedCurUrl);
	}else{
		location=(char *)PCALLOC(r->pool, encodedSize
										  +strlen(redirect_uri)+4
										  +strlen(cause)+7 // 7= > "cause="
										  +(sec->cookieAuthDomain!=NULL?strlen(sec->cookieAuthDomain)+4:0) // 4 => "d="
										  +strlen(requestedServer)
		);
		sprintf(location,"%s%cl=%s&cause=%s", redirect_uri, urlPrm,  b64EncodedCurUrl, cause);
	}

	if (sec->cookieAuthDomain != NULL){
		strcat(location, "&d=");
		strcat(location,sec->cookieAuthDomain);
	}

	apr_table_set(r->headers_out, "Location", location);
	apr_table_set(r->headers_out, "Expires", "Sat, 26 Jul 1997 05:00:00 GMT" );
	apr_table_set(r->headers_out, "Last-Modified", "Sat, 26 Jul 1997 05:00:00 GMT GMT" );
	apr_table_set(r->headers_out, "Cache-Control","no-store, no-cache, must-revalidate, max-age=0" );
	apr_table_set(r->headers_out, "Cache-Control", "post-check=0, pre-check=0");
	apr_table_set(r->headers_out, "Pragma", "no-cache" );

	return DONE;
}

int haveOSACookie(request_rec *r){
		const char * cookies;
		spliting cookiesList;
		osa_config_rec *sec =(osa_config_rec *)ap_get_module_config (r->per_dir_config, &osa_module);
		int i;

		if ((cookies = TABLE_GET(r->headers_in, "Cookie")) != NULL) {
			split(r, (char*)cookies,';', &cookiesList);
			for (i=0;i<cookiesList.tokensCount;i++){
				spliting cookie;
				split(r, trim(cookiesList.tokens[i]),'=',&cookie);
				if (strcmp(trim(cookie.tokens[0]), sec->cookieAuthName)==0){
					return 1;
				}
				
			}
		}
		return 0;
	
}

int getTokenFromCookie(request_rec *r, char *token){
		const char * cookies;
		token[0]=0;
		spliting cookiesList;
		int i;
		osa_config_rec *sec =(osa_config_rec *)ap_get_module_config (r->per_dir_config, &osa_module);

		
		
		if ((cookies = TABLE_GET(r->headers_in, "Cookie")) != NULL) {
			split(r, (char*)cookies,';', &cookiesList);
			for (i=0;i<cookiesList.tokensCount;i++){
				spliting cookie;
				split(r, trim(cookiesList.tokens[i]),'=',&cookie);
				if (strcmp(trim(cookie.tokens[0]), sec->cookieAuthName)==0){
		
					strcpy(token, trim(cookie.tokens[1]));
					i=cookiesList.tokensCount;
				}
				
			}
		}
		if (token[0]==0){
			LOG_ERROR_1(APLOG_DEBUG, 0, r, "%s", "No cookie found, let's check if BasicAuth or loginForm are defined");
			if (!sec->basicAuthEnable){
				if(sec->cookieAuthLoginForm==NULL){
					//authCookie is the only authentication mode: no cookie=error
					return osa_error(r,"No authentication cookie found", 401);
				}else{
					return redirectToLoginForm(r, NULL);
				}
			}else{
				if (TABLE_GET(r->headers_in, "Authorization")==NULL && sec->cookieAuthLoginForm!=NULL) {
					return redirectToLoginForm(r,NULL);
				}
				//basicAuth is also available, so let basic auth do the job
				LOG_ERROR_1(APLOG_DEBUG, 0, r, "%s", "No cookie found, let's try Basic Auth");
				return DECLINED;
			}
		}
		
		return OK;
}



void deleteAuthCookie(request_rec *r){
	//Delete cookie on client to try Basic Auth on next shot
	char *buff;
	osa_config_rec *sec =(osa_config_rec *)ap_get_module_config (r->per_dir_config, &osa_module);
	buff=apr_psprintf(r->pool, "%s=deleted; path=/;expires=Thu, 01 Jan 1970 00:00:00 GMT", sec->cookieAuthName);
	if (sec->cookieAuthDomain != NULL){
		char *domain;
		domain=apr_psprintf(r->pool, "; domain=%s",  sec->cookieAuthDomain);
		buff=apr_psprintf(r->pool, "%s%s", buff, domain);
	}
	apr_table_set(r->headers_out, "Set-Cookie", buff);
	apr_table_set(r->err_headers_out, "Set-Cookie", buff);
}


/*--------------------------------------------------------------------------------------------------*/
/* int authenticate_cookie_user(request_rec *r)                                                           */
/*--------------------------------------------------------------------------------------------------*/
/* callback from Apache to check authentication and authorization againt auth cookie                */
/* (depending on configuration entries)                                                             */
/*--------------------------------------------------------------------------------------------------*/
/* IN:                                                                                              */
/*        request_rec *r: apache request                                                            */
/*--------------------------------------------------------------------------------------------------*/
/* RETURN: apache processing status                                                                 */
/*         DONE: if unthorized                                                                      */
/*         OK: else or if no quotas management required                                             */
/*--------------------------------------------------------------------------------------------------*/
int authenticate_cookie_user(request_rec *r){

	osa_config_rec *sec =(osa_config_rec *)ap_get_module_config (r->per_dir_config, &osa_module);
	/* retreive configuration */
	int Rc=OK;
	const char *sent_pw;

	if (sec->cookieAuthEnable){
			char token[MAX_STRING_LEN];			
			char *initialToken;
			
			if (!read_tokens_clean_cache(r->server, r)){
				Rc=cleanGeneratedTokens(r);
				if (Rc != OK){
					return Rc;
				}
				store_tokens_clean(r);
				
			}
			Rc=getTokenFromCookie(r, token);
			if (Rc != OK){
				return Rc;
			}
			int require_new_token;
			P_db(sec, r, token);
			Rc=validateToken(r, token, &initialToken, &require_new_token);
			V_db(sec, r, token);
			if (Rc != OK){
				deleteAuthCookie(r);
				return Rc;
			}

			if (sec->cookieAuthBurn){
				if (require_new_token){
					//That's the first call with this token.
					// Regenerate a new one and "burn" the current one
					Rc=regenerateToken(r, token, initialToken);
					if (Rc != OK){
						deleteAuthCookie(r);
						return Rc;
					}
				}
			}else{
				Rc=extendToken(r, token);
				if (Rc != OK){
					deleteAuthCookie(r);
					return Rc;
				}
			}
			
	}else{
		Rc=DECLINED;
	}
	return Rc;
}

int send_request_basic_auth(request_rec *r){
	char *realm;

	apr_table_set(r->err_headers_out, "Server", "OSA");

	osa_config_rec *sec = (osa_config_rec *)ap_get_module_config(r->per_dir_config, &osa_module);
	
	realm=apr_psprintf(r->pool, "Basic realm=\"%s\"", sec->authName);
	apr_table_set(r->err_headers_out, "WWW-Authenticate", realm);
	return 0;
}


/*
 * check if user is member of at least one of the necessary group(s)
 */
int check_auth(request_rec *r)
{

	osa_config_rec *sec =
		(osa_config_rec *)ap_get_module_config(r->per_dir_config,
							&osa_module);
						
	if (!sec->osaEnable){
		return DECLINED;
	}
	char *user = r->user;
	int method = r->method_number;

	register int x;
	char **groups = NULL;

	if (!sec->osaGroupField) return DECLINED; /* not doing groups here */
	//if (!reqs_arr) return DECLINED; /* no "require" line in access config */

	if (!user || user[0]==0 ) return DECLINED;

	if (strcmp(user, ANONYMOUS_USER_ALLOWED)==0){
		r->user = NULL;
		return DECLINED;
	}
	/* if the group table is not specified, use the same as for password */
	if (!sec->osagrptable) sec->osagrptable = sec->osapwtable;

	 
	const char *requireClause = sec->require;
	const char *t, *want;

	if (!requireClause){
		return DECLINED;
	}

	while (requireClause != NULL && requireClause[0]!=0){

		//if (!(reqs[x].method_mask & (1 << method))) continue;

		//t = reqs[x].requirement;
		want = ap_getword_conf(r->pool, &requireClause);
		
		if (!strcmp(want, "valid-user")) {
			return OK;
		}

		if (!strcmp(want, "user")) {
			while (requireClause != NULL && requireClause[0]!=0) {
				want = ap_getword_conf(r->pool, &requireClause);
				if (strcmp(user, want) == 0) {
					return OK;
				}
			}
		}else if(!strcmp(want,"group")) {
			/* check for list of groups from database only first time thru */
			stringKeyValList groupList;
			groupList.listCount=0;
			if (read_keyval_from_cache(r->server, r, "groups", "", r->user, &groupList)){
				groups=(char **) PCALLOC(r->pool, sizeof(char *) * (groupList.listCount+1));
				groups[groupList.listCount]=0;
				for (int i=0;i<groupList.listCount;i++){
					groups[i]=PSTRDUP(r->pool, groupList.list[i].val);
				}
			}else{
				groups = get_groups(r, user, sec);
				if (groups){
					for (int i=0;groups[i];i++){
						groupList.list[i].key=PSTRDUP(r->pool, "group");
						groupList.list[i].val=PSTRDUP(r->pool, groups[i]);
						groupList.listCount++;
					}
					store_keyval_cache(r, "groups", "", r->user, &groupList, sec->userGroupsCacheTTL);
				}
			}


			if (groups || (groups = get_groups(r, user, sec))) {

				/* loop through list of groups specified in htaccess file */
				while(requireClause != NULL && requireClause[0]!=0) {
					int i = 0;
					want = ap_getword_conf(r->pool, &requireClause);
					/* compare against each group to which this user belongs */
					while(groups[i]) {	/* last element is NULL */

						if(!strcmp(groups[i],want)) {
							return OK;		/* we found the user! */
						}
						++i;
					}
				}

		 	}
		}
	}
	if (sec->osaAuthoritative) {
		char *authorizationError;
		authorizationError=apr_psprintf(r->pool, "User %s is not allowed for group %s", user, want);
		//ap_note_basic_auth_failure(r);
		if (sec->cookieAuthLoginForm != NULL && TABLE_GET(r->headers_in, "Authorization")==NULL ){
			//Authorization fail and we didn't came here by basic auth (Authorization is set by BA);
			return redirectToLoginForm(r,"authorization");
		}else if (sec->basicAuthEnable){
			deleteAuthCookie(r);
			send_request_basic_auth(r);
		}

		return osa_error(r,authorizationError,NOT_AUTHORIZED);
		
	
			//return NOT_AUTHORIZED;
	}
	return DECLINED;
}

authz_status check_auth_base(request_rec *r, const char *require_line, const void *parsed_require_line)
{
	//We don't really check authz at this level because we wan't to return custom payload.
	// So default behaviour is just to trigger authent. if required (return AUTHZ_DENIED_NO_USER)
	// and authorize request at apache AUTHZ step.
	// Real OSA authorization are checked at begining of "fixups" step
	// see  register_hooks
	//

	osa_config_rec *sec =
		(osa_config_rec *)ap_get_module_config(r->per_dir_config,
							&osa_module);


	if ((!r->user || r->user[0]==0) && sec->osaAuthoritative && sec->osaEnable){
		//We don't have yet any user identified
		if (sec->allowAnonymous){
				if (TABLE_GET(r->headers_in, "Authorization")!=NULL	|| haveOSACookie(r)){
				//But anonymous is allowed for OSA and we have some creds in the request
				//So trigger auth
				return AUTHZ_DENIED_NO_USER;
			}

		}else{
			//Anonymous access is not allowed, so trigger auth
			return AUTHZ_DENIED_NO_USER;
		}
	}
	if (r->user && apr_strnatcmp((const char *)r->user, ANONYMOUS_USER_ALLOWED)==0){
		r->user=NULL;
	}
	return AUTHZ_GRANTED;
}
/*--------------------------------------------------------------------------------------------------*/
/* int check_quotas(request_rec *r)                                                                 */
/*--------------------------------------------------------------------------------------------------*/
/* callback from Apache to do the  quotas checking (depending on configuration entries)             */
/*--------------------------------------------------------------------------------------------------*/
/* IN:                                                                                              */
/*        request_rec *r: apache request                                                            */
/*--------------------------------------------------------------------------------------------------*/
/* RETURN: apache processing status                                                                 */
/*         DONE: if error or over quotas                                                            */
/*         OK: else or if no quotas management required                                             */
/*--------------------------------------------------------------------------------------------------*/
int check_quotas(request_rec *r){

	const char *sent_pw;
	int res=0;
	int rc=OK;


	/* retreive configuration */
	osa_config_rec *sec =(osa_config_rec *)ap_get_module_config (r->per_dir_config, &osa_module);


	if (sec->checkUserQuotas || sec->checkGlobalQuotas){


	//	LOG_ERROR_1(APLOG_NOERRNO|APLOG_ERR, 0, r, "User=%s\n", r->user);
	/* BHE BA COOKIE
			if (sec->osaEnable){
			if ((res = ap_get_basic_auth_pw (r, &sent_pw)) != OK)  {
				LOG_ERROR(APLOG_NOERRNO|APLOG_ERR, 0, r, "checkquotas: Authent is required required, but not no cred in request...... nothing to do...");
				//Authent is required, but still not occursed (typically first call from browser without creds)
				//Don't check
				return DECLINED;
			}
		}
	*/
		rc=OK;
		if (sec->checkUserQuotas && sec->osaEnable && r->user != NULL){
			rc=checkUserQuotas(sec,r);
		}

		if (sec->checkGlobalQuotas  && sec->osaEnable && rc==OK){
			rc= checkGlobalQuotas(sec,r);

		}
		

		return rc;
	}else{

		return OK;
	}

}



/*
 * callback from Apache to do the authentication of the user to his
 * password.
 */
int authenticate_basic_user (request_rec *r)
{

	int passwords_match = 0;	/* Assume no match */
	encryption * enc_data = 0;
	int i = 0;

	char *user;
	osa_config_rec *sec =
		(osa_config_rec *)ap_get_module_config (r->per_dir_config,
							 &osa_module);

	const char *sent_pw, *real_pw, *salt = 0, *salt_column = 0;
	int res;

	if (!sec->osaEnable)	/* no mysql authorization */
		return DECLINED;	


	if (r->user != NULL) {

		LOG_ERROR_1(APLOG_DEBUG, 0, r, "Basic Auth: Found user=%s in request (pre-auth)", r->user);
		//User was authenticated by some else (ex. Cookie, other module)
		//Use it/trust it
		return DECLINED;
	}
		


	if (sec->basicAuthEnable && r->user==NULL){
				
		if ((res = get_basic_auth_creds (r, (char**)&sent_pw)) == 0){
			if (sec->allowAnonymous){
				//If this method is tigerred, it's because, authent is required or anonymous access is allowed and we found creds in the request.
				// At this point, creds where not validated, but like anonymous access is allowed, we need to let apache to got futher
				//so:
				//  - set  a fake user because apache expect a user at the end of auth methods
				//  - inform apache that auth is OK	
				//Fake user will be erased in AUTHZ 
				r->user=(char *) PSTRDUP(r->pool, ANONYMOUS_USER_ALLOWED);
				return OK;
			}else{
				send_request_basic_auth(r);
				return NOT_AUTHORIZED;
			}
		}
				
	}
	if (!sec->cookieAuthEnable && !sec->basicAuthEnable){
		return DECLINED;
	}else{
		if (r->user==NULL){
			return osa_error(r,"User not authentifed!", NOT_AUTHORIZED);
		}
		LOG_ERROR_1(APLOG_DEBUG, 0, r, "Basic Auth: Found user=%s in request (pre-auth)", r->user);
	}
			

/* Determine the encryption method */
	if (sec->osaEncryptionField) {
		for (i = 0; i < sizeof(encryptions) / sizeof(encryptions[0]); i++) {
			if (strcasecmp(sec->osaEncryptionField, encryptions[i].string) == 0) {
				enc_data = &(encryptions[i]);
				break;
			}
		}
		if (!enc_data) {  /* Entry was not found in the list */
			char *authenticationError;
			
			authenticationError=apr_psprintf(r->pool, "invalid encryption method %s", sec->osaEncryptionField);
			LOG_ERROR_1(APLOG_NOERRNO|APLOG_ERR, 0, r,"%s",  authenticationError);
			//ap_note_basic_auth_failure(r);
			send_request_basic_auth(r);
			return osa_error(r,authenticationError,NOT_AUTHORIZED);
			//return NOT_AUTHORIZED;

		}
	}
	else
		enc_data = &encryptions[0];

	user = r->user;


	if (enc_data->salt_status == NO_SALT || !sec->osaSaltField){
		salt = salt_column = 0;
	}else { 			/* Parse the osaSaltField */

		short salt_length = strlen(sec->osaSaltField);

		if (strcasecmp(sec->osaSaltField, "<>") == 0) { /* Salt against self */
			salt = salt_column = 0;
		} else if (sec->osaSaltField[0] == '<' && sec->osaSaltField[salt_length-1] == '>') {
			salt =  PSTRNDUP(r->pool, sec->osaSaltField+1, salt_length - 2);
			salt_column = 0;
		} else {
			salt = 0;
			salt_column = sec->osaSaltField;
		}
	}

	if (enc_data->salt_status == SALT_REQUIRED && !salt && !salt_column) {
		LOG_ERROR_1(APLOG_NOERRNO | APLOG_ERR, 0, r, "MySQL Salt field not specified for encryption %s", sec->osaEncryptionField);
		return DECLINED;
	}

	char cached_pw[MAX_STRING_LEN];
	cached_pw[0]=0;
	if (read_pw_from_cache(r->server, r, user, cached_pw)){
		real_pw=PSTRDUP(r->pool, cached_pw);
ap_log_rerror(APLOG_MARK, APLOG_DEBUG, 0, r, "PW FROM CACHE");
	}else{
		real_pw = get_db_pw(r, user, sec, salt_column, &salt ); /* Get a salt if one was specified */
ap_log_rerror(APLOG_MARK, APLOG_DEBUG, 0, r, "PW FROM DB");
		if (real_pw){
			store_pw_cache(r, user, (char *)real_pw);
		}
	}

	if(!real_pw)
	{
		/* user not found in database */

		LOG_ERROR_2(APLOG_NOERRNO|APLOG_ERR, 0, r, "MySQL user %s not found: %s", user, r->uri);
		//ap_note_basic_auth_failure (r);
		send_request_basic_auth(r);

		return osa_error(r,"Wrong username password or account expired", NOT_AUTHORIZED);
		if (!sec->osaAuthoritative)
			return DECLINED;		/* let other schemes find user */
		else{
			return NOT_AUTHORIZED;
		}
	}

	if (!salt)
		salt = real_pw;

	/* if we don't require password, just return ok since they exist */
	if (sec->osaNoPasswd) {
		return OK;
	}

	passwords_match = enc_data->func(r->pool, real_pw, sent_pw, salt);

	if(passwords_match) {
		return OK;
	} else {
		char *authenticationError;
		authenticationError=apr_psprintf(r->pool, "user %s: password mismatch: %s", user, r->uri);
		LOG_ERROR_1(APLOG_NOERRNO|APLOG_ERR, 0, r,"%s", authenticationError);

		//ap_note_basic_auth_failure (r);
		send_request_basic_auth(r);
		return osa_error(r,authenticationError,NOT_AUTHORIZED);
		//return NOT_AUTHORIZED;
	}
}


void *create_osa_dir_config (POOL *p, char *d)
{
	osa_config_rec *m = PCALLOC(p, sizeof(osa_config_rec));
	if (!m) return NULL;		/* failure to get memory is a bad thing */

	m->db_server=get_db_server_config(p, m);

	if (!m->db_server) return NULL;

	m->osapwtable = _PWTABLE;
	m->osagrptable = 0;                             /* user group table */
	m->osaNameField = _NAMEFIELD;		    /* default user name field */
	m->osaPasswordField = _PASSWORDFIELD;	    /* default user password field */
	m->osaGroupUserNameField = _GROUPUSERNAMEFIELD; /* user name field in group table */
	m->osaEncryptionField = _ENCRYPTION;  	    /* default encryption is encrypted */
	m->osaSaltField = _SALTFIELD;	    	    /* default is scramble password against itself */
	m->osaKeepAlive = _KEEPALIVE;         	    /* do not keep persistent connection */
	m->osaAuthoritative = _AUTHORITATIVE; 	    /* we are authoritative source for users */
	m->osaNoPasswd = _NOPASSWORD;         	    /* we require password */
	m->osaEnable = _ENABLE;		    	    /* authorization on by default */
	m->osaUserCondition = 0;             	    /* No condition to add to the user
									 where-clause in select query */
	m->osaGroupCondition = 0;            	    /* No condition to add to the group
									 where-clause in select query */
	m->osaGlobalQuotasCondition = 0;            	    /* No condition to add to the group*/
	m->osaCharacterSet = _CHARACTERSET;		    /* default characterset to use */

	m->indentityHeadersMapping = 0; 			/* default identity forwarding disabled */
	m->logHit=0;								/*default log hit in DB */
	m->cookieAuthEnable=0;								/*default cookie authen */
	m->cookieAuthBurn=1;								/*default cookie authen */
	m->cookieAuthName="OSAAuthToken";
	m->cookieAuthDomain=NULL;
	m->cookieAuthLoginForm=NULL;
	m->cookieAuthTTL=60;


	m->cookieAuthTable="authtoken";
	m->cookieAuthUsernameField="userName";
	m->cookieAuthTokenField="token";
	m->cookieInitialAuthTokenField="initialToken";
	m->cookieAuthValidityField="validUntil";
    m->cookieAuthBurnedField="burned";

	m->basicAuthEnable=0;								/*default cookie authen */
	m->require=NULL;
	m->authName="Open Service Access gateway: please enter your credentials";
	
	m->allowAnonymous=0;
	m->cookieCacheTime=COOKIE_BURN_SURVIVAL_TIME;

	m->indentityHeadersExtendedMapping=NULL;


	m->userGroupsCacheTTL=30;
	m->userAttributesCacheTTL=30;
	m->quotasDefCacheTTL=30;

	return (void *)m;
}



int forward_identity(request_rec *r)
{
	stringKeyValList headersMappingList;

	osa_config_rec *sec =
		(osa_config_rec *)ap_get_module_config(r->per_dir_config,
							&osa_module);
	
	if (sec->indentityHeadersMapping){ //basic attributes
					
	
		spliting coupleList;
		split(r, sec->indentityHeadersMapping,';',&coupleList);
		int i;
		char *fields="";
		headersMappingList.listCount=0;
		
		
		//Explode configuration string in set (header name/field name)
		for (i=0;i<coupleList.tokensCount;i++){
			spliting mapping; 
			split(r, coupleList.tokens[i],',',&mapping);
			headersMappingList.list[i].key=PSTRDUP(r->pool, mapping.tokens[1]);
			
			headersMappingList.listCount++;
			if (i>0){
				fields=STRCAT(r->pool, fields,",", NULL);
			}
			fields=STRCAT(r->pool, fields, mapping.tokens[0], NULL);
		}
		
		if (r->user != NULL){
			int rc;

			if (!read_keyval_from_cache(r->server, r, "basic", sec->resourceName, r->user,  &headersMappingList)){
				if ((rc=get_user_basic_attributes(r, fields, &headersMappingList)) != OK){
					return rc;
				}else{
					store_keyval_cache(r, "basic", sec->resourceName, r->user, &headersMappingList, sec->userAttributesCacheTTL);
				}

			}


			//We found a user in request (i.e successfull authentication ), search the user in DB
			for (i=0;i<headersMappingList.listCount;i++){

				apr_table_setn(r->headers_in, headersMappingList.list[i].key, headersMappingList.list[i].val);
			}
		}else{
			//We didn't found a user in request (i.e unsuccessfull authentication BUT allowAnonymous is set )
			// Forward empty headers
			for (i=0;i<headersMappingList.listCount;i++){
				apr_table_setn(r->headers_in, headersMappingList.list[i].key, "");
			}
		}
	}
	return OK;
}

int forward_extended_identity(request_rec *r){
	stringKeyValList extendedHeadersMappingList;
	osa_config_rec *sec =
			(osa_config_rec *)ap_get_module_config (r->per_dir_config,
								&osa_module);
	if (sec->indentityHeadersExtendedMapping && r->user){
		stringKeyValList userProps;
		stringKeyValList extendedMapping;
		apr_status_t rc=OK;

		userProps.listCount=0;
		if (!read_keyval_from_cache(r->server, r, "extended", sec->resourceName, r->user, &userProps)){
			if ((rc=get_user_extended_attributes(r, &userProps)) != OK){
				return rc;
			}else{
				store_keyval_cache(r, "extended", sec->resourceName, r->user, &userProps, sec->userAttributesCacheTTL);
			}
		}


		spliting coupleList;
		split(r, sec->indentityHeadersExtendedMapping,';',&coupleList);
		int i;
		
		
		//Explode configuration string in set (header name/field name)
		for (i=0;i<coupleList.tokensCount;i++){
			spliting mapping; 
			split(r, coupleList.tokens[i],',',&mapping);

			char *headerVal="";
			for (int j=0;j<userProps.listCount;j++){
				if (strcmp(userProps.list[j].key, mapping.tokens[0]) == 0) {
					headerVal=userProps.list[j].val;
					j=userProps.listCount; //End search loop
				}
			}
			apr_table_setn(r->headers_in,  PSTRDUP(r->pool, mapping.tokens[1]), PSTRDUP(r->pool, headerVal));

		}
		return OK;

	}else{
		return DECLINED;
	}
}


char *getToken(request_rec *r){
	char *token;
	struct timeval tv;
	gettimeofday(&tv,NULL);
	unsigned long time_in_micros = 1000000 * tv.tv_sec + tv.tv_usec;

	token=ap_md5(r->pool,
					apr_psprintf(r->pool,
					"%lu-%010d-%010d-%010d",
					time_in_micros, getpid(),
					(rand()%1000000000)+1, (rand()%1000000000)+1
	));

	return token;
}


// Set "per module" cache file name config
const char *set_cache_filename(cmd_parms *cmd, void *in_struct_ptr, const char *arg)
{
	osa_server_config_rec *conf = ap_get_module_config(cmd->server->module_config,
            &osa_module);

	conf->cache_filename=(char*)arg;
	return NULL;

}

// Create and initialize per module config
void *create_osa_server_config(apr_pool_t *p, server_rec *s){
	osa_server_config_rec *m = apr_pcalloc(p, sizeof(osa_server_config_rec));
	if (!m) return NULL;		/* failure to get memory is a bad thing */

	m->cache_filename = DEFAULT_CACHE_FILENAME;
	
	return (void *)m;
}



void register_hooks(POOL *p)
{
	build_decoding_table();
	url_encoder_rfc_tables_init();
	srand ( time(NULL) );

	// ap_register_auth_provider(p, AUTHZ_PROVIDER_GROUP, "group",
	// 	AUTHZ_PROVIDER_VERSION,
	// 	&authz_osa_provider, AP_AUTH_INTERNAL_PER_URI);
	// ap_register_auth_provider(p, AUTHZ_PROVIDER_GROUP, "valid-user",
	// 	AUTHZ_PROVIDER_VERSION,
	// 	&authz_osa_provider, AP_AUTH_INTERNAL_PER_URI);


	// ap_hook_check_authn(authenticate_cookie_user, NULL, NULL, APR_HOOK_MIDDLE, AP_AUTH_INTERNAL_PER_URI);
	// ap_hook_check_authn(authenticate_basic_user, NULL, NULL, APR_HOOK_MIDDLE, AP_AUTH_INTERNAL_PER_URI);
    ap_hook_pre_config(osa_precfg, NULL, NULL, APR_HOOK_MIDDLE);
    ap_hook_post_config(cache_post_config, NULL, NULL, APR_HOOK_MIDDLE);
	ap_hook_child_init(osa_child_init, NULL, NULL, APR_HOOK_MIDDLE);


	ap_hook_fixups(authenticate_cookie_user, NULL, NULL, APR_HOOK_FIRST);
	ap_hook_fixups(authenticate_basic_user, NULL, NULL, APR_HOOK_FIRST);
	ap_hook_fixups(check_auth, NULL, NULL, APR_HOOK_FIRST);
	ap_hook_fixups(check_quotas, NULL, NULL, APR_HOOK_FIRST);
	ap_hook_fixups(forward_identity, NULL, NULL, APR_HOOK_LAST);
	ap_hook_fixups(forward_extended_identity, NULL, NULL, APR_HOOK_LAST);
	ap_hook_log_transaction( register_hit, NULL, NULL, APR_HOOK_FIRST);
	
	
}



