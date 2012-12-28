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

// $Id: User.js,v 1.13 2006/08/25 23:38:29 chris Exp $

var from_popup_return  = false;
function city_set_return(popup_reply_data)
{
	//alert("Popup :"+popup_reply_data.next_field);
	from_popup_return = true;
	var form_name = popup_reply_data.form_name;
	var name_to_value_array = popup_reply_data.name_to_value_array;
	related_id='EMPTY';
	next_field='';
	for (var the_key in name_to_value_array)
	{
		//alert("the key :"+the_key);
		if(the_key == 'toJSON')
		{
			/* just ignore */
		}
		else if(the_key == 'next_field'){
		alert("name :"+name_to_value_array);
		next_field = name_to_value_array[the_key].replace(/&amp;/gi,'&').replace(/&lt;/gi,'<').replace(/&gt;/gi,'>').replace(/&#039;/gi,'\'').replace(/&quot;/gi,'"');
		
		}
		else
		{
			var displayValue=name_to_value_array[the_key];
			displayValue=displayValue.replace('&#039;',"'");  //restore escaped single quote.
			displayValue=displayValue.replace( '&amp;',"&");  //restore escaped &.
			displayValue=displayValue.replace( '&gt;',">");  //restore escaped >.
			displayValue=displayValue.replace( '&lt;',"<");  //restore escaped <.
			displayValue=displayValue.replace( '&quot; ',"\"");  //restore escaped ".
			if (the_key == 'address_city') {
				related_id =displayValue;
			}
			window.document.forms[form_name].elements[the_key].value = displayValue;
		}
	}
	alert("Next field :"+next_field);
	getCityDetails(next_field);
}

function state_set_return(popup_reply_data)
{
	from_popup_return = true;
	var form_name = popup_reply_data.form_name;
	var name_to_value_array = popup_reply_data.name_to_value_array;
	related_id='EMPTY';
	for (var the_key in name_to_value_array)
	{
	//	alert("the key :"+the_key);
		if(the_key == 'toJSON')
		{
			/* just ignore */
		}
		else
		{
			var displayValue=name_to_value_array[the_key];
			displayValue=displayValue.replace('&#039;',"'");  //restore escaped single quote.
			displayValue=displayValue.replace( '&amp;',"&");  //restore escaped &.
			displayValue=displayValue.replace( '&gt;',">");  //restore escaped >.
			displayValue=displayValue.replace( '&lt;',"<");  //restore escaped <.
			displayValue=displayValue.replace( '&quot; ',"\"");  //restore escaped ".
			if (the_key == 'address_state') {
				related_id =displayValue;
			}
			window.document.forms[form_name].elements[the_key].value = displayValue;
		}
	}
	
	getStateDetails(popup_reply_data.next_field);
}

function getCityDetails(field){
	//alert("In getCityDetails "+field);
	fieldName = field;
	http.open('post','/sfa/getCityDetails.php');
	http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	http.onreadystatechange = handleCityDetails;
	http.send('city_id='+document.getElementById(fieldName).value);
}

function handleCityDetails(){
	/* Make sure that the transaction has finished. The XMLHttpRequest object
		has a property called readyState with several states:
		0: Uninitialized
		1: Loading
		2: Loaded
		3: Interactive
		4: Finished */
	if(http.readyState == 4){ //Finished loading the response
		var response = http.responseXML;
		var xmlObj = response.documentElement.selectSingleNode("state");

		if(xmlObj)
		{
			document.getElementById('address_state_desc').value = xmlObj.getAttribute("description");
			document.getElementById('address_state').value = xmlObj.getAttribute("id");
		}

		xmlObj = response.documentElement.selectSingleNode("country");
		if(xmlObj)
		{
			document.getElementById('address_country_desc').value = xmlObj.getAttribute("description");
			document.getElementById('address_country').value = xmlObj.getAttribute("id");
		}
	}
}

function getStateDetails(field){
	fieldName = field;
	http.open('post','/sfa/getStateDetails.php');
	http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	http.onreadystatechange = handleStateDetails;
	http.send('state_id='+document.getElementById(fieldName).value);
}

function handleStateDetails(){
	/* Make sure that the transaction has finished. The XMLHttpRequest object
		has a property called readyState with several states:
		0: Uninitialized
		1: Loading
		2: Loaded
		3: Interactive
		4: Finished */
	if(http.readyState == 4){ //Finished loading the response
		var response = http.responseXML;
		var xmlObj = response.documentElement.selectSingleNode("country");
		if(xmlObj)
		{
			var newField = fieldName.substring(0,fieldName.lastIndexOf("_"))+"_country";
			document.getElementById(newField+'_desc').value = xmlObj.getAttribute("description");
			document.getElementById(newField).value = xmlObj.getAttribute("id");
		}
	}
}

function createRequestObject()
{
	var request_o; //declare the variable to hold the object.
	var browser = navigator.appName; //find the browser name
	if(browser == "Microsoft Internet Explorer"){
		/* Create the object using MSIE's method */
		request_o = new ActiveXObject("Microsoft.XMLHTTP");
	}else{
		/* Create the object using other browser's method */
		request_o = new XMLHttpRequest();
	}
	return request_o; //return the object
}

/* The variable http will hold our new XMLHttpRequest object. */
var http = createRequestObject();
var fieldName;
