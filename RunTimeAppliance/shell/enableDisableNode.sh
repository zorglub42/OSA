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
 # File Name   : ApplianceManager/RunTimeAppliance/shell/enableDisableNode.sh
 #
 # Created     : 2016-11
 # Authors     : Benoit HERARD <benoit.herard(at)orange.com>
 #
 # Description :
 #      .../...
 #--------------------------------------------------------
 # History     :
 # 1.0.0 - 2016-11-07 : Release of the file
##

#!/bin/bash


function enableRedhatSite(){
	[ -f /etc/httpd/conf.d/$1.conf ] && rm /etc/httpd/conf.d/$1
	ln -s $APACHE_SITES_DEFINITION_DIR/$1  /etc/httpd/conf.d/$1
	chown $APACHE_USER:$APACHE_GROUP    /etc/httpd/conf.d/$1
	chmod 644  /etc/httpd/conf.d/$1.conf
}
function disableRedhatSite(){
	[  -f /etc/httpd/conf.d/$1 ] && rm /etc/httpd/conf.d/$1
}


if [ -f /etc/redhat-release ] ; then
	echo "RedHat system"

	APACHE_INITD_FILE=/etc/init.d/httpd

	[ ! -d $INSTALL_DIR/RunTimeAppliance/apache/conf/sites-availables ]  && mkdir -p $INSTALL_DIR/RunTimeAppliance/apache/conf/sites-availables
	APACHE_SITES_DEFINITION_DIR=$INSTALL_DIR/RunTimeAppliance/apache/conf/sites-availables
	APACHE_ENABLE_SITE=enableRedhatSite
	APACHE_DISABLE_SITE=disableRedhatSite

elif [ -f /etc/debian_version ] ; then
	echo "Debian system"

	APACHE_INITD_FILE=/etc/init.d/apache2
	APACHE_SITES_DEFINITION_DIR=/etc/apache2/sites-available
	APACHE_ENABLE_SITE=a2ensite
	APACHE_DISABLE_SITE=a2dissite

else
	echo "This script only works with debian or redhat"
	exit 1
fi




if [ ! -f $APACHE_SITES_DEFINITION_DIR/nursery-osa-node-$1.conf ] ; then
	echo "Node $1 does not exists"
	exit 1
fi 


case $2 in
	0)
		$APACHE_DISABLE_SITE nursery-osa-node-$1.conf
		$APACHE_INITD_FILE graceful 2>&1
	;;
	1)
		$APACHE_ENABLE_SITE nursery-osa-node-$1.conf
		$APACHE_INITD_FILE graceful 2>&1
	;;
esac
