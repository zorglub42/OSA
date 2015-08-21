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
 * File Name   : ApplianceManager/ApplianceManager.php/objects/Quota.class.php
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

class Quota extends ApplianceObject{
	private $reqSec=0;
	private $reqDay=0;
	private $reqMonth=0;
	private $serviceName;
	private $serviceUri;
	private $userName;
	private $userUri;
	
	
	
	function getServiceName(){
		return $this->serviceName;
	}
	function setServiceName($serviceName){
		$this->serviceName=$serviceName;
	}
		
	function getReqSec(){
		return $this->reqSec;
	}
	function setReqSec($reqSec){
		$this->reqSec=$reqSec;
	}

		
	function getReqDay(){
		return $this->reqDay;
	}
	function setReqDay($reqDay){
		$this->reqDay=$reqDay;
	}

		
	function getReqMonth(){
		return $this->reqMonth;
	}
	function setReqMonth($reqMonth){
		$this->reqMonth=$reqMonth;
	}
	
		
	function getUserUri(){
		return $this->userUri;
	}
	function setUserUri($userUri){
		$this->userUri=$this->getPublicUriPrefix()  . $userUri;
	}
		
			
	function getServiceUri(){
		return $this->serviceUri;
	}
	function setServiceUri($serviceUri){
		$this->serviceUri=$this->getPublicUriPrefix()  . $serviceUri;
	}

			
	function getUserName(){
		return $this->userName;
	}
	function setUserName($userName){
		$this->userName=$userName;
	}
	
	
    public function __construct($rqt=NULL)
    {
		if ($rqt != NULL){
			$this->setUri("services/" . urlencode($rqt["serviceName"]) . "/quotas/" . urlencode($rqt["userName"]));
			$this->setUserName($rqt["userName"]);
			$this->setUserUri("users/" . urlencode($rqt["userName"]));
			$this->setServiceName($rqt["serviceName"]);		
			$this->setServiceUri("services/" . urlencode($rqt["serviceName"]));		
			$this->setReqSec($rqt["reqSec"]);
			$this->setReqDay($rqt["reqDay"]);
			$this->setReqMonth($rqt["reqMonth"]);
		}
	}
	
	public function toArray(){
		return Array(
				"uri" => $this->getUri(),
				"serviceName" => $this->getServiceName(),
				"serviceUri" => $this->getServiceUri(),
				"userName" => $this->getUserName(),
				"userUri" => $this->getUserUri(),
				"reqSec" => $this->getReqSec(),
				"reqDay" => $this->getReqDay(),
				"reqMonth" => $this->getReqMonth() 
			);
		
	}
}
