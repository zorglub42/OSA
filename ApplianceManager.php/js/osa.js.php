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
 *      Merge in a single file all .js used by the application to
 * 		reduce number of requests a loading time
 *--------------------------------------------------------
 * History     :
 * 1.0.0 - 2012-10-01 : Release of the file
*/
require_once "../include/Localization.php";

/**
 * Get last modification date on addons files
 * 
 * @return date
 */
function getLastAddonsModdify()
{
    $lastModify=0;

    $dir    = '../addons/';
    $files = scandir($dir);

    foreach ($files as &$addon) {
        if (is_dir($dir . $addon) && $addon != ".." && $addon != ".") {
            //We found an addon
            $jsDir=$dir . $addon . "/js/";
            if (is_dir($jsDir)) {
                //And it have a js folder
                
                //Include file of this folder
                $jsFiles= scandir($jsDir);
                foreach ($jsFiles as &$js) {
                    if (preg_match("/.*\.js/", $js) || preg_match("/.*\.php/", $js)) {
                        if (filemtime($jsDir . $js)>$lastModify) {
                            $lastModify=filemtime($jsDir . $js);
                        }
                    }
                }
            }
                
        }
    }
    return $lastModify;
}


/** 
 * Include addons files
 * 
 * @return void
 */
function includeAddons()
{
    $dir    = '../addons/';
    $files = scandir($dir);

    foreach ($files as &$addon) {
        if (is_dir($dir . $addon) && $addon != ".." && $addon != ".") {
            //We found an addon
            $jsDir=$dir . $addon . "/js/";
            if (is_dir($jsDir)) {
                //And it have a js folder
                
                //Include file of this folder
                $jsFiles= scandir($jsDir);
                foreach ($jsFiles as &$js) {
                    if (preg_match("/.*\.js/", $js) ||preg_match("/.*\.php/", $js)) {
                        include $jsDir . $js;
                    }
                }
            }
                
        }
    }
    

}


$dir    = '.';
$files = scandir($dir);

Localization::getString("app.title");  //force Load localization settings
$lastModify=getLastAddonsModdify();
foreach ($files as &$file) {
    if (preg_match("/.*\.js/", $file) ||preg_match("/.*\.php/", $file) ) {
        if (filemtime($file)>$lastModify) {
            $lastModify=filemtime($file);
        }
    }
}
if (Localization::$lastModify>$lastModify) {
    $lastModify=Localization::$lastModify;
}
$headers=getallheaders();

if (isset($headers['If-Modified-Since']) 
    && (strtotime($headers['If-Modified-Since']) >= $lastModify)
) {
    // Client's cache IS current, so we just respond '304 Not Modified'.
    header(
        'Last-Modified: '.gmdate('D, d M Y H:i:s', $lastModify).' GMT',
        true,
        304
    );
    die();
} else {
    // Image not cached or cache outdated, we respond '200 OK' and output the image.
    header(
        'Last-Modified: '.gmdate('D, d M Y H:i:s', $lastModify).' GMT',
        true,
        200
    );
    
}
header("Content-type: text/javascript");



require_once "jquery-1.11.3.min.js";
echo "\n";
foreach ($files as &$file) {
    if ((preg_match("/.*\.js/", $file)||preg_match("/.*\.js.php/", $file)) 
        && $file!="jquery-1.8.2.js" 
        && $file!="osa.js.php"
    ) {
        include $file;
        echo "\n";
    }
}
$localizedFile = $_SERVER["DOCUMENT_ROOT"] . 
                 "/ApplianceManager/js/localization/datepicker-" .
                 Localization::getString("locale") .
                 ".js";
if (file_exists($localizedFile)) {
    include "localization/datepicker-" . Localization::getString("locale") . ".js";
}
includeAddons();
?>

