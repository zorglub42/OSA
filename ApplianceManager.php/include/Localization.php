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


class Localization{
	private static $languages = Array();
	private static $strings =null;
	public static $debug=false;
	public static $lastModify;
	
	private static function getLanguages(){
		if (count(self::$languages) == 0){
			//Initialize the requested languages array
			$hdrs=getallheaders();
			if (isset($hdrs["ACCEPT_LANGUAGE"])){
				$lng=explode(",",$hdrs["ACCEPT_LANGUAGE"]);
				foreach ($lng as $l){
					$tmp=explode(";", $l);
					$tmp=explode("-",$tmp[0]);
					self::$languages[$tmp[0]]= $tmp[0];
				}
			}elseif (isset($hdrs["Accept-Language"])){
				$lng=explode(",",$hdrs["Accept-Language"]);
				foreach ($lng as $l){
					$tmp=explode(";", $l);
					$tmp=explode("-",$tmp[0]);
					self::$languages[$tmp[0]]= $tmp[0];
				}
			}else{
				$languages[0]="fr";
			}
		}
		return self::$languages;	
	}
	
	public static function getString($string){
		$rc="*** $string ***";
		if (self::$lastModify==null){
			self::$lastModify=time();
		}
		if (self::$strings==null|| self::$debug){
			$langList=self::getLanguages();
			foreach ($langList as $language){
				unset($strings);
				@include "localization/$language.php";
				if (isset($strings) && isset($strings[$string])){
					self::$strings=$strings;
					self::$lastModify=filemtime(dirname(__FILE__) ."/localization/$language.php");
					return $strings[$string];
				}
			}
			unset($strings);
			include "localization/default.php";
			self::$lastModify=filemtime("localization/default.php");
			self::$strings=$strings;
		}
		if (isset(self::$strings[$string])){
			return self::$strings[$string];
		}
		return $rc;
	}
	public static function getJSString($string){

		return str_replace("'","\'",str_replace("\n","\\n",self::getString($string)));;
	}
	

}

?>
