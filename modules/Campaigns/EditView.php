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
 * $Id: EditView.php,v 1.28 2006/08/03 00:20:38 wayne Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Campaigns/Campaign.php');
require_once('modules/Campaigns/Forms.php');

global $app_strings;
global $timedate;
global $app_list_strings;
global $mod_strings;
global $current_user;
global $sugar_version, $sugar_config;

// Unimplemented until jscalendar language files are fixed
// global $current_language;
// global $default_language;
// global $cal_codes;
$focus = new Campaign();

if(isset($_REQUEST['record'])) {
    $focus->retrieve($_REQUEST['record']);
}
if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
}

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$json = getJSONobj();

$GLOBALS['log']->info("Campaign Edit View");
echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME'].": ".$focus->name, true);
echo "\n</p>\n";
$xtpl=new XTemplate ('modules/Campaigns/EditView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

/// Users Popup
$popup_request_data = array(
	'call_back_function' => 'set_return',
	'form_name' => 'EditView',
	'field_to_name_array' => array(
		'id' => 'assigned_user_id',
		'user_name' => 'assigned_user_name',
		),
	);
$xtpl->assign('encoded_users_popup_request_data', $json->encode($popup_request_data));


/// Product Popup
$popup_request_data = array(
	'call_back_function' => 'set_return',
	'form_name' => 'EditView',
	'field_to_name_array' => array(
		'id' => 'product_id',
		'name' => 'product_name',
		),
	);
$xtpl->assign('encoded_product_popup_request_data', $json->encode($popup_request_data));



// Unimplemented until jscalendar language files are fixed
// $xtpl->assign("CALENDAR_LANG", ((empty($cal_codes[$current_language])) ? $cal_codes[$default_language] : $cal_codes[$current_language]));
$xtpl->assign("CALENDAR_LANG", "en");
$xtpl->assign("USER_DATEFORMAT", '('. $timedate->get_user_date_format().')');
$xtpl->assign("CALENDAR_DATEFORMAT", $timedate->get_cal_date_format());

if (isset($_REQUEST['return_module'])) $xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
if (isset($_REQUEST['return_action'])) $xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
if (isset($_REQUEST['return_id'])) $xtpl->assign("RETURN_ID", $_REQUEST['return_id']);
// handle Create $module then Cancel
if (empty($_REQUEST['return_id'])) {
	$xtpl->assign("RETURN_ACTION", 'index');
}
$xtpl->assign("THEME", $theme);
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "index.php?".$GLOBALS['request_string']);
require_once('include/QuickSearchDefaults.php');
$qsd = new QuickSearchDefaults();
$sqs_objects = array('parent_name' => $qsd->getQSParent(), 
					'assigned_user_name' => $qsd->getQSUser(),



					);
$quicksearch_js = $qsd->getQSScripts();
$quicksearch_js .= '<script type="text/javascript" language="javascript">sqs_objects = ' . $json->encode($sqs_objects) . '</script>';

$xtpl->assign("JAVASCRIPT", get_set_focus_js().get_validate_record_js().$quicksearch_js);

$xtpl->assign("DATE_ENTERED", $focus->date_entered);
$xtpl->assign("DATE_MODIFIED", $focus->date_modified);
$xtpl->assign("CREATED_BY", $focus->created_by_name);
$xtpl->assign("MODIFIED_BY", $focus->modified_by_name);
$xtpl->assign("ID", $focus->id);
$xtpl->assign("START_DATE", $focus->start_date);
$xtpl->assign("END_DATE", $focus->end_date);
$xtpl->assign("BUDGET", $focus->budget);
$xtpl->assign("ACTUAL_COST", $focus->actual_cost);
$xtpl->assign("EXPECTED_REVENUE", $focus->expected_revenue);
$xtpl->assign("EXPECTED_COST", $focus->expected_cost);
$xtpl->assign("OBJECTIVE", $focus->objective);
$xtpl->assign("CONTENT", $focus->content);
$xtpl->assign("NAME", $focus->name);
$xtpl->assign("TRACKER_TEXT", $focus->tracker_text);

