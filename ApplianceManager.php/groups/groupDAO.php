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
 * File Name   : ApplianceManager/ApplianceManager.php/groups/groupDAO.php
 *
 * Created     : 2013-11
 * Authors     : Benoit HERARD <benoit.herard(at)orange.com>
 * 				 zorglub42 <contact@zorglub42.fr>
 *
 * Description :
 *      Manage database access for group object
 *--------------------------------------------------------
 * History     :
 * 1.0.0 - 2013-11-12 : Release of the file
 * 2.0.0 - 2015-08-02 : update to PDO
*/
require_once '../objects/Error.class.php';
require_once '../objects/Group.class.php';
require_once '../objects/User.class.php';
require_once '../include/Constants.php';
require_once '../include/Func.inc.php';
require_once '../include/Settings.ini.php';
require_once '../include/PDOFunc.php';




function getGroup($groupName = NULL, $request_data = NULL){
	GLOBAL $BDName;
	GLOBAL $BDUser;
	GLOBAL $BDPwd;


	$groupName=normalizeName($groupName);


	$error = new Error();
	$error->setHttpStatus(200);
	
	try {
		$db=openDB($BDName, $BDUser, $BDPwd);

		if ($groupName != NULL){
			$strSQL = "SELECT * FROM groups WHERE groupName=?";
			//'" .  . "'";
			$stmt=$db->prepare($strSQL);
			$stmt->execute(array(cut($groupName, GROUPNAME_LENGTH)));
			
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			
			if (!$row){
				$error->setHttpStatus(404);
				$error->setHttpLabel("Unknown group");
				$error->setFunctionalCode(4);
				$error->setFunctionalLabel("Group ". $groupName . " does not exists");
				throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());;
			}else{
				$group = new Group($row);
				$rc = $group->toArray();
				
			}
		}else{
			$strSQLComp="";
			$bindPrms=array();
			if (isset($request_data["groupDescritpionFilter"]) && $request_data["groupDescritpionFilter"]!=""){
				$strSQLComp = addSQLFilter("description like ?", $strSQLComp);
				array_push($bindPrms,"%" . $request_data["groupDescritpionFilter"] . "%");
			}
			if (isset($request_data["groupNameFilter"]) && $request_data["groupNameFilter"]!=""){
				$strSQLComp = addSQLFilter("groupName like ?", $strSQLComp);
				array_push($bindPrms,"%" . $request_data["groupNameFilter"] . "%");
			}
			//if (isset($request_data["filter"])){
			//	$strSQLComp=addSQLFilter($request_data["filter"],$strSQLComp);
			//}
			
			$strSQL="SELECT * FROM groups" . $strSQLComp;
			if (isset($request_data["order"]) && $request_data["order"] != ""){
				$strSQL=$strSQL . " ORDER BY " . EscapeOrder($request_data["order"]);
			}

			
			$stmt=$db->prepare($strSQL);
			$stmt->execute($bindPrms);
			$rc=Array();
			while ($row=$stmt->fetch(PDO::FETCH_ASSOC)){
				$group = new Group($row);
				array_push($rc,$group->toArray());
			}
		}
	}catch (Exception $e){
		if ($error->getHttpStatus() ==200){
			$error->setHttpStatus(500);
			$error->setFunctionalCode(3);
			$error->setFunctionalLabel($e->getMessage());
		}
		throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());
		
	}

return $rc;
}
function getGroupMembers($groupName, $request_data = NULL){
	GLOBAL $BDName;
	GLOBAL $BDUser;
	GLOBAL $BDPwd;
	
	
	$groupName=normalizeName($groupName);
	
	
	
	$error = new Error();
	$error->setHttpStatus(200);
	
	try{
		$db=openDB($BDName, $BDUser, $BDPwd);
		$bindPrms=array();
		if ($groupName != VALID_USER_GROUP){
			$strSQL="SELECT u.* FROM users u, usersgroups ug WHERE (ug.userName = u.userName AND ug.groupName=?) ";
			array_push($bindPrms, cut($groupName, GROUPNAME_LENGTH));
		}else{
			$strSQL="SELECT u.* FROM users u WHERE (endDate is NULL or endDate < now()) ";
		}
		if (isset($request_data["withLog"])){
			$strSQL=$strSQL . " AND exists(SELECT 'x' FROM hits h WHERE h.userName=u.userName)";
		}
		
		if (isset($request_data["userNameFilter"]) && $request_data["userNameFilter"]!=""){
			$strSQL = addSQLFilter("u.userName like ?", $strSQL);
			array_push($bindPrms, "%" . $request_data["userNameFilter"] . "%");
		}
		if (isset($request_data["firstNameFilter"]) && $request_data["firstNameFilter"]!=""){
			$strSQL = addSQLFilter("u.firstName like ?", $strSQL);
			array_push($bindPrms, "%" . $request_data["firstNameFilter"] . "%");
		}
		if (isset($request_data["lastNameFilter"]) && $request_data["lastNameFilter"]!=""){
			$strSQL = addSQLFilter("u.lastName like ?", $strSQL);
			array_push($bindPrms, "%" . $request_data["lastNameFilter"] . "%");
		}
		if (isset($request_data["emailAddressFilter"]) && $request_data["emailAddressFilter"]!=""){
			$strSQL = addSQLFilter("u.emailAddress like ?", $strSQL);
			array_push($bindPrms, "%" . $request_data["emailAddressFilter"] . "%");
		}
		if (isset($request_data["entityFilter"]) && $request_data["entityFilter"]!=""){
			$strSQL = addSQLFilter("u.entity like ?", $strSQL);
			array_push($bindPrms, "%" . $request_data["entityFilter"] . "%");
		}
		if (isset($request_data["extraFilter"]) && $request_data["extraFilter"]!=""){
			$strSQL = addSQLFilter("u.extra like ?", $strSQL);
			array_push($bindPrms, "%" . $request_data["extraFilter"] . "%");
		}
		if (isset($request_data["order"]) && $request_data["order"] != ""){
			$strSQL=$strSQL . " ORDER BY " . EscapeOrder($request_data["order"]);
		}
		$stmt=$db->prepare($strSQL);
		$stmt->execute($bindPrms);
		$rc=Array();
		while ($row=$stmt->fetch(PDO::FETCH_ASSOC)){
			$user = new User($row);
			array_push($rc,$user->toArray());
		}
		
	}catch (Exception $e){
			$error->setHttpStatus(500);
			$error->setFunctionalCode(3);
			$error->setFunctionalLabel($ce->getMessage());
			throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());
		
	}

	return $rc;
}



