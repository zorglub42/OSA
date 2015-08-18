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
* File Name   : ApplianceManager/RunTimeAppliance/sql/2.2-to-2.3.sql
*
* Created     : 2014-09-23
* Authors     : Benoit HERARD <benoit.herard(at)orange.com>
* 				zorglub42 <contact@zorglub42.fr>
*
* Description :
* 	Upgrade database form version 2.3 to version 2.4
*--------------------------------------------------------
* History     :
*
* 1.0.0 - 2015-08-18 : Release of the file
**/
ALTER TABLE `users` 
ADD COLUMN `extra` TEXT NULL AFTER `entity`;
