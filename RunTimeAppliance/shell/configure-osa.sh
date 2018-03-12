#!/bin/bash
##--------------------------------------------------------
 # Module Name : ApplianceManager
 # Version : 1.0.0
 #
 # Software Name : OpenServicesAccess
 # Version : 1.0
 #
 # Copyright (c) 2011 – 2014 Orange
 # This software is distributed under the Apache 2 license
 # <http://www.apache.org/licenses/LICENSE-2.0.html>
 #
 #--------------------------------------------------------
 # File Name   : ApplianceManager/RunTimeAppliance/shell/configure-osa.sh
 #
 # Created     : 2012-02
 # Authors     : Benoit HERARD <benoit.herard(at)orange.com>
 #
 # Description :
 #      .../...
 #--------------------------------------------------------
 # History     :
 # 1.0.0 - 2012-10-01 : Release of the file
##

#!/bin/bash
unset http_proxy
ABSOLUTE_URI=1


function randomString(){

index=0

str=""

for i in {a..z}; do arr[index]=$i; index=`expr ${index} + 1`; done

for i in {A..Z}; do arr[index]=$i; index=`expr ${index} + 1`; done

for i in {0..9}; do arr[index]=$i; index=`expr ${index} + 1`; done

for i in {1..64}; do str="$str${arr[$RANDOM%$index]}"; done

echo $str

}



function configureCryptoKey(){
	if [  $KEEP_DB -eq 0 -o ! -f "$INSTALL_DIR/ApplianceManager.php/include/Crypto.ini.php" ] ; then
		cryptoKeys='Array("'`randomString`'","'`randomString`'","'`randomString`'","'`randomString`'","'`randomString`'","'`randomString`'","'`randomString`'","'`randomString`'","'`randomString`'","'`randomString`'");';
	
		echo '<?php'>/tmp/Crypto.ini.php
		echo "/* Array of cryting keys for custome bi-directionel custom crypting system */" >>/tmp/Crypto.ini.php
		echo "/* To enforce security on your system, you shoud change thos values and  generate random keys on install*/" >>/tmp/Crypto.ini.php
		echo '$cryptKey='$cryptoKeys >>/tmp/Crypto.ini.php
		echo '?>'>>/tmp/Crypto.ini.php

		mv /tmp/Crypto.ini.php $INSTALL_DIR/ApplianceManager.php/include/Crypto.ini.php
		

	 fi  
	 
}

function configureFileSystemPrivileges(){
	#Protect database creds in include for endpoint (ReverseProxies)
	chown $APACHE_USER:$APACHE_GROUP $INSTALL_DIR/RunTimeAppliance/apache/conf/vhAppliance/osa-endpoints-settings.inc
	chown -h $APACHE_USER:$APACHE_GROUP    /etc/ApplianceManager/osa-endpoints-settings.inc

	chmod 500 $INSTALL_DIR/RunTimeAppliance/apache/conf/vhAppliance/osa-endpoints-settings.inc

	#Protect creds for PHP app
	chown $APACHE_USER:$APACHE_GROUP $INSTALL_DIR/ApplianceManager.php/include/Settings.ini.php
	chown -h $APACHE_USER:$APACHE_GROUP /etc/ApplianceManager/Settings.ini.php
	chmod 500 $INSTALL_DIR/ApplianceManager.php/include/Settings.ini.php
	
	#Protect ws usage creds 
	chown root:root $INSTALL_DIR/RunTimeAppliance/shell/doAppliance.sh
	chmod 700 $INSTALL_DIR/RunTimeAppliance/shell/doAppliance.sh
	chown root:root $INSTALL_DIR/RunTimeAppliance/shell/backupConf.sh
	chmod 700 $INSTALL_DIR/RunTimeAppliance/shell/backupConf.sh
	
	#Prevents form too talkative logs
	chown -R $APACHE_USER:$APACHE_GROUP $LOG_DIR
	chmod 700 $LOG_DIR	
	
	#Prevents from too talkative logs apache logs
	mkdir -p $APAHCE_LOG_DIR/local
	chown -R $APACHE_USER:$APACHE_GROUP $LOG_DIR/local
	chmod 700 $LOG_DIR/local
	mkdir -p $LOG_DIR/admin
	chown -R $APACHE_USER:$APACHE_GROUP $LOG_DIR/admin
	chmod 700 $LOG_DIR/admin

	#Protect code
	chown -R $APACHE_USER:$APACHE_GROUP $INSTALL_DIR/ApplianceManager.php
	chmod a-w $INSTALL_DIR
	chmod o-r $INSTALL_DIR
	chmod g-r $INSTALL_DIR
	
	chown -R $APACHE_USER:$APACHE_GROUP /var/www/local
	chmod 700 /var/www/local/main
	chmod 000 /var/www/local/empty


}

######################################################################
# changeProperty
######################################################################
# Change the value of a particular propertie in a properti file
######################################################################
function changeProperty(){
	PROP=`echo $2| sed 's/\./\\\./g'`
	PROP_VALUE=`echo $3| sed 's/\./\\\./g'`
	
	egrep ".*$PROP.*=.*" $1 > /dev/null
	if [ $? -eq 0 ] ; then
		cat $1 | sed "s|^\(.*\)$PROP=.*|\1$PROP=$PROP_VALUE|g" > /tmp/$$.tmp
		:>$1
		cat /tmp/$$.tmp > $1
	fi
}



######################################################################
# configureEtc
######################################################################
# Create /etc/ApplianceManager
######################################################################
function configureEtc(){
	mkdir /etc/ApplianceManager
	cd /etc/ApplianceManager
	
	#PHP Settings
	[ -f Settings.ini.php ] &&  rm Settings.ini.php
	ln -s $INSTALL_DIR/ApplianceManager.php/include/Settings.ini.php Settings.ini.php
	#Apache Module Settings
	[ -f osa-endpoints-settings.inc ] && rm osa-endpoints-settings.inc
	ln -s $INSTALL_DIR/RunTimeAppliance/apache/conf/vhAppliance/osa-endpoints-settings.inc.$RDBMS osa-endpoints-settings.inc
	
	#Template for apache config generation (virtualHosts/node, Location/endpoints)
	[ -f https_virtualhost_template.php ] &&  rm https_virtualhost_template.php
	ln -s $INSTALL_DIR/ApplianceManager.php/resources/apache.conf/https_virtualhost_template.php https_virtualhost_template.php
	
	[ -f http_virtualhost_template.php ] &&  rm http_virtualhost_template.php
	ln -s $INSTALL_DIR/ApplianceManager.php/resources/apache.conf/http_virtualhost_template.php http_virtualhost_template.php
	
	[ -f endpoint_template.php ] &&  rm endpoint_template.php
	ln -s $INSTALL_DIR/ApplianceManager.php/resources/apache.conf/endpoint_template.php endpoint_template.php
	
}



