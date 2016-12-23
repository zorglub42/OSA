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
 * File Name   : ApplianceManager/ApplianceManager.php/js/error.js
 *
 * Created     : 2012-02
 * Authors     : Benoit HERARD <benoit.herard(at)orange.com>
 *
 * Description :
 *      AJAX Management for Errors
 *--------------------------------------------------------
 * History     :
 * 1.0.0 - 2012-10-01 : Release of the file
*/


			function displayError(jqXHR, textStatus, errorThrown){
				eval("err =" + jqXHR.responseText);
				alert("An error as occursed: " + err.label + " (HTTP_STATUS=" + jqXHR.status + ")");
				hideWait();
			} 

			function displayErrorV2(jqXHR, textStatus, errorThrown){
				errorText=jqXHR.responseText;
				//eval("err =" + errorText);
				hideWait();
				var err;
				try{
					err=JSON.parse(errorText);
				}catch (e){
					alert("An error as occursed: " + errorText + " (HTTP_STATUS=" + jqXHR.status + ")");
					return;
				}	

				try{
					alert("An error as occursed: " + err.error.message + " (HTTP_STATUS=" + jqXHR.status + ")");
				}catch (e){
					try{
						alert("An error as occursed: " + err.label + " (HTTP_STATUS=" + jqXHR.status + ")");
					}catch (e2){
						alert("An error as occursed: " + errorText + " (HTTP_STATUS=" + jqXHR.status + ")");
					}
				}
			} 
