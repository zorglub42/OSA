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

-- Version: 2.7


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `authtoken`
--

DROP TABLE IF EXISTS `authtoken`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `authtoken` (
`token` varchar(255) NOT NULL,
`validUntil` datetime DEFAULT NULL,
`userName` varchar(45) DEFAULT NULL,
PRIMARY KEY (`token`),
KEY `fk_authtoken_1` (`userName`),
KEY `idx_date` (`validUntil`)
) CHARSET=LATIN1  ENGINE=Memory;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `counters`
--

DROP TABLE IF EXISTS `counters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `counters` (
`counterName` varchar(255) NOT NULL,
`value` int(10) unsigned NOT NULL,
PRIMARY KEY (`counterName`) USING BTREE
) CHARSET=LATIN1  ENGINE=InnoDB;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `groups` (
`groupName` varchar(45) NOT NULL,
`description` varchar(2000) NOT NULL,
PRIMARY KEY (`groupName`) USING BTREE
) CHARSET=LATIN1  ENGINE=InnoDB;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hits`
--

DROP TABLE IF EXISTS `hits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hits` (
`id` bigint(20) NOT NULL AUTO_INCREMENT,
`frontEndEndPoint` varchar(200) NOT NULL,
`userName` varchar(45) NOT NULL,
`timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
`message` varchar(1024) NOT NULL,
`status` int(11) DEFAULT NULL,
`serviceName` varchar(45) DEFAULT NULL,
PRIMARY KEY (`id`),
KEY `idx_serviceName` (`serviceName`),
KEY `idx_userName` (`userName`)
) CHARSET=LATIN1  ENGINE=InnoDB;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `nodes`
--

DROP TABLE IF EXISTS `nodes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
PRIMARY KEY (`nodeName`),
UNIQUE KEY `UNQ_BIND` (`localIP`,`port`,`serverFQDN`)
) CHARSET=LATIN1  ENGINE=InnoDB;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `services`
--

DROP TABLE IF EXISTS `services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `services` (
`serviceName` varchar(45) NOT NULL DEFAULT 'none',
`reqSec` int(10) unsigned NOT NULL DEFAULT '0',
`reqDay` int(10) unsigned NOT NULL DEFAULT '0',
`reqMonth` int(10) unsigned NOT NULL DEFAULT '0',
`frontEndEndPoint` varchar(200) NOT NULL,
`isGlobalQuotasEnabled` tinyint(1) NOT NULL DEFAULT '1',
`isUserQuotasEnabled` tinyint(1) NOT NULL DEFAULT '1',
`groupName` varchar(45) DEFAULT NULL,
`backEndEndPoint` varchar(200) NOT NULL,
`backEndUsername` varchar(45) DEFAULT NULL,
`backEndPassword` varchar(255) DEFAULT NULL,
`isIdentityForwardingEnabled` tinyint(1) unsigned NOT NULL DEFAULT '0',
`isPublished` tinyint(1) unsigned NOT NULL DEFAULT '1',
`isUserAuthenticationEnabled` tinyint(1) DEFAULT '1',
`isHitLoggingEnabled` tinyint(1) DEFAULT '0',
`additionalConfiguration` text,
`onAllNodes` tinyint(1) NOT NULL DEFAULT '1',
`loginFormUri` VARCHAR(255) NULL DEFAULT '',
`isAnonymousAllowed` TINYINT(1) NULL DEFAULT 0 ,
PRIMARY KEY (`serviceName`) USING BTREE,
KEY `FK_services_groups` (`groupName`),
CONSTRAINT `FK_services_groups` FOREIGN KEY (`groupName`) REFERENCES `groups` (`groupName`)
) CHARSET=LATIN1  ENGINE=InnoDB;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `servicesnodes`
--

DROP TABLE IF EXISTS `servicesnodes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `servicesnodes` (
`serviceName` varchar(45) NOT NULL,
`nodeName` varchar(45) NOT NULL,
PRIMARY KEY (`serviceName`,`nodeName`),
KEY `fk_servicesnodes_1` (`serviceName`),
KEY `fk_servicesnodes_2` (`nodeName`),
CONSTRAINT `fk_servicesnodes_1` FOREIGN KEY (`serviceName`) REFERENCES `services` (`serviceName`) ON DELETE CASCADE ON UPDATE NO ACTION,
CONSTRAINT `fk_servicesnodes_2` FOREIGN KEY (`nodeName`) REFERENCES `nodes` (`nodeName`) ON DELETE CASCADE ON UPDATE NO ACTION
) CHARSET=LATIN1  ENGINE=InnoDB;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
`userName` varchar(45) NOT NULL,
`password` varchar(2048) NOT NULL,
`endDate` datetime NOT NULL,
`emailAddress` varchar(200) NOT NULL,
`md5Password` varchar(2048) NOT NULL,
`firstName` varchar(45) DEFAULT NULL,
`lastName` varchar(45) DEFAULT NULL,
`entity` varchar(45) DEFAULT NULL,
`extra` TEXT NULL,
`lastTokenLogin` datetime NULL,
PRIMARY KEY (`userName`) USING BTREE
) CHARSET=LATIN1  ENGINE=InnoDB;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `usersgroups`
--

DROP TABLE IF EXISTS `usersgroups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usersgroups` (
`userName` varchar(45) NOT NULL,
`groupName` varchar(45) NOT NULL,
PRIMARY KEY (`userName`,`groupName`) USING BTREE,
KEY `FK_user_groups_group` (`groupName`) USING BTREE,
CONSTRAINT `FK_user_groups_group` FOREIGN KEY (`groupName`) REFERENCES `groups` (`groupName`),
CONSTRAINT `FK_user_groups_user` FOREIGN KEY (`userName`) REFERENCES `users` (`userName`) ON DELETE CASCADE ON UPDATE CASCADE
) CHARSET=LATIN1  ENGINE=InnoDB;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `usersquotas`
--

