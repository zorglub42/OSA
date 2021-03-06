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
<?php
	require_once "../include/Constants.php";
	require_once "../include/Settings.ini.php";
	require_once "../include/Localization.php";
?>

var currentService;
var currentServiceGroup;
var serviceModified;
var nodesLoaded=false;

var serviceNameFilterPrevVal="";
var serviceGroupNameFilterPrevVal="";
var frontEndEndPointFilterPrevVal="";
var backEndEndPointFilterPrevVal="";
var nodeNameFilterPrevVal="";
var logHitsFilterPrevVal="";

var doServiceClone=false;
var serviceHeadersCount;

/* Enable or disable UI control according to service properties updates */
function setServiceModified(isModified){
	serviceModified=isModified;
	if (isModified){
		setActionButtonEnabled('saveService',true);
	}else{
		setActionButtonEnabled('saveService',false);
	}
	if ($("#additionalConfiguration").val() != "" || $("#additionalBackendConnectionConfiguration").val() != ""){
		$("#warnAdditionalConfig").show();
	}else{
		$("#warnAdditionalConfig").hide();
	}

}

/* Populate node list filter field */
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

/* Load nodes and polulate node list filetr field */
function startLoadNodes(){
	$.getJSON("nodes", populateNodeListFilter).error(displayErrorV2);
}

/* Load a service and edit */
function startEditService(serviceURI){
	$.getJSON(serviceURI, editService).error(displayErrorV2);
}


/* Clone a service */
function cloneService(serviceURI){
	doServiceClone=true;
	startEditService(serviceURI);
}
/* Load group list and populate group list field */
function startPopulateGroups(){
	$.getJSON("groups/", populateGroups).error(displayErrorV2);
}

/* Populate list group field */
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


/* Update (and deploy/undeploy) a service */
function updateService(serviceURI){
	if (serviceURI != ''){
		saveOrUpdateService('PUT');
	}else{
		saveOrUpdateService('POST');
	}
}


/* Enable disable UI control accoding to end user authentication status
   (required/not required)*/
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
		 cbForwardIdentClicked();

	}else{
		 $('#loginForm').hide();
		 $('#allowAnonymous').hide();
		 $('#group').hide();
		 $('#idForwarding').hide();
		 $('#idMapping').hide();
		 $('#idAttributesMapping').hide();
		 $('#userQuota').hide();
	}
	setServiceModified(true);
}

/* Hide/show headers mapping UI controls according to identity
   forwarding status */
function cbForwardIdentClicked(){
		if (!document.getElementById("isIdentityForwardingEnabled").checked){
			$("#idMapping").hide();
			$("#idAttributesMapping").hide();
		}else{
			$("#idMapping").show();
			$("#idAttributesMapping").show();
		}
		setServiceModified(true)
}

/* Enable/disable header name status according to corresponding
   checkbox status */
function cbHeaderClicked(header){
		$("#" + header + "Header").prop('disabled',!document.getElementById("cb" + header + "Header").checked);
		setServiceModified(true);
}

function deleteHeaderMapping(propNum, propName){
	if (confirm("<?php echo Localization::getJSString("user.property.delete.confirm")?> " + propName + "?")) {
		prop=document.getElementById('property_' + propNum);
		prop.parentNode	.removeChild(prop);
		setServiceModified(true);
	}

}
function addHeaderMapping(){
	if ($("#propertyName_new").val() != "" && $("#propertyHeader_new").val() != ""){
		for (i=0;i<serviceHeadersCount;i++){
			if ($("#propertyHeader_" + i).val() == $("#propertyHeader_new").val()){
				alert("<?php echo Localization::getJSString("user.property.mapping.exists")?>");
				return false;
			}
		}
		table=document.getElementById("data");

		rowPattern=document.getElementById("rowTpl");

		newRow=rowPattern.cloneNode(true);
		newRow.removeAttribute('id');
		newRow.setAttribute('id', 'property_' + serviceHeadersCount);
		newRow.removeAttribute('style');
		newRow.className=newRow.className + " tabular_table_body" +  ((serviceHeadersCount)%2);
		newRow.innerHTML=newRow.innerHTML.replaceAll("{i}", serviceHeadersCount)
										.replaceAll("{headers[i].name}", $("#propertyName_new").val())
										.replaceAll("{headers[i].header}", $("#propertyHeader_new").val());

		table.insertBefore(newRow, document.getElementById('newProp'));
		$("#propertyHeader_new").val("");
		$("#propertyName_new").val("");
		serviceHeadersCount++;

		setServiceModified(true);

	}
}

