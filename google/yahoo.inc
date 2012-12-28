<?php
//om
function request_cache($url, $dest_file, $timeout=43200) {
  if(!file_exists($dest_file) || filemtime($dest_file) < (time()-$timeout)) {
    $stream = fopen($url,'r');
    $tmpf = tempnam('/tmp','YWS');
    file_put_contents($tmpf, $stream);
    fclose($stream);
    rename($tmpf, $dest_file);
  }
}

function yahoo_geo($location) {
  $request = 'http://api.local.yahoo.com/MapsService/V1/geocode';
  $postargs = 'appid=XLVuXtvV34Eux4VTgQx1l.Et8iUgDubcuYiOYKihwSuG6V0YlctBhebJQZzbaJk-&location='.rawurlencode($location);
  $tmp = '/tmp/yws_geo_'.md5($location);
//  echo "Request :".$request."?".$postargs;
//  request_cache($q, $tmp, 43200);
  //libxml_use_internal_errors(true);
// $xml = simplexml_load_file($tmp); 
// Get the curl session object
  
  $session = curl_init($request);

	// Set the POST options.
	curl_setopt ($session, CURLOPT_POST, true);
	curl_setopt ($session, CURLOPT_POSTFIELDS, $postargs);
	curl_setopt($session, CURLOPT_HEADER, false);
	curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

	// Do the POST and then close the session
	$xml = curl_exec($session);
	curl_close($session);    

	$xml = trim($xml);  
	
//	echo $xml;
	$dom = new DOMDocument('1.0', 'UTF-8'); 
	if ($dom->loadXML($xml) === false) {
	   die('Parsing failed');
	}
	
	$lats = $dom->getElementsByTagName("Latitude");
	$lngs = $dom->getElementsByTagName("Longitude");

	$lat = $lats->item(0)->textContent;
	$lng = $lngs->item(0)->textContent;
	
	$ret = array();
	$ret['latitude'] = $lat;
	$ret['longitude'] = $lng;

    return $ret;
}

?>
