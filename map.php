<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>Google Maps - Draggable Marker</title>
    <link rel="stylesheet" href="../style.css" type="text/css">
    <script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=ABQIAAAAkug0nthHAqR_qYi5pwmw_BRXAJIoL5n65zLpVVhMWMvxEZWgRBSwLkssILD2QBcZtyHQuo3A2ru2rg" type="text/javascript"></script>
  </head>
  <body onunload="GUnload()">

		<table>
			<tr>
				<td align="center">
					<div id="map" style="width: 600px; height: 500px"></div>

					<input type="button" value="Retrieve Plotter" onClick="centerMarker()" />
				</td>
				<td> </td>
				<td>
					<table>
						<tr><td width="80"></td><td width="95%"></td></tr>
						<tr>
							<td colspan='2'>

								<h1>Get Google Maps Coordinates</h1>
								Simply drag and drop the marker where you want.<br/>
							  You will then retrieve it's geographical coordinates.<br/>
							  <br/>
							  Then enter a title, a description and click on PLOT.<br/>
							  You will have plotted a new marker and in the textarea below it's associated KML code.<br/>

							  <br/>
							</td>
						</tr>
						<form name="form_map" action="/sfa/map.php" method="POST">
						<tr><td>Find approx location:</td><td><input type="text" name="location" /><input type="submit" value="Search"/>
						</td></tr>						
<?php
require_once('google/yahoo.inc');
if(!empty($_REQUEST['location'])) {
	$a = yahoo_geo($_REQUEST['location']);  
	echo "<pre>"; print_r($a); echo "</pre>";
	if(is_array($a)){
		$lat = $a['Latitude'];
		$lng = $a['Longitude'];
	}
	else{
		$lat = "";
		$lng = "";
	}
}
?>						<tr><td>Lat:</td><td><input type="text" id="<?= $lat ?>" /></td></tr>
						<tr><td>Lng:</td><td><input type="text" id="<?= $lng ?>" /></td></tr>
						<tr><td>Title:</td><td><input type="text" id="title" size="40" /></td></tr>
						<tr><td>Desc:</td><td><textarea id="desc" rows="10" cols="30"></textarea></td></tr>

						<tr><td></td><td><input type="button" value="PLOT!" onClick="saveMarker()" /></td></tr>
					</table>
				</td>
			</tr>
		</table>
		<hr />
		KML code generated:<br/>
		<span style="font-family:courier">

		 &lt;?xml version="1.0" encoding="UTF-8"?&gt; <br/>
     &lt;kml xmlns="http://earth.google.com/kml/2.1"&gt; <br/>
	   &lt;Folder&gt; <br/>
		</span>
		<textarea id="xml" rows="20" cols="80"></textarea><br/>

		<span style="font-family:courier">
	   &lt;/Folder&gt; <br/>
		 &lt;/kml&gt;
	  </span>

    <noscript><b>JavaScript must be enabled in order for you to use Google Maps.</b>
      However, it seems JavaScript is either disabled or not supported by your browser.
      To view Google Maps, enable JavaScript by changing your browser options, and then
      try again.
    </noscript>

    <script type="text/javascript">
    //<![CDATA[

		// main marker
		var marker;

		// Retrieve the URL parameter specified
		function getURLParam(strParamName){
		  var strReturn = "";
		  var strHref = window.location.href;
		  if (strHref.indexOf("?") > -1) {
		    var strQueryString = strHref.substr(strHref.indexOf("?")).toLowerCase();
		    var aQueryString = strQueryString.split("&");
		    for (var iParam = 0; iParam < aQueryString.length; iParam++ ){
		      if (aQueryString[iParam].indexOf(strParamName + "=") > -1 ){
		        var aParam = aQueryString[iParam].split("=");
		        strReturn = aParam[1];
		        break;
		      }
		    }
		  }
		  return strReturn;
		}

		// A function to create the marker and set up the event window
		function centerMarker() {
			marker.setPoint(new GLatLng(map.getCenter().lat(), map.getCenter().lng()))
		  document.getElementById('lat').value = marker.getPoint().lat();
		  document.getElementById('lng').value = marker.getPoint().lng();
		}

		// A function to create the marker and set up the event window
		function createMarker(point,name,html) {
			var i = new GIcon(G_DEFAULT_ICON, "google/smallmarker.png");
			i.iconSize = new GSize(12,20);
			i.iconAnchor = new GPoint(6, 20);
			i.shadowSize = new GSize(12,20);
			var m = new GMarker(point,{icon: i});
			GEvent.addListener(m, "click", function() {
				m.openInfoWindowHtml("<b>" + name + "</b><br />" + html);
			});
			map.addOverlay(m);
		}

		function saveMarker() {
			var lat   = document.getElementById('lat').value;
			var lng   = document.getElementById('lng').value;
			var title = document.getElementById('title').value;
			var desc  = document.getElementById('desc').value;
			var str;
			str = "<Placemark>\n";
			str+= "  <name>" + title + "</name>\n";
			str+= "  <description><![CDATA[" + desc + "]]></description>\n";
			str+= "  <Point>\n";
			str+= "    <coordinates>" + lat + "," + lng + ",0</coordinates>\n";
			str+= "  </Point>\n";
			str+= "</Placemark>\n";

			document.getElementById('xml').value = document.getElementById('xml').value + str;
			createMarker(new GPoint(lng, lat), title, desc);
		}

    if (GBrowserIsCompatible()) {

    	var map = new GMap2(document.getElementById("map"));
      map.addControl(new GLargeMapControl());
      map.addControl(new GMapTypeControl());
			var center = new GLatLng(48.857, 2.347);
			map.setCenter(center, 3);

			marker = new GMarker(center, {icon: new GIcon(G_DEFAULT_ICON, "google/bigmarker.png"), draggable: true});

			GEvent.addListener(marker, "dragstart", function() {
			  map.closeInfoWindow();
			  });

			GEvent.addListener(marker, "dragend", function() {
			  document.getElementById('lat').value = marker.getPoint().lat();
			  document.getElementById('lng').value = marker.getPoint().lng();
			});

			map.addOverlay(marker);

    }
    else {
      alert("Sorry, the Google Maps API is not compatible with this browser");
    }
    </script>

</body>
</html>