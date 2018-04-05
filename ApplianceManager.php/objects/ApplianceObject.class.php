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
 * File Name   : 
 *  ApplianceManager/ApplianceManager.php/objects/ApplianceObject.class.php
 *
 * Created     : 2012-02
 * Authors     : Benoit HERARD <benoit.herard(at)orange.com>
 *
 * Description :
 *      Base class for all business object of datamodel
 *--------------------------------------------------------
 * History     :
 * 1.0.0 - 2012-10-01 : Release of the file
*/

require_once '../include/Settings.ini.php';

/**
 * Base class for OSA Objects
 * 
 * PHP Version 7.0
 * 
 * @category ReverseProxy
 * @package  OSA
 * @author   Benoit HERARD <benoit.herard@orange.com>
 * @license  http://www.apache.org/licenses/LICENSE-2.0.htm Apache 2 license
 * @link     https://github.com/zorglub42/OSA/
*/
class ApplianceObject
{

    /**
     * Object URI
     * 
     * @var url uri
     */
    public $uri;

    //Private members
    private $_publicUriPrefix="";

    /**
     * Get for object public URI
     * 
     * @return uri object uri
     */
    public function getPublicUriPrefix()
    {
        if ($this->_publicUriPrefix=="") {
            $hdrs=getallheaders();
            if (isset($hdrs[uriPrefixHeader]) 
                && $hdrs[uriPrefixHeader] != ""
            ) {
                $publicUriPrefix=$hdrs[uriPrefixHeader] . "/";
            } else {
                $publicUriPrefix=defaultUriPrefix ;
            }
                
        }
        return $publicUriPrefix;
    }
    
    /**
     * Setter for object URI
     * 
     * @param uri $uri Object URI
     * 
     * @return void
    */
    function setUri($uri)
    {
        
        $this->uri=$this->getPublicUriPrefix()  . $uri;
    }
    
    /**
     * Object URI getter
     * 
     * @return uri
    */
    function getUri()
    {
        return  $this->uri;
    }
    
    
    
}
