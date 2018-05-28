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


void split(char *str, char delimiter, spliting *s){

	int i=0;
	int wordLen=0;
	char *ptr=str;
	s->tokensCount=0;
	while (str[i]){
		if (str[i]==delimiter){
			//confMapping[i]=0;
			strncpy(s->tokens[s->tokensCount], ptr, wordLen);
			s->tokens[s->tokensCount][wordLen]='\0';
			ptr=str+i;
			ptr++;
			wordLen=0;
			s->tokensCount++;
		}else{
			wordLen++;
		}
		i++;
	}
	strcpy(s->tokens[s->tokensCount], ptr);
	s->tokensCount++;
}

char *replace(char *st, char *orig, char *repl) {
	static char buffer[4096];
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
	char strHttpBody[2000];

	strHttpBody[0]=0;

	strcat(strHttpBody,"<h1>An error has occurred</h1>\n");
	strcat(strHttpBody,"<table>\n");
	strcat(strHttpBody,"	<tr><td>Error code:</td><td>-1</td></tr>\n");
	strcat(strHttpBody,"	<tr><td>Error label:</td><td>");
	strcat(strHttpBody,errMSG);
	strcat(strHttpBody,"</td></tr>\n");
	strcat(strHttpBody,"</table>\n");

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
	char strHttpBody[2000];

	strHttpBody[0]=0;

	strcat(strHttpBody,"Error code: -1\n");
	strcat(strHttpBody,"Error label: ");
	strcat(strHttpBody,errMSG);

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
	char strHttpBody[2000];

	char errorMessage[255];
	strcpy(errorMessage,replace(errMSG,"\n","\\n"));
	strcpy(errorMessage,replace(errorMessage,"\"","\\\""));

	strHttpBody[0]=0;

	strcat(strHttpBody,"{\n");
	strcat(strHttpBody,"    \"code\": \"-1\",\n");
	strcat(strHttpBody,"    \"label\": \"");
	strcat(strHttpBody,errorMessage);
	strcat(strHttpBody,"\"\n");
	strcat(strHttpBody,"}\n");

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
	char strHttpBody[2000];

	strHttpBody[0]=0;
	strcat(strHttpBody,"<?xml version='1.0' encoding='UTF-8'?>\n");
	strcat(strHttpBody,"<osa>\n");
	strcat(strHttpBody,"	<code>-1</code>\n");
	strcat(strHttpBody,"	<label>");
	strcat(strHttpBody,errMSG);
	strcat(strHttpBody,"</osa>\n");

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
	char strHttpBody[2000];

	strHttpBody[0]=0;
	strcat(strHttpBody,"<?xml version='1.0' ?>\n");
	strcat(strHttpBody,"<env:Envelope xmlns:env='http://schemas.xmlsoap.org/soap/envelope/'>\n");
	strcat(strHttpBody,"	<env:Body>\n");
	strcat(strHttpBody,"		<env:Fault>\n");
	strcat(strHttpBody,"			<faultcode>env:Server</faultcode>\n");
	strcat(strHttpBody,"			<faultstring>");
	strcat(strHttpBody,"                    	");
	strcat(strHttpBody, errMSG);
	strcat(strHttpBody,"                    </faultstring>\n");
	strcat(strHttpBody,"		</env:Fault>\n");
	strcat(strHttpBody,"	</env:Body>\n");
	strcat(strHttpBody,"</env:Envelope>\n");

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
		split(acceptHeader,',', &acceptList);
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
	split(authorizationHeader, ' ', &authHeaderWords);
	if (authHeaderWords.tokensCount == 2 && strcmp(authHeaderWords.tokens[0],"Basic")==0){
		unsigned char decoded[255];
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



int redirectToLoginForm(request_rec *r, char *cause){

	osa_config_rec *sec =(osa_config_rec*)ap_get_module_config (r->per_dir_config, &osa_module);
	r->status=303;
	char *curUrl;

	char *requestedServer;
	if (strncmp(sec->cookieAuthLoginForm, "http://", 7) && strncmp(sec->cookieAuthLoginForm, "https://", 8)){
		/* If loginForm URL is not starting with "http://" or "https://": is not at absolute URL (i.e. on current server)
		Use a relative URI for the requested URL */
 		requestedServer=(char *) PSTRDUP(r->pool, "");
	}else{
		/* If loginForm URL is starting with "http://" or "https://": it's an absolute URL (i.e. probably not on current server)
		Use an absolute URI for the requested URL */
 		requestedServer=(char *) PSTRDUP(r->pool, sec->serverName);
	}
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
	if (strstr(sec->cookieAuthLoginForm,"?") != NULL){
		urlPrm='&';
	}else{
		urlPrm='?';
	}

		
	if (cause==NULL){
	
		location=(char *)PCALLOC(r->pool, encodedSize+strlen(sec->cookieAuthLoginForm)+4+(sec->cookieAuthDomain!=NULL?strlen(sec->cookieAuthDomain)+4:0));
		sprintf(location,"%s%cl=%s", sec->cookieAuthLoginForm, urlPrm, b64EncodedCurUrl);
	}else{
		location=(char *)PCALLOC(r->pool, encodedSize+strlen(sec->cookieAuthLoginForm)+4+strlen(cause)+7+(sec->cookieAuthDomain!=NULL?strlen(sec->cookieAuthDomain)+4:0));
		sprintf(location,"%s%cl=%s&cause=%s", sec->cookieAuthLoginForm, urlPrm,  b64EncodedCurUrl, cause);
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
			split((char*)cookies,';', &cookiesList);
			for (i=0;i<cookiesList.tokensCount;i++){
				spliting cookie;
				split(trim(cookiesList.tokens[i]),'=',&cookie);
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
			split((char*)cookies,';', &cookiesList);
			for (i=0;i<cookiesList.tokensCount;i++){
				spliting cookie;
				split(trim(cookiesList.tokens[i]),'=',&cookie);
				if (strcmp(trim(cookie.tokens[0]), sec->cookieAuthName)==0){
		
					strcpy(token, trim(cookie.tokens[1]));
					i=cookiesList.tokensCount;
				}
				
			}
		}
		if (token[0]==0){
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
	char buff[255];
	osa_config_rec *sec =(osa_config_rec *)ap_get_module_config (r->per_dir_config, &osa_module);
	sprintf(buff,"%s=deleted; path=/;expires=Thu, 01 Jan 1970 00:00:00 GMT", sec->cookieAuthName);
	if (sec->cookieAuthDomain != NULL){
		char domain[MAX_STRING_LEN];
		sprintf(domain,"; domain=%s",  sec->cookieAuthDomain);
		strcat(buff, domain);
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

	/* retreive configuration */
	osa_config_rec *sec =(osa_config_rec *)ap_get_module_config (r->per_dir_config, &osa_module);
	int Rc=OK;
	const char *sent_pw;

	if (sec->cookieAuthEnable){
			char token[255];			
			
			Rc=getTokenFromCookie(r, token);
			if (Rc != OK){
				return Rc;
			}
			int stillValidFor;
			Rc=validateToken(r, token, &stillValidFor);
			if (Rc != OK){
				deleteAuthCookie(r);
				return Rc;
			}
			if ( ((sec->cookieAuthTTL*60)-stillValidFor) >COOKIE_BURN_SURVIVAL_TIME){
				//We received a request with a token created for more than COOKIE_BURN_SURVIVAL_TIME secs
				//re-generate a new one and burn the received one 
				Rc=generateToken(r, token);
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
	char realm[255];

	apr_table_set(r->err_headers_out, "Server", "OSA");

	osa_config_rec *sec = (osa_config_rec *)ap_get_module_config(r->per_dir_config, &osa_module);
	
	sprintf(realm,"Basic realm=\"%s\"", sec->authName);
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

	if (!user || user[0]==0) return DECLINED;
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
		char authorizationError[255];
		sprintf(authorizationError, "User %s is not allowed for group %s", user, want);
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
			char authenticationError[255];
			
			sprintf(authenticationError,"invalid encryption method %s", sec->osaEncryptionField);
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


	if (enc_data->salt_status == NO_SALT || !sec->osaSaltField)
		salt = salt_column = 0;
	else { 			/* Parse the osaSaltField */
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

	real_pw = get_db_pw(r, user, sec, salt_column, &salt ); /* Get a salt if one was specified */

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
		char authenticationError[255];
		sprintf(authenticationError, "user %s: password mismatch: %s", user, r->uri);
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

	m->serverName="";
	
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
	m->cookieAuthValidityField="validUntil";


	m->basicAuthEnable=0;								/*default cookie authen */
	m->require=NULL;
	m->authName="Open Service Access gateway: please enter your credentials";
	
	m->allowAnonymous=0;

	return (void *)m;
}

void register_hooks(POOL *p)
{
	build_decoding_table();
	srand ( time(NULL) );

	// ap_register_auth_provider(p, AUTHZ_PROVIDER_GROUP, "group",
	// 	AUTHZ_PROVIDER_VERSION,
	// 	&authz_osa_provider, AP_AUTH_INTERNAL_PER_URI);
	// ap_register_auth_provider(p, AUTHZ_PROVIDER_GROUP, "valid-user",
	// 	AUTHZ_PROVIDER_VERSION,
	// 	&authz_osa_provider, AP_AUTH_INTERNAL_PER_URI);


	// ap_hook_check_authn(authenticate_cookie_user, NULL, NULL, APR_HOOK_MIDDLE, AP_AUTH_INTERNAL_PER_URI);
	// ap_hook_check_authn(authenticate_basic_user, NULL, NULL, APR_HOOK_MIDDLE, AP_AUTH_INTERNAL_PER_URI);


	ap_hook_fixups(authenticate_cookie_user, NULL, NULL, APR_HOOK_FIRST);
	ap_hook_fixups(authenticate_basic_user, NULL, NULL, APR_HOOK_FIRST);
	ap_hook_fixups(check_auth, NULL, NULL, APR_HOOK_FIRST);
	ap_hook_fixups(check_quotas, NULL, NULL, APR_HOOK_FIRST);
	ap_hook_fixups(forward_identity, NULL, NULL, APR_HOOK_LAST);
	ap_hook_log_transaction( register_hit, NULL, NULL, APR_HOOK_FIRST);
	
	
}


