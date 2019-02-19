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
 * Version : 2.0
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
 * 				 zorglub42 <conntact(at)zorglub42.fr>
 *
 * Description :
 *      Manage database access for group object
 *--------------------------------------------------------
 * History     :
 * 1.0.0 - 2013-11-12 : Release of the file
 * 2.0.0 - 2015-08-02 : Update to PDO
*/
require_once '../objects/Error.class.php';
require_once '../objects/Service.class.php';
require_once '../objects/Node.class.php';
require_once '../objects/Quota.class.php';
require_once '../objects/User.class.php';
require_once '../objects/HeaderMapping.class.php';
require_once '../include/PDOFunc.php';
require_once '../include/Func.inc.php';

/**
 * Get Headers mapping for a service from database
 * 
 * @param string $serviceName  Concercned service Identifier
 * @param string $userProperty Filter on this property only
 * 
 * @return array Matching properties
 */
function getServiceHeadersMapping($serviceName , $userProperty=null)
{
    @include '../include/Constants.php';
    @include '../include/Settings.ini.php';

    $serviceName=normalizeName($serviceName);

    $error = new OSAError();
    $error->setHttpStatus(200);

    try{
        $qryPrms=array("serviceName" => $serviceName);
        $strSQL="SELECT * FROM headersmapping h WHERE serviceName=:serviceName";
        if (! empty($userProperty)) {
            /*if (! in_array($userProperty, $userProperties)) {
                $error->setFunctionalCode(4);
                $error->setHttpStatus(400);
                $error->setFunctionalLabel(
                    "User property " . $userProperty . " can not be mapped"
                );
            }*/
            $strSQL = $strSQL . " AND columnName=:userProperty";
            $qryPrms["userProperty"]=$userProperty;
        }
        if ($error->getHttpStatus() == 200) {
            $db=openDBConnection();
            $stmt=$db->prepare($strSQL);
            $stmt->execute($qryPrms);
            $rc =  Array();
            while ($row=$stmt->fetch(PDO::FETCH_ASSOC)) {
                $mapping = new HeaderMapping($row);
                array_push($rc, $mapping->toArray());
            }
            if (count($rc)==0) {
                if (empty($userProperty)) {
                    foreach ($userProperties as $property) {
                        $row["serviceName"]=$serviceName;
                        $row["columnName"]=$property;
                        $row["headerName"]=$defaultHeadersName[$property];
                        $row["extendedAttribute"]=0;

                        $mapping = new HeaderMapping($row);
                        array_push($rc, $mapping->toArray());

                    }
                } else {
                    $error->setFunctionalCode(4);
                    $error->setHttpStatus(404);
                    $error->setFunctionalLabel(
                        "No headers mapping defined for service " .
                        $serviceName .
                        " and user property " .
                        $userProperty
                    );
                }
            }
        }
    }catch (Exception $e) {
        if ($error->getHttpStatus() ==200) {
            $error->setHttpStatus(500);
            $error->setFunctionalLabel($e->getMessage());
        }
        $error->setFunctionalCode(3);
        throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());;

    }
    if ($error->getHttpStatus() == 200) {
        return $rc;
    } else {
        throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());;
    }
}

/**
 * Insert a header mapping for a service into database
 * 
 * @param string $serviceName       Service identifier
 * @param string $userProperty      User property name
 * @param string $headerName        Associated header name
 * @param string $extendedAttribute Header is an extended user attribute (default 0)
 * 
 * @return array Created mapping
 */
function createServiceHeadersMapping(
    $serviceName , $userProperty, $headerName,$extendedAttribute=0
) {
    @include '../include/Constants.php';
    @include '../include/Settings.ini.php';

    $serviceName=normalizeName($serviceName);

    $error = new OSAError();
    $error->setHttpStatus(200);

    /*if (! in_array($userProperty, $userProperties)) {
        $error->setFunctionalCode(4);
        $error->setHttpStatus(400);
        $error->setFunctionalLabel(
            "User property " . $userProperty . " can not be mapped"
        );
        throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());;
    } else*/
    if (empty($headerName)) {
        $error->setFunctionalCode(4);
        $error->setHttpStatus(400);
        $error->setFunctionalLabel("headerName is required");
        throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());;
    } else {
        try{
            $qryPrms=array(
                "serviceName" => $serviceName,
                "userProperty"=>$userProperty,
                "headerName"=>$headerName,
                "extendedAttribute"=>$extendedAttribute,
            );

            $strSQL="INSERT INTO headersmapping ".
                    "   (serviceName, columnName, headerName, extendedAttribute) ".
                    "values ".
                    "   (:serviceName, :userProperty, :headerName, :extendedAttribute)";
            $db=openDBConnection();
            $stmt=$db->prepare($strSQL);
            $stmt->execute($qryPrms);
        }catch (Exception $e) {
            echo $e->getMessage();
            if (strpos($e->getMessage(), "Duplicate entry")>=0
                || strpos($e->getMessage(), "UNIQUE constraint failed")>=0
            ) {
                $error->setHttpStatus(409);
                $error->setFunctionalCode(5);
                $error->setFunctionalLabel(
                    "Header mapping for user property " .
                    $userProperty .
                    " and Service " .
                    $serviceName .
                    " already exists "
                );

            } elseif ($error->getHttpStatus() ==200) {
                $error->setFunctionalCode(3);
                $error->setHttpStatus(500);
                $error->setFunctionalLabel($e->getMessage());
            }
            throw new Exception(
                $error->GetFunctionalLabel(), $error->getHttpStatus()
            );

        }
        $rc =  getServiceHeadersMapping($serviceName, $userProperty);
        return $rc;
    }
}


/** 
 * Delete a header mapping for a service from the database
 * 
 * @param string $serviceName Service ID
 * 
 * @return void
 */
function deleteServiceHeadersMapping($serviceName)
{

    $serviceName=normalizeName($serviceName);

    $error = new OSAError();
    $error->setHttpStatus(200);

    try{
        $qryPrms=array("serviceName" => $serviceName);
        $strSQL="DELETE FROM headersmapping WHERE serviceName=:serviceName";

        $db=openDBConnection();
        $stmt=$db->prepare($strSQL);
        $stmt->execute($qryPrms);
    }catch (Exception $e) {
        if ($error->getHttpStatus() ==200) {
            $error->setHttpStatus(500);
            $error->setFunctionalLabel($e->getMessage());
        }
        $error->setFunctionalCode(3);
        throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());;

    }
}


/**
 * Get one or many services from the database
 * 
 * @param string $serviceName  [optional] Service ID (if set, get this service)
 * @param string $request_data [optional] Filter on services properties
 * 
 * @return array matching services
 */
