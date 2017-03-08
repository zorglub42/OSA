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

require_once 'groupDAO.php';
require_once '../api/userDAO.php';
/**
 * Groups management
 */
class Groups{
	
	
	//~ private function getParameterValue($paramName, $request_data){
		//~ if (isset($request_data[$paramName]) && $request_data[$paramName]!="" ){
			//~ return $request_data[$paramName];
		//~ }else{
			//~ return NULL;
		//~ }
	//~ }
	
	/**
	 * Get groups list
	 * 
	 * Get informations about groups
	 * 
	 * @url GET 
	 * 
	 * @param string groupNameFilter [optional] Only retreive groups with groupName containing that string (filter conbination is AND)
	 * @param string groupDescritpionFilter [optional] Only retreive groups with description containing that string (filter conbination is AND)
	 * 
	 * @return array {@type Group}
	 */
	function getAll( $groupNameFilter=null, $groupDescritpionFilter=null, $protecting=null, $order=null){
		#Array param is legacy from previous (initial) version of Restler 
		$params=array("order" =>$order,
					  "groupNameFilter" =>$groupNameFilter,
					  "groupDescritpionFilter" =>$groupDescritpionFilter,
		);
		return $this->get(null, $params);
	}
	
	/**
	 * Get a group
	 * 
	 * Get informations about a group
	 * 
	 * @url GET :groupName
	 * 
	 * @param string groupName group identifer
	 * 
	 * @return Group
	 */
	function getOne($groupName){
		return $this->get($groupName);
	}
	private function get($groupName=NULL, $request_data = NULL){
		try{
			return getGroup($groupName, $request_data);
		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	}
	/**
	 * Membership
	 * 
	 * Get users of a particular group
	 *  
	 * @url GET :groupName/members
	 * 
	 * @param string groupName group identifier
	 * @param int withLog [optional] {@choice 0,1} If set to 1 retreive only users with records in logs,  If set to 1 retreive only users without records in logs
	 * @param string userNameFilter [optional] Only retreive user with userName containing that string (filter conbination is AND)
	 * @param string firstNameFilter [optional] Only retreive user with first name containing that string (filter conbination is AND)
	 * @param string lastNameFilter [optional] Only retreive user with last name containing that string (filter conbination is AND)
	 * @param string emailAddressFilter [optional] Only retreive user with email address containing that string (filter conbination is AND)
	 * @param string entityFilter [optional] Only retreive user with entity containing that string (filter conbination is AND)
	 * @param string extraFilter [optional] Only retreive user with extra data containing that string (filter conbination is AND)
	 * @param string order [optional] "SQL Like" order clause based on User properties
	 * 
	 * @return array Group members {@type User}
	 */
	function getMembers($groupName, $withLog=null , $userNameFilter=null, $firstNameFilter=null, $lastNameFilter=null, $emailAddressFilter=null, $entityFilter=null, $extraFilter=null, $order=null){
		try{
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

			return getGroupMembers($groupName, $params);
		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	}

	/**
	 * Create a group
	 * 
	 * Add a new users group to the system
	 * 
	 * @url POST 
	 * 
	 * @param string groupName group identifier
	 * @param string description [Optional] group description
	 * 
	 * @return Group newly created Group
	 */
	function create($groupName, $description = NULL){
		try{
			return addGroup($groupName,  $description);
		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	}
	/**
	 * Delete a group
	 * 
	 * Remove a group from the system
	 * 
	 * @url DELETE :groupName
	 * 
	 * @param string groupName group identifer
	 * 
	 * @return Group deleted group
	 */
	function delete($groupName){
		try{
			return deleteGroup($groupName);
		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	}
	/**
	 * Update a group
	 * 
	 * Update an particular group properties
	 * 
	 * @url PUT :groupName
	 * 
	 * @param string groupName group identifier
	 * @param string description [Optional] group description
	 * 
	 * @return Group newly created Group
	 */
	function update($groupName , $description=null){
		try{
			return updateGroup($groupName,  $description);
		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	}
	
	
	/*
	 * @url GET :groupName/users
	 *
	 function getGroupMembers($groupName){
		try{
			return getGroupMembers($groupName);
		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	 }*/
	/**
	 * Membership
	 * 
	 * Add a particular user to a particular group
	 * 
	 * @url PUT :groupName/users/:userName
	 * 
	 * @param string groupName group identifier
	 * @param string userName user identifier
	 * 
	 * @return Group updated group 
	 */
	 function addGroupMember($groupName, $userName){
		try{
			$this->get($groupName);
			$u= new Users();
			$u->getOne($userName);
			return addUserToGroup($userName, $groupName);
		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	 }
	/**
	 * Cancel membership
	 * 
	 * Remove a particular user from a particular group
	 *  
	 * @url DELETE :groupName/users/:userName
	 * 
	 * @param string groupName group identifier
	 * @param string userName user idenfier
	 * 
	 * @return Group group updated
	 */
	 function removeGroupMember($groupName, $userName){
		try{
			$this->getOne($groupName);
			$u=new Users();
			$u->getOne($userName);
			return removeUserFromGroup($userName, $groupName);
		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	 }
	 
}
