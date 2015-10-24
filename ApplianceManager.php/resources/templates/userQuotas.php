<?php
require_once "../../include/Localization.php";
?>
<div class="row">
	<div class="col-md-10 col-md-offset-1">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><b><?php echo Localization::getString("user.quotas")?></b></h3>
			</div>
			<div class="panel-body">
				<div class="row list-group-item header" >
						<div class="col-xs-3 col-md-3 ellipsis" title="<?php echo Localization::getString("service.list.serviceName")?>"><?php echo Localization::getString("service.list.serviceName")?></div>
						<div class="col-xs-2 col-md-2 ellipsis" title="<?php echo Localization::getString("service.list.quotas.reqSec")?>"><?php echo Localization::getString("service.list.quotas.reqSec")?></div>
						<div class="col-xs-2 col-md-2 ellipsis" title="<?php echo Localization::getString("service.list.quotas.reqDay")?>"><?php echo Localization::getString("service.list.quotas.reqDay")?></div>
						<div class="col-xs-2 col-md-2 ellipsis" title="<?php echo Localization::getString("service.list.quotas.reqMonth")?>"><?php echo Localization::getString("service.list.quotas.reqMonth")?></div>
						<div class="col-xs-3 col-md-3 ellipsis" title="<?php echo Localization::getString("list.actions")?>"><?php echo Localization::getString("list.actions")?></div>
				</div>
				<div class="list-group" id="data" >
					<a class="list-group-item row" id="rowTpl" style="display:none" >
						<div class="col-xs-3 col-md-3 ellipsis" title="{quotasList[i].serviceName}">{quotasList[i].serviceName}</div>
						<div class="col-xs-2 col-md-2 ellipsis" title="{quotasList[i].reqSec}">{quotasList[i].reqSec}</div>
						<div class="col-xs-2 col-md-2 ellipsis" title="{quotasList[i].reqDay}">{quotasList[i].reqDay}</div>
						<div class="col-xs-2 col-md-2 ellipsis" title="{quotasList[i].reqMonth}">{quotasList[i].reqMonth}</div>
						<div class="col-xs-3 col-md-2">
							<button type="button" class="btn btn-default" id="btnEdit" title="<?php echo Localization::getString("user.editQuota.tooltip")?>" onclick="startEditUserQuotas('{quotasList[i].uri}')">
							  <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
							</button>
							<button type="button" class="btn btn-default" id="btnDelete" title="<?php echo Localization::getString("user.deleteQuota.tooltip")?>" onclick="deleteUserQuotas('{quotasList[i].uri}', '{quotasList[i].serviceName}')">
							  <span class="glyphicon glyphicon glyphicon-trash" aria-hidden="true"></span>
							</button>
						</div>
					</a>
				</div>
			</div>
			<div class="panel-footer">
				<div class="row">
					<div class="col-md-offset-5 col-md-2 col-xs-4 col-xs-offset-4">
						<button type="button" class="btn btn-default"   onclick="startEditCurrentUser()" >
							<span><?php echo Localization::getString("button.ok")?></span>
						</button>
						<button type="button" class="btn btn-info"   onclick="addUserQuotas()" >
							<span><?php echo Localization::getString("button.add")?></span>
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
