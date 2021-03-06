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
* File Name   : ApplianceManager/RunTimeAppliance/sql/2.5-to-2.6.sql
*
* Created     : 2014-09-23
* Authors     : Benoit HERARD <benoit.herard(at)orange.com>
* 				zorglub42 <contact@zorglub42.fr>
*
* Description :
* 	Upgrade database form version 2.5 to version 2.6
*--------------------------------------------------------
* History     :
*
* 1.0.0 - 2017-02-07 : Release of the file
**/
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

