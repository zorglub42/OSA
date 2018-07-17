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
 * File Name   : ApplianceManager/ApplianceManager.php/objects/Service.class.php
 *
 * Created     : 2012-02
 * Authors     : Benoit HERARD <benoit.herard(at)orange.com>
 *
 * Description :
 *      .../...
 *--------------------------------------------------------
 * History     :
 * 1.0.0 - 2018-07-17 : Release of the file
*/
require_once "../include/Func.inc.php";
require_once '../objects/ApplianceObject.class.php';

/**
 * Session Class
 * 
 * PHP Version 7.0
 * 
 * @category ReverseProxy
 * @package  OSA
 * @author   Benoit HERARD <benoit.herard@orange.com>
 * @license  http://www.apache.org/licenses/LICENSE-2.0.htm Apache 2 license
 * @link     https://github.com/zorglub42/OSA/
*/
class Session extends ApplianceObject
{

    /**
     * Session identifier
     * 
     * @var string id session identifier
     **/
    public $id;

    /**
     * User name identifier
     * 
     * @var string userName Session owner
     */
    public $userName;
    

    /**
     * End date validtity
     * 
     * @var string validUntil Date on witch Session will timeout
     */
    public $validUntil;


    /**
     * Setter
     * 
     * @param string $username Username (id)
     * 
     * @return void
     */
    function setUsername($username)
    {
        $this->userName=$username;
    }
    /**
     * Getter
     * 
     * @return string Username (id)
     */
    function getUsername()
    {
        return $this->userName;
    }

    /**
     * Setter
     * 
     * @param string $validUntil End date (ISO format)
     * 
     * @return void
     */
    function setValidUntil($validUntil)
    {
        $this->validUntil=$validUntil;
    }
    /**
     * Getter
     * 
     * @return string datetime validity end (ISO format)
     */
    function getValidUntil()
    {
        return $this->validUntil;
    }

    /**
     * Setter
     * 
     * @param string $id Session identifier
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
     * @return string session identifier
     */
    function getId()
    {
        return $this->id;
    }


    /**
     * Constructor
     * 
     * @param object $rqt PDO row
     */
    public function __construct($rqt=null)
    {
        if ($rqt != null) {
            $this->setUserName($rqt["userName"]);
            $this->setUri("auth/sessions/" . urlencode($rqt["initialToken"]));
            $this->setId($rqt["initialToken"]);

            $dt=explode(" ", $rqt["validUntil"]);
            $d=explode("-", $dt[0]);
            $t=explode(":", $dt[1]);
            $date = str_replace(" ", "T", $rqt["validUntil"]) .
                    ".0" . 
                    @date(
                        'P',
                        @mktime($t[0], $t[1], $t[2], $d[1], $d[2], $d[0])
                    );
            $this->setValidUntil($date);
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
            "uri" => $this->getUri() ,
            "id" => $this->getId() ,
            "userName" => $this->getUserName() ,
            "validUntil" => $this->getValidUntil(),
        );
    }

}
