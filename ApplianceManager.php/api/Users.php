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

require_once 'userDAO.php';
require_once 'Services.php';
require_once 'Groups.php';

/**
 * Users management
 * 
 * PHP Version 7.0
 * 
 * @category ReverseProxy
 * @package  OSA
 * @author   Benoit HERARD <benoit.herard@orange.com>
 * @license  http://www.apache.org/licenses/LICENSE-2.0.htm Apache 2 license
 * @link     https://github.com/zorglub42/OSA/
*/
class Users
{
    
    /**
     * Change password
     * 
     * Change connected user password
     * 
     * @param string $oldPassword new password
     * @param string $newPassword new password
     * 
     * @url PUT me/password
     * 
     * @return User
     */
    function resetPassword($oldPassword, $newPassword)
    {
        $me=getRequestor();
        if (!isset($oldPassword) or $oldPassword == "") {
            throw new RestException(400, "oldPassword parameter is required");
        }
        if (!isset($newPassword) or $newPassword == "") {
            throw new RestException(400, "newPassword parameter is required");
        }
            
        $me= $this->_get($me);
        if ($me["password"] != $oldPassword) {
            throw new RestException(
                400,
                "current password does not match oldPassword parameter"
            );
        }
        return updateUserPassword($me["userName"], $newPassword);
    }
    
    /**
     * Get current user
     * 
     * Get connected user description
     * 
     * @url GET me
     * 
     * @return User
     */
    function whoAmI()
    {
        try {
             $me=getRequestor();
             return $this->_get($me);
        } catch (Exception $e){
            if (is_numeric($e->getCode())) {
                throw new RestException($e->getCode(), $e->getMessage());
            } else {
                throw new RestException(500, $e->getMessage());
            }

        }
    }
    
    /**
     * Get unset quotas
     * 
     * Get quotas witch are not yet defined for a particular user 
     * (based on services requiring users quotas settings)
     * 
     * @param string $userName user identifer
     *  
     * @url GET :userName/quotas/unset
     * 
     * @return array List of potentials quotas {@type Quota}
     */
    function getUnsetQuotaForUSer($userName)
    {
        try{
            $this->getOne($userName);
            return getUnsetQuota($userName);
        }catch (Exception $e){
            if (is_numeric($e->getCode())) {
                throw new RestException($e->getCode(), $e->getMessage());
            } else {
                throw new RestException(500, $e->getMessage());
            }

        }
    }
    /**
     * Get user's quotas
     * 
     * Reteive all defined quotas for a particular user
     * 
     * @param string $userName user identifier
     * 
     * @url GET :userName/quotas
     * 
     * @return array list of defined quotas for this user {@type Quota}
     */
    function getAllQuotasForUser($userName)
    {
        $this->getOne($userName);
        return $this->_getQuotaForUser($userName);
    }
    /**
     * Get user's quota for a service
     * 
     * Reteive defined quotas for a particular user and a particular service
     * 
     * @param string $userName    user identifier
     * @param string $serviceName service identifier
     * 
     * @url GET :userName/quotas/:serviceName
     * 
     * @return Quota quotas for this user and this service  {@type Quota}
     */
    function getQuotasForUserAndService($userName, $serviceName)
    {
        $this->getOne($userName);
        return $this->_getQuotaForUser($userName, $serviceName);
    }
    
