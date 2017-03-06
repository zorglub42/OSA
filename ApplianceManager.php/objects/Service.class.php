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
 * File Name   : ApplianceManager/ApplianceManager.php/objects/Service.class.php
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
require_once "../include/Func.inc.php";
require_once '../objects/ApplianceObject.class.php';

class Service extends ApplianceObject{

	/**
	 * @var string serviceName service identifier
	 **/
	public $serviceName;
	/**
	 * @var string groupName Users have to be member of this group to use this service
	 */
	public $groupName;
	/**
	 * @var int reqSec maximun number of request allowed per seconds
	 */
	public $reqSec;
	/**
	 * @var int reqSec maximun number of request allowed per days
	 */
	public $reqDay;
	/**
	 * @var int reqSec maximun number of request allowed per months
	 */
	public $reqMonth;
	/**
	 * @var int isGlobalQuotasEnabled Is there global quotas management on this service? (O: no, 1: yes) {@choice 0,1}
	 */
	public $isGlobalQuotasEnabled;
	/**
	 * @var int isGlobalQuotasEnabled Is there quotas management at user level on this service? (O: no, 1: yes) {@choice 0,1}
	 */
	public $isUserQuotasEnabled;
	/**
	 * @var int isIdentityForwardingEnabled Authenticated user identity is forwarded to backend? (O: no, 1: yes) {@choice 0,1}
	 */
	public $isIdentityForwardingEnabled;
	/**
	 * @var int isPublished Is this server currently available on nodes? (O: no, 1: yes) {@choice 0,1}
	 */
	public $isPublished;
	/**
	 * @var url frontEndEndPoint URI on frontend node
	 */
	public $frontEndEndPoint;
	/**
	 * @var url backEndEndPoint URL to backend server
	 */
	public $backEndEndPoint;
	/**
	 * @var string username to authenticate against backend server (basic auth)
	 */
	public $backEndUserName;
	/**
	 * @var string password to authenticate against backend server (basic auth)
	 */
	public $backEndPassword;
	/**
	 * @var int isUserAuthenticationEnabled Is authentication enabled for this service? (O: no, 1: yes) {@choice 0,1}
	 */
	public $isUserAuthenticationEnabled;
	/**
	 * @var int isHitLoggingEnabled IS log recording activiated for this service? (O: no, 1: yes) {@choice 0,1}
	 */
	public $isHitLoggingEnabled;
	/**
	 * @var string additionalConfiguration Additionnal Apache configuration directive (for "Location" tag)
	 */
	public $additionalConfiguration;
	/**
	 * @var int isHitLoggingEnabled Is this service available for all running nodes? (O: no, 1: yes) {@choice 0,1}
	 */
	public $onAllNodes;
	/**
	 * @var int isAnonymousAllowed Is authentication absolutly required to invoke this service or anonymous access is also possible? (O: no, 1: yes) {@choice 0,1}
	 */
	public $isAnonymousAllowed;
	/**
	 * @var url loginFormUri Login form url to redirect to for unauthenticated access
	 */
	public $loginFormUri;
	
	
	
	public function getIsAnonymousAllowed(){
		return $this->isAnonymousAllowed;
	}
	public function setIsAnonymousAllowed($isAnonymousAllowed){
		$this->isAnonymousAllowed=$isAnonymousAllowed;
	}
	
	public function getIsUserAuthenticationEnabled(){
		return $this->isUserAuthenticationEnabled;
	}
	public function setIsUserAuthenticationEnabled($isUserAuthenticationEnabled){
		$this->isUserAuthenticationEnabled=$isUserAuthenticationEnabled;
	}
	public function getIsHitLoggingEnabled(){
		return $this->isHitLoggingEnabled;
	}
	public function setIsHitLoggingEnabled($isHitLoggingEnabled){
		$this->isHitLoggingEnabled=$isHitLoggingEnabled;
	}
	function getGroupUri(){
		return $this->getPublicUriPrefix() . "groups/" . $this->getGroupName();
	}
	function setGroupName($groupName){
		$this->groupName=$groupName;
	}
	function getGroupName(){
		return $this->groupName;
	}
	
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
	
		
	function getFrontEndEndPoint(){
		return $this->frontEndEndPoint;
	}
	function setFrontEndEndPoint($frontEndEndPoint){
		$this->frontEndEndPoint=$frontEndEndPoint;
	}
		
	function getBackEndEndPoint(){
		return $this->backEndEndPoint;
	}
	function setBackEndEndPoint($backEndEndPoint){
		$this->backEndEndPoint=$backEndEndPoint;
	}

	function getBackEndUserName(){
		return $this->backEndUserName;
	}
	function setBackEndUserName($backEndUserName){
		$this->backEndUserName=$backEndUserName;
	}
	
	function getBackEndPassword(){
		return $this->backEndPassword;
	}
	function setBackEndPassword($backEndPassword){
		$this->backEndPassword=$backEndPassword;
	}
	
