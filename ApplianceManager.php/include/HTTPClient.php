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
 * @category ReverseProxy
 * @package  OSA
 * @author   Benoit HERARD <benoit.herard@orange.com>
 * @license  http://www.apache.org/licenses/LICENSE-2.0.htm Apache 2 license
 * @link     https://github.com/zorglub42/OSA/
 */
class HttpResponse
{
        
    //private memebers
    private $_statusCode; // HTTP Status Code
    private $_statusLabel; // HTTP Status label

    private $_body=""; // Response body
    private $_headers=array(); // Response headers

    /**
     * Constructor
     * 
     * @param string $streamContent a string containing the entire response stream,
     *               including headers and body
     * 
     * @return void
     */
    public function HttpResponse($streamContent="")
    {
        if ($streamContent != "") {
            do {
                $cleanUp=false;
                if (strpos($streamContent, "200 Connection established")>0) {
                        $streamContent=substr(
                            $streamContent,
                            strpos($streamContent, "\r\n\r\n") + 4
                        );
                        $cleanUp=true;
                }

                if (strpos($streamContent, "302 Found")>0) {
                        $streamContent=substr(
                            $streamContent,
                            strpos($streamContent, "\r\n\r\n") + 4
                        );
                        $cleanUp=true;
                }
                if (strpos($streamContent, "301 Moved")>0) {
                        $streamContent=substr(
                            $streamContent,
                            strpos($streamContent, "\r\n\r\n") + 4
                        );
                        $cleanUp=true;
                }
                if (strpos($streamContent, "302 Moved")>0) {
                        $streamContent=substr(
                            $streamContent,
                            strpos($streamContent, "\r\n\r\n") + 4
                        );
                        $cleanUp=true;
                }
                
                
                if (strpos($streamContent, "100 Continue")>0) {
                        $streamContent=substr(
                            $streamContent,
                            strpos($streamContent, "\r\n\r\n") + 4
                        );
                        $cleanUp=true;
                }
            } while ($cleanUp);                      
            
            
            $streamFirstLine=substr(
                $streamContent,
                9, 
                strpos($streamContent, "\r\n") - 9
            );
            
            
            $this->_statusCode = substr(
                $streamFirstLine,
                0,
                strpos($streamFirstLine, " ")
            );
            $this->_statusLabel=substr(
                $streamFirstLine,
                strpos($streamFirstLine, " ") + 1
            );

            $strHeaders=substr(
                $streamContent,
                strpos($streamContent, "\r\n") + 2,
                strpos($streamContent, "\r\n\r\n") - strpos(
                    $streamContent,
                    "\r\n"
                ) - 2
            );
            $arrHeaders=explode("\r\n", $strHeaders);
            
            for ($i=0;$i<sizeof($arrHeaders);$i++) {
                $temp=explode(":", $arrHeaders[0]);
                $this->_headers[$temp[0]]=array(
                    "name" => $temp[0],
                    "value" => trim($temp[1])
                );
            }

            $this->_body=substr(
                $streamContent,
                strpos($streamContent, "\r\n\r\n") + 4
            );
        }                       
    }
    
    
    //Getters and setters
    /**
     * HTTP Status code for response
     *
     * @return integer
     */
    public function getStatusCode()
    {
            return $this->_statusCode;
    }
    /**
     * HTTP Status label for response
     *
     * @return string
     */
    public function getStatusLabel()
    {
            return $this->_statusLabel;
    }
    
    /**
     * Get response headers
     *
     * @return array array of headers indexed by header name
     */
    public function getHeaders()
    {
            return $this->_headers;
    }
    