$xtpl->assign("PRODUCT_ID", $focus->product_id);
$xtpl->assign("PRODUCT_NAME", $focus->product_name);

if($focus->refer_url == '')
	$xtpl->assign("REFER_URL", 'http://');
else
	$xtpl->assign("REFER_URL", $focus->refer_url);
	
if (empty($focus->assigned_user_id) && empty($focus->id))  $focus->assigned_user_id = $current_user->id;
if (empty($focus->assigned_name) && empty($focus->id))  $focus->assigned_user_name = $current_user->user_name;
$xtpl->assign("ASSIGNED_USER_OPTIONS", get_select_options_with_id(get_user_array(TRUE, "Active", $focus->assigned_user_id), $focus->assigned_user_id));
$xtpl->assign("ASSIGNED_USER_NAME", $focus->assigned_user_name);
$xtpl->assign("ASSIGNED_USER_ID", $focus->assigned_user_id );

if((!isset($focus->status)) && (!isset($focus->id)))
	$xtpl->assign("STATUS_OPTIONS", get_select_options_with_id($app_list_strings['campaign_status_dom'], 'Planning'));
else
	$xtpl->assign("STATUS_OPTIONS", get_select_options_with_id($app_list_strings['campaign_status_dom'], $focus->status));

//$xtpl->assign("TYPE_OPTIONS", get_select_options_with_id($app_list_strings['campaign_type_dom'], $focus->campaign_type));

global $current_user;
require_once('modules/Currencies/ListCurrency.php');
$currency = new ListCurrency();
if(isset($focus->currency_id) && !empty($focus->currency_id)){
	$selectCurrency = $currency->getSelectOptions($focus->currency_id);
	$xtpl->assign("CURRENCY", $selectCurrency);
}
else if($current_user->getPreference('currency') && !isset($focus->id))
{
	$selectCurrency = $currency->getSelectOptions($current_user->getPreference('currency'));
	$xtpl->assign("CURRENCY", $selectCurrency);
}else{

	$selectCurrency = $currency->getSelectOptions();
	$xtpl->assign("CURRENCY", $selectCurrency);

}
global $current_user;
if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){	
	$record = '';
	if(!empty($_REQUEST['record'])){
		$record = 	$_REQUEST['record'];
	}
	$xtpl->assign("ADMIN_EDIT","<a href='index.php?action=index&module=DynamicLayout&from_action=".$_REQUEST['action'] ."&from_module=".$_REQUEST['module'] ."&record=".$record. "'>".get_image($image_path."EditLayout","border='0' alt='Edit Layout' align='bottom'")."</a>");	
}

//Add Custom Fields
require_once('modules/DynamicFields/templates/Files/EditView.php');
















$xtpl->parse("main.open_source");




echo $currency->getJavascript();

$xtpl->parse("main");
$xtpl->out("main");
require_once('include/javascript/javascript.php');
$javascript = new javascript();
$javascript->setFormName('EditView');
$javascript->setSugarBean($focus);
$javascript->addAllFields('');




$javascript->addToValidateBinaryDependency('assigned_user_name', 'alpha', $app_strings['ERR_SQS_NO_MATCH_FIELD'] . $app_strings['LBL_ASSIGNED_TO'], 'false', '', 'assigned_user_id');
echo $javascript->getScript();

require_once('modules/SavedSearch/SavedSearch.php');
$savedSearch = new SavedSearch();
$json = getJSONobj();
$savedSearchSelects = $json->encode(array($GLOBALS['app_strings']['LBL_SAVED_SEARCH_SHORTCUT'] . '<br>' . $savedSearch->getSelect('Campaigns')));
$str = "<script>
YAHOO.util.Event.addListener(window, 'load', SUGAR.util.fillShortcuts, $savedSearchSelects);
</script>";
echo $str;

?>
