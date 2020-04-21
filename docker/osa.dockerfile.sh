#!/bin/bash
function generatePassword(){
	echo date +%s | sha256sum | base64 | head -c 32 ; echo
}

function getRootImage(){
	if [ "$DOCKER_FROM" == "" ] ; then
		uname -a | grep " arm">/dev/null
		if [ $? -ne 0 ] ; then
			DOCKER_FROM="ubuntu:18.04"
			JSON_PACKAGE="libjson-c3 libjson-c-dev"
		elif [ -f "/etc/os-release" ] ; then
			grep "stretch" "/etc/os-release" >/dev/null
			if [ $? -eq 0 ] ; then
				DOCKER_FROM="schachr/raspbian-stretch"
				RDBMS_PACKAGE="mysql-server $PHP-mysql default-libmysqlclient-dev"
				JSON_PACKAGE="libjson-c3 libjson-c-dev"
			else
				grep "jessie" "/etc/os-release">/dev/null
				if [ $? -eq 0 ] ; then
					DOCKER_FROM="resin/rpi-raspbian"
					PHP=php5
					RDBMS_PACKAGE="mysql-server $PHP-mysql libmysqlclient-dev"
					JSON_PACKAGE="libjson-c2 libjson-c-dev"
				fi
			fi
		else
			echo "Unable to find a root docker image"
			exit 1
		fi
	fi
}

APPLIANCE_MYSQL_PW=`generatePassword`
ROOT_MYSQL_PW=`generatePassword`
PHP=php
RDBMS_PACKAGE="mysql-server $PHP-mysql libmysqlclient-dev"
RDBMS=mysql
SAV_DBMS_INSTALL="tar cvzf /root/mysql.def.tgz /var/lib/mysql "
START_RDBMS="find /var/lib/mysql -type f -exec touch {} \; && chown -R mysql:mysql /var/lib/mysql && service mysql restart"

getRootImage

#  Parse command line arguments
while [ "$1" != "" ] ; do
	if [ "$1" == "-rdbms" ] ; then
		if [ "$2" == "mysql" ] ; then
			echo "Setting mysql as RDBMS"
		elif [ "$2" == "sqlite" ] ; then
			echo "Setting sqlite as RDBMS"
			RDBMS_PACKAGE="sqlite3 $PHP-sqlite3 libsqlite3-dev"
			RDBMS=sqlite
			START_RDBMS="/bin/true"
			SAV_DBMS_INSTALL=""
		else
			echo "Invalid RDBMS System $2"
			exit 1
		fi
	fi
	shift
	shift
done
echo "I'll use $RDBMS as RDBMS"

if [ "$http_proxy" != "" -o "$https_proxy" != "" ]; then
	PROXIES=`printf "ENV	http_proxy=$http_proxy\n\tENV	https_proxy=$https_proxy"`
fi

:>start-osa-container.sh
if [ "$RDBMS" == "mysql" ] ; then
	cat <<EOF >>start-osa-container.sh
if [ ! -d /var/lib/mysql/mysql ]; then
	cd /
   	tar xvzf /root/mysql.def.tgz
	cd -
fi
EOF
fi
cat <<EOF >>start-osa-container.sh
$START_RDBMS
service apache2 restart
service cron restart
echo "'\$*'"
/usr/local/bin/configure-osa-container.sh "\$*"||exit 1

tail -f /dev/null
EOF

VERSION=$(curl -s https://raw.githubusercontent.com/zorglub42/OSA/master/ApplianceManager.php/include/Constants.php| grep version|awk -F '"' '{print $4}')
[ "$VERSION" == "" ] && echo "Unable to get version" && exit 1
#| docker build -t osa:$RDBMS-$VERSION  -
cat<<EOF > osa.dockerfile 

FROM $DOCKER_FROM
MAINTAINER benoit.herard@orange.com

ARG DEBIAN_FRONTEND=noninteractive


$PROXIES

RUN apt-get update && echo "mysql-server mysql-server/root_password password $ROOT_MYSQL_PW"| debconf-set-selections  && echo "mysql-server mysql-server/root_password_again password $ROOT_MYSQL_PW"|debconf-set-selections && apt-get install -y apache2 $PHP $PHP-curl libapache2-mod-$PHP openssl curl zip autoconf zlib1g-dev zlib1g apache2-dev git inetutils-ping net-tools cron sudo wget vim $JSON_PACKAGE  $RDBMS_PACKAGE; \\
	cd /usr/local/src && git clone https://github.com/zorglub42/OSA; \\
	cd /usr/local/src/OSA && ./install.sh -m -rdbms $RDBMS /usr/local/OSA; \\
	# Pre installing Addons; \\
	cd /usr/local/src && git clone https://github.com/zorglub42/OSA-VirtualBackend; \\
	cd /usr/local/src && git clone https://github.com/zorglub42/OSA-Letsencrypt ; \\
	cd /usr/local/src/OSA-Letsencrypt && curl -s  https://dl.eff.org/certbot-auto -o certbot-auto && chmod u+x certbot-auto && ./certbot-auto -n --os-packages-only && ./certbot-auto certificates; \\
	# Disabling default apache2 conf;\\
	cat /etc/apache2/ports.conf |sed 's/80/81/'|sed 's/443/8443/'>ports && mv ports /etc/apache2/ports.conf; \\
	cat /etc/apache2/sites-available/000-default.conf |sed 's/80/81/'>site && mv site /etc/apache2/sites-available/000-default.conf; \\
	cat /etc/apache2/sites-available/default-ssl.conf |sed 's/443/8443/'>site && mv site /etc/apache2/sites-available/default-ssl.conf; \\
	a2enmod cache_socache  socache_shmcb

ADD start-osa-container.sh /usr/local/bin/
WORKDIR /usr/local/OSA/RunTimeAppliance/shell
RUN cat envvars.sh| sed "s/ROOT_MYSQL_PW=.*/ROOT_MYSQL_PW=\"$ROOT_MYSQL_PW\"/" | sed "s/APPLIANCE_MYSQL_PW=.*/APPLIANCE_MYSQL_PW=\"$APPLIANCE_MYSQL_PW\"/" | sed "s/HTTP_VHOST_PORT=.*/HTTP_VHOST_PORT=80/" | sed "s/HTTPS_VHOST_PORT=.*/HTTPS_VHOST_PORT=443/"| sed "s/HTTPS_ADMIN_VHOST_NAME=.*/HTTPS_ADMIN_VHOST_NAME=localhost/">vars && mv vars envvars.sh && chmod u+x envvars.sh; \\
	curl -s https://raw.githubusercontent.com/zorglub42/OSA/master/docker/configure-osa-container.sh >/usr/local/bin/configure-osa-container.sh ; \\
	chmod u+x /usr/local/bin/configure-osa-container.sh; \\ 
	chmod u+x /usr/local/bin/start-osa-container.sh; \\
	$SAV_DBMS_INSTALL;

ENTRYPOINT ["/bin/bash", "/usr/local/bin/start-osa-container.sh"]
EOF
