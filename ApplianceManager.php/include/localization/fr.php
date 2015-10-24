<?php
$strings["date.format"]="dd/mm/yyyy";
$strings["locale"]="fr";
/* Global app labels */
$strings["app.title"]="Open Services Access";
$strings["app.version"]="Open Services Access V";

/* Navigation bar */
$strings["nav.toggle"]="Basculer";
$strings["nav.groups"]="Groupes";
$strings["nav.services"]="Services";
$strings["nav.users"]="Utilisateurs";
$strings["nav.counters"]="Compteurs";
$strings["nav.counters.search"]="Rechercher...";
$strings["nav.counters.exceeded"]="Quotas dépassés";
$strings["nav.nodes"]="Noeuds";
$strings["nav.logs"]="Logs";

/* Global list labels */
$strings["list.actions"]="Actions";

/*Global buttons label */
$strings["button.ok"]="OK";
$strings["button.cancel"]="Annuler";
$strings["button.back"]="Retour";
$strings["button.edit"]="Editer";
$strings["button.delete"]="Supprimer";
$strings["button.add"]="Ajouter";
$strings["button.members"]="Membres";
$strings["button.groups"]="Groupes";
$strings["button.quotas"]="Quotas";
$strings["button.search"]="Rechercher";
$strings["button.searchRefresh"]="Actualiser";
$strings["button.filter"]="filtrer";
$strings["button.reset"]="r.a.z";
$strings["button.resetSSL"]="Supprimer la clef et le certificat existant";
$strings["button.resetCASSL"]="Supprimer les infos de chaîne de certification";

$strings["button.filter.tooltip"]="Appliquer le filtre de recherche";
$strings["button.reset.tooltip"]="Rénitialiser les critères de recherche";
$strings["button.search.tooltip"]="Lancer la recherche";
$strings["button.searchRefresh.tooltip"]="Relance la dernière recherche";


/* Groups */
/* List */
$strings["group.list.found"]="groupes trouvés";
$strings["group.list.name"]="Nom";
$strings["group.list.description"]="Description";
/* Details */
$strings["group.name.placeholder"]="nom du groupe";
$strings["group.description.placeholder"]="description du groupe";
$strings["group.delete.confirm"]="Etes vous sur de vouloir supprimer le groupe";

$strings["group.edit.tooltip"]="Editer ce groupe";
$strings["group.delete.tooltip"]="Supprimer ce groupe";
$strings["group.add.tooltip"]="Ajouter un groupe au système";
$strings["group.name.tooltip"]="Saisissez ici le nom du groupe\nATTENTION: ne pas utiliser de caractères spéciaux\nLe nom de groupe est utilisé pour gérer l'authentification d'accès aux services.\nLe nom de groupe est un identifiant qui ne peut pas être modifié.";
$strings["group.description.tooltip"]="Saisissez ici la description du groupe\nCette donnée est a vocation informative et n'est pas exploitée par le système";
$strings["group.properties.new"]="Proriétés du nouveau groupe";
$strings["group.properties"]="Proriétés du groupe {group.groupName}";

$strings["group.members"]="Membres du groupe {currentGroup.groupName}";

/*Services */
/* List */
$strings["service.list.found"]="services trouvés";
$strings["service.list.name"]="Nom";
$strings["service.list.serviceName"]="Service";
$strings["service.list.published"]="Publié";
$strings["service.list.groupName"]="Groupe";
$strings["service.list.frontendEndpoint"]="Alias publique";
$strings["service.list.backendEndpoint"]="Backend";
$strings["service.list.quotas.reqSec"]="Max/sec";
$strings["service.list.quotas.reqDay"]="Max/jour";
$strings["service.list.quotas.reqMonth"]="Max/mois";
/* Details */
$strings["service.delete.confirm"]="Estes vous sûr de vouloir supprimer le service";
/*Placeholders*/
$strings["service.name.placeholder"]="Nom du service";
$strings["service.groupName.placeholder"]="Nom du groupe autorisant l'accès";
$strings["service.frontendEndpoint.placeholder"]="Alias sur le noeud d'exposition";
$strings["service.backendEndpoint.placeholder"]="URL du backend exposé";
$strings["service.loginForm.placeholder"]="Ex.: /ApplianceManagerAdmin/auth/loginFom";
$strings["service.baUsername.placeholder"]="Nom d'utilisateur";
$strings["service.baPassword.placeholder"]="Mot de passe";
$strings["service.reqSec.placeholder"]="";
$strings["service.reqDay.placeholder"]="";
$strings["service.reqMonth.placeholder"]="";

