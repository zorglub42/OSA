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
 * File Name   : ApplianceManager/ApplianceManager.php/users/userDAO.php
 *
 * Created     : 2013-11
 * Authors     : Benoit HERARD <benoit.herard(at)orange.com>
 * 				 zorglub42 <contact(at)zorglub42.fr>
 *
 * Description :
 *      Manage database access for user object
 *--------------------------------------------------------
 * History     :
 * 1.0.0 - 2013-11-12 : Release of the file
 * 2.0.0 - 2015-08-02 : update to PDO 
*/
require_once '../objects/Error.class.php';
require_once '../objects/Group.class.php';
require_once '../objects/User.class.php';
require_once '../objects/Quota.class.php';
require_once '../objects/Service.class.php';
require_once '../include/Constants.php';
require_once '../include/Func.inc.php';
require_once '../include/Settings.ini.php';
require_once '../include/PDOFunc.php';
require_once 'groupDAO.php';



function setUserProperties($db, $userName, $properties) {
	if (!is_null($properties)) {
		$stmt = $db->prepare("DELETE FROM additionnaluserproperties WHERE userName=?");
			
		$stmt->execute(array($userName));
		foreach ($properties as $p) {
			$stmt = $db->prepare("INSERT INTO additionnaluserproperties(userName, propertyName, value) values (?, ?, ?)");
			$prms=array($userName, $p->name, $p->value);
			$stmt->execute($prms);
		}
	}		
}


function getUserProperties($db, $user) {
	$userProps=$user->getProperties();
	$stmt = $db->prepare("SELECT * FROM additionnaluserproperties WHERE userName=?");
		
	$stmt->execute(array($user->getUsername()));
	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		$property = new UserProperty($row);
		array_push($userProps, $property);
		
	}
	$user->setProperties($userProps);

}

function getUser($userName = NULL, $request_data = NULL){
	@include '../include/Settings.ini.php';

	$error = new OSAError();
			

	$userName=normalizeName($userName, ".");

		
	if ($userName==="me"){
		$hdrs=getallheaders();
		if (isset($hdrs[$defaultHeadersName["userName"]])){
			$userName=$hdrs[$defaultHeadersName["userName"]];
		}else{
			throw new Exception("current user not found in request");
		}

	}
	$db=openDBConnection();
	if ($userName != NULL && $userName != ""){
		$stmt = $db->prepare("SELECT * FROM users WHERE userName=?");
		try{
			$stmt->execute(array($userName));
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			if ($row){
				$user = new User($row);
				getUserProperties($db, $user);
				$rc= $user->toArray();
			}else{
				$error->setHttpStatus(404);
				$error->setHttpLabel("Unknown user");
				$error->setFunctionalCode(4);
				$error->setFunctionalLabel("User ". $userName . " does not exists");
				throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());
			}
		}catch (Exception $e){
			if ($error->getHttpStatus() == 200){
				$error->setHttpStatus(500);
				$error->setFunctionalCode(3);
				$error->setFunctionalLabel($e->getMessage());
			}
			throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());
		}
	}else{
		$strSQLComp="";
		$prmBind=array();
		if (isset($request_data["extraFilter"]) && !empty($request_data["extraFilter"])){
			$strSQLComp=" WHERE exists(SELECT 'x' FROM additionnaluserproperties up WHERE up.userName=u.userName AND value like ?)";
			array_push($prmBind, "%" . $request_data["extraFilter"] . "%");
		}
		if (isset($request_data["withLog"]) && $request_data["withLog"]==1){
			$strSQLComp=" WHERE exists(SELECT 'x' FROM hits h WHERE h.userName=u.userName)";
		}elseif (isset($request_data["withLog"]) && $request_data["withLog"]==0){
			$strSQLComp=" WHERE not exists(SELECT 'x' FROM hits h WHERE h.userName=u.userName)";
		}
		
		if (isset($request_data["userNameFilter"]) && $request_data["userNameFilter"]!==""){
			$strSQLComp = addSQLFilter("userName like ?", $strSQLComp);
			array_push($prmBind, "%" . $request_data["userNameFilter"] . "%");
		}
		if (isset($request_data["firstNameFilter"]) && $request_data["firstNameFilter"]!==""){
			$strSQLComp = addSQLFilter("firstName like ?", $strSQLComp);
			array_push($prmBind, "%" . $request_data["firstNameFilter"] . "%");
		}
		if (isset($request_data["lastNameFilter"]) && $request_data["lastNameFilter"]!==""){
			$strSQLComp = addSQLFilter("lastName like ?", $strSQLComp);
			array_push($prmBind, "%" . $request_data["lastNameFilter"] . "%");
		}
		if (isset($request_data["emailAddressFilter"]) && $request_data["emailAddressFilter"]!==""){
			$strSQLComp = addSQLFilter("emailAddress like ?", $strSQLComp);
			array_push($prmBind, "%" . $request_data["emailAddressFilter"] . "%");
		}
		if (isset($request_data["entityFilter"]) && $request_data["entityFilter"]!==""){
			$strSQLComp = addSQLFilter("entity like ?", $strSQLComp);
			array_push($prmBind, "%" . $request_data["entityFilter"] . "%");
		}
		$strSQL="SELECT * FROM users u" . $strSQLComp	;
		if (isset($request_data["order"]) && $request_data["order"] != ""){
			$strSQL=$strSQL . " ORDER BY " . escapeOrder($request_data["order"]);
		}
		$stmt = $db->prepare($strSQL);
		try{
			$stmt->execute($prmBind);
			$rc = Array();
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				$user = new User($row);
				getUserProperties($db, $user);
				array_push ($rc, $user->toArray());
			}
		}catch (Exception $e){
			if ($error->getHttpStatus() == 200){
				$error->setHttpStatus(500);
				$error->setFunctionalCode(3);
				$error->setFunctionalLabel($e->getMessage());
			}
			throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());
		}
	}
	return $rc;
}



