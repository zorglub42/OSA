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
 * File Name   : ApplianceManager/ApplianceManager.php/objects/Header.class.php
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

class HeaderMappingCreation{
	/**
	 * @var string headerName HTTP Header name
	 */
	public $headerName;
	/**
	 * @var string userProperty corresponding user property
	 */
	public $userProperty;
}
class HeaderMapping extends ApplianceObject{

	/**
	 * @var string serviceName Service identifier
	 */
	public $serviceName;
	/**
	 * @var string headerName HTTP Header name
	 */
	public $headerName;
	/**
	 * @var string userProperty corresponding user property
	 */
	public $userProperty;

	
	function setServicename($serviceName){
		$this->serviceName=$serviceName;
	}
	function getServicename(){
		return $this->serviceName;
	}
	
	function setHeadername($headerName){
		$this->headerName=$headerName;
	}
	function getHeadername(){
		return $this->headerName;
	}
	
	function setUserProperty($userProperty){
		$this->userProperty=$userProperty;
	}
	function getUserProperty(){
		return $this->userProperty;
	}


    public function __construct($rqt=NULL)
    {
		if ($rqt != NULL){
			$this->setServicename($rqt["serviceName"]);
			$this->setHeadername($rqt["headerName"]);
			$this->setUserProperty($rqt["columnName"]);
			$this->setUri( "services/" . urlencode($rqt["serviceName"]) . "/headers-mapping/" . urlencode($rqt["columnName"]));
		}
	}
	
	function toArray(){
		return Array(
				"uri"  => $this->getUri(),
				"serviceName"  => $this->getServicename(),
				"headerName"  => $this->getHeadername(),
				"userProperty"  => $this->getUserProperty(),
			);
	}
				
}
