<?php

require_once('XTemplate/xtpl.php');
require_once("data/Tracker.php");
require_once("include/utils.php");

global $currentModule, $theme, $focus, $action, $open_status, $log;
global $app_strings;
global $current_language;
$current_module_strings = return_module_language($current_language, 'Users');




	echo get_form_header($current_module_strings['LBL_ACTIVITY_REPORT'], '','', false);
	$search_form->out("main");
	//echo get_form_footer();
	echo "\n</p>\n";
	
?>