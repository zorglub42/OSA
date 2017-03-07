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
 * File Name   : ApplianceManager/ApplianceManager.php/counters/Counters.php
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
require_once 'counterDAO.php';

/**
 * Counters and exceeded counters management
 */
class Counters{
	
	
	/**
	 * Get excedeed
	 * 
	 * Get all excedeed counters
	 * 
	 * @param string resourceNameFilter [optional] Only retreive counters with resourceName containing that string (filter conbination is OR)
	 * @param string userNameFilter [optional] Only retreive counters with userName containing that string (filter conbination is OR)
	 * 
	 * @url GET excedeed
	 * 
	 * @return ExcedeedCounter
	 */
	 function getExcedeed($resourceNameFilter=null, $userNameFilter=null){
		try{
			#Array param is legacy from previous (initial) version of Restler 
			$params=array("resourceNameFilter" =>$resourceNameFilter,
						  "userNameFilter" => $userNameFilter,
			);
			return getExceededCounter( $params		);
		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	 }
	
	/**
	 * Get a counter
	 * 
	 * Get a particular counter
	 * 
	 * @url GET :counterName
	 * 
	 * @param string counterName counter identifier
	 * 
	 * @return Counter requested counter
	 */
	function getOne($counterName){
		 return $this->get($counterName);
	}
	/**
	 * Get counters
	 * 
	 * Get counters list
	 * 
	 * @url GET
	 * 
	 * @param string resourceName related resource identifier filter 
	 * @param string userName related user identifier filter
	 * @param string timeUnit related time timeUnit (S: Second, D: Day, M: Month) {@choice S,D,M}
	 *  
	 * @return array Counters list {@type Counter}
	 */
	function getAll($resourceName=null, $userName=null, $timeUnit=null){
		#Array param is legacy from previous (initial) version of Restler 
		$params=array("resourceName" =>$resourceName,
					  "userName" =>$userName,
					  "timeUnit" =>$timeUnit,
		);
		return $this->get(null, $params);
	}
	private function get($counterName=NULL, $request_data=NULL){
		try{
			return getCounter($counterName, $request_data);
		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	 }
	/**
	 * Delete
	 * 
	 * Delete a counter
	 * 
	 * @url DELETE :counterName
	 * 
	 * @param string counterName counter identifier to remove
	 * 
	 * @return Counter
	 */
	 function delete($counterName){
		try{
			$this->get($counterName);
			return deleteCounter($counterName);
		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	 }
	 
	/**
	 * Update a counter
	 * 
	 * Update counter value
	 * 
	 * @url PUT :counterName
	 * 
	 * @param string counter identifier
	 * @param int value value to set
	 * 
	 * @return Counter updated counter
	 */
	 function update($counterName, $value){
		try{
			return updateCounter($counterName, $value);
		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	 }
}