addLogRotateForApache(){
	cat <<EOF >> /tmp/$$.logrotate.conf
$LOG_DIR/$1/main.access.log  {
    rotate 5
    daily
    postrotate
		touch  $LOG_DIR/$1/main.access.log
		chown  $APACHE_USER:$APACHE_GROUP /var/log/OSA/HTTP/main.access.log
    endscript
}
$LOG_DIR/$1/main.error.log  {
    rotate 5
    daily
    postrotate
		touch  $LOG_DIR/$1/main.access.log
		chown  $APACHE_USER:$APACHE_GROUP /var/log/OSA/HTTP/main.error.log
    endscript
}
$LOG_DIR/$1/rewrite.log  {
    rotate 5
    daily
    postrotate
		touch  $LOG_DIR/$1/main.access.log
		chown  $APACHE_USER:$APACHE_GROUP /var/log/OSA/HTTP/rewrite.log
    endscript
}
EOF
}
######################################################################
# configureCron
######################################################################
# Contfigure cron jobs (logrotate)
######################################################################
function configureCron(){
	crontab -l | grep -v '/RunTimeAppliance/shell/logrotate.conf' | grep -v "/RunTimeAppliance/shell/purgeHits.sh" > /tmp/$$.crontab
	echo '0 0 * * * /usr/sbin/logrotate '$INSTALL_DIR'/RunTimeAppliance/shell/logrotate.conf > '$LOG_DIR'/cron-logrotate.log 2>&1  ; '$APACHE_INITD_FILE' reload >> '$LOG_DIR'/cron-logrotate.log 2>&1' >>/tmp/$$.crontab
 	echo '0 0 * * * '$INSTALL_DIR'/RunTimeAppliance/shell/purgeHits.sh "'$APPLIANCE_MYSQL_HOST'"  "'$APPLIANCE_MYSQL_PORT'" "'$APPLIANCE_MYSQL_USER'" "'$APPLIANCE_MYSQL_PW'" "'$APPLIANCE_MYSQL_SCHEMA'" 15' >>/tmp/$$.crontab
	crontab /tmp/$$.crontab

	cat <<EOF >/tmp/$$.logrotate.conf
##Common section START
compress

$LOG_DIR/*.log  {
    rotate 5
    daily
    postrotate
        touch $LOG_DIR/doAppliance.log
        chown $APACHE_USER:$APACHE_GROUP $LOG_DIR/doAppliance.log
    endscript
}
EOF
addLogRotateForApache local
addLogRotateForApache admin
echo "##Common section END" >>/tmp/$$.logrotate.conf
echo "##Nodes section START" >>/tmp/$$.logrotate.conf

mv /tmp/$$.logrotate.conf $INSTALL_DIR/RunTimeAppliance/shell/logrotate.conf
}


######################################################################
# updateAdminService
######################################################################
# Update the admin service to be sure to have it in proper conf
######################################################################
function updateAdminService(){
	echo "Setting up admin service"
	curl -H "Accept: application/json"  "http://127.0.0.1:$PRIVATE_VHOST_PORT/ApplianceManager/services/ApplianceManagerAdmin"|grep -v '"uri"' | grep -v '"groupUri"'| sed 's/"additionalConfiguration":.*/"additionalConfiguration": "RequestHeader set Public-Root-URI \\"%{publicServerProtocol}e%{publicServerName}e%{frontEndEndPoint}e\\"",/'>/tmp/$$.putdata

	#curl -i -X PUT -H "Content-Type: application/json" -d @"/tmp/x2" localhost:82/ApplianceManager/services/ApplianceManagerAdmin
	curl -i -X PUT -H "Content-Type: application/json" -d @"/tmp/$$.putdata" "http://127.0.0.1:$PRIVATE_VHOST_PORT/ApplianceManager/services/ApplianceManagerAdmin" >/tmp/$$.curl
 grep " 200 OK" /tmp/$$.curl>/dev/null
if [ $? -ne 0 ] ; then
	cat /tmp/$$.curl
	echo ""
	echo ""
	echo ""
	echo "                 *****************************************"
	echo ""
	echo "Something has failed in this configuration.... check ouput for details...."
	echo "OSA Configuration IS NOT done, exiting..."
	echo ""
	echo "                 *****************************************"
	echo ""
	echo ""
	shellExit 1
fi


}	
######################################################################
# updateAdminUser
######################################################################
# Update the default application user (Admin) to set it a customized password
######################################################################
function updateAdminUser(){
	
	echo "Setting password for Admin user"
	cat <<EOF >/tmp/$$.putdata
password=$APPLIANCE_ADMIN_PW&email=$APACHE_ADMIN_MAIL&endDate=2100-12-31T23:59:59Z&firstName=Administrator&lastName=&entity=
EOF

curl -i -X PUT -H "Content-Type: application/x-www-form-urlencoded; charset=UTF-8"  -d @"/tmp/$$.putdata" http://127.0.0.1:$PRIVATE_VHOST_PORT/ApplianceManager/users/Admin >/tmp/$$.curl
 grep " 200 OK" /tmp/$$.curl
if [ $? -ne 0 ] ; then
	cat /tmp/$$.curl
	echo ""
	echo ""
	echo ""
	echo "                 *****************************************"
	echo ""
	echo "Something has failed in this configuration.... check ouput for details...."
	echo "OSA Configuration IS NOT done, exiting..."
	echo ""
	echo "                 *****************************************"
	echo ""
	echo ""
	shellExit 1
fi


}	



######################################################################
# configureSudoers
######################################################################
# configure the /ec/sudoers file to allow GUI/WS to generate 
# reverse proxification rules with shell script
######################################################################
function configureSudoers(){
	
	[ -f /etc/sudoers.d/ApplianceManager ] && rm /etc/sudoers.d/ApplianceManager
	
	cat <<EOF >> /etc/sudoers.d/ApplianceManager
#Nursery open source Appliance
Defaults:www-data    !requiretty
Cmnd_Alias      NURSERY_APPLIANCE_CMDS=$INSTALL_DIR/RunTimeAppliance/shell/doAppliance.sh
Cmnd_Alias      NURSERY_VH_APPLIANCE_CMDS=$INSTALL_DIR/RunTimeAppliance/shell/doVHAppliance.sh
Cmnd_Alias      NURSERY_VH_APPLIANCE_ENAB_DISAB_CMDS=$INSTALL_DIR/RunTimeAppliance/shell/enableDisableNode.sh
User_Alias      NURSERY_APPLIANCE_USERS=www-data  NURSERY_APPLIANCE_USERS       ALL = NOPASSWD: NURSERY_APPLIANCE_CMDS, NURSERY_VH_APPLIANCE_CMDS, NURSERY_VH_APPLIANCE_ENAB_DISAB_CMDS
#End of Nursery open source Appliance
EOF
	chmod 0440 /etc/sudoers.d/ApplianceManager
}

function shellExit(){
	deleteTempFiles
	exit $1
}

######################################################################
# configureShellScripts
######################################################################
# configure the shell script used by Admin GUI to generate reverse
# proxification rules
######################################################################
function configureShellScripts(){
	#End point generator
	changeProperty $INSTALL_DIR/RunTimeAppliance/shell/backupConf.sh APPLIANCE_INSTALL_DIR $INSTALL_DIR
	changeProperty $INSTALL_DIR/RunTimeAppliance/shell/doAppliance.sh APPLIANCE_INSTALL_DIR $INSTALL_DIR
	changeProperty $INSTALL_DIR/RunTimeAppliance/shell/doAppliance.sh APPLIANCE_LOCAL_USER '""'
	changeProperty $INSTALL_DIR/RunTimeAppliance/shell/doAppliance.sh APPLIANCE_LOCAL_PWD '""'
	changeProperty $INSTALL_DIR/RunTimeAppliance/shell/doAppliance.sh APPLIANCE_LOCAL_SERVER '"http://127.0.0.1:'$PRIVATE_VHOST_PORT'"'
	changeProperty $INSTALL_DIR/RunTimeAppliance/shell/doAppliance.sh HTTP_FQDN '"'$HTTP_VHOST_NAME'"'
	changeProperty $INSTALL_DIR/RunTimeAppliance/shell/doAppliance.sh HTTPS_FQDN '"'$HTTP_VHOST_NAME'"'
	changeProperty $INSTALL_DIR/RunTimeAppliance/shell/doAppliance.sh USE_HTTP $USE_HTTP
	changeProperty $INSTALL_DIR/RunTimeAppliance/shell/doAppliance.sh USE_HTTPS $USE_HTTPS
	changeProperty $INSTALL_DIR/RunTimeAppliance/shell/doAppliance.sh APPLIANCE_LOG_DIR $LOG_DIR

	#VirtualHost generator
	changeProperty $INSTALL_DIR/RunTimeAppliance/shell/doVHAppliance.sh APPLIANCE_INSTALL_DIR $INSTALL_DIR
	changeProperty $INSTALL_DIR/RunTimeAppliance/shell/doVHAppliance.sh APPLIANCE_LOG_DIR $LOG_DIR
	changeProperty $INSTALL_DIR/RunTimeAppliance/shell/doVHAppliance.sh APPLIANCE_LOCAL_SERVER '"http://127.0.0.1:'$PRIVATE_VHOST_PORT'"'
	changeProperty $INSTALL_DIR/RunTimeAppliance/shell/doVHAppliance.sh APPLIANCE_LOCAL_USER '""'
	changeProperty $INSTALL_DIR/RunTimeAppliance/shell/doVHAppliance.sh APPLIANCE_LOCAL_PWD '""'

	#VirtualHost enabler/disabler
	changeProperty $INSTALL_DIR/RunTimeAppliance/shell/enableDisableNode.sh APPLIANCE_LOG_DIR $LOG_DIR
		

	
	chmod 700 $INSTALL_DIR/RunTimeAppliance/shell/doAppliance.sh
	chmod 700 $INSTALL_DIR/RunTimeAppliance/shell/doVHAppliance.sh
	chmod 700 $INSTALL_DIR/RunTimeAppliance/shell/enableDisableNode.sh
	
	
}


