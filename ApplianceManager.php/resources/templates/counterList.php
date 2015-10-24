<?php
require_once "../../include/Localization.php";
?>
<?php
require_once "../../include/Localization.php";
?>
<div class="row">
	<div class="col-md-10 col-md-offset-1">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><b>{counterList.length} <?php echo Localization::getString("counter.list.found")?></b></h3>
			</div>
			<div class="panel-body">
				<div class="row list-group-item header" >
						<div class="col-xs-2 col-md-3 ellipsis" title="<?php echo Localization::getString("service.list.name")?>"><?php echo Localization::getString("service.list.name")?></div>
						<div class="col-xs-2 col-md-3 ellipsis" title="<?php echo Localization::getString("user.list.userName")?>"><?php echo Localization::getString("user.list.userName")?></div>
						<div class="col-xs-1 col-md-1 ellipsis" title="<?php echo Localization::getString("counter.list.timeunit")?>"><?php echo Localization::getString("counter.list.timeunit")?></div>
						<div class="col-xs-2 col-md-2 ellipsis" title="<?php echo Localization::getString("counter.list.date")?>"><?php echo Localization::getString("counter.list.date")?></div>
						<div class="col-xs-1 col-md-1 ellipsis" title="<?php echo Localization::getString("counter.list.value")?>"><?php echo Localization::getString("counter.list.value")?></div>
						<div class="col-xs-3 col-md-2 ellipsis" title="<?php echo Localization::getString("list.actions")?>"><?php echo Localization::getString("list.actions")?></div>
				</div>
				<div class="list-group" id="data" >
					<a class="list-group-item row" id="rowTpl" style="display:none" >
						<div class="col-xs-2 col-md-3 ellipsis" title="{counterList[i].resourceName}">{counterList[i].resourceName}</div>
						<div class="col-xs-2 col-md-3 ellipsis" title="{counterList[i].userName}">{counterList[i].userName}</div>
						<div class="col-xs-1 col-md-1 ellipsis" title="{counterList[i].timeUnit}">{counterList[i].timeUnit}</div>
						<div class="col-xs-2 col-md-2 ellipsis" title="{counterList[i].timeValue}">{counterList[i].timeValue}</div>
						<div class="col-xs-1 col-md-1 ellipsis" title="{counterList[i].value}">{counterList[i].value}</div>
						<div class="col-xs-3 col-md-2">
								<button type="button" class="btn btn-default" id="btnEdit" title="<?php echo Localization::getString("counter.edit.tooltip")?>" onclick="startEditCounter('{i}')"">
								  <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
								</button>
								<button type="button" class="btn btn-default" id="btnDelete" title="<?php echo Localization::getString("counter.delete.tooltip")?>" onclick="deleteCounter('{i}')">
								  <span class="glyphicon glyphicon glyphicon-trash" aria-hidden="true"></span>
								</button>
							</div>
						</div>
					</a>
				</div>
			</div>
			<div class="panel-footer">
				<div class="row">
					<div class="col-md-2 col-md-offset-5 col-xs-2 col-xs-offset-5">
						<button type="button" class="btn btn-info" id="search" title="<?php echo Localization::getString("button.searchRefresh.tooltip")?>" onclick="executeSearch()">
						  <span class="glyphicon glyphicon-search" aria-hidden="true" ></span> <?php echo Localization::getString("button.searchRefresh")?></span>
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
