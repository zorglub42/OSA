<?php
$strings["date.format"]="MM/dd/yyyy";
$strings["date.format.parseexact"]="mm/dd/yyyy";
$strings["locale"]="fr";
/* Global app labels */
$strings["app.title"]="Open Services Access";
$strings["app.version"]="Open Services Access V";

/* Navigation bar */
$strings["nav.toggle"]="Toggle";
$strings["nav.groups"]="Groups";
$strings["nav.services"]="Services";
$strings["nav.users"]="Users";
$strings["nav.counters"]="Counters";
$strings["nav.counters.search"]="Search...";
$strings["nav.counters.exceeded"]="Exceeded quotas";
$strings["nav.nodes"]="Nodes";
$strings["nav.logs"]="Logs";

/* Global list labels */
$strings["list.actions"]="Actions";

/*Global buttons label */
$strings["button.ok"]="OK";
$strings["button.cancel"]="Cancel";
$strings["button.back"]="Back";
$strings["button.edit"]="Edit";
$strings["button.delete"]="Delete";
$strings["button.add"]="Add";
$strings["button.members"]="Members";
$strings["button.groups"]="Groups";
$strings["button.quotas"]="Quotas";
$strings["button.search"]="Search";
$strings["button.searchRefresh"]="Refresh";
$strings["button.filter"]="filter";
$strings["button.reset"]="reset";
$strings["button.resetSSL"]="Delete existing certificate and key";
$strings["button.resetCASSL"]="Delete existing CA and certification chain data";

$strings["button.filter.tooltip"]="Apply search filter";
$strings["button.reset.tooltip"]="Reset search criterias";
$strings["button.search.tooltip"]="Start request";
$strings["button.searchRefresh.tooltip"]="Restart previous request";


/* Groups */
/* List */
$strings["group.list.found"]="groups found";
$strings["group.list.name"]="Name";
$strings["group.list.description"]="Description";
/* Details */
$strings["group.name.placeholder"]="group name";
$strings["group.description.placeholder"]="group description";
$strings["group.delete.confirm"]="Do you really want to remove group";

$strings["group.edit.tooltip"]="Edit this group";
$strings["group.delete.tooltip"]="Delete this group";
$strings["group.add.tooltip"]="Add a group to the system";
$strings["group.name.tooltip"]="Enter here the group name\nATTENTION: do not use special chars\nGroup name is used to manage authentication to services.\Group name is an identifier and can not be changed.";
$strings["group.description.tooltip"]="Enter here a description for this group\nThis data is for your information only";
$strings["group.properties.new"]="New group properties";
$strings["group.properties"]="{group.groupName} properties";

$strings["group.members"]="{currentGroup.groupName} members";


/*Users */
/* List */
$strings["user.list.found"]="users found";
$strings["user.list.userName"]="User name";
$strings["user.list.firstName"]="First name";
$strings["user.list.lastName"]="Last name";
$strings["user.list.email"]="Email";
$strings["user.list.endDate"]="End date";

/*Placeholders*/
$strings["user.userName.placeholder"]="User name";
$strings["user.email.placeholder"]="Email address";
$strings["user.entity.placeholder"]="Organisation";
$strings["user.firstName.placeholder"]="Users's first name";
$strings["user.lastName.placeholder"]="User's last name";
$strings["user.password.placeholder"]="Password";
$strings["user.entity.placeholder"]="User's organisation (optional)";
$strings["user.emailAddress.placeholder"]="User's email address";
$strings["user.endDate.placeholder"]="From this date it will no more possible for the user to connect";
$strings["user.additionalData.placeholder"]="Additionnal data (free use)";
$strings["user.quotas.reqSec.placeholder"]="";
$strings["user.quotas.reqDay.placeholder"]="";
$strings["user.quotas.reqMonth.placeholder"]="";

