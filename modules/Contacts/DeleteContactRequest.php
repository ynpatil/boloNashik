<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('modules/Contacts/ContactRequest.php');
$focus = new ContactRequest();
$focus->retrieve($_REQUEST['record']);
$focus->deleted = 1;
$focus->save(FALSE);

header("Location: index.php?module=Contacts&action=RequestsListView");

?>
