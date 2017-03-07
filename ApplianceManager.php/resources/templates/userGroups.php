<?php
require_once "../../include/Localization.php";
?>
<div class="row" id="userGroups">
	<div class="col-md-10 col-md-offset-1">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><b><?php echo Localization::getString("user.groups")?></b></h3>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-6 col-xs-6" title="<?php echo Localization::getString("user.membership.tooltip")?>">
						<label for="mebership"><?php echo Localization::getString("user.label.membership")?></label>
						<div class="row  list-group-item header" id="membership">
							<div class="col-md-4" title="<?php echo Localization::getString("group.list.name")?>">
								<?php echo Localization::getString("group.list.name")?>
							</div>
							<div class="col-md-4" title="<?php echo Localization::getString("group.list.description")?>">
								<?php echo Localization::getString("group.list.description")?>
							</div>
							<div class="col-md-4" title="<?php echo Localization::getString("list.actions")?>">
								<?php echo Localization::getString("list.actions")?>
							</div>
						</div>
						<div class="list-group" id="data" >
							<div class="row" id="rowTpl" style="display:none">
								<div class="col-md-4 col-xs-4 ellipsis" title="{groupList[i].groupName}">
									{groupList[i].groupName}
								</div>
								<div class="col-md-4 col-xs-4 ellipsis" title="{groupList[i].groupName}">
									{groupList[i].description}
								</div>
								<div class="col-md-4 col-xs-4">
									<button type="button" class="btn btn-default" id="btnDelete" title="<?php echo Localization::getString("user.deleteGroup.tooltip")?>" onclick="deleteUserGroup('groups/{groupList[i].groupName}', '{groupList[i].groupName}',  '{currentUserURI}')">
									  <span class="glyphicon glyphicon glyphicon-trash" aria-hidden="true"></span>
									</button>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-6 col-xs-6" title="<?php echo Localization::getString("user.availableGroups.tooltip")?>">
						<div class="row">
							<div class="col-md-12 col-xs-12">
								<label for="avaialableGroupList"><?php echo Localization::getString("user.label.availableGroups")?></label><br>
								<select id="availableGroupsList" title="{availableGroupsToolTip}" name="availableGroupsList" id="availableGroupsList" size="15" multiple  class="availableGroupsList">
								</select>
							</div>
							<div class="row">
								<div class="col-md-2 col-md-offset-5 col-xs-2 col-xs-offset-5">
									<br>
									<button type="button" class="btn btn-info" id="addGroups" title="<?php echo Localization::getString("user.addGroups.tooltip")?>" onclick="addGroupToUser('{currentUser.uri}')" >
										<span><<</span>
									</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="panel-footer">
				<div class="row">
					<div class="col-md-offset-5 col-md-2 col-xs-2 col-xs-offset-5 ">
						<button type="button" class="btn btn-default"   onclick="startEditUser('{currentUser.uri}')" >
							<span><?php echo Localization::getString("button.ok")?></span>
						</button>
					</div>
				</div>
			</div>
		<div>
	</div>
</div>
