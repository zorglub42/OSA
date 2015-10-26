<div class="row">
	<div  class="col-md-6 col-xs-6"  title="<?php echo Localization::getString("user.userName.tooltip")?>">
		<label for="userName"><?php echo Localization::getString("user.label.userName")?></label><br>
		{userName}<input type="{userNameInputType}" class="form-control"  placeholder="<?php echo Localization::getString("user.userName.placeholder")?>" id="userName" value="{userName}" onchange="setUserModified(true)" onkeypress="setUserModified(true)">
	</div>
	<div  class="col-md-6 col-xs-6"  title="<?php echo Localization::getString("user.password.tooltip")?>">
		<label for="password"><?php echo Localization::getString("user.label.password")?></label><br>
		<input type="password" class="form-control"  placeholder="<?php echo Localization::getString("user.password.placeholder")?>" id="userPass" value="{password}" onchange="setUserModified(true)" onkeypress="setUserModified(true)">
	</div>
</div>
<div class="row">
	<div  class="col-md-6 col-xs-6"  title="<?php echo Localization::getString("user.firstName.tooltip")?>">
		<label for="firstName"><?php echo Localization::getString("user.label.firstName")?></label><br>
		<input type="text" class="form-control"  placeholder="<?php echo Localization::getString("user.firstName.placeholder")?>" id="firstName" value="{firstName}" onchange="setUserModified(true)" onkeypress="setUserModified(true)">
	</div>
	<div  class="col-md-6 col-xs-6"  title="<?php echo Localization::getString("user.lastName.tooltip")?>">
		<label for="lastName"><?php echo Localization::getString("user.label.lastName")?></label><br>
		<input type="text" class="form-control"  placeholder="<?php echo Localization::getString("user.lastName.placeholder")?>" id="lastName" value="{lastName}" onchange="setUserModified(true)" onkeypress="setUserModified(true)">
	</div>
</div>
<div class="row">
	<div  class="col-md-4 col-xs-4"  title="<?php echo Localization::getString("user.entity.tooltip")?>">
		<label for="entity"><?php echo Localization::getString("user.label.entity")?></label><br>
		<input type="text" class="form-control"  placeholder="<?php echo Localization::getString("user.entity.placeholder")?>" id="entity" value="{entity}" onchange="setUserModified(true)" onkeypress="setUserModified(true)">
	</div>
	<div  class="col-md-8  col-xs-8"  title="<?php echo Localization::getString("user.emailAddress.tooltip")?>">
		<label for="emailAddress"><?php echo Localization::getString("user.label.emailAddress")?></label><br>
		<input type="text" class="form-control"  placeholder="<?php echo Localization::getString("user.emailAddress.placeholder")?>" id="userMail" value="{emailAddress}" onchange="setUserModified(true)" onkeypress="setUserModified(true)">
	</div>
</div>
<div class="row">
	<div  class="col-md-12 col-xs-12"  title="<?php echo Localization::getString("user.endDate.tooltip")?>">
		<label for="userEndDate"><?php echo Localization::getString("user.label.endDate")?></label><br>
		<input type="text" class="form-control"  placeholder="<?php echo Localization::getString("user.endDate.placeholder")?>" id="userEndDate" value="{endDate}" onchange="setUserModified(true)" onkeypress="setUserModified(true)">
	</div>
</div>
<div class="row">
	<div  class="col-md-12 "  title="<?php echo Localization::getString("user.additionalData.tooltip")?>">
		<label for="extra"><?php echo Localization::getString("user.label.additionalData")?></label><br>
		<textarea class="form-control" rows=10 placeholder="<?php echo Localization::getString("user.additionalData.placeholder")?>" id="extra" onchange="setUserModified(true)" onkeypress="setUserModified(true)">{extra}</textarea>
	</div>
</div>
