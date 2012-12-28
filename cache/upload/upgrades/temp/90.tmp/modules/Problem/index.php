<?php
if(empty($GLOBALS['sugarEntry'])) die('Not A Valid Entry Point');
/*******************************************************************************
 * Initial access point for the Problem tab
 ******************************************************************************/

  global $theme;
  $theme_path = 'themes/' . $theme . '/';
  $image_path = $theme_path .'images/';

//get rid of the export link in the listview
  $sugar_config['disable_export'] = true;
  require_once($theme_path . 'layout_utils.php');
  global $mod_strings;
  echo "\n<p>\n";
  echo get_module_title($mod_strings['LBL_MODULE_NAME'],
	 $mod_strings['LBL_MODULE_TITLE'], true);
  echo "\n</p>\n";
  include ("modules/$currentModule/ListView.php"); 

?>
