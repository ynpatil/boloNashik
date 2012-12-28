<?php
if(empty($GLOBALS['sugarEntry'])) die('Not A Valid Entry Point');
/*******************************************************************************
 * Side-bar menu for Problem
 ******************************************************************************/

global $current_user;
global $mod_strings;
$module_menu = array();

// Each index of module_menu must be an array of:
// the link url, display text for the link, and the icon name.

if(ACLController::checkAccess('Problem', 'edit', true))$module_menu[] = array("index.php?module=Problem&action=EditView&return_module=Problem&return_action=DetailView",
	$mod_strings['LNK_NEW_PROBLEM'], 'CreateProblem');
if(ACLController::checkAccess('Problem', 'list', true))$module_menu[] = array('index.php?module=Problem&action=index',
	$mod_strings['LNK_PROBLEM_LIST'], 'Problem');
if(ACLController::checkAccess('ProblemSolution', 'edit', true))$module_menu[] = array("index.php?module=ProblemSolution&action=EditView&return_module=ProblemSolution&return_action=DetailView",
	$mod_strings['LNK_NEW_PROBLEM_SOLUTION'], 'CreateProblemSolution');
if(ACLController::checkAccess('ProblemSolution', 'list', true))$module_menu[] = array('index.php?module=ProblemSolution&action=index',
	$mod_strings['LNK_PROBLEM_SOLUTION_LIST'], 'ProblemSolution');

?>
