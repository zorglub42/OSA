#define OSA_DEBUG

/* HTTP HEADER name to set when mediations fails */
#define OSA_ERROR_HEADER "OSA-ERROR"


#define DEAD_LOCK_SLEEP_TIME_MICRO_S 10000
#define DEAD_LOCK_MAX_RETRY 100
#define MAX_SPLITED_TOKENS 20
#define MAX_SPLITED_TOKEN_SIZE 500

#define ANONYMOUS_USER_ALLOWED "*** ANONYOUS USER ***"

#define COOKIE_BURN_SURVIVAL_TIME 10 //allowed surviving time is sec before cookie is burned

#define REQUEST_URL_PARAM "%requested_uri%" //Pseudo variable name form loginform url

#define STRING(x) STR(x)		/* Used to build strings from compile options */
#define STR(x) #x



/* Compile time options for code generation */
#ifdef AES
	#define _AES 1
#else
	#define _AES 0
#endif


#ifdef PWTABLE				/* Password table */
	#define _PWTABLE STRING(PWTABLE)
#else
	#define _PWTABLE "user_info" 		/* Default is user_info */
#endif

#ifdef NAMEFIELD			/* Name column in password table */
	#define _NAMEFIELD STRING(NAMEFIELD)
#else
	#define _NAMEFIELD "user_name"	/* Default is "user_name" */
#endif

#ifdef PASSWORDFIELD			/* Password column in password table */
	#define _PASSWORDFIELD STRING(PASSWORDFIELD)
#else
	#define _PASSWORDFIELD "user_password" /* Default is user_password */
#endif

#ifdef GROUPUSERNAMEFIELD
	#define _GROUPUSERNAMEFIELD STRING(GROUPUSERNAMEFIELD)
#else
	#define _GROUPUSERNAMEFIELD NULL
#endif

#ifdef ENCRYPTION			/* Encryption type */
	#define _ENCRYPTION STRING(ENCRYPTION)
#else
	#define _ENCRYPTION 0			/* Will default to "crypt" in code */
#endif

#ifdef SALTFIELD			/* If a salt column is not defined */
	#define _SALTFIELD STRING(SALTFIELD)
#else
	#define _SALTFIELD "<>"		/* Default is no salt */
#endif

#ifdef KEEPALIVE			/* Keep the connection alive */
	#define _KEEPALIVE KEEPALIVE
#else
	#define _KEEPALIVE 0			/* Do not keep it alive */
#endif

#ifdef AUTHORITATIVE			/* If we are the last word */
	#define _AUTHORITATIVE AUTHORITATIVE
#else
	#define _AUTHORITATIVE 1 		/* Yes, we are */
#endif

#ifdef NOPASSWORD			/* If password not needed */
	#define _NOPASSWORD NOPASSWORD
#else
	#define _NOPASSWORD 0			/* It is required */
#endif

#ifdef ENABLE				/* If we are to be enabled */
	#define _ENABLE ENABLE
#else
	#define _ENABLE 1			/* Assume we are */
#endif

#ifdef CHARACTERSET
	#define _CHARACTERSET STRING(CHARACTERSET)
#else
	#define _CHARACTERSET NULL		/* Default is no character set */
#endif

#ifndef TRUE
	#define TRUE 1
#endif
#ifndef FALSE
	#define FALSE 0
#endif



#define PCALLOC apr_pcalloc
#define SNPRINTF apr_snprintf
#define PSTRDUP apr_pstrdup
#define PSTRNDUP apr_pstrndup
#define STRCAT apr_pstrcat
#define POOL apr_pool_t

#define ISSPACE apr_isspace
#define LOG_ERROR(lvl, stat, rqst, msg)  \
	ap_log_rerror (APLOG_MARK, lvl, stat, rqst, msg)
#define LOG_ERROR_1(lvl, stat, rqst, msg, parm)  \
	ap_log_rerror (APLOG_MARK, lvl, stat, rqst, msg, parm)
#define LOG_ERROR_2(lvl, stat, rqst, msg, parm1, parm2)  \
	ap_log_rerror (APLOG_MARK, lvl, stat, rqst, msg, parm1, parm2)
#define LOG_ERROR_3(lvl, stat, rqst, msg, parm1, parm2, parm3)  \
	ap_log_rerror (APLOG_MARK, lvl, stat, rqst, msg, parm1, parm2, parm3)
#define APACHE_FUNC static apr_status_t
#define APACHE_FUNC_RETURN(rc) return rc
#define NOT_AUTHORIZED HTTP_UNAUTHORIZED
#define TABLE_GET apr_table_get


/* salt flags */
#define NO_SALT		      0
#define SALT_OPTIONAL	      1
#define SALT_REQUIRED	      2



#ifndef TRUE
#define TRUE 1
#endif
#ifndef FALSE
#define FALSE 0
#endif


#include <time.h>
#include <stdio.h>
#include <string.h>
#include <stdlib.h>


#include <sys/types.h>
#include <sys/ipc.h>
#include <sys/sem.h>


