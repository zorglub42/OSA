<?php
/**
 *  Reverse Proxy as a service
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
 * File Name   : ApplianceManager/ApplianceManager.php/include/PaginationFunc.inc.php
 *
 * Created     : 2012-02
 * Authors     : Benoit HERARD <benoit.herard(at)orange.com>
 *
 * Description :
 *      Various functions to managed paginated lists
 *--------------------------------------------------------
 * History     :
 * 1.0.0 - 2012-10-01 : Release of the file
*/
require_once "../objects/ApplianceObject.class.php";

/**
 * Return "previous" link url
 * 
 * @param string $uri           base uri for final URL
 * @param int    $currentOffset current offset in pages list
 * 
 * @return url
 */
function generatePreviousLink($uri, $currentOffset)
{
    $applianceObject = new ApplianceObject();
    $uriPrefix = $applianceObject->getPublicUriPrefix();

    $queryString="";
    foreach ($_REQUEST as $prm => $prmValue) {
        if ($prm != "offset") {
            if ($queryString != "") {
                $queryString=$queryString . "&";
            }
            $queryString=$queryString . $prm . "=" . urlencode($prmValue);
        }
    }

    if ($currentOffset==0) {
        $previous="";
    } else {
        $previous=$uriPrefix .
                  $uri .
                  "/?" .
                  $queryString .
                  "&offset=" .
                  ($currentOffset-1);
    }
    return $previous;
}

/**
 * Return "next" link url
 * 
 * @param string $uri           base uri for final URL
 * @param int    $currentOffset current offset in pages list
 * @param int    $pageCount     total pages count
 * @param int    $listCount     list count
 * 
 * @return url
 */
function generateNextLink($uri, $currentOffset, $pageCount, $listCount)
{
    $applianceObject = new ApplianceObject();
    $uriPrefix = $applianceObject->getPublicUriPrefix();

    $queryString="";
    foreach ($_REQUEST as $prm => $prmValue) {
        if ($prm != "offset") {
            if ($queryString != "") {
                $queryString=$queryString . "&";
            }
            $queryString=$queryString . $prm . "=" . urlencode($prmValue);
        }
    }
    if ($pageCount + ($currentOffset * recordCountPerPage) < $listCount) {
        $next =$uriPrefix . 
                            $uri . "/?" . 
                            $queryString . 
                            "&offset=" . 
                            ($currentOffset+1);
    } else {
        $next="";
    }
    return $next;
}
?>