/* Load edit service template and display */
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

		if (doServiceClone){
			serviceName="";
			uri="";
			serviceNameInputType="text";
		}else{
			serviceName=service.serviceName;
			uri=service.uri;
			serviceNameInputType="hidden";
		}

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

		$('#content').html(data.replaceAll("{uri}", uri)
							   .replaceAll("{reqSec}", service.reqSec==0?"":service.reqSec)
							   .replaceAll("{reqDay}", service.reqDay==0?"":service.reqDay)
							   .replaceAll("{reqMonth}", service.reqMonth==0?"":service.reqMonth)
							   .replaceAll("{cbUserQuota}", cbUserQuota)
							   .replaceAll("{serviceName}", serviceName)
							   .replaceAll("{serviceNameInputType}", serviceNameInputType)
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
							   .replaceAll("{additionalBackendConnectionConfiguration}", service.additionalBackendConnectionConfiguration==null?"":service.additionalBackendConnectionConfiguration)
		);


		$(function() {
			$( "#tabs" ).tabs();
		});

		document.getElementById("isGlobalQuotasEnabled").checked=!(service.isGlobalQuotasEnabled==0);
		setQuotasVisibility();
		setNodesVisiblility();
		startPopulateGroups();
		checkUserAuth();
		$("#mainForm").height($("#tabs").height()+10);
		$("#mainForm").click(function(){
				$("#mainForm").height($("#tabs").height()+10);
		});
		cbForwardIdentClicked();
		$.getJSON( service.uri + "/headers-mapping",
			function (data){
				for (i=0;i<data.length;i++){
					if (data[i].extendedAttribute == 0){
						$("#cb" + data[i].userProperty + "Header").prop("checked",true);
						$("#" + data[i].userProperty + "Header").val(data[i].headerName);
						cbHeaderClicked(data[i].userProperty);
					}else{
						table=document.getElementById("data");

						rowPattern=document.getElementById("rowTpl");

						newRow=rowPattern.cloneNode(true);
						newRow.removeAttribute('id');
						newRow.setAttribute('id', 'property_' + i);
						newRow.removeAttribute('style');
						newRow.className=newRow.className + " tabular_table_body" +  (i%2);
						newRow.innerHTML=newRow.innerHTML.replaceAll("{i}", i)
														.replaceAll("{headers[i].name}", data[i].userProperty)
														.replaceAll("{headers[i].header}", data[i].headerName);

						table.insertBefore(newRow, document.getElementById('newProp'));

					}
				}
				serviceHeadersCount=data.length;
				serviceHeadersCount=i;
				setServiceModified(false);
			}
		);

	});
}


/* Save (create) or update a service
	 once done, store headers mapping if required
	 method: PUT=update, POST=create */
function saveOrUpdateService(method){
	if (checkNodesList()){
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
		additionalBackendConnectionConfiguration="additionalBackendConnectionConfiguration=" + encodeURIComponent(document.getElementById("additionalBackendConnectionConfiguration").value);

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
		}else{
			isGlobalQuotasEnabled="isGlobalQuotasEnabled=0"
		}
		if (document.getElementById("isUserQuotasEnabled").checked){
			isUserQuotasEnabled="isUserQuotasEnabled=1";
		}else{
			isUserQuotasEnabled="isUserQuotasEnabled=0";
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
		"&" + additionalBackendConnectionConfiguration +
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
		if (!document.getElementById('onAllNodes').checked || document.getElementById("isIdentityForwardingEnabled").checked){
			postData = postData + "&noApply=1";
		}
		showWait();
		$.ajax({
			  url: businessUrl ,
			  dataType: 'json',
			  type:method,
			  data: postData,
			  success: setHeadersMappings,
			  error: displayErrorV2

		});
	}
}

