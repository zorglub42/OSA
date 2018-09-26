/**--------------------------------------------------------
* Module Name : RunTimeAppliance
* Version : 2.0.0
*
* Software Name : OpenSourceAppliance
* Version : 2.4
*
* Copyright (c) 2012 – 2013 France Télécom
* This software is distributed under the Apache 2 license
* <http://www.apache.org/licenses/LICENSE-2.0.html>
*
*--------------------------------------------------------
* File Name   : ApplianceManager/RunTimeAppliance/sql/sqlite/3.0-to-3.0.1.sql
*
* Created     : 2018-09-11
* Authors     : zorglub42 <contact@zorglub42.fr>
*
* Description :
* 	Upgrade database form version 3.0 to version 3.0.1
*--------------------------------------------------------
* History     :
*
* 1.0.0 - 2018-06-25 : Release of the file
**/
DROP TABLE `hits`;
CREATE TABLE `hits` (
`id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
`frontEndEndPoint` text NOT NULL,
`userName` varchar(45) NOT NULL,
`timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
`message` varchar(1024) NOT NULL,
`status` int(11) DEFAULT NULL,
`serviceName` varchar(45) DEFAULT NULL
) ;
CREATE INDEX idx_serviceName on hits(serviceName);
CREATE INDEX idx_userName on hits(userName);
