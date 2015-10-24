<?php
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
 * File Name   : ApplianceManager/ApplianceManager.php/index.php
 *
 * Created     : 2012-02
 * Authors     : Benoit HERARD <benoit.herard(at)orange.com>
 *
 * Description :
 *      Main HTML page for GUI bootstrapping AJAX app.
 *--------------------------------------------------------
 * History     :
 * 1.0.0 - 2012-10-01 : Release of the file
*/
 
require_once 'include/Constants.php';
require_once 'include/Settings.ini.php';
require_once 'include/Mobile_Detect.php';
require_once 'include/Localization.php';
$firstName="";
$lastName="";
$hdrs=getallheaders();
if (isset($hdrs[firstNameHeader])){
	$firstName=$hdrs[firstNameHeader];
}
if (isset($hdrs[lastNameHeader])){
	$lastName=$hdrs[lastNameHeader];
}
?>
<html>
	<head>
	
		<title><?php echo Localization::getString("app.title")?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="css/osa.css.php">

		<script type="text/javascript" src="js/osa.js.php"></script>
	
		<script>
			var mouseLeaveTimer;
			$('.selector').tooltip().on('mouseleave', function(e){
				var that = this;

				// close the tooltip later (maybe ...)
				mouseLeaveTimer = setTimeout(function(){
					$(that).tooltip('close');
				}, 100);

				// prevent tooltip widget to close the tooltip now
				e.stopImmediatePropagation(); 
			});

			$(document).on('mouseenter', '.ui-tooltip', function(e){
				// cancel tooltip closing on hover
				clearTimeout(mouseLeaveTimer);
			});

			$(document).on('mouseleave', '.ui-tooltip', function(){
				// make sure tooltip is closed when the mouse is gone
				$('.selector').tooltip('close');
			});			
		</script>
	</head>
	<body >
		<div class="container-fluid">
			<div  style="padding-left:10px;">
				<img class="img-responsive"  src="./images/LogoTitle.jpg" />
			</div>	
			<?php include "include/menuBootstrap.php"?>
			<div id="waitScreen" class="rounded-corners"  style="position: absolute; z-index:3; visibility: hidden; background-color:#000000;  ">
			</div>
			<div id="content">
			</div>
			<div id="footer">
				<hr>
				<span class="withRightBorder">
					<?php echo Localization::getString("app.version") . version?>
				</span>
			</div>
		</div>
	</body>
</html>
