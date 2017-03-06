<?php
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

class Counter extends ApplianceObject{
	/**
	 * @var string counter name
	 */
	public $counterName;
	/**
	 * @var string controled resource (service)
	 */
	public $resourceName;
	/**
	 * @var string time unit for this counter (S: second, D: day, M: Month) {@choice S,D,M}
	 */
	public $timeUnit;
	/**
	 * @var string timeValue reference time for tis counter
	 */
	public $timeValue;
	/**
	 * @var int value counter value
	 */
	public $value;
	/**
	 * @var string userName relative user
	 */
	public $userName;
	
	
	function setUserName($userName){
		$this->userName=$userName;
	}
	function getUserName(){
		return $this->userName;
	}
	
	
	
	
	function setResourceName($resourceName){
		$this->resourceName=$resourceName;
	}
	function getResourceName(){
		return $this->resourceName;
	}
	
	function setTimeUnit($timeUnit){
		$this->timeUnit=$timeUnit;
	}
	function getTimeUnit(){
		return $this->timeUnit;
	}
	
	function setTimeValue($timeValue){
		$this->timeValue=$timeValue;
	}
	function getTimeValue(){
		return $this->timeValue;
	}
	
	function setValue($value){
		$this->value=$value;
	}
	function getValue(){
		return $this->value;
	}


	function setCounterName($counterName){
		$this->counterName=$counterName;
	}
	function getCounterName(){
		return $this->counterName;
	}
	public function toArray(){
		return Array(
			"uri" => $this->getUri(),
			"counterName" => $this->getCounterName(),
			"userName" => $this->getUsername()==NULL?"":$this->getUsername(),
			"resourceName" => $this->getResourceName(),
			"timeUnit" => $this->getTimeUnit(),
			"timeValue" => $this->getTimeValue(),
			"value" => $this->getValue()
		);
	}
	
	
    public function __construct($rqt=NULL)
    {
		if ($rqt != NULL){
			$this->setValue($rqt["value"]);		
			$this->setUri( "counters/" . urlencode($rqt["counterName"]));
			$this->setCounterName( $rqt["counterName"]);
			$counterName=($rqt["counterName"]);

			$cnPart=explode("$$$", $counterName);
			
			if (count($cnPart)>2){
				//Per user counter
				$res=explode("=", $cnPart[0]);
				$this->setResourceName($res[1]);
				$res=explode("=", $cnPart[1]);
				$this->setUserName($res[1]);
				$res=explode("=", $cnPart[2]);
				$this->setTimeValue($res[1]);
				$this->setTimeUnit($res[0]);
			}else{
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
