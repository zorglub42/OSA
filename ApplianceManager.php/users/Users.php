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

require_once 'userDAO.php';


class Users{
	
	/**
	 * @url PUT me/password
	 * update current user password
	 */
	 function resetPassword($request_data){
			 $me=getRequestor();
			 if ( !isset($request_data["oldPassword"] ) or $request_data["oldPassword"] == ""){
				 throw new RestException(400,"oldPassword parameter is required");
			 }
			 if ( !isset($request_data["newPassword"] ) or $request_data["newPassword"] == ""){
				 throw new RestException(400,"newPassword parameter is required");
			 }
			 
			 $me= $this->get($me);
			 if ($me["password"] != $request_data["oldPassword"]){
				 throw new RestException(400,"current password does not match oldPassword parameter");
			 }
			 return updateUserPassword($me["userName"], $request_data["newPassword"] );
	 }
	
	/**
	 * @url GET me
	 */
	 function whoAmI(){
		 try{
			 $me=getRequestor();
			 return $this->get($me);
		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	 }
	
	function getParameterValue($paramName, $request_data){
		if (isset($request_data[$paramName]) && $request_data[$paramName]!="" ){
			return $request_data[$paramName];
		}else{
			return NULL;
		}
	}
	/**
	 * @url GET :userName/quotas/unset
	 */
	function getUnsetQuotaForUSer($userName=NULL,$serviceName=NULL, $request_data = NULL){
		try{
			return getUnsetQuota($userName);
		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	}
	/**
	 * @url GET :userName/quotas
	 * @url GET :userName/quotas/:serviceName
	 */
	function getQuotaForUser($userName=NULL,$serviceName=NULL, $request_data = NULL){
		try{
			return getUserQuota($userName, $serviceName, $request_data);
		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	}
	/**
	 * @url POST :userName/quotas/:serviceName
	 */
	function createQuotaForUser($userName,$serviceName, $request_data = NULL){
		try{
			return addUserQuota($userName, $serviceName, $request_data);
		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	}
	/**
	 * @url GET :userName/groups/available
	 */
	function getAvailableGroupForUser($userName=NULL, $request_data = NULL){
		try{
			return getAvailableGroup($userName, $request_data);
		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	}
	
	/**
	 * @url GET :userName/groups
	 * @url GET :userName/groups/:groupName
	 */
	function getUserGroup($userName=NULL, $groupName=NULL, $request_data = NULL){
		try{
			return getUserGroup($userName, $groupName, $request_data);
		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	}
	/**
	 * @url DELETE :userName/groups/:groupName
	 */
	function removeUserGroup($userName, $groupName){
		try{
			return removeUserFromGroup($userName, $groupName);
		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	}
	/**
	 * @url POST :userName/groups/:groupName
	 */
	function addUserGroup($userName, $groupName){
		try{
			return addUserToGroup($userName, $groupName);
		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	}
	/**
	 * @url GET
	 * @url GET :userName
	 */
	function get($userName=NULL, $request_data = NULL){
		try{
			return getUser($userName, $request_data);
		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	}
	/**
	 * @url POST
	 * @url POST :userName
	 */
	function create($userName=NULL, $request_data = NULL){
		try{
			return addUser($userName, $request_data);
		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	}
	/**
	 * @url PUT
	 * @url PUT :userName
	 */
	function update($userName=NULL, $request_data = NULL){
		try{
			return updateUser($userName, $request_data);
		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	}
	/**
	 * @url DELETE
	 * @url DELETE :userName
	 */
	function delete($userName=NULL, $request_data = NULL){
		try{
			return deleteUser($userName);
		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	}
}
