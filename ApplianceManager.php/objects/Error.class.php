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
 * File Name   : ApplianceManager/ApplianceManager.php/objects/Error.class.php
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

/**
 * Error object class
 * 
 * PHP Version 7.0
 * 
 * @category ReverseProxy
 * @package  OSA
 * @author   Benoit HERARD <benoit.herard@orange.com>
 * @license  http://www.apache.org/licenses/LICENSE-2.0.htm Apache 2 license
 * @link     https://github.com/zorglub42/OSA/
 */
class OSAError
{
    private $_httpStatus;
    private $_httpLabel;
    private $_functionalCode;
    private $_functionalLabel;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->setHttpStatus(200);
    }

    /**
     * HTTP Status getter
     * 
     * @return int HTTP Status
     */
    function getHttpStatus()
    {
        return $this->_httpStatus;
    }
    /**
     * HTTP Status setter
     * 
     * @param int $httpStatus HTTP STatus
     * 
     * @return void
     */
    function setHttpStatus($httpStatus)
    {
        $this->_httpStatus=$httpStatus;
    }
        
    /**
     * HTTP label getter
     * 
     * @return string HTTP label
     */
    function getHttpLabel()
    {
        return $this->_httpLabel;
    }
    /**
     * HTTP Status setter
     * 
     * @param string $httpLabel HTTP Label
     * 
     * @return void
     */
    function setHttpLabel($httpLabel)
    {
        $this->_httpLabel=$httpLabel;
    }

    
    /**
     * Functional error code getter
     * 
     * @return int Error code
     */
    function getFunctionalCode()
    {
        return $this->_functionalCode;
    }
    /**
     * Functional error code setter
     * 
     * @param int $functionalCode Error code
     * 
     * @return void
     */
    function setFunctionalCode($functionalCode)
    {
        $this->_functionalCode=$functionalCode;
    }

    /**
     * Functional error text getter
     * 
     * @return string Error text
     */
    function getFunctionalLabel()
    {
        return $this->_functionalLabel;
    }
    /**
     * Functional error text setter
     * 
     * @param string $functionalLabel Error text
     * 
     * @return void
     */
    function setFunctionalLabel($functionalLabel)
    {
        $this->_functionalLabel=$functionalLabel;
    }
    

}
