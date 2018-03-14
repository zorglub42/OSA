/* Copyright (c) 1995 The Apache Group.  All rights reserved.ook
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 * 1. Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 *
 * 2. Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in
 *    the documentation and/or other materials provided with the
 *    distribution.
 *
 * 3. All advertising materials mentioning features or use of this
 *    software must display the following acknowledgment:
 *    "This product includes software developed by the Apache Group
 *    for use in the Apache HTTP server project (http://www.apache.org/)."
 *
 * 4. The names "Apache Server" and "Apache Group" must not be used to
 *    endorse or promote products derived from this software without
 *    prior written permission.
 *
 * 5. Redistributions of any form whatsoever must retain the following
 *    acknowledgment:
 *    "This product includes software developed by the Apache Group
 *    for use in the Apache HTTP server project (http://www.apache.org/)."
 *
 * THIS SOFTWARE IS PROVIDED BY THE APACHE GROUP ``AS IS'' AND ANY
 * EXPRESSED OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR
 * PURPOSE ARE DISCLAIMED.  IN NO EVENT SHALL THE APACHE GROUP OR
 * IT'S CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT
 * NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)
 * HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT,
 * STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED
 * OF THE POSSIBILITY OF SUCH DAMAGE.
 * ====================================================================
 *
 * This software consists of voluntary contributions made by many
 * individuals on behalf of the Apache Group and was originally based
 * on public domain software written at the National Center for
 * Supercomputing Applications, University of Illinois, Urbana-Champaign.
 * For more information on the Apache Group and the Apache HTTP server
 * project, please see <http://www.apache.org/>.
 *
 */


/*--------------------------------------------------------
 * Module Name : RunTimeAppliance
 * Version : 1.0.0
 *
 * Software Name : OpenSourceAppliance
 * Version : 1.0
 *
 * Copyright (c) 2011 – 2018 Orange
 * This software is distributed under the Apache 2 license
 * <http://www.apache.org/licenses/LICENSE-2.0.html>
 *
 *--------------------------------------------------------
 * File Name   : ApplianceManager/RunTimeAppliance/apache/module/sqlite/mod_osa_sqlite3.c
 *
 * Created     : 2018-03
 * Authors     : Benoit HERARD <benoit.herard(at)orange.com>
 *
 * Description :
 * 	This module, has been created on mod_auth_mysql 3.0.0 (http://modauthsqlite3.sourceforge.net/) to create a web service publishing appliance module
 *  called mod_osa (using mysql)
 *	In addition to authentication and authorization provided by mod_auth_sqlite3 3.0.0 this module also support supports
 *		- quotas controls (per "alias" and per "user and alias")
 *		- full identity forwarding as HTTP headers (not only username)
 *		- content negociation for error results (json, XML, SOAP, text, html)
 *		- compliant with load balancing (with many http servers 1 DB)
 * This module provide same module name (mod_osa) and same functionalities but usi ng sqlite3
 *--------------------------------------------------------
 * History     :
 * mod_auth_mysql 3.0.0 - 2005-6-22 : Last release of mod_auth_myqsl
 * 1.0.0 - 2012-10-01 : 1st Release of mod_osa using mysql
 * 1.0.0 - 2018-03-02: 1st release of mod_osa using sqlite3
*/

/*
 * Module definition information - the part between the -START and -END
 * lines below is used by Configure. This could be stored in a separate
 * instead.
 *
 * MODULE-DEFINITION-START
 * Name: osa_module
 * ConfigStart
     SQLITE3_LIB="-lsqlite3 -lm -lz"
     if [ "X$SQLITE3_LIB" != "X" ]; then
         LIBS="$LIBS $SQLITE3_LIB"
         echo " + using $SQLITE3_LIB for SQLITE3 support"
     fi
 * ConfigEnd
 * MODULE-DEFINITION-END
 */

#define OSA_DEBUG

/* HTTP HEADER name to set when mediations fails */
#define OSA_ERROR_HEADER "OSA-ERROR"


#define DEAD_LOCK_SLEEP_TIME_MICRO_S 10000
#define DEAD_LOCK_MAX_RETRY 100
#define MAX_SPLITED_TOKENS 20
#define MAX_SPLITED_TOKEN_SIZE 500



#define COOKIE_BURN_SURVIVAL_TIME 10 //allowed surviving time is sec before cookie is burned

#define SQLITE3_BUSY_TIMEOUT 5000 //ms


#define STRING(x) STR(x)		/* Used to build strings from compile options */
#define STR(x) #x
#include <time.h>
#include <stdio.h>
#include <string.h>
#include <stdlib.h>


#include <sys/types.h>
#include <sys/ipc.h>
#include <sys/sem.h>

#include "ap_mmn.h"			/* For MODULE_MAGIC_NUMBER */
/* Use the MODULE_MAGIC_NUMBER to check if at least Apache 2.0 */
#if AP_MODULE_MAGIC_AT_LEAST(20010223,0)
  #define APACHE2
#endif

/* Compile time options for code generation */
#ifdef AES
  #define _AES 1
#else
  #define _AES 0
#endif
/* set any defaults not specified at compile time */
#ifdef DB_FILE				/* Host to use */
  #define _DB_FILE STRING(DB_FILE)
#else
  #define _DB_FILE "osa.db"			/* Will default to localhost */
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

#include "httpd.h"
#include "http_config.h"
#include "http_core.h"
#include "http_log.h"
#include "http_protocol.h"



#ifdef APACHE2
  #define PCALLOC apr_pcalloc
  #define SNPRINTF apr_snprintf
  #define PSTRDUP apr_pstrdup
  #define PSTRNDUP apr_pstrndup
  #define STRCAT apr_pstrcat
  #define POOL apr_pool_t
  #include "http_request.h"   /* for ap_hook_(check_user_id | auth_checker)*/
  #include "ap_compat.h"
  #include "apr_strings.h"
  #include "apr_sha1.h"
  #include "apr_base64.h"
  #include "apr_lib.h"
  #define ISSPACE apr_isspace
  #ifdef CRYPT
    #include "crypt.h"
  #else
    #include "unistd.h"
  #endif
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
#else
  #define PCALLOC ap_pcalloc
  #define SNPRINTF ap_snprintf
  #define PSTRDUP ap_pstrdup
  #define PSTRNDUP ap_pstrndup
  #define STRCAT apr_pstrcat
  #define POOL pool
  #include <stdlib.h>
  #include "ap_sha1.h"
  #include "ap_ctype.h"
  #define LOG_ERROR(lvl, stat, rqst, msg) \
	  ap_log_error(APLOG_MARK, lvl, rqst->server, msg)
  #define LOG_ERROR_1(lvl, stat, rqst, msg, parm) \
	  ap_log_error(APLOG_MARK, lvl, rqst->server, msg, parm)
  #define LOG_ERROR_2(lvl, stat, rqst, msg, parm1, parm2) \
	  ap_log_error(APLOG_MARK, lvl, rqst->server, msg, parm1, parm2)
  #define LOG_ERROR_3(lvl, stat, rqst, msg, parm1, parm2, parm3) \
	  ap_log_error(APLOG_MARK, lvl, rqst->server, msg, parm1, parm2, parm3)
  #define APACHE_FUNC static void
  #define APACHE_FUNC_RETURN(rc) return
  #define NOT_AUTHORIZED AUTH_REQUIRED
  #define TABLE_GET ap_table_get
  #define ISSPACE ap_isspace
#endif

#include "util_md5.h"
#ifndef APACHE2
/* Both Apache 1's ap_config.h and my_global.h define closesocket (to the same value) */
/* This gets rid of a warning message.  It's OK because we don't use it anyway */
  #undef closesocket
#endif
#if _AES  /* Only needed if AES encryption desired */
  #include <my_global.h>
#endif
#include <sqlite3.h>
#if _AES
  #include <my_aes.h>
#endif

/* salt flags */
#define NO_SALT		      0
#define SALT_OPTIONAL	      1
#define SALT_REQUIRED	      2


typedef struct {
	char key[MAX_SPLITED_TOKEN_SIZE];
	char val[MAX_SPLITED_TOKEN_SIZE];
} stringKeyVal;

typedef struct{
	stringKeyVal list[MAX_SPLITED_TOKENS];
	int listCount;
} stringKeyValList;

typedef struct {
        char tokens[MAX_SPLITED_TOKENS][MAX_SPLITED_TOKEN_SIZE];
        int tokensCount;
} spliting ;

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

//void base64_cleanup() {
//    free(decoding_table);
//}
void build_decoding_table() {

    //decoding_table = malloc(256);

	int i;
    for ( i = 0; i < 64; i++)
        decoding_table[(unsigned char) encoding_table[i]] = i;
}

