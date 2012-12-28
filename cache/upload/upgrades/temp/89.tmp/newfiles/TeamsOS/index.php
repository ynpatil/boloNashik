
<?php
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once ($theme_path."layout_utils.php");
global $mod_strings;
echo "<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_TITLE'], true); 
echo "\n</p>\n";
include ('modules/TeamsOS/ListView.php'); 
?>
