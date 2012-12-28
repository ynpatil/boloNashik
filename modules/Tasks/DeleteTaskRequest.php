<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('modules/Tasks/TaskRequest.php');
$focus = new TaskRequest();
$focus->retrieve($_REQUEST['record']);
$focus->deleted = 1;
$focus->save(FALSE);

header("Location: index.php?module=Tasks&action=RequestsListView");

?>