/*tooltips*/
$strings["user.edit.tooltip"]="Edit this user";
$strings["user.delete.tooltip"]="Delete this user";
$strings["user.userName.tooltip"]="Enter here the user name to connect the system\nATTENTION: do not use special chars\nUser name is an identifier and can not be changed.";
$strings["user.password.tooltip"]="Enter here user's password";
$strings["user.firstName.tooltip"]="Enter here user's first name";
$strings["user.lastName.tooltip"]="Enter here user's last name";
$strings["user.entity.tooltip"]="User's organisation (optinal)";
$strings["user.emailAddress.tooltip"]="Adresse mail de l'utilisateur";
$strings["user.endDate.tooltip"]="From this date it will no more possible for the user to connect";
$strings["user.additionalData.tooltip"]="Additionnal data (free use)";
$strings["user.membership.tooltip"]="{currentUser.userName} groups membership";
$strings["user.availableGroups.tooltip"]="Select (with CTRL+Click) groups in which {currentUser.userName} should be added";
$strings["user.deleteGroup.tooltip"]="Remove user {currentUser.userName} from group {groupList[i].groupName}";
$strings["user.deleteQuota.tooltip"]="Delete quotas on service {quotasList[i].serviceName} for {currentUser.userName}";
$strings["user.editQuota.tooltip"]="Edit quotas on service {quotasList[i].serviceName} for {currentUser.userName}";
$strings["user.quotas.serviceName.tooltip"]="Select service to define user quotas";
$strings["user.quotas.reqSec.tooltip"]="Enter here the maximum of number of request per sercond allowed for the user on this service.";
$strings["user.quotas.reqDay.tooltip"]="Enter here the maximum of number of request per day allowed for the user on this service.";
$strings["user.quotas.reqMonth.tooltip"]="Enter here the maximum of number of request per month allowed for the user on this service.";

/* Details*/
$strings["user.properties.new"]="New user properties";
$strings["user.delete.confirm"]="Do you really want to delete user";
$strings["user.deleteGroup.confirm"]="Do you really want to remove this user form group";
$strings["user.deleteQuota.confirm"]="Do you really want to remove quot on service";
$strings["user.groups"]="{currentUser.userName}'s groups";
$strings["user.quotas"]="{currentUser.userName}'s quotas";
$strings["user.quotas.add"]="Add service quotas for user {currentUser.userName}";
$strings["user.quotas.edit"]="Edit service {quota.serviceName} quotas for user {quota.userName} ";
$strings["user.properties"]="{userName} properties";
$strings["user.label.userName"]="User name";
$strings["user.label.password"]="Password";
$strings["user.label.firstName"]="First name";
$strings["user.label.lastName"]="Last name";
$strings["user.label.entity"]="Organisation";
$strings["user.label.emailAddress"]="Email address";
$strings["user.label.endDate"]="End date";
$strings["user.label.additionalData"]="Additionnal data";
$strings["user.label.membership"]="Member of";
$strings["user.label.availableGroups"]="Available groups";


/*Services */
/* List */
$strings["service.list.found"]="services found";
$strings["service.list.name"]="Name";
$strings["service.list.serviceName"]="Service";
$strings["service.list.published"]="Published";
$strings["service.list.groupName"]="Group";
$strings["service.list.frontendEndpoint"]="Public alias";
$strings["service.list.backendEndpoint"]="Backend";
$strings["service.list.quotas.reqSec"]="Max/sec";
$strings["service.list.quotas.reqDay"]="Max/day";
$strings["service.list.quotas.reqMonth"]="Max/month";
$strings["service.publish.tooltip"]="Publish this service";
$strings["service.unpublish.tooltip"]="Unpublish this service";
/* Details */
$strings["service.delete.confirm"]="Do you really want to remove service";
/*Placeholders*/
$strings["service.name.placeholder"]="Service name";
$strings["service.groupName.placeholder"]="Group name to allow access";
$strings["service.frontendEndpoint.placeholder"]="Alias on publishing node";
$strings["service.backendEndpoint.placeholder"]="Backend URL";
$strings["service.loginForm.placeholder"]="Ex.: /ApplianceManagerAdmin/auth/loginFom";
$strings["service.baUsername.placeholder"]="User name";
$strings["service.baPassword.placeholder"]="Password";
$strings["service.reqSec.placeholder"]="";
$strings["service.reqDay.placeholder"]="";
$strings["service.reqMonth.placeholder"]="";

