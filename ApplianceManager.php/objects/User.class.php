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

class User extends ApplianceObject{

	//Private mebers
	/**
	 * @var string userName users's identifier {@required true}
	 */
	public $userName;

	/**
	 * @var string password users's password {@required true}
	 */
	public $password;

	/**
	 * @var email email users's email
	 */
	public $email;

	/**
	 * @var string firstName email users's first name
	 */
	public $firstName;

	/**
	 * @var string lastName email users's last name
	 */
	public $lastName;

	/**
	 * @var string entity users's entity
	 */
	public $entity;

	/**
	 * @var string endDate users's validity end date in ISO 8601 full format
	 */
	public $endDate;

	/**
	 * @var string extra users's extra data in free format
	 */
	public $extra;

	
	function setFirstname($firstName){
		$this->firstName=$firstName;
	}
	function getFirstname(){
		return $this->firstName;
	}
	
	function setLastname($lastName){
		$this->lastName=$lastName;
	}
	function getLastname(){
		return $this->lastName;
	}
	
	function setEntity($entity){
		$this->entity=$entity;
	}
	function getEntity(){
		return $this->entity;
	}
	function setExtra($extra){
		$this->extra=$extra;
	}
	function getExtra(){
		return $this->extra;
	}
	
	
	
	
	function setUsername($username){
		$this->userName=$username;
	}
	function getUsername(){
		return $this->userName;
	}
	
	
	
	
	
	function setPassword($password){
		$this->password=$password;
	}
	function getPassword(){
		return $this->password;
	}


	function setEmail($email){
		$this->email=$email;
	}
	function getEmail(){
		return $this->email;
	}
	
	function setEndDate($endDate){
		$this->endDate=$endDate;
	}
	function getEndDate(){
		return $this->endDate;
	}


    public function __construct($rqt=NULL)
    {
		if ($rqt != NULL){
			$this->setUsername($rqt["userName"]);		
			$this->setPassword(decrypt($rqt["password"]));		
			$this->setEmail($rqt["emailAddress"]);
			$this->setFirstname($rqt["firstName"]);
			$this->setLastname($rqt["lastName"]);
			$this->setEntity($rqt["entity"]);
			$this->setExtra($rqt["extra"]);
			$dt=explode(" ",$rqt["endDate"]);
			$d=explode("-",$dt[0]);
			$t=explode(":",$dt[1]);
			$date = str_replace(" ","T", $rqt["endDate"]) . ".0" . @date('P', @mktime($t[0],$t[1],$t[2],$d[1],$d[2],$d[0])) ;
			$this->setEndDate($date);
			$this->setUri( "users/" . urlencode($rqt["userName"]));
		}
	}
	
	function toArray(){
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
			);
	}
				
}
