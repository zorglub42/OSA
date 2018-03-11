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
 # File Name   : ApplianceManager/RunTimeAppliance/apache/module/compileAndDeploy.sh
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

#Does current system looks like
if [ -f /etc/init.d/httpd ] ; then
	#RedHat
	APACHE_INIT_SCRIPT=/etc/init.d/httpd
	APACHE_APXS=apxs
else
	if [ -f /etc/init.d/apache2 ] ; then
		#Debian
		APACHE_INIT_SCRIPT=/etc/init.d/apache2
		APACHE_APXS=apxs2
	else
		echo "No /etc/init.d/httpd or /etc/init.d/apache2 script foud: exiting......"
		exit 1
	fi
fi





$APACHE_INIT_SCRIPT stop
clear
echo $APACHE_APXS -c  -I/usr/include/sqlite  -lsqlite3 -lm -lz mod_osa.c 
$APACHE_APXS -c -I/usr/include/sqlite  -lsqlite3 -lm -lz mod_osa.c 
if [ $? -ne 0 ]; then
	exit 1
fi
# echo $APACHE_APXS -i mod_osa.la
$APACHE_APXS -i mod_osa.la
$APACHE_INIT_SCRIPT start

