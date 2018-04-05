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
 * File Name   : ApplianceManager/ApplianceManager.php/objects/User.class.php
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

require_once '../objects/ApplianceObject.class.php';
/**
 * Node object
 * 
 * PHP Version 7.0
 * 
 * @category ReverseProxy
 * @package  OSA
 * @author   Benoit HERARD <benoit.herard@orange.com>
 * @license  http://www.apache.org/licenses/LICENSE-2.0.htm Apache 2 license
 * @link     https://github.com/zorglub42/OSA/
*/
class Node extends ApplianceObject
{

    //Private mebers
    /**
     * Node idnetifier
     * 
     * @var string nodeName node identifier
     */
    public $nodeName;

    /**
     * Node description
     * 
     * @var string nodeDescription description of this node
     */
    public $nodeDescription;

    /**
     * Is it an HTTPS node?
     * 
     * @var int isHTTPS Does this node use HTTPS? (O: no, 1: yes) {@choice 0,1}
     */
    public $isHTTPS;

    /**
     * Is Basic auth enabled on this node?
     * 
     * @var int isBasicAuthEnabled  Does this node handle basic authentication? 
     *                              (O: no, 1: yes) {@choice 0,1}
     */
    public $isBasicAuthEnabled;

    /**
     * Is Cookie Auth enabled on this node?
     * 
     * @var int iscookieAuthEnabled Does this not handel cookie based auth.? 
     *                              (O: no, 1: yes) {@choice 0,1}
     */
    public $iscookieAuthEnabled;

    /**
     * Server's FQDN
     * 
     * @var string serverFQDN public FQDN for this node
     */
    public $serverFQDN;

    /**
     * Listening host/ip
     * 
     * @var string loalIP local listening IP (or *) of this note
     */
    public $localIP;

    /**
     * Listening port
     * 
     * @var int port listening port
     */
    public $port;

    /**
     * HTTPS private key
     * 
     * @var string privateKey for HTTPS
     */
    public $privateKey;

    /**
     * Server certificate for HTTPS
     * 
     * @var string cert server certificate for HTTPS
     */
    public $cert;

    /**
     * Certification authority certificate
     * 
     * @var string ca Certification authority certificate
     */
    public $ca;

    /**
     * Intermediate certification authority certificates
     * 
     * @var string intermediate certification authority certificates
     */
    public $caChain;

    /**
     * Additionnal apache directive for this virtualHost/node
     * 
     * @var string additionnal apache directive for this virtualHost/node
     */
    public $additionalConfiguration;

    /**
     * Is active/published nodde?
     * 
     * @var int isPublished Is this node published? (O: no, 1: yes) {@choice 0,1}
     */
    public $isPublished;

    /**
     * Setter
     * 
     * @param string $additionalConfiguration Additional apache directives
     * 
     * @return void
     */
    function setAdditionalConfiguration($additionalConfiguration)
    {
        $this->additionalConfiguration=(
            $additionalConfiguration=="null"?"":$additionalConfiguration
        );
    }
    /**
     * Getter
     * 
     * @return string Additional apache directives
     */
    function getAdditionalConfiguration()
    {
        return $this->additionalConfiguration;
    }


    /**
     * Setter
     * 
     * @param string $nodeDescription Node description
     * 
     * @return void
     */
    function setNodeDescription($nodeDescription)
    {
        $this->nodeDescription=$nodeDescription;
    }
    /**
     * Getter
     * 
     * @return string Node desciption
    */
    function getNodeDescription()
    {
        return $this->nodeDescription;
    }
    
    
    /**
     * Setter
     * 
     * @param string $port Listeing port (int or *)
     * 
     * @return void
     */
    function setPort($port)
    {
        $this->port=$port;
    }
    /**
     * Getter
     * 
     * @return string listening port
     */
    function getPort()
    {
        return $this->port;
    }
    
    /**
     * Setter
     * 
     * @param int $isCookieAuthEnabled Is cookie auth. enabled?
     * 
     * @return void
     */
    function setIsCookieAuthEnabled($isCookieAuthEnabled)
    {
        $this->isCookieAuthEnabled=$isCookieAuthEnabled;
    }
    /**
     * Getter
     * 
     * @return int Is cookie auth. enabled?
     */
    function getIsCookieAuthEnabled()
    {
        return $this->isCookieAuthEnabled;
    }

    /** 
     * Setter
     * 
     * @param string $nodeName Node name (id)
     * 
     * @return void
    */
    function setNodeName($nodeName)
    {
        $this->nodeName=$nodeName;
    }
    /**
     * Getter
     * 
     * @return string Node name
    */
    function getNodeName()
    {
        return $this->nodeName;
    }

    /**
     * Setter
     * 
     * @param string $serverFQDN Server FQDN
     * 
     * @return void
     */
    function setServerFQDN($serverFQDN)
    {
        $this->serverFQDN=$serverFQDN;
    }
    /**
     * Getter
     * 
     * @return string Server FQDN
     */
    function getServerFQDN()
    {
        return $this->serverFQDN;
    }

    /**
     * Setter
     * 
     * @param int $isHTTPS Is an HTTPS node
     * 
     * @return void
    */
    function setIsHTTPS($isHTTPS)
    {
        $this->isHTTPS=$isHTTPS;
    }
    /**
     * Getter
     * 
     * @return in Is it an HTTPS Node
     */
    function getIsHTTPS()
    {
        return $this->isHTTPS;
    }
    
