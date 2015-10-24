<?php
require_once "../../include/Localization.php";
?>
<div class="row">
	<div class="col-md-10 col-md-offset-1">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><b><?php echo Localization::getString("user.quotas.edit")?></b></h3>
			</div>
			<div class="panel-body">
				<input type="hidden" id="quotaUri" value="{quota.uri}">
				<div class="row">
					<div class="col-md-4 col-xs-5">
						<label for="reqSec"><?php echo Localization::getString("service.list.quotas.reqSec")?></label>
						<input type="number" class="form-control"  title="<?php echo Localization::getString("user.quotas.reqSec.tooltip")?>"  placeholder="<?php echo Localization::getString("user.quotas.reqSec.placeholder")?>" id="reqSec" value="{quota.reqSec}" onchange="setQuotaModified(true)" onkeypress="setQuotaModified(true)">
					</div>
					<div class="col-md-4 col-xs-5">
						<label for="reqDay"><?php echo Localization::getString("service.list.quotas.reqDay")?></label>
						<input type="number"  class="form-control"  title="<?php echo Localization::getString("user.quotas.reqDay.tooltip")?>"  placeholder="<?php echo Localization::getString("user.quotas.reqDay.placeholder")?>" id="reqDay" value="{quota.reqDay}" onchange="setQuotaModified(true)" onkeypress="setQuotaModified(true)">
					</div>
					<div class="col-md-4 col-xs-5">
						<label for="reqSec"><?php echo Localization::getString("service.list.quotas.reqSec")?></label>
						<input type="number"  class="form-control"  title="<?php echo Localization::getString("user.quotas.reqSec.tooltip")?>"  placeholder="<?php echo Localization::getString("user.quotas.reqMonth.placeholder")?>" id="reqMonth" value="{quota.reqMonth}" onchange="setQuotaModified(true)" onkeypress="setQuotaModified(true)">
					</div>
			</div>
			<div class="panel-footer">
				<div class="row">
					<div class="col-md-2 col-md-offset-5 col-xs-4 col-xs-offset-5">
						<button type="button" class="btn btn-default" id="saveEditQuotas" onclick="updateQuota()">
							<span><?php echo Localization::getString("button.ok")?></span>
						</button> 
						<button type="button" class="btn btn-info" onclick="startDisplayUserQuotasForCurrentUser()">
							<span><?php echo Localization::getString("button.cancel")?></span>
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
