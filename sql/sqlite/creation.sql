/**--------------------------------------------------------
* Module Name : RunTimeAppliance
* Version : 2.0.0
*
* Software Name : OpenSourceAppliance
* Version : 2.3
*
* Copyright (c) 2012 – 2013 France Télécom
* This software is distributed under the Apache 2 license
* <http://www.apache.org/licenses/LICENSE-2.0.html>
*
*--------------------------------------------------------
* File Name   : ApplianceManager/RunTimeAppliance/sql/creation.sql
*
* Created     : 2012-10-01
* Authors     : Benoit HERARD <benoit.herard(at)orange.com>
*
* Description :
* 	Create dabase object  and bootstrat it with:
* 		- admin user (pass=a)
* 		- ApplianceManager REST servicees granted to admin
*
*--------------------------------------------------------
* History     :
*
* 1.0.0 - 2012-10-01 : Release of the file
**/

-- Version: 3.0.1



--
-- Table structure for table `authtoken`
--

DROP TABLE IF EXISTS `authtoken`;
CREATE TABLE `authtoken` (
`token` varchar(255) NOT NULL,
`initialToken` varchar(255) NOT NULL,
`validUntil` datetime DEFAULT NULL,
`userName` varchar(45) DEFAULT NULL,
`burned` int(1) default 0,
PRIMARY KEY (`token`)
) ;
CREATE INDEX fk_authtoken_1 on authtoken(userName);
CREATE INDEX idx_date on authtoken(validUntil);
--
-- Table structure for table `counters`
--

	DROP TABLE IF EXISTS `counters`;
	CREATE TABLE `counters` (
	`counterName` varchar(255) NOT NULL,
	`value` int(10)  NOT NULL,
	PRIMARY KEY (`counterName`) 
	) ;

--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `groups`;
CREATE TABLE `groups` (
`groupName` varchar(45) NOT NULL,
`description` varchar(2000) NOT NULL,
PRIMARY KEY (`groupName`) 
) ;

--
-- Table structure for table `hits`
--

DROP TABLE IF EXISTS `hits`;
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
--
-- Table structure for table `nodes`
--

DROP TABLE IF EXISTS `nodes`;
CREATE TABLE `nodes` (
`nodeName` varchar(45) NOT NULL,
`nodeDescription` varchar(2000) DEFAULT NULL,
`isHTTPS` tinyint(1) NOT NULL,
`isBasicAuthEnabled` tinyint(1) NOT NULL,
`isCookieAuthEnabled` tinyint(1) NOT NULL,
`serverFQDN` varchar(255) NOT NULL,
`localIP` varchar(45) NOT NULL,
`port` int(11) NOT NULL,
`privateKey` text,
`cert` text,
`ca` TEXT NULL DEFAULT NULL,
`caChain` TEXT NULL DEFAULT NULL,
`additionalConfiguration` text,
`isPublished` tinyint(1) NOT NULL default 1,
PRIMARY KEY (`nodeName`)
) ;
CREATE UNIQUE INDEX unq_bind On nodes(localIP, port, serverFQDN);
--
-- Table structure for table `services`
--

DROP TABLE IF EXISTS `services`;
CREATE TABLE `services` (
`serviceName` varchar(45) NOT NULL DEFAULT 'none',
`reqSec` int(10)  NOT NULL DEFAULT '0',
`reqDay` int(10)  NOT NULL DEFAULT '0',
`reqMonth` int(10) NOT NULL DEFAULT '0',
`frontEndEndPoint` varchar(200) NOT NULL,
`isGlobalQuotasEnabled` tinyint(1) NOT NULL DEFAULT '1',
`isUserQuotasEnabled` tinyint(1) NOT NULL DEFAULT '1',
`groupName` varchar(45) DEFAULT NULL,
`backEndEndPoint` varchar(200) NOT NULL,
`backEndUsername` varchar(45) DEFAULT NULL,
`backEndPassword` varchar(255) DEFAULT NULL,
`isIdentityForwardingEnabled` tinyint(1) NOT NULL DEFAULT '0',
`isPublished` tinyint(1) NOT NULL DEFAULT '1',
`isUserAuthenticationEnabled` tinyint(1) DEFAULT '1',
`isHitLoggingEnabled` tinyint(1) DEFAULT '0',
`additionalConfiguration` text,
`onAllNodes` tinyint(1) NOT NULL DEFAULT '1',
`loginFormUri` VARCHAR(255) NULL DEFAULT '',
`isAnonymousAllowed` TINYINT(1) NULL DEFAULT 0 ,
PRIMARY KEY (`serviceName`), 
CONSTRAINT `FK_services_groups` FOREIGN KEY (`groupName`) REFERENCES `groups` (`groupName`)
) ;
CREATE INDEX fk_groupName on services(groupName);
--
-- Table structure for table `servicesnodes`
--

