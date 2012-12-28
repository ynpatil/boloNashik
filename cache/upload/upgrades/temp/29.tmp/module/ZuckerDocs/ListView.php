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
require_once('include/JSON.php');
require_once('modules/ZuckerDocs/ZuckerDocument.php');

global $app_strings;
global $app_list_strings;
global $mod_strings;
global $current_language;

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

require_once('include/ListView/ListView.php');

$search_form=new XTemplate ('modules/ZuckerDocs/SearchForm.html');
$search_form->assign("MOD", $mod_strings);
$search_form->assign("APP", $app_strings);
$search_form->assign("IMAGE_PATH", $image_path);

if (!empty($_REQUEST['parent_id']) && !empty($_REQUEST['parent_module'])) {
	$parentName = KT_SugarProvider::__getParentName($_REQUEST['parent_module'], $_REQUEST['parent_id']);

	$search_form->assign("PARENT_ID", $_REQUEST['parent_id']);
	$search_form->assign("PARENT_NAME", $parentName);
}
$types = array('');
$orig_types = parse_list_modules($app_list_strings['record_type_display']);
foreach (array_keys($orig_types) as $key) {
	$types[$key] = $orig_types[$key];
}
$search_form->assign("TYPE_OPTIONS", get_select_options_with_id($types, $_REQUEST['parent_module']));
$popup_request_data = array(
	'call_back_function' => 'set_return',
	'form_name' => 'EditView',
	'field_to_name_array' => array(
		'id' => 'parent_id',
		'name' => 'parent_name',
	),
);
$json = new JSON(JSON_LOOSE_TYPE);
$encoded_popup_request_data = $json->encode($popup_request_data);
$search_form->assign('encoded_popup_request_data', $encoded_popup_request_data);

$search_form->assign("FILENAME", $_REQUEST['filename']);
$search_form->assign("SEARCHTERM", $_REQUEST['searchterm']);

$cats = array('');
$orig_cats = $app_list_strings['doc_category'];
foreach (array_keys($orig_cats) as $key) {
	$cats[$key] = $orig_cats[$key];
}

$search_form->assign("CATNAME_OPTIONS", get_select_options_with_id($cats, $_REQUEST['cat_name']));

$search_form->assign("JAVASCRIPT", get_clear_form_js());

if ($_REQUEST['mode'] == 'fulltext') {
	$mode = 'fulltext';
	$header = get_module_title($mod_strings['LBL_FOLDER'], $mod_strings['LBL_FULLTEXTSEARCH_FORM_TITLE'], false);
} else {
	$mode = 'meta';
	$header = get_module_title($mod_strings['LBL_FOLDER'], $mod_strings['LBL_METASEARCH_FORM_TITLE'], false);
}
echo "\n<p>\n";
echo $header;
echo "\n</p>\n";
$search_form->parse("buttons");
$search_form->out("buttons");


$search_form->parse($mode);
$search_form->out($mode);

if (!empty($_REQUEST['querymode'])) {
	$lv = new ListView();
	$lv ->setQuery('', '', 'name', 'DOCUMENT');
	$orderBy = $lv->getSessionVariable('DOCUMENT', "ORDER_BY");
	$orderByAsc = ($lv->getSessionVariable('DOCUMENT', $orderBy."S") != 1);

	$seed = new ZuckerDocument();
	
	if ($_REQUEST['querymode'] == 'meta') {
		$list = $seed->find($_REQUEST['parent_module'], $_REQUEST['parent_id'], $_REQUEST['cat_name'], $_REQUEST['filename'], $orderBy, $orderByAsc);
		$section = "main";
	} else if ($_REQUEST['querymode'] == 'fulltext') {
		$list = $seed->findByText($_REQUEST['searchterm']);
		$section = "fulltext";
	}
	if (is_array($list)) {
		$lv ->initNewXTemplate('modules/ZuckerDocs/ListView.html', $mod_strings);
		$lv ->setHeaderTitle($mod_strings['LBL_MODULE_NAME']);
		$lv ->processListViewTwo($list, $section, "DOCUMENT");
	} else {
		echo $list;
	}
}
?>