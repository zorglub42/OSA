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
var servicesLoaded=false;


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
	if (confirm("<?php echo Localization::getJSString("node.deleteCASSL.confirm")?>")){
		removeCASSLSetting=true;
		setNodeModified(true);
		clearFileInput("CHAINfileuploadFLD");
	}
	
}
function startResetSSL(){
	if (confirm("<?php echo Localization::getJSString("node.deleteSSL.confirm")?>")){
		removeSSLSetting=true;
		setNodeModified(true);
		clearFileInput("PKfileuploadFLD");
		clearFileInput("CERTfileuploadFLD");
	}
	
}
function loadNodeServices(nodeURI){
	if (!servicesLoaded){
		showWait();
		$.getJSON(nodeURI + "/services/", displayNodeServices).error(displayErrorV2);
	}
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
										 .replaceAll("{servicelist[i].cbpublishedcheck}", cbPublishedCheck)
										 .replaceAll("{i}", i)
										 .replaceAll("{serviceList[i].groupName}", serviceList[i].groupName)
										 .replaceAll("{serviceList[i].frontEndEndPoint}", serviceList[i].frontEndEndPoint)
										 .replaceAll("{serviceList[i].backEndEndPoint}", serviceList[i].backEndEndPoint);
		table.appendChild(newRow);
	}
	if (serviceList.length == 0 ){
		$("#servicesList").hide();
	}
	servicesLoaded=true;

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
		clearFileInput("CHAINfileuploadFLD");
		
		
	}
}
			