    /**
     * Get User quota
     * 
     * @param string $userName    User ID
     * @param string $serviceName [optional] service id
     * 
     * @return array Matching quotas
     */
    private function _getQuotaForUser($userName, $serviceName=null)
    {
        try{
            return getUserQuota($userName, $serviceName);
        }catch (Exception $e){
            if (is_numeric($e->getCode())) {
                throw new RestException($e->getCode(), $e->getMessage());
            } else {
                throw new RestException(500, $e->getMessage());
            }

        }
    }
    /**
     * Add quotas
     * 
     * Add quotas on a particular service to a particular user
     * 
     * @param string $userName    user identifier
     * @param string $serviceName group identifier
     * @param int    $reqSec      maximum number of request per seconds allowed
     * @param int    $reqDay      maximum number of request per days allowed
     * @param int    $reqMonth    maximum number of request per months allowed
     * 
     * @url POST :userName/quotas/:serviceName
     * 
     * @return Quota added quota
     */
    function createQuotaForUser($userName,$serviceName, $reqSec, $reqDay, $reqMonth)
    {
        try{
            // Array param is legacy from previous (initial) version of Restler 
            $params=array("reqSec" =>$reqSec,
                          "reqDay" =>$reqSec,
                          "reqMonth" =>$reqSec,
            );
            $this->_get($userName);
            $s= new Services();
            $s->getOne($serviceName);
            return addUserQuota($userName, $serviceName, $params);
        }catch (Exception $e){
            if (is_numeric($e->getCode())) {
                throw new RestException($e->getCode(), $e->getMessage());
            } else {
                throw new RestException(500, $e->getMessage());
            }

        }
    }
    /**
     * Get available groups
     * 
     * Get groups where user is not yet a member
     * 
     * @param string $userName user identifier
     * 
     * @url GET :userName/groups/available
     * 
     * @return array group list {@type Group}
     */
    function getAvailableGroupForUser($userName)
    {
        try{
            $this->getOne($userName);
            return getAvailableGroup($userName);
        }catch (Exception $e){
            if (is_numeric($e->getCode())) {
                throw new RestException($e->getCode(), $e->getMessage());
            } else {
                throw new RestException(500, $e->getMessage());
            }

        }
    }
    
    /**
     * Get groups membership
     * 
     * Get list of group where the user is a member
     * 
     * Get groups membership for a particular user
     * 
     * @param string $userName user identifier
     * 
     * @url GET :userName/groups
     * 
     * @return array List of user's groups {@type Group}
     */
    function geListOfGroupForUser($userName)
    {
        return $this->_getUserGroup($userName);
    } 
    
    /**
     * Get group membership
     * 
     * Get a particular group membership for a particular user
     * 
     * @param string $userName  user identifier
     * @param string $groupName group identifier
     * 
     * @url GET :userName/groups/:groupName
     * 
     * @return Group List of user's groups 
     */
    public function getGroupForUser($userName, $groupName)
    {
        return $this->_getUserGroup($userName, $groupName);
    }
    
    /**
     * Get users's groups
     * 
     * @param string $userName  user id
     * @param string $groupName group id
     * 
     * @return array matching UserGroup
    */
    private function _getUserGroup($userName=null, $groupName=null)
    {
        try{
            return getDAOUserGroup($userName, $groupName);
        }catch (Exception $e){
            if (is_numeric($e->getCode())) {
                throw new RestException($e->getCode(), $e->getMessage());
            } else {
                throw new RestException(500, $e->getMessage());
            }

        }
    }
    /**
     * Remove group
     * 
     * Remove a particular user from a particular group
     * 
     * @param string $userName  user identifier
     * @param string $groupName group identifier
     * 
     * @url DELETE :userName/groups/:groupName
     * 
     * @return Group removed group
     */
    function removeUserGroup($userName, $groupName)
    {
        try{
            $this->_get($userName);
            
            $g= new Groups();
            $g->getOne($groupName);
            
            return removeUserFromGroup($userName, $groupName);
        }catch (Exception $e){
            if (is_numeric($e->getCode())) {
                throw new RestException($e->getCode(), $e->getMessage());
            } else {
                throw new RestException(500, $e->getMessage());
            }

        }
    }
    /**
     * Add group
     * 
     * Add a paraticular user to a particular group
     * 
     * @param string $userName  user identifier
     * @param string $groupName group identifier
     * 
     * @url POST :userName/groups/:groupName
     * 
     * @return Group added group
     */
    function addUserGroup($userName, $groupName)
    {
        try{
            $this->_get($userName);
            $g=new Groups();
            $g->getOne($groupName);
            return addUserToGroup($userName, $groupName);
        }catch (Exception $e){
            if (is_numeric($e->getCode())) {
                throw new RestException($e->getCode(), $e->getMessage());
            } else {
                throw new RestException(500, $e->getMessage());
            }

        }
    }
    /**
     * Get a user
     * 
     * Get informations about a user
     * 
     * @param string $userName user's identifer
     * 
     * @url GET :userName
     * 
     * @return User
     */
    function getOne($userName)
    {
        return $this->_get($userName);
    }
    /**
     * Get users list
     * 
     * Get informations about users
     * 
     * @param int    $withLog            [optional] {@choice 0,1} If set to 1
     *                                   retreive only users with records in logs,
     *                                   If set to 0 retreive only users without
     *                                   records in logs (filter conbination is AND)
     * @param string $userNameFilter     [optional] Only retreive user with userName
     *                                   containing that string (filter conbination
     *                                   is AND)
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
     * @url GET 
     * 
     * @return array {@type User}
     */
    function getAll(
        $withLog=null, $userNameFilter=null, $firstNameFilter=null,
        $lastNameFilter=null, $emailAddressFilter=null, $entityFilter=null,
        $extraFilter=null, $order=null
    ) {
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
        return $this->_get(null, $params);
    }
    
