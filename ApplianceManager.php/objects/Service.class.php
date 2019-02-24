<?php
/**
 * Reverse Proxy as a service
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
 * Version : 1.0
 *
 * Copyright (c) 2011 – 2014 Orange
 * This software is distributed under the Apache 2 license
 * <http://www.apache.org/licenses/LICENSE-2.0.html>
 *
 *--------------------------------------------------------
 * File Name   : ApplianceManager/ApplianceManager.php/objects/Service.class.php
 *
 * Created     : 2012-02
 * Authors     : Benoit HERARD <benoit.herard(at)orange.com>
 *
 * Description :
 *      .../...
 *--------------------------------------------------------
 * History     :
 * 1.0.0 - 2012-10-01 : Release of the file
*/
require_once "../include/Func.inc.php";
require_once '../objects/ApplianceObject.class.php';

/**
 * Service Class
 * 
 * PHP Version 7.0
 * 
 * @category ReverseProxy
 * @package  OSA
 * @author   Benoit HERARD <benoit.herard@orange.com>
 * @license  http://www.apache.org/licenses/LICENSE-2.0.htm Apache 2 license
 * @link     https://github.com/zorglub42/OSA/
*/
class Service extends ApplianceObject
{

    /**
     * Service identifier
     * 
     * @var string serviceName service identifier
     **/
    public $serviceName;

    /**
     * Authorization group identifier
     * 
     * @var string groupName Users have to be member of this group to use this
     *                       service
     */
    public $groupName;

    /**
     * Max request/sec
     * 
     * @var int reqSec maximun number of request allowed per seconds
     */
    public $reqSec;

    /**
     * Max request/day
     * 
     * @var int reqDay maximun number of request allowed per days
     */
    public $reqDay;

    /**
     * Max request/month
     * 
     * @var int reqMonth maximun number of request allowed per months
     */
    public $reqMonth;

    /**
     * Are globlal quotas enabled?
     * 
     * @var int isGlobalQuotasEnabled Is there global quotas management on this
     *          service? (O: no, 1: yes) {@choice 0,1}
     */
    public $isGlobalQuotasEnabled;

    /**
     * Are user quotas enabled?
     * 
     * @var int isUserQuotasEnabled Is there quotas management at user level on
     *          this service? (O: no, 1: yes) {@choice 0,1}
     */
    public $isUserQuotasEnabled;

    /**
     * Is identity forwarding to backend enabled?
     * 
     * @var int isIdentityForwardingEnabled Authenticated user identity is
     *          forwarded to backend? (O: no, 1: yes) {@choice 0,1}
     */
    public $isIdentityForwardingEnabled;

    /**
     * Is this service pushished on node(s)
     * 
     * @var int isPublished Is this server currently available on nodes?
     *          (O: no, 1: yes) {@choice 0,1}
     */
    public $isPublished;

    /**
     * Front end Alias on node(s)
     * 
     * @var url frontEndEndPoint URI on frontend node
     */
    public $frontEndEndPoint;

    /**
     * Backend URL
     * 
     * @var url backEndEndPoint URL to backend server
     */
    public $backEndEndPoint;

    /**
     * Backend username (Basic auth.)
     * 
     * @var string username to authenticate against backend server (basic auth)
     */
    public $backEndUserName;

    /**
     * Backend password (basic auth)
     * 
     * @var string password to authenticate against backend server (basic auth)
     */
    public $backEndPassword;

    /**
     * Is user authent. required for this service
     * 
     * @var int isUserAuthenticationEnabled Is authentication enabled for this 
     *          service? (O: no, 1: yes) {@choice 0,1}
     */
    public $isUserAuthenticationEnabled;

    /**
     * Log calls for this service?
     * 
     * @var int isHitLoggingEnabled IS log recording activiated for this 
     *          service? (O: no, 1: yes) {@choice 0,1}
     */
    public $isHitLoggingEnabled;

    /**
     * Additional apache config directive for this service (<Location> tag)
     * 
     * @var string additionalConfiguration Additionnal Apache configuration 
     *             directive (for "Location" tag)
     */
    public $additionalConfiguration;

    /**
     * Additional apache config directive for connection to backend (ProxyPass tag)
     * 
     * @var string additionalBackendConnectionConfiguration Additionnal Apache configuration 
     *             directive (for "ProxyPass" tag)
     */
    public $additionalBackendConnectionConfiguration;

