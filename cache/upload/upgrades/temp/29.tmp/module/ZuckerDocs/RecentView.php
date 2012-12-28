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
require_once('include/ListView/ListView.php');

global $app_strings;
global $app_list_strings;
global $mod_strings;
global $current_language;

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');


echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_FOLDER'], $mod_strings['LBL_METASEARCH_FORM_TITLE'], false);
echo "\n</p>\n";


$seed = new ZuckerDocument();
$list = $seed->getRecentlyChangedDocuments();

if (is_array($list)) {
	$lv = new ListView();
	$lv ->initNewXTemplate('modules/ZuckerDocs/ListView.html', $mod_strings);
	$lv ->setHeaderTitle($mod_strings['LBL_MENU_RECENT']);
	$lv ->processListViewTwo($list, "main", "DOCUMENT");
} else {
	echo $list;
}
?>