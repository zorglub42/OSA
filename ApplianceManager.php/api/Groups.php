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

require_once '../include/commonHeaders.php';

require_once 'groupDAO.php';
require_once '../api/userDAO.php';
/**
 * Groups management API
 * 
 * PHP Version 7.0
 * 
 * @category ReverseProxy
 * @package  OSA
 * @author   Benoit HERARD <benoit.herard@orange.com>
 * @license  http://www.apache.org/licenses/LICENSE-2.0.htm Apache 2 license
 * @link     https://github.com/zorglub42/OSA/
 */
class Groups
{
    
    

    /**
     * Get groups list
     * 
     * Get informations about groups
     * 
     * @param string $groupNameFilter        [optional] Only retreive groups with
     *                                       groupName containing that string 
     *                                       (filter conbination is AND)
     * @param string $groupDescritpionFilter [optional] Only retreive groups with
     *                                       description containing that string 
     *                                       (filter conbination is AND)
     * @param string $order                  [optional] Order clause
     * 
     * @url GET 
     * 
     * @return array {@type Group}
     */
    function getAll(
        $groupNameFilter=null, $groupDescritpionFilter=null, $order=null
    ) {
        //Array param is legacy from previous (initial) version of Restler 
        $params=array("order" =>$order,
                      "groupNameFilter" =>$groupNameFilter,
                      "groupDescritpionFilter" =>$groupDescritpionFilter,
        );
        return $this->_get(null, $params);
    }
    
    /**
     * Get a group
     * 
     * Get informations about a group
     * 
     * @param string $groupName group identifer
     * 
     * @url GET :groupName
     * 
     * @return Group
     */
    function getOne($groupName)
    {
        return $this->_get($groupName);
    }

    /**
     * Get groups
     * 
     * Get one or a list of groups
     * 
     * @param string $groupName    [optional] If set, return this group
     * @param array  $request_data [optional] Filter to get a list of groups
     *                             $request_data["groupDescritpionFilter"]
     *                             $request_data["groupNameFilter"]
     * 
     * @return array matching groups
    */
    private function _get($groupName=null, $request_data = null)
    {
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
     * @param string $groupName          group identifier
     * @param int    $withLog            [optional] {@choice 0,1} If set to 1 
     *                                   retreive only users with records in logs,
     *                                   If set to 1 retreive only users without 
     *                                    records in logs
     * @param string $userNameFilter     [optional] Only retreive user with userName
     *                                   containing that string 
     *                                   (filter conbination is AND)
     * @param string $firstNameFilter    [optional] Only retreive user with first 
     *                                   name containing that string 
     *                                   (filter conbination is AND)
     * @param string $lastNameFilter     [optional] Only retreive user with last 
     *                                   name containing that string 
     *                                   (filter conbination is AND)
     * @param string $emailAddressFilter [optional] Only retreive user with email
     *                                   address containing that string
     *                                   (filter conbination is AND)
     * @param string $entityFilter       [optional] Only retreive user with entity
     *                                   containing that string
     *                                   (filter conbination is AND)
     * @param string $extraFilter        [optional] Only retreive user with extra 
     *                                   data containing that string
     *                                   (filter conbination is AND)
     * @param string $order              [optional] "SQL Like" order clause based on
     *                                   User properties
     *  
     * @url GET :groupName/members
     * 
     * @return array Group members {@type User}
     */
    function getMembers(
        $groupName, $withLog=null , $userNameFilter=null, $firstNameFilter=null,
        $lastNameFilter=null, $emailAddressFilter=null, $entityFilter=null,
        $extraFilter=null, $order=null
    ) {
        try{
            //Array param is legacy from previous (initial) version of Restler 
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
     * @param string $groupName   group identifier
     * @param string $description [Optional] group description
     * 
     * @url POST 
     * 
     * @return Group newly created Group
     */
    function create($groupName, $description = null)
    {
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
     * @param string $groupName group identifer
     * 
     * @url DELETE :groupName
     * 
     * @return Group deleted group
     */
    function delete($groupName)
    {
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
     * @param string $groupName   group identifier
     * @param string $description [Optional] group description
     * 
     * @url PUT :groupName
     * 
     * @return Group newly created Group
     */
    function update($groupName , $description=null)
    {
        try{
            return updateGroup($groupName,  $description);
        }catch (Exception $e){
            throw new RestException($e->getCode(), $e->getMessage());
        }
    }
    
    
    /**
     * Membership
     * 
     * Add a particular user to a particular group
     * 
     * @param string $groupName group identifier
     * @param string $userName  user identifier
     * 
     * @url PUT :groupName/users/:userName
     * 
     * @return Group updated group 
     */
    function addGroupMember($groupName, $userName)
    {
        try{
            $this->_get($groupName);
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
     * @param string $groupName group identifier
     * @param string $userName  user idenfier
     *  
     * @url DELETE :groupName/users/:userName
     * 
     * @return Group group updated
     */
    function removeGroupMember($groupName, $userName)
    {
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
