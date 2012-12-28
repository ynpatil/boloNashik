<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * The detailed view for a State
 *
 * The contents of this file are subject to the SugarCRM Public License Version
 * 1.1.3 ("License"); You may not use this file except in compliance with the
 * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an "AS IS" basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied.  See the License
 * for the specific language governing rights and limitations under the
 * License.
 *
 * All copies of the Covered Code must include on each user interface screen:
 *    (i) the "Powered by SugarCRM" logo and
 *    (ii) the SugarCRM copyright notice
 * in the same form as they appear in the distribution.  See full license for
 * requirements.
 *
 * The Original Code is: SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) 2004-2006 SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 */
//om
// $Id: DetailView.php,v 1.44.4.1 2006/09/13 00:50:39 jenny Exp $

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('include/time.php');
require_once('modules/StateMaster/State.php');
require_once('include/DetailView/DetailView.php');

global $app_strings;
global $mod_strings;
global $theme;
global $current_user;

$GLOBALS['log']->info('State detail view');
$focus = new State();

// only load a record if a record id is given;
// a record id is not given when viewing in layout editor
$detailView = new DetailView();
$offset=0;
if (isset($_REQUEST['offset']) or isset($_REQUEST['record'])) {
	$result = $detailView->processSugarBean("STATE", $focus, $offset);
	if($result == null) {
	    sugar_die($app_strings['ERROR_NO_RECORD']);
	}
	$focus=$result;
} else {
	header("Location: index.php?module=Home&action=index");
}
echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'],
	$mod_strings['LBL_MODULE_NAME'] . ': ' . $focus->name, true);
echo "\n</p>\n";

$theme_path = 'themes/' . $theme . '/';
$image_path = $theme_path . 'images/';

require_once($theme_path.'layout_utils.php');

$xtpl = new XTemplate('modules/StateMaster/DetailView.html');

///
/// Assign the template variables
///

$xtpl->assign('MOD', $mod_strings);
$xtpl->assign('APP', $app_strings);
if(isset($_REQUEST['return_module']))
{
	$xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
}

if(isset($_REQUEST['return_action']))
{
	$xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
}

if(isset($_REQUEST['return_id']))
{
	$xtpl->assign("RETURN_ID", $_REQUEST['return_id']);
}

$xtpl->assign('PRINT_URL', "index.php?".$GLOBALS['request_string']);
$xtpl->assign('THEME', $theme);
$xtpl->assign('GRIDLINE', $gridline);
$xtpl->assign('IMAGE_PATH', $image_path);
$xtpl->assign('id', $focus->id);
$xtpl->assign('name', $focus->name);
$xtpl->assign('assigned_user_name', $focus->assigned_user_name);

$xtpl->assign("country_id_c", $focus->country_id_c);
$xtpl->assign("CREATED_BY", $focus->created_by_name);
$xtpl->assign("MODIFIED_BY", $focus->modified_by_name);
$xtpl->assign("DATE_MODIFIED", $focus->date_modified);
$xtpl->assign("DATE_ENTERED",$focus->date_entered);
$xtpl->assign("region_id_c", $focus->region_id_c);
$xtpl->assign("REGION_ID_C_NAME", $focus->region_id_c_name);
if(is_admin($current_user)
	&& $_REQUEST['module'] != 'DynamicLayout'
	&& !empty($_SESSION['editinplace']))
{
	$xtpl->assign('ADMIN_EDIT',
		'<a href="index.php?action=index&module=DynamicLayout&from_action='
		. $_REQUEST['action'] . '&from_module=' . $_REQUEST['module']
		. '&record=' . $_REQUEST['record'] . '">'
		. get_image($image_path . 'EditLayout',
			 'border="0" alt="Edit Layout" align="bottom"') . '</a>');
}

$detailView->processListNavigation($xtpl, "STATE", $offset, $focus->is_AuditEnabled());
// adding custom fields
require_once('modules/DynamicFields/templates/Files/DetailView.php');

$xtpl->parse('main.open_source');

$xtpl->parse('main');
$xtpl->out('main');

$sub_xtpl = $xtpl;

$old_contents = ob_get_contents();
ob_end_clean();
ob_start();
echo $old_contents;

echo '<script type="text/javascript" src="include/javascript/popup_parent_helper.js?s=' . $sugar_version . '&c=' . $sugar_config['js_custom_version'] . '"></script>';
/*
require_once('include/SubPanel/SubPanelTiles.php');
$subpanel = new SubPanelTiles($focus, 'STATE');
echo $subpanel->display();
*/

require_once('modules/SavedSearch/SavedSearch.php');
$savedSearch = new SavedSearch();
$json = getJSONobj();
$savedSearchSelects = $json->encode(array($GLOBALS['app_strings']['LBL_SAVED_SEARCH_SHORTCUT'] . '<br>' . $savedSearch->getSelect('StateMaster')));
$str = "<script>
YAHOO.util.Event.addListener(window, 'load', SUGAR.util.fillShortcuts, $savedSearchSelects);
</script>";
echo $str;

?>
