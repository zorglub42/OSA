#!/bin/bash
function generatePassword(){
	echo date +%s | sha256sum | base64 | head -c 32 ; echo
}
APPLIANCE_MYSQL_PW=`generatePassword`
ROOT_MYSQL_PW=`generatePassword`
BOX_DOMAIN="$2"	
APPLIANCE_ADMIN_PW=$1

cat<<EOF | docker build -t zorglub42:osa  -

	FROM ubuntu:16.04
	MAINTAINER benoit.herard@orange.com


	RUN apt-get update
	RUN echo "mysql-server mysql-server/root_password password $ROOT_MYSQL_PW"| debconf-set-selections  && echo "mysql-server mysql-server/root_password_again password $ROOT_MYSQL_PW"|debconf-set-selections && apt-get install -y mysql-server apache2 php php-mysql php-curl libapache2-mod-php openssl curl zip autoconf libmysqlclient-dev apache2-dev git inetutils-ping cron sudo wget vim
	RUN cd /usr/local/src && git clone https://github.com/zorglub42/OSA
	RUN cd /usr/local/src/OSA && ./install.sh -m /usr/local/OSA

	RUN cat /etc/apache2/ports.conf |sed 's/80/81/'|sed 's/443/8443/'>ports && mv ports /etc/apache2/ports.conf
	RUN cat /etc/apache2/sites-available/000-default.conf |sed 's/80/81/'>site && mv site /etc/apache2/sites-available/000-default.conf
	RUN cat /etc/apache2/sites-available/default-ssl.conf |sed 's/443/8443/'>site && mv site /etc/apache2/sites-available/default-ssl.conf


	WORKDIR /usr/local/OSA/RunTimeAppliance/shell
	RUN cat envvars.sh| sed "s/BOX_DOMAIN=.*/BOX_DOMAIN=\"$BOX_DOMAIN\"/" | sed "s/ROOT_MYSQL_PW=.*/ROOT_MYSQL_PW=\"$ROOT_MYSQL_PW\"/" | sed "s/APPLIANCE_MYSQL_PW=.*/APPLIANCE_MYSQL_PW=\"$APPLIANCE_MYSQL_PW\"/" | sed "s/APPLIANCE_ADMIN_PW=.*/APPLIANCE_ADMIN_PW=\"$APPLIANCE_ADMIN_PW\"/" | sed "s/HTTP_VHOST_PORT=.*/HTTP_VHOST_PORT=80/" | sed "s/HTTPS_VHOST_PORT=.*/HTTPS_VHOST_PORT=443/"| sed "s/HTTPS_ADMIN_VHOST_NAME=.*/HTTPS_ADMIN_VHOST_NAME=localhost/">vars && mv vars envvars.sh && chmod u+x envvars.sh
	RUN service mysql start && service apache2 start && ./configure-osa.sh
	RUN cat envvars.sh| sed "s/KEEP_DB=.*/KEEP_DB=1/" >vars && mv vars envvars.sh && chmod u+x envvars.sh
	RUN printf "service mysql restart\nservice apache2 restart\nservice cron restart\n\ntail -f /dev/null\n">/usr/local/bin/start-osa-container.sh ;chmod u+x /usr/local/bin/start-osa-container.sh
	ENTRYPOINT /usr/local/bin/start-osa-container.sh
EOF

