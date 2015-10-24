<div class="row">
	<div class="col-md-12 col-xs-12">
			<div id="tabs">
				<ul>
					<li><a  href="#tabs-general">General</a></li>
					<li><a  href="#tabs-SSL">SSL certs and key</a></li>
					<li id="showServices"><a  href="#tabs-node-services" onclick="loadNodeServices('{node.uri}')">Services</a></li>
					<li><a href="#tabs-node-advance" >Advance</a></li>
				</ul>

				<div id="tabs-node-services">
					<center>
					<h1 id="serviceListTitle">
									{serviceList.length} <?php echo Localization::getString("service.list.found")?>
					</h1>
					<hr>
						<table id="servicesList" class="tabular_table scroll choices-border" >
							<thead>
								<tr class="tabular_table_header">
									<th>Service name</th>
									<th>On</th>
									<th>Group name</th>
									<th>Frontend endpoint</th>
									<th>Backend endpoint</th>
								</tr>
							</thead>
							<tbody id="data">
									<tr id="rowTpl" style="display:none">
										<td title="{serviceList[i].serviceName}">{serviceList[i].serviceName}</td>
											
										<td class="isPublished"><input  title="{isPublishedToolTip}" type="checkbox" id="isPublished{i}" {cbPublishedCheck} disabled><label for="isPublished{i}"></label></td>

										<td title="{serviceList[i].groupName}">{serviceList[i].groupName}</td>
										<td title="{serviceList[i].frontEndEndPoint}">{serviceList[i].frontEndEndPoint}</td>
										<td title="{serviceList[i].backEndEndPoint}">{serviceList[i].backEndEndPoint}</td>
									</tr>
							</tbody>
						 </table>
					</center>
				</div>
				<div id="tabs-node-advance">
					<div class="row">
						<div class="col-md-12 col-xs-12">
							<label><?php echo Localization::getString("node.label.additionalConfiguration")?></label>
						</div>
						<div class="col-md-12 col-xs-12">
							<p id="warnAdditionalConfig" class="errorMessage"><?php echo Localization::getString("node.label.additionalConfiguration.warning")?></p>
						</div>
						<div class="col-md-12 col-xs-12" title="<?php echo Localization::getString("node.additionalConfiguration.tooltip")?>">
							<textarea rows="10"  class="form-control" id="additionalConfiguration" onClick="setNodeModified(true)"  onchange="setNodeModified(true)" onkeypress="setNodeModified(true)">{node.additionalConfiguration}</textarea>
						</div>

					</div>
				</div>

				<div id="tabs-general">
					<div class="row">
						<div class="col-md-6 col-xs-6" >
							<label for="nodeNameFld"><?php echo Localization::getString("node.label.nodeName")?></label>
							{node.nodeName}<input class="form-control"  title="<?php echo Localization::getString("node.nodeName.tooltip")?>"  placeholder="<?php echo Localization::getString("node.nodeName.placeholder")?>" type="{nodeNameInputType}" id="nodeNameFld" value="{node.nodeName}" onchange="setNodeModified(true)" onkeypress="setNodeModified(true)">
						</div>
						<div class="col-md-6 col-xs-6" title="<?php echo Localization::getString("node.isHTTPS.tooltip")?>" >
							<label for="isHTTPS"><?php echo Localization::getString("node.label.isHTTPS")?></label><br>
							<input type="checkbox" id="isHTTPS" onClick="setNodeModified(true)"  onchange="setNodeModified(true)" onkeypress="setNodeModified(true)" {cbIsHTTPS} >
						</div>
					</div>
					<div class="row">
						<div class="col-md-6 col-xs-6" title="<?php echo Localization::getString("node.localIP.tooltip")?>">
							<label for="localIP"><?php echo Localization::getString("node.label.localIP")?></label>
							<input class="form-control" placeholder="<?php echo Localization::getString("node.localIP.placeholder")?>" type="text" id="localIP" value="{node.localIP}" onchange="setNodeModified(true)" onkeypress="setNodeModified(true)">
						</div>
						<div class="col-md-6 col-xs-6" title="<?php echo Localization::getString("node.port.tooltip")?>" >
							<label for="port"><?php echo Localization::getString("node.label.port")?></label><br>
							<input class="form-control" placeholder="<?php echo Localization::getString("node.port.placeholder")?>" type="number" id="port" value="{node.port}" onchange="setNodeModified(true)" onkeypress="setNodeModified(true)">
						</div>
					</div>
					<div class="row">
						<div class="col-md-6 col-xs-6" title="<?php echo Localization::getString("node.serverFQDN.tooltip")?>">
							<label for="serverFQDN"><?php echo Localization::getString("node.label.serverFQDN")?></label>
							<input class="form-control" placeholder="<?php echo Localization::getString("node.serverFQDN.placeholder")?>" type="text" id="serverFQDN" value="{node.serverFQDN}" onchange="setNodeModified(true)" onkeypress="setNodeModified(true)">
						</div>
						<div class="col-md-6 col-xs-6" title="<?php echo Localization::getString("node.nodeDescription.tooltip")?>" >
							<label for="nodeDescription"><?php echo Localization::getString("node.label.nodeDescription")?></label><br>
							<input class="form-control" placeholder="<?php echo Localization::getString("node.nodeDescription.placeholder")?>" type="text" id="nodeDescription" value="{node.nodeDescription}" onchange="setNodeModified(true)" onkeypress="setNodeModified(true)">
						</div>
					</div>
					<hr>
					<div class="row">
						<div class="col-md-6 col-xs-6" title="<?php echo Localization::getString("node.isBasicAuthEnabled.tooltip")?>">
							<label for="isBasicAuthEnabled"><?php echo Localization::getString("node.label.isBasicAuthEnabled")?></label><br>
							<input  type="checkbox" id="isBasicAuthEnabled" onClick="setNodeModified(true)"  onchange="setNodeModified(true)" onkeypress="setNodeModified(true)" {cbIsBasicAuthEnabled} >
						</div>
						<div class="col-md-6 col-xs-6" title="<?php echo Localization::getString("node.isCookieAuthEnabled.tooltip")?>" >
							<label for="isCookieAuthEnabled"><?php echo Localization::getString("node.label.isCookieAuthEnabled")?></label><br>
							<input  type="checkbox" id="isCookieAuthEnabled" onClick="setNodeModified(true)"  onchange="setNodeModified(true)" onkeypress="setNodeModified(true)" {cbIsCookieAuthEnabled} >
						</div>
					</div>
				</div>
				<div id="tabs-SSL">
					<form enctype="multipart/form-data">
					<div id="fileupload"/>
					
					<div class="row">
						<div class="col-md-6 col-xs-6" title="<?php echo Localization::getString("node.privateKey.tooltip")?>">
							<label for="PKfileuploadFLD"><?php echo Localization::getString("node.label.privateKey")?></label>
							<input class="form-control" id="PKfileuploadFLD" type="file"  onchange="setNodeModified(true)" >
						</div>
						<div class="col-md-6 col-xs-6" title="<?php echo Localization::getString("node.cert.tooltip")?>" >
							<label for="CERTfileuploadFLD"><?php echo Localization::getString("node.label.cert")?></label><br>
							<input id="CERTfileuploadFLD" type="file" class="form-control" onchange="setNodeModified(true)" >
						</div>
					</div>
					</form>
					<div id="resetSSL">
						<hr>
						<button type="button" class="btn btn-info" onclick="startResetSSL()">
							<span><?php echo Localization::getString("button.resetSSL")?></span>
						</button>

					</div>
					<hr><label for="manageCaCert"><?php echo Localization::getString("node.label.manageCA")?></label> <input  title="<?php echo Localization::getString("node.manageCA.tooltip")?>" type="checkbox" id="manageCaCert" {cbManageCAEnabled} onclick="toggleAuthority()"><br>
					<div id="sslAuthority"
						<form enctype="multipart/form-data">
							<div id="fileupload"/>
							<div class="row">
								<div class="col-md-6 col-xs-6" title="<?php echo Localization::getString("node.ca.tooltip")?>">
									<label id="lblCa" for="CAfileuploadFLD"><?php echo Localization::getString("node.label.ca")?></label>
									<input class="form-control" id="CAfileuploadFLD" type="file"  onchange="setNodeModified(true)" >
								</div>
								<div class="col-md-6 col-xs-6" title="<?php echo Localization::getString("node.cert.tooltip")?>" >
									<label id="lblChain" for="CHAINfileuploadFLD"><?php echo Localization::getString("node.label.chain")?></label><br>
									<input id="CHAINfileuploadFLD" type="file" class="form-control" onchange="setNodeModified(true)" >
								</div>
							</div>
						</form>
						<div id="resetCASSL">
							<hr>
							<button type="button" class="btn btn-info" onclick="startResetCASSL()">
								<span><?php echo Localization::getString("button.resetCASSL")?></span>
							</button>
						</div>
					</div>
				</div>
				
			</div>
	</div>
</div>
