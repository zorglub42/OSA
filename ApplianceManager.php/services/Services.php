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
require_once '../users/userDAO.php';


class Services{
	
	
	function getParameterValue($paramName, $request_data){
		if (isset($request_data[$paramName]) && $request_data[$paramName]!="" ){
			return $request_data[$paramName];
		}else{
			return NULL;
		}
	}
	
	/**
	 * @url POST :serviceName/headers-mapping/:userProperty
	 */
	 function createHeadersMapping($serviceName, $userProperty, $headerName){
		try{
			return createServiceHeadersMapping($serviceName, $userProperty, $headerName);
		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	 }
	
	/**
	 * @url POST :serviceName/headers-mapping/
	 */
	 function setHeadersMappings($serviceName, $request_data){
		try{
			$this->deleteHeadersMapping($serviceName);
			foreach ($request_data as $header){
				if (!empty($header["userProperty"])  && !empty($header["headerName"])){
					$this->createHeadersMapping($serviceName, $header["userProperty"], $header["headerName"]);
				}
			}
			return $this->getHeadersMapping($serviceName);
		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	 }	
	/**
	 * @url GET :serviceName/headers-mapping/:userProperty
	 * @url GET :serviceName/headers-mapping
	 */
	 function getHeadersMapping($serviceName, $userProperty=NULL){
		try{
			return getServiceHeadersMapping($serviceName, $userProperty);
		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	 }
	
	
	/**
	 * @url DELETE :serviceName/headers-mapping
	 */
	 function deleteHeadersMapping($serviceName){
		try{
			return deleteServiceHeadersMapping($serviceName);
		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	 }
	
	
	/**
	 * @url GET 
	 * @url GET :serviceName
	 */
	 function get($serviceName = NULL, $request_data=NULL){
		try{
			return getService($serviceName, $request_data);
		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	 }
	
	/**
	 * @url POST 
	 * @url POST :serviceName
	 */
	 function addService($serviceName = NULL, $request_data=NULL){
		try{
			return createService($serviceName, $request_data);
		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	 }
	
	/**
	 * @url PUT 
	 * @url PUT :serviceName
	 */
	 function update($serviceName = NULL, $request_data=NULL){
		try{
			return updateService($serviceName, $request_data);
		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	 }
	/**
	 * @url DELETE 
	 * @url DELETE :serviceName
	 */
	 function delete($serviceName = NULL, $request_data=NULL){
		try{
			return deleteService($serviceName, $request_data);
		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	 }
	 
	 /**
	  * @url GET :serviceName/quotas/unset
	  */
	 function getUnsetQuotasForService($serviceName = NULL, $request_data=NULL){
		try{
			return getUnsetQuotas($serviceName, $request_data);
		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	 }
	 
	 
	 /**
	  * @url GET :serviceName/quotas
	  * @url GET :serviceName/quotas/:userName
	  */
	 function userQuotasForService($serviceName = NULL, $userName = NULL, $request_data=NULL){
		try{
			return getUserQuotas($serviceName, $userName, $request_data);
		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	 }
	 /**
	  * @url POST :serviceName/quotas/:userName
	  */
	 function addUserQuotasForService($serviceName = NULL, $userName = NULL, $request_data=NULL){
		try{
			return addUserQuota($userName,$serviceName, $request_data);
		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	 }
	 /**
	  * @url PUT :serviceName/quotas/:userName
	  */
	 function updateUserQuotasForService($serviceName = NULL, $userName = NULL, $request_data=NULL){
		try{
			return updateUserQuotas($userName,$serviceName, $request_data);
		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	 }
	 /**
	  * @url DELETE :serviceName/quotas/:userName
	  */
	 function deleteUserQuotasForService($serviceName = NULL, $userName = NULL, $request_data=NULL){
		try{
			return deleteUserQuotas($userName,$serviceName, $request_data);
		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	 }
	 
	 
	 /**
	  * @url GET :serviceName/nodes
	  * @url GET //nodes
	  */
	  function getNodesForService($serviceName=NULL){
		try{
			return nodesListForService($serviceName);
		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	  }
	 /**
	  * @url POST :serviceName/nodes
	  */
	  function defineNodesForService($serviceName, $request_data){
		try{
			return setNodesListForService($serviceName, $request_data);
		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	  }
}
