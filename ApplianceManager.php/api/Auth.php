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
require_once '../objects/Session.class.php';
require_once '../include/Constants.php';
require_once '../include/Func.inc.php';
require_once '../include/PDOFunc.php';
require_once '../include/HTTPClient.php';
require_once '../include/Settings.ini.php';
require_once 'Users.php';
require_once 'sessionDAO.php';
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
                                                      "/auth/token/me" . ")".
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
                              getSQLKeyword("now") . 
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
        return $this->generateForAny($requestor);
    
    }
    /**
     * Generate authentication a token for any user
     * 
     * Generate authentication a token for any user
     * 
     * @param string $userName   User id for who we want a token
     * @param int    $mustExists User must exists in OSA (default 1)
     *                           {@choice 1,0} {@from query}
     * 
     * @url POST /token/{userName}
     * 
     * @return AuthToken Token
     */
    function generateForAny($userName, $mustExists=1)
    {

        if ($mustExists == 1) {
            $userService = new Users();
            $user = $userService->getOne($userName);
        }
 
        

    
        try {
            $db=openDBConnection();
            
            $db->exec(
                "DELETE FROM authtoken ".
                "WHERE validUntil<" . getSQLKeyword("now")
            );

            $strSQL="";
            $strSQL=$strSQL . "INSERT INTO authtoken (token, initialToken, validUntil, userName) ";
            $strSQL=$strSQL . "VALUES (";
            $strSQL=$strSQL . "        ?,"; 
            $strSQL=$strSQL . "        ?,"; 
            $strSQL=$strSQL . "        " . getSQLKeyword("add_minute") . " , ";
            $strSQL=$strSQL . "        ?";
            $strSQL=$strSQL . ")";

            $stmt=$db->prepare($strSQL);
            if (RDBMS == "mysql") {
                $timeInterval = authTokenTTL;
            } else {
                $timeInterval = "+" . authTokenTTL . " minute";
            }

            $tokenGenerated=false;
            while (!$tokenGenerated) {
                $tokenGenerated=true;
                list($usec, $sec) = explode(" ", microtime());
                $time_in_micros = $sec .  ($usec*1000000);

                $token=md5(
                    $time_in_micros . sprintf("-%010d", getmypid()) . 
                    $this->_getAleat() . 
                    $this->_getAleat()
                );
                try{
                    $stmt->execute(array($token, $token, $timeInterval, $userName));

                }catch (Exception $e){
                    if (strpos($e->getMessage(), "Duplicate entry")>=0 
                        ||strpos($e->getMessage(), "UNIQUE constraint failed")>=0
                    ) {
                        $tokenGenerated=false;
                    } else {
                        throw $e;
                    }
                }
                        
            }
        }catch (Exception $e){
            throw new RestException(500, $e->getMessage());
        }
        return Array("token" => $token);
    
    }

    /**
     * List all active session
     * 
     * Get a list of all active sessions
     * 
     * @param string $userName Retreive only active sessions for this user (optional)
     * 
     * @url GET /sessions
     * 
     * @return Array {@type Session} Token
     */
    function getAllSessions($userName=null) {
        try{
            $rc = getActiveSessions($userName);
        }catch (Exception $e){
            throw new RestException(500, $e->getMessage());
        }
        return $rc;
    }
    /**
     * Close a session
     * 
     * Close and existing session on server
     * 
     * @param string $id Session identifier to close
     * 
     * @url DELETE /sessions/{id}
     * 
     * @return Session Closed session
     */
    function closeSession($id) {
        try{
            $rc = closeSessionById($id);
        }catch (Exception $e){
            if (is_numeric($e->getCode())) {
                throw new RestException($e->getCode(), $e->getMessage());
            } else {
                throw new RestException(500, $e->getMessage());
            }

        }
        return $rc;
    }


    /**
     * Get a session
     * 
     * Get a session by its ID
     * 
     * @param string $id Session identifier
     * 
     * @url GET /sessions/{id}
     * 
     * @return Session requested session
     */
    function getOne($id) {
        try{
            return getSessionById($id);
        }catch (Exception $e){
            if (is_numeric($e->getCode())) {
                throw new RestException($e->getCode(), $e->getMessage());
            } else {
                throw new RestException(500, $e->getMessage());
            }

        }

    }
}
?>