function addUser($userName = NULL, $request_data = NULL){
	$userName=normalizeName($userName, ".");
	
	$error = new OSAError();
			
		


	$error->setHttpStatus(200);
	$error->setHttpLabel("Bad request for method \"" . $_SERVER["REQUEST_METHOD"] . "\" for resource \"user\"");


	$error->setFunctionalLabel("Bad request for method \"" . $_SERVER["REQUEST_METHOD"] . "\" for resource \"user\"\n");
	$error->setFunctionalCode(0);

	if ($userName == NULL || $userName=="" ){
		$error->setHttpStatus(400);
		$error->setFunctionalCode(1);
		$error->setFunctionalLabel($error->getFunctionalLabel() . "userName is required\n");
	}else{
		$mySQLuserName=cut($userName, USERNAME_LENGTH);
	}
	if (!isset($request_data["password"]) || $request_data["password"]=="" ){
		$error->setHttpStatus(400);
		$error->setFunctionalCode(1);
		$error->setFunctionalLabel($error->getFunctionalLabel() . "password is required\n");
	}else{
		$mySQLPassword=encrypt($request_data["password"], PASSWORD_LENGTH) ;
		$mySQLmd5Password=  md5($request_data["password"]) ;
	}
	if (!isset($request_data["email"]) || $request_data["email"]=="" ){
		$mySQLEmail=null;
	}else{
		$mySQLEmail= cut($request_data["email"], EMAIL_LENGTH) ;
	}

	if (!isset($request_data["endDate"]) || $request_data["endDate"]=="" ){
		$mySQLEndDate=null;
	}else{
		if ( preg_match( "/([0-9]{4})-([0-9]{2})-([0-9]{2})/", $request_data["endDate"], $regs ) ) {
		  $mySQLEndDate = "$regs[1]-$regs[2]-$regs[3] 00:00:00";
		} else {
			$error->setHttpStatus(400);
			$error->setFunctionalCode(2);
			$error->setFunctionalLabel($error->getFunctionalLabel() . "Invalid date format for \"" . $request_data["endDate"] . "\" YYYY-MM-DD (iso) expected\n");
		}
	}
	if (isset($request_data["firstName"])){
		$mySQLFirstname=cut($request_data["firstName"], FIRSTNAME_LENGTH) ;
	}else{
		$mySQLFirstname=null; 
	}
	if (isset($request_data["lastName"])){
		$mySQLLastname= cut($request_data["lastName"], USERNAME_LENGTH);
	}else{
		$mySQLLastname=null; 
	}
	if (isset($request_data["entity"])){
		$mySQLEntity=cut($request_data["entity"], ENTITY_LENGTH) ;
	}else{
		$mySQLEntity=null; 
	}
	if(isset($request_data["properties"])) {
		$properties=$request_data["properties"];
	}else{
		$properties=null;
	
	}



	if ($error->getHttpStatus() != 200){
		throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());
	}else{
		$strSQL = "INSERT INTO users (userName, password, md5Password, endDate, emailAddress, firstName, lastName, entity) values (?, ?, ?, ?, ?, ?, ?, ?)";
		try{
			
			$db=openDBConnection();
			$stmt=$db->prepare($strSQL);
			$db->beginTransaction();
			$stmt->execute(array($mySQLuserName,$mySQLPassword,$mySQLmd5Password,$mySQLEndDate,$mySQLEmail,$mySQLFirstname,$mySQLLastname,$mySQLEntity));
			setUserProperties($db, $mySQLuserName, $properties);
			$db->commit();
		}catch (Exception $e){
				$db->rollBack();
				$error->setFunctionalCode(5);
				if (strpos($e->getMessage(),"Duplicate entry")>=0 ||strpos($e->getMessage(),"UNIQUE constraint failed")>=0 ){
					$error->setHttpStatus(409);
					$error->setFunctionalLabel("User " . $userName . " already exists");
				}else{
					$error->setHttpStatus(500);
					$error->setFunctionalLabel($e->getMessage());
				}
				throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());
		}
		
		if ($error->getHttpStatus() != 200){
			throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());
		}else{
			return getUser($userName);
		}
	}
}






