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
 * File Name   : ApplianceManager/ApplianceManager.php/nodes/nodeDAO.php
 *
 * Created     : 2013-11
 * Authors     : Benoit HERARD <benoit.herard(at)orange.com>
 * 				 zorglub42 <conntact(at)zorglub42.fr>
 *
 * Description :
 *      Manage database access for node object
 *--------------------------------------------------------
 * History     :
 * 1.0.0 - 2013-11-12 : Release of the file
 * 2.0.0 - 2015-08-02 : Update to PDO
*/
require_once '../objects/Error.class.php';
require_once '../objects/Node.class.php';
require_once '../objects/Service.class.php';
require_once '../include/Constants.php';
require_once '../include/Func.inc.php';
require_once '../include/Settings.ini.php';
require_once '../include/PDOFunc.php';
require_once '../api/groupDAO.php';

function getServices($nodeName){
	GLOBAL $BDName;
	GLOBAL $BDUser;
	GLOBAL $BDPwd;
	$error = new OSAError();
	$error->setHttpStatus(200);
	
	$nodeName=normalizeName($nodeName);
	
	try{
		$db=openDB($BDName, $BDUser, $BDPwd);
		$strSQL = "SELECT * FROM services s WHERE (onAllNodes=1 or exists (SELECT 'x' FROM servicesnodes sn WHERE sn.serviceName = s.serviceName AND sn.nodeName=?)) and isPublished=1";
		$stmt=$db->prepare($strSQL);
		$stmt->execute(array(cut($nodeName, NODENAME_LENGTH)));
		$rc = Array();
		while ($row=$stmt->fetch(PDO::FETCH_ASSOC)){
			$service = new Service($row);
			array_push ($rc, $service);
		}
		
	}catch (Exception $e){
			$error->setHttpStatus(500);
			$error->setFunctionalCode(3);
			$error->setFunctionalLabel($e->getMessage());
			throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());
	}

	
	return $rc;
}	


function getDAONode($nodeName = NULL, $request_data = NULL){
	GLOBAL $BDName;
	GLOBAL $BDUser;
	GLOBAL $BDPwd;
	$error = new OSAError();
	$error->setHttpStatus(200);
	
	$nodeName=normalizeName($nodeName);
	
	try {
		$db=openDB($BDName, $BDUser, $BDPwd);
		if ($nodeName != NULL && $nodeName != ""){
			$strSQL = "SELECT * FROM nodes WHERE nodeName=?";
			$stmt=$db->prepare($strSQL);
			$stmt->execute(array(cut($nodeName, NODENAME_LENGTH)));
			
			
			if ($row=$stmt->fetch(PDO::FETCH_ASSOC)){
				$node = new Node($row);
				$rc= $node;
			}else{
				$error->setHttpStatus(404);
				$error->setHttpLabel("Unknown node");
				$error->setFunctionalCode(4);
				$error->setFunctionalLabel("Node ". $nodeName . " does not exists");
				throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());
			}
		}else{
			$strSQLComp="";
			$bindPrms=array();
			
			if (isset($request_data["nodeNameFilter"]) && $request_data["nodeNameFilter"]!=""){
				$strSQLComp = addSQLFilter("nodeName like ?", $strSQLComp);
				array_push($bindPrms, "%" . $request_data["nodeNameFilter"] . "%");
			}
			if (isset($request_data["nodeDescriptionFilter"]) && $request_data["nodeDescriptionFilter"]!=""){
				$strSQLComp = addSQLFilter("nodeDescription like ?", $strSQLComp);
				array_push($bindPrms, "%" . $request_data["nodeDescriptionFilter"] . "%");
			}
			if (isset($request_data["localIPFilter"]) && $request_data["localIPFilter"]!=""){
				$strSQLComp = addSQLFilter("localIP like ?", $strSQLComp);
				array_push($bindPrms,"%" . $request_data["localIPFilter"] . "%");
			}
			if (isset($request_data["portFilter"]) && $request_data["portFilter"]!=""){
				$strSQLComp = addSQLFilter("port=?"  , $strSQLComp);
				array_push($bindPrms,  $request_data["portFilter"]);
			}
			if (isset($request_data["serverFQDNFilter"]) && $request_data["serverFQDNFilter"]!=""){
				$strSQLComp = addSQLFilter("serverFQDN like ?" , $strSQLComp);
				array_push($bindPrms,"%" . $request_data["serverFQDNFilter"] . "%");
			}
			$strSQL="SELECT * FROM nodes n" . $strSQLComp	;
			if (isset($request_data["order"]) && $request_data["order"] != ""){
				$strSQL=$strSQL . " ORDER BY " . EscapeOrder($request_data["order"]);
			}
			
			$stmt=$db->prepare($strSQL);
			$stmt->execute($bindPrms);

			$rc = Array();
			while ($row=$stmt->fetch(PDO::FETCH_ASSOC)){
				$node = new Node($row);
				array_push ($rc, $node);
			}
		}
	}catch  (Exception $e){
		if ($error->getHttpStatus()==200){
				$error->setHttpStatus(500);
				$error->setFunctionalCode(3);
				$error->setFunctionalLabel($e->getMessage());
		}
		throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());
	}

	return $rc;
}



