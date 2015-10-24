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
 * File Name   : ApplianceManager/ApplianceManager.php/js/quotas.js
 *
 * Created     : 2012-02
 * Authors     : Benoit HERARD <benoit.herard(at)orange.com>
 *
 * Description :
 *      AJAX Managegment for quotas
 *--------------------------------------------------------
 * History     :
 * 1.0.0 - 2012-10-01 : Release of the file
*/

var currentQuotaUri;
var quotaModified;

var userServiceQuotaToolTip="Available service on which user quotas are defined";



function saveNewQuota(){
	saveOrUpdateQuota('POST');
}


function updateQuota(){
	saveOrUpdateQuota('PUT');
}

function saveOrUpdateQuota(method){
	currentQuotaUri=document.getElementById("quotaUri").value;
	reqSec = "reqSec=" + encodeURIComponent(document.getElementById("reqSec").value);
	reqDay = "reqDay=" + encodeURIComponent(document.getElementById("reqDay").value);
	reqMonth = "reqMonth=" + encodeURIComponent(document.getElementById("reqMonth").value);
	postData=reqSec + "&" + reqDay + "&" + reqMonth;
	$.ajax({
		  url: currentQuotaUri + "?" + postData, 
		  dataType: 'json',
		  type:method,
		  data: postData,
		  success: startDisplayUserQuotasForCurrentUser,
		  error: displayError
		});
	
	

}

function setQuotaModified(isModified){
	quotaModified=isModified;
	if (isModified){
		setActionButtonEnabled('saveEditQuotas',true);
	}else{
		setActionButtonEnabled('saveEditQuotas',false);
	}
}

function startEditUserQuotasForCurrentQuota(){
	startEditUserQuotas(currentQuotaUri);
}


function startEditUserQuotas(quotaURI){
	currentQuotaUri=quotaURI;
	$.getJSON(quotaURI, editUserQuotas).error(displayError);
}


function startPopulateUnsetQuotas(userURI){
	$.getJSON(userURI + "/quotas/unset/", populateUnsetQuotas).error(displayError);
}


function populateUnsetQuotas(quotaList){
	if (quotaList.length>0){
		$.each(quotaList, function (i, item) {
			$('#quotaUri').append($('<option>', { 
				value: item.uri,
				text : item.serviceName 
			}));
		});
	}else{
		$('#quotaUri').hide();
		$("#saveEditQuotas").hide();
	}
	
}

function addUserQuotas(){
	$.get( "resources/templates/userQuotaAdd.php", function( data ) {
		$("#content").html(data.replaceAll("{currentUser.userName}", currentUser.userName)
		);
		setQuotaModified(true);
		startPopulateUnsetQuotas(currentUserUri);
	});
}


function editUserQuotas(quota){

		$.get("resources/templates/userQuotaEdit.php", function (data){
			$("#content").html(data.replaceAll("{quota.serviceName}",quota.serviceName)
								   .replaceAll("{quota.userName}", quota.userName)
								   .replaceAll("{quota.uri}", quota.uri)
								   .replaceAll("{quota.reqSec}", quota.reqSec)
								   .replaceAll("{quota.reqDay}", quota.reqDay)
								   .replaceAll("{quota.reqMonth}", quota.reqMonth)
			);
			currentQuotaUri=quota.uri;
			setQuotaModified(false);
		});
}
	
