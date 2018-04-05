<?php
/**
 *  Reverse Proxy as a service
 * 
 * PHP Version 7.0
 * 
 * @category ReverseProxy
 * @package  OSA
 * @author   Benoit HERARD <benoit.herard@orange.com>
 * @license  http://www.apache.org/licenses/LICENSE-2.0.htm Apache 2 license
 * @link     https://github.com/zorglub42/OSA/
*/
?>
<html>
	<head>
		<title>OSA default logout form</title>
		<script src="./js/jquery-1.8.2.js">
		</script>
		<link rel="stylesheet" type="text/css" href="./css/osa.css">
		<script>
			function doLogout(){
				$.ajax({
					  url: "../logout",  
					  dataType: 'json',
					  type:"DELETE",
					  success: loggedOut,
					  error: displayError
					});
			}

			function loggedOut(user){
				$("#bye").html("Bye bye <b>" + user + "</b>!");
			}	
			function displayError(jqXHR, textStatus, errorThrown){
				eval("err =" + jqXHR.responseText);
				if (err.error.code==401){
					$('#error').html("Wrong password or username on not subscribed to authentication service");
				}else{
					$('#error').html(err.error.message);
				}
				$('#error').show();
			} 

		</script>
	</head>
	<body>
		<div id="logo" style="padding-left:10px;">
			<img src="./images/LogoTitle.jpg" />
		</div>
		<div id="title">
			<div style="padding-left:20px;">
				
			</div>
		</div>
		<div id="content">
			<br>
			<div id="error" class="errorMessage"></div>
			<br>
			<center>
			<h1><div id="bye"></div></h1>
			</center>
		</div>
		<script>
			$('#error').hide();
			doLogout();
 		</script>
	</body>
</html>
