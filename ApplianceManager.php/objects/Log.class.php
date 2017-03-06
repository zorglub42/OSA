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
 * File Name   : ApplianceManager/ApplianceManager.php/objects/Log.class.php
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

class Log extends ApplianceObject{
	/**
	 * @var int id log identifier
	 */	
	public $id;
	/**
	 * @var string message message Logged message
	 */
	public $message;
	/**
	 * @var uri front end uri invoked
	 */
	public $frontEndUri;
	/**
	 * @var int status HTTP Response status
	 */
	public $status;
	/**
	 * @var string serviceName service invoked
	 */
	public $serviceName;
	/**
	 * @var string userName [optional] authentifed user
	 */
	public $userName;
	/**
	 * @var string timeStamp hit date in ISO 8601 full format
	 */
	public $timeStamp;
	

	function setId($id){
		$this->id=$id;
	}
	function getId(){
		return $this->id;
	}
	
	
	function getServiceName(){
		return $this->serviceName;
	}
	function setServiceName($serviceName){
		$this->serviceName=$serviceName;
	}
		
	
		
	function getUserUri(){
		return $this->getPublicUriPrefix() . "users/" . urlencode($this->userName);
	}
		
			
	function getServiceUri(){
		return "services/" . urlencode($this->serviceName);
	}

			
	function getUserName(){
		return $this->userName;
	}
	function setUserName($userName){
		$this->userName=$userName;
	}
	
	function getFrontEndUri(){
		return $this->frontEndUri;
	}
	function setFrontEndUri($frontEndUri){
		$this->frontEndUri=$frontEndUri;
	}
	function getStatus(){
		return $this->status;
	}
	function setStatus($status){
		$this->status=$status;
	}
	function getMessage(){
		return $this->message;
	}
	function setMessage($message){
		$this->message=$message;
	}
	function getTimeStamp(){
		return $this->timeStamp;
	}
	function setTimeStamp($timeStamp){
		$this->timeStamp=$timeStamp;
	}


    public function __construct($rqt=NULL)
    {
		if ($rqt != NULL){
			$this->setMessage($rqt["message"]);
			$this->setStatus($rqt["status"]);
			$this->setId($rqt["id"]);
			$this->setServiceName($rqt["serviceName"]);
			$this->setUserName($rqt["userName"]);
			
			if ($rqt["timestamp"] != ""){
				$dt=explode(" ",$rqt["timestamp"]);
				$d=explode("-",$dt[0]);
				$t=explode(":",$dt[1]);
				$date = str_replace(" ","T", $rqt["timestamp"]) . ".0" . @date('P', @mktime($t[0],$t[1],$t[2],$d[1],$d[2],$d[0])) ;
				$this->setTimeStamp($date);
			}
			$this->setFrontEndUri($rqt["frontEndEndPoint"]);
			$this->setUri("logs/" . $this->getId());
		}
	}
	
	public function toArray(){
		return Array(
				"uri" => $this->getUri(),
				"userName" => $this->getUsername(),
				"serviceName" => $this->getServiceName(),
				"frontEndUri" => $this->getFrontEndUri(),
				"timeStamp" => $this->getTimeStamp(),
				"status" => $this->getStatus(),
				"message" => $this->getMessage()
			);
	}
	
}