function addNode($nodeName = NULL, $request_data = NULL){
	GLOBAL $BDName;
	GLOBAL $BDUser;
	GLOBAL $BDPwd;
	$error = new OSAError();
			
	$nodeName=normalizeName($nodeName);		


	$error->setHttpStatus(200);
	$error->setHttpLabel("Bad request for method \"" . $_SERVER["REQUEST_METHOD"] . "\" for resource \"nodes\"");


	$error->setFunctionalLabel("Bad request for method \"" . $_SERVER["REQUEST_METHOD"] . "\" for resource \"nodes\"\n");
	$error->setFunctionalCode(0);

	if ($nodeName == NULL || $nodeName=="" ){
		$error->setHttpStatus(400);
		$error->setFunctionalCode(1);
		$error->setFunctionalLabel($error->getFunctionalLabel() . "nodeName is required\n");
	}else{
		$mySQLnodeName=cut($nodeName, NODENAME_LENGTH);
	}

	if (isset($request_data["nodeDescription"])){
		$mySQLnodeDescription=cut($request_data["nodeDescription"], NODEDESCRIPTION_LENGTH);
	}else{
		$mySQLnodeDescription=""; 
	}
	if ($request_data["serverFQDN"] == NULL || $request_data["serverFQDN"]=="" ){
		$error->setHttpStatus(400);
		$error->setFunctionalCode(1);
		$error->setFunctionalLabel($error->getFunctionalLabel() . "serverFQDN is required\n");
	}else{
		$mySQLserverFQDN=cut($request_data["serverFQDN"], SERVERFQDN_LENGTH);
	}
	if ($request_data["localIP"] == NULL || $request_data["localIP"]=="" ){
		$error->setHttpStatus(400);
		$error->setFunctionalCode(1);
		$error->setFunctionalLabel($error->getFunctionalLabel() . "localIP is required\n");
	}else{
		$mySQLlocalIP=cut($request_data["localIP"], LOCALIP_LENGTH);
	}
	if ($request_data["port"] == NULL || $request_data["port"]=="" ){
		$error->setHttpStatus(400);
		$error->setFunctionalCode(1);
		$error->setFunctionalLabel($error->getFunctionalLabel() . "listening port is required\n");
	}else if (is_numeric($request_data["port"]) && $request_data["port"]>0){
		$mySQLport=$request_data["port"];
	}else{
			$error->setHttpStatus(400);
			$error->setFunctionalCode(1);
			$error->setFunctionalLabel($error->getFunctionalLabel() . " allowed value for port is a positive integer\n");
	}
	if (isset($request_data["isHTTPS"])){
		if ($request_data["isHTTPS"]=="1" ||  $request_data["isHTTPS"]=="0"){
			$mySQLisHTTPS=$request_data["isHTTPS"];
		}else{
			$error->setHttpStatus(400);
			$error->setFunctionalCode(1);
			$error->setFunctionalLabel($error->getFunctionalLabel() . " allowed value for isHTTPS is 0 or 1\n");
		}
	}else{
		$error->setHttpStatus(400);
		$error->setFunctionalCode(1);
		$error->setFunctionalLabel($error->getFunctionalLabel() . "isHTTPS is required\n");
	}
	if (isset($request_data["isBasicAuthEnabled"])){
		if ($request_data["isBasicAuthEnabled"]=="1" ||  $request_data["isBasicAuthEnabled"]=="0"){
			$mySQLisBasicAuthEnabled=$request_data["isBasicAuthEnabled"];
		}else{
			$error->setHttpStatus(400);
			$error->setFunctionalCode(1);
			$error->setFunctionalLabel($error->getFunctionalLabel() . " allowed value for isBasicAuthEnabled is 0 or 1\n");
		}
	}else{
			$mySQLisBasicAuthEnabled=1;
	}
	if (isset($request_data["isCookieAuthEnabled"])){
		if ($request_data["isCookieAuthEnabled"]=="1" ||  $request_data["isCookieAuthEnabled"]=="0"){
			$mySQLisCookieAuthEnabled=$request_data["isCookieAuthEnabled"];
		}else{
			$error->setHttpStatus(400);
			$error->setFunctionalCode(1);
			$error->setFunctionalLabel($error->getFunctionalLabel() . " allowed value for isCookieAuthEnabled is 0 or 1\n");
		}
	}else{
			$mySQLisCookieAuthEnabled=1;
	}
	if (isset($request_data["additionalConfiguration"])){
			$mySQLadditionalConfiguration=$request_data["additionalConfiguration"];
	}else{
			$mySQLadditionalConfiguration="";
	}




	if ($error->getHttpStatus() != 200){
		throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());
	}else{
		try{
			$db=openDB($BDName, $BDUser, $BDPwd );
			$strSQL = "INSERT INTO nodes (nodeName, nodeDescription, serverFQDN, localIP, port, isBasicAuthEnabled, isCookieAuthEnabled, isHTTPS, additionalConfiguration, isPublished) values (?, ?, ?, ?, ?, ?, ?, ?, ?, 1)";
			$bindPrms=array($mySQLnodeName,$mySQLnodeDescription,$mySQLserverFQDN,$mySQLlocalIP,$mySQLport,$mySQLisBasicAuthEnabled,$mySQLisCookieAuthEnabled,$mySQLisHTTPS,$mySQLadditionalConfiguration);
			
			$stmt=$db->prepare($strSQL);
			$stmt->execute($bindPrms);
		}catch(Exception $e){
			if (strpos($e->getMessage(),"Duplicate entry")>0){
				$error->setHttpStatus(409);
				$error->setFunctionalLabel("Node " . $nodeName . " already exists");
			}else{
				$error->setHttpStatus(500);
				$error->setFunctionalLabel($e->getMessage());
			}
			$error->setFunctionalCode(3);
			throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());
		}
		$RC=getDAONode($nodeName);
		return $RC;
	}
	
}