/*Tooltips*/
$strings["service.name.tooltip"]="Enter here service name\nATTENTION: do not use special chars\nService name is an identifier and can not be changed.";
$strings["service.frontendEndpoint.tooltip"]="Enter here the public alias for this service\nEx: /myservice";
$strings["service.backendEndpoint.tooltip"]="Enter here the service URL on backend system\nEx: http://backend.server/myservice\nOSA supports http, https and, if apache2.4 or more, also supports ws and wss";
$strings["service.edit.tooltip"]="Edit service";
$strings["service.delete.tooltip"]="Delete service";
$strings["service.add.tooltip"]="Add a service to the system";
$strings["service.isPublished.tooltip"]="If this box is checked, this service will be publihed on nodes\nelse it's not available"; 
$strings["service.group.tooltip"]="To use the service, users have to be members of selected group";
$strings["service.loginForm.tooltip"]="If the publication node support cookie authentication, unauthenticated access will be redirected to this URL";
$strings["service.isAnonymousAllowed.tooltip"]="Unauthenticated can also acces to this service.\nIn this case, backend system have to check if and identity is forwarded (connected user) or if it's empty (anonymous)";
$strings["service.forwardIdentity.tooltip"]="If this box is checked, connected user identity will be forwarded as an HTTP header to the backend server";
$strings["service.baUsername.tooltip"]="User name to authenticate against backend (basic auth.)";
$strings["service.baPassword.tooltip"]="Password to authenticate against backend";
$strings["service.isGlobalQuotasEnabled.tooltip"]="If this box is checked, backend system access will be limited by global quotas (what ever who's the user)";
$strings["service.reqSec.tooltip"]="Maximum number of request per seconds";
$strings["service.reqDay.tooltip"]="Maximum number of request per day";
$strings["service.reqMonth.tooltip"]="Maximum number of request per month";
$strings["service.isUserQuotasEnabled.tooltip"]="If this box is checked,  backend system access will be limited by per user quotas";
$strings["service.onAllNodes.tooltip"]="If this box is checked, this service will be published on all available nodes";
$strings["service.publishedOnNodes.tooltip"]="Select (with CTRL+Click) nodes on whitch {serviceName} will be published";
$strings["service.logHits.tooltip"]="If this box is checked, hits to this service will be recorded in logs\nATTENTION: this can dramatically affect OSA performances";
$strings["service.additionalConfiguration.tooltip"]="You can add here additinnal apache directives relative to </Location> tag";
$strings["service.isUserAuthenticationEnabled.tooltip"]="If this box is checked, OSA will check identity of authenticated users";

/*Labels*/
$strings["service.properties.new"]="New service properties";
$strings["service.properties"]="{serviceName} properties";

$strings["service.label.name"]="Service name";
$strings["service.label.chooseOne"]="-- Choose one --";
$strings["service.label.chooseNode"]="-- Published on any node --";
$strings["service.label.isPublished"]="Published";
$strings["service.label.frontendEndpoint"]="Public alias";
$strings["service.label.backendEndpoint"]="Backend server";
$strings["service.label.isUserAuthenticationEnabled"]="Enable user authentication";
$strings["service.label.group"]="Allow members of";
$strings["service.label.loginForm"]="Login page URL";
$strings["service.label.isAnonymousAllowed"]="Allow allow anonymous access";
$strings["service.label.forwardIdentity"]="Forward identity";
$strings["service.label.baUsername"]="User name (basic authentication)";
$strings["service.label.baPassword"]="Password (basic authentication)";
$strings["service.label.isGlobalQuotasEnabled"]="Enable global qutas";
$strings["service.label.reqSec"]="Maximum per second";
$strings["service.label.reqDay"]="Maximum per day";
$strings["service.label.reqMonth"]="Maximum per month";
$strings["service.label.isUserQuotasEnabled"]="Enable per user quotas";
$strings["service.label.onAllNodes"]="Available on all nodes";
$strings["service.label.publishedOnNodes"]="Service is available on following nodes";
$strings["service.label.logHits"]="Record hits";
$strings["service.label.warning.additionalConfiguration"]="ATTENTION: Using apache directives may alter system global configuration. Use at your own risks";
$strings["service.label.additionalConfiguration"]="Additionnal apache directives";
$strings["service.label.additionalConfiguration.helpText"]="In addition to standard apache environnement variable, in link with the way used by the node to published this service, you can use here:\n" .
						"<ul>\n" .
						"	<li>\n" .
						"		%{publicServerProtocol}e for protocol used (i.e http:// or https://)\n" .
						"	</li>\n" .
						"	<li>\n" .
						"		%{publicServerName}e server public name\n" .
						"	</li>\n" .
						"	<li>\n" .
						"		%{publicServerPort}e server port\n" .
						"	</li>\n" .
						"	<li>\n" .
						"		%{frontEndEndPoint}e public alias (prefix)\n" .
						"	</li>\n" .
						"	<li>\n" .
						"		%{publicServerPrefix}e concatenation of previous variables (ex: https//public.node.com:8443/myservice)\n" .
						"	</li>\n" .
						"	<br>Ex:<br><code>RequestHeader set Public-Root-URI \"%{publicServerProtocol}e%{publicServerName}e:%{publicServerPort}e/%{frontEndEndPoint}e\"</code>\n" .
						"</ul>\n";
