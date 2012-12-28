<?php /* Smarty version 2.6.11, created on 2007-02-06 09:24:48
         compiled from custom/modules/Home/Dashlets/MapsDashlet/MapsDashletScript.tpl */ ?>


<?php echo '
<script type="text/javascript">
var YAHOO=window.YAHOO||{}; YAHOO.namespace=function(_1){ if(!_1||!_1.length){ return null; } var _2=_1.split("."); var _3=YAHOO; for(var i=(_2[0]=="YAHOO")?1:0;i<_2.length;++i){ _3[_2[i]]=_3[_2[i]]||{}; _3=_3[_2[i]]; } return _3; }; YAHOO.namespace("util"); YAHOO.namespace("widget"); YAHOO.namespace("example"); var YMAPPID = "SUGARCRMMAPSAPPID"; function _ywjs(inc) { var o=\'<\'+\'script src="\'+inc+\'"\'+\' type="text/javascript"><\'+\'/script>\'; document.write(o); } _ywjs(\'http://api.maps.yahoo.com/v3.4/aj/ymapapi.js\');
</script>
</script>
<script type="text/javascript" src="include/javascript/popup_parent_helper.js?s={$sugar_version}&c={$js_custom_version}"></script>
<script>
if(typeof Maps == \'undefined\') { // since the dashlet can be included multiple times a page, don\'t redefine these functions
	Maps = function() {
	var _map;
	var firstTime = true;
	var _site_url = "';  echo $this->_tpl_vars['site_url'];  echo '";
	 _yimg = new YImage(\'';  echo $this->_tpl_vars['site_url'];  echo '/include/images/sugar_icon.ico\', new YSize(15,15));
	    return {
	    	/**
	    	 * Called when the textarea is blurred
	    	 */
	        blur: function(ta, id) {
	        	ajaxStatus.showStatus(\'';  echo $this->_tpl_vars['saving'];  echo '\'); // show that AJAX call is happening
	        	// what data to post to the dashlet
    	    	postData = \'to_pdf=1&module=Home&action=CallMethodDashlet&method=mapRecord&id=\' + id + \'&trackingNumber=\' + ta.value;
				var cObj = YAHOO.util.Connect.asyncRequest(\'POST\',\'index.php\', 
								  {success: Maps.saved, failure: Maps.saved}, postData);
	        },
	        click: function(id) {
				
	        	Maps.drawMap(id, firstTime);
	        	firstTime = false;
				var zp = new YCoordPoint(40,40);
				// translate coordinates from left,top default
				zp.translate(\'left\',\'bottom\');
				_map.addZoomLong(zp);
					        			
				if(document.getElementById("'; ?>
maps_mapping_type_<?php echo $this->_tpl_vars['id'];  echo '").value == \'find\'){
//					var address1 = document.getElementById(\'maps_input_primary_address_street_\'+id).value;
					var address_city = document.getElementById(\'maps_input_primary_address_city_\'+id).value;
//					var address_state = document.getElementById(\'maps_input_primary_address_state_\'+id).value;
//					var address_postalcode = document.getElementById(\'maps_input_primary_address_postalcode_\'+id).value;
//					var address_country = document.getElementById(\'maps_input_primary_address_country_\'+id).value;
					YEvent.Capture(_map,EventsList.onEndGeoCode, Maps.EndGeoCode);
//					_map.geoCodeAddress(address1+" "+address_city+" ,"+address_state+" "+address_postalcode+" "+address_country);
					alert("City address :"+address_city);
					address_city="Mumbai";
					_map.geoCodeAddress(address_city);
				}else{
					var address1 = document.getElementById(\'maps_input_my_address_\'+id).value;
					var distance = document.getElementById(\'maps_input_my_dist_\'+id).value;
					//YEvent.Capture(_map,EventsList.onEndGeoCode, Maps.EndGeoCodeMyAddress);
					//_map.geoCodeAddress(address1);
					ajaxStatus.showStatus(\'';  echo $this->_tpl_vars['saving'];  echo '\'); // show that AJAX call is happening
	        	// what data to post to the dashlet
    	    	postData = \'to_pdf=1&module=Home&action=CallMethodDashlet&method=getClosest&id=\' + id + \'&zip=\'+address1+\'&distance=\'+distance;
				var cObj = YAHOO.util.Connect.asyncRequest(\'POST\',\'index.php\', 
								  {success: Maps.saved, failure: Maps.saved}, postData);
				}
				
				
	        },
	        drawMap: function(id, firstTime){
//        	alert("Document element id :"+document.getElementById(\'maps_output_\'+id)+" first time :"+firstTime);

	        if(firstTime){
	        	_map = new YMap(document.getElementById(\'maps_output_\'+id));
	        	}
	        	_map.addTypeControl();
					_map.addPanControl(); 
				// Add a slider zoom control 
				if(firstTime){
					_map.addZoomLong();
				}
				 
				_map.enableDragMap();
				
				//add HQ
			
				var hqPoint = new YGeoPoint(37.324077,-122.014068);
				var hqOutput = "<div style=\'width: 150px\'><a href=\'http://www.sugarcrm.com\' target=\'top\'>SugarCRM Inc.</a><br>10050 North Wolfe Road<br>SW2-130<br>Cupertino, CA 95014 USA<br>408.454.6900</div>";
				var marker = new YMarker(hqPoint, _yimg);
				//marker.addAutoExpand(hqOutput);
//				YEvent.Capture(marker,EventsList.MouseDoubleClick, 
				
				YEvent.Capture(marker,EventsList.MouseClick, 
    			function() { marker.openSmartWindow(hqOutput) });
	        	_map.addOverlay(marker); 
				
				// Set map type to either of: YAHOO_MAP_SAT YAHOO_MAP_HYB YAHOO_MAP_REG
				//map.setMapType(YAHOO_MAP_SAT);
				
				//Get valid map types, returns array [YAHOO_MAP_REG, YAHOO_MAP_SAT, YAHOO_MAP_HYB]
				var myMapTypes = _map.getMapTypes(); 

				_map.drawZoomAndCenter(hqPoint, 15);						
	        },
	        EndGeoCode: function(data)
	        {
	        	var marker = new YMarker(data.GeoPoint, _yimg);
				// Add a label to the marker
				id = \'';  echo $this->_tpl_vars['id'];  echo '\';
				var name =  document.getElementById(\'maps_input_\'+id).value;
				var record_id =  document.getElementById(\'maps_input_id_\'+id).value;
				var module = document.getElementById(\'maps_type_\'+id).value;
				var address1 = document.getElementById(\'maps_input_primary_address_street_\'+id).value;
				var address_city = document.getElementById(\'maps_input_primary_address_city_\'+id).value;
				var address_state = document.getElementById(\'maps_input_primary_address_state_\'+id).value;
				var address_postalcode = document.getElementById(\'maps_input_primary_address_postalcode_\'+id).value;
				var address_country = document.getElementById(\'maps_input_primary_address_country_\'+id).value;
				var phone_work = document.getElementById(\'maps_input_phone_work_\'+id).value;
				to_yahoo_url = "http://maps.yahoo.com/dd?taddr="+address1+"&tcsz="+address_city+"+"+address_state+"+"+address_postalcode+"&tcountry="+address_country;
				from_yahoo_url = "http://maps.yahoo.com/dd?addr="+address1+"&csz="+address_city+"+"+address_state+"+"+address_postalcode+"&country="+address_country;
				var name_href = _site_url+"/index.php?module="+module+"&record="+record_id+"&action=DetailView";
				output = "<div style=\'width: 150px\'><a href="+name_href+" target=top>"+name+"</a><br>"+address1+" "+address_city+" ,"+address_state+" "+address_postalcode+" "+address_country+"<br>"+phone_work+"<br>Directions: <a href=\'"+to_yahoo_url+"\' target=\'top\'>To here</a> - <a href=\'"+from_yahoo_url+"\' target=\'top\'>From here</a></div>";
				//marker.addAutoExpand(output);
				YEvent.Capture(marker,EventsList.MouseClick, 
    			function() { marker.openSmartWindow(output) });
	        	data.ThisMap.addOverlay(marker); 	
	        	data.ThisMap.drawZoomAndCenter(data.GeoPoint, 15);
	        },
	        EndGeoCodeMyAddress: function(data)
	        {
	        //console.debug("message",data) 
	        	var marker = new YMarker(data.GeoPoint);
				// Add a label to the marker
				id = \'';  echo $this->_tpl_vars['id'];  echo '\';
				var address1 = document.getElementById(\'maps_input_my_address_\'+id).value;
				//to_yahoo_url = "http://maps.yahoo.com/dd?taddr="+address1+"&tcsz="+address_city+"+"+address_state+"+"+address_postalcode+"&tcountry="+address_country;
				//from_yahoo_url = "http://maps.yahoo.com/dd?addr="+address1+"&csz="+address_city+"+"+address_state+"+"+address_postalcode+"&country="+address_country;
				output = address1;
				//marker.addAutoExpand(output);
				YEvent.Capture(marker,EventsList.MouseClick, 
    			function() { marker.openSmartWindow(output) });
	        	data.ThisMap.addOverlay(marker); 	
	        	data.ThisMap.drawZoomAndCenter(data.GeoPoint, 5);
	        },
		    /**
	    	 * Called when the textarea is double clicked on
	    	 */
			edit: function(divObj, id) {
				ta = document.getElementById(\'maps_input_\' + id);
				if(isIE) ta.value = divObj.innerHTML.replace(/<br>/gi, "\\n");
				else ta.value = divObj.innerHTML.replace(/<br>/gi, \'\');
				
				divObj.style.display = \'none\';
				ta.style.display = \'\';
				ta.focus();
			},
		    /**
	    	 * handle the response of the saveText method
	    	 */
	        saved: function(data) {
	        	
	        	eval(data.responseText);
	        	if(typeof result != \'undefined\') {
					ta = document.getElementById(\'maps_output_num_found_\' + result[\'id\']);
		        	ta.innerHTML = "';  echo $this->_tpl_vars['found'];  echo ': "+result[\'records_found\'].length+" "+result[\'module\']+"(s)";
		        	for (var x = 0; x < result[\'records_found\'].length; x++)
	   				{
	   					var marker = new YMarker(result[\'records_found\'][x][\'address_postalcode\'], _yimg);
	   					var name = result[\'records_found\'][x][\'name\'];
	   					var module = result[\'module\'];
	   					var record_id = result[\'records_found\'][x][\'id\'];
	   					var name_href = _site_url+"/index.php?module="+module+"&record="+record_id+"&action=DetailView";
	   					var address1 = result[\'records_found\'][x][\'address_street\'];
	   					var address_city = result[\'records_found\'][x][\'address_city\'];
	   					var address_state = result[\'records_found\'][x][\'address_state\'];
	   					var address_postalcode = result[\'records_found\'][x][\'address_postalcode\'];
	   					var address_country = result[\'records_found\'][x][\'address_country\']; 
	   					to_yahoo_url = "http://maps.yahoo.com/dd?taddr="+address1+"&tcsz="+address_city+"+"+address_state+"+"+address_postalcode+"&tcountry="+address_country;
						from_yahoo_url = "http://maps.yahoo.com/dd?addr="+address1+"&csz="+address_city+"+"+address_state+"+"+address_postalcode+"&country="+address_country;
	   					var output = "<div style=\'width: 150px\'><a href="+name_href+" target=top>"+name+"</a><br>"+result[\'records_found\'][x][\'address_street\']+" "+result[\'records_found\'][x][\'address_city\']+", "+result[\'records_found\'][x][\'address_state\']+" "+result[\'records_found\'][x][\'address_postalcode\']+" "+result[\'records_found\'][x][\'address_country\']+"<br>"+result[\'records_found\'][x][\'phone_office\']+"<br>irections: <a href=\'"+to_yahoo_url+"\' target=\'top\'>To here</a> - <a href=\'"+from_yahoo_url+"\' target=\'top\'>From here</a></div>";
						//marker.addAutoExpand(output);
						YEvent.Capture(marker,EventsList.MouseClick, 
	    			function() { marker.openSmartWindow(output) });
	   					_map.addOverlay(marker);
	   				}
	   				var marker = new YMarker(result[\'center_zip\'], _yimg);
	   				marker.addAutoExpand("You ("+result[\'center_zip\']+")");
	   				_map.addOverlay(marker);
	   				_map.drawZoomAndCenter(result[\'center_zip\'], 15);
		           	ajaxStatus.showStatus(\'';  echo $this->_tpl_vars['saved'];  echo '\');
	
					//ta.style.display = \'none\';
					//theDiv.style.display = \'\';
				}
	           	window.setTimeout(\'ajaxStatus.hideStatus()\', 2000);
	        },
	        openPopup: function(){
	        	element = document.getElementById("'; ?>
maps_type_<?php echo $this->_tpl_vars['id'];  echo '");
	        	new_module = element.value;
	        	if(new_module == \'Contacts\' || new_module == \'Leads\') {
					encoded_popup = "';  echo $this->_tpl_vars['encoded_popup_request_data_contacts'];  echo '";
				}
				else {
					encoded_popup = "';  echo $this->_tpl_vars['encoded_popup_request_data_other'];  echo '";
				}	
	        	open_popup(element.value, 600, 400, "&request_data="+encoded_popup, true, false);
	        }
	    };
	}();
} 
</script>'; ?>

<?php echo '
<script type="text/javascript" src="include/JSON.js?s={$sugar_version}&c={$js_custom_version}"></script><script type="text/javascript">
var GLOBAL_REGISTRY = new Object();

GLOBAL_REGISTRY.config = {"site_url":"';  echo $this->_tpl_vars['site_url'];  echo '"};
GLOBAL_REGISTRY.meta = new Object();
GLOBAL_REGISTRY.meta.modules = new Object();

GLOBAL_REGISTRY.current_user = {"theme":"Sugar","fields":{"id":"1","user_name":"admin","first_name":"","last_name":"Administrator","full_name":"Administrator","email":"roger@sugarcrm.com","dst_start":"2006-04-02 03:00:00","dst_end":"2006-10-29 01:00:00","gmt_offset":-300,"date_time_format":{"date":"Y-m-d","time":"H:i","userGmt":"(GMT-5)","userGmtOffset":-5}},"module":"User"};
</script>
		<script type="text/javascript" src="include/jsolait/init.js?s=';  echo $this->_tpl_vars['sugar_version'];  echo '&c=';  echo $this->_tpl_vars['js_custom_version'];  echo '"></script>
		<script type="text/javascript" src="include/jsolait/lib/urllib.js?s=';  echo $this->_tpl_vars['sugar_version'];  echo '&c=';  echo $this->_tpl_vars['js_custom_version'];  echo '"></script>
		<script type="text/javascript" src="include/javascript/entity_ref_to_html.js?s=';  echo $this->_tpl_vars['sugar_version'];  echo '&c=';  echo $this->_tpl_vars['js_custom_version'];  echo '"></script>
		<script type="text/javascript" src="include/javascript/jsclass_base.js?s=';  echo $this->_tpl_vars['sugar_version'];  echo '&c=';  echo $this->_tpl_vars['js_custom_version'];  echo '"></script>
		<script type="text/javascript" src="include/javascript/jsclass_async.js?s=';  echo $this->_tpl_vars['sugar_version'];  echo '&c=';  echo $this->_tpl_vars['js_custom_version'];  echo '"></script>
		<script type="text/javascript">sqsWaitGif = "themes/Sugar/images/sqsWait.gif";</script>
		<script type="text/javascript" src="include/javascript/quicksearch.js?s=';  echo $this->_tpl_vars['sugar_version'];  echo '&c=';  echo $this->_tpl_vars['js_custom_version'];  echo '"></script>
		<script type="text/javascript">
		function changeQS() {
			element = document.getElementById("'; ?>
maps_type_<?php echo $this->_tpl_vars['id'];  echo '");
			new_module = element.value;
			if(new_module == \'Contacts\' || new_module == \'Leads\') {
				sqs_objects[\''; ?>
maps_input_<?php echo $this->_tpl_vars['id'];  echo '\'][\'disable\'] = true;
				document.getElementById("'; ?>
maps_input_<?php echo $this->_tpl_vars['id'];  echo '").readOnly = true;
			}
			else {
				sqs_objects[\''; ?>
maps_input_<?php echo $this->_tpl_vars['id'];  echo '\'][\'disable\'] = false;
				document.getElementById("'; ?>
maps_input_<?php echo $this->_tpl_vars['id'];  echo '").readOnly = false;
			}	
			sqs_objects[\''; ?>
maps_input_<?php echo $this->_tpl_vars['id'];  echo '\'][\'module\'] = new_module;	
		}
		
		function selectDiv() {
			element = document.getElementById("'; ?>
maps_mapping_type_<?php echo $this->_tpl_vars['id'];  echo '");
			type = element.value;
			if(type == \'closest\') {
				document.getElementById("'; ?>
maps_find_div_<?php echo $this->_tpl_vars['id'];  echo '").style.visibility = \'hidden\';
				document.getElementById("'; ?>
maps_closest_div_<?php echo $this->_tpl_vars['id'];  echo '").style.visibility = \'visible\';
			}
			else {
				document.getElementById("'; ?>
maps_find_div_<?php echo $this->_tpl_vars['id'];  echo '").style.visibility = \'visible\';
				document.getElementById("'; ?>
maps_closest_div_<?php echo $this->_tpl_vars['id'];  echo '").style.visibility = \'hidden\';
			}	
		}
		</script>
		<script type="text/javascript" language="javascript">sqs_objects = {"'; ?>
maps_input_<?php echo $this->_tpl_vars['id'];  echo '":{"method":"query","modules":["Accounts"],"group":"or","field_list":["name","id", "billing_address_street", "billing_address_city", "billing_address_state", "billing_address_postalcode", "billing_address_country", "phone_office"],"populate_list":["'; ?>
maps_input_<?php echo $this->_tpl_vars['id'];  echo '", "'; ?>
maps_input_id_<?php echo $this->_tpl_vars['id'];  echo '", "'; ?>
maps_input_primary_address_street_<?php echo $this->_tpl_vars['id'];  echo '", "'; ?>
maps_input_primary_address_city_<?php echo $this->_tpl_vars['id'];  echo '", "'; ?>
maps_input_primary_address_state_<?php echo $this->_tpl_vars['id'];  echo '", "'; ?>
maps_input_primary_address_postalcode_<?php echo $this->_tpl_vars['id'];  echo '", "'; ?>
maps_input_primary_address_country_<?php echo $this->_tpl_vars['id'];  echo '", "'; ?>
maps_input_phone_work_<?php echo $this->_tpl_vars['id'];  echo '"],"conditions":[{"name":"name","op":"like_custom","end":"%","value":""}],"order":"name","limit":"30","no_match_text":"No Match"}}</script>
'; ?>