/*--------------------------------------------------------
 * Module Name : ApplianceManager
 * Version : 1.0.0
 *
 * Software Name : OpenNodesAccess
 * Version : 1.0
 *
 * Copyright (c) 2011 – 2014 Orange
 * This software is distributed under the Apache 2 license
 * <http://www.apache.org/licenses/LICENSE-2.0.html>
 *
 *--------------------------------------------------------
 * File Name   : ApplianceManager/ApplianceManager.php/js/nodes.js
 *
 * Created     : 2012-02
 * Authors     : Benoit HERARD <benoit.herard(at)orange.com>
 *
 * Description :
 *      AJAX MAnagement for nodes
 *--------------------------------------------------------
 * History     :
 * 1.0.0 - 2012-10-01 : Release of the file
*/

var currentNode;
var nodeModified;
var removeSSLSetting=false;
var removeCASSLSetting=false;

var nodeNameToolTip="Enter the node name here<br>Node name is node identifier and can not be changed later";
var localIPToolTip="Enter the IP address on witch the node is binded (* for any available)";
var portToolTip="Enter the port on witch the node listen";
var serverFQDNToolTip="Enter a fully qulified node name (like mynode.mydomain.com)";
var nodeDescriptionToolTip="Enter the node description";
var isHTTPSToolTip="Check this box if the node should use HTTPS instead of HTTP";
var isBasicAuthEnabledToolTip="Check this box if the node should support basic authentication mode";
var isCookieAuthEnabledToolTip="Check this box if the node should support cookie based authentication mode";
var manageCAcertToolTip="Check this checkbox if you need to add CA cert and CA-Chain cert";

var editNodeToolTip="Edit this node";
var deleteNodeToolTip="Delete this node";
var addNodeTooTip="Add a new servie to the system";


var nodeNameFilterPrevVal="";
var nodeDescriptionFilterPrevVal="";
var localIPFilterPrevVal="";
var portFilterPrevVal="";
var serverFQDNFilterPrevVal="";

function setNodeModified(isModified){
	nodeModified=isModified;
	if (isModified){
		setActionButtonEnabled('saveNode',true);
	}else{
		setActionButtonEnabled('saveNode',false);
	}
	if ($('#isHTTPS').is(':checked')){
		$('#tabs').tabs( "enable", 1);
	}else{
		$('#tabs').tabs( "disable", 1);
	}
	
	if ($("#additionalConfiguration").val() != ""){
		$("#warnAdditionalConfig").show();
	}else{
		$("#warnAdditionalConfig").hide();
	}
	
}

function startEditNode(nodeURI){
	$.getJSON(nodeURI, editNode).error(displayErrorV2);
}
			
function updateNode(nodeURI){
	saveOrUpdateNode('PUT');
}

function startResetCASSL(){
	if (confirm("Do you realy want to delete existing Certification Authority SSL setting?")){
		removeCASSLSetting=true;
		setNodeModified(true);
		clearFileInput("CAfileuploadFLD");
		clearFileInput("CHAINfileuploadFLD");

		$("#lblCa").html("Add CA cert.");
		$("#lblChain").html("Add chain cert.");

	}
	
}
function startResetSSL(){
	if (confirm("Do you realy want to delete existing SSL setting?")){
		removeSSLSetting=true;
		setNodeModified(true);
		clearFileInput("PKfileuploadFLD");
		clearFileInput("CERTfileuploadFLD");
	}
	
}
function loadNodeServices(nodeURI){
	showWait();
	$.getJSON(nodeURI + "/services/", displayNodeServices).error(displayErrorV2);
}

function displayNodeServices(serviceList){
	
	hideWait();
	table=document.getElementById("data");
	rowPattern=document.getElementById("rowTpl");
	table.removeChild(rowPattern);
	
	
	
	$("#serviceListTitle").html($("#serviceListTitle").html().replaceAll("{serviceList.length}", serviceList.length))	;
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
										 .replaceAll("{cbpublishedcheck}", cbPublishedCheck)
										 .replaceAll("{i}", i)
										 .replaceAll("{serviceList[i].groupName}", serviceList[i].groupName)
										 .replaceAll("{serviceList[i].frontEndEndPoint}", serviceList[i].frontEndEndPoint)
										 .replaceAll("{serviceList[i].backEndEndPoint}", serviceList[i].backEndEndPoint);
		table.appendChild(newRow);
	}
	if (serviceList.length > 0 ){
		/* make the table scrollable with a fixed header */
		$("table.scroll").createScrollableTable({
			width: '800px',
			height: '350px',
			border: '0px'
		});
		touchScroll("servicesList_body_wrap");
	}else{
		$("#servicesList").hide();
	}

}
function clearFileInput(id)
{
	var oldInput = document.getElementById(id);
	
	var newInput = document.createElement("input");
	
	newInput.type = "file";
	newInput.id = oldInput.id;
	newInput.name = oldInput.name;
	newInput.className = oldInput.className;
	newInput.style.cssText = oldInput.style.cssText;
	// copy any other relevant attributes
	
	oldInput.parentNode.replaceChild(newInput, oldInput);
}
function toggleAuthority(){
	$("#sslAuthority").toggle();
	if (!$('#manageCaCert').is(':checked')){
		clearFileInput("CAfileuploadFLD");
		clearFileInput("CHAINfileuploadFLD");
		
		
	}
}
			
