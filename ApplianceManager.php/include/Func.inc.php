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
 *      Various functions set..... anything but "a business object only library"
 *--------------------------------------------------------
 * History     :
 * 1.0.0 - 2012-10-01 : Release of the file
*/
require_once 'Settings.ini.php';
require_once 'Crypto.ini.php';

/**
 * Return true if $haystack starts with $needle
 *
 * @param string $haystack string to search into
 * @param string $needle   searched string
 * 
 * @return bool
 */
function startsWith($haystack, $needle)
{
    return $needle === "" || strpos($haystack, $needle) === 0;
}

/**
 * Return true if $char is parted of allowed chars defined in $str
 * 
 * @param string $char char to examine
 * @param string $str  allowed chars
 * 
 * @return bool
 */
function allowedChar($char, $str)
{
    $allowed=false;
    for ($i=0;$i<strlen($str) && !$allowed;$i++) {
        $allowed=(substr($str, $i, 1)==$char);
    }
    return $allowed;
}

/**
 * Return a normalized string (only allowed chars) from $name
 * 
 * @param string $name         String to normalize
 * @param string $allowedExtra list of allowed chars 
 *                             (in addition to [A-Z][a-z][0-9]-_)
 * 
 * @return string
 */
function normalizeName($name, $allowedExtra="")
{

    $rc="";
    for ($i=0;$i<strlen($name);$i++) {
        $c=substr($name, $i, 1);
        
        if (($c >= 'a' && $c <= 'z')
            || ($c >= 'A' && $c <= 'Z')
            || ($c >= '0' && $c <= '9')
            || $c=='-'
            || $c == '_'
            || allowedChar($c, $allowedExtra)
        ) {
                $rc = $rc . $c;
        }
    }
    return $rc;
}


/**
 *  Custom encrytpting method (see cryptKey array)
 *  
 * @param sting $str String to encryt
 * 
 * @return string Encrypted string
 */
function encrypt($str)
{
    @include 'Crypto.ini.php';

    $keyIdx=rand(0, count($cryptKey)-1);
    $key = $cryptKey[$keyIdx];
    while (strlen($key)<strlen($str)) {
        $key = $key . $key;
    }
    
    $crypted=sprintf("%03d", $keyIdx);
    
    for ($i=0;$i<strlen($str);$i++) {
        $item = ord(substr($str, $i, 1)) ^ ord(substr($key, $i, 1));
        $crypted=$crypted . sprintf("%03d", $item);
        
        
    }
    return $crypted;
}


/**
 * Custom decrypting method (see cryptKey array) 
 * 
 * @param string $str String to decrypt
 * 
 * @return string decrypted string
 */
function decrypt($str)
{
    @include 'Crypto.ini.php';

    $keyIdx=substr($str, 0, 3);
    $key=$cryptKey[$keyIdx+0];
    while (strlen($key)<(strlen($str)-3)/3) {
        $key = $key . $key;
    }
    $realStr=substr($str, 3, strlen($str)-3);
    $decrypted="";
    for ($i=0;$i<strlen($realStr);$i+=3) {
        $cChar=substr($realStr, $i, 3)+0;
        $dChar=chr($cChar ^ ord(substr($key, $i/3, 3)));
        $decrypted =  $decrypted  . $dChar;
    }
    return $decrypted;
}

/**
 *  Launch shell batch enable or disable a node (VirtualHost) 
 *  Return true in case of success
 * 
 * @param string $nodeName  Node to (des)activate
 * @param bool   $published Set to true to activate, false to desactivate
 * @param bool   $noreload  If set to true, apache conf is reloaded
 *  
 * @return bool 
 **/
function enableDisableNode($nodeName, $published,$noreload="")
{

    $rc=0;
    if (runtimeApplianceAutomaticConfiguration) {
        $remoteCmd="sudo " . runtimeApplianceEnableDisableVirtulaHostScript . 
                   ' "' . 
                   $nodeName . 
                   '" ' . 
                   $published . 
                   ' "' . 
                   $noreload . 
                   '" ' ;
        $remoteCmd = $remoteCmd . 
                     " 2>&1 >> " . 
                     runtimeApplianceEnableDisableVirtulaHostLogFile;
    }
    system("$remoteCmd", $rc);
    if ($rc != 0) {
        return false;
    } else {
        return true;
    }
    
}

/**
 * Apply and reload apache config for nodes (VirtualHosts)
 * Return true in case of success
 * 
 * @param array $nodeList List of nodes to reconfigure (optional)
 *                        if not set all nodes are reconfigured
 * 
 * @return bool
 */
