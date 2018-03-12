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
 # File Name   : ApplianceManager/RunTimeAppliance/shell/remove-osa.sh
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


function shellExit(){
	deleteTempFiles
	exit $1
}



######################################################################
# removeEtc
######################################################################
# Remove /etc/ApplianceManager
######################################################################
function removeEtc(){
	if [ $PURGE_ALL -eq 1 ] ; then
		rm -rf /etc/ApplianceManager
	fi
	
}

######################################################################
# removeCron
######################################################################
# remove cron jobs (logrotate)
######################################################################
function removeCron(){
	crontab -l | grep -v '/RunTimeAppliance/shell/logrotate.conf' | grep -v "/RunTimeAppliance/shell/purgeHits.sh" > /tmp/$$.crontab
	crontab /tmp/$$.crontab
}


######################################################################
# removeSudoers
######################################################################
# remove the configuration in /ec/sudoers file to allow GUI/WS to generate 
# reverse proxification rules with shell script
######################################################################
function removeSudoers(){
	
	[ -f /etc/sudoers.d/ApplianceManager ]  && rm /etc/sudoers.d/ApplianceManager
}


######################################################################
# deleteTempFiles
######################################################################
# delete temporary files
######################################################################
function deleteTempFiles(){
	rm /tmp/$$.*
}


######################################################################
# configureMySQLSettings
######################################################################
# configure settings for mysql server
######################################################################
function removeMySQLSettings(){
	if [ $PURGE_ALL -eq 1 -a $DB_IS_LOCAL -eq 1 ] ; then
		grep -v innodb_lock_wait_timeout /etc/mysql/my.cnf > /tmp/$$.my.cnf
		:> /etc/mysql/my.cnf
		cat /tmp/$$.my.cnf > /etc/mysql/my.cnf
	fi
}



function rmFile(){
	[ -f $1 ] && rm $1;
}
function rmDir(){
	[ -d $1 ] && rm -rf $1;
}
######################################################################
# removeApacheConf
######################################################################
# Remove apache vitual hosts for
#     - local usage (127.0.0.1: core app not protected)
#     - admin, using https
#     - api publishing with https (is enabled)
#     - api publishing with http (is enabled)
######################################################################
function removeApacheConf(){

	
	
	for sDef in `ls $APACHE_SITES_DEFINITION_DIR/osa*` ; do
		site=`basename $sDef`
		$APACHE_DISABLE_SITE $site
	done


	#Configure ports.conf
		if  [ ! -d /etc/apache2/conf.d ] ; then
			a2disconf osa-0-ports.conf
		fi
		rmFile $APACHE_LISTEN_PORTS

	if [ $PURGE_ALL -eq 1 ]  ; then

	#remove logs
		rmDir $LOG_DIR
		
		rmFile $LOG_DIR/doAppliance.log
		
		for sDef in  `ls $APACHE_SITES_DEFINITION_DIR/osa*` ; do
			rmFile $sDef
		done

	#remove cretificates
		for cert in `ls /etc/ssl/certs/osa-*` ; do
			rm  $cert
		done
		for key in `ls /etc/ssl/private/osa-*` ; do
			rm  $key
		done



		
	fi
}



######################################################################
# removeSqliteSchema
######################################################################
# remove Sqlite object (DB File)
######################################################################
function removeSqliteSchema(){
	if [ $PURGE_ALL -ne 1 ] ; then
		echo "-purge is not set: don't remove anything in DB"
	else
		rm $INSTALL_DIR/sql/sqlite/osa.db
	fi	
}


######################################################################
# removeMysqlSchema
######################################################################
# remove MySQL object (schema, tables user)
######################################################################
function removeMysqlSchema(){

if [ $PURGE_ALL -ne 1 ] ; then
	echo "-purge is not set: don't remove anything in DB"
else
	rmFile dbversion
	mysql -u root -p$ROOT_MYSQL_PW -h $APPLIANCE_MYSQL_HOST  -P $APPLIANCE_MYSQL_PORT <<EOF | grep $DB_SCHEMA_NAME>/dev/null
show databases;
EOF

	#If database exists, drop it
	if [ $? -eq 0 ] ; then
		mysql -u root -p$ROOT_MYSQL_PW -h $APPLIANCE_MYSQL_HOST  -P $APPLIANCE_MYSQL_PORT <<EOF
drop database $DB_SCHEMA_NAME;
EOF
	fi

	#if user exists, drop it
	mysql -u root -p$ROOT_MYSQL_PW -h $APPLIANCE_MYSQL_HOST  -P $APPLIANCE_MYSQL_PORT <<EOF | grep $DB_USER>/dev/null
select User from mysql.user where User='$DB_USER';
EOF
	if [ $? -eq 0 ] ; then
		mysql -u root -p$ROOT_MYSQL_PW -h $APPLIANCE_MYSQL_HOST  -P $APPLIANCE_MYSQL_PORT <<EOF
drop user $DB_USER;
delete from mysql.user where User='$DB_USER';
flush privileges;
EOF
	fi
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
echo '	-purge:  also remove all files and DB objects created by configuer-osa.sh'
echo '	-mysql-root-password pwd : mysql root password (if -purge is set and RDBMS is MySQL)'
echo ''
echo 'Ex.:'
echo "$0"'  -mysql-root-password mySqlRootPwd  '
}

