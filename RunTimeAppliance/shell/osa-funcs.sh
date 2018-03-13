#!/bin/bash
##--------------------------------------------------------
 # Module Name : ApplianceManager
 # Version : 1.0.0
 #
 # Software Name : OpenServicesAccess
 # Version : 1.0
 #
 # Copyright (c) 2011 – 2017 Orange
 # This software is distributed under the Apache 2 license
 # <http://www.apache.org/licenses/LICENSE-2.0.html>
 #
 #--------------------------------------------------------
 # File Name   : ApplianceManager/RunTimeAppliance/shell/osa-funcs.sh
 #
 # Created     : 2017-01
 # Authors     : Benoit HERARD <benoit.herard(at)orange.com>
 #
 # Description :
 #      .../...
 #--------------------------------------------------------
 # History     :
 # 1.0.0 - 2017-01-19 : Release of the file
 # 2.0.0 - 2018-03-13 : Default value for RDBMS
##
RDBMS=mysql #compatibility to version before 4.0 (sqlite)

######################################################################
# getApacheUserDebian
######################################################################
# retreive the user used to run apache2
######################################################################
function getApacheUserDebian(){
	grep "APACHE_RUN_USER=" /etc/apache2/envvars|sed 's/.*APACHE_RUN_USER=\(.*\)/\1/'
}
######################################################################
# getApacheGroupDebian
######################################################################
# retreive the group used to run apache2
######################################################################
function getApacheGroupDebian(){
	grep "APACHE_RUN_GROUP=" /etc/apache2/envvars|sed 's/.*APACHE_RUN_GROUP=\(.*\)/\1/'
}
######################################################################
# getApacheUserRedhat
######################################################################
# retreive the user used to run apache2
######################################################################
function getApacheUserRedhat(){
	egrep "^User .*" //etc/httpd/conf/httpd.conf|sed 's/User \(.*\)/\1/'
}
######################################################################
# getApacheGroupRedhat
######################################################################
# retreive the group used to run apache2
######################################################################
function getApacheGroupRedhat(){
	egrep "^Group .*" /etc/httpd/conf/httpd.conf|sed 's/Group \(.*\)/\1/'
}
function enableRedhatSite(){
	[ -f /etc/httpd/conf.d/$1.conf ] && rm /etc/httpd/conf.d/$1
	ln -s $APACHE_SITES_DEFINITION_DIR/$1  /etc/httpd/conf.d/$1
	chown $APACHE_USER:$APACHE_GROUP    /etc/httpd/conf.d/$1
	chmod 644  /etc/httpd/conf.d/$1.conf
}
function disableRedhatSite(){
	[  -f /etc/httpd/conf.d/$1 ] && rm /etc/httpd/conf.d/$1
}



######################################################################
# deleteTempFiles
######################################################################
# delete temporary files
######################################################################
function deleteTempFiles(){
	ls /tmp/$$.* > /dev/null
	if [ $? -eq 0 ] ; then
		echo "Deleting temp files"
		rm /tmp/$$.*
	#else
	#	echo "No file delete (/tmp/$$.*)"
	fi
}
