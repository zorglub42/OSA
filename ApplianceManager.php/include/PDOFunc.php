<?php
require_once "Settings.ini.php";


function getSQlKeyword($word){
	$sqlGrammar = array("sqlite"=>
			array("now"=>'DateTime("Now")',
				"add_minute" =>'DateTime(CURRENT_TIMESTAMP, ?)',
			),
		"mysql" =>
			array("now"=>"now()",
				"add_minute" => " date_add(now() ,interval ? minute)"
			)
	);

	return $sqlGrammar[RDBMS][$word];
}

function openDBConnection(){
    if (RDBMS=="sqlite") {
        return openDBConnectionSQLITE();
    } else {
        return openDBConnectionMYSQL();
    }
}

function openDBConnectionSQLITE(){
    $pdo = new PDO('sqlite:' . SQLITE_DATABASE_PATH);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // ERRMODE_WARNING | ERRMODE_EXCEPTION | ERRMODE_SILENT
    $pdo->exec("PRAGMA foreign_keys = ON");
    $pdo->exec("PRAGMA auto_vacuum = 1");
    //$pdo->exec("PRAGMA read_uncommitted = True");

    return $pdo;
}

function openDBConnectionMYSQL(){
	global $BDName, $BDUser, $BDPwd;

	return openDB($BDName, $BDUser, $BDPwd);

}

function openDB($BDName, $BDUser, $BDPwd){
	$T=explode("@", $BDName);
	$DB=$T[0];
	$T=explode(":",$T[1]);
	$HOST=$T[0];
	$PORT=$T[1];
	
	$db = new PDO("mysql:host=" . $HOST . ";dbname=". $DB . ";charset=utf8;port=" . $PORT, $BDUser, $BDPwd, array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
	return $db;
}


function EscapeOrder($str){

		$strOUT=preg_replace("/;/","", preg_replace("/'/","''",$str));
		return $strOUT;
}

function cut($str, $Lng){
	global $includeBDMySQL;

	return substr($str,0, $Lng);
}


?>
