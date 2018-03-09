# OSA
Open Services Access: Apaches RP management tool

OSA is a tool to manage in click-and-play mode an Apache reverse proxy
It's based on apache 2 web server.

In addition, it offers some extra functionalities like dual mode authentication (HTTP basic or cookie) for a same resource set or quota management.

OSA is distributed under Apache2 licence

In addition of standard Apache reverse proxying capabilities, OSA us an additional apache module.
It comes with 2 versions of this module:
 * using MySQL as backend
 * using SQLite3 as backend
**NOTE** Only one version may be used at the salme time by apache

## Install
Install and configuration scripts are developped for debian, but, with few changes, should be compliant with RedHat too...

To install some prerequisite are needed

**IMPORTANT NOTE:** Apply following MySQL instrustruction on if you plan to use MySQL version of OSA apache module and need to connect the database on a remote server.
  - root mysql user on target server should be able to create/delete users and databases, with a connection from the server where OSA is installed If it's not the case, run on target MySQL server:
      - to add this privileges: GRANT all on *.* to 'root'@'%' identified by 'password' WITH GRANT OPTION;
      - to remove priovileges: DELETE from mysql.user WHERE user='root' AND host='%'; flush privileges;


Installation process described here assume that MySQL server will be running locally
First of all, install required packages and clone OSA repository
  - connect as root
  - install required packages 
    
	**Until Ubuntu 16.04 (not included) OR Debian/Raspbian Jessie**

    		apt-get install apache2 php5 php5-curl php5-mysql  mysql-server libmysqlclient-dev openssl curl zip autoconf apache2-prefork-dev git build-essential
    
	**Debian/Raspbian Stretch**

    		apt-get install apache2 php5 php5-curl php5-mysql mysql-server default-libmysqlclient-dev openssl curl zip autoconf apache2-prefork-dev git build-essential

    
	**Since Ubuntu 16.04 (included)**

    		apt-get install apache2 libapache2-mod-php php php-curl php-mysql mysql-server libmysqlclient-dev openssl curl zip autoconf apache2-dev git build-essential	
  - clone git repo

		git clone https://github.com/zorglub42/OSA
  - Go to OSA clone folder
  
		cd OSA

Then run install.sh with "-m /path/to/your/local/installation" as argument 

Note: 
- Your local installation folder will be created by install.sh if it does not exists


		Ex:
			./install.sh -m /usr/local/OSA
			
		
- Go to $INSTALL_DIR/RunTimeAppliance/shell

		Ex.:
			cd /usr/local/OSA/RunTimeAppliance/shell
		
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
- and then start configuration by issuing the following command

		./configure-osa.sh

If, at the end of execution the message "OSA Configuration done, exiting..." appears, OSA is correctly installed!

**IMPORTANT NOTE:**
If you plan to use standard ports for HTTP and HTTPS nodes (i.e 80 and 443 instead of 81 and 8443), first disable standard apache configuration:
- disable default virtual hosts

		a2dissite 000-default default-ssl
- edit /etc/apache2/ports.conf and comment all *Listen* directives relative to port 80 and 443



**IMPORTANT NOTE #2:**
If at first connection on admin console the following appears

		An error has occurred

			Error code:	-1
			Error label:	Unable to connect database
			
It's probably because my sql server didn't restart properly after install. In such a case, issue the following:

		service mysql restart

## Docker
Instead of installaling on a box, you can also run OSA as a docker container.
### Build an OSA Image

	wget https://raw.githubusercontent.com/zorglub42/OSA/master/osa.dockerfile.sh -O osa.dockerfile.sh; bash ./osa.dockerfile.sh admin-passwd domain
Where parameters are:
- admin-password: OSA admin passwed to set
- domain (optional): your FQDN (Ex: zorglub42.fr)

### Run OSA container
Due to the fact that OSA can create Listening port (nodes), it's better to bind OSA container to host network.
Using port mapping will limit the accessibility of created node in the container.
(refer to docker documentation if you want to use it anyway)

	docker run --name OSA --net=host -d zorglub42:osa

**IMPORTANT NOTE:** With --net=host option, container assume that ports 3306, 80, 443 and 6443 are not used on host running it.th

## Update
To deploy a new version of OSA from github do the following
1.  **If you whant to keep your DB contents:** ensure that KEEP_DB environnement variable is set to 1 in INSTALL_DIR/RunTimeAppliance/shell. You don't have to check that point on next updates unless you want a factory reset.
2.  start "update.sh" from the folder where initial checkout was done
	
		Ex:
			cd OSA
			./update.sh


##Trouble shooting
If after using additional apache directives (on service or node) OSA doesn't answer it's probably because apache configuration is corrupted due to invalid directives. To fix it:
- go to apache available and enabled sites configuration folder and remove all  osa-node files (DO NOT REMOVE osa* but only osa-node*)

		cd /etc/apache2/sites-available
		rm osa-node*
		cd /etc/apache2/sites-enabled
		rm osa-node*
- restart apache

		service apache2 restart
		
- reconnect OSA admin console and fix invalid configuration (service or node)

