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

function startsWith($haystack, $needle)
{
    return $needle === "" || strpos($haystack, $needle) === 0;
}


function allowedChar($char, $str){
	$allowed=false;
	for ($i=0;$i<strlen($str) && !$allowed;$i++){
		$allowed=(substr($str,$i,1)==$char);
	}
	return $allowed;
}

function normalizeName($name, $allowedExtra=""){

        $rc="";
        for ($i=0;$i<strlen($name);$i++){
                $c=substr($name,$i,1);
                if (($c >= 'a' && $c <= 'z') || ($c >= 'A' && $c <= 'Z') || ($c >= '0' && $c <= '9') || $c=='-' || $c == '_' ||allowedChar($c, $allowedExtra)){
                        $rc = $rc . $c;
                }
        }
        return $rc;
}



/* Enhance basic PHP behaviour to handel PUT HTTP verb */
/* ==> add parameter received in a PUT request into $_REQUEST array */
function LoadPutParameters(){
	$method=$_SERVER['REQUEST_METHOD'];
	if ($method == "PUT" ) {
			$prm=Array();
			parse_str(file_get_contents('php://input'), $prm);
			$_REQUEST=array_merge($_REQUEST,$prm);
	}
	
}



/* Custom crytpting method (see cryptKey array) */
function encrypt($str){
	GLOBAL $cryptKey;
	$keyIdx=rand(0, count($cryptKey)-1);
	$key = $cryptKey[$keyIdx];
	while (strlen($key)<strlen($str)){
		$key = $key . $key;
	}
	
	$crypted=sprintf("%03d", $keyIdx);
	
	for ($i=0;$i<strlen($str);$i++){
		$crypted=$crypted . sprintf("%03d", ord(substr($str, $i,1)) ^ ord(substr($key,$i,1)));
		
		
	}
	return $crypted;
}


/* Custome decrypting method (see cryptKey array) */
function decrypt($str){
	GLOBAL $cryptKey;
	$keyIdx=substr($str,0,3);
	$key=$cryptKey[$keyIdx+0];
	while (strlen($key)<(strlen($str)-3)/3){
		$key = $key . $key;
	}
	$realStr=substr($str,3, strlen($str)-3);
	$decrypted="";
	for ($i=0;$i<strlen($realStr);$i+=3){
		$cChar=substr($realStr,$i,3)+0;
		$dChar=chr($cChar ^ ord(substr($key,$i/3,3)));
		$decrypted =  $decrypted  . $dChar;
	}
	return $decrypted;
}


/* Return simple formt (html, xmsl, json or plain) depending on value of "Accept" HTTP header*/ 
function RenderingFormat(){
	$accept=explode(",", $_SERVER["HTTP_ACCEPT"]);
	
	$rc="plain";
	for ($i=0;$i<count($accept);$i++){
		if (strpos($accept[$i],"html")){
			$rc="html";
			$i=count($accept);
		}elseif (strpos($accept[$i],"xml")){
			$rc="xml";
			$i=count($accept);
		}elseif (strpos($accept[$i],"json")){
			$rc="json";
			$i=count($accept);
		}elseif (strpos($accept[$i],"plain")){
			$rc="plain";
			$i=count($accept);
		}
	}
	
	return $rc;
	
}


/* Launch shell batch to generate reverse proxification (endpoints definitions) on nodes */
function enableDisableNode($nodeName, $published,$noreload=""){

		
	$remoteCmd="sudo " . runtimeApplianceEnableDisableVirtulaHostScript . ' "' . $nodeName . '" ' . $published . ' "' . $noreload . '" ' ;
	$remoteCmd = $remoteCmd . " 2>&1 >> " . runtimeApplianceEnableDisableVirtulaHostLogFile;
		
	system("$remoteCmd",$rc);
	if ($rc != 0){
		return false;
	}else{
		return true;
	}
	
}


