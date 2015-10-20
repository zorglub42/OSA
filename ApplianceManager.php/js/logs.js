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
 * File Name   : ApplianceManager/ApplianceManager.php/js/logs.js
 *
 * Created     : 2012-02
 * Authors     : Benoit HERARD <benoit.herard(at)orange.com>
 *
 * Description :
 *      AJAX Management for logs
 *--------------------------------------------------------
 * History     :
 * 1.0.0 - 2012-10-01 : Release of the file
*/

var	logSearch_userName="";
var logSearch_serviceName="";
var logSearch_status="";
var logSearch_message="";
var logSearch_from="";
var	logSearch_until="";
var logSearch_frontEndEndPoint="";

var logRegExComp="\n(may include * for any string)";
var logServiceNameToolTip="Choose the service for which you want to find logs or leave empty for any." + logRegExComp;
var logUserNameToolTip="Choose the user for which you want to find logs or leaves empty for any." + logRegExComp;
var logHTTPStatusToolTip="Enter the HTTP Status of the response send to consumer for which you want to find logs or leave empty for any.";
var logFromToolTip="Enter the date/time from which logs where recorded or leave empty for any.";
var logUntilToolTip="Enter date/time until which logs where recorded. Leave empty for any.";
var logMessageToolTip="Enter the error message returned to consumer. Leave empty for any" + logRegExComp;
var logFrontEndEndPointToolTip="Enter the frontend URL prefix. Leave empty for any" + logRegExComp;

var logRowPattern=null;
var nextFetch="";
var previousFetch="";
var fetchingMore=0;

function resetSearchLogs(){
	logSearch_userName="";
	logSearch_serviceName="";
	logSearch_status="";
	logSearch_message="";
	logSearch_from="";
	logSearch_until="";
	logSearch_frontEndEndPoint="";
	
	searchLogs();
}

function generateLogsTableRows(logsList){

		table=document.getElementById("data");
		if (logRowPattern==null){
			logRowPattern=document.getElementById("rowTpl");
			table.removeChild(logRowPattern);
		}
		for (i=0;i<logsList.length;i++){
			D= new Date();
			D.setISO8601(logsList[i].timeStamp);
			timeStamp= D.format("mm/dd/yyyy HH:MM:ss");

			
			newRow=logRowPattern.cloneNode(true);
			newRow.removeAttribute('id');
			newRow.removeAttribute('style');
			newRow.className="tabular_table_body" +  (i%2) + " " + newRow.className ;
			newRow.innerHTML=newRow.innerHTML.replaceAll("{logsList[i].serviceName}", logsList[i].serviceName)
											 .replaceAll("{logsList[i].userName}", logsList[i].userName)
											 .replaceAll("{logsList[i].frontEndUri}", logsList[i].frontEndUri)
											 .replaceAll("{logsList[i].status}", logsList[i].status)
											 .replaceAll("{logsList[i].message}", logsList[i].message)
											 .replaceAll("{timeStamp}", timeStamp);
			table.appendChild(newRow);
		}
}

function displayLogsList(logsList){
	
	$.get("resources/templates/logList.html", function (data){
		
		$("#content").html(data.replaceAll("{logsList.length}",logsList.length));
		logRowPattern=null;
		if (logsList.logs.length>0){
			nextFetch = logsList.next;
			previousFetch=logsList.previous;
			generateLogsTableRows(logsList.logs);
		}else{
			$("#logsList").hide();
			nextFetch = "";
			previousFetch="";
		}
		fetchingMore=0;
		nextFetch = logsList.next;
		previousFetch=logsList.previous;
		hideWait();
		/* make the table scrollable with a fixed header */
		$("table.scroll").createScrollableTable({
			width: '800px',
			height: '350px',
			border: '0px'
		});
		$("#logsList_body_wrap").scroll(function(){
			var item=$('.item:last');
			var wrap=$("#logsList_body_wrap");
				/* If last item appear and we are not fetching and there something to fetch then fetch next*/
				if ((wrap.offset().top + wrap.height() - item.height() )> item.offset().top && fetchingMore==0 && nextFetch!=""){
					fetchingMore=1;
					showWait();
					$.ajax({
						url: nextFetch,
						dataType: 'json',
						type:'GET',
						success: addFetch,
						error: displayError
					});

				}
					
		});
		touchScroll("logsList_body_wrap");
	});
}

function addFetch(logsList){
		hideWait();
		fetchingMore=0;
		nextFetch = logsList.next;
		previousFetch=logsList.previous;
		$("#logsCount").html(logsList.length + " hits found");
		generateLogsTableRows(logsList.logs);
}

