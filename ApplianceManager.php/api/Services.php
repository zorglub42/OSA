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
require_once 'serviceDAO.php';
require_once '../api/userDAO.php';

/**
 * Services managements
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
class Services
{



    /**
     * Create header mapping
     *
     * Create header mapping form a particular service and a particular property
     *
     * @param string $serviceName       service identifier
     * @param string $userProperty      User property to map
     * @param string $headerName        HTTP header name
     * @param int    $extendedAttribute {@choice 0,1}
     *
     * @url POST :serviceName/headers-mapping/:userProperty
     *
     * @return HeaderMapping Created header
     */
    function createHeadersMapping(
        $serviceName, $userProperty, $headerName, $extendedAttribute=0
    ) {
        try{
            return createServiceHeadersMapping(
                $serviceName, $userProperty, $headerName, $extendedAttribute
            );
        }catch (Exception $e) {
            throw new RestException($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Create headers mapping
     *
     * Create headers mapping for alist of user properties for a particular header
     *
     * @param string $serviceName Service identifier
     * @param array  $mapping     {@type HeaderMappingCreation} Headers to map
     * @param int    $noApply     [Optional] {@choice 0,1} Don't apply apache 
     *                            configuration?
     *                            (O: no 1: yes, default 0)
     *
     * @url POST :serviceName/headers-mapping/
     *
     * @return array {@type HeaderMapping} Created headers
     */
    function setHeadersMappings($serviceName, $mapping, $noApply=0)
    {
        try{
            $this->deleteHeadersMapping($serviceName);
            foreach ($mapping as $header) {
                if (!empty($header->userProperty) && !empty($header->headerName)) {
                    $this->createHeadersMapping(
                        $serviceName, $header->userProperty,
                        $header->headerName, $header->extendedAttribute
                    );
                }
            }
            if ($noApply==0) {
                if (!applyApacheConfiguration()) {
                    throw new RestException(500, "Invalid apache configuration");
                }
            }
            return $this->getHeadersMapping($serviceName);
        }catch (Exception $e) {
            throw new RestException($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Get headers mapping
     *
     * Get all headers mapping for a particular service
     *
     * @param string $serviceName Service identifier
     *
     * @url GET :serviceName/headers-mapping
     *
     * @return array {@type HeaderMapping} Headers
     */
    function getHeadersMapping($serviceName)
    {
        try{
            $s = new Services();
            $s->getOne($serviceName);
            return getServiceHeadersMapping($serviceName, null);
        }catch (Exception $e) {
            throw new RestException($e->getCode(), $e->getMessage());
        }
    }
    /**
     * Get property headers mapping
     *
     * Get header mapping for a particular service and a particular user property
     *
     * @param string $serviceName  Service identifier
     * @param string $userProperty Service identifier
     *
     * @url GET :serviceName/headers-mapping/:userProperty
     *
     * @return array {@type HeaderMapping} Requested Header (array of 1 item)
     */
    function getUserPropertyHeadersMapping($serviceName, $userProperty)
    {
        try{
            $s = new Services();
            $s->getOne($serviceName);
            return getServiceHeadersMapping($serviceName, $userProperty);
        }catch (Exception $e) {
            throw new RestException($e->getCode(), $e->getMessage());
        }
    }


    /**
     * Delete headers mapping
     *
     * Delete all headers mapping for a particular service
     *
     * @param string $serviceName Service identifier
     *
     * @url DELETE :serviceName/headers-mapping
     *
     * @return array {@type HeaderMapping} Currint list of Headers for this service
     */
    function deleteHeadersMapping($serviceName)
    {
        try{
            $s = new Services();
            $s->getOne($serviceName);
            return deleteServiceHeadersMapping($serviceName);
        }catch (Exception $e) {
            throw new RestException($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Get a service
     *
     * Get details about a particular Service
     *
     * @param string $serviceName Service identifier
     *
     * @url GET :serviceName
     *
     * @return Service requested Service
     */
    function getOne($serviceName)
    {
         return $this->_get($serviceName);
    }
    
    /**
     * Get Services
     *
     * Get a list of Services
     *
     * @param int    $withLog                           [optional] {@choice 0,1} If
     *                                                  set to 1 retreive only
     *                                                  services with records in
     *                                                  logs,  If set to 0 retreive
     *                                                  only services without
     *                                                  records in logs
     *                                                  (filter conbination is AND)
     * @param string $serviceNameFilter                 [optional] Only retreive
     *                                                  services with serviceName
     *                                                  containing that string
     *                                                  (filter conbination is AND)
     * @param string $groupNameFilter                   [optional] Only retreive
     *                                                  services with groupName
     *                                                  containing that string
     *                                                  (filter conbination is AND)
     * @param string $frontEndEndPointFilter            [optional] Only retreive
     *                                                  service with frontEndEndPoint
     *                                                  containing that string
     *                                                  (filter conbination is AND)
     * @param string $backEndEndPointFilter             [optional] Only retreive
     *                                                  services with backEndEndPoint
     *                                                  containing that string
     *                                                  (filter conbination is AND)
     * @param string $nodeNameFilter                    [optional] Only retreive
     *                                                  services available on that
     *                                                  node
     *                                                  (filter conbination is AND)
     * @param int    $withQuotas                        [optional] {@choice 0,1} 
     *                                                  If set to 1 retreive only
     *                                                  services with any kind of
     *                                                  quotas activated,
     *                                                  If set to 0 retreive only
     *                                                  services  without any kind
     *                                                  of quotas activated
     *                                                  (filter conbination is AND)
     * @param int    $isIdentityForwardingEnabledFilter [optional] {@choice 0,1}
     *                                                  If set to 1 retreive only
     *                                                  services with identity
     *                                                  forwarding enabled,
     *                                                  If set to 0 retreive only
     *                                                  services with identity
     *                                                  forwarding disabled
     *                                                  (filter conbination is AND)
     * @param int    $isGlobalQuotasEnabledFilter       [optional] {@choice 0,1}
     *                                                  If set to 1 retreive only
     *                                                  services with global quotas
     *                                                  enabled,
     *                                                  If set to 0 retreive only
     *                                                  services with global quotas
     *                                                  disabled
     *                                                  (filter conbination is AND)
     * @param int    $isUserQuotasEnabledFilter         [optional] {@choice 0,1}
     *                                                  If set to 1 retreive only
     *                                                  services  with users quotas
     *                                                  enabled,
     *                                                  If set to 0 retreive only
     *                                                  services  with users quotas
     *                                                  disabled 
     *                                                  (filter conbination is AND)
     * @param int    $isPublishedFilter                 [optional] {@choice 0,1}
     *                                                  If set to 1 retreive only
     *                                                  services published on nodes,
     *                                                  If set to 0 retreive only
     *                                                  services not published on
     *                                                  nodes
     *                                                  (filter conbination is AND)
     * @param int    $isHitLoggingEnabledFilter         [optional] {@choice 0,1}
     *                                                  If set to 1 retreive only
     *                                                  services with logs recording
     *                                                  enabled,
     *                                                  If set to 0 retreive only
     *                                                  services with logs recording
     *                                                  disabled
     *                                                  (filter conbination is AND)
     * @param int    $isUserAuthenticationEnabledFilter [optional] {@choice 0,1}
     *                                                  If set to 1 retreive only
     *                                                  services with user 
     *                                                  authentication enabled,
     *                                                  If set to 1 retreive only
     *                                                  services  with user
     *                                                  authentication disabled
     *                                                  (filter conbination is AND)
     * @param string $additionalConfigurationFilter     [optional] Only retreive
     *                                                  services with
     *                                                  additionalConfiguration
     *                                                  containing that
     *                                                  string
     *                                                  (filter conbination is AND)
     * @param string $additionalBackendConnectionConfigurationFilter     [optional] Only retreive
     *                                                  services with
     *                                                  additionalBackendConnectionConfiguration
     *                                                  containing that
     *                                                  string
     *                                                  (filter conbination is AND)
     *
     * @url GET
     *
     * @return array {@type Service} List of Services
     */
    function getAll(
        $withLog=null, $serviceNameFilter=null, $groupNameFilter=null,
        $frontEndEndPointFilter=null, $backEndEndPointFilter=null, 
        $nodeNameFilter=null, $withQuotas=null,
        $isIdentityForwardingEnabledFilter=null, $isGlobalQuotasEnabledFilter=null,
        $isUserQuotasEnabledFilter=null, $isPublishedFilter=null,
        $isHitLoggingEnabledFilter=null, $isUserAuthenticationEnabledFilter=null,
        $additionalConfigurationFilter=null, $additionalBackendConnectionConfigurationFilter=null
    ) {
        //Array param is legacy from previous (initial) version of Restler
        $params=array(
            "withLog" => $withLog,
            "serviceNameFilter" => $serviceNameFilter,
            "groupNameFilter" => $groupNameFilter,
            "frontEndEndPointFilter" => $frontEndEndPointFilter,
            "backEndEndPointFilter" => $backEndEndPointFilter,
            "nodeNameFilter" => $nodeNameFilter,
            "withQuotas" => $withQuotas,
            "isIdentityForwardingEnabledFilter" => 
                $isIdentityForwardingEnabledFilter,
            "isGlobalQuotasEnabledFilter" => $isGlobalQuotasEnabledFilter,
            "isUserQuotasEnabledFilter" => $isUserQuotasEnabledFilter,
            "isPublishedFilter" => $isPublishedFilter,
            "isHitLoggingEnabledFilter" => $isHitLoggingEnabledFilter,
            "isUserAuthenticationEnabledFilter" => 
                $isUserAuthenticationEnabledFilter,
            "additionalConfigurationFilter" => $additionalConfigurationFilter,
            "additionalBackendConnectionConfigurationFilter" => $additionalBackendConnectionConfigurationFilter,
            );

        return $this->_get(null, $params);
    }

    /**
     * Get one or more services
     * 
     * @param string $serviceName  Id of service to get (if one)
     * @param string $request_data Filter
     * 
     * @return array Matching services
     */
    private function _get($serviceName=null, $request_data=null)
    {
        try{
            return getService($serviceName, $request_data);
        }catch (Exception $e) {
            throw new RestException($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Create service
     *
     * Create and deploy a new Service
     *
     * @param string $serviceName                              Serice identifier
     * @param string $frontEndEndPoint                         URI on frontend node
     * @param string $backEndEndPoint                          URL to backend server
     * @param int    $isPublished                              [Optional] Is tis service deployed?
     *                                                         (O: no 1: yes, default 1)
     *                                                         {@choice 0,1}
     * @param string $additionalConfiguration                  [Optional] Additional Apache 
     *                                                         "&lt;Location&gt;" tag directives
     * @param string $additionalBackendConnectionConfiguration [Optional] Additional Apache 
     *                                                         "ProxyPass" tag directives
     * @param int    $isHitLoggingEnabled                      [Optional] {@choice 0,1} Is log
     *                                                         recording is enabled?
     *                                                         (O: no 1: yes, default 0)
     * @param int    $onAllNodes                               [Optional] {@choice 0,1} Is this
     *                                                         service alavaliable on all
     *                                                         publshed nodes?
     *                                                         (O: no 1: yes, default 1)
     * @param int    $isUserAuthenticationEnabled              [Optional] {@choice 0,1} Is user
     *                                                         authentication enabled?
     *                                                         (O: no 1: yes, default 0)
     * @param string $groupName                                [Optional] User must be a member
     *                                                         of this group to use this service
     *                                                         (required if 
     *                                                         isUserAuthenticationEnabled=1)
     * @param int    $isIdentityForwardingEnabled              [Optional] {@choice 0,1}
     *                                                         Is authenticated user's identity
     *                                                         forwarded to backend system?
     *                                                         (O: no 1: yes, default 0)
     * @param int    $isAnonymousAllowed                       [Optional] {@choice 0,1}
     *                                                         Is authentication absolutly 
     *                                                         required to invoke this service 
     *                                                         or anonymous access is also 
     *                                                         possible?
     *                                                         (O: no 1: yes, default 0)
     * @param string $backEndUsername                          [Optional] username to 
     *                                                         authenticate against backend 
     *                                                         system (basic authentication),
     *                                                         use "%auto%" to use credentials
     *                                                         received on OSA against backend
     * @param string $backEndPassword                          [Optional] password to
     *                                                         authenticate against
     *                                                         backend system
     * @param string $loginFormUri                             [Optional] Login from URL to 
     *                                                         redirect to in case of
     *                                                         unauthenticated access on a
     *                                                         compliant node
     * @param int    $isGlobalQuotasEnabled                    [Optional] {@choice 0,1} Is global
     *                                                         quotas enabled?
     *                                                         (O: no 1: yes, default 0)
     * @param int    $reqSec                                   [Optional] Maximun number of 
     *                                                         requests allowed per second
     *                                                         (Required if 
     *                                                         isGlobalQuotasEnabled=1)
     * @param int    $reqDay                                   [Optional] Maximun number of
     *                                                         requests allowed per second
     *                                                         (Required if
     *                                                         isGlobalQuotasEnabled=1)
     * @param int    $reqMonth                                 [Optional] Maximun number of
     *                                                         requests allowed per second
     *                                                         (Required if
     *                                                         isGlobalQuotasEnabled=1)
     * @param int    $isUserQuotasEnabled                      [Optional] {@choice 0,1}
     *                                                         Are quotas enabled at user level?
     *                                                         (O: no 1: yes, default 0)
     *
     * @url POST :serviceName
     * @url POST
     *
     * @return Service created service
     */
    function addService(
        $serviceName, $frontEndEndPoint, $backEndEndPoint,
        $isPublished=null,  $additionalConfiguration=null, $additionalBackendConnectionConfiguration=null,
        $isHitLoggingEnabled=null, $onAllNodes=null,
        $isUserAuthenticationEnabled=null, $groupName=null, 
        $isIdentityForwardingEnabled=null, $isAnonymousAllowed=null,
        $backEndUsername=null, $backEndPassword=null, $loginFormUri=null,
        $isGlobalQuotasEnabled=null, $reqSec=null, $reqDay=null, $reqMonth=null,
        $isUserQuotasEnabled=null
    ) {
        try{
            //Array param is legacy from previous (initial) version of Restler
            $params=array(
                "isPublished" => $isPublished,
                "isGlobalQuotasEnabled" => $isGlobalQuotasEnabled,
                "frontEndEndPoint" => $frontEndEndPoint,
                "backEndEndPoint" => $backEndEndPoint,
                "additionalConfiguration" => $additionalConfiguration,
                "additionalBackendConnectionConfiguration" => $additionalBackendConnectionConfiguration,
                "isAnonymousAllowed" => $isAnonymousAllowed,
                "isHitLoggingEnabled" => $isHitLoggingEnabled,
                "onAllNodes" => $onAllNodes,
                "isUserAuthenticationEnabled" => $isUserAuthenticationEnabled,
                "reqSec" => $reqSec,
                "reqDay" => $reqDay,
                "reqMonth" => $reqMonth,
                "isIdentityForwardingEnabled" => $isIdentityForwardingEnabled,
                "backEndUsername" => $backEndUsername,
                "backEndPassword" => $backEndPassword,
                "isUserQuotasEnabled" => $isUserQuotasEnabled,
                "loginFormUri" => $loginFormUri,
                "groupName" =>$groupName,
            );

            return createService($serviceName, $params);
        }catch (Exception $e) {
            throw new RestException($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Update a service
     *
     * @param string $serviceName                              Serice identifier
     * @param string $frontEndEndPoint                         URI on frontend node
     * @param string $backEndEndPoint                          URL to backend server
     * @param int    $isPublished                              [Optional] Is tis service deployed?
     *                                                         (O: no 1: yes, default 1)
     *                                                         {@choice 0,1}
     * @param string $additionalConfiguration                  [Optional] Additional Apache 
     *                                                         "&lt;Location&gt;" tag directives
     * @param string $additionalBackendConnectionConfiguration [Optional] Additional Apache 
     *                                                         "ProxyPass" tag directives
     * @param int    $isHitLoggingEnabled                      [Optional] {@choice 0,1} Is log
     *                                                         recording is enabled?
     *                                                         (O: no 1: yes, default 0)
     * @param int    $onAllNodes                               [Optional] {@choice 0,1} Is this
     *                                                         service alavaliable on all
     *                                                         publshed nodes?
     *                                                         (O: no 1: yes, default 1)
     * @param int    $isUserAuthenticationEnabled              [Optional] {@choice 0,1} Is user
     *                                                         authentication enabled?
     *                                                         (O: no 1: yes, default 0)
     * @param string $groupName                                [Optional] User must be a member
     *                                                         of this group to use this service
     *                                                         (required if 
     *                                                         isUserAuthenticationEnabled=1)
     * @param int    $isIdentityForwardingEnabled              [Optional] {@choice 0,1}
     *                                                         Is authenticated user's identity
     *                                                         forwarded to backend system?
     *                                                         (O: no 1: yes, default 0)
     * @param int    $isAnonymousAllowed                       [Optional] {@choice 0,1}
     *                                                         Is authentication absolutly 
     *                                                         required to invoke this service 
     *                                                         or anonymous access is also 
     *                                                         possible?
     *                                                         (O: no 1: yes, default 0)
     * @param string $backEndUsername                          [Optional] username to 
     *                                                         authenticate against backend 
     *                                                         system (basic authentication),
     *                                                         use "%auto%" to use credentials
     *                                                         received on OSA against backend
     * @param string $backEndPassword                          [Optional] password to
     *                                                         authenticate against
     *                                                         backend system
     * @param string $loginFormUri                             [Optional] Login from URL to 
     *                                                         redirect to in case of
     *                                                         unauthenticated access on a
     *                                                         compliant node
     * @param int    $isGlobalQuotasEnabled                    [Optional] {@choice 0,1} Is global
     *                                                         quotas enabled?
     *                                                         (O: no 1: yes, default 0)
     * @param int    $reqSec                                   [Optional] Maximun number of 
     *                                                         requests allowed per second
     *                                                         (Required if 
     *                                                         isGlobalQuotasEnabled=1)
     * @param int    $reqDay                                   [Optional] Maximun number of
     *                                                         requests allowed per second
     *                                                         (Required if
     *                                                         isGlobalQuotasEnabled=1)
     * @param int    $reqMonth                                 [Optional] Maximun number of
     *                                                         requests allowed per second
     *                                                         (Required if
     *                                                         isGlobalQuotasEnabled=1)
     * @param int    $isUserQuotasEnabled                      [Optional] {@choice 0,1}
     *                                                         Are quotas enabled at user level?
     *                                                         (O: no 1: yes, default 0)
     * @param int    $noApply                                  [Optional] {@choice 0,1} Don't 
     *                                                         apply apache configuration?
     *                                                         (O: no 1: yes, default 0)
     *
     * @url PUT :serviceName
     *
     * @return Service Updated service
     */
    function update($serviceName, $frontEndEndPoint=null, $backEndEndPoint=null,
        $isPublished=null,  $additionalConfiguration=null,  $additionalBackendConnectionConfiguration=null,
        $isHitLoggingEnabled=null, $onAllNodes=null,
        $isUserAuthenticationEnabled=null, $groupName=null,
        $isIdentityForwardingEnabled=null, $isAnonymousAllowed=null,
        $backEndUsername=null, $backEndPassword=null, $loginFormUri=null,
        $isGlobalQuotasEnabled=null, $reqSec=null, $reqDay=null,
        $reqMonth=null, $isUserQuotasEnabled=null, $noApply=0
    ) {
        try{
            // Array param is legacy from previous (initial) version of Restler
            $params=array(
                "isPublished" => $isPublished,
                "isGlobalQuotasEnabled" => $isGlobalQuotasEnabled,
                "frontEndEndPoint" => $frontEndEndPoint,
                "backEndEndPoint" => $backEndEndPoint,
                "additionalConfiguration" => $additionalConfiguration,
                "additionalBackendConnectionConfiguration" => $additionalBackendConnectionConfiguration,
                "isAnonymousAllowed" => $isAnonymousAllowed,
                "isHitLoggingEnabled" => $isHitLoggingEnabled,
                "onAllNodes" => $onAllNodes,
                "isUserAuthenticationEnabled" => $isUserAuthenticationEnabled,
                "reqSec" => $reqSec,
                "reqDay" => $reqDay,
                "reqMonth" => $reqMonth,
                "isIdentityForwardingEnabled" => $isIdentityForwardingEnabled,
                "backEndUsername" => $backEndUsername,
                "backEndPassword" => $backEndPassword,
                "isUserQuotasEnabled" => $isUserQuotasEnabled,
                "loginFormUri" => $loginFormUri,
                "groupName" =>$groupName,
                "noApply" => $noApply
            );
            return updateService($serviceName, $params);
        }catch (Exception $e) {
            throw new RestException($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Delete Service
     *
     * Remove and undeploy a particular service
     *
     * @param string $serviceName Service identifier
     *
     * @url DELETE :serviceName
     *
     * @return Service Deleted Service
     */
    function delete($serviceName)
    {
        try{
            return deleteService($serviceName);
        }catch (Exception $e) {
            throw new RestException($e->getCode(), $e->getMessage());
        }
    }

    /**
      * Get Users without quotas
      *
      * Get a list of user witch are allowed to use this Service but where User 
      * quotas are not set but required
      *
      * @param string $serviceName Service Id
      *
      * @url GET :serviceName/quotas/unset
      *
      * @return array {@type User} Users list
      */
    function getUnsetQuotasForService($serviceName)
    {
        try{
            $s = new Services();
            $s->getOne($serviceName);
            return getUnsetQuotas($serviceName);
        }catch (Exception $e) {
            throw new RestException($e->getCode(), $e->getMessage());
        }
    }


    /**
      * Get user quotas
      *
      * Get user quotas defined for a particular service
      *
      * @param string $serviceName Service identifier
      *
      * @url GET :serviceName/quotas
      *
      * @return array {@type Quota}
      */
    function userQuotasForService($serviceName)
    {
        try{
            $s = new Services();
            $s->getOne($serviceName);
            return getUserQuotas($serviceName);
        }catch (Exception $e) {
            throw new RestException($e->getCode(), $e->getMessage());
        }
    }
    /**
      * Get user quotas
      *
      * Get user quotas defined for a particular service
      *
      * @param string $serviceName Service identifier
      * @param string $userName    User identifier
      *
      * @url GET :serviceName/quotas/:userName
      *
      * @return array {@type Quota}
    */
    function userQuotasForServiceAndUser($serviceName,$userName)
    {
        try{
            $s = new Services();
            $s->getOne($serviceName);
            return getUserQuotas($serviceName, $userName);
        }catch (Exception $e) {
            throw new RestException($e->getCode(), $e->getMessage());
        }
    }
    /**
      * Create user quotas
      *
      * Create quotas for a particular user and a particular service
      *
      * @param string $serviceName Service identifier
      * @param string $userName    User identifier
      * @param string $reqSec      Maximum number of allowed requests per seconds
      * @param string $reqDay      Maximum number of allowed requests per days
      * @param string $reqMonth    Maximum number of allowed requests per months
      *
      * @url POST :serviceName/quotas/:userName
      *
      * @return Quota Created Quota
    */
    function addUserQuotasForService(
        $serviceName, $userName, $reqSec, $reqDay, $reqMonth
    ) {
        try{
            $params=array("reqSec" =>$reqSec,
                          "reqDay" =>$reqDay,
                          "reqMonth" =>$reqMonth,
            );
            return addUserQuota($userName, $serviceName, $params);
        }catch (Exception $e) {
            throw new RestException($e->getCode(), $e->getMessage());
        }
    }
    /**
      * Update user quotas
      *
      * Update quotas for a particular user and a particular service
      *
      * @param string $serviceName Service identifier
      * @param string $userName    User identifier
      * @param string $reqSec      Maximum number of allowed requests per seconds
      * @param string $reqDay      Maximum number of allowed requests per days
      * @param string $reqMonth    Maximum number of allowed requests per months
      *
      * @url PUT :serviceName/quotas/:userName
      *
      * @return Quota Created Quota
      */
    function updateUserQuotasForService(
        $serviceName, $userName, $reqSec, $reqDay, $reqMonth
    ) {
        try{
            $params=array("reqSec" =>$reqSec,
                          "reqDay" =>$reqDay,
                          "reqMonth" =>$reqMonth,
            );

            return updateUserQuotas($userName, $serviceName, $request_data);
        }catch (Exception $e) {
            throw new RestException($e->getCode(), $e->getMessage());
        }
    }
    /**
      * Delete users quotas
      *
      * Delete quotzas for a particular service and a particular user
      *
      * @param string $serviceName Service identifier
      * @param string $userName    User identifier
      *
      * @url DELETE :serviceName/quotas/:userName
      *
      * @return Quota Deleted quota
      */
    function deleteUserQuotasForService($serviceName, $userName)
    {
        try{
            return deleteUserQuotas($userName, $serviceName);
        }catch (Exception $e) {
            throw new RestException($e->getCode(), $e->getMessage());
        }
    }


    /**
      * Get Nodes where service is availables
      *
      * @param string $serviceName Service identifier
      *
      * @url GET :serviceName/nodes
      *
      * @return ServiceNode All Nodes with pulication indicator
      */
    function getNodesForService($serviceName=null)
    {
        try{
            return nodesListForService($serviceName);
        }catch (Exception $e) {
            throw new RestException($e->getCode(), $e->getMessage());
        }
    }
    /**
      * Publish on Nodes
      *
      * Publish a particular Service on a Node lsit
      *
      * @param string $serviceName Service identifier
      * @param array  $nodes       {@type string} Nodes identifiers list
      * @param int    $noApply     Don't apply configuration immediatly?
      *                            {@choice 0,1} (0: no, 1: yes, default 0)
      *
      * @url POST :serviceName/nodes
      *
      * @return array {@type ServiceNode} Node on which servie is available
    */
    function defineNodesForService($serviceName, $nodes, $noApply=0)
    {
        try{
            return setNodesListForService($serviceName, $nodes, $noApply);
        }catch (Exception $e) {
            throw new RestException($e->getCode(), $e->getMessage());
        }
    }
}
