<?
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
require_once '../include/restler/restler.php';
require_once '../include/restler/xmlformat.php';
require_once 'Nodes.php';




$r = new Restler();
$r->setSupportedFormats('JsonFormat', 'XmlFormat' ,'UrlEncodedFormat');
$r->addAPIClass('Nodes', "/");
$r->handle();

?>
