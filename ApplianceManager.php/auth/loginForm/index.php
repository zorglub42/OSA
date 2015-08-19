<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>OSA default login form</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="css/bootstrap-3.0.3.min.css" rel="stylesheet" id="bootstrap-css">
	<link href="css/loginForm.css" rel="stylesheet" id="loginForm-css">
    <script src="js/wait.js"></script>
    <script src="js/jquery-1.8.2.js"></script>
    <script src="js/bootstrap-3.0.0.min.js"></script>
	<script>
		function handleKey(e) {
			if (e.keyCode == 13) {
				doLogin();
				return false;
			}
		}
		function doLogin(){
			showWait();
			params="userName=" + encodeURI($("#username").val()) + "&password=" + encodeURI($("#password").val()) + "&d=" + encodeURI(dom);
			$.ajax({
				  url: "/ApplianceManagerAdmin/auth/login",  
				  dataType: 'json',
				  type:"POST",
				  data: params,
				  success: loggedIn,
				  error: displayError
				});
		}

		function loggedIn(generatedToken){
			hideWait();
			if (loc != ''){
				window.location=loc;
			}else{
				alert("Login is successfull");
			}
		}	
		function displayError(jqXHR, textStatus, errorThrown){
			hideWait();
			eval("err =" + jqXHR.responseText);
			if (err.error.code==401){
				showError("Invalid user/password......");
			}else{
				showError(err.error.message);
			}
		} 

		<?php
		if (isset($_REQUEST["l"])) {
			echo "var loc=\"" . base64_decode($_REQUEST["l"]) . "\";\n";
		}else{ 
			echo "var loc=\"\";\n";
		}
		if (isset($_REQUEST["d"])) {
			echo "var dom=\"" . $_REQUEST["d"] . "\";\n";
		}else{ 
			echo "var dom=\"\";\n";
		}
		?>
	</script>

</head>
<body>
	<div id="waitScreen" class="rounded-corners"  style="position: absolute; z-index:3; visibility: hidden; background-color:#000000;  "></div>
	<div class="container">
		<div class="row vertical-offset-100">
			<div class="col-md-4 col-md-offset-4">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title"><b><? if (isset($_REQUEST["l"])) echo base64_decode($_REQUEST["l"]) ?></b></h3>
					</div>
					<div id="error" class="errorMessage"></div>
					<div class="panel-body">
						<form accept-charset="UTF-8" role="form" onkeypress="return handleKey(event)">
						<img src="images/LogoTitle.jpg" class="img-responsive" ><br>
						<fieldset>
							<div class="form-group">
								<input class="form-control" placeholder="User name" id="username" type="text">
							</div>
							<div class="form-group">
								<input class="form-control" placeholder="Password" name="password" type="password" id="password" value="">
							</div>

						</fieldset>
						</form>
					</div>
					<div class="panel-footer">
							<input class="btn btn-lg btn-info btn-block" type="button" value="Ok" id="loginBtn">
					</div>
				</div>
			</div>
		</div>
	</div>
	<script>
			$('#loginBtn').click(doLogin);
			$('#username').focus();
			<?if (isset($_REQUEST["cause"])){
				if ($_REQUEST["cause"] == "authorization"){
					$msg="D&eacute;sol&eacute; mais tapadroit d'aller sur " . base64_decode($_REQUEST["l"]);
				}else{
					$msg=$_REQUEST["cause"];
				}
			?>
				showError('<?echo $msg?>');
			<?}else{?>
				$('#error').hide();
			<?}?>
			
		</script>

</body>
</html>