char *base64_encode(const unsigned char *data,
                    size_t *output_length) {

     size_t input_length=strlen(data);

    *output_length = 4 * ((input_length + 2) / 3);

    char *encoded_data = malloc(*output_length+1);
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


unsigned char *base64_decode(const char *data,
                             size_t *output_length,
                             unsigned char *decoded_data) {

	size_t input_length=strlen(data);

    if (input_length % 4 != 0) return NULL;

    *output_length = input_length / 4 * 3;
    if (data[input_length - 1] == '=') (*output_length)--;
    if (data[input_length - 2] == '=') (*output_length)--;

    //unsigned char *decoded_data = malloc(*output_length);
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





stringKeyValList headersMappingList;


/* forward function declarations */
static short pw_md5(POOL * pool, const char * real_pw, const char * sent_pw, const char * salt);
static short pw_crypted(POOL * pool, const char * real_pw, const char * sent_pw, const char * salt);
#if _AES
static short pw_aes(POOL * pool, const char * real_pw, const char * sent_pw, const char * salt);
#endif
static short pw_sha1(POOL * pool, const char * real_pw, const char * sent_pw, const char * salt);
static short pw_plain(POOL * pool, const char * real_pw, const char * sent_pw, const char * salt);

static char * format_remote_host(request_rec * r, char ** parm);
static char * format_remote_ip(request_rec * r, char ** parm);
static char * format_filename(request_rec * r, char ** parm);
static char * format_server_name(request_rec * r, char ** parm);
static char * format_server_hostname(request_rec * r, char ** parm);
static char * format_protocol(request_rec * r, char ** parm);
static char * format_method(request_rec * r, char ** parm);
static char * format_args(request_rec * r, char ** parm);
static char * format_request(request_rec * r, char ** parm);
static char * format_uri(request_rec * r, char ** parm);
static char * format_percent(request_rec * r, char ** parm);
static char * format_cookie(request_rec * r, char ** parm);


typedef struct {	      /* Encryption methods */
  char * string; 	      /* Identifing string */
  short salt_status;	      /* If a salt is required, optional or unused */
  short (*func)(POOL * pool, const char * real_pw, const char * sent_pw, const char * salt);
} encryption ;

/* Encryption methods used.  The first entry is the default entry */
static encryption encryptions[] = {{"crypt", SALT_OPTIONAL, pw_crypted},
					   {"none", NO_SALT, pw_plain},
					   {"md5", NO_SALT, pw_md5},
#if _AES
					   {"aes", SALT_REQUIRED, pw_aes},
#endif
					   {"sha1", NO_SALT, pw_sha1}};
typedef struct {		/* User formatting patterns */
  char pattern;			/* Pattern to match */
  char * (*func)(request_rec * r, char ** parm);
} format;

format formats[] = {{'h', format_remote_host},
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
/*
 * structure to hold the configuration details for the request
 */
typedef struct  {
  char *sqlite_db_filename;		/* host name of db server */
  char *sqlite3pwtable;		/* user password table */
  char *sqlite3grptable;		/* user group table */
  char *sqlite3NameField;		/* field in password table with username */
  char *sqlite3PasswordField;	/* field in password table with password */
  char *sqlite3GroupField;	/* field in group table with group name */
  char *sqlite3GroupUserNameField;/* field in group table with username */
  char *sqlite3EncryptionField;   /* encryption type for passwords */
  char *sqlite3SaltField;		/* salt for scrambled password */
  int  sqlite3KeepAlive;		/* keep connection persistent? */
  int  sqlite3Authoritative;	/* are we authoritative? */
  int  sqlite3NoPasswd;		/* do we ignore password? */
  int  sqlite3Enable;		/* do we bother trying to auth at all? */
  char *sqlite3UserCondition; 	/* Condition to add to the user where-clause in select query */
  char *sqlite3GroupCondition; 	/* Condition to add to the group where-clause in select query */
  char *sqlite3CharacterSet;	/* SQLite character set to use */
  char *reqSecField;		/* "Per second quota" fied name */
  char *reqDayField;		/* "Per day quota" fied name */
  char *reqMonthField;		/* "Per month quota" fied name */

  /* Quotas Management */
  int checkGlobalQuotas;	/* check global quotas for the resource */
  int checkUserQuotas;		/* check per user quotas for the resource */
  char *resourceName;		/* Resource on with quota are managed */
  char *sqlite3ResourceNameField;	/* Field in tables containing resource name */
  char *sqlite3PerSecField;	/* Field of "per second" quotas */
  char *sqlite3PerDayField;	/* Field of "per day" quotas */
  char *sqlite3PerMonthField;	/* Field of "per month" quotas */
  /* global quotas */
  char *sqlite3GlobalQuotasTable;	/* Table containing Global quotas definition */
  char *sqlite3GlobalQuotasCondition;	/* Condition to add to the GlobalQuotas where-clause in select query */
  /* per user quotas */
  char *sqlite3UserQuotasTable;	/* Table containing per user quotas definition */
  char *sqlite3UserQuotasCondition;  /* Condition to add to the PerUserQuotas where-clause in select query */
  /* quotas counters */
  char *countersTable;		/* Table containing counters */
  char *counterNameField; 	/* column for counter name */
  char *counterValueField;	/* column for counter value */
  
  
  /* Identity forwarding */
  char *indentityHeadersMapping; /* Forward user identity */
  
  /* Log HIT in DB flag */
  int logHit;

  char *serverName;

  
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
  /*Allow unauthenticated access even if (OSARequire && (OSABasicAuthEnable||OSACookieAuthEnable)) are set. In such a case, Identity is forwarded*/
  int allowAnonymous;
  
 } osa_config_rec;

/*
 * Global information for the database connection.  Contains
 * the db file name,If handle is not null, assume it is
 * still valid.
 */
typedef struct {
  sqlite3 * handle;
  char sqlite_db_filename[255];
  time_t last_used;
} sqlite_connection;

//static sqlite_connection connection = {NULL, "", 0};
sqlite_connection connection = {NULL, "", 0};

int sqlite3_stmt_data_count(sqlite3_stmt *stmt){
    int rc = 0;
    while (sqlite3_step(stmt) != SQLITE_DONE){
        rc++;
    }
    sqlite3_reset(stmt);
    return rc;
}

int sqlite3_query_execute(sqlite3 *db, char *query){

    sqlite3_stmt *res;
    int rc = sqlite3_prepare_v2(db, query, -1, &res, 0);    
    
    if (rc != SQLITE_OK) {
        
        fprintf(stderr, "Failed to prepare statement: %s\n", sqlite3_errmsg(db));
        
        return rc;
    }    
    
    rc = sqlite3_step(res);
    
    if (rc == SQLITE_ROW || rc == SQLITE_DONE) {
        rc =0;
    }else{
        sqlite3_finalize(res);
    }
    return rc;
}


char *replace(char *st, char *orig, char *repl) {
  static char buffer[4096];
  char *ch;
  if (!(ch = strstr(st, orig)))
   return st;
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
static void dumpHTMLError(request_rec *r, char *errMSG){
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
static void dumpTextError(request_rec *r, char *errMSG){
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
static void dumpJSONError(request_rec *r, char *errMSG){
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
static void dumpXMLError(request_rec *r, char *errMSG){
char strHttpBody[2000];



strHttpBody[0]=0;
strcat(strHttpBody,"<?xml version='1.0' encoding='UTF-8'?>\n");
strcat(strHttpBody,"<appliance:Error xmlns:appliance='http://nursery.orange.com/appliance/V2'  xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance' >\n");
strcat(strHttpBody,"	<appliance:Code>-1</appliance:Code>\n");
strcat(strHttpBody,"	<appliance:Label>");
strcat(strHttpBody,errMSG);
strcat(strHttpBody,"</appliance:Label>\n");
strcat(strHttpBody,"</appliance:Error>\n");


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
/* Error management for Nursery's mediations                                                        */
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


void P_db(osa_config_rec *sec, request_rec *r, char *sem){
  char query [255];


  sprintf(query,"BEGIN TRANSACTION");
  if (sqlite3_query_execute(connection.handle, query) != 0) {
    LOG_ERROR_2(APLOG_ERR, 0, r, "P_db (%s): %s: ", query, sqlite3_errmsg(connection.handle));
    osa_error(r,"DB Error", 500);
  }



  sprintf(query,"INSERT INTO %s (counterName,value) VALUES ('SEM_%s__',0)",sec->countersTable, sem);
  int tryNumber=0;
  int getLock=0;
  while (!getLock && tryNumber <DEAD_LOCK_MAX_RETRY){
    if (sqlite3_query_execute(connection.handle, query)!=0){
      char sqlError[255];
      strcpy(sqlError, (char*)sqlite3_errmsg(connection.handle));
      if (strstr(sqlError, "database is locked")){
        tryNumber++;
        
        usleep(DEAD_LOCK_SLEEP_TIME_MICRO_S);
      }else{
            LOG_ERROR_1(APLOG_ERR, 0, r, "P_db SQLite ERROR: %s: ", sqlite3_errmsg(connection.handle));
            sprintf(query,"rollback");
            sqlite3_query_execute(connection.handle, query) ; 
            osa_error(r,"DB query error",500);
      }
    }else{
      getLock=1;
    }
  }
  if (tryNumber >=DEAD_LOCK_MAX_RETRY) {
    LOG_ERROR_1(APLOG_ERR, 0, r, "Max retry of %d on deadlock reached", DEAD_LOCK_MAX_RETRY);
    sprintf(query,"rollback");
    sqlite3_query_execute(connection.handle, query) ;
    osa_error(r,"Can't lock counter.......",500);
  }
}



void V_db(osa_config_rec *sec, request_rec *r, char *sem){
  char query [255];
  sprintf(query,"DELETE FROM %s WHERE counterName='SEM_%s__'",sec->countersTable, sem);
  if (sqlite3_query_execute(connection.handle, query) != 0) {
          LOG_ERROR_1(APLOG_ERR, 0, r, "V_db.delete %s: ", sqlite3_errmsg(connection.handle));
          osa_error(r,"DB Error", 500);
  }
  sprintf(query,"commit");
  if (sqlite3_query_execute(connection.handle, query) != 0) {
          LOG_ERROR_1(APLOG_ERR, 0, r, "V_db.commit %s: ", sqlite3_errmsg(connection.handle));
          osa_error(r,"DB Error", 500);
  }
}



static void close_connection() {
  if (connection.handle)
    sqlite3_close(connection.handle);
  connection.handle = NULL;		/* make sure we don't try to use it later */
  return;
}

/*
 * Callback to close sqlite3 handle when necessary.  Also called when a
 * child httpd process is terminated.
 */
APACHE_FUNC
mod_osa_cleanup (void *notused)
{
  close_connection();
  APACHE_FUNC_RETURN(0);
}

/*
 * empty function necessary because register_cleanup requires it as one
 * of its parameters
 */
APACHE_FUNC
mod_osa_cleanup_child (void *data)
{
  /* nothing */
  APACHE_FUNC_RETURN(0);
}


#ifndef APACHE2
/*
 * handler to do cleanup on child exit
 */
static void
child_exit(server_rec *s, pool *p)
{
  mod_osa_cleanup(NULL);
}
#endif



#ifndef TRUE
#define TRUE 1
#endif
#ifndef FALSE
#define FALSE 0
#endif

/*
 * open connection to DB server if necessary.  Return TRUE if connection
 * is good, FALSE if not able to connect.  If false returned, reason
 * for failure has been logged to error_log file already.
 */
static int open_db_handle(request_rec *r, osa_config_rec *m)
{
  char query[MAX_STRING_LEN];
  short file_match = FALSE;
  int rc;
  
  if (connection.handle) {
      if (m->sqlite_db_filename && strcmp(m->sqlite_db_filename, connection.sqlite_db_filename) == 0){
      	rc = TRUE; /* already open */
          /* Otherwise we need to reselect the database */
      }else {
        close_connection();
        strcpy(connection.sqlite_db_filename, m->sqlite_db_filename);
        rc = sqlite3_open(connection.sqlite_db_filename, &connection.handle) == SQLITE_OK;
        sqlite3_busy_timeout(connection.handle, SQLITE3_BUSY_TIMEOUT);
        //sqlite3_query_execute(connection.handle, "PRAGMA read_uncommitted = True");
      }
  }else{
        strcpy(connection.sqlite_db_filename, m->sqlite_db_filename);
        rc =  sqlite3_open (connection.sqlite_db_filename, &connection.handle) == SQLITE_OK;
        sqlite3_busy_timeout(connection.handle, SQLITE3_BUSY_TIMEOUT);

        //sqlite3_query_execute(connection.handle, "PRAGMA read_uncommitted = True");
  }
  return rc;
}

static void * create_osa_dir_config (POOL *p, char *d)
{
  osa_config_rec *m = PCALLOC(p, sizeof(osa_config_rec));
  if (!m) return NULL;		/* failure to get memory is a bad thing */

  /* default values */
  m->sqlite_db_filename = _DB_FILE;
  m->sqlite3pwtable = _PWTABLE;
  m->sqlite3grptable = 0;                             /* user group table */
  m->sqlite3NameField = _NAMEFIELD;		    /* default user name field */
  m->sqlite3PasswordField = _PASSWORDFIELD;	    /* default user password field */
  m->sqlite3GroupUserNameField = _GROUPUSERNAMEFIELD; /* user name field in group table */
  m->sqlite3EncryptionField = _ENCRYPTION;  	    /* default encryption is encrypted */
  m->sqlite3SaltField = _SALTFIELD;	    	    /* default is scramble password against itself */
  m->sqlite3KeepAlive = _KEEPALIVE;         	    /* do not keep persistent connection */
  m->sqlite3Authoritative = _AUTHORITATIVE; 	    /* we are authoritative source for users */
  m->sqlite3NoPasswd = _NOPASSWORD;         	    /* we require password */
  m->sqlite3Enable = _ENABLE;		    	    /* authorization on by default */
  m->sqlite3UserCondition = 0;             	    /* No condition to add to the user
						       where-clause in select query */
  m->sqlite3GroupCondition = 0;            	    /* No condition to add to the group
						       where-clause in select query */
  m->sqlite3GlobalQuotasCondition = 0;            	    /* No condition to add to the group*/
  m->sqlite3CharacterSet = _CHARACTERSET;		    /* default characterset to use */

  m->serverName=NULL;
  
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

#ifdef APACHE2
static
command_rec osa_cmds[] = {

	AP_INIT_RAW_ARGS("OSAAuthName", ap_set_string_slot,
	(void *) APR_OFFSETOF(osa_config_rec, authName),
	OR_AUTHCFG | RSRC_CONF, "Realm value for basic auth"),

	AP_INIT_TAKE1("OSAReqMonthField", ap_set_string_slot,
	(void *) APR_OFFSETOF(osa_config_rec, reqMonthField),
	OR_AUTHCFG | RSRC_CONF, "max number of request per month field"),

	AP_INIT_TAKE1("OSAReqDayField", ap_set_string_slot,
	(void *) APR_OFFSETOF(osa_config_rec, reqDayField),
	OR_AUTHCFG | RSRC_CONF, "max number of request per day field"),

	AP_INIT_TAKE1("OSAReqSecField", ap_set_string_slot,
	(void *) APR_OFFSETOF(osa_config_rec, reqSecField),
	OR_AUTHCFG | RSRC_CONF, "max number of request per second field"),

	AP_INIT_TAKE1("OSASqliteFilename", ap_set_string_slot,
	(void *) APR_OFFSETOF(osa_config_rec, sqlite_db_filename),
	OR_AUTHCFG | RSRC_CONF, "sqlite3 server host name"),

	AP_INIT_TAKE1("OSAUserTable", ap_set_string_slot,
	(void *) APR_OFFSETOF(osa_config_rec, sqlite3pwtable),
	OR_AUTHCFG | RSRC_CONF, "sqlite3 user table name"),

	AP_INIT_TAKE1("OSAGroupTable", ap_set_string_slot,
	(void *) APR_OFFSETOF(osa_config_rec, sqlite3grptable),
	OR_AUTHCFG | RSRC_CONF, "sqlite3 group table name"),

	AP_INIT_TAKE1("OSANameField", ap_set_string_slot,
	(void *) APR_OFFSETOF(osa_config_rec, sqlite3NameField),
	OR_AUTHCFG | RSRC_CONF, "sqlite3 User ID field name within User table"),

	AP_INIT_TAKE1("OSAGroupField", ap_set_string_slot,
	(void *) APR_OFFSETOF(osa_config_rec, sqlite3GroupField),
	OR_AUTHCFG | RSRC_CONF, "sqlite3 Group field name within table"),

	AP_INIT_TAKE1("OSAGroupUserNameField", ap_set_string_slot,
	(void *) APR_OFFSETOF(osa_config_rec, sqlite3GroupUserNameField),
	OR_AUTHCFG | RSRC_CONF, "sqlite3 User ID field name within Group table"),

	AP_INIT_TAKE1("OSAPasswordField", ap_set_string_slot,
	(void *) APR_OFFSETOF(osa_config_rec, sqlite3PasswordField),
	OR_AUTHCFG | RSRC_CONF, "sqlite3 Password field name within table"),

	AP_INIT_TAKE1("OSAPwEncryption", ap_set_string_slot,
	(void *) APR_OFFSETOF(osa_config_rec, sqlite3EncryptionField),
	OR_AUTHCFG | RSRC_CONF, "sqlite3 password encryption method"),

	AP_INIT_TAKE1("OSASaltField", ap_set_string_slot,
	(void*) APR_OFFSETOF(osa_config_rec, sqlite3SaltField),
	OR_AUTHCFG | RSRC_CONF, "sqlite3 salfe field name within table"),

/*	AP_INIT_FLAG("OSAKeepAlive", ap_set_flag_slot,
	(void *) APR_OFFSETOF(osa_config_rec, sqlite3KeepAlive),
	OR_AUTHCFG | RSRC_CONF, "sqlite3 connection kept open across requests if On"),
*/
	AP_INIT_FLAG("OSAAuthoritative", ap_set_flag_slot,
	(void *) APR_OFFSETOF(osa_config_rec, sqlite3Authoritative),
	OR_AUTHCFG | RSRC_CONF, "sqlite3 lookup is authoritative if On"),

	AP_INIT_FLAG("OSANoPasswd", ap_set_flag_slot,
	(void *) APR_OFFSETOF(osa_config_rec, sqlite3NoPasswd),
	OR_AUTHCFG | RSRC_CONF, "If On, only check if user exists; ignore password"),

	AP_INIT_FLAG("OSAEnable", ap_set_flag_slot,
	(void *) APR_OFFSETOF(osa_config_rec, sqlite3Enable),
	OR_AUTHCFG | RSRC_CONF, "enable sqlite3 authorization"),

	AP_INIT_TAKE1("OSAUserCondition", ap_set_string_slot,
	(void *) APR_OFFSETOF(osa_config_rec, sqlite3UserCondition),
	OR_AUTHCFG | RSRC_CONF, "condition to add to user where-clause"),

	AP_INIT_TAKE1("OSAGroupCondition", ap_set_string_slot,
	(void *) APR_OFFSETOF(osa_config_rec, sqlite3GroupCondition),
	OR_AUTHCFG | RSRC_CONF, "condition to add to group where-clause"),

	AP_INIT_TAKE1("OSACharacterSet", ap_set_string_slot,
	(void *) APR_OFFSETOF(osa_config_rec, sqlite3CharacterSet),
	OR_AUTHCFG | RSRC_CONF, "sqlite3 character set to be used"),

  AP_INIT_FLAG("OSALogHit", ap_set_flag_slot,
  (void *) APR_OFFSETOF(osa_config_rec, logHit),
  OR_AUTHCFG | RSRC_CONF, "log hit in DB"),


  AP_INIT_TAKE1("OSAServerName", ap_set_string_slot,
  (void *) APR_OFFSETOF(osa_config_rec, serverName),
  OR_AUTHCFG | RSRC_CONF, "Server name prefix. Ex. https://www.server.com"),


  AP_INIT_TAKE1("OSACookieAuthTable", ap_set_string_slot,
  (void *) APR_OFFSETOF(osa_config_rec, cookieAuthTable),
  OR_AUTHCFG | RSRC_CONF, "table name containing authentication tokens default=authtoken"),

  AP_INIT_TAKE1("OSACookieAuthUsernameField", ap_set_string_slot,
  (void *) APR_OFFSETOF(osa_config_rec, cookieAuthUsernameField),
  OR_AUTHCFG | RSRC_CONF, "field name in OSACookieAuthTable containing authenticated used default=userName"),

  AP_INIT_TAKE1("OSACookieAuthTokenField", ap_set_string_slot,
  (void *) APR_OFFSETOF(osa_config_rec, cookieAuthTokenField),
  OR_AUTHCFG | RSRC_CONF, "field name in OSACookieAuthTable containing generated token default=token"),

  AP_INIT_TAKE1("OSACookieAuthValidityField", ap_set_string_slot,
  (void *) APR_OFFSETOF(osa_config_rec, cookieAuthValidityField),
  OR_AUTHCFG | RSRC_CONF, "field name in OSACookieAuthTable containing validity date for generated token default=validUntil"),



  AP_INIT_FLAG("OSACookieAuthEnable", ap_set_flag_slot,
  (void *) APR_OFFSETOF(osa_config_rec, cookieAuthEnable),
  OR_AUTHCFG | RSRC_CONF, "enable authentication/authorization from cookie"),

  AP_INIT_TAKE1("OSACookieAuthName", ap_set_string_slot,
  (void *) APR_OFFSETOF(osa_config_rec, cookieAuthName),
  OR_AUTHCFG | RSRC_CONF, "cookie name for authentication"),

  AP_INIT_TAKE1("OSACookieAuthLoginForm", ap_set_string_slot,
  (void *) APR_OFFSETOF(osa_config_rec, cookieAuthLoginForm),
  OR_AUTHCFG | RSRC_CONF, "login form URI to redirect to login if OSACookieAuthEnable is enable and not OSABasicAuthEnable"),

  AP_INIT_FLAG("OSACookieAuthBurn", ap_set_flag_slot,
  (void *) APR_OFFSETOF(osa_config_rec, cookieAuthBurn),
  OR_AUTHCFG | RSRC_CONF, "burn auth cookie after usage"),

  AP_INIT_TAKE1("OSACookieAuthDomain", ap_set_string_slot,
  (void *) APR_OFFSETOF(osa_config_rec, cookieAuthDomain),
  OR_AUTHCFG | RSRC_CONF, "cookie name for authentication"),

  AP_INIT_TAKE1("OSACookieAuthTTL", ap_set_int_slot,
  (void *) APR_OFFSETOF(osa_config_rec, cookieAuthTTL),
  OR_AUTHCFG | RSRC_CONF, "Time To Live for authentication cookie"),



  AP_INIT_FLAG("OSABasicAuthEnable", ap_set_flag_slot,
  (void *) APR_OFFSETOF(osa_config_rec, basicAuthEnable),
  OR_AUTHCFG | RSRC_CONF, "enable authentication/authorization with basic authentication"),

  AP_INIT_RAW_ARGS("OSARequire", ap_set_string_slot,
  (void *) APR_OFFSETOF(osa_config_rec, require),
  OR_AUTHCFG | RSRC_CONF, "Required authorization"),

  AP_INIT_FLAG("OSAAllowAnonymous", ap_set_flag_slot,
  (void *) APR_OFFSETOF(osa_config_rec, allowAnonymous),
  OR_AUTHCFG | RSRC_CONF, "Allow unauthenticated access even if (OSARequire && (OSABasicAuthEnable||OSACookieAuthEnable)) are set. In such a case, Identity is forwarded"),


  AP_INIT_FLAG("OSACheckGlobalQuotas", ap_set_flag_slot,
  (void *) APR_OFFSETOF(osa_config_rec, checkGlobalQuotas),
  OR_AUTHCFG | RSRC_CONF, "check global quotas for resource"),

  AP_INIT_FLAG("OSACheckUserQuotas", ap_set_flag_slot,
  (void *) APR_OFFSETOF(osa_config_rec, checkUserQuotas),
  OR_AUTHCFG | RSRC_CONF, "check per user quotas for resource"),

  AP_INIT_TAKE1("OSAResourceName", ap_set_string_slot,
  (void *) APR_OFFSETOF(osa_config_rec, resourceName),
  OR_AUTHCFG | RSRC_CONF, "resource on witch quotas are check"),

  AP_INIT_TAKE1("OSAResourceNameField", ap_set_string_slot,
  (void *) APR_OFFSETOF(osa_config_rec, sqlite3ResourceNameField),
  OR_AUTHCFG | RSRC_CONF, "column containing resource name"),

  AP_INIT_TAKE1("OSAPerSecField", ap_set_string_slot,
  (void *) APR_OFFSETOF(osa_config_rec, sqlite3PerSecField),
  OR_AUTHCFG | RSRC_CONF, "column containing per second quota"),

  AP_INIT_TAKE1("OSAPerDayField", ap_set_string_slot,
  (void *) APR_OFFSETOF(osa_config_rec, sqlite3PerDayField),
  OR_AUTHCFG | RSRC_CONF, "column containing per day quota"),

  AP_INIT_TAKE1("OSAPerMonthField", ap_set_string_slot,
  (void *) APR_OFFSETOF(osa_config_rec, sqlite3PerMonthField),
  OR_AUTHCFG | RSRC_CONF, "column containing per month quota"),

  AP_INIT_TAKE1("OSAGlobalQuotasTable", ap_set_string_slot,
  (void *) APR_OFFSETOF(osa_config_rec, sqlite3GlobalQuotasTable),
  OR_AUTHCFG | RSRC_CONF, "table containing global quotas"),

  AP_INIT_TAKE1("OSAGlobalQuotasCondition", ap_set_string_slot,
  (void *) APR_OFFSETOF(osa_config_rec, sqlite3GlobalQuotasCondition),
  OR_AUTHCFG | RSRC_CONF, "condition to add to GlobalQuotas query"),

  AP_INIT_TAKE1("OSAUserQuotasTable", ap_set_string_slot,
  (void *) APR_OFFSETOF(osa_config_rec, sqlite3UserQuotasTable),
  OR_AUTHCFG | RSRC_CONF, "table containing global quotas"),

  AP_INIT_TAKE1("OSAUserQuotasCondition", ap_set_string_slot,
  (void *) APR_OFFSETOF(osa_config_rec, sqlite3UserQuotasCondition),
  OR_AUTHCFG | RSRC_CONF, "condition to add to UserQuotas query"),

  AP_INIT_TAKE1("OSACountersTable", ap_set_string_slot,
  (void *) APR_OFFSETOF(osa_config_rec, countersTable),
  OR_AUTHCFG | RSRC_CONF, "Table containing counters"),

  AP_INIT_TAKE1("OSACounterNameField", ap_set_string_slot,
  (void *) APR_OFFSETOF(osa_config_rec, counterNameField),
  OR_AUTHCFG | RSRC_CONF, "Column containting counter name"),


  AP_INIT_TAKE1("OSACounterValueField", ap_set_string_slot,
  (void *) APR_OFFSETOF(osa_config_rec, counterValueField),
  OR_AUTHCFG | RSRC_CONF, "Column containting counter value"),

  AP_INIT_TAKE1("OSAIdentityHeadersMapping", ap_set_string_slot,
  (void *) APR_OFFSETOF(osa_config_rec, indentityHeadersMapping),
  OR_AUTHCFG | RSRC_CONF, "forward user identity as HTTP Headers"),

  { NULL }
};
#else
static
command_rec osa_cmds[] = {
  { "OSASqliteFilename", ap_set_string_slot,
    (void*)XtOffsetOf(osa_config_rec, sqlite_db_filename),
    OR_AUTHCFG | RSRC_CONF, TAKE1, "sqlite db finemane" },

  { "OSAUserTable", ap_set_string_slot,
    (void*)XtOffsetOf(osa_config_rec, sqlite3pwtable),
    OR_AUTHCFG | RSRC_CONF, TAKE1, "sqlite3 user table name" },

  { "OSAGroupTable", ap_set_string_slot,
    (void*)XtOffsetOf(osa_config_rec, sqlite3grptable),
    OR_AUTHCFG | RSRC_CONF, TAKE1, "sqlite3 group table name" },

  { "OSANameField", ap_set_string_slot,
    (void*)XtOffsetOf(osa_config_rec, sqlite3NameField),
    OR_AUTHCFG | RSRC_CONF, TAKE1, "sqlite3 User ID field name within User table" },

  { "OSAGroupField", ap_set_string_slot,
    (void*)XtOffsetOf(osa_config_rec, sqlite3GroupField),
    OR_AUTHCFG | RSRC_CONF, TAKE1, "sqlite3 Group field name within table" },

  { "OSAGroupUserNameField", ap_set_string_slot,
    (void*)XtOffsetOf(osa_config_rec, sqlite3GroupUserNameField),
    OR_AUTHCFG | RSRC_CONF, TAKE1, "sqlite3 User ID field name within Group table" },

  { "OSAPasswordField", ap_set_string_slot,
    (void*)XtOffsetOf(osa_config_rec, sqlite3PasswordField),
    OR_AUTHCFG | RSRC_CONF, TAKE1, "sqlite3 Password field name within table" },

  { "OSAPwEncryption", ap_set_string_slot,
    (void *)XtOffsetOf(osa_config_rec, sqlite3EncryptionField),
    OR_AUTHCFG | RSRC_CONF, TAKE1, "sqlite3 password encryption method" },

  { "OSASaltField", ap_set_string_slot,
    (void *)XtOffsetOf(osa_config_rec, sqlite3SaltField),
    OR_AUTHCFG | RSRC_CONF, TAKE1, "sqlite3 salt field name within table" },

/*  { "OSAKeepAlive", ap_set_flag_slot,
    (void*)XtOffsetOf(osa_config_rec, sqlite3KeepAlive),
    OR_AUTHCFG | RSRC_CONF, FLAG, "sqlite3 connection kept open across requests if On" },
*/
  { "OSAAuthoritative", ap_set_flag_slot,
    (void*)XtOffsetOf(osa_config_rec, sqlite3Authoritative),
    OR_AUTHCFG | RSRC_CONF, FLAG, "sqlite3 lookup is authoritative if On" },

  { "OSANoPasswd", ap_set_flag_slot,
    (void*)XtOffsetOf(osa_config_rec, sqlite3NoPasswd),
    OR_AUTHCFG | RSRC_CONF, FLAG, "If On, only check if user exists; ignore password" },

  { "OSAEnable", ap_set_flag_slot,
    (void *)XtOffsetOf(osa_config_rec, sqlite3Enable),
    OR_AUTHCFG | RSRC_CONF, FLAG, "enable sqlite3 authorization"},

  { "OSAUserCondition", ap_set_string_slot,
    (void*)XtOffsetOf(osa_config_rec, sqlite3UserCondition),
    OR_AUTHCFG | RSRC_CONF, TAKE1, "condition to add to user where-clause" },

  { "OSAGroupCondition", ap_set_string_slot,
    (void*)XtOffsetOf(osa_config_rec, sqlite3GroupCondition),
    OR_AUTHCFG | RSRC_CONF, TAKE1, "condition to add to group where-clause" },

  { "OSACharacterSet", ap_set_string_slot,
    (void*)XtOffsetOf(osa_config_rec, sqlite3CharacterSet),
    OR_AUTHCFG | RSRC_CONF, TAKE1, "sqlite3 character set to use" },

  { NULL }
};
#endif

module osa_module;

/*
 * Convert binary to hex
 */
static char * bin2hex (POOL *pool, const char * bin, short len) {
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

static char hex2chr(char * in) {
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
static short pw_md5(POOL * pool, const char * real_pw, const char * sent_pw, const char * salt) {
  return strcmp(real_pw,ap_md5(pool, (const unsigned char *) sent_pw)) == 0;
}

/* Checks crypt()ed passwords */
static short pw_crypted(POOL * pool, const char * real_pw, const char * sent_pw, const char * salt) {
  /* salt will contain either the salt or real_pw */
  return strcmp(real_pw, crypt(sent_pw, salt)) == 0;
}

#if _AES
/* checks aes passwords */
static short pw_aes(POOL * pool, const char * real_pw, const char * sent_pw, const char * salt) {
  /* salt will contain the salt value */
  /* Encryption is in 16 byte blocks */
  char * encrypted_sent_pw = PCALLOC(pool, 16 * ((strlen(sent_pw) / 16) + 1));
  short enc_len = my_aes_encrypt(sent_pw, strlen(sent_pw), encrypted_sent_pw, salt, strlen(salt));
  return enc_len > 0 && memcmp(real_pw, encrypted_sent_pw, enc_len) == 0;
}
#endif

/* checks SHA1 passwords */
static short pw_sha1(POOL * pool, const char * real_pw, const char * sent_pw, const char * salt) {
  char *scrambled_sent_pw, *buffer=PCALLOC(pool, 128);
  short enc_len = 0;
#ifdef APACHE2
  apr_sha1_base64(sent_pw, strlen(sent_pw), buffer);
  buffer += 5;   /* go past {SHA1} eyecatcher */
  scrambled_sent_pw = PCALLOC(pool, apr_base64_decode_len(buffer) + 1);
  enc_len = apr_base64_decode(scrambled_sent_pw, buffer);
#else
  ap_sha1_base64(sent_pw, strlen(sent_pw), buffer);
  buffer += 5;   /* go past {SHA1} eyecatcher */
  scrambled_sent_pw = PCALLOC(pool, ap_base64decode_len(buffer) + 1);
  enc_len = ap_base64decode(scrambled_sent_pw, buffer);
#endif
  scrambled_sent_pw[enc_len] = '\0';
  return  strcasecmp(bin2hex(pool, scrambled_sent_pw, enc_len), real_pw) == 0;
}

/* checks plain text passwords */
static short pw_plain(POOL * pool, const char * real_pw, const char * sent_pw, const char * salt) {
  return strcmp(real_pw, sent_pw) == 0;
}

static char * str_format(request_rec * r, char * input) {
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
          LOG_ERROR_1(APLOG_ERR|APLOG_NOERRNO, 0, r, "str_format SQLite ERROR: Insufficient storage to expand format %c", *(pos-1));
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
      LOG_ERROR_2(APLOG_ERR|APLOG_NOERRNO, 0, r, "str_format SQLite ERROR: Invalid formatting character at position %ld: \"%s\"",  (long int)pos-(long int)output, output);
      return input;
    }
  }
  return output;
}

static char * format_remote_host(request_rec * r, char ** parm) {
#ifdef APACHE2
  return  ap_escape_logitem(r->pool, ap_get_remote_host(r->connection, r->per_dir_config, REMOTE_NAME, NULL));
#else
  return ap_escape_logitem(r->pool, ap_get_remote_host(r->connection, r->per_dir_config, REMOTE_NAME));
#endif
}

static char * format_remote_ip(request_rec * r, char ** parm) {
#if (AP_SERVER_MAJORVERSION_NUMBER==2) && (AP_SERVER_MINORVERSION_NUMBER>=4)
		return r->connection->client_ip;
#else
 		return r->connection->remote_ip;	
#endif
}

static char * format_filename(request_rec * r, char ** parm) {
  return ap_escape_logitem(r->pool, r->filename);
}

static char * format_server_name(request_rec * r, char ** parm) {
  return ap_escape_logitem(r->pool, ap_get_server_name(r));
}

static char * format_server_hostname(request_rec * r, char ** parm) {
  return ap_escape_logitem(r->pool, r->server->server_hostname);
}

static char * format_protocol(request_rec * r, char ** parm) {
  return ap_escape_logitem(r->pool, r->protocol);
}

static char * format_method(request_rec * r, char ** parm) {
  return ap_escape_logitem(r->pool, r->method);
}

static char * format_args(request_rec * r, char ** parm) {
  if (r->args)
    return ap_escape_logitem(r->pool, r->args);
  else
    return "";
}

static char * format_request(request_rec * r, char ** parm) {
  return ap_escape_logitem(r->pool,
    (r->parsed_uri.password) ? STRCAT(r->pool, r->method, " ",
#ifdef APACHE2
	apr_uri_unparse(r->pool, &r->parsed_uri, 0),
#else
	ap_unparse_uri_components(r->pool, &r->parsed_uri, 0),
#endif
	r->assbackwards ? NULL : " ", r->protocol, NULL) :
    r->the_request);
}

static char * format_uri(request_rec * r, char ** parm) {
  return ap_escape_logitem(r->pool, r->uri);
}

static char * format_percent(request_rec * r, char ** parm) {
  return "%";
}

static char * format_cookie(request_rec * r, char ** parm) {
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


/*
 * Fetch and return password string from database for named user.
 * If we are in NoPasswd mode, returns user name instead.
 * If user or password not found, returns NULL
 */
static char * get_sqlite3_pw(request_rec *r, char *user, osa_config_rec *m, const char *salt_column, const char ** psalt) {
  char *pw = NULL;		/* password retrieved */
  char *sql_safe_user = NULL;
  int ulen;
  char query[MAX_STRING_LEN];

  if(!open_db_handle(r,m)) {
	  LOG_ERROR_1(APLOG_ERR, 0, r, "get_sqlite3_pw.open_db_handle SQLite ERROR (db open): %s: ", sqlite3_errmsg(connection.handle));

    return NULL;		/* failure reason already logged */
  }

  /*
   * If we are not checking for passwords, there may not be a password field
   * in the database.  We just look up the name field value in this case
   * since it is guaranteed to exist.
   */
  if (m->sqlite3NoPasswd) {
    m->sqlite3PasswordField = m->sqlite3NameField;
  }

  ulen = strlen(user);
  sql_safe_user = PCALLOC(r->pool, ulen*2+1);
  //sqlite3_escape_string(sql_safe_user,user,ulen);
  strcpy(sql_safe_user, user);

  if (salt_column) {	/* If a salt was requested */
    if (m->sqlite3UserCondition) {
      SNPRINTF(query,sizeof(query)-1,"SELECT %s, length(%s), %s FROM %s WHERE upper(%s)=upper(?) AND %s",
		  m->sqlite3PasswordField, m->sqlite3PasswordField, salt_column, m->sqlite3pwtable,
		  m->sqlite3NameField, str_format(r, m->sqlite3UserCondition));
    } else {
      SNPRINTF(query,sizeof(query)-1,"SELECT %s, length(%s), %s FROM %s WHERE upper(%s)=upper(?)",
		  m->sqlite3PasswordField, m->sqlite3PasswordField, salt_column, m->sqlite3pwtable,
		  m->sqlite3NameField);
    }
  } else {
    if (m->sqlite3UserCondition) {
      SNPRINTF(query,sizeof(query)-1,"SELECT %s, length(%s) FROM %s WHERE upper(%s)=upper(?) AND %s",
      m->sqlite3PasswordField, m->sqlite3PasswordField, m->sqlite3pwtable,
      m->sqlite3NameField,  str_format(r, m->sqlite3UserCondition));
    } else {
      SNPRINTF(query,sizeof(query)-1,"SELECT %s, length(%s) FROM %s WHERE upper(%s)=upper(?)",
      m->sqlite3PasswordField, m->sqlite3PasswordField, m->sqlite3pwtable,
      m->sqlite3NameField);
    }
  }

  sqlite3_stmt *stmt;
  int rc = sqlite3_prepare_v2(connection.handle, query, -1, &stmt, 0);    

  if (rc != SQLITE_OK) {
      LOG_ERROR_2(APLOG_ERR, 0, r, "get_sqlite3_pw.sqlite3_query SQLite ERROR: %s: %s", sqlite3_errmsg(connection.handle), r->uri);
      return NULL;
  }
  sqlite3_bind_text(stmt, 1, sql_safe_user, strlen(sql_safe_user), 0);    
  rc = sqlite3_step(stmt);

  /* if (result && (sqlite3_num_rows(result) == 1)) */
  if (rc == SQLITE_ROW) {
    if (sqlite3_column_text(stmt, 0)) {
      int len = atoi(sqlite3_column_text(stmt, 1));
      pw = (char *) PCALLOC(r->pool, len + 1);
      memcpy(pw, sqlite3_column_text(stmt, 0), len);
/*      pw = (char *) PSTRDUP(r->pool, data[0]); */
    } else {		/* no password in sqlite3 table returns NULL */
      /* this should never happen, but test for it anyhow */
      sqlite3_finalize(stmt);
      return NULL;
    }

    if (salt_column) {
      if (sqlite3_column_text(stmt, 2)) {
        *psalt = (char *) PSTRDUP(r->pool, sqlite3_column_text(stmt, 2));
      } else {		/* no alt in sqlite3 table returns NULL */
        *psalt = 0;
      }
    }
  }

  sqlite3_finalize(stmt);

  return pw;
}

/*
 * get list of groups from database.  Returns array of pointers to strings
 * the last of which is NULL.  returns NULL pointer if user is not member
 * of any groups.
 */
static char ** get_sqlite3_groups(request_rec *r, char *user, osa_config_rec *m)
{
  char **list = NULL;
  char query[MAX_STRING_LEN];
  char *sql_safe_user;
  int ulen;

  if(!open_db_handle(r,m)) {
    return NULL;		/* failure reason already logged */
  }

  ulen = strlen(user);
  sql_safe_user = PCALLOC(r->pool, ulen*2+1);
  //sqlite3_escape_string(sql_safe_user,user,ulen);
  strcpy(sql_safe_user, user);

  if (m->sqlite3GroupUserNameField == NULL)
    m->sqlite3GroupUserNameField = m->sqlite3NameField;
  if (m->sqlite3GroupCondition) {
    SNPRINTF(query,sizeof(query)-1,"SELECT %s FROM %s WHERE upper(%s)=upper(?) AND %s",
    m->sqlite3GroupField, m->sqlite3grptable,
    m->sqlite3GroupUserNameField, str_format(r, m->sqlite3GroupCondition));
  } else {
    SNPRINTF(query,sizeof(query)-1,"SELECT %s FROM %s WHERE upper(%s)=upper(?)",
    m->sqlite3GroupField, m->sqlite3grptable,
    m->sqlite3GroupUserNameField);
  }

  sqlite3_stmt *stmt;
  int rc = sqlite3_prepare_v2(connection.handle, query, -1, &stmt, 0);    

  if (rc != SQLITE_OK) {
      LOG_ERROR_2(APLOG_ERR, 0, r, "get_sqlite3_pw.sqlite3_query SQLite ERROR: %s: %s", sqlite3_errmsg(connection.handle), r->uri);
      return NULL;
  }    
  sqlite3_bind_text(stmt, 1, sql_safe_user, strlen(sql_safe_user), 0);
  int i = sqlite3_stmt_data_count(stmt);
  rc = sqlite3_step(stmt);

  /* if (result && (sqlite3_num_rows(result) == 1)) */
  if (i>0) {

    list = (char **) PCALLOC(r->pool, sizeof(char *) * (i+1));
    list[i] = NULL;		/* last element in array is NULL */
    while (i--) {		/* populate the array elements */
      if (sqlite3_column_text(stmt, 0)){
	      list[i] = (char *) PSTRDUP(r->pool, sqlite3_column_text(stmt, 0));
      }else{
	      list[i] = "";		/* if no data, make it empty, not NULL */
      }
      rc = sqlite3_step(stmt);
    }
  }

 	sqlite3_finalize(stmt);


  return list;
}


/*--------------------------------------------------------------------------------------------------*/
/* checkQuotas( osa_config_rec *sec, request_rec *r,char *counterPrefix, char *quotaScope,   */
/*              int maxReqSec, int maxReqDay, int maxReqMon)                                        */
/*--------------------------------------------------------------------------------------------------*/
/* check quotas for a particular resource and a particular scope (global/user)                      */
/*--------------------------------------------------------------------------------------------------*/
/* IN:                                                                                              */
/*        osa_config_rec *sec: module configuration context                                  */
/*        request_rec *r: apache request                                                            */
/*        char *counterPrefix: prefix to use for counters names                                     */
/*        char *quotaScope: scope for quota management (to display errors                           */
/*        int maxReqSec: maximum number of request per second allowed for this counterPrefx/scope   */
/*        int maxReqDay: maximum number of request per day allowed for this counterPrefx/scope      */
/*        int maxReqMon: maximum number of request per month allowed for this counterPrefx/scope    */
/*--------------------------------------------------------------------------------------------------*/
/* RETURN: apache processing status                                                                 */
/*         DONE: if error or over quotas                                                            */
/*         OK: else...                                                                              */
/*--------------------------------------------------------------------------------------------------*/
int checkQuotas( osa_config_rec *sec, request_rec *r,char *counterPrefix, char *quotaScope, unsigned long maxReqSec, unsigned long maxReqDay, unsigned long maxReqMon, int httpStatusOver){

  char query[255];
  char counterSecName[255];
  char counterDayName[255];
  char counterMonName[255];
  unsigned long reqSec, reqDay, reqMon;
  sqlite3_stmt *stmt;
  int sqlite3_rc;





  /* get current time */
  time_t rawtime;
  struct tm * timeinfo;


  time ( &rawtime );
  timeinfo = localtime ( &rawtime );








  /* 2. Check per second quotas */
  /*    delete previous counters (outdated counters)*/
  /*      Create counter name from counterPrefix and current second value */
  sprintf(counterSecName,"%s$$$S=%d-%02d-%02dT%02d:%02d:%02d",counterPrefix,
                timeinfo->tm_year+1900, 
                timeinfo->tm_mon+1,
                timeinfo->tm_mday,
                timeinfo->tm_hour,
                timeinfo->tm_min,
                timeinfo->tm_sec);

  /*     delete previous counters */
  sprintf(query, "DELETE FROM %s WHERE  %s!='%s' and %s like '%s$$$S%%'",sec->countersTable, sec->counterNameField, counterSecName, sec->counterNameField, counterPrefix);
  if (sqlite3_query_execute(connection.handle, query) != 0) {
    LOG_ERROR_1(APLOG_ERR, 0, r, "checkQuotas.delete.old.per.second SQLite ERROR: %s: ", sqlite3_errmsg(connection.handle));
    return osa_error(r,"DB query error",500);
  }

  /*    2.1 retreive counter value for current "per second counter" */
  sprintf(query, "SELECT %s FROM %s WHERE %s='%s'", sec->counterValueField, sec->countersTable, sec->counterNameField, counterSecName);
  sqlite3_rc = sqlite3_prepare_v2(connection.handle, query, -1, &stmt, 0);    

  if (sqlite3_rc != SQLITE_OK) {
    LOG_ERROR_2(APLOG_ERR, 0, r, "get_sqlite3_pw.sqlite3_query SQLite ERROR: %s: %s", sqlite3_errmsg(connection.handle), r->uri);
    return osa_error(r,"DB query error", 500);;
  }    
  if (sqlite3_step(stmt) == SQLITE_ROW) {
    /*      2.1.1 counter was found, get current counter value */
    reqSec=strtol (sqlite3_column_text(stmt, 0),NULL,0);//atoi(data[0]);
  }else{
    /*      2.1.2 counter was not found, start from 0 and insert counter into DB */
    reqSec=0;
    sprintf(query, "INSERT INTO %s (%s,%s) VALUES ('%s',0)", sec->countersTable, sec->counterNameField, sec->counterValueField, counterSecName);
    if (sqlite3_query_execute(connection.handle, query) != 0) {
      LOG_ERROR_1(APLOG_ERR, 0, r, "checkQuotas.insert.per.second SQLite ERROR: %s: ", sqlite3_errmsg(connection.handle));
      return osa_error(r,"DB query error", 500);
    }
  }
  sqlite3_finalize(stmt);

  /*    2.2 increment coutner (in DB too)*/
  sprintf(query, "UPDATE %s SET %s=%lu WHERE %s='%s'", sec->countersTable, sec->counterValueField, reqSec+1, sec->counterNameField, counterSecName);
  if (sqlite3_query_execute(connection.handle, query) != 0) {
    LOG_ERROR_1(APLOG_ERR, 0, r, "checkQuotas.update.per.second SQLite ERROR: %s: ", sqlite3_errmsg(connection.handle));
    return osa_error(r,"DB query error",500);
  }
  /*    2.3 if new coutner value exceed quota, display error and stop */
  if (reqSec+1 > maxReqSec){
    char err[255];
    sprintf(err, "Maximum number of request (%s %lu/%lu) per second allowed exedeed", quotaScope, reqSec+1, maxReqSec);
    
    return osa_error(r,err,httpStatusOver);
  }





  /* 3. Check per day quotas */
  /*      Create counter name from counterPrefix and current day value */
  /*sprintf(counterDayName,"%s-D=%d",counterPrefix, timeinfo->tm_mday);*/
  sprintf(counterDayName,"%s$$$D=%d-%02d-%02d",counterPrefix,
              timeinfo->tm_year+1900, 
                timeinfo->tm_mon+1,
                timeinfo->tm_mday);
  /*      delete previous counters */
  sprintf(query, "DELETE FROM %s WHERE  %s!='%s' and %s like '%s$$$D%%'",sec->countersTable, sec->counterNameField, counterDayName, sec->counterNameField, counterPrefix);
  if (sqlite3_query_execute(connection.handle, query) != 0) {
    LOG_ERROR_1(APLOG_ERR, 0, r, "checkQuotas.delete.old.per.day SQLite ERROR: %s: ", sqlite3_errmsg(connection.handle));
    return osa_error(r,"DB query error", 500);
  }
  /*    3.1 retreive counter value for current "per day counter" */
  sprintf(query, "SELECT %s FROM %s WHERE %s='%s'", sec->counterValueField, sec->countersTable, sec->counterNameField, counterDayName);
  sqlite3_rc = sqlite3_prepare_v2(connection.handle, query, -1, &stmt, 0);    

  if (sqlite3_rc != SQLITE_OK) {
    LOG_ERROR_2(APLOG_ERR, 0, r, "get_sqlite3_pw.sqlite3_query SQLite ERROR: %s: %s", sqlite3_errmsg(connection.handle), r->uri);
    return osa_error(r,"DB query error", 500);;
  }    
  if (sqlite3_step(stmt) == SQLITE_ROW) {
    /*      3.1.1 counter was found, get current counter value */
    reqDay=strtol (sqlite3_column_text(stmt, 0),NULL,0);//atoi(data[0]);
  }else{
    /*      3.1.2 counter was not found, start from 0 and insert counter into DB */
    reqDay=0;
    sprintf(query, "INSERT INTO %s (%s,%s) VALUES ('%s',0)", sec->countersTable, sec->counterNameField, sec->counterValueField, counterDayName);
    if (sqlite3_query_execute(connection.handle, query) != 0) {
            LOG_ERROR_1(APLOG_ERR, 0, r, "checkQuotas.insert.per.day SQLite ERROR: %s: ", sqlite3_errmsg(connection.handle));
            return osa_error(r,"DB query error", 500);
    }
  }
  sqlite3_finalize(stmt);
  sprintf(query, "UPDATE %s SET %s=%lu WHERE %s='%s'", sec->countersTable, sec->counterValueField, reqDay+1, sec->counterNameField, counterDayName);
  if (sqlite3_query_execute(connection.handle, query) != 0) {
    LOG_ERROR_1(APLOG_ERR, 0, r, "checkQuotas.update.per.day SQLite ERROR: %s: ", sqlite3_errmsg(connection.handle));
    return osa_error(r,"DB query error", 500);
  }
  /*    3.3 if new coutner value exceed quota, display error and stop */
  if (reqDay+1 > maxReqDay){
    char err[255];
    sprintf(err, "Maximum number of request (%s %lu/%lu) per day allowed exedeed", quotaScope, reqDay+1, maxReqDay);

    return osa_error(r,err, httpStatusOver);
  }



  /* 4. Check per month quotas */
  /*      Create counter name from counterPrefix and current month value */
  sprintf(counterMonName,"%s$$$M=%d-%02d",counterPrefix,
                timeinfo->tm_year+1900, 
                timeinfo->tm_mon+1);
  /*      delete previous counters */
  sprintf(query, "DELETE FROM %s WHERE  %s!='%s' and %s like '%s$$$M%%'",sec->countersTable, sec->counterNameField, counterMonName, sec->counterNameField, counterPrefix);
  if (sqlite3_query_execute(connection.handle, query) != 0) {
    LOG_ERROR_1(APLOG_ERR, 0, r, "checkQuotas.delete.old.per.month SQLite ERROR: %s: ", sqlite3_errmsg(connection.handle));
    return osa_error(r,"DB query error", 500);
  }
  /*    4.1 retreive counter value for current "per month counter" */
  sprintf(query, "SELECT %s FROM %s WHERE %s='%s'", sec->counterValueField, sec->countersTable, sec->counterNameField, counterMonName);
  sqlite3_rc = sqlite3_prepare_v2(connection.handle, query, -1, &stmt, 0);    

  if (sqlite3_rc != SQLITE_OK) {
    LOG_ERROR_2(APLOG_ERR, 0, r, "get_sqlite3_pw.sqlite3_query SQLite ERROR: %s: %s", sqlite3_errmsg(connection.handle), r->uri);
    return osa_error(r,"DB query error", 500);;
  }    
  if (sqlite3_step(stmt) == SQLITE_ROW) {
    reqMon=strtol (sqlite3_column_text(stmt, 0),NULL,0);//atoi(data[0]);
  }else{
    /*      4.1.2 counter was not found, start from 0 and insert counter into DB */
    reqMon=0;
    sprintf(query, "INSERT INTO %s (%s,%s) VALUES ('%s',0)", sec->countersTable, sec->counterNameField, sec->counterValueField, counterMonName);
    if (sqlite3_query_execute(connection.handle, query) != 0) {
      LOG_ERROR_1(APLOG_ERR, 0, r, "checkQuotas.insert.per.month SQLite ERROR: %s: ", sqlite3_errmsg(connection.handle));
      return osa_error(r,"DB query error", 500);
    }
  }
  sqlite3_finalize(stmt);
  /*    4.2 increment coutner (in DB too) */
  sprintf(query, "UPDATE %s SET %s=%lu WHERE %s='%s'", sec->countersTable, sec->counterValueField, reqMon+1, sec->counterNameField, counterMonName);
  if (sqlite3_query_execute(connection.handle, query) != 0) {
    LOG_ERROR_1(APLOG_ERR, 0, r, "checkQuotas.update.per.month SQLite ERROR: %s: ", sqlite3_errmsg(connection.handle));
    return osa_error(r,"DB query error", 500);
  }
  /*    4.3 if new coutner value exceed quota, display error and stop */
  if (reqMon+1 > maxReqMon){
    char err[255];
    sprintf(err, "Maximum number of request (%s %lu/%lu) per month allowed exedeed", quotaScope, reqMon+1, maxReqMon);

    return osa_error(r,err, httpStatusOver);

  }

  /* 5. no error occurs in quotas management (under quotas) return OK to continue processing */
  return OK;


}

/*--------------------------------------------------------------------------------------------------*/
/* int checkGlobalQuotas( osa_config_rec *sec, request_rec *r)                               */
/*--------------------------------------------------------------------------------------------------*/
/* check quotas for a particular resource at global level                                           */
/*--------------------------------------------------------------------------------------------------*/
/* IN:                                                                                              */
/*        osa_config_rec *sec: module configuration context                                  */
/*        request_rec *r: apache request                                                            */
/*--------------------------------------------------------------------------------------------------*/
/* RETURN: apache processing status                                                                 */
/*         DONE: if error or over quotas                                                            */
/*         OK: else...                                                                              */
/*--------------------------------------------------------------------------------------------------*/
int checkGlobalQuotas( osa_config_rec *sec, request_rec *r){

  char query[2048];
  char counterPrefix[255];
  sqlite3_stmt *stmt;
  int sqlite3_rc;
  unsigned long reqSec=0;
  unsigned long reqDay=0;
  unsigned long reqMonth=0;

  /* 1. create a counter prefix from quotas enabled resource resource name */
  sprintf(counterPrefix,"R=%s",  sec->resourceName);

  /* 2. retreive values form Maximum allowed for resource (sec/day/month) */
  sprintf(query, "SELECT %s, %s, %s FROM %s WHERE %s='%s'", sec->sqlite3PerSecField, sec->sqlite3PerDayField, sec->sqlite3PerMonthField, sec->sqlite3GlobalQuotasTable,  sec->sqlite3ResourceNameField, sec->resourceName);
  if (sec->sqlite3GlobalQuotasCondition){
    /*    2.1 if configuration set a condition (sql) to retreive quotas, integrate it to request */
    sprintf(query,"%s AND %s", query, sec->sqlite3GlobalQuotasCondition);
  }

  sqlite3_rc = sqlite3_prepare_v2(connection.handle, query, -1, &stmt, 0);    

  if (sqlite3_rc != SQLITE_OK) {
    /*    2.2 No quota definition was found in DB ==> ERROR */
    LOG_ERROR_1(APLOG_ERR, 0, r, "checkGlobalQuotas: SQLite ERROR: %s: ", sqlite3_errmsg(connection.handle));
    return osa_error(r,"checkGlobalQuotas: DB query error", 500);
  }    
  if (sqlite3_step(stmt) == SQLITE_ROW) {
    /*    2.3 quota definition was found: get values */
    reqSec=strtol (sqlite3_column_text(stmt, 0),NULL,0); //atoi(data[0]);
    reqDay=strtol (sqlite3_column_text(stmt, 1),NULL,0); //atoi(data[1]);
    reqMonth=strtol (sqlite3_column_text(stmt, 2),NULL,0); //atoi(data[2]);
  }
  sqlite3_finalize(stmt);
  char scope[255];

  /* 3.  Define "quotaScope" variable for checkQuotas and call it */
  sprintf(scope,"global for resource %s",  sec->resourceName);



  int rc;

  P_db(sec, r, counterPrefix);
  rc=checkQuotas(sec, r, counterPrefix, scope, reqSec, reqDay, reqMonth, 503);
  V_db(sec, r, counterPrefix);

  return rc;

}



/*--------------------------------------------------------------------------------------------------*/
/* int checkGlobalQuotas( osa_config_rec *sec, request_rec *r)                               */
/*--------------------------------------------------------------------------------------------------*/
/* check quotas for a particular resource at global level                                           */
/*--------------------------------------------------------------------------------------------------*/
/* IN:                                                                                              */
/*        osa_config_rec *sec: module configuration context                                  */
/*        request_rec *r: apache request                                                            */
/*--------------------------------------------------------------------------------------------------*/
/* RETURN: apache processing status                                                                 */
/*         DONE: if error or over quotas                                                            */
/*         OK: else...                                                                              */
/*--------------------------------------------------------------------------------------------------*/
int checkUserQuotas( osa_config_rec *sec, request_rec *r){

  char query[2048];
  char counterPrefix[255];
  unsigned long reqSec=0;
  unsigned long reqDay=0;
  unsigned long reqMonth=0;
  sqlite3_stmt *stmt;
  int sqlite3_rc;

    


  /* 1. create a counter prefix from quotas enabled resource resource name and username */
  sprintf(counterPrefix,"R=%s$$$U=%s",  sec->resourceName, r->user);

  /* 2. retreive values form Maximum allowed for resource (sec/day/month) */
  sprintf(query, "SELECT %s, %s, %s FROM %s WHERE %s='%s' AND %s='%s'", sec->sqlite3PerSecField, sec->sqlite3PerDayField, sec->sqlite3PerMonthField, sec->sqlite3UserQuotasTable,  sec->sqlite3ResourceNameField, sec->resourceName, sec->sqlite3NameField, r->user);
  if (sec->sqlite3UserQuotasCondition){
    /*    2.1 if configuration set a condition (sql) to retreive quotas, integrate it to request */
    sprintf(query,"%s AND %s", query, sec->sqlite3UserQuotasCondition);
  }

  sqlite3_rc = sqlite3_prepare_v2(connection.handle, query, -1, &stmt, 0);    

  if (sqlite3_rc != SQLITE_OK) {
    /*    2.2 No quota definition was found in DB ==> ERROR */
    LOG_ERROR_1(APLOG_ERR, 0, r, "checkUserQuotas: SQLite ERROR: %s: ", sqlite3_errmsg(connection.handle));
    return osa_error(r,"checkUserQuotas: DB query error", 500);
  }

  sqlite3_rc = sqlite3_step(stmt);
  if (sqlite3_rc == SQLITE_ROW) {
    /*    2.3 quota definition was found: get values */
    reqSec=strtol (sqlite3_column_text(stmt, 0),NULL,0); //atol(data[0]);
    reqDay=strtol (sqlite3_column_text(stmt, 1),NULL,0); //atol(data[1]);
    reqMonth=strtol (sqlite3_column_text(stmt, 2),NULL,0); //atoi(data[2]);
  }else{
    char err[100];
    sprintf(err,"No quota defined for user %s with user quotas control is activated on resource %s",r->user, sec->resourceName); 
    return osa_error(r,err, 500);
  }
  sqlite3_finalize(stmt);
  /* 3.  Define "quotaScope" variable for checkQuotas and call it */
  char scope[255];
  sprintf(scope,"for user %s and resource %s", r->user, sec->resourceName);

  int rc;

  P_db(sec, r, "USER_QUOTAS");
  rc=checkQuotas(sec, r, counterPrefix, scope, reqSec, reqDay, reqMonth, 429);
  V_db(sec, r, "USER_QUOTAS");

  return rc;

}



int redirectToLoginForm(request_rec *r, char *cause){

	osa_config_rec *sec =(osa_config_rec *)ap_get_module_config (r->per_dir_config, &osa_module);
	r->status=303;
	char *curUrl;
	if (r->args==NULL){
		curUrl=(char *)malloc(strlen(r->uri)+1+strlen(sec->serverName));

		sprintf(curUrl,"%s%s", sec->serverName, r->uri);
	}else{
		curUrl=(char *)malloc(strlen(r->uri)+strlen(r->args)+2+strlen(sec->serverName));
		sprintf(curUrl,"%s%s?%s", sec->serverName, r->uri, r->args);
	}
	
	size_t encodedSize;
	char *b64EncodedCurUrl=base64_encode(curUrl, &encodedSize);
	char *location;
	char urlPrm;
	if (strstr(sec->cookieAuthLoginForm,"?") != NULL){
		urlPrm='&';
	}else{
		urlPrm='?';
	}

		
	if (cause==NULL){
	
		location=(char *)malloc(encodedSize+strlen(sec->cookieAuthLoginForm)+4+(sec->cookieAuthDomain!=NULL?strlen(sec->cookieAuthDomain)+4:0));
		sprintf(location,"%s%cl=%s", sec->cookieAuthLoginForm, urlPrm, b64EncodedCurUrl);
	}else{
		location=(char *)malloc(encodedSize+strlen(sec->cookieAuthLoginForm)+4+strlen(cause)+7+(sec->cookieAuthDomain!=NULL?strlen(sec->cookieAuthDomain)+4:0));
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

	free(location);
	free(b64EncodedCurUrl);
	free(curUrl);
	return DONE;
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
					return osa_error(r,"No authentication cookie found",400);
				}else{
					return redirectToLoginForm(r, NULL);
				}
			}else{
				if (TABLE_GET(r->headers_in, "Authorization")==NULL && sec->cookieAuthLoginForm!=NULL) {
					return redirectToLoginForm(r,NULL);
				}
				//basicAuth is also available, so let basic auth do the job
				return DECLINED;
			}
		}
		
		return OK;
}



void deleteAuthCookie(request_rec *r){
	//Delete cookie on client to try Basic Auth on next shot
	char buff[255];
	osa_config_rec *sec =(osa_config_rec *)ap_get_module_config (r->per_dir_config, &osa_module);
	sprintf(buff,"%s=%d; path=/;expires=Thu, 01 Jan 1970 00:00:00 GMT", sec->cookieAuthName,rand());
	if (sec->cookieAuthDomain != NULL){
		char domain[MAX_STRING_LEN];
		sprintf(domain,"; domain=%s",  sec->cookieAuthDomain);
		strcat(buff, domain);
	}
	apr_table_set(r->headers_out, "Set-Cookie", buff);
}


int validateToken(request_rec *r , char *token, int *stillValidFor){
	 osa_config_rec *sec =(osa_config_rec *)ap_get_module_config (r->per_dir_config, &osa_module);
		char query[MAX_STRING_LEN];
		int rc;

		sprintf(query,"DELETE FROM %s WHERE %s<CURRENT_TIMESTAMP",sec->cookieAuthTable, sec->cookieAuthValidityField);
		if (sqlite3_query_execute(connection.handle, query) != 0) {
			LOG_ERROR_1(APLOG_ERR, 0, r, "sqlite3_check_auth_cookie: SQLite ERROR: %s: ", sqlite3_errmsg(connection.handle));
			return osa_error(r,"DB query error",500);
		}

		sprintf(query,"SELECT %s,((julianday(%s) - julianday(CURRENT_TIMESTAMP)) * 86400.0)  FROM %s WHERE token='%s' AND %s>=CURRENT_TIMESTAMP",
			sec->cookieAuthUsernameField, 
			sec->cookieAuthValidityField,
			sec->cookieAuthTable,
			token,
			sec->cookieAuthValidityField);

    sqlite3_stmt *stmt;
    int sqlite3_rc = sqlite3_prepare_v2(connection.handle, query, -1, &stmt, 0);    

    if (rc != SQLITE_OK) {
			LOG_ERROR_1(APLOG_ERR, 0, r, "sqlite3_check_auth_cookie: SQLite ERROR: %s: ", sqlite3_errmsg(connection.handle));
			return osa_error(r,"DB query error",500);
		}
		
		if (sqlite3_step(stmt) == SQLITE_ROW) {
			r->user=(char*)malloc(strlen(sqlite3_column_text(stmt, 0)));
			strcpy(r->user, sqlite3_column_text(stmt, 0));
			*stillValidFor=atoi(sqlite3_column_text(stmt, 1)); 
			rc= OK;
		}else{
			//received token was not found in DB
			if (sec->cookieAuthLoginForm!=NULL){
				//A login form is set up and we wre in the cookie auth schema,
				//Assume that this schema is the prefred one and continue with it
				
				rc= redirectToLoginForm(r, NULL);
			}else if (sec->basicAuthEnable){
				deleteAuthCookie(r);
				rc = DECLINED;
			}else{
				rc= osa_error(r,"Invalid or outdated token", 401);
			}
		}
		sqlite3_finalize(stmt);
		return rc;
}


int generateToken(request_rec *r, char *receivedToken){
  osa_config_rec *sec =(osa_config_rec *)ap_get_module_config (r->per_dir_config, &osa_module);
  char token[255];
  char query[MAX_STRING_LEN];
  int Rc=OK;

  srand(clock());
  int done=0;

  do{
    sprintf(token,"%10d-%010d-%010d-%010d-%010d",  (unsigned)time(NULL), (rand()%1000000000)+1, (rand()%1000000000)+1, (rand()%1000000000)+1, (rand()%1000000000)+1);


    sprintf(query,"INSERT INTO %s (%s, %s, %s) VALUES ('%s',DateTime(CURRENT_TIMESTAMP, '+%d minute'), '%s')", 
      sec->cookieAuthTable,
      sec->cookieAuthTokenField,
      sec->cookieAuthValidityField,
      sec->cookieAuthUsernameField,
      token, sec->cookieAuthTTL, 
      r->user);

      if (sqlite3_query_execute(connection.handle, query) != 0) {
        if (strstr(sqlite3_errmsg(connection.handle),"Duplicate entry") == NULL){
          LOG_ERROR_1(APLOG_ERR, 0, r, "sqlite3_check_auth_cookie: SQLite ERROR: %s: ", sqlite3_errmsg(connection.handle));
          return osa_error(r,"DB query error",500);
        }else{
          LOG_ERROR_1(APLOG_ERR, 0, r, "%s", "Generated token already exists: retry");
        }	
      }else{
        done=1;
      }
  }while (!done);

  if (sec->cookieAuthBurn){
    //Burn received token
    sprintf(query,"UPDATE %s SET %s=DateTime(CURRENT_TIMESTAMP, '+%d second') WHERE %s='%s'",
      sec->cookieAuthTable,
      sec->cookieAuthValidityField,
      COOKIE_BURN_SURVIVAL_TIME, 
      sec->cookieAuthTokenField,
      receivedToken);
      
    if (sqlite3_query_execute(connection.handle, query) != 0) {
      LOG_ERROR_1(APLOG_ERR, 0, r, "sqlite3_check_auth_cookie: SQLite ERROR: %s: ", sqlite3_errmsg(connection.handle));
      return osa_error(r,"DB query error",500);
    }
  }

  sprintf(query,"%s=%s; path=/",sec->cookieAuthName,token);
  if (sec->cookieAuthDomain != NULL){
    char domain[MAX_STRING_LEN];
    sprintf(domain,"; domain=%s",  sec->cookieAuthDomain);
    strcat(query, domain);
  }
  apr_table_set(r->headers_out, "Set-Cookie", query);

  return Rc;
}


/*--------------------------------------------------------------------------------------------------*/
/* int sqlite3_check_quotas(request_rec *r)                                                           */
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
static int sqlite3_authenticate_cookie_user(request_rec *r){

  /* retreive configuration */
  osa_config_rec *sec =(osa_config_rec *)ap_get_module_config (r->per_dir_config, &osa_module);
  int Rc=OK;
  const char *sent_pw;

  if (sec->cookieAuthEnable){
      char token[255];


      if (connection.handle==NULL){
        /* connect database */
        if(!open_db_handle(r,sec)) {
          return osa_error(r,"Unable to connect database", 500);
        }
      }
      P_db(sec, r, "token");
      Rc=getTokenFromCookie(r, token);
      if (Rc != OK){
        V_db(sec, r, "token");
        return Rc;
      }

      int stillValidFor;
      Rc=validateToken(r, token, &stillValidFor);
      if (Rc != OK){
        V_db(sec, r, "token");
        return Rc;
      }
      if ( ((sec->cookieAuthTTL*60)-stillValidFor) >COOKIE_BURN_SURVIVAL_TIME){
        //We received a request with a token created for more than COOKIE_BURN_SURVIVAL_TIME secs
        //re-generate a new one and burn the received one 

        Rc=generateToken(r, token);
        if (Rc != OK){
          V_db(sec, r, "token");
          return Rc;
        }
      }
      V_db(sec, r, "token");

        
  }else{
    Rc=DECLINED;
  }
  return Rc;
}

/*--------------------------------------------------------------------------------------------------*/
/* int sqlite3_check_quotas(request_rec *r)                                                           */
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
static int sqlite3_check_quotas(request_rec *r){

  const char *sent_pw;
  int res=0;
  int rc=OK;


  /* retreive configuration */
  osa_config_rec *sec =(osa_config_rec *)ap_get_module_config (r->per_dir_config, &osa_module);


  if (sec->checkUserQuotas || sec->checkGlobalQuotas){


      if (connection.handle==NULL){
        /* connect database */
        if(!open_db_handle(r,sec)) {
          return osa_error(r,"Unable to connect database", 500);
        }
      }
  //	LOG_ERROR_1(APLOG_NOERRNO|APLOG_ERR, 0, r, "User=%s\n", r->user);
  /* BHE BA COOKIE
      if (sec->sqlite3Enable){
      if ((res = ap_get_basic_auth_pw (r, &sent_pw)) != OK)  {
        LOG_ERROR(APLOG_NOERRNO|APLOG_ERR, 0, r, "checkquotas: Authent is required required, but not no cred in request...... nothing to do...");
        //Authent is required, but still not occursed (typically first call from browser without creds)
        //Don't check
        return DECLINED;
      }
    }
  */
    rc=OK;
    if (sec->checkUserQuotas && sec->sqlite3Enable && r->user != NULL){
      rc=checkUserQuotas(sec,r);
    }

    if (sec->checkGlobalQuotas  && sec->sqlite3Enable && rc==OK){
      rc= checkGlobalQuotas(sec,r);

    }
    

    return rc;
  }else{

    return OK;
  }

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


		r->user=(char*)malloc(strlen(decoded)+5);
		strcpy(r->user,decoded);

		(*pwd)=(char*)malloc(strlen(decoded+i+1)+5);
		strcpy(*pwd,decoded+i+1);
		(*pwd)[len-(strlen(r->user)+1)]=0;

	}else{
		rc=0;
	}
	//free(decoded);
	return rc;
  }else{
	  return 0;
  }
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
 * callback from Apache to do the authentication of the user to his
 * password.
 */
static int sqlite3_authenticate_basic_user (request_rec *r)
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


  if (!sec->sqlite3Enable)	/* no sqlite3 authorization */
    return DECLINED;	

	if (sec->cookieAuthEnable){
		if (r->user != NULL) {
			return DECLINED;
		}
			
	}
	if (sec->basicAuthEnable && r->user==NULL){
				
			  if ((res = get_basic_auth_creds (r, (char**)&sent_pw)) == 0){
				if (sec->allowAnonymous){
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
	}

/* Determine the encryption method */
  if (sec->sqlite3EncryptionField) {
    for (i = 0; i < sizeof(encryptions) / sizeof(encryptions[0]); i++) {
      if (strcasecmp(sec->sqlite3EncryptionField, encryptions[i].string) == 0) {
	enc_data = &(encryptions[i]);
	break;
      }
    }
    if (!enc_data) {  /* Entry was not found in the list */
      char authenticationError[255];
      
      sprintf(authenticationError,"invalid encryption method %s", sec->sqlite3EncryptionField);
      LOG_ERROR_1(APLOG_NOERRNO|APLOG_ERR, 0, r,"%s",  authenticationError);
      //ap_note_basic_auth_failure(r);
      send_request_basic_auth(r);
	  return osa_error(r,authenticationError,NOT_AUTHORIZED);
      //return NOT_AUTHORIZED;

    }
  }
  else
    enc_data = &encryptions[0];

#ifdef APACHE2
  user = r->user;
#else
  user = r->connection->user;
#endif


  if (enc_data->salt_status == NO_SALT || !sec->sqlite3SaltField)
    salt = salt_column = 0;
  else { 			/* Parse the sqlite3SaltField */
    short salt_length = strlen(sec->sqlite3SaltField);

    if (strcasecmp(sec->sqlite3SaltField, "<>") == 0) { /* Salt against self */
      salt = salt_column = 0;
    } else if (sec->sqlite3SaltField[0] == '<' && sec->sqlite3SaltField[salt_length-1] == '>') {
      salt =  PSTRNDUP(r->pool, sec->sqlite3SaltField+1, salt_length - 2);
      salt_column = 0;
    } else {
      salt = 0;
      salt_column = sec->sqlite3SaltField;
    }
  }

  if (enc_data->salt_status == SALT_REQUIRED && !salt && !salt_column) {
    LOG_ERROR_1(APLOG_NOERRNO | APLOG_ERR, 0, r, "SQLite Salt field not specified for encryption %s", sec->sqlite3EncryptionField);
    return DECLINED;
  }

  real_pw = get_sqlite3_pw(r, user, sec, salt_column, &salt ); /* Get a salt if one was specified */

  if(!real_pw)
  {
    /* user not found in database */

    LOG_ERROR_2(APLOG_NOERRNO|APLOG_ERR, 0, r, "SQLite user %s not found: %s", user, r->uri);
    //ap_note_basic_auth_failure (r);
    send_request_basic_auth(r);

    return osa_error(r,"Wrong username password or account expired", NOT_AUTHORIZED);
    if (!sec->sqlite3Authoritative)
      return DECLINED;		/* let other schemes find user */
    else{
      return NOT_AUTHORIZED;
    }
  }

  if (!salt)
    salt = real_pw;

  /* if we don't require password, just return ok since they exist */
  if (sec->sqlite3NoPasswd) {
	  LOG_ERROR_1(APLOG_NOERRNO|APLOG_ERR, 0, r, "SQLite user found: %s and no pwd check required", user);
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



/*
 * check if user is member of at least one of the necessary group(s)
 */
static int sqlite3_check_auth(request_rec *r)
{

  osa_config_rec *sec =
    (osa_config_rec *)ap_get_module_config(r->per_dir_config,
						  &osa_module);
					  
	if (!sec->sqlite3Enable){
		return DECLINED;
	}
#ifdef APACHE2
  char *user = r->user;
#else
  char *user = r->connection->user;
#endif
  int method = r->method_number;

/*#ifdef APACHE2
  const apr_array_header_t *reqs_arr = ap_requires(r);
#else
  const array_header *reqs_arr = ap_requires(r);
#endif

  require_line *reqs = reqs_arr ? (require_line *)reqs_arr->elts : NULL;
*/
  register int x;
  char **groups = NULL;

  if (!sec->sqlite3GroupField) return DECLINED; /* not doing groups here */
  //if (!reqs_arr) return DECLINED; /* no "require" line in access config */

  if (!user || user[0]==0) return DECLINED;
  /* if the group table is not specified, use the same as for password */
  if (!sec->sqlite3grptable) sec->sqlite3grptable = sec->sqlite3pwtable;

   
  const char *requireClause = sec->require;
  const char *t, *want;
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


      if (groups || (groups = get_sqlite3_groups(r, user, sec))) {

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
  if (sec->sqlite3Authoritative) {
	  char authorizationError[255];
	  sprintf(authorizationError,"User %s is not allowed for group %s", user, want);
      //ap_note_basic_auth_failure(r);
      if (sec->cookieAuthLoginForm != NULL && TABLE_GET(r->headers_in, "Authorization")==NULL ){
        //Authorization fail and we didn't came here by basic auth (Authorization is set by BA);
        return redirectToLoginForm(r,"authorization");
      }else if (sec->basicAuthEnable){
        deleteAuthCookie(r);
        send_request_basic_auth(r);
      }
      return osa_error(r,authorizationError,NOT_AUTHORIZED);
  }
  return DECLINED;
}

static int sqlite3_register_hit(request_rec *r)
{
 

  osa_config_rec *sec =
    (osa_config_rec *)ap_get_module_config(r->per_dir_config,
						  &osa_module);
	
	if (sec->logHit ){
		if (connection.handle==NULL){
			/* connect database */
			if(!open_db_handle(r,sec)) {
				return osa_error(r,"Unable to connect database", 500);
			}
		}


		char usr[100];
		if (r->user == NULL){
			strcpy(usr,"");
		}else{
			strcpy(usr, r->user);
		}
		char msg[150];
		char query[2048];


		
		msg[0]=0;
		
		char *S= apr_pstrdup(r->pool, apr_table_get(r->err_headers_out, OSA_ERROR_HEADER));
		if (S==NULL||strcmp(S,"(null)")==0){
			/* Particular case: authent was required, but module succed to handle and authent failed (thandel case where no creds were in request) */
			if (sec->sqlite3Enable && r->status==NOT_AUTHORIZED){
				strcpy(msg,"Authentication was required but no credentials found in request");
			}else{
				strcpy(msg,"OSA controls are OK, backend called");
			}
		}else{
			strcpy(msg,S);
		}
		int i;
		for (i=0;msg[i];i++){
			if (msg[i]=='\''){
				msg[i]=' ';
			}
		}
		char queryString[4096];
		if (r->args != NULL){
			sprintf(queryString,"?%s", r->args);
		}else{
			queryString[0]=0;
		}
		sprintf(query,"insert into hits( serviceName, frontEndEndPoint, userName, message, status) values( '%s','%s %s%s','%s','%s',%d)",  sec->resourceName, r->method, r->uri, queryString , usr, msg, r->status);
		if (sqlite3_query_execute(connection.handle, query) != 0) {
		        LOG_ERROR_1(APLOG_ERR, 0, r, "sqlite3_register_hit: SQLite ERROR: %s: ", sqlite3_errmsg(connection.handle));
		}
	}
	return OK;
}



static int sqlite3_forward_identity(request_rec *r)
{

  osa_config_rec *sec =
    (osa_config_rec *)ap_get_module_config(r->per_dir_config,
						  &osa_module);

	
	if (sec->indentityHeadersMapping){
		if (connection.handle==NULL){
			/* connect database */
			if(!open_db_handle(r,sec)) {
				return osa_error(r,"Unable to connect database", 500);
			}
		}
	      	

		spliting coupleList;
		split(sec->indentityHeadersMapping,';',&coupleList);
		int i;
		char query[MAX_STRING_LEN];
		char fields[MAX_STRING_LEN];
		query[0]='\0';
		fields[0]='\0';
		headersMappingList.listCount=0;
		
		
		//Explode configuration string in set (header name/field name)
		for (i=0;i<coupleList.tokensCount;i++){
			spliting mapping; 
			split(coupleList.tokens[i],',',&mapping);
			strcpy(headersMappingList.list[i].key, mapping.tokens[1]);
			
			headersMappingList.listCount++;
			if (i>0){
				strcat(fields,",");
			}
			strcat(fields, mapping.tokens[0]);
		}
		
		if (r->user != NULL){
			//We found a user in request (i.e successfull authentication ), search the user in DB
			sprintf(query,"SELECT %s FROM %s WHERE upper(%s)=upper(?)",
						fields,  sec->sqlite3pwtable, sec->sqlite3NameField);
      if (sec->sqlite3UserCondition && strlen(sec->sqlite3UserCondition)){
        strcat(query," AND ");
        strcat(query, str_format(r, sec->sqlite3UserCondition));
      }
      sqlite3_stmt *stmt;
      int sqlite3_rc = sqlite3_prepare_v2(connection.handle, query, -1, &stmt, 0);    
		
      if (sqlite3_rc != SQLITE_OK) {
					LOG_ERROR_1(APLOG_ERR, 0, r, "sqlite3_forward_identity: SQLite ERROR: %s: ", sqlite3_errmsg(connection.handle));
					return osa_error(r,"DB query error",500);
			}
      sqlite3_bind_text(stmt, 1, r->user, strlen(r->user), 0);    
      
			if (sqlite3_step(stmt) == SQLITE_ROW) {
				int i;
				char headerName[500];
				char headerValue[500];
				for (i=0;i<headersMappingList.listCount;i++){
					
					if (sqlite3_column_text(stmt, i)){
						strcpy(headersMappingList.list[i].val, sqlite3_column_text(stmt, i));
					}else{
						headersMappingList.list[i].val[0]=0;
					}
					
					apr_table_setn(r->headers_in, headersMappingList.list[i].key, headersMappingList.list[i].val);
									
				}
			}else{
		    LOG_ERROR_1(APLOG_ERR, 0, r, "User %s not found in DB", r->user);
        
      }
			sqlite3_finalize(stmt);
		}else{
			//We didn't found a user in request (i.e unsuccessfull authentication BUT allowAnonymous is set )
			// Forward empty headers
			int i;
			char headerName[500];
			char headerValue[500];
			for (i=0;i<headersMappingList.listCount;i++){
				apr_table_setn(r->headers_in, headersMappingList.list[i].key, "");
			}
		}
	}
	return OK;
}
#ifdef APACHE2
static void register_hooks(POOL *p)
{
    build_decoding_table();
	srand ( time(NULL) );

	//ap_hook_check_user_id(sqlite3_authenticate_basic_user, NULL, NULL, APR_HOOK_MIDDLE);
	//ap_hook_auth_checker(sqlite3_check_auth, NULL, NULL, APR_HOOK_MIDDLE);
	ap_hook_fixups(sqlite3_authenticate_cookie_user, NULL, NULL, APR_HOOK_FIRST);
	ap_hook_fixups(sqlite3_authenticate_basic_user, NULL, NULL, APR_HOOK_FIRST);
	ap_hook_fixups(sqlite3_check_auth, NULL, NULL, APR_HOOK_FIRST);
	ap_hook_fixups(sqlite3_check_quotas, NULL, NULL, APR_HOOK_FIRST);
	ap_hook_fixups(sqlite3_forward_identity, NULL, NULL, APR_HOOK_LAST);
	ap_hook_log_transaction( sqlite3_register_hit, NULL, NULL, APR_HOOK_FIRST);
	
	
}
#endif

#ifdef APACHE2
module AP_MODULE_DECLARE_DATA osa_module =
{
STANDARD20_MODULE_STUFF,
create_osa_dir_config, /* dir config creater */
NULL,                       /* dir merger --- default is to override */
NULL,                       /* server config */
NULL,                       /* merge server config */
osa_cmds,              /* command apr_table_t */
register_hooks              /* register hooks */
};

#else
module osa_module = {
   STANDARD_MODULE_STUFF,
   NULL,			/* initializer */
   create_osa_dir_config, /* dir config creater */
   NULL,			/* dir merger --- default is to override */
   NULL,			/* server config */
   NULL,			/* merge server config */
   osa_cmds,		/* command table */
   NULL,			/* handlers */
   NULL,			/* filename translation */
   sqlite3_authenticate_basic_user, /* check_user_id */
   sqlite3_check_auth,		/* check auth */
   sqlite3_check_quotas,		/* check access */
   NULL,			/* type_checker */
   NULL,			/* fixups */
   NULL,			/* logger */
   NULL,			/* header parser */
   NULL,			/* child_init */
   child_exit,			/* child_exit */
   NULL				/* post read-request */
};
#endif


