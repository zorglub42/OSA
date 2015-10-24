<?php
require_once "../../include/Localization.php";
?>
<div class="row">
	<div class="col-md-10 col-md-offset-1">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><b><?php echo Localization::getString("service.properties")?></b></h3>
			</div>

			<form>
				<?php include "serviceProperties.php"?>
				<div class="panel-footer">
						<div class="row">
							<div class="col-md-2 col-md-offset-5 col-xs-6 col-xs-offset-3">
								<button type="button" class="btn btn-default" id="saveService" onclick="updateService('{uri}')" >
									<span><?php echo Localization::getString("button.ok")?></span>
								</button>
								<button type="button" class="btn btn-info" onclick="showServices()">
									<span><?php echo Localization::getString("button.cancel")?></span>
								</button>
							</div>
						</div>
				</div>
			</form>
		</div>
	<div>
</div>
