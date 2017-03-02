var divHooks=[];

var osaAddonsObserver = new WebKitMutationObserver(function(mutations) {
													mutations.forEach(	function(mutation) {
																			for (var i = 0; i < mutation.addedNodes.length; i++){
																				for (var j=0;j<divHooks.length;j++){
																					if (mutation.addedNodes[i].nodeName == "DIV" && mutation.addedNodes[i].innerHTML.indexOf(divHooks[j].name)>=0){
																						divHooks[j].callback();
																					}
																				}
																			}
																		}
													)
												});


function addOSADivHook(divName, callback){
	divHooks.push({"name": divName, "callback": callback});	
}

$( document ).ready(function() {
	console.log( "ready!" );
	osaAddonsObserver.observe(document.getElementById("content"), { childList: true });
});