function editNode(node){
	$.get("resources/templates/nodeEdit.php", function (data){
		cbIsBasicAuthEnabled="checked";
		cbIsCookieAuthEnabled="checked";
		cbManageCAEnabled="";
		if (node.isCookieAuthEnabled==0){
			cbIsCookieAuthEnabled="";
		}
		if (node.isBasicAuthEnabled==0){
			cbIsBasicAuthEnabled="";
		}
		if (node.isHTTPS==0){
			cbIsHTTPS="";
		}
		if (node.caUri != "" || node.chainUri!=""){
			cbManageCAEnabled="checked";
		}
		currentNode=node;


		lblCa="Add CA cert.";
		lblChain="Add chain cert.";
		if (node.caUri != "") {
			lblCa = "Change current CA cert.";
		}
		if (node.chainUri != "") {
			lblChain = "Change current chain cert.";
		}
		
		$("#content").html(data.replaceAll("{node.uri}", node.uri)
							   .replaceAll("{additionalConfigurationToolTip}", additionalConfigurationToolTip)
							   .replaceAll("{node.additionalConfiguration}", node.additionalConfiguration)
							   .replaceAll("{nodeNameAsLabel}", node.nodeName)
							   .replaceAll("{nodeNameInputType}", "hidden")
							   .replaceAll("{nodeNameToolTip}", nodeNameToolTip)
							   .replaceAll("{node.nodeName}", node.nodeName)
							   .replaceAll("{isHTTPSToolTip}", isHTTPSToolTip)
							   .replaceAll("{cbIsHTTPS}", cbIsHTTPS)
							   .replaceAll("{localIPToolTip}", localIPToolTip)
							   .replaceAll("{node.localIP}", node.localIP)
							   .replaceAll("{portToolTip}", portToolTip)
							   .replaceAll("{node.port}", node.port)
							   .replaceAll("{serverFQDNToolTip}", serverFQDNToolTip)
							   .replaceAll("{node.serverFQDN}", node.serverFQDN)
							   .replaceAll("{nodeDescriptionToolTip}", nodeDescriptionToolTip)
							   .replaceAll("{node.nodeDescription}", node.nodeDescription)
							   .replaceAll("{isBasicAuthEnabledToolTip}", isBasicAuthEnabledToolTip)
							   .replaceAll("{cbIsBasicAuthEnabled}", cbIsBasicAuthEnabled)
							   .replaceAll("{isCookieAuthEnabledToolTip}", isCookieAuthEnabledToolTip)
							   .replaceAll("{cbIsCookieAuthEnabled}", cbIsCookieAuthEnabled)
							   .replaceAll("{manageCAcertToolTip}", manageCAcertToolTip)
							   .replaceAll("{cbManageCAEnabled}", cbManageCAEnabled)
							   .replaceAll("{lblCa}", lblCa)
							   .replaceAll("{lblChain}", lblChain)
							   .replaceAll("{manageCAcertToolTip}", manageCAcertToolTip)
		);

		if (node.caUri == "" && node.chainUri==""){
			$('#sslAuthority').hide();
		}
		$(function() {
			$( "#tabs" ).tabs();
		});

		$("#mainForm").height($("#tabs").height()+10);
		$("#mainForm").click(function(){
				$("#mainForm").height($("#tabs").height()+10);
		});
		setNodeModified(false);
		removeSSLSetting=false;
		removeCASSLSetting=false;
	});
}

			
function saveOrUpdateNode(method){
	if (!$('#isCookieAuthEnabled').is(':checked') && !$('#isBasicAuthEnabled').is(':checked') ){
		alert("At least one authentication methode (basic authentication or cookie authentication) should be enabled");
		return false;
	}
	
	showWait();
	 var uploadPrivKeyFLD=document.getElementById("PKfileuploadFLD");
	 var uploadCertFLD=document.getElementById("CERTfileuploadFLD");
	 var uploadCaFLD=document.getElementById("CAfileuploadFLD");
	 var uploadChainFLD=document.getElementById("CHAINfileuploadFLD");
	del=true;
	if (removeSSLSetting && uploadPrivKeyFLD.files.length==0 && uploadCertFLD.files.length==0){
		del=false;
		
		$.ajax({
		  url: "nodes/" + currentNode.nodeName + "/cert" ,
		  dataType: 'json',
		  async: false,
		  type:'DELETE',
		  success: function (e){
			  del=true;
		  },
		  error: displayErrorV2
		});
		if (del){
			del=false;
			$.ajax({
			  url: "nodes/" + currentNode.nodeName + "/privateKey" ,
			  dataType: 'json',
			  type:'DELETE',
			  async: false,
			  success: function (e){
				  del=true;
			  },
			  error: displayErrorV2
			});
		
		}
	}	
	if (removeCASSLSetting && uploadCaFLD.files.length==0){
		if (del){
			del=false;
			$.ajax({
			  url: "nodes/" + currentNode.nodeName + "/ca" ,
			  dataType: 'json',
			  type:'DELETE',
			  async: false,
			  success: function (e){
				  del=true;
			  },
			  error: displayErrorV2
			});
		
		}
	}	
	if (removeCASSLSetting && uploadChainFLD.files.length==0){
		if (del){
			del=false;
			$.ajax({
			  url: "nodes/" + currentNode.nodeName + "/chain" ,
			  dataType: 'json',
			  type:'DELETE',
			  async: false,
			  success: function (e){
				  del=true;
			  },
			  error: displayErrorV2
			});
		
		}
	}	
	if (!del){
		hideWait();
		return false;
	}
	
	nodeName="nodeName=" + encodeURIComponent($("#nodeNameFld").val());
	localIP="localIP=" + encodeURIComponent(document.getElementById("localIP").value);
	port="port=" + encodeURIComponent(document.getElementById("port").value);
	nodeDescription="nodeDescription=" + encodeURIComponent(document.getElementById("nodeDescription").value);
	additionalConfiguration="additionalConfiguration=" + encodeURIComponent($("#additionalConfiguration").val());
	serverFQDN="serverFQDN=" + encodeURIComponent(document.getElementById("serverFQDN").value);
	if (document.getElementById("isHTTPS").checked){
		isHTTPS="isHTTPS=1";
	}else{
		isHTTPS="isHTTPS=0";
	}
	if (document.getElementById("isBasicAuthEnabled").checked){
		isBasicAuthEnabled="isBasicAuthEnabled=1";
	}else{
		isBasicAuthEnabled="isBasicAuthEnabled=0";
	}
	if (document.getElementById("isCookieAuthEnabled").checked){
		isCookieAuthEnabled="isCookieAuthEnabled=1";
	}else{
		isCookieAuthEnabled="isCookieAuthEnabled=0";
	}
	postData=localIP + 
	"&" + serverFQDN + 
	"&" + port + 
	"&" + isBasicAuthEnabled + 
	"&" + isCookieAuthEnabled + 
	"&" + nodeDescription + 
	"&" + additionalConfiguration + 
	"&" + isHTTPS;
	if (method=='PUT'){
		businessUrl="nodes/" + encodeURIComponent(document.getElementById("nodeNameFld").value);
	}else{
		businessUrl="nodes/";
		postData="nodeName=" +encodeURIComponent(document.getElementById("nodeNameFld").value) + "&" + postData;
	}
	if (method=='POST'){
		//In case of create, first create to be able to update SSL setting in next step
		postOk=false;
		$.ajax({
			  url: businessUrl ,
			  dataType: 'json',
			  type:method,
			  data: postData + "&apply=0",
			  async: false,
			  success: function(){postOk=true},
			  error: displayErrorV2
			});
		if (!postOk){
			return false;
		}
	 }

	 error=false;
	 ssl=false;
	 nodeURI="nodes/" + encodeURIComponent(document.getElementById("nodeNameFld").value);
	 if (uploadPrivKeyFLD.files.length>0){
		$('#fileupload').fileupload();
		$('#fileupload').fileupload('send', {async: false, files: uploadPrivKeyFLD.files, url:nodeURI + "/privateKey"})
			.error(function (jqXHR, textStatus, errorThrown) {error=error||true; $('#fileupload').fileupload('destroy');displayErrorV2(jqXHR, textStatus, errorThrown)});
		ssl=true;
		$('#fileupload').fileupload('destroy');
	 }
	 if (uploadCertFLD.files.length>0){
		$('#fileupload').fileupload();
		$('#fileupload').fileupload('send', {async: false, files: uploadCertFLD.files, url:nodeURI + "/cert"})
			.error(function (jqXHR, textStatus, errorThrown) {error=error||true; $('#fileupload').fileupload('destroy');displayErrorV2(jqXHR, textStatus, errorThrown)});
			ssl=true;
		$('#fileupload').fileupload('destroy');
	 }
	 if (uploadCaFLD.files.length>0){
		$('#fileupload').fileupload();
		$('#fileupload').fileupload('send', {async: false, files: uploadCaFLD.files, url:nodeURI + "/ca"})
			.error(function (jqXHR, textStatus, errorThrown) {error=error||true; $('#fileupload').fileupload('destroy');displayErrorV2(jqXHR, textStatus, errorThrown)});
			ssl=true;
		$('#fileupload').fileupload('destroy');
	 }
	 if (uploadChainFLD.files.length>0){
		$('#fileupload').fileupload();
		$('#fileupload').fileupload('send', {async: false, files: uploadChainFLD.files, url:nodeURI + "/chain"})
			.error(function (jqXHR, textStatus, errorThrown) {error=error||true; $('#fileupload').fileupload('destroy');displayErrorV2(jqXHR, textStatus, errorThrown)});
			ssl=true;
		$('#fileupload').fileupload('destroy');
	 }
	 if (error){
		 return false;
	 }
	 //Anyway, update and apply config
	$.ajax({
		  url: businessUrl ,
		  dataType: 'json',
		  type:'PUT',
		  data: postData,
		  success: showNodes,
		  error: displayErrorV2
		});

}

