<?php
require_once "../../include/Localization.php";
?>
<div class="row">
	<div class="col-md-10 col-md-offset-1">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><b><?php echo Localization::getString("counter.edit.title")?></b></h3>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-6 col-xs-6">
						<label><?php echo Localization::getString("service.list.name")?></label><br>{counter.resourceName}
					</div>
					<div class="col-md-6 col-xs-6">
						<label><?php echo Localization::getString("user.list.userName")?></label><br>{counter.userName}
					</div>
				</div>
				<div class="row">
					<div class="col-md-6 col-xs-6">
						<label><?php echo Localization::getString("counter.list.timeunit")?></label><br>{counter.timeUnit}
					</div>
					<div class="col-md-6 col-xs-6">
						<label><?php echo Localization::getString("counter.list.date")?></label><br>{counter.timeValue}
					</div>
				</div>
				<div class="row">
					<div class="col-md-12 col-xs-12">
						<label for="counterValue"><?php echo Localization::getString("counter.list.value")?></label>
						<input class="form-control" title="{counterValueToolTip}" type='number' id='counterValue' value="{counter.value}"  onchange="setCounterModified(true)"  onkeypress="setCounterModified(true)">
					</div>
			</div>
			<div class="panel-footer">
				<div class="row">
					<div class="col-md-2 col-md-offset-5 col-xs-4 col-xs-offset-5">
						<button type="button" class="btn btn-default" id="save" onclick="updateCounter('{counter.uri}')">
							<span><?php echo Localization::getString("button.ok")?></span>
						</button> 
						<button type="button" class="btn btn-info" onclick="backList()">
							<span><?php echo Localization::getString("button.back")?></span>
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
