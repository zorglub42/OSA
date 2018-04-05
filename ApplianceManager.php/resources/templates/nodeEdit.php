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
<div class="row" id="nodeEdit">
	<div class="col-md-10 col-md-offset-1">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><b><?php echo Localization::getString("node.properties")?></b></h3>
			</div>
			<form>
				<?php include "nodeProperties.php"?>
				<div class="panel-footer">
						<div class="row">
							<div class="col-md-2 col-md-offset-5 col-xs-6 col-xs-offset-3">
								<button type="button" class="btn btn-default" id="saveNode" onclick="updateNode('{node.uri}')">
									<span><?php echo Localization::getString("button.ok")?></span>
								</button>
								<button type="button" class="btn btn-info" onclick="showNodes()">
									<span><?php echo Localization::getString("button.cancel")?></span>
								</button>
							</div>
						</div>
				</div>
			</form>
		</div>
	<div>
</div>
