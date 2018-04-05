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
<div class="row" id="userAdd">
	<div class="col-md-10 col-md-offset-1">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><b><?php echo Localization::getString("user.properties.new")?></b></h3>
			</div>

			<?php include "userProperties.php"?>
			<div class="panel-footer">
					<div class="row">
						<div class="col-md-offset-5 col-md-2 col-xs-4 col-xs-offset-4">
							<button type="button" class="btn btn-default" id="saveNew" onclick="saveNewUser()">
								<span><?php echo Localization::getString("button.ok")?></span>
							</button>
							<button type="button" class="btn btn-info" onclick="showUsers()" id="cancel">
								<span><?php echo Localization::getString("button.cancel")?></span>
							</button>
						</div>
					</div>
			</div>
		</div>
	<div>
</div>
