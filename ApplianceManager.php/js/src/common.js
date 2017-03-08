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
 *      Common functions
 *--------------------------------------------------------
 * History     :
 * 1.0.0 - 2017-03-01 : Release of the file
*/function addItem(list, item){
	addItem(list, item, false);
}
function addItem(list, item, distinct){
	if (list.indexOf(item)<0 || !distinct){
		list.push(item);
		list.sort();
		console.log("Sorted");
	}
}
