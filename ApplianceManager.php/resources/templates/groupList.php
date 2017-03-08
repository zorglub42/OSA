<?php
require_once "../../include/Localization.php";
?>
<div class="row" id="groupsList">
	<div class="col-md-10 col-md-offset-1 col-xs-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><b>{groupList.length} <?php echo Localization::getString("group.list.found")?></b></h3>
			</div>
			<div class="panel-body">
				<form accept-charset="UTF-8" role="form" onkeypress="return handelGroupFilterFormKeypress(event)">
					<fieldset>
						<div class="form-group">
							<div class="row ">
								<div class="col-xs-4 col-md-4 search-control">
									<input class="form-control" placeholder="<?php echo Localization::getString("group.name.placeholder")?>" id="groupNameFilter" value="{groupNameFilterPrevVal}">
								</div>
								<div class="col-xs-5 col-md-6 search-control">
									<input class="form-control" placeholder="<?php echo Localization::getString("group.description.placeholder")?>" id="groupDescritpionFilter" value="{groupDescritpionFilterPrevVal}">
								</div>
								<div class="col-xs-3 col-md-2 search-control">
									<button type="button" class="btn btn-default" title="<?php echo Localization::getString("button.filter.tooltip")?>" onclick=showGroups()>
										<span><?php echo Localization::getString("button.filter")?></span>
									</button>
									<button type="button" class="btn btn-info"   title="<?php echo Localization::getString("button.reset.tooltip")?>" onclick=resetGroupFilter()>
										<span><?php echo Localization::getString("button.reset")?></span>
									</button>
								</div>
							</div>
							
						</div>
					</fieldset>
				</form>
				<hr>
				<div class="row list-group-item header" >
						<div class="col-xs-4 col-md-4 ellipsis"><?php echo Localization::getString("group.list.name")?></div>
						<div class="col-xs-5 col-md-6 ellipsis"><?php echo Localization::getString("group.list.description")?></div>
						<div class="col-xs-3 col-md-2	 ellipsis"><?php echo Localization::getString("list.actions")?></div>
				</div>
				<div class="list-group" id="data">
					<a class="list-group-item row" id="rowTpl" style="display:none">
						<div ondblclick="startEditGroup('{groupList[i].uri}')">							<div class="col-xs-4 col-md-4 ellipsis" title="{groupList[i].groupName}">{groupList[i].groupName}</div>
							<div class="col-xs-5 col-md-6 ellipsis" title="{groupList[i].description}">{groupList[i].description}</div>
							<div class="col-xs-3 col-md-2 ">
								<button type="button" class="btn btn-default" id="btnEdit" title="<?php echo Localization::getString("group.edit.tooltip")?>" onclick="startEditGroup('{groupList[i].uri}')">
								  <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
								</button>
								<button type="button" class="btn btn-default" id="btnDelete" title="<?php echo Localization::getString("group.edit.tooltip")?>" onclick="deleteGroup('{groupList[i].uri}', '{groupList[i].groupName}')">
								  <span class="glyphicon glyphicon glyphicon-trash" aria-hidden="true"></span>
								</button>
							</div>
						</div>
					</a>
				</div>
			</div>
			<div class="panel-footer">
				<div class="row">
					<div class="col-md-offset-5 col-md-2 col-xs-2 col-xs-offset-5">
						<button type="button" class="btn btn-info" id="addGroup" title="<?php echo Localization::getString("group.add.tooltip")?>">
						  <span class="glyphicon glyphicon-plus" aria-hidden="true" ></span> <?php echo Localization::getString("button.add")?></span>
						</button>
					</div>
				</div>
			</div>
	</div>
</div>
<script>
$(
	function (){
		$('#addGroup').click(addGroup);
	}
);

</script>