function deleteUser($userName){
	$userName=normalizeName($userName, ".");
	$error= new OSAError() ;
	if (isset($userName) && isset($userName) != ""){
		if ($userName == ADMIN_USER){
			$error->setHttpStatus(403);
			$error->setFunctionalCode(3);
			$error->setFunctionalLabel("User Admin can't be deleted");
			throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());
		}
	}else{
		$error->setHttpLabel("Bad request for method \"" . $_SERVER["REQUEST_METHOD"] . "\" for resource \"user\"");
		$error->setHttpStatus(400);
		$error->setFunctionalCode(1);
		$error->setFunctionalLabel($error->getFunctionalLabel() . "userName is required\n");
		throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());
	}

	$usr=getUser($userName);
	
	try{
		$db=openDBConnection();
		$strSQL="DELETE FROM users WHERE userName=?";
		$stmt=$db->prepare($strSQL);
		$stmt->execute(array(cut($userName, USERNAME_LENGTH)));

		$strSQL="DELETE FROM counters WHERE  counterName like ?";
		$stmt=$db->prepare($strSQL);
		$stmt->execute(array("%U=" . cut($userName, USERNAME_LENGTH) . "%"));

	}catch(Exception $e){
		$error->setHttpStatus(500);
		$error->setFunctionalCode(3);
		$error->setFunctionalLabel($e->getMessage());
		throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());
	}
	return $usr;
}

function updateUserPassword($userName, $newPassword){
	$error = new OSAError();


	$userName=normalizeName($userName, ".");
	$mySQLuserName=  cut($userName, USERNAME_LENGTH) ;
	$mySQLPassword= encrypt($newPassword);
	$mySQLmd5Password= md5($newPassword) ;
	$strSQL = "";

	$strSQL = $strSQL  . "UPDATE users SET ";
	$strSQL = $strSQL  . "      password=?, ";
	$strSQL = $strSQL  . "      md5Password=? ";
	$strSQL = $strSQL  . "WHERE userName=?";
	$bindPrms=array($mySQLPassword, $mySQLmd5Password, $userName);

	try{
		$db=openDBConnection();
		$stmt=$db->prepare($strSQL);
		$stmt->execute($bindPrms);
	}catch (Exception $e){
		$error->setHttpStatus(500);
		$error->setFunctionalCode(3);
		$error->setFunctionalLabel($e->getMessage());
		throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());
	}
	if ($error->getHttpStatus() != 200){
		throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());
	}else{
		return getUser($userName);
	}

}

