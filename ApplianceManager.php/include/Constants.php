<?php
/**
 * Reverse Proxy as a service
 * 
 * PHP Version 7.0
 * 
 * @category ReverseProxy
 * @package  OSA
 * @author   Benoit HERARD <benoit.herard@orange.com>
 * @license  http://www.apache.org/licenses/LICENSE-2.0.htm Apache 2 license
 * @link     https://github.com/zorglub42/OSA/
*/
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
 * File Name   : ApplianceManager/ApplianceManager.php/include/Constants.php
 *
 * Created     : 2012-02
 * Authors     : Benoit HERARD <benoit.herard(at)orange.com>
 *
 * Description :
 *      Fields length constants definition
 *--------------------------------------------------------
 * History     :
 * 1.0.0 - 2012-10-01 : Release of the file
*/
define("FIRSTNAME_LENGTH", 45);
define("LASTNAME_LENGTH", 45);
define("ENTITY_LENGTH", 45);
define("USERNAME_LENGTH", 45);
define("PASSWORD_LENGTH", 45);
define("EMAIL_LENGTH", 200);

define("GROUPNAME_LENGTH", 45);
define("DESCRIPTION_LENGTH", 2000);

define("SERVICENAME_LENGTH", 45);


define("NODENAME_LENGTH", 45);
define("NODEDESCRIPTION_LENGTH", 2000);
define("SERVERFQDN_LENGTH", 255);
define("LOCALIP_LENGTH", 45);


define("FRONTENDENDPOINT_LENGTH", 200);
define("BACKENDENDPOINT_LENGTH", 200);


define("BACKENDUSERNAME_LENGTH", 45);
define("BACKENDPASSWORD_LENGTH", 45);




define("ADMIN_GROUP", "Admin");
define("VALID_USER_GROUP", "valid-user");
define("ADMIN_USER", "Admin");
define("ADMIN_SERVICE", "ApplianceManagerAdmin");

define("version", "4.0-rc3");


$userProperties = array("userName", 
            "firstName", 
            "lastName", 
            "entity", 
            "emailAddress", 
            "extra"
);
?>