function deleteNode($nodeName = NULL){
	GLOBAL $BDName;
	GLOBAL $BDUser;
	GLOBAL $BDPwd;
	$error = new OSAError();
	
	if (isset($nodeName) && isset($nodeName) != ""){
		$nodeName=normalizeName($nodeName);
		
		
		$node=getDAONode($nodeName);
		
		try{
			$db=openDB($BDName, $BDUser, $BDPwd);
			$strSQL="DELETE FROM nodes WHERE  nodeName=?";
			$stmt=$db->prepare($strSQL);
			$stmt->execute(array(cut($nodeName, USERNAME_LENGTH)));
			$rc = $node->toArray();
		}catch (Exception $e){
				$error->setHttpStatus(500);
				$error->setFunctionalCode(3);
				$error->setFunctionalLabel($e->getMessage());
				throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());
		}
	}else{
		$error->setHttpLabel("Bad request for method \"" . $_SERVER["REQUEST_METHOD"] . "\" for resource \"node\"");
		$error->setHttpStatus(400);
		$error->setFunctionalCode(1);
		$error->setFunctionalLabel($error->getFunctionalLabel() . "nodeName is required\n");
		throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());
	}

	return $rc;
}

function setPublicationStatus($nodeName, $published){
	GLOBAL $BDName;
	GLOBAL $BDUser;
	GLOBAL $BDPwd;

	$nodeName=normalizeName($nodeName);

	getDAONode($nodeName);
	try{
		$db=openDB($BDName, $BDUser, $BDPwd );

		$strSQL = "";
		$strSQL = $strSQL  . "UPDATE nodes SET ";
		$strSQL = $strSQL  . "      isPublished=? ";
		$strSQL = $strSQL  . "WHERE nodeName=?";

		$stmt=$db->prepare($strSQL);
		$stmt->execute(array($published,$nodeName));
	}catch (Exception $e){
	
		$error->setHttpStatus(500);
		$error->setFunctionalCode(3);
		$error->setFunctionalLabel($e->getMessage());
		throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());
	}
}


