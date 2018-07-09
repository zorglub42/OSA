<?php
/**
 * Reverse Proxy as a service
 * 
 * PHP Version 7.0
 * 
 * @category ReverseProxy
 * @package  OSA
 * @author   Benoit HERARD <benoit.herard@orange.com>
 * @license  http://www.apache.org/licenses/LICENSE-2.0.htm Apache 2 license
 * @link     https://github.com/zorglub42/OSA/
 * 
 * @codingStandardsIgnoreStart
*/
?>
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
 * File Name   : ApplianceManager/ApplianceManager.php/js/users.js
 *
 * Created     : 2012-02
 * Authors     : Benoit HERARD <benoit.herard(at)orange.com>
 *
 * Description :
 *      AJAX MAnagement for users
 *--------------------------------------------------------
 * History     :
 * 1.0.0 - 2012-10-01 : Release of the file
 */

var currentUserURI;
var currentUser;
var userModified;
var propertiesCount;

var editQuota = "Edit this quota";
var deleteQuota = "Delete this quota";


var userNameFilterPrevVal="";
var firstNameFilterPrevVal="";
var lastNameFilterPrevVal="";
var emailAddressFilterPrevVal="";
var entityFilterPrevVal="";


/* Delete a user quota */
function deleteUserQuotas(quotaURI, serviceName) {
	if (confirm("<?php echo Localization::getJSString("user.deleteQuota.confirm")?> " + serviceName + "?")) {
		$.ajax({
			url : quotaURI,
			dataType : 'json',
			type : 'DELETE',
			// data: data,
			success : startDisplayUserQuotasForCurrentUser,
			error : displayErrorV2
		});
	}

}

/* Enable or disable UI control according to user properties updates */
function setUserModified(isModified) {
	userModified = isModified;
	if (isModified) {
		$("#cancel").html("<?php echo Localization::getJSString("button.cancel")?>")
		setActionButtonEnabled('saveNew', true);
		setActionButtonEnabled('saveEdit', true);
		setActionButtonEnabled('groupsEdit', false);
		setActionButtonEnabled('quotasEdit', false);
	} else {
		$("#cancel").html("<?php echo Localization::getJSString("button.back")?>")
		setActionButtonEnabled('saveNew', false);
		setActionButtonEnabled('saveEdit', false);
		setActionButtonEnabled('groupsEdit', true);
		setActionButtonEnabled('quotasEdit', true);
	}
}

/* Remove a user from a group */
function deleteUserGroup(groupURI, groupName, userURI) {
	if (confirm("<?php echo Localization::getJSString("user.deleteGroup.confirm")?> " + groupName + "?")) {
		$.ajax({
			url : userURI + "/" + groupURI,
			dataType : 'json',
			type : 'DELETE',
			// data: data,
			success : startDisplayUserGroupsForCurrentUser,
			error : displayErrorV2
		});
	}

}

/* Add user to selected groups */
function addGroupToUser(userURI) {
	grps = document.getElementById('availableGroupsList');
	selectedCount = 0;
	// count selected item to be able to start reload page on last one
	for (i = 0; i < grps.options.length; i++) {
		if (grps.options[i].selected) {
			selectedCount++;
		}
	}
	currentItem = 1;
	for (i = 0; i < grps.options.length; i++) {
		if (grps.options[i].selected) {
			if (currentItem == selectedCount) {
				onSuccess = startDisplayUserGroupsForCurrentUser;
			} else {
				onSusccess = null;
			}
			$.ajax({
				url : userURI + "/" + grps.options[i].value,
				dataType : 'json',
				type : 'POST',
				data : null,
				success : startDisplayUserGroupsForCurrentUser,
				error : displayErrorV2
			});
			currentItem++;
		}
	}
	startDisplayUserGroups(userURI);
}


