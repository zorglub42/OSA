<?php
require_once "../../include/Localization.php";
?>
<div class="row" id="nodesList">
	<div class="col-md-10 col-md-offset-1">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><b>{nodeList.length} <?php echo Localization::getString("node.list.found")?></b></h3>
			</div>
			<div class="panel-body">
				<form accept-charset="UTF-8" role="form"  onkeypress="return handelNodeFilterFormKeypress(event)">
					<fieldset>
						<div class="form-group">
							<div class="row">
								<div class="col-md-4 col-xs-4 search-control">
									<input type="text" class="form-control" id="nodeNameFilter" value="{nodeNameFilterPrevVal}" placeholder="<?php echo Localization::getString("node.nodeName.placeholder")?>">  
								</div>
								<div class="col-md-4 col-xs-4 search-control">
									<input class="form-control" placeholder="<?php echo Localization::getString("node.description.placeholder")?>" id="nodeDescriptionFilter" value="{nodeDescriptionFilterPrevVal}">
								</div>
								<div class="col-md-4 col-xs-4 search-control">
									<input class="form-control" placeholder="<?php echo Localization::getString("node.serverFQDN.placeholder")?>" id="serverFQDNFilter" value="{serverFQDNFilterPrevVal}">
								</div>
							</div>
							<div class="row">
								<div class="col-md-4 col-xs-4 search-control">
									<input type="text" class="form-control" placeholder="<?php echo Localization::getString("node.localIP.placeholder")?>" id="localIPFilter"value="{localIPFilterPrevVal}"> 
								</div>
								<div class="col-md-4 col-xs-4 search-control">
									<input class="form-control" placeholder="<?php echo Localization::getString("node.port.placeholder")?>" id="portFilter" value="{portFilterPrevVal}">
								</div>
								<div class="col-md-4 col-xs-4 search-control" >
										<button type="button" class="btn btn-default" title="<?php echo Localization::getString("button.filter.tooltip")?>" onclick=showNodes()>
											<span><?php echo Localization::getString("button.filter")?></span>
										</button>
										<button type="button" class="btn btn-info" title="<?php echo Localization::getString("button.reset.tooltip")?>" onclick=resetNodeFilter()>
											<span><?php echo Localization::getString("button.reset")?></span>
										</button>
								</div>	
							</div>
						</div>
					</fieldset>
				</form>
				<hr>
				<div class="row list-group-item header" >
						<div class="col-xs-4 col-md-2 ellipsis" title="<?php echo Localization::getString("node.list.nodeName")?>"><?php echo Localization::getString("node.list.nodeName")?></div>
						<div class="col-xs-1 col-md-1 ellipsis mobile-optional" title="<?php echo Localization::getString("node.list.ssl")?>"><?php echo Localization::getString("node.list.ssl")?></div>
						<div class="col-xs-2 col-md-2 ellipsis mobile-optional" title="<?php echo Localization::getString("node.list.FQDN")?>"><?php echo Localization::getString("node.list.FQDN")?></div>
						<div class="col-xs-3 col-md-2 ellipsis" title="<?php echo Localization::getString("node.list.binding")?>"><?php echo Localization::getString("node.list.binding")?></div>
						<div class="col-xs-2 col-md-3 ellipsis mobile-optional" title="<?php echo Localization::getString("node.list.description")?>"><?php echo Localization::getString("node.list.description")?></div>
						<div class="col-xs-5 col-md-2 ellipsis" title="<?php echo Localization::getString("list.actions")?>"><?php echo Localization::getString("list.actions")?></div>
				</div>
				<div class="list-group" id="data" >
					<a class="list-group-item row"  id="rowTpl" style="display:none">
						<div  ondblclick="startEditNode('{nodeList[i].uri}')">
							<div class="col-xs-4 col-md-2 ellipsis" title="{nodeList[i].nodeName}">{nodeList[i].nodeName}</div>
							<div class="col-xs-1 col-md-1 ellipsis mobile-optional"><input type="checkbox" title="<?php echo Localization::getString("node.isHTTPS.tooltip")?>" {cbIsHTTPS} disabled></div>
							<div class="col-xs-2 col-md-2 ellipsis mobile-optional" title="{nodeList[i].serverFQDN}">{nodeList[i].serverFQDN}</div>
							<div class="col-xs-3 col-md-2 ellipsis" title="{nodeList[i].localIP}:{nodeList[i].port}">{nodeList[i].localIP}:{nodeList[i].port}</div>
							<div class="col-xs-2 col-md-3 ellipsis mobile-optional" title="{nodeList[i].nodeDescription}">{nodeList[i].nodeDescription}</div>
							<div class="col-xs-5 col-md-2">
									<button type="button" class="btn btn-default" id="btnEdit" title="<?php echo Localization::getString("node.edit.tooltip")?>" onclick="startEditNode('{nodeList[i].uri}')">
									  <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
									</button>
									<button type="button" class="btn btn-default" id="btnDelete" title="<?php echo Localization::getString("node.delete.tooltip")?>" onclick="deleteNode('{nodeList[i].uri}', '{nodeList[i].nodeName}')">
									  <span class="glyphicon glyphicon glyphicon-trash" aria-hidden="true"></span>
									</button>
									<button type="button" class="btn btn-default" id="btnPublish" title="<?php echo Localization::getString("node.publish.tooltip")?>" onclick="publishNode('{nodeList[i].uri}', '1')">
									  <span class="glyphicon glyphicon-play" aria-hidden="true"></span>
									</button>
									<button type="button" class="btn btn-default" id="btnUnpublish" title="<?php echo Localization::getString("node.unpublish.tooltip")?>" onclick="publishNode('{nodeList[i].uri}', '0')">
									  <span class="glyphicon glyphicon-pause" aria-hidden="true"></span>
									</button>
							</div>
						</div>
					</a>
				</div>
			</div>
			<div class="panel-footer">
				<div class="row">
					<div class="col-md-offset-5 col-md-2 col-xs-2 col-xs-offset-4">
						<button type="button" class="btn btn-info" id="addNode" title="<?php echo Localization::getString("node.add.tooltip")?>">
						  <span class="glyphicon glyphicon-plus" aria-hidden="true" ></span> <?php echo Localization::getString("button.add")?></span>
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
$(
	function (){
		$('#addNode').click(addNode);
	}
);

