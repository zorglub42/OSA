<center>
	<h1>
		Node properties
	</h1>
	<hr> 
		<form>
			<?php include "nodeProperties.html"?>
		<br>
		<input type="button" id="saveNode" onclick="updateNode('" + node.uri + "')" value="Save" class="button_orange">&nbsp;
		<input type="button" onclick="showNodes()" value="Cancel" class="button_orange">
	</form>
</center>