function getService($serviceName=null, $request_data=null)
{
    $serviceName=normalizeName($serviceName);

    $error = new OSAError();
    $error->setHttpStatus(200);

    try{
        $db=openDBConnection();
        if ($serviceName != null && $serviceName != "") {
            $strSQL = "SELECT * FROM services WHERE serviceName=?";
            $stmt=$db->prepare($strSQL);
            $stmt->execute(array(cut($serviceName, SERVICENAME_LENGTH)));

            if ($row=$stmt->fetch(PDO::FETCH_ASSOC)) {
                $service = new Service($row);
                $rc=$service->toArray();
            } else {
                $error->setHttpStatus(404);
                $error->setHttpLabel("Unknown service");
                $error->setFunctionalCode(4);
                $error->setFunctionalLabel(
                    "Service ". $serviceName . " does not exists"
                );
                throw new Exception(
                    $error->GetFunctionalLabel(), $error->getHttpStatus()
                );
            }
        } else {
            $strSQLComp="";
            $bindPrms=array();
            if (isset($request_data["withLog"]) && $request_data["withLog"]==1) {
                $strSQLComp=" WHERE exists (SELECT 'x' ".
                                           "FROM hits h ".
                                           "WHERE h.serviceName=s.serviceName)";
            } elseif (isset($request_data["withLog"]) 
                && $request_data["withLog"]==0
            ) {
                $strSQLComp=" WHERE not exists (SELECT 'x' ".
                                               "FROM hits h ".
                                               "WHERE h.serviceName=s.serviceName)";
            }
            if (isset($request_data["serviceNameFilter"]) 
                && $request_data["serviceNameFilter"]
            ) {
                $strSQLComp = addSQLFilter("serviceName like ?", $strSQLComp);
                array_push(
                    $bindPrms,
                    "%" . 
                    cut(
                        $request_data["serviceNameFilter"],
                        SERVICENAME_LENGTH
                    ) . 
                    "%"
                );
            }
            if (isset($request_data["nodeNameFilter"]) 
                && !empty($request_data["nodeNameFilter"])
            ) {
                $strSQLComp=addSQLFilter(
                    "(onAllNodes=1 " .
                    " or ".
                    "exists (SELECT 'x' ".
                            "FROM servicesnodes sn ".
                            "WHERE sn.serviceName=s.serviceName ".
                            "AND sn.nodeName = ?))",
                    $strSQLComp
                );
                array_push(
                    $bindPrms,
                    cut($request_data["nodeNameFilter"], NODENAME_LENGTH)
                );
            }
            if (isset($request_data["groupNameFilter"]) 
                && $request_data["groupNameFilter"]!==""
            ) {
                $strSQLComp = addSQLFilter("groupName like ?", $strSQLComp);
                array_push(
                    $bindPrms,
                    "%" .
                    cut(
                        $request_data["groupNameFilter"],
                        GROUPNAME_LENGTH
                    ) .
                    "%"
                );
            }
            if (isset($request_data["withQuotas"]) 
                && $request_data["withQuotas"]=1
            ) {
                $strSQLComp=addSQLFilter(
                    "(isGlobalQuotasEnabled=1 or isUserQuotasenabled=1)",
                    $strSQLComp
                );
            } elseif (isset($request_data["withQuotas"]) 
                && $request_data["withQuotas"]=0
            ) {
                $strSQLComp=addSQLFilter(
                    "(isGlobalQuotasEnabled=0 AND isUserQuotasenabled=0)",
                    $strSQLComp
                );
            }
            if (isset($request_data["isIdentityForwardingEnabledFilter"]) 
                && $request_data["isIdentityForwardingEnabledFilter"]!==""
            ) {
                $strSQLComp = addSQLFilter(
                    "isIdentityForwardingEnabled =?",
                    $strSQLComp
                );
                array_push(
                    $bindPrms,
                    $request_data["isIdentityForwardingEnabledFilter"]
                );
            }
            if (isset($request_data["isGlobalQuotasEnabledFilter"])
                && $request_data["isGlobalQuotasEnabledFilter"]!==""
            ) {
                $strSQLComp = addSQLFilter(
                    "isGlobalQuotasEnabled =?",
                    $strSQLComp
                );
                array_push(
                    $bindPrms,
                    $request_data["isGlobalQuotasEnabledEnabledFilter"]
                );
            }
            if (isset($request_data["isUserQuotasEnabledFilter"]) 
                && $request_data["isUserQuotasEnabledFilter"]!==""
            ) {
                $strSQLComp = addSQLFilter(
                    "isUserQuotasEnabled =?", $strSQLComp
                );
                array_push(
                    $bindPrms,
                    $request_data["isUserQuotasEnabledFilter"]
                );
            }
            if (isset($request_data["isPublishedFilter"]) 
                && $request_data["isPublishedFilter"]!==""
            ) {
                $strSQLComp = addSQLFilter("isPublished =?", $strSQLComp);
                array_push($bindPrms, $request_data["isPublishedFilter"]);
            }
            if (isset($request_data["isHitLoggingEnabledFilter"]) 
                && $request_data["isHitLoggingEnabledFilter"]!==""
            ) {
                $strSQLComp = addSQLFilter("isHitLoggingEnabled =?", $strSQLComp);
                array_push($bindPrms, $request_data["isHitLoggingEnabledFilter"]);
            }
            if (isset($request_data["isUserAuthenticationEnabledFilter"]) 
                && $request_data["isUserAuthenticationEnabledFilter"]!==""
            ) {
                $strSQLComp = addSQLFilter(
                    "isUserAuthenticationEnabled =?",
                    $strSQLComp
                );
                array_push(
                    $bindPrms,
                    $request_data["isUserAuthenticationEnabledFilter"]
                );
            }
            if (isset($request_data["frontEndEndPointFilter"]) 
                && $request_data["frontEndEndPointFilter"]!==""
            ) {
                $strSQLComp = addSQLFilter("frontEndEndPoint like ?", $strSQLComp);
                array_push(
                    $bindPrms,
                    "%" .
                    cut(
                        $request_data["frontEndEndPointFilter"],
                        FRONTENDENDPOINT_LENGTH
                    ) .
                    "%"
                );
            }
            if (isset($request_data["backEndEndPointFilter"]) 
                && $request_data["backEndEndPointFilter"]!==""
            ) {
                $strSQLComp = addSQLFilter("backEndEndPoint like ?", $strSQLComp);
                array_push(
                    $bindPrms,
                    "%" . 
                    cut(
                        $request_data["backEndEndPointFilter"],
                        BACKENDENDPOINT_LENGTH
                    ) .
                    "%"
                );
            }
            if (isset($request_data["additionalConfigurationFilter"]) 
                && $request_data["additionalConfigurationFilter"]!==""
            ) {
                $strSQLComp = addSQLFilter(
                    "additionalConfiguration like ?",
                    $strSQLComp
                );
                array_push(
                    $bindPrms,
                    "%" . $request_data["additionalConfigurationFilter"] . "%"
                );
            }

            $strSQL="SELECT * FROM services s" . $strSQLComp;
            if (isset($request_data["order"]) && $request_data["order"] != "") {
                $strSQL=$strSQL . " ORDER BY " . escapeOrder($request_data["order"]);
            }
            $stmt=$db->prepare($strSQL);
            $stmt->execute($bindPrms);
            $rc =  Array();
            while ($row=$stmt->fetch(PDO::FETCH_ASSOC)) {
                $service = new Service($row);
                array_push($rc, $service->toArray());
            }
        }
    }catch (Exception $e) {
        if ($error->getHttpStatus() ==200) {
            $error->setHttpStatus(500);
            $error->setFunctionalLabel($e->getMessage());
        }
        $error->setFunctionalCode(3);
        throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());;

    }
    return $rc;

}





