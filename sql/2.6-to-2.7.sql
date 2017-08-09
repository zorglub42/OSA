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
* File Name   : ApplianceManager/RunTimeAppliance/sql/2.4-to-2.5.sql
*
* Created     : 2017-08-09
* Authors     : zorglub42 <contact@zorglub42.fr>
*
* Description :
* 	Upgrade database form version 2.6 to version 2.7
*--------------------------------------------------------
* History     :
*
* 1.0.0 - 2017-08-09 : Release of the file
**/
alter table users add (lastTokenLogin datetime);
update nodes set isBasicAuthEnabled=1, isCookieAuthEnabled=1;