function addGroup($groupName = NULL, $description = NULL){
	GLOBAL $BDName;
	GLOBAL $BDUser;
	GLOBAL $BDPwd;


	$groupName=normalizeName($groupName);



	$error = new Error();
	$error->setHttpStatus(200);
	$error->setHttpLabel("Bad request for method \"" . $_SERVER["REQUEST_METHOD"] . "\" for resource \"group\"");


	$error->setFunctionalLabel("Bad request for method \"" . $_SERVER["REQUEST_METHOD"] . "\" for resource \"group\"\n");
	$error->setFunctionalCode(0);

	if ($groupName==NULL){
		$error->setHttpStatus(400);
		$error->setFunctionalCode(1);
		$error->setFunctionalLabel($error->getFunctionalLabel() . "groupName is required\n");
		throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());;
	}else{
		$groupName = normalizeName( $groupName);
	}


	
	try {
		$db=openDB($BDName, $BDUser, $BDPwd );
		
		$strSQL = "INSERT INTO groups (groupName, description) values (?,?)";
		$stmt=$db->prepare($strSQL);
		$stmt->execute(array(cut($groupName, GROUPNAME_LENGTH), cut($description, DESCRIPTION_LENGTH)));
	}catch (Exception $e){
		if (strpos($e->getMessage(),"Duplicate entry")){
			$error->setHttpStatus(500);
			$error->setHttpStatus(409);
			$error->setFunctionalCode(5);
			$error->setFunctionalLabel("Group " . $groupName . " already exists");
		}else{
			$error->setHttpStatus(500);
			$error->setFunctionalCode(3);
			$error->setFunctionalLabel($e->getMessage());
		}
		throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());
	}
	
	
	return getGroup($groupName);
}





function deleteGroup($groupName){
GLOBAL $BDName;
GLOBAL $BDUser;
GLOBAL $BDPwd;

$groupName=normalizeName($groupName);


$error = new Error();
$error->setHttpStatus(200);


if ($groupName== NULL || $groupName == ""){
	$error->setHttpLabel("Bad request for method \"" . $_SERVER["REQUEST_METHOD"] . "\" for resource \"group\"");
	$error->setHttpStatus(400);
	$error->setFunctionalCode(1);
	$error->setFunctionalLabel($error->getFunctionalLabel() . "groupName is required\n");
	throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());;
}else if ($groupName==ADMIN_GROUP || $groupName == VALID_USER_GROUP){
	$error->setHttpStatus(403);
	$error->setFunctionalCode(3);
	$error->setFunctionalLabel(ADMIN_GROUP . " group can't be suppressed");
	throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());
}


$rc=getGroup($groupName);

try{
	$db=openDB($BDName, $BDUser, $BDPwd);

	$strSQL="DELETE FROM groups WHERE  groupName=?";
	$stmt=$db->prepare($strSQL);
	$stmt->execute(array(cut($groupName, GROUPNAME_LENGTH)));
	

	$strSQL="DELETE FROM counters WHERE  counterName like ?";
	$stmt=$db->prepare($strSQL);
	$stmt->execute(array("%U=" . cut($groupName, GROUPNAME_LENGTH) . "%"));

}catch(Exception $e){
	if (strpos($e->getMessage(),"a foreign key constraint fails")){
		$error->setFunctionalLabel("The group " . $groupName . " is used by some services. Please remove subscribtions/user's quotas and services referencing it first");
		$error->setHttpStatus(403);
	}else{
		$error->setHttpStatus(500);
		$error->setFunctionalLabel($e->getMessage());
	}
	$error->setFunctionalCode(3);
	throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());;
}


return $rc;
}





function updateGroup($groupName = NULL, $description = NULL){
GLOBAL $BDName;
GLOBAL $BDUser;
GLOBAL $BDPwd;

	$groupName=normalizeName($groupName);


	$error = new Error();
	$error->setHttpStatus(200);

	if ($groupName == NULL || $groupName=="" ){
		$error->setHttpStatus(400);
		$error->setFunctionalCode(1);
		$error->setFunctionalLabel($error->getFunctionalLabel() . "groupName is required\n");
		throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());;
	}else{
		$group=getGroup($groupName);
		try{
			$db=openDB($BDName, $BDUser, $BDPwd );

			$strSQL = "";
			$strSQL = $strSQL  . "UPDATE groups SET ";
			$strSQL = $strSQL  . "      description=? ";
			$strSQL = $strSQL  . "WHERE groupName=?";
			
			$stmt=$db->prepare($strSQL);
			$stmt->execute(array(cut($description, DESCRIPTION_LENGTH), cut($groupName, GROUPNAME_LENGTH)));
		}catch (Exception $e){
			$error->setHttpStatus(500);
			$error->setFunctionalCode(3);
			$error->setFunctionalLabel($e->getMessage());
			throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());;
		}
		
		return getGroup($groupName);
	}
}	
