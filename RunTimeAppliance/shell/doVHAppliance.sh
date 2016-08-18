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
 # File Name   : ApplianceManager/RunTimeAppliance/shell/doVHAppliance.sh
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
APPLIANCE_LOG_DIR=/var/log/OSA
APPLIANCE_LOCAL_SERVER="http://127.0.0.1:82"
APPLIANCE_LOCAL_USER=""
APPLIANCE_LOCAL_PWD=""
# End of Configuration section #############################################################################

removeLogRotateForAllNodes(){
	
	startLine=`grep -n "##Nodes section START" $EXEC_DIR/logrotate.conf| awk -F: '{print $1}'`
	if [ "$startLine" != "" ] ; then
		head -$startLine $EXEC_DIR/logrotate.conf > /tmp/$$.logrotate.conf
		mv /tmp/$$.logrotate.conf $EXEC_DIR/logrotate.conf

	else
		echo "Log rotate configuration is corrupted!!!!"
	fi
	
}

removeLogRotateForNode(){
	startLine=`grep -n "##Node $1 START" $EXEC_DIR/logrotate.conf| awk -F: '{print $1}'`
	if [ "$startLine" != "" ] ; then
		startLine=`expr $startLine - 1`
		endLine=`grep -n "##Node $1 END" $EXEC_DIR/logrotate.conf| awk -F: '{print $1}'`
		if [ "$endLine" != "" ] ; then
			lineTot=`cat $EXEC_DIR/logrotate.conf|wc -l`
			endLine=`expr $endLine`
			endLine=`expr $lineTot - $endLine`
			head -$startLine $EXEC_DIR/logrotate.conf > /tmp/$$.logrotate.conf
			tail -$endLine $EXEC_DIR/logrotate.conf >> /tmp/$$.logrotate.conf
			mv /tmp/$$.logrotate.conf $EXEC_DIR/logrotate.conf
		else
			echo "Log rotate configuration is corrupted (end section for node not found)!!!!"
		fi
	else
		echo "Log rotate configuration is corrupted  (start section for node not found)!!!!"
	fi
		
}


addLogRotateForNode(){
	cat <<EOF > /tmp/$$.logrotate.conf
##Node $1 START
$APPLIANCE_LOG_DIR/$1/main.access.log  {
    rotate 5
    daily
    postrotate
		touch  $APPLIANCE_LOG_DIR/$1/main.access.log
    endscript
}
$APPLIANCE_LOG_DIR/$1/main.error.log  {
    rotate 5
    daily
    postrotate
		touch  $APPLIANCE_LOG_DIR/$1/main.error.log
    endscript
}
$APPLIANCE_LOG_DIR/$1/rewrite.log  {
    rotate 5
    daily
    postrotate
		touch  $APPLIANCE_LOG_DIR/$1/rewrite.log
    endscript
}
##Node $1 END
EOF
cat /tmp/$$.logrotate.conf >> $EXEC_DIR/logrotate.conf
}


