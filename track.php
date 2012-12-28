<?php
if(!defined('sugarEntry'))define('sugarEntry', true);
$GLOBALS['sugarEntry'] = true;
require_once('include/entryPoint.php');
require_once('data/Tracker.php');

$user_id = $_GET['user_id'];
$module = $_GET['module'];
$entity_id = $_GET['module_id'];
$entity_summary = $_GET['module_summary'];

$tracker = new Tracker();
$tracker->track_view($user_id, $module, $entity_id, $entity_summary);
echo "Om Done";
?>