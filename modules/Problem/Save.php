<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

/**
 * Save functionality for Problem
 */

require_once('modules/Problem/Problem.php');
require_once('log4php/LoggerManager.php');
require_once('include/formbase.php');


$sugarbean = new Problem();
$sugarbean = populateFromPost('', $sugarbean);
if(!$sugarbean->ACLAccess('Save')){
		ACLController::displayNoAccess(true);
		sugar_cleanup(true);
}
$sugarbean->save($GLOBALS['check_notify']);
$return_id = $sugarbean->id;
handleRedirect($return_id,'Problem');

?>