function updateUser($userName = NULL, $request_data = NULL){
	$error = new OSAError();


	$userName=normalizeName($userName, ".");
	$bindPrms=array();

	
	if ($userName == NULL || $userName=="" ){
		$error->setHttpStatus(400);
		$error->setFunctionalCode(1);
		$error->setFunctionalLabel($error->getFunctionalLabel() . "userName is required\n");
	}else{
		$mySQLuserName= cut($userName, USERNAME_LENGTH);
	}
	
	
	$strUPD="";
	if (isset($request_data["password"]) && !is_null($request_data["password"]) ){
		if ($request_data["password"]=="" ){
			$error->setHttpStatus(400);
			$error->setFunctionalCode(1);
			$error->setFunctionalLabel($error->getFunctionalLabel() . "password is required\n");
		}else{
			$mySQLPassword= encrypt($request_data["password"]);
			$mySQLmd5Password=md5($request_data["password"]);

			array_push($bindPrms, $mySQLPassword);
			array_push($bindPrms, $mySQLmd5Password	);
		}
		$strUPD=$strUPD . "password=?, md5Password=?, ";
	}
	if (isset($request_data["email"])&& !is_null($request_data["email"])) { 
		$mySQLEmail=cut($request_data["email"], EMAIL_LENGTH) ;
		array_push($bindPrms, $mySQLEmail);
		$strUPD = $strUPD . "emailAddress=?, ";
	}
	if (isset($request_data["endDate"])&& !is_null($request_data["endDate"])){
		if ($request_data["endDate"]=="" ){
			$error->setHttpStatus(400);
			$error->setFunctionalCode(1);
			$error->setFunctionalLabel($error->getFunctionalLabel() . "endDate is required\n");
		}else{
			if ( preg_match( "/([0-9]{4})-([0-9]{2})-([0-9]{2})/", $request_data["endDate"], $regs ) ) {
			  $mySQLEndDate = "$regs[1]-$regs[2]-$regs[3] 00:00:00";
			  array_push($bindPrms, $mySQLEndDate);
			  $strUPD=$strUPD . "endDate=?, ";
			} else {
				$error->setHttpStatus(400);
				$error->setFunctionalCode(2);
				$error->setFunctionalLabel($error->getFunctionalLabel() . "Invalid date format for \"" . $request_data["endDate"] . "\" YYYY-MM-DD (iso) expected\n");
			}
		}
	}


	if (isset($request_data["lastName"]) && !is_null($request_data["lastName"])){
		$mySQLLastname=cut($request_data["lastName"], USERNAME_LENGTH) ;
		array_push($bindPrms, $mySQLLastname);
		$strUPD=$strUPD . "lastName=?, "; 
	}
	if (isset($request_data["firstName"]) && !is_null($request_data["firstName"])){
		$mySQLFirstname=cut($request_data["firstName"], FIRSTNAME_LENGTH) ;
		array_push($bindPrms, $mySQLFirstname);
		$strUPD=$strUPD . "firstName=?,"; 
	}
	if (isset($request_data["entity"]) && !is_null($request_data["entity"])){
		$mySQLEntity=cut($request_data["entity"], ENTITY_LENGTH);
		array_push($bindPrms, $mySQLEntity);
		$strUPD=$strUPD . "entity=?, "; 
	}

	if(isset($request_data["properties"])) {
		$properties=$request_data["properties"];
	}else{
		$properties=null;
	
	}


	if ($error->getHttpStatus() != 200){
		throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());
	}else{
		$strSQL = "";

		array_push($bindPrms, $mySQLuserName);
		$strSQL = $strSQL  . "UPDATE users SET " . $strUPD . " userName=userName ";
		$strSQL = $strSQL  . "WHERE userName=?";

		try {
			$db=openDBConnection();
			$db->beginTransaction();
			$stmt=$db->prepare($strSQL);
			$stmt->execute($bindPrms);
			setUserProperties($db, $userName, $properties);
			$db->commit();
		}catch (Exception $e){
			$db->rollBack();
			$error->setHttpStatus(500);
			$error->setFunctionalCode(3);
			$error->setFunctionalLabel($e->getMessage());
			throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());
		}
		
	}
	return getUser($userName);
}




function getDAOUserGroup($userName, $groupName= NULL){


	$userName=normalizeName($userName, ".");
	$groupName=normalizeName($groupName);


	$error = new OSAError();
	$error->setHttpStatus(200);
	
	try{
		$db=openDBConnection();
		$bindPrms=array();

		$strSQL="SELECT g.* FROM groups g, usersgroups ug where g.groupName = ug.groupName and ug.userName=?";
		array_push($bindPrms, cut($userName, USERNAME_LENGTH));

		if ($groupName != NULL && $groupName != ""){
			$strSQL .= " AND g.groupName=?";
			array_push($bindPrms, cut($groupName, GROUPNAME_LENGTH));
			$stmt=$db->prepare($strSQL);
			$stmt->execute($bindPrms);
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			if ($row){
				$group = new Group($row);
				$rc = $group->toArray();
			}else{
				$error->setHttpStatus(404);
				$error->setHttpLabel("Unknown group");
				$error->setFunctionalCode(4);
				$error->setFunctionalLabel("Group ". $groupName . " does not exists for user " . $userName);

				throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());
			}
			
			
		}else{
			$stmt=$db->prepare($strSQL);
			$stmt->execute($bindPrms);
			$rc = Array();
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				$group = new Group($row);
				array_push ($rc, $group->toArray());
			}
		}
	}catch (Exception $e){
		if ($error->getHttpStatus()==200){
			$error->setHttpStatus(500);
			$error->setFunctionalCode(3);
			$error->setFunctionalLabel($e->getMessage());
		}
		throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());
	}

	return $rc;
}



