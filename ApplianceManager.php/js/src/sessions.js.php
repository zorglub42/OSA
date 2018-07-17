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
 * Copyright (c) 2011 – 2018 Orange
 * This software is distributed under the Apache 2 license
 * <http://www.apache.org/licenses/LICENSE-2.0.html>
 *
 *--------------------------------------------------------
 * File Name   : ApplianceManager/ApplianceManager.php/js/groups.js
 *
 * Created     : 2018-07
 * Authors     : Benoit HERARD <benoit.herard(at)orange.com>
 *
 * Description :
 *      .../...
 *--------------------------------------------------------
 * History     :
 * 1.0.0 - 2018-07-17 : Release of the file
*/

/* Handle key press on group filter form to apply filter when "enter" key
* is pressed */
function handelGroupFilterFormKeypress(e) {
	if (e.keyCode == 13) {
		showGroups();
		return false;
	}
}

/* Load group list template and display */
function displaySessionList(sessionList){

	$.get( "resources/templates/sessionList.php", function( data ) {

			$( "#content" ).html( data.replaceAll("{sessionList.length}", sessionList.length )
								);
			table=document.getElementById("data");
			rowPattern=document.getElementById("rowTpl");
			table.removeChild(rowPattern);


			for (i=0;i<sessionList.length;i++){

				var d = new Date();
				d.setISO8601(sessionList[i].validUntil);
				dateFormated=d.format("<?php echo Localization::getJSString("date.format.long")?>")

				newRow=rowPattern.cloneNode(true);
				newRow.removeAttribute('id');
				newRow.removeAttribute('style');
				newRow.className=newRow.className + " tabular_table_body" +  (i%2);
				newRow.innerHTML=newRow.innerHTML.replaceAll("{sessionList[i].id}", sessionList[i].id)
												 .replaceAll("{sessionList[i].userName}", sessionList[i].userName)
												 .replaceAll("{sessionList[i].uri}", sessionList[i].uri)
												 .replaceAll("{sessionList[i].validUntil}", dateFormated);
				table.appendChild(newRow);
			}
	});


}

function closeSession(id, uri){


	if (confirm("<?php echo Localization::getJSString("session.close.confirm")?> " + id + "?")){
		$.ajax({
			  url: uri,
			  dataType: 'json',
			  type:'DELETE',
			  //data: data,
			  success: showSessions,
			  error: displayErrorV2
			});
	}

}

/* Search sessions list and display */
function showSessions(){
	$.ajax({
		url : './auth/sessions/',
		dataType : 'json',
		type : 'GET',
		success : displaySessionList,
		error : displayErrorV2
	});

}


/* Attach Event to UI main menu controls */
$(
		function (){
			
			$('#listSessions').click(showSessions);
		}
	);
