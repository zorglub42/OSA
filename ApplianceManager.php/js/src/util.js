/*--------------------------------------------------------
 * Module Name : ApplianceManager
 * Version : 1.0.0
 *
 * Software Name : OpenServicesAccess
 * Version : 1.0
 *
 * Copyright (c) 2011 – 2014 Orange
 * This software is distributed under the Apache 2 license
 * <http://www.apache.org/licenses/LICENSE-2.0.html>
 *
 *--------------------------------------------------------
 * File Name   : ApplianceManager/ApplianceManager.php/js/util.js
 *
 * Created     : 2012-02
 * Authors     : Benoit HERARD <benoit.herard(at)orange.com>
 *
 * Description :
 *      Various JS function
 *--------------------------------------------------------
 * History     :
 * 1.0.0 - 2012-10-01 : Release of the file
*/
/* Add a "browser" prperty to JQuery to know if current broswer is msie */
jQuery.browser = {};
(function () {
    jQuery.browser.msie = false;
    jQuery.browser.version = 0;
    if (navigator.userAgent.match(/MSIE ([0-9]+)\./)) {
        jQuery.browser.msie = true;
        jQuery.browser.version = RegExp.$1;
    }
})();

/* Add a replaceAll method to String object to replace all occurences of
   a partern in a sting */
String.prototype.replaceAll = function (find, replace) {
    var str = this;
    if (replace == null){
        replace = "";
    }
    return str.replace(new RegExp(find.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&'), 'g'), replace);
};

/* Add a remove method to DOM Element to remove it from DOM Tree */
Element.prototype.remove = function() {
    this.parentElement.removeChild(this);
}

/* Add a remove methode to DOM Node List to remove it from DOM tree */
NodeList.prototype.remove = HTMLCollection.prototype.remove = function() {
    for(var i = this.length - 1; i >= 0; i--) {
        if(this[i] && this[i].parentElement) {
            this[i].parentElement.removeChild(this[i]);
        }
    }
}

/* Add a startWith method to String object to know if a string starts
  with a particular string */
if (typeof String.prototype.startsWith != 'function') {
  // see below for better implementation!
  String.prototype.startsWith = function (str){
    return this.indexOf(str) == 0;
  };
}

/* Enable/Disable HTML element */
function setActionButtonEnabled(itemId, enabled){
	$("#" + itemId).prop('disabled', !enabled);
}

/* Return a filter field value
   if filed exists as HTML element, return its value, else return value previously stored */
function getFilterValue(fieldName){
	rc='';
	if ($('#' + fieldName ).val()!= undefined || window[fieldName + 'PrevVal']!='' ){
		if ($('#' + fieldName ).val()!= undefined){
			rc=$('#' + fieldName ).val();
			window[fieldName + 'PrevVal']=rc;
		}else{
			rc=window[fieldName + 'PrevVal'];
		}
	}
	return rc;
}
