<?php
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
 * File Name   : ApplianceManager/ApplianceManager.php/scripts/generateApacheConfig.php
 *
 * Created     : 2012-02
 * Authors     : Benoit HERARD <benoit.herard(at)orange.com>
 * 				 zorglub42 <conntact(at)zorglub42.fr>
 *
 * Description :
 *      .../...
 *--------------------------------------------------------
 * History     :
 * 1.0.0 - 2012-10-01 : Release of the file
 * 2.0.0 - 2015-08-02 : Update to PDO
*/

require_once '../include/Constants.php';
require_once '../include/Settings.ini.php';
require_once '../include/Func.inc.php';
require_once '../include/PDOFunc.php';
require_once '../api/serviceDAO.php';



	$db=openDBConnection();
	
	
	$strSQLBase="";
	$strSQLBase= $strSQLBase . "SELECT 	* ";
	$strSQLBase= $strSQLBase . "FROM	services s ";
	$strSQLBase= $strSQLBase . "WHERE 	s.isPublished=1 ";
	$strSQLBase= $strSQLBase . "AND 	( ";
	$strSQLBase= $strSQLBase . "			onAllNodes=1 ";
	$strSQLBase= $strSQLBase . "		OR ";
	$strSQLBase= $strSQLBase . "			exists(SELECT 'x' FROM servicesnodes sn WHERE sn.serviceName=s.serviceName AND sn.nodeName=?)";
	$strSQLBase= $strSQLBase . "		) ";
	
	$strSQL = $strSQLBase . " ORDER BY frontEndEndPoint ASC"; 
	
	$stmt=$db->prepare($strSQL);
	$stmt->execute(array($_REQUEST["node"]));

	
	header("Content-Type: text/plain");
	echo "#######################################################################\n";
	echo "#\n";
	echo "#                         IMPORTANT NOTE\n";
	echo "#\n";
	echo "# This file is auto generated by ApplianceManager (GUI/REST-services)\n";
	echo "# Any modification done manually in this will be erased on next\n";
	echo "# regeneration by the application\n";
	echo "#\n";
	echo "#######################################################################\n";
	echo "\n\n\n";
		
	while ($row=$stmt->fetch(PDO::FETCH_ASSOC)){
		$BACK_END     = $row["backEndEndPoint"];
		$FRONT_END    = $row["frontEndEndPoint"];
		$GROUP_NAME   = $row["groupName"];
		$SERVICE_NAME = $row["serviceName"];
		if ($row["isGlobalQuotasEnabled"]==1){
			$GLOBAL_QUOTA_ENABLE="On";
		}else{
			$GLOBAL_QUOTA_ENABLE="Off";
		}
		if ($row["isUserQuotasEnabled"]==1){
			$USER_QUOTA_ENABLE="On";
		}else{
			$USER_QUOTA_ENABLE="Off";
		}
		$BASIC_AUTH_TOKEN="";
		if ($row["backEndUsername"] != "" && decrypt($row["backEndPassword"]) != "" && $row["backEndUsername"] != "%auto%"){
			$BASIC_AUTH_TOKEN=base64_encode($row["backEndUsername"] . ":" . decrypt($row["backEndPassword"]));
		}
		$FORWARD_AUTH_TOKEN=false;
		if ($row["backEndUsername"] === "%auto%" ){
			$FORWARD_AUTH_TOKEN=true;
		}

		$FORWARD_IDENT=true;
		if ($row["isIdentityForwardingEnabled"]==0){
			$FORWARD_IDENT=false;
			
		}else{
			$IDENTITY_MAPPING="";
			foreach (getServiceHeadersMapping($SERVICE_NAME) as $mapping){
				if (!empty($IDENTITY_MAPPING)){
					$IDENTITY_MAPPING=$IDENTITY_MAPPING. ";";
				}
				$IDENTITY_MAPPING=$IDENTITY_MAPPING . $mapping["userProperty"] . "," . $mapping["headerName"];
			}
			if (empty($IDENTITY_MAPPING)){
				foreach($userProperties as $property){
					if (!empty($IDENTITY_MAPPING)){
						$IDENTITY_MAPPING=$IDENTITY_MAPPING. ";";
					}
					$IDENTITY_MAPPING=$IDENTITY_MAPPING . $property . "," . $defaultHeadersName[$property];
				}
			}
		}
		//$BACK_END_DOMAIN=getServerDomain($BACK_END);
		//$BACK_END_PATH = getPath($BACK_END);
		$FRONT_END_TOP_DOMAIN="";
		$FRONT_END_DOMAIN=$_REQUEST["domain"] ;
		$domParts=explode(".", $_REQUEST["domain"]);
		if (count($domParts)>1){
			for ($i=1;$i<count($domParts);$i++){
				$FRONT_END_TOP_DOMAIN = $FRONT_END_TOP_DOMAIN . "." . $domParts[$i];
			}
		}else{
			$FRONT_END_TOP_DOMAIN="";
		}
		if ($row["isHitLoggingEnabled"]==1){
			$HIT_LOGGING_ENABLE="On";
		}else{
			$HIT_LOGGING_ENABLE="Off";
		}
		if ($row["isUserAuthenticationEnabled"]==1){
			$USER_AUTHENTICATION_ENABLE="On";
			$BASIC_AUTH_ENABLED= ($_REQUEST["BasicAuthEnabled"] == 1);
			$COOKIE_AUTH_ENABLED= ($_REQUEST["CookieAuthEnabled"] == 1);
		}else{
			$USER_AUTHENTICATION_ENABLE="Off";
			$BASIC_AUTH_ENABLED= false;
			$COOKIE_AUTH_ENABLED= false;
		}
		$ANONYMOUS_ALLOWED = ($row["isAnonymousAllowed"]==1);
		if ($row["additionalConfiguration"] != ""){
			$ADDITIONAL_CONFIGURATION=str_replace("%{frontEndEndPoint}e", $row["frontEndEndPoint"], $row["additionalConfiguration"]);
		}else{
			$ADDITIONAL_CONFIGURATION="";
		}
		
		$LOGIN_FORM_URI="";
		if ($COOKIE_AUTH_ENABLED){
			$LOGIN_FORM_URI=$row["loginFormUri"];
		}
		
		$PUBLIC_SERVER_PREFIX=$_REQUEST["serverPrefix"];
		
		include "../resources/apache.conf/endpoint_template.php";
	}
	
	

	$strSQL= $strSQLBase . "ORDER BY frontEndEndPoint DESC ";

	$stmt=$db->prepare($strSQL);
	$stmt->execute(array($_REQUEST["node"]));
	while ($row=$stmt->fetch(PDO::FETCH_ASSOC)){
		$BACK_END     = $row["backEndEndPoint"];
		$FRONT_END    = $row["frontEndEndPoint"];

		#echo 'RewriteCond     %{REQUEST_URI}      ^' . $FRONT_END . '.*$' . "\n";
		#echo 'RewriteRule  <?' . $FRONT_END .'(.*) ' . $BACK_END . '$1 [P,L]' . "\n\n";
		echo "ProxyPass $FRONT_END $BACK_END nocanon\n\n";
	}
	
	
	
?>
