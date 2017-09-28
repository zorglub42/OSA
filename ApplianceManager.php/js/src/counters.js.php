
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
 * File Name   : ApplianceManager/ApplianceManager.php/js/counters.js
 *
 * Created     : 2012-02
 * Authors     : Benoit HERARD <benoit.herard(at)orange.com>
 *
 * Description :
 *      AJAX Management for counters
 *--------------------------------------------------------
 * History     :
 * 1.0.0 - 2012-10-01 : Release of the file
*/

var currentCounter;
var counterModified;

var counterSearch_resourceName;
var counterSearch_userName;
var counterSearch_timeUnit;

var currentCounterList;



var backList;

/* Enable or disable UI control according to counter properties updates */
function setCounterModified(isModified){
	counterModified=isModified;
	if (isModified){
		setActionButtonEnabled('save',true);
	}else{
		setActionButtonEnabled('save',false);
	}
}

/* Update a counter in DB */
function updateCounter(counterURI) {
	saveOrUpdateCounter('PUT');
}

/* Save (create) or update a counter
	 method: PUT=update, POST=create */
function saveOrUpdateCounter(method){
	value = "value=" + encodeURIComponent(document.getElementById("counterValue").value);
	$.ajax({
		  url: currentCounterUri,
		  dataType: 'json',
		  type:method,
		  data: value,
		  success: editCurrentCounter,
		  error: displayErrorV2
		});
}

/* start counter edit */
function startEditCounter(counterNum){
	currentCounterUri=currentCounterList[counterNum].uri;
	editCurrentCounter();
}

/* Load counter properties and display */
function editCurrentCounter(){
	$.getJSON(currentCounterUri, editCounter).error(displayErrorV2);
}

/* Load counter template and display */
function editCounter(counter){

	$.get("resources/templates/counterEdit.php", function(data){
		$("#content").html(data.replaceAll("{counter.resourceName}", counter.resourceName)
							   .replaceAll("{counter.userName}", counter.userName)
							   .replaceAll("{counter.timeUnit}", counter.timeUnit)
							   .replaceAll("{counter.timeValue}", counter.timeValue)
							   .replaceAll("{counter.value}", counter.value)
							   .replaceAll("{counter.uri}", counter.uri)
		);
		currentCounter=counter;
		currentCounterUi=counter.uri;
		setCounterModified(false);
	});
}


/* Load counters list template and display */
function displayCounterList(counterList){


	$.get( "resources/templates/counterList.php", function( data ) {

		$( "#content" ).html( data.replaceAll("{counterList.length}", counterList.length )
							);
		table=document.getElementById("data");
		rowPattern=document.getElementById("rowTpl");
		table.removeChild(rowPattern);

		for (i=0;i<counterList.length;i++){


			newRow=rowPattern.cloneNode(true);
			newRow.removeAttribute('id');
			newRow.removeAttribute('style');
			newRow.className=newRow.className + " tabular_table_body" +  (i%2);
			newRow.innerHTML=newRow.innerHTML.replaceAll("{counterList[i].resourceName}", counterList[i].resourceName)
											 .replaceAll("{counterList[i].userName}", counterList[i].userName)
											 .replaceAll("{counterList[i].timeUnit}", counterList[i].timeUnit)
											 .replaceAll("{counterList[i].timeValue}", counterList[i].timeValue)
											 .replaceAll("{counterList[i].value}", counterList[i].value)
											 .replaceAll("{rowParity}", (i%2))
											 .replaceAll("{i}", i);
			table.appendChild(newRow);
			edit=document.getElementById("btnEdit");
			del=document.getElementById("btnDelete");
			del.removeAttribute("id");
			edit.removeAttribute("id");
		}
		if (counterList.length>0){
			currentCounterList=counterList;
			backList=executeSearch;
		}else{
			$('#countersList').hide();
		}
	});


}

/* Reset counter filter form and apply */
function resetExceededCountersFilter(){
	$('#serviceNameFilter').val("");
	$('#userNameFilter').val("");
	searchExcedeedCounters();

}

