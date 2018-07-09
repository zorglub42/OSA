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
DROP TABLE IF EXISTS `additionnaluserproperties`;
CREATE TABLE `additionnaluserproperties` (
  `userName` VARCHAR(45) NOT NULL,
  `propertyName` VARCHAR(45) NOT NULL,
  `value` TEXT NULL,
  PRIMARY KEY (`userName`, `propertyName`),
  CONSTRAINT `fk_additionnaluserproperties_user`
    FOREIGN KEY (`userName`)
    REFERENCES `users` (`userName`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION);

INSERT INTO additionnaluserproperties(userName, propertyName, value) SELECT userName, "extra", extra FROM users WHERE extra != "";

ALTER TABLE `users` 
CHANGE COLUMN `endDate` `endDate` DATETIME NULL ,
CHANGE COLUMN `emailAddress` `emailAddress` VARCHAR(200) NULL,
DROP column `extra` ;

ALTER TABLE `headersmapping` 
ADD COLUMN `extendedAttribute` INT NULL DEFAULT 0 AFTER `headerName`;

UPDATE headersmapping SET extendedAttribute=1 WHERE columnName='extra';

ALTER TABLE `authtoken`
ADD COLUMN `burned` INT(0) DEFAULT 0;