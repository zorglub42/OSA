<?php
require_once "../../include/Localization.php";
?>
<div class="row" id="logsList">
	<div class="col-md-10 col-md-offset-1">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><b>{logsList.length} <?php echo Localization::getString("log.list.found")?></b></h3>
			</div>
			<div class="panel-body">
				<div class="row list-group-item header" >
						<div class="col-xs-4 col-md-2 ellipsis" title="<?php echo Localization::getString("log.list.servieName")?>"><?php echo Localization::getString("log.list.serviceName")?></div>
						<div class="col-xs-1 col-md-1 ellipsis mobile-optional" title="<?php echo Localization::getString("log.list.userName")?>"><?php echo Localization::	getString("log.list.userName")?></div>
						<div class="col-xs-2 col-md-3 ellipsis mobile-optional" title="<?php echo Localization::getString("log.list.frontEndEndPoint")?>"><?php echo Localization::getString("log.list.frontEndEndPoint")?></div>
						<div class="col-xs-3 col-md-1 ellipsis" title="<?php echo Localization::getString("log.list.status")?>"><?php echo Localization::getString("log.list.status")?></div>
						<div class="col-xs-2 col-md-3 ellipsis mobile-optional" title="<?php echo Localization::getString("log.list.message")?>"><?php echo Localization::getString("log.list.message")?></div>
						<div class="col-xs-5 col-md-2 ellipsis" title="<?php echo Localization::getString("log.list.time")?>"><?php echo Localization::getString("log.list.time")?></div>
				</div>
				<div class="list-group scrollable" id="data">
					<a class="list-group-item row item" id="rowTpl" style="display:none" >
						<div class="col-xs-4 col-md-2 ellipsis" title="{logsList[i].serviceName}">{logsList[i].serviceName}</div>
						<div class="col-xs-1 col-md-1 ellipsis mobile-optional" title="{logsList[i].userName}">{logsList[i].userName}</div>
						<div class="col-xs-2 col-md-3 ellipsis mobile-optional" title="{logsList[i].frontEndUri}">{logsList[i].frontEndUri}</div>
						<div class="col-xs-3 col-md-1 ellipsis" title="{logsList[i].status}">{logsList[i].status}</div>
						<div class="col-xs-2 col-md-3 ellipsis mobile-optional" title="{logsList[i].message}">{logsList[i].message}</div>
						<div class="col-xs-5 col-md-2 ellipsis" title="{timeStamp}">{timeStamp}</div>
					</a>
				</div>
			</div>
			<div class="panel-footer">
				<div class="row">
					<div class="col-md-offset-5 col-md-3 col-xs-3 col-xs-offset-4">
						<button type="button" class="btn btn-info" id="refresh" title="<?php echo Localization::getString("button.searchRefresh.tooltip")?>" onclick="executeSearchLog()">
						  <span class="glyphicon glyphicon-search" aria-hidden="true" ></span> <?php echo Localization::getString("button.searchRefresh")?></span>
						</button>
						<button type="button" class="btn btn-info" id="refresh" onclick="searchLogs()">
						  <span  aria-hidden="true" ></span> <?php echo Localization::getString("button.back")?></span>
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