/* Load add user template and display */
function addUser() {
	$.get( "resources/templates/userAdd.php", function( data ) {
		currentService=null;
		currentServiceGroup=null;
		$('#content').html(data.replaceAll("{userNameAsLabel}","")
							   .replaceAll("{userNameInputType}", "text")
							   .replaceAll("{userName}", "")
							   .replaceAll("{password}", "")
							   .replaceAll("{firstName}", "")
							   .replaceAll("{lastName}", "")
							   .replaceAll("{entity}", "")
							   .replaceAll("{emailAddress}", "")
							   .replaceAll("{endDate}", "")
		);
		$('#userEndDate').datepicker();
		$('#lastTokenLogin').hide();
		$('#lastTokenLoginLabel').hide();
		setUserModified(false);
	});
}

/* Save (create) a new user */
function saveNewUser() {
	saveOrUpdateUser('POST');
}

/* Update a user */
function updateUser(userURI) {
	saveOrUpdateUser('PUT');
}

/* Save (create) or update a user */
function saveOrUpdateUser(method) {
	currentUserUri = "users/"
			+ encodeURIComponent(document.getElementById("userName").value);
	password = "password="
			+ encodeURIComponent(document.getElementById("userPass").value);
	userName = "userName="
			+ encodeURIComponent(document.getElementById("userName").value);
	email = "email=" + encodeURIComponent(document.getElementById("userMail").value);
	firstName = "firstName="
			+ encodeURIComponent(document.getElementById("firstName").value);
	lastName = "lastName="
			+ encodeURIComponent(document.getElementById("lastName").value);
	entity = "entity=" + encodeURIComponent(document.getElementById("entity").value);
	try{
		d=Date.parseExact(document.getElementById("userEndDate").value,"<?php echo Localization::getJSString("date.format.parseexact")?>");
		d.setHours(12);
		endDate = "endDate="
				+ encodeURIComponent(d.format("isoUtcDateTime"));
	}catch (ex){
		endDate="endDate=";
	}
	user={
		"userName": $("#userName").val(),
		"password": $("#userPass").val(),
		"email": $("#userMail").val(),
		"firstName": $("#firstName").val(),
		"lastName": $("#lastName").val(),
		"entity": $("#entity").val(),
		"properties": []
	};
	for (i=0;i<propertiesCount;i++){
		if ($("#propertyName_" + i).val() !== undefined && $("#propertyValue_" + i).val() !== undefined){
			prop={
				"name": $("#propertyName_" + i).val(),
				"value": $("#propertyValue_" + i).val()
			}
			user.properties.push(prop);
		}
	}
	postData = password + "&" + email + "&" + endDate + "&" + firstName + "&"
			+ lastName + "&" + entity;
	if (method == 'POST') {
		uri = "users/";
		postData = "userName="
				+ encodeURIComponent(document.getElementById("userName").value) + "&"
				+ postData;
	} else {
		uri = "users/" + encodeURIComponent(document.getElementById("userName").value);
	}
	$.ajax({
		url : uri,
		dataType : 'json',
		contentType: "application/json",
		type : method,
		//data : postData,
		data: JSON.stringify(user),
		success : startEditCurrentUser,
		error : displayErrorV2
	});

}

/* Start user edit for a user */
function startEditUser(userURI) {
	currentUserUri = userURI;
	$.getJSON(userURI, editUser).error(displayErrorV2);
}

/* Restart edit for current user */
function startEditCurrentUser() {
	startEditUser(currentUserUri);
}

/* Load available groups for a user and populate list */
function startDisplayAvailableGroups(userURI) {
	currentUserURI = userURI;
	$.getJSON(userURI + "/groups/available/", displayAvailableGroups).error(
			displayErrorV2);
}

/* Load groups membership for current user and populate list */
function startDisplayUserGroupsForCurrentUser(group) {
	startDisplayUserGroups(currentUserURI);
}

/* Load groups membership for a user and populate list */
function startDisplayUserGroups(userURI) {
	currentUserURI = userURI;
	$.getJSON(userURI + "/groups/", displayUserGroups).error(displayErrorV2);
}

/* Loads quotas for a user and display */
function startDisplayUserQuotas(userURI) {
	currentUserURI = userURI;
	$.getJSON(userURI + "/quotas/", displayUserQuotas).error(displayErrorV2);
}