function updateNode($nodeName = NULL, $request_data = NULL){
	GLOBAL $BDName;
	GLOBAL $BDUser;
	GLOBAL $BDPwd;

	$nodeName=normalizeName($nodeName);


	$error = new OSAError();
	$error->setHttpStatus(200);

	if ($nodeName == NULL || $nodeName=="" ){
		$error->setHttpStatus(400);
		$error->setFunctionalCode(1);
		$error->setFunctionalLabel($error->getFunctionalLabel() . "nodeName is required\n");
	}else{
		$mySQLnodeName=cut($nodeName, USERNAME_LENGTH);
	}

	if (isset($request_data["nodeDescription"])){
		$mySQLnodeDescription=cut($request_data["nodeDescription"], NODEDESCRIPTION_LENGTH);
	}else{
		$mySQLnodeDescription=""; 
	}
	if ($request_data["serverFQDN"] == NULL || $request_data["serverFQDN"]=="" ){
		$error->setHttpStatus(400);
		$error->setFunctionalCode(1);
		$error->setFunctionalLabel($error->getFunctionalLabel() . "serverFQDN is required\n");
	}else{
		$mySQLserverFQDN=cut($request_data["serverFQDN"], SERVERFQDN_LENGTH);
	}
	if ($request_data["localIP"] == NULL || $request_data["localIP"]=="" ){
		$error->setHttpStatus(400);
		$error->setFunctionalCode(1);
		$error->setFunctionalLabel($error->getFunctionalLabel() . "localIP is required\n");
	}else{
		$mySQLlocalIP=cut($request_data["localIP"], LOCALIP_LENGTH) ;
	}
	if ($request_data["port"] == NULL || $request_data["port"]=="" ){
		$error->setHttpStatus(400);
		$error->setFunctionalCode(1);
		$error->setFunctionalLabel($error->getFunctionalLabel() . "listening port is required\n");
	}else if (is_numeric($request_data["port"]) && $request_data["port"]>0){
		$mySQLport=$request_data["port"];
	}else{
			$error->setHttpStatus(400);
			$error->setFunctionalCode(1);
			$error->setFunctionalLabel($error->getFunctionalLabel() . " allowed value for port is a positive integer\n");
	}
	if (isset($request_data["isHTTPS"])){
		if ($request_data["isHTTPS"]=="1" ||  $request_data["isHTTPS"]=="0"){
			$mySQLisHTTPS=$request_data["isHTTPS"];
		}else{
			$error->setHttpStatus(400);
			$error->setFunctionalCode(1);
			$error->setFunctionalLabel($error->getFunctionalLabel() . " allowed value for isHTTPS is 0 or 1\n");
		}
	}else{
		$error->setHttpStatus(400);
		$error->setFunctionalCode(1);
		$error->setFunctionalLabel($error->getFunctionalLabel() . "isHTTPS is required\n");
	}
	if (isset($request_data["isBasicAuthEnabled"])){
		if ($request_data["isBasicAuthEnabled"]=="1" ||  $request_data["isBasicAuthEnabled"]=="0"){
			$mySQLisBasicAuthEnabled=$request_data["isBasicAuthEnabled"];
		}else{
			$error->setHttpStatus(400);
			$error->setFunctionalCode(1);
			$error->setFunctionalLabel($error->getFunctionalLabel() . " allowed value for isBasicAuthEnabled is 0 or 1\n");
		}
	}else{
			$mySQLisBasicAuthEnabled=1;
	}
	if (isset($request_data["isCookieAuthEnabled"])){
		if ($request_data["isCookieAuthEnabled"]=="1" ||  $request_data["isCookieAuthEnabled"]=="0"){
			$mySQLisCookieAuthEnabled=$request_data["isCookieAuthEnabled"];
		}else{
			$error->setHttpStatus(400);
			$error->setFunctionalCode(1);
			$error->setFunctionalLabel($error->getFunctionalLabel() . " allowed value for isCookieAuthEnabled is 0 or 1, not " . $request_data["isCookieAuthEnabled"] ."\n");
		}
	}else{
			$mySQLisCookieAuthEnabled=1;
	}
	if (isset($request_data["additionalConfiguration"])){
			$mySQLadditionalConfiguration=$request_data["additionalConfiguration"];
	}else{
			$mySQLadditionalConfiguration="";
	}


	if ($error->getHttpStatus() != 200){
		throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());
	}else{
		getDAONode($nodeName);
		try{
			$db=openDB($BDName, $BDUser, $BDPwd );

			$strSQL = "";
			$strSQL = $strSQL  . "UPDATE nodes SET ";
			$strSQL = $strSQL  . "      nodeDescription=?, ";
			$strSQL = $strSQL  . "      isHTTPS=?, ";
			$strSQL = $strSQL  . "      isCookieAuthEnabled=?, ";
			$strSQL = $strSQL  . "      isBasicAuthEnabled=?, ";
			$strSQL = $strSQL  . "      localIP=?, ";
			$strSQL = $strSQL  . "      port=?, ";
			$strSQL = $strSQL  . "      serverFQDN=?, ";
			$strSQL = $strSQL  . "      additionalConfiguration=? ";
			$strSQL = $strSQL  . "WHERE nodeName=?";

			$stmt=$db->prepare($strSQL);
			$stmt->execute(array($mySQLnodeDescription,$mySQLisHTTPS,$mySQLisCookieAuthEnabled,$mySQLisBasicAuthEnabled,$mySQLlocalIP,$mySQLport,$mySQLserverFQDN,$mySQLadditionalConfiguration,$mySQLnodeName));
		}catch (Exception $e){
		
			$error->setHttpStatus(500);
			$error->setFunctionalCode(3);
			$error->setFunctionalLabel($e->getMessage());
			throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());
		}
	}
	return getDAONode($nodeName);
}

