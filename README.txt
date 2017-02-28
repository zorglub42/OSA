Open Service Access (AKA OSA) is a Web Services gateway designed as a security element of your network, dedicated to WebService protection and publishing.
It support:
	* Publish backends on different networks (default configuration allow up 2)
	* Ensure encryption (https) 
	* Verify authentication with basic authentication
	* Check authorizations
	* Apply global quotas (per second, day and month) for a backend
	* Apply user quotas (per second, day and month) for a backend 
	* Forward consumer identity to provider
	* Forward publishing endpoint to provider
	* Provide advance service usage logging to administrators.
	* REST Full compliant error management (for OSA errors)
	* Simple GUI to adminstrators



INSTALL:
------------------
	Pre requisites:
		- Apache2
		- php5
		- php5-mysql
		- php5-curl
		- curl
		- zip
		- mod_osa (see bellow)
		- running mysql server (local)
	
	
	Install
		run install.sh with $INSTALL_DIR as argument  (you may add -m option to also compile and install apache module)
		*** Note: 
			- $INSTALL_DIR will be created by install.sh if it does not exists
			- Application is pre-configured to be installed in  /usr/local/ApplianceManager

		Ex:
			./install.sh /usr/local/ApplianceManager
			or
			./install.sh -m /usr/local/ApplianceManager
			
		
		Go to $INSTALL_DIR/RunTimeAppliance/shell
		Edit envvars.sh file and set configuration variables according to your system. 
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



	How to compile and install manually mod_osa apache2 module
	----------------------------------------
	Pre requisites:
		- c compiler
		- make
		- autoconf
		- apache-dev tools and headers
		- mysqlclient-dev header
		

	First run install.sh as described in the Install section		
	Go to  $INSTALL_DIR/RunTimeAppliance/apache/module
	Run:
		aclocal
		autoconf
		automake
		./configure
		make
		make install



USE APPLICATION:
-------------------------
	After install,  connect https://your-server:6443/ApplianceManagerAdmin with admin as user and $APPLIANCE_ADMIN_PW to manage services publishing    (port used can be changed via envvars.sh file)
	If HTTPS publication is activated services are available at:
		https://your-server:8443/your-service   (port used can be changed via envvars.sh file)
	If HTTP  publication is activated services are available at:
		http://your-server:81/your-service   (port used can be changed via envvars.sh file)
	NOTE: If you re run configure-osa.sh all the configuration is re-build, including drop and re-create of database if already exisitng. To preseverve exisiting DB ad -keep-db parameter to configure-osa.sh



