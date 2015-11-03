# OSA
Open Services Access: Apaches RP management tool

OSA is a tool to manage in click-and-play mode an Apache reverse proxy
It's based on apache 2 web server.

In addition, it offers some extra functionalities like dual mode authentication (HTTP basic or cookie) for a same resource set or quota management.

OSA is distributed under Apache2 licence

##Install
Install and configuration scripts are developped for debian, but, with few changes, should be compliant with RedHat too...

To install some prerequisite are needed
**IMPORTANT NOTE:** Apply following MySQL instrustruction on if you plan to connect databe on a remote server.
  - root mysql user on target server should be able to create/delete users and databases, with a connection from the server where OSA is installed If it's not the case, run on target MySQL server:
      - to add this privileges: GRANT all on *.* to 'root'@'%' identified by 'password' WITH GRANT OPTION;
      - to remove priovileges: DELETE from mysql.user WHERE user='root' AND host='%'; flush privileges;


Installation process described here assume that MySQL server will be running locally
First of all, install required packages and clone OSA repository
  - connect as root
  - apt-get install mysql-server apache2 php5 php5-mysql php5-curl openssl curl zip autoconf libmysqlclient-dev apache2-prefork-dev git
  - git clone https://github.com/zorglub42/OSA
  - cd OSA

Then run install.sh with "-m /path/to/your/local/installation" as argument 

Note: 
- Your local installation folder will be created by install.sh if it does not exists


		Ex:
			./install.sh -m /usr/local/OSA
			
		
- Go to $INSTALL_DIR/RunTimeAppliance/shell
- Edit envvars.sh file and set configuration variables according to your system. 

		By default just following are required:
			APACHE_ADMIN_MAIL: system administor mail (for information, no email send)
			ROOT_MYSQL_PW: password for mysql "roor" user
			APPLIANCE_MYSQL_PW: whished password for mysql user created to allow application to connect mysql on application database
			APPLIANCE_ADMIN_PW: "admin" appliaction user whished password
			USE_HTTP: publish (or not) service with HTTP
			USE_HTTPS: publish (or not) service with HTTPS
		and (end of file)
			INSTALL_DIR: application  location
			LOG_DIR: application logs location
run ./configure-osa.sh

If, at the end of execution the message "OSA Configuration done, exiting..." appears, OSA is correctly installed!

**IMPORTANT NOTE:**
If you plan to use standard ports for HTTP and HTTPS nodes (i.e 80 and 443 instead of 81 and 8443), first disable standard apache configuration:
- disable default virtual hosts

		a2dissite 000-default default-ssl
- edit /etc/apache2/ports.conf and comment all *Listen* directives relative to port 80 and 443
