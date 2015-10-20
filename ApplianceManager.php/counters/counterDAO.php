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
 * File Name   : ApplianceManager/ApplianceManager.php/counters/counterDAO.php
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
require_once '../objects/Counter.class.php';
require_once '../objects/ExcedeedCounter.class.php';
require_once '../include/Constants.php';
require_once '../include/Func.inc.php';
require_once '../include/Settings.ini.php';
require_once '../include/PDOFunc.php';


function getCounter($counterName= NULL, $request_data=NULL){
	GLOBAL $BDName;
	GLOBAL $BDUser;
	GLOBAL $BDPwd;
	$error = new Error();


	$rPart="R=";
	$uPart="%";
	$tPart="";
	$resourceLevel=false;
	$error->setHttpStatus(200);
	$bindPrms=array();

	if (isset($request_data["resourceName"])){
		$rPart = "R=" .$request_data["resourceName"] . "%";
	}else{
		$rPart = "R=%";
	}
	if (isset($request_data["userName"])){
		if ($request_data["userName"]=="*** Any ***"){
			$uPart = '$$$U=%'; 
		}else if ($request_data["userName"]==""){
			$resourceLevel=true;
			$uPart="";
		}else{
			$uPart = '$$$U=' . $request_data["userName"];
		}
	}
	if (isset($request_data["timeUnit"])){
		if ($request_data["timeUnit"]=="M" ||
			$request_data["timeUnit"]=="D" ||
			$request_data["timeUnit"]=="S"){
				$tPart = '$$$' . $request_data["timeUnit"] . "=%";
		}else{
				$error->setHttpStatus(400);
				$error->setFunctionalCode(1);
				$error->setFunctionalLabel($error->getFunctionalLabel() . " unsupported value " . $request_data["timeUnit"] . " for timeUnit");
		}
	}

	if ($error->getHttpStatus() != 200){
		throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());;
	}


	try{
		$db=openDB($BDName, $BDUser, $BDPwd);
		if ($counterName != NULL ){
			if ($counterName != ""){
				$strSQL = "SELECT * FROM counters WHERE counterName =?";
				$stmt=$db->prepare($strSQL);
				$stmt->execute(array($counterName));
				
				$row = $stmt->fetch(PDO::FETCH_ASSOC);
				if ($row){
			
					$counter=new Counter($row);
					$rc = $counter->toArray();
				}else{
					$error->setHttpStatus(404);
					$error->setFunctionalCode(1);
					$error->setFunctionalLabel("counterName " . $counterName . " does not exists");
					throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());;
				}
			}else{
				$error->setHttpStatus(400);
				$error->setFunctionalCode(1);
				$error->setFunctionalLabel($error->getFunctionalLabel() . "counterName is required\n");
				throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());;
			}
		}else{
				
			
			$strSQL = "SELECT * FROM counters WHERE counterName like ?";
			if ( $resourceLevel ){
				$strSQL=$strSQL . " AND counterName not like 'R=%U=%'";
	 		}
			$stmt=$db->prepare($strSQL);
			$stmt->execute(array( $rPart . $uPart . $tPart . "%"));
			
			
			$rc=Array();
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				$counter=new Counter($row);
				array_push($rc,$counter->toArray());
				
			}
		}
	}catch (Exception $e){
		if ($error->getHttpStatus() == 200){
			$error->setHttpStatus(500);
			$error->setFunctionalCode(3);
			$error->setFunctionalLabel($e->getMessage());
		}
		throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());
	}
	return $rc;
}



function deleteCounter($counterName){
	GLOBAL $BDName;
	GLOBAL $BDUser;
	GLOBAL $BDPwd;


	$error = new Error();
	$error->setHttpStatus(200);

	if ($counterName != NULL && $counterName != ""){

		try {
			$db=openDB($BDName, $BDUser, $BDPwd);
			$strSQL = "SELECT * FROM counters WHERE counterName=?";
			
			$stmt=$db->prepare($strSQL);
			$stmt->execute(array($counterName));
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			if (!$row){
				$error->setHttpStatus(404);
				$error->setHttpLabel("Unknown counter");
				$error->setFunctionalCode(4);
				$error->setFunctionalLabel("Counter ". $counterName. " does not exists");
				throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());;
			}else{
				$counter = new Counter($row);
				$strSQL="DELETE FROM counters WHERE  counterName=?";
				$stmt=$db->prepare($strSQL);
				$stmt->execute(array($counterName));

				
				
			}
		}catch (Exception $e){
			if($error->getHttpStatus() != 200){
				if (strpos($e->getMessage(),"a foreign key constraint fails")){
					$error->setFunctionalLabel("The counter " . $counterName. " is used by some services. Please remove subscribtions and services referencing it first");
					$error->setHttpStatus(400);
				}else{
					$error->setHttpStatus(500);
					$error->setFunctionalCode(3);
					$error->setFunctionalLabel($e->getMessage());
				}
			}
			throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());;
		}
	}else{
			$error->setHttpLabel("Bad request for method \"" . $_SERVER["REQUEST_METHOD"] . "\" for resource \"counter\"");
			$error->setHttpStatus(400);
			$error->setFunctionalCode(1);
			$error->setFunctionalLabel($error->getFunctionalLabel() . "counterName is required\n");
			throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());;
	}

	return $counter->toArray();
}






