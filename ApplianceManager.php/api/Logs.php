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
 * File Name   : ApplianceManager/ApplianceManager.php/logs/Logs.php
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

require_once 'logDAO.php';

/**
 * Logs management
 * 
 * PHP Version 7.0
 * 
 * @category ReverseProxy
 * @package  OSA
 * @author   Benoit HERARD <benoit.herard@orange.com>
 * @license  http://www.apache.org/licenses/LICENSE-2.0.htm Apache 2 license
 * @link     https://github.com/zorglub42/OSA/
 */
class Logs
{
    
    
    /**
     * Get a Log
     * 
     * Get a particular log details
     * 
     * @param int $id Log identifier
     * 
     * @url GET :id
     * 
     * @return Log
     */
    function getOne($id)
    {
         return $this->_get($id);
    }

    /**
     * Get Logs
     * 
     * Get paginated logs
     * 
     * @param string $serviceName      [optional] Only retreive logs with 
     *                                 serviceName containing that string 
     *                                 (filter conbination is AND)
     * @param string $userName         [optional] Only retreive logs with userName
     *                                 containing that string
     *                                 (filter conbination is AND)
     * @param int    $status           [optional] Only retreive logs with HTTP 
     *                                 return status equals to this parameter
     *                                 (filter conbination is AND)
     * @param string $message          [optional] Only retreive logs with message
     *                                 containing that string
     *                                 (filter conbination is AND)
     * @param string $frontEndEndPoint [optional] Only retreive logs with 
     *                                 frontEndEndPoint containing that string
     *                                 (filter conbination is AND)
     * @param string $from             [optional] Only retreive logs from this date
     *                                 in ISO 8601 full format
     *                                 (filter conbination is AND)
     * @param string $until            [optional] Only retreive logs untill this 
     *                                 date in ISO 8601 full format
     *                                 (filter conbination is AND)
     * @param int    $offset           [optional] page number
     * @param string $order            [optional] "SQL Like" order clause based on
     *                                 Log properties
     * 
     * @url GET 
     * 
     * @return LogsPage
     */
    function getAll(
        $serviceName=null, $userName=null, $status=null, $message=null,
        $frontEndEndPoint=null, $from=null, $until=null, $offset=null, $order=null
    ) {
        //Array param is legacy from previous (initial) version of Restler 
        $params=array("serviceName" => $serviceName,
                      "userName" =>$userName,
                      "status" => $status,
                      "message" => $message,
                      "frontEndEndPoint" => $frontEndEndPoint,
                      "from" => $from,
                      "until" => $until,
                      "offset" => $offset,
                      "order" => $order,
        );
        return $this->_get(null, $params);
    }
    
    /**
     * Get Logs
     * 
     * Get logs from DB
     * 
     * @param int   $id           Log id to get (to get only one)
     * @param array $request_data Filter parameters
     *                            $request_data["serviceName"]
     *                            $request_data["userName"]
     *                            $request_data["status"]
     *                            $request_data["message"]
     *                            $request_data["frontEndEndPoint"]
     *                            $request_data["from"]
     *                            $request_data["until"]
     *                            $request_data["offset"]
     *                            $request_data["order"]
     * 
     * @return Array matching logs
     */
    private function _get($id=null, $request_data=null)
    {
        try{
            return getLogs($id, $request_data);
        }catch (Exception $e){
            throw new RestException($e->getCode(), $e->getMessage());
        }
    }
    
}
