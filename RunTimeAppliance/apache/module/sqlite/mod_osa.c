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
#include <sqlite3.h>


#ifdef DB_FILE				/* Host to use */
  #define _DB_FILE STRING(DB_FILE)
#else
  #define _DB_FILE "osa.db"			/* Will default to localhost */
#endif
#define SQLITE3_BUSY_TIMEOUT 10000 //ms

/*
 * structure to hold the configuration details for the request
 */
typedef struct sqlite3_connection{
	char *sqlite_db_filename;		/* host name of db server */
} sqlite3_db;
#define getDbServer(r) ((sqlite3_db *)r->db_server)

/*
 * Global information for the database connection.  Contains
 * the db file name,If handle is not null, assume it is
 * still valid.
 */
typedef struct {
  sqlite3 * handle;
  char *sqlite_db_filename;
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
        
        sqlite3_finalize(res);
        fprintf(stderr, "Failed to prepare statement: %s\n", sqlite3_errmsg(db));
        
        return rc;
    }    
    
    rc = sqlite3_step(res);
    
    if (rc == SQLITE_ROW || rc == SQLITE_DONE) {
        rc =0;
    }
    sqlite3_finalize(res);
    return rc;
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


/*
 * open connection to DB server if necessary.  Return TRUE if connection
 * is good, FALSE if not able to connect.  If false returned, reason
 * for failure has been logged to error_log file already.
 */
static int open_db_handle(request_rec *r, osa_config_rec *m)
{
  short file_match = FALSE;
  int rc;
  
  if (connection.handle) {
      if (getDbServer(m)->sqlite_db_filename && strcmp(getDbServer(m)->sqlite_db_filename, connection.sqlite_db_filename) == 0){
      	rc = TRUE; /* already open */
          /* Otherwise we need to reselect the database */
      }else {
        close_connection();
        connection.sqlite_db_filename=PSTRDUP(r->pool, getDbServer(m)->sqlite_db_filename);
        rc = sqlite3_open(connection.sqlite_db_filename, &connection.handle) == SQLITE_OK;
        sqlite3_busy_timeout(connection.handle, SQLITE3_BUSY_TIMEOUT);
        //sqlite3_query_execute(connection.handle, "PRAGMA read_uncommitted = True");
      }
  }else{
        connection.sqlite_db_filename=PSTRDUP(r->pool, getDbServer(m)->sqlite_db_filename);
        rc =  sqlite3_open (connection.sqlite_db_filename, &connection.handle) == SQLITE_OK;
        sqlite3_busy_timeout(connection.handle, SQLITE3_BUSY_TIMEOUT);

        //sqlite3_query_execute(connection.handle, "PRAGMA read_uncommitted = True");
  }
  return rc;
}

void *get_db_server_config (POOL *p, osa_config_rec *m)
{
	sqlite3_db *s = PCALLOC(p, sizeof(sqlite3_db));
	if (!s) return NULL;		/* failure to get memory is a bad thing */

	
	/* default values */
	s->sqlite_db_filename = _DB_FILE;

	return (void *)s;
}



const char *set_sqlite3_db_filename(cmd_parms *cmd, void *cfg, const char *filename){
	osa_config_rec *config = (osa_config_rec*)cfg;
	sqlite3_db *s = (sqlite3_db*)config->db_server;

	s->sqlite_db_filename = (char*)filename;

	return NULL;
}


static command_rec osa_cmds[] = {
  //Common config
  #include "../base/cmd_config.h"

  //SQLite config

	AP_INIT_TAKE1("OSASqliteFilename", set_sqlite3_db_filename,
	NULL,
	OR_AUTHCFG | RSRC_CONF, "sqlite3 server host name"),

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
			osa_error(r,"Unable to connect database", 500);
      return;
		}
	}

  query=apr_psprintf(r->pool, "%s", "BEGIN TRANSACTION");
  if (sqlite3_query_execute(connection.handle, query) != 0) {
    LOG_ERROR_2(APLOG_ERR, 0, r, "P_db (%s): %s: ", query, sqlite3_errmsg(connection.handle));
    osa_error(r,"DB Error", 500);
  }



  query=apr_psprintf(r->pool, "INSERT INTO %s (counterName,value) VALUES ('SEM_%s__',0)",sec->countersTable, sem);
  int tryNumber=0;
  int getLock=0;
  while (!getLock && tryNumber <DEAD_LOCK_MAX_RETRY){
    if (sqlite3_query_execute(connection.handle, query)!=0){
      char *sqlError;
      sqlError=apr_psprintf(r->pool,  "%s", (char*)sqlite3_errmsg(connection.handle));
      if (strstr(sqlError, "database is locked")){
        tryNumber++;
        
        usleep(DEAD_LOCK_SLEEP_TIME_MICRO_S);
      }else{
            LOG_ERROR_1(APLOG_ERR, 0, r, "P_db SQLite ERROR: %s: ", sqlite3_errmsg(connection.handle));
            query=apr_psprintf(r->pool, "%s", "rollback");
            sqlite3_query_execute(connection.handle, query) ; 
            osa_error(r,"DB query error",500);
      }
    }else{
      getLock=1;
    }
  }
  if (tryNumber >=DEAD_LOCK_MAX_RETRY) {
    LOG_ERROR_1(APLOG_ERR, 0, r, "Max retry of %d on deadlock reached", DEAD_LOCK_MAX_RETRY);
    query=apr_psprintf(r->pool, "%s", "rollback");
    sqlite3_query_execute(connection.handle, query) ;
    osa_error(r,"Can't lock counter.......",500);
  }
}