function updateCert($nodeName, $cert){
	GLOBAL $BDName;
	GLOBAL $BDUser;
	GLOBAL $BDPwd;

	$nodeName=normalizeName($nodeName);

	$error = new OSAError();
	$error->setHttpStatus(200);

	if ($nodeName == NULL || $nodeName=="" ){
		$error->setHttpStatus(400);
		$error->setFunctionalCode(1);
		$error->setFunctionalLabel($error->getFunctionalLabel() . "nodeName is required\n");
	}else{
		$mySQLnodeName=cut($nodeName, USERNAME_LENGTH);
	}




	if ($error->getHttpStatus() != 200){
		throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());
	}else{
		$node=getDAONode($nodeName);
		
		try{
			$db=openDB($BDName, $BDUser, $BDPwd );
			
			$strSQL = "";

			$strSQL = $strSQL  . "UPDATE nodes SET ";
			$strSQL = $strSQL  . "      cert=? ";
			$strSQL = $strSQL  . "WHERE nodeName=?";
			
			$stmt=$db->prepare($strSQL);
			$stmt->execute(array($cert, $mySQLnodeName));
		}catch (Exceptiob $e){
			$error->setHttpStatus(500);
			$error->setFunctionalCode(3);
			$error->setFunctionalLabel($e->getMessaqge());
			throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());
		}
	}
}
function updateCaCert($nodeName, $ca){
	GLOBAL $BDName;
	GLOBAL $BDUser;
	GLOBAL $BDPwd;

	$nodeName=normalizeName($nodeName);

	$error = new OSAError();
	$error->setHttpStatus(200);

	if ($nodeName == NULL || $nodeName=="" ){
		$error->setHttpStatus(400);
		$error->setFunctionalCode(1);
		$error->setFunctionalLabel($error->getFunctionalLabel() . "nodeName is required\n");
	}else{
		$mySQLnodeName=cut($nodeName, USERNAME_LENGTH);
	}




	if ($error->getHttpStatus() != 200){
		throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());
	}else{

		$node=getDAONode($nodeName);
		
		

		
		try{
			$db=openDB($BDName, $BDUser, $BDPwd );

			$strSQL = "";
			$strSQL = $strSQL  . "UPDATE nodes SET ";
			$strSQL = $strSQL  . "       ca=? ";
			$strSQL = $strSQL  . "WHERE nodeName=?";
			
			$stmt=$db->prepare($strSQL);
			$stmt->execute(array($ca, $mySQLnodeName));
		}catch (Exception $e){
			$error->setHttpStatus(500);
			$error->setFunctionalCode(3);
			$error->setFunctionalLabel($e->getMessage());
			throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());
		}
		
	}
}
function updateCaChain($nodeName, $caChain){
	GLOBAL $BDName;
	GLOBAL $BDUser;
	GLOBAL $BDPwd;

	$nodeName=normalizeName($nodeName);

	$error = new OSAError();
	$error->setHttpStatus(200);

	if ($nodeName == NULL || $nodeName=="" ){
		$error->setHttpStatus(400);
		$error->setFunctionalCode(1);
		$error->setFunctionalLabel($error->getFunctionalLabel() . "nodeName is required\n");
	}else{
		$mySQLnodeName=cut($nodeName, USERNAME_LENGTH);
	}




	if ($error->getHttpStatus() != 200){
		throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());
	}else{

		$node=getDAONode($nodeName);
		
		

		
		try{
			$db=openDB($BDName, $BDUser, $BDPwd );

			$strSQL = "";
			$strSQL = $strSQL  . "UPDATE nodes SET ";
			$strSQL = $strSQL  . "       caChain=? ";
			$strSQL = $strSQL  . "WHERE nodeName=?";
			
			$stmt=$db->prepare($strSQL);
			$stmt->execute(array($caChain, $mySQLnodeName));
		}catch (Exception $e){
			$error->setHttpStatus(500);
			$error->setFunctionalCode(3);
			$error->setFunctionalLabel($e->getMessage());
			throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());
		}
		
	}
}