function getAvailableGroup($userName){
	$userName=normalizeName($userName, ".");

	$error = new OSAError();
	
	
	try {
		$strSQL="SELECT g.* FROM groups g where g.groupName not in (SELECT ug.groupName FROM  usersgroups ug WHERE  ug.userName=?)";
		
		$db=openDBConnection();
		$stmt=$db->prepare($strSQL);
		$stmt->execute(array(cut($userName, USERNAME_LENGTH) ));
		$rc = Array();
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			$group = new Group($row);
			if ($group->getGroupName() != VALID_USER_GROUP){
				array_push($rc, $group->toArray());
			}
		}
	}catch (Exception $e){
		$error->setHttpStatus(500);
		$error->setFunctionalCode(3);
		$error->setFunctionalLabel($e->getMessage());
		throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());
	}
	return $rc;
}




function addUserToGroup($userName, $groupName){

	$userName=normalizeName($userName, ".");
	$groupName=normalizeName($groupName);

	$error = new OSAError();
	$error->setHttpStatus(200);
	$error->setHttpLabel("Bad request for method \"" . $_SERVER["REQUEST_METHOD"] . "\" for resource \"group\"");


	$error->setFunctionalLabel("Bad request for method \"" . $_SERVER["REQUEST_METHOD"] . "\" for resource \"group\"\n");
	$error->setFunctionalCode(0);

	if (!isset($groupName) || $groupName=="" ){
		$error->setHttpStatus(400);
		$error->setFunctionalCode(1);
		$error->setFunctionalLabel($error->getFunctionalLabel() . "groupName is required\n");
	}
	if (!isset($userName) || $userName=="" ){
		$error->setHttpStatus(400);
		$error->setFunctionalCode(1);
		$error->setFunctionalLabel($error->getFunctionalLabel() . "userName is required\n");
	}
	if ($error->getFunctionalCode()!=0){
		throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());
	}else{
		$mySQLGroupName=cut($groupName, GROUPNAME_LENGTH);
		$mySQLuserName=cut($userName, USERNAME_LENGTH);
		$strSQL = "INSERT INTO usersgroups (groupName, userName) values (?, ?)";
		try{
			$db=openDBConnection();
			$stmt=$db->prepare($strSQL);
			$stmt->execute(array($mySQLGroupName,$mySQLuserName));
		}catch (Exception $e){
			if (strpos($e->getMessage(),"Duplicate entry")>=0 ||strpos($e->getMessage(),"UNIQUE constraint failed")>=0 ){
				$error->setHttpStatus(409);
				$error->setFunctionalCode(5);
				$error->setFunctionalLabel("Group " . $groupName . " already exists for user " . $userName);
				
			}else{
				$error->setHttpStatus(500);
				$error->setFunctionalCode(3);
				$error->setFunctionalLabel($e->getMessage());
				
				
			}
		}
	}
	return getGroup($groupName);
}




function removeUserFromGroup($userName, $groupName){

	$userName=normalizeName($userName, ".");
	$groupName=normalizeName($groupName);

	$error = new OSAError();

	if ($groupName != NULL && $groupName != ""){
		if ($groupName == ADMIN_GROUP && $userName == ADMIN_USER){
			$error->setHttpStatus(403);
			$error->setFunctionalCode(3);
			$error->setFunctionalLabel("Admin group can't be removed for Admin user");
			throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());
		}
		try{
			$db=openDBConnection();
			
			$strSQL="SELECT g.* FROM groups g, usersgroups ug WHERE ug.groupName=? AND ug.userName=? AND g.groupName = ug.groupName";
			$stmt=$db->prepare($strSQL);
			$stmt->execute(array(cut($groupName, GROUPNAME_LENGTH), cut($userName, USERNAME_LENGTH)));
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			if ($row){
				$group = new Group($row);
				$strSQL="DELETE FROM usersgroups WHERE groupName=? AND userName=?";
				$stmt=$db->prepare($strSQL);
				$stmt->execute(array(cut($groupName, GROUPNAME_LENGTH), cut($userName, USERNAME_LENGTH)));
			}else{
				$error->setHttpStatus(404);
				$error->setHttpLabel("Unknown group for user");
				$error->setFunctionalCode(4);
				$error->setFunctionalLabel("Group ". $groupName . " does not exists for user " . $userName);
				throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());
			}
		}catch (Exception $e){
			if ($error->getHttpStatus() == 200){
				$error->setHttpStatus(500);
				$error->setFunctionalCode(3);
				$error->setFunctionalLabel($e->getMessage());
			}
				throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());
			
		}
		if (!$row){
			$error->setHttpStatus(404);
			$error->setHttpLabel("Unknown group");
			$error->setFunctionalCode(4);
			$error->setFunctionalLabel("Group ". $groupName . " does not exists");
		}
	}else{
		$error->setHttpLabel("Bad request for method \"" . $_SERVER["REQUEST_METHOD"] . "\" for resource \"group\"");
		$error->setHttpStatus(400);
		$error->setFunctionalCode(1);
		$error->setFunctionalLabel($error->getFunctionalLabel() . "groupName is required\n");
		throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());
	}
	return $group->toArray();

}


