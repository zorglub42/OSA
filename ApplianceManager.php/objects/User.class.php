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
 * File Name   : ApplianceManager/ApplianceManager.php/objects/User.class.php
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
 * User class
 * 
 * PHP Version 7.0
 * 
 * @category ReverseProxy
 * @package  OSA
 * @author   Benoit HERARD <benoit.herard@orange.com>
 * @license  http://www.apache.org/licenses/LICENSE-2.0.htm Apache 2 license
 * @link     https://github.com/zorglub42/OSA/
*/
class User extends ApplianceObject
{

    //Private mebers
    /**
     * User name
     * 
     * @var string userName users's identifier {@required true}
     */
    public $userName;

    /**
     * User Password
     * 
     * @var string password users's password {@required true}
     */
    public $password;

    /**
     * Email address
     * 
     * @var string email users's email
     */
    public $email;

    /**
     * First name
     * 
     * @var string firstName email users's first name
     */
    public $firstName;

    /**
     * Last name
     * 
     * @var string lastName email users's last name
     */
    public $lastName;

    /**
     * Entity
     * 
     * @var string entity users's entity
     */
    public $entity;

    /**
     * End date
     * 
     * @var string endDate users's validity end date in ISO 8601 full format
     */
    public $endDate;

    /**
     * Additional data
     * 
     * @var string extra users's extra data in free format
     */
    public $extra;

    /**
     * Last login with a token
     * 
     * @var datetime last login with token (cookie) creation
     */
    public $lastTokenLogin;

    
    /**
     * Setter
     * 
     * @param string $firstName First name
     * 
     * @return void
     */
    function setFirstname($firstName)
    {
        $this->firstName=$firstName;
    }
    /**
     * Getter
     * 
     * @return string First name
     */
    function getFirstname()
    {
        return $this->firstName;
    }
    
    /**
     * Setter
     * 
     * @param string $lastName Last name
     * 
     * @return void
     */
    function setLastname($lastName)
    {
        $this->lastName=$lastName;
    }
    /**
     * Getter
     * 
     * @return string last name
     */
    function getLastname()
    {
        return $this->lastName;
    }
    
    /**
     * Setter
     * 
     * @param string $entity Entity
     * 
     * @return void
     */
    function setEntity($entity)
    {
        $this->entity=$entity;
    }
    /**
     * Getter
     * 
     * @return string Entity
     */
    function getEntity()
    {
        return $this->entity;
    }


    /**
     * Setter
     * 
     * @param string $extra Additional data
     * 
     * @return void
     */
    function setExtra($extra)
    {
        $this->extra=$extra;
    }
    /**
     * Getter
     * 
     * @return string Additional data
     */
    function getExtra()
    {
        return $this->extra;
    }

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
     * @param string $password Password
     * 
     * @return void
     */
    function setPassword($password)
    {
        $this->password=$password;
    }
    /**
     * Getter
     * 
     * @return string Password
     */
    function getPassword()
    {
        return $this->password;
    }


    /**
     * Setter
     * 
     * @param string $email Email address
     * 
     * @return void
     */
    function setEmail($email)
    {
        $this->email=$email;
    }
    /**
     * Getter
     * 
     * @return string Email address
     */
    function getEmail()
    {
        return $this->email;
    }
    
    /**
     * Setter
     * 
     * @param string $endDate End date (ISO format)
     * 
     * @return void
     */
    function setEndDate($endDate)
    {
        $this->endDate=$endDate;
    }
    /**
     * Getter
     * 
     * @return string End date (ISO format)
     */
    function getEndDate()
    {
        return $this->endDate;
    }
    
    /**
     * Setter
     * 
     * @param string $lastTokenLogin Last login with a cookie/token (ISO date format)
     * 
     * @return void
     */
    function setLastTokenLogin($lastTokenLogin)
    {
        $this->lastTokenLogin=$lastTokenLogin;
    }
    /**
     * Getter
     * 
     * @return string Last login with cookie (ISO format date)
     */
    function getLastTokenLogin()
    {
        return $this->lastTokenLogin;
    }


    /**
     * Constructor
     * 
     * @param object $rqt PDO row
     */
    public function __construct($rqt=null)
    {
        if ($rqt != null) {
            $this->setUsername($rqt["userName"]);
            $this->setPassword(decrypt($rqt["password"]));
            $this->setEmail($rqt["emailAddress"]);
            $this->setFirstname($rqt["firstName"]);
            $this->setLastname($rqt["lastName"]);
            $this->setEntity($rqt["entity"]);
            $this->setExtra($rqt["extra"]);
            $dt=explode(" ", $rqt["endDate"]);
            $d=explode("-", $dt[0]);
            $t=explode(":", $dt[1]);
            $date = str_replace(" ", "T", $rqt["endDate"]) .
                    ".0" . 
                    @date(
                        'P',
                        @mktime($t[0], $t[1], $t[2], $d[1], $d[2], $d[0])
                    );
            $this->setEndDate($date);
            if (!empty($rqt["lastTokenLogin"])) {
                $dt=explode(" ", $rqt["lastTokenLogin"]);
                $d=explode("-", $dt[0]);
                $t=explode(":", $dt[1]);
                $date = str_replace(" ", "T", $rqt["lastTokenLogin"]) .
                        ".0" .
                        @date(
                            'P',
                            @mktime($t[0], $t[1], $t[2], $d[1], $d[2], $d[0])
                        );
                $this->setLastTokenLogin($date);
            }
            $this->setUri("users/" . urlencode($rqt["userName"]));
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
                "userName"  => $this->getUsername(),
                "password"  => $this->getPassword(),
                "firstName"  => $this->getFirstname(),
                "lastName"  => $this->getLastname(),
                "entity"  => $this->getEntity(),
                "emailAddress"  => $this->getEmail(),
                "endDate"  => $this->getEndDate(),
                "extra"  => $this->getExtra(),
                "lastTokenLogin"  => $this->getLastTokenLogin(),
            );
    }
                
}