######################################################################
# configureMySQLSettings
######################################################################
# configure settings for mysql server
######################################################################
function configureMySQLSettings(){
	if [ $DB_IS_LOCAL -eq 1 ] ; then
		PRM_CHANGED=0
		
		cat $MYSQL_CONF_FILE |grep "innodb_lock_wait_timeout=120"
		LOCK_WAIT_TIMEOUT=`echo $?`

		if [ $LOCK_WAIT_TIMEOUT -ne 0 ] ; then
			PRM_CHANGED=1
			cat $MYSQL_CONF_FILE|grep -v innodb_lock_wait_timeout   > /tmp/$$.my.cnf
			
			
			
			SECTION_START=`cat   /tmp/$$.my.cnf| grep -n '\[mysqld\]'| awk -F: '{print $1}'`
			FILE_LEN=`cat /tmp/$$.my.cnf| wc -l`
			END_SIZE=`expr $FILE_LEN - $SECTION_START`
			
			
			
			head -$SECTION_START /tmp/$$.my.cnf >/tmp/$$.my.cnf.1
			echo "innodb_lock_wait_timeout=120" >> /tmp/$$.my.cnf.1
			tail -$END_SIZE /tmp/$$.my.cnf>>/tmp/$$.my.cnf.1
			:>$MYSQL_CONF_FILE
			cat /tmp/$$.my.cnf.1 >> $MYSQL_CONF_FILE
		fi	
		if [ ! -z "$MYSQL_BIND_ADDRESS" ] ; then
			echo "Testing current network binding for mysql"
			grep "bind-address" /etc/mysql/my.cnf | grep "$APPLIANCE_MYSQL_HOST"
			if [ $? -ne 0 ] ; then
				echo "Changing network binding for mysql to $APPLIANCE_MYSQL_HOST"
				changeProperty $MYSQL_CONF_FILE "bind-address" "localhost"
				PRM_CHANGED=1
			fi
		fi 
		
		
		if [ $PRM_CHANGED -eq 1 ] ; then
			$MYSQL_INITD_FILE restart
		fi
	fi
	
}
######################################################################
# configureSqliteSettings
######################################################################
# configure SqLite settings (DB file)
######################################################################
function configureSqliteSettings(){
	sed -i "s|\(OSASqliteFilename \).*|\1$INSTALL_DIR/sql/sqlite/osa.db|" $INSTALL_DIR/RunTimeAppliance/apache/conf/vhAppliance/osa-endpoints-settings.inc.$RDBMS
	sed -i 's|.*"RDBMS".*|	define("RDBMS", "'$RDBMS'");|' $INSTALL_DIR/ApplianceManager.php/include/Settings.ini.php
	sed -i 's|.*"SQLITE_DATABASE_PATH".*|	define("SQLITE_DATABASE_PATH", "'$INSTALL_DIR'/sql/sqlite/osa.db");|' $INSTALL_DIR/ApplianceManager.php/include/Settings.ini.php
}
######################################################################
# configureMySQLCreds
######################################################################
# configure application (module, web app) with proper mysql creds
######################################################################
function configureMySQLCreds(){
	sed -i 's|.*"RDBMS".*|	define("RDBMS", "$RDBMS");|' $INSTALL_DIR/ApplianceManager.php/include/Settings.ini.php
	changeProperty $INSTALL_DIR/ApplianceManager.php/include/Settings.ini.php BDPwd '"'$APPLIANCE_MYSQL_PW'";'
	changeProperty $INSTALL_DIR/ApplianceManager.php/include/Settings.ini.php BDUser '"'$APPLIANCE_MYSQL_USER'";'
	changeProperty $INSTALL_DIR/ApplianceManager.php/include/Settings.ini.php BDName '"'$APPLIANCE_MYSQL_SCHEMA'@'$APPLIANCE_MYSQL_HOST':'$APPLIANCE_MYSQL_PORT'";'

	cat $INSTALL_DIR/RunTimeAppliance/apache/conf/vhAppliance/osa-endpoints-settings.inc.$RDBMS | sed "s/\(OSAPort \).*/\1$APPLIANCE_MYSQL_PORT/g"| sed "s/\(OSAHost \).*/\1$APPLIANCE_MYSQL_HOST/g" | sed "s/\(OSAPassword \).*/\1$APPLIANCE_MYSQL_PW/g"| sed "s/\(OSADB \).*/\1$APPLIANCE_MYSQL_SCHEMA/g"| sed "s/\(OSAUser \).*/\1$APPLIANCE_MYSQL_USER/g" > /tmp/$$.osa-endpoints-settings.inc
	:>$INSTALL_DIR/RunTimeAppliance/apache/conf/vhAppliance/osa-endpoints-settings.inc.$RDBMS
	cat /tmp/$$.osa-endpoints-settings.inc >> $INSTALL_DIR/RunTimeAppliance/apache/conf/vhAppliance/osa-endpoints-settings.inc.$RDBMS
}
######################################################################
# configurePathAndSettings
######################################################################
# configure application according to INSTALL_DIR
######################################################################
function configurePathAndSettings(){
	cat $INSTALL_DIR/ApplianceManager.php/include/Settings.ini.php  \
		| sed 's|.*"runtimeApplianceConfigLocation".*|	define("runtimeApplianceConfigLocation",  "'$INSTALL_DIR'/RunTimeAppliance/apache/conf/vhAppliance");|' \
		| sed 's|.*"runtimeApplianceConfigScript".*|	define("runtimeApplianceConfigScript", "'$INSTALL_DIR'/RunTimeAppliance/shell/doAppliance.sh");|' \
		| sed 's|.*"runtimeApplianceConfigScriptLogFile".*|	define("runtimeApplianceConfigScriptLogFile", "'$LOG_DIR'/doAppliance.log");|' \
		| sed 's|.*"runtimeApplianceEnableDisableVirtulaHostScript".*|	define("runtimeApplianceEnableDisableVirtulaHostScript", "'$INSTALL_DIR'/RunTimeAppliance/shell/enableDisableNode.sh");|' \
		| sed 's|.*"runtimeApplianceEnableDisableVirtulaHostLogFile".*|	define("runtimeApplianceEnableDisableVirtulaHostLogFile", "'$LOG_DIR'/enabDisabVH.log");|' \
		| sed 's|.*"runtimeApplianceVirtualHostsConfigScript".*|	define("runtimeApplianceVirtualHostsConfigScript", "'$INSTALL_DIR'/RunTimeAppliance/shell/doVHAppliance.sh");|' \
		| sed 's|.*"runtimeApplianceVirtualHostsConfigScriptLogFile".*|	define("runtimeApplianceVirtualHostsConfigScriptLogFile", "'$LOG_DIR'/doVHAppliance.log");|' \
		| sed 's|.*"runtimeApplianceConfigScriptLogDir".*|	define("runtimeApplianceConfigScriptLogDir", "'$LOG_DIR'");|' \
		| sed 's|.*"osaAdminUri".*|	define("osaAdminUri", "https://localhost:'$HTTPS_ADMIN_VHOST_PORT'/");|' \
		 > /tmp/$$.Settings.ini.php
	
	:>$INSTALL_DIR/ApplianceManager.php/include/Settings.ini.php
	cat /tmp/$$.Settings.ini.php >> $INSTALL_DIR/ApplianceManager.php/include/Settings.ini.php
	
	
	
	cat $INSTALL_DIR/RunTimeAppliance/apache/conf/vhAppliance/osa-endpoints-settings.inc.$RDBMS \
		| sed "s/\(OSAPassword \).*/\1$APPLIANCE_MYSQL_PW/g" \
		> /tmp/$$.osa-endpoints-settings.inc
	
	:>$INSTALL_DIR/RunTimeAppliance/apache/conf/vhAppliance/osa-endpoints-settings.inc.$RDBMS
	cat /tmp/$$.osa-endpoints-settings.inc >> $INSTALL_DIR/RunTimeAppliance/apache/conf/vhAppliance/osa-endpoints-settings.inc
	
	
	curPath=`cat $INSTALL_DIR/RunTimeAppliance/apache/conf/vhAppliance/applianceManagerRewriting.conf | grep "<Directory" | awk -F\" '{print $2}' | sed 's|\(.*\)/ApplianceManager.php|\1|'`
	cat $INSTALL_DIR/RunTimeAppliance/apache/conf/vhAppliance/applianceManagerRewriting.conf \
		| sed "s|$curPath|$INSTALL_DIR|g" \
		> /tmp/$$.rewriting
	
		
	:> $INSTALL_DIR/RunTimeAppliance/apache/conf/vhAppliance/applianceManagerRewriting.conf
	cat /tmp/$$.rewriting>>$INSTALL_DIR/RunTimeAppliance/apache/conf/vhAppliance/applianceManagerRewriting.conf
	
	
	
	
	#cat $INSTALL_DIR/RunTimeAppliance/shell/doAppliance.sh | \ 
	#	sed "s|APPLIANCE_CONFIG_LOC=.*|APPLIANCE_CONFIG_LOC=$INSTALL_DIR/RunTimeAppliance/apache/conf/vhAppliance|" \
	#	> /tmp/$$.doAppliance.sh
	#	
	#:>  $INSTALL_DIR/RunTimeAppliance/shell/doAppliance.sh
	#cat /tmp/$$.doAppliance.sh >>$INSTALL_DIR/RunTimeAppliance/shell/doAppliance.sh
}


