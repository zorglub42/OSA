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
* 1.0.0 - 2018-06-25 : Release of the file
**/
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

INSERT INTO additionnaluserproperties(userName, propertyName, value) SELECT userName, "extra", extra FROM users WHERE extra != "";

PRAGMA foreign_keys = OFF;
CREATE TABLE `users2` (
`userName` varchar(45) NOT NULL,
`password` varchar(2048) NOT NULL,
`endDate` datetime NULL,
`emailAddress` varchar(200) NULL,
`md5Password` varchar(2048) NOT NULL,
`firstName` varchar(45) DEFAULT NULL,
`lastName` varchar(45) DEFAULT NULL,
`entity` varchar(45) DEFAULT NULL,
`lastTokenLogin` datetime NULL,
PRIMARY KEY (`userName`) 
) ;
insert into users2 SELECT `userName`, `password`, `endDate`, `emailAddress`, `md5Password`, `firstName`, `lastName`, `entity`, `lastTokenLogin` FROM users;
DROP TABLE users;
CREATE TABLE `users` as select * from users2;
DROP TABLE users2;


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


PRAGMA foreign_keys = ON;
 

ALTER TABLE `headersmapping` 
ADD COLUMN `extendedAttribute` INT NULL DEFAULT 0;

UPDATE headersmapping SET extendedAttribute=1 WHERE columnName='extra';