$strings["service.label.headers"]="HTTP Headers to forward";
$strings["service.label.headers.userName"]=$strings["user.label.userName"];
$strings["service.label.headers.firstName"]=$strings["user.label.firstName"];
$strings["service.label.headers.lastName"]=$strings["user.label.lastName"];
$strings["service.label.headers.entity"]=$strings["user.label.entity"];
$strings["service.label.headers.emailAddress"]=$strings["user.label.emailAddress"];
$strings["service.label.headers.extra"]=$strings["user.label.additionalData"];


/* Tabs */
$strings["service.tab.general"]="General";
$strings["service.tab.frontend"]="Public alias and authentication";
$strings["service.tab.backend"]="Bakend identity";
$strings["service.tab.quotas"]="Quotas";
$strings["service.tab.nodes"]="Nodes";
$strings["service.tab.advanced"]="Advanced";



/* Counters */
$strings["counter.delete.confirm"]="Do you really want to remove this counter?";
$strings["counter.search.title"]="Search counters";
$strings["counter.edit.title"]="Edit counter value";
/* List */
$strings["counter.list.found"]="counters found";
$strings["counter.list.timeunit"]="Type";
$strings["counter.list.date"]="Date";
$strings["counter.list.value"]="Value";
$strings["counter.list.maxValue"]="Limit";

/* labels */
$strings["counter.label.timeunit.all"]="All";
$strings["counter.label.timeunit.sec"]="Second";
$strings["counter.label.timeunit.day"]="Day";
$strings["counter.label.timeunit.month"]="Month";
$strings["counter.label.timeunit"]="Time unit";
$strings["counter.label.service"]="Service";
$strings["counter.label.user"]="User";
$strings["counter.label.user.any"]="*** Any ***";

/* Tooltips */
$strings["counter.timeunit.tooltip"]="Select counter related time unit";
$strings["counter.service.tooltip"]="Enter here first letters of related service";
$strings["counter.user.tooltip"]="Enter here first letters of related user";
$strings["counter.edit.tooltip"]="Edit value for this counter";
$strings["counter.delete.tooltip"]="Delte this counter (reset)";



/* Nodes */
$strings["node.delete.confirm"]="Do you really want to delete node";
$strings["node.properties.new"]="New node properties";
$strings["node.properties"]="{node.nodeName} properties";
$strings["node.deleteCASSL.confirm"]="Do you really want to remove CA and chain data?";
$strings["node.deleteSSL.confirm"]="Do you really want to remove cert and key?";
/* List */
$strings["node.list.found"]="modes found";
$strings["node.list.nodeName"]="Name";
$strings["node.list.ssl"]="SSL";
$strings["node.list.description"]="Description";
$strings["node.list.FQDN"]="FQDN";
$strings["node.list.binding"]="Binding";

/*placeholders*/
$strings["node.nodeName.placeholder"]="Node name";
$strings["node.description.placeholder"]="Description";
$strings["node.serverFQDN.placeholder"]="Server's FQDN";
$strings["node.localIP.placeholder"]="Local listening IP";
$strings["node.port.placeholder"]="port";
$strings["node.localIP.placeholder"]="*";
$strings["node.nodeDescription.placeholder"]="";

