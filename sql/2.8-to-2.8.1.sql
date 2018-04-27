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
* File Name   : ApplianceManager/RunTimeAppliance/sql/2.7-to-2.8.sql
*
* Created     : 2017-08-09
* Authors     : zorglub42 <contact@zorglub42.fr>
*
* Description :
* 	Upgrade database form version 2.7 to version 2.8
*--------------------------------------------------------
* History     :
*
* 1.0.0 - 2018-04-27 : Release of the file
**/
INSERT INTO `services` (`serviceName`,`reqSec`,`reqDay`,`reqMonth`,`frontEndEndPoint`,`isGlobalQuotasEnabled`,`isUserQuotasEnabled`,`groupName`,`backEndEndPoint`,`backEndUsername`,`backEndPassword`,`isIdentityForwardingEnabled`,`isPublished`,`isHitLoggingEnabled`,`isUserAuthenticationEnabled`) 
SELECT 'ApplianceManagerAdminAuthTokenAnyUser',reqSec,reqDay,reqMonth,frontEndEndPoint,isGlobalQuotasEnabled,isUserQuotasEnabled,'Admin',backEndEndPoint,backEndUsername,backEndPassword,isIdentityForwardingEnabled,isPublished,isHitLoggingEnabled,isUserAuthenticationEnabled
FROM   services
WHERE serviceName='ApplianceManagerAdminAuthToken';
