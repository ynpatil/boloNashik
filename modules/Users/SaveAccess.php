<?php
require_once('modules/Users/Access.php');
global $current_user;

$users = $_REQUEST['user_options'];
$modules = $_REQUEST['access_modules'];

//echo "Users :".implode(",",$users);
//echo "Modules :".implode(",",$modules);

foreach($users as $user){

	foreach($modules as $module){
	
		$seed = new Access();
		$seed->user_id = $user;
		$seed->access_to_user_id = $current_user->id;
		$seed->access_to_module = $module;
		$seed->save(FALSE);		
	}
}	
$redirect = "index.php?action=AssetDetailView&module=Users";
header("Location: {$redirect}");
?>