/**
 * The contents of this file are subject to the SugarCRM Public License Version
 * 1.1.3 ("License"); You may not use this file except in compliance with the
 * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an "AS IS" basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied.  See the License
 * for the specific language governing rights and limitations under the
 * License.
 *
 * All copies of the Covered Code must include on each user interface screen:
 *    (i) the "Powered by SugarCRM" logo and
 *    (ii) the SugarCRM copyright notice
 * in the same form as they appear in the distribution.  See full license for
 * requirements.
 *
 * The Original Code is: SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) 2004-2006 SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 */

// $Id: menu.js,v 1.3 2006/08/22 22:16:59 awu Exp $
var menuStack = new Array();
var hiddenElmStack = new Array();
var currentMenu = null;
var closeMenusDelay = null;
var openMenusDelay = null;
var delayTime = 75;					// ms for menu open delay


function eraseTimeout(tId) {
	//if (tId != null)
	window.clearTimeout(tId);
	return null;
}

function tbButtonMouseOverOrig(id){
	closeMenusDelay = eraseTimeout(closeMenusDelay);
	var menuName = id.replace(/Handle/i,'Menu');
	var menu = getLayer(menuName);
	//if (menu) menu.className = 'tbButtonMouseOverUp';
	if (currentMenu){
		closeAllMenus();
	}
	popupMenu(id, menu);
}
function tbButtonMouseOver(id,top,left,leftOffset,topOffset){
	closeMenusDelay = eraseTimeout(closeMenusDelay);
	if (openMenusDelay == null){
		openMenusDelay = window.setTimeout("showMenu('"+id+"','"+top+"','"+left+"','"+leftOffset+"','"+topOffset+"')", delayTime);
	}
}
function showMenu(id,top,left,leftOffset,topOffset){
	openMenusDelay = eraseTimeout(openMenusDelay);
	var menuName = id.replace(/Handle/i,'Menu');
	var menu = getLayer(menuName);
	//if (menu) menu.className = 'tbButtonMouseOverUp';
	if (currentMenu){
		closeAllMenus();
	}
	popupMenu(id, menu, top,left,leftOffset,topOffset);
}

function showSubMenu(id){
	closeMenusDelay = eraseTimeout(closeMenusDelay);
	var menuName = id.replace(/Handle/i,'Menu');
	var menu = getLayer(menuName);
//	if (currentMenu){
//		closeMenus();
//	}
//	popupMenu(id, menu);
	popupSubMenu(id, menu);
}

function popupMenu(handleID, menu, top, left, leftOffset, topOffset){
	var bw = checkBrowserWidth();
	var menuName = handleID.replace(/Handle/i,'Menu');
	var menuWidth = 120;
	var imgWidth = document.getElementById(handleID).width;
	if (menu){
		var menuHandle = getLayer(handleID);
		var p=menuHandle;
		if (left == "") {
			var left = 0;
			while(p&&p.tagName.toUpperCase()!='BODY'){
				left+=p.offsetLeft;
				p=p.offsetParent;
			}
			left+=parseInt(leftOffset);
		}
		if (top == "") {
			var top = 0;
			p=menuHandle;
			top+=p.offsetHeight;
			while(p&&p.tagName.toUpperCase()!='BODY'){
				top+=p.offsetTop;
				p=p.offsetParent;
			}
			if(topOffset != 'undefined'){
				top+=parseInt(topOffset);
			}
		}
		if (left+menuWidth>bw) {
			left = left-menuWidth+imgWidth;
		}
		setMenuVisible(menu, left, top, false);
	}
}


function popupSubMenu(handleID, menu){
	if (menu){
		var menuHandle = getLayer(handleID);
		var p=menuHandle;
		//var top = p.offsetHeight, left = 0;
		var top = 0, left = p.offsetWidth;
		while(p&&p.tagName.toUpperCase()!='BODY'){
			top+=p.offsetTop;
			left+=p.offsetLeft;
			p=p.offsetParent;
		}
		if (is.ie && is.mac){
			top -= 3;
			left -= 10;
		}
/*
		if (menu.isSubMenu){
			try{
				if (blnNetscape6){
					left+=(getLayer(menu.parentid).offsetWidth - 4);
				}else{
					left+=(getLayer(menu.parentid).clientWidth - 8);
				}
			}catch(e){
			}
		}else{
			top += menuItem.offsetHeight;
		}
*/
		//menu.x = left;
		//menu.y = top;
		setMenuVisible(menu, left, top, true);
		//fixWidth(paneID, menu);
	}
}

