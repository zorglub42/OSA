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


var editQuota = "Edit this quota";
var deleteQuota = "Delete this quota";


var userNameFilterPrevVal="";
var firstNameFilterPrevVal="";
var lastNameFilterPrevVal="";
var emailAddressFilterPrevVal="";
var entityFilterPrevVal="";



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

function setUserModified(isModified) {
	userModified = isModified;
	if (isModified) {
		setActionButtonEnabled('saveNew', true);
		setActionButtonEnabled('saveEdit', true);
		setActionButtonEnabled('groupsEdit', false);
		setActionButtonEnabled('quotasEdit', false);
	} else {
		setActionButtonEnabled('saveNew', false);
		setActionButtonEnabled('saveEdit', false);
		setActionButtonEnabled('groupsEdit', true);
		setActionButtonEnabled('quotasEdit', true);
	}
}

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
							   .replaceAll("{extra}", "")
		);
		$('#userEndDate').datepicker();
		setUserModified(false);
	});
}
function saveNewUser() {
	saveOrUpdateUser('POST');
}

function updateUser(userURI) {
	saveOrUpdateUser('PUT');
}

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
	extra = "extra=" + encodeURIComponent(document.getElementById("extra").value);
	try{
		d=Date.parseExact(document.getElementById("userEndDate").value,"<?php echo Localization::getJSString("date.format")?>");
		d.setHours(12);
		endDate = "endDate="
				+ encodeURIComponent(d.format("isoUtcDateTime"));
	}catch (ex){
		endDate="endDate=";
	}
	postData = password + "&" + email + "&" + endDate + "&" + firstName + "&"
			+ lastName + "&" + entity + "&" + extra;
	if (method == 'POST') {
		uri = "users/";
		postData = "userName="
				+ encodeURIComponent(document.getElementById("userName").value) + "&"
				+ postData;
	} else {
		uri = "users/" + encodeURIComponent(document.getElementById("userName").value);
	}
	$.ajax({
		url : uri, // "users/" +
					// encodeURIComponent(document.getElementById("userName").value) ,
					// //+ "?" + postData,
		dataType : 'json',
		type : method,
		data : postData,
		success : startEditCurrentUser,
		error : displayErrorV2
	});

}
function startEditUser(userURI) {
	currentUserUri = userURI;
	$.getJSON(userURI, editUser).error(displayErrorV2);
}
function startEditCurrentUser() {
	startEditUser(currentUserUri);
}

function startDisplayAvailableGroups(userURI) {
	currentUserURI = userURI;
	$.getJSON(userURI + "/groups/available/", displayAvailableGroups).error(
			displayErrorV2);
}

function startDisplayUserGroupsForCurrentUser(group) {
	startDisplayUserGroups(currentUserURI);
}
function startDisplayUserGroups(userURI) {
	currentUserURI = userURI;
	$.getJSON(userURI + "/groups/", displayUserGroups).error(displayErrorV2);
}
function startDisplayUserQuotas(userURI) {
	currentUserURI = userURI;
	$.getJSON(userURI + "/quotas/", displayUserQuotas).error(displayErrorV2);
}
function startDisplayUserQuotasForCurrentUser() {
	startDisplayUserQuotas(currentUserUri);
}

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


function editUser(user) {
	$.get( "resources/templates/userEdit.php", function( data ) {
		userDate = new Date(user.endDate);
		dateFormated = userDate.format("<?php echo Localization::getJSString("date.format")?>");
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
							   .replaceAll("{extra}", user.extra==null?"":user.extra)
							   .replaceAll("{uri}", user.uri)
		);
		$('#userEndDate').datepicker();
		setUserModified(false);
	});

}

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
function handelUserFilterFormKeypress(e) {
	if (e.keyCode == 13) {
		showUsers();
		return false;
	}
}
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

		for (i=0;i<userList.length;i++){
			var d = new Date();
			d.setISO8601(userList[i].endDate);

			
			newRow=rowPattern.cloneNode(true);
			newRow.removeAttribute('id');
			newRow.removeAttribute('style');
			newRow.className=newRow.className + " tabular_table_body" +  (i%2);
			newRow.innerHTML=newRow.innerHTML.replaceAll("{userList[i].userName}", userList[i].userName)
											 .replaceAll("{userList[i].emailAddress}", userList[i].emailAddress)
											 .replaceAll("{userList[i].uri}", userList[i].uri)
											 .replaceAll("{userList[i].endDate}", d.format("<?php echo Localization::getJSString("date.format")?>"));
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
	});


}

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



function  resetUserFilter(){
	$('#userNameFilter').val("");
	$('#firstNameFilter').val("")
	$('#lastNameFilter').val("")
	$('#emailAddressFilter').val("");
	$('#entityFilter').val("");
	showUsers();
}
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

// Event
$(function() {
	$('#listUser').click(resetUserFilter);
	$('#addUser').click(addUser);
})