DROP TABLE IF EXISTS `usersquotas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usersquotas` (
`serviceName` varchar(45) NOT NULL,
`userName` varchar(45) NOT NULL,
`reqSec` int(10) unsigned NOT NULL,
`reqDay` int(10) unsigned NOT NULL,
`reqMonth` int(10) unsigned NOT NULL,
PRIMARY KEY (`serviceName`,`userName`) USING BTREE,
KEY `FK_user_quotas_user` (`userName`) USING BTREE,
CONSTRAINT `FK_user_quotas_resource` FOREIGN KEY (`serviceName`) REFERENCES `services` (`serviceName`) ON DELETE CASCADE,
CONSTRAINT `FK_user_quotas_user` FOREIGN KEY (`userName`) REFERENCES `users` (`userName`) ON DELETE CASCADE
) CHARSET=LATIN1  ENGINE=InnoDB;
/*!40101 SET character_set_client = @saved_cs_client */;



DROP TABLE IF EXISTS `headersmapping`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE headersmapping (
id INT NOT NULL AUTO_INCREMENT,
serviceName VARCHAR(45) NOT NULL,
columnName VARCHAR(45) NOT NULL,
headerName VARCHAR(45) NOT NULL,
PRIMARY KEY (id),
INDEX fk_headersmapping_1_idx (serviceName ASC),
CONSTRAINT fk_headersmapping_1
FOREIGN KEY (serviceName)
REFERENCES services (serviceName)
ON DELETE CASCADE
ON UPDATE NO ACTION) CHARSET=LATIN1  ENGINE=InnoDB;

/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;





/*!40000 ALTER TABLE `groups` DISABLE KEYS */;
INSERT INTO `groups` (`groupName`,`description`) VALUES
('Admin','Appliance Manager Admin group');
INSERT INTO `groups` (`groupName`,`description`) VALUES
('valid-user','*** Any valid user ***');
/*!40000 ALTER TABLE `groups` ENABLE KEYS */;



/*!40000 ALTER TABLE `services` DISABLE KEYS */;
INSERT INTO `services` (`serviceName`,`reqSec`,`reqDay`,`reqMonth`,`frontEndEndPoint`,`isGlobalQuotasEnabled`,`isUserQuotasEnabled`,`groupName`,`backEndEndPoint`,`backEndUsername`,`backEndPassword`,`isIdentityForwardingEnabled`,`isPublished`,`isHitLoggingEnabled`,`isUserAuthenticationEnabled`) VALUES
('ApplianceManagerAdmin',0,0,0,'/ApplianceManagerAdmin',0,0,'Admin','http://127.0.0.1:PRIVATE_VHOST_PORT/ApplianceManager','','002',1,0,0,1);
INSERT INTO `services` (`serviceName`,`reqSec`,`reqDay`,`reqMonth`,`frontEndEndPoint`,`isGlobalQuotasEnabled`,`isUserQuotasEnabled`,`groupName`,`backEndEndPoint`,`backEndUsername`,`backEndPassword`,`isIdentityForwardingEnabled`,`isPublished`,`isHitLoggingEnabled`,`isUserAuthenticationEnabled`) VALUES
('ApplianceManagerAdminAuthToken',0,0,0,'/ApplianceManagerAdmin/auth/token',0,0,'valid-user','http://127.0.0.1:PRIVATE_VHOST_PORT/ApplianceManager/auth/token','','002',1,1,0,1);
INSERT INTO `services` (`serviceName`,`reqSec`,`reqDay`,`reqMonth`,`frontEndEndPoint`,`isGlobalQuotasEnabled`,`isUserQuotasEnabled`,`groupName`,`backEndEndPoint`,`backEndUsername`,`backEndPassword`,`isIdentityForwardingEnabled`,`isPublished`,`isHitLoggingEnabled`,`isUserAuthenticationEnabled`) VALUES
('ApplianceManagerAdminCurrentUser',0,0,0,'/ApplianceManagerAdmin/users/me',0,0,'valid-user','http://127.0.0.1:PRIVATE_VHOST_PORT/ApplianceManager/users/me','','002',1,1,0,1);
INSERT INTO `services` (`serviceName`,`reqSec`,`reqDay`,`reqMonth`,`frontEndEndPoint`,`isGlobalQuotasEnabled`,`isUserQuotasEnabled`,`groupName`,`backEndEndPoint`,`backEndUsername`,`backEndPassword`,`isIdentityForwardingEnabled`,`isPublished`,`isHitLoggingEnabled`,`isUserAuthenticationEnabled`) VALUES
('ApplianceManagerAdminAuth',0,0,0,'/ApplianceManagerAdmin/auth/',0,0,NULL,'http://127.0.0.1:PRIVATE_VHOST_PORT/ApplianceManager/auth/','','002',1,1,0,0);
/*!40000 ALTER TABLE `services` ENABLE KEYS */;


/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`userName`,`password`,`endDate`,`emailAddress`,`md5Password`,`firstName`,`lastName`,`entity`) VALUES
('Admin','004034','2019-12-23 00:00:00','admin@server','0cc175b9c0f1b6a831c399e269772661','Admnistrator','','');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;


/*!40000 ALTER TABLE `usersgroups` DISABLE KEYS */;
INSERT INTO `usersgroups` (`userName`,`groupName`) VALUES
('Admin','Admin');
/*!40000 ALTER TABLE `usersgroups` ENABLE KEYS */;

