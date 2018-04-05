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
 * File Name   : ApplianceManager/ApplianceManager.php/objects/Log.class.php
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
 * Logs class
 * 
 * PHP Version 7.0
 * 
 * @category ReverseProxy
 * @package  OSA
 * @author   Benoit HERARD <benoit.herard@orange.com>
 * @license  http://www.apache.org/licenses/LICENSE-2.0.htm Apache 2 license
 * @link     https://github.com/zorglub42/OSA/
 */
class Log extends ApplianceObject
{
    /**
     * Log identifier
     * 
     * @var int id log identifier
     */
    public $id;
    /**
     * Log message
     * 
     * @var string message message Logged message
     */
    public $message;
    /**
     * URI requested
     * 
     * @var uri front end uri invoked
     */
    public $frontEndUri;
    /**
     * HTTP response status
     * 
     * @var int status HTTP Response status
     */
    public $status;
    /**
     * Requested service
     * 
     * @var string serviceName service invoked
     */
    public $serviceName;
    /**
     * Requestor username
     * 
     * @var string userName [optional] authentifed user
     */
    public $userName;
    /**
     * Request timestamp
     * 
     * @var string timeStamp hit date in ISO 8601 full format
     */
    public $timeStamp;
    
    /**
     * Setter
     * 
     * @param int $id Log id
     * 
     * @return void
     */
    function setId($id)
    {
        $this->id=$id;
    }
    /**
     * Getter
     * 
     * @return int log id
     */
    function getId()
    {
        return $this->id;
    }
    
    
    /**
     * Getter
     * 
     * @return string service name
     */
    function getServiceName()
    {
        return $this->serviceName;
    }
    /**
     * Setter
     * 
     * @param string $serviceName servic eid
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
     * @return string user uri
     */
    function getUserUri()
    {
        return $this->getPublicUriPrefix() . "users/" . urlencode($this->userName);
    }
        
            
    /**
     * Getter
     * 
     * @return string service uri
     */
    function getServiceUri()
    {
        return "services/" . urlencode($this->serviceName);
    }

            
    /**
     * Getter
     * 
     * @return string user name
     */
    function getUserName()
    {
        return $this->userName;
    }
    
    /**
     * Setter
     * 
     * @param string $userName user id
     * 
     * @return void
     */
    function setUserName($userName)
    {
        $this->userName=$userName;
    }
    
    /**
     * Getter
     * 
     * @return string front end uri
     */
    function getFrontEndUri()
    {
        return $this->frontEndUri;
    }
    
    /**
     * Setter
     * 
     * @param string $frontEndUri requested uri
     * 
     * @return void
     */
    function setFrontEndUri($frontEndUri)
    {
        $this->frontEndUri=$frontEndUri;
    }
    
    /**
     * Getter
     * 
     * @return int http status
     */
    function getStatus()
    {
        return $this->status;
    }
    
    /**
     * Setter
     * 
     * @param int $status HTT response status
     * 
     * @return void
     */
    function setStatus($status)
    {
        $this->status=$status;
    }
    
    /**
     * Getter
     * 
     * @return string log message
     */
    function getMessage()
    {
        return $this->message;
    }
    
    /**
     * Setter
     * 
     * @param string $message log message
     * 
     * @return void
     */
    function setMessage($message)
    {
        $this->message=$message;
    }
    
    /**
     * Getter
     * 
     * @return string request timestamp
     */
    function getTimeStamp()
    {
        return $this->timeStamp;
    }
    
    /**
     * Setter
     * 
     * @param string $timeStamp request timestamp
     * 
     * @return void
     */
    function setTimeStamp($timeStamp)
    {
        $this->timeStamp=$timeStamp;
    }

    /**
     * Constructor
     * 
     * @param object $rqt PDO row
     */
    public function __construct($rqt=null)
    {
        if ($rqt != null) {
            $this->setMessage($rqt["message"]);
            $this->setStatus($rqt["status"]);
            $this->setId($rqt["id"]);
            $this->setServiceName($rqt["serviceName"]);
            $this->setUserName($rqt["userName"]);
            
            if ($rqt["timestamp"] != "") {
                $dt=explode(" ", $rqt["timestamp"]);
                $d=explode("-", $dt[0]);
                $t=explode(":", $dt[1]);
                $date = str_replace(" ", "T", $rqt["timestamp"]) . 
                        ".0" . 
                        @date(
                            'P',
                            @mktime($t[0], $t[1], $t[2], $d[1], $d[2], $d[0])
                        );
                $this->setTimeStamp($date);
            }
            $this->setFrontEndUri($rqt["frontEndEndPoint"]);
            $this->setUri("logs/" . $this->getId());
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
                "userName" => $this->getUsername(),
                "serviceName" => $this->getServiceName(),
                "frontEndUri" => $this->getFrontEndUri(),
                "timeStamp" => $this->getTimeStamp(),
                "status" => $this->getStatus(),
                "message" => $this->getMessage()
            );
    }
    
}
