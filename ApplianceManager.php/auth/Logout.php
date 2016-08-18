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
 * File Name   : ApplianceManager/ApplianceManager.php/users/Logout.php
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
require_once '../include/PDOFunc.php';

class Logout{
	/**
	 * @url DELETE  
	 * @url GET 
	 */
	function deleteTokensOfUserFromToken(){
		GLOBAL $BDName;
		GLOBAL $BDUser;
		GLOBAL $BDPwd;
		
		
		$error = new OSAError();
		$error->setHttpStatus(200);
		if (isset($_COOKIE[authTokenCookieName])){
			try {
				$db=openDB($BDName, $BDUser, $BDPwd );
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
}
?>
