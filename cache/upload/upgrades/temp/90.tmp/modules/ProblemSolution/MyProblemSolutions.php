<?php
if(empty($GLOBALS['sugarEntry'])) die('Not A Valid Entry Point');
/**
 * Small subpanel for the Home page
 */

// $Id: MySolutions.php,v 1.4.4.1 2006/01/08 04:36:05 majed Exp $

require_once('XTemplate/xtpl.php');
require_once("data/Tracker.php");
require_once('modules/ProblemSolution/ProblemSolution.php');
require_once('themes/' . $theme . '/layout_utils.php');
require_once('log4php/LoggerManager.php');
require_once('include/ListView/ListView.php');
require_once('include/TimeDate.php');

$timedate = new TimeDate();
global $app_strings;
global $app_list_strings;
global $current_language, $current_user;
$current_module_strings = return_module_language($current_language, 'Solution');

$today = date("Y-m-d"); 
$today = $timedate->handle_offset($today, $timedate->dbDayFormat, false);

$ListView = new ListView();
$seedSolution = new Solution();
$where = "problem_solution.assigned_user_id='{$current_user->id}'"
	. " AND (problem_solution.status IS NULL OR (problem_solution.status!='Completed' AND problem_solution.status!='Deferred'))"
	. " AND (problem_solution.date_start IS NULL OR problem_solution.date_start <= '$today')";
$ListView->initNewXTemplate('modules/ProblemSolution/MySolutions.html',
	$current_module_strings);
$header_text = '';

if(is_admin($current_user)
	&& $_REQUEST['module'] != 'DynamicLayout'
	&& !empty($_SESSION['editinplace']))
{	
	$header_text = "&nbsp;<a href='index.php?action=index&module=DynamicLayout&from_action=MySolutions&from_module=ProblemSolution'>"
		. get_image($image_path."EditLayout","border='0' alt='Edit Layout' align='bottom'")
		. '</a>';
}
$ListView->setHeaderTitle($current_module_strings['LBL_LIST_MY_PROBLEM_SOLUTIONS'].$header_text);
$ListView->setQuery($where, "", "date_due,priority desc", "PROBLEM_SOLUTION");
$ListView->processListView($seedSolution, "main", "PROBLEM_SOLUTION");
?>