function updatePrivateKey($nodeName, $key){
	GLOBAL $BDName;
	GLOBAL $BDUser;
	GLOBAL $BDPwd;

	$nodeName=normalizeName($nodeName);

	$error = new OSAError();
	$error->setHttpStatus(200);

	if ($nodeName == NULL || $nodeName=="" ){
		$error->setHttpStatus(400);
		$error->setFunctionalCode(1);
		$error->setFunctionalLabel($error->getFunctionalLabel() . "nodeName is required\n");
	}else{
		$mySQLnodeName=cut($nodeName, USERNAME_LENGTH);
	}




	if ($error->getHttpStatus() != 200){
		throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());
	}else{

		$node=getDAONode($nodeName);
		
		


		
		try{
			$db=openDB($BDName, $BDUser, $BDPwd );

			$strSQL = "";
			$strSQL = $strSQL  . "UPDATE nodes SET ";
			$strSQL = $strSQL  . "       privateKey=? ";
			$strSQL = $strSQL  . "WHERE nodeName=?";
			
			$stmt=$db->prepare($strSQL);
			$stmt->execute(array($key, $mySQLnodeName));
		}catch (Exception $e){
			$error->setHttpStatus(500);
			$error->setFunctionalCode(3);
			$error->setFunctionalLabel($e->getMessage());
			throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());
		}
		
	}
}

