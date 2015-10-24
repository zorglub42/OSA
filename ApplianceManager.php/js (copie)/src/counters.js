
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


var resourceNameToolTip="Choose resource on which you want to search counters.";
var userNameToolTip="Choose user for which you want to search counters.";
var timeUnitToolTip="Choose the time unit (second/day/month) for which you want to search counters.";
var counterValueToolTip="Set the value for this counter.";
var editCounterToolTip="Edit this counter";
var deleteCounterToolTip="Delete this counter";

var backList;

function setCounterModified(isModified){
	counterModified=isModified;
	if (isModified){
		setActionButtonEnabled('save',true);
	}else{
		setActionButtonEnabled('save',false);
	}
}



function updateCounter(counterURI) {
	saveOrUpdateCounter('PUT');
}

function saveOrUpdateCounter(method){
	//currentCounterUri="counters/" + encodeURIComponent(currentCounterURI);
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


function startEditCounter(counterNum){
	
	currentCounterUri=currentCounterList[counterNum].uri;
	editCurrentCounter();
}
function editCurrentCounter(){
	$.getJSON(currentCounterUri, editCounter).error(displayErrorV2);
}

function editCounter(counter){
	
	$.get("resources/templates/counterEdit.php", function(data){
		$("#content").html(data.replaceAll("{counter.resourceName}", counter.resourceName)
							   .replaceAll("{counter.userName}", counter.userName)
							   .replaceAll("{counter.timeUnit}", counter.timeUnit)
							   .replaceAll("{counter.timeValue}", counter.timeValue)
							   .replaceAll("{counterValueToolTip}", counterValueToolTip)
							   .replaceAll("{counter.value}", counter.value)
							   .replaceAll("{counter.uri}", counter.uri)
		);
		currentCounter=counter;
		currentCounterUi=counter.uri;
		setCounterModified(false);
	});
}

			
			
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
											 .replaceAll("{editCounterToolTip}", editCounterToolTip)
											 .replaceAll("{deleteCounterToolTip}", deleteCounterToolTip)
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
			$("table.scroll").createScrollableTable({
				width: '800px',
				height: '350px',
				border: '0px'
			});
			touchScroll("countersList_body_wrap");
		}else{
			$('#countersList').hide();
		}
	});
				

}

function resetExceededCountersFilter(){
	$('#serviceNameFilter').val("");
	$('#userNameFilter').val("");
	searchExcedeedCounters();
	
}

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
											 .replaceAll("{editCounterToolTip}", editCounterToolTip)
											 .replaceAll("{deleteCounterToolTip}", deleteCounterToolTip)
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
			$("table.scroll").createScrollableTable({
				width: '800px',
				height: '350px',
				border: '0px'
			});
			touchScroll("countersList_body_wrap");
		}else{
			$('#countersList').hide();
		}
	});
}


function deleteCounter(counterNum){
	
	
	if (confirm("ZZOPEN echo Localization::getJSString(QUOTEcounter.delete.confirmQUOTE)ZZCLOSE")){
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


function showCounters(){
	/*$.ajax({
		  url: './counter/',
		  dataType: 'json',
		  //data: data,
		  success: displayCounterList,
		  error: displayErrorV2
		});*/

	$.getJSON("./counters/?order=counterName", displayCounterList).error(displayErrorV2);
}

function startPopulateServices(){
	$.getJSON("services/?withQuotas&order=serviceName", populateServices).error(displayErrorV2);
}
function startPopulateUsers(){
	$.getJSON("users/?order=userName", populateUsers).error(displayErrorV2);
}
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


function startSearchCounters(){
	counterSearch_userName=document.getElementById("userName").value;
	counterSearch_resourceName=document.getElementById("resourceName").value;
	counterSearch_timeUnit=document.getElementById("timeUnit").value;

	executeSearch();
}

function handelExcedeedCountersFilterFormKeypress(e) {
	if (e.keyCode == 13) {
		searchExcedeedCounters();
		return false;
	}
}					
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

function searchCounters(){
	
	$.get("resources/templates/counterSearch.php", function(data){
		$("#content").html(data);




	
		startPopulateServices();
		startPopulateUsers();
	});
	
	
}

//Event 			
$(
	function (){
		$('#searchCounter').click(searchCounters);
		$('#searchExcedeedCounters').click(searchExcedeedCounters);
	}
)
