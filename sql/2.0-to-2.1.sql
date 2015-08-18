/**--------------------------------------------------------
* Module Name : RunTimeAppliance
* Version : 2.0.0
*
* Software Name : OpenSourceAppliance
* Version : 2.1
*
* Copyright (c) 2012 – 2013 France Télécom
* This software is distributed under the Apache 2 license
* <http://www.apache.org/licenses/LICENSE-2.0.html>
*
*--------------------------------------------------------
* File Name   : ApplianceManager/RunTimeAppliance/sql/2.0-to-2.1.sql
*
* Created     : 2014-04-16
* Authors     : Benoit HERARD <benoit.herard(at)orange.com>
*
* Description :
* 	Upgrade database form version 2.0 to version 2.1
*--------------------------------------------------------
* History     :
*
* 1.0.0 - 2013-12-03 : Release of the file
**/
ALTER TABLE `services` ADD COLUMN `isAnonymousAllowed` TINYINT(1) NULL DEFAULT 0 ;
INSERT INTO `services` (`serviceName`,`reqSec`,`reqDay`,`reqMonth`,`frontEndEndPoint`,`isGlobalQuotasEnabled`,`isUserQuotasEnabled`,`groupName`,`backEndEndPoint`,`backEndUsername`,`backEndPassword`,`isIdentityForwardingEnabled`,`isPublished`,`isHitLoggingEnabled`,`isUserAuthenticationEnabled`) VALUES
('ApplianceManagerAdminCurrentUser',0,0,0,'/ApplianceManagerAdmin/users/me',0,0,'valid-user','http://127.0.0.1:PRIVATE_VHOST_PORT/ApplianceManager/users/me','','002',1,1,0,1);
