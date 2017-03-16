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
require_once 'Services.php';
require_once 'Groups.php';

/**
 * Users management
 */
class Users{
	
	/**
	 * Change password
	 * 
	 * Change connected user password
	 * 
	 * @url PUT me/password
	 * 
	 * @param string oldPassword new password
	 * @param string newPassword new password
	 * 
	 * @return User
	 */
	 function resetPassword($oldPassword, $newPassword){
			 $me=getRequestor();
			 if ( !isset($oldPassword ) or $oldPassword == ""){
				 throw new RestException(400,"oldPassword parameter is required");
			 }
			 if ( !isset($newPassword ) or $newPassword == ""){
				 throw new RestException(400,"newPassword parameter is required");
			 }
			 
			 $me= $this->get($me);
			 if ($me["password"] != $oldPassword){
				 throw new RestException(400,"current password does not match oldPassword parameter");
			 }
			 return updateUserPassword($me["userName"], $newPassword );
	 }
	
	/**
	 * Get current user
	 * 
	 * Get connected user description
	 * @url GET me
	 * 
	 * @return User
	 */
	 function whoAmI(){
		 try{
			 $me=getRequestor();
			 return $this->get($me);
		}catch (Exception $e){
			if (is_numeric($e->getCode())){
				throw new RestException($e->getCode(), $e->getMessage());
			}else{
				throw new RestException(500, $e->getMessage());
			}

		}
	 }
	
	private function getParameterValue($paramName, $request_data){
		if (isset($request_data[$paramName]) && $request_data[$paramName]!="" ){
			return $request_data[$paramName];
		}else{
			return NULL;
		}
	}
	/**
	 * Get unset quotas
	 * 
	 * Get quotas witch are not yet defined for a particular user (based on services requiring users quotas settings)
	 *  
	 * @url GET :userName/quotas/unset
	 * 
	 * @param string userName user identifer
	 * 
	 * @return array List of potentials quotas {@type Quota}
	 */
	function getUnsetQuotaForUSer($userName){
		try{
			$this->getOne($userName);
			return getUnsetQuota($userName);
		}catch (Exception $e){
			if (is_numeric($e->getCode())){
				throw new RestException($e->getCode(), $e->getMessage());
			}else{
				throw new RestException(500, $e->getMessage());
			}

		}
	}
	/**
	 * Get user's quotas
	 * 
	 * Reteive all defined quotas for a particular user
	 * 
	 * @url GET :userName/quotas
	 * 
	 * @param string userName user identifier
	 * 
	 * @return array list of defined quotas for this user {@type Quota}
	 */
	function getAllQuotasForUser($userName){
		$this->getOne($userName);
		return $this->getQuotaForUser($userName);
	}
	/**
	 * Get user's quota for a service
	 * 
	 * Reteive defined quotas for a particular user and a particular service
	 * 
	 * @url GET :userName/quotas/:serviceName
	 * 
	 * @param string userName user identifier
	 * @param string serviceName service identifier
	 * 
	 * @return Quota quotas for this user and this service  {@type Quota}
	 */
	function getQuotasForUserAndService($userName, $serviceName){
		$this->getOne($userName);
		return $this->getQuotaForUser($userName, $serviceName);
	}
	private function getQuotaForUser($userName, $serviceName=NULL){
		try{
			return getUserQuota($userName, $serviceName);
		}catch (Exception $e){
			if (is_numeric($e->getCode())){
				throw new RestException($e->getCode(), $e->getMessage());
			}else{
				throw new RestException(500, $e->getMessage());
			}

		}
	}
	/**
	 * Add quotas
	 * 
	 * Add quotas on a particular service to a particular user
	 * 
	 * @url POST :userName/quotas/:serviceName
	 * 
	 * @param string userName user identifier
	 * @param string groupName group identifier
	 * @param int reqSec maximum number of request per seconds allowed
	 * @param int reqDay maximum number of request per days allowed
	 * @param int reqMonth maximum number of request per months allowed
	 * 
	 * @return Quota added quota
	 */
	function createQuotaForUser($userName,$serviceName, $reqSec, $reqDay, $reqMonth){
		try{
			#Array param is legacy from previous (initial) version of Restler 
			$params=array("reqSec" =>$reqSec,
						  "reqDay" =>$reqSec,
						  "reqMonth" =>$reqSec,
			);
			$this->get($userName);	
			$s= new Services();
			$s->getOne($serviceName);
			return addUserQuota($userName, $serviceName, $params);
		}catch (Exception $e){
			if (is_numeric($e->getCode())){
				throw new RestException($e->getCode(), $e->getMessage());
			}else{
				throw new RestException(500, $e->getMessage());
			}

		}
	}
	/**
	 * Get available groups
	 * 
	 * Get groups where user is not yet a member
	 * 
	 * @url GET :userName/groups/available
	 * 
	 * @param string userName user identifier
	 * 
	 * @return array group list {@type Group}
	 */
	function getAvailableGroupForUser($userName){
		try{
			$this->getOne($userName);
			return getAvailableGroup($userName);
		}catch (Exception $e){
			if (is_numeric($e->getCode())){
				throw new RestException($e->getCode(), $e->getMessage());
			}else{
				throw new RestException(500, $e->getMessage());
			}

		}
	}
	
