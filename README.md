# OSA
Open Services Access: Apaches RP management tool

OSA is a tool to manage in click-and-play mode an Apache reverse proxy
It's based on apache 2 web server.

In addition, it offers some extra functionalities like dual mode authentication (HTTP basic or cookie) for a same resource set or quota management.

OSA is distributed under Apache2 licence

##Install
Install and configuration scripts are developped for debian, but, with few changes, should be compliant with RedHat too...

To install some prerequisite are needed

**IMPORTANT NOTE:** Apply following MySQL instrustruction on if you plan to connect database on a remote server.
  - root mysql user on target server should be able to create/delete users and databases, with a connection from the server where OSA is installed If it's not the case, run on target MySQL server:
      - to add this privileges: GRANT all on *.* to 'root'@'%' identified by 'password' WITH GRANT OPTION;
      - to remove priovileges: DELETE from mysql.user WHERE user='root' AND host='%'; flush privileges;


Installation process described here assume that MySQL server will be running locally
First of all, install required packages and clone OSA repository
  - connect as root
  - install required packages 
    
	**Until Ubuntu 16.04 (not included) OR Debian/Raspbian Jessie**

    		apt-get install mysql-server apache2 php5 php5-mysql php5-curl openssl curl zip autoconf libmysqlclient-dev apache2-prefork-dev git build-essential

    
	**Since Ubuntu 16.04 (included)**

    		apt-get install mysql-server apache2 php php-mysql php-curl libapache2-mod-php openssl curl zip autoconf libmysqlclient-dev apache2-dev git build-essential	
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
Due to the fact that OSA can create Listening port (nodes), it's better to bnd OSA container to host network.
Specifying will limit the accessibility of created node in the container.

	docker run --net=host -d zorglub42:osa

## Update
To deploy a new version of OSA from github do the folowing
- Ensure that KEEP_DB environnement variable is set to 1 in INSTALL_DIR/RunTimeAppliance/shell if you whant to keep your DB contents
- update local git repository from github (git pull)
- restart install process from git local repository (checking envvars.sh file is no more required)
	
		Ex:
			cd OSA
			git pull
			./install.sh -m /usr/local/OSA
			cd /usr/local/OSA/RunTimeAppliance/shell
			./configure-osa.sh


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

