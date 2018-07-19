<?php
/**
 * Reverse Proxy as a service
 * 
 * PHP Version 7.0
 * 
 * @category ReverseProxy
 * @package  OSA
 * @author   Benoit HERARD <benoit.herard@orange.com>
 * @license  http://www.apache.org/licenses/LICENSE-2.0.htm Apache 2 license
 * @link     https://github.com/zorglub42/OSA/
 * 
 * @codingStandardsIgnoreStart
*/
require_once "../../include/Localization.php";
?>
<div class="row" id="sessionsList">
	<div class="col-md-10 col-md-offset-1 col-xs-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><b>{sessionList.length} <?php echo Localization::getString("session.list.found")?></b></h3>
			</div>
			<div class="panel-body">
				<div class="row list-group-item header" >
						<div class="col-xs-3 col-md-3 ellipsis"><?php echo Localization::getString("session.list.id")?></div>
						<div class="col-xs-3 col-md-3 ellipsis"><?php echo Localization::getString("session.list.userName")?></div>
						<div class="col-xs-4 col-md-4 ellipsis"><?php echo Localization::getString("session.list.validUntil")?></div>
						<div class="col-xs-2 col-md-2 ellipsis"><?php echo Localization::getString("list.actions")?></div>
				</div>
				<div class="list-group" id="data">
					<a class="list-group-item row" id="rowTpl" style="display:none" >
						<div ondblclick="startEditUser('./users/{sessionList[i].userName}')">
							<div class="col-xs-3 col-md-3 ellipsis" title="{sessionList[i].id}">{sessionList[i].id}</div>
							<div class="col-xs-3 col-md-3 ellipsis" title="{sessionList[i].userName}">{sessionList[i].userName}</div>
							<div class="col-xs-4 col-md-4 ellipsis" title="{sessionList[i].validUntil}">{sessionList[i].validUntil}</div>
							<div class="col-xs-2 col-md-2 ">
								<button type="button" class="btn btn-default" id="btnDelete" title="<?php echo Localization::getString("session.close.tooltip")?>" onclick="closeSession('{sessionList[i].id}', '{sessionList[i].uri}')">
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
					</div>
				</div>
			</div>
	</div>
</div>
