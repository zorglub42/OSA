<?php
require_once "../../include/Localization.php";
?>
<div class="row">
	<div class="col-md-10 col-md-offset-1">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><b><?php echo Localization::getString("user.properties")?></b></h3>
			</div>

			<form>
				<?php include "userProperties.php"?>
			</form>
			<div class="panel-footer">
					<div class="row">
						<div class="col-md-offset-4 col-md-8 col-xs-8 col-xs-offset-3">
							<button type="button" class="btn btn-default" id="saveEdit" onclick="updateUser('{uri}')">
								<span><?php echo Localization::getString("button.ok")?></span>
							</button>
							<button type="button" class="btn btn-info" onclick="showUsers()" >
								<span id="cancel"><?php echo Localization::getString("button.cancel")?></span>
							</button>
							<button type="button" class="btn btn-info" id="groupsEdit" onclick="startDisplayUserGroups('{uri}')">
								<span><?php echo Localization::getString("button.groups")?></span>
							</button>
							<button type="button" class="btn btn-info" id="quotasEdit" onclick="startDisplayUserQuotas('{uri}')">
								<span><?php echo Localization::getString("button.quotas")?></span>
							</button>
						</div>
					</div>
			</div>
		</div>
	<div>
</div>
