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
 * File Name   : ApplianceManager/ApplianceManager.php/objects/Header.class.php
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
 * Header mapping class for creation
 * 
 * PHP Version 7.0
 * 
 * @category ReverseProxy
 * @package  OSA
 * @author   Benoit HERARD <benoit.herard@orange.com>
 * @license  http://www.apache.org/licenses/LICENSE-2.0.htm Apache 2 license
 * @link     https://github.com/zorglub42/OSA/
 */
class HeaderMappingCreation
{
    /**
     * Header name
     * 
     * @var string headerName HTTP Header name
     */
    public $headerName;
    /**
     * User property
     * 
     * @var string userProperty corresponding user property
     */
    public $userProperty;

    /**
     * Extended attribute
     * 
     * @var int extendedAttribute (0->basic user property, 1->extended property)
     */
    public $extendedAttribute;
}
/**
 * Header mapping class
 * 
 * PHP Version 7.0
 * 
 * @category ReverseProxy
 * @package  OSA
 * @author   Benoit HERARD <benoit.herard@orange.com>
 * @license  http://www.apache.org/licenses/LICENSE-2.0.htm Apache 2 license
 * @link     https://github.com/zorglub42/OSA/
 */
class HeaderMapping extends ApplianceObject
{

    /**
     * Service name
     * 
     * @var string serviceName Service identifier
     */
    public $serviceName;
    /**
     * Header name
     * 
     * @var string headerName HTTP Header name
     */
    public $headerName;
    /**
     * User property
     * 
     * @var string userProperty corresponding user property
     */
    public $userProperty;

    /**
     * Extended attribute
     * 
     * @var int extendtedAttribute (0->basic user property, 1->extended property)
     */
    public $extendedAttribute;

    /**
     * Servicename setter
     * 
     * @param string $serviceName servic ename
     * 
     * @return void
     */
    function setServicename($serviceName)
    {
        $this->serviceName=$serviceName;
    }
    /**
     * Service name getter
     * 
     * @return string service name
     */
    function getServicename()
    {
        return $this->serviceName;
    }
    
    /**
     * Header name setter
     * 
     * @param string $headerName Header name
     * 
     * @return void
     */
    function setHeadername($headerName)
    {
        $this->headerName=$headerName;
    }
    /**
     * Header name getter
     * 
     * @return string header name
     */
    function getHeadername()
    {
        return $this->headerName;
    }
    
    /**
     * User property setter
     * 
     * @param string $userProperty user proprety
     * 
     * @return void
     */
    function setUserProperty($userProperty)
    {
        $this->userProperty=$userProperty;
    }
    /**
     * User property getter
     * 
     * @return string User property
     */
    function getUserProperty()
    {
        return $this->userProperty;
    }


    /**
     * Extended attribute setter
     * 
     * @param int $extendedAttribute Is it an extended user proprety
     * 
     * @return void
     */
    function setExtendedAttribute($extendedAttribute)
    {
        $this->extendedAttribute=$extendedAttribute;
    }
    /**
     * Extended attribute getter
     * 
     * @return int Is it and extended user property
     */
    function getExtendedAttribute()
    {
        return $this->extendedAttribute;
    }

    /**
     * Constructor
     * 
     * @param object $rqt PDO row
     */
    public function __construct($rqt=null)
    {
        if ($rqt != null) {
            $this->setServicename($rqt["serviceName"]);
            $this->setHeadername($rqt["headerName"]);
            $this->setUserProperty($rqt["columnName"]);
            $this->setUri(
                "services/" . 
                urlencode($rqt["serviceName"]) . 
                "/headers-mapping/" . 
                urlencode($rqt["columnName"])
            );
            $this->setExtendedAttribute($rqt["extendedAttribute"]);
        }
    }
    
    /**
     * Convert object to associative array
     * 
     * @return array Object in a array
     */
    function toArray()
    {
        return Array(
                "uri"  => $this->getUri(),
                "serviceName"  => $this->getServicename(),
                "headerName"  => $this->getHeadername(),
                "userProperty"  => $this->getUserProperty(),
                "extendedAttribute"  => $this->getExtendedAttribute(),
            );
    }
                
}