function startPopulateAutoCompleteServices(){
	$.getJSON("services/?withLog=&order=serviceName", populatePopulateAutoCompleteServices).error(displayError);
}
function startPopulateAutoCompleteUsers(){
	$.getJSON("users/?withLog=&order=userName", populatePopulateAutoCompleteUsers).error(displayError);
}
function populatePopulateAutoCompleteServices(servicesList){
	var serviceListAutoComplete=new Array();
	var autoCompIdx=0;
	for (i=0;i<servicesList.length;i++){
		if (servicesList[i].isHitLoggingEnabled==1){
			serviceListAutoComplete[autoCompIdx++]=servicesList[i].serviceName;
		}
	}

	$( "#serviceName" ).autocomplete({
					source: serviceListAutoComplete,
					minLength: 0
	});
}
function populatePopulateAutoCompleteUsers(usersList){
	var userListAutoComplete=new Array();
	userListAutoComplete[0]="None"
	for (i=0;i<usersList.length;i++){
		userListAutoComplete[i+1]=usersList[i].userName
	}

	$( "#userName" ).autocomplete({
					source: userListAutoComplete,
					minLength: 0
	});
}


function startSearchLogs(){
	logSearch_userName=document.getElementById("userName").value;
	logSearch_serviceName=document.getElementById("serviceName").value;
	logSearch_status=document.getElementById("httpStatus").value;
	logSearch_message=document.getElementById("message").value;
	logSearch_frontEndEndPoint=document.getElementById("frontEndEndPoint").value;
	logSearch_from=document.getElementById("from").value;
	logSearch_until=document.getElementById("until").value;

	executeSearchLog();
}

function executeSearchLog(){
	queryString="";
	if (logSearch_serviceName != ""){
		queryString+="serviceName=" + encodeURIComponent(logSearch_serviceName) + "&";
	}
	
	if (logSearch_userName != ""){
		if (queryString != ""){
			queryString +="&";
		}
		if (logSearch_userName=="None"){
			queryString+="userName=";
		}else{
			queryString+="userName=" + encodeURIComponent(logSearch_userName) ;
		}
	}
	if (logSearch_frontEndEndPoint != ""){
		if (queryString != ""){
			queryString +="&";
		}
		queryString+="frontEndEndPoint=" + encodeURIComponent(logSearch_frontEndEndPoint) ;
	}
	if (logSearch_status != ""){
		if (queryString != ""){
			queryString +="&";
		}
		queryString+="status=" + encodeURIComponent(logSearch_status) ;
	}
	if (logSearch_message != ""){
		if (queryString != ""){
			queryString +="&";
		}
		queryString+="message=" + encodeURIComponent(logSearch_message) ;
	}
	if (logSearch_from != ""){
		if (queryString != ""){
			queryString +="&";
		}
		D = new Date(logSearch_from + ":00");
		//D.setISO8601();
		queryString+="from=" + encodeURIComponent(D.format("isoUtcDateTime")) ;
	}
	if (logSearch_until != ""){
		if (queryString != ""){
			queryString +="&";
		}
		D = new Date(logSearch_until + ":00");
		//D.setISO8601(logSearch_until + ":00");
		queryString+="until=" + encodeURIComponent(D.format("isoUtcDateTime")) ;
	}
	go=true;
	if (queryString == ""){
		go=confirm("Execute a query without any criteria can be very long and generate heavy load on appliance.\nContinue anyway?");
	}
	if (go){
		if (queryString != ""){
			queryString +="&";
		}
		queryString +="order=" + encodeURIComponent("timeStamp desc") ;
		showWait();
		$.ajax({
			  url: "logs/?" + queryString,
			  dataType: 'json',
			  type:'GET',
			  success: displayLogsList,
			  error: displayError
			});
	}
}

function searchLogs(){
	$.get("resources/templates/logSearch.html", function(data){
	
		$("#content").html(data.replaceAll("{logServiceNameToolTip}", logServiceNameToolTip)
							   .replaceAll("{logSearch_serviceName}", logSearch_serviceName)
							   .replaceAll("{logUserNameToolTip}", logUserNameToolTip)
							   .replaceAll("{logSearch_userName}", logSearch_userName)
							   .replaceAll("{logFrontEndEndPointToolTip}", logFrontEndEndPointToolTip)
							   .replaceAll("{logSearch_frontEndEndPoint}", logSearch_frontEndEndPoint)
							   .replaceAll("{logHTTPStatusToolTip}", logHTTPStatusToolTip)
							   .replaceAll("{logSearch_status}", logSearch_status)
							   .replaceAll("{logMessageToolTip}", logMessageToolTip)
							   .replaceAll("{logSearch_message}", logSearch_message)
							   .replaceAll("{logMessageToolTip}", logMessageToolTip)
							   .replaceAll("{logFromToolTip}", logFromToolTip)
							   .replaceAll("{logSearch_from}", logSearch_from)
							   .replaceAll("{logUntilToolTip}", logUntilToolTip)
							   .replaceAll("{logSearch_until}", logSearch_until)
		);
		startPopulateAutoCompleteServices();
		startPopulateAutoCompleteUsers();
		$('#from').datetimepicker();
		$('#until').datetimepicker();
	});

}

	
//Event 			
$(
	function (){
		$('#searchLogs').click(searchLogs);
	}
)