function closeMenusOrig(){
	if (currentMenu){
		setMenuVisibility(currentMenu, false);
//		currentMenu = null;
	}
}

function closeSubMenus(handle){
	closeMenusDelay = eraseTimeout(closeMenusDelay);
	if (menuStack.length > 0){
		for (var i = menuStack.length-1; i >=0; i--){
			var menu = menuStack[menuStack.length-1];
			if (menu.id == handle.getAttribute('parentid')){
				currentMenu = menu;
				break;
			}else{
				closeMenu(menu);
				//menuStack.pop();
				menuPop();
			}
		}
	}
}

function closeMenu(menu){
	setMenuVisibility(menu, false);
}

function closeMenusOrig(){
	if (menuStack.length > 0){
		for (var i = menuStack.length-1; i >=0; i--){
			//var menu = menuStack.pop();
			var menu = menuPop();
			closeMenu(menu);
		}
	}
	currentMenu = null;
}

function closeMenus(){
	if (closeMenusDelay == null){
		closeMenusDelay = window.setTimeout("closeAllMenus()", delayTime);
	}
}

function closeAllMenus(){
	closeMenusDelay = eraseTimeout(closeMenusDelay);
	if (menuStack.length > 0){
		for (var i = menuStack.length-1; i >=0; i--){
			//var menu = menuStack.pop();
			var menu = menuPop();
			closeMenu(menu);
		}
	}
	currentMenu = null;
}

function setMenuVisible(menu, x, y, isSubMenu){
/*
   var id = menu.id;
   var left=0;
   var top=0;
   var menuItem = menu.getMenuItemElm();

   if (menuItem && menu){
      if (menu.isTopMenu){
         menuItem.className = 'tbButtonMouseDown';
      }
   }
*/
	if (menu){
		//menu.x = left;
		//menu.y = top;
		if (isSubMenu){
			if (menu.getAttribute('parentid') == currentMenu.getAttribute('parentid')){
				//menuStack.pop();
				menuPop();
				setMenuVisibility(currentMenu, false);
			}
		}else{
			//menuStack.pop();
			menuPop();
			setMenuVisibility(currentMenu, false);
		}
		currentMenu = menu;
		//menuStack.push(menu);
		menuPush(menu);
		setMenuVisibility(menu, true, x, y);
	}
}

function getLayer(layerid){
/*
	if (document.layers && layerid){
		if (document.layers[layerid]) return document.layers[layerid];
	}
	if (document.links && layerid){
		if (document.links[layerid]) return document.links[layerid];
	}
	if (document.all && layerid){
		if (document.all(layerid)) return document.all(layerid);
	}
*/
	return document.getElementById(layerid);
}

function setMenuVisibility(menu, on, x, y){
	var parent = menu;
	if (menu){
/*
		menu.visible = on;
		setLayer(menu.id, !menu.visible, menu.x, menu.y);
		setLayer(menu.id, !menu.visible, 0, 0);
		menu.visible = on;
*/
		setLayer(menu.id, !on, x, y);
		if (is.ie){
			if (!on){
				if (!menu.getAttribute('parentid')){
					showElement("SELECT");
				}
			}else{
				hideElement("SELECT", x, y, menu.offsetWidth, menu.offsetHeight);
			}
		}
/*
		setLayer(menu.id, !menu.visible, 0, 0);
		var menuWidth, menuHeight;
		var menuLayer = getLayer(menu.id);
		if (menuLayer){
			if (blnIE55){
				menuWidth = menuLayer.clientWidth;
				menuHeight = menuLayer.clientHeight;
			}else{
				menuWidth = menuLayer.offsetWidth;
				menuHeight = menuLayer.offsetHeight;
			}
			if (menu.x+menuWidth > clientWindowWidth){
				menu.x = clientWindowWidth - menuWidth - 25;
				if (menu.x < 10){
					menu.x = 10;
				}
			}
			if (menu.y+menuHeight > clientWindowHeight){
				menu.y = clientWindowHeight - menuHeight - 25;
				if (menu.y < 10){
					menu.y = 10;
				}
			}
			setLayer(menu.id, !menu.visible, menu.x, menu.y);
		}
*/
	}
/*
	var parentid = menu.parentid;
	while (parentid){
		parent = getMenu(menu.paneID, parentid);
		if (parent){
			parent.visible = on;
			setLayer(parent.id, !parent.visible, parent.x, parent.y);
			parentid = parent.parentid;
			if (on == false) currentMenu = parent;
		}else{
			parentid = null;
		}
	}
	}
	return parent;
*/
}

