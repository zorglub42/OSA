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
 * File Name   : ApplianceManager/ApplianceManager.php/objects/ApplianceObject.class.php
 *
 * Created     : 2012-02
 * Authors     : Benoit HERARD <benoit.herard(at)orange.com>
 *
 * Description :
 *      Base class for all business object of datamodel
 *--------------------------------------------------------
 * History     :
 * 1.0.0 - 2012-10-01 : Release of the file
*/

require_once '../include/Settings.ini.php';

 class ApplianceObject{

	/**
	 * @var url uri
	 */
	public $uri;
	//Private members
	private $publicUriPrefix="";

	public function getPublicUriPrefix(){
		if ($this->publicUriPrefix==""){
			$hdrs=getallheaders();
			if (isset($hdrs[uriPrefixHeader]) && $hdrs[uriPrefixHeader] != ""){
				$publicUriPrefix=$hdrs[uriPrefixHeader] . "/";
			}else{
				$publicUriPrefix=defaultUriPrefix ;
			}
				
		}
		return $publicUriPrefix;
	}
	
	function setUri($uri){
		
		$this->uri=$this->getPublicUriPrefix()  . $uri;
	}
	function getUri(){
		return  $this->uri;
	}
	
	
	
}
