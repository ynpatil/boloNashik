<?php
global $app_list_strings;

require_once('include/JSON_NoAuth.php');
$json = new JSON(JSON_LOOSE_TYPE);
			
if(isset($app_list_strings[$_REQUEST['id']]))
echo $app_list_strings[$_REQUEST['id']];
else
echo "Empty";
?>