######################################################################
# ensureModuleIsAvailableRedhat
######################################################################
# Ensure that a particular module is available and loaded.
# if available but not loaded: add it to apache conf
# if not available fails
######################################################################
function ensureModuleIsAvailableRedhat(){
	if [ -f /etc/httpd/modules/mod_$1.so ] ; then
		egrep "^LoadModule $1_module modules/mod_$1.so" /etc/httpd/conf/httpd.conf>/dev/null
		if [ $? -ne 0 ] ; then
			echo "LoadModule $1_module modules/mod_$1.so" >>/etc/httpd/conf.d/osa-0-modules.conf
		fi
	else
		echo "Apache module $1 is not available...."
		shellExit 30
	fi
	
}


######################################################################
# ensureModuleIsAvailableDebian
######################################################################
# Ensure that a particular module is available and loaded.
# if available but not loaded: add it to apache conf
# if not available fails
######################################################################
function ensureModuleIsAvailableDebian(){
	if [ -f /etc/apache2/mods-available/$1.load ] ; then
		[ ! -f /etc/apache2/mods-enabled/$1.load ] && a2enmod $1
	else
		echo "Apache module $1 is nopt available...."
		shellExit 30
	fi
	
}

######################################################################
# createApacheConf
######################################################################
# Create apache vitual hosts for
#     - local usage (127.0.0.1: core app not protected)
#     - admin, using https
#     - api publishing with https (is enabled)
#     - api publishing with http (is enabled)
######################################################################
function createApacheConf(){


	#Disable all existing sites
	#cd  /etc/apache2/sites-enabled/
	#for s in `ls` ; do
	#a2dissite $s
	#done

#Ensure that required module are available and enabled
	$APACHE_LOAD_MOD ssl
	$APACHE_LOAD_MOD headers
	$APACHE_LOAD_MOD proxy
	$APACHE_LOAD_MOD proxy_http
	$APACHE_LOAD_MOD osa
	$APACHE_LOAD_MOD rewrite
	$APACHE_LOAD_MOD proxy_wstunnel

#Configure ports.conf
	:>$APACHE_LISTEN_PORTS
	grep -v "Listen 127.0.0.1:$PRIVATE_VHOST_PORT" $APACHE_LISTEN_PORTS > /tmp/$$.ports.conf.1
	
	LADDR=`echo "$HTTPS_ADMIN_VHOST_ADDR"| sed 's/\*/\\\\*/g'` 
	grep -v "Listen $LADDR:$HTTPS_ADMIN_VHOST_PORT" /tmp/$$.ports.conf.1 >  /tmp/$$.ports.conf.2
	
	:>$APACHE_LISTEN_PORTS 
	cat /tmp/$$.ports.conf.2 >> $APACHE_LISTEN_PORTS 

	if [ ! -d /etc/apache2/conf.d ] ; then
		a2enconf osa-0-ports.conf
		service apache2 restart
	fi

#Create vitual host for local usage (core app not protected=
	
	mkdir -p /var/www/local/main
	mkdir -p /var/www/local/empty
	mkdir -p $LOG_DIR/local/
	[ -h  /var/www/local/main/ApplianceManager ] && rm  /var/www/local/main/ApplianceManager
	ln -s $INSTALL_DIR/ApplianceManager.php /var/www/local/main/ApplianceManager
	[ -d $INSTALL_DIR/ApplianceManager.php/api/cache ] && rm -rf $INSTALL_DIR/ApplianceManager.php/api/cache
	mkdir -p $INSTALL_DIR/ApplianceManager.php/api/cache
	chown -R $APACHE_USER:$APACHE_GROUP $INSTALL_DIR/ApplianceManager.php/api/cache
	sed -i -e "s/\$r = new Restler.*/\$r = new Restler(True);/" $INSTALL_DIR/ApplianceManager.php/api/restler-gateway.php

	echo "Listen 127.0.0.1:$PRIVATE_VHOST_PORT" >> $APACHE_LISTEN_PORTS 
	cat   $INSTALL_DIR/RunTimeAppliance/apache/conf/samples/standard/osa-local | sed "s/PRIVATE_VHOST_PORT/$PRIVATE_VHOST_PORT/g" | sed "s/APACHE_ADMIN_MAIL/$APACHE_ADMIN_MAIL/g" | sed "s|INSTALL_DIR|$INSTALL_DIR|"| sed "s|LOG_DIR|$LOG_DIR|g" > $APACHE_SITES_DEFINITION_DIR/osa-local.conf
	$APACHE_ENABLE_SITE osa-local.conf

#Create vitual host for admin usage (publishing of core app protected by osa module)
	echo "Creating apache conf for HTTPS ADMIN site"
	cat $INSTALL_DIR/RunTimeAppliance/apache/conf/samples/standard/ssleay.cnf | sed "s/@HostName@/$HTTPS_ADMIN_VHOST_NAME/g" >  $INSTALL_DIR/RunTimeAppliance/apache/conf/samples/standard/osa-admin.cnf
	openssl req -config $INSTALL_DIR/RunTimeAppliance/apache/conf/samples/standard/osa-admin.cnf  -new -x509  -days 3650 -nodes  -out  /etc/ssl/certs/osa-admin.pem  -keyout  /etc/ssl/private/osa-admin.key
	chmod 600   /etc/ssl/certs/osa-admin.pem
	chmod 600   /etc/ssl/private/osa-admin.key
	mkdir -p $LOG_DIR/admin/
	[ -f $APACHE_SITES_DEFINITION_DIR/osa-admin.conf ] && rm $APACHE_SITES_DEFINITION_DIR/osa-admin.conf
	echo "Listen $HTTPS_ADMIN_VHOST_ADDR:$HTTPS_ADMIN_VHOST_PORT" >> $APACHE_LISTEN_PORTS 
	cat   $INSTALL_DIR/RunTimeAppliance/apache/conf/samples/standard/osa-admin  | sed "s/HTTPS_ADMIN_VHOST_ADDR/$HTTPS_ADMIN_VHOST_ADDR/g" | sed "s/HTTPS_ADMIN_VHOST_PORT/$HTTPS_ADMIN_VHOST_PORT/g"  | sed "s/HTTPS_ADMIN_VHOST_NAME/$HTTPS_ADMIN_VHOST_NAME/g"  | sed "s/PRIVATE_VHOST_PORT/$PRIVATE_VHOST_PORT/g" | sed "s/APACHE_ADMIN_MAIL/$APACHE_ADMIN_MAIL/g" | sed "s|LOG_DIR|$LOG_DIR|g" > $APACHE_SITES_DEFINITION_DIR/osa-admin.conf

	if [ $ABSOLUTE_URI -eq 0 ] ; then
		cat $APACHE_SITES_DEFINITION_DIR/osa-admin.conf | grep -v "RequestHeader set Public-Root-URI" > /tmp/$$.osa-admin
		cp /tmp/$$.osa-admin $APACHE_SITES_DEFINITION_DIR/osa-admin.conf
		
		cat $INSTALL_DIR/ApplianceManager.php/include/Settings.ini.php | sed 's|.*"defaultUriPrefix".*|	define("defaultUriPrefix",  "");|'  > /tmp/$$.Settings.ini.php
		:>$INSTALL_DIR/ApplianceManager.php/include/Settings.ini.php
		cat /tmp/$$.Settings.ini.php >> $INSTALL_DIR/ApplianceManager.php/include/Settings.ini.php

	fi
	echo "" >> $APACHE_LISTEN_PORTS 
	echo "#Deployed nodes" >> $APACHE_LISTEN_PORTS 
	
	
	$APACHE_ENABLE_SITE osa-admin.conf

}