DROP TABLE IF EXISTS `servicesnodes`;
CREATE TABLE `servicesnodes` (
`serviceName` varchar(45) NOT NULL,
`nodeName` varchar(45) NOT NULL,
PRIMARY KEY (`serviceName`,`nodeName`),
CONSTRAINT `fk_servicesnodes_1` FOREIGN KEY (`serviceName`) REFERENCES `services` (`serviceName`) ON DELETE CASCADE ON UPDATE NO ACTION,
CONSTRAINT `fk_servicesnodes_2` FOREIGN KEY (`nodeName`) REFERENCES `nodes` (`nodeName`) ON DELETE CASCADE ON UPDATE NO ACTION
) ;
create index fk_servicesnodes_1 on servicesnodes(serviceName);
CREATE INDEX fk_servicesnodes_2 on servicesnodes(nodeName);
--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
`userName` varchar(45) NOT NULL,
`password` varchar(2048) NOT NULL,
`endDate` datetime NULL,
`emailAddress` varchar(200) NULL,
`md5Password` varchar(2048) NOT NULL,
`firstName` varchar(45) DEFAULT NULL,
`lastName` varchar(45) DEFAULT NULL,
`entity` varchar(45) DEFAULT NULL,
`extra` TEXT NULL,
`lastTokenLogin` datetime NULL,
PRIMARY KEY (`userName`) 
) ;

--
-- Table structure for table `usersgroups`
--

DROP TABLE IF EXISTS `usersgroups`;
CREATE TABLE `usersgroups` (
`userName` varchar(45) NOT NULL,
`groupName` varchar(45) NOT NULL,
PRIMARY KEY (`userName`,`groupName`) ,
CONSTRAINT `FK_user_groups_group` FOREIGN KEY (`groupName`) REFERENCES `groups` (`groupName`),
CONSTRAINT `FK_user_groups_user` FOREIGN KEY (`userName`) REFERENCES `users` (`userName`) ON DELETE CASCADE ON UPDATE CASCADE
) ;
CREATE INDEX FK_user_groups_group on usersgroups(groupName);
--
-- Table structure for table `usersquotas`
--

DROP TABLE IF EXISTS `usersquotas`;
CREATE TABLE `usersquotas` (
`serviceName` varchar(45) NOT NULL,
`userName` varchar(45) NOT NULL,
`reqSec` int(10) NOT NULL,
`reqDay` int(10) NOT NULL,
`reqMonth` int(10) NOT NULL,
PRIMARY KEY (`serviceName`,`userName`) ,
CONSTRAINT `FK_user_quotas_resource` FOREIGN KEY (`serviceName`) REFERENCES `services` (`serviceName`) ON DELETE CASCADE,
CONSTRAINT `FK_user_quotas_user` FOREIGN KEY (`userName`) REFERENCES `users` (`userName`) ON DELETE CASCADE
) ;
CREATE INDEX fk_user_quotas_user on usersquotas(userName);


DROP TABLE IF EXISTS `headersmapping`;
CREATE TABLE headersmapping (
id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
serviceName VARCHAR(45) NOT NULL,
columnName VARCHAR(45) NOT NULL,
headerName VARCHAR(45) NOT NULL,
extendedAttribute INT(1) NOT NULL DEFAULT 0,
CONSTRAINT fk_headersmapping_1
FOREIGN KEY (serviceName)
REFERENCES services (serviceName)
ON DELETE CASCADE
ON UPDATE NO ACTION) ;
CREATE INDEX fk_headersmapping_1_idx on headersmapping (serviceName ASC);

