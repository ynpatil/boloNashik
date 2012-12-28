<?php
require_once('modules/Users/Access.php');
global $current_user;

$record = $_REQUEST['record'];
$seed = new Access();
$seed->retrieve($record);
$seed->deleted = 1;
$seed->save(FALSE);

$redirect = "index.php?action=AssetDetailView&module=Users";
header("Location: {$redirect}");
?>