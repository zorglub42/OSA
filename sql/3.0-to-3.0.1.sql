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
* File Name   : ApplianceManager/RunTimeAppliance/sql/3.0-to-3.0.1.sql
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

alter table hits modify frontEndEndPoint text not null;
