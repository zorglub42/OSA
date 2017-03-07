<?php
require_once "../../include/Localization.php";
?>
		<div class="row" id="groupEdit">
			<div class="col-md-10 col-md-offset-1">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title"><b><?php echo Localization::getString("group.properties")?></b></h3>
					</div>
					<?php include "groupProperties.php"?>
					<div class="panel-footer">
							<div class="row">
								<div class="col-md-3 col-md-offset-5 col-xs-4 col-xs-offset-4">
									<button type="button" class="btn btn-default" id="saveGroup" onclick="updateGroup('{group.uri}')">
										<span><?php echo Localization::getString("button.ok")?></span>
									</button>
									<button type="button" class="btn btn-info" onclick="showGroups()">
										<span><?php echo Localization::getString("button.cancel")?></span>
									</button>
									<button type="button" class="btn btn-info" onclick="showMembers()">
										<span><?php echo Localization::getString("button.members")?></span>
									</button>
								</div>
							</div>
					</div>
				</div>
			</div>
		</div>