/*Tooltips*/
$strings["service.name.tooltip"]="Saisissez ici le nom du service\nATTENTION: Ne pas utilisez de caractères spéciaux\nLe nom du service est un identifiant qui ne peut pas être changé ultérieurement";
$strings["service.frontendEndpoint.tooltip"]="Saisissez ici l'alias public du service\nEx: /monservice";
$strings["service.backendEndpoint.tooltip"]="Saisissez ici l'URL du service sur le backend\nEx: http://serveur.backend/monservice\nOSA supporte http, https et si apache2.4 ou plus, ws et wss";
$strings["service.edit.tooltip"]="Editer ce service";
$strings["service.delete.tooltip"]="Supprimer ce service";
$strings["service.add.tooltip"]="Ajouter un service au système";
$strings["service.isPublished.tooltip"]="Si cette case est cochée, le service est publié sur les noeuds d'exposition\nsinon, il n'est pas accéssible"; 
$strings["service.group.tooltip"]="Pour utuliser ce service, les utilisateurs devront faire partie du groupe séléctionné";
$strings["service.loginForm.tooltip"]="Si le noeud de publication supporte l'autorisation par cookie, les accés non authentifiés seront redirigés vers cette URL";
$strings["service.isAnonymousAllowed.tooltip"]="Les utilisateurs non authentifiés peuvent tout de même accéder à ce service.\nCharge au backend determiner si un utilisateur est connecté ou non via la propagation d'identité";
$strings["service.forwardIdentity.tooltip"]="Si cette case est cochée, l'identité de l'utilisateurs connecté sera transmise sous forme d'ente HTTP au serveur backend";
$strings["service.baUsername.tooltip"]="Nom d'utilisateur à utiliser pour s'authenfier sur le serveur backend";
$strings["service.baPassword.tooltip"]="Mot de passe à utiliser pour s'authenfier sur le serveur backend";
$strings["service.isGlobalQuotasEnabled.tooltip"]="Si cette case est cochée, l'accès au serveur backend sera limité par des quotas tout utilisateurs confondus";
$strings["service.reqSec.tooltip"]="Nombre maximum de requêtes par seconde";
$strings["service.reqDay.tooltip"]="Nombre maximum de requêtes par jour";
$strings["service.reqMonth.tooltip"]="Nombre maximum de requêtes par mois";
$strings["service.isUserQuotasEnabled.tooltip"]="Si cette case est cochée, l'accès au serveur backend sera limité par des quotas par utilisateur";
$strings["service.onAllNodes.tooltip"]="Si cette case est cochée, le service sera publié sur tous les noeuds disponibles";
$strings["service.publishedOnNodes.tooltip"]="Seléctionnez (avec CTRL+Click) les noeuds sur lesquels le service {serviceName} sera publié";
$strings["service.logHits.tooltip"]="Si cette case est cochée, les appels au service seront enregistrés dans les logs\nATTENTION: Cela peut altérer considérablement les performances";
$strings["service.additionalConfiguration.tooltip"]="Vous pouvez ici rajouter des directives apache applicables au tag apache </Location>";

/*Labels*/
$strings["service.properties.new"]="Propriétés du nouveau service";
$strings["service.properties"]="Propriétés du service {serviceName}";