    /**
     * Is this service available on all publised nodes?
     * 
     * @var int onAllNodes Is this service available for all running nodes?
     *          (O: no, 1: yes) {@choice 0,1}
     */
    public $onAllNodes;

    /**
     * Is anonymous (unauthenticated) access allowed
     * 
     * @var int isAnonymousAllowed Is authentication absolutly required to invoke
     *          this service or anonymous access is also possible?
     *          (O: no, 1: yes) {@choice 0,1}
     */
    public $isAnonymousAllowed;

    /**
     * Login for URI
     * 
     * @var url loginFormUri Login form url to redirect to for unauthenticated access
     */
    public $loginFormUri;
    
    
    /**
     * Getter
     * 
     * @return int Is anonymous access also allowed?
     */
    public function getIsAnonymousAllowed()
    {
        return $this->isAnonymousAllowed;
    }
    /**
     * Setter
     * 
     * @param int $isAnonymousAllowed Is anonymous also allowed
     * 
     * @return void
     */
    public function setIsAnonymousAllowed($isAnonymousAllowed)
    {
        $this->isAnonymousAllowed=$isAnonymousAllowed;
    }
    

    /**
     * Getter
     * 
     * @return int Is user authentication enabled?
     */
    public function getIsUserAuthenticationEnabled()
    {
        return $this->isUserAuthenticationEnabled;
    }
    /**
     * Setter
     * 
     * @param int $isUserAuthenticationEnabled Is user authent. enabled?
     * 
     * @return void
     */
    public function setIsUserAuthenticationEnabled($isUserAuthenticationEnabled)
    {
        $this->isUserAuthenticationEnabled=$isUserAuthenticationEnabled;
    }
    
    
    /**
     * Getter
     * 
     * @return int Log calls on this service?
     */
    public function getIsHitLoggingEnabled()
    {
        return $this->isHitLoggingEnabled;
    }
    /**
     * Setter
     * 
     * @param int $isHitLoggingEnabled Log calls?
     * 
     * @return void
     */
    public function setIsHitLoggingEnabled($isHitLoggingEnabled)
    {
        $this->isHitLoggingEnabled=$isHitLoggingEnabled;
    }
    
    
    /**
     * Getter
     * 
     * @return string Authorization group URI
     */
    function getGroupUri()
    {
        return $this->getPublicUriPrefix() . "groups/" . $this->getGroupName();
    }
    /**
     * Setter
     * 
     * @param string $groupName Authorization group
     * 
     * @return void
     */
    function setGroupName($groupName)
    {
        $this->groupName=$groupName;
    }
    /**
     * Getter
     * 
     * @return string Authorization group
     */
    function getGroupName()
    {
        return $this->groupName;
    }
    

    /**
     * Getter
     * 
     * @return string Service Identifier
     */
    function getServiceName()
    {
        return $this->serviceName;
    }
    /**
     * Setter
     * 
     * @param string $serviceName Service identifier
     * 
     * @return void
     */
    function setServiceName($serviceName)
    {
        $this->serviceName=$serviceName;
    }
        

    /**
     * Getter
     * 
     * @return int Max. number of requests/sec on backend
     */
    function getReqSec()
    {
        return $this->reqSec;
    }
    /**
     * Setter
     * 
     * @param int $reqSec Max number of requests/sec on backend
     * 
     * @return void
     */
    function setReqSec($reqSec)
    {
        $this->reqSec=$reqSec;
    }

        
    /**
     * Getter
     * 
     * @return int Max. number of requests/day on backend
     */
    function getReqDay()
    {
        return $this->reqDay;
    }
    /**
     * Setter
     * 
     * @param int $reqDay Max number of requests/day on backend
     * 
     * @return void
     */
    function setReqDay($reqDay)
    {
        $this->reqDay=$reqDay;
    }

        
    /**
     * Getter
     * 
     * @return int Max. number of requests/month on backend
     */
    function getReqMonth()
    {
        return $this->reqMonth;
    }
    /**
     * Setter
     * 
     * @param int $reqMonth Max number of requests/month on backend
     * 
     * @return void
     */
    function setReqMonth($reqMonth)
    {
        $this->reqMonth=$reqMonth;
    }
    
        
    /**
     * Getter
     * 
     * @return string Endpoint on frontend Node
     */
    function getFrontEndEndPoint()
    {
        return $this->frontEndEndPoint;
    }
    /**
     * Setter
     * 
     * @param string $frontEndEndPoint Endpoint on frontend nodes
     * 
     * @return void
     */
    function setFrontEndEndPoint($frontEndEndPoint)
    {
        $this->frontEndEndPoint=$frontEndEndPoint;
    }
        
