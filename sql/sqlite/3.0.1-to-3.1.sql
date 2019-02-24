/**--------------------------------------------------------
* Module Name : RunTimeAppliance
*
* Software Name : OpenSourceAppliance
*
* Copyright (c) 2012 – 2019 France Télécom
* This software is distributed under the Apache 2 license
* <http://www.apache.org/licenses/LICENSE-2.0.html>
*
*--------------------------------------------------------
* File Name   : ApplianceManager/RunTimeAppliance/sql/3.0.1-to-3.1.sql
*
* Created     : 2019-02-24
* Authors     : zorglub42 <contact@zorglub42.fr>
*
* Description :
* 	Upgrade database form version 3.0.1 to version 3.1
**/

alter table services add additionalBackendConnectionConfiguration text null;