/*tooltips*/
$strings["node.add.tooltip"]="Add publication nodes to the systeme";
$strings["node.edit.tooltip"]="Edit ths node";
$strings["node.delete.tooltip"]="Delet this node";
$strings["node.nodeName.tooltip"]="Enter here node name\nnATTENTION: do not use special chars\nNode name is an identifier and can not be changed.";
$strings["node.isHTTPS.tooltip"]="If this box is checked, thios node will be avaible with HTTPS, esle, with HTTP";
$strings["node.localIP.tooltip"]="Enter here IP or hotname on which node is listening (or * for all)";
$strings["node.port.tooltip"]="Enter here TCP port on which node is listening";
$strings["node.serverFQDN.tooltip"]="Enter here server FQDN (VirtualHost ServerName)";
$strings["node.nodeDescription.tooltip"]="Node description (optional)";
$strings["node.isBasicAuthEnabled.tooltip"]="If this box is checked, services publshed on this node will be protected by HTTP basic authentication mode";
$strings["node.isCookieAuthEnabled.tooltip"]="If this box is checked, services publshed on this node will be protected by HTTP cookie mode";
$strings["node.privateKey.tooltip"]="Select file containing server private key. If you don't select any file, a key will be generated by the system";
$strings["node.cert.tooltip"]="Select file containing server certificate. If you don't select any file, a certificate will be generated by the system";
$strings["node.manageCA.tooltip"]="Check this bow to specify CA and certification chain data";
$strings["node.ca.tooltip"]="Select a file containing CA cert.";
$strings["node.chain.tootip"]="Selectlect a file containing certification chain certificates.";
$strings["node.additionalConfiguration.tooltip"]="You can add here any appache directive applicable to </VirtualHost> tag";
$strings["node.publish.tooltip"]="Start ths node";
$strings["node.unpublish.tooltip"]="Stop this node";
$strings["node.https-on-80-warning"]="ATTENTION: Using HTTPS on port 80 (and not HTTP) may cause problems...\nDo you really want to do that?";
$strings["node.http-on-443-warning"]="ATTENTION: Using HTTP on port 443 (and not HTTPS) may cause problems...\nDo you really want to do that?";
$strings["node.no-authent-warning"]="At least one authentication methode (basic authentication or cookie authentication) should be enabled";

/*Labels */
$strings["node.label.nodeName"]="Node name";
$strings["node.label.isHTTPS"]="Use HTTPS";
$strings["node.label.localIP"]="Local IP";
$strings["node.label.port"]="Port";
$strings["node.label.serverFQDN"]="Server FQDN";
$strings["node.label.nodeDescription"]="Description";
$strings["node.label.isBasicAuthEnabled"]="Use HTTP Basic authentication";
$strings["node.label.isCookieAuthEnabled"]="Use HTTP cookie authentication";
$strings["node.label.privateKey"]="Private key";
$strings["node.label.cert"]="Certificate";
$strings["node.label.manageCA"]="Manage certification chain";
$strings["node.label.ca"]="CA certificate";
$strings["node.label.chain"]="Chain certificates";
$strings["node.label.additionalConfiguration"]="Additional apache configuration";
$strings["node.label.additionalConfiguration.warning"]="ATTENTION: Using apache directives may alter system global configuration. Use at your own risks";

/* Tabs */
$strings["node.tab.general"]="General";
$strings["node.tab.ssl"]="SSL certs and key";
$strings["node.tab.services"]="Services";
$strings["node.tab.advance"]="Advanced";


/* Logs */
$strings["log.search.title"]="Search in logs";
$strings["log.execute.confirm"]="Execute a request without criterais may be long and and dramatically affect gataway performances.\nContitnue anyway?";
	/*Labels*/
$strings["log.label.serviceName"]="Service";
$strings["log.label.userName"]="User";
$strings["log.label.frontEndEndPoint"]="Publique alias";
$strings["log.label.httpStatus"]="HTTP status";
$strings["log.label.message"]="Message";
$strings["log.label.from"]="From";
$strings["log.label.until"]="Until";


/*List*/
$strings["log.list.found"]="hits found";
$strings["log.list.serviceName"]="Service";
$strings["log.list.userName"]="User";
$strings["log.list.frontEndEndPoint"]="Alias";
$strings["log.list.time"]="Date";
$strings["log.list.status"]="Status";
$strings["log.list.message"]="Message";
?>