/**
 * Create a service
 * 
 * @param string $serviceName  Service identifier
 * @param string $request_data Service properties
 *                             $request_data["additionalConfiguration"]
 *                             $request_data["isAnonymousAllowed"]
 *                             $request_data["isHitLoggingEnabled"]
 *                             $request_data["onAllNodes"]
 *                             $request_data["isUserAuthenticationEnabled"]
 *                             $request_data["isPublished"]
 *                             $request_data["isGlobalQuotasEnabled"]
 *                             $request_data["reqSec"]
 *                             $request_data["reqDay"]
 *                             $request_data["reqMonth"]
 *                             $request_data["isIdentityForwardingEnabled"]
 *                             $request_data["backEndUsername"]
 *                             $request_data["backEndPassword"]
 *                             $request_data["isUserQuotasEnabled"]
 *                             $request_data["frontEndEndPoint"]
 *                             $request_data["backEndEndPoint"]
 *                             $request_data["loginFormUri"]
 *                             $request_data["groupName"]
 * 
 * @return array service created
 */
function createService($serviceName, $request_data=null)
{
    // $endpointRegEx="}^(http|https|ws|wss|balancer)://[\w\d:#@%/;$()~_?\+-=\\\.&]*}";
    $endpointRegEx="}^([0-9|a-z|A-Z]+)://[\w\d:#@%/;$()~_?\+-=\\\.&]*}";
    $fkFail="foreign key constraint fail";

    $serviceName=normalizeName($serviceName);

    $error = new OSAError();
    $error->setHttpStatus(200);
    $error->setHttpLabel(
        "Bad request for method \"" .
        $_SERVER["REQUEST_METHOD"] .
        "\" for resource \"service\""
    );


    $error->setFunctionalLabel(
        "Bad request for method \"" .
        $_SERVER["REQUEST_METHOD"] .
        "\" for resource \"service\"\n"
    );
    $error->setFunctionalCode(0);


    $mySQLIsUserAuthenticationEnabled=1;
    $mySQLIsHitLoggingEnabled=0;
    $mySQLIsAnonymousAllowed=0;
    $mySQLOnAllNodes=1;
    $mySQLLoginFormUri="";

    if (isset($request_data["additionalConfiguration"])) {
        $mySQLAdditionalConfiguration=$request_data["additionalConfiguration"];
    } else {
        $mySQLAdditionalConfiguration="";
    }
    if (isset($request_data["isAnonymousAllowed"])) {
        if ($request_data["isAnonymousAllowed"]=="1" 
            ||  $request_data["isAnonymousAllowed"]=="0"
        ) {
            $mySQLIsAnonymousAllowed=$request_data["isAnonymousAllowed"];
        } else {
            $error->setHttpStatus(400);
            $error->setFunctionalCode(1);
            $error->setFunctionalLabel(
                $error->getFunctionalLabel() .
                " allowed value for isAnonymousAllowed is 0 or 1\n"
            );
        }
    }
    if (isset($request_data["isHitLoggingEnabled"])) {
        if ($request_data["isHitLoggingEnabled"]=="1" 
            || $request_data["isHitLoggingEnabled"]=="0"
        ) {
            $mySQLIsHitLoggingEnabled=$request_data["isHitLoggingEnabled"];
        } else {
            $error->setHttpStatus(400);
            $error->setFunctionalCode(1);
            $error->setFunctionalLabel(
                $error->getFunctionalLabel() .
                " allowed value for isHitLoggingEnabled is 0 or 1\n"
            );
        }
    }
    if (!isset($request_data["onAllNodes"]) or $request_data["onAllNodes"]==="") {
        $request_data["onAllNodes"]=1;
    }
    if (isset($request_data["onAllNodes"])) {
        if ($request_data["onAllNodes"]=="1" ||  $request_data["onAllNodes"]=="0") {
            $mySQLOnAllNodes=$request_data["onAllNodes"];
        } else {
            $error->setHttpStatus(400);
            $error->setFunctionalCode(1);
            $error->setFunctionalLabel(
                $error->getFunctionalLabel() .
                " allowed value for onAllNodes is 0 or 1\n"
            );
        }
    }
    if (isset($request_data["isUserAuthenticationEnabled"])) {
        if ($request_data["isUserAuthenticationEnabled"]=="1" 
            || $request_data["isUserAuthenticationEnabled"]=="0"
        ) {
            $mySQLIsUserAuthenticationEnabled=$request_data[
                "isUserAuthenticationEnabled"
            ];
        } else {
            $error->setHttpStatus(400);
            $error->setFunctionalCode(1);
            $error->setFunctionalLabel(
                $error->getFunctionalLabel() .
                " allowed value for isUserAuthenticationEnabled is 0 or 1\n"
            );
        }
    }

    if ($serviceName == null || $serviceName=="" ) {
        $error->setHttpStatus(400);
        $error->setFunctionalCode(1);
        $error->setFunctionalLabel(
            $error->getFunctionalLabel() .
            "serviceName is required\n"
        );
    } else {
        $mySQLServiceName=cut($serviceName, SERVICENAME_LENGTH);
    }

    if (!isset($request_data["isPublished"])) {
        $request_data["isPublished"]=1;
    }
    if ($request_data["isPublished"]=="1" ||  $request_data["isPublished"]=="0") {
        $mySQLIsPublished=$request_data["isPublished"];
    } else {
        $error->setHttpStatus(400);
        $error->setFunctionalCode(1);
        $error->setFunctionalLabel(
            $error->getFunctionalLabel() .
            " allowed value for isPublished are 0 or 1\n"
        );
    }


    if (!isset($request_data["isGlobalQuotasEnabled"]) 
        || $request_data["isGlobalQuotasEnabled"]==""
    ) {
        $request_data["isGlobalQuotasEnabled"]="0";
    }
    if ($request_data["isGlobalQuotasEnabled"]=="1" 
        || $request_data["isGlobalQuotasEnabled"]=="0"
    ) {
        $mySQLGlobalQuotas=$request_data["isGlobalQuotasEnabled"];
    } else {
        $error->setHttpStatus(400);
        $error->setFunctionalCode(1);
        $error->setFunctionalLabel(
            $error->getFunctionalLabel() .
            " allowed values for isGlobalQuotasEnabled is 0 or 1\n"
        );
    }
    $mySQLReqSec=0;
    $mySQLReqDay=0;
    $mySQLReqMonth=0;

    if (!isset($request_data["isGlobalQuotasEnabled"]) 
        || $request_data["isGlobalQuotasEnabled"]=="1"
    ) {
        if (!isset($request_data["reqSec"]) || $request_data["reqSec"]==="" ) {
            $error->setHttpStatus(400);
            $error->setFunctionalCode(1);
            $error->setFunctionalLabel(
                $error->getFunctionalLabel() .
                "reqSec is required when isGlobalQuotasEnabled=1\n"
            );
        } else {
            $mySQLReqSec=$request_data["reqSec"];
        }
        if (!isset($request_data["reqDay"]) || $request_data["reqDay"]==="" ) {
            $error->setHttpStatus(400);
            $error->setFunctionalCode(1);
            $error->setFunctionalLabel(
                $error->getFunctionalLabel() .
                "reqDay is required when isGlobalQuotasEnabled=1\n"
            );
        } else {
            $mySQLReqDay=$request_data["reqDay"];
        }
        if (!isset($request_data["reqMonth"]) || $request_data["reqMonth"]==="" ) {
            $error->setHttpStatus(400);
            $error->setFunctionalCode(1);
            $error->setFunctionalLabel(
                $error->getFunctionalLabel() .
                "reqMonth is required when isGlobalQuotasEnabled=1\n"
            );
        } else {
            $mySQLReqMonth=$request_data["reqMonth"];
        }

    }

    if (isset($request_data["isIdentityForwardingEnabled"]) 
        && $request_data["isIdentityForwardingEnabled"]!==""
    ) {
        if ($request_data["isIdentityForwardingEnabled"]=="0"
            || $request_data["isIdentityForwardingEnabled"]=="1"
        ) {
            $mySQLIdentityForwarding=$request_data["isIdentityForwardingEnabled"];
            if ($mySQLIsUserAuthenticationEnabled==0 
                && $request_data["isIdentityForwardingEnabled"]==1
            ) {
                $error->setHttpStatus(400);
                $error->setFunctionalCode(1);
                $error->setFunctionalLabel(
                    $error->getFunctionalLabel() .
                    " isIdentityForwardingEnabled can not be set to 1 when ".
                    "isUserAuthentication is disabled\n"
                );
            }
        } else {
            $error->setHttpStatus(400);
            $error->setFunctionalCode(1);
            $error->setFunctionalLabel(
                $error->getFunctionalLabel() .
                " allowed values for isIdentityForwardingEnabled is 0 or 1\n"
            );
        }

    } else {
        $mySQLIdentityForwarding=0;
    }

    if (isset($request_data["backEndUsername"])) {
            $mySQLBackEndUsername=cut(
                $request_data["backEndUsername"],
                BACKENDENDPOINT_LENGTH
            );
    } else {
        $mySQLBackEndUsername=null;
    }
    if (isset($request_data["backEndPassword"])) {
            $mySQLBackEndPassword= encrypt($request_data["backEndPassword"]);
    } else {
        $mySQLBackEndPassword=null;
    }

    if (isset($request_data["isUserQuotasEnabled"])) {
        if ($request_data["isUserQuotasEnabled"]=="0" 
            || $request_data["isUserQuotasEnabled"]=="1"
        ) {
            $mySQLUserQuotas=$request_data["isUserQuotasEnabled"];
            if ($mySQLIsUserAuthenticationEnabled==0 
                && $request_data["isUserQuotasEnabled"]==1
            ) {
                $error->setHttpStatus(400);
                $error->setFunctionalCode(1);
                $error->setFunctionalLabel(
                    $error->getFunctionalLabel() .
                    " isUserQuotasEnabled can not be set to 1 when ".
                    "isUserAuthentication is disabled\n"
                );
            }
        } else {
            $error->setHttpStatus(400);
            $error->setFunctionalCode(1);
            $error->setFunctionalLabel(
                $error->getFunctionalLabel() .
                " allowed value for isUSerquotasEnabled is 0 or 1\n"
            );
        }
    } else {
        $mySQLUserQuotas=0;
    }
    if (!isset($request_data["frontEndEndPoint"]) 
        || $request_data["frontEndEndPoint"]==""
    ) {
        $error->setHttpStatus(400);
        $error->setFunctionalCode(1);
        $error->setFunctionalLabel(
            $error->getFunctionalLabel() .
            "frontEndEndPoint is required\n"
        );
    } else {
        $mySQLFrontEndEndPoint=$request_data["frontEndEndPoint"];
        if (substr($mySQLFrontEndEndPoint, 0, 1) != "/") {
            $mySQLFrontEndEndPoint="/" . $mySQLFrontEndEndPoint;
        }
        $mySQLFrontEndEndPoint=cut($mySQLFrontEndEndPoint, FRONTENDENDPOINT_LENGTH);
    }
    if (!isset($request_data["backEndEndPoint"]) 
        || $request_data["backEndEndPoint"]==""
    ) {
        $error->setHttpStatus(400);
        $error->setFunctionalCode(1);
        $error->setFunctionalLabel(
            $error->getFunctionalLabel() .
            "backEndEndPoint is required\n"
        );
    } elseif (preg_match($endpointRegEx, $request_data["backEndEndPoint"])) {
        $mySQLBackEndEndPoint=cut(
            $request_data["backEndEndPoint"],
            BACKENDENDPOINT_LENGTH
        );
    } else {
        $error->setHttpStatus(400);
        $error->setFunctionalCode(1);
        $error->setFunctionalLabel(
            $error->getFunctionalLabel() .
            $request_data["backEndEndPoint"] .
            " is not a valid URL for backend service\n"
        );
    }
    if (isset($request_data["loginFormUri"]) 
        && $request_data["loginFormUri"]!==""
    ) {
        $mySQLLoginFormUri=$request_data["loginFormUri"] ;
    }

    if (isset($request_data["groupName"])) {
        if ($request_data["groupName"]!=="" ) {
                $mySQLGroupName=cut(
                    $request_data["groupName"],
                    GROUPNAME_LENGTH
                );
        } else if ($mySQLIsUserAuthenticationEnabled==1) {
            $error->setHttpStatus(400);
            $error->setFunctionalCode(1);
            $error->setFunctionalLabel(
                $error->getFunctionalLabel() .
                " value for groupName is required\n"
            );
        }
    } else {
        $mySQLGroupName=null;
    }

    if ($mySQLIsUserAuthenticationEnabled==0) {
        $mySQLGroupName=null;
        $mySQLUserQuotas=0;
        $mySQLIdentityForwarding=0;
    }



    if ($mySQLIsAnonymousAllowed==1) {
        $mySQLIdentityForwarding=1;
    }


    if ($error->getHttpStatus() != 200) {
        throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());
    } else {
        try {


            $db=openDBConnection();

            $strSQL = "";
            $strSQL = $strSQL . "INSERT INTO services (";
            $strSQL = $strSQL . "	serviceName, ";
            $strSQL = $strSQL . "	reqSec,";
            $strSQL = $strSQL . "	 reqDay,";
            $strSQL = $strSQL . "	 reqMonth,";
            $strSQL = $strSQL . "	 frontEndEndPoint,";
            $strSQL = $strSQL . "	 isGlobalQuotasEnabled,";
            $strSQL = $strSQL . "	 isUserQuotasEnabled,";
            $strSQL = $strSQL . "	 isIdentityForwardingEnabled,";
            $strSQL = $strSQL . "	 groupName,";
            $strSQL = $strSQL . "	 backEndEndPoint,";
            $strSQL = $strSQL . "	 backEndUsername,";
            $strSQL = $strSQL . "	 backEndPassword,";
            $strSQL = $strSQL . "	 isHitLoggingEnabled,";
            $strSQL = $strSQL . "	 isAnonymousAllowed,";
            $strSQL = $strSQL . "	 isUserAuthenticationEnabled,";
            $strSQL = $strSQL . "	 additionalConfiguration,";
            $strSQL = $strSQL . "	 onAllNodes,";
            $strSQL = $strSQL . "	 loginFormUri";

            $strSQL = $strSQL . ") values (";
            $strSQL = $strSQL . "	?,";
            $strSQL = $strSQL . "	? ,";
            $strSQL = $strSQL . "	? ,";
            $strSQL = $strSQL . "	? ,";
            $strSQL = $strSQL . "	?,";
            $strSQL = $strSQL . "	?,";
            $strSQL = $strSQL . "	?,";
            $strSQL = $strSQL . "	?,";
            $strSQL = $strSQL . "	?,";
            $strSQL = $strSQL . "	?,";
            $strSQL = $strSQL . "	?,";
            $strSQL = $strSQL . "	?,";
            $strSQL = $strSQL . "	?,";
            $strSQL = $strSQL . "	?,";
            $strSQL = $strSQL . "	?,";
            $strSQL = $strSQL . "	?,";
            $strSQL = $strSQL . "	?,";
            $strSQL = $strSQL . "	?)";

            $stmt=$db->prepare($strSQL);
            $stmt->execute(
                array(
                    $mySQLServiceName,
                    $mySQLReqSec ,
                    $mySQLReqDay ,
                    $mySQLReqMonth ,
                    $mySQLFrontEndEndPoint,
                    $mySQLGlobalQuotas,
                    $mySQLUserQuotas,
                    $mySQLIdentityForwarding,
                    $mySQLGroupName,
                    $mySQLBackEndEndPoint,
                    $mySQLBackEndUsername,
                    $mySQLBackEndPassword,
                    $mySQLIsHitLoggingEnabled,
                    $mySQLIsAnonymousAllowed,
                    $mySQLIsUserAuthenticationEnabled,
                    $mySQLAdditionalConfiguration,
                    $mySQLOnAllNodes,
                    $mySQLLoginFormUri
                )
            );
            if (applyApacheConfiguration()) {
                return getService($serviceName);
            } else {
                deleteService($serviceName);
                $error->setHttpStatus(400);
                $error->setFunctionalCode(1);
                $error->setFunctionalLabel("Invalid apache configuration");
                throw new Exception(
                    $error->GetFunctionalLabel(),
                    $error->getHttpStatus()
                );
            }
        }catch (Exception $e) {
            if (strpos($e->getMessage(), "Duplicate entry")>=0 
                ||strpos($e->getMessage(), "UNIQUE constraint failed")>=0
            ) {
                $error->setHttpStatus(409);
                $error->setFunctionalCode(5);
                $error->setFunctionalLabel(
                    "Service " .
                    $serviceName .
                    " already exists or a sevice with " .
                    $request_data["frontEndEndPoint"] .
                    " as front end URI already exists"
                );
            } elseif (strpos(strtolower($e->getMessage()), $fkFail)>=0) {

                $error->setHttpStatus(404);
                $error->setFunctionalCode(5);
                $error->setFunctionalLabel(
                    "The group " . 
                    $request_data["groupName"] . 
                    " does not exists"
                );
            } else {
                $error->setHttpStatus(500);
                $error->setFunctionalCode(3);
                $error->setFunctionalLabel($e->getMessage());
            }
            throw new Exception(
                $error->GetFunctionalLabel(),
                $error->getHttpStatus()
            );
        }
    }
}