function menuPop(){
	if (is.ie && (is.mac || !is.ie5_5up)){
		var menu = menuStack[menuStack.length-1];
		var newMenuStack = new Array();
		for (var i = 0; i < menuStack.length-1; i++){
			newMenuStack[newMenuStack.length] = menuStack[i];
		}
		menuStack = newMenuStack;
		return menu;
	}else{
		return menuStack.pop();
	}
}

function menuPush(menu){
	if (is.ie && (is.mac || !is.ie5_5up)){
		menuStack[menuStack.length] = menu;
	}else{
		menuStack.push(menu);
	}
}

function checkBrowserWidth(){
	var	windowWidth;
	if (is.ie){
		windowWidth = document.body.clientWidth;
	}else{
		// 17px for scrollbar width
		windowWidth = window.innerWidth - 16;
	}
	if (windowWidth >= 1000){
		showSB('sbContent',true,'sb');
	}else{
		showSB('sbContent',false,'sb');
	}
	return windowWidth;
}

function showSB(id, hideit, imgIdPrefix){
	setLayer(id, !hideit, -1, -1);
	setLayer(imgIdPrefix+'On', !hideit, -1, -1);
	setLayer(imgIdPrefix+'Off', hideit, -1, -1);
}

function setLayer(id, hidden, x, y){
	var layer = getLayer(id);
	setLayerElm(layer, hidden, x, y);
}

function setLayerElm(layer, hideit, x, y){
	if (layer && layer.style){
		if (hideit){
			layer.style.visibility='hidden';
			//layer.style.display='none';
		}else{
			layer.style.display='block';
			layer.style.visibility='visible';
		}
		if (x >=0 && y >= 0){
//alert(layer.id+': '+x+', '+y+'\n'+layer.offsetLeft+', '+layer.offsetTop);
			//layer.style.left=x;
			//layer.style.top=y;
			layer.style.left = x+'px';
			layer.style.top = y+'px';
		}
	}
}

function hiliteItem(menuItem,changeClass){
	closeMenusDelay = eraseTimeout(closeMenusDelay);
	if (changeClass=='yes') {
		if (menuItem.getAttribute('avid') == 'false'){
			menuItem.className = 'menuItemHiliteX';
		}else{
			menuItem.className = 'menuItemHilite';
		}
	}
}
function unhiliteItem(menuItem){
	closeMenusDelay = eraseTimeout(closeMenusDelay);
	if (menuItem.getAttribute('avid') == 'false'){
		menuItem.className = 'menuItemX';
	}else{
		menuItem.className = 'menuItem';
	}
}

function showElement(elmID){
	for (i = 0; i < document.getElementsByTagName(elmID).length; i++)	{
		obj = document.getElementsByTagName(elmID)[i];
		if (! obj || ! obj.offsetParent)
			continue;
		obj.style.visibility = "";
	}
}
function showElementNew(elmID){
	if (hiddenElmStack.length > 0){
		for (var i = hiddenElmStack.length-1; i >=0; i--){
			var obj = hiddenElmStack[hiddenElmStack.length-1];
			obj.style.visibility = "";;
			hiddenElmStack.pop();
		}
	}
}

function hideElement(elmID,x,y,w,h){
	for (i = 0; i < document.getElementsByTagName(elmID).length; i++){
		obj = document.getElementsByTagName(elmID)[i];
		if (! obj || ! obj.offsetParent)
			continue;

		// Find the element's offsetTop and offsetLeft relative to the BODY tag.
		objLeft   = obj.offsetLeft;
		objTop    = obj.offsetTop;
		objParent = obj.offsetParent;
		while (objParent.tagName.toUpperCase() != "BODY"){
			objLeft  += objParent.offsetLeft;
			objTop   += objParent.offsetTop;
			objParent = objParent.offsetParent;
		}
		// Adjust the element's offsetTop relative to the dropdown menu
		objTop = objTop - y;

		if (x > (objLeft + obj.offsetWidth) || objLeft > (x + w))
			;
		else if (objTop > h)
			;
		else if ((y + h) <= 80)
			;
		else {
			obj.style.visibility = "hidden";
			//hiddenElmStack.push(obj);
		}
	}
}

