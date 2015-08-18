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


if (typeof String.prototype.startsWith != 'function') {
  // see below for better implementation!
  String.prototype.startsWith = function (str){
    return this.indexOf(str) == 0;
  };
}

/*Enable/Disable an action button*/
function setActionButtonEnabled(itemId, enabled){
	it=document.getElementById(itemId);
	if (it != null){
		if (enabled){
			$('#' + itemId).removeClass('button_orange_disabled');
			$('#' + itemId).addClass('button_orange');
			it.disabled=false;
		}else{
			$('#' + itemId).removeClass('button_orange');
			$('#' + itemId).addClass('button_orange_disabled');
			it.disabled=true;
		}
	}
}


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