function updateCounter($counterName, $request_data){
	GLOBAL $BDName;
	GLOBAL $BDUser;
	GLOBAL $BDPwd;
	$error = new Error();
	$error->setHttpStatus(200);
	
	if (!isset($request_data["value"]) || $request_data["value"]==""){
		$error->setHttpLabel("parameter \"value\" is required");
		$error->setHttpStatus(400);
		$error->setFunctionalCode(1);
		$error->setFunctionalLabel($error->getFunctionalLabel() . "\"value\" is required\n");
		throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());
	}

	if ($counterName == NULL || $counterName==""){
		$error->setHttpLabel("parameter \"counterName\" is required");
		$error->setHttpStatus(400);
		$error->setFunctionalCode(1);
		$error->setFunctionalLabel($error->getFunctionalLabel() . "counterName is required\n");
		throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());
	}
	
	$counter=getCounter($counterName);
	try {
		$db=openDB($BDName, $BDUser, $BDPwd);
		$strSQL="UPDATE counters SET value=? WHERE  counterName=?";
		
		$stmt=$db->prepare($strSQL);
		$stmt->execute(array($request_data["value"] , $counterName));
		$counter=getCounter($counterName);
	}catch (Exception $e){
			$error->setHttpStatus(500);
			$error->setFunctionalCode(3);
			$error->setFunctionalLabel($e->getMessage());
			throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());
	}


	return $counter;
}




function getExceededCounter($request_data){
	GLOBAL $BDName;
	GLOBAL $BDUser;
	GLOBAL $BDPwd;
	$error = new Error();


	$error->setHttpStatus(200);



	try {
		$db=openDB($BDName, $BDUser, $BDPwd);
		$strSQL = "";
		$strSQL = $strSQL . 'Select * from (';
		$strSQL = $strSQL . '	SELECT c.* , s.serviceName, NULL userName, s.reqSec, s.reqDay, s.reqMonth';
		$strSQL = $strSQL . '	FROM counters c,';
		$strSQL = $strSQL . '		 services s';
		$strSQL = $strSQL . '	WHERE   (counterName like concat(\'R=\',s.serviceName,\'$$$S=%\') and c.value>=s.reqSec)';
		$strSQL = $strSQL . '	OR      (counterName like concat(\'R=\',s.serviceName,\'$$$D=%\') and c.value>=s.reqDay)';
		$strSQL = $strSQL . '	OR      (counterName like concat(\'R=\',s.serviceName,\'$$$M=%\') and c.value>=s.reqMonth)';
		$strSQL = $strSQL . '	UNION';
		$strSQL = $strSQL . '	SELECT c2.* , uq.serviceName, uq.userName, uq.reqSec, uq.reqDay, uq.reqMonth';
		$strSQL = $strSQL . '	FROM counters c2,';
		$strSQL = $strSQL . '		 usersquotas uq';
		$strSQL = $strSQL . '	WHERE   (counterName like concat(\'R=\',uq.serviceName,\'$$$U=\', uq.userName, \'$$$S=%\') and c2.value>=uq.reqSec) ';
		$strSQL = $strSQL . '	OR      (counterName like concat(\'R=\',uq.serviceName, \'$$$U=\', uq.userName, \'$$$D=%\') and c2.value>=uq.reqDay)';
		$strSQL = $strSQL . '	OR      (counterName like concat(\'R=\',uq.serviceName,\'$$$U=\', uq.userName, \'$$$M=%\') and c2.value>=uq.reqMonth)';
		$strSQL = $strSQL . ') excedeed ';
		$strSQL = $strSQL . 'order by serviceName, userName';

		$stmt=$db->prepare($strSQL);
		$stmt->execute(array());
		
		$rc=Array();
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			$counter = new ExcedeedCounter($row);
			$pushIt=true;
			if (isset($request_data["resourceNameFilter"]) && 
				$request_data["resourceNameFilter"] != ""  && 
				strpos(strtoupper($counter->getResourceName()), strtoupper($request_data["resourceNameFilter"]))===false){
				$pushIt=false;
			}
			if (isset($request_data["userNameFilter"]) && 
				$request_data["userNameFilter"] != ""  && 
				strpos(strtoupper($counter->getUserName()), strtoupper($request_data["userNameFilter"]))===false){
				$pushIt=false;
			}
			if ($pushIt){
				array_push($rc, $counter->toArray());
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
