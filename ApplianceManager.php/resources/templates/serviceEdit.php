<center>
<h1>
{serviceName}'s properties
</h1>
<hr> 
<form>
<?php include "serviceProperties.html"?>
<br>
<input type="button" id="saveService" onclick="updateService('{uri}')" value="Save" class="button_orange">&nbsp;
<input type="button" onclick="showServices()" value="Cancel" class="button_orange">
</form>
</center>
