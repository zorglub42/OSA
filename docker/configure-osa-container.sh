#!/bin/bash
##--------------------------------------------------------
 # Module Name : ApplianceManager
 # Version : 1.0.0
 #
 # Software Name : OpenServicesAccess
 # Version : 1.0
 #
 # Copyright (c) 2011 – 2018 Orange
 # This software is distributed under the Apache 2 license
 # <http://www.apache.org/licenses/LICENSE-2.0.html>
 #
 #--------------------------------------------------------
 # File Name   : docker/start-osa-container.sh
 #
 # Created     : 2018-03
 # Authors     : Benoit HERARD <benoit.herard(at)orange.com>
 #
 # Description :
 #      .../...
 #--------------------------------------------------------
 # History     :
 # 1.0.0 - 2018-03-27 : Release of the file
##
#!/bin/bash
if [ ! -f /usr/local/OSA/RunTimeAppliance/shell/container-build ] ; then
	if [ "$1" != "" ] ; then
		BOX_DOMAIN="$2"	
		APPLIANCE_ADMIN_PW=$1
	
		cd /usr/local/OSA/RunTimeAppliance/shell/
		cat envvars.sh| sed "s/BOX_DOMAIN=.*/BOX_DOMAIN=\"$BOX_DOMAIN\"/" | sed "s/APPLIANCE_ADMIN_PW=.*/APPLIANCE_ADMIN_PW=\"$APPLIANCE_ADMIN_PW\"/" >vars && mv vars envvars.sh && chmod u+x envvars.sh
		./configure-osa.sh
		cat envvars.sh| sed "s/KEEP_DB=.*/KEEP_DB=1/" >vars && mv vars envvars.sh && chmod u+x envvars.sh
		touch /usr/local/OSA/RunTimeAppliance/shell/container-build
	else
		echo "Container must be lauch with parameters:"
		echo '	$1: admin-password'
		echo '	$2: box-domain (optional)'
		echo "ex:"
		echo "	docker run osa:mysql-4.0 my-password .zorglub42.fr"
		echo "	or"
		echo "	docker run osa:mysql-4.0 my-password"
		exit 1
	fi
fi
		