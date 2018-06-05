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

require_once 'nodeDAO.php';

/**
 * Nodes management
 * 
 * PHP Version 7.0
 * 
 * @category ReverseProxy
 * @package  OSA
 * @author   Benoit HERARD <benoit.herard@orange.com>
 * @license  http://www.apache.org/licenses/LICENSE-2.0.htm Apache 2 license
 * @link     https://github.com/zorglub42/OSA/
*/
class Nodes
{
    
    /**
     * Get node services
     * 
     * Get services available on this node
     * 
     * @param string $nodeName Node identifier
     * 
     * @url GET :nodeName/services
     * 
     * @return array services published on this node {@type Service}
     */
    function publishedServices($nodeName)
    {
        try{
            $services = getServices($nodeName);
            $rc = Array();
            foreach ($services as $aService) {
                array_push($rc, $aService->toArray());
            }
            return $rc;
        }catch (Exception $e) {
            throw new RestException($e->getCode(), $e->getMessage());
        }
    }
    
    /**
     * Remove CA
     * 
     * Remove certification autority certificate from a Node
     * 
     * @param string $nodeName Node identifier
     * 
     * @url DELETE :nodeName/ca
     * 
     * @return string Certificate
     */
    function removeCa($nodeName)
    {
        try{
            updateCaCert($nodeName, null);
            $this->getCa($nodeName);
        }catch (Exception $e) {
            throw new RestException($e->getCode(), $e->getMessage());
        }
    }

    /*
     * Upload CA
     * 
     * Upload certification authority certificate
     * Expect Certificate as Uploaded File (files name collection name: files)
     * 
     * @param string $nodeName Node identifier
     * 
     * @url POST :nodeName/ca
     * 
     *
     function uploadCa($nodeName) {
        try{
            $ca=file_get_contents($_FILES["files"]["tmp_name"][0]);
            if ($ca == null || $ca=="" ) {
                throw new RestException(400 ,"ca cert is required\n");
            } else {
                updateCaCert($nodeName, $ca);
                $this->getCa($nodeName);
            }
        }catch (Exception $e) {
            throw new RestException($e->getCode(), $e->getMessage());
        }
    }*/

