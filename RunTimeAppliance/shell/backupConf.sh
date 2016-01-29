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
 # File Name   : ApplianceManager/RunTimeAppliance/shell/doAppliance.sh
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
#Generating endpoints entry for HTTP Host
unset http_proxy



# Configuration section #############################################################################
APPLIANCE_INSTALL_DIR=/usr/local/OSA
APPLIANCE_CONFIG_LOC=$APPLIANCE_INSTALL_DIR/RunTimeAppliance/apache/conf/vhAppliance
# End of Configuration section #############################################################################






function enableRedhatSite(){
	[ -f /etc/httpd/conf.d/$1.conf ] && rm /etc/httpd/conf.d/$1.conf
	ln -s $APACHE_SITES_DEFINITION_DIR/$1  /etc/httpd/conf.d/$1.conf
	chown $APACHE_USER:$APACHE_GROUP    /etc/httpd/conf.d/$1.conf
	chmod 644  /etc/httpd/conf.d/$1.conf
}





function backup(){
	cd $APPLIANCE_CONFIG_LOC
	[ ! -d backup ] && mkdir backup
	rm backup/*
	cp /etc/ssl/certs/nursery-osa-node-*.pem backup
	cp /etc/ssl/private/nursery-osa-node-*.key backup
	cp $APACHE_SITES_DEFINITION_DIR/nursery-osa-node-* backup
	cp $APPLIANCE_CONFIG_LOC/applianceManagerServices-node-*.endpoints backup
	cp $APACHE_SITES_ENABLED_DIR/nursery-osa-node-* backup
	cp $APACHE_LISTEN_PORTS backup
	cd $APACHE_SITES_ENABLED_DIR
	ls nursery-osa-node-* > $APPLIANCE_CONFIG_LOC/backup/sites-enabled
}

function restaure(){
	cd $APPLIANCE_CONFIG_LOC/backup
	cp nursery-osa-node-*.pem /etc/ssl/certs/ 
	cp nursery-osa-node-*.key /etc/ssl/private/
	rm $APACHE_SITES_DEFINITION_DIR/nursery-osa-node-*
	cp nursery-osa-node-* $APACHE_SITES_DEFINITION_DIR/
	cp applianceManagerServices-node-*.endpoints $APPLIANCE_CONFIG_LOC/
	cp nursery-osa-node-* $APACHE_SITES_ENABLED_DIR/
	cp nursery-osa-0-ports.conf $APACHE_LISTEN_PORTS
	rm $APACHE_SITES_ENABLED_DIR/nursery-osa-node-* 
	for s in `cat sites-enabled`; do
		$APACHE_ENABLE_SITE $s
	done
	
	
}


function deleteTempFiles(){
	ls /tmp/$$.*  > /dev/null  2>&1
	if [ $? -eq 0 ] ; then
		#echo "Deleting temp files"
		rm /tmp/$$.*
	#else
		#echo "No file delete (/tmp/$$.*)"
	fi
}


function shellExit(){
	deleteTempFiles
	exit $1
}



echo "Starting $0 with $*"

if [ -f /etc/redhat-release ] ; then
	echo "RedHat system"

	APACHE_INITD_FILE=/etc/init.d/httpd

	[ ! -d $APPLIANCE_INSTALL_DIR/RunTimeAppliance/apache/conf/sites-availables ]  && mkdir -p $APPLIANCE_INSTALL_DIR/RunTimeAppliance/apache/conf/sites-availables
	APACHE_SITES_DEFINITION_DIR=$APPLIANCE_INSTALL_DIR/RunTimeAppliance/apache/conf/sites-availables
	APACHE_SITES_ENABLED_DIR=/etc/httpd/conf.d
	APACHE_LISTEN_PORTS=/etc/httpd/conf.d/nursery-osa-0-ports.conf
	APACHE_ENABLE_SITE=enableRedhatSite
	APACHE_LISTEN_PORTS=/etc/httpd/conf.d/nursery-osa-0-ports.conf
	APACHE_LOG_DIR=/var/log/httpd

	touch $APACHE_LISTEN_PORTS
	mkdir -p  /etc/ssl/certs
	mkdir -p   /etc/ssl/private
elif [ -f /etc/debian_version ] ; then
	echo "Debian system"

	APACHE_INITD_FILE=/etc/init.d/apache2
	APACHE_SITES_DEFINITION_DIR=/etc/apache2/sites-available
	APACHE_SITES_ENABLED_DIR=/etc/apache2/sites-enabled
	if [ ! -d /etc/apache2/conf.d ] ; then
		APACHE_LISTEN_PORTS=/etc/apache2/conf-available/nursery-osa-0-ports.conf
	else
		APACHE_LISTEN_PORTS=/etc/apache2/conf.d/nursery-osa-0-ports.conf
	fi
	APACHE_ENABLE_SITE=a2ensite
	#on some install a2ensite and other are not in the PATH....
	PATH=$PATH:/usr/sbin
	export PATH

else
	echo "This script only works with debian or redhat"
	shellExit 1
fi


case $1 in
	-backup)
		backup
	;;
	-restaure)
		restaure
	;;
	*)
		echo "Usage: "`basename $0`" -backup|-restaure"
		shellExit 1
	;;
esac;
shellExit 0	
