<?
/*--------------------------------------------------------
 * Module Name : ApplianceManager
 * Version : 2.0.0
 *
 * Software Name : OpenServicesAccess
 * Version : 2.2
 *
 * Copyright (c) 2011 – 2014 Orange
 * This software is distributed under the Apache 2 license
 * <http://www.apache.org/licenses/LICENSE-2.0.html>
 *
 *--------------------------------------------------------
 * File Name   : auth/Token.php
 *
 * Created     : 2013-03
 * Authors     : Benoit HERARD <benoit.herard(at)orange.com>
 * 				 zorglub42 <contact(at)zorglub42.fr>
 *
 * Description :
 *     REST handler for /auth/token request
 *--------------------------------------------------------
 * History     :
 * 1.0.0 - 2013-03-20 : Release of the file
 */

require_once('../include/commonHeaders.php');

require_once '../objects/Error.class.php';
require_once '../include/Constants.php';
require_once '../include/Func.inc.php';
require_once '../include/Settings.ini.php';
require_once '../include/PDOFunc.php';


class Token{
	
	function getAleat(){
		return sprintf("-%010d", rand(1,1000000000)); 
	}

	/**
	 * @url POST 
	 * @url GET 
	 */
	function generate(){
		GLOBAL $BDName;
		GLOBAL $BDUser;
		GLOBAL $BDPwd;
		
		$requestor=getRequestor($_REQUEST);
		$token=time() . $this->getAleat() . $this->getAleat() . $this->getAleat() . $this->getAleat() ;
		

	
		try {
			$db=openDB($BDName, $BDUser, $BDPwd );
			
			$db->exec("DELETE FROM authtoken WHERE validUntil<now()");

			$strSQL="";
			$strSQL=$strSQL . "INSERT INTO authtoken (token, validUntil, userName) ";
			$strSQL=$strSQL . "VALUES (";
			$strSQL=$strSQL . 		"?,"; 
			$strSQL=$strSQL . 		" date_add(now() ,interval ? minute) , ";
			$strSQL=$strSQL . 		"?";
			$strSQL=$strSQL . ")";
			
			$stmt=$db->prepare($strSQL);
			$stmt->execute(array($token, authTokenTTL, $requestor));
		}catch (Exception $e){
			throw new RestException(500,$e->getMessage);
		}
		return Array("token" => $token);
	
	}
	
}
