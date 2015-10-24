<?php
require_once "../../include/Localization.php";
?>
<div class="row">
	<div class="col-md-10 col-md-offset-1">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><b>{serviceList.length} <?php echo Localization::getString("service.list.found")?></b></h3>
			</div>
			<div class="panel-body">
				<form accept-charset="UTF-8" role="form"  onkeypress="return handelServiceFilterFormKeypress(event)">
					<fieldset>
						<div class="form-group">
							<div class="row">
								<div class="col-md-5 col-xs-4 search-control">
									<input class="form-control" placeholder="<?php echo Localization::getString("service.name.placeholder")?>"  id="serviceNameFilter" value="{serviceNameFilterPrevVal}">
								</div>
								<div class="col-md-5 col-xs-4 search-control">
									<input class="form-control" placeholder="<?php echo Localization::getString("service.groupName.placeholder")?>" id="serviceGroupNameFilter" value="{serviceGroupNameFilterPrevVal}">
								</div>
							</div>
							<div class="row">
								<div class="col-md-5  col-xs-4 search-control" >
									<input class="form-control" placeholder="<?php echo Localization::getString("service.frontendEndpoint.placeholder")?>"   id="frontEndEndPointFilter"value="{frontEndEndPointFilterPrevVal}">
								</div>
								<div class="col-md-5 col-xs-4 search-control">
									<input class="form-control" placeholder="<?php echo Localization::getString("service.backendEndpoint.placeholder")?>" id="backEndEndPointFilter" value="{backEndEndPointFilterPrevVal}">
								</div>
								<div col="col-xs-4 col-md-2">
										<button type="button" class="btn btn-default" title="<?php echo Localization::getString("button.filter.tooltip")?>" onclick=showServices()>
											<span><?php echo Localization::getString("button.filter")?></span>
										</button>
										<button type="button" class="btn btn-info" title="<?php echo Localization::getString("button.reset.tooltip")?>" onclick=resetServiceFilter()>
											<span><?php echo Localization::getString("button.reset")?></span>
										</button>
								</div>	
							</div>
							
						</div>
					</fieldset>
				</form>
				<hr>
				<div class="row list-group-item header" >
						<div class="col-xs-4 col-md-2 ellipsis" title="<?php echo Localization::getString("service.list.name")?>"><?php echo Localization::getString("service.list.name")?></div>
						<div class="col-xs-1 col-md-1 ellipsis mobile-optional" title="<?php echo Localization::getString("service.list.published")?>"><?php echo Localization::getString("service.list.published")?></div>
						<div class="col-xs-2 col-md-2 ellipsis mobile-optional" title="<?php echo Localization::getString("service.list.groupName")?>"><?php echo Localization::getString("service.list.groupName")?></div>
						<div class="col-xs-4 col-md-3 ellipsis" title="<?php echo Localization::getString("service.list.frontendEndpoint")?>"><?php echo Localization::getString("service.list.frontendEndpoint")?></div>
						<div class="col-xs-2 col-md-3 ellipsis mobile-optional" title="<?php echo Localization::getString("service.list.backendEndpoint")?>"><?php echo Localization::getString("service.list.backendEndpoint")?></div>
						<div class="col-xs-4 col-md-1 ellipsis" title="<?php echo Localization::getString("list.actions")?>"><?php echo Localization::getString("list.actions")?></div>
				</div>
				<div class="list-group" id="data" >
					<a class="list-group-item row" id="rowTpl" style="display:none" >
						<div class="col-xs-4 col-md-2 ellipsis" title="{serviceList[i].serviceName}">{serviceList[i].serviceName}</div>
						<div class="col-xs-1 col-md-1 ellipsis mobile-optional"><input type="checkbox" title="<?php echo Localization::getString("service.isPublished.tooltip")?>" id="isPublished{i}" {serviceList[i].cbPublishedCheck} disabled><label for="isPublished{i}"></label></div>
						<div class="col-xs-2 col-md-2 ellipsis mobile-optional" title="{serviceList[i].groupName}">{serviceList[i].groupName}</div>
						<div class="col-xs-4 col-md-3 ellipsis" title="{serviceList[i].frontEndEndPoint}">{serviceList[i].frontEndEndPoint}</div>
						<div class="col-xs-2 col-md-3 ellipsis mobile-optional" title="{serviceList[i].backEndEndPoint}">{serviceList[i].backEndEndPoint}</div>
						<div class="col-xs-4 col-md-1">
								<button type="button" class="btn btn-default" id="btnEdit" title="<?php echo Localization::getString("service.edit.tooltip")?>" onclick="startEditService('{serviceList[i].uri}')">
								  <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
								</button>
								<button type="button" class="btn btn-default" id="btnDelete" title="<?php echo Localization::getString("service.delete.tooltip")?>" onclick="deleteService('{serviceList[i].uri}', '{serviceList[i].serviceName}')">
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
						<button type="button" class="btn btn-info" id="addService" title="<?php echo Localization::getString("service.add.tooltip")?>">
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
		$('#addService').click(addService);
	}
);

</script>
