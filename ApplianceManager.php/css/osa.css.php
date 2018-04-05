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
 * File Name   : ApplianceManager/ApplianceManager.php/js/osa.js.php
 *
 * Created     : 2012-02
 * Authors     : Benoit HERARD <benoit.herard(at)orange.com>
 *
 * Description :
 *      Merge in a single file all .css used by the application to
 * 		reduce number of requests a loading time
 *--------------------------------------------------------
 * History     :
 * 1.0.0 - 2012-10-01 : Release of the file
*/
header("Content-type: text/css");
require_once '../include/Mobile_Detect.php';

$dir    = '.';
$files = scandir($dir);
$lastModify=0;
foreach ($files as &$file) {
    if (preg_match("/.*\.css/", $file) ||preg_match("/.*\.php/", $file)) {
        if (filemtime($file)>$lastModify) {
            $lastModify=filemtime($file);
        }
    }
}


$headers=getallheaders();
if (isset($headers['If-Modified-Since']) 
    && (strtotime($headers['If-Modified-Since']) >= $lastModify)
) {
    // Client's cache IS current, so we just respond '304 Not Modified'.
    header(
        'Last-Modified: '.gmdate('D, d M Y H:i:s', $lastModify) .
        ' GMT',
        true,
        304
    );
    die();
} else {
    // Image not cached or cache outdated, we respond '200 OK' and output the image.
    header(
        'Last-Modified: '.gmdate('D, d M Y H:i:s', $lastModify).
        ' GMT',
        true,
        200
    );
    
}

foreach ($files as &$file) {
    if (preg_match("/.*\.css/", $file) && $file!="checkbox-radio.css") {
        include_once $file;
    }
}
$detect = new Mobile_Detect();
if (!$detect->isMobile()) {
    include_once "checkbox-radio.css";
}

?>

