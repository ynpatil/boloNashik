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

require_once('include/ListView/ListView.php');
require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('include/ListView/ListView.php');
require_once('modules/ZuckerDocs/ZuckerDocument.php');

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once ($theme_path."layout_utils.php");

global $mod_strings;
global $app_list_strings;
global $app_strings;
global $current_user, $focus;
global $current_language;
$current_module_strings = return_module_language($current_language, 'ZuckerDocs');

if (!isset($parent_type)) {
	$parent_type = $_REQUEST['module'];
}
if (!isset($parent_id)) {
	$parent_id = $_REQUEST['record'];
}
if (!isset($return_module)) {
	$return_module = $_REQUEST['module'];
}
if (!isset($return_action)) {
	$return_action = $_REQUEST['action'];
}
if (!isset($return_id)) {
	$return_id = $_REQUEST['record'];
}
if (!isset($header_title)) {
	$header_title = $current_module_strings['LBL_LIST_FORM_TITLE'];
}

$button = "";
if (!$skip_new_button || !$skip_list_button) {
	$button  = "<form action='index.php' method='POST' name='form' id='form'  enctype='multipart/form-data'>\n";
	$button .= "<input type='hidden' name='module' value='ZuckerDocs'>\n";
	$button .= "<input type='hidden' name='action'>\n";
	$button .= "<input type='hidden' name='querymode'>\n";
	$button .= "<input type='hidden' name='parent_module' value='".$parent_type."'>\n";
	$button .= "<input type='hidden' name='parent_id' value='".$parent_id."'>\n";
	$button .= "<input type='hidden' name='return_module' value='".$return_module."'>\n";
	$button .= "<input type='hidden' name='return_action' value='".$return_action."'>\n";
	$button .= "<input type='hidden' name='return_id' value='".$return_id."'>\n";
	if (!$skip_new_button) {
		$button .= "<input name='uploadfile' type='file'/><input class='button' type='submit' name='New' onclick=\"this.form.action.value='DocSave'\" value=' ".$current_module_strings['LBL_DOCUMENT_NEW']."  '>\n";
		$button .= "<input class='button' type='submit' name='New' onclick=\"this.form.action.value='NewView'\" value=' ".$current_module_strings['LBL_DOCUMENT_CREATE']."  '>\n";
	}
	if (!$skip_list_button) {
		$button .= "<input class='button' type='submit' name='List' onclick=\"this.form.action.value='ListView';this.form.querymode.value='meta'\" value='  ".$current_module_strings['LBL_LIST_DOCS']."  '>\n";
	}
	$button .= "</form>\n";
}


$seed = new ZuckerDocument();
if ($parent_type == 'Folders') {
	$list = $seed->find_for_parent('Folders', $parent_id);
} else {
	$list = $seed->find_for_parent($parent_type, $parent_id);
}
if (isDocumentsError($list)) {
	echo KT_SugarProvider::formatError($list);
} else {
	$lv = new ListView();
	$lv->initNewXTemplate( 'modules/ZuckerDocs/SubPanelView.html', $current_module_strings);
	$lv->xTemplateAssign("RETURN_MODULE", $return_module);
	$lv->xTemplateAssign("RETURN_ACTION", $return_action);
	$lv->xTemplateAssign("RETURN_ID", $return_id);
	$lv->setHeaderTitle($header_title);
	$lv->setHeaderText($button);
	$lv->processListViewTwo($list, "main", "DOCUMENT");
}
	
if (isset($focus) && isset($focus->db)) {
	$focus->db->resetSettings();	
}

?>
