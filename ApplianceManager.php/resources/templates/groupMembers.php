<?php
require_once "../../include/Localization.php";
?>
<div class="row">
	<div class="col-md-10 col-md-offset-1">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><b><?php echo Localization::getString("group.members")?></b></h3>
			</div>
			<div class="panel-body">
				<div class="row list-group-item header" >
						<div class="col-xs-3 col-md-3 ellipsis"><?php echo Localization::getString("user.list.userName")?></div>
						<div class="col-xs-3 col-md-3 ellipsis"><?php echo Localization::getString("user.list.firstName")?></div>
						<div class="col-xs-3 col-md-3 ellipsis"><?php echo Localization::getString("user.list.lastName")?></div>
						<div class="col-xs-3 col-md-3 ellipsis"><?php echo Localization::getString("user.list.email")?></div>
				</div>
				<div class="list-group" id="data">
					<a class="list-group-item row" id="rowTpl" style="display:none" >
						<div class="col-xs-3 col-md-3 ellipsis" title="{userList[i].userName}">{userList[i].userName}</div>
						<div class="col-xs-3 col-md-3 ellipsis" title="{userList[i].firstName}">{userList[i].firstName}</div>
						<div class="col-xs-3 col-md-3 ellipsis" title="{userList[i].lastName}">{userList[i].lastName}</div>
						<div class="col-xs-3 col-md-3 ellipsis" title="{userList[i].emailAddress}">{userList[i].emailAddress}</div>
					</a>
				</div>
				<div class="row">
					<div class="col-md-2 col-md-offset-5">
						<button type="button" class="btn btn-info" onclick="editGroup(currentGroup)">
							<span><?php echo Localization::getString("button.ok")?></span>
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
