<?php
/*--------------------------------------------------------
 * Module Name : ApplianceManager
 * Version : 1.0.0
 *
 * Software Name : OpenServicesAccess
 * Version : 1.0
 *
 * Copyright (c) 2011 – 2014 Orange
 * This software is distributed under the Apache 2 license
 * <http://www.apache.org/licenses/LICENSE-2.0.html>
 *
 *--------------------------------------------------------
 * File Name   : ApplianceManager/ApplianceManager.php/include/Settings.ini.php
 *
 * Created     : 2012-02
 * Authors     : Benoit HERARD <benoit.herard(at)orange.com>
 *
 * Description :
 *      .../...
 *--------------------------------------------------------
 * History     :
 * 1.0.0 - 2012-10-01 : Release of the file
*/
	#Database connection parameter
	$BDName="appliance@localhost:3306";
	$BDUser="appliance";
	$BDPwd="osapwd";
	
	define("runtimeApplianceConfigLocation",  "/usr/local/OSA/RunTimeAppliance/apache/conf/vhAppliance");
	
	# Runtime appliance automatic configuration
	define("runtimeApplianceConfigScript", "/usr/local/OSA/RunTimeAppliance/shell/doAppliance.sh");
	define("runtimeApplianceConfigScriptLogFile", "/var/log/OSA/doAppliance.log");
            
	define("runtimeApplianceVirtualHostsConfigScript", "/usr/local/OSA/RunTimeAppliance/shell/doVHAppliance.sh");
	define("runtimeApplianceVirtualHostsConfigScriptLogFile", "/var/log/OSA/doVHAppliance.log");

	define("runtimeApplianceEnableDisableVirtulaHostScript", "/usr/local/OSA/RunTimeAppliance/shell/enableDisableNode.sh");
	define("runtimeApplianceEnableDisableVirtulaHostLogFile", "/var/log/OSA/enabDisabVH.log");


	define("runtimeApplianceAutomaticConfiguration",true);




	define("runtimeApplianceConfigScriptLogDir", "/var/log/OSA");




	#uri building (compliancy with REST standards for hypermedia links)

	#Best: If this header is received and set, all URI will by prefixed with this value
	define("uriPrefixHeader", "Public-Root-URI");
	
	#If uriPrefixHeader is not set or null value all URI will by prefixed with this value 
	#To genererate absolute URI form the server set something like /ApplianceManager/ (handels perfectly direct access)
	#If behind a reverse proxy witch change "context root" leave and empty and ensure that uriPrefixHeader is not send or empty 
	define("defaultUriPrefix",  "");
	
		

	#Identity forwarding headers name
	$defaultHeadersName = array(	"userName" => "X_REMOTE_USER",
					"firstName" => "X_OSA_FIRSTNAME",
					"lastName" => "X_OSA_LASTNAME",
					"entity" => "X_OSA_ENTITY",
					"emailAddress" => "X_OSA_EMAIL",
					"extra" => "X_OSA_EXTRA"
	);
/*							  
	define("userNameHeader", "X_REMOTE_USER");
	define("firstNameHeader", "NURS_FIRSTNAME");
	define("lastNameHeader", "NURS_LASTNAME");
	define("entityHeader", "NURS_ENTITY");
	define("emailAddressHeader", "NURS_EMAIL");
	define("extraHeader", "NURS_EXTRA");*/
	
	
	#list pagination
	define("recordCountPerPage", 10);
	
	
	#auth token 
		#validity period (in min)
	define("authTokenTTL", 60);
		#cookie name
	define("authTokenCookieName", "OSAAuthToken");
	define("osaAdminUri", "https://localhost:6443/");
?>
