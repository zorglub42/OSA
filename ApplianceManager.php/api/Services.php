<?php
/*--------------------------------------------------------
 * Module Name : ApplianceManager
 * Version : 1.0.0
 *
 * Software Name : OpenServicesAccess
 * Version : 2.0
 *
 * Copyright (c) 2011 – 2014 Orange
 * This software is distributed under the Apache 2 license
 * <http://www.apache.org/licenses/LICENSE-2.0.html>
 *
 *--------------------------------------------------------
 * File Name   : ApplianceManager/ApplianceManager.php/groups/Groups.php
 *
 * Created     : 2013-11
 * Authors     : Benoit HERARD <benoit.herard(at)orange.com>
 *
 * Description :
 *      REST Handler
 * 
 *--------------------------------------------------------
 * History     :
 * 1.0.0 - 2013-11-12 : Release of the file
*/
require_once('../include/commonHeaders.php');
require_once 'serviceDAO.php';
require_once '../api/userDAO.php';

/**
 * Services managements
 */
class Services{
	
	
	
	/**
	 * Create header mapping
	 * 
	 * Create header mapping form a particular service and a particular property
	 * 
	 * @param string servieName service identifier
	 * @param string userProperty User property to map
	 * @param string headerName HTTP header name
	 * 
	 * @url POST :serviceName/headers-mapping/:userProperty
	 * 
	 * @return HeaderMapping Created header 
	 */
	 function createHeadersMapping($serviceName, $userProperty, $headerName){
		try{
			return createServiceHeadersMapping($serviceName, $userProperty, $headerName);
		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	 }
	
	/**
	 * Create headers mapping
	 * 
	 * Create headers mapping for alist of user properties for a particular header
	 * 
	 * @param string sertiveName Service identifier
	 * @param array mapping {@type HeaderMappingCreation} Headers to map
	 * 
	 * @url POST :serviceName/headers-mapping/
	 * 
	 * @return array {@type HeaderMapping} Created headers
	 */
	 function setHeadersMappings($serviceName, $mapping){
		try{
			$this->deleteHeadersMapping($serviceName);
			foreach ($mapping as $header){
				if (!empty($header->userProperty)  && !empty($header->headerName)){
					$this->createHeadersMapping($serviceName, $header->userProperty, $header->headerName);
				}
			}
			return $this->getHeadersMapping($serviceName);
		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	 }	
	/**
	 * Get headers mapping
	 * 
	 * Get all headers mapping for a particular service
	 * 
	 * @url GET :serviceName/headers-mapping
	 * 
	 * @param string serviceName Service identifier 
	 *
	 * @return array {@type HeaderMapping} Headers
	 * 
	 */
	 function getHeadersMapping($serviceName){
		try{
			$s = new Services();
			$s->getOne($serviceName);
			return getServiceHeadersMapping($serviceName, null);
		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	 }
	/**
	 * Get property headers mapping
	 * 
	 * Get header mapping for a particular service and a particular user property
	 * 
	 * @url GET :serviceName/headers-mapping/:userProperty
	 * 
	 * @param string serviceName Service identifier 
	 * @param string userPromperty Service identifier 
	 *
	 * @return array {@type HeaderMapping} Requested Header (array of 1 item)
	 * 
	 */
	 function getUserPropertyHeadersMapping($serviceName, $userProperty){
		try{
			$s = new Services();
			$s->getOne($serviceName);
			return getServiceHeadersMapping($serviceName, $userProperty);
		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	 }
	
	
	/**
	 * Delete headers mapping
	 * 
	 * Delete all headers mapping for a particular service
	 * 
	 * @url DELETE :serviceName/headers-mapping
	 * 
	 * @param string servieName Service identifier
	 * 
	 * @return arry {@type HeaderMapping} Currint list of Headers for this service
	 */
	 function deleteHeadersMapping($serviceName){
		try{
			$s = new Services();
			$s->getOne($serviceName);
			return deleteServiceHeadersMapping($serviceName);
		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	 }
	
	/** 
	 * Get a service
	 * 
	 * Get details about a particular Service
	 * 
	 * @url GET :serviceName
	 * 
	 * @param string serviceName Service identifier
	 * 
	 * @return Service requested Service
	 * 
	 */
	 function getOne($serviceName){
		 return $this->get($serviceName);
	}
	/**
	 * Get Services
	 * 
	 * Get a list of Services
	 * 
	 * @url GET 
	 * 
	 * @param int withLog [optional] {@choice 0,1} If set to 1 retreive only services with records in logs,  If set to 0 retreive only services without records in logs  (filter conbination is AND)
	 * @param string serviceNameFilter  [optional] Only retreive services with serviceName containing that string (filter conbination is AND)
	 * @param string groupNameFilter [optional] Only retreive services with groupName containing that string (filter conbination is AND)
	 * @param string frontEndEndPointFilter [optional] Only retreive services with frontEndEndPoint containing that string (filter conbination is AND)
	 * @param string backEndEndPointFilter [optional] Only retreive services with backEndEndPoint containing that string (filter conbination is AND)
	 * @param string nodeNameFilter [optional] Only retreive services available on that node (filter conbination is AND)
	 * @param int withQuotas [optional] {@choice 0,1} If set to 1 retreive only services  with any king of quotas activated,  If set to 0 retreive only services  without any king of quotas activated  (filter conbination is AND)
	 * @param int isIdentityForwardingEnabledFilter [optional] {@choice 0,1} If set to 1 retreive only services  with identity forwarding enabled,  If set to 0 retreive only services  with identity forwarding disabled  (filter conbination is AND)
	 * @param int isGlobalQuotasEnabledFilter [optional] {@choice 0,1} If set to 1 retreive only services  with global quotas enabled,  If set to 0 retreive only services  with global quotas disabled  (filter conbination is AND)
	 * @param int isUserQuotasEnabledFilter [optional] {@choice 0,1} If set to 1 retreive only services  with users quotas enabled,  If set to 0 retreive only services  with users quotas disabled  (filter conbination is AND)
	 * @param int isPublishedFilter [optional] {@choice 0,1} If set to 1 retreive only services  which are published on nodes,  If set to 0 retreive only services  which are not published on nodes  (filter conbination is AND)
	 * @param int isHitLoggingEnabledFilter [optional] {@choice 0,1} If set to 1 retreive only services  with logs recording enabled,  If set to 0 retreive only services  with logs recording disabled  (filter conbination is AND)
	 * @param int isUserAuthenticationEnabledFilter [optional] {@choice 0,1} If set to 1 retreive only with user authentication enabled,  If set to 1 retreive only services  with user authentication disabled  (filter conbination is AND)
	 * @param int additionalConfigurationFilter [optional] Only retreive services with additionalConfiguration containing that string (filter conbination is AND)
	 * 
	 * @return array {@type Service} List of Services
	 */
	 function getAll($withLog=null, $serviceNameFilter=null, $groupNameFilter=null, $frontEndEndPointFilter=null, $backEndEndPointFilter=null, $nodeNameFilter=null,
					 $withQuotas=null, $isIdentityForwardingEnabledFilter=null, $isGlobalQuotasEnabledFilter=null, $isUserQuotasEnabledFilter=null, $isPublishedFilter=null, 
					 $isHitLoggingEnabledFilter=null, $isUserAuthenticationEnabledFilter=null, $additionalConfigurationFilter=null){
				#Array param is legacy from previous (initial) version of Restler 
		$params=array("withLog" => $withLog,
					  "serviceNameFilter" => $serviceNameFilter,
					  "groupNameFilter" => $groupNameFilter,
					  "frontEndEndPointFilter" => $frontEndEndPointFilter,
					  "backEndEndPointFilter" => $backEndEndPointFilter,
					  "nodeNameFilter" => $nodeNameFilter,
					  "withQuotas" => $withQuotas,
					  "isIdentityForwardingEnabledFilter" => $isIdentityForwardingEnabledFilter,
					  "isGlobalQuotasEnabledFilter" => $isGlobalQuotasEnabledFilter,
					  "isUserQuotasEnabledFilter" => $isUserQuotasEnabledFilter,
					  "isPublishedFilter" => $isPublishedFilter,
					  "isHitLoggingEnabledFilter" => $isHitLoggingEnabledFilter,
					  "isUserAuthenticationEnabledFilter" => $isUserAuthenticationEnabledFilter,
					  "additionalConfigurationFilter" => $additionalConfigurationFilter,
		);
	
		return $this->get(null, $params);
	}

	 private function get($serviceName = NULL, $request_data=NULL){
		try{
			return getService($serviceName, $request_data);
		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	 }
	
	/**
	 * Create service
	 * 
	 * Create and deplaoy a new Service
	 * 
	 * @url POST :serviceName
	 * @url POST 
	 * 
	 * @param string serviceName Serive identifier
	 * @param string frontEndEndPoint URI on frontend node
	 * @param url backEndEndPoint URL to backend server
	 * @param int isPublished [Optional] {@choice 0,1} Is tis service deployed? (O: no 1: yes, default 1)
	 * @param string additionalConfiguration [Optional] Additional Apache "<Location>" tag directives 
	 * @param int isHitLoggingEnabled [Optional] {@choice 0,1} Is log recording is enabled? (O: no 1: yes, default 0)
	 * @param string onAllNodes [Optional] {@choice 0,1} Is this service alavaliable on all publshed nodes? (O: no 1: yes, default 1)
	 * @param int isUserAuthenticationEnabled [Optional] {@choice 0,1} Is user authentication enabled? (O: no 1: yes, default 0)
	 * @param string groupName [Optional] User must be a member of this group to use this service (required if isUserAuthenticationEnabled=1) 
	 * @param int isIdentityForwardingEnabled [Optional] {@choice 0,1} Is authenticated user's identity forwarded to backend system? (O: no 1: yes, default 0)
	 * @param int isAnonymousAllowed [Optional] {@choice 0,1}  Is authentication absolutly required to invoke this service or anonymous access is also possible? (O: no 1: yes, default 0)
	 * @param string backEndUsername [Optional] username to authenticate against backend system (basic authentication), use "%auto%" to use credentials on OSA agains backend
	 * @param string backEndPassword [Optional] password to authenticate agains backend system
	 * @param string loginFormUri [Optional] Login from URL to redirecto to in case of unauthenticated access on a compliant node
	 * @param int isGlobalQuotasEnabled [Optional] {@choice 0,1} Is global quotas enabled?  (O: no 1: yes, default 0)
	 * @param int reqSec [Optional] Maximun number of request alloed per second (Required if isGlobalQuotasEnabled=1)
	 * @param int reqDay [Optional] Maximun number of request alloed per second (Required if isGlobalQuotasEnabled=1)
	 * @param int reqMonth [Optional] Maximun number of request alloed per second (Required if isGlobalQuotasEnabled=1)
	 * @param int isUserQuotasEnabled [Optional] {@choice 0,1} Are quotas enabled at user level? (O: no 1: yes, default 0)
	 * 
	 * @return Service created service
	 */
		function addService($serviceName, $frontEndEndPoint, $backEndEndPoint, 
							$isPublished=null,  $additionalConfiguration=null,
							$isHitLoggingEnabled=null, $onAllNodes=null, 
							$isUserAuthenticationEnabled=null, $groupName=null, $isIdentityForwardingEnabled=null,  $isAnonymousAllowed=null, 
							$backEndUsername=null, $backEndPassword=null, $loginFormUri=null,
							$isGlobalQuotasEnabled=null, $reqSec=null, $reqDay=null, $reqMonth=null, $isUserQuotasEnabled=null){
		try{
			#Array param is legacy from previous (initial) version of Restler 
			$params=array(	"isPublished" => $isPublished,
							"isGlobalQuotasEnabled" => $isGlobalQuotasEnabled,
							"frontEndEndPoint" => $frontEndEndPoint,
							"backEndEndPoint" => $backEndEndPoint, 
							"additionalConfiguration" => $additionalConfiguration,
							"isAnonymousAllowed" => $isAnonymousAllowed,
							"isHitLoggingEnabled" => $isHitLoggingEnabled,
							"onAllNodes" => $onAllNodes, 
							"isUserAuthenticationEnabled" => $isUserAuthenticationEnabled,
							"reqSec" => $reqSec,
							"reqDay" => $reqDay,
							"reqMonth" => $reqMonth,
							"isIdentityForwardingEnabled" => $isIdentityForwardingEnabled,
							"backEndUsername" => $backEndUsername,
							"backEndPassword" => $backEndPassword,
							"isUserQuotasEnabled" => $isUserQuotasEnabled,
							"loginFormUri" => $loginFormUri,
							"groupName" =>$groupName,
			);
							
			return createService($serviceName, $params);
		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	 }
	
	/**
	 * Update a service
	 * 
	 * @url PUT :serviceName
	 * 
	 * @param string serviceName Serive identifier
	 * @param string frontEndEndPoint URI on frontend node
	 * @param url backEndEndPoint URL to backend server
	 * @param int isPublished [Optional] {@choice 0,1} Is tis service deployed? (O: no 1: yes, default 1)
	 * @param string additionalConfiguration [Optional] Additional Apache "<Location>" tag directives 
	 * @param int isHitLoggingEnabled [Optional] {@choice 0,1} Is log recording is enabled? (O: no 1: yes, default 0)
	 * @param string onAllNodes [Optional] {@choice 0,1} Is this service alavaliable on all publshed nodes? (O: no 1: yes, default 1)
	 * @param int isUserAuthenticationEnabled [Optional] {@choice 0,1} Is user authentication enabled? (O: no 1: yes, default 0)
	 * @param string groupName [Optional] User must be a member of this group to use this service (required if isUserAuthenticationEnabled=1) 
	 * @param int isIdentityForwardingEnabled [Optional] {@choice 0,1} Is authenticated user's identity forwarded to backend system? (O: no 1: yes, default 0)
	 * @param int isAnonymousAllowed [Optional] {@choice 0,1}  Is authentication absolutly required to invoke this service or anonymous access is also possible? (O: no 1: yes, default 0)
	 * @param string backEndUsername [Optional] username to authenticate against backend system (basic authentication), use "%auto%" to use credentials on OSA agains backend
	 * @param string backEndPassword [Optional] password to authenticate agains backend system
	 * @param string loginFormUri [Optional] Login from URL to redirecto to in case of unauthenticated access on a compliant node
	 * @param int isGlobalQuotasEnabled [Optional] {@choice 0,1} Is global quotas enabled?  (O: no 1: yes, default 0)
	 * @param int reqSec [Optional] Maximun number of request alloed per second (Required if isGlobalQuotasEnabled=1)
	 * @param int reqDay [Optional] Maximun number of request alloed per second (Required if isGlobalQuotasEnabled=1)
	 * @param int reqMonth [Optional] Maximun number of request alloed per second (Required if isGlobalQuotasEnabled=1)
	 * @param int isUserQuotasEnabled [Optional] {@choice 0,1} Are quotas enabled at user level? (O: no 1: yes, default 0)
	 * 
	 * @return Service Updated service
	 */
	 function update($serviceName, $frontEndEndPoint, $backEndEndPoint, 
							$isPublished=null,  $additionalConfiguration=null,
							$isHitLoggingEnabled=null, $onAllNodes=null, 
							$isUserAuthenticationEnabled=null, $groupName=null, $isIdentityForwardingEnabled=null,  $isAnonymousAllowed=null, 
							$backEndUsername=null, $backEndPassword=null, $loginFormUri=null,
							$isGlobalQuotasEnabled=null, $reqSec=null, $reqDay=null, $reqMonth=null, $isUserQuotasEnabled=null){
		try{
			#Array param is legacy from previous (initial) version of Restler 
			$params=array(	"isPublished" => $isPublished,
							"isGlobalQuotasEnabled" => $isGlobalQuotasEnabled,
							"frontEndEndPoint" => $frontEndEndPoint,
							"backEndEndPoint" => $backEndEndPoint, 
							"additionalConfiguration" => $additionalConfiguration,
							"isAnonymousAllowed" => $isAnonymousAllowed,
							"isHitLoggingEnabled" => $isHitLoggingEnabled,
							"onAllNodes" => $onAllNodes, 
							"isUserAuthenticationEnabled" => $isUserAuthenticationEnabled,
							"reqSec" => $reqSec,
							"reqDay" => $reqDay,
							"reqMonth" => $reqMonth,
							"isIdentityForwardingEnabled" => $isIdentityForwardingEnabled,
							"backEndUsername" => $backEndUsername,
							"backEndPassword" => $backEndPassword,
							"isUserQuotasEnabled" => $isUserQuotasEnabled,
							"loginFormUri" => $loginFormUri,
							"groupName" =>$groupName,
			);
			return updateService($serviceName, $params);
		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	 }
	/**
	 * Delete Service
	 * 
	 * Remove and undeploy a particular service
	 * 
	 * @url DELETE :serviceName
	 * 
	 * @param string serviceName Service identifier
	 * 
	 * @return Service Deleted Service
	 */
	 function delete($serviceName){
		try{
			return deleteService($serviceName);
		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	 }
	 
	 /**
	  * Get Users without quotas
	  * 
	  * Get a list of user for who are allowed to use this Service but User quotas are not set but required 
	  * 
	  * @url GET :serviceName/quotas/unset
	  * 
	  * @param string serviceName
	  * 
	  * @return array {@type User} Users list
	  */
	 function getUnsetQuotasForService($serviceName){
		try{
			$s = new Services();
			$s->getOne($serviceName);
			return getUnsetQuotas($serviceName);
		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	 }
	 
	 
	 /**
	  * Get user quotas
	  * 
	  * Get user quotas defined for a particular service
	  * 
	  * @url GET :serviceName/quotas
	  * 
	  * @param string serviceName Service identifier
	  * 
	  * @return array {@type Quota}
	  * 
	  */
	 function userQuotasForService($serviceName){
		try{
			$s = new Services();
			$s->getOne($serviceName);
			return getUserQuotas($serviceName);
		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	 }
	 /**
	  * Get user quotas
	  * 
	  * Get user quotas defined for a particular service
	  * 
	  * @url GET :serviceName/quotas/:userName
	  * 
	  * @param string serviceName Service identifier
	  * @param string userName User identifier
	  * 
	  * @return array {@type Quota}
	  * 
	  */
	 function userQuotasForServiceAndUser($serviceName,$userName){
		try{
			$s = new Services();
			$s->getOne($serviceName);
			return getUserQuotas($serviceName, $userName);
		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	 }
	 /**
	  * Create user quotas
	  * 
	  * Create quotas for a particular user and a particular service
	  * 
	  * @url POST :serviceName/quotas/:userName
	  * 
	  * @param string serviceName Service identifier
	  * @param string userName User identifier
	  * @param string reqSec Maximum number of allowed requests per seconds
	  * @param string reqDay Maximum number of allowed requests per days
	  * @param string reqMonth Maximum number of allowed requests per months
	  * 
	  * @return Quota Created Quota
	  */
	 function addUserQuotasForService($serviceName, $userName, $reqSec, $reqDay, $reqMonth){
		try{
			$params=array("reqSec" =>$reqSec,
						  "reqDay" =>$reqDay,
						  "reqMonth" =>$reqMonth,
			);
			return addUserQuota($userName,$serviceName, $params);
		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	 }
	 /**
	  * Update user quotas
	  * 
	  * Update quotas for a particular user and a particular service
	  * 
	  * @url PUT :serviceName/quotas/:userName
	  * 
	  * @param string serviceName Service identifier
	  * @param string userName User identifier
	  * @param string reqSec Maximum number of allowed requests per seconds
	  * @param string reqDay Maximum number of allowed requests per days
	  * @param string reqMonth Maximum number of allowed requests per months
	  * 
	  * @return Quota Created Quota
	  */
	 function updateUserQuotasForService($serviceName, $userName, $reqSec, $reqDay, $reqMonth){
		try{
			$params=array("reqSec" =>$reqSec,
						  "reqDay" =>$reqDay,
						  "reqMonth" =>$reqMonth,
			);
			
			return updateUserQuotas($userName,$serviceName, $request_data);
		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	 }
	 /**
	  * Delete users quotas
	  * 
	  * Delete quotzas for a particular service and a particular user
	  * 
	  * @url DELETE :serviceName/quotas/:userName
	  * 
	  * @param string serviceName Service identifier
	  * @param string userName User identifier
	  * 
	  * @return  Quota Deleted quota
	  */
	 function deleteUserQuotasForService($serviceName, $userName){
		try{
			return deleteUserQuotas($userName,$serviceName);
		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	 }
	 
	 
	 /**
	  * Get Nodes where service is availables
	  * 
	  * @url GET :serviceName/nodes
	  * 
	  * @param string serviceName Service identifier
	  * 
	  * @return ServiceNode All Nodes with pulication indicator
	  */
	  function getNodesForService($serviceName){
		try{
			return nodesListForService($serviceName);
		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	  }
	 /**
	  * Publish on Nodes
	  * 
	  * Publish a particular Service on a Node lsit
	  * 
	  * @url POST :serviceName/nodes
	  * 
	  * @param string servieName Service identifier
	  * @param array {@type string} nodes Nodes identifiers list
	  * @param int noApply {@choice 0,1} Apply configuration immediatly? (0: no, 1: yes, default 1)
	  * 
	  * @return array {@type ServiceNode} Node on which servie is available
	  */
	  function defineNodesForService($serviceName, $nodes, $noApply=0){
		try{
			return setNodesListForService($serviceName, $nodes, $noApply);
		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	  }
}
