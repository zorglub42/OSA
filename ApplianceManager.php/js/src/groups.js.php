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
 * File Name   : ApplianceManager/ApplianceManager.php/js/groups.js
 *
 * Created     : 2012-02
 * Authors     : Benoit HERARD <benoit.herard(at)orange.com>
 *
 * Description :
 *      .../...
 *--------------------------------------------------------
 * History     :
 * 1.0.0 - 2012-10-01 : Release of the file
 * 1.1.0 - 2015-10-14 : HTML with templates
*/

var groupModified;

var groupNameFilterPrevVal="";
var groupDescritpionFilterPrevVal="";
var currentGroup;

/* Enable or disable UI control according to group properties updates */
function  resetGroupFilter(){
	groupNameFilterPrevVal="";
	groupDescritpionFilterPrevVal="";

	$('#groupNameFilter').val("")
	$('#groupDescritpionFilter').val("")
	showGroups();
}

/* Handle key press on group filter form to apply filter when "enter" key
* is pressed */
function handelGroupFilterFormKeypress(e) {
	if (e.keyCode == 13) {
		showGroups();
		return false;
	}
}

/* Enable or disable UI control according to group properties updates */
function setGroupModified(isModified){
	groupModified=isModified;
	if (isModified){
		setActionButtonEnabled('saveGroup',true);
		setActionButtonEnabled('groupMembers',false);
	}else{
		setActionButtonEnabled('saveGroup',false);
		setActionButtonEnabled('groupMembers',true);
	}
}

/* Load group properties and edit */
function startEditGroup(groupURI){
	$.getJSON(groupURI, editGroup).error(displayErrorV2);
}

/* Update group in DB */
function updateGroup(groupURI){
	desc = "description=" + encodeURIComponent(document.getElementById("groupDesc").value);
	$.ajax({
		  url: groupURI + "?" + desc,
		  dataType: 'json',
		  type:'PUT',
		  data: desc,
		  success: showGroups,
		  error: displayErrorV2
		});

}

/* Load group properties template and display */
function editGroup(group){
	currentGroup=group;



	$.get( "resources/templates/groupEdit.php", function( data ) {
			$( "#content" ).html( data.replaceAll("{group.groupName}", group.groupName )
									  .replaceAll("{group.description}", group.description)
									  .replaceAll("{group.uri}", group.uri)
									  .replaceAll("{groupNameInputType}", "hidden")
							    );
			setGroupModified(false);
	});

}

/* Create a new group in DB */
function saveNewGroup(){
	desc = "description=" + encodeURIComponent(document.getElementById("groupDesc").value);
	groupName="groupName=" + encodeURIComponent(document.getElementById("groupName").value);
	$.ajax({
		  url: "groups/?" + desc + "&" + groupName,
		  dataType: 'json',
		  type:'POST',
		  data: desc,
		  success: showGroups,
		  error: displayErrorV2
		});


}

/* Load add new group template and display */
function addGroup(){
	$.get( "resources/templates/groupAdd.php", function( data ) {
			$( "#content" ).html( data.replaceAll("{groupNameInputType}", "text")
									  .replaceAll("{group.groupName}", "")
									  .replaceAll("{group.description}", "")
			);
			setGroupModified(false);
	});

}


/* Load group list template and display */
function displayGroupList(groupList){

	$.get( "resources/templates/groupList.php", function( data ) {

			$( "#content" ).html( data.replaceAll("{groupList.length}", groupList.length )
									  .replaceAll("{groupNameFilterPrevVal}", groupNameFilterPrevVal )
									  .replaceAll("{groupDescritpionFilterPrevVal}", groupDescritpionFilterPrevVal )
								);
			table=document.getElementById("data");
			rowPattern=document.getElementById("rowTpl");
			table.removeChild(rowPattern);


			var groupsNamesListAutoComplete=new Array();
			var groupsDescriptionsListAutoComplete=new Array();


			for (i=0;i<groupList.length;i++){

				addItem(groupsNamesListAutoComplete, groupList[i].groupName, true);
				addItem(groupsDescriptionsListAutoComplete, groupList[i].description, true);

				newRow=rowPattern.cloneNode(true);
				newRow.removeAttribute('id');
				newRow.removeAttribute('style');
				newRow.className=newRow.className + " tabular_table_body" +  (i%2);
				newRow.innerHTML=newRow.innerHTML.replaceAll("{groupList[i].groupName}", groupList[i].groupName)
												 .replaceAll("{groupList[i].description}", groupList[i].description)
												 .replaceAll("{groupList[i].uri}", groupList[i].uri);
				table.appendChild(newRow);
				edit=document.getElementById("btnEdit");
				del=document.getElementById("btnDelete");
				if (groupList[i].groupName == "Admin" || groupList[i].groupName == "valid-user"){
					del.remove();
				}else{
					del.removeAttribute("id");
				}
				edit.removeAttribute("id");
			}
			$( "#groupNameFilter" ).autocomplete({
						source: groupsNamesListAutoComplete,
						minLength: 0
			});
			$( "#groupDescritpionFilter" ).autocomplete({
						source: groupsDescriptionsListAutoComplete,
						minLength: 0
			});

		setGroupModified(false);
	});


}

/* Delete a group from DB */
function deleteGroup(groupURI, groupName){


	if (confirm("<?php echo Localization::getJSString("group.delete.confirm")?> " + groupName + "?")){
		$.ajax({
			  url: groupURI,
			  dataType: 'json',
			  type:'DELETE',
			  //data: data,
			  success: showGroups,
			  error: displayErrorV2
			});
	}

}

/* Search group list and display */
function showGroups(){

	prms="order=groupName";
	prms=prms + "&groupNameFilter=" + encodeURIComponent(getFilterValue('groupNameFilter'));
	prms=prms + "&groupDescritpionFilter=" + encodeURIComponent(getFilterValue('groupDescritpionFilter'));

	$.ajax({
		url : './groups/',
		dataType : 'json',
		type : 'GET',
		data: prms,
		success : displayGroupList,
		error : displayErrorV2
	});

}

/* Load group members template and display */
function displayGroupMembers(userList) {

				$.get( "resources/templates/groupMembers.php", function( data ) {

						$( "#content" ).html( data.replaceAll("{currentGroup.groupName}", currentGroup.groupName )
											);
						table=document.getElementById("data");
						rowPattern=document.getElementById("rowTpl");
						table.removeChild(rowPattern);

						for (i=0;i<userList.length;i++){

							newRow=rowPattern.cloneNode(true);
							newRow.removeAttribute('id');
							newRow.removeAttribute('style');
							newRow.className=newRow.className + " tabular_table_body" +  (i%2);
							newRow.innerHTML=newRow.innerHTML.replaceAll("{userList[i].userName}", userList[i].userName)
															 .replaceAll("{userList[i].firstName}", userList[i].firstName)
															 .replaceAll("{userList[i].lastName}", userList[i].lastName)
															 .replaceAll("{userList[i].emailAddress}", userList[i].emailAddress);
							table.appendChild(newRow);
						}
				});

}

/* Search for group members and display */
function showMembers(){


	$.ajax({
		url : currentGroup.uri + '/members',
		dataType : 'json',
		type : 'GET',
		data: 'order=userName',
		success : displayGroupMembers,
		error : displayErrorV2
	});

}





/* Attach Event to UI main menu controls */
$(
		function (){
			$('#listGroup').click(resetGroupFilter);
			$('#addGroup').click(addGroup);
		}
	);