$strings["service.label.name"]="Nom du service";
$strings["service.label.chooseOne"]="-- Chosissez un groupe --";
$strings["service.label.isPublished"]="Publié";
$strings["service.label.frontendEndpoint"]="Alias publique";
$strings["service.label.backendEndpoint"]="Serveur backend";
$strings["service.label.isUserAuthenticationEnabled"]="Activer l'authentification utilisateur";
$strings["service.label.group"]="Autoriser les utilisateurs appartenant à";
$strings["service.label.loginForm"]="URL de la page de login";
$strings["service.label.isAnonymousAllowed"]="Permettre également l'accès anonyme";
$strings["service.label.forwardIdentity"]="Transmettre l'identité";
$strings["service.label.baUsername"]="Nom d'utilisateur (basic authentication)";
$strings["service.label.baPassword"]="Mot de passe (basic authentication)";
$strings["service.label.isGlobalQuotasEnabled"]="Activer le contrôle de quotas globaux";
$strings["service.label.reqSec"]="Maximum par seconde";
$strings["service.label.reqDay"]="Maximum par jour";
$strings["service.label.reqMonth"]="Maximum par mois";
$strings["service.label.isUserQuotasEnabled"]="Activer le contrôle de quotas par utilisateur";
$strings["service.label.onAllNodes"]="Disponible sur tous les noeuds";
$strings["service.label.publishedOnNodes"]="Service disponible sur les noeuds suivants";
$strings["service.label.logHits"]="Enregistrer les traces";
$strings["service.label.warning.additionalConfiguration"]="ATTENTION: Utiliser des directive de configuration appache aditionnelles peut corompre la configuration globale du système. A utiliser à vos risque et périls";
$strings["service.label.additionalConfiguration"]="Directives de configuration apache aditionnelles";
$strings["service.label.additionalConfiguration.helpText"]="En plus des variables standard d'apache, en rapport avec la façon dont le noeud expose le service, vous pouvez utiliser ici:\n" .
						"<ul>\n" .
						"	<li>\n" .
						"		%{publicServerProtocol}e pour le protocole utilisé (i.e http:// or https://)\n" .
						"	</li>\n" .
						"	<li>\n" .
						"		%{publicServerName}e nom public du serveur\n" .
						"	</li>\n" .
						"	<li>\n" .
						"		%{publicServerPort}e port du serveur\n" .
						"	</li>\n" .
						"	<li>\n" .
						"		%{frontEndEndPoint}e alias public (préfix)\n" .
						"	</li>\n" .
						"	<li>\n" .
						"		%{publicServerPrefix}e concatenation des variables précédents (ex: https//public.node.com:8443/myservice)\n" .
						"	</li>\n" .
						"	<br>Ex:<br><code>RequestHeader set Public-Root-URI \"%{publicServerProtocol}e%{publicServerName}e:%{publicServerPort}e/%{frontEndEndPoint}e\"</code>\n" .
						"</ul>\n";


/* Tabs */
$strings["service.tab.general"]="Générales";
$strings["service.tab.frontend"]="Alias publique et authentification";
$strings["service.tab.backend"]="Identité sur le backend";
$strings["service.tab.quotas"]="Quotas";
$strings["service.tab.nodes"]="Noeuds d'exposition";
$strings["service.tab.advanced"]="Avancées";

/*Users */
/* List */
$strings["user.list.found"]="utilisateurs trouvés";
$strings["user.list.userName"]="Nom d'utilisateur";
$strings["user.list.firstName"]="Prénom";
$strings["user.list.lastName"]="Nom";
$strings["user.list.email"]="Mail";
$strings["user.list.endDate"]="Date de fin";