function saveNewNode(){
	saveOrUpdateNode('POST');
}
			
			
			
function addNode(){
	$.get("resources/templates/nodeAdd.php", function (data){
		currentNode=null;


		lblCa="Add CA cert.";
		lblChain="Add chain cert.";
		
		$("#content").html(data.replaceAll("{node.uri}", "")
							   .replaceAll("{additionalConfigurationToolTip}", additionalConfigurationToolTip)
							   .replaceAll("{node.additionalConfiguration}", "")
							   .replaceAll("{nodeNameAsLabel}", "")
							   .replaceAll("{nodeNameInputType}", "text")
							   .replaceAll("{nodeNameToolTip}", nodeNameToolTip)
							   .replaceAll("{node.nodeName}", "")
							   .replaceAll("{isHTTPSToolTip}", isHTTPSToolTip)
							   .replaceAll("{cbIsHTTPS}", "")
							   .replaceAll("{localIPToolTip}", localIPToolTip)
							   .replaceAll("{node.localIP}", "")
							   .replaceAll("{portToolTip}", portToolTip)
							   .replaceAll("{node.port}", "")
							   .replaceAll("{serverFQDNToolTip}", serverFQDNToolTip)
							   .replaceAll("{node.serverFQDN}", "")
							   .replaceAll("{nodeDescriptionToolTip}", nodeDescriptionToolTip)
							   .replaceAll("{node.nodeDescription}", "")
							   .replaceAll("{isBasicAuthEnabledToolTip}", isBasicAuthEnabledToolTip)
							   .replaceAll("{cbIsBasicAuthEnabled}", "")
							   .replaceAll("{isCookieAuthEnabledToolTip}", isCookieAuthEnabledToolTip)
							   .replaceAll("{cbIsCookieAuthEnabled}", "")
							   .replaceAll("{manageCAcertToolTip}", manageCAcertToolTip)
							   .replaceAll("{cbManageCAEnabled}", "")
							   .replaceAll("{lblCa}", lblCa)
							   .replaceAll("{lblChain}", "")
							   .replaceAll("{manageCAcertToolTip}", manageCAcertToolTip)
		);
		
		$('#sslAuthority').hide();
		$("#showServices").hide();
		$("#resetSSL").hide();
		$("#resetCASSL").hide();
		$(function() {
			$( "#tabs" ).tabs();
		});

		$("#mainForm").height($("#tabs").height()+10);
		$("#mainForm").click(function(){
				$("#mainForm").height($("#tabs").height()+10);
		});
		setNodeModified(false);
		removeSSLSetting=false;
		removeCASSLSetting=false;
	});
}

