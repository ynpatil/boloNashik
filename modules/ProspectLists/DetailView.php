<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/*********************************************************************************
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
 ********************************************************************************/
/*********************************************************************************
 * $Id: DetailView.php,v 1.23 2006/07/31 21:02:19 jenny Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/ProspectLists/ProspectList.php');
require_once('modules/ProspectLists/Forms.php');
require_once('include/DetailView/DetailView.php');


global $mod_strings;
global $app_strings;
global $app_list_strings;

$focus = new ProspectList();

$detailView = new DetailView();
$offset=0;
if (isset($_REQUEST['offset']) or isset($_REQUEST['record'])) {
	$result = $detailView->processSugarBean("PROSPECT_LIST", $focus, $offset);
	if($result == null) {
	    sugar_die($app_strings['ERROR_NO_RECORD']);
	}
	$focus=$result;
} else {
	header("Location: index.php?module=Accounts&action=index");
}

echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_ID'], $mod_strings['LBL_MODULE_NAME'].": ".$focus->name, true);
echo "\n</p>\n";
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$GLOBALS['log']->info("Prospect Lists detail view");

$xtpl=new XTemplate ('modules/ProspectLists/DetailView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
$populate_lead_button="<input title=".$app_strings['LBL_POPULATE_LEAD_BUTTON_TITLE']." accessKey=".$app_strings['LBL_POPULATE_LEAD_BUTTON_KEY']." class=\"button\" onclick=\"this.form.return_module.value='ProspectLists'; this.form.return_action.value='DetailView';  this.form.return_id.value='".$focus->id."'; this.form.action.value='Populate'; \" type=\"submit\" name=\"Populate\" value='".$app_strings['LBL_POPULATE_LEAD_BUTTON_LABEL']."'>";
if($focus->populate_lead_status==0){
    $xtpl->assign("POPULATE_LEAD_BUTTON",$populate_lead_button);
}
$xtpl->assign("THEME", $theme);
$xtpl->assign("GRIDLINE", $gridline);
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "index.php?".$GLOBALS['request_string']);
$xtpl->assign("ID", $focus->id);
$xtpl->assign("ASSIGNED_TO", $focus->assigned_user_name);
$xtpl->assign("NAME", $focus->name);
$xtpl->assign("ENTRY_COUNT", $focus->get_entry_count());
$xtpl->assign("SKIP_COUNT", $focus->get_lead_dnd_count());
$xtpl->assign("DESCRIPTION", nl2br(url2html($focus->description)));
$xtpl->assign("DATE_MODIFIED", $focus->date_modified);
$xtpl->assign("DATE_ENTERED", $focus->date_entered);
if($focus->populate_lead_status==0){
    $xtpl->assign("POPULATE_LEAD_STATUS","Not Started");
}elseif ($focus->populate_lead_status==1) {
    $xtpl->assign("POPULATE_LEAD_STATUS","Initiated");
}elseif ($focus->populate_lead_status==2) {
    $xtpl->assign("POPULATE_LEAD_STATUS","Progress");
}elseif ($focus->populate_lead_status==3) {
    $xtpl->assign("POPULATE_LEAD_STATUS","Completed");
}elseif($focus->populate_lead_status==4) {
    $xtpl->assign("POPULATE_LEAD_STATUS","Lead data is not found");
}

$xtpl->assign("PARENT_NAME", $focus->parent_name);
if (isset($focus->parent_type)) $xtpl->assign("PARENT_MODULE", $focus->parent_type);
$xtpl->assign("PARENT_TYPE", $app_list_strings['prospect_list_type_dom'][$focus->parent_type]);
$xtpl->assign("PARENT_ID", $focus->parent_id);
/*
$xtpl->assign("DOMAIN_NAME", $focus->domain_name);
if (empty($focus->list_type) or $focus->list_type!='exempt_domain') {
	$xtpl->assign("DOMAIN_HIDDEN", "display:none");
}
if ($focus->list_type == 'level') {
    $list_type_value=$app_list_strings['level_dom'][$focus->list_type_value];
} else if ($focus->list_type == 'experience') {
    $list_type_value=$focus->list_type_value;
} else if ($focus->list_type == 'city') {
    $city_arr=get_city_array();
    $list_type_value=$city_arr[$focus->list_type_value];
}
$xtpl->assign("LIST_TYPE_VALUE", $list_type_value);
 * 
 */
$xtpl->assign("START_DATE", $focus->start_date);
$xtpl->assign("END_DATE", $focus->end_date);

$xtpl->assign("CREATED_BY", $focus->created_by_name);
$xtpl->assign("MODIFIED_BY", $focus->modified_by_name);
if (!empty($focus->list_type) && isset($app_list_strings['prospect_list_type_dom'][$focus->list_type])) {
	$xtpl->assign("LIST_TYPE_NAME", $app_list_strings['prospect_list_type_dom'][$focus->list_type]);
}

global $current_user;
if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){

	$xtpl->assign("ADMIN_EDIT","<a href='index.php?action=index&module=DynamicLayout&from_action=".$_REQUEST['action'] ."&from_module=".$_REQUEST['module'] ."&record=".$_REQUEST['record']. "'>".get_image($image_path."EditLayout","border='0' alt='Edit Layout' align='bottom'")."</a>");
}

$detailView->processListNavigation($xtpl, "PROSPECT_LIST", $offset);
// adding custom fields:
require_once('modules/DynamicFields/templates/Files/DetailView.php');






$xtpl->parse("main.open_source");





$xtpl->parse("main");
$xtpl->out("main");

echo "</p>\n";

require_once('include/SubPanel/SubPanelTiles.php');
$subpanel = new SubPanelTiles($focus, 'ProspectLists');
echo $subpanel->display();
?>