/** 
 * Delete a service from database
 * 
 * @param string $serviceName Service to delete
 * 
 * @return array deleted service
 */
function deleteService($serviceName)
{
    $error = new OSAError();

    $serviceName=normalizeName($serviceName);


    if ($serviceName != null && $serviceName != "") {
        $db=openDBConnection();

        $stmt=$db->prepare("SELECT * FROM services WHERE serviceName=?");
        $stmt->execute(array(cut($serviceName, SERVICENAME_LENGTH)));

        if (!$row=$stmt->fetch(PDO::FETCH_ASSOC)) {
            $error->setHttpStatus(404);
            $error->setHttpLabel("Unknown service");
            $error->setFunctionalCode(4);
            $error->setFunctionalLabel(
                "Service ". $serviceName . " does not exists"
            );
            throw new RestException(
                $error->getHttpStatus(),
                $error->GetFunctionalLabel()
            );
        } else {
            if (startsWith($serviceName, ADMIN_SERVICE)) {
                $error->setHttpStatus(403);
                $error->setFunctionalCode(3);
                $error->setFunctionalLabel(
                    $serviceName . " service can't be deleted"
                );
                throw new RestException(
                    $error->getHttpStatus(),
                    $error->GetFunctionalLabel()
                );
            }
            $service = new Service($row);
            try{
                $stmt=$db->prepare("DELETE FROM services WHERE  serviceName=?");
                $stmt->execute(array(cut($serviceName, SERVICENAME_LENGTH)));
            }catch (Exception $e) {
                $error->setHttpStatus(500);
                $error->setFunctionalCode(3);
                $error->setFunctionalLabel($e->getMessage());
                if (strpos(strtolower($e->getMessage()), $fkFail)>=0) {
                    $error->setHttpStatus(400);
                    $error->setFunctionalLabel(
                        "The service " . $serviceName . 
                        " is used by some users. ".
                        "Please remove subscribtions to it first"
                    );
                }
                throw new RestException(
                    $error->getHttpStatus(),
                    $error->GetFunctionalLabel()
                );

            }
            $strSQL="DELETE FROM counters WHERE  counterName like ?";
            $stmt=$db->prepare($strSQL);

            $stmt->execute(
                array(
                    "R=" . cut($serviceName, SERVICENAME_LENGTH) . "%"
                )
            );
            if (applyApacheConfiguration()) {

                $rc = $service->toArray();
            } else {
                $error->setHttpStatus(500);
                $error->setFunctionalCode(1);
                $error->setFunctionalLabel(
                    "Service successfully saved but unable to apply configuration ".
                    "on runtime appliance"
                );
                throw new RestException(
                    $error->getHttpStatus(),
                    $error->GetFunctionalLabel()
                );
            }

        }
    } else {
        $error->setHttpLabel(
            "Bad request for method \"" . 
            $_SERVER["REQUEST_METHOD"] . 
            "\" for resource \"service\""
        );
        $error->setHttpStatus(400);
        $error->setFunctionalCode(1);
        $error->setFunctionalLabel(
            $error->getFunctionalLabel() . 
            "serviceName is required\n"
        );
        throw new RestException(
            $error->getHttpStatus(), 
            $error->GetFunctionalLabel()
        );
    }
    return $rc;
}

