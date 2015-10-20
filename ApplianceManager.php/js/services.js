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

			var availableNodesToolTip = "List of nodes on which current service is available<br>Multi selection is possible";
			var serviceNameToolTip="Enter the service name here<br>Service name is service identifier and can not be changed later";
			var isUserAuthenticationToolTip="If this checkbox is checked, basic authentication will activated on this service";
			var serviceGroupToolTip="Group of users allowed to use this service.<br>Members of this group will be allowed to use this service"; 
			var isPublishedToolTip="If this checkbox is checked, the service is available on publishing nodes.<br>If not, the service can not be used"; 
			var frontEndToolTip="Service's controls are applied to URL<br>called on publishing node begining with this value.<br>Must begin with a /<br>Ex.:<br>/demo";
			var backEndURLToolTip="Backend on which request are proxified<br>Can either be http or https<br>ex.:<br>http://backendserver.private.net/demo"; 
			var identityForwardingToolTip="If this checkbox is checked, HTTP headers containing consumer identity are send to backend"; 
			var backendUserToolTip="If backend system requires basic authentication, enter here the username to use to connect it";
			var backendPasswordToolTip="If backend system requires basic authentication, enter here the username's password to use to connect it";
			var serviceQuotaToolTip="If this checkbox is checked, quotas management on this service are activated<br>Quotas are applied globally (maximum values allowed for all consumers)";
			var perSecToolTip="Maximum number of request per second send to backend";
			var perDayToolTip="Maximum number of request per day send to backend";
			var perMonthToolTip="Maximum number of request per month send to backend";
			var userQuotaToolTip="If this checkbox is checked,<br>quotas management on this service user per user are activated.";
			var logHitToolTip="If this checkbox is checked, hits on this service are recorded in logs<br>Use with care, it may dramatically slow down the system....";
			var additionalConfigurationToolTip="Enter here addtional Apache directive configuration.<br>CAUTION use wih care: it may corrupt entire configuration";
			var serviceOnAllNodesToolTip="If this checkbopx is check, servie will be available on evry nodes, else only on selected list";
			var isAnonymousAllowedToolTip="Even if an authorization is set (group or valid user, resource can be acceded without authentication. In such a case, up to back to process properly depending on the fact the user is connected or not";
			var loginFormUriToolTip="(optional) This URL is used to redirect unauthenticated users on nodes where cookie auth is enabled";

			var editServiceToolTip="Edit this service";
			var deleteServiceToolTip="Delete this service";
			var addServiceTooTip="Add a new servie to the system";
			
			
			var serviceNameFilterPrevVal="";
			var serviceGroupNameFilterPrevVal="";
			var frontEndEndPointFilterPrevVal="";
			var backEndEndPointFilterPrevVal="";

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
						text : item.groupName 
					}));
				});
				$("#groupName option[value=" +currentServiceGroupName + "]").prop("selected", "selected");
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

					$('#content').html(data.replaceAll("{serviceNameToolTip}",serviceNameToolTip)
										   .replaceAll("{isPublishedToolTip}",isPublishedToolTip)
										   .replaceAll("{isUserAuthenticationToolTip}",isUserAuthenticationToolTip)
										   .replaceAll("{loginFormUriToolTip}",loginFormUriToolTip)
										   .replaceAll("{isAnonymousAllowedToolTip}",isAnonymousAllowedToolTip)
										   .replaceAll("{identityForwardingToolTip}",identityForwardingToolTip)
										   .replaceAll("{serviceQuotaToolTip}",serviceQuotaToolTip)
										   .replaceAll("{serviceOnAllNodesToolTip}",serviceOnAllNodesToolTip)
										   .replaceAll("{additionalConfigurationToolTip}",additionalConfigurationToolTip)
										   .replaceAll("{frontEndToolTip}",frontEndToolTip)
										   .replaceAll("{backEndURLToolTip}", backEndURLToolTip)
										   .replaceAll("{backendUserToolTip}", backendUserToolTip)
										   .replaceAll("{backendPasswordToolTip}", backendPasswordToolTip)
										   .replaceAll("{logHitToolTip}", logHitToolTip)
										   .replaceAll("{perSecToolTip}",additionalConfigurationToolTip)
										   .replaceAll("{perDayToolTip}",additionalConfigurationToolTip)
										   .replaceAll("{perMonthToolTip}",additionalConfigurationToolTip)
										   .replaceAll("{userQuotaToolTip}", userQuotaToolTip)
										   .replaceAll("{serviceGroupToolTip}", serviceGroupToolTip)
										   .replaceAll("{availableNodesToolTip}", availableNodesToolTip)
										   .replaceAll("{uri}", service.uri)
										   .replaceAll("{reqSec}", service.reqSec==0?"":service.reqSec)
										   .replaceAll("{reqDay}", service.reqDay==0?"":service.reqDay)
										   .replaceAll("{reqMonth}", service.reqMonth==0?"":service.reqMonth)
										   .replaceAll("{cbUserQuota}", cbUserQuota)
										   .replaceAll("{serviceName}", service.serviceName)
										   .replaceAll("{serviceNameInputType}","hidden")
										   .replaceAll("{cbIsPublished}", cbIsPublished)
										   .replaceAll("{backEndEndPoint}", service.backEndEndPoint)
										   .replaceAll("{frontEndEndPoint}", service.frontEndEndPoint)
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

				if  ($('#onAllNodes').attr('checked')) {
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
				if (!$('#onAllNodes').attr('checked')){
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
				if (!$('#onAllNodes').attr('checked') ){
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
				if ($('#onAllNodes').attr('checked')){
					$('#publishedOnNodes').hide();
				}else{
					$('#publishedOnNodes').show();
					$.getJSON("services/" + encodeURIComponent($('#serviceName').val()) + "/nodes/?order=nodeName", displayServiceNodes).error(displayErrorV2);
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
				}

				q=document.getElementById('quotas');
				document.getElementById("isUserQuotasEnabled").checked=userQuotas;
				checkUserAuth();
				setServiceModified(serviceModified);
				
			}
			
			function addService(){

				$.get( "resources/templates/serviceAdd.php", function( data ) {
					currentService=null;
					currentServiceGroup=null;
					$('#content').html(data.replaceAll("{serviceNameToolTip}",serviceNameToolTip)
										   .replaceAll("{isPublishedToolTip}",isPublishedToolTip)
										   .replaceAll("{isUserAuthenticationToolTip}",isUserAuthenticationToolTip)
										   .replaceAll("{loginFormUriToolTip}",loginFormUriToolTip)
										   .replaceAll("{isAnonymousAllowedToolTip}",isAnonymousAllowedToolTip)
										   .replaceAll("{identityForwardingToolTip}",identityForwardingToolTip)
										   .replaceAll("{serviceQuotaToolTip}",serviceQuotaToolTip)
										   .replaceAll("{serviceOnAllNodesToolTip}",serviceOnAllNodesToolTip)
										   .replaceAll("{additionalConfigurationToolTip}",additionalConfigurationToolTip)
										   .replaceAll("{frontEndToolTip}",frontEndToolTip)
										   .replaceAll("{backEndURLToolTip}", backEndURLToolTip)
										   .replaceAll("{backendUserToolTip}", backendUserToolTip)
										   .replaceAll("{backendPasswordToolTip}", backendPasswordToolTip)
										   .replaceAll("{logHitToolTip}", logHitToolTip)
										   .replaceAll("{perSecToolTip}",additionalConfigurationToolTip)
										   .replaceAll("{perDayToolTip}",additionalConfigurationToolTip)
										   .replaceAll("{perMonthToolTip}",additionalConfigurationToolTip)
										   .replaceAll("{userQuotaToolTip}", userQuotaToolTip)
										   .replaceAll("{serviceGroupToolTip}", serviceGroupToolTip)
										   .replaceAll("{availableNodesToolTip}", availableNodesToolTip)
										   .replaceAll("{uri}", "")
										   .replaceAll("{reqSec}", "")
										   .replaceAll("{reqDay}", "")
										   .replaceAll("{reqMonth}", "")
										   .replaceAll("{cbUserQuota}", "")
										   .replaceAll("{serviceName}", "")
										   .replaceAll("{serviceNameInputType}","text")
										   .replaceAll("{cbIsPublished}", "checked")
										   .replaceAll("{backEndEndPoint}", "http://")
										   .replaceAll("{frontEndEndPoint}", "")
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
					if (item.published){
						$("#serviceNodesList option[value=" +item.node.nodeName + "]").prop("selected", "selected");
					}
				});
			}
			function displayServiceList(serviceList){
				
				
				hideWait();
				$.get( "resources/templates/serviceList.html", function( data ) {
						
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
															 .replaceAll("{isPublishedToolTip}", isPublishedToolTip)
															 .replaceAll("{i}", i)
															 .replaceAll("{servicelist[i].cbpublishedcheck}", cbPublishedCheck)
															 .replaceAll("{serviceList[i].groupName}", serviceList[i].groupName)
															 .replaceAll("{serviceList[i].frontEndEndPoint}", serviceList[i].frontEndEndPoint)
															 .replaceAll("{serviceList[i].backEndEndPoint}", serviceList[i].backEndEndPoint)
															 .replaceAll("{editServiceToolTip}", editServiceToolTip)
															 .replaceAll("{serviceList[i].uri}", serviceList[i].uri)
															 .replaceAll("{deleteServiceToolTip}", deleteServiceToolTip);
							table.appendChild(newRow);
							edit=document.getElementById("btnEdit");
							del=document.getElementById("btnDelete");
							if (serviceList[i].serviceName.startsWith("ApplianceManagerAdmin")){
								del.remove();
							}else{
								del.removeAttribute("id");
							}
							edit.removeAttribute("id");
						}
						setServiceModified(false);
						/* make the table scrollable with a fixed header */
						$("table.scroll").createScrollableTable({
							width: '800px',
							height: '350px',
							border: '0px'
						});
						touchScroll("servicesList_body_wrap");
				});




			
			}

			
			function deleteService(serviceURI, serviceName){
				
				
				if (confirm("Are you sure to want to remove service " + serviceName + "?")){
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
				showServices();
			}
			
			function showServices(){
				prms="order=serviceName";
				prms=prms + "&serviceNameFilter=" + encodeURIComponent(getFilterValue('serviceNameFilter'));
				prms=prms + "&groupNameFilter=" + encodeURIComponent(getFilterValue('serviceGroupNameFilter'));
				prms=prms + "&frontEndEndPointFilter=" + encodeURIComponent(getFilterValue('frontEndEndPointFilter'));
				prms=prms + "&backEndEndPointFilter=" + encodeURIComponent(getFilterValue('backEndEndPointFilter'));

				$.ajax({
					url : './services/',
					dataType : 'json',
					type : 'GET',
					data: prms,
					success : displayServiceList,
					error : displayErrorV2
				});
			}

			
			
			
			
//Event 			
			$(
					function (){
						$('#listService').click(resetServiceFilter);
						$('#addService').click(addService);
					}
				);