function getUserQuota($userName, $serviceName=NULL){

	$userName=normalizeName($userName, ".");
	$serviceName=normalizeName($serviceName);


	if ($userName == NULL || $userName==""){
		$error->setHttpStatus(400);
		$error->setFunctionalCode(1);
		$error->setFunctionalLabel($error->getFunctionalLabel() . "userName is required\n");
		throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());
	}
	$error=new OSAError();
	$error->setHttpStatus(404);
	try{
		$db=openDBConnection();
		$bindPrms=array();
		$strSQL="SELECT * FROM usersquotas WHERE userName=?";// . cut($userName, USERNAME_LENGTH ) . "'";
		array_push($bindPrms, cut($userName, USERNAME_LENGTH ));
		if ($serviceName != NULL && $serviceName != ""){
			$strSQL .= " AND serviceName=?" ;
			array_push($bindPrms, cut($serviceName, SERVICENAME_LENGTH ));
			$stmt=$db->prepare($strSQL);
			$stmt->execute($bindPrms);
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			if ($row){
				$quota = new Quota($row);
				$rc = $quota->toArray();
			}else{
				$error->setHttpStatus(404);
				$error->setHttpLabel("Unknown quotas");
				$error->setFunctionalCode(4);
				$error->setFunctionalLabel("Quotas for user ". $userName . " and service " . $serviceName . " does not exists for user " . $userName);
				throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());
			}
			
			
		}else{
			$stmt=$db->prepare($strSQL);
			$stmt->execute($bindPrms);
			$rc = Array();
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				$quota = new quota($row);
				array_push($rc, $quota->toArray());
			}
		}
	}catch (Exception $e){
		if ($error->getHttpStatus() ==200){
			$error->setHttpStatus(500);
			$error->setFunctionalCode(3);
			$error->setFunctionalLabel($e->getMessage());
			throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());
		}else{
			throw $e;
		}
	}



	return $rc;
}



