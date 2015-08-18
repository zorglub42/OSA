<?php
/*--------------------------------------------------------
 * Module Name : ApplianceManager
 * Version : 1.0.0
 *
 * Software Name : OpenServicesAccess
 * Version : 1.0
 *
 * Copyright (c) 2011 – 2014 Orange
 * This software is distributed under the Apache 2 license
 * <http://www.apache.org/licenses/LICENSE-2.0.html>
 *
 *--------------------------------------------------------
 * File Name   : ApplianceManager/ApplianceManager.php/include/menu.php
 *
 * Created     : 2012-02
 * Authors     : Benoit HERARD <benoit.herard(at)orange.com>
 *
 * Description :
 *      Generate application GUI menu
 *--------------------------------------------------------
 * History     :
 * 1.0.0 - 2012-10-01 : Release of the file
*/
?>

<div id="groupsMenu" class="submenu">
	<h2>Groups</h2>
	<ul>
		<li>
			<a href="#" id="listGroup"  title="List, modify and delete existing groups">List</a>
		</li>
		<li>
			<a href="#" id="addGroup"  title="Add a new group to the system">Add</a>
		</li>
	</ul>
</div>
<div id="servicesMenu" class="submenu">
	<h2>Services</h2>
	<ul>
		<li>
			<a href="#" id="listService"  title="List, modify and delete existing services">List</a>
		</li>
		<li>
			<a href="#" id="addService"  title="Add a new service to the system">Add</a>
		</li>
	</ul>
</div>
<div id="usersMenu" class="submenu">
	<h2>Users</h2>
	<ul>
		<li>
			<a href="#" id="listUser"  title="List, modify and delete existing users">List</a>
		</li>
		<li>
			<a href="#" id="addUser"  title="Add a new user">Add</a>
		</li>
	</ul>
</div>
<div  id="countersMenu" class="submenu">
	<h2>Counters</h2>
	<ul>
		<li>
			<a href="#" id="searchCounter"  title="Search for current counters values for quotas">Search</a>
		</li>
		<li>
			<a href="#" id="searchExcedeedCounters"  title="Search for current counters  where value exceed quotas">Excedeed</a>
		</li>
	</ul>
</div>
<div  id="nodesMenu" class="submenu">
	<h2>Nodes</h2>
	<ul>
		<li>
			<a href="#" id="listNode"  title="List, modify and delete existing access nodes">List</a>
		</li>
		<li>
			<a href="#" id="addNode"  title="Add a new node to the system">Add</a>
		</li>
	</ul>
</div>
<div  id="logsMenu" class="submenu">
	<h2>Logs</h2>
	<ul>
		<li>
			<a href="#" id="searchLogs" title="Search hits accross realtime traffic logs">Search</a>
		</li>
	</ul>
</div>