function applyApacheConfiguration($nodeList=null)
{

    if (runtimeApplianceAutomaticConfiguration) {
        
        $remoteCmd="sudo " . runtimeApplianceConfigScript;
        if ($nodeList !== null) {
            $remoteCmd = $remoteCmd . " \"" . implode(" ", $nodeList) . "\"";
        }
        if (runtimeApplianceConfigScriptLogFile!="") {
            $remoteCmd = $remoteCmd . 
                         " 2>&1 >> " . 
                         runtimeApplianceConfigScriptLogFile;
        }
        system("$remoteCmd", $rc);
        if ($rc != 0) {
            return false;
        } else {
            return true;
        }
    } else {
        return true;
    
    }
    
}
/**
 *  Launch shell batch to generate VirtualHosts for node  + 
 *  reverse proxification (endpoints definitions) on nodes 
 *  Return true in case of success
 * 
 * @param string $nodeName Node on with conf should be generarted
 * @param string $action   (optional) action on node (C/U/D)
 * 
 * @return bool
 */
function applyApacheNodesConfiguration($nodeName="", $action="")
{
    if (runtimeApplianceAutomaticConfiguration) {
        
        $remoteCmd="sudo " . 
                   runtimeApplianceVirtualHostsConfigScript  . 
                   " " . 
                   $action . 
                   " " . 
                   $nodeName;
        if (runtimeApplianceVirtualHostsConfigScriptLogFile!="") {
            $remoteCmd = $remoteCmd . 
                         " 2>&1 >> " . 
                         runtimeApplianceVirtualHostsConfigScriptLogFile;
        }

        
        system("$remoteCmd", $rc);
        if ($rc != 0) {
            return false;
        } else {
            return true;
        }
    } else {
        return true;
    
    }
    
}

/**
 * Split usr in readable part in array (host, domain, path-on-host.....
 * 
 * @param string $url URL To split
 * 
 * @return array
 */
function getUrlParts($url)
{
    // get host name from URL
    
    if (!preg_match("/^https:\/\/([^\/]+)(.*)/i", $url, $matches)) {
        if (!preg_match("/^http:\/\/([^\/]+)(.*)/i", $url, $matches)) {
            if (!preg_match("/^ws:\/\/([^\/]+)(.*)/i", $url, $matches)) {
                preg_match("/^wss:\/\/([^\/]+)(.*)/i", $url, $matches);
            }
        }
    }
    $host = $matches[1];
    $path= $matches[2];
    
    if (preg_match("/.*@(.*)/", $host, $matches)) {
        $host=($matches[1]);
    }
    
    if (preg_match("/(.*):.*/", $host, $matches)) {
        $host=$matches[1];
    }
    
    if (preg_match("/[^\.]*(\..*)/", $host, $matches) 
        && !preg_match("/[0-9]*\.[0-9]*\.[0-9]*\.[0-9]*/", $host)
    ) {
            $domain=$matches[1];
    } else {
        $domain=$host;
    }
    
    
    $parts=Array("host" => $host, "domain" => $domain, "path" => $path);
    return $parts;
}

/**
 * Get a date for a serialized date using ISO 8601 format 
 * 
 * @param string $strDate serialized date
 * 
 * @return date
 */
function getDateFromIso($strDate)
{
    $isoRegExp="/([0-9]{4})-([0-9]{2})-([0-9]{2})T([0-9]{2}):" .
               "([0-9]{2}):([0-9]{2})(.*)$/";
    if (preg_match($isoRegExp, $strDate, $regs)) {
        if ($regs[7]=="Z" || preg_match("/[+|-][0-9]{2}:[0-9]{2}$/", $regs[7])) {
            $fromPart = "$regs[1]-$regs[2]-$regs[3] $regs[4]:$regs[5]:$regs[6]";
                $time = strtotime($strDate);
                return date('Y-m-d H:i:s', $time);
        } else {
            throw new Exception("Invalid timezone format for " . $strDate);
        }
    } else {
        throw new Exception(
            "Invalid date format for " .
            $strDate .
            " YYYY-MM-DDTHH:MI:SSTZ (iso) expected\n"
        );
    }
}

/**
 * Get requestor (userName) from request headers
 * Return requestor if found, else raise HTTP 401
 * 
 * @return string
 */
function getRequestor()
{
    @include 'Settings.ini.php';

    $hdrs=getallheaders();
    if (isset($hdrs[$defaultHeadersName["userName"]])) {
        $requestor=$hdrs[$defaultHeadersName["userName"]];
    } else {
        throw new RestException(401, "Missing authentication credentials");
    }
    return $requestor;
}


/**
 * Add a filter complement to a SQL string request
 * Return SQL request including complement integration
 * 
 * @param string $compToAdd complement to add
 * @param string $sqlString SQL string to add complement to
 * 
 * @return string
 */
function addSQLFilter($compToAdd, $sqlString)
{
    if ($sqlString != "") {
        $sqlString=$sqlString . " AND ";
    } else {
        $sqlString=" WHERE ";
    }
    $sqlString=$sqlString . $compToAdd;
    return $sqlString;
}
