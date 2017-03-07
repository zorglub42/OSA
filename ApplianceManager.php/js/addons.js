var divHooks=[];

var osaAddonsObserver = new MutationObserver(function(mutations) {
												for (var j=0;j<divHooks.length;j++){
													if ($(divHooks[j].selector).length){
														divHooks[j].callback();
													}
												}
											});





function addonAddGUIHook(selector, callback){
	divHooks.push({"selector": selector, "callback": callback});	
}

$( document ).ready(function() {
	osaAddonsObserver.observe(document.getElementById("content"), { childList: true });
});
