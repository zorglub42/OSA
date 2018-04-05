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
<div class="row" id="logsSearch">
	<div class="col-md-10 col-md-offset-1">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><b><?php echo Localization::getString("log.search.title")?></b></h3>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-6 col-xs-6">
						<label for="serviceName"><?php echo Localization::getString("log.label.serviceName")?></label>
						<input class="form-control"  type="text" id="serviceName" value="{logSearch_serviceName}" onfocus="javascript:$(this).autocomplete('search',$(this).value);">
					</div>
					<div class="col-md-6 col-xs-6">
						<label for="userName"><?php echo Localization::getString("log.label.userName")?></label>
						<input class="form-control"   type="text" id="userName" value="{logSearch_userName}"onfocus="javascript:$(this).autocomplete('search',$(this).value);">
					</div>
				</div>
				<div class="row">
					<div class="col-md-6 col-xs-6">
						<label for="frontEndEndPoint"><?php echo Localization::getString("log.label.frontEndEndPoint")?></label>
						<input class="form-control"  type="text" value="{logSearch_frontEndEndPoint}" id="frontEndEndPoint">
					</div>
					<div class="col-md-6 col-xs-6">
						<label for="httpStatus"><?php echo Localization::getString("log.label.httpStatus")?></label>
						<input  class="form-control" type="text" value="{logSearch_status}"id="httpStatus">
					</div>
				</div>
				<div class="row">
					<div class="col-md-4 col-xs-4">
						<label for="message"><?php echo Localization::getString("log.label.message")?></label>
						<input class="form-control"  type="text" value="{logSearch_message}" id="message">
					</div>
					<div class="col-md-4 col-xs-4">
						<label for="from"><?php echo Localization::getString("log.label.from")?></label>
						<input  class="form-control" value="{logSearch_from}" type="text" id="from">
					</div>
					<div class="col-md-4 col-xs-4">
						<label for="until"><?php echo Localization::getString("log.label.until")?></label>
						<input  class="form-control"  type="text" value="{logSearch_until}" id="until">
					</div>
				</div>
			</div>
			<div class="panel-footer">
				<div class="row">
					<div class="col-md-4 col-md-offset-5 col-xs-4 col-xs-offset-5">
						<button type="button" class="btn btn-info" id="search" title="<?php echo Localization::getString("button.search.tooltip")?>" onclick="startSearchLogs()">
						  <span class="glyphicon glyphicon-search" aria-hidden="true" ></span> <?php echo Localization::getString("button.search")?></span>
						</button>
						<button type="button" class="btn btn-info" id="search" title="<?php echo Localization::getString("button.rest.tooltip")?>" onclick="resetSearchLogs()">
						  <span  aria-hidden="true" ></span> <?php echo Localization::getString("button.reset")?></span>
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
