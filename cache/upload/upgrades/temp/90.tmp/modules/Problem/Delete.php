<?php
if(empty($GLOBALS['sugarEntry'])) die('Not A Valid Entry Point');
/*******************************************************************************
 * Delete functionality for a Problem
 ******************************************************************************/

require_once('modules/Problem/Problem.php');
require_once('log4php/LoggerManager.php');

$sugarbean = new Problem();

// perform the delete if given a record to delete

if(empty($_REQUEST['record']))
{
	$GLOBALS['log']->info('delete called without a record id specified');
}
else
{
	$record = $_REQUEST['record'];
	$sugarbean->retrieve($record);
	if(!$sugarbean->ACLAccess('Delete')){
		ACLController::displayNoAccess(true);
		sugar_cleanup(true);
	}
	$GLOBALS['log']->info("deleting record: $record");
	$sugarbean->mark_deleted($record);
}

// handle the return location variables

$return_module = empty($_REQUEST['return_module']) ? 'Problem'
	: $_REQUEST['return_module'];

$return_action = empty($_REQUEST['return_action']) ? 'index'
	: $_REQUEST['return_action'];

$return_id = empty($_REQUEST['return_id']) ? ''
	: $_REQUEST['return_id'];

$return_location = "index.php?module=$return_module&action=$return_action";

// append the return_id if given
if(!empty($return_id))
{
	$return_location .= "&record=$return_id";
}

// now that the delete has been performed, return to given location

header("Location: $return_location");

?>
