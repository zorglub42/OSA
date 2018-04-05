<?php
/**
 *  Reverse Proxy as a service
 * 
 * PHP Version 7.0
 * 
 * @category ReverseProxy
 * @package  OSA
 * @author   Benoit HERARD <benoit.herard@orange.com>
 * @license  http://www.apache.org/licenses/LICENSE-2.0.htm Apache 2 license
 * @link     https://github.com/zorglub42/OSA/
*/

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
require_once '../include/commonHeaders.php';
require_once 'counterDAO.php';

/**
 * Counters and exceeded counters management
 * 
 * PHP Version 7.0
 * 
 * @category ReverseProxy
 * @package  OSA
 * @author   Benoit HERARD <benoit.herard@orange.com>
 * @license  http://www.apache.org/licenses/LICENSE-2.0.htm Apache 2 license
 * @link     https://github.com/zorglub42/OSA/
 */
class Counters
{
    
    
    /**
     * Get excedeed
     * 
     * Get all excedeed counters
     * 
     * @param string $resourceNameFilter [optional] Only retreive counters with 
     *                                   resourceName containing that string (filter
     *                                   conbination is OR)
     * @param string $userNameFilter     [optional] Only retreive counters with 
     *                                   userName containing that string (filter
     *                                   conbination is OR)
     * 
     * @url GET excedeed
     * 
     * @return ExcedeedCounter
     */
    function getExcedeed($resourceNameFilter=null, $userNameFilter=null)
    {
        try {
            //Array param is legacy from previous (initial) version of Restler 
            $params=array("resourceNameFilter" =>$resourceNameFilter,
                          "userNameFilter" => $userNameFilter,
            );
            return getExceededCounter($params);
        }catch (Exception $e) {
            throw new RestException($e->getCode(), $e->getMessage());
        }
    }
    
    /**
     * Get a counter
     * 
     * Get a particular counter
     * 
     * @param string $counterName counter identifier
     * 
     * @url GET :counterName
     * 
     * @return Counter requested counter
     */
    function getOne($counterName)
    {
         return $this->_get($counterName);
    }

    /**
     * Get counters
     * 
     * Get counters list
     * 
     * @param string $resourceName related resource identifier filter 
     * @param string $userName     related user identifier filter
     * @param string $timeUnit     related time timeUnit 
     *                             (S: Second, D: Day, M: Month) {@choice S,D,M}
     * 
     * @url GET
     *  
     * @return array Counters list {@type Counter}
     */
    function getAll($resourceName=null, $userName=null, $timeUnit=null)
    {
        //Array param is legacy from previous (initial) version of Restler 
        $params=array("resourceName" =>$resourceName,
                      "userName" =>$userName,
                      "timeUnit" =>$timeUnit,
        );
        return $this->_get(null, $params);
    }

    /**
     * Get counter
     * 
     * Get counter from DAO
     * 
     * @param string $counterName  optional, counter name to get
     * @param array  $request_data optional, filter to apply
     *                             $request_data["resourceName"]
     *                             $request_data["userName"]
     *                             $request_data["timeUnit"] (M|D|S)
     * 
     * @return array Matching counters
     */
    private function _get($counterName=null, $request_data=null)
    {
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
     * @param string $counterName counter identifier to remove
     * 
     * @url DELETE :counterName
     * 
     * @return Counter
     */
    function delete($counterName)
    {
        try{
            $this->_get($counterName);
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
     * @param string $counterName identifier
     * @param int    $value       value to set
     * 
     * @url PUT :counterName
     * 
     * @return Counter updated counter
     */
    function update($counterName, $value)
    {
        try{
            return updateCounter($counterName, $value);
        }catch (Exception $e){
            throw new RestException($e->getCode(), $e->getMessage());
        }
    }
}