    /**
     * Getter
     * 
     * @return url Backend URL
     */
    function getBackEndEndPoint()
    {
        return $this->backEndEndPoint;
    }
    /**
     * Setter
     * 
     * @param string $backEndEndPoint Backend URL
     * 
     * @return void
     */
    function setBackEndEndPoint($backEndEndPoint)
    {
        $this->backEndEndPoint=$backEndEndPoint;
    }

    /**
     * Getter
     * 
     * @return string Backend basic auth username
     */
    function getBackEndUserName()
    {
        return $this->backEndUserName;
    }
    /**
     * Setter
     * 
     * @param string $backEndUserName Backend basic auth. username
     * 
     * @return void
     */
    function setBackEndUserName($backEndUserName)
    {
        $this->backEndUserName=$backEndUserName;
    }
    
    /**
     * Getter
     * 
     * @return string Backend basic auth password
     */
    function getBackEndPassword()
    {
        return $this->backEndPassword;
    }
    /**
     * Setter
     * 
     * @param string $backEndPassword Backend basic auth. password
     * 
     * @return void
     */
    function setBackEndPassword($backEndPassword)
    {
        $this->backEndPassword=$backEndPassword;
    }
    
    /**
     * Getter
     * 
     * @return int Is identity forwarding enabled?
     */
    function getIsIdentityForwardingEnabled()
    {
        return $this->isIdentityForwardingEnabled;
    }
    /**
     * Setter
     * 
     * @param int $isIdentityForwardingEnabled Is identity forwarding enabled?
     * 
     * @return void
     */
    function setIsIdentityForwardingEnabled($isIdentityForwardingEnabled)
    {
        $this->isIdentityForwardingEnabled=$isIdentityForwardingEnabled;
    }

    /**
     * Getter
     * 
     * @return int Is this service published on node
     */
    function getIsPublished()
    {
        return $this->isPublished;
    }
    /**
     * Setter
     * 
     * @param int $isPublished Is this service published on nodes
     * 
     * @return void
     */
    function setIsPublished($isPublished)
    {
        $this->isPublished=$isPublished;
    }
    
    
    /**
     * Getter
     * 
     * @return int Are global quotas enabled for this service
     */
    function getIsGlobalQuotasEnabled()
    {
        return $this->isGlobalQuotasEnabled;
    }
    /**
     * Setter
     * 
     * @param int $isGlobalQuotasEnabled Are global quotas enabled
     * 
     * @return void
     */
    function setIsGlobalQuotasEnabled($isGlobalQuotasEnabled)
    {
        $this->isGlobalQuotasEnabled=$isGlobalQuotasEnabled;
    }

            
    /**
     * Getter
     * 
     * @return int Are user quotas enabled for this service
     */
    function getIsUserQuotasEnabled()
    {
        return $this->isUserQuotasEnabled;
    }
    /**
     * Setter
     * 
     * @param int $isUserQuotasEnabled Are user quotas enabled
     * 
     * @return void
     */
    function setIsUserQuotasEnabled($isUserQuotasEnabled)
    {
        $this->isUserQuotasEnabled=$isUserQuotasEnabled;
    }


    /**
     * Getter
     * 
     * @return string Additionnal apache configuration
     */
    function getAdditionalConfiguration()
    {
        return $this->additionalConfiguration;
    }
    /**
     * Setter
     * 
     * @param string $additionalConfiguration Additional apache config
     * 
     * @return void
     */
    function setAdditionalConfiguration($additionalConfiguration)
    {
        $this->additionalConfiguration=$additionalConfiguration;
    }


    /**
     * Getter
     * 
     * @return string Additionnal apache configuration for backend connection (ProxyPass)
     */
    function getAdditionalBackendConnectionConfiguration()
    {
        return $this->additionalBackendConnectionConfiguration;
    }
    /**
     * Setter
     * 
     * @param string $additionalBackendConnectionConfiguration Additional apache config for backend connection (ProxyPass)
     * 
     * @return void
     */
    function setAdditionalBackendConnectionConfiguration($additionalBackendConnectionConfiguration)
    {
        $this->additionalBackendConnectionConfiguration=$additionalBackendConnectionConfiguration;
    }

