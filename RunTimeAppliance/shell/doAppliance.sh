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
APPLIANCE_LOG_DIR=/var/log/OSA
APPLIANCE_CONFIG_LOC=$APPLIANCE_INSTALL_DIR/RunTimeAppliance/apache/conf/vhAppliance
APPLIANCE_LOCAL_SERVER="http://127.0.0.1:82"
APPLIANCE_LOCAL_USER=""
APPLIANCE_LOCAL_PWD=""
HTTP_FQDN="r-lnx-jmjb0521"
HTTPS_FQDN="r-lnx-jmjb0521"
USE_HTTP=1
USE_HTTPS=1
# End of Configuration section #############################################################################


















if [ -f /etc/init.d/httpd ] ; then
	APACHE_INIT_SCRIPT=/etc/init.d/httpd
else
	if [ -f /etc/init.d/apache2 ] ; then
		APACHE_INIT_SCRIPT=/etc/init.d/apache2
	else
		echo "No /etc/init.d/httpd or /etc/init.d/apache2 script found exiting......"
		exit 1
	fi
fi


https=0;
http=0;

function generateConf(){
		
		if [ "$3" != "1" -a "$3" != "0" ] ; then 
			echo "Prameter 3 of generateConf (BasicAuth enabled) should be 0 or 1";
			exit 1
		fi
		if [ "$4" != "1" -a "$4" != "0" ] ; then 
			echo "Prameter 4 of generateConf (CookieAuthAuth enabled) should be 0 or 1";
			exit 1
		fi

		echo "Generating conf for $*"
        wget --user="$APPLIANCE_LOCAL_USER" --password="$APPLIANCE_LOCAL_PWD" "$APPLIANCE_LOCAL_SERVER/ApplianceManager/scripts/generateApacheConfig.php?domain=$1&BasicAuthEnabled=$3&CookieAuthEnabled=$4&node=$2&serverPrefix=$5" -O /tmp/applianceManagerServices.endpoints 2>&1
        [ ! -f $APPLIANCE_CONFIG_LOC/applianceManagerServices-node-$2.endpoints ] && touch $APPLIANCE_CONFIG_LOC/applianceManagerServices-node-$2.endpoints
        diffCount=`diff $APPLIANCE_CONFIG_LOC/applianceManagerServices-node-$2.endpoints /tmp/applianceManagerServices.endpoints | wc -l`
        
        echo "dc=$diffCount"
        if [ $diffCount -ne 0 ] ; then
                mv /tmp/applianceManagerServices.endpoints $APPLIANCE_CONFIG_LOC/applianceManagerServices-node-$2.endpoints
                Rc=1;
        else
                Rc=0;
        fi
        return $Rc
}
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



echo "Starting $0 with $*"
DO_BACKUP=1
for p in $* ; do
	if [ $p == "-nobackup" ] ; then
		DO_BACKUP=0
	fi
done

if [ $DO_BACKUP -eq 1 ] ; then
	`dirname $0`/backupConf.sh -backup
fi


if [ "$1" == "D" -o "$1" == "U"  -o "$1" == "C" ] ; then
	if [ "$1" == "U"  -o "$1" == "C" ] ; then
		curl -s --user "$APPLIANCE_LOCAL_USER:$APPLIANCE_LOCAL_PWD" $APPLIANCE_LOCAL_SERVER/ApplianceManager/nodes/$2>/tmp/$$.nodes
	else
		:>/tmp/$$.nodes
	fi
elif [ "$1" ==  "" -o "$1" == "-nobackup" ] ; then
	curl -s --user "$APPLIANCE_LOCAL_USER:$APPLIANCE_LOCAL_PWD" $APPLIANCE_LOCAL_SERVER/ApplianceManager/nodes/>/tmp/$$.nodes
else
	touch /tmp/$$.nodes
	for node in `echo $1`; do
		curl -s --user "$APPLIANCE_LOCAL_USER:$APPLIANCE_LOCAL_PWD" $APPLIANCE_LOCAL_SERVER/ApplianceManager/nodes/$node>>/tmp/$$.nodes
		echo "" >>/tmp/$$.nodes
	done
fi
echo "" >>/tmp/$$.nodes




grep '"error"' /tmp/$$.nodes>/dev/null
if [ $? -eq 0 ] ; then
	echo "An error occursed while downloding node list"; 
	shellExit 1
fi
apacheReload=1;


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
	echo $line | grep "isCookieAuthEnabled">/dev/null
	if [ $? -eq 0 ] ; then
		isCookieAuthEnabled=`echo $line | sed 's/[^0-9]*\([0-9]*\).*/\1/'` 
		echo "isCookieAuthEnabled=$isCookieAuthEnabled";
	fi
	echo $line | grep "isBasicAuthEnabled">/dev/null
	if [ $? -eq 0 ] ; then
		isBasicAuthEnabled=`echo $line | sed 's/[^0-9]*\([0-9]*\).*/\1/'` 
		echo "isBasicAuthEnabled=$isBasicAuthEnabled";
	fi
	echo $line | grep "isHTTPS">/dev/null
	if [ $? -eq 0 ] ; then
		isHTTPS=`echo $line | sed 's/[^0-9]*\([0-9]*\).*/\1/'` 
		echo "isHTTPS=$isHTTPS";
	fi
	echo $line | grep "port">/dev/null
	if [ $? -eq 0 ] ; then
		port=`echo $line | sed 's/[^0-9]*\([0-9]*\).*/\1/'` 
		echo "port=$port";
	fi

	if [ "$line" == "}" -o "$line" == "}," ] ; then
		if [ $isHTTPS -eq 1 ] ; then
			if [ $port -eq 443 ] ; then
				serverPrefix="https://$serverFQDN"
			else
				serverPrefix="https://$serverFQDN:$port"
			fi
	else
			if [ $port -eq 80 ] ; then
				serverPrefix="http://$serverFQDN"
			else
				serverPrefix="http://$serverFQDN:$port"
			fi
	fi
				
		generateConf $serverFQDN $nodeName $isBasicAuthEnabled $isCookieAuthEnabled $serverPrefix
		rc=$?
		if [ $rc -eq 1 ] ; then
			apacheReload=1;
		else
			apacheReload=0
			echo "RC=$?"
		fi
	fi
done < /tmp/$$.nodes




apachectl configtest 2>&1
if [ $? -ne 0 ] ; then
	`dirname $0`/backupConf.sh -restaure >/dev/null 2>&1
	shellExit 2
fi

echo reload=$apacheReload
if [ $apacheReload -eq 1 ] ; then
	echo restart apache
	#$APACHE_INIT_SCRIPT graceful 2>&1
	$APACHE_INIT_SCRIPT reload 2>&1
	sleep 1
	$APACHE_INIT_SCRIPT status 2>&1
	rc=$?
	if [ $rc -ne 0 ] ; then
		`dirname $0`/backupConf.sh -restaure >/dev/null 2>&1
		$APACHE_INIT_SCRIPT start >/dev/null 2>&1
		shellExit 2
	fi

fi
chmod 666 $APPLIANCE_LOG_DIR/*.log
shellExit 0
