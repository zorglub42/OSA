#define OSA_DEBUG

/* HTTP HEADER name to set when mediations fails */
#define OSA_ERROR_HEADER "OSA-ERROR"


#define DEAD_LOCK_SLEEP_TIME_MICRO_S 10000
#define DEAD_LOCK_MAX_RETRY 100
#define MAX_SPLITED_TOKENS 20
#define MAX_SPLITED_TOKEN_SIZE 500

#define ANONYMOUS_USER_ALLOWED "*** ANONYOUS USER ***"

#define COOKIE_BURN_SURVIVAL_TIME 10 //allowed surviving time is sec before cookie is burned

#define STRING(x) STR(x)		/* Used to build strings from compile options */
#define STR(x) #x



/* Compile time options for code generation */
#ifdef AES
	#define _AES 1
#else
	#define _AES 0
#endif
/* set any defaults not specified at compile time */
#ifdef HOST				/* Host to use */
	#define _HOST STRING(HOST)
#else
	#define _HOST 0			/* Will default to localhost */
#endif

/* Apache 1.x defines the port as a string, but Apache 2.x uses an integer */
#ifdef PORT				/* The port to use */
	#define _PORT PORT
#else
	#define _PORT MYSQL_PORT		/* Use the one from MySQL */
#endif

#ifdef SOCKET				/* UNIX socket */
	#define _SOCKET STRING(SOCKET)
#else
	#define _SOCKET MYSQL_UNIX_ADDR
#endif

#ifdef USER				/* Authorized user */
	#define _USER STRING(USER)
#else
	#define _USER 0			/* User must be specified in config */
#endif

#ifdef PASSWORD				/* Default password */
	#define _PASSWORD STRING(PASSWORD)
#else
	#define _PASSWORD 0			/* Password must be specified in config */
#endif

#ifdef DB				/* Default database */
	#define _DB STRING(DB)
#else
	#define _DB "test"			/* Test database */
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


stringKeyValList headersMappingList;




// Common functions
void build_decoding_table();
char *base64_encode(POOL *p, const unsigned char *data,
					size_t *output_length);
unsigned char *base64_decode(const char *data,
							 size_t *output_length,
							 unsigned char *decoded_data);

void split(char *str, char delimiter, spliting *s);
char *replace(char *st, char *orig, char *repl);
int renderErrorBody(request_rec *r, char *errMSG, int status);
void dumpSOAPFault(request_rec *r, char *errMSG);
static void dumpXMLError(request_rec *r, char *errMSG);
static void dumpJSONError(request_rec *r, char *errMSG);
static void dumpTextError(request_rec *r, char *errMSG);
static void dumpHTMLError(request_rec *r, char *errMSG);
int osa_error(request_rec *r, char *errMSG, int status);