/**
 * Update a service in database
 * 
 * @param string $serviceName  Service identifier
 * @param string $request_data Service properties
 *                             $request_data["additionalConfiguration"]
 *                             $request_data["isAnonymousAllowed"]
 *                             $request_data["isHitLoggingEnabled"]
 *                             $request_data["onAllNodes"]
 *                             $request_data["isUserAuthenticationEnabled"]
 *                             $request_data["isPublished"]
 *                             $request_data["isGlobalQuotasEnabled"]
 *                             $request_data["reqSec"]
 *                             $request_data["reqDay"]
 *                             $request_data["reqMonth"]
 *                             $request_data["isIdentityForwardingEnabled"]
 *                             $request_data["backEndUsername"]
 *                             $request_data["backEndPassword"]
 *                             $request_data["isUserQuotasEnabled"]
 *                             $request_data["frontEndEndPoint"]
 *                             $request_data["backEndEndPoint"]
 *                             $request_data["loginFormUri"]
 *                             $request_data["groupName"]
 * 
 * @return array updated service
 */
function updateService($serviceName, $request_data=null)
{
    // $endpointRegEx="}^(http|https|ws|wss|balancer)://[\w\d:#@%/;$()~_?\+-=\\\.&]*}";
    $endpointRegEx="}^([0-9|a-z|A-Z]+)://[\w\d:#@%/;$()~_?\+-=\\\.&]*}";
    $fkFail="foreign key constraint fail";

    $serviceName=normalizeName($serviceName);

    $error = new OSAError();
    $error->setHttpStatus(200);
    $error->setHttpLabel(
        "Bad request for method \"" .
        $_SERVER["REQUEST_METHOD"] .
        "\" for resource \"service\""
    );


    $error->setFunctionalLabel(
        "Bad request for method \"" .
        $_SERVER["REQUEST_METHOD"] .
        "\" for resource \"service\"\n"
    );
    $error->setFunctionalCode(0);


    if ($serviceName == null || $serviceName=="" ) {
        $error->setHttpStatus(400);
        $error->setFunctionalCode(1);
        $error->setFunctionalLabel(
            $error->getFunctionalLabel() . "serviceName is required\n"
        );
    } else {
        $serviceName=str_replace(" ", "_", $serviceName);
        $mySQLServiceName=cut($serviceName, SERVICENAME_LENGTH);
    }
    $service=getService($serviceName);
    $serviceBkg= $service;

    if (isset($request_data["additionalConfiguration"])) {
        $service["additionalConfiguration"]=$request_data["additionalConfiguration"];
    }


    if (isset($request_data["isHitLoggingEnabled"])) {
        if ($request_data["isHitLoggingEnabled"]=="1" 
            || $request_data["isHitLoggingEnabled"]=="0"
        ) {
            $service["isHitLoggingEnabled"]=$request_data["isHitLoggingEnabled"];
        } else {
            $error->setHttpStatus(400);
            $error->setFunctionalCode(1);
            $error->setFunctionalLabel(
                $error->getFunctionalLabel() .
                " allowed value for isHitLoggingEnabled is 0 or 1\n"
            );
        }
    }
    if (isset($request_data["isAnonymousAllowed"])) {
        if ($request_data["isAnonymousAllowed"]=="1" 
            || $request_data["isAnonymousAllowed"]=="0"
        ) {
            $service["isAnonymousAllowed"]=$request_data["isAnonymousAllowed"];
        } else {
            $error->setHttpStatus(400);
            $error->setFunctionalCode(1);
            $error->setFunctionalLabel(
                $error->getFunctionalLabel() .
                " allowed value for isAnonymousAllowed is 0 or 1\n"
            );
        }
    }
    if (isset($request_data["isUserAuthenticationEnabled"])) {
        if ($request_data["isUserAuthenticationEnabled"]=="1" 
            || $request_data["isUserAuthenticationEnabled"]=="0"
        ) {
            if ($request_data["isUserAuthenticationEnabled"]=="1" 
                && (!isset($request_data["groupName"]) 
                ||  $request_data["groupName"]=="")
            ) {
                $error->setHttpStatus(400);
                $error->setFunctionalCode(1);
                $error->setFunctionalLabel(
                    $error->getFunctionalLabel() .
                    " value for groupName is required\n"
                );
            } else {
                $service[
                    "isUserAuthenticationEnabled"
                ]=$request_data[
                    "isUserAuthenticationEnabled"
                ];
            }
        } else {
            $error->setHttpStatus(400);
            $error->setFunctionalCode(1);
            $error->setFunctionalLabel(
                $error->getFunctionalLabel() .
                " allowed value for isUserAuthenticationEnabled is 0 or 1\n"
            );
        }
    }

    if (isset($request_data["isPublished"])) {
        if ($request_data["isPublished"]=="1" 
            || $request_data["isPublished"]=="0"
        ) {
            $service["isPublished"]=$request_data["isPublished"];
        } else {
            $error->setHttpStatus(400);
            $error->setFunctionalCode(1);
            $error->setFunctionalLabel(
                $error->getFunctionalLabel() .
                " allowed value for isPublished is 0 or 1\n"
            );
        }
    }
    if (isset($request_data["onAllNodes"])) {
        if ($request_data["onAllNodes"]=="1" ||  $request_data["onAllNodes"]=="0") {
            $service["onAllNodes"]=$request_data["onAllNodes"];
        } else {
            $error->setHttpStatus(400);
            $error->setFunctionalCode(1);
            $error->setFunctionalLabel(
                $error->getFunctionalLabel() .
                " allowed value for onAllNodes is 0 or 1\n"
            );
        }
    }

    if (isset($request_data["isGlobalQuotasEnabled"]) 
        && $request_data["isGlobalQuotasEnabled"]!==""
    ) {
        if ($request_data["isGlobalQuotasEnabled"]=="1" 
            || $request_data["isGlobalQuotasEnabled"]=="0"
        ) {
            $service["isGlobalQuotasEnabled"]=$request_data["isGlobalQuotasEnabled"];
        } else {
            $error->setHttpStatus(400);
            $error->setFunctionalCode(1);
            $error->setFunctionalLabel(
                $error->getFunctionalLabel() .
                " allowed values for isGlobalQuotasEnabled is 0 or 1\n"
            );
        }
    }

    if (isset($request_data["isGlobalQuotasEnabled"]) 
        && $request_data["isGlobalQuotasEnabled"]=="1"
    ) {
        if (!isset($request_data["reqSec"]) || $request_data["reqSec"]==="" ) {
            $error->setHttpStatus(400);
            $error->setFunctionalCode(1);
            $error->setFunctionalLabel(
                $error->getFunctionalLabel() .
                "reqSec is required when isGlobalQuotasEnabled=1\n"
            );
        } elseif (is_numeric($request_data["reqSec"]) 
            && $request_data["reqSec"]>=0
        ) {
            $service["reqSec"]=$request_data["reqSec"];
        } else {
            $error->setHttpStatus(400);
            $error->setFunctionalCode(1);
            $error->setFunctionalLabel(
                $error->getFunctionalLabel() .
                "reqSec should be an integer >=0\n"
            );
        }
        if (!isset($request_data["reqDay"]) || $request_data["reqDay"]==="" ) {
            $error->setHttpStatus(400);
            $error->setFunctionalCode(1);
            $error->setFunctionalLabel(
                $error->getFunctionalLabel() .
                "reqDay is required when isGlobalQuotasEnabled=1\n"
            );
        } elseif (is_numeric($request_data["reqDay"]) 
            && $request_data["reqDay"]>=0
        ) {
            $service["reqDay"]=$request_data["reqDay"];
        } else {
            $error->setHttpStatus(400);
            $error->setFunctionalCode(1);
            $error->setFunctionalLabel(
                $error->getFunctionalLabel() . 
                "reqDay should be an integer >=0\n"
            );
        }
        if (!isset($request_data["reqMonth"]) 
            || $request_data["reqMonth"]===""
        ) {
            $error->setHttpStatus(400);
            $error->setFunctionalCode(1);
            $error->setFunctionalLabel(
                $error->getFunctionalLabel() .
                "reqMonth is required when isGlobalQuotasEnabled=1\n"
            );
        } elseif (is_numeric($request_data["reqMonth"]) 
            && $request_data["reqMonth"]>=0
        ) {
            $service["reqMonth"]=$request_data["reqMonth"];
        } else {
            $error->setHttpStatus(400);
            $error->setFunctionalCode(1);
            $error->setFunctionalLabel(
                $error->getFunctionalLabel() .
                "reqMonth should be an integer >=0\n"
            );
        }

    }

    if (isset($request_data["isIdentityForwardingEnabled"])) {
        if ((int)$request_data["isIdentityForwardingEnabled"]==0 
            || (int)$request_data["isIdentityForwardingEnabled"]==1
        ) {
            $service[
                "isIdentityForwardingEnabled"
            ]=$request_data["isIdentityForwardingEnabled"];
            if ($service["isUserAuthenticationEnabled"]==0 
                && $request_data["isIdentityForwardingEnabled"]==1
            ) {
                $error->setHttpStatus(400);
                $error->setFunctionalCode(1);
                $error->setFunctionalLabel(
                    $error->getFunctionalLabel() .
                    " isIdentityForwardingEnabled can not be set to 1 when ".
                    "isUserAuthentication is disabled\n"
                );
            }
        } else {
            $error->setHttpStatus(400);
            $error->setFunctionalCode(1);
            $error->setFunctionalLabel(
                $error->getFunctionalLabel() .
                " allowed values for isIdentityForwardingEnabled is 0 or 1\n"
            );
        }

    }
    if (isset($request_data["backEndUsername"])) {
            $service["backEndUsername"]=$request_data["backEndUsername"];
    }
    if (isset($request_data["backEndPassword"])) {
            $service["backEndPassword"]=$request_data["backEndPassword"];
    }

    if (isset($request_data["isUserQuotasEnabled"])) {
        if ($request_data["isUserQuotasEnabled"]=="0" 
            || $request_data["isUserQuotasEnabled"]=="1"
        ) {
            $service["isUserQuotasEnabled"]=$request_data["isUserQuotasEnabled"];
            if ($request_data["isUserAuthenticationEnabled"]==0 
                && $request_data["isUserQuotasEnabled"]==1
            ) {
                $error->setHttpStatus(400);
                $error->setFunctionalCode(1);
                $error->setFunctionalLabel(
                    $error->getFunctionalLabel() .
                    " isUserQuotasEnabled can not be set to 1 when ".
                    "isUserAuthentication is disabled\n"
                );
            }
        } else {
            $error->setHttpStatus(400);
            $error->setFunctionalCode(1);
            $error->setFunctionalLabel(
                $error->getFunctionalLabel() .
                " allowed value for isUSerquotasEnabled is 0 or 1\n"
            );
        }
    }
    if (isset($request_data["frontEndEndPoint"]) 
        && $request_data["frontEndEndPoint"]!==""
    ) {
        $frontEndEndPoint=$request_data["frontEndEndPoint"];
        if (substr($frontEndEndPoint, 0, 1) != "/") {
            $frontEndEndPoint="/" . $frontEndEndPoint;
        }
        $frontEndEndPoint=cut($frontEndEndPoint, FRONTENDENDPOINT_LENGTH);

        $service["frontEndEndPoint"]=$frontEndEndPoint;
    }
    if (isset($request_data["backEndEndPoint"]) 
        && $request_data["backEndEndPoint"]!==""
    ) {
        if (preg_match($endpointRegEx, $request_data["backEndEndPoint"])) {
            $service["backEndEndPoint"]=$request_data["backEndEndPoint"];
        } else {
            $error->setHttpStatus(400);
            $error->setFunctionalCode(1);
            $error->setFunctionalLabel(
                $error->getFunctionalLabel() . 
                $request_data["backEndEndPoint"] . " is not a valid URL\n"
            );
        }
    }
    if (isset($request_data["groupName"]) && $request_data["groupName"]!=="" ) {
        $service["groupName"]=$request_data["groupName"];
    }
    if ($service["isUserAuthenticationEnabled"]==0) {
        $service["groupName"]=null;
        $service["isUserQuotasEnabled"]=0;
        $service["isIdentityForwardingEnabled"]=0;
    }

    if ($service["isAnonymousAllowed"]==1) {
        $service["isIdentityForwardingEnabled"]=1;
    }
    if (isset($request_data["loginFormUri"])  ) {
        $service["loginFormUri"]=$request_data["loginFormUri"];
    }


    if ($error->getHttpStatus() != 200) {
        throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());
    } else {
        $strSQL = "";
        $strSQL = $strSQL . "UPDATE services SET ";
        $strSQL = $strSQL . "	 reqSec=?," ;
        $strSQL = $strSQL . "	 reqDay=?," ;
        $strSQL = $strSQL . "	 reqMonth=?," ;
        $strSQL = $strSQL . "	 frontEndEndPoint=?," ;
        $strSQL = $strSQL . "	 isGlobalQuotasEnabled=?," ;
        $strSQL = $strSQL . "	 isUserQuotasEnabled=?," ;
        $strSQL = $strSQL . "	 isIdentityForwardingEnabled=?," ;
        $strSQL = $strSQL . "	 isPublished=?," ;
        $strSQL = $strSQL . "	 groupName=?," ;
        $strSQL = $strSQL . "	 backEndEndPoint=?," ;
        $strSQL = $strSQL . "	 backEndUsername=?," ;
        $strSQL = $strSQL . "	 backEndPassword=?," ;
        $strSQL = $strSQL . "	 isHitLoggingEnabled=?," ;
        $strSQL = $strSQL . "	 isAnonymousAllowed=?," ;
        $strSQL = $strSQL . "	 isUserAuthenticationEnabled=?," ;
        $strSQL = $strSQL . "	 onAllNodes=?," ;
        $strSQL = $strSQL . "	 additionalConfiguration=?," ;
        $strSQL = $strSQL . "	 loginFormUri=?" ;
        $strSQL = $strSQL . " WHERE serviceName=?";


        $bindPrms=array($service["reqSec"],
                        $service["reqDay"],
                        $service["reqMonth"],
                        $service["frontEndEndPoint"],
                        $service["isGlobalQuotasEnabled"],
                        $service["isUserQuotasEnabled"],
                        $service["isIdentityForwardingEnabled"],
                        $service["isPublished"],
                        $service["groupName"],
                        $service["backEndEndPoint"],
                        $service["backEndUsername"],
                        encrypt($service["backEndPassword"]),
                        $service["isHitLoggingEnabled"],
                        $service["isAnonymousAllowed"],
                        $service["isUserAuthenticationEnabled"],
                        $service["onAllNodes"],
                        $service["additionalConfiguration"],
                        $service["loginFormUri"],
                        $mySQLServiceName);
        try{
            $db=openDBConnection();
            $stmt=$db->prepare($strSQL);
            $stmt->execute($bindPrms);
            if ($service["onAllNodes"]==1) {
                // Remove potential nodes association
                // on future nodes list application, an empty existing list means 
                // that service was previously deployed on all node
                $strSQL="DELETE FROM servicesnodes WHERE serviceName=:serviceName";
                $stmt=$db->prepare($strSQL);
                $stmt->execute(array("serviceName" => $mySQLServiceName));
            }


        }catch (Exception $e) {
            if (strpos($e->getMessage(), "Duplicate entry")>=0 
                || strpos($e->getMessage(), "UNIQUE constraint failed")>=0
            ) {
                $error->setHttpStatus(409);
                $error->setFunctionalCode(5);
                $error->setFunctionalLabel(
                    "Service " . $serviceName . " already exists"
                );

            } elseif (strpos(strtolower($e->getMessage()), $fkFail)>=0) {
                $error->setHttpStatus(404);
                $error->setFunctionalLabel(
                    "The group " . $request_data["groupName"] . " does not exists"
                );
            } else {
                $error->setHttpStatus(500);
                $error->setFunctionalCode(3);
                $error->setFunctionalLabel($e->getMessage());


            }

        }

        if ($error->getHttpStatus() != 200) {
            throw new Exception(
                $error->GetFunctionalLabel(),
                $error->getHttpStatus()
            );
        }
        if ($request_data["noApply"] == 1) {
            return getService($serviceName);
        } else if (applyApacheConfiguration()) {
            return getService($serviceName);
        } else {
            $serviceBkg["noApply"]=1;
            updateService($serviceName, $serviceBkg);
            $error->setHttpStatus(400);
            $error->setFunctionalCode(1);
            $error->setFunctionalLabel("Invalid apache configurration");
            throw new Exception(
                $error->GetFunctionalLabel(),
                $error->getHttpStatus()
            );
        }
    }
}


