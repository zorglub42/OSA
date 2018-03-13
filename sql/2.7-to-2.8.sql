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
* 1.0.0 - 2018-03-13 : Release of the file
**/
CREATE VIEW  excedeedcounters AS
	SELECT 	c.* , s.serviceName, NULL userName, s.reqSec, s.reqDay, s.reqMonth	
	FROM 	counters c,	services s	
	WHERE 	(counterName like concat('R=',s.serviceName,'$$$S=%') and c.value>=s.reqSec)
			OR
			(counterName like concat('R=',s.serviceName,'$$$D=%') and c.value>=s.reqDay)	
			OR 
			(counterName like concat('R=',s.serviceName,'$$$M=%') and c.value>=s.reqMonth)	
	UNION	
	SELECT	c2.* , uq.serviceName, uq.userName, uq.reqSec, uq.reqDay, uq.reqMonth	
	FROM 	counters c2,	usersquotas uq	
	WHERE	(counterName like concat('R=',uq.serviceName,'$$$U=', uq.userName, '$$$S=%') and c2.value>=uq.reqSec) 
			OR 
			(counterName like concat('R=',uq.serviceName, '$$$U=', uq.userName, '$$$D=%') and c2.value>=uq.reqDay)	
			OR 
			(counterName like concat('R=',uq.serviceName,'$$$U=', uq.userName, '$$$M=%') and c2.value>=uq.reqMonth);