    /**
     * Setter
     * 
     * @param int $isBasicAuthEnabled Is basic auth enabled?
     * 
     * @return void
     */
    function setIsBasicAuthEnabled($isBasicAuthEnabled)
    {
        $this->isBasicAuthEnabled=$isBasicAuthEnabled;
    }
    /**
     * Getter
     * 
     * @return int Is basic auth. enabled?
     */
    function getIsBasicAuthEnabled()
    {
        return $this->isBasicAuthEnabled;
    }

    /**
     * Getter
     * 
     * @return string Listeing IP/hostname
     */
    function getLocalIP()
    {
        return $this->localIP;
    }
    /**
     * Setter
     * 
     * @param string $localIP Listening IP/hostname
     * 
     * @return void
     */
    function setLocalIP($localIP)
    {
        $this->localIP=$localIP;
    }

    /**
     * Getter
     * 
     * @return string HTTPS private key
     */
    function getPrivateKey()
    {
        return $this->privateKey;
    }
    /**
     * Setter
     * 
     * @param string $privateKey HTTPS Private key
     * 
     * @return void
     */
    function setPrivateKey($privateKey)
    {
        $this->privateKey=$privateKey;
    }
    
    /**
     * Getter
     * 
     * @return string HTTPS public key
     */
    function getCert()
    {
        return $this->cert;
    }
    /**
     * Setter
     * 
     * @param string $cert HTTPS Public cert
     * 
     * @return void
     */
    function setCert($cert)
    {
        $this->cert=$cert;
    }

    /**
     * Getter
     * 
     * @return string Certification authority cert
     */
    function getCa()
    {
        return $this->ca;
    }
    /**
     * Setter
     * 
     * @param string $ca Certification authority cert
     * 
     * @return void
     */
    function setCa($ca)
    {
        $this->ca=$ca;
    }

    /**
     * Getter
     * 
     * @return string Certification authrity chain certs
     */
    function getChain()
    {
        return $this->chain;
    }
    /**
     * Setter
     * 
     * @param string $chain Certification authrity chain certs
     * 
     * @return void
     */
    function setChain($chain)
    {
        $this->chain=$chain;
    }

    /**
     * Getter
     * 
     * @return int Is this node active/published?
     */
    function getIsPublished()
    {
        return $this->isPublished;
    }
    /**
     * Setter
     * 
     * @param int $isPublished Is this node active/published?
     * 
     * @return void
     */
    function setIsPublished($isPublished)
    {
        $this->isPublished=$isPublished;
    }

    /**
     * Constructor
     * 
     * @param object $rqt PDO row
     */
    public function __construct($rqt=null)
    {
        if ($rqt != null) {
            $this->setNodeName($rqt["nodeName"]);
            $this->setServerFQDN($rqt["serverFQDN"]);
            $this->setIsHTTPS($rqt["isHTTPS"]);
            $this->setNodeDescription($rqt["nodeDescription"]);
            $this->setPort($rqt["port"]);
            $this->setLocalIP($rqt["localIP"]);
            $this->setIsCookieAuthEnabled($rqt["isCookieAuthEnabled"]);
            $this->setIsBasicAuthEnabled($rqt["isBasicAuthEnabled"]);
            $this->setPrivateKey($rqt["privateKey"]);
            $this->setCert($rqt["cert"]);
            $this->setCa($rqt["ca"]);
            $this->setChain($rqt["caChain"]);
            $this->setUri("nodes/" . urlencode($rqt["nodeName"]));
            $this->setAdditionalConfiguration($rqt["additionalConfiguration"]);
            $this->setIsPublished($rqt["isPublished"]);
        }
    }
    
    /**
     * Convert object to associative array
     * 
     * @return array Object in a array
     */
    function toArray()
    {
        $certUri="";
        $privateKeyUri="";
        $caUri="";
        $caChainUri="";
        if ($this->getIsHTTPS()) {
            $certUri=$this->getUri() . "/cert";
            $privateKeyUri=$this->getUri() . "/privateKey";
            if ($this->getCa() != null) {
                $caUri=$this->getUri() . "/ca";
            }
            if ($this->getChain() != null) {
                $caChainUri=$this->getUri() . "/chain";
            }
                
        }
        return Array(
                "uri"  => $this->getUri(),
                "nodeName"  => $this->getNodeName(),
                "serverFQDN"  => $this->getServerFQDN(),
                "nodeDescription"  => $this->getNodeDescription(),
                "localIP"  => $this->getLocalIP(),
                "port"  => $this->getPort(),
                "isCookieAuthEnabled"  => $this->getIsCookieAuthEnabled(),
                "isHTTPS"  => $this->getIsHTTPS(),
                "isBasicAuthEnabled"  => $this->getIsBasicAuthEnabled(),
                "certificateUri"  => $certUri,
                "privateKeyUri"  => $privateKeyUri,
                "caUri"  => $caUri,
                "chainUri"  => $caChainUri,
                "isPublished"  => $this->isPublished,
                "additionalConfiguration" => $this->getAdditionalConfiguration()
            );
    }
                
}