DROP TABLE IF EXISTS `additionnaluserproperties` ;
CREATE TABLE `additionnaluserproperties` (
  `userName` VARCHAR(45) NOT NULL,
  `propertyName` VARCHAR(45) NOT NULL,
  `value` TEXT NULL,
  PRIMARY KEY (`userName`, `propertyName`),
  CONSTRAINT `fk_additionnaluserproperties_user`
    FOREIGN KEY (`userName`)
    REFERENCES `users` (`userName`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION
);

CREATE VIEW excedeedcounters as
	SELECT c.* , s.serviceName, NULL userName, s.reqSec, s.reqDay, s.reqMonth
	FROM counters c,
		 services s	
	WHERE   (counterName like 'R='||s.serviceName||'$$$S=%' and c.value>=s.reqSec)
				OR
		    (counterName like 'R='||s.serviceName||'$$$D=%' and c.value>=s.reqDay)
				OR
		    (counterName like 'R='||s.serviceName||'$$$M=%' and c.value>=s.reqMonth)
	UNION
	SELECT c2.* , uq.serviceName, uq.userName, uq.reqSec, uq.reqDay, uq.reqMonth	
	FROM 	counters c2,		 
			usersquotas uq	
	WHERE   (counterName like 'R='||uq.serviceName||'$$$U='||uq.userName||'$$$S=%' and c2.value>=uq.reqSec)
			 	OR      
			(counterName like 'R='||uq.serviceName||'$$$U='||uq.userName||'$$$D=%' and c2.value>=uq.reqDay)
				OR      
			(counterName like 'R='||uq.serviceName||'$$$U='||uq.userName||'$$$M=%' and c2.value>=uq.reqMonth);


INSERT INTO `groups` (`groupName`,`description`) VALUES
('Admin','Appliance Manager Admin group');
INSERT INTO `groups` (`groupName`,`description`) VALUES
('valid-user','*** Any valid user ***');



INSERT INTO `services` (`serviceName`,`reqSec`,`reqDay`,`reqMonth`,`frontEndEndPoint`,`isGlobalQuotasEnabled`,`isUserQuotasEnabled`,`groupName`,`backEndEndPoint`,`backEndUsername`,`backEndPassword`,`isIdentityForwardingEnabled`,`isPublished`,`isHitLoggingEnabled`,`isUserAuthenticationEnabled`) VALUES
('ApplianceManagerAdmin',0,0,0,'/ApplianceManagerAdmin',0,0,'Admin','http://127.0.0.1:PRIVATE_VHOST_PORT/ApplianceManager','','002',1,0,0,1);
INSERT INTO `services` (`serviceName`,`reqSec`,`reqDay`,`reqMonth`,`frontEndEndPoint`,`isGlobalQuotasEnabled`,`isUserQuotasEnabled`,`groupName`,`backEndEndPoint`,`backEndUsername`,`backEndPassword`,`isIdentityForwardingEnabled`,`isPublished`,`isHitLoggingEnabled`,`isUserAuthenticationEnabled`) VALUES
('ApplianceManagerAdminAuthToken',0,0,0,'/ApplianceManagerAdmin/auth/token/me',0,0,'valid-user','http://127.0.0.1:PRIVATE_VHOST_PORT/ApplianceManager/auth/token/me','','002',1,1,0,1);
INSERT INTO `services` (`serviceName`,`reqSec`,`reqDay`,`reqMonth`,`frontEndEndPoint`,`isGlobalQuotasEnabled`,`isUserQuotasEnabled`,`groupName`,`backEndEndPoint`,`backEndUsername`,`backEndPassword`,`isIdentityForwardingEnabled`,`isPublished`,`isHitLoggingEnabled`,`isUserAuthenticationEnabled`) VALUES
('ApplianceManagerAdminAuthTokenAnyUser',0,0,0,'/ApplianceManagerAdmin/auth/token',0,0,'Admin','http://127.0.0.1:PRIVATE_VHOST_PORT/ApplianceManager/auth/token','','002',1,1,0,1);
INSERT INTO `services` (`serviceName`,`reqSec`,`reqDay`,`reqMonth`,`frontEndEndPoint`,`isGlobalQuotasEnabled`,`isUserQuotasEnabled`,`groupName`,`backEndEndPoint`,`backEndUsername`,`backEndPassword`,`isIdentityForwardingEnabled`,`isPublished`,`isHitLoggingEnabled`,`isUserAuthenticationEnabled`) VALUES
('ApplianceManagerAdminCurrentUser',0,0,0,'/ApplianceManagerAdmin/users/me',0,0,'valid-user','http://127.0.0.1:PRIVATE_VHOST_PORT/ApplianceManager/users/me','','002',1,1,0,1);
INSERT INTO `services` (`serviceName`,`reqSec`,`reqDay`,`reqMonth`,`frontEndEndPoint`,`isGlobalQuotasEnabled`,`isUserQuotasEnabled`,`groupName`,`backEndEndPoint`,`backEndUsername`,`backEndPassword`,`isIdentityForwardingEnabled`,`isPublished`,`isHitLoggingEnabled`,`isUserAuthenticationEnabled`) VALUES
('ApplianceManagerAdminAuth',0,0,0,'/ApplianceManagerAdmin/auth/',0,0,NULL,'http://127.0.0.1:PRIVATE_VHOST_PORT/ApplianceManager/auth/','','002',1,1,0,0);


INSERT INTO `users` (`userName`,`password`,`endDate`,`emailAddress`,`md5Password`,`firstName`,`lastName`,`entity`) VALUES
('Admin','004034','2019-12-23 00:00:00','admin@server','0cc175b9c0f1b6a831c399e269772661','Admnistrator','','');


INSERT INTO `usersgroups` (`userName`,`groupName`) VALUES
('Admin','Admin');