######################################################################
# deleteTempFiles
######################################################################
# delete temporary files
######################################################################
function deleteTempFiles(){
	ls /tmp/$$.* > /dev/null
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

function enableRedhatSite(){
	[ -f /etc/httpd/conf.d/$1.conf ] && rm /etc/httpd/conf.d/$1.conf
	ln -s $APACHE_SITES_DEFINITION_DIR/$1  /etc/httpd/conf.d/$1.conf
	chown $APACHE_USER:$APACHE_GROUP    /etc/httpd/conf.d/$1.conf
	chmod 644  /etc/httpd/conf.d/$1.conf
}

function generateCerts(){
	cat $APPLIANCE_INSTALL_DIR/RunTimeAppliance/apache/conf/samples/standard/ssleay.cnf | sed "s/@HostName@/$1/g" >  $APPLIANCE_INSTALL_DIR/RunTimeAppliance/apache/conf/samples/standard/nursery-osa-node-$2.cnf
	openssl req -config $APPLIANCE_INSTALL_DIR/RunTimeAppliance/apache/conf/samples/standard/nursery-osa-node-$2.cnf  -new -x509  -days 3650 -nodes  -out  /etc/ssl/certs/nursery-osa-node-$2.pem  -keyout  /etc/ssl/private/nursery-osa-node-$2.key 2>&1 >/dev/null
	chmod 600   /etc/ssl/certs/nursery-osa-node-$2.pem
	chmod 600   /etc/ssl/private/nursery-osa-node-$2.key
	
	
	curl -s -X POST -k   --form "files[]=@/etc/ssl/certs/nursery-osa-node-$2.pem" --user "$OSA_USAGE_USER:$OSA_ADMIN_PWD"  $APPLIANCE_LOCAL_SERVER/ApplianceManager/nodes/$2/cert	>/dev/null
	curl -s -X POST -k   --form "files[]=@/etc/ssl/private/nursery-osa-node-$2.key" --user "$OSA_USAGE_USER:$OSA_ADMIN_PWD"  $APPLIANCE_LOCAL_SERVER/ApplianceManager/nodes/$2/privateKey>	/dev/null

}


function delFiles(){
	echo "Cleaning $1"
	ls $1 2>/dev/null >/dev/null
	if [ $? -eq 0 ] ; then
		rm $1
	fi
}


function configureApachePorts(){
	

		LADDR=`echo "$LOCAL_IP"| sed 's/\*/\\\\*/g'` 
		grep -v "$LADDR:$PORT" $APACHE_LISTEN_PORTS>/tmp/$$.port
		cat /tmp/$$.port >$APACHE_LISTEN_PORTS
		
		realIp=`getRealIp "$LOCAL_IP"`
		grep "$realIp:$PORT" /tmp/$$.APACHE_LISTENING>/dev/null
		if [ $? -ne 0 ] ; then

			echo "Listen $LOCAL_IP:$PORT" >>$APACHE_LISTEN_PORTS
		fi
		echo "NameVirtualHost $LOCAL_IP:$PORT" >>$APACHE_LISTEN_PORTS
}


function getRealIp(){
	if [ "$1" == '*' ] ; then
		echo '*'
	else
		arp $1 | sed 's/.*(\(.*\)).*/\1/'
	fi
}

EXEC_DIR=`dirname $0`
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
	APACHE_LOG_DIR=/var/log/apache2
	#on some install a2ensite and other are not in the PATH....
	PATH=$PATH:/usr/sbin
	export PATH
	
	
	
	#Get list of "default" listening interface:port fro apache

	#Get BOX ips 
	APACHE_DEFAULT_LISTENING=""
	BOX_IPS=""
	for ip in `ifconfig| grep "inet adr"|awk -F: '{print $2}'|awk '{print $1}'` ; do
		BOX_IPS="$BOX_IPS $ip"
	done
	
	#Generate couple ip:port for each Listen PORT_NUM in ports.conf
	for port in `grep Listen /etc/apache2/ports.conf | grep -v ":"| awk '{print $2}'| sort -u` ; do
		for ip in $BOX_IPS ; do
			APACHE_DEFAULT_LISTENING="$APACHE_DEFAULT_LISTENING $ip:$port"
		done
	done
	
	#
	for l in `grep Listen /etc/apache2/ports.conf | grep ":"| awk '{print $2}'| sort -u` ; do
		ip=`echo $l|awk -F: '{print $1}'`
		port=`echo $l|awk -F: '{print $2}'`
		echo "searching ip for  $i"
		realIp=`getRealIp "$ip"`
		APACHE_DEFAULT_LISTENING="$APACHE_DEFAULT_LISTENING $realIp:$port"
	done
	

else
	echo "This script only works with debian or redhat"
	shellExit 1
fi
echo $APACHE_DEFAULT_LISTENING>/tmp/$$.APACHE_LISTENING


echo "Starting $0 with $*"

$EXEC_DIR/backupConf.sh -backup
if [ "$1" == "D" -o "$1" == "U"  -o "$1" == "C" ] ; then
	delFiles "/etc/ssl/certs/nursery-osa-node-$2-ca.pem"
	delFiles "/etc/ssl/certs/nursery-osa-node-$2-chain.pem"
	delFiles "/etc/ssl/certs/nursery-osa-node-$2.pem"
	delFiles "/etc/ssl/private/nursery-osa-node-$2.key"
	delFiles "$APACHE_SITES_DEFINITION_DIR/nursery-osa-node-$2"
	delFiles "$APACHE_SITES_DEFINITION_DIR/nursery-osa-node-$2.conf"
	delFiles "/etc/ApplianceManager/applianceManagerServices-node-$2.endpoints"
	delFiles "$APPLIANCE_CONFIG_LOC/applianceManagerServices-node-$2.endpoints"
	delFiles "$APACHE_SITES_ENABLED_DIR/nursery-osa-node-$2"
	delFiles "$APACHE_SITES_ENABLED_DIR/nursery-osa-node-$2.conf"
	removeLogRotateForNode $2

	if [ "$1" == "U"  -o "$1" == "C" ] ; then
		curl -s --user "$APPLIANCE_LOCAL_USER:$APPLIANCE_LOCAL_PWD" $APPLIANCE_LOCAL_SERVER/ApplianceManager/nodes/$2>/tmp/$$.nodes
	else
		[ -d $APPLIANCE_LOG_DIR/$2 ] && rm -rf $APPLIANCE_LOG_DIR/$2
		:>/tmp/$$.nodes
		$APACHE_INITD_FILE graceful 2>&1
	fi
elif [ "$1" ==  "" ] ; then
	delFiles "/etc/ssl/certs/nursery-osa-node-*.pem"
	delFiles "/etc/ssl/private/nursery-osa-node-*.key"
	delFiles "$APACHE_SITES_DEFINITION_DIR/nursery-osa-node-*"
	delFiles "/etc/ApplianceManager/applianceManagerServices-node-*.endpoints"
	delFiles "$APPLIANCE_CONFIG_LOC/applianceManagerServices-node-*.endpoints"
	delFiles "$APACHE_SITES_ENABLED_DIR/nursery-osa-node-*"
	removeLogRotateForAllNodes
	
	curl -s --user "$APPLIANCE_LOCAL_USER:$APPLIANCE_LOCAL_PWD" $APPLIANCE_LOCAL_SERVER/ApplianceManager/nodes/>/tmp/$$.nodes
fi



echo "" >>/tmp/$$.nodes

grep '"error"' /tmp/$$.nodes>/dev/null
if [ $? -eq 0 ] ; then
	echo "An error occursed while downloding node list"; 
	shellExit 1
fi


while read line  
do   
	echo $line | grep "nodeName">/dev/null
	if [ $? -eq 0 ] ; then
		nodeName=`echo $line | awk -F\" '{print $4}'`
		echo "nodeName=$nodeName";
	fi
	echo $line | grep "serverFQDN">/dev/null
	if [ $? -eq 0 ] ; then
		serverFQDN=`echo $line | awk -F\" '{print $4}'`
		echo "serverFQDN=$serverFQDN";
	fi
	echo $line | grep "isHTTPS">/dev/null
	if [ $? -eq 0 ] ; then
		isHTTPS=`echo $line | sed 's/[^0-9]*\([0-9]*\).*/\1/'`
		echo "isHTTPS=$isHTTPS";
	fi
	echo $line  | grep "port">/dev/null
	if [ $? -eq 0 ] ; then
		PORT=`echo $line | sed 's/[^0-9]*\([0-9]*\).*/\1/'`
		echo "port=$PORT";
	fi
	echo $line  | grep "localIP">/dev/null
	if [ $? -eq 0 ] ; then
		LOCAL_IP=`echo $line| awk -F\" '{print $4}'`
		echo "localIP=$LOCAL_IP";
	fi
	if [ "$line" == "}" -o "$line" == "}," ] ; then
		if [ "$isHTTPS" == "1" ] ; then
			curl -s --user "$APPLIANCE_LOCAL_USER:$APPLIANCE_LOCAL_PWD" $APPLIANCE_LOCAL_SERVER/ApplianceManager/nodes/$nodeName/cert>/etc/ssl/certs/nursery-osa-node-$nodeName.pem
			curl -s --user "$APPLIANCE_LOCAL_USER:$APPLIANCE_LOCAL_PWD" $APPLIANCE_LOCAL_SERVER/ApplianceManager/nodes/$nodeName/privateKey>/etc/ssl/private/nursery-osa-node-$nodeName.key
			curl -s --user "$APPLIANCE_LOCAL_USER:$APPLIANCE_LOCAL_PWD" $APPLIANCE_LOCAL_SERVER/ApplianceManager/nodes/$nodeName/ca>/etc/ssl/certs/nursery-osa-node-$nodeName-ca.pem
			curl -s --user "$APPLIANCE_LOCAL_USER:$APPLIANCE_LOCAL_PWD" $APPLIANCE_LOCAL_SERVER/ApplianceManager/nodes/$nodeName/chain>/etc/ssl/certs/nursery-osa-node-$nodeName-chain.pem
			if [ -s  /etc/ssl/certs/nursery-osa-node-$nodeName.pem -a -s /etc/ssl/private/nursery-osa-node-$nodeName.key  ] ; then
				chmod 600 /etc/ssl/certs/nursery-osa-node-$nodeName.pem
				chmod 600 /etc/ssl/private/nursery-osa-node-$nodeName.key
			else
				generateCerts $serverFQDN $nodeName
			fi
			if [ -s  /etc/ssl/certs/nursery-osa-node-$nodeName-ca.pem ] ; then
				chmod 600 /etc/ssl/certs/nursery-osa-node-$nodeName-ca.pem
			fi
			if [ -s  /etc/ssl/certs/nursery-osa-node-$nodeName-chain.pem ] ; then
				chmod 600 /etc/ssl/certs/nursery-osa-node-$nodeName-chain.pem
			fi
		fi
		[ ! -d $APPLIANCE_LOG_DIR/$nodeName ] && mkdir -p $APPLIANCE_LOG_DIR/$nodeName
		
		configureApachePorts
		curl -s --user "$APPLIANCE_LOCAL_USER:$APPLIANCE_LOCAL_PWD" $APPLIANCE_LOCAL_SERVER/ApplianceManager/nodes/$nodeName/virtualHost>$APACHE_SITES_DEFINITION_DIR/nursery-osa-node-$nodeName.conf
		$APACHE_ENABLE_SITE nursery-osa-node-$nodeName.conf
		echo touch $APPLIANCE_CONFIG_LOC/applianceManagerServices-node-$nodeName.endpoints
		touch $APPLIANCE_CONFIG_LOC/applianceManagerServices-node-$nodeName.endpoints
		ln -s $APPLIANCE_CONFIG_LOC/applianceManagerServices-node-$nodeName.endpoints /etc/ApplianceManager/applianceManagerServices-node-$nodeName.endpoints
		addLogRotateForNode $nodeName
	fi
	
done < /tmp/$$.nodes



$EXEC_DIR/doAppliance.sh $* -nobackup
shellExit $?
