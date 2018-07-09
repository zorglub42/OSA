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
 * UserProperty class
 * 
 * PHP Version 7.0
 * 
 * @category ReverseProxy
 * @package  OSA
 * @author   Benoit HERARD <benoit.herard@orange.com>
 * @license  http://www.apache.org/licenses/LICENSE-2.0.htm Apache 2 license
 * @link     https://github.com/zorglub42/OSA/
*/
class UserProperty
{
    /**
     * Property name
     * 
     * @var string property name
     *
     */
    public $name;

    /**
     * Property value
     * 
     * @var string property value
     *
     */
    public $value;

    /**
     * Constructor
     * 
     * @param object $rqt PDO row
     */
    public function __construct($rqt=null)
    {
        if ($rqt != null) {
            $this->name=$rqt["propertyName"];
            $this->value=$rqt["value"];

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
                "name"  => $this->name,
                "value"  => $this->value,
        );
    }
}


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
     * Last login with a token
     * 
     * @var datetime last login with token (cookie) creation
     */
    public $lastTokenLogin;

    

    /**
     * Additional properties list
     * 
     * @var array Array of additionnal properties {@type UserProperty}
     */
    public $properties=array();

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
     * @param array $properties user's properties {@type UserProperty} 
     * 
     * @return void
     */
    function setProperties($properties)
    {
        $this->properties=$properties;
    }
    /**
     * Getter
     * 
     * @return array user 's properties {@type UserProperty} 
     */
    function getProperties()
    {
        return $this->properties;
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
            if (!empty($rqt["endDate"])) {
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
            }
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
        $properties=array();
        foreach ($this->properties as $p){
            array_push($properties, $p->toArray());
        }
        return Array(
                "uri"  => $this->getUri(),
                "userName"  => $this->getUsername(),
                "password"  => $this->getPassword(),
                "firstName"  => $this->getFirstname(),
                "lastName"  => $this->getLastname(),
                "entity"  => $this->getEntity(),
                "emailAddress"  => $this->getEmail(),
                "endDate"  => $this->getEndDate(),
                "lastTokenLogin"  => $this->getLastTokenLogin(),
                "properties"  => $properties,
            );
    }
                
}
