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

function enableAddon(){
        echo "Enabling $1 addon"
        cd /usr/local/src
        if [ -d $1 ] ; then
			cd $1/bin
			./update.sh
        else
			curl -s -i https://github.com/zorglub42/$1 | egrep "HTTP/.* 200">/dev/null
			if [ $? -ne 0 ] ; then
					echo "$1 does not exist.... don't installing it....."
			else
					git clone https://github.com/zorglub42/$1
					if [ $? -eq 0 ] ; then
									cd $1/bin
									./install.sh
					fi
			fi
        fi
}

if [ ! -f /usr/local/OSA/RunTimeAppliance/shell/container-build ] ; then
	APPLIANCE_ADMIN_PW=""
	BOX_DOMAIN=""
	ADDONS=""
	for p in `echo $*` ; do
		p_name=$(echo $p| sed 's/^\([^:]*\).*/\1/')
		p_value=$(echo $p| sed "s/$p_name://")

		case $p_name in
			-pwd)
				APPLIANCE_ADMIN_PW=$p_value
			;;
			-domain)
				BOX_DOMAIN=$p_value
			;;
			-addon)
				ADDONS=$(echo $ADDONS $p_value)
			;;
		esac
	done
	if [ "$APPLIANCE_ADMIN_PW" != "" ] ; then
	
		cd /usr/local/OSA/RunTimeAppliance/shell/
		cat envvars.sh| sed "s/BOX_DOMAIN=.*/BOX_DOMAIN=\"$BOX_DOMAIN\"/" | sed "s/APPLIANCE_ADMIN_PW=.*/APPLIANCE_ADMIN_PW=\"$APPLIANCE_ADMIN_PW\"/" >vars && mv vars envvars.sh && chmod u+x envvars.sh
		./configure-osa.sh
		cat envvars.sh| sed "s/KEEP_DB=.*/KEEP_DB=1/" >vars && mv vars envvars.sh && chmod u+x envvars.sh
		touch /usr/local/OSA/RunTimeAppliance/shell/container-build
		for addon in `echo $ADDONS` ; do
			enableAddon $addon
		done
	else
		echo "Container must be launched with parameters:"
		echo '	-pwd:XXXX  where XXXX is the admin password'
		echo '	-domain:DDDD where DDDD is the DNS domain (optional)'
		echo '	-addon:OSA-Letsencrypt to enable Letsencrypt addon (optional)'
		echo '	-addon:OSA-VirtualBackend to enable VirtualBackend addon (optional)'
		echo "ex:"
		echo "	docker run osa:mysql-4.0 -pwd:my-password -domain:.zorglub42.fr -addon:OSA-Letsencrypt -addon:OSA-VirtualBackend"
		echo "	or"
		echo "	docker run osa:mysql-4.0 -pwd:my-password"
		exit 1
	fi
fi
