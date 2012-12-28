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
	
	$xtpl=new XTemplate ('modules/ZuckerDocs/EditContentsView.html');
	$xtpl->assign("MOD", $mod_strings);
	$xtpl->assign("APP", $app_strings);
	
	if (isset($_REQUEST['return_module'])) $xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
	if (isset($_REQUEST['return_action'])) $xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
	if (isset($_REQUEST['return_id'])) $xtpl->assign("RETURN_ID", $_REQUEST['return_id']);
	$xtpl->assign("THEME", $theme);
	$xtpl->assign("IMAGE_PATH", $image_path);
	if (!empty($_REQUEST['comment'])) {
		$xtpl->assign("CHECKIN_COMMENT", $_REQUEST["comment"]);
	}
	$xtpl->assign("ID", $focus->id);

	if (!empty($_REQUEST["contents"])) {
		$contents = $_REQUEST["contents"];
	} else {
		$contents = KT_SugarProvider::getDocumentContents($focus->id);
	}
	$xtpl->assign("CONTENTS", $contents);

	if (strstr($focus->mimetype, "text/html")) {

		$javascript .= '<script type="text/javascript">_editor_url = "'.$htmlarea_base.'"; _editor_lang = "'.$htmlarea_lang_map[$current_language].'";</script>';
		$javascript .= '<script type="text/javascript" src="'.$htmlarea_base.'htmlarea.js"></script>';
		$javascript .= '<script type="text/javascript" defer="1">HTMLArea.replace("contents");</script>';
		$xtpl->assign("JAVASCRIPT", $javascript);
	}
	
	$xtpl->parse("main");
	$xtpl->out("main");
}
?>
