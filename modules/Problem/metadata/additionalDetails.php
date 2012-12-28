<?php 
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

/*********************************************************************************
 * KnowledgeBase module
 * Creates Problem Additional Details popup for the small tringle on list view line
 *********************************************************************************/
 
require_once('include/utils.php');

function additionalDetailsProblem($fields) {
	static $mod_strings;
	if(empty($mod_strings)) {
		global $current_language;
		$mod_strings = return_module_language($current_language, 'Problem');
	}
		
	$overlib_string = '';
	
	if(!empty($fields['DESCRIPTION'])) {
		$overlib_string .= '<b>'. $mod_strings['LBL_DESCRIPTION'] . '</b> ' . substr($fields['DESCRIPTION'], 0, 300);
		if(strlen($fields['DESCRIPTION']) > 300) $overlib_string .= '...';
	}	

	return array('fieldToAddTo' => 'NAME', 'string' => $overlib_string);
}
 
 ?>
 
 
