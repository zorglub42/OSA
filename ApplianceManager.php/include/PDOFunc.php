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
 * Version : 1.0
 *
 * Copyright (c) 2011 – 2014 Orange
 * This software is distributed under the Apache 2 license
 * <http://www.apache.org/licenses/LICENSE-2.0.html>
 *
 *--------------------------------------------------------
 * File Name   : ApplianceManager/ApplianceManager.php/include/PDOFunc.php
 *
 * Created     : 2012-02
 * Authors     : Benoit HERARD <benoit.herard(at)orange.com>
 *
 * Description :
 *      Various functions to use PDO
 *--------------------------------------------------------
 * History     :
 * 1.0.0 - 2012-10-01 : Release of the file
*/

/**
 * Get an SQL keyword according to the proper gramar (MySQL/SQLite)
 * 
 * @param string $word key word ID to get
 * 
 * @return string
 */
function getSQlKeyword($word)
{
    $sqlGrammar = array(
        "sqlite"=> array(
                "now"=>'DateTime("Now")',
                "add_minute" =>'DateTime(CURRENT_TIMESTAMP, ?)',
            ),
        "mysql" => array(
                "now"=>"now()",
                "add_minute" => " date_add(now() ,interval ? minute)"
            )
    );

    return $sqlGrammar[RDBMS][$word];
}

/**
 * Open a connection to the database using the proper lib (MySQL/SQLite)
 * 
 * @return PDO
 */
function openDBConnection()
{
    if (RDBMS=="sqlite") {
        return openDBConnectionSQLITE();
    } else {
        return openDBConnectionMYSQL();
    }
}

/**
 * Open a connection to the SQLite database
 * 
 * @return PDO
 */
function openDBConnectionSQLITE()
{
    $pdo = new PDO('sqlite:' . SQLITE_DATABASE_PATH);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // ERRMODE_WARNING | ERRMODE_EXCEPTION | ERRMODE_SILENT
    $pdo->exec("PRAGMA foreign_keys = ON");
    $pdo->exec("PRAGMA auto_vacuum = 1");
    //$pdo->exec("PRAGMA read_uncommitted = True");

    return $pdo;
}

/**
 * Open a connection to the MySQL database
 * 
 * @return PDO
 */
function openDBConnectionMYSQL()
{
    @include 'Settings.ini.php';

    $T=explode("@", $BDName);
    $DB=$T[0];
    $T=explode(":", $T[1]);
    $HOST=$T[0];
    $PORT=$T[1];
    
    $db = new PDO(
        "mysql:host=" . $HOST . ";dbname=". $DB . ";charset=utf8;port=" . $PORT,
        $BDUser,
        $BDPwd,
        array(
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        )
    );
    return $db;

}


/**
 * Escape "dangerous" char (SQL Injection) in a string
 * 
 * @param string $str string to escape
 * 
 * @return string
*/
function escapeOrder($str)
{

        $strOUT=preg_replace("/;/", "", preg_replace("/'/", "''", $str));
        return $strOUT;
}

/**
 * Cut a string at a particular length
 * 
 * @param string $str String to cut
 * @param int    $Lng Maximal length
 * 
 * @return string
 */
function cut($str, $Lng)
{
    return substr($str, 0, $Lng);
}


?>