#include <ap_mmn.h>			/* For MODULE_MAGIC_NUMBER */
/* Use the MODULE_MAGIC_NUMBER to check if at least Apache 2.0 */
#if AP_MODULE_MAGIC_AT_LEAST(20010223,0)
	#define APACHE2
#else
	need_at_least_20010223();
#endif


#if _AES  /* Only needed if AES encryption desired */
	#include <my_global.h>
#endif
#include <httpd.h>
#include <http_config.h>
#include <http_core.h>
#include <http_log.h>
#include <http_protocol.h>
#include <mod_auth.h>
#include <http_request.h>   /* for ap_hook_(check_user_id | auth_checker)*/
#include <ap_compat.h>
#include <apr_strings.h>
#include <apr_sha1.h>
#include <apr_base64.h>
#include <apr_lib.h>
#include "util_md5.h"


#ifdef CRYPT
	#include "crypt.h"
#else
	#include "unistd.h"
#endif

#if _AES
	#include <my_aes.h>
#endif


// Prefix for logs
#ifdef APLOG_USE_MODULE
APLOG_USE_MODULE(osa);
#endif



typedef struct {
	char key[MAX_SPLITED_TOKEN_SIZE];
	char val[MAX_SPLITED_TOKEN_SIZE];
}stringKeyVal;

typedef struct{
	stringKeyVal list[MAX_SPLITED_TOKENS];
	int listCount;
}stringKeyValList;

typedef struct {
				char tokens[MAX_SPLITED_TOKENS][MAX_SPLITED_TOKEN_SIZE];
				int tokensCount;
} spliting ;


typedef struct {	      /* Encryption methods */
	char * string; 	      /* Identifing string */
	short salt_status;	      /* If a salt is required, optional or unused */
	short (*func)(POOL * pool, const char * real_pw, const char * sent_pw, const char * salt);
} encryption ;

typedef struct {		/* User formatting patterns */
	char pattern;			/* Pattern to match */
	char * (*func)(request_rec * r, char ** parm);
} format;


typedef struct  {
	void *db_server;		/* host name of db server */
	char *osapwtable;		/* user password table */
	char *osagrptable;		/* user group table */
	char *osaNameField;		/* field in password table with username */
	char *osaPasswordField;	/* field in password table with password */
	char *osaGroupField;	/* field in group table with group name */
	char *osaGroupUserNameField;/* field in group table with username */
	char *osaEncryptionField;   /* encryption type for passwords */
	char *osaSaltField;		/* salt for scrambled password */
	int  osaKeepAlive;		/* keep connection persistent? */
	int  osaAuthoritative;	/* are we authoritative? */
	int  osaNoPasswd;		/* do we ignore password? */
	int  osaEnable;		/* do we bother trying to auth at all? */
	char *osaUserCondition; 	/* Condition to add to the user where-clause in select query */
	char *osaGroupCondition; 	/* Condition to add to the group where-clause in select query */
	char *osaCharacterSet;	/* MySQL character set to use */
	char *reqSecField;		/* "Per second quota" fied name */
	char *reqDayField;		/* "Per day quota" fied name */
	char *reqMonthField;		/* "Per month quota" fied name */

	/* Quotas Management */
	int checkGlobalQuotas;	/* check global quotas for the resource */
	int checkUserQuotas;		/* check per user quotas for the resource */
	char *resourceName;		/* Resource on with quota are managed */
	char *osaResourceNameField;	/* Field in tables containing resource name */
	char *osaPerSecField;	/* Field of "per second" quotas */
	char *osaPerDayField;	/* Field of "per day" quotas */
	char *osaPerMonthField;	/* Field of "per month" quotas */
	/* global quotas */
	char *osaGlobalQuotasTable;	/* Table containing Global quotas definition */
	char *osaGlobalQuotasCondition;	/* Condition to add to the GlobalQuotas where-clause in select query */
	/* per user quotas */
	char *osaUserQuotasTable;	/* Table containing per user quotas definition */
	char *osaUserQuotasCondition;  /* Condition to add to the PerUserQuotas where-clause in select query */
	/* quotas counters */
	char *countersTable;		/* Table containing counters */
	char *counterNameField; 	/* column for counter name */
	char *counterValueField;	/* column for counter value */
	
	
	/* Identity forwarding */
	char *indentityHeadersMapping; /* Forward user identity */
	
	/* Log HIT in DB flag */
	int logHit;

	
	/* Cookie authentcation relatives */
	int cookieAuthEnable;
	int cookieAuthTTL;
	char *cookieAuthName;
	char *cookieAuthLoginForm;
	char *cookieAuthDomain;
	int cookieAuthBurn;
	char *cookieAuthTable;
	char *cookieAuthUsernameField;
	char *cookieAuthTokenField;
	char *cookieAuthValidityField;
	
	
	/* Basic auth relative */
	int basicAuthEnable;
	char *require;
	char *authName;
	/*Allow unauthenticated access even if (Require && (OSABasicAuthEnable||OSACookieAuthEnable)) are set. In such a case, Identity is forwarded*/
	int allowAnonymous;
	
 } osa_config_rec;