######################################################################
# verifyParameters
######################################################################
# Ensute that received parameters (ex mysql root password) are valid
#  and that required parameters are set
######################################################################
function verifyParameters(){

RC=0;
if [ $PURGE_ALL -eq 1 ] ; then
	if [ "$RDBMS" == "mysql" ] ; then
		if [ -z "$ROOT_MYSQL_PW"  ] ; then
			echo "mysql root password is missing"
			RC=21
		else
			mysql -u root -p"$ROOT_MYSQL_PW"  -h $APPLIANCE_MYSQL_HOST  -P $APPLIANCE_MYSQL_PORT<<EOF >/dev/null
EOF
			if [ $? -ne 0 ] ; then
				echo "mysql root password is invalid"
				RC=22
			fi
		fi
	fi
fi
if [ $RC -ne 0 ] ; then
	echo "configuration is not valid"
	usage
	exit $RC
fi

echo "Starting with configuration:"
echo "HTTP_VHOST_ADDR=$HTTP_VHOST_ADDR"
echo "HTTP_VHOST_PORT=$HTTP_VHOST_PORT"
echo "HTTPS_VHOST_ADDR=$HTTPS_VHOST_ADDR"
echo "HTTPS_VHOST_PORT=$HTTPS_VHOST_PORT"
echo "HTTPS_ADMIN_VHOST_ADDR=$HTTPS_ADMIN_VHOST_ADDR"
echo "HTTPS_ADMIN_VHOST_PORT=$HTTPS_ADMIN_VHOST_PORT"
echo "PRIVATE_VHOST_PORT=$PRIVATE_VHOST_PORT"
echo "USE_HTTP=$USE_HTTP"
echo "USE_HTTPS=$USE_HTTPS"
echo "ROOT_MYSQL_PW=$ROOT_MYSQL_PW"
echo "PURGE_ALL=$PURGE_ALL"
echo "LOG_DIR=$LOG_DIR"

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
		#we are on Apache2.4 like installation, migrate from 2.2 like
		if [ -f /etc/apache2/conf.d/osa-0-ports.conf ] ; then
			mv /etc/apache2/conf.d/osa-0-ports.conf $APACHE_LISTEN_PORTS
		fi
		a2enconf osa-0-ports.conf
	fi
}

######################################################################
# loadFromConfig
######################################################################
# Parse current config to load uninstall params
######################################################################

function loadFromConfig(){

	#Old versions cleaning
	find $APACHE_BASE -name "*nursery*" | xargs rm -rf
	find $INSTALL_DIR -name "*nursery*" | xargs rm -rf

	if [ -f $APACHE_SITES_DEFINITION_DIR/osa-local.conf ] ; then
		if [ -f $APACHE_SITES_DEFINITION_DIR/osa-http.conf ] ; then
			USE_HTTP=1
			addrPort=`cat $APACHE_SITES_DEFINITION_DIR/osa-http.conf| grep "<VirtualHost" |sed 's/<VirtualHost \(.*\)>.*/\1/'`
			HTTP_VHOST_ADDR=`echo $addrPort | awk -F: '{print $1}'` 
			HTTP_VHOST_PORT=`echo $addrPort | awk -F: '{print $2}'`
			
			
		fi
		if [ -f $APACHE_SITES_DEFINITION_DIR/osa-https.conf ] ; then
			USE_HTTPS=1
			addrPort=`cat $APACHE_SITES_DEFINITION_DIR/osa-https.conf| grep "<VirtualHost" |sed 's/<VirtualHost \(.*\)>.*/\1/'`
			HTTPS_VHOST_ADDR=`echo $addrPort | awk -F: '{print $1}'` 
			HTTPS_VHOST_PORT=`echo $addrPort | awk -F: '{print $2}'`
		fi
		addrPort=`cat $APACHE_SITES_DEFINITION_DIR/osa-admin.conf| grep "<VirtualHost" |sed 's/<VirtualHost \(.*\)>.*/\1/'`
		HTTPS_ADMIN_VHOST_ADDR=`echo $addrPort | awk -F: '{print $1}'` 
		HTTPS_ADMIN_VHOST_PORT=`echo $addrPort | awk -F: '{print $2}'`

		addrPort=`cat $APACHE_SITES_DEFINITION_DIR/osa-local.conf| grep "<VirtualHost" |sed 's/<VirtualHost \(.*\)>.*/\1/'`
		PRIVATE_VHOST_PORT=`echo $addrPort | awk -F: '{print $2}'`
			
		DB_SCHEMA_NAME=`grep "BDName" /etc/ApplianceManager/Settings.ini.php | awk -F= '{print $2}' | sed 's/"//g' | awk -F@ '{print $1}'`
		DB_USER=`grep "BDUser" /etc/ApplianceManager/Settings.ini.php | awk -F= '{print $2}' | sed 's/"//g' |sed 's/;$//'`
		
		LOG_DIR=`grep "ErrorLog " $APACHE_SITES_DEFINITION_DIR/osa-local.conf  | sed 's|.*ErrorLog \(.*\)/local/main.error.log|\1|'`
		
	else
		echo "Hummmmm... It seems that there's no OSA installed here..."
		exit 0
	fi
	
}


