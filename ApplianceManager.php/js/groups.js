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
			var groupDescToolTip="Enter here the group description\nIt is only used for information purpose";
			var groupNameToolTip="Enter the group name here.\nDon't use special chars.\nGroup name is used to manage authorization on services.\nGroup name is group identifier and can not be modified.";
			var editGroupToolTip="Edit this group";
			var deleteGroupToolTip="Delete this group";
			var addGroupToolTip="Add a new group to the system";		
					
			var groupNameFilterPrevVal="";
			var groupDescritpionFilterPrevVal="";
			var currentGroup;
					
			function  resetGroupFilter(){
				groupNameFilterPrevVal="";
				groupDescritpionFilterPrevVal="";
				
				$('#groupNameFilter').val("")
				$('#groupDescritpionFilter').val("")
				showGroups();
			}
					
			function handelGroupFilterFormKeypress(e) {
				if (e.keyCode == 13) {
					showGroups();
					return false;
				}
			}					
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


			function startEditGroup(groupURI){
				$.getJSON(groupURI, editGroup).error(displayErrorV2);
			}
			
			
						
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
			function editGroup(group){
				currentGroup=group;



				$.get( "resources/templates/groupEdit.html", function( data ) {
						$( "#content" ).html( data.replaceAll("{group.groupName}", group.groupName )
												  .replaceAll("{group.description}", group.description)
												  .replaceAll("{group.uri}", group.uri)
												  .replaceAll("{groupDescToolTip}", groupDescToolTip)
										    );
						setGroupModified(false);
				});

			}

			
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
			
			function addGroup(){
				$.get( "resources/templates/groupAdd.html", function( data ) {
						$( "#content" ).html( data.replaceAll("{groupDescToolTip}", groupDescToolTip)
												  .replaceAll("{groupNameToolTip}",groupNameToolTip)
										    );
						setGroupModified(false);
				});

			}

			
			
			function displayGroupList(groupList){
				
				$.get( "resources/templates/groupList.html", function( data ) {
						
						$( "#content" ).html( data.replaceAll("{groupList.length}", groupList.length )
												  .replaceAll("{groupNameFilterPrevVal}", groupNameFilterPrevVal )	
												  .replaceAll("{groupDescritpionFilterPrevVal}", groupDescritpionFilterPrevVal )
												  .replaceAll("{editGroupToolTip}", editGroupToolTip )
												  .replaceAll("{deleteGroupToolTip}", groupDescritpionFilterPrevVal )
											);	
						table=document.getElementById("data");
						rowPattern=document.getElementById("rowTpl");
						table.removeChild(rowPattern);
						
						for (i=0;i<groupList.length;i++){
							
							newRow=rowPattern.cloneNode(true);
							newRow.removeAttribute('id');
							newRow.removeAttribute('style');
							newRow.className=newRow.className + " tabular_table_body" +  (i%2);
							newRow.innerHTML=newRow.innerHTML.replaceAll("{groupList[i].groupName}", groupList[i].groupName)
															 .replaceAll("{groupList[i].description}", groupList[i].description)
															 .replaceAll("{groupList[i].uri}", groupList[i].uri);
							table.appendChild(newRow);
							edit=document.getElementById("bntEdit");
							del=document.getElementById("btnDelete");
							if (groupList[i].groupName == "Admin" || groupList[i].groupName == "valid-user"){
								del.remove();
							}else{
								del.removeAttribute("id");
							}
							edit.removeAttribute("id");
						}
						setGroupModified(false);
						/* make the table scrollable with a fixed header */
						$("table.scroll").createScrollableTable({
							width: '800px',
							height: '350px',
							border: '0px'
						});
						touchScroll("groupsList_body_wrap");
				});

				
			}

			
			function deleteGroup(groupURI, groupName){
				
				
				if (confirm("Are you sure to want to remove group " + groupName + "?")){
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
			
			
function displayGroupMembers(userList) {

				$.get( "resources/templates/groupMembers.html", function( data ) {
						
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

			
			
			
			
//Event 			
			$(
					function (){
						$('#listGroup').click(resetGroupFilter);
						$('#addGroup').click(addGroup);
					}
				);
