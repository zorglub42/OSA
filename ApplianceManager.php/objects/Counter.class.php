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
 * File Name   : ApplianceManager/ApplianceManager.php/objects/Counter.class.php
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
 * Counters class
 * 
 * PHP Version 7.0
 * 
 * @category ReverseProxy
 * @package  OSA
 * @author   Benoit HERARD <benoit.herard@orange.com>
 * @license  http://www.apache.org/licenses/LICENSE-2.0.htm Apache 2 license
 * @link     https://github.com/zorglub42/OSA/
 */
class Counter extends ApplianceObject
{
    /**
     * Counter name
     * 
     * @var string counter name
     */
    public $counterName;
    /**
     * Controled resource (service)
     * 
     * @var string controled resource (service)
     */
    public $resourceName;
    /**
     * Time unit
     * 
     * @var string time unit for this counter 
     *             (S: second, D: day, M: Month) {@choice S,D,M}
     */
    public $timeUnit;
    /**
     * Time value
     * 
     * @var string timeValue reference time for tis counter
     */
    public $timeValue;
    /**
     * Counter value
     * 
     * @var int value counter value
     */
    public $value;
    /**
     * User name
     *
     * @var string userName relative user
     */
    public $userName;
    
    /**
     * Username setter
     * 
     * @param string $userName User name (id)
     * 
     * @return void
     */
    function setUserName($userName)
    {
        $this->userName=$userName;
    }
    /**
     * Username getter
     * 
     * @return string username
     */
    function getUserName()
    {
        return $this->userName;
    }
    
    
    
    /**
     * Resourcename setter
     * 
     * @param string $resourceName resource ID
     * 
     * @return void
     */
    function setResourceName($resourceName)
    {
        $this->resourceName=$resourceName;
    }
    /**
     * Resource name getter
     * 
     * @return string resource name
     */
    function getResourceName()
    {
        return $this->resourceName;
    }
    
    /**
     * Time uni setter
     * 
     * @param string $timeUnit time unit
     * 
     * @return void
     */
    function setTimeUnit($timeUnit)
    {
        $this->timeUnit=$timeUnit;
    }
    
    /**
     * Time unit getter
     * 
     * @return string time unit
     */
    function getTimeUnit()
    {
        return $this->timeUnit;
    }
    
    
    /**
     * Time value stetter
     * 
     * @param string $timeValue Time value
     * 
     * @return void
     */
    function setTimeValue($timeValue)
    {
        $this->timeValue=$timeValue;
    }

    /**
     * Time value getter
     * 
     * @return string time value
     */
    function getTimeValue()
    {
        return $this->timeValue;
    }
    
    /**
     * Value setter
     * 
     * @param string $value Value
     * 
     * @return void
     */
    function setValue($value)
    {
        $this->value=$value;
    }
    /**
     * Value getter
     * 
     * @return string value
     */
    function getValue()
    {
        return $this->value;
    }

    /**
     * Counter name setter
     * 
     * @param string $counterName Counter name
     * 
     * @return void
     */
    function setCounterName($counterName)
    {
        $this->counterName=$counterName;
    }

    /**
     * Counter name getter
     * 
     * @return string counter name
     */
    function getCounterName()
    {
        return $this->counterName;
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
            "counterName" => $this->getCounterName(),
            "userName" => $this->getUsername()==null?"":$this->getUsername(),
            "resourceName" => $this->getResourceName(),
            "timeUnit" => $this->getTimeUnit(),
            "timeValue" => $this->getTimeValue(),
            "value" => $this->getValue()
        );
    }
    
    /**
     * Constructor
     * 
     * @param object $rqt PDO row
     * 
     * @return Counter
     */
    public function __construct($rqt=null)
    {
        if ($rqt != null) {
            $this->setValue($rqt["value"]);
            $this->setUri("counters/" . urlencode($rqt["counterName"]));
            $this->setCounterName($rqt["counterName"]);
            $counterName=($rqt["counterName"]);

            $cnPart=explode("$$$", $counterName);
            
            if (count($cnPart)>2) {
                //Per user counter
                $res=explode("=", $cnPart[0]);
                $this->setResourceName($res[1]);
                $res=explode("=", $cnPart[1]);
                $this->setUserName($res[1]);
                $res=explode("=", $cnPart[2]);
                $this->setTimeValue($res[1]);
                $this->setTimeUnit($res[0]);
            } else {
                // Per resource counter
                $res=explode("=", $cnPart[0]);
                $this->setResourceName($res[1]);
                $res=explode("=", $cnPart[1]);
                $this->setTimeValue($res[1]);
                $this->setTimeUnit($res[0]);
            }
            
        }
    }

}


?>