/*Placeholders*/
$strings["user.userName.placeholder"]="Nom d'utilisateur";
$strings["user.email.placeholder"]="Addresse mail";
$strings["user.entity.placeholder"]="Oraganisation";
$strings["user.firstName.placeholder"]="Prénom";
$strings["user.lastName.placeholder"]="Prénom";
$strings["user.password.placeholder"]="Mot de passe";
$strings["user.firstName.placeholder"]="Prénom de l'utilisateur";
$strings["user.lastName.placeholder"]="Nom de famille de l'utilisateur";
$strings["user.entity.placeholder"]="Organisation de l'utilisateur (optionnelle)";
$strings["user.emailAddress.placeholder"]="Adresse mail de l'utilisateur";
$strings["user.endDate.placeholder"]="Date de fin après laquelle il sera impossible de se connecter";
$strings["user.additionalData.placeholder"]="Données aditionnelles à votre libre choix";
$strings["user.quotas.reqSec.placeholder"]="";
$strings["user.quotas.reqDay.placeholder"]="";
$strings["user.quotas.reqMonth.placeholder"]="";

/*tooltips*/
$strings["user.edit.tooltip"]="Editer cet utilisateur";
$strings["user.delete.tooltip"]="Supprimer cet utilisateur";
$strings["user.userName.tooltip"]="Saisissez ici le nom d'utilisateur se connecter au système\nLe nom d'utilisateur est un identifiant qui ne peut pas être changé ultérieurement";
$strings["user.password.tooltip"]="Saisissez ici son mot de passe";
$strings["user.firstName.tooltip"]="Saisissez ici le prénom de l'utilisateur";
$strings["user.lastName.tooltip"]="Saisissez ici son nom de famille";
$strings["user.entity.tooltip"]="Organisation de l'utilisateur (optionnelle)";
$strings["user.emailAddress.tooltip"]="Adresse mail de l'utilisateur";
$strings["user.endDate.tooltip"]="Date de fin après laquelle il sera impossible de se connecter";
$strings["user.additionalData.tooltip"]="Données aditionnelles à votre libre choix";
$strings["user.membership.tooltip"]="Liste des groupes dont l'utilisateur {currentUser.userName} fait partie";
$strings["user.availableGroups.tooltip"]="Seléctionnez (avec CTRL+Click) les groupes dans lesquels l'utilisateur {currentUser.userName} doit être ajouté";
$strings["user.deleteGroup.tooltip"]="Retirer l'utilisateur {currentUser.userName} du groupe {groupList[i].groupName}";
$strings["user.deleteQuota.tooltip"]="Supprimer les quotas sur le service {quotasList[i].serviceName} pour l'utilisateur {currentUser.userName}";
$strings["user.editQuota.tooltip"]="Modifier les quotas sur le service {quotasList[i].serviceName} pour l'utilisateur {currentUser.userName}";
$strings["user.quotas.serviceName.tooltip"]="Selectionner le service pour lequel les quotas utilisateurs doivent être définis";
$strings["user.quotas.reqSec.tooltip"]="Saisissez ici le nombre maximal de requête par secondes qu'un utilisateur peut passer sur le service";
$strings["user.quotas.reqDay.tooltip"]="Saisissez ici le nombre maximal de requête par jours qu'un utilisateur peut passer sur le service";
$strings["user.quotas.reqMonth.tooltip"]="Saisissez ici le nombre maximal de requête par mois qu'un utilisateur peut passer sur le service";

/* Details*/
$strings["user.properties.new"]="Proriétés du nouvel utilisateur";
$strings["user.delete.confirm"]="Etes vous sûr de vouloir supprimer l'utilisateur";
$strings["user.deleteGroup.confirm"]="Etes vous sûr de vouloir supprimer l'utilisateur du groupe";
$strings["user.deleteQuota.confirm"]="Etes vous sûr de vouloir supprimer le quota sur le service";
$strings["user.groups"]="Groupes de l'utilisateur {currentUser.userName}";
$strings["user.quotas"]="Quotas de l'utilisateur {currentUser.userName}";
$strings["user.quotas.add"]="Ajouter des quotas de service pour l'utilisateur {currentUser.userName}";
$strings["user.quotas.edit"]="Modifier les quotas du service {quota.serviceName} pour l'utilisateur {quota.userName} ";
$strings["user.properties"]="Proriétés de l'utilisateur {userName}";
$strings["user.label.userName"]="Nom d'utilisateur";
$strings["user.label.password"]="Mot de passe";
$strings["user.label.firstName"]="Prénom";
$strings["user.label.lastName"]="Nom";
$strings["user.label.entity"]="Organisation";
$strings["user.label.emailAddress"]="Adresse mail";
$strings["user.label.endDate"]="Date de fin";
$strings["user.label.additionalData"]="Données aditionnelles";
$strings["user.label.membership"]="Fait partie de";
$strings["user.label.availableGroups"]="Groupes disponbles";

