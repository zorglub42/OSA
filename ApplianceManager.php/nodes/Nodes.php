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

require_once('../include/commonHeaders.php');

require_once 'nodeDAO.php';


class Nodes{
	
	/**
	 * @url GET :nodeName/services
	 * Return services published on this node
	 */
	 function publishedServices($nodeName){
		try{
				$services = getServices($nodeName);
				$rc = Array();
				foreach($services as $aService)  {
					array_push ($rc, $aService->toArray());
				}
				return $rc;
		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	 }
	/**
	 * @url DELETE :nodeName/ca
	 */
	 function removeCa($nodeName){
		try{
			updateCaCert($nodeName,NULL);
			$this->getCa($nodeName);
		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	}
	/**
	 * @url POST :nodeName/ca
	 */
	 function uploadCa($nodeName){
		try{
			$ca=file_get_contents($_FILES["files"]["tmp_name"][0]);
			if ($ca == NULL || $ca=="" ){
				throw new RestException(400 ,"ca cert is required\n");
			}else{
				updateCaCert($nodeName, $ca);
				$this->getCa($nodeName);
			}
		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	}
	/**
	 * @url DELETE :nodeName/chain
	 */
	 function removeChain($nodeName){
		try{
			updateCaChain($nodeName,NULL);
			$this->getChain($nodeName);
		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	}
	/**
	 * @url POST :nodeName/chain
	 */
	 function uploadChain($nodeName){
		try{
			$chain=file_get_contents($_FILES["files"]["tmp_name"][0]);
			if ($chain == NULL || $chain=="" ){
				throw new RestException(400 ,"chain cert is required\n");
			}else{
				updateCaChain($nodeName, $chain);
				$this->getChain($nodeName);
			}
		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	}
	
	/**
	 * @url DELETE :nodeName/cert
	 */
	 function removeCert($nodeName){
		try{
			updateCert($nodeName,NULL);
			$this->getCert($nodeName);
		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	}
	/**
	 * @url POST :nodeName/cert
	 */
	 function uploadCert($nodeName){
		try{
			//if ($_FILES["files"]["type"][0] != "application/x-x509-ca-cert"){
			//	throw new RestException(400, $_FILES["files"]["name"][0] . " is not a valid certificate file (" . $_FILES["files"]["type"][0] . ")"); 
			//}
			$cert=file_get_contents($_FILES["files"]["tmp_name"][0]);
			if ($cert == NULL || $cert=="" ){
				throw new RestException(400 ,"cert is required\n");
			}else{
				updateCert($nodeName, $cert);
				$this->getCert($nodeName);
			}
		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	}
	/**
	 * @url POST :nodeName/privateKey
	 */
	 function uploadPrivateKey($nodeName){
		try{
			//if ($_FILES["files"]["type"][0] != "application/pgp-keys"){
			//	throw new RestException(400, $_FILES["files"]["name"][0] . " is not a valid private key file (" . $_FILES["files"]["type"][0] . ")"); 
			//}
			$key=file_get_contents($_FILES["files"]["tmp_name"][0]);
			if ($key == NULL || $key=="" ){
				throw new RestException(400 ,"private key is required\n");
			}else{
				updatePrivateKey($nodeName, $key);
				$this->getPrivateKey($nodeName);
			}
		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	}
	/**
	 * @url DELETE :nodeName/privateKey
	 */
	 function removePrivateKey($nodeName){
		try{
			updatePrivateKey($nodeName, NULL);
			$this->getPrivateKey($nodeName);
		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	}
	/**
	 * @url GET :nodeName/ca
	 */
	 function getCa($nodeName){
		try{
			$node=getDAONode($nodeName);
			header("Content-Type: text/plain", true);
			echo $node->getCa();
		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	}
	/**
	 * @url GET :nodeName/chain
	 */
	 function getChain($nodeName){
		try{
			$node=getDAONode($nodeName);
			header("Content-Type: text/plain", true);
			echo $node->getChain();
		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	}
	/**
	 * @url GET :nodeName/cert
	 */
	 function getCert($nodeName){
		try{
			$node=getDAONode($nodeName);
			header("Content-Type: text/plain", true);
			echo $node->getCert();
		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	}
	/**
	 * @url GET :nodeName/privateKey
	 */
	 function getPrivateKey($nodeName){
		try{
			$node=getDAONode($nodeName);
			header("Content-Type: text/plain", true);
			echo $node->getPrivateKey();
		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	}
	
	function getParameterValue($paramName, $request_data){
		if (isset($request_data[$paramName]) && $request_data[$paramName]!="" ){
			return $request_data[$paramName];
		}else{
			return NULL;
		}
	}


	/**
	 * @url GET
	 * @url GET :nodeName
	 */
	function get($nodeName=NULL, $request_data = NULL){
		try{
			if ($nodeName!=NULL){
				return getDAONode($nodeName, $request_data)->toArray();
			}else{
				$nodes=getDAONode($nodeName, $request_data);
				$rc = Array();
				foreach($nodes as $aNode)  {
					array_push ($rc, $aNode->toArray());
				}
				return $rc;
			}
		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	}
	
	/**
	 * @url GET :nodeName/virtualHost
	 */
	 function generateVirtualHost($nodeName){
		try{
			$node=getDAONode($nodeName);
			 header("Content-Type: text/plain", true);
			 
			 $HTTP_VHOST_ADDR=$node->getLocalIP();
			 $HTTP_VHOST_PORT=$node->getPort(); 
			 $HTTP_VHOST_NAME=$node->getServerFQDN();
			 $NODE_NAME=$node->getNodeName();
			 
			$HTTP_VHOST_TOP_DOMAIN=""; 
			$domParts=explode(".", $node->getServerFQDN());
			if (count($domParts)>1){
				for ($i=1;$i<count($domParts);$i++){
					$HTTP_VHOST_TOP_DOMAIN = $HTTP_VHOST_TOP_DOMAIN . "." . $domParts[$i];
				}
			}else{
				$HTTP_VHOST_TOP_DOMAIN=$node->getServerFQDN();
			}
			
			$ADDITIONAL_CONFIGURATION=$node->getAdditionalConfiguration();	
			 
			 
			 if ($node->getIsHTTPS()==0){
				require_once "../resources/apache.conf/http_virtualhost_template.php";
			 }else{
				$HTTPS_HAVE_CA_CERT=false; 
				$HTTPS_HAVE_CHAIN_CERT=false; 
				if ($node->getCa() != ""){
					$HTTPS_HAVE_CA_CERT=true;
				}
				if ($node->getChain() != ""){
					$HTTPS_HAVE_CHAIN_CERT=true;
				}
				require_once "../resources/apache.conf/https_virtualhost_template.php";
			 }
		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	}
	
	/**
	 * @url POST
	 * @url POST :nodeName
	 */
	function create($nodeName=NULL, $request_data = NULL){
		try{
			$rc= addNode($nodeName, $request_data);
			if (!isset($request_data["apply"]) || $request_data["apply"]=="1"){ 
				applyApacheNodesConfiguration($nodeName, "C");
			}
			return $rc->toArray();;
		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	}
	/**
	 * @url PUT
	 * @url PUT :nodeName
	 */
	function update($nodeName=NULL, $request_data = NULL){
		try{
			$rc= updateNode($nodeName, $request_data);
			if (!isset($request_data["apply"]) || $request_data["apply"]=="1"){ 
				applyApacheNodesConfiguration($nodeName, "U");
			}
			return $rc->toArray();

		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	}
	/**
	 * @url DELETE
	 * @url DELETE :nodeName
	 */
	function delete($nodeName=NULL, $request_data = NULL){
		try{
			$rc= deleteNode($nodeName);
			if (!isset($request_data["apply"]) || $request_data["apply"]=="1"){ 
				applyApacheNodesConfiguration($nodeName, "D");
			}
			return $rc;

		}catch (Exception $e){
			throw new RestException($e->getCode(), $e->getMessage());
		}
	}
}
