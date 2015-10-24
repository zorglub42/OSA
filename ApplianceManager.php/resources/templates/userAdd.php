<?php
require_once "../../include/Localization.php";
?>
<div class="row">
	<div class="col-md-10 col-md-offset-1">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><b><?php echo Localization::getString("user.properties.new")?></b></h3>
			</div>

			<?php include "userProperties.php"?>
			<div class="panel-footer">
					<div class="row">
						<div class="col-md-offset-5 col-md-2 col-xs-4 col-xs-offset-4">
							<button type="button" class="btn btn-default" id="saveNew" onclick="saveNewUser()">
								<span><?php echo Localization::getString("button.ok")?></span>
							</button>
							<button type="button" class="btn btn-info" onclick="showUsers()">
								<span><?php echo Localization::getString("button.cancel")?></span>
							</button>
						</div>
					</div>
			</div>
		</div>
	<div>
</div>