/* Counters */
$strings["counter.delete.confirm"]="Etes vous sûr de vouloir supprimer ce compteur ?";
$strings["counter.search.title"]="Rechercher des compteurs";
$strings["counter.edit.title"]="Modifier les valeurs du compteur";
/* List */
$strings["counter.list.found"]="compteurs trouvés";
$strings["counter.list.timeunit"]="Type";
$strings["counter.list.date"]="Date";
$strings["counter.list.value"]="Valeur";
$strings["counter.list.maxValue"]="Limite";

/* labels */
$strings["counter.label.timeunit.all"]="Tout";
$strings["counter.label.timeunit.sec"]="Seconde";
$strings["counter.label.timeunit.day"]="Jour";
$strings["counter.label.timeunit.month"]="Mois";
$strings["counter.label.timeunit"]="Unité de temps";
$strings["counter.label.service"]="Service";
$strings["counter.label.user"]="Utilisateur";
$strings["counter.label.user.any"]="*** N'importe lequel ***";

/* Tooltips */
$strings["counter.timeunit.tooltip"]="Selectionnez l'unité de temps sur laquelle portent les compteurs rechechés";
$strings["counter.service.tooltip"]="Tapez les premières lettres du service sur lequel portent les compteurs rechechés";
$strings["counter.user.tooltip"]="Tapez les premières lettres du  nom d'utilisateur lequel portent les compteurs rechechés";
$strings["counter.edit.tooltip"]="Changer les valeurs de ce compteur";
$strings["counter.delete.tooltip"]="Supprimer ce compteur (ré-initialiser)";



/* Nodes */
$strings["node.delete.confirm"]="Etes vous sur de vouloir supprimer le noeud";
$strings["node.properties.new"]="Propriétés du nouveau noeud de publication";
$strings["node.properties"]="Propriétés du noeud de publication {node.nodeName}";
$strings["node.deleteCASSL.confirm"]="Etes vous sûr de vouloir supprimer les informations de la chaîne de certification ?";
$strings["node.deleteSSL.confirm"]="Etes vous sûr de vouloir supprimer les paramètres de certificat ?";
/* List */
$strings["node.list.found"]="noeuds trouvés";
$strings["node.list.nodeName"]="Nom";
$strings["node.list.ssl"]="SSL";
$strings["node.list.description"]="Description";
$strings["node.list.FQDN"]="FQDN";
$strings["node.list.binding"]="Binding";

/*placeholders*/
$strings["node.nodeName.placeholder"]="Nom du noeud";
$strings["node.description.placeholder"]="Description";
$strings["node.serverFQDN.placeholder"]="FQDN du serveur";
$strings["node.localIP.placeholder"]="IP locale d'écoute";
$strings["node.port.placeholder"]="port";
$strings["node.localIP.placeholder"]="*";
$strings["node.nodeDescription.placeholder"]="";

