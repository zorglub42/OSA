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
 
 *--------------------------------------------------------
 * File Name   : ApplianceManager/ApplianceManager.php/include/Localization.php
 *
 * Created     : 2012-02
 * Authors     : Benoit HERARD <benoit.herard(at)orange.com>
 *
 * Description :
 *      Localized labels management
 *--------------------------------------------------------
 * History     :
 * 1.0.0 - 2012-10-01 : Release of the file
*/


/**
 * Localized label management
 * 
 * PHP Version 7.0
 * 
 * @category ReverseProxy
 * @package  OSA
 * @author   Benoit HERARD <benoit.herard@orange.com>
 * @license  http://www.apache.org/licenses/LICENSE-2.0.htm Apache 2 license
 * @link     https://github.com/zorglub42/OSA/
*/
class Localization
{
    private static $_languages = Array();
    private static $_strings =null;
    public static $debug=true;
    public static $lastModify;
    
    /**
     * Load list of languages supported by the client brother
     * return this list
     * 
     * @return array
     */
    public static function getLanguages()
    {
        if (count(self::$_languages) == 0) {
            //Initialize the requested languages array
            $hdrs=getallheaders();
            if (isset($hdrs["ACCEPT_LANGUAGE"])) {
                $lng=explode(",", $hdrs["ACCEPT_LANGUAGE"]);
                foreach ($lng as $l) {
                    $tmp=explode(";", $l);
                    $tmp=explode("-", $tmp[0]);
                    self::$_languages[$tmp[0]]= $tmp[0];
                }
            } elseif (isset($hdrs["Accept-Language"])) {
                $lng=explode(",", $hdrs["Accept-Language"]);
                foreach ($lng as $l) {
                    $tmp=explode(";", $l);
                    $tmp=explode("-", $tmp[0]);
                    self::$_languages[$tmp[0]]= $tmp[0];
                }
            } else {
                $languages[0]="fr";
            }
        }
        return self::$_languages;
    }
    
    /**
     * Get a localized string
     * 
     * @param string $string string code to localize
     * 
     * @return string
     */
    public static function getString($string)
    {
        $rc="*** $string ***";
        if (self::$lastModify==null) {
            self::$lastModify=time();
        }
        if (self::$_strings==null|| self::$debug) {
            $langList=self::getLanguages();
            foreach ($langList as $language) {
                unset($strings);
                @include "localization/$language.php";
                if (isset($strings) && isset($strings[$string])) {
                    self::$_strings=$strings;
                    self::$lastModify=filemtime(
                        dirname(__FILE__) ."/localization/$language.php"
                    );
                    return $strings[$string];
                }
            }
            unset($strings);
            include "localization/default.php";
            self::$lastModify=filemtime("localization/default.php");
            self::$_strings=$strings;
        }
        if (isset(self::$_strings[$string])) {
            return self::$_strings[$string];
        }
        return $rc;
    }

    /**
     * Get a localized string to use from JavaScript
     * 
     * @param string $string string code to localize
     * 
     * @return string
     */
    public static function getJSString($string)
    {

        return str_replace(
            "'",
            "\'",
            str_replace("\n", "\\n", self::getString($string))
        );
    }
}

?>