function Is (){
    // convert all characters to lowercase to simplify testing
    var agt = navigator.userAgent.toLowerCase();

    // *** BROWSER VERSION ***
    // Note: On IE5, these return 4, so use is.ie5up to detect IE5.
    this.major = parseInt(navigator.appVersion);
    this.minor = parseFloat(navigator.appVersion);

    // Note: Opera and WebTV spoof Navigator.  We do strict client detection.
    // If you want to allow spoofing, take out the tests for opera and webtv.
    this.nav  = ((agt.indexOf('mozilla')!=-1) && (agt.indexOf('spoofer')==-1)
                && (agt.indexOf('compatible') == -1) && (agt.indexOf('opera')==-1)
                && (agt.indexOf('webtv')==-1) && (agt.indexOf('hotjava')==-1));
    this.nav2 = (this.nav && (this.major == 2));
    this.nav3 = (this.nav && (this.major == 3));
    this.nav4 = (this.nav && (this.major == 4));
    this.nav4up = (this.nav && (this.major >= 4));
    this.navonly      = (this.nav && ((agt.indexOf(";nav") != -1) ||
                          (agt.indexOf("; nav") != -1)) );
    this.nav6 = (this.nav && (this.major == 5));
    this.nav6up = (this.nav && (this.major >= 5));
    this.gecko = (agt.indexOf('gecko') != -1);

    this.nav7 = (this.gecko && (this.major >= 5) && (agt.indexOf('netscape/7')!=-1));
    this.moz1 = false;
    this.moz1up = false;
    this.moz1_1 = false;
    this.moz1_1up = false;
    if (this.nav6up){
//    if (this.nav){
       myRegEx = new RegExp("rv:\\d*.\\d*.\\d*");
       //myFind = myRegEx.exec("; rv:9.10.5)");
       myFind = myRegEx.exec(agt);
	   if(myFind!=null){
         var strVersion = myFind.toString();
         strVersion = strVersion.replace(/rv:/,'');
         var arrVersion = strVersion.split('.');
         var major = parseInt(arrVersion[0]);
         var minor = parseInt(arrVersion[1]);
         if (arrVersion[2]) var revision = parseInt(arrVersion[2]);
         this.moz1 = ((major == 1) && (minor == 0));
         this.moz1up = ((major == 1) && (minor >= 0));
         this.moz1_1 = ((major == 1) && (minor == 1));
         this.moz1_1up = ((major == 1) && (minor >= 1));
	  }
    }

    this.ie     = ((agt.indexOf("msie") != -1) && (agt.indexOf("opera") == -1));
    this.ie3    = (this.ie && (this.major < 4));
    this.ie4    = (this.ie && (this.major == 4) && (agt.indexOf("msie 4")!=-1) );
    this.ie4up  = (this.ie  && (this.major >= 4));
    this.ie5    = (this.ie && (this.major == 4) && (agt.indexOf("msie 5.0")!=-1) );
    this.ie5_5  = (this.ie && (this.major == 4) && (agt.indexOf("msie 5.5") !=-1));
    this.ie5up  = (this.ie  && !this.ie3 && !this.ie4);

    this.ie5_5up =(this.ie && !this.ie3 && !this.ie4 && !this.ie5);
    this.ie6    = (this.ie && (this.major == 4) && (agt.indexOf("msie 6.")!=-1) );
    this.ie6up  = (this.ie  && !this.ie3 && !this.ie4 && !this.ie5 && !this.ie5_5);

	this.mac    = (agt.indexOf("mac") != -1);
}

function checkBrowser (){
	//if (is.ie5_5up || is.nav7 || is.moz1up) ;// supported browser
	//else alert('This browser is not a supported browser or browser version.\nUnexpected or erroneous behavior may occur.');
	//else alert(strBrowserWarn1+'\n'+strBrowserWarn2+'\n'+strBrowserWarn3);
	if (!is.ie5up && !is.nav6up){
		//alert('Avid.com is a richly-featured site that requires the latest version of an Internet browser. Please upgrade your browser in order to take full advantage of what the site has to offer.');
	}
}

function runPageLoadItems (){
	var myVar;
	//alert('called');
	checkBrowserWidth();
}
var is = new Is();
checkBrowser();

//window.onload = alert('page loaded');
//window.onload = myFunctionCall();
//window.onresize=checkBrowserWidth();
//window.onload=runPageLoadItems();

if (is.ie) {
	document.write('<style type="text/css">');
	document.write('body {font-size: x-small;}');
	document.write ('</style>');
}