    /**
     * Get Users
     * 
     * @param string $userName     User id
     * @param string $request_data Filters on properties
     * 
     * @return array Matching users
     */
    private function _get($userName=null, $request_data = null)
    {
        try{
            return getUser($userName, $request_data);
        }catch (Exception $e){
            if (is_numeric($e->getCode())) {
                throw new RestException($e->getCode(), $e->getMessage());
            } else {
                throw new RestException(500, $e->getMessage());
            }

        }
    }
    /**
     * Create user
     * 
     * Create a new user into the system
     * 
     * @param string $userName  user identitfier
     * @param string $password  password to authenticate against OSA
     * @param string $email     user's mail address
     * @param string $endDate   users's validity end date in ISO 8601 full format
     * @param string $firstName [Optional] user's first name
     * @param string $lastName  [Optional] user's last name
     * @param string $entity    [Optional] user's entity
     * @param string $extra     [Optional] users's extra data in free format
     * 
     * @url POST :userName
     * @url POST
     * 
     * @return User newly created user
     */
    function create(
        $userName, $password, $email, $endDate, $firstName=null, $lastName=null,
        $entity=null, $extra=null
    ) {
        try{
            //Array param is legacy from previous (initial) version of Restler 
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
            if (is_numeric($e->getCode())) {
                throw new RestException($e->getCode(), $e->getMessage());
            } else {
                throw new RestException(500, $e->getMessage());
            }

        }
    }

    /**
     * Update
     * 
     * Update user properties
     * 
     * @param string $userName  user identitfier
     * @param string $password  password to authenticate against OSA
     * @param string $email     user's mail address
     * @param string $endDate   users's validity end date in ISO 8601 full format
     * @param string $firstName [Optional] user's first name
     * @param string $lastName  [Optional] user's last name
     * @param string $entity    [Optional] user's entity
     * @param string $extra     [Optional] users's extra data in free format
     * 
     * @url PUT :userName
     * 
     * @return User updated user
     */
    function update(
        $userName, $password, $email, $endDate, $firstName=null,
        $lastName=null, $entity=null, $extra=null
    ) {
        try{
            $this->_get($userName);
            // Array param is legacy from previous (initial) version of Restler 
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
            if (is_numeric($e->getCode())) {
                throw new RestException($e->getCode(), $e->getMessage());
            } else {
                throw new RestException(500, $e->getMessage());
            }

        }
    }

    /**
     * Delete user
     * 
     * Remove user form the system
     * 
     * @param string $userName user identifier
     * 
     * @url DELETE :userName
     * 
     * @return User deleted user
     */
    function delete($userName)
    {
        try{
             return deleteUser($userName);
        }catch (Exception $e){
            if (is_numeric($e->getCode())) {
                throw new RestException($e->getCode(), $e->getMessage());
            } else {
                throw new RestException(500, $e->getMessage());
            }

        }
    }
}
