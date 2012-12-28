<?
/**
 * ZuckerDocs by go-mobile
 * Copyright (C) 2005 Florian Treml, go-mobile
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the 
 * GNU General Public License as published by the Free Software Foundation; either version 2 of the 
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even 
 * the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General 
 * Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program; if not, 
 * write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 */

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/ZuckerDocs/ZuckerDocument.php');

global $app_strings;
global $app_list_strings;
global $mod_strings;
global $current_language;

$focus = new ZuckerDocument();
$focus->retrieve($_REQUEST['record']);

if ($focus->errorMessage) {
	echo $focus->errorMessage;
} else {
	
	echo "\n<p>\n";
	echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_DOCUMENT'].": ".$focus->name, false); 
	echo "\n</p>\n";
	
	require_once("modules/ZuckerDocs/DocumentMenu.php");	
	
	global $theme;
	$theme_path="themes/".$theme."/";
	$image_path=$theme_path."images/";
	require_once($theme_path.'layout_utils.php');
	
	$xtpl=new XTemplate ('modules/ZuckerDocs/EditView.html');
	$xtpl->assign("MOD", $mod_strings);
	$xtpl->assign("APP", $app_strings);
	
	if (isset($_REQUEST['return_module'])) $xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
	if (isset($_REQUEST['return_action'])) $xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
	if (isset($_REQUEST['return_id'])) $xtpl->assign("RETURN_ID", $_REQUEST['return_id']);
	$xtpl->assign("THEME", $theme);
	$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "index.php?".$GLOBALS['request_string']);
	$xtpl->assign("JAVASCRIPT", get_set_focus_js().get_validate_record_js());
	$xtpl->assign("ID", $focus->id);
	$xtpl->assign("NAME", $focus->name);
	$xtpl->assign("DESCRIPTION", nl2br($focus->description));
	$xtpl->assign("MODIFIED", $focus->modified);
	$xtpl->assign("STATUS", $focus->status);
	$xtpl->assign("VERSION", $focus->version);
	$xtpl->assign("FILENAME", $focus->filename);
	$xtpl->assign("PARENT_NAME", $focus->parent_name);
	$xtpl->assign("PARENT_MODULE", $focus->parent_type);
	$xtpl->assign("PARENT_ID", $focus->parent_id);
	$xtpl->assign("PARENT_LINK", $focus->parent_link);
	$xtpl->assign("CATNAME_OPTIONS", get_select_options_with_id($app_list_strings['doc_category'], $focus->cat_name));

	if (!empty($dmsBase)) {
		$xtpl->assign("URL", $focus->url);
		$xtpl->parse("main.ktlink");
	}

	$xtpl->parse("main");
	$xtpl->out("main");
	require_once('include/javascript/javascript.php');
	$javascript = new javascript();
	$javascript->setFormName('EditView');
	$javascript->setSugarBean($focus);
	$javascript->addAllFields('');
	echo $javascript->getScript();
}
?>