function createBasicNodes(){
#Create vitual host for api publishing with https
	if [ $USE_HTTPS -ne 0 ] ; then
		echo "Creating apache conf for HTTPS site"
		
		echo "nodeName=HTTPS&nodeDescription=Default+node+using+HTTPS&serverFQDN=$HTTPS_VHOST_NAME&localIP=$HTTPS_VHOST_ADDR&port=$HTTPS_VHOST_PORT&isHTTPS=1&isBasicAuthEnabled=1&isCookieAuthEnabled=1">/tmp/$$.postdata
		curl -i -X POST -k -H  "Content-Type: application/x-www-form-urlencoded; charset=UTF-8"  -d @"/tmp/$$.postdata" --user "$OSA_USAGE_USER:$OSA_ADMIN_PWD"  http://127.0.0.1:$PRIVATE_VHOST_PORT/ApplianceManager/nodes/>/tmp/$$.curl
		egrep " 200 OK|409 Conflict" /tmp/$$.curl
		if [ $? -ne 0 ] ; then
			cat /tmp/$$.curl
			echo ""
			echo ""
			echo ""
			echo "                 *****************************************"
			echo ""
			echo "HTTPS Node: Something has failed in this configuration.... check ouput for details...."
			echo "OSA Configuration IS NOT done, exiting..."
			echo ""
			echo "                 *****************************************"
			echo ""
			echo ""
			shellExit 1
		fi
	fi

#Create vitual host for api publishing with http
	if [ $USE_HTTP -ne 0 ] ; then
		echo "Creating apache conf for HTTP site"
		echo "nodeName=HTTP&nodeDescription=Default+node+using+HTTP&serverFQDN=$HTTP_VHOST_NAME&localIP=$HTTP_VHOST_ADDR&port=$HTTP_VHOST_PORT&isHTTPS=0&isBasicAuthEnabled=1&isCookieAuthEnabled=1">/tmp/$$.postdata

		curl -i -X POST -k -H  "Content-Type: application/x-www-form-urlencoded; charset=UTF-8"  -d @"/tmp/$$.postdata" --user "$OSA_USAGE_USER:$OSA_ADMIN_PWD"  http://127.0.0.1:$PRIVATE_VHOST_PORT/ApplianceManager/nodes/>/tmp/$$.curl
		egrep " 200 OK|409 Conflict" /tmp/$$.curl
		if [ $? -ne 0 ] ; then
			cat /tmp/$$.curl
			echo ""
			echo ""
			echo ""
			echo "                 *****************************************"
			echo ""
			echo "HTTP Node: Something has failed in this configuration.... check ouput for details...."
			echo "OSA Configuration IS NOT done, exiting..."
			echo ""
			echo "                 *****************************************"
			echo ""
			echo ""
			shellExit 1
		fi
	fi
}

function addDBEngine(){
(
ON_CREATE=0;
while read l ; do
	echo "$l"| grep -i "create table">/dev/null
	if [ $? -eq 0 ] ; then
		ON_CREATE=1
		ENGINE="InnoDB"
		echo "$l"| grep -i "authtoken">/dev/null
		if [ $? -eq 0 ] ; then
			ENGINE="Memory"
		fi
	fi
	if [ $ON_CREATE -eq 1 ] ; then
		echo "$l" | egrep ".*\).*;$">/dev/null
		if [ $? -eq 0 ] ; then
			l=`echo $l | sed 's/ENGINE=.* / /'| sed 's/ENGINE=.*;/;/'`
			l=`echo $l|sed "s/;/ ENGINE=$ENGINE;/"`
			echo "$l"
			ON_CREATE=0;
		else
			echo "$l"
		fi
	else
		echo "$l"
	fi
	
done
)<$1 >/tmp/$$.sql

mv /tmp/$$.sql $1
}



function createDBUser(){
	
	
	if [  $DB_IS_LOCAL -eq 1 ] ; then 
		mysql -u root -p"$ROOT_MYSQL_PW"   -h $APPLIANCE_MYSQL_HOST  -P $APPLIANCE_MYSQL_PORT<<EOF
		create user $APPLIANCE_MYSQL_USER identified by '$APPLIANCE_MYSQL_PW';
		grant insert, delete , select, update on $APPLIANCE_MYSQL_SCHEMA.* to '$APPLIANCE_MYSQL_USER'@'localhost' identified by  '$APPLIANCE_MYSQL_PW';
		flush privileges;
EOF
	else
		mysql -u $APPLIANCE_MYSQL_USER -p"$APPLIANCE_MYSQL_PW"   -h $APPLIANCE_MYSQL_HOST  -P $APPLIANCE_MYSQL_PORT<<EOF 2>/tmp/$$.dberr
EOF
		if [ $? -ne 0 ] ; then
			connectingHost=`cat /tmp/$$.dberr | sed 's/'"'"'//g'|sed 's/.*@\([^ ]*\).*/\1/'`
			mysql -u root -p"$ROOT_MYSQL_PW"   -h $APPLIANCE_MYSQL_HOST  -P $APPLIANCE_MYSQL_PORT<<EOF
			create user $APPLIANCE_MYSQL_USER identified by '$APPLIANCE_MYSQL_PW';
			grant insert, delete , select, update on $APPLIANCE_MYSQL_SCHEMA.* to '$APPLIANCE_MYSQL_USER'@'%' identified by  '$APPLIANCE_MYSQL_PW';
			flush privileges;
EOF
			
		fi
	fi
}


