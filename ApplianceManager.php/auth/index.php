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
 * File Name   : resources/index.php
 *
 * Created     : 2013-03
 * Authors     : Benoit HERARD <benoit.herard(at)orange.com>
 *
 * Description :
 *     REST Request gateway for restler luracast
 *--------------------------------------------------------
 * History     :
 * 1.0.0 - 2013-03-20 : Release of the file
 */
require_once '../include/restler/restler.php';
require_once '../include/restler/xmlformat.php';
require_once 'Token.php';
require_once 'Login.php';
require_once 'Logout.php';

//CORS Compliancy
header("Access-Control-Allow-Credentials : true");
header("Access-Control-Allow-Headers: X-Requested-With, Depth, Authorization");
header("Access-Control-Allow-Methods: OPTIONS, GET, HEAD, DELETE, PROPFIND, PUT, PROPPATCH, COPY, MOVE, REPORT, LOCK, UNLOCK");
header("Access-Control-Allow-Origin: *");



$r = new Restler();
$r->setSupportedFormats('JsonFormat', 'XmlFormat' ,'UrlEncodedFormat');
$r->addAPIClass('Token');
$r->addAPIClass('Login');
$r->addAPIClass('Logout');
$r->handle();
?>
