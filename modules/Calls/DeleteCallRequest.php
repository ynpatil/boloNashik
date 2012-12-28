<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('modules/Calls/CallRequest.php');
$focus = new CallRequest();
$focus->retrieve($_REQUEST['record']);
$focus->deleted = 1;
$focus->save(FALSE);

header("Location: index.php?module=Calls&action=RequestsListView");

?>
