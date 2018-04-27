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
 * Copyright (c) 2011 – 2017 Orange
 * This software is distributed under the Apache 2 license
 * <http://www.apache.org/licenses/LICENSE-2.0.html>
 *
 *--------------------------------------------------------
 * File Name   : ApplianceManager/ApplianceManager.php/users/Login.php
 *
 * Created     : 2017-03
 * Authors     : Benoit HERARD <benoit.herard(at)orange.com>
 *
 * Description :
 *      Authentication API
 * 
 *--------------------------------------------------------
 * History     :
 * 1.0.0 - 2017-03-06 : Release of the file
*/
require_once '../include/commonHeaders.php';

require_once '../objects/Error.class.php';
require_once '../objects/AuthToken.class.php';
require_once '../include/Constants.php';
require_once '../include/Func.inc.php';
require_once '../include/PDOFunc.php';
require_once '../include/HTTPClient.php';
require_once '../include/Settings.ini.php';
require_once 'Users.php';
/**
 * Authentication management
 * 
 * Services to login, logout and generate authentication token
 * 
 * PHP Version 7.0
 * 
 * @category ReverseProxy
 * @package  OSA
 * @author   Benoit HERARD <benoit.herard@orange.com>
 * @license  http://www.apache.org/licenses/LICENSE-2.0.htm Apache 2 license
 * @link     https://github.com/zorglub42/OSA/
 */
class Auth
{
    /**
     * Logout from system 
     * 
     * Logout and unset authentication cookie
     * 
     * @url DELETE /logout  
     * @url GET /logout
     * 
     * @return string previously connected userName 
     */
    function deleteTokensOfUserFromToken()
    {

        $error = new OSAError();
        $error->setHttpStatus(200);
        if (isset($_COOKIE[authTokenCookieName])) {
            try {
                $db=openDBConnection();
                $strSQL="";
                $strSQL=$strSQL . "SELECT * FROM authtoken WHERE token=?";
                
                $stmt=$db->prepare($strSQL);
                $stmt->execute(array($_COOKIE[authTokenCookieName]));
                
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if (!$row) {
                    $error->setHttpStatus(404);
                    $error->setFunctionalLabel("Session not found");

                } else {
                    $stmt=$db->prepare("DELETE FROM authtoken WHERE userName=?");
                    $stmt->execute(array($row["userName"]));
                    setcookie(
                        authTokenCookieName,
                        rand(1, 1000000000),
                        time()-3600, "/"
                    );
                    return $row["userName"];
                }
            }catch (Exception $e){
                if ($error->getHttpStatus() != 200) {
                    throw new RestException(
                        $error->getHttpStatus(),
                        $error->getFunctionalLabel()
                    );
                } else {
                    throw new RestException(500, $e->getMessage());
                }
            }
        } else {
            throw new RestException(400, "Session cookie not found");
        }
    }
    
    /**
     * Login
     * 
     * Login with user/passord and set authentication cookie
     * 
     * @param string $userName User namme to log in
     * @param string $password Password to authenticate
     * @param string $d        domain to set cookie
     * 
     * @url POST /login
     * 
     * @return AuthToken Authentication token 
     */
    function generateTokenFormUserAndPass($userName, $password, $d=null)
    {

            $httpClient = new HttpClient("", "", "", "", true);
            
            $headers=Array("Accept: application/json");
            $httpResponse=$httpClient->post(
                osaAdminUri . "/auth/token/me",
                "",
                $headers,
                $userName,
                $password
            );
            if ($httpResponse->getStatusCode() != 200) {
                throw new RestException(
                    $httpResponse->getStatusCode(),
                    $httpResponse->getStatusLabel() . "(backend=" . 
                                                      osaAdminUri . 
                                                      "/auth/token" . ")".
                                                      $httpResponse->getBody()
                );
            }
            /*foreach ($httpResponse->getHeaders() as $key => $value){
                    header($key . ": " . $value);
            }*/
            $tokenObj=json_decode($httpResponse->getBody(), true);
            if (!empty($d)) {
                setcookie(authTokenCookieName, $tokenObj["token"], null, "/", $d);
            } else {
                setcookie(authTokenCookieName, $tokenObj["token"], null, "/");
            }


            $db=openDBConnection();
            

            $strSQL="";
            $strSQL=$strSQL . "UPDATE users SET lastTokenLogin=" . 
                              getSQlKeyword("now") . 
                              " WHERE userName=? ";
            
            $stmt=$db->prepare($strSQL);
            $stmt->execute(array($userName));
            

            return $tokenObj;
    }

    /**
     * _getAleat
     * 
     * Return formated random number as string
     * 
     * @return string Random number as string
     */
    private function _getAleat()
    {
        return sprintf("-%010d", rand(1, 1000000000)); 
    }

    /**
     * Generate authentication a token for authenticated user
     * 
     * Generate authentication a token for authenticated user (current user)
     * 
     * @url POST /token/me 
     * 
     * @return AuthToken Token
     */
    function generate()
    {

        $requestor=getRequestor();
        $token=time() . $this->_getAleat() . 
                        $this->_getAleat() .
                        $this->_getAleat() .
                        $this->_getAleat();
        

    
        try {
            $db=openDBConnection();
            
            $db->exec(
                "DELETE FROM authtoken ".
                "WHERE validUntil<" . getSQLKeyword("now")
            );

            $strSQL="";
            $strSQL=$strSQL . "INSERT INTO authtoken (token, validUntil, userName) ";
            $strSQL=$strSQL . "VALUES (";
            $strSQL=$strSQL . "        ?,"; 
            $strSQL=$strSQL . "        " . getSQlKeyword("add_minute") . " , ";
            $strSQL=$strSQL . "        ?";
            $strSQL=$strSQL . ")";

            $stmt=$db->prepare($strSQL);
            if (RDBMS == "mysql") {
                $timeInterval = authTokenTTL;
            } else {
                $timeInterval = "+" . authTokenTTL . " minute";
            }
            $stmt->execute(array($token, $timeInterval, $requestor));
        }catch (Exception $e){
            throw new RestException(500, $e->getMessage());
        }
        return Array("token" => $token);
    
    }
    /**
     * Generate authentication a token for any user
     * 
     * Generate authentication a token for any user
     * 
     * @param string $userName User id for who we want a token
     * 
     * @url POST /token/{userName}
     * 
     * @return AuthToken Token
     */
    function generateForAny($userName)
    {

        $userService = new Users();
        $user = $userService->getOne($userName);
 
        $token=time() . $this->_getAleat() . 
                        $this->_getAleat() .
                        $this->_getAleat() .
                        $this->_getAleat();
        

    
        try {
            $db=openDBConnection();
            
            $db->exec(
                "DELETE FROM authtoken ".
                "WHERE validUntil<" . getSQLKeyword("now")
            );

            $strSQL="";
            $strSQL=$strSQL . "INSERT INTO authtoken (token, validUntil, userName) ";
            $strSQL=$strSQL . "VALUES (";
            $strSQL=$strSQL . "        ?,"; 
            $strSQL=$strSQL . "        " . getSQlKeyword("add_minute") . " , ";
            $strSQL=$strSQL . "        ?";
            $strSQL=$strSQL . ")";

            $stmt=$db->prepare($strSQL);
            if (RDBMS == "mysql") {
                $timeInterval = authTokenTTL;
            } else {
                $timeInterval = "+" . authTokenTTL . " minute";
            }
            $stmt->execute(array($token, $timeInterval, $userName));
        }catch (Exception $e){
            throw new RestException(500, $e->getMessage());
        }
        return Array("token" => $token);
    
    }
}
?>
