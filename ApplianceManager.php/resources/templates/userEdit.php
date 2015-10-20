<center>
<h1>
User {userName} properties
</h1>
<hr>
	<?php include "userProperties.html"?>
<br>
<input type="button" id="saveEdit" onclick="updateUser('{uri}')" value="Save" class="button_orange">&nbsp;
<input type="button" id="cancelEdit" onclick="showUsers()" value="Done" class="button_orange">&nbsp;
<input type="button"  title="{editUserGroupsToolTip}"  id="groupsEdit"onclick="startDisplayUserGroups('{uri}')" value="Groups" class="button_orange">&nbsp;
<input type="button"  title="{editUserQuotasToolTip}" id="quotasEdit"onclick="startDisplayUserQuotas('{uri}')" value="Quotas" class="button_orange">
<div id="userGroups"\>
</form>
<hr>

</center>