function handelNodeFilterFormKeypress(e) {
	if (e.keyCode == 13) {
		showNodes();
		return false;
	}
}			
			
function displayNodeList(nodeList){
	
	
	hideWait();
	$.get( "resources/templates/nodeList.html", function( data ) {
		
		$( "#content" ).html( data.replaceAll("{nodeList.length}", nodeList.length )
								  .replaceAll("{nodeNameFilterPrevVal}", nodeNameFilterPrevVal )	
								  .replaceAll("{nodeDescriptionFilterPrevVal}", nodeDescriptionFilterPrevVal )
								  .replaceAll("{serverFQDNFilterPrevVal}", serverFQDNFilterPrevVal )
								  .replaceAll("{localIPFilterPrevVal}", localIPFilterPrevVal )
								  .replaceAll("{portFilterPrevVal}", portFilterPrevVal )
							);	
		table=document.getElementById("data");
		rowPattern=document.getElementById("rowTpl");
		table.removeChild(rowPattern);
		
		for (i=0;i<nodeList.length;i++){
			if (nodeList[i].isHTTPS==1){
				cbIsHTTPS="checked";
			}else{
				cbIsHTTPS=""
			}

			
			newRow=rowPattern.cloneNode(true);
			newRow.removeAttribute('id');
			newRow.removeAttribute('style');
			newRow.className=newRow.className + " tabular_table_body" +  (i%2);
			newRow.innerHTML=newRow.innerHTML.replaceAll("{nodeList[i].nodeName}", nodeList[i].nodeName)
											 .replaceAll("{isPublishedToolTip}", isPublishedToolTip)
											 .replaceAll("{rowParity}", (i%2))
											 .replaceAll("{i}", i)
											 .replaceAll("{cbishttps}", cbIsHTTPS)
											 .replaceAll("{nodeList[i].serverFQDN}", nodeList[i].serverFQDN)
											 .replaceAll("{nodeList[i].localIP}", nodeList[i].localIP)
											 .replaceAll("{nodeList[i].port}", nodeList[i].port)
											 .replaceAll("{nodeList[i].nodeDescription}", nodeList[i].nodeDescription)
											 .replaceAll("{editNodeToolTip}", editNodeToolTip)
											 .replaceAll("{nodeList[i].uri}", nodeList[i].uri)
											 .replaceAll("{deleteNodeToolTip}", deleteNodeToolTip);
			table.appendChild(newRow);
			edit=document.getElementById("btnEdit");
			del=document.getElementById("btnDelete");
			del.removeAttribute("id");
			edit.removeAttribute("id");
		}
		if (nodeList.length > 0 ){
			/* make the table scrollable with a fixed header */
			$("table.scroll").createScrollableTable({
				width: '800px',
				height: '350px',
				border: '0px'
			});
			touchScroll("nodesList_body_wrap");
		}else{
			$("#nodesList").hide();
		}
		
	});
}