/* Save headers mapping if required and start nodes managements
	 also apply apache conf
	 		if headers mapping defined and "on all nodes"
	 		(i.e no publishing nodes to save)
*/
function setHeadersMappings(){
	if (document.getElementById("isIdentityForwardingEnabled").checked){
		var mapping=Array()
		var hdrNum=0

		<?php for ($i=0;$i<count($userProperties);$i++){?>
			if (document.getElementById("cb<?php echo $userProperties[$i]?>Header").checked){
				m=new Object()
				mapping[hdrNum]=m
				mapping[hdrNum].userProperty="<?php echo $userProperties[$i]?>"
				mapping[hdrNum].headerName=$("#<?php echo $userProperties[$i]?>Header").val()
				mapping[hdrNum].extendedAttribute=0;
				hdrNum++
			}
		<?php }?>
		for (i=0;i<serviceHeadersCount;i++){
			if ($("#propertyName_" + i).val() !== undefined){
				m=new Object()
				mapping[hdrNum]=m
				mapping[hdrNum].userProperty=$("#propertyName_" + i).val()
				mapping[hdrNum].headerName=$("#propertyHeader_" + i).val()
				mapping[hdrNum].extendedAttribute=1;
				hdrNum++
			}
		}
		data={
			"noApply": document.getElementById("onAllNodes").checked?0:1,
			"mapping": mapping
		}
		$.ajax({
			  url: "services/" + encodeURIComponent($("#serviceName").val()) + "/headers-mapping" ,
			  dataType: 'json',
			  type:'POST',
			  contentType: 'application/json',
			  data: JSON.stringify(data),
			  success: manageNodesList,
			  error: displayErrorV2

		});
	}else{
		manageNodesList();
	}
}

/* Save publishing node and apply apache conf (if any)
	 and switch to services list */
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
		postData={ "nodes": selectedNodes};
		$.ajax({
			  url: "services/" + encodeURIComponent($('#serviceName').val()) + "/nodes/" ,
			  dataType: 'json',
			  data: JSON.stringify(postData),
			  contentType: 'application/json; charset=utf-8',
			  type:"POST",
			  success: showServices,
			  error: displayErrorV2

			});
	}else{
		showServices();
	}
}

/* Save (create) a new service */
function saveNewService(){
	saveOrUpdateService('POST');
}

/* Set Node list visibility according to "on all nodes" checkbox status
	 and polulate nodes list if visible */
function setNodesVisiblility(){
	if (document.getElementById('onAllNodes').checked){
		$('#publishedOnNodes').hide();
	}else{
		$('#publishedOnNodes').show();
		if (!nodesLoaded){
			$.getJSON("services/" + encodeURIComponent(currentService.serviceName) + "/nodes/?order=nodeName", displayServiceNodes).error(displayErrorV2);
		}
	}
}

/* Set quotas values visibility according to "quota enabled" checkbox status
   and populate quotas values fields with currentService values */
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

/* Load add service template and display */
function addService(){
	$.get( "resources/templates/serviceAdd.php", function( data ) {
		currentService={
			serviceName: "",
			uri: ""
		}
		nodesLoaded=false;
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
							   .replaceAll("{cbOnAllNodes}", "")
							   .replaceAll("{cbIsHitLoggingEnabled}", "")
							   .replaceAll("{additionalConfiguration}", "")
							   .replaceAll("{additionalBackendConnectionConfiguration}", "")
		);

		$(function() {
			$( "#tabs" ).tabs();
		});

		startPopulateGroups();
		setQuotasVisibility();
		setNodesVisiblility();

		$("#mainForm").height($("#tabs").height()+10);
		$("#mainForm").click(function(){
				$("#mainForm").height($("#tabs").height()+10);
		});

		<?php foreach ($userProperties as $property){?>
			$("#cb<?php echo $property?>Header").prop("checked", true);
			$("#<?php echo $property?>Header").val('<?php echo $defaultHeadersName[$property]?>');
		<?php }?>

		setServiceModified(false);
	});

}

/* Handle key press on services filter form to apply filter when "enter" key
* is pressed */
function handelServiceFilterFormKeypress(e) {
	if (e.keyCode == 13) {
		showServices();
		return false;
	}
}

/* Check nodes publication configuration consistency
	 return true if 'on all node' or if at least on node is selected
 */
function checkNodesList(){
	if  (document.getElementById('onAllNodes').checked) {
		return true;
	}else{
		nodes = document.getElementById('serviceNodesList');
		// count selected item to be able to start reload page on last one
		var selectedNodes = new Array();
		selectedCount=0;
		for (i = 0; i < nodes.options.length; i++) {
			if (nodes.options[i].selected) {
				selectedNodes[selectedCount]=nodes.options[i].value
				selectedCount++;
			}
		}
		if (selectedCount == 0 ){
			var index = $('#tabs a[href="#tabs-nodes"]').parent().index();
			$('#tabs').tabs('select', index);
			alert("<?php echo Localization::getJSString("service.submit.alert.nodeList")?>");
			return false;
		}else{
			return true;
		}
	}
}

/* Populate publishing node list
	 and select nodes where current service is published */
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

