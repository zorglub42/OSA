<?php
require_once "../../include/Localization.php";
?>
<div class="row">
	<div class="col-md-10 col-md-offset-1">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><b>{userList.length} <?php echo Localization::getString("user.list.found")?></b></h3>
			</div>
			<div class="panel-body">
				<form accept-charset="UTF-8" role="form"  onkeypress="return handelUserFilterFormKeypress(event)">
					<fieldset>
						<div class="form-group">
							<div class="row">
								<div class="col-md-4 col-xs-4 search-control">
									<input class="form-control" placeholder="<?php echo Localization::getString("user.userName.placeholder")?>"  id="userNameFilter" value="{userNameFilterPrevVal}">
								</div>
								<div class="col-md-4 col-xs-4 search-control">
									<input class="form-control" placeholder="<?php echo Localization::getString("user.email.placeholder")?>" id="emailAddressFilter" value="{emailAddressFilterPrevVal}">
								</div>
								<div class="col-md-4 col-xs-4 search-control">
									<input class="form-control" placeholder="<?php echo Localization::getString("user.entity.placeholder")?>" id="entityFilter" value="{entityFilterPrevVal}">
								</div>
							</div>
							<div class="row">
								<div class="col-md-4 col-xs-4 search-control">
									<input class="form-control" placeholder="<?php echo Localization::getString("user.firstName.placeholder")?>"   id="firstNameFilter"value="{firstNameFilterPrevVal}">
								</div>
								<div class="col-md-4 col-xs-4 search-control">
									<input class="form-control" placeholder="<?php echo Localization::getString("user.lastName.placeholder")?>" id="lastNameFilter" value="{lastNameFilterPrevVal}">
								</div>
								<div class="col-xs-3 col-md-4 search-control">
									<button type="button" class="btn btn-default" title="<?php echo Localization::getString("button.filter.tooltip")?>" onclick=showUsers()>
										<span><?php echo Localization::getString("button.filter")?></span>
									</button>
									<button type="button" class="btn btn-info" title="<?php echo Localization::getString("button.reset.tooltip")?>" onclick=resetUserFilter()>
										<span><?php echo Localization::getString("button.reset")?></span>
									</button>	
								</div>
							</div>
							
						</div>
					</fieldset>
				</form>
				<hr>
				<div class="row list-group-item header" >
						<div class="col-xs-3 col-md-3 ellipsis" title="<?php echo Localization::getString("user.list.userName")?>"><?php echo Localization::getString("user.list.userName")?></div>
						<div class="col-xs-3 col-md-3 ellipsis" title="<?php echo Localization::getString("user.list.email")?>"><?php echo Localization::getString("user.list.email")?></div>
						<div class="col-xs-3 col-md-4 ellipsis" title="<?php echo Localization::getString("user.list.endDate")?>"><?php echo Localization::getString("user.list.endDate")?></div>
						<div class="col-xs-3 col-md-2 ellipsis" title="<?php echo Localization::getString("list.actions")?>"><?php echo Localization::getString("list.actions")?></div>
				</div>
				<div class="list-group" id="data" >
					<a class="list-group-item row" id="rowTpl" style="display:none" >
						<div class="col-xs-3 col-md-3 ellipsis" title="{userList[i].userName}">{userList[i].userName}</div>
						<div class="col-xs-3 col-md-3 ellipsis" title="{userList[i].emailAddress}">{userList[i].emailAddress}</div>
						<div class="col-xs-3 col-md-4 ellipsis" title="{userList[i].endDate}">{userList[i].endDate}</div>
						<div class="col-xs-3 col-md-2">
							<button type="button" class="btn btn-default" id="btnEdit" title="<?php echo Localization::getString("user.edit.tooltip")?>" onclick="startEditUser('{userList[i].uri}')">
							  <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
							</button>
							<button type="button" class="btn btn-default" id="btnDelete" title="<?php echo Localization::getString("user.delete.tooltip")?>" onclick="deleteUser('{userList[i].uri}', '{userList[i].userName}')">
							  <span class="glyphicon glyphicon glyphicon-trash" aria-hidden="true"></span>
							</button>
						</div>
					</a>
				</div>
			</div>
			<div class="panel-footer">
				<div class="row center-block">
					<div class="col-md-offset-5 col-md-2 col-xs-2 col-xs-offset-5">
						<button type="button" class="btn btn-info" id="addUser" title="<?php echo Localization::getString("service.add.tooltip")?>">
						  <span class="glyphicon glyphicon-plus" aria-hidden="true" ></span> <?php echo Localization::getString("button.add")?></span>
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
$(
	function (){
		$('#addUser').click(addUser);
	}
);
					



