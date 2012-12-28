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

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$rootFolder = KT_SugarProvider::getRootFolder();

if(!empty($_REQUEST['record'])) {
	$folder = KT_SugarProvider::getFolderDetails($_REQUEST['record']);
} else if (!empty($_REQUEST['name'])) {
	$folder = KT_SugarProvider::getSubFolder($rootFolder->id, $_REQUEST['name']);
} else {
	$folder = $rootFolder;
}

echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_FOLDER'], $mod_strings['LBL_FOLDER'].": ".$folder->name, false);
echo "\n</p><p>";
echo ZuckerDocument::get_root_line_links($folder->id);
echo "\n</p>\n";

$xtpl=new XTemplate ('modules/ZuckerDocs/FoldersView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("THEME", $theme);
$xtpl->assign("GRIDLINE", $gridline);
$xtpl->assign("IMAGE_PATH", $image_path);

$xtpl->assign("ID", $folder->id);
$xtpl->assign("PARENT_ID", $folder->parent_id);
$xtpl->assign("NAME", $folder->name);
$xtpl->assign("DESCRIPTION", nl2br($folder->description));
$xtpl->assign("FULL_PATH", $folder->full_path);

if (!empty($dmsBase)) {
	$xtpl->assign("URL", $dmsBase."/presentation/lookAndFeel/knowledgeTree/documentmanagement/browseBL.php?fBrowseType=folder&fFolderID=".$folder->id);
	$xtpl->parse("main.ktlink");
}
if ($folder->id != $rootFolder->id) {
	$xtpl->parse("main.deletelink");
}
$xtpl->parse("main");
$xtpl->out("main");

echo "<p/>\n";

if(!empty($_REQUEST['createfoldername'])) {
	$newFolder = KT_SugarProvider::addFolder($folder->id, $_REQUEST['createfoldername']);
	if (isDocumentsError($newFolder)) {
		echo KT_SugarProvider::formatError($newFolder);
	}
}

$folders = KT_SugarProvider::getSubFolders($folder->id, TRUE);
if (isDocumentsError($folders)) {
	echo KT_SugarProvider::formatError($folders);
}
else {
	
	$list = array();
	foreach ($folders as $f) {
		$item = new FolderItem();
		$item->fromFolder($f);
		$list[] = $item;
	}

	$button  = "<form action='index.php' method='get' name='form' id='form'>\n";
	$button .= "<input type='hidden' name='module' value='ZuckerDocs'>\n";
	$button .= "<input type='hidden' name='action' value='FoldersView'>\n";
	$button .= "<input type='hidden' name='record' value='".$folder->id."'>\n";
	$button .= "<input name='createfoldername' size='20' maxlength='50' type='text'/>\n";
	$button .= "<input class='button' type='submit' name='New' value=' ".$mod_strings['LBL_FOLDER_NEW']."  '>\n";
	$button .= "</form>\n";
	
	require_once('include/ListView/ListView.php');
	$lv = new ListView();
	$lv->initNewXTemplate('modules/ZuckerDocs/FoldersView.html', $mod_strings);
	$lv->setHeaderTitle($mod_strings['LBL_FOLDER_SUB']);
	$lv->setHeaderText($button);
	$lv->processListViewTwo($list, "subfolders", "FOLDER");
}

$parent_type = 'Folders';
$parent_id = $folder->id;
$return_module = 'ZuckerDocs';
$return_action = 'FoldersView';
$return_id = $folder->id;
$skip_new_button = FALSE;
$skip_list_button = TRUE;
require_once('modules/ZuckerDocs/SubPanelView.php');

?>
