/*--------------------------------------------------------
 * Module Name : ApplianceManager
 * Version : 1.0.0
 *
 * Software Name : OpenServicesAccess
 * Version : 1.0
 *
 * Copyright (c) 2011 – 2017 Orange
 * This software is distributed under the Apache 2 license
 * <http://www.apache.org/licenses/LICENSE-2.0.html>
 *
 *--------------------------------------------------------
 * File Name   : ApplianceManager/ApplianceManager.php/js/addons.js
 *
 * Created     : 2017-03
 * Authors     : Zorglub42 <contact(at)zorglub42.fr>
 *
 * Description :
 *      Addons management
 *--------------------------------------------------------
 * History     :
 * 1.0.0 - 2017-03-01 : Release of the file
*/
var divHooks=[];
var apiDocsURI=[];

//DOM Mutation Observer
var osaAddonsObserver = new MutationObserver(
	function(mutations) {
		for (var j=0;j<divHooks.length;j++){
			if ($(divHooks[j].selector).length){
				//we found at least 1 element satifying provided JQuery selector pattern
				//Trigger hook callback
				divHooks[j].callback();
			}
		}
	}
);


/* Folling methods are used by add-ons developpers to intergate addons
   in OSA GUI */

// add a hook on a particular GUI Element selected with JQuery
// "callback" is executed when HTML element with id "selector" is found
function addonAddGUIHook(selector, callback){
	divHooks.push({"selector": selector, "callback": callback});
}

// declare an item in API Documentation menu
// Doc URL = <OSA BASE URL>/addons/<addon>/<uri>
function addonAddDocURI(addon, uri){
	apiDocsURI.push({"addon" : addon, "uri" : uri});
	apiDocsURI.sort();
}



$( document ).ready(function() {
	//Start mutation observer
	osaAddonsObserver.observe(document.getElementById("content"), { childList: true });
	$("#content").html($("#content").html());

	//Add items to API documentation menu
	apiDocListHtml = $("#apiDocList").html();
	apiDocsURI.forEach(function(item){
			apiDocListHtml = apiDocListHtml + '<li><a id="apiDocMenu' + item.addon + '" href="#"  onclick="loadDoc(\'addons/'+ item.addon + '/' + item.uri + '\')">' + item.addon + '</a></li>';
	});
	$("#apiDocList").html(apiDocListHtml);
});
