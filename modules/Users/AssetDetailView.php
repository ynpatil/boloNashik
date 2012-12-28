<?php

require_once('XTemplate/xtpl.php');
require_once("data/Tracker.php");
require_once("include/utils.php");
require_once("modules/Users/Access.php");

global $currentModule, $theme, $focus, $action, $open_status, $log;
global $app_strings;
global $current_language;
$current_module_strings = return_module_language($current_language, 'Users');

$xtpl=new XTemplate ('modules/Users/AssetDetailView.html');
$xtpl->assign("MOD", $current_module_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("THEME",$theme);
$xtpl->assign("IMAGE_PATH", $image_path);
$xtpl->assign("RETURN_URL", "&return_module=$currentModule&return_action=DetailView&return_id=" . ((is_object($focus) && ! empty($focus->id)) ? $focus->id : ""));
$xtpl->assign("EDIT_INLINE_PNG",  get_image($image_path.'edit_inline','align="absmiddle" alt="'.$app_strings['LNK_EDIT'].'" border="0"'));
$xtpl->assign("DELETE_INLINE_PNG",  get_image($image_path.'delete_inline','align="absmiddle" alt="'.$app_strings['LNK_DELETE'].'" border="0"'));

$seed = new Access();
$where = " users_access.access_to_user_id = '".$current_user->id."'";

$query = $seed->create_list_query('',$where,FALSE);
//echo "Query :".$query;

include('modules/Users/field_arrays.php');
		
$list = $seed->build_related_list2($query, $seed,$fields_array['Access']['list_fields']);

$xtpl->parse("main.header");

		$remove_confirmation_text = $app_strings['NTC_REMOVE_CONFIRMATION'];
		$icon_remove_text = $app_strings['LNK_REMOVE'];
		$icon_remove_html = get_image($image_path . 'delete_inline',
			'align="absmiddle" alt="' . $icon_remove_text . '" border="0"');

foreach($list as $access){

$remove_url = "index.php?module=Users&action=DeleteAccess&record=$access->id";
$remove_link = '<a href="' . $remove_url . '"'
			. ' class="listViewTdToolsS1"'
			. " onclick=\"return confirm('$remove_confirmation_text');\""
			. ">$icon_remove_html&nbsp;$icon_remove_text</a>";

$access_fields = array(
		'ID' => $access->id,
		'FULL_NAME' => $access->full_name,
		'ACCESS_TO_MODULE' => $access->access_to_module,
		'REMOVE_LINK' => $remove_link,
	);
//	echo implode(",",$access_fields);
	$xtpl->assign("ACCESS", $access_fields);
	$xtpl->parse("main.row");
}

$xtpl->parse("main");
$xtpl->out("main");

$user_list = get_user_array(FALSE);
$userlist = get_select_options_with_id($user_list,'');

$search_form=new XTemplate ('modules/Users/AssetEditView.html');
$search_form->assign("MOD", $current_module_strings);
$search_form->assign("APP", $app_strings);
$search_form->assign("THEME", $theme);
$search_form->assign("USER_OPTIONS",$userlist);
$search_form->assign("MODULE_OPTIONS",get_select_options_with_id($app_list_strings['access_modules'],''));
$search_form->parse("main");
echo "\n<p>\n";

echo get_form_header($current_module_strings['LBL_ADD_ACCESS'], '','', false);
$search_form->out("main");

?>
