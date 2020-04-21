
FROM ubuntu:18.04
MAINTAINER benoit.herard@orange.com

ARG DEBIAN_FRONTEND=noninteractive




RUN apt-get update && echo "mysql-server mysql-server/root_password password ZDgzZjMyMGNiMWJiMDA3MWYxZjQzODJi"| debconf-set-selections  && echo "mysql-server mysql-server/root_password_again password ZDgzZjMyMGNiMWJiMDA3MWYxZjQzODJi"|debconf-set-selections && apt-get install -y apache2 php php-curl libapache2-mod-php openssl curl zip autoconf zlib1g-dev zlib1g apache2-dev git inetutils-ping net-tools cron sudo wget vim libjson-c3 libjson-c-dev  mysql-server php-mysql libmysqlclient-dev; \
	cd /usr/local/src && git clone https://github.com/zorglub42/OSA; \
	cd /usr/local/src/OSA && ./install.sh -m -rdbms mysql /usr/local/OSA; \
	# Pre installing Addons; \
	cd /usr/local/src && git clone https://github.com/zorglub42/OSA-VirtualBackend; \
	cd /usr/local/src && git clone https://github.com/zorglub42/OSA-Letsencrypt ; \
	cd /usr/local/src/OSA-Letsencrypt && curl -s  https://dl.eff.org/certbot-auto -o certbot-auto && chmod u+x certbot-auto && ./certbot-auto -n --os-packages-only && ./certbot-auto certificates; \
	# Disabling default apache2 conf;\
	cat /etc/apache2/ports.conf |sed 's/80/81/'|sed 's/443/8443/'>ports && mv ports /etc/apache2/ports.conf; \
	cat /etc/apache2/sites-available/000-default.conf |sed 's/80/81/'>site && mv site /etc/apache2/sites-available/000-default.conf; \
	cat /etc/apache2/sites-available/default-ssl.conf |sed 's/443/8443/'>site && mv site /etc/apache2/sites-available/default-ssl.conf; \
	a2enmod cache_socache  socache_shmcb

ADD start-osa-container.sh /usr/local/bin/
WORKDIR /usr/local/OSA/RunTimeAppliance/shell
RUN cat envvars.sh| sed "s/ROOT_MYSQL_PW=.*/ROOT_MYSQL_PW=\"ZDgzZjMyMGNiMWJiMDA3MWYxZjQzODJi\"/" | sed "s/APPLIANCE_MYSQL_PW=.*/APPLIANCE_MYSQL_PW=\"ZDgzZjMyMGNiMWJiMDA3MWYxZjQzODJi\"/" | sed "s/HTTP_VHOST_PORT=.*/HTTP_VHOST_PORT=80/" | sed "s/HTTPS_VHOST_PORT=.*/HTTPS_VHOST_PORT=443/"| sed "s/HTTPS_ADMIN_VHOST_NAME=.*/HTTPS_ADMIN_VHOST_NAME=localhost/">vars && mv vars envvars.sh && chmod u+x envvars.sh; \
	curl -s https://raw.githubusercontent.com/zorglub42/OSA/master/docker/configure-osa-container.sh >/usr/local/bin/configure-osa-container.sh ; \
	chmod u+x /usr/local/bin/configure-osa-container.sh; \ 
	chmod u+x /usr/local/bin/start-osa-container.sh; \
	tar cvzf /root/mysql.def.tgz /var/lib/mysql ;
ENTRYPOINT ["/bin/bash", "/usr/local/bin/start-osa-container.sh"]
