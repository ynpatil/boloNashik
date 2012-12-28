<?php
/*
***** SugarTime *****
Developed by Paul K. Lynch, Everyday Interactive Networks (ein.com.au)
Mozilla Public License v1.1
*/
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once ($theme_path."layout_utils.php");
global $mod_strings;
echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_TITLE'], true); 
echo "\n</p>\n";

include ('modules/sugartime/listview.php');

?>