function applyApacheConfiguration($nodeList=null){

	if (runtimeApplianceAutomaticConfiguration){
		
		$remoteCmd="sudo " . runtimeApplianceConfigScript;
		if ($nodeList !== null){
			$remoteCmd = $remoteCmd . " \"" . implode(" ", $nodeList) . "\"";
		}
		if (runtimeApplianceConfigScriptLogFile!=""){
			$remoteCmd = $remoteCmd . " 2>&1 >> " . runtimeApplianceConfigScriptLogFile;
		}
		system("$remoteCmd",$rc);
		if ($rc != 0){
			return false;
		}else{
			return true;
		}
	}else{
		return true;
	
	}
	
}
/* Launch shell batch to generate VirtualHosts for node  + reverse proxification (endpoints definitions) on nodes */
function applyApacheNodesConfiguration($nodeName="", $action=""){

	if (runtimeApplianceAutomaticConfiguration){
		
		$remoteCmd="sudo " . runtimeApplianceVirtualHostsConfigScript  . " " . $action . " " . $nodeName;
		if (runtimeApplianceVirtualHostsConfigScriptLogFile!=""){
			$remoteCmd = $remoteCmd . " 2>&1 >> " . runtimeApplianceVirtualHostsConfigScriptLogFile;
		}

		
		system("$remoteCmd",$rc);
		if ($rc != 0){
			return false;
		}else{
			return true;
		}
	}else{
		return true;
	
	}
	
}

/* Split usr in readable part in array (host, domain, path-on-host..... */
function getUrlParts($url){
	// get host name from URL
	
	if (!preg_match("/^https:\/\/([^\/]+)(.*)/i",$url, $matches)){
		if (!preg_match("/^http:\/\/([^\/]+)(.*)/i",$url, $matches)){
			if (!preg_match("/^ws:\/\/([^\/]+)(.*)/i",$url, $matches)){
				preg_match("/^wss:\/\/([^\/]+)(.*)/i",$url, $matches);
			}
		}
	}
	$host = $matches[1];
	$path= $matches[2];
	
	if (preg_match("/.*@(.*)/",$host, $matches)){
		$host=($matches[1]);
	}
	
	if (preg_match("/(.*):.*/",$host, $matches)){
		$host=$matches[1];
	}
	
	if (preg_match("/[^\.]*(\..*)/",$host,$matches) && !preg_match("/[0-9]*\.[0-9]*\.[0-9]*\.[0-9]*/", $host)){
			$domain=$matches[1];
	}else{
		$domain=$host;
	}
	
	
	$parts=Array("host" => $host, "domain" => $domain, "path" => $path);
	return $parts;
}

/* serialize a Date using ISO 8601 format */
function getDateFromIso($strDate){
	if ( preg_match( "/([0-9]{4})-([0-9]{2})-([0-9]{2})T([0-9]{2}):([0-9]{2}):([0-9]{2})(.*)$/", $strDate, $regs ) ) {
		if ($regs[7]=="Z" || preg_match("/[+|-][0-9]{2}:[0-9]{2}$/", $regs[7])){
			$fromPart = "$regs[1]-$regs[2]-$regs[3] $regs[4]:$regs[5]:$regs[6]";
				$time = strtotime( $strDate );
				return date( 'Y-m-d H:i:s', $time );
		}else{
			throw new Exception("Invalid timezone format for " . $strDate);
		}
	} else {
		throw new Exception("Invalid date format for " . $strDate ." YYYY-MM-DDTHH:MI:SSTZ (iso) expected\n");
	}
}


function getRequestor($request_data=null){
	GLOBAL $defaultHeadersName;

	$hdrs=getallheaders();
	if (isset($hdrs[$defaultHeadersName["userName"]])){
		$requestor=$hdrs[$defaultHeadersName["userName"]];
	}else{
		throw new RestException(400,"Missing authentication credentials");
	}
	return $requestor;
}



function addSQLFilter($compToAdd, $sqlString){
	if ($sqlString != ""){
		$sqlString=$sqlString . " AND ";
	}else{
		$sqlString=" WHERE ";
	}
	$sqlString=$sqlString . $compToAdd;
	return $sqlString;
}
