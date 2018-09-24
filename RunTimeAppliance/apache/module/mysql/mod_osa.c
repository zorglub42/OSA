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
 * Copyright (c) 2011 – 2014 Orange
 * This software is distributed under the Apache 2 license
 * <http://www.apache.org/licenses/LICENSE-2.0.html>
 *
 *--------------------------------------------------------
 * File Name   : ApplianceManager/RunTimeAppliance/apache/module/mod_osa.c
 *
 * Created     : 2011-08
 * Authors     : Benoit HERARD <benoit.herard(at)orange.com>
 *
 * Description :
 * 	This module, has been created on mod_auth_mysql 3.0.0 (http://modauthmysql.sourceforge.net/) to create a web service publishing appliance module
 *	In addition to authentication and authorization provided by mod_auth_mysql 3.0.0 this module also support supports
 *		- quotas controls (per "alias" and per "user and alias")
 *		- full identity forwarding as HTTP headers (not only username)
 *		- content negociation for error results (json, XML, SOAP, text, html)
 *		- compliant with load balancing (with many http servers 1 DB)
 *--------------------------------------------------------
 * History     :
 * mod_auth_mysql 3.0.0 - 2005-6-22 : Last release of mod_auth_myqsl
 * 1.0.0 - 2012-10-01 : Release of the file
*/

/*
 * Module definition information - the part between the -START and -END
 * lines below is used by Configure. This could be stored in a separate
 * instead.
 *
 * MODULE-DEFINITION-START
 * Name: osa_module
 * ConfigStart
		 MYSQL_LIB="-L/usr/local/lib/mysql -lmysqlclient -lm -lz"
		 if [ "X$MYSQL_LIB" != "X" ]; then
				 LIBS="$LIBS $MYSQL_LIB"
				 echo " + using $MYSQL_LIB for MySQL support"
		 fi
 * ConfigEnd
 * MODULE-DEFINITION-END
 */

#include "../base/osa_base.h"
#include <mysql.h>


#ifndef SCRAMBLED_PASSWORD_CHAR_LENGTH /* Ensure it is defined for older MySQL releases */
	#define SCRAMBLED_PASSWORD_CHAR_LENGTH 32 /* Big enough for the old method of scrambling */
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

/*
 * structure to hold the configuration details for the request
 */
typedef struct mysql_server_connection{
	char *mysqlhost;		/* host name of db server */
	int  mysqlport;		/* port number of db server */
	char *mysqlsocket;		/* socket path of db server */
	char *mysqluser;		/* user ID to connect to db server */
	char *mysqlpasswd;		/* password to connect to db server */
	char *mysqlDB;		/* DB name */	
} mysql_server;
#define getDbServer(r) ((mysql_server *)r->db_server)

/*
 * Global information for the database connection.  Contains
 * the host name, userid and database name used to open the
 * connection.  If handle is not null, assume it is
 * still valid.  MySQL in recent incarnations will re-connect
 * automaticaly if the connection is closed, so we don't have
 * to worry about that here.
 */
typedef struct mysql_connection_handle{
	MYSQL * handle;
	char host [255];
	char user [255];
	char db [255];
	time_t last_used;
} mysql_connection;

static mysql_connection connection = {NULL, "", "", ""};




/*
 * Global handle to db.  If not null, assume it is still valid.
 * MySQL in recent incarnations will re-connect automatically if the
 * connection is closed, so we don't worry about that here.
 */
/* static MYSQL *mysql_handle = NULL; */

static void close_connection() {
	if (connection.handle)
		mysql_close(connection.handle);
	connection.handle = NULL;		/* make sure we don't try to use it later */
	return;
}

/*
 * Callback to close mysql handle when necessary.  Also called when a
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


/*
 * open connection to DB server if necessary.  Return TRUE if connection
 * is good, FALSE if not able to connect.  If false returned, reason
 * for failure has been logged to error_log file already.
 */
static int open_db_handle(request_rec *r, osa_config_rec *m)
{
	static MYSQL mysql_conn;
	short host_match = FALSE;
	short user_match = FALSE;

	if (connection.handle) {

		/* See if the host has changed */
		if (!getDbServer(m)->mysqlhost || (strcmp(getDbServer(m)->mysqlhost, "localhost") == 0)) {
			if (connection.host[0] == '\0')
				host_match = TRUE;
		}
		else
			if (getDbServer(m)->mysqlhost && (strcmp(getDbServer(m)->mysqlhost, connection.host) == 0))
				host_match = TRUE;

		/* See if the user has changed */
		if (getDbServer(m)->mysqluser) {
			if (strcmp(getDbServer(m)->mysqluser, connection.user) == 0)
				user_match = TRUE;
		}
		else
			if (connection.user[0] == '\0')
				user_match = TRUE;

		/* if the host, or user have changed, need to close and reopen database connection */
		if (host_match && user_match) {
			/* If the database hasn't changed, we can just return */
			if (getDbServer(m)->mysqlDB && strcmp(getDbServer(m)->mysqlDB, connection.db) == 0)
				return TRUE; /* already open */

			/* Otherwise we need to reselect the database */
			else {
				if (mysql_select_db(connection.handle,getDbServer(m)->mysqlDB) != 0) {
					LOG_ERROR_1(APLOG_ERR, 0, r, "open_db_handle.mysql_select_db MySQL ERROR: %s", mysql_error(connection.handle));
					return FALSE;
				}else {
					strcpy (connection.db, getDbServer(m)->mysqlDB);
					return TRUE;
				}
			}
		}
		else
			close_connection();
	}

	connection.handle = mysql_init(&mysql_conn);
	if (! connection.handle) {
		LOG_ERROR_1(APLOG_ERR, 0, r, "open_db_handle.mysql_init MySQL ERROR: %s", mysql_error(&mysql_conn));
	}

	if (!getDbServer(m)->mysqlhost || strcmp(getDbServer(m)->mysqlhost,"localhost") == 0) {
		connection.host[0] = '\0';
	} else {
		strcpy(connection.host, getDbServer(m)->mysqlhost);
	}

	connection.handle=mysql_real_connect(&mysql_conn,connection.host,getDbServer(m)->mysqluser,
						getDbServer(m)->mysqlpasswd, NULL, getDbServer(m)->mysqlport,
					getDbServer(m)->mysqlsocket, 0);
	if (!connection.handle) {
		LOG_ERROR_1(APLOG_ERR, 0, r, "open_db_handle.mysql_real_connect MySQL ERROR: %s", mysql_error(&mysql_conn));
		return FALSE;
	}

	if (!m->osaKeepAlive) {
		/* close when request done */
		apr_pool_cleanup_register(r->pool, (void *)NULL, mod_osa_cleanup, mod_osa_cleanup_child);
	}

	if (getDbServer(m)->mysqluser)
		strcpy(connection.user, getDbServer(m)->mysqluser);
	else
		connection.user[0] = '\0';

	if (mysql_select_db(connection.handle,getDbServer(m)->mysqlDB) != 0) {
		LOG_ERROR_1(APLOG_ERR, 0, r, "open_db_handle.mysql_select_db MySQL ERROR: %s", mysql_error(connection.handle));
		return FALSE;
	}
	else {
		strcpy (connection.db, getDbServer(m)->mysqlDB);
	}
	if (m->osaCharacterSet) {	/* If a character set was specified */
		char *query;
		query=apr_psprintf(r->pool, "SET CHARACTER SET %s", m->osaCharacterSet);
		if (mysql_query(connection.handle, query) != 0) {
			LOG_ERROR_2(APLOG_ERR, 0, r, "open_db_handle.mysql_query MySQL ERROR: %s: %s", mysql_error(connection.handle), r->uri);
			return FALSE;
		}
	}

	return TRUE;
}


void *get_db_server_config (POOL *p, osa_config_rec *m)
{
	mysql_server *s = PCALLOC(p, sizeof(mysql_server));
	if (!s) return NULL;		/* failure to get memory is a bad thing */

	
	/* default values */
	s->mysqlhost = _HOST;
	s->mysqlport = _PORT;
	s->mysqlsocket = _SOCKET;
	s->mysqluser = _USER;
	s->mysqlpasswd = _PASSWORD;

	return (void *)s;
}

const char *set_mysql_host(cmd_parms *cmd, void *cfg, const char *host){
	osa_config_rec *config = (osa_config_rec*)cfg;
	mysql_server *s = (mysql_server*)config->db_server;

	s->mysqlhost = (char*)host;
	return NULL;
}
const char *set_mysql_port(cmd_parms *cmd, void *cfg, const char *strPort){
	osa_config_rec *config = (osa_config_rec*)cfg;
	mysql_server *s = (mysql_server*)config->db_server;

	s->mysqlport = atoi(strPort);
	return NULL;
}
const char *set_mysql_socket(cmd_parms *cmd, void *cfg, const char *socket){
	osa_config_rec *config = (osa_config_rec*)cfg;
	mysql_server *s = (mysql_server*)config->db_server;

	s->mysqlsocket = (char*)socket;
	return NULL;
}
const char *set_mysql_user(cmd_parms *cmd, void *cfg, const char *user){
	osa_config_rec *config = (osa_config_rec*)cfg;
	mysql_server *s = (mysql_server*)config->db_server;

	s->mysqluser = (char*)user;
	return NULL;
}
const char *set_mysql_password(cmd_parms *cmd, void *cfg, const char *password){
	osa_config_rec *config = (osa_config_rec*)cfg;
	mysql_server *s = (mysql_server*)config->db_server;

	s->mysqlpasswd = (char*)password;
	return NULL;
}
const char *set_mysql_db(cmd_parms *cmd, void *cfg, const char *db){
	osa_config_rec *config = (osa_config_rec*)cfg;
	mysql_server *s = (mysql_server*)config->db_server;

	s->mysqlDB = (char*)db;
	return NULL;
}


static command_rec osa_cmds[] = {
	// Common config 	
	#include "../base/cmd_config.h"

	//Mysql config
	AP_INIT_TAKE1("OSAHost", set_mysql_host,
	NULL,
	OR_AUTHCFG | RSRC_CONF, "MySQL server host"),

	AP_INIT_TAKE1("OSAPort", set_mysql_port,
	NULL,
	OR_AUTHCFG | RSRC_CONF, "MySQL server port"),

	AP_INIT_TAKE1("OSASocket", set_mysql_socket,
	NULL,
	OR_AUTHCFG | RSRC_CONF, "MySQL server socket"),

	AP_INIT_TAKE1("OSADB", set_mysql_db,
	NULL,
	OR_AUTHCFG | RSRC_CONF, "MySQL server database name"),

	AP_INIT_TAKE1("OSAUser", set_mysql_user,
	NULL,
	OR_AUTHCFG | RSRC_CONF, "MySQL server username"),

	AP_INIT_TAKE1("OSAPassword", set_mysql_password,
	NULL,
	OR_AUTHCFG | RSRC_CONF, "MySQL server password"),

	{ NULL }
};


/*--------------------------------------------------------------------------------------------------*/
/*                 void P_db(osa_config_rec *sec, request_rec *r, char *sem)                        */
/*--------------------------------------------------------------------------------------------------*/
/* Use databse locks to implement semaphore acquire                                                 */
/*--------------------------------------------------------------------------------------------------*/
/* IN:                                                                                              */
/*        osa_config_rec *sec: Module configuration                                                 */
/*        request_rec *r: apache request                                                            */
/*        char *sem: semaphore name                                                                 */
/*--------------------------------------------------------------------------------------------------*/
/* RETURN: void                                                                                     */
/*--------------------------------------------------------------------------------------------------*/
void P_db(osa_config_rec *sec, request_rec *r, char *sem){
	char *query;

	if (connection.handle==NULL){
		/* connect database */
		if(!open_db_handle(r,sec)) {
			LOG_ERROR_1(APLOG_ERR, 0, r, "%s", "Error while connecting DB");

			osa_error(r,"Unable to connect database", 500);
		}
	}

	query=apr_psprintf(r->pool, "SET AUTOCOMMIT=0");
	if (mysql_query(connection.handle, query) != 0) {
		LOG_ERROR_2(APLOG_ERR, 0, r, "P_db (%s): %s: ", query, mysql_error(connection.handle));
	}

	query=apr_psprintf(r->pool, "START TRANSACTION");
	if (mysql_query(connection.handle, query) != 0) {
		LOG_ERROR_2(APLOG_ERR, 0, r, "P_db (%s): %s: ", query, mysql_error(connection.handle));
	}



	query=apr_psprintf(r->pool, "INSERT INTO %s (counterName,value) VALUES ('SEM_%s__',0)", sec->countersTable, sem);
	int tryNumber=0;
	int gotLock=0;
	while (!gotLock && tryNumber <DEAD_LOCK_MAX_RETRY){
		if (mysql_query(connection.handle, query)!=0){
			LOG_ERROR_1(APLOG_DEBUG, 0, r, "%s", "LOCK !!!");
			char *sqlError;
			sqlError=apr_psprintf(r->pool, "%s", (char*)mysql_error(connection.handle));
			if (strstr(sqlError, "Deadlock found when trying to get lock")){
				tryNumber++;
				usleep(DEAD_LOCK_SLEEP_TIME_MICRO_S);
			}else{
				LOG_ERROR_1(APLOG_ERR, 0, r, "P_db MySQL ERROR: %s: ", mysql_error(connection.handle));
				query=apr_psprintf(r->pool, "rollback");
				mysql_query(connection.handle, query) ; 
						osa_error(r,"DB query error",500);
			}
		}else{
			gotLock=1;
		}
	}
	if (tryNumber >=DEAD_LOCK_MAX_RETRY) {
		LOG_ERROR_1(APLOG_ERR, 0, r, "Max retry of %d on deadlock reached", DEAD_LOCK_MAX_RETRY);
		query=apr_psprintf(r->pool, "rollback");
		mysql_query(connection.handle, query) ;
		osa_error(r,"Can't lock counter.......",500);
	}
}



/*--------------------------------------------------------------------------------------------------*/
/*                 void V_db(osa_config_rec *sec, request_rec *r, char *sem)                        */
/*--------------------------------------------------------------------------------------------------*/
/* Use databse locks to implement semaphore acquire                                                 */
/*--------------------------------------------------------------------------------------------------*/
/* IN:                                                                                              */
/*        osa_config_rec *sec: Module configuration                                                 */
/*        request_rec *r: apache request                                                            */
/*        char *sem: semaphore name                                                                 */
/*--------------------------------------------------------------------------------------------------*/
/* RETURN: void                                                                                     */
/*--------------------------------------------------------------------------------------------------*/
void V_db(osa_config_rec *sec, request_rec *r, char *sem){
	char *query;
	query=apr_psprintf(r->pool, "DELETE FROM %s WHERE counterName='SEM_%s__'",sec->countersTable, sem);
	mysql_query(connection.handle, query) ;
	query=apr_psprintf(r->pool, "commit");
	mysql_query(connection.handle, query) ;
}

/*
 * Fetch and return password string from database for named user.
 * If we are in NoPasswd mode, returns user name instead.
 * If user or password not found, returns NULL
 */
char * get_db_pw(request_rec *r, char *user, osa_config_rec *m, const char *salt_column, const char ** psalt) {
	MYSQL_RES *result;
	char *pw = NULL;		/* password retrieved */
	char *sql_safe_user = NULL;
	int ulen;
	char *query;

	if(!open_db_handle(r,m)) {
	LOG_ERROR_1(APLOG_ERR, 0, r, "get_db_pw.open_db_handle MySQL ERROR (db open): %s: ", mysql_error(connection.handle));

		return NULL;		/* failure reason already logged */
	}

	/*
	 * If we are not checking for passwords, there may not be a password field
	 * in the database.  We just look up the name field value in this case
	 * since it is guaranteed to exist.
	 */
	if (m->osaNoPasswd) {
		m->osaPasswordField = m->osaNameField;
	}

	ulen = strlen(user);
	sql_safe_user = PCALLOC(r->pool, ulen*2+1);
	mysql_escape_string(sql_safe_user,user,ulen);

	if (salt_column) {	/* If a salt was requested */
		if (m->osaUserCondition) {
			query=apr_psprintf( r->pool, "SELECT %s, length(%s), %s FROM %s WHERE %s='%s' AND %s",
								m->osaPasswordField, m->osaPasswordField, salt_column, m->osapwtable,
								m->osaNameField, sql_safe_user, str_format(r, m->osaUserCondition)
			);
		} else {
			query=apr_psprintf(r->pool, "SELECT %s, length(%s), %s FROM %s WHERE %s='%s'",
							   m->osaPasswordField, m->osaPasswordField, salt_column, m->osapwtable,
							   m->osaNameField, sql_safe_user
			);
		}
	} else {
		if (m->osaUserCondition) {
			query=apr_psprintf(r->pool, "SELECT %s, length(%s) FROM %s WHERE %s='%s' AND %s",
							   m->osaPasswordField, m->osaPasswordField, m->osapwtable,
							   m->osaNameField, sql_safe_user, str_format(r, m->osaUserCondition)
			);
		} else {
			query=apr_psprintf(r->pool, "SELECT %s, length(%s) FROM %s WHERE %s='%s'",
							   m->osaPasswordField, m->osaPasswordField, m->osapwtable,
							   m->osaNameField, sql_safe_user
			);
		}
	}

	if (mysql_query(connection.handle, query) != 0) {
		LOG_ERROR_2(APLOG_ERR, 0, r, "get_db_pw.mysql_query MySQL ERROR: %s: %s", mysql_error(connection.handle), r->uri);
		return NULL;
	}
	result = mysql_store_result(connection.handle);
	/* if (result && (mysql_num_rows(result) == 1)) */
	if (result && (mysql_num_rows(result) >= 1)) {
		MYSQL_ROW data = mysql_fetch_row(result);
		if (data[0]) {
			int len = atoi(data[1]);
			pw = (char *) PCALLOC(r->pool, len + 1);
			memcpy(pw, data[0], len);
/*      pw = (char *) PSTRDUP(r->pool, data[0]); */
		} else {		/* no password in mysql table returns NULL */
			/* this should never happen, but test for it anyhow */
			mysql_free_result(result);
			return NULL;
		}

		if (salt_column) {
			if (data[2]) {
				*psalt = (char *) PSTRDUP(r->pool, data[2]);
			} else {		/* no alt in mysql table returns NULL */
				*psalt = 0;
			}
		}
	}

	if (result) mysql_free_result(result);

	return pw;
}

/*
 * get list of groups from database.  Returns array of pointers to strings
 * the last of which is NULL.  returns NULL pointer if user is not member
 * of any groups.
 */
char ** get_groups(request_rec *r, char *user, osa_config_rec *m)
{
	MYSQL_RES *result;
	char **list = NULL;
	char *query;
	char *sql_safe_user;
	int ulen;

	if(!open_db_handle(r,m)) {
		return NULL;		/* failure reason already logged */
	}

	ulen = strlen(user);
	sql_safe_user = PCALLOC(r->pool, ulen*2+1);
	mysql_escape_string(sql_safe_user,user,ulen);

	if (m->osaGroupUserNameField == NULL)
		m->osaGroupUserNameField = m->osaNameField;
	if (m->osaGroupCondition) {
		query=apr_psprintf(	r->pool, "SELECT %s FROM %s WHERE %s='%s' AND %s",
							m->osaGroupField, m->osagrptable,
							m->osaGroupUserNameField, sql_safe_user, str_format(r, m->osaGroupCondition)
		);
	} else {
		query=apr_psprintf(	r->pool, "SELECT %s FROM %s WHERE %s='%s'",
							m->osaGroupField, m->osagrptable,
							m->osaGroupUserNameField, sql_safe_user
		);
	}

	if (mysql_query(connection.handle, query) != 0) {
		LOG_ERROR_2(APLOG_ERR, 0, r, "MySQL error %s: %s", mysql_error(connection.handle),r->uri);
		return NULL;
	}

	result = mysql_store_result(connection.handle);
	if (result && (mysql_num_rows(result) > 0)) {
		int i = mysql_num_rows(result);
		list = (char **) PCALLOC(r->pool, sizeof(char *) * (i+1));
		list[i] = NULL;		/* last element in array is NULL */
		while (i--) {		/* populate the array elements */
			MYSQL_ROW data = mysql_fetch_row(result);
			if (data[0])
				list[i] = (char *) PSTRDUP(r->pool, data[0]);
			else
				list[i] = "";		/* if no data, make it empty, not NULL */
		}
	}

	if (result) mysql_free_result(result);

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

	char *query;
	char *counterSecName;
	char *counterDayName;
	char *counterMonName;
	unsigned long reqSec, reqDay, reqMon;
	MYSQL_RES *result;

	/* get current time */
	time_t rawtime;
	struct tm * timeinfo;

	time ( &rawtime );
	timeinfo = localtime ( &rawtime );

	/* 2. Check per second quotas */
	/*    delete previous counters (outdated counters)*/
	/*      Create counter name from counterPrefix and current second value */
	counterSecName=apr_psprintf(r->pool, "%s$$$S=%d-%02d-%02dT%02d:%02d:%02d",counterPrefix,
								timeinfo->tm_year+1900, 
								timeinfo->tm_mon+1,
								timeinfo->tm_mday,
								timeinfo->tm_hour,
								timeinfo->tm_min,
								timeinfo->tm_sec);

	/*     delete previous counters */
	query=apr_psprintf(	r->pool, "DELETE FROM %s WHERE  %s!='%s' and %s like '%s$$$S%%'",
						sec->countersTable, sec->counterNameField, counterSecName, sec->counterNameField, counterPrefix
	);
	if (mysql_query(connection.handle, query) != 0) {
		LOG_ERROR_1(APLOG_ERR, 0, r, "checkQuotas.delete.old.per.second MySQL ERROR: %s: ", mysql_error(connection.handle));
		return osa_error(r,"DB query error",500);
	}

	/*    2.1 retreive counter value for current "per second counter" */
	query=apr_psprintf(r->pool, "SELECT %s FROM %s WHERE %s='%s'", sec->counterValueField, sec->countersTable, sec->counterNameField, counterSecName);
	if (mysql_query(connection.handle, query) != 0) {
		LOG_ERROR_1(APLOG_ERR, 0, r, "checkQuotas.select_current.per_second MySQL ERROR: %s: ", mysql_error(connection.handle));
		return osa_error(r,"DB query error",500);
	}
	result = mysql_store_result(connection.handle);
	if (result && (mysql_num_rows(result) >= 1)) {
		/*      2.1.1 counter was found, get current counter value */
		MYSQL_ROW data = mysql_fetch_row(result);
		reqSec=strtol (data[0],NULL,0);//atoi(data[0]);
	}else{
		/*      2.1.2 counter was not found, start from 0 and insert counter into DB */
		reqSec=0;
		query=apr_psprintf(r->pool, "INSERT INTO %s (%s,%s) VALUES ('%s',0)", sec->countersTable, sec->counterNameField, sec->counterValueField, counterSecName);
		if (mysql_query(connection.handle, query) != 0) {
			LOG_ERROR_1(APLOG_ERR, 0, r, "checkQuotas.insert.per.second MySQL ERROR: %s: ", mysql_error(connection.handle));
			return osa_error(r,"DB query error", 500);
		}
	}
	if (result) mysql_free_result(result);

	/*    2.2 increment coutner (in DB too)*/
	query=apr_psprintf(r->pool, "UPDATE %s SET %s=%lu WHERE %s='%s'", sec->countersTable, sec->counterValueField, reqSec+1, sec->counterNameField, counterSecName);
	if (mysql_query(connection.handle, query) != 0) {
		LOG_ERROR_1(APLOG_ERR, 0, r, "checkQuotas.update.per.second MySQL ERROR: %s: ", mysql_error(connection.handle));
			return osa_error(r,"DB query error",500);
	}
	/*    2.3 if new coutner value exceed quota, display error and stop */
	if (reqSec+1 > maxReqSec){
		char *err;
		err=apr_psprintf(r->pool, "Maximum number of request (%s %lu/%lu) per second allowed exedeed", quotaScope, reqSec+1, maxReqSec);
		
		return osa_error(r,err,httpStatusOver);
	}

	/* 3. Check per day quotas */
	/*      Create counter name from counterPrefix and current day value */
	counterDayName=apr_psprintf(r->pool, "%s$$$D=%d-%02d-%02d",counterPrefix,
							timeinfo->tm_year+1900, 
								timeinfo->tm_mon+1,
								timeinfo->tm_mday);
	/*      delete previous counters */
	query=apr_psprintf(r->pool, "DELETE FROM %s WHERE  %s!='%s' and %s like '%s$$$D%%'",sec->countersTable, sec->counterNameField, counterDayName, sec->counterNameField, counterPrefix);
	if (mysql_query(connection.handle, query) != 0) {
		LOG_ERROR_1(APLOG_ERR, 0, r, "checkQuotas.delete.old.per.day MySQL ERROR: %s: ", mysql_error(connection.handle));
		return osa_error(r,"DB query error", 500);
	}
	/*    3.1 retreive counter value for current "per day counter" */
	query=apr_psprintf(r->pool, "SELECT %s FROM %s WHERE %s='%s'", sec->counterValueField, sec->countersTable, sec->counterNameField, counterDayName);
	if (mysql_query(connection.handle, query) != 0) {
		LOG_ERROR_1(APLOG_ERR, 0, r, "checkQuotas.select_current.per.day MySQL ERROR: %s: ", mysql_error(connection.handle));
		return osa_error(r,"DB query error", 500);
	}
	result = mysql_store_result(connection.handle);
	if (result && (mysql_num_rows(result) >= 1)) {
		/*      3.1.1 counter was found, get current counter value */
		MYSQL_ROW data = mysql_fetch_row(result);
		reqDay=strtol (data[0],NULL,0);//atoi(data[0]);
	}else{
		/*      3.1.2 counter was not found, start from 0 and insert counter into DB */
		reqDay=0;
		query=apr_psprintf(r->pool, "INSERT INTO %s (%s,%s) VALUES ('%s',0)", sec->countersTable, sec->counterNameField, sec->counterValueField, counterDayName);
		if (mysql_query(connection.handle, query) != 0) {
			LOG_ERROR_1(APLOG_ERR, 0, r, "checkQuotas.insert.per.day MySQL ERROR: %s: ", mysql_error(connection.handle));
			return osa_error(r,"DB query error", 500);
		}
	}
	if (result) mysql_free_result(result);/*    3.2 increment coutner (in DB too) */
	query=apr_psprintf(r->pool, "UPDATE %s SET %s=%lu WHERE %s='%s'", sec->countersTable, sec->counterValueField, reqDay+1, sec->counterNameField, counterDayName);
	if (mysql_query(connection.handle, query) != 0) {
		LOG_ERROR_1(APLOG_ERR, 0, r, "checkQuotas.update.per.day MySQL ERROR: %s: ", mysql_error(connection.handle));
		return osa_error(r,"DB query error", 500);
	}
	/*    3.3 if new coutner value exceed quota, display error and stop */
	if (reqDay+1 > maxReqDay){
		char *err;
		err=apr_psprintf(r->pool, "Maximum number of request (%s %lu/%lu) per day allowed exedeed", quotaScope, reqDay+1, maxReqDay);
		
		return osa_error(r,err, httpStatusOver);
	}

	/* 4. Check per month quotas */
	/*      Create counter name from counterPrefix and current month value */
	counterMonName=apr_psprintf(r->pool, "%s$$$M=%d-%02d",counterPrefix,
								timeinfo->tm_year+1900, 
								timeinfo->tm_mon+1);
	/*      delete previous counters */
	query=apr_psprintf(r->pool, "DELETE FROM %s WHERE  %s!='%s' and %s like '%s$$$M%%'",sec->countersTable, sec->counterNameField, counterMonName, sec->counterNameField, counterPrefix);
	if (mysql_query(connection.handle, query) != 0) {
		LOG_ERROR_1(APLOG_ERR, 0, r, "checkQuotas.delete.old.per.month MySQL ERROR: %s: ", mysql_error(connection.handle));
		return osa_error(r,"DB query error", 500);
	}
	/*    4.1 retreive counter value for current "per month counter" */
	query=apr_psprintf(r->pool, "SELECT %s FROM %s WHERE %s='%s'", sec->counterValueField, sec->countersTable, sec->counterNameField, counterMonName);
	if (mysql_query(connection.handle, query) != 0) {
		LOG_ERROR_1(APLOG_ERR, 0, r, "checkQuotas.select_current.per.month MySQL ERROR: %s: ", mysql_error(connection.handle));
		return osa_error(r,"DB query error", 500);
	}
	result = mysql_store_result(connection.handle);
	if (result && (mysql_num_rows(result) >= 1)) {
		/*      4.1.1 counter was found, get current counter value */
		MYSQL_ROW data = mysql_fetch_row(result);
		reqMon=strtol (data[0],NULL,0);//atoi(data[0]);
	}else{
		/*      4.1.2 counter was not found, start from 0 and insert counter into DB */
		reqMon=0;
		query=apr_psprintf(r->pool, "INSERT INTO %s (%s,%s) VALUES ('%s',0)", sec->countersTable, sec->counterNameField, sec->counterValueField, counterMonName);
		if (mysql_query(connection.handle, query) != 0) {
						LOG_ERROR_1(APLOG_ERR, 0, r, "checkQuotas.insert.per.month MySQL ERROR: %s: ", mysql_error(connection.handle));
						return osa_error(r,"DB query error", 500);
		}
	}
	if (result) mysql_free_result(result);
	/*    4.2 increment coutner (in DB too) */
	query=apr_psprintf(r->pool, "UPDATE %s SET %s=%lu WHERE %s='%s'", sec->countersTable, sec->counterValueField, reqMon+1, sec->counterNameField, counterMonName);
	if (mysql_query(connection.handle, query) != 0) {
		LOG_ERROR_1(APLOG_ERR, 0, r, "checkQuotas.update.per.month MySQL ERROR: %s: ", mysql_error(connection.handle));
		return osa_error(r,"DB query error", 500);
	}
	/*    4.3 if new coutner value exceed quota, display error and stop */
	if (reqMon+1 > maxReqMon){
		char *err;
		err=apr_psprintf(r->pool, "Maximum number of request (%s %lu/%lu) per month allowed exedeed", quotaScope, reqMon+1, maxReqMon);

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

	char *query;
	char *counterPrefix;
	MYSQL_RES *result;
	unsigned long reqSec=0;
	unsigned long reqDay=0;
	unsigned long reqMonth=0;

	if (connection.handle==NULL){
		/* connect database */
		if(!open_db_handle(r,sec)) {
			return osa_error(r,"Unable to connect database", 500);
		}
	}


	/* 1. create a counter prefix from quotas enabled resource resource name */
	counterPrefix=apr_psprintf(r->pool, "R=%s",  sec->resourceName);

	/* 2. retreive values form Maximum allowed for resource (sec/day/month) */
	query=apr_psprintf(r->pool, "SELECT %s, %s, %s FROM %s WHERE %s='%s'", sec->osaPerSecField, sec->osaPerDayField, sec->osaPerMonthField, sec->osaGlobalQuotasTable,  sec->osaResourceNameField, sec->resourceName);
	if (sec->osaGlobalQuotasCondition){
		/*    2.1 if configuration set a condition (sql) to retreive quotas, integrate it to request */
		query=apr_psprintf(r->pool, "%s AND %s", query, sec->osaGlobalQuotasCondition);
	}

	if (mysql_query(connection.handle, query) != 0) {
		/*    2.2 No quota definition was found in DB ==> ERROR */
		LOG_ERROR_1(APLOG_ERR, 0, r, "checkGlobalQuotas: MySQL ERROR: %s: ", mysql_error(connection.handle));
		return osa_error(r,"checkGlobalQuotas: DB query error", 500);
	}

	result = mysql_store_result(connection.handle);
	if (result && (mysql_num_rows(result) >= 1)) {
		/*    2.3 quota definition was found: get values */
		MYSQL_ROW data = mysql_fetch_row(result);
		reqSec=strtol (data[0],NULL,0); //atoi(data[0]);
		reqDay=strtol (data[1],NULL,0); //atoi(data[1]);
		reqMonth=strtol (data[2],NULL,0); //atoi(data[2]);
	}
	if (result) mysql_free_result(result);
	char *scope;

	/* 3.  Define "quotaScope" variable for checkQuotas and call it */
	scope=apr_psprintf(r->pool, "global for resource %s",  sec->resourceName);



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

	char *query;
	char *counterPrefix;
	MYSQL_RES *result;
	unsigned long reqSec=0;
	unsigned long reqDay=0;
	unsigned long reqMonth=0;


	if (connection.handle==NULL){
		/* connect database */
		if(!open_db_handle(r,sec)) {
			return osa_error(r,"Unable to connect database", 500);
		}
	}

	/* 1. create a counter prefix from quotas enabled resource resource name and username */
	counterPrefix=apr_psprintf(r->pool, "R=%s$$$U=%s",  sec->resourceName, r->user);

	/* 2. retreive values form Maximum allowed for resource (sec/day/month) */
	query=apr_psprintf(r->pool, "SELECT %s, %s, %s FROM %s WHERE %s='%s' AND %s='%s'", sec->osaPerSecField, sec->osaPerDayField, sec->osaPerMonthField, sec->osaUserQuotasTable,  sec->osaResourceNameField, sec->resourceName, sec->osaNameField, r->user);
	if (sec->osaUserQuotasCondition){
		/*    2.1 if configuration set a condition (sql) to retreive quotas, integrate it to request */
		query=apr_psprintf(r->pool, "%s AND %s", query, sec->osaUserQuotasCondition);
	}

	if (mysql_query(connection.handle, query) != 0) {
		/*    2.2 No quota definition was found in DB ==> ERROR */
		LOG_ERROR_1(APLOG_ERR, 0, r, "checkUserQuotas: MySQL ERROR: %s: ", mysql_error(connection.handle));
		return osa_error(r,"checkUserQuotas: DB query error", 500);
	}

	result = mysql_store_result(connection.handle);
	if (result && (mysql_num_rows(result) >= 1)) {
		/*    2.3 quota definition was found: get values */
		MYSQL_ROW data = mysql_fetch_row(result);
		reqSec=strtol (data[0],NULL,0); //atol(data[0]);
		reqDay=strtol (data[1],NULL,0); //atol(data[1]);
		reqMonth=strtol (data[2],NULL,0); //atoi(data[2]);
	}else{
		if (result){
			char *err;
			err=apr_psprintf(r->pool, "No quota defined for user %s with user quotas control is activated on resource %s",r->user, sec->resourceName); 
			return osa_error(r,err, 500);
		}
	}
	if (result) mysql_free_result(result);
	/* 3.  Define "quotaScope" variable for checkQuotas and call it */
	char *scope;
	scope=apr_psprintf(r->pool, "for user %s and resource %s", r->user, sec->resourceName);

	int rc;

	P_db(sec, r, "USER_QUOTAS");
	rc=checkQuotas(sec, r, counterPrefix, scope, reqSec, reqDay, reqMonth, 429);
	V_db(sec, r, "USER_QUOTAS");

	return rc;
}


int cleanGeneratedTokens(request_rec *r){
	 osa_config_rec *sec =(osa_config_rec *)ap_get_module_config (r->per_dir_config, &osa_module);
		char *query;
		MYSQL_RES *result;
		int rc;

		if (connection.handle==NULL){
			/* connect database */
			if(!open_db_handle(r,sec)) {
				return osa_error(r,"Unable to connect database", 500);
			}
		}

		query=apr_psprintf(r->pool, "DELETE FROM %s WHERE %s<now()",sec->cookieAuthTable, sec->cookieAuthValidityField);

		if (mysql_query(connection.handle, query) != 0) {
			LOG_ERROR_1(APLOG_ERR, 0, r, "cleanGeneratedTokens: MySQL ERROR: %s: ", mysql_error(connection.handle));
			return osa_error(r,"DB query error",500);
		}
		return OK;
}


int validateToken(request_rec *r , char *token, char **initialToken, int *burned){
	 osa_config_rec *sec =(osa_config_rec *)ap_get_module_config (r->per_dir_config, &osa_module);
		char *query;
		MYSQL_RES *result;
		int rc;
		rc= OK;

		if (connection.handle==NULL){
			/* connect database */
			if(!open_db_handle(r,sec)) {
				return osa_error(r,"Unable to connect database", 500);
			}
		}
		//Search if token exists in table ans is still valid
		query=apr_psprintf(r->pool, "SELECT %s, %s, %s FROM %s WHERE token='%s' AND %s>=now()",
			sec->cookieAuthUsernameField, 
			sec->cookieAuthBurnedField,
			sec->cookieInitialAuthTokenField,
			sec->cookieAuthTable,
			token,
			sec->cookieAuthValidityField);

		if (mysql_query(connection.handle, query) != 0) {
			LOG_ERROR_1(APLOG_ERR, 0, r, "validateToken: MySQL ERROR: %s: ", mysql_error(connection.handle));
			return osa_error(r,"DB query error",500);
		}
		result = mysql_store_result(connection.handle);
		if (result && (mysql_num_rows(result) >= 1)) {
					MYSQL_ROW data = mysql_fetch_row(result);
			//r->user=(char *) PCALLOC(r->pool, strlen(data[0]));
 			//strcpy(r->user, data[0]);
 			r->user=(char *) PSTRDUP(r->pool, data[0]);
			*burned=atoi(data[1]);
			(*initialToken)=(char *) PSTRDUP(r->pool, data[2]);
						LOG_ERROR_1(APLOG_DEBUG, 0, r, "validateToken: initial=%s: ", *initialToken);

			if (sec->cookieAuthBurn){
				//Burn received token (Mark it as burned and set it outdated to be garbadged after cacheTTL)
				query=apr_psprintf(r->pool, "UPDATE %s SET %s=date_add(now() ,interval %d second), %s=1 WHERE %s='%s' and %s=0",
					sec->cookieAuthTable,
					sec->cookieAuthValidityField,
					sec->cookieCacheTime, 
					sec->cookieAuthBurnedField,
					sec->cookieAuthTokenField,
					token,
					sec->cookieAuthBurnedField);

				if (mysql_query(connection.handle, query) != 0) {
					LOG_ERROR_1(APLOG_ERR, 0, r, "regenerateToken: MySQL ERROR: %s: ", mysql_error(connection.handle));
					rc= osa_error(r,"DB query error",500);
				}
			}
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
		if (result) mysql_free_result(result);
		return rc;
}

int extendToken(request_rec *r, char *receivedToken){
	osa_config_rec *sec =(osa_config_rec *)ap_get_module_config (r->per_dir_config, &osa_module);
	char *token;
	char *query;
	int Rc=OK;

	if (connection.handle==NULL){
		/* connect database */
		if(!open_db_handle(r,sec)) {
			return osa_error(r,"Unable to connect database", 500);
		}
	}


	query=apr_psprintf(r->pool, "UPDATE %s SET %s=date_add(now() ,interval %d minute) WHERE %s='%s' and abs(TIMESTAMPDIFF(SECOND, %s, date_add(now() ,interval %d minute)))>%d",
		sec->cookieAuthTable,
		sec->cookieAuthValidityField,
		sec->cookieAuthTTL, 
		sec->cookieAuthTokenField,
		receivedToken,
		sec->cookieAuthValidityField,
		sec->cookieAuthTTL, 
		sec->cookieCacheTime);
	LOG_ERROR_1(APLOG_DEBUG, 0, r, "extendToken: SQL->%s: ", query);

	if (mysql_query(connection.handle, query) != 0) {
		LOG_ERROR_1(APLOG_ERR, 0, r, "extendToken: MySQL ERROR: %s: ", mysql_error(connection.handle));
		return osa_error(r,"DB query error",500);
	}
	return Rc;
}


int regenerateToken(request_rec *r, char *receivedToken, char *initialToken){
	osa_config_rec *sec =(osa_config_rec *)ap_get_module_config (r->per_dir_config, &osa_module);
	char *token;
	char *query;
	int Rc=OK;
	int done=0;

	if (connection.handle==NULL){
		/* connect database */
		if(!open_db_handle(r,sec)) {
			return osa_error(r,"Unable to connect database", 500);
		}
	}

	do{
		token=getToken(r);


		query=apr_psprintf(r->pool, "INSERT INTO %s (%s, %s, %s, %s, %s) VALUES ('%s', date_add(now() ,interval %d minute), '%s', 0, '%s')", 
			sec->cookieAuthTable,
			sec->cookieAuthTokenField,
			sec->cookieAuthValidityField,
			sec->cookieAuthUsernameField,
			sec->cookieAuthBurnedField,
			sec->cookieInitialAuthTokenField,
			token, sec->cookieAuthTTL, 
			r->user,
			initialToken);
			
			if (mysql_query(connection.handle, query) != 0) {
				if (strstr(mysql_error(connection.handle),"Duplicate entry") == NULL){
					LOG_ERROR_2(APLOG_ERR, 0, r, "regenerateToken: MySQL ERROR: %s (%s): ", mysql_error(connection.handle), query);
					return osa_error(r,"DB query error",500);
				}else{
					LOG_ERROR_1(APLOG_ERR, 0, r, "%s", "Generated token already exists: retry");
				}	
			}else{
				done=1;
			}
	}while (!done);

	query=apr_psprintf(r->pool, "%s=%s; path=/",sec->cookieAuthName,token);
	if (sec->cookieAuthDomain != NULL){
		char *domain;
		domain=apr_psprintf(r->pool, "; domain=%s",  sec->cookieAuthDomain);
		query=apr_psprintf(r->pool, "%s%s",query, domain);
	}
	apr_table_set(r->headers_out, "Set-Cookie", query);

	return Rc;
}

int register_hit(request_rec *r)
{
 

	MYSQL_RES *result;
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
		P_db(sec, r, "hits");


		char *usr;
		if (r->user == NULL){
			usr=apr_psprintf(r->pool, "%s","");
		}else{
			usr=apr_psprintf(r->pool, "%s", r->user);
		}
		char *msg;
		char *query;


		
		
		char *S= apr_pstrdup(r->pool, apr_table_get(r->err_headers_out, OSA_ERROR_HEADER));
		if (S==NULL||strcmp(S,"(null)")==0){
			/* Particular case: authent was required, but module succed to handle and authent failed (thandel case where no creds were in request) */
			if (sec->osaEnable && r->status==NOT_AUTHORIZED){
				msg=apr_psprintf(r->pool, "Authentication was required but no credentials found in request");
			}else{
				msg=apr_psprintf(r->pool, "OSA controls are OK, backend called");
			}
		}else{
			msg=apr_psprintf(r->pool, "%s", S);
		}
		int i;
		for (i=0;msg[i];i++){
			if (msg[i]=='\''){
				msg[i]=' ';
			}
		}
		char *queryString;
		if (r->args != NULL){
			queryString=apr_psprintf(r->pool, "?%s", r->args);
		}else{
			queryString=apr_psprintf(r->pool, "%s", "");
		}
		query=apr_psprintf(r->pool, "insert into hits( serviceName, frontEndEndPoint, userName, message, status) values( '%s','%s %s%s','%s','%s',%d)",  sec->resourceName, r->method, r->uri, queryString , usr, msg, r->status);
		if (mysql_query(connection.handle, query) != 0) {
			LOG_ERROR_1(APLOG_ERR, 0, r, "register_hit: MySQL ERROR: %s: ", mysql_error(connection.handle));
		}
		V_db(sec, r, "hits");
	}
	return OK;
}

int get_user_extended_attributes(request_rec *r, stringKeyValList *props){
	MYSQL_RES *result;
	osa_config_rec *sec =
		(osa_config_rec *)ap_get_module_config(r->per_dir_config,
							&osa_module);
	char *query;

	if (connection.handle==NULL){
		/* connect database */
		if(!open_db_handle(r,sec)) {
			return osa_error(r,"Unable to connect database", 500);
		}
	}

	query=apr_psprintf(r->pool, "SELECT %s, %s FROM %s WHERE %s='%s'", sec->userAttributeNameField, sec->userAttributeValueField, sec->userAttributesTable, sec->osaNameField, r->user);
	if (mysql_query(connection.handle, query) != 0) {
		LOG_ERROR_1(APLOG_ERR, 0, r, "register_hit: MySQL ERROR: %s: ", mysql_error(connection.handle));
		return osa_error(r,"Database request error", 500);
	}

	result = mysql_store_result(connection.handle);
	if (result == NULL) {
		LOG_ERROR_1(APLOG_ERR, 0, r, "register_hit: MySQL ERROR: %s: ", mysql_error(connection.handle));
		return osa_error(r,"Database request error", 500);
	}
	 MYSQL_ROW row;
  
  	while ((row = mysql_fetch_row(result))){ 
		props->list[props->listCount].key=PSTRDUP(r->pool, row[0]);
		props->list[props->listCount].val=PSTRDUP(r->pool, row[1]);
		props->listCount++;
	}
    mysql_free_result(result);
    return OK;

}

int get_user_basic_attributes(request_rec *r, char *fields, stringKeyValList *headersMappingList){
	MYSQL_RES *result;
	osa_config_rec *sec =
		(osa_config_rec *)ap_get_module_config(r->per_dir_config,
							&osa_module);
	char *query;

	if (connection.handle==NULL){
		/* connect database */
		if(!open_db_handle(r,sec)) {
			return osa_error(r,"Unable to connect database", 500);
		}
	}

	//We found a user in request (i.e successfull authentication ), search the user in DB
	query=apr_psprintf(r->pool, "SELECT %s FROM %s WHERE %s='%s'", fields,  sec->osapwtable, sec->osaNameField, r->user);
	if (sec->osaUserCondition && strlen(sec->osaUserCondition)){
		query=apr_psprintf(r->pool, "%s AND %s", query, str_format(r, sec->osaUserCondition));
	}

	if (mysql_query(connection.handle, query) != 0) {
		LOG_ERROR_1(APLOG_ERR, 0, r, "forward_identity: MySQL ERROR: %s: ", mysql_error(connection.handle));
		return osa_error(r,"DB query error",500);
	}
	result = mysql_store_result(connection.handle);
	if (result && (mysql_num_rows(result) >= 1)) {
			MYSQL_ROW data = mysql_fetch_row(result);
		int i;
		for (i=0;i<headersMappingList->listCount;i++){
			
			if (data[i]){
				headersMappingList->list[i].val=PSTRDUP(r->pool, data[i]);
			}else{
				headersMappingList->list[i].val=PSTRDUP(r->pool, "");
			}
		}
	}
	if (result) mysql_free_result(result);
	return OK;

}



module AP_MODULE_DECLARE_DATA osa_module =
{
	STANDARD20_MODULE_STUFF,
	create_osa_dir_config, 		/* dir config creater */
	NULL,                  		/* dir merger --- default is to override */
	create_osa_server_config,   /* server config */
	NULL,                  		/* merge server config */
	osa_cmds,              		/* command apr_table_t */
	register_hooks         		/* register hooks */
};
