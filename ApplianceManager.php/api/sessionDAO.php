<?php
/**
 *  Reverse Proxy as a service
 * 
 * PHP Version 7.0
 * 
 * @category ReverseProxy
 * @package  OSA
 * @author   Benoit HERARD <benoit.herard@orange.com>
 * @license  http://www.apache.org/licenses/LICENSE-2.0.htm Apache 2 license
 * @link     https://github.com/zorglub42/OSA/
*/
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
 *      Manage database access for session object
 *--------------------------------------------------------
 * History     :
 * 1.0.0 - 2018-07-12 : Release of the file
*/
require_once '../objects/Error.class.php';
require_once '../objects/Session.class.php';
require_once '../include/Constants.php';
require_once '../include/Func.inc.php';
require_once '../include/Settings.ini.php';
require_once '../include/PDOFunc.php';

/**
 * Return sessions
 * 
 * Return active sessions
 * 
 * @param string $userName Retreive only active sessions for this user (optional)
 * 
 * @return array Active sessions {@type Session}
 */
function getActiveSessions($userName=null) {
    @include '../include/Settings.ini.php';

    $rc = Array();
	$error = new OSAError();
    $db=openDBConnection();
    $prms=Array();
    $strSQL = "SELECT initialToken, validUntil, userName FROM authtoken WHERE burned=0 AND validUntil>=" . getSQLKeyword("now");
    if (!empty($userName)) {
        array_push($prms, $userName);
        $strSQL = $strSQL . " AND userName=?";
    }
    try{
		$stmt = $db->prepare($strSQL);
        $stmt->execute($prms);
        $rc = Array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $s = new Session($row);
            array_push ($rc, $s->toArray());
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
function getSessionById($id)  {
    @include '../include/Settings.ini.php';
	$error = new OSAError();
    $db=openDBConnection();

    $stmt = $db->prepare("SELECT * FROM authtoken WHERE initialToken=?");
    try{
        $stmt->execute(array($id));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row){
            $s = new Session($row);
            $rc= $s->toArray();
            return $rc;
        }else{
            $error->setHttpStatus(404);
            $error->setHttpLabel("Session user");
            $error->setFunctionalCode(4);
            $error->setFunctionalLabel("Session ". $id . " does not exists");
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

}

function closeSessionById($id) {
    @include '../include/Settings.ini.php';
	$error = new OSAError();

    $rc = getSessionById($id);

    $db=openDBConnection();
    $stmt = $db->prepare("DELETE FROM authtoken WHERE initialToken=?");
    try{
        $stmt->execute(array($id));
    }catch (Exception $e){
        $error->setHttpStatus(500);
        $error->setFunctionalCode(3);
        $error->setFunctionalLabel($e->getMessage());
        throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());
    }
    return $rc;

}
