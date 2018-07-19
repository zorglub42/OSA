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
?>
<div id="userProperties">
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
		<div  class="col-md-6 col-xs-6"  title="<?php echo Localization::getString("user.endDate.tooltip")?>">
			<label for="userEndDate"><?php echo Localization::getString("user.label.endDate")?></label><br>
			<input type="text" class="form-control"  placeholder="<?php echo Localization::getString("user.endDate.placeholder")?>" id="userEndDate" value="{endDate}" onchange="setUserModified(true)" onkeypress="setUserModified(true)">
		</div>
		<div  class="col-md-6 col-xs-6"  title="<?php echo Localization::getString("user.lastTokenLogin.tooltip")?>">
			<label id="lastTokenLoginLabel" for="lastTokenLogin"><?php echo Localization::getString("user.label.lastTokenLogin")?></label><br>
			<div id="lastTokenLogin">{lastTokenLogin}</div>
		</div>
	</div>
	<br>
	<style>
		.row {
			margin-right: 0px;
			margin-left: 0px;
		}
	</style>
	<div class="row list-group-item header" >
		<div class="col-md-5 ellipsis" title="<?php echo Localization::getString("user.property.name")?>"><?php echo Localization::getString("user.property.name")?></div>
		<div class="col-md-5 ellipsis" title="<?php echo Localization::getString("user.property.value")?>"><?php echo Localization::getString("user.property.value")?></div>
		<div class="col-md-2 ellipsis" title="<?php echo Localization::getString("list.actions")?>"><?php echo Localization::getString("list.actions")?></div>
	</div>
	<div class="list-group" id="data" >
		<a class="list-group-item row" id="rowTpl" style="display:none" >
			<div class="col-md-2 ellipsis" title="{propertiesList[i].name}">{propertiesList[i].name}<input id="propertyName_{i}" type="hidden" value="{propertiesList[i].name}"/></div>
			<div class="col-md-8 ellipsis" title="{propertiesList[i].value}"><input  class="form-control" id="propertyValue_{i}" value="{propertiesList[i].value}"/></div>
			<div class="col-md-2">
				<button type="button" class="btn btn-default" title="<?php echo Localization::getString("user.property.delete.tooltip")?>" onclick="deleteUserProperty('{i}', '{propertiesList[i].name}')">
					<span class="glyphicon glyphicon glyphicon-trash" aria-hidden="true"></span>
				</button>
			</div>
		</a>
		<a class="list-group-item row" id="newProp" >
			<div class="col-md-2 ellipsis"><input onchange="setUserModified(true)" id="propertyName_new" type="text"  class="form-control"/></div>
			<div class="col-md-8 ellipsis" ><input onchange="setUserModified(true)" id="propertyValue_new" value="" class="form-control"/></div>
			<div class="col-md-2">
				<button type="button" class="btn btn-default" title="<?php echo Localization::getString("user.property.add.tooltip")?>" onclick="addUserProperty()">
					<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
				</button>
			</div>
		</a>
	</div>

</div>