	function getIsIdentityForwardingEnabled(){
		return $this->isIdentityForwardingEnabled;
	}
	function setIsIdentityForwardingEnabled($isIdentityForwardingEnabled){
		$this->isIdentityForwardingEnabled=$isIdentityForwardingEnabled;
	}

	function getIsPublished(){
		return $this->isPublished;
	}
	function setIsPublished($isPublished){
		$this->isPublished=$isPublished;
	}
	
	
	function getIsGlobalQuotasEnabled(){
		return $this->isGlobalQuotasEnabled;
	}
	function setIsGlobalQuotasEnabled($isGlobalQuotasEnabled){
		$this->isGlobalQuotasEnabled=$isGlobalQuotasEnabled;
	}

			
	function getIsUserQuotasEnabled(){
		return $this->isUserQuotasEnabled;
	}
	function setIsUserQuotasEnabled($isUserQuotasEnabled){
		$this->isUserQuotasEnabled=$isUserQuotasEnabled;
	}
	function getAdditionalConfiguration(){
		return $this->additionalConfiguration;
	}
	function setAdditionalConfiguration($AdditionalConfiguration){
		$this->additionalConfiguration=$AdditionalConfiguration;
	}
	function getOnAllNodes(){
		return $this->onAllNodes;
	}
	function setOnAllNodes($OnAllNodes){
		$this->onAllNodes=$OnAllNodes;
	}
	function setLoginFormUri($LoginFormUri){
		$this->loginFormUri=$LoginFormUri;
	}
	function getLoginFormUri(){
		return $this->loginFormUri;
	}
	

    public function __construct($rqt=NULL)
    {
		if ($rqt != NULL){
			$this->setServiceName($rqt["serviceName"]);		
			$this->setUri("services/" . urlencode($rqt["serviceName"]));

			$this->setIsIdentityForwardingEnabled($rqt["isIdentityForwardingEnabled"]);
			

			$this->setBackEndUsername($rqt["backEndUsername"]);
			$this->setBackEndPassword(decrypt($rqt["backEndPassword"]));
			$this->setBackEndEndPoint($rqt["backEndEndPoint"]);
			$this->setFrontEndEndPoint($rqt["frontEndEndPoint"]);

			$this->setReqSec($rqt["reqSec"]);
			$this->setReqDay($rqt["reqDay"]);
			$this->setReqMonth($rqt["reqMonth"]);
			
			$this->setIsGlobalQuotasEnabled($rqt["isGlobalQuotasEnabled"]);
			$this->setIsUserQuotasEnabled($rqt["isUserQuotasEnabled"]);
			
			$this->setGroupName($rqt["groupName"]);
			$this->setIsPublished($rqt["isPublished"]);
			
			$this->setIsHitLoggingEnabled($rqt["isHitLoggingEnabled"]);
			$this->setIsUserAuthenticationEnabled($rqt["isUserAuthenticationEnabled"]);
			$this->setOnAllNodes($rqt["onAllNodes"]);
			$this->setIsAnonymousAllowed($rqt["isAnonymousAllowed"]);
			$this->setLoginFormUri($rqt["loginFormUri"]);
			
			$this->setAdditionalConfiguration($rqt["additionalConfiguration"]);
			if ($this->getIsUserAuthenticationEnabled()==0){
				$this->setGroupName("");
				$this->setIsUserQuotasEnabled(0);
				$this->setIsIdentityForwardingEnabled(0);
				$this->setIsAnonymousAllowed(0);
			}
		}
	}

	public function toArray(){

		return Array(
			"uri" => $this->getUri() ,
			"groupUri" => $this->getGroupUri() ,
			"serviceName" => $this->getServiceName() ,
			"groupName" => $this->getGroupName() ,
			"isIdentityForwardingEnabled" => $this->getIsIdentityForwardingEnabled() ,
			"isGlobalQuotasEnabled" => $this->getIsGlobalQuotasEnabled() ,
			"isUserQuotasEnabled" => $this->getIsUserQuotasEnabled() ,
			"isPublished" => $this->getIsPublished() ,
			"reqSec" => $this->getReqSec() ,
			"reqDay" => $this->getReqDay() ,
			"reqMonth" => $this->getReqMonth() ,
			"frontEndEndPoint" => $this->getFrontEndEndPoint() ,
			"backEndEndPoint" => $this->getBackEndEndPoint() ,
			"backEndUsername" => $this->getBackEndUsername() ,
			"backEndPassword" => $this->getBackEndPassword() ,
			"isHitLoggingEnabled" => $this->getIsHitLoggingEnabled() ,
			"isUserAuthenticationEnabled" => $this->getIsUserAuthenticationEnabled() ,
			"additionalConfiguration" => $this->getAdditionalConfiguration(),
			"onAllNodes" => $this->getOnAllNodes(),
			"loginFormUri" => $this->getLoginFormUri(),
			"isAnonymousAllowed" => $this->getIsAnonymousAllowed()
		);
	}

}
