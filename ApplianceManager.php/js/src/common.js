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
*/

// Add an item to a list
function addItem(list, item){
	addItem(list, item, false);
}

//Add an item to a list
// if distinct is true add item only if it does not already exists in the list
// if distinct is false add item even if it aleady exists in the list
function addItem(list, item, distinct){
	if (list.indexOf(item)<0 || !distinct){
		list.push(item);
		list.sort();
	}
}
