 
<?php
require_once('modules/TeamsOS/TeamOS.php');
global $mod_strings;

require_once('include/logging.php');

$focus = new TeamOS();

if(!isset($_REQUEST['record']))
	sugar_die($mod_strings['ERR_DELETE_RECORD']);

$focus->mark_deleted($_REQUEST['record']);
header("Location: index.php?module=".$_REQUEST['return_module']."&action=".$_REQUEST['return_action']."&record=".$_REQUEST['return_id']);
?>
