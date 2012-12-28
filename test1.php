<?php
$xml = simplexml_load_file('testxml.xml');
$outstream = fopen('feed.csv','w');
$header=false;
echo "<pre>";
print_r($xml);

foreach($xml as $k=>$details){
    
    print_r($details);
    if(is_object($details)){
        echo $details->TALLYREQUEST;
    }
    exit;
//    if(!$header){
//        fputcsv($outstream,array_keys(get_object_vars($details)));
//        $header=true;
//    }
//    fputcsv($outstream,get_object_vars($details));
}
fclose($outstream);
exit;



  function xml2csv($xmlFile, $xPath)          {

	// Load the XML file
	$xml = simplexml_load_file($xmlFile);

	// Jump to the specified xpath
	$path = $xml->xpath($xPath);

	// Loop through the specified xpath
	foreach($path as $item) {
	
		// Loop through the elements in this xpath
		foreach($item as $key => $value) {
		
			$csvData .= '"' . trim($value) . '"' . ',';
		
		}
		
		// Trim off the extra comma
		$csvData = trim($csvData, ',');
		
		// Add an LF
		$csvData .= "\n";
	
	}
	
	// Return the CSV data
	return $csvData;
	
}

?>