    /**
     * Remove Certication chain
     * 
     * Remove intermediate  certification autority certificate from a Node
     * 
     * @param string $nodeName Node identifier
     * 
     * @url DELETE :nodeName/chain
     * 
     * @return string Certificate
     */
    function removeChain($nodeName)
    {
        try{
            updateCaChain($nodeName, null);
            return true;
        }catch (Exception $e) {
            throw new RestException($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Upload Chain
     * 
     * Upload intermediate certification authority certificates
     * Expect Certificates as multipart/form-data; Uploaded File
     * (files name collection name: files)
     * 
     * @param string $nodeName Node identifier
     * @param array  $files    {@field files}{@type associative}
     *                         Certificate(s) as multipart/form-data Uploaded File 
     * 
     * @url POST :nodeName/chain
     * 
     * @return bool True in case of success
     */
    function uploadChain($nodeName, array $files)
    {
        try{
            $chain=file_get_contents($files["tmp_name"][0]);
            if ($chain == null || $chain=="" ) {
                throw new RestException(400, "chain cert is required\n");
            } else {
                updateCaChain($nodeName, $chain);
                return true;
            }
        }catch (Exception $e) {
            throw new RestException($e->getCode(), $e->getMessage());
        }
    }
    
    /**
     * Remove certificate
     * 
     * Remove server certificate from a Node
     * 
     * @param string $nodeName Node identifier
     * 
     * @url DELETE :nodeName/cert
     * 
     * @return string Certificate
     */
    function removeCert($nodeName)
    {
        try{
            updateCert($nodeName, null);
            return true;
            //$this->getCert($nodeName);
        }catch (Exception $e) {
            throw new RestException($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Upload certificate
     * 
     * Upload server certificate
     * Expect Certificates as multipart/form-data; Uploaded File (files field)
     * 
     * @param string $nodeName Node identifier
     * @param array  $files    {@field files}{@type associative}
     *                         Certificate as multipart/form-data Uploaded File 
     * 
     * @url POST :nodeName/cert
     * 
     * @return bool True in case of success
     */
    function uploadCert($nodeName,array $files)
    {
        try{
            $cert=file_get_contents($files["tmp_name"][0]);
            if ($cert == null || $cert=="" ) {
                throw new RestException(400, "cert is required\n");
            } else {
                updateCert($nodeName, $cert);
                return true;
            }
        }catch (Exception $e) {
            throw new RestException($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Upload Private key
     * 
     * Upload private key
     * Expect private key as multipart/form-data; Uploaded File
     * (files name collection name: files)
     * 
     * @param string $nodeName Node identifier
     * @param array  $files    {@field files}{@type associative} 
     *                         Private key as multipart/form-data Uploaded File 
     * 
     * @url POST :nodeName/privatekey
     * 
     * @return bool True in case of success
     */
    function uploadPrivateKey($nodeName, array $files)
    {
        try{
            $key=file_get_contents($files["tmp_name"][0]);
            if ($key == null || $key=="" ) {
                throw new RestException(400, "private key is required\n");
            } else {
                updatePrivateKey($nodeName, $key);
                return true;
            }
        }catch (Exception $e) {
            throw new RestException($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Remove private key
     * 
     * Remove private key from a Node
     * 
     * @param string $nodeName Node identifier
     * 
     * @url DELETE :nodeName/privatekey
     * 
     * @return string Certificate
     */
    function removePrivateKey($nodeName)
    {
        try{
            updatePrivateKey($nodeName, null);
            return true;
        }catch (Exception $e) {
            throw new RestException($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Get Certification authority
     * 
     * Get Certification authority certificate
     * 
     * @param string $nodeName node identifier
     * 
     * @url GET :nodeName/ca
     * 
     * @return string certificate
     */
    function getCa($nodeName)
    {
        try{
            $node=getDAONode($nodeName);
            header("Content-Type: text/plain", true);
            echo $node->getCa();
        }catch (Exception $e) {
            throw new RestException($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Get Certification chain
     * 
     * Get intermediate certification authority certificated
     * 
     * @param string $nodeName node identifier
     * 
     * @url GET :nodeName/chain
     * 
     * @return string certificate
     */
    function getChain($nodeName)
    {
        try{
            $node=getDAONode($nodeName);
            header("Content-Type: text/plain", true);
            echo $node->getChain();
        }catch (Exception $e) {
            throw new RestException($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Get server certificate
     * 
     * Get server certificate
     * 
     * @param string $nodeName node identifier
     * 
     * @url GET :nodeName/cert
     * 
     * @return string certificate
     */
    function getCert($nodeName)
    {
        try{
            $node=getDAONode($nodeName);
            header("Content-Type: text/plain", true);
            echo $node->getCert();
        }catch (Exception $e) {
            throw new RestException($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Get private key
     * 
     * Get server private key
     * 
     * @param string $nodeName node identifier
     * 
     * @url GET :nodeName/privatekey
     * 
     * @return string private key
     */
    function getPrivateKey($nodeName)
    {
        try{
            $node=getDAONode($nodeName);
            header("Content-Type: text/plain", true);
            echo $node->getPrivateKey();
        }catch (Exception $e) {
            throw new RestException($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Get nodes
     * 
     * Get all nodes
     * 
     * @param string $nodeNameFilter        [optional] Only retreive nodes with
     *                                      nodeName address containing that string
     *                                      (filter conbination is AND)
     * @param string $nodeDescriptionFilter [optional] Only retreive nodes with
     *                                      nodeDescription address containing
     *                                      that string
     *                                      (filter conbination is AND)
     * @param string $localIPFilter         [optional] Only retreive nodes with 
     *                                      localIP address containing that string
     *                                      (filter conbination is AND)
     * @param int    $portFilter            [optional] Only retreive nodes with 
     *                                      listening on that port
     *                                      (filter conbination is AND)
     * @param string $serverFQDNFilter      [optional] Only retreive nodes with 
     *                                      nodeName serverFQDN containing that
     *                                      string
     *                                      (filter conbination is AND)
     * 
     * @url GET
     * 
     * @return array Nodes list {@type Node}
     */
    function getAll(
        $nodeNameFilter=null, $nodeDescriptionFilter=null, $localIPFilter=null,
        $portFilter=null, $serverFQDNFilter=null
    ) {
        //Array param is legacy from previous (initial) version of Restler 
        $params=array("nodeNameFilter" => $nodeNameFilter,
                      "nodeDescriptionFilter" => $nodeDescriptionFilter,
                      "localIPFilter" => $localIPFilter,
                      "portFilter" => $portFilter,
                      "serverFQDNFilter" => $serverFQDNFilter,
        );
        return $this->_get(null, $params);
    }
     
    /**
     * Get a Node
     * 
     * Get description of a particular Node
     * 
     * @param string $nodeName Node identifier
     * 
     * @url GET :nodeName
     * 
     * @return Node Requested Node
     */
    function getOn($nodeName)
    {
         return $this->_get($nodeName);
    }

    /**
     * Get on or more nodes
     * 
     * @param string $nodeName     Node to retreive (if one)
     * @param array  $request_data Filter
     *                             $request_data["nodeNameFilter"]
     *                             $request_data["nodeDescriptionFilter"]
     *                             $request_data["localIPFilter"]
     *                             $request_data["portFilter"]
     *                             $request_data["serverFQDNFilter"]
     *                             $request_data["order"] => order clause
     * 
     * @return array Matching nodes
     */
    private function _get($nodeName=null, $request_data = null)
    {
        try{
            if ($nodeName!=null) {
                return getDAONode($nodeName, $request_data)->toArray();
            } else {
                $nodes=getDAONode($nodeName, $request_data);
                $rc = Array();
                foreach ($nodes as $aNode) {
                    array_push($rc, $aNode->toArray());
                }
                return $rc;
            }
        }catch (Exception $e) {
            throw new RestException($e->getCode(), $e->getMessage());
        }
    }
    
    /**
     * Get Apache VirtualHost
     * 
     * Get corresponding Apache VirtualHost
     * 
     * @param string $nodeName Node identifier
     * 
     * @url GET :nodeName/virtualHost
     * 
     * @return string VirtualHost file
     */
    function generateVirtualHost($nodeName)
    {
        try{
            $node=getDAONode($nodeName);
             header("Content-Type: text/plain", true);
             
             $HTTP_VHOST_ADDR=$node->getLocalIP();
             $HTTP_VHOST_PORT=$node->getPort(); 
             $HTTP_VHOST_NAME=$node->getServerFQDN();
             $NODE_NAME=$node->getNodeName();
             
            $HTTP_VHOST_TOP_DOMAIN=""; 
            $domParts=explode(".", $node->getServerFQDN());
            if (count($domParts)>1) {
                for ($i=1;$i<count($domParts);$i++) {
                    $HTTP_VHOST_TOP_DOMAIN = $HTTP_VHOST_TOP_DOMAIN . 
                                             "." . $domParts[$i];
                }
            } else {
                $HTTP_VHOST_TOP_DOMAIN=$node->getServerFQDN();
            }
            
            $ADDITIONAL_CONFIGURATION=$node->getAdditionalConfiguration();
             
             
            if ($node->getIsHTTPS()==0) {
                include "../resources/apache.conf/http_virtualhost_template.php";
            } else {
                $HTTPS_HAVE_CA_CERT=false; 
                $HTTPS_HAVE_CHAIN_CERT=false; 
                if ($node->getCa() != "") {
                    $HTTPS_HAVE_CA_CERT=true;
                }
                if ($node->getChain() != "") {
                    $HTTPS_HAVE_CHAIN_CERT=true;
                }
                include "../resources/apache.conf/https_virtualhost_template.php";
            }
        }catch (Exception $e) {
            throw new RestException($e->getCode(), $e->getMessage());
        }
    }
    
    /**
     * Create
     * 
     * Create and deploy a new Node
     * 
     * @param string $nodeName                Node identifier
     * @param string $serverFQDN              Public server FQDN
     * @param string $localIP                 Listening IP (IP, hostname or * for
     *                                        all available interfaces)
     * @param int    $port                    Listeing port
     * @param int    $isHTTPS                 Does this node use HTTPS?
     *                                        (O: no, 1: yes) {@choice 0,1}
     * @param string $nodeDescription         Node description
     * @param int    $isBasicAuthEnabled      Does this node handle 
     *                                        basic authentication? (O: no, 1: yes)
     *                                        {@choice 0,1}
     * @param int    $isCookieAuthEnabled     Does this not handel cookie based
     *                                        authentication? (O: no, 1: yes)
     *                                        {@choice 0,1}
     * @param string $additionalConfiguration additionnal apache directive for this
     *                                        virtualHost/node
     * @param int    $apply                   Apply this configuration immediatly?
     *                                        (O: no, 1: yes) {@choice 0,1}
     * 
     * @url POST 
     * 
     * @return Node newly created Node
     */
    function create(
        $nodeName, $serverFQDN, $localIP, $port, $isHTTPS, $nodeDescription=null,
        $isBasicAuthEnabled=null, $isCookieAuthEnabled=null,
        $additionalConfiguration=null, $apply=1
    ) {
        try{
            //Array param is legacy from previous (initial) version of Restler 
            $params=array("nodeName" => $nodeName,
                          "serverFQDN" => $serverFQDN,
                          "localIP" => $localIP,
                          "port" => $port,
                          "isHTTPS" => $isHTTPS,
                          "nodeDescription" => $nodeDescription,
                          "isBasicAuthEnabled" => $isBasicAuthEnabled,
                          "isCookieAuthEnabled" => $isCookieAuthEnabled,
                          "additionalConfiguration" => $additionalConfiguration,
                          "apply" => $apply,
            );
            
            $nodeName=normalizeName($nodeName);
            $rc= addNode($nodeName, $params);
            if ($apply=="1" || empty($apply)) { 
                if (!applyApacheNodesConfiguration($nodeName, "C")) {
                    $this->delete($nodeName, array("apply"=>0));
                    throw new RestException(400, "Invalid apache configuration");
                }
            }
            return $rc->toArray();;
        }catch (Exception $e) {
            throw new RestException($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Update Node
     * 
     * Update and deploy Node
     * 
     * @param string $nodeName                Node identifier
     * @param string $serverFQDN              Public server FQDN
     * @param string $localIP                 Listening IP (IP, hostname or * for
     *                                        all available interfaces)
     * @param int    $port                    Listeing port
     * @param int    $isHTTPS                 Does this node use HTTPS?
     *                                        (O: no, 1: yes) {@choice 0,1}
     * @param string $nodeDescription         Node description
     * @param int    $isBasicAuthEnabled      Does this node handle 
     *                                        basic authentication? (O: no, 1: yes)
     *                                        {@choice 0,1}
     * @param int    $isCookieAuthEnabled     Does this not handel cookie based
     *                                        authentication? (O: no, 1: yes)
     *                                        {@choice 0,1}
     * @param string $additionalConfiguration additionnal apache directive for this
     *                                        virtualHost/node
     * @param int    $apply                   Apply this configuration immediatly?
     * 
     * @url PUT 
     * @url PUT :nodeName 
     * 
     * @return Node updated Node
     */
    function update(
        $nodeName, $serverFQDN, $localIP, $port, $isHTTPS, $nodeDescription=null,
        $isBasicAuthEnabled=null, $isCookieAuthEnabled=null,
        $additionalConfiguration=null, $apply=1
    ) {
        try{
            //Array param is legacy from previous (initial) version of Restler 
            $params=array("nodeName" => $nodeName,
                          "serverFQDN" => $serverFQDN,
                          "localIP" => $localIP,
                          "port" => $port,
                          "isHTTPS" => $isHTTPS,
                          "nodeDescription" => $nodeDescription,
                          "isBasicAuthEnabled" => $isBasicAuthEnabled,
                          "isCookieAuthEnabled" => $isCookieAuthEnabled,
                          "additionalConfiguration" => $additionalConfiguration,
                          "apply" => $apply,
            );
            $node=$this->_get($nodeName);
            $rc= updateNode($nodeName, $params);
            if ($apply=="1") { 
                if (!applyApacheNodesConfiguration($nodeName, "U")) {
                    throw new RestException(400, "Invalid apache configuration");
                }
                /*if (!applyApacheNodesConfiguration($nodeName, "C")) {
                    $node["apply"]=0;
                    $this->update($nodeName, $node);
                    throw new RestException(400, "Invalid apache configuration");
                }*/
            }
            return $rc->toArray();

        }catch (Exception $e) {
            throw new RestException($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Delete Node
     * 
     * Delete and undeploy a Node
     * 
     * @param string $nodeName Node identifier
     * @param int    $apply    Apply this configuration immediatly?
     *                         (O: no, 1: yes) {@choice 0,1}
     * 
     * @url DELETE :nodeName
     * 
     * @return Node Deleted Node
     */
    function delete($nodeName, $apply=null)
    {
        try{
            if ($apply=="1" || empty($apply)) { 
                applyApacheNodesConfiguration($nodeName, "D");
            }
            $rc= deleteNode($nodeName);
            return $rc;

        }catch (Exception $e) {
            throw new RestException($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Enable/disable 
     * 
     * Enable or disable a Node
     * 
     * @param string $nodeName  node identifier
     * @param int    $published 0: not published, 1: published {@choce 0,1}
     * @param string $reload    {@choice yes,no}, default: yes. Apply configuration.
     * 
     * @url POST :nodeName/status
     * 
     * @return Node updated Node 
     */
    function setPublished($nodeName, $published, $reload="yes")
    {
        if ($reload == "no") {
            $noreload="noreload";
        } else {
            $noreload="";
        }
        setPublicationStatus($nodeName, $published);
        enableDisableNode($nodeName, $published, $noreload);
         
         
         
        return getDAONode($nodeName);
    }

    /**
     * Apply configuration
     * 
     * @param string $nodeName Identifier of node for witch apache config will be
     *                         generated
     * 
     * @url POST :nodeName/virtualHost
     * 
     * @return Node requested Node
     */
    function applyConf($nodeName)
    {
        try{
            $node=$this->_get($nodeName);
            applyApacheNodesConfiguration($nodeName, "U");
            return $node;

        }catch (Exception $e) {
            throw new RestException($e->getCode(), $e->getMessage());
        }
    }
}