/**
 * Get user quotas from database
 * 
 * @param string $serviceName  Service id
 * @param string $userName     User Id
 * @param array  $request_data Filter/order
 * 
 * @return array quotas
 */
function getUserQuotas($serviceName=null, $userName=null, $request_data=null)
{
    $serviceName=normalizeName($serviceName);
    $userName=normalizeName($userName);

    $error = new OSAError();

    if ($serviceName == null || $serviceName=="") {
        $error->setHttpStatus(400);
        $error->setFunctionalCode(1);
        $error->setFunctionalLabel(
            $error->getFunctionalLabel() . "serviceName is required\n"
        );
        throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());
    }

    $db=openDBConnection();
    $strSQL="SELECT * FROM usersquotas WHERE serviceName=?";

    $bindPrms=array(cut($serviceName, SERVICENAME_LENGTH));

    if ($userName != null && $userName != "") {
        $strSQL .= " AND userName=?";
        array_push($bindPrms, cut($userName, USERNAME_LENGTH));

        $stmt=$db->prepare($strSQL);
        $stmt->execute($bindPrms);
        if (!$row=$stmt->fetch(PDO::FETCH_ASSOC)) {
            $error->setHttpStatus(404);
            $error->setHttpLabel("Unknown quotas");
            $error->setFunctionalCode(4);
            $error->setFunctionalLabel(
                "Quotas for user ". $userName . " and service " . $serviceName . 
                " does not exists for user " . $userName
            );
            throw new Exception(
                $error->GetFunctionalLabel(),
                $error->getHttpStatus()
            );
        } else {
            $rc = new Quota($row);
            $rc = $rc->toArray();
        }
    } else {
        if (isset($request_data["order"]) && $request_data["order"] != "") {
            $strSQL .= " ORDER BY " . escapeOrder($request_data["order"]);
        }
        $stmt=$db->prepare($strSQL);
        $stmt->execute($bindPrms);

        $rc = Array();
        while ($row=$stmt->fetch(PDO::FETCH_ASSOC)) {
            $quota = new Quota($row);
            array_push($rc, $quota->toArray());
        }


    }
    return $rc;
}