function addUserQuota($userName, $serviceName, $request_data=NULL){
	$userName=normalizeName($userName, ".");
	$serviceName=normalizeName($serviceName);
	


	$error = new OSAError();
	$error->setHttpStatus(200);
	$error->setHttpLabel("Bad request for method \"" . $_SERVER["REQUEST_METHOD"] . "\" for resource \"quota\"");


	$error->setFunctionalLabel("Bad request for method \"" . $_SERVER["REQUEST_METHOD"] . "\" for resource \"quota\"\n");
	$error->setFunctionalCode(0);

	if ($serviceName == NULL || $serviceName=="" ){
		$error->setHttpStatus(400);
		$error->setFunctionalCode(1);
		$error->setFunctionalLabel($error->getFunctionalLabel() . "serviceName is required\n");
	}
	if ($userName == NULL || $userName=="" ){
		$error->setHttpStatus(400);
		$error->setFunctionalCode(1);
		$error->setFunctionalLabel($error->getFunctionalLabel() . "userName is required\n");
	}
	if (!isset($request_data["reqSec"]) || $request_data["reqSec"]=="" ){
		$error->setHttpStatus(400);
		$error->setFunctionalCode(1);
		$error->setFunctionalLabel($error->getFunctionalLabel() . "reqSec is required\n");
	}
	if (!isset($request_data["reqDay"]) || $request_data["reqDay"]=="" ){
		$error->setHttpStatus(400);
		$error->setFunctionalCode(1);
		$error->setFunctionalLabel($error->getFunctionalLabel() . "reqDay is required\n");
	}
	if (!isset($request_data["reqMonth"]) || $request_data["reqMonth"]=="" ){
		$error->setHttpStatus(400);
		$error->setFunctionalCode(1);
		$error->setFunctionalLabel($error->getFunctionalLabel() . "reqMonth is required\n");
	}
	if ($error->getFunctionalCode()!=0){
		throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());
	}else{
		$mySQLServiceName=cut($serviceName, SERVICENAME_LENGTH);
		$mySQLuserName=cut($userName, USERNAME_LENGTH);
		$strSQL = "";
		$strSQL .="INSERT INTO usersquotas (";
		$strSQL .="	serviceName, ";
		$strSQL .="	userName,";
		$strSQL .="	reqSec,";
		$strSQL .="	reqDay,";
		$strSQL .="	reqMonth) ";
		$strSQL .="values (";
		$strSQL .="	?,";
		$strSQL .="	?,";
		$strSQL .= "?,";
		$strSQL .= "?,";
		$strSQL .= "?)";
		
		try{
			$db=openDBConnection();
			$stmt=$db->prepare($strSQL);
			$stmt->execute(array(cut($serviceName, SERVICENAME_LENGTH),
								 cut($userName, USERNAME_LENGTH),
								 $request_data["reqSec"],
								 $request_data["reqDay"],
								 $request_data["reqMonth"]));
		}catch (Exception $e){
				if (strpos($e->getMessage(),"Duplicate entry")>=0 ||strpos($e->getMessage(),"UNIQUE constraint failed")>=0 ){
					$error->setHttpStatus(409);
					$error->setFunctionalCode(5);
					$error->setFunctionalLabel("Quota for service " . $serviceName . " and user " . $userName . " already exists");
					
				}elseif (strpos(strtolower($e->getMessage()), "foreign key constraint fail")>=0){
					$error->setHttpStatus(404);
					$error->setFunctionalCode(6);
					$error->setFunctionalLabel("Service " . $serviceName . " or user " . $userName . " does not exists");
				}else{
					$error->setHttpStatus(500);
					$error->setFunctionalCode(3);
					$error->setFunctionalLabel($e->getMessage());
					
					
				}
				throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());
		}
		
		return getUserQuota($userName, $serviceName);
	}
}


function getUnsetQuota($userName){
	$userName=normalizeName($userName, ".");


	$error = new OSAError();
	$error->setHttpStatus(200);

	try{
		$db=openDBConnection();
		$strSQL="SELECT *, ? userName FROM services WHERE isUserQuotasEnabled=1 AND serviceName not in (SELECT serviceName FROM  usersquotas WHERE  userName=?)";
		$stmt=$db->prepare($strSQL);
		$stmt->execute(array(cut($userName, USERNAME_LENGTH), cut($userName, USERNAME_LENGTH)));
		$rc=Array();
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			$quota = new Quota($row);
			array_push($rc, $quota->toArray());
		}
	
	}catch (Exception $e){
			$error->setHttpStatus(500);
			$error->setFunctionalCode(3);
			$error->setFunctionalLabel($e->getMessage());
			throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());
	}

	return $rc;
}




