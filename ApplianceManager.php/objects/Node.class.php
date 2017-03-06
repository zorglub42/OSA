<?php
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

class Node extends ApplianceObject{

	//Private mebers
	/**
	 * @var string nodeName node identifier
	 */
	public $nodeName;
	/**
	 * @var string nodeDescription description of this node
	 */
	public $nodeDescription;
	/**
	 * @var int isHTTPS Does this node use HTTPS? (O: no, 1: yes) {@choice 0,1}
	 */
	public $isHTTPS;
	/**
	 * @var int isBasicAuthEnabled  Does this node handle basic authentication? (O: no, 1: yes) {@choice 0,1}
	 */
	public $isBasicAuthEnabled;
	/**
	 * @var int iscookieAuthEnabled Does this not handel cookie based authentication? (O: no, 1: yes) {@choice 0,1}
	 */
	public $iscookieAuthEnabled;
	/**
	 * @var string serverFQDN public FQDN for this node
	 */
	public $serverFQDN;
	/**
	 * @var string loalIP local listening IP (or *) of this note
	 */
	public $localIP;
	/**
	 * @var int port listening port
	 */
	public $port;
	/**
	 * @var string privateKey for HTTPS
	 */
	public $publicKey;
	/**
	 * @var string cert server certificate for HTTPS
	 */
	public $cert;
	/**
	 * @var string ca Certification authority certificate
	 */
	public $ca;
	/**
	 * @var string intermediate certification authority certificates
	 */
	public $caChain;
	/**
	 * @var string additionalConfiguration additionnal apache directive for this virtualHost/node
	 */
	public $additionalConfiguration;
	/**
	 * @var int isPublished Is this node published? (O: no, 1: yes) {@choice 0,1}
	 */
	public $isPublished;

	
	function setAdditionalConfiguration($additionalConfiguration){
		$this->additionalConfiguration=($additionalConfiguration=="null"?"":$additionalConfiguration);
	}
	function getAdditionalConfiguration(){
		return $this->additionalConfiguration;
	}
	function setNodeDescription($nodeDescription){
		$this->nodeDescription=$nodeDescription;
	}
	function getNodeDescription(){
		return $this->nodeDescription;
	}
	
	function setPort($port){
		$this->port=$port;
	}
	function getPort(){
		return $this->port;
	}
	
	function setIsCookieAuthEnabled($isCookieAuthEnabled){
		$this->isCookieAuthEnabled=$isCookieAuthEnabled;
	}
	function getIsCookieAuthEnabled(){
		return $this->isCookieAuthEnabled;
	}
	
	
	
	
	function setNodeName($nodeName){
		$this->nodeName=$nodeName;
	}
	function getNodeName(){
		return $this->nodeName;
	}
	
	
	
	
	
	function setServerFQDN($serverFQDN){
		$this->serverFQDN=$serverFQDN;
	}
	function getServerFQDN(){
		return $this->serverFQDN;
	}


	function setIsHTTPS($isHTTPS){
		$this->isHTTPS=$isHTTPS;
	}
	function getIsHTTPS(){
		return $this->isHTTPS;
	}
	
	function setIsBasicAuthEnabled($isBasicAuthEnabled){
		$this->isBasicAuthEnabled=$isBasicAuthEnabled;
	}
	function getIsBasicAuthEnabled(){
		return $this->isBasicAuthEnabled;
	}
	function getLocalIP(){
		return $this->localIP;
	}
	function setLocalIP($localIP){
		$this->localIP=$localIP;
	}
	function getPrivateKey(){
		return $this->privateKey;
	}
	function setPrivateKey($privateKey){
		$this->privateKey=$privateKey;
	}
	function getCert(){
		return $this->cert;
	}
	function setCert($cert){
		$this->cert=$cert;
	}
	function getCa(){
		return $this->ca;
	}
	function setCa($ca){
		$this->ca=$ca;
	}

	function getChain(){
		return $this->chain;
	}
	function setChain($chain){
		$this->chain=$chain;
	}
	function getIsPublished(){
		return $this->isPublished;
	}
	function setIsPublished($isPublished){
		$this->isPublished=$isPublished;
	}

    public function __construct($rqt=NULL)
    {
		if ($rqt != NULL){
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
			$this->setUri( "nodes/" . urlencode($rqt["nodeName"]));
			$this->setAdditionalConfiguration($rqt["additionalConfiguration"]);
			$this->setIsPublished($rqt["isPublished"]);
		}
	}
	
	function toArray(){
		$certUri="";
		$privateKeyUri="";
		$caUri="";
		$caChainUri="";
		if ($this->getIsHTTPS()){
			$certUri=$this->getUri() . "/cert";
			$privateKeyUri=$this->getUri() . "/privateKey";
			if ($this->getCa() != NULL){
				$caUri=$this->getUri() . "/ca";
			}
			if ($this->getChain() != NULL){
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
