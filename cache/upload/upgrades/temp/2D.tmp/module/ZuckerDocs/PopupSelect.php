<?php

global $theme;
require_once('include/logging.php');
require_once('XTemplate/xtpl.php');
require_once('include/utils.php');
require_once('include/ListView/ListView.php');
require_once('modules/ZuckerDocs/ZuckerDocument.php');

global $app_strings;
global $app_list_strings;
global $mod_strings;
global $current_language;

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

if(!isset($_REQUEST['form'])) sugar_die("Missing 'form' parameter");

$rootFolder = KT_SugarProvider::getRootFolder();

if(!empty($_REQUEST['record'])) {
	$folder = KT_SugarProvider::getFolderDetails($_REQUEST['record']);
} else if (!empty($_REQUEST['name'])) {
	$folder = KT_SugarProvider::getSubFolder($rootFolder->id, $_REQUEST['name']);
} else {
	$folder = $rootFolder;
}

$form =new XTemplate ('modules/ZuckerDocs/PopupSelect.html');
$form->assign("MOD", $mod_strings);
$form->assign("APP", $app_strings);

$the_javascript  = "<script type='text/javascript' language='JavaScript'>\n";
$the_javascript .= "function set_return(doc_id, doc_name) {\n";
$the_javascript .= "	window.opener.document.".$_REQUEST['form'].".doc_name.value = doc_name;\n";
$the_javascript .= "	window.opener.document.".$_REQUEST['form'].".doc_id.value = doc_id;\n";
$the_javascript .= "}\n";
$the_javascript .= "</script>\n";

$button  = "<form>\n";
$button .= "<input class='button' LANGUAGE=javascript onclick=\"window.opener.document.".$_REQUEST['form'].".doc_name.value = '';window.opener.document.".$_REQUEST['form'].".doc_id.value = ''; window.close()\" type='submit' name='button' value='  ".$app_strings['LBL_CLEAR_BUTTON_LABEL']."  '>\n";
$button .= "<input class='button' LANGUAGE=javascript onclick=\"window.close()\" type='submit' name='button' value='  ".$app_strings['LBL_CANCEL_BUTTON_LABEL']."  '>\n";
$button .= "</form>\n";

$form->assign("SET_RETURN_JS", $the_javascript);
$form->assign("THEME", $theme);
$form->assign("IMAGE_PATH", $image_path);
$form->assign("MODULE_NAME", $currentModule);
$form->assign("FORM", $_REQUEST['form']);
$form->assign("SET_RETURN_JS", $the_javascript);

insert_popup_header($theme);

echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_FOLDER'], $mod_strings['LBL_FOLDER'].": ".$folder->name, false);
echo "\n</p><p>";

$root_line = ZuckerDocument::get_root_line($folder->id);
$links = array();
foreach ($root_line as $obj) {
	$links[] = '<a href="index.php?module=ZuckerDocs&action=PopupSelect&record='.($obj->id).'&form='.$_REQUEST['form'].'">'.$obj->name.'</a>';
}
echo join("->", $links);
echo "\n</p>\n";

echo "\n<p>\n";
echo $button;
echo "\n</p>\n";

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

	$lv = new ListView();
	$lv->setXTemplate($form);
	$lv->setHeaderTitle($mod_strings['LBL_FOLDER_SUB']);
	$lv->setModStrings($mod_strings);
	$lv->processListViewTwo($list, "subfolders", "FOLDER");
}

$seed = new ZuckerDocument();
$docs = $seed->find_for_parent('Folders', $folder->id);
if (isDocumentsError($docs)) {
	echo KT_SugarProvider::formatError($docs);
}
else {

	$lv = new ListView();
	$lv->setXTemplate($form);
	$lv->setHeaderTitle($mod_strings['LBL_LIST_FORM_TITLE']);
	$lv->setModStrings($mod_strings);
	$lv->processListViewTwo($docs, "documents", "DOCUMENT");
}

echo get_form_footer();
insert_popup_footer();
?>