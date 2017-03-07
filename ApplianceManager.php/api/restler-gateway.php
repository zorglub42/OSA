<?php
/*--------------------------------------------------------
 * Module Name : ApplianceManager
 * Version : 1.0.0
 *
 * Software Name : OpenServicesAccess
 * Version : 2.0
 *
 * Copyright (c) 2011 – 2014 Orange
 * This software is distributed under the Apache 2 license
 * <http://www.apache.org/licenses/LICENSE-2.0.html>
 *
 *--------------------------------------------------------
 * File Name   : ApplianceManager/ApplianceManager.php/users/index.php
 *
 * Created     : 2013-11
 * Authors     : Benoit HERARD <benoit.herard(at)orange.com>
 *
 * Description :
 *      restler Luract set up
 * 
 *--------------------------------------------------------
 * History     :
 * 1.0.0 - 2013-11-12 : Release of the file
*/



require_once('../include/commonHeaders.php');
require_once('../include/Settings.ini.php');
require_once 'Users.php';
require_once 'Groups.php';
require_once 'Logs.php';
require_once 'Counters.php';
require_once 'Nodes.php';
require_once 'Services.php';
require_once 'Auth.php';
require_once '../include/restler3/restler.php';
use Luracast\Restler\Restler;


Resources::$useFormatAsExtension = false;
JsonFormat::$prettyPrint=True;

$r = new Restler();

if (isset(getallheaders()[uriPrefixHeader])){
	$r->setBaseUrl(getallheaders()[uriPrefixHeader]);
}
$r->setSupportedFormats('JsonFormat' ,'UrlEncodedFormat','UploadFormat');

$r->addAPIClass('Luracast\\Restler\\Resources');  //this creates resources.json at API root 

$r->addAPIClass('Auth');
$r->addAPIClass('Counters');
$r->addAPIClass('Groups');
$r->addAPIClass('Logs');
$r->addAPIClass('Nodes');
$r->addAPIClass('Users');
$r->addAPIClass('Services');


$r->handle();





?>