/*tooltips*/
$strings["node.add.tooltip"]="Ajouter un noeud de publication au système";
$strings["node.edit.tooltip"]="Editer ce noeud";
$strings["node.delete.tooltip"]="Supprimer ce noeud";
$strings["node.nodeName.tooltip"]="Saisissez ici le nom du noeud de publication\nATTENTION : ne pas utiliser de caractères spéciaux\nLe nom du noeud est un identifiant qui ne peut pas être changé ultérieurement";
$strings["node.isHTTPS.tooltip"]="Si cette case est cochée, le noeud sera dispinnible en https, sinon en http";
$strings["node.localIP.tooltip"]="Saisissez ici l'adresse IP (ou le nom correspondant) sur laquelle le noeud doit écouter (ou * pour toutes)";
$strings["node.port.tooltip"]="Saissisez ici le port TCP sur lequel le noeud doit écouter";
$strings["node.serverFQDN.tooltip"]="Saisissez ici le FQDN auquel le serveur doit répondre";
$strings["node.nodeDescription.tooltip"]="(optionnelle) Description informative du noeud";
$strings["node.isBasicAuthEnabled.tooltip"]="Si cette case est cochée, les services publiés sur ce noeud pourront être protégés par le mode d'authentification \"basic authentication\"";
$strings["node.isCookieAuthEnabled.tooltip"]="Si cette case est cochée, les services publiés sur ce noeud pourront être protégés par le mode d'authentification par cookie";
$strings["node.privateKey.tooltip"]="Selectionnez le fichier contenant la clef privée. Si aucun fichier n'est selectionné, le système en générera une";
$strings["node.cert.tooltip"]="Selectionnez le fichier contenant le certificat. Si aucun fichier n'est selectionné, le système en générera un";
$strings["node.manageCA.tooltip"]="Cocher cette case pour renseigner les certification amont";
$strings["node.ca.tooltip"]="Selectionnez le fichier contenant le certificat de l'authorité de certification.";
$strings["node.chain.tootip"]="Selectionnez le fichier contenant les certificats de la chaîne de certification.";
$strings["node.additionalConfiguration.tooltip"]="Vous pouvez ici rajouter des directives apache applicables au tag apache </VirtualHost>";


/*Labels */
$strings["node.label.nodeName"]="Nom du noeud";
$strings["node.label.isHTTPS"]="Utiliser HTTPS";
$strings["node.label.localIP"]="IP locale";
$strings["node.label.port"]="Port";
$strings["node.label.serverFQDN"]="FQDN du serveur";
$strings["node.label.nodeDescription"]="Description";
$strings["node.label.isBasicAuthEnabled"]="Utiliser l'authentification \"Basic authentication\"";
$strings["node.label.isCookieAuthEnabled"]="Utiliser l'authentification par cookie";
$strings["node.label.privateKey"]="Clé privée";
$strings["node.label.cert"]="Certificat";
$strings["node.label.manageCA"]="Gérer la chaine de certification";
$strings["node.label.ca"]="Certificat \"CA\"";
$strings["node.label.chain"]="Certificats \"chain\"";
$strings["node.label.additionalConfiguration"]="Configuration apache additionnelle";
$strings["node.label.additionalConfiguration.warning"]="ATTENTION: Utiliser des directive de configuration appache aditionnelles peut corompre la configuration globale du système. A utiliser à vos risque et périls";


/* Logs */
$strings["log.search.title"]="Rechercher dans les logs";
$strings["log.execute.confirm"]="Effectuer une recherche sans critères peut être long et affecter les performances de la gateway\nEffectuer tout de même ?";
	/*Labels*/
$strings["log.label.serviceName"]="Service";
$strings["log.label.userName"]="Utilisateur";
$strings["log.label.frontEndEndPoint"]="Alias publique";
$strings["log.label.httpStatus"]="Statut HTTP";
$strings["log.label.message"]="Message";
$strings["log.label.from"]="Depuis le";
$strings["log.label.until"]="Jusqu'au";


/*List*/
$strings["log.list.found"]="hits trouvés";
$strings["log.list.serviceName"]="Service";
$strings["log.list.userName"]="Utilisateur";
$strings["log.list.fontEndEndPoint"]="Alias";
$strings["log.list.status"]="Statut";
$strings["log.list.message"]="Message";
?>
