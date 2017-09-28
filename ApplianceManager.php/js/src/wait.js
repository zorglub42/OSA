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
 * File Name   : ApplianceManager/ApplianceManager.php/js/wait.js
 *
 * Created     : 2012-02
 * Authors     : Benoit HERARD <benoit.herard(at)orange.com>
 *
 * Description :
 *      AJAX WaitScreen/loader management
 *--------------------------------------------------------
 * History     :
 * 1.0.0 - 2012-10-01 : Release of the file
*/

var largePictureBackgroundOpacity=70;

/* Define alpha coef (transparency) of an HTML Element */
function setAlpha(element,alpha)
{
	op=alpha / 100;

	element.style.opacity = alpha / 100;
	/** Test pour notre cher IE */
	if (document.body.filters != undefined)
	{
		element.style.filter = 'alpha(opacity:' + alpha + ')';
	}
}

/* Determine if current browser is msie */
function isIE()
{
	if (window.ActiveXObject){
		return (true);
	}else{
		return(false);
	}
}

/* Return HTML code for "wait screen" */
function setHtmlWait(){
strHTML="";
strHTML+="<center>";
strHTML+="	<img border=0 style=\"position: absolute\" id=\"waitPic\" src=\"images/loading.gif\">";
strHTML+="</center>";
return( strHTML);
}

/* Display wait screen
	 assume that an HTML Element with id "waitScreen" exists in the current document */
function showWait(){
		if (isIE()){
			MaxW=screen.width; //-50;
			MaxH=screen.height-200;
		}else{
			MaxW=window.innerWidth; //-50;
			MaxH=window.innerHeight;
		}
		waitHeight=50;
		waitWidth=90;

		document.getElementById('waitScreen').innerHTML=setHtmlWait();
		document.getElementById('waitScreen').style.top=((MaxH-waitHeight)/2) + "px";//"0px";
		document.getElementById('waitPic').style.top= ((waitHeight-32)/2) + "px";
		document.getElementById('waitPic').style.left= ((waitWidth-32)/2) + "px";
		document.getElementById('waitScreen').style.left=((MaxW-waitWidth)/2) + "px"; //"0px";
		document.getElementById('waitScreen').style.width=waitWidth + "px";
		document.getElementById('waitScreen').style.height=waitHeight+ "px";
		bkgAlpha=largePictureBackgroundOpacity;
		setAlpha(document.getElementById('waitScreen'),bkgAlpha);
		document.getElementById('waitScreen').style.visibility='visible';
}

/* Hide wait screen */
function hideWait(){
		document.getElementById('waitScreen').style.visibility='hidden';
}
