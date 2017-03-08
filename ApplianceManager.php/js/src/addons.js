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

var osaAddonsObserver = new MutationObserver(function(mutations) {
												for (var j=0;j<divHooks.length;j++){
													if ($(divHooks[j].selector).length){
														divHooks[j].callback();
													}
												}
											});





function addonAddGUIHook(selector, callback){
	divHooks.push({"selector": selector, "callback": callback});	
}

$( document ).ready(function() {
	osaAddonsObserver.observe(document.getElementById("content"), { childList: true });
});