function updateUserQuotas($userName, $serviceName, $request_data){
	$userName=normalizeName($userName, ".");
	$serviceName=normalizeName($serviceName);

	$error = new OSAError();
	$error->setHttpStatus(200);
	$error->setHttpLabel("Bad request for method \"" . $_SERVER["REQUEST_METHOD"] . "\" for resource \"quota\"");


	$error->setFunctionalLabel("Bad request for method \"" . $_SERVER["REQUEST_METHOD"] . "\" for resource \"quota\"\n");
	$error->setFunctionalCode(0);

	if ($serviceName == NULL || $serviceName=="" ){
		$error->setHttpStatus(400);
		$error->setFunctionalCode(1);
		$error->setFunctionalLabel($error->getFunctionalLabel() . "serviceName is required\n");
	}
	if ($userName==NULL || $userName=="" ){
		$error->setHttpStatus(400);
		$error->setFunctionalCode(1);
		$error->setFunctionalLabel($error->getFunctionalLabel() . "userName is required\n");
	}
	if (!isset($request_data["reqSec"]) || $request_data["reqSec"]=="" ){
		$error->setHttpStatus(400);
		$error->setFunctionalCode(1);
		$error->setFunctionalLabel($error->getFunctionalLabel() . "reqSec is required\n");
	}
	if (!isset($request_data["reqDay"]) || $request_data["reqDay"]=="" ){
		$error->setHttpStatus(400);
		$error->setFunctionalCode(1);
		$error->setFunctionalLabel($error->getFunctionalLabel() . "reqDay is required\n");
	}
	if (!isset($request_data["reqMonth"]) || $request_data["reqMonth"]=="" ){
		$error->setHttpStatus(400);
		$error->setFunctionalCode(1);
		$error->setFunctionalLabel($error->getFunctionalLabel() . "reqMonth is required\n");
	}
	if ($error->getFunctionalCode()!=0){
		throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());
	}else{
		$strSQL = "";
		$strSQL .="UPDATE usersquotas SET";
		$strSQL .="	reqSec=?,";
		$strSQL .="	reqDay=?,";
		$strSQL .="	reqMonth=? ";
		$strSQL .="WHERE ";
		$strSQL .="		serviceName=? ";
		$strSQL .="AND	userName=?";
		
		try{
			$db=openDBConnection();
			$stmt=$db->prepare($strSQL);
			$stmt->execute(array($request_data["reqSec"], 
								 $request_data["reqDay"], 
								 $request_data["reqMonth"],
								 cut($serviceName, SERVICENAME_LENGTH),
								 cut($userName, USERNAME_LENGTH)));
		}catch (Exception $e){
			if (strpos($e->getMessage(),"Duplicate entry")>=0 ||strpos($e->getMessage(),"UNIQUE constraint failed")>=0 ){
				$error->setHttpStatus(409);
				$error->setFunctionalCode(5);
				$error->setFunctionalLabel("Quota for service " . $serviceName . " and user " . $userName . " already exists");
				
			}elseif (strpos(strtolower($e->getMessage()), "foreign key constraint fail")>=0){
				$error->setHttpStatus(404);
				$error->setFunctionalCode(6);
				$error->setFunctionalLabel("Service " . $serviceName . " or user " . $userName . " does not exists");
			}else{
				$error->setHttpStatus(500);
				$error->setFunctionalCode(3);
				$error->setFunctionalLabel($e->getMessage());
			}
		}
		return getUserQuota($userName, $serviceName);
	}
}




function deleteUserQuotas($userName, $serviceName){
	$userName=normalizeName($userName, ".");
	$serviceName=normalizeName($serviceName);

	$error = new OSAError();
	$error->setHttpStatus(200);
	$error->setHttpLabel("Bad request for method \"" . $_SERVER["REQUEST_METHOD"] . "\" for resource \"quota\"");


	$error->setFunctionalLabel("Bad request for method \"" . $_SERVER["REQUEST_METHOD"] . "\" for resource \"quota\"\n");
	$error->setFunctionalCode(0);

	if ($serviceName==NULL || $serviceName=="" ){
		$error->setHttpStatus(400);
		$error->setFunctionalCode(1);
		$error->setFunctionalLabel($error->getFunctionalLabel() . "serviceName is required\n");
	}
	if ($userName==NULL || $userName=="" ){
		$error->setHttpStatus(400);
		$error->setFunctionalCode(1);
		$error->setFunctionalLabel($error->getFunctionalLabel() . "userName is required\n");
	}
	if ($error->getFunctionalCode()!=0){
		throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());
	}else{
		
		try{
			$db=openDBConnection();
			
			$strSQL = "SELECT * FROM  usersquotas WHERE serviceName=? AND userName=?"; 
			$stmt=$db->prepare($strSQL);
			$stmt->execute(array(cut($serviceName, SERVICENAME_LENGTH),
								 cut($userName, USERNAME_LENGTH)));
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			if (!$row){
				$error->setHttpStatus(404);
				$error->setHttpLabel("Unknown quotas");
				$error->setFunctionalCode(4);
				$error->setFunctionalLabel("Quotas for service ". $serviceName . " and user " . $userName . " does not exists");
				throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());
			}else{
				$quotas = new Quota($row);
				$strSQL = "DELETE FROM  usersquotas WHERE serviceName=? AND userName=?"; 
				$stmt=$db->prepare($strSQL);
				$stmt->execute(array(cut($serviceName, SERVICENAME_LENGTH),
									 cut($userName, USERNAME_LENGTH)));
			}

		}catch (Exception $e){
			if ($error->getHttpStatus() == 200){
				$error->setHttpStatus(500);
				$error->setFunctionalCode(3);
				$error->setFunctionalLabel($e->getMessage());
				throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());
			}else{
				throw $e;
			}
		}
		
		return $quotas->toArray();
	}
}
