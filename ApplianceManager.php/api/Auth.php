<?php
/*--------------------------------------------------------
 * Module Name : ApplianceManager
 * Version : 1.0.0
 *
 * Software Name : OpenServicesAccess
 * Version : 2.0
 *
 * Copyright (c) 2011 – 2017 Orange
 * This software is distributed under the Apache 2 license
 * <http://www.apache.org/licenses/LICENSE-2.0.html>
 *
 *--------------------------------------------------------
 * File Name   : ApplianceManager/ApplianceManager.php/users/Login.php
 *
 * Created     : 2017-03
 * Authors     : Benoit HERARD <benoit.herard(at)orange.com>
 *
 * Description :
 *      Authentication API
 * 
 *--------------------------------------------------------
 * History     :
 * 1.0.0 - 2017-03-06 : Release of the file
*/
require_once('../include/commonHeaders.php');

require_once '../objects/Error.class.php';
require_once '../objects/AuthToken.class.php';
require_once '../include/Constants.php';
require_once '../include/Func.inc.php';
require_once '../include/PDOFunc.php';
require_once '../include/HTTPClient.php';
require_once '../include/Settings.ini.php';

/**
 * Authentication management
 * 
 * Services to login, logout and generate authentication token
 */
class Auth{
	/**
	 * Logout from system 
	 * 
	 * Logout and unset authentication cookie
	 * 
	 * @url DELETE /logout  
	 * @url GET /logout
	 * 
	 * @return string previously connected userName 
	 */
	function deleteTokensOfUserFromToken(){

		$error = new OSAError();
		$error->setHttpStatus(200);
		if (isset($_COOKIE[authTokenCookieName])){
			try {
				$db=openDBConnection();
				$strSQL="";
				$strSQL=$strSQL . "SELECT * FROM authtoken WHERE token=?";
				
				$stmt=$db->prepare($strSQL);
				$stmt->execute(array($_COOKIE[authTokenCookieName]));
				
				$row = $stmt->fetch(PDO::FETCH_ASSOC);
				if (!$row){
					$error->setHttpStatus(404);
					$error->setFunctionalLabel("Session not found");

				}else{
					$stmt=$db->prepare("DELETE FROM authtoken WHERE userName=?");
					$stmt->execute(array($row["userName"]));
					setcookie(authTokenCookieName, rand(1,1000000000), time()-3600,"/");
					return $row["userName"];
				}
			}catch (Exception $e){
				if ($error->getHttpStatus() != 200){
					throw new RestException($error->getHttpStatus(),$error->getFunctionalLabel());
				}else{
					throw new RestException(500,$e->getMessage());
				}
			}
		}else{
			throw new RestException(400,"Session cookie not found");
		}
	}
	
	/**
	 * Login
	 * 
	 * Login with user/passord and set authentication cookie
	 * 
	 * @param string userName User namme to log in
	 * @param string password Password to authenticate
	 * @param string d domain to set cookie
	 * 
	 * @url POST /login
	 * 
	 * @return AuthToken Authentication token 
	 */
	function generateTokenFormUserAndPass($userName, $password, $d=null){

			$httpClient = new HttpClient("","","","",true);
			
			$headers=Array("Accept: application/json");
			$httpResponse=$httpClient->Post(osaAdminUri . "/auth/token", "", $headers, $userName,$password);
			if ($httpResponse->getStatusCode() != 200){
				throw new RestException($httpResponse->getStatusCode(), $httpResponse->getStatusLabel() . "(backend=" . osaAdminUri . "/auth/token" . ")" .$httpResponse->getBody());
			}
			/*foreach ($httpResponse->getHeaders() as $key => $value){
					header($key . ": " . $value);
			}*/
			$tokenObj=json_decode($httpResponse->getBody(), true);
			if (!empty($d)){
				setcookie(authTokenCookieName, $tokenObj["token"],NULL,"/",$d);
			}else{
				setcookie(authTokenCookieName, $tokenObj["token"],NULL,"/");
			}


			$db=openDBConnection();
			

			$strSQL="";
			$strSQL=$strSQL . "UPDATE users SET lastTokenLogin=" . getSQlKeyword("now") . " WHERE userName=? ";
			
			$stmt=$db->prepare($strSQL);
			$stmt->execute(array($userName));
			

			return $tokenObj;
	}
	private function getAleat(){
		return sprintf("-%010d", rand(1,1000000000)); 
	}

	/**
	 * Generate authentication token for authenticated user
	 * 
	 * @url POST /token 
	 * 
	 * @return AuthToken Token
	 */
	function generate(){

		$requestor=getRequestor($_REQUEST);
		$token=time() . $this->getAleat() . $this->getAleat() . $this->getAleat() . $this->getAleat() ;
		

	
		try {
			$db=openDBConnection();
			
			$db->exec("DELETE FROM authtoken WHERE validUntil<" . getSQLKeyword("now"));

			$strSQL="";
			$strSQL=$strSQL . "INSERT INTO authtoken (token, validUntil, userName) ";
			$strSQL=$strSQL . "VALUES (";
			$strSQL=$strSQL . 		"?,"; 
			$strSQL=$strSQL . 		" " . getSQlKeyword("add_minute") . " , ";
			$strSQL=$strSQL . 		"?";
			$strSQL=$strSQL . ")";

			$stmt=$db->prepare($strSQL);
			if (RDBMS == "mysql"){
				$timeInterval = authTokenTTL;
			}else{
				$timeInterval = "+" . authTokenTTL . " minute";
			}
			$stmt->execute(array($token, $timeInterval, $requestor));
		}catch (Exception $e){
			throw new RestException(500,$e->getMessage());
		}
		return Array("token" => $token);
	
	}
}
?>