    /**
     * Get response body
     *
     * @return string
     */
    public function getBody()
    {
            return $this->_body;
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
 * @category ReverseProxy
 * @package  OSA
 * @author   Benoit HERARD <benoit.herard@orange.com>
 * @license  http://www.apache.org/licenses/LICENSE-2.0.htm Apache 2 license
 * @link     https://github.com/zorglub42/OSA/
*/
class HttpClient
{

        //Private mebers
    private $_proxyHost;
    private $_proxyPort;
    private $_proxyUsername;
    private $_proxyPassword;
    private $_bypassSSLVerification;
    
    
    /**
     * Constructor
     * 
     * @param string $proxyHost             proxy to connect itenrnet
     * @param string $proxyPort             TCP port on proxy
     * @param string $proxyUsername         username for authentication on proxy  
     * @param string $proxyPassword         password for authentication on proxy
     * @param bool   $bypassSSLVerification bypass SSL Certificate verification 
     *                                      (usefull for selfsigned certificates
     * 
     * @return void
     */
    function HttpClient(
        $proxyHost="", 
        $proxyPort="", 
        $proxyUsername="", 
        $proxyPassword="", 
        $bypassSSLVerification=false
    ) {
            $this->_proxyHost=$proxyHost;
            $this->_proxyPort=$proxyPort;
            $this->_proxyUsername=$proxyUsername;
            $this->_proxyPassword=$proxyPassword;
            $this->_bypassSSLVerification=$bypassSSLVerification;
    }
    
    
    /**
     * Checks if a particular header exists in response headers
     *
     * @param string $headerName name of searched header
     * @param array  $headers    headers list
     * 
     * @return boolean
     */
    private function _headerExists($headerName, $headers)
    {
        $found = false;
        
        $i=0;
        while (is_array($headers) && $i<sizeof($headers)&& !$found) {
            $curHeaderName=substr(
                strtolower($headers[$i]),
                0,
                strlen($headerName)+1
            );
            if ($curHeaderName == strtolower($headerName) . ":") {
                $found=true;
            } else {
                $i++;
            }
        }
        
        return $found;
    }
    
    /**
     * This function execute the HTTP request and initalize HttpResponse 
     * returned object.
     * 
     * @param string $method     HTTP method to use ("GET" or "POST")
     * @param string $url        requested url
     * @param array  $headers    (if any) array of string containing headers to send 
     *                           to remote server
     * @param string $content    (if any) FOR POST METHOD ONLY! content to post to 
     *                           remote server
     * @param string $baUsername basic authentication username on remote server
     * @param string $baPassword basic authentication password on remote server
     * 
     * @return HttpResponse 
     */
    private function _doRequest(
        $method, 
        $url, 
        $headers="", 
        $content="", 
        $baUsername="", 
        $baPassword=""
    ) {
            // If basic authentication is required, add authentication header
        if ($headers=="") {
            $headers=array();
        }
        if ($baUsername != "") {
            $headers[]= "Authorization: Basic " . 
                        base64_encode($baUsername . ":" . $baPassword);
        }
        
        
        $cUrl = curl_init();
        if ($method == 'POST') {
            curl_setopt($cUrl, CURLOPT_POST, 1);
            curl_setopt($cUrl, CURLOPT_POSTFIELDS, $content);
            if (!$this->headerExists("Content-Type", $headers)) {
                if (is_array($content)) {
                        $headers[]="Content-Type: multipart/form-data"; 
                } else {
                        $headers[]="Content-Type: application/x-www-form-urlencoded";
                }
            }
        }
        
        @curl_setopt($cUrl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($cUrl, CURLOPT_URL, $url);
        
        // If some headers are present, send them to remote server on request
        if ($headers != "") {
            curl_setopt($cUrl, CURLOPT_HTTPHEADER, $headers);
        }
        
        // if proxy is required for connection (see constructor parameters)
        // configure CURL to use it
        if ($this->_proxyHost != "" && $this->_proxyPort != "") {
            curl_setopt(
                $cUrl,
                CURLOPT_PROXY,
                $this->_proxyHost . ":" . $this->_proxyPort
            );
        }
        //If authentication is required on proxy, configure CURL to use it
        if ($this->_proxyUsername != "") {
            curl_setopt(
                $cUrl,
                CURLOPT_PROXYUSERPWD,
                $this->_proxyUsername . ":" . $this->_proxyPassword
            );
        }
        
        //Expect server response content on CURL's response
        curl_setopt($cUrl, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($cUrl, CURLOPT_TIMEOUT, 300);
        
        //Expect server response headers on CURL's response
        curl_setopt($cUrl, CURLOPT_HEADER, 1);
        
        if ($this->_bypassSSLVerification) {
                //Do not check SSL certificate 
                //(self signed certificates compatibility)
                curl_setopt($cUrl, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($cUrl, CURLOPT_SSL_VERIFYHOST,  0);
                curl_setopt($cUrl, CURLOPT_CAINFO, null);
                curl_setopt($cUrl, CURLOPT_CAPATH, null);       
        } else {
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
     * @param string $url        requested url
     * @param array  $headers    array of string containing headers to send to
     *                           remote server
     * @param string $baUsername basic authentication username on remote server
     * @param string $baPassword basic authentication password on remote server
     * 
     * @return HttpResponse object
     */
    function get($url, $headers="", $baUsername="", $baPassword="")
    {
            return $this->_doRequest(
                "GET",
                $url,
                $headers,
                "",
                $baUsername,
                $baPassword
            );
    }
    
    
    
    


    /**
     * Executes a POST HTTP request on remote server.
     * 
     * @param string $url        requested url
     * @param mixed  $content    string for url encoded parameters, or array
     *                           for multi-part encoding
     * @param array  $headers    array of string containing headers to send 
     *                           to remote server
     * @param string $baUsername basic authentication username on remote server
     * @param string $baPassword basic authentication password on remote server
     * 
     * @return HttpResponse object
     */
    function post($url, $content="", $headers="", $baUsername="", $baPassword="")
    {
            return $this->_doRequest(
                "POST",
                $url,
                $headers,
                $content,
                $baUsername,
                $baPassword
            );
    }
        

}

?>