/**
 * Get a list of user witch are allowed to use this Service but where User quotas 
 * are not set but required
 * 
 * @param string $serviceName servic eid
 * 
 * @return array list of matching users
 */
function getUnsetQuotas($serviceName)
{
    $serviceName=normalizeName($serviceName);

    $error = new OSAError();


    $db=openDBConnection();

    $strSQL="";
    $strSQL.="SELECT u.* ";
    $strSQL.="FROM 	users u, ";
    $strSQL.="	   	usersgroups ug, ";
    $strSQL.="		services s ";
    $strSQL.="WHERE isUserQuotasEnabled=1 ";
    $strSQL.="AND	s.groupName=ug.groupName ";
    $strSQL.="AND	ug.userName=u.userName ";
    $strSQL.="AND	s.serviceName=? ";
    $strSQL.="AND	u.userName not in (SELECT uq.userName ".
                                      "FROM usersquotas uq ".
                                      "WHERE uq.serviceName=?)";

    $bindPrms=array(
        cut($serviceName, SERVICENAME_LENGTH),
        cut($serviceName, SERVICENAME_LENGTH)
    );
    $stmt=$db->prepare($strSQL);
    $stmt->execute($bindPrms);
    $rc = Array();
    while ($row=$stmt->fetch(PDO::FETCH_ASSOC)) {
        $user = new User($row);
        array_push($rc, $user->toArray());
    }
    return $rc;
}

