<?php
/**
 * Save functionality for ProblemSolution
 */

// $Id: Save.php,v 1.13 2005/12/06 03:16:22 roger Exp $
require_once('modules/ProblemSolution/ProblemSolution.php');
require_once('include/TimeDate.php');

$project = new Solution();
if(!empty($_POST['record']))
{
	$project->retrieve($_POST['record']);
}
////
//// save the fields to the ProjectTask object
////

foreach($project->column_fields as $field)
{
	if(isset($_REQUEST[$field]))
	{
		$project->$field = $_REQUEST[$field];
	}

	if(!isset($_REQUEST['milestone_flag']))
	{
		$project->milestone_flag = 'off';
	}
}

//$project->time_start = str_replace('.',':',$_REQUEST['time_start']);
//$project->time_due = str_replace('.',':',$_REQUEST['time_due']);
// Get GMT clean values

if(!empty($_REQUEST['date_start']) && !empty($_REQUEST['time_start'])){
	$time_start_meridiem = "";
	if(isset($_REQUEST['time_start_meridiem'])){
		$time_start_meridiem = $_REQUEST['time_start_meridiem'];
	}
	
	$project->date_start = $_REQUEST['date_start'];
	$project->time_start = $_REQUEST['time_start'].$time_start_meridiem;
}
if(!empty($_REQUEST['date_due']) && !empty($_REQUEST['time_due'])){
	$time_due_meridiem = "";
	if(isset($_REQUEST['time_due_meridiem'])){
		$time_due_meridiem = $_REQUEST['time_due_meridiem'];
	}

	$project->date_due = $_REQUEST['date_due'];
	$project->time_due =  $_REQUEST['time_due'].$time_due_meridiem;
}

// disable SugarBean date processing
$project->process_save_dates = false;

$GLOBALS['check_notify'] = false;
if (!empty($_POST['assigned_user_id']) && ($focus->assigned_user_id != $_POST['assigned_user_id']) && ($_POST['assigned_user_id'] != $current_user->id)) {
	$GLOBALS['check_notify'] = true;
}

	if(!$project->ACLAccess('Save')){
		ACLController::displayNoAccess(true);
		sugar_cleanup(true);
	}
$project->save($GLOBALS['check_notify']);
if(isset($_REQUEST['form']))
{
	// we are doing the save from a popup window
	echo '<script>opener.window.location.reload();self.close();</script>';
	die();
}
else
{
	// need to refresh the page properly

	$return_module = empty($_REQUEST['return_module']) ? 'ProblemSolution'
		: $_REQUEST['return_module'];

	$return_action = empty($_REQUEST['return_action']) ? 'index'
		: $_REQUEST['return_action'];

	$return_id = empty($_REQUEST['return_id']) ? $project->id
		: $_REQUEST['return_id'];

header("Location: index.php?module=$return_module&action=$return_action&record=$return_id");

}
?>
