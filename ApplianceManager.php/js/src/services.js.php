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
 * File Name   : ApplianceManager/ApplianceManager.php/js/services.js
 *
 * Created     : 2012-02
 * Authors     : Benoit HERARD <benoit.herard(at)orange.com>
 *
 * Description :
 *      AJAX MAnagement for services
 *--------------------------------------------------------
 * History     :
 * 1.0.0 - 2012-10-01 : Release of the file
*/

			var currentService;
			var currentServiceGroup;
			var serviceModified;
			var nodesLoaded=false;

			var serviceNameFilterPrevVal="";
			var serviceGroupNameFilterPrevVal="";
			var frontEndEndPointFilterPrevVal="";
			var backEndEndPointFilterPrevVal="";
			var nodeNameFilterPrevVal="";

			function setServiceModified(isModified){
				serviceModified=isModified;
				if (isModified){
					setActionButtonEnabled('saveService',true);
				}else{
					setActionButtonEnabled('saveService',false);
				}
				if ($("#additionalConfiguration").val() != ""){
					$("#warnAdditionalConfig").show();
				}else{
					$("#warnAdditionalConfig").hide();
				}

			}
			function populateNodeListFilter(nodeList){
				defaultLabel='<?php echo Localization::getJSString("service.label.chooseNode")?>';
				$('#nodeNameFilter').append($('<option>', { 
					value: "",
					text : defaultLabel 
				}));
				$.each(nodeList, function (i, item) {
					$('#nodeNameFilter').append($('<option>', { 
						value: item.nodeName,
						text : item.nodeDescription!=""?item.nodeDescription:item.nodeName 
					}));
				});
				if (nodeNameFilterPrevVal != ""){
					$("#nodeNameFilter option[value=" + nodeNameFilterPrevVal + "]").prop("selected", "selected");
				}
			}
			function startLoadNodes(){
				$.getJSON("nodes", populateNodeListFilter).error(displayErrorV2);
			}

			function startEditService(serviceURI){
				$.getJSON(serviceURI, editService).error(displayErrorV2);
			}
			
			function startPopulateGroups(){
				$.getJSON("groups/", populateGroups).error(displayErrorV2);
			}
			
			function populateGroups(groupList){
				$.each(groupList, function (i, item) {
					$('#groupName').append($('<option>', { 
						value: item.groupName,
						text : item.description!=""?item.description:item.groupName 
					}));
				});
				if (currentServiceGroupName != ""){
					$("#groupName option[value=" + currentServiceGroupName + "]").prop("selected", "selected");
				}
			}
			
			function updateService(serviceURI){
				saveOrUpdateService('PUT');
			}

			function checkUserAuth(){
				if (document.getElementById("isUserAuthenticationEnabled").checked){
					 $('#allowAnonymous').show();
					 $('#group').show();
					 $('#idForwarding').show();
					 $('#userQuota').show();
					 $('#loginForm').show();
					 if (document.getElementById("isAnonymousAllowed").checked){
						$('#isIdentityForwardingEnabled').prop("checked", true);
						$('#isIdentityForwardingEnabled').prop("disabled", true);
					 }else{
						$('#isIdentityForwardingEnabled').prop("disabled", false);
					 }
						 
				}else{
					 $('#loginForm').hide();
					 $('#allowAnonymous').hide();
					 $('#group').hide();
					 $('#idForwarding').hide();
					 $('#userQuota').hide();
				}
				setServiceModified(true);
			}
			
			function editService(service){

				$.get( "resources/templates/serviceEdit.php", function( data ) {
					nodesLoaded=false;
					currentService=service;
					currentServiceGroup=service.groupUri;
					currentServiceGroupName=service.groupName;
					cbIsPublished="checked";
					cbIsUserAuthenticationEnabled="checked";
					cbIsAnonymousAllowed="";
					cbIsHitLoggingEnabled="";
					cbOnAllNodes="checked";
					cbIdentFwd="";
					cbUserQuota="";

					if (service.isUserQuotasEnabled==1){
						cbUserQuota="checked";
					}
					if (service.isIdentityForwardingEnabled==1){
						cbIdentFwd="checked";
					}
					if (service.isPublished==0){
						cbIsPublished="";
					}
					if (service.isUserAuthenticationEnabled==0){
						cbIsUserAuthenticationEnabled="";
					}
					if (service.isHitLoggingEnabled==1){
						cbIsHitLoggingEnabled="checked";
					}
					if (service.isAnonymousAllowed==1){
						cbIsAnonymousAllowed="checked";
					}
					if (service.onAllNodes==0){
						cbOnAllNodes="";
					}

					$('#content').html(data.replaceAll("{uri}", service.uri)
										   .replaceAll("{reqSec}", service.reqSec==0?"":service.reqSec)
										   .replaceAll("{reqDay}", service.reqDay==0?"":service.reqDay)
										   .replaceAll("{reqMonth}", service.reqMonth==0?"":service.reqMonth)
										   .replaceAll("{cbUserQuota}", cbUserQuota)
										   .replaceAll("{serviceName}", service.serviceName)
										   .replaceAll("{serviceNameInputType}","hidden")
										   .replaceAll("{cbIsPublished}", cbIsPublished)
										   .replaceAll("{backEndEndPointValue}", service.backEndEndPoint)
										   .replaceAll("{frontEndEndPointValue}", service.frontEndEndPoint)
										   .replaceAll("{cbIsUserAuthenticationEnabled}", cbIsUserAuthenticationEnabled)
										   .replaceAll("{loginFormUri}", service.loginFormUri)
										   .replaceAll("{cbIsAnonymousAllowed}", cbIsAnonymousAllowed)
										   .replaceAll("{cbIdentFwd}", cbIdentFwd)
										   .replaceAll("{backEndUsername}", service.backEndUsername)
										   .replaceAll("{backEndPassword}", service.backEndPassword)
										   .replaceAll("{cbOnAllNodes}", cbOnAllNodes)
										   .replaceAll("{cbIsHitLoggingEnabled}", cbIsHitLoggingEnabled)
										   .replaceAll("{additionalConfiguration}", service.additionalConfiguration==null?"":service.additionalConfiguration)
					);
						

					$(function() {
						$( "#tabs" ).tabs();
					});
					
					document.getElementById("isGlobalQuotasEnabled").checked=!(service.isGlobalQuotasEnabled==0);
					setQuotasVisibility();
					setNodesVisiblility();
					startPopulateGroups();
					checkUserAuth();
					setServiceModified(false);
					$("#mainForm").height($("#tabs").height()+10);
					$("#mainForm").click(function(){
							$("#mainForm").height($("#tabs").height()+10);
					});
				});
			}

			
			function saveOrUpdateService(method){
				isUserAuthenticationEnabled="isUserAuthenticationEnabled=0";
				isHitLoggingEnabled="isHitLoggingEnabled=0";
				isUserQuotasEnabled="isUserQuotasEnabled=0";
				isGlobalQuotasEnabled="isGlobalQuotasEnabled=0";
				isIdentityForwardingEnabled="isIdentityForwardingEnabled=0";
				isAnonymousAllowed="isAnonymousAllowed=0";
				isPublished="isPublished=1";
				serviceName="serviceName=" + encodeURIComponent(document.getElementById("serviceName").value);
				frontEndEndPoint="frontEndEndPoint=" + encodeURIComponent(document.getElementById("frontEndEndPoint").value);
				backEndEndPoint="backEndEndPoint=" + encodeURIComponent(document.getElementById("backEndEndPoint").value);
				backEndUsername="backEndUsername=" + encodeURIComponent(document.getElementById("backEndUsername").value);
				backEndPassword="backEndPassword=" + encodeURIComponent(document.getElementById("backEndPassword").value);
				groupName="groupName=" + encodeURIComponent(document.getElementById("groupName").value);
				additionalConfiguration="additionalConfiguration=" + encodeURIComponent(document.getElementById("additionalConfiguration").value);

				loginFormUri="loginFormUri="+ encodeURIComponent(document.getElementById("loginFormUri").value);

				if  (document.getElementById('onAllNodes').checked) {
					onAllNodes="onAllNodes=1";
				}else{
					onAllNodes="onAllNodes=0";
				}
				if (document.getElementById("isGlobalQuotasEnabled").checked){
					isGlobalQuotasEnabled="isGlobalQuotasEnabled=1";
					reqSec="reqSec=" + document.getElementById("reqSec").value;
					reqDay="reqDay=" + document.getElementById("reqDay").value;
					reqMonth="reqMonth=" + document.getElementById("reqMonth").value;
				}
				if (document.getElementById("isUserQuotasEnabled").checked){
					isUserQuotasEnabled="isUserQuotasEnabled=1";
				}
				if (!document.getElementById("isPublished").checked){
					isPublished="isPublished=0";
				}
				if (document.getElementById("isIdentityForwardingEnabled").checked){
					isIdentityForwardingEnabled="isIdentityForwardingEnabled=1";
				}
				if (document.getElementById("isUserAuthenticationEnabled").checked){
					isUserAuthenticationEnabled="isUserAuthenticationEnabled=1";
				}else{
					isUserQuotasEnabled="isUserQuotasEnabled=0";
					isIdentityForwardingEnabled="isIdentityForwardingEnabled=0";
				}
				if (document.getElementById("isHitLoggingEnabled").checked){
					isHitLoggingEnabled="isHitLoggingEnabled=1";
				}
				if (document.getElementById("isAnonymousAllowed").checked){
					isAnonymousAllowed="isAnonymousAllowed=1";
				}
				postData=isUserQuotasEnabled + 
		  		"&" + isGlobalQuotasEnabled + 
		  		"&" + isIdentityForwardingEnabled + 
		  		"&" + isPublished + 
		  		"&" + frontEndEndPoint + 
		  		"&" + backEndEndPoint +
		  		"&" + backEndUsername +
		  		"&" + backEndPassword +
		  		"&" + groupName +
		  		"&" + isUserAuthenticationEnabled + 
		  		"&" + isHitLoggingEnabled +
		  		"&" + onAllNodes +
		  		"&" + additionalConfiguration +
		  		"&" + isAnonymousAllowed +
		  		"&" + loginFormUri;
				if (document.getElementById("isGlobalQuotasEnabled").checked){
					postData+="&" + reqSec +
					"&" + reqDay +
					"&" + reqMonth;
				}
				if (method=='PUT'){
					businessUrl="services/" + encodeURIComponent(document.getElementById("serviceName").value);
				}else{
					businessUrl="services/";
					postData="serviceName=" +encodeURIComponent(document.getElementById("serviceName").value) + "&" + postData ;
				}
				if (!document.getElementById('onAllNodes').checked){
					postData = postData + "&noApply=";
				}
				showWait();
				$.ajax({
					  url: businessUrl ,
					  dataType: 'json',
					  type:method,
					  data: postData,
					  success: manageNodesList,
					  error: displayErrorV2

					});
			}
			
			function manageNodesList(){
				nodes = document.getElementById('serviceNodesList');
				if (!document.getElementById('onAllNodes').checked ){
					// count selected item to be able to start reload page on last one
					var selectedNodes = new Array();
					selectedCount=0;
					for (i = 0; i < nodes.options.length; i++) {
						if (nodes.options[i].selected) {
							selectedNodes[selectedCount]=nodes.options[i].value
							selectedCount++;
						}
					}
					$.ajax({
						  url: "services/" + encodeURIComponent($('#serviceName').val()) + "/nodes/" ,
						  dataType: 'json',
						  data: JSON.stringify(selectedNodes),
						  contentType: 'application/json; charset=utf-8',
						  type:"POST",
						  success: showServices,
						  error: displayErrorV2

						});
				}else{
					showServices();
				}
			}
			
			function saveNewService(){
				saveOrUpdateService('POST');
			}
			
			
			function setNodesVisiblility(){
				if (document.getElementById('onAllNodes').checked){
					$('#publishedOnNodes').hide();
				}else{
					$('#publishedOnNodes').show();
					if (!nodesLoaded){
						$.getJSON("services/" + encodeURIComponent($('#serviceName').val()) + "/nodes/?order=nodeName", displayServiceNodes).error(displayErrorV2);
					}
				}
			}
			function setQuotasVisibility(){
				
				reqSec="";
				reqDay="";
				reqMonth="";
				userQuotas="";
				if (currentService != null){
					if (document.getElementById("isUserQuotasEnabled")!=null){
						if (document.getElementById("isUserQuotasEnabled").checked){
							currentService.isUserQuotasEnabled=1;
						}else{
							currentService.isUserQuotasEnabled=0;
						}
					}
					reqSec = currentService.reqSec;
					reqDay = currentService.reqDay;
					reqMonth=currentService.reqMonth;
					userQuotas=(currentService.isUserQuotasEnabled!=0);
				}else{
					if (document.getElementById("isUserQuotasEnabled")!=null){
						userQuotas=document.getElementById("isUserQuotasEnabled").checked;
					}
				}
				gc= document.getElementById('isGlobalQuotasEnabled');
				if (gc.checked){
					$("#globalQuotasMonth").show();
					$("#globalQuotasDay").show();
					$("#globalQuotasSec").show();
				}else{
					$("#globalQuotasMonth").hide();
					$("#globalQuotasDay").hide();
					$("#globalQuotasSec").hide();
				}

				q=document.getElementById('quotas');
				document.getElementById("isUserQuotasEnabled").checked=userQuotas;
				checkUserAuth();
				setServiceModified(serviceModified);
				
			}
			
			function addService(){

				$.get( "resources/templates/serviceAdd.php", function( data ) {
					nodesLoaded=false;
					currentService=null;
					currentServiceGroup=null;
					currentServiceGroupName=null;
					$('#content').html(data.replaceAll("{uri}", "")
										   .replaceAll("{reqSec}", "")
										   .replaceAll("{reqDay}", "")
										   .replaceAll("{reqMonth}", "")
										   .replaceAll("{cbUserQuota}", "")
										   .replaceAll("{serviceName}", "")
										   .replaceAll("{serviceNameInputType}","text")
										   .replaceAll("{cbIsPublished}", "checked")
										   .replaceAll("{backEndEndPointValue}", "")
										   .replaceAll("{frontEndEndPointValue}", "")
										   .replaceAll("{cbIsUserAuthenticationEnabled}", "")
										   .replaceAll("{loginFormUri}", "")
										   .replaceAll("{cbIsAnonymousAllowed}", "")
										   .replaceAll("{cbIdentFwd}", "")
										   .replaceAll("{backEndUsername}", "")
										   .replaceAll("{backEndPassword}", "")
										   .replaceAll("{cbOnAllNodes}", "checked")
										   .replaceAll("{cbIsHitLoggingEnabled}", "")
										   .replaceAll("{additionalConfiguration}", "")
					);

					$(function() {
						$( "#tabs" ).tabs();
					});

					startPopulateGroups();
					setQuotasVisibility();
					setNodesVisiblility();
					setServiceModified(false);

					$("#mainForm").height($("#tabs").height()+10);
					$("#mainForm").click(function(){
							$("#mainForm").height($("#tabs").height()+10);
					});
				});

			}

			function handelServiceFilterFormKeypress(e) {
				if (e.keyCode == 13) {
					showServices();
					return false;
				}
			}			
			function displayServiceNodes(nodeList){
				$('#serviceNodesList')
				.find('option')
				.remove()
				.end();
				$.each(nodeList, function (i, item) {
						port="";
						if(item.node.isHTTPS==1){
							nodePrefix="https://";
							if (item.node.port != 443){
								port=":" + item.node.port;
							}
						}else{
							nodePrefix="http://";
							if (item.node.port != 80){
								port=":" + item.node.port;
							}
						}
						optionText=item.node.nodeName +  " (" + nodePrefix + item.node.serverFQDN + port+ ")";

					$('#serviceNodesList').append($('<option>', { 
						value: item.node.nodeName,
						text : optionText 
					}));
					if (item.published != "0"){
						$("#serviceNodesList option[value=" +item.node.nodeName + "]").prop("selected", "selected");
					}
					nodesLoaded=true;
				});
			}
			function displayServiceList(serviceList){
				
				
				hideWait();
				$.get( "resources/templates/serviceList.php", function( data ) {
						
						$( "#content" ).html( data.replaceAll("{serviceList.length}", serviceList.length )
												  .replaceAll("{serviceNameFilterPrevVal}", serviceNameFilterPrevVal )	
												  .replaceAll("{serviceGroupNameFilterPrevVal}", serviceGroupNameFilterPrevVal )
												  .replaceAll("{frontEndEndPointFilterPrevVal}", frontEndEndPointFilterPrevVal )
												  .replaceAll("{backEndEndPointFilterPrevVal}", backEndEndPointFilterPrevVal )
											);	
						table=document.getElementById("data");
						rowPattern=document.getElementById("rowTpl");
						table.removeChild(rowPattern);
						
						for (i=0;i<serviceList.length;i++){
							if (serviceList[i].isPublished==1){
								cbPublishedCheck="checked";
							}else{
								cbPublishedCheck=""
							}

							
							newRow=rowPattern.cloneNode(true);
							newRow.removeAttribute('id');
							newRow.removeAttribute('style');
							newRow.className=newRow.className + " tabular_table_body" +  (i%2);
							newRow.innerHTML=newRow.innerHTML.replaceAll("{serviceList[i].serviceName}", serviceList[i].serviceName)
															 .replaceAll("{i}", i)
															 .replaceAll("{servicelist[i].cbpublishedcheck}", cbPublishedCheck)
															 .replaceAll("{serviceList[i].groupName}", serviceList[i].groupName)
															 .replaceAll("{serviceList[i].frontEndEndPoint}", serviceList[i].frontEndEndPoint)
															 .replaceAll("{serviceList[i].backEndEndPoint}", serviceList[i].backEndEndPoint)
															 .replaceAll("{serviceList[i].uri}", serviceList[i].uri);
															 
							table.appendChild(newRow);

							edit=document.getElementById("btnEdit");
							del=document.getElementById("btnDelete");
							publish=document.getElementById("btnPublish");
							unpublish=document.getElementById("btnUnpublish");

							if (serviceList[i].serviceName.startsWith("ApplianceManagerAdmin")){
								del.remove();
							}else{
								del.removeAttribute("id");
							}
							edit.removeAttribute("id");
							
							
							if (serviceList[i].isPublished == 1){
								publish.remove();
								unpublish.removeAttribute("id");
							}else{
								unpublish.remove();
								publish.removeAttribute("id");
							}
							
						}
						setServiceModified(false);
						startLoadNodes();
				});




			
			}

			
			function deleteService(serviceURI, serviceName){
				
				
				if (confirm("<?php echo Localization::getJSString("service.delete.confirm")?> " + serviceName + "?")){
					showWait();
					$.ajax({
						  url: serviceURI,
						  dataType: 'json',
						  type:'DELETE',
						  //data: data,
						  success: showServices,
						  error: displayErrorV2
						});
				}
				
			}
			
			function  resetServiceFilter(){
				$('#serviceNameFilter').val("");
				$('#serviceGroupNameFilter').val("")
				$('#frontEndEndPointFilter').val("");
				$('#backEndEndPointFilter').val("");
				$('#nodeNameFilter option[value=""]').prop("selected", "selected");
				showServices();
			}
			
			function showServices(){
							
				prms="order=serviceName";
				prms=prms + "&serviceNameFilter=" + encodeURIComponent(getFilterValue('serviceNameFilter'));
				prms=prms + "&groupNameFilter=" + encodeURIComponent(getFilterValue('serviceGroupNameFilter'));
				prms=prms + "&frontEndEndPointFilter=" + encodeURIComponent(getFilterValue('frontEndEndPointFilter'));
				prms=prms + "&backEndEndPointFilter=" + encodeURIComponent(getFilterValue('backEndEndPointFilter'));
				prms=prms + "&nodeNameFilter=" + encodeURIComponent(getFilterValue('nodeNameFilter'));


				$.ajax({
					url : './services/',
					dataType : 'json',
					type : 'GET',
					data: prms,
					success : displayServiceList,
					error : displayErrorV2
				});
			}


			function publishService(serviceURI, state){
				
				
					showWait();
					$.ajax({
						  url: serviceURI,
						  dataType: 'json',
						  type:'PUT',
						  data: "isPublished=" + state,
						  success: showServices,
						  error: displayErrorV2
						});
				
			}
			
			
			
			
//Event 			
			$(
					function (){
						$('#listService').click(resetServiceFilter);
						$('#addService').click(addService);
					}
				);