/**
 * Get list of nodes where a service is published
 * 
 * @param string $serviceName service id
 * 
 * @return array list of matching nodes
 */
function nodesListForService($serviceName)
{
    $serviceName=normalizeName($serviceName);
    $error = new OSAError();


    $db=openDBConnection();
    $bindPrms=array();
    if ($serviceName==null) {
        $strSQL= "SELECT n.*, 0 as onNode FROM nodes n";
    } else {
        $strSQL= "SELECT n.*, exists(SELECT 'x' ".
                                    "FROM servicesnodes sn ".
                                    "WHERE sn.serviceName=? ".
                                    "and sn.nodeName=n.nodeName) as onNode ".
                 "FROM nodes n";
        array_push($bindPrms, $serviceName);
    }

    if (isset($request_data["order"])) {
        $strSQL =$strSQL .  " ORDER BY " . escapeOrder($request_data["order"]);
    }
    $stmt=$db->prepare($strSQL);
    $stmt->execute($bindPrms);
    $rc = Array();
    while ($row=$stmt->fetch(PDO::FETCH_ASSOC)) {
        $node = new Node($row);
        $published = $row["onNode"];
        array_push(
            $rc,
            Array (
                "node" => $node->toArray(),
                "published" => $published
            )
        );
    }
    return $rc;

}



/**
 * Set list of node on with serivice is published
 * 
 * @param string $serviceName serviceId
 * @param array  $nodesList   List of nodes
 * @param int    $noApply     (0|1) apply apache conf or not
 * 
 * @return array list of nodes
 */
function setNodesListForService($serviceName, $nodesList, $noApply)
{
    $impactedNodes=array();

    $serviceName=normalizeName($serviceName);
    $error = new OSAError();

    if (count($nodesList) <=0) {
        throw new RestException(400, "At least one node to publish on is required");
    }


    $db=openDBConnection();

    //Get nodes previously using this service
    $nodes=nodesListForService($serviceName);
    $fromAllNodes=true;
    foreach ($nodes as $node) {
        $nodeName=$node["node"]["nodeName"];
        if ($node["published"] == 1 && !isset($impactedNodes[$nodeName])) {
            $impactedNodes[$nodeName]=$nodeName;
            $fromAllNodes=false;
        }
    }
    $stmt=$db->prepare("DELETE FROM servicesnodes WHERE serviceName=?");
    $stmt->execute(array($serviceName));
    $strSQL = "INSERT INTO servicesnodes (serviceName, nodeName) VALUES (?, ?)";
    $stmt=$db->prepare($strSQL);
    for ($i=0; $i<count($nodesList); $i++) {
        $bindPrms=array($serviceName, $nodesList[$i]);
        $stmt->execute($bindPrms);
        if (!isset($impactedNodes[$nodesList[$i]])) {
            $impactedNodes[$nodesList[$i]]=$nodesList[$i];
        }
    }
    if ($noApply==1 ) {
        return nodesListForService($serviceName);
    } else if (($fromAllNodes && applyApacheNodesConfiguration()) 
        || applyApacheConfiguration($impactedNodes)
    ) {
        return nodesListForService($serviceName);
    } else {
        $error->setHttpStatus(500);
        $error->setFunctionalCode(1);
        $error->setFunctionalLabel(
            "Service successfully saved but unable to apply ".
            "configuration on runtime appliance"
        );
        throw new Exception($error->GetFunctionalLabel(), $error->getHttpStatus());
    }

}
