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

/* Enable or disable UI control according to quota properties updates */
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

/* Load node properties and start edit form */
function startEditNode(nodeURI){
	$.getJSON(nodeURI, editNode).error(displayErrorV2);
}

/* Update node properties */
function updateNode(nodeURI){
	/* del existing certs if required and start new certs upload if required
	 * then save node properties and finally open nodes list */
	delCerts();
}

/* Manager for "Reset CA/Chain certs" button */
function startResetCASSL(){
	if (confirm("<?php echo Localization::getJSString("node.deleteCASSL.confirm")?>")){
		removeCASSLSetting=true;
		setNodeModified(true);
		clearFileInput("CHAINfileuploadFLD");
	}

}
/* Manager for "reset Priv key/cert" button */
function startResetSSL(){
	if (confirm("<?php echo Localization::getJSString("node.deleteSSL.confirm")?>")){
		removeSSLSetting=true;
		setNodeModified(true);
		clearFileInput("PKfileuploadFLD");
		clearFileInput("CERTfileuploadFLD");
	}

}

/* Load services deployed on a node and starts method to display */
function loadNodeServices(nodeURI){
	if (!servicesLoaded){
		showWait();
		$.getJSON(nodeURI + "/services/", displayNodeServices).error(displayErrorV2);
	}
}

/* Display services deployed on a node */
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


/* Clear a "file upload" form field */
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

/* Toggle check box 'manage authority chain
 * and clear upload chain file upload filed is if check box unchecked */
function toggleAuthority(){
	$("#sslAuthority").toggle();
	if (!$('#manageCaCert').is(':checked')){
		clearFileInput("CHAINfileuploadFLD");


	}
}


/* Load node properties template and display node properties */
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


/* Save (create) or update node properties (and not sub items like certs)
	 once done, starts "nextStep"
	 method: PUT=update, POST=create
 */
function saveOrUpdateNodeBase(method, nextStep, apply){

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
	if (!apply){
		applyComp="&apply=0"
	}else {
		applyComp=""
	}
	$.ajax({
		  url: businessUrl ,
		  dataType: 'json',
		  type:method,
		  data: postData + applyComp,
		  success: nextStep,
		  error: displayErrorV2
		});

}


/* Delete existing SSL certs if requested and then  starts upload new certs
*  (if required). Upload method will then start update of "core" node properties
* and node deployment */
function delCerts(){
	promises=[];
	var uploadPrivKeyFLD=document.getElementById("PKfileuploadFLD");
	var uploadCertFLD=document.getElementById("CERTfileuploadFLD");
	var uploadChainFLD=document.getElementById("CHAINfileuploadFLD");
	showWait()

	if (removeSSLSetting && uploadPrivKeyFLD.files.length==0 && uploadCertFLD.files.length==0){
		/* remove key/cert requested and no upload requested => clear certs */
		cert = new Promise(function(resolv, reject){

			$.ajax({
				url: "nodes/" + currentNode.nodeName + "/cert" ,
				dataType: 'json',
				async: false,
				type:'DELETE',
				success: resolv,
				error: reject
			});
		});
		promises.push[cert];

		key = new Promise(function (resolv, reject){
			$.ajax({
			  url: "nodes/" + currentNode.nodeName + "/privateKey" ,
			  dataType: 'json',
			  type:'DELETE',
			  async: false,
			  success: resolv,
			  error: reject
			});

		});
		promises.push(key);
	}

	if (removeCASSLSetting && uploadChainFLD.files.length==0){
		/* remove chain requested and no upload requested => clear cert */
		chain = new Promise(function(resolv, reject){
			$.ajax({
				url: "nodes/" + currentNode.nodeName + "/chain" ,
				dataType: 'json',
				type:'DELETE',
				async: false,
				success: resolv,
				error: reject
			});
		});
	}
	if (promises.length){
		/* at least on delete requested */
		Promise.all(promises).then(function(res){
			/* Delete done, start upload certs management */
			uploadCerts()
		}).catch(function(reason){
			displayErrorV2(reason);
		});
	}else{
		/* No delete requested, start upload certs management */
		uploadCerts();
	}

}

