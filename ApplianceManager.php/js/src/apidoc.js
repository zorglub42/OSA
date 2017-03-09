/*--------------------------------------------------------
 * Module Name : ApplianceManager
 * Version : 1.0.0
 *
 * Software Name : OpenServicesAccess
 * Version : 1.0
 *
 * Copyright (c) 2011 – 2017 Orange
 * This software is distributed under the Apache 2 license
 * <http://www.apache.org/licenses/LICENSE-2.0.html>
 *
 *--------------------------------------------------------
 * File Name   : ApplianceManager/ApplianceManager.php/js/apidoc.js
 *
 * Created     : 2017-03
 * Authors     : Zorglub42 <contact(at)zorglub42.fr>
 *
 * Description :
 *      Apis documentation management
 *--------------------------------------------------------
 * History     :
 * 1.0.0 - 2017-03-01 : Release of the file
*/			function loadDoc(uri){
				$.get( "resources/templates/apidoc.php", function( data ) {
					$("#content").html(data);
					$("#apidocframe").attr("src", uri);
					console.log($("#apidocframe").attr("src"));
				});
			}
