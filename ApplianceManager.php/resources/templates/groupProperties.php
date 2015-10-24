			<div class="panel-body">
				<form accept-charset="UTF-8" role="form">
				<fieldset>
					<div class="form-group">
						<input class="form-control" placeholder="<?php echo Localization::getString("group.name.placeholder")?>"  title="<?php echo Localization::getString("group.name.tooltip")?>" type="{groupNameInputType}" id="groupName" onchange="setGroupModified(true)" onkeypress="setGroupModified(true)" value="{group.groupName}">
					</div>
					<div class="form-group">
						<input class="form-control" placeholder="<?php echo Localization::getString("group.description.placeholder")?>"  title="<?php echo Localization::getString("group.description.tooltip")?>" type='text' id='groupDesc' onchange="setGroupModified(true)" onkeypress="setGroupModified(true)" value="{group.description}">
					</div>

				</fieldset>
				</form>
			</div>