stringKeyValList headersMappingList;
module osa_module;




// Common functions
void build_decoding_table();
char *base64_encode(POOL *p, const unsigned char *data,
					size_t *output_length);
unsigned char *base64_decode(const char *data,
							 size_t *output_length,
							 unsigned char *decoded_data);

void url_encoder_rfc_tables_init();
char *url_encode(unsigned char *s, char *enc);

void split(char *str, char delimiter, spliting *s);
char *replace(char *st, char *orig, char *repl);
int renderErrorBody(request_rec *r, char *errMSG, int status);
void dumpSOAPFault(request_rec *r, char *errMSG);
void dumpXMLError(request_rec *r, char *errMSG);
void dumpJSONError(request_rec *r, char *errMSG);
void dumpTextError(request_rec *r, char *errMSG);
void dumpHTMLError(request_rec *r, char *errMSG);
int osa_error(request_rec *r, char *errMSG, int status);


char hex2chr(char * in);
char *bin2hex (POOL *pool, const char * bin, short len);

/* Encryption methods used.  The first entry is the default entry */
short pw_md5(POOL * pool, const char * real_pw, const char * sent_pw, const char * salt);
short pw_crypted(POOL * pool, const char * real_pw, const char * sent_pw, const char * salt);
#if _AES
short pw_aes(POOL * pool, const char * real_pw, const char * sent_pw, const char * salt);
#endif
short pw_sha1(POOL * pool, const char * real_pw, const char * sent_pw, const char * salt);
short pw_plain(POOL * pool, const char * real_pw, const char * sent_pw, const char * salt);

char * format_remote_host(request_rec * r, char ** parm);
char * format_remote_ip(request_rec * r, char ** parm);
char * format_filename(request_rec * r, char ** parm);
char * format_server_name(request_rec * r, char ** parm);
char * format_server_hostname(request_rec * r, char ** parm);
char * format_protocol(request_rec * r, char ** parm);
char * format_method(request_rec * r, char ** parm);
char * format_args(request_rec * r, char ** parm);
char * format_request(request_rec * r, char ** parm);
char * format_uri(request_rec * r, char ** parm);
char * format_percent(request_rec * r, char ** parm);
char * format_cookie(request_rec * r, char ** parm);

char * str_format(request_rec * r, char * input);
char *trim(char *str);
int get_basic_auth_creds(request_rec *r, char **pwd);
int authenticate_basic_user (request_rec *r);
int redirectToLoginForm(request_rec *r, char *cause);
int haveOSACookie(request_rec *r);
int getTokenFromCookie(request_rec *r, char *token);
void deleteAuthCookie(request_rec *r);

int authenticate_cookie_user(request_rec *r);
int send_request_basic_auth(request_rec *r);


int check_quotas(request_rec *r);	
int check_auth(request_rec *r);
authz_status check_auth_base(request_rec *r, const char *require_line, const void *parsed_require_line);


void *create_osa_dir_config (POOL *p, char *d);
void register_hooks(POOL *p);

void P_db(osa_config_rec *sec, request_rec *r, char *sem); //To implement for specific RDMBS
void V_db(osa_config_rec *sec, request_rec *r, char *sem); //To implement for specific RDMBS
void *get_db_server_config (POOL *p, osa_config_rec *m);  //To implement for specific RDMBS
char * get_db_pw(request_rec *r, char *user, osa_config_rec *m, const char *salt_column, const char ** psalt); //To implement for specific RDMBS
int generateToken(request_rec *r, char *receivedToken);//To implement for specific RDMBS
int validateToken(request_rec *r , char *token, int *stillValidFor);//To implement for specific RDMBS
char ** get_groups(request_rec *r, char *user, osa_config_rec *m); //To implement for specific RDMBS
int checkUserQuotas( osa_config_rec *sec, request_rec *r); 		//To implement for specific RDMBS
int checkGlobalQuotas( osa_config_rec *sec, request_rec *r);	//To implement for specific RDMBS
int register_hit(request_rec *r); //To implement for specific RDMBS
int forward_identity(request_rec *r); //To implement for specific RDMBS

static encryption encryptions[] = {{"crypt", SALT_OPTIONAL, pw_crypted},
						 {"none", NO_SALT, pw_plain},
						 {"md5", NO_SALT, pw_md5},
#if _AES
						 {"aes", SALT_REQUIRED, pw_aes},
#endif
						 {"sha1", NO_SALT, pw_sha1}};



static format formats[] = {{'h', format_remote_host},
	            {'a', format_remote_ip},
		    {'f', format_filename},
		    {'V', format_server_name},
		    {'v', format_server_hostname},
		    {'H', format_protocol},
		    {'m', format_method},
		    {'q', format_args},
		    {'r', format_request},
		    {'U', format_uri},
		    {'%', format_percent},
		    {'C', format_cookie}};


static const authz_provider authz_osa_provider =
{
	&check_auth_base,
	NULL,
};

static command_rec osa_cmds[];