    /**
     * Getter
     * 
     * @return int Is this service published on all nodes?
     */
    function getOnAllNodes()
    {
        return $this->onAllNodes;
    }
    /**
     * Setter
     * 
     * @param int $onAllNodes Is this service avalaible on all nodes?
     * 
     * @return void
     */
    function setOnAllNodes($onAllNodes)
    {
        $this->onAllNodes=$onAllNodes;
    }

    /**
     * Setter
     * 
     * @param string $loginFormUri Login form URI
     * 
     * @return void
     */
    function setLoginFormUri($loginFormUri)
    {
        $this->loginFormUri=$loginFormUri;
    }
    /**
     * Getter
     * 
     * @return string Login for URI
     */
    function getLoginFormUri()
    {
        return $this->loginFormUri;
    }
    

    /**
     * Constructor
     * 
     * @param object $rqt PDO row
     */
    public function __construct($rqt=null)
    {
        if ($rqt != null) {
            $this->setServiceName($rqt["serviceName"]);
            $this->setUri("services/" . urlencode($rqt["serviceName"]));

            $this->setIsIdentityForwardingEnabled(
                $rqt["isIdentityForwardingEnabled"]
            );
            

            $this->setBackEndUsername($rqt["backEndUsername"]);
            $this->setBackEndPassword(decrypt($rqt["backEndPassword"]));
            $this->setBackEndEndPoint($rqt["backEndEndPoint"]);
            $this->setFrontEndEndPoint($rqt["frontEndEndPoint"]);

            $this->setReqSec($rqt["reqSec"]);
            $this->setReqDay($rqt["reqDay"]);
            $this->setReqMonth($rqt["reqMonth"]);
            
            $this->setIsGlobalQuotasEnabled($rqt["isGlobalQuotasEnabled"]);
            $this->setIsUserQuotasEnabled($rqt["isUserQuotasEnabled"]);
            
            $this->setGroupName($rqt["groupName"]);
            $this->setIsPublished($rqt["isPublished"]);
            
            $this->setIsHitLoggingEnabled($rqt["isHitLoggingEnabled"]);
            $this->setIsUserAuthenticationEnabled(
                $rqt["isUserAuthenticationEnabled"]
            );
            $this->setOnAllNodes($rqt["onAllNodes"]);
            $this->setIsAnonymousAllowed($rqt["isAnonymousAllowed"]);
            $this->setLoginFormUri($rqt["loginFormUri"]);
            
            $this->setAdditionalConfiguration($rqt["additionalConfiguration"]);
            $this->setAdditionalBackendConnectionConfiguration($rqt["additionalBackendConnectionConfiguration"]);
            if ($this->getIsUserAuthenticationEnabled()==0) {
                $this->setGroupName("");
                $this->setIsUserQuotasEnabled(0);
                $this->setIsIdentityForwardingEnabled(0);
                $this->setIsAnonymousAllowed(0);
            }
        }
    }

    /**
     * Convert object to associative array
     * 
     * @return array Object in a array
     */
    public function toArray()
    {

        return Array(
            "uri" => $this->getUri() ,
            "groupUri" => $this->getGroupUri() ,
            "serviceName" => $this->getServiceName() ,
            "groupName" => $this->getGroupName() ,
            "isIdentityForwardingEnabled" => $this->getIsIdentityForwardingEnabled(),
            "isGlobalQuotasEnabled" => $this->getIsGlobalQuotasEnabled() ,
            "isUserQuotasEnabled" => $this->getIsUserQuotasEnabled() ,
            "isPublished" => $this->getIsPublished() ,
            "reqSec" => $this->getReqSec() ,
            "reqDay" => $this->getReqDay() ,
            "reqMonth" => $this->getReqMonth() ,
            "frontEndEndPoint" => $this->getFrontEndEndPoint() ,
            "backEndEndPoint" => $this->getBackEndEndPoint() ,
            "backEndUsername" => $this->getBackEndUsername() ,
            "backEndPassword" => $this->getBackEndPassword() ,
            "isHitLoggingEnabled" => $this->getIsHitLoggingEnabled() ,
            "isUserAuthenticationEnabled" => $this->getIsUserAuthenticationEnabled(),
            "additionalConfiguration" => $this->getAdditionalConfiguration(),
            "additionalBackendConnectionConfiguration" => $this->getAdditionalBackendConnectionConfiguration(),
            "onAllNodes" => $this->getOnAllNodes(),
            "loginFormUri" => $this->getLoginFormUri(),
            "isAnonymousAllowed" => $this->getIsAnonymousAllowed()
        );
    }

}
