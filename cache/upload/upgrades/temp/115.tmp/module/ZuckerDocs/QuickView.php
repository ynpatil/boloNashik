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


function renderDocument($focus, $xtpl) {
	$xtpl->assign("URL", "index.php?module=ZuckerDocs&action=DocView&record=".$focus->id);
	$xtpl->assign("ICON_URL", $focus->icon_path);
	$xtpl->assign("NAME", $focus->name);

	if (strstr($focus->mimetype, "text/html")) {
		$html = KT_SugarProvider::getDocumentContents($focus->id);
		if (isDocumentsError($html)) {
			$xtpl->assign("CONTENTS", KT_SugarProvider::formatError($html));
			$xtpl->parse("text");
			$xtpl->out("text");
		} else {
			$regs = array();
			if (ereg ("<body([^>]*)>(.*)</body>", $html, $regs)) {
				$html = $regs[2];
			} else if (ereg ("<BODY([^>]*)>(.*)</BODY>", $html, $regs)) {
				$html = $regs[2];
			}
			$xtpl->assign("CONTENTS", $html);
			$xtpl->parse("html");
			$xtpl->out("html");
		}
	} else if (strstr($focus->mimetype, "text/")) {
		$contents = KT_SugarProvider::getDocumentContents($focus->id);
		if (isDocumentsError($contents)) {
			$xtpl->assign("CONTENTS", KT_SugarProvider::formatError($contents));
			$xtpl->parse("text");
			$xtpl->out("text");
		} else {
			$xtpl->assign("CONTENTS", $contents);
			$xtpl->parse("text");
			$xtpl->out("text");
		}
	} else if (strstr($focus->mimetype, "image/")) {
		$xtpl->assign("IMAGE_URL", "download.php?module=ZuckerDocs&action=ViewDocument&record=".$focus->id);
		$xtpl->assign("NAME", $focus->name);
		$xtpl->parse("image");
		$xtpl->out("image");
	} else {
		$xtpl->parse("link");
		$xtpl->out("link");
	}
}


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
	
	$xtpl=new XTemplate ('modules/ZuckerDocs/QuickView.html');
	$xtpl->assign("MOD", $mod_strings);
	$xtpl->assign("APP", $app_strings);
	
	if (isset($_REQUEST['return_module'])) $xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
	if (isset($_REQUEST['return_action'])) $xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
	if (isset($_REQUEST['return_id'])) $xtpl->assign("RETURN_ID", $_REQUEST['return_id']);
	$xtpl->assign("THEME", $theme);
	$xtpl->assign("ID", $focus->id);
	$xtpl->assign("NAME", $focus->name);	
	
	renderDocument(&$focus, &$xtpl);
	
	$links = $focus->find_linked_documents();
	if (is_array($links) && count($links) > 0) {
		echo get_form_header($mod_strings['LBL_LINKED_DOCS'], "", false);
		foreach ($links as $l) {
			renderDocument(&$l, &$xtpl);
		}
	}
}
?>