function editNode(node){
	$.get("resources/templates/nodeEdit.php", function (data){
		servicesLoaded=false
		cbIsHTTPS="checked";
		cbManageCAEnabled="";
		if (node.isHTTPS==0){
			cbIsHTTPS="";
		}
		if (node.caUri != "" || node.chainUri!=""){
			cbManageCAEnabled="checked";
		}
		currentNode=node;


		
		$("#content").html(data.replaceAll("{node.uri}", node.uri)
							   .replaceAll("{node.additionalConfiguration}", node.additionalConfiguration)
							   .replaceAll("{nodeNameAsLabel}", node.nodeName)
							   .replaceAll("{nodeNameInputType}", "hidden")
							   .replaceAll("{node.nodeName}", node.nodeName)
							   .replaceAll("{cbIsHTTPS}", cbIsHTTPS)
							   .replaceAll("{node.localIP}", node.localIP)
							   .replaceAll("{node.port}", node.port)
							   .replaceAll("{node.serverFQDN}", node.serverFQDN)
							   .replaceAll("{node.nodeDescription}", node.nodeDescription)
							   .replaceAll("{cbManageCAEnabled}", cbManageCAEnabled)
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
	
	if ($("#port").val() == "80" && document.getElementById("isHTTPS").checked){
		if (!confirm('<?php echo Localization::getJSString("node.https-on-80-warning")?>')){
			return false;
		}
	}
	if ($("#port").val() == "443" && !document.getElementById("isHTTPS").checked){
		if (!confirm('<?php echo Localization::getJSString("node.http-on-443-warning")?>')){
			return false;
		}
	}
	showWait();
	 var uploadPrivKeyFLD=document.getElementById("PKfileuploadFLD");
	 var uploadCertFLD=document.getElementById("CERTfileuploadFLD");
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
	postData=localIP + 
	"&" + serverFQDN + 
	"&" + port + 
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


		$("#content").html(data.replaceAll("{node.uri}", "")
							   .replaceAll("{node.additionalConfiguration}", "")
							   .replaceAll("{nodeNameAsLabel}", "")
							   .replaceAll("{nodeNameInputType}", "text")
							   .replaceAll("{node.nodeName}", "")
							   .replaceAll("{cbIsHTTPS}", "")
							   .replaceAll("{node.localIP}", "")
							   .replaceAll("{node.port}", "")
							   .replaceAll("{node.serverFQDN}", "")
							   .replaceAll("{node.nodeDescription}", "")
							   .replaceAll("{cbManageCAEnabled}", "")
							   .replaceAll("{lblChain}", "")
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
	$.get( "resources/templates/nodeList.php", function( data ) {
		
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

		var nodesListAutoComplete=new Array();
		var descriptionsListAutoComplete=new Array();
		var fqdnsListAutoComplete=new Array();
		var ipsListAutoComplete=new Array();
		var portsListAutoComplete=new Array();

		
		for (i=0;i<nodeList.length;i++){
			if (nodeList[i].isHTTPS==1){
				cbIsHTTPS="checked";
			}else{
				cbIsHTTPS=""
			}

			addItem(nodesListAutoComplete, nodeList[i].nodeName);
			addItem(descriptionsListAutoComplete, nodeList[i].nodeDescription, true);
			addItem(fqdnsListAutoComplete, nodeList[i].serverFQDN, true);
			addItem(ipsListAutoComplete, nodeList[i].localIP, true);
			addItem(portsListAutoComplete, nodeList[i].port.toString(), true);
			
			newRow=rowPattern.cloneNode(true);
			newRow.removeAttribute('id');
			newRow.removeAttribute('style');
			newRow.className=newRow.className + " tabular_table_body" +  (i%2);
			newRow.innerHTML=newRow.innerHTML.replaceAll("{nodeList[i].nodeName}", nodeList[i].nodeName)
											 .replaceAll("{rowParity}", (i%2))
											 .replaceAll("{i}", i)
											 .replaceAll("{cbishttps}", cbIsHTTPS)
											 .replaceAll("{nodeList[i].serverFQDN}", nodeList[i].serverFQDN)
											 .replaceAll("{nodeList[i].localIP}", nodeList[i].localIP)
											 .replaceAll("{nodeList[i].port}", nodeList[i].port)
											 .replaceAll("{nodeList[i].nodeDescription}", nodeList[i].nodeDescription)
											 .replaceAll("{nodeList[i].uri}", nodeList[i].uri);
			table.appendChild(newRow);
			edit=document.getElementById("btnEdit");
			del=document.getElementById("btnDelete");
			del.removeAttribute("id");
			edit.removeAttribute("id");
		
			publish=document.getElementById("btnPublish");
			unpublish=document.getElementById("btnUnpublish");
			if (nodeList[i].isPublished == 1){
				publish.remove();
				unpublish.removeAttribute("id");
			}else{
				unpublish.remove();
				publish.removeAttribute("id");
			}

		}
		$( "#nodeNameFilter" ).autocomplete({
						source: nodesListAutoComplete,
						minLength: 0
		});
		$( "#nodeDescriptionFilter" ).autocomplete({
						source: descriptionsListAutoComplete,
						minLength: 0
		});
		$( "#serverFQDNFilter" ).autocomplete({
						source: fqdnsListAutoComplete,
						minLength: 0
		});
		$( "#localIPFilter" ).autocomplete({
						source: ipsListAutoComplete,
						minLength: 0
		});
		$( "#portFilter" ).autocomplete({
						source: portsListAutoComplete,
						minLength: 0
		});
		
		if (nodeList.length == 0 ){
			$("#nodesList").hide();
		}
		
	});
}


function deleteNode(nodeURI, nodeName){
	
	
	if (confirm("<?php echo Localization::getJSString("node.delete.confirm")?> " + nodeName + "?")){
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

function publishNode(nodeURI, state){
	
	
		showWait();
		$.ajax({
			  url: nodeURI + "/status",
			  dataType: 'json',
			  type:'POST',
			  data: "published=" + state,
			  success: showNodes,
			  error: displayErrorV2
			});
	
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
	if (getFilterValue('portFilter')!=""){
		prms=prms + "&portFilter=" + encodeURIComponent(getFilterValue('portFilter'));
	}
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
