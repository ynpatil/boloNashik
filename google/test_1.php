<?php

   // Your Google Maps API key
   $key = "ABQIAAAAkug0nthHAqR_qYi5pwmw_BRXAJIoL5n65zLpVVhMWMvxEZWgRBSwLkssILD2QBcZtyHQuo3A2ru2rg";

   // Connect to the MySQL database
//   $conn = mysql_connect("localhost", "jason", "secretpswd");

   // Select the database
//   $db = mysql_select_db("googlemaps");

   // Query the table
   //$query = "SELECT id, address, city, state FROM hospitals";
   //$result = mysql_query($query) or die(mysql_error());

   // Loop through each row, submit HTTP request, output coordinates
   //while (list($id, $address, $city, $state) = mysql_fetch_row($result))
   //{
   	  $address = "Dr. D.N. Road";
   	  $city = "Mumbai";
   	  $state = "Maharastra";

      $mapaddress = urlencode("$address $city $state");

      // Desired address
      $url = "http://maps.google.com/maps/geo?q=$mapaddress&output=xml&key=$key";

      // Retrieve the URL contents
      $page = file_get_contents($url);

      // Parse the returned XML file
      $xml = new SimpleXMLElement($page);

      // Parse the coordinate string
      list($longitude, $latitude, $altitude) = explode(",", $xml->Response->Placemark->Point->coordinates);

      // Output the coordinates
      echo "latitude: $latitude, longitude: $longitude <br />";

   //}

?>