/* Start diplay quatas for current user */
function startDisplayUserQuotasForCurrentUser() {
	startDisplayUserQuotas(currentUserUri);
}

/* Poplulate list of available groups (for membership) */
function displayAvailableGroups(groupList) {
		if (groupList.length>0){
			$.each(groupList, function (i, item) {
				$('#availableGroupsList').append($('<option>', {
					value: "groups/" + item.groupName,
					text : item.groupName
				}));
			});
		}else{
			$("#addGroups").hide();
		}
}

function addUserProperty(){
	if ($("#propertyName_new").val() != "" && $("#propertyValue_new").val() != ""){
		for (i=0;i<propertiesCount;i++){
			if ($("#propertyName_" + i).val() == $("#propertyName_new").val()){
				alert("<?php echo Localization::getJSString("user.property.exists")?>");
				return false;
			}
		}
		table=document.getElementById("data");

		rowPattern=document.getElementById("rowTpl");

		newRow=rowPattern.cloneNode(true);
		newRow.removeAttribute('id');
		newRow.setAttribute('id', 'property_' + propertiesCount);
		newRow.removeAttribute('style');
		newRow.className=newRow.className + " tabular_table_body" +  ((propertiesCount)%2);
		newRow.innerHTML=newRow.innerHTML.replaceAll("{i}", propertiesCount)
										.replaceAll("{propertiesList[i].name}", $("#propertyName_new").val())
										.replaceAll("{propertiesList[i].value}", $("#propertyValue_new").val());

		table.insertBefore(newRow, document.getElementById('newProp'));
		$("#propertyValue_new").val("");
		$("#propertyName_new").val("");
		propertiesCount++;

		setUserModified(true);

	}
}

function deleteUserProperty(propNum, propName){
	if (confirm("<?php echo Localization::getJSString("user.property.delete.confirm")?> " + propName + "?")) {
		prop=document.getElementById('property_' + propNum);
		prop.parentNode	.removeChild(prop);
		setUserModified(true);
	}

}

/* Load edit user template and display */
function editUser(user) {
	$.get( "resources/templates/userEdit.php", function( data ) {
		if (user.endDate !== null){
			userDate = new Date(user.endDate);
			dateFormated = userDate.format("<?php echo Localization::getJSString("date.format")?>");
		}else{
			dateFormated="";
		}

		if (user.lastTokenLogin != null){
			loginDate = new Date(user.lastTokenLogin);
			lastTokenLogin = loginDate.format("<?php echo Localization::getJSString("date.format.parseexact.long")?>");
		}else{
			lastTokenLogin="";
		}
		currentUser=user;
		$('#content').html(data.replaceAll("{userNameAsLabel}",user.userName)
							   .replaceAll("{userNameInputType}", "hidden")
							   .replaceAll("{userName}", user.userName)
							   .replaceAll("{password}", user.password)
							   .replaceAll("{firstName}", user.firstName)
							   .replaceAll("{lastName}", user.lastName)
							   .replaceAll("{entity}", user.entity)
							   .replaceAll("{emailAddress}", user.emailAddress)
							   .replaceAll("{endDate}", dateFormated)
							   .replaceAll("{lastTokenLogin}", lastTokenLogin)
							   .replaceAll("{uri}", user.uri)
		);

		if (user.properties.length>0){
			for (i=0;i<user.properties.length;i++){
				table=document.getElementById("data");

				rowPattern=document.getElementById("rowTpl");

				newRow=rowPattern.cloneNode(true);
				newRow.removeAttribute('id');
				newRow.setAttribute('id', 'property_' + i);
				newRow.removeAttribute('style');
				newRow.className=newRow.className + " tabular_table_body" +  (i%2);
				newRow.innerHTML=newRow.innerHTML.replaceAll("{i}", i)
												 .replaceAll("{propertiesList[i].name}", user.properties[i].name)
												 .replaceAll("{propertiesList[i].value}", user.properties[i].value.replaceAll("\"","&quot;"));

				table.insertBefore(newRow, document.getElementById('newProp'));
			}
			
		}
		propertiesCount=user.properties.length;

		$('#userEndDate').datepicker();
		setUserModified(false);
	});

}

