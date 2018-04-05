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
<div class="row" id="countersSearch">
	<div class="col-md-10 col-md-offset-1">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><b><?php echo Localization::getString("counter.search.title")?></b></h3>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-12 col-xs-12" title="<?php echo Localization::getString("counter.service.tooltip")?>">
						<label for="resourceName"><?php echo Localization::getString("counter.label.service")?></label>
						<input class="form-control" type="text" id="resourceName" onfocus="javascript:$(this).autocomplete('search',$(this).value);">
					</div>
					<div class="col-md-12 col-xs-12" title="<?php echo Localization::getString("counter.user.tooltip")?>">
						<label for="userName"><?php echo Localization::getString("counter.label.user")?></label>
						<input class="form-control" type"text" id="userName" onfocus="javascript:$(this).autocomplete('search',$(this).value);">
					</div>
					<div class="col-md-12 col-xs-12" title="<?php echo Localization::getString("counter.timeunit.tooltip")?>">
						<label for="timeUnit"><?php echo Localization::getString("counter.label.timeunit")?></label>
						<select  class="form-control"  id="timeUnit" name="timeUnit">
							<option value="All"><?php echo Localization::getString("counter.label.timeunit.all")?></option>
							<option value="M"><?php echo Localization::getString("counter.label.timeunit.month")?></option>
							<option value="D"><?php echo Localization::getString("counter.label.timeunit.day")?></option>
							<option value="S"><?php echo Localization::getString("counter.label.timeunit.sec")?></option>
						</select>
					</div>
				</div>
			</div>
			<div class="panel-footer">
				<div class="row">
					<div class="col-md-2 col-md-offset-5 col-xs-2 col-xs-offset-5">
						<button type="button" class="btn btn-info" id="search" title="<?php echo Localization::getString("button.search.tooltip")?>" onclick="startSearchCounters()">
						  <span class="glyphicon glyphicon-search" aria-hidden="true"></span> <?php echo Localization::getString("button.search")?></span>
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