void V_db(osa_config_rec *sec, request_rec *r, char *sem){
  char *query;
  query=apr_psprintf(r->pool, "DELETE FROM %s WHERE counterName='SEM_%s__'",sec->countersTable, sem);
  if (sqlite3_query_execute(connection.handle, query) != 0) {
          LOG_ERROR_1(APLOG_ERR, 0, r, "V_db.delete %s: ", sqlite3_errmsg(connection.handle));
          osa_error(r,"DB Error", 500);
  }
  query=apr_psprintf(r->pool, "%s", "commit");
  if (sqlite3_query_execute(connection.handle, query) != 0) {
          LOG_ERROR_1(APLOG_ERR, 0, r, "V_db.commit %s: ", sqlite3_errmsg(connection.handle));
          osa_error(r,"DB Error", 500);
  }
}

/*
 * Fetch and return password string from database for named user.
 * If we are in NoPasswd mode, returns user name instead.
 * If user or password not found, returns NULL
 */
char * get_db_pw(request_rec *r, char *user, osa_config_rec *m, const char *salt_column, const char ** psalt) {
  char *pw = NULL;		/* password retrieved */
  char *sql_safe_user = NULL;
  int ulen;
  char *query;

  if(!open_db_handle(r,m)) {
	  LOG_ERROR_1(APLOG_ERR, 0, r, "get_sqlite3_pw.open_db_handle SQLite ERROR (db open): %s: ", sqlite3_errmsg(connection.handle));

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
  //sqlite3_escape_string(sql_safe_user,user,ulen);
  strcpy(sql_safe_user, user);

  if (salt_column) {	/* If a salt was requested */
    if (m->osaUserCondition) {
      query=apr_psprintf(r->pool, "SELECT %s, length(%s), %s FROM %s WHERE upper(%s)=upper(?) AND %s",
		  m->osaPasswordField, m->osaPasswordField, salt_column, m->osapwtable,
		  m->osaNameField, str_format(r, m->osaUserCondition));
    } else {
      query=apr_psprintf(r->pool, "SELECT %s, length(%s), %s FROM %s WHERE upper(%s)=upper(?)",
		  m->osaPasswordField, m->osaPasswordField, salt_column, m->osapwtable,
		  m->osaNameField);
    }
  } else {
    if (m->osaUserCondition) {
      query=apr_psprintf(r->pool, "SELECT %s, length(%s) FROM %s WHERE upper(%s)=upper(?) AND %s",
      m->osaPasswordField, m->osaPasswordField, m->osapwtable,
      m->osaNameField,  str_format(r, m->osaUserCondition));
    } else {
      query=apr_psprintf(r->pool, "SELECT %s, length(%s) FROM %s WHERE upper(%s)=upper(?)",
      m->osaPasswordField, m->osaPasswordField, m->osapwtable,
      m->osaNameField);
    }
  }

  sqlite3_stmt *stmt;
  int rc = sqlite3_prepare_v2(connection.handle, query, -1, &stmt, 0);    

  if (rc != SQLITE_OK) {
      sqlite3_finalize(stmt);
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
char ** get_groups(request_rec *r, char *user, osa_config_rec *m)
{
  char **list = NULL;
  char *query;
  char *sql_safe_user;
  int ulen;

  if(!open_db_handle(r,m)) {
    return NULL;		/* failure reason already logged */
  }

  ulen = strlen(user);
  sql_safe_user = PCALLOC(r->pool, ulen*2+1);
  //sqlite3_escape_string(sql_safe_user,user,ulen);
  strcpy(sql_safe_user, user);

  if (m->osaGroupUserNameField == NULL)
    m->osaGroupUserNameField = m->osaNameField;
  if (m->osaGroupCondition) {
    query=apr_psprintf(r->pool, "SELECT %s FROM %s WHERE upper(%s)=upper(?) AND %s",
    m->osaGroupField, m->osagrptable,
    m->osaGroupUserNameField, str_format(r, m->osaGroupCondition));
  } else {
    query=apr_psprintf(r->pool, "SELECT %s FROM %s WHERE upper(%s)=upper(?)",
    m->osaGroupField, m->osagrptable,
    m->osaGroupUserNameField);
  }

  sqlite3_stmt *stmt;
  int rc = sqlite3_prepare_v2(connection.handle, query, -1, &stmt, 0);    

  if (rc != SQLITE_OK) {
      sqlite3_finalize(stmt);
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
/* checkGenericQuotas( osa_config_rec *sec, request_rec *r,char *counterPrefix, char *quotaScope,   */
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
int checkGenericQuotas( osa_config_rec *sec, request_rec *r,char *counterPrefix, char *quotaScope, unsigned long maxReqSec, unsigned long maxReqDay, unsigned long maxReqMon, int httpStatusOver){

    char *query;
    char *counterSecName;
    char *counterDayName;
    char *counterMonName;
    unsigned long reqSec, reqDay, reqMon;
    sqlite3_stmt *stmt;
    int sqlite3_rc;





    /* get current time */
    time_t rawtime;
    struct tm * timeinfo;


    time ( &rawtime );
    timeinfo = localtime ( &rawtime );


    if (maxReqSec){
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
        query=apr_psprintf(r->pool, "DELETE FROM %s WHERE  %s!='%s' and %s like '%s$$$S%%'",sec->countersTable, sec->counterNameField, counterSecName, sec->counterNameField, counterPrefix);
        if (sqlite3_query_execute(connection.handle, query) != 0) {
            LOG_ERROR_1(APLOG_ERR, 0, r, "checkGenericQuotas.delete.old.per.second SQLite ERROR: %s: ", sqlite3_errmsg(connection.handle));
            return osa_error(r,"DB query error",500);
        }

        /*    2.1 retreive counter value for current "per second counter" */
        query=apr_psprintf(r->pool, "SELECT %s FROM %s WHERE %s='%s'", sec->counterValueField, sec->countersTable, sec->counterNameField, counterSecName);
        sqlite3_rc = sqlite3_prepare_v2(connection.handle, query, -1, &stmt, 0);    

        if (sqlite3_rc != SQLITE_OK) {
            sqlite3_finalize(stmt);
            LOG_ERROR_2(APLOG_ERR, 0, r, "get_sqlite3_pw.sqlite3_query SQLite ERROR: %s: %s", sqlite3_errmsg(connection.handle), r->uri);
            return osa_error(r,"DB query error", 500);;
        }    
        if (sqlite3_step(stmt) == SQLITE_ROW) {
            /*      2.1.1 counter was found, get current counter value */
            reqSec=strtol (sqlite3_column_text(stmt, 0),NULL,0);//atoi(data[0]);
        }else{
            /*      2.1.2 counter was not found, start from 0 and insert counter into DB */
            reqSec=0;
            query=apr_psprintf(r->pool, "INSERT INTO %s (%s,%s) VALUES ('%s',0)", sec->countersTable, sec->counterNameField, sec->counterValueField, counterSecName);
            if (sqlite3_query_execute(connection.handle, query) != 0) {
                sqlite3_finalize(stmt);
                LOG_ERROR_1(APLOG_ERR, 0, r, "checkGenericQuotas.insert.per.second SQLite ERROR: %s: ", sqlite3_errmsg(connection.handle));
                return osa_error(r,"DB query error", 500);
            }
        }
        sqlite3_finalize(stmt);

        /*    2.2 increment coutner (in DB too)*/
        query=apr_psprintf(r->pool, "UPDATE %s SET %s=%lu WHERE %s='%s'", sec->countersTable, sec->counterValueField, reqSec+1, sec->counterNameField, counterSecName);
        if (sqlite3_query_execute(connection.handle, query) != 0) {
            LOG_ERROR_1(APLOG_ERR, 0, r, "checkGenericQuotas.update.per.second SQLite ERROR: %s: ", sqlite3_errmsg(connection.handle));
            return osa_error(r,"DB query error",500);
        }
        /*    2.3 if new coutner value exceed quota, display error and stop */
        if (reqSec+1 > maxReqSec){
            char *err;
            err=apr_psprintf(r->pool, "Maximum number of request (%s %lu/%lu) per second allowed exedeed", quotaScope, reqSec+1, maxReqSec);

            return osa_error(r,err,httpStatusOver);
        }
    }



    if (maxReqDay){
        /* 3. Check per day quotas */
        /*      Create counter name from counterPrefix and current day value */
        counterDayName=apr_psprintf(r->pool, "%s$$$D=%d-%02d-%02d",counterPrefix,
                    timeinfo->tm_year+1900, 
                    timeinfo->tm_mon+1,
                    timeinfo->tm_mday);
        /*      delete previous counters */
        query=apr_psprintf(r->pool, "DELETE FROM %s WHERE  %s!='%s' and %s like '%s$$$D%%'",sec->countersTable, sec->counterNameField, counterDayName, sec->counterNameField, counterPrefix);
        if (sqlite3_query_execute(connection.handle, query) != 0) {
            LOG_ERROR_1(APLOG_ERR, 0, r, "checkGenericQuotas.delete.old.per.day SQLite ERROR: %s: ", sqlite3_errmsg(connection.handle));
            return osa_error(r,"DB query error", 500);
        }
        /*    3.1 retreive counter value for current "per day counter" */
        query=apr_psprintf(r->pool, "SELECT %s FROM %s WHERE %s='%s'", sec->counterValueField, sec->countersTable, sec->counterNameField, counterDayName);
        sqlite3_rc = sqlite3_prepare_v2(connection.handle, query, -1, &stmt, 0);    

        if (sqlite3_rc != SQLITE_OK) {
            sqlite3_finalize(stmt);
            LOG_ERROR_2(APLOG_ERR, 0, r, "get_sqlite3_pw.sqlite3_query SQLite ERROR: %s: %s", sqlite3_errmsg(connection.handle), r->uri);
            return osa_error(r,"DB query error", 500);;
        }    
        if (sqlite3_step(stmt) == SQLITE_ROW) {
            /*      3.1.1 counter was found, get current counter value */
            reqDay=strtol (sqlite3_column_text(stmt, 0),NULL,0);//atoi(data[0]);
        }else{
            /*      3.1.2 counter was not found, start from 0 and insert counter into DB */
            reqDay=0;
            query=apr_psprintf(r->pool, "INSERT INTO %s (%s,%s) VALUES ('%s',0)", sec->countersTable, sec->counterNameField, sec->counterValueField, counterDayName);
            if (sqlite3_query_execute(connection.handle, query) != 0) {
                    sqlite3_finalize(stmt);
                    LOG_ERROR_1(APLOG_ERR, 0, r, "checkGenericQuotas.insert.per.day SQLite ERROR: %s: ", sqlite3_errmsg(connection.handle));
                    return osa_error(r,"DB query error", 500);
            }
        }
        sqlite3_finalize(stmt);
        query=apr_psprintf(r->pool, "UPDATE %s SET %s=%lu WHERE %s='%s'", sec->countersTable, sec->counterValueField, reqDay+1, sec->counterNameField, counterDayName);
        if (sqlite3_query_execute(connection.handle, query) != 0) {
            LOG_ERROR_1(APLOG_ERR, 0, r, "checkGenericQuotas.update.per.day SQLite ERROR: %s: ", sqlite3_errmsg(connection.handle));
            return osa_error(r,"DB query error", 500);
        }
        /*    3.3 if new coutner value exceed quota, display error and stop */
        if (reqDay+1 > maxReqDay){
            char *err;
            err=apr_psprintf(r->pool, "Maximum number of request (%s %lu/%lu) per day allowed exedeed", quotaScope, reqDay+1, maxReqDay);

            return osa_error(r,err, httpStatusOver);
        }
    }

    if (maxReqMon){
        /* 4. Check per month quotas */
        /*      Create counter name from counterPrefix and current month value */
        counterMonName=apr_psprintf(r->pool, "%s$$$M=%d-%02d",counterPrefix,
                    timeinfo->tm_year+1900, 
                    timeinfo->tm_mon+1);
        /*      delete previous counters */
        query=apr_psprintf(r->pool, "DELETE FROM %s WHERE  %s!='%s' and %s like '%s$$$M%%'",sec->countersTable, sec->counterNameField, counterMonName, sec->counterNameField, counterPrefix);
        if (sqlite3_query_execute(connection.handle, query) != 0) {
            LOG_ERROR_1(APLOG_ERR, 0, r, "checkGenericQuotas.delete.old.per.month SQLite ERROR: %s: ", sqlite3_errmsg(connection.handle));
            return osa_error(r,"DB query error", 500);
        }
        /*    4.1 retreive counter value for current "per month counter" */
        query=apr_psprintf(r->pool, "SELECT %s FROM %s WHERE %s='%s'", sec->counterValueField, sec->countersTable, sec->counterNameField, counterMonName);
        sqlite3_rc = sqlite3_prepare_v2(connection.handle, query, -1, &stmt, 0);    

        if (sqlite3_rc != SQLITE_OK) {
            sqlite3_finalize(stmt);
            LOG_ERROR_2(APLOG_ERR, 0, r, "get_sqlite3_pw.sqlite3_query SQLite ERROR: %s: %s", sqlite3_errmsg(connection.handle), r->uri);
            return osa_error(r,"DB query error", 500);;
        }    
            if (sqlite3_step(stmt) == SQLITE_ROW) {
            reqMon=strtol (sqlite3_column_text(stmt, 0),NULL,0);//atoi(data[0]);
        }else{
            /*      4.1.2 counter was not found, start from 0 and insert counter into DB */
            reqMon=0;
            query=apr_psprintf(r->pool, "INSERT INTO %s (%s,%s) VALUES ('%s',0)", sec->countersTable, sec->counterNameField, sec->counterValueField, counterMonName);
            if (sqlite3_query_execute(connection.handle, query) != 0) {
                sqlite3_finalize(stmt);
                LOG_ERROR_1(APLOG_ERR, 0, r, "checkGenericQuotas.insert.per.month SQLite ERROR: %s: ", sqlite3_errmsg(connection.handle));
                return osa_error(r,"DB query error", 500);
            }
        }
        sqlite3_finalize(stmt);
        /*    4.2 increment coutner (in DB too) */
        query=apr_psprintf(r->pool, "UPDATE %s SET %s=%lu WHERE %s='%s'", sec->countersTable, sec->counterValueField, reqMon+1, sec->counterNameField, counterMonName);
        if (sqlite3_query_execute(connection.handle, query) != 0) {
            LOG_ERROR_1(APLOG_ERR, 0, r, "checkGenericQuotas.update.per.month SQLite ERROR: %s: ", sqlite3_errmsg(connection.handle));
            return osa_error(r,"DB query error", 500);
        }
        /*    4.3 if new coutner value exceed quota, display error and stop */
        if (reqMon+1 > maxReqMon){
            char *err;
            err=apr_psprintf(r->pool, "Maximum number of request (%s %lu/%lu) per month allowed exedeed", quotaScope, reqMon+1, maxReqMon);

            return osa_error(r,err, httpStatusOver);

        }
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
    sqlite3_stmt *stmt;
    int sqlite3_rc;
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

 	if (!read_quota_from_cache(r->server, r,  sec->resourceName, "", &reqSec, &reqMonth, &reqDay)){
    /* 2. retreive values form Maximum allowed for resource (sec/day/month) */

        query=apr_psprintf(r->pool, "SELECT %s, %s, %s FROM %s WHERE %s='%s'", sec->osaPerSecField, sec->osaPerDayField, sec->osaPerMonthField, sec->osaGlobalQuotasTable,  sec->osaResourceNameField, sec->resourceName);
        if (sec->osaGlobalQuotasCondition){
            query=apr_psprintf(r->pool, "%s AND %s", query, sec->osaGlobalQuotasCondition);
        }

        sqlite3_rc = sqlite3_prepare_v2(connection.handle, query, -1, &stmt, 0);    

        if (sqlite3_rc != SQLITE_OK) {
            /*    2.2 No quota definition was found in DB ==> ERROR */
            sqlite3_finalize(stmt);
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
		store_quota_cache(r, sec->resourceName, "", reqSec, reqDay, reqMonth, sec->quotasDefCacheTTL);
     }
    char *scope;

    /* 3.  Define "quotaScope" variable for checkGenericQuotas and call it */
    scope=apr_psprintf(r->pool, "global for resource %s",  sec->resourceName);



    int rc;

    P_db(sec, r, counterPrefix);
    rc=checkGenericQuotas(sec, r, counterPrefix, scope, reqSec, reqDay, reqMonth, 503);
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
  unsigned long reqSec=0;
  unsigned long reqDay=0;
  unsigned long reqMonth=0;
  sqlite3_stmt *stmt;
  int sqlite3_rc;

    

	if (connection.handle==NULL){
		/* connect database */
		if(!open_db_handle(r,sec)) {
			return osa_error(r,"Unable to connect database", 500);
		}
	}

  /* 1. create a counter prefix from quotas enabled resource resource name and username */
  counterPrefix=apr_psprintf(r->pool, "R=%s$$$U=%s",  sec->resourceName, r->user);

	if (!read_quota_from_cache(r->server, r, sec->resourceName, r->user, &reqSec, &reqDay, &reqMonth)){
    /* 2. retreive values form Maximum allowed for resource (sec/day/month) */
    query=apr_psprintf(r->pool, "SELECT %s, %s, %s FROM %s WHERE %s='%s' AND upper(%s)=upper('%s')", sec->osaPerSecField, sec->osaPerDayField, sec->osaPerMonthField, sec->osaUserQuotasTable,  sec->osaResourceNameField, sec->resourceName, sec->osaNameField, r->user);
    if (sec->osaUserQuotasCondition){
        /*    2.1 if configuration set a condition (sql) to retreive quotas, integrate it to request */
        query=apr_psprintf(r->pool, "%s AND %s", query, sec->osaUserQuotasCondition);
    }

    sqlite3_rc = sqlite3_prepare_v2(connection.handle, query, -1, &stmt, 0);    

    if (sqlite3_rc != SQLITE_OK) {
        /*    2.2 No quota definition was found in DB ==> ERROR */
        sqlite3_finalize(stmt);
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
        char *err;
        err=apr_psprintf(r->pool, "No quota defined for user %s with user quotas control is activated on resource %s",r->user, sec->resourceName); 
        sqlite3_finalize(stmt);
        return osa_error(r,err, 500);
    }
    sqlite3_finalize(stmt);
	store_quota_cache(r, sec->resourceName, r->user, reqSec, reqDay, reqMonth, sec->quotasDefCacheTTL);
  }
  /* 3.  Define "quotaScope" variable for checkGenericQuotas and call it */
  char *scope;
  scope=apr_psprintf(r->pool, "for user %s and resource %s", r->user, sec->resourceName);

  int rc;

  P_db(sec, r, "USER_QUOTAS");
  rc=checkGenericQuotas(sec, r, counterPrefix, scope, reqSec, reqDay, reqMonth, 429);
  V_db(sec, r, "USER_QUOTAS");

  return rc;

}

int cleanGeneratedTokens(request_rec *r){
	osa_config_rec *sec =(osa_config_rec *)ap_get_module_config (r->per_dir_config, &osa_module);
  char *query;

	if (connection.handle==NULL){
		/* connect database */
		if(!open_db_handle(r,sec)) {
			return osa_error(r,"Unable to connect database", 500);
		}
	}

	query=apr_psprintf(r->pool, "DELETE FROM %s WHERE %s<datetime(CURRENT_TIMESTAMP, 'localtime') OR %s=1",sec->cookieAuthTable, sec->cookieAuthValidityField, sec->cookieAuthBurnedField);
	if (sqlite3_query_execute(connection.handle, query) != 0) {
		LOG_ERROR_1(APLOG_ERR, 0, r, "cleanGeneratedTokens: SQLite ERROR: %s: ", sqlite3_errmsg(connection.handle));
		return osa_error(r,"DB query error",500);
	}
  return OK;
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


	query=apr_psprintf(r->pool, "UPDATE %s SET %s=DateTime(datetime(CURRENT_TIMESTAMP, 'localtime'), '+%d minute') WHERE %s='%s' and abs(CAST(strftime('%%s',  %s) as integer) - CAST(strftime('%%s', DateTime(datetime(CURRENT_TIMESTAMP, 'localtime'), '+%d minute')) as integer))>%d",
		sec->cookieAuthTable,
		sec->cookieAuthValidityField,
		sec->cookieAuthTTL, 
		sec->cookieAuthTokenField,
		receivedToken,
		sec->cookieAuthValidityField,
		sec->cookieAuthTTL, 
		sec->cookieCacheTime);

	LOG_ERROR_1(APLOG_DEBUG, 0, r, "extendToken: SQL->%s: ", query);
    
  if (sqlite3_query_execute(connection.handle, query) != 0) {
    LOG_ERROR_1(APLOG_ERR, 0, r, "extendToken: SQLite ERROR: %s: ", sqlite3_errmsg(connection.handle));
    return osa_error(r,"DB query error",500);
  }
    return OK;

}

int validateToken(request_rec *r , char *token, char **initialToken, int *require_new){
	osa_config_rec *sec =(osa_config_rec *)ap_get_module_config (r->per_dir_config, &osa_module);
	char *query;
	int rc=OK;

	query=apr_psprintf(r->pool, "SELECT %s, %s, %s, ((julianday(DateTime(datetime(CURRENT_TIMESTAMP, 'localtime'), '+%d minute')) - julianday(%s)) * 86400.0) FROM %s WHERE token='%s' AND %s>=datetime(CURRENT_TIMESTAMP, 'localtime')",
		sec->cookieAuthUsernameField, 
		sec->cookieAuthBurnedField,
    sec->cookieInitialAuthTokenField,
    sec->cookieAuthTTL,
    sec->cookieAuthValidityField,
    sec->cookieAuthTable,
		token,
		sec->cookieAuthValidityField);

  sqlite3_stmt *stmt;
  int sqlite3_rc = sqlite3_prepare_v2(connection.handle, query, -1, &stmt, 0);    

  if (sqlite3_rc != SQLITE_OK) {
    sqlite3_finalize(stmt);
    LOG_ERROR_1(APLOG_ERR, 0, r, "validateToken: SQLite ERROR: %s: ", sqlite3_errmsg(connection.handle));
    return osa_error(r,"DB query error",500);
  }

	int burnable;
  if (sqlite3_step(stmt) == SQLITE_ROW) {
    // r->user=(char*)PCALLOC(r->pool, strlen(sqlite3_column_text(stmt, 0)));
    // strcpy(r->user, sqlite3_column_text(stmt, 0));
    r->user=(char *) PSTRDUP(r->pool, sqlite3_column_text(stmt, 0));
    *require_new=!sqlite3_column_int(stmt, 1); 
    *initialToken =(char *) PSTRDUP(r->pool, sqlite3_column_text(stmt, 2));
    burnable=(sqlite3_column_int(stmt, 3) > sec->cookieCacheTime);
    if (sec->cookieAuthBurn){
      if (burnable){
      //Burn received token
      query=apr_psprintf(r->pool, "UPDATE %s SET %s=DateTime(datetime(CURRENT_TIMESTAMP, 'localtime'), '+%d second'), %s=1 WHERE %s='%s' and %s=0",
        sec->cookieAuthTable,
        sec->cookieAuthValidityField,
        sec->cookieCacheTime, 
        sec->cookieAuthBurnedField,
        sec->cookieAuthTokenField,
        token,
        sec->cookieAuthBurnedField);
        
        if (sqlite3_query_execute(connection.handle, query) != 0) {
          LOG_ERROR_1(APLOG_ERR, 0, r, "generateToken(update): SQLite ERROR: %s: ", sqlite3_errmsg(connection.handle));
          rc = osa_error(r,"DB query error",500);
        }
      }else{
        *require_new=0;
      }

    }
  }else{
    //received token was not found in DB
    if (sec->cookieAuthLoginForm!=NULL){
      //A login form is set up and we wre in the cookie auth schema,
      //Assume that this schema is the prefred one and continue with it
      
      rc = redirectToLoginForm(r, NULL);
    }else if (sec->basicAuthEnable){
      deleteAuthCookie(r);
      rc = DECLINED;
    }else{
      rc = osa_error(r,"Invalid or outdated token", 401);
    }
  }
  sqlite3_finalize(stmt);
  LOG_ERROR_1(APLOG_ERR, 0, r, "exiting validateToken %s", token);

  return rc;
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

    query=apr_psprintf(r->pool, "INSERT INTO %s (%s, %s, %s, %s, %s) VALUES ('%s',DateTime(datetime(CURRENT_TIMESTAMP, 'localtime'), '+%d minute'), '%s', 0, '%s')", 
      sec->cookieAuthTable,
      sec->cookieAuthTokenField,
      sec->cookieAuthValidityField,
      sec->cookieAuthUsernameField,
			sec->cookieAuthBurnedField,
      sec->cookieInitialAuthTokenField,
      token, sec->cookieAuthTTL, 
      r->user,
      initialToken);


      if (sqlite3_query_execute(connection.handle, query) != 0) {
        if (strstr(sqlite3_errmsg(connection.handle),"Duplicate entry") == NULL){
          LOG_ERROR_2(APLOG_ERR, 0, r, "generateToken: SQLite ERROR: %s->%s", sqlite3_errmsg(connection.handle), query);
          return osa_error(r,"DB query error",500);
        }else{
          LOG_ERROR_1(APLOG_ERR, 0, r, "%s", "Generated token already exists: retry");
        }	
      }else{
        done=1;
      }
  }while (!done);

  //Burn received token
  query=apr_psprintf(r->pool, "UPDATE %s SET %s=DateTime(datetime(CURRENT_TIMESTAMP, 'localtime'), '+%d second'), %s=1 WHERE %s='%s' and %s=0",
    sec->cookieAuthTable,
    sec->cookieAuthValidityField,
    sec->cookieCacheTime, 
    sec->cookieAuthBurnedField,
    sec->cookieAuthTokenField,
    receivedToken,
    sec->cookieAuthBurnedField);
    
  if (sqlite3_query_execute(connection.handle, query) != 0) {
    LOG_ERROR_1(APLOG_ERR, 0, r, "generateToken(update): SQLite ERROR: %s: ", sqlite3_errmsg(connection.handle));
    return osa_error(r,"DB query error",500);
  }

  query=apr_psprintf(r->pool, "%s=%s; path=/",sec->cookieAuthName,token);
  if (sec->cookieAuthDomain != NULL){
    char *domain;
    domain=apr_psprintf(r->pool, "; domain=%s",  sec->cookieAuthDomain);
    query=apr_psprintf(r->pool, "%s%s", query, domain);
  }
  apr_table_set(r->headers_out, "Set-Cookie", query);

  return Rc;
}








int register_hit(request_rec *r)
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


		char *usr;
		if (r->user == NULL){
			usr=apr_psprintf(r->pool, "%s", ""); 
		}else{
      usr=apr_psprintf(r->pool, "%s", r->user);
		}
		char *msg="";
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
		if (sqlite3_query_execute(connection.handle, query) != 0) {
		        LOG_ERROR_1(APLOG_ERR, 0, r, "sqlite3_register_hit: SQLite ERROR: %s: ", sqlite3_errmsg(connection.handle));
		}
	}
	return OK;
}

int get_user_basic_attributes(request_rec *r, char *fields, stringKeyValList *headersMappingList){

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
  query=apr_psprintf(r->pool, "SELECT %s FROM %s WHERE upper(%s)=upper(?)",
        fields,  sec->osapwtable, sec->osaNameField);
  if (sec->osaUserCondition && strlen(sec->osaUserCondition)){
    query=apr_psprintf(r->pool,"%s AND %s", query, str_format(r, sec->osaUserCondition));
  }

  sqlite3_stmt *stmt;
  int sqlite3_rc = sqlite3_prepare_v2(connection.handle, query, -1, &stmt, 0);    

  if (sqlite3_rc != SQLITE_OK) {
      sqlite3_finalize(stmt);
      LOG_ERROR_1(APLOG_ERR, 0, r, "sqlite3_forward_identity: SQLite ERROR: %s: ", sqlite3_errmsg(connection.handle));
      return osa_error(r,"DB query error",500);
  }
  sqlite3_bind_text(stmt, 1, r->user, strlen(r->user), 0);    
  
  if (sqlite3_step(stmt) == SQLITE_ROW) {
    int i;
    for (i=0;i<headersMappingList->listCount;i++){
      
      if (sqlite3_column_text(stmt, i)){
        headersMappingList->list[i].val=PSTRDUP(r->pool, sqlite3_column_text(stmt, i));
      }else{
        headersMappingList->list[i].val=PSTRDUP(r->pool, "");
      }
      
              
    }
  }else{
    LOG_ERROR_1(APLOG_ERR, 0, r, "User %s not found in DB", r->user);
    
  }
  sqlite3_finalize(stmt);
  return OK;
}


int get_user_extended_attributes(request_rec *r, stringKeyValList *props){
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

	query=apr_psprintf(r->pool, "SELECT %s, %s FROM %s WHERE upper(%s)=upper(?)", sec->userAttributeNameField, sec->userAttributeValueField, sec->userAttributesTable, sec->osaNameField);
  sqlite3_stmt *stmt;
  int sqlite3_rc = sqlite3_prepare_v2(connection.handle, query, -1, &stmt, 0);    

  if (sqlite3_rc != SQLITE_OK) {
      sqlite3_finalize(stmt);
      LOG_ERROR_1(APLOG_ERR, 0, r, "sqlite3_forward_identity: SQLite ERROR: %s: ", sqlite3_errmsg(connection.handle));
      return osa_error(r,"DB query error",500);
  }
  sqlite3_bind_text(stmt, 1, r->user, strlen(r->user), 0);    
	
  
  
  while (sqlite3_step(stmt) == SQLITE_ROW){ 
		props->list[props->listCount].key=PSTRDUP(r->pool, sqlite3_column_text(stmt, 0));
		props->list[props->listCount].val=PSTRDUP(r->pool, sqlite3_column_text(stmt, 1));
		(props->listCount)++;
	}
	sqlite3_finalize(stmt);
  return OK;

}






module AP_MODULE_DECLARE_DATA osa_module =
{
	STANDARD20_MODULE_STUFF,
	create_osa_dir_config,    /* dir config creater */
	NULL,                     /* dir merger --- default is to override */
	create_osa_server_config, /* server config */
	NULL,                     /* merge server config */
	osa_cmds,                 /* command apr_table_t */
	register_hooks            /* register hooks */
};
