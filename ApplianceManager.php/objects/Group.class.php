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
 * File Name   : ApplianceManager/ApplianceManager.php/objects/Group.class.php
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
 * Group class
 * 
 * PHP Version 7.0
 * 
 * @category ReverseProxy
 * @package  OSA
 * @author   Benoit HERARD <benoit.herard@orange.com>
 * @license  http://www.apache.org/licenses/LICENSE-2.0.htm Apache 2 license
 * @link     https://github.com/zorglub42/OSA/
*/
class Group extends ApplianceObject
{
    
    /**
     * Group identifier
     * 
     * @var string groupName group identifier
     */ 
    public $groupName;
    
    /**
     * Group description
     * 
     * @var string description group description
    */
    public $description;
    
    /**
     * Constructor
     * 
     * @param object $rqt PDO row
     */
    public function __construct($rqt=null)
    {
        if ($rqt != null) {
            $this->setGroupName($rqt["groupName"]);
            $this->setDescription($rqt["description"]);
            $this->setUri("groups/" . urlencode($rqt["groupName"]));
        }
    }


    /** 
     * Group name getter
     * 
     * @return string group name
     */
    function getGroupName()
    {
        return $this->groupName;
    }
    /**
     * Group name setter
     * 
     * @param string $groupName group name
     * 
     * @return void
     */
    function setGroupName($groupName)
    {
        $this->groupName=$groupName;
    }
    /** 
     * Group description getter
     * 
     * @return string group description
     */
    function getDescription()
    {
        return $this->description;
    }
    /**
     * Group description setter
     * 
     * @param string $description group description
     * 
     * @return void
     */
    function setDescription($description)
    {
        $this->description=$description;
    }

    /**
     * Convert object to associative array
     * 
     * @return array Object in a array
     */
    function toArray()
    {
        return Array("uri"  => $this->getUri(),
                      "groupName" => $this->getGroupName(),
                      "description"  => $this->getDescription()
               );
    }

}