######################################################################
# upgradeDBFrom
######################################################################
# Upgrade DB from currentVersion to last "foundable" upgrade
######################################################################
function upgradeDB(){
	curVer=`cat $INSTALL_DIR/RunTimeAppliance/shell/dbversion`
	echo "finding upgrade scripts"
	
	ls $INSTALL_DIR/sql/$curVer-to-* >/dev/null 2>&1
	if [ $? -ne 0 ] ; then
			echo "Can not find the script any to upgrade your existing database from $curVer version..... I hope it's in the proper version to contiunue........ "
	else
		echo "Found at least one script to upgrade from $curVer"
		upgradeFileCount=`ls $INSTALL_DIR/sql/$curVer-to-* | wc -l` 
		echo "I found $upgradeFileCount files to upgrade from $curVer"
		if [ $upgradeFileCount -gt 1 ] ; then
			echo "Found more than 1 script to upgrade DB from $curVer.... WTF?....."
			shellExit 201
		else
		
			targetVer=`ls $INSTALL_DIR/sql/$curVer-to-*|sed 's/.*-to-\(.*\).sql/\1/'`
			SQL_UPDATE=`echo $INSTALL_DIR/sql/$curVer-to-$targetVer.sql`;


				
			cat  $SQL_UPDATE|  sed "s/PRIVATE_VHOST_PORT/$PRIVATE_VHOST_PORT/g">/tmp/$$.sql
			mysql -u root -p$ROOT_MYSQL_PW  -h $APPLIANCE_MYSQL_HOST  -P $APPLIANCE_MYSQL_PORT $APPLIANCE_MYSQL_SCHEMA</tmp/$$.sql 2>&1 >db_schema.log
			if [ $? -ne 0 ] ; then
				echo "****************** DB Schema management errors!!!! ******************"
				echo "*		See "`pwd`"/db_schema.log for details"
				echo "*********************************************************************"
				if [ $KEEP_DB -eq 1 ] ; then
					echo "Try to upgrade to $curDBVersion but DB was already updated?"
				fi
				shellExit 200
			else
				echo $targetVer > $INSTALL_DIR/RunTimeAppliance/shell/dbversion
			fi
			upgradeDB
		fi
	fi
			
}
######################################################################
# createMysqlSchema
######################################################################
# Create (or replace) required MySQL object (schema, tables user)
######################################################################
function createSqliteSchema(){
	if [ -f $INSTALL_DIR/sql/sqlite/osa.db -a $KEEP_DB -eq 0 ] ; then
		rm $INSTALL_DIR/sql/sqlite/osa.db
	fi
	if [ $KEEP_DB -ne 1 ]; then
		echo "Creating SQLite schema"
		cat  ../../sql/sqlite/creation.sql|  sed "s/PRIVATE_VHOST_PORT/$PRIVATE_VHOST_PORT/g">/tmp/$$.sql
		echo "sqlite3 ../../sql/sqlite/osa.db </tmp/$$.sql" >db_schema.log
		sqlite3 ../../sql/sqlite/osa.db </tmp/$$.sql >>db_schema.log
	fi
	chown $APACHE_USER:$APACHE_GROUP ../../sql/sqlite/osa.db
	chown $APACHE_USER:$APACHE_GROUP ../../sql/sqlite/
	chmod 600 ../../sql/sqlite/osa.db
}

######################################################################
# createMysqlSchema
######################################################################
# Create (or replace) required MySQL object (schema, tables user)
######################################################################
function createMysqlSchema(){

mysql -u root -p"$ROOT_MYSQL_PW" -h $APPLIANCE_MYSQL_HOST  -P $APPLIANCE_MYSQL_PORT<<EOF | grep $APPLIANCE_MYSQL_SCHEMA>/dev/null
show databases;
EOF

#If database exists, drop it
if [ $? -eq 0 -a $KEEP_DB -eq 0 ] ; then
	 mysql -u root -p$ROOT_MYSQL_PW  -h $APPLIANCE_MYSQL_HOST  -P $APPLIANCE_MYSQL_PORT<<EOF
drop database $APPLIANCE_MYSQL_SCHEMA;
EOF
fi

#if user exists, drop it
mysql -u root -p$ROOT_MYSQL_PW  -h $APPLIANCE_MYSQL_HOST  -P $APPLIANCE_MYSQL_PORT<<EOF | grep $APPLIANCE_MYSQL_USER>/dev/null
select User from mysql.user where User='$APPLIANCE_MYSQL_USER';
EOF
if [ $? -eq 0 ] ; then
	 echo "droping existing user"
	 mysql -u root -p$ROOT_MYSQL_PW  -h $APPLIANCE_MYSQL_HOST  -P $APPLIANCE_MYSQL_PORT<<EOF
drop user $APPLIANCE_MYSQL_USER;
delete from mysql.user where User='$APPLIANCE_MYSQL_USER';
flush privileges;
EOF
fi
if [ $KEEP_DB -ne 1 ] ; then
	echo "Creating DB objects"
	mysql -u root -p"$ROOT_MYSQL_PW"  -h $APPLIANCE_MYSQL_HOST  -P $APPLIANCE_MYSQL_PORT<<EOF
	create database $APPLIANCE_MYSQL_SCHEMA;
EOF
	createDBUser
	echo "DB objects created"
	curDBVersion=`cat ../../sql/creation.sql | grep " Version:" | awk -F: '{print $2}'`
	addDBEngine ../../sql/creation.sql
	cat  ../../sql/creation.sql|  sed "s/PRIVATE_VHOST_PORT/$PRIVATE_VHOST_PORT/g">/tmp/$$.sql
	echo mysql -u root -p"$ROOT_MYSQL_PW"   -h $APPLIANCE_MYSQL_HOST  -P $APPLIANCE_MYSQL_PORT $APPLIANCE_MYSQL_SCHEMA</tmp/$$.sql >db_schema.log
	mysql -u root -p"$ROOT_MYSQL_PW"   -h $APPLIANCE_MYSQL_HOST  -P $APPLIANCE_MYSQL_PORT $APPLIANCE_MYSQL_SCHEMA</tmp/$$.sql >db_schema.log
else
	echo "Creating appliance user"
	createDBUser
	#Have a look to see if DB needs upgrade
	if [ -f $INSTALL_DIR/RunTimeAppliance/shell/dbversion ] ; then
		curDBVersion=`cat $INSTALL_DIR/RunTimeAppliance/shell/dbversion`;
		newDBVersion=`cat ../../sql/creation.sql | grep " Version:" | awk -F: '{print $2}'|sed 's/ //g'`
		if [ "$curDBVersion" == "$newDBVersion" ] ;then
			curDBVersion=$newDBVersion;
			echo  "Current DB version is already in $newDBVersion: nothing to do!";
		else
			#SQL_UPDATE=`echo $INSTALL_DIR/sql/$curDBVersion-to-$newDBVersion.sql`;
			#addDBEngine $SQL_UPDATE
			#if [ -f $SQL_UPDATE ] ; then
			#	echo "Updating DB objects"
			#	cat  $SQL_UPDATE|  sed "s/PRIVATE_VHOST_PORT/$PRIVATE_VHOST_PORT/g">/tmp/$$.sql

			#	curDBVersion=$newDBVersion;
			#	mysql -u root -p"$ROOT_MYSQL_PW"   -h $APPLIANCE_MYSQL_HOST  -P $APPLIANCE_MYSQL_PORT $APPLIANCE_MYSQL_SCHEMA </tmp/$$.sql 2>&1 >db_schema.log
			#else
			#	echo "Can not find the script ($SQL_UPDATE) to upgrade your existing database..... Sorry.... "
			#	shellExit 201
			#fi
			upgradeDB
			newDBVersionAfterUpgrade=`cat $INSTALL_DIR/RunTimeAppliance/shell/dbversion`;
			if [ "$newDBVersionAfterUpgrade" != "$newDBVersion" ] ; then
				echo "Database should be in $newDBVersion version but it is in $newDBVersionAfterUpgrade version after upgrade"
				echo "I didn't succeed to find proper upgrade scripts......."
				shellExit 202
			else
				curDBVersion=$newDBVersion;
				echo "Database have been upgraded to $newDBVersion version. Good!"
			fi
				
		fi
	else
		echo "Unable to determine current DB version (dbversion file from previous install not found)";
		shellExit 202
	fi
			
fi 

if [ $? -ne 0 ] ; then
	echo "****************** DB Schema management errors!!!! ******************"
	echo "*		See "`pwd`"/db_schema.log for details"
	echo "*********************************************************************"
	if [ $KEEP_DB -eq 1 ] ; then
		echo "Try to upgrade to $curDBVersion but DB was already updated?"
	fi
	shellExit 200
else
	echo $curDBVersion > $INSTALL_DIR/RunTimeAppliance/shell/dbversion
fi
	
}




