<?php

global $app_strings;
global $app_list_strings;
global $current_user;
global $mod_strings;
global $theme;
global $currentModule;
global $gridline;

if (!is_admin($current_user))
  sugar_die("Unauthorized access to administration.");

$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');


echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_CONFIG_TITLE'], true);
echo "\n</p>\n";

?>

<p>
<table width="100%" cellpadding="0" cellspacing="<?php echo $gridline;?>" border="0" class="tabDetailView2">
<tr>
</tr>
</table>
</p>


?>
