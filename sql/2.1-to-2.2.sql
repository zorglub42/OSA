/**--------------------------------------------------------
* Module Name : RunTimeAppliance
* Version : 2.0.0
*
* Software Name : OpenSourceAppliance
* Version : 2.2
*
* Copyright (c) 2012 – 2013 France Télécom
* This software is distributed under the Apache 2 license
* <http://www.apache.org/licenses/LICENSE-2.0.html>
*
*--------------------------------------------------------
* File Name   : ApplianceManager/RunTimeAppliance/sql/2.0-to-2.1.sql
*
* Created     : 2014-09-23
* Authors     : Benoit HERARD <benoit.herard(at)orange.com>
*
* Description :
* 	Upgrade database form version 2.1 to version 2.2
*--------------------------------------------------------
* History     :
*
* 1.0.0 - 2014-09-23 : Release of the file
**/
ALTER TABLE `nodes` 
ADD COLUMN `ca` TEXT NULL DEFAULT NULL AFTER `cert`,
ADD COLUMN `caChain` TEXT NULL DEFAULT NULL AFTER `ca`;
