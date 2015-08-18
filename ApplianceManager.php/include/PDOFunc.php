<?php

function openDB($BDName, $BDUser, $BDPwd){
	$T=split("@", $BDName);
	$DB=$T[0];
	$T=split(":",$T[1]);
	$HOST=$T[0];
	$PORT=$T[1];
	
	$db = new PDO("mysql:host=" . $HOST . ";dbname=". $DB . ";charset=utf8;port=" . $PORT, $BDUser, $BDPwd, array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
	return $db;
}


function EscapeOrder($str){

		$strOUT=ereg_replace(";","", ereg_replace("'","''",$str));
		return $strOUT;
}

function cut($str, $Lng){
	global $includeBDMySQL;

	return substr($str,0, $Lng);
}


?>