</script>


<!--
<center>
<h1>
	{nodeList.length} nodes found
</h1>
<hr>
	<form onkeypress="return handelNodeFilterFormKeypress(event)">
	<table class="tabular_table searchFormTable" >	
		<tr class="tabular_table_body">	
			<th>node name</th> <td></td>	
			<th>node description</th> <td></td>	
			<th>server FQDN</th> <td><input type="text" id="serverFQDNFilter" value="{serverFQDNFilterPrevVal}"></td>	
		</tr>	
		<tr class="tabular_table_body">	
			<th>Local IP</th> <td><input type="text" id="localIPFilter"value="{localIPFilterPrevVal}"></td>	
			<th>port</th> <td><input type="text" id="portFilter" value="{portFilterPrevVal}"></td>	
			<td colspan="2"><input type="button" class="button_orange" value="filter" onclick="showNodes()"><input type="button" class="button_white" value="reset" onclick="resetNodeFilter()"></th> 	
		</tr>	
	</table>	
	</form>
	<table id="nodesList" class="tabular_table scroll choices-border" >
		<thead>
			<tr class="tabular_table_header">
				<th>Node name</th>
				<th>SSL</th>
				<th>FQDN</th>
				<th>Binding</th>
				<th>Description</th>
				<th>Actions</th>
			</tr>
		</thead>
		<tbody id="data">
			<tr id="rowTpl" class="item" style="display:none">
				<td title="{nodeList[i].nodeName}">{nodeList[i].nodeName}</td>
				<td class="isPublished"><input  title="{isPublishedToolTip}" type="checkbox" id="isPublished{i}" {cbIsHTTPS} disabled><label for="isPublished{i}"></label></td>
				<td title="{nodeList[i].serverFQDN}">{nodeList[i].serverFQDN}</td>
				<td title="{nodeList[i].localIP}:{nodeList[i].port}">{nodeList[i].localIP}:{nodeList[i].port}</td>
				<td title="{nodeList[i].nodeDescription}">{nodeList[i].nodeDescription}</td>
				<td class="action">
					<a id="btnEdit" title="{editNodeToolTip}"  href="javascript:startEditNode('{nodeList[i].uri}')"><img  border="0" src="images/edit.gif"></a>
					<a id="btnDelete" title="{deleteNodeToolTip}"  href="javascript:deleteNode('{nodeList[i].uri}', '{nodeList[i].nodeName}')"><img border="0" src="images/delete.gif"></a>
				</td>
			</tr>
		</tbody>
	 </table>
</center>
-->