/* Load user's group membership template and display */
function displayUserGroups(groupList) {
	$.get( "resources/templates/userGroups.php", function( data ) {

		$( "#content" ).html( data.replaceAll("{currentUser.userName}", currentUser.userName )
								  .replaceAll("{currentUser.uri}", currentUser.uri )
							);
		table=document.getElementById("data");
		rowPattern=document.getElementById("rowTpl");
		table.removeChild(rowPattern);

		if (groupList.length>0){
			for (i=0;i<groupList.length;i++){


				newRow=rowPattern.cloneNode(true);
				newRow.removeAttribute('id');
				newRow.removeAttribute('style');
				newRow.className=newRow.className + " tabular_table_body" +  (i%2);
				newRow.innerHTML=newRow.innerHTML.replaceAll("{groupList[i].groupName}", groupList[i].groupName)
												 .replaceAll("{currentUserURI}", currentUserURI)
												 .replaceAll("{groupList[i].description}", groupList[i].description);

				table.appendChild(newRow);
				edit=document.getElementById("btnEdit");
				del=document.getElementById("btnDelete");
				if ( groupList[i].groupName == "Admin" && currentUser.userName == "Admin"){
					del.remove();
				}else{
					del.removeAttribute("id");
				}
			}
		}else{
			$('#userGroupList').hide();
		}

		startDisplayAvailableGroups(currentUserURI);
	});
}

/* Load user's quota template and dispaly */
function displayUserQuotas(quotasList) {
	$.get( "resources/templates/userQuotas.php", function( data ) {

		$( "#content" ).html( data.replaceAll("{currentUser.userName}", currentUser.userName )
							);
		table=document.getElementById("data");
		rowPattern=document.getElementById("rowTpl");
		table.removeChild(rowPattern);

		if (quotasList.length>0){
			for (i=0;i<quotasList.length;i++){


				newRow=rowPattern.cloneNode(true);
				newRow.removeAttribute('id');
				newRow.removeAttribute('style');
				newRow.className=newRow.className + " tabular_table_body" +  (i%2);
				newRow.innerHTML=newRow.innerHTML.replaceAll("{quotasList[i].serviceName}", quotasList[i].serviceName)
												 .replaceAll("{quotasList[i].reqSec}", quotasList[i].reqSec)
												 .replaceAll("{quotasList[i].reqDay}", quotasList[i].reqDay)
												 .replaceAll("{quotasList[i].reqMonth}", quotasList[i].reqMonth)
												 .replaceAll("{quotasList[i].uri}", quotasList[i].uri);

				table.appendChild(newRow);
				edit=document.getElementById("btnEdit");
				del=document.getElementById("btnDelete");
				del.removeAttribute("id");
				edit.removeAttribute("id");
			}
		}else{
			$('#userQuotasList').hide();
		}
	});

}

/* Handle key press on services filter form to apply filter when "enter" key
* is pressed */
function handelUserFilterFormKeypress(e) {
	if (e.keyCode == 13) {
		showUsers();
		return false;
	}
}