######################################################################
# getconfFromOS
######################################################################
# set execution parameter according to OS (debian vs RedHAT
# 
######################################################################
function getConfFromOS(){
if [ -f /etc/redhat-release ] ; then
	echo "RedHat system"
	MYSQL_CONF_FILE=/etc/my.cnf
	MYSQL_INITD_FILE=/etc/init.d/mysqld

	APACHE_INITD_FILE=/etc/init.d/httpd

	[ ! -d $INSTALL_DIR/RunTimeAppliance/apache/conf/sites-availables ]  && mkdir -p $INSTALL_DIR/RunTimeAppliance/apache/conf/sites-availables
	APACHE_SITES_DEFINITION_DIR=$INSTALL_DIR/RunTimeAppliance/apache/conf/sites-availables
	APACHE_DISABLE_SITE=disableRedhatSite
	APACHE_LISTEN_PORTS=/etc/httpd/conf.d/osa-0-ports.conf
	APACHE_LOAD_MOD=ensureModuleIsAvailableRedhat
	APACHE_LOG_DIR=/var/log/httpd
	APACHE_BASE=/etc/httpd

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
	MYSQL_INITD_FILE=/etc/init.d/mysql

	APACHE_INITD_FILE=/etc/init.d/apache2
	APACHE_SITES_DEFINITION_DIR=/etc/apache2/sites-available
	APACHE_DISABLE_SITE=a2dissite
	if [ ! -d /etc/apache2/conf.d ] ; then
		APACHE_LISTEN_PORTS=/etc/apache2/conf-available/osa-0-ports.conf
	else
		APACHE_LISTEN_PORTS=/etc/apache2/conf.d/osa-0-ports.conf
	fi
	APACHE_LOAD_MOD=ensureModuleIsAvailableDebian
	APACHE_LOG_DIR=/var/log/apache2
	APACHE_BASE=/etc/apache2
	
	migrateApacheConfig
else
	echo "This script only works with debian or redhat"
	shellExit 1
fi

}

function disableRedhatSite(){
	[  -f /etc/httpd/conf.d/$1.conf ] && rm /etc/httpd/conf.d/$1.conf
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
	exit 1
fi



PURGE_ALL=0

HTTP_VHOST_ADDR=""
HTTP_VHOST_PORT=80

HTTPS_VHOST_ADDR=""
HTTPS_VHOST_PORT=443

HTTPS_ADMIN_VHOST_ADDR=""
HTTPS_ADMIN_VHOST_PORT=8443

PRIVATE_VHOST_PORT=80

USE_HTTP=1
USE_HTTPS=1

ROOT_MYSQL_PW=""

#set default values for paramameters
. envvars.sh
chmod 700 envvars.sh
if [  "$APPLIANCE_MYSQL_HOST" == "" ] ; then
	APPLIANCE_MYSQL_HOST=localhost
fi
if [ "$APPLIANCE_MYSQL_PORT" == "" ] ; then
	APPLIANCE_MYSQL_PORT=3306
fi
if [ "$MYSQL_BIND_ADDRESS" == "" ] ; then
	MYSQL_BIND_ADDRESS="127.0.0.1"
fi

DB_IP=`ping -c 1 $APPLIANCE_MYSQL_HOST | grep PING|sed 's/[^(]*.\([^)]*\).*/\1/'`
ifconfig | grep $DB_IP > /dev/null
if [ $? -eq 0 ] ; then
	DB_IS_LOCAL=1
else
	DB_IS_LOCAL=0
fi




getConfFromOS


#Parse received parameters
while [ "$1" != "" ] ; do
	if [ "$1" == "-mysql-root-password" ] ; then
		ROOT_MYSQL_PW=$2
	fi
	if [ "$1" == "-purge" ] ; then
		PURGE_ALL=1
	fi
	
	if [ "$1" == "-h" ] ; then
		usage
		exit 1
	fi
	shift
done


loadFromConfig

verifyParameters
if [ "$RDBMS" == "mysql" ] ; then
	removeMysqlSchema
	removeMySQLSettings
else
	removeSqliteSchema
fi
removeApacheConf
removeSudoers
removeCron
removeEtc

if [ $DB_IS_LOCAL -eq 1 -a "$RDBMS" == "mysql" ] ; then
	/etc/init.d/mysql restart
fi
$APACHE_INITD_FILE restart

deleteTempFiles