/* Load service list template and display */
function displayServiceList(serviceList){
	hideWait();
	$.get( "resources/templates/serviceList.php", function( data ) {

			logHitFilterAny="";
			logHitFilterEnabled="";
			logHitFilterDisabled="";
			if (logHitsFilterPrevVal == ""){
				logHitFilterAny="checked";
			}else if (logHitsFilterPrevVal == "1"){
				logHitFilterEnabled="checked";
			}else{
				logHitFilterDisabled="checked";
			}


			$( "#content" ).html( data.replaceAll("{serviceList.length}", serviceList.length )
									  .replaceAll("{serviceNameFilterPrevVal}", serviceNameFilterPrevVal )
									  .replaceAll("{serviceGroupNameFilterPrevVal}", serviceGroupNameFilterPrevVal )
									  .replaceAll("{frontEndEndPointFilterPrevVal}", frontEndEndPointFilterPrevVal )
									  .replaceAll("{backEndEndPointFilterPrevVal}", backEndEndPointFilterPrevVal )
									  .replaceAll("{logHitFilterAny}", logHitFilterAny )
									  .replaceAll("{logHitFilterEnabled}", logHitFilterEnabled )
									  .replaceAll("{logHitFilterDisabled}", logHitFilterDisabled )
								);
			table=document.getElementById("data");
			rowPattern=document.getElementById("rowTpl");
			table.removeChild(rowPattern);

			var servicesListAutoComplete=new Array();
			var groupsListAutoComplete=new Array();
			var aliasesListAutoComplete=new Array();
			var backendsListAutoComplete=new Array();

			for (i=0;i<serviceList.length;i++){
				if (serviceList[i].isPublished==1){
					cbPublishedCheck="checked";
				}else{
					cbPublishedCheck=""
				}

				addItem(servicesListAutoComplete, serviceList[i].serviceName);
				addItem(groupsListAutoComplete, serviceList[i].groupName, true);
				addItem(aliasesListAutoComplete, serviceList[i].frontEndEndPoint, true);
				addItem(backendsListAutoComplete, serviceList[i].backEndEndPoint, true);

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
			$( "#serviceGroupNameFilter" ).autocomplete({
							source: groupsListAutoComplete,
							minLength: 0
			});

			$("#serviceNameFilter").autocomplete({
							source: servicesListAutoComplete,
							minLength: 0
			});
			$("#frontEndEndPointFilter").autocomplete({
							source: aliasesListAutoComplete,
							minLength: 0
			});
			$("#backEndEndPointFilter").autocomplete({
							source: backendsListAutoComplete,
							minLength: 0
			});
	});
}

/* Remove a service (and undeploy) */
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

/* Reset service filter fields and apply search */
function  resetServiceFilter(){
	$('#serviceNameFilter').val("");
	$('#serviceGroupNameFilter').val("")
	$('#frontEndEndPointFilter').val("");
	$('#backEndEndPointFilter').val("");
	$('#nodeNameFilter option[value=""]').prop("selected", "selected");
	showServices();
}


/* Load services (search) and display */
function showServices(){
	doServiceClone=false;

	if ($('input[name=optIsLogHitsEnabled]:checked').val() != undefined){
		logHitsFilterPrevVal=$('input[name=optIsLogHitsEnabled]:checked').val();
	}


	prms="order=serviceName";
	prms=prms + "&serviceNameFilter=" + encodeURIComponent(getFilterValue('serviceNameFilter'));
	prms=prms + "&groupNameFilter=" + encodeURIComponent(getFilterValue('serviceGroupNameFilter'));
	prms=prms + "&frontEndEndPointFilter=" + encodeURIComponent(getFilterValue('frontEndEndPointFilter'));
	prms=prms + "&backEndEndPointFilter=" + encodeURIComponent(getFilterValue('backEndEndPointFilter'));
	prms=prms + "&nodeNameFilter=" + encodeURIComponent(getFilterValue('nodeNameFilter'));
	if (logHitsFilterPrevVal != ""){
		prms=prms + "&isHitLoggingEnabledFilter=" + logHitsFilterPrevVal;
	}


	$.ajax({
		url : './services/',
		dataType : 'json',
		type : 'GET',
		data: prms,
		success : displayServiceList,
		error : displayErrorV2
	});
}



/* Publish/Unpublish a service */
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



/* Attach Event to UI main menu controls */
$(
		function (){
			$('#listService').click(resetServiceFilter);
			$('#addService').click(addService);
		}
	);
