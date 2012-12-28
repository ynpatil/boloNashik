<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

/**
 * Initial access point for the Problem tab
 */

// $Id: index.php,v 1.2.12.1 2006/01/08 04:36:05 majed Exp $

global $theme;
$theme_path = 'themes/' . $theme . '/';
$image_path = $theme_path .'images/';

// get rid of the export link in the listview
$sugar_config['disable_export'] = true;

require_once($theme_path . 'layout_utils.php');

global $mod_strings;

echo "\n<p>\n";

echo get_module_title($mod_strings['LBL_MODULE_NAME'],
	$mod_strings['LBL_MODULE_TITLE'], true);

echo "\n</p>\n";

include ("modules/$currentModule/ListView.php"); 

?>
