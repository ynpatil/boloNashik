<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
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
require_once('include/time.php');
require_once('include/ListView/ListView.php');
require_once('modules/ZuckerDocs/ZuckerDocument.php');

global $app_strings;
global $app_list_strings;
global $mod_strings;
$mod_list_strings = return_mod_list_strings_language($current_language, "ZuckerDocs");


$focus = new ZuckerDocument();

if(!empty($_REQUEST['record'])) {
    $focus->retrieve($_REQUEST['record']);
} else {
	header("Location: index.php?module=ZuckerDocs&action=index");
}
if ($focus->errorMessage) {
	echo $focus->errorMessage;
} else {
	$history = $focus->get_history();
	$linked = $focus->find_linked_documents();
	$linking = $focus->find_linking_documents();	
	
	global $theme;
	$theme_path="themes/".$theme."/";
	$image_path=$theme_path."images/";
	require_once($theme_path.'layout_utils.php');
	
	
	echo "\n<p>\n";
	echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_DOCUMENT'].": ".$focus->name, false);
	echo "\n</p>\n";
	
	require_once("modules/ZuckerDocs/DocumentMenu.php");
	
	$xtpl=new XTemplate ('modules/ZuckerDocs/DetailView.html');
	
	if (isset($_REQUEST['return_module'])) $xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
	if (isset($_REQUEST['return_action'])) $xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
	if (isset($_REQUEST['return_id'])) $xtpl->assign("RETURN_ID", $_REQUEST['return_id']);
	
	$xtpl->assign("MOD", $mod_strings);
	$xtpl->assign("APP", $app_strings);
	$xtpl->assign("THEME", $theme);
	$xtpl->assign("GRIDLINE", $gridline);
	$xtpl->assign("IMAGE_PATH", $image_path);
	$xtpl->assign("ID", $focus->id);
	$xtpl->assign("NAME", $focus->name);
	$xtpl->assign("FILENAME", $focus->filename);
	$xtpl->assign("DESCRIPTION", nl2br($focus->description));
	$xtpl->assign("MODIFIED", $focus->modified);
	$xtpl->assign("STATUS", $focus->status);
	$xtpl->assign("VERSION", $focus->version);
	$xtpl->assign("MIMETYPE", $focus->mimetype);
	$xtpl->assign("ICON_PATH", $focus->icon_path);
	$xtpl->assign("PARENT_MODULE", $focus->parent_type);
	$xtpl->assign("PARENT_ID", $focus->parent_id);
	$xtpl->assign("PARENT_NAME", $focus->parent_name);
	$xtpl->assign("PARENT_LINK", $focus->parent_link);
	$xtpl->assign("CATNAME", $mod_list_strings['doc_category'][$focus->cat_name]);
	$xtpl->assign("CHECKOUT_USERNAME", $focus->checkedout_username);

	if (!empty($dmsBase)) {
		$xtpl->assign("URL", $focus->url);
		$xtpl->parse("main.ktlink");
	}
	$xtpl->parse("main");
	$xtpl->out("main");

	echo get_form_header($mod_strings['LBL_MANAGEMENT'], "", false);
	echo $_REQUEST['DMS_MANAGE_ERROR'];
	
	if ($focus->is_checked_out) {
		if (strtolower($current_user->user_name) == strtolower($focus->checkedout_username)) {
			$xtpl->assign("CHECKIN_COMMENT", $_REQUEST["comment"]);
			$xtpl->parse("checkin");
			$xtpl->out("checkin");
		} else {
			echo "checked out by ".$focus->checkedout_username;
		}
	} else {
		$xtpl->assign("CHECKOUT_COMMENT", $_REQUEST["comment"]);
		$xtpl->parse("checkout");
		$xtpl->out("checkout");
	}
	
	echo $_REQUEST['DMS_MANAGE_SCRIPT'];
	$xtpl->parse("viewdocument.view");
	if ($editcontentEnabled) {
		$xtpl->parse("viewdocument.edit");
	}
	$xtpl->parse("viewdocument");
	$xtpl->out("viewdocument");


	
	$histView = new ListView();
	$histView->initNewXTemplate('modules/ZuckerDocs/DetailView.html', $mod_strings);
	$histView->setHeaderTitle($mod_strings['LBL_HISTORY']);
	$histView->processListViewTwo($history, "history", "HISTORY");
	
	$button  = "<form action='index.php' method='get' name='LinkDocumentForm' id='form'>\n";
	$button .= "<input type='hidden' name='module' value='ZuckerDocs'>\n";
	$button .= "<input type='hidden' name='action' value='NewDocumentLink'>\n";
	$button .= "<input type='hidden' name='record' value='".$_REQUEST['record']."'>\n";
	$button .= "<input type='hidden' name='return_module' value='ZuckerDocs'>\n";
	$button .= "<input type='hidden' name='return_action' value='DetailView'>\n";
	$button .= "<input type='hidden' name='return_id' value='".$_REQUEST['record']."'>\n";
	$button .= "<input type='hidden' name='doc_id'>\n";
	$button .= "<input type='text' name='doc_name' readonly>\n";
	$button .= "<input class='button' type='button' onclick='return window.open(\"index.php?module=ZuckerDocs&action=PopupSelect&form=LinkDocumentForm\",\"test\",\"width=600,height=400,resizable=1,scrollbars=1\");' value=' ... '>\n";
	$button .= "<input class='button' type='submit' onclick='return verify_link_data(this.form);' value='".$mod_strings['LBL_LINK_DOC']."'>\n";
	$button .= "</form>\n";
	
	$lv = new ListView();
	$lv->initNewXTemplate('modules/ZuckerDocs/SubPanelViewLinked.html', $mod_strings);
	$lv->xTemplateAssign("JAVASCRIPT", get_set_focus_js().get_validate_record_js());
	$lv->xTemplateAssign("RECORD", $_REQUEST['record']);
	$lv->setHeaderTitle($mod_strings['LBL_LINKED_DOCS']);
	$lv->setHeaderText($button);
	$lv->processListViewTwo($linked, "main", "DOCUMENT");

	$lv = new ListView();
	$lv->initNewXTemplate('modules/ZuckerDocs/SubPanelViewLinking.html', $mod_strings);
	$lv->setHeaderTitle($mod_strings['LBL_LINKING_DOCS']);
	$lv->setHeaderText("");
	$lv->processListViewTwo($linking, "main", "DOCUMENT");
}	
?>
<? include('modules/ZuckerDocs/SubPanelView.php'); ?>