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
 * File Name   : include/HTTPClient.php
 *
 * Created     : 2013-03
 * Authors     : Benoit HERARD <benoit.herard(at)orange.com>
 *
 * Description :
 *    Basic HTTP Client
 *--------------------------------------------------------
 * History     :
 * 1.0.0 - 2013-03-20 : Release of the file
 */
/**
 * This class represente the result of an HTTP query
 * 
 * @package orange
 * 
 */
class HttpResponse{
        
        //private memebers
        private $statusCode; // HTTP Status Code
        private $statusLabel; // HTTP Status label
        
        private $body=""; // Response body
        private $headers=array(); // Response headers
        
        /**
         * Constructor
         * 
         * @param string $streamContent a string containing the entire response stream, including headers and body
         */
        public function HttpResponse($streamContent=""){
                
                
                if ($streamContent != ""){
        
                        
                        do{
                                $cleanUp=false;
                                if (strpos($streamContent, "200 Connection established")>0){
                                        //If https connect through a proxy a first set of headers occurs....
                                        //Remove It....
                                        $streamContent=substr($streamContent,strpos($streamContent,"\r\n\r\n") +4);
                                        $cleanUp=true;
                                }
        
                                if (strpos($streamContent, "302 Found")>0){
                                        //If https connect through a proxy a first set of headers occurs....
                                        //Remove It....
                                        $streamContent=substr($streamContent,strpos($streamContent,"\r\n\r\n") +4);
                                        $cleanUp=true;
                                }
                                if (strpos($streamContent, "301 Moved")>0){
                                        //If https connect through a proxy a first set of headers occurs....
                                        //Remove It....
                                        $streamContent=substr($streamContent,strpos($streamContent,"\r\n\r\n") +4);
                                        $cleanUp=true;
                                }
                                if (strpos($streamContent, "302 Moved")>0){
                                        //If https connect through a proxy a first set of headers occurs....
                                        //Remove It....
                                        $streamContent=substr($streamContent,strpos($streamContent,"\r\n\r\n") +4);
                                        $cleanUp=true;
                                }
                                
                                
                                if (strpos($streamContent, "100 Continue")>0){
                                        //If https connect through a proxy a first set of headers occurs....
                                        //Remove It....
                                        $streamContent=substr($streamContent,strpos($streamContent,"\r\n\r\n") +4);
                                        $cleanUp=true;
                                }
                        }while ($cleanUp);                      
                        
                        
                        $streamFirstLine=substr($streamContent,9, strpos($streamContent,"\r\n")-9);
                        
                        
                        $this->statusCode = substr($streamFirstLine,0, strpos($streamFirstLine," "));
                        $this->statusLabel=substr($streamFirstLine,strpos($streamFirstLine," ")+1);
        
                        $strHeaders=substr($streamContent,strpos($streamContent,"\r\n")+2, strpos($streamContent,"\r\n\r\n") - strpos($streamContent,"\r\n")-2);
                        $arrHeaders=explode("\r\n", $strHeaders);
                        
                        for ($i=0;$i<sizeof($arrHeaders);$i++){
                                $temp=explode(":",$arrHeaders[0]);
                                $this->headers[$temp[0]]=array("name" => $temp[0], "value" => trim($temp[1]));
                        }
        
                        $this->body=substr($streamContent,strpos($streamContent,"\r\n\r\n") +4);
                }                       
        }
        
        
        //Getters and setters
        /**
         * HTTP Status code for response
         *
         * @return integer
         */
        public function getStatusCode(){
                return $this->statusCode;
        }
        /**
         * HTTP Status label for response
         *
         * @return string
         */
        public function getStatusLabel(){
                return $this->statusLabel;
        }
        
        /**
         * response headers
         *
         * @return array array of headers indexed by header name
         */
        public function getHeaders(){
                return $this->headers;
        }
        
        /**
         * response body
         *
         * @return string
         */
        public function getBody(){
                return $this->body;
        }
        
        
}

/**
 * Class used to get content though Http for GET and POST method,
 * (optional) using connection via proxy and basic authentication for:
 *      - URL retreived
 *      - proxy 
 * 
 * REQUIRES CURL EXTENSION
 * 
 * @package orange
 */
class HttpClient{

        //Private mebers
        private $proxyHost;
        private $proxyPort;
        private $proxyUsername;
        private $proxyPassword;
        private $bypassSSLVerification;
        
        
        /**
         * Constructor
         * 
         * @param string $proxyHost proxy to connect itenrnet
         * @param string $proxyPort TCP port on proxy
         * @param string $proxyUSername username for authentication on proxy  
         * @param string $proxyPassword password for authentication on proxy
         * @param boolean $bypassSSLVerification bypass SSL Certificate verification (usefull for selfsigned certificates
         * 
         */
        function HttpClient($proxyHost="", $proxyPort="", $proxyUsername="", $proxyPassword="",$bypassSSLVerification=false){
                $this->proxyHost=$proxyHost;
                $this->proxyPort=$proxyPort;
                $this->proxyUsername=$proxyUsername;
                $this->proxyPassword=$proxyPassword;
                $this->bypassSSLVerification=$bypassSSLVerification;
        }
        
        
        /**
         * Checks if a particular header exists in response headers
         *
         * @param string $headerName
         * @param array $headers
         * @return boolean
         */
        private function headerExists($headerName, $headers){
                $found = false;
                
                $i=0;
                while (is_array($headers) && $i<sizeof($headers)&& !$found){
                        if (substr(strtolower($headers[$i]), 0,strlen($headerName)+1) == strtolower($headerName) . ":"){
                                $found=true;
                        }else{
                                $i++;
                        }
                }
                
                return $found;
        }
        
        /**
         * This function execute the HTTP request and initalize HttpResponse returned object.
         * 
         * @param $method: HTTP method to use ("GET" or "POST")
         * @param $url: requested url
         * @param $headers: (if any) array of string containing headers to send to remote server
         * @param $content: (if any) FOR POST METHOD ONLY! content to post to remote server
         * @param $baUsername: basic authentication username on remote server
         * @param $baPassword: basic authentication password on remote server
         * 
         * @return HttpResponse 
         */
        private function doRequest($method, $url, $headers="", $content="", $baUsername="", $baPassword="")
        {
                // If basic authentication is required, add authentication header
                if ($headers==""){
                        $headers=array();
                }
                if ($baUsername != ""){
                        $headers[]= "Authorization: Basic " . base64_encode($baUsername . ":" . $baPassword) ;
                }
                
                
                $cUrl = curl_init();
                if ($method == 'POST') {
                        curl_setopt($cUrl, CURLOPT_POST, 1);
                        curl_setopt($cUrl, CURLOPT_POSTFIELDS, $content);
                        if (!$this->headerExists("Content-Type", $headers)){
                                if (is_array($content)){
                                        $headers[]="Content-Type: multipart/form-data"; 
                                }else{
                                        $headers[]="Content-Type: application/x-www-form-urlencoded";
                                }
                        }
                }
                
                @curl_setopt($cUrl, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($cUrl, CURLOPT_URL, $url);
                
                // If some headers are present, send them to remote server on request
                if ($headers != ""){
                        curl_setopt($cUrl, CURLOPT_HTTPHEADER, $headers);
                }
                
                // if proxy is required for connection (see constructor parameters) configure CURL to use it
                if ($this->proxyHost != "" && $this->proxyPort != ""){
                        curl_setopt($cUrl, CURLOPT_PROXY, $this->proxyHost . ":" . $this->proxyPort );
                }
                //If authentication is required on proxy, configure CURL to use it
                if ($this->proxyUsername != ""){
                        curl_setopt($cUrl, CURLOPT_PROXYUSERPWD, $this->proxyUsername . ":" . $this->proxyPassword);
                }
                
                //Expect server response content on CURL's response
                curl_setopt($cUrl, CURLOPT_RETURNTRANSFER, 1);
                //curl_setopt($cUrl, CURLOPT_TIMEOUT, 300);
                
                //Expect server response headers on CURL's response
                curl_setopt($cUrl, CURLOPT_HEADER, 1);
                
                if ($this->bypassSSLVerification){
                        //Do not check SSL certificate (self signed certificates compatibility)
                        curl_setopt($cUrl, CURLOPT_SSL_VERIFYPEER, FALSE);
                        curl_setopt($cUrl, CURLOPT_SSL_VERIFYHOST,  0);
                        curl_setopt($cUrl, CURLOPT_CAINFO, NULL);
                        curl_setopt($cUrl, CURLOPT_CAPATH, NULL);       
                }else{
                        curl_setopt($cUrl, CURLOPT_SSL_VERIFYPEER, true);
                        curl_setopt($cUrl, CURLOPT_SSL_VERIFYHOST,  true);
                        
                }

                
                $pageContent = trim(curl_exec($cUrl));
                echo curl_error($cUrl); 
                //echo "Curl dit Code=" . curl_getinfo($cUrl, CURLINFO_HTTP_CODE);
                curl_close($cUrl);
                
                
                $resp = new HttpResponse($pageContent);
                
                return $resp;
        }
        
        /**
         * Executes a GET HTTP request on remote server.
         * 
         * @param string $url requested url
         * @param array $headers array of string containing headers to send to remote server
         * @param string $baUsername basic authentication username on remote server
         * @param string $baPassword basic authentication password on remote server
         * 
         * @return HttpResponse object
         */
        function Get($url, $headers="", $baUsername="", $baPassword="")
        {
                return $this->doRequest("GET",  $url, $headers, "", $baUsername, $baPassword);
        }
        
        
        
        


        /**
         * Executes a POST HTTP request on remote server.
         * 
         * @param string $url requested url
         * @param mixed $content  string for url encoded parameters, or array for multi-part encoding
         * @param array $headers  array of string containing headers to send to remote server
         * @param string $baUsername basic authentication username on remote server
         * @param string $baPassword basic authentication password on remote server
         * 
         * @return HttpResponse object
         */
        function Post($url, $content="", $headers="", $baUsername="", $baPassword="")
        {
                return $this->doRequest("POST",  $url, $headers, $content, $baUsername, $baPassword);
        }
        

}

?>