/* Upload priv key/cert/chain management.
* Upload certs if required and trigger node "core" properties update and
* node deployment */
function uploadCerts(){
	var uploadPrivKeyFLD=document.getElementById("PKfileuploadFLD");
	var uploadCertFLD=document.getElementById("CERTfileuploadFLD");
	var uploadChainFLD=document.getElementById("CHAINfileuploadFLD");
	nodeURI="nodes/" + encodeURIComponent(document.getElementById("nodeNameFld").value);
	promises=[]

	if (uploadPrivKeyFLD.files.length>0){
		/* priv key upload requested */
		key = new Promise(function(resolv, reject){

									$('#fileuploadKEY').fileupload();
									jqxhr = $('#fileuploadKEY').fileupload('send', {files: uploadPrivKeyFLD.files, url:nodeURI + "/privateKey"})
																						 .error(function (jqXHR, textStatus, errorThrown) {
																							 					$('#fileuploadKEY').fileupload('destroy')
																							 					reject(jqXHR);
																							}).complete(function (jqXHR, textStatus, errorThrown) {
																								$('#fileuploadKEY').fileupload('destroy');
																								resolv(true);
																							});
 		});
		promises.push(key);
	}
	if (uploadCertFLD.files.length>0){
		/* Public cert upload requested */
		cert = new Promise(function (resolv, reject){
									$('#fileuploadPEM').fileupload();
									jqxhr = $('#fileuploadPEM').fileupload('send', { files: uploadCertFLD.files, url:nodeURI + "/cert"})
																						 .error(function (jqXHR, textStatus, errorThrown) {
																							 			$('#fileuploadPEM').fileupload('destroy');
																										reject(jqXHR);
																						 }).complete(function (){
													 													$('#fileuploadPEM').fileupload('destroy');
																										resolv(true);
													 									 });
		});
		promises.push(cert);
	}
	if (uploadChainFLD.files.length>0){
		/* chain cert upload requested */
		chain = new Promise(function (resolv, reject){
								 $('#fileuploadCHAIN').fileupload();
								 jqxhr = $('#fileuploadCHAIN').fileupload('send', { files: uploadChainFLD.files, url:nodeURI + "/chain"})
									 														.error(function (jqXHR, textStatus, errorThrown) {
																										$('#fileuploadCHAIN').fileupload('destroy');
																										reject(jqXHR)})
																							.complete(function (){
														 									 			$('#fileuploadCHAIN').fileupload('destroy');
																										resolv(true);
														 								 });
		});
		promises.push(chain)
	}
	if (promises.length>0){
		/* at least one upload was requested */
		Promise.all(promises).then(function (res){
				/* once done, update node properties and deploy it,
				   then switch to nod list */
				saveOrUpdateNodeBase('PUT', showNodes, true);
		}).catch(function (reason){
				displayErrorV2(reason);
		});
	}else{
		/* No upload, update node properties and deploy it,
			 then switch to nodes list */
		saveOrUpdateNodeBase('PUT', showNodes, true);
	}
}


/* Create and deploy a new node */
function saveNewNode(){
	var uploadCertFLD=document.getElementById("CERTfileuploadFLD");
	var uploadPrivKeyFLD=document.getElementById("PKfileuploadFLD");
	var uploadChainFLD=document.getElementById("CHAINfileuploadFLD");

	if ((uploadPrivKeyFLD.files.length >0 && uploadPrivKeyFLD.length >0) || uploadPrivKeyFLD.files.length>0 ){
		/* Some cert upload are required, so first create node in DB without
		* deployment and then, apply update, i.e certs upload and deployment */
		saveOrUpdateNodeBase('POST', updateNode, false);
	}else{
		/* No certs upload requested, so create and deploy new node and then
		* switch to nodes list */
		saveOrUpdateNodeBase('POST', showNodes, true);
	}
}

/* Load add node template and display correspnding form */
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


/* Handle key press on node filter form to apply filter when "enter" key
* is pressed */
function handelNodeFilterFormKeypress(e) {
	if (e.keyCode == 13) {
		showNodes();
		return false;
	}
}

/* Load node list templay and display nodes list */
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


/* Delete and undeploy a node and then switch to node list */
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

/* Publish/unpublish (deploy/undeploy) a node
   then switch to node list */
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

/* Reset node filter form and apply */
function  resetNodeFilter(){
	$('#nodeNameFilter').val("");
	$('#nodeDescriptionFilter').val("")
	$('#localIPFilter').val("");
	$('#portFilter').val("");
	$('#serverFQDNFilter').val("");
	showNodes();
}


/* Load node list and start display */
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





/* Attach Event to UI main menu controls */
$(
		function (){
			$('#listNode').click(resetNodeFilter);
			$('#addNode').click(addNode);
		}
	);