	/**
	 * Get groups membership
	 * 
	 * Get list of group where the user is a member
	 * 
	 * @url GET :userName/groups
	 * 
	 * Get groups membership for a particular user
	 * 
	 * @param string userName user identifier
	 * 
	 * @return array List of user's groups {@type Group}
	 */
	function geListOfGroupForUser($userName){
		return $this->getUserGroup($userName);
	} 
	
	/**
	 * Get group membership
	 * 
	 * Get a particular group membership for a particular user
	 * 
	 * @url GET :userName/groups/:groupName
	 * 
	 * @param string userName user identifier
	 * @param string groupName group identifier
	 * 
	 * @return Group List of user's groups 
	 */
	public function getGroupForUser($userName, $groupName){
		return $this->getUserGroup($userName, $groupName);
	}
	
	private function getUserGroup($userName=NULL, $groupName=NULL){
		try{
			return getDAOUserGroup($userName, $groupName);
		}catch (Exception $e){
			if (is_numeric($e->getCode())){
				throw new RestException($e->getCode(), $e->getMessage());
			}else{
				throw new RestException(500, $e->getMessage());
			}

		}
	}
	/**
	 * Remove group
	 * 
	 * Remove a particular user from a particular group
	 * 
	 * @url DELETE :userName/groups/:groupName
	 * 
	 * @param string userName user identifier
	 * @param string groupName group identifier
	 * 
	 * @return Group removed group
	 */
	function removeUserGroup($userName, $groupName){
		try{
			$this->get($userName);
			
			$g= new Groups();
			$g->getOne($groupName);
			
			return removeUserFromGroup($userName, $groupName);
		}catch (Exception $e){
			if (is_numeric($e->getCode())){
				throw new RestException($e->getCode(), $e->getMessage());
			}else{
				throw new RestException(500, $e->getMessage());
			}

		}
	}
	/**
	 * Add group
	 * 
	 * Add a paraticular user to a particular group
	 * 
	 * @url POST :userName/groups/:groupName
	 * 
	 * @param string $userName user identifier
	 * @param string $groupName group identifier
	 * 
	 * @return Group added group
	 */
	function addUserGroup($userName, $groupName){
		try{
			$this->get($userName);
			$g=new Groups();
			$g->getOne($groupName);
			return addUserToGroup($userName, $groupName);
		}catch (Exception $e){
			if (is_numeric($e->getCode())){
				throw new RestException($e->getCode(), $e->getMessage());
			}else{
				throw new RestException(500, $e->getMessage());
			}

		}
	}
	/**
	 * Get a user
	 * 
	 * Get informations about a user
	 * 
	 * @url GET :userName
	 * 
	 * @param string userName user's identifer
	 * 
	 * @return User
	 */
	function getOne($userName){
		return $this->get($userName);
	}
	/**
	 * Get users list
	 * 
	 * Get informations about users
	 * 
	 * @param int withLog [optional] {@choice 0,1} If set to 1 retreive only users with records in logs,  If set to 0 retreive only users without records in logs  (filter conbination is AND)
	 * @param string userNameFilter [optional] Only retreive user with userName containing that string (filter conbination is AND)
	 * @param string firstNameFilter [optional] Only retreive user with first name containing that string (filter conbination is AND)
	 * @param string lastNameFilter [optional] Only retreive user with last name containing that string (filter conbination is AND)
	 * @param string emailAddressFilter [optional] Only retreive user with email address containing that string (filter conbination is AND)
	 * @param string entityFilter [optional] Only retreive user with entity containing that string (filter conbination is AND)
	 * @param string extraFilter [optional] Only retreive user with extra data containing that string (filter conbination is AND)
	 * @param string order [optional] "SQL Like" order clause based on User properties
	 * 
	 * @url GET 
	 * 
	 * 
	 * @return array {@type User}
	 */
	function getAll($withLog=null, $userNameFilter=null, $firstNameFilter=null, $lastNameFilter=null, $emailAddressFilter=null, $entityFilter=null, $extraFilter=null, $order=null){
		#Array param is legacy from previous (initial) version of Restler 
		$params=array("withLog" =>$withLog,
					  "userNameFilter" =>$userNameFilter,
					  "firstNameFilter" =>$firstNameFilter,
					  "lastNameFilter" =>$lastNameFilter,
					  "emailAddressFilter" =>$emailAddressFilter,
					  "entityFilter" =>$entityFilter,
					  "extraFilter" => $extraFilter,
					  "order" => $order
		);
		return $this->get(null, $params);
	}
	private function get($userName=NULL, $request_data = NULL){
		try{
			return getUser($userName, $request_data);
		}catch (Exception $e){
			if (is_numeric($e->getCode())){
				throw new RestException($e->getCode(), $e->getMessage());
			}else{
				throw new RestException(500, $e->getMessage());
			}

		}
	}
	/**
	 * Create user
	 * 
	 * Create a new user into the system
	 * 
	 * @url POST :userName
	 * @url POST
	 * 
	 * @param string userName user identitfier
	 * @param string password password to authenticate against OSA
	 * @param email email user's mail address
	 * @param string endDate users's validity end date in ISO 8601 full format
	 * @param string firstName [Optional] user's first name
	 * @param string lastName [Optional] user's last name
	 * @param string entity [Optional] user's entity
	 * @param string extra [Optional] users's extra data in free format
	 * 
	 * @return User newly created user
	 */
	function create($userName, $password, $email, $endDate, $firstName=NULL, $lastName=NULL, $entity=NULL, $extra=NULL){
		try{
			#Array param is legacy from previous (initial) version of Restler 
			$params=Array("password"=>$password,
						 "email" =>$email,
						 "endDate" =>$endDate,
						 "firstName" =>$firstName,
						 "lastName" =>$lastName,
						 "entity" =>$entity,
						 "extra" =>$extra
			);
			return addUser($userName, $params);
		}catch (Exception $e){
			if (is_numeric($e->getCode())){
				throw new RestException($e->getCode(), $e->getMessage());
			}else{
				throw new RestException(500, $e->getMessage());
			}

		}
	}
	/**
	 * Update
	 * 
	 * Update user properties
	 * 
	 * @url PUT :userName
	 * 
	 * @param string userName user identitfier
	 * @param string password password to authenticate against OSA
	 * @param email email user's mail address
	 * @param string endDate users's validity end date in ISO 8601 full format
	 * @param string firstName [Optional] user's first name
	 * @param string lastName [Optional] user's last name
	 * @param string entity [Optional] user's entity
	 * @param string extra [Optional] users's extra data in free format
	 * 
	 * @return User updated user
	 */
	function update($userName, $password, $email, $endDate, $firstName=NULL, $lastName=NULL, $entity=NULL, $extra=NULL){
		try{
			$this->get($userName);
			#Array param is legacy from previous (initial) version of Restler 
			$params=Array("password"=>$password,
						 "email" =>$email,
						 "endDate" =>$endDate,
						 "firstName" =>$firstName,
						 "lastName" =>$lastName,
						 "entity" =>$entity,
						 "extra" =>$extra
			);
			
			
			return updateUser($userName, $params);
		}catch (Exception $e){
			if (is_numeric($e->getCode())){
				throw new RestException($e->getCode(), $e->getMessage());
			}else{
				throw new RestException(500, $e->getMessage());
			}

		}
	}
	/**
	 * Delete user
	 * 
	 * Remove user form the system
	 * 
	 * @url DELETE :userName
	 * 
	 * @param string userName user identifier
	 * 
	 * @return User deleted user
	 */
	function delete($userName){
		try{
 			return deleteUser($userName);
		}catch (Exception $e){
			if (is_numeric($e->getCode())){
				throw new RestException($e->getCode(), $e->getMessage());
			}else{
				throw new RestException(500, $e->getMessage());
			}

		}
	}
}