function deleteNode(nodeURI, nodeName){
	
	
	if (confirm("Are you sure to want to remove node " + nodeName + "?")){
		showWait();
		$.ajax({
			  url: nodeURI,
			  dataType: 'json',
			  type:'DELETE',
			  //data: data,
			  success: showNodes,
			  error: displayErrorV2
			});
	}
	
}

function  resetNodeFilter(){
	$('#nodeNameFilter').val("");
	$('#nodeDescriptionFilter').val("")
	$('#localIPFilter').val("");
	$('#portFilter').val("");
	$('#serverFQDNFilter').val("");
	showNodes();
}

function showNodes(){
	prms="order=nodeName";
	prms=prms + "&nodeNameFilter=" + encodeURIComponent(getFilterValue('nodeNameFilter'));
	prms=prms + "&nodeDescriptionFilter=" + encodeURIComponent(getFilterValue('nodeDescriptionFilter'));
	prms=prms + "&localIPFilter=" + encodeURIComponent(getFilterValue('localIPFilter'));
	prms=prms + "&portFilter=" + encodeURIComponent(getFilterValue('portFilter'));
	prms=prms + "&serverFQDNFilter=" + encodeURIComponent(getFilterValue('serverFQDNFilter'));



	$.ajax({
		url : './nodes/',
		dataType : 'json',
		type : 'GET',
		data: prms,
		success : displayNodeList,
		error : displayErrorV2
	});
}





//Event 			
$(
		function (){
			$('#listNode').click(resetNodeFilter);
			$('#addNode').click(addNode);
		}
	);
