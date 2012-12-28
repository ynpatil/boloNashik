<?php

// Script to convert Google xml weather interface to custom buttons AtomFeed 1.0
// Sample input at http://www.google.com/ig/api?weather=94043
// To see sample output, run this script with ?zip=94043

$zip = $_GET['zip'];

header('Content-type: text/xml');

// http://www.google.com/ig/api?weather=94043
$dom= domxml_open_file("http://www.google.com/ig/api?weather=400001");

$xpath = xpath_new_context($dom);
// $params = $dom->documentElement->firstChild->getElementsByTagName('param');

$temp = $xpath->xpath_eval('//city/@data');
$city = $temp->nodeset[0]->value;

$icons = $xpath->xpath_eval('//current_conditions/icon/@data');
$icon_url = $icons->nodeset[0]->value;
$icon = base64_encode(file_get_contents("http://www.google.com$icon_url"));

$conditions = $xpath->xpath_eval('//current_conditions/condition/@data');
$condition = $conditions->nodeset[0]->value;

$temp = $xpath->xpath_eval('//current_conditions/temp_f/@data');
$tempf = $temp->nodeset[0]->value;

$temp = $xpath->xpath_eval('//current_conditions/humidity/@data');
$humidity = $temp->nodeset[0]->value;

$temp = $xpath->xpath_eval('//current_conditions/wind_condition/@data');
$wind = $temp->nodeset[0]->value;

echo "<?xml version='1.0'?>";
echo "\n<feed xmlns='http://www.w3.org/2005/Atom' ";
echo     "xmlns:gtb='http://toolbar.google.com/custombuttons/'>";
echo "\n<id>http://www.example.com/custombuttons/samples/feeds/weather</id>";
echo "\n<title>Weather $city</title>";
echo "\n<link href='http://www.google.com/search?q=weather+$zip/' />";
echo "\n<link rel='self' href='http://www.example.com/custombuttons/";
echo     "samples/feeds/weather' />";
echo "\n<gtb:description>$city\n$tempfF $condition\n$humidity\n$wind";
echo   "</gtb:description>";
echo "\n<gtb:icon mode='base64' type='image/x-icon'>$icon</gtb:icon>";

$days = $xpath->xpath_eval('//forecast_conditions/day_of_week/@data');
$conditions = $xpath->xpath_eval('//forecast_conditions/condition/@data');
$icons = $xpath->xpath_eval('//forecast_conditions/icon/@data');
$highs = $xpath->xpath_eval('//forecast_conditions/high/@data');
$lows = $xpath->xpath_eval('//forecast_conditions/low/@data');

for ($i = 0; $i < count($conditions->nodeset); $i++) {
  $day_of_week = $days->nodeset[$i]->value;
  $condition = $conditions->nodeset[$i]->value;
  $high = $highs->nodeset[$i]->value;
  $low = $lows->nodeset[$i]->value;
  echo "\n<entry>";
  echo "\n<title>$day_of_week: $condition $high | $low</title>";
  echo "\n<link href='http://www.google.com/search?q=weather+$zip/' />";
  echo "\n<id>http://www.example.com/custombuttons/samples/feeds/weather/$i</id>";
  $icon_url = $icons->nodeset[$i]->value;
  $icon = base64_encode(file_get_contents("http://www.google.com$icon_url"));
  echo "\n<gtb:icon mode='base64' type='image/x-icon'>$icon</gtb:icon>";
  echo "\n</entry>";
}

echo "\n</feed>";

?>
