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
 * File Name   : ApplianceManager/ApplianceManager.php/users/Login.php
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
require_once '../include/HTTPClient.php';
require_once '../include/Settings.ini.php';

class Login{
	/**
	 * @url POST 
	 */
	function generateTokenFormUserAndPass($request_data){

		if (!isset($request_data["userName"]) || $request_data["userName"]=="" ||
		    !isset($request_data["password"]) || $request_data["password"]==""){
				throw new RestException(400,"Parameters userName and password are required");
		}
			$httpClient = new HttpClient("","","","",true);
			
			$headers=Array("Accept: application/json");
			$httpResponse=$httpClient->Post(osaAdminUri . "/auth/token", "", $headers, $request_data["userName"],$request_data["password"]);
			if ($httpResponse->getStatusCode() != 200){
				
				throw new RestException($httpResponse->getStatusCode(), $httpResponse->getStatusLabel() . "(backend=" . osaAdminUri . "/auth/token" . ")");
			}
			/*foreach ($httpResponse->getHeaders() as $key => $value){
					header($key . ": " . $value);
			}*/
			$tokenObj=json_decode($httpResponse->getBody(), true);
			if (isset($request_data["d"]) && $request_data["d"] != ""){
				setcookie(authTokenCookieName, $tokenObj["token"],NULL,"/",$request_data["d"]);
			}else{
				setcookie(authTokenCookieName, $tokenObj["token"],NULL,"/");
			}

			return $tokenObj;
	}
}
?>
