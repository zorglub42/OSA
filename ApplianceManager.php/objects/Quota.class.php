<?php
/**
 * Reverse Proxy as a service
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
 * Version : 1.0.0
 *
 * Software Name : OpenServicesAccess
 * Version : 1.0
 *
 * Copyright (c) 2011 – 2014 Orange
 * This software is distributed under the Apache 2 license
 * <http://www.apache.org/licenses/LICENSE-2.0.html>
 *
 *--------------------------------------------------------
 * File Name   : ApplianceManager/ApplianceManager.php/objects/Quota.class.php
 *
 * Created     : 2012-02
 * Authors     : Benoit HERARD <benoit.herard(at)orange.com>
 *
 * Description :
 *      .../...
 *--------------------------------------------------------
 * History     :
 * 1.0.0 - 2012-10-01 : Release of the file
*/
require_once '../objects/ApplianceObject.class.php';

/**
 * Quota class
 * 
 * PHP Version 7.0
 * 
 * @category ReverseProxy
 * @package  OSA
 * @author   Benoit HERARD <benoit.herard@orange.com>
 * @license  http://www.apache.org/licenses/LICENSE-2.0.htm Apache 2 license
 * @link     https://github.com/zorglub42/OSA/
*/
class Quota extends ApplianceObject
{
    /**
     * Relative service identifier
     * 
     * @var string relative service identifier
     */
    public $serviceName;
    
    /**
     * Relative service uri
     * 
     * @var url relative service uri
     */
    public $serviceUri;
    
    /**
     * Relative user idientifer
     * 
     * @var string relative user identifier
     */
    public $userName;
    
    /**
     * Relative user uri
     * 
     * @var url relative user uri
     */
    public $userUri;
    
    /**
     * Number of requests per sec.
     * 
     * @var int reqSec maximum number of request per seconds allowed
     */
    public $reqSec=0;
    
    /**
     * Number of requests per day.
     * 
     * @var int reqDay maximum number of request per days allowed
     */
    public $reqDay=0;
    
    /**
     * Number of requests per month
     * 
     * @var int reqMonth maximum number of request pre months allowed
     */
    public $reqMonth=0;
    
    
    /**
     * Getter
     * 
     * @return string $serviceName Relative service name
     */
    function getServiceName()
    {
        return $this->serviceName;
    }
    /**
     * Setter
     * 
     * @param string $serviceName Relative service name
     * 
     * @return void
     */
    function setServiceName($serviceName)
    {
        $this->serviceName=$serviceName;
    }
    
    
    /**
     * Getter
     * 
     * @return int Number of requests per sec.
     */
    function getReqSec()
    {
        return $this->reqSec;
    }
    /**
     * Setter
     * 
     * @param int $reqSec Number of requests per sec.
     * 
     * @return void
     */
    function setReqSec($reqSec)
    {
        $this->reqSec=$reqSec;
    }

        
    /**
     * Getter
     * 
     * @return int Number of requests per day.
     */
    function getReqDay()
    {
        return $this->reqDay;
    }
    /**
     * Setter
     * 
     * @param int $reqDay Number of requests per day
     * 
     * @return void
     */
    function setReqDay($reqDay)
    {
        $this->reqDay=$reqDay;
    }

        
    /**
     * Getter
     * 
     * @return int Number of requests per month.
     */
    function getReqMonth()
    {
        return $this->reqMonth;
    }
    /**
     * Setter
     * 
     * @param int $reqMonth Number of requests per Month
     * 
     * @return void
     */
    function setReqMonth($reqMonth)
    {
        $this->reqMonth=$reqMonth;
    }
    
        
    /**
     * Getter
     * 
     * @return uri User URI
     */
    function getUserUri()
    {
        return $this->userUri;
    }
    /**
     * Setter
     * 
     * @param string $userUri User URI
     * 
     * @return void
     */
    function setUserUri($userUri)
    {
        $this->userUri=$this->getPublicUriPrefix()  . $userUri;
    }
        
            
    /**
     * Getter
     * 
     * @return uri Service URI
     */
    function getServiceUri()
    {
        return $this->serviceUri;
    }
    /**
     * Setter
     * 
     * @param string $serviceUri Service URI
     * 
     * @return void
     */
    function setServiceUri($serviceUri)
    {
        $this->serviceUri=$this->getPublicUriPrefix()  . $serviceUri;
    }

            
    /**
     * Getter
     * 
     * @return string User name
     */
    function getUserName()
    {
        return $this->userName;
    }
    /**
     * Setter
     * 
     * @param string $userName User Name
     * 
     * @return void
     */
    function setUserName($userName)
    {
        $this->userName=$userName;
    }
    
    /**
     * Constructor
     * 
     * @param object $rqt PDO row
     */
    public function __construct($rqt=null)
    {
        if ($rqt != null) {
            $this->setUri(
                "services/" . 
                urlencode($rqt["serviceName"]) . 
                "/quotas/" . 
                urlencode($rqt["userName"])
            );
            $this->setUserName($rqt["userName"]);
            $this->setUserUri("users/" . urlencode($rqt["userName"]));
            $this->setServiceName($rqt["serviceName"]);
            $this->setServiceUri("services/" . urlencode($rqt["serviceName"]));
            $this->setReqSec($rqt["reqSec"]);
            $this->setReqDay($rqt["reqDay"]);
            $this->setReqMonth($rqt["reqMonth"]);
        }
    }
    
    /**
     * Convert object to associative array
     * 
     * @return array Object in a array
     */
    public function toArray()
    {
        return Array(
                "uri" => $this->getUri(),
                "serviceName" => $this->getServiceName(),
                "serviceUri" => $this->getServiceUri(),
                "userName" => $this->getUserName(),
                "userUri" => $this->getUserUri(),
                "reqSec" => $this->getReqSec(),
                "reqDay" => $this->getReqDay(),
                "reqMonth" => $this->getReqMonth() 
            );
        
    }
}