######################################################################
# usage
######################################################################
# Display a list of possible parameters
######################################################################
function usage(){

echo $0 OPTIONS
echo '	-h : this list....'
echo ''
echo '	-mysql-root-password pwd : mysql root password'
echo '	-mysql-osa-password pwd : mysql appliance user password (the user created with this password by the script)'
echo '	-admin-new-password pwd : new password for Admin user (GUI/REST web service user)'
echo '	-apache-admin : apache admin mail'
echo '	-http-private-port port : tcp port for internal (localhost) usage'
echo '	-https-admin-addr ip|name : ip address or interface name for https admin site'
echo '	-https-admin-port port : tcp port for https admin site'
echo '	-https-admin-fqdn fqdn : fqdn for https admin  site (if not set https-admin-addr will be used instead), '
echo '	-disable-http : disable API publhing on http site'
echo '	-http-addr ip|name : ip address or interface name for http publishing site'
echo '	-http-port port : tcp port for http publishing site'
echo '	-http-fqdn fqdn : fqdn for http publishing site (if not set http-addr will be used instead)'
echo '	-disable-https : disable API publhing on https site'
echo '	-https-fqdn fqdn : fqdn for http publishing  site'
echo '	-https-addr ip|name : ip address or interface name for http publishing site'
echo '	-https-port port : tcp port for https publishing site'
echo '	-keep-db : set it to run this script without deleting/creating database schema'
echo ''
echo 'Ex.:'
echo "$0"'  -mysql-root-password mySqlRootPwd \ '
echo '	 	 -mysql-osa-password mySqlAppliancePwd \ '
echo '	 	 -http-private-port 82 \ '
echo '	 	 -https-admin-addr r-ptxp-jmjb0521 \ '
echo '	 	 -https-admin-port 6443 \ '
echo '	 	 -https-admin-fqdn adminapis.emerginov.local \ '
echo '	 	 -http-addr r-ptxp-jmjb0521 \ '
echo '	 	 -http-fqdn apis.emerginov.local   \ '
echo '	 	 -http-port 81 \ '
echo '	 	 -https-addr r-ptxp-jmjb0521 \ '
echo '	 	 -https-fqdn apis.emerginov.org \ '
echo '	 	 -https-port 8443 \ '
echo '	 	 -apache-admin jdoe@acme.com \ '
echo '	 	 -admin-new-password myNewPassword '
}

######################################################################
# verifyParameters
######################################################################
# Ensute that received parameters (ex mysql root password) are valid
#  and that required parameters are set
######################################################################
function verifyParameters(){

RC=0;
if [ "$RDBMS" != "mysql" -a "$RDBMS" != "sqlite" ] ; then
	echo "Invalid rdbms system: $RDBMS"
	RC=21
	return $RC
fi
if [ "$RDBMS" == "mysql" ] ; then
	if [ -z "$APPLIANCE_MYSQL_PW" ] ; then
		echo "mysql appliance password is missing"
		RC=21
	fi
	if [ -z "$ROOT_MYSQL_PW" ] ; then
		echo "mysql root password is missing"
		RC=21
	else
		mysql -u root -p"$ROOT_MYSQL_PW"  -h "$APPLIANCE_MYSQL_HOST"  -P "$APPLIANCE_MYSQL_PORT"<<EOF >/dev/null
EOF
		if [ $? -ne 0 ] ; then
			echo "mysql root password is invalid"
			RC=22
		fi
	fi
fi
if [ -z $APPLIANCE_ADMIN_PW ]  ; then
	echo "Appliance admin password is missing"
	RC=20
fi
if [ $USE_HTTP -ne 0 -a -z "$HTTP_VHOST_ADDR" ] ; then
	echo "IP for http API publishing site is missing"
	RC=23
fi
if [ $USE_HTTPS -ne 0 -a -z "$HTTPS_VHOST_ADDR" ] ; then
	echo "IP for https API publishing site is missing"
	RC=24
fi
if [ -z "$HTTPS_ADMIN_VHOST_ADDR" ] ; then
	echo "IP for https admin site is missing"
	RC=25
fi
if [ -z $APACHE_USER ] ; then
	echo "Unable to determine APACHE_USER....."
	if [ -f /etc/redhat-release ] ; then
		echo 'Check for User configuration directive in your /etc/httpd/conf/httpd.conf file.... this script seach for:  ^.*APACHE_RUN_USER=\(.*\)'
	elif [ -f /etc/debian_version ] ; then
		echo 'Check for APACHE_RUN_USER variable definition  in your  /etc/apache2/envvars  file.... this script seach for: ^User \(.*\)'
	fi
	RC=26
fi
if [ -z $APACHE_GROUP ] ; then
	echo "Unable to determine APACHE_USER_GROUP....."
	if [ -f /etc/redhat-release ] ; then
		echo 'Check for Group configuration directive in your /etc/httpd/conf/httpd.conf file.... this script seach for: ^.*APACHE_RUN_GROUP=\(.*\)'
	elif [ -f /etc/debian_version ] ; then
		echo 'Check for APACHE_RUN_GROUP variable definition  in your  /etc/apache2/envvars  file.... this script seach for: ^Group \(.*\)'
	fi
	RC=27
fi
if [ $RC -ne 0 ] ; then
	echo "configuration is not valid"
	usage
	shellExit $RC
fi

echo "Starting with configuration:"
echo "HTTP_VHOST_NAME=$HTTP_VHOST_NAME"
echo "HTTP_VHOST_ADDR=$HTTP_VHOST_ADDR"
echo "HTTP_VHOST_PORT=$HTTP_VHOST_PORT"
echo "HTTPS_VHOST_NAME=$HTTPS_VHOST_NAME"
echo "HTTPS_VHOST_ADDR=$HTTPS_VHOST_ADDR"
echo "HTTPS_VHOST_PORT=$HTTPS_VHOST_PORT"
echo "HTTPS_ADMIN_VHOST_NAME=$HTTPS_ADMIN_VHOST_NAME"
echo "HTTPS_ADMIN_VHOST_ADDR=$HTTPS_ADMIN_VHOST_ADDR"
echo "HTTPS_ADMIN_VHOST_PORT=$HTTPS_ADMIN_VHOST_PORT"
echo "PRIVATE_VHOST_PORT=$PRIVATE_VHOST_PORT"
echo "USE_HTTP=$USE_HTTP"
echo "USE_HTTPS=$USE_HTTPS"
echo "ROOT_MYSQL_PW=$ROOT_MYSQL_PW"
echo "APPLIANCE_MYSQL_PW=$APPLIANCE_MYSQL_PW"
echo "APACHE_ADMIN_MAIL=$APACHE_ADMIN_MAIL"
echo "APACHE_USER=$APACHE_USER"
echo "APPLIANCE_ADMIN_PW=$APPLIANCE_ADMIN_PW"
echo "KEEP_DB=$KEEP_DB"

}




#Migrate apache OSA conf from Apache2.2 packaging mode to Apache2.4
function migrateApacheConfig(){
	for f in `ls $APACHE_SITES_DEFINITION_DIR/osa*` ; do
		echo $f| grep ".conf">/dev/null
		if [ $? -ne 0 ] ; then
			$APACHE_DISABLE_SITE `basename $f`
			mv $f $f.conf
		fi
	done
	if [ ! -d /etc/apache2/conf.d ] ; then
		#we are on Apache2.4 like installation, migrate form 2.2 like
		if [ -f /etc/apache2/conf.d/osa-0-ports.conf ] ; then
			mv /etc/apache2/conf.d/osa-0-ports.conf $APACHE_LISTEN_PORTS
		fi
		a2enconf osa-0-ports.conf
	fi
}

######################################################################
# main code
######################################################################
# 
# 
######################################################################
id | grep "uid=0(root)" > /dev/null
if [ $? -ne 0 ] ; then
	echo "This scrips must run as root!"
	shellExit 1
fi


#set default values for parameters a first time to have basic paramters (Ex INSTALL_DIR)
EXEC_DIR=`dirname $0`
cd $EXEC_DIR
. ./osa-funcs.sh
. ./envvars.sh
chmod 700 envvars.sh
chown -R root:root $INSTALL_DIR


