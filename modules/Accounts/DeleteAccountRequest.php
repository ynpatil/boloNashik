<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('modules/Accounts/AccountRequest.php');
$focus = new AccountRequest();
$focus->retrieve($_REQUEST['record']);
$focus->deleted = 1;
$focus->save(FALSE);

header("Location: index.php?module=Accounts&action=RequestsListView");

?>