/* Load excedeed counters templates and display */
function displayExcedeedCounterList(counterList){


	$.get( "resources/templates/counterExceededList.php", function( data ) {

		$( "#content" ).html( data.replaceAll("{counterList.length}", counterList.length )
								  .replaceAll("{serviceNameFilterPrevVal}", serviceNameFilterPrevVal )
								  .replaceAll("{userNameFilterPrevVal}", userNameFilterPrevVal )
							);
		table=document.getElementById("data");
		rowPattern=document.getElementById("rowTpl");
		table.removeChild(rowPattern);

		for (i=0;i<counterList.length;i++){


			newRow=rowPattern.cloneNode(true);
			newRow.removeAttribute('id');
			newRow.removeAttribute('style');
			newRow.className=newRow.className + " tabular_table_body" +  (i%2);
			newRow.innerHTML=newRow.innerHTML.replaceAll("{counterList[i].resourceName}", counterList[i].resourceName)
											 .replaceAll("{counterList[i].userName}", counterList[i].userName)
											 .replaceAll("{counterList[i].timeUnit}", counterList[i].timeUnit)
											 .replaceAll("{counterList[i].timeValue}", counterList[i].timeValue)
											 .replaceAll("{counterList[i].value}", counterList[i].value)
											 .replaceAll("{counterList[i].maxValue}", counterList[i].maxValue)
											 .replaceAll("{rowParity}", (i%2))
											 .replaceAll("{i}", i);
			table.appendChild(newRow);
			edit=document.getElementById("btnEdit");
			del=document.getElementById("btnDelete");
			del.removeAttribute("id");
			edit.removeAttribute("id");
		}
		if (counterList.length>0){
			currentCounterList=counterList;
			backList=searchExcedeedCounters;
		}else{
			$('#countersList').hide();
		}
	});
}


/* Delete a counter */
function deleteCounter(counterNum){
	if (confirm("<?php echo Localization::getJSString("counter.delete.confirm")?>")){
		$.ajax({
			  url: currentCounterList[counterNum].uri,
			  dataType: 'json',
			  type:'DELETE',
			  //data: data,
			  success: backList,
			  error: displayErrorV2
			});
	}

}

/* Load counters and display */
function showCounters(){
	$.getJSON("./counters/?order=counterName", displayCounterList).error(displayErrorV2);
}

/* Load services with counter list and populate autocomplete */
function startPopulateServices(){
	$.getJSON("services/?withQuotas=1&order=serviceName", populateServices).error(displayErrorV2);
}

/* Load users list and populate autocomplete */
function startPopulateUsers(){
	$.getJSON("users/?order=userName", populateUsers).error(displayErrorV2);
}

/* Populate autocomplete list for service field */
function populateServices(servicesList){
	if (servicesList.length>0){
		var serviceListAutoComplete=new Array();
		var autoCompIdx=0;

		for (i=0;i<servicesList.length;i++){
			if (servicesList[i].isHitLoggingEnabled==1){
				serviceListAutoComplete[autoCompIdx++]=servicesList[i].serviceName;
			}
		}

		$( "#resourceName" ).autocomplete({
						source: serviceListAutoComplete,
						minLength: 0
		});
	}

}

/* Populate autocomplete list for user field */
function populateUsers(usersList){

	if (usersList.length>0){
		var usersListAutoComplete=new Array();
		var autoCompIdx=0;

		usersListAutoComplete[autoCompIdx++]="*** Any ***"
		for (i=0;i<usersList.length;i++){
			usersListAutoComplete[autoCompIdx++]=usersList[i].userName;
		}

		$( "#userName" ).autocomplete({
						source: usersListAutoComplete,
						minLength: 0
		});
	}

}

/* Initialize search parameters and start seach */
function startSearchCounters(){
	counterSearch_userName=document.getElementById("userName").value;
	counterSearch_resourceName=document.getElementById("resourceName").value;
	counterSearch_timeUnit=document.getElementById("timeUnit").value;

	executeSearch();
}

/* Load excedeed counters and display */
function searchExcedeedCounters(){
	prms="";
	prms=prms + "userNameFilter=" + encodeURIComponent(getFilterValue('userNameFilter'));
	prms=prms + "&resourceNameFilter=" + encodeURIComponent(getFilterValue('serviceNameFilter'));
	$.ajax({
		  url: "counters/excedeed/",
		  dataType: 'json',
		  type:'GET',
		  data: prms,
		  success: displayExcedeedCounterList,
		  error: displayErrorV2
		});
}

/* Search and load counters and display */
function executeSearch(){
	queryString="";
	if (counterSearch_resourceName != "All"){
		queryString+="resourceName=" + encodeURIComponent(counterSearch_resourceName) + "&";
	}

	if (counterSearch_userName == "None"){
		queryString+="userName=" ;
	}else if (counterSearch_userName != "All"){
		queryString+="userName=" + encodeURIComponent(counterSearch_userName) ;
	}

	if (counterSearch_timeUnit != "All"){
		if (queryString != ""){
			queryString+="&";
		}
		queryString+="timeUnit=" + encodeURIComponent(counterSearch_timeUnit) ;
	}
	$.ajax({
		  url: "counters/?" + queryString,
		  dataType: 'json',
		  type:'GET',
		  success: displayCounterList,
		  error: displayErrorV2
		});

}

/* Load search counters templates and display */
function searchCounters(){
	$.get("resources/templates/counterSearch.php", function(data){
		$("#content").html(data);
		startPopulateServices();
		startPopulateUsers();
	});


}

/* Attach Event to UI main menu controls */
$(
	function (){
		$('#searchCounter').click(searchCounters);
		$('#searchExcedeedCounters').click(searchExcedeedCounters);
	}
)