if [ -f /etc/redhat-release ] ; then
	echo "RedHat system"
	MYSQL_CONF_FILE=/etc/my.cnf
	MYSQL_INITD_FILE=/etc/init.d/mysqld

	APACHE_INITD_FILE=/etc/init.d/httpd
	APACHE_USER=`getApacheUserRedhat`
	APACHE_GROUP=`getApacheGroupRedhat`

	[ ! -d $INSTALL_DIR/RunTimeAppliance/apache/conf/sites-availables ]  && mkdir -p $INSTALL_DIR/RunTimeAppliance/apache/conf/sites-availables
	APACHE_SITES_DEFINITION_DIR=$INSTALL_DIR/RunTimeAppliance/apache/conf/sites-availables
	APACHE_ENABLE_SITE=enableRedhatSite
	APACHE_DISABLE_SITE=disableRedhatSite
	APACHE_LISTEN_PORTS=/etc/httpd/conf.d/osa-0-ports.conf
	APACHE_LOAD_MOD=ensureModuleIsAvailableRedhat
	APACHE_LOG_DIR=/var/log/httpd

	grep "#includedir /etc/sudoers.d" /etc/sudoers>/dev/null
	if [ $? -ne 0 ] ; then
		mkdir /etc/sudoers.d
		chmod 755 /etc/sudoers.d
		echo "#includedir /etc/sudoers.d" >> /etc/sudoers
	fi


	touch $APACHE_LISTEN_PORTS
	mkdir -p  /etc/ssl/certs
	mkdir -p   /etc/ssl/private
	[ -f /etc/httpd/conf.d/osa-0-modules.conf ]  && rm /etc/httpd/conf.d/osa-0-modules.conf

elif [ -f /etc/debian_version ] ; then
	echo "Debian system"
	MYSQL_CONF_FILE=/etc/mysql/my.cnf
	if [ -f /etc/mysql/mysql.conf.d/mysqld.cnf ] ; then
		MYSQL_CONF_FILE=/etc/mysql/mysql.conf.d/mysqld.cnf
	fi
	MYSQL_INITD_FILE=/etc/init.d/mysql

	APACHE_INITD_FILE=/etc/init.d/apache2
	APACHE_USER=`getApacheUserDebian`
	APACHE_GROUP=`getApacheGroupDebian`
	APACHE_SITES_DEFINITION_DIR=/etc/apache2/sites-available
	APACHE_ENABLE_SITE=a2ensite
	APACHE_DISABLE_SITE=a2dissite
	if [ ! -d /etc/apache2/conf.d ] ; then
		APACHE_LISTEN_PORTS=/etc/apache2/conf-available/osa-0-ports.conf
	else
		APACHE_LISTEN_PORTS=/etc/apache2/conf.d/osa-0-ports.conf
	fi
	APACHE_LOAD_MOD=ensureModuleIsAvailableDebian
	APACHE_LOG_DIR=/var/log/apache2
	
	migrateApacheConfig

else
	echo "This script only works with debian or redhat"
	shellExit 1
fi
	
#set default values for parameters a second time to have  parameters computed from OS parameters (ex: LOG_DIR)
if [  "$APPLIANCE_MYSQL_HOST" == "" ] ; then
	APPLIANCE_MYSQL_HOST=localhost
fi
if [ "$APPLIANCE_MYSQL_PORT" == "" ] ; then
	APPLIANCE_MYSQL_PORT=3306
fi
if [ "$MYSQL_BIND_ADDRESS" == "" ] ; then
	MYSQL_BIND_ADDRESS="127.0.0.1"
fi


#Parse received parameters
while [ "$1" != "" ] ; do
	if [ "$1" == "-mysql-root-password" ] ; then
		ROOT_MYSQL_PW=$2
	fi
	if [ "$1" == "-mysql-osa-password" ] ; then
		APPLIANCE_MYSQL_PW=$2
	fi
	if [ "$1" == "-admin-new-password" ] ; then
		APPLIANCE_ADMIN_PW=$2
	fi
	if [ "$1" == "-keep-db" ] ; then
		KEEP_DB=1
	fi
	
	if [ "$1" == "-disable-http" ] ; then
		USE_HTTP=0
	fi
	if [ "$1" == "-disable-https" ] ; then
		USE_HTTPS=0
	fi
	
	if [ "$1" == "-http-fqdn" ] ; then
		HTTP_VHOST_NAME=$2
	fi
	if [ "$1" == "-http-addr" ] ; then
		HTTP_VHOST_ADDR=$2
	fi
	if [ "$1" == "-http-port" ] ; then
		HTTP_VHOST_PORT=$2
	fi
	
	if [ "$1" == "-https-fqdn" ] ; then
		HTTPS_VHOST_NAME=$2
	fi
	if [ "$1" == "-https-port" ] ; then
		HTTPS_VHOST_PORT=$2
	fi
	if [ "$1" == "-https-addr" ] ; then
		HTTPS_VHOST_ADDR=$2
	fi
	
	if [ "$1" == "-https-admin-fqdn" ] ; then
		HTTPS_ADMIN_VHOST_NAME=$2
	fi
	if [ "$1" == "-https-admin-port" ] ; then
		HTTPS_ADMIN_VHOST_PORT=$2
	fi
	if [ "$1" == "-https-admin-addr" ] ; then
		HTTPS_ADMIN_VHOST_ADDR=$2
	fi
	
	if [ "$1" == "-http-private-port" ] ; then
		PRIVATE_VHOST_PORT=$2
	fi
	if [ "$1" == "-apache-admin" ] ; then
		APACHE_ADMIN_MAIL=$2
	fi
	if [ "$1" == "-enable-absolute-uri" ] ; then
		ABSOLUTE_URI=1
	fi
	if [ "$1" == "-rdbms" ] ; then
		RDBMS=$2
	fi
	if [ "$1" == "-h" ] ; then
		usage
		shellExit 1
	fi
	shift
done

DB_IP=`ping -c 1 $APPLIANCE_MYSQL_HOST | grep PING|sed 's/[^(]*.\([^)]*\).*/\1/'`
ifconfig | grep "inet adr:$DB_IP " > /dev/null
if [ $? -eq 0 ] ; then
	DB_IS_LOCAL=1
else
	DB_IS_LOCAL=0
fi

if [ -z $HTTP_VHOST_NAME ] ; then
	HTTP_VHOST_NAME=$HTTP_VHOST_ADDR
fi
if [ -z $HTTPS_VHOST_NAME ] ; then
	HTTPS_VHOST_NAME=$HTTPS_VHOST_ADDR
fi
if [ -z $HTTPS_ADMIN_VHOST_NAME ] ; then
	HTTPS_ADMIN_VHOST_NAME=$HTTPS_ADMIN_VHOST_ADDR
fi


verifyParameters

if [ $KEEP_DB -eq 1 ] ; then
	echo "Removing previous OSA configuration"
	$INSTALL_DIR/RunTimeAppliance/shell/remove-osa.sh
else
	echo "Removing previous OSA configuration and DB objects"
	$INSTALL_DIR/RunTimeAppliance/shell/remove-osa.sh -purge -mysql-root-password "$ROOT_MYSQL_PW"
fi

configureCryptoKey

mkdir -p /var/www/local/main
mkdir -p $LOG_DIR

if [ "$RDBMS" == "mysql" ] ; then
	configureMySQLSettings
	createMysqlSchema
	configureMySQLCreds
else
	createSqliteSchema
	configureSqliteSettings
fi
createApacheConf
configurePathAndSettings
configureShellScripts
configureSudoers
configureCron
configureEtc
configureFileSystemPrivileges

$APACHE_INITD_FILE restart
if [ $KEEP_DB -eq 1 ] ; then
	$INSTALL_DIR/RunTimeAppliance/shell/doVHAppliance.sh
fi

createBasicNodes
updateAdminUser
updateAdminService

deleteTempFiles
echo "OSA Configuration done, exiting..."
echo "You can now connect https://$HTTPS_ADMIN_VHOST_NAME:$HTTPS_ADMIN_VHOST_PORT/ (with admin as user and $APPLIANCE_ADMIN_PW as password for credentials) to manage OSA."
exit 0


