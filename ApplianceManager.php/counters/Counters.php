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


class Counters{
	
	
	function getParameterValue($paramName, $request_data){
		if (isset($request_data[$paramName]) && $request_data[$paramName]!="" ){
			return $request_data[$paramName];
		}else{
			return NULL;
		}
	}
	
	
	
	/**
	 * @url GET excedeed
	 */
	 function getExcedeed( $request_data=NULL){
		try{
			return getExceededCounter( $request_data);
		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	 }
	
	/**
	 * @url GET 
	 * @url GET :counterName
	 */
	 function get($counterName=NULL, $request_data=NULL){
		try{
			return getCounter($counterName, $request_data);
		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	 }
	/**
	 * @url DELETE 
	 * @url DELETE :counterName
	 */
	 function delete($counterName=NULL, $request_data=NULL){
		try{
			return deleteCounter($counterName, $request_data);
		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	 }
	/**
	 * @url PUT 
	 * @url PUT :counterName
	 */
	 function update($counterName=NULL, $request_data=NULL){
		try{
			return updateCounter($counterName, $request_data);
		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	 }
}
