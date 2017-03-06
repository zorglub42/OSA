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

class Group extends ApplianceObject{
	
	/**
	 * @var string groupName group identifier
	 */ 
	public $groupName;
	
	/**
	 * @var string description group description
	*/
	public $description;
	
    public function __construct($rqt=NULL)
    {
		if ($rqt != NULL ){
			$this->setGroupName($rqt["groupName"]);		
			$this->setDescription($rqt["description"]);		
			$this->setUri("groups/" . urlencode($rqt["groupName"]));
		}
	}


		
	function getGroupName(){
		return $this->groupName;
	}
	function setGroupName($groupName){
		$this->groupName=$groupName;
	}
		
	function getDescription(){
		return $this->description;
	}
	function setDescription($description){
		$this->description=$description;
	}

	function toArray(){
		return 	Array("uri"  => $this->getUri(),
					  "groupName" => $this->getGroupName(),
					  "description"  => $this->getDescription()
				);
	}

}
