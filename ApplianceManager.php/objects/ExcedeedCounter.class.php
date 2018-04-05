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
 *  ApplianceManager/ApplianceManager.php/objects/ExcedeedCounter.class.php
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

require_once '../objects/Counter.class.php';

/**
 * Ecceeeded counters class
 * 
 * PHP Version 7.0
 * 
 * @category ReverseProxy
 * @package  OSA
 * @author   Benoit HERARD <benoit.herard@orange.com>
 * @license  http://www.apache.org/licenses/LICENSE-2.0.htm Apache 2 license
 * @link     https://github.com/zorglub42/OSA/
 */
class ExcedeedCounter extends Counter
{

    /**
     * Maximum allowed value
     * 
     * @param int maxValue Maximum allowed value
     */
    public $maxValue;
    
    /**
     * Max value getter
     * 
     * @return int Maximum allowed value
     */
    public function getMaxValue()
    {
        return $this->maxValue;
    }
    /**
     * Max value setter
     * 
     * @param int $maxValue MAximum value
     * 
     * @return void
    */
    public function setMaxValue($maxValue)
    {
        $this->maxValue=$maxValue;
    }
    
    /**
     * Convert ExcedeedCounter in an associative array
     * 
     * @return array object converted
     */
    public function toArray()
    {
        $rc = parent::toArray();
        $rc["maxValue"]=$this->getMaxValue();
        return $rc;
    }
    
    /**
     * Constructor
     * 
     * @param object $rqt PDO Row
     */
    public function __construct($rqt=null)
    {
        if ($rqt != null) {
            parent::__construct($rqt);
    
            if ($this->getTimeUnit() == 'S') {
                $this->setMaxValue($rqt["reqSec"]);
            } elseif ($this->getTimeUnit() == 'D') {
                $this->setMaxValue($rqt["reqDay"]);
            } elseif ($this->getTimeUnit() == 'M') {
                $this->setMaxValue($rqt["reqMonth"]);
            }
        }
    }

}
?>