/* Load users list template and dispaly */
function displayUserList(userList) {
	$.get("resources/templates/userList.php", function( data ) {

		$( "#content" ).html( data.replaceAll("{userList.length}", userList.length )
								  .replaceAll("{userNameFilterPrevVal}", userNameFilterPrevVal )
								  .replaceAll("{emailAddressFilterPrevVal}", emailAddressFilterPrevVal )
								  .replaceAll("{entityFilterPrevVal}", entityFilterPrevVal )
								  .replaceAll("{firstNameFilterPrevVal}", firstNameFilterPrevVal )
								  .replaceAll("{lastNameFilterPrevVal}", lastNameFilterPrevVal )
							);
		table=document.getElementById("data");
		rowPattern=document.getElementById("rowTpl");
		table.removeChild(rowPattern);


		var usersListAutoComplete=new Array();
		var emailsListAutoComplete=new Array();
		var entitiesListAutoComplete=new Array();
		var firtNameListAutoComplete=new Array();
		var lastNameListAutoComplete=new Array();


		for (i=0;i<userList.length;i++){
			if (userList[i].endDate !== null){
				var d = new Date();
				d.setISO8601(userList[i].endDate);
				dateFormated=d.format("<?php echo Localization::getJSString("date.format")?>")
			}else{
				dateFormated="";
			}

			addItem(usersListAutoComplete, userList[i].userName);
			addItem(emailsListAutoComplete, userList[i].emailAddress, true);
			addItem(entitiesListAutoComplete, userList[i].entity, true);
			addItem(firtNameListAutoComplete, userList[i].firstName, true);
			addItem(lastNameListAutoComplete, userList[i].lastName, true);

			newRow=rowPattern.cloneNode(true);
			newRow.removeAttribute('id');
			newRow.removeAttribute('style');
			newRow.className=newRow.className + " tabular_table_body" +  (i%2);
			newRow.innerHTML=newRow.innerHTML.replaceAll("{userList[i].userName}", userList[i].userName)
											 .replaceAll("{userList[i].emailAddress}", userList[i].emailAddress)
											 .replaceAll("{userList[i].uri}", userList[i].uri)
											 .replaceAll("{userList[i].endDate}", dateFormated);
			table.appendChild(newRow);
			edit=document.getElementById("btnEdit");
			del=document.getElementById("btnDelete");
			if (userList[i].userName === "Admin"){
				del.remove();
			}else{
				del.removeAttribute("id");
			}
			edit.removeAttribute("id");
		}
		if (userList.length==0){
			$('#usersList').hide();
		}
		$( "#userNameFilter" ).autocomplete({
			source: usersListAutoComplete,
			minLength: 0
		});
		$( "#emailAddressFilter" ).autocomplete({
			source: emailsListAutoComplete,
			minLength: 0
		});
		$( "#entityFilter" ).autocomplete({
			source: entitiesListAutoComplete,
			minLength: 0
		});
		$( "#firstNameFilter" ).autocomplete({
			source: firtNameListAutoComplete,
			minLength: 0
		});
		$( "#lastNameFilter" ).autocomplete({
			source: lastNameListAutoComplete,
			minLength: 0
		});

	});


}

/* Delete a user */
function deleteUser(userURI, userName) {
	if (confirm("<?php echo Localization::getJSString("user.delete.confirm")?> " + userName + "?")) {
		$.ajax({
			url : userURI,
			dataType : 'json',
			type : 'DELETE',
			// data: data,
			success : showUsers,
			error : displayErrorV2
		});
	}

}

/* REset user search filter form fields and apply search */
function  resetUserFilter(){
	$('#userNameFilter').val("");
	$('#firstNameFilter').val("")
	$('#lastNameFilter').val("")
	$('#emailAddressFilter').val("");
	$('#entityFilter').val("");
	showUsers();
}

/* Load (search) users and display */
function showUsers() {
	prms="order=userName";

	prms=prms + "&userNameFilter=" + encodeURIComponent(getFilterValue('userNameFilter'));
	prms=prms + "&firstNameFilter=" + encodeURIComponent(getFilterValue('firstNameFilter'));
	prms=prms + "&lastNameFilter=" + encodeURIComponent(getFilterValue('lastNameFilter'));
	prms=prms + "&emailAddressFilter=" + encodeURIComponent(getFilterValue('emailAddressFilter'));
	prms=prms + "&entityFilter=" + encodeURIComponent(getFilterValue('entityFilter'));

	$.ajax({
		url : './users/',
		dataType : 'json',
		type : 'GET',
		data: prms,
		success : displayUserList,
		error : displayErrorV2
	});
}

/* Attach Event to UI main menu controls */
$(function() {
	$('#listUser').click(resetUserFilter);
	$('#addUser').click(addUser);
})
