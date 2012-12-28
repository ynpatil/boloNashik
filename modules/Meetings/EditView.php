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

 * Description: TODO:  To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): Ken Brill (TeamsOS)
 ********************************************************************************/

//UPDATED FOR TeamsOS 3.0c by Ken Brill Jan 7th, 2007

require_once ('XTemplate/xtpl.php');
require_once ('data/Tracker.php');
require_once('include/json_config.php');
$json_config = new json_config();

require_once ('modules/Meetings/Meeting.php');
//require_once('modules/Meetings/Forms.php');

global $timedate;
global $app_strings;
global $app_list_strings;
global $mod_strings;
global $current_user;
global $sugar_version, $sugar_config;

$json = getJSONobj();
// Unimplemented until jscalendar language files are fixed
// global $current_language;
// global $default_language;
// global $cal_codes;

$focus = & new Meeting();

if (isset ($_REQUEST['record'])) {
	$focus->retrieve($_REQUEST['record']);
}

if (isset($_REQUEST['name']))
	$focus->name = $_REQUEST['name'];

if (isset($_REQUEST['description']))
	$focus->description = $_REQUEST['description'];

if (isset ($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$json_id = $focus->id;
	$focus->id = "";
    $focus->status = $app_list_strings['meeting_status_default'];
}

if (isset($_REQUEST['duration_hours']))
	$focus->duration_hours = $_REQUEST['duration_hours'];

if (isset($_REQUEST['duration_minutes']))
	$focus->duration_minutes = $_REQUEST['duration_minutes'];

if (isset($_REQUEST['date_start']))
	$focus->date_start = $_REQUEST['date_start'];

if (isset($_REQUEST['time_hour_start']))
	$focus->time_hour_start = $_REQUEST['time_hour_start'];

if (isset($_REQUEST['time_minute_start']))
	$focus->time_minute_start = $_REQUEST['time_minute_start'];

if (isset($_REQUEST['time_hour_exit']))
	$focus->time_hour_exit = $_REQUEST['time_hour_exit'];

if (isset($_REQUEST['time_minute_exit']))
	$focus->time_minute_exit = $_REQUEST['time_minute_exit'];

if (isset($_REQUEST['time_hour_in']))
	$focus->time_hour_in = $_REQUEST['time_hour_in'];

if (isset($_REQUEST['time_minute_in']))
	$focus->time_minute_in = $_REQUEST['time_minute_in'];

//setting default date and time
if (is_null($focus->date_start))
	$focus->date_start = $timedate->to_display_date(gmdate('Y-m-d H:i:s'));
if (is_null($focus->time_start))
	$focus->time_start = $timedate->to_display_time(gmdate('Y-m-d H:i:s'), true);
if (is_null($focus->time_exit))
	$focus->time_exit = $timedate->to_display_time(gmdate('Y-m-d H:i:s'), true);
if (is_null($focus->time_in))
	$focus->time_in = $timedate->to_display_time(gmdate('Y-m-d H:i:s'), true);
if (!isset ($focus->duration_hours))
	$focus->duration_hours = "1";

//needed when creating a new meeting with default values passed in
if (isset ($_REQUEST['contact_name']) && is_null($focus->contact_name)) {
	$focus->contact_name = $_REQUEST['contact_name'];
}
if (isset ($_REQUEST['contact_id']) && is_null($focus->contact_id)) {
	$focus->contact_id = $_REQUEST['contact_id'];
}
if (isset ($_REQUEST['parent_name'])) {
	$focus->parent_name = $_REQUEST['parent_name'];
}
if (isset ($_REQUEST['location'])) {
	$focus->location = $_REQUEST['location'];
}
if (isset ($_REQUEST['parent_id'])) {
	$focus->parent_id = $_REQUEST['parent_id'];
}
if (isset ($_REQUEST['parent_type'])) {
	$focus->parent_type = $_REQUEST['parent_type'];
}
elseif (is_null($focus->parent_type)) {
	$focus->parent_type = $app_list_strings['record_type_default_key'];
}

// cn: bug 9911 - meeting status not preserved
if(isset($_REQUEST['status']) && !empty($_REQUEST['status'])) {
	$focus->status = $_REQUEST['status'];
} elseif(empty ($focus->status)) {
	$focus->status = $app_list_strings['meeting_status_default'];
}

echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME'].": ".$focus->name, true);
echo "\n</p>\n";
global $theme;
$theme_path = "themes/".$theme."/";
$image_path = $theme_path."images/";
require_once ($theme_path.'layout_utils.php');

$GLOBALS['log']->info("Meeting detail view");

$xtpl = new XTemplate('modules/Meetings/EditView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

// Unimplemented until jscalendar language files are fixed
// $xtpl->assign("CALENDAR_LANG", ((empty($cal_codes[$current_language])) ? $cal_codes[$default_language] : $cal_codes[$current_language]));
$xtpl->assign("CALENDAR_LANG", "en");
$xtpl->assign("CALENDAR_DATEFORMAT", parse_calendardate($app_strings['NTC_DATE_FORMAT']));

if (empty ($focus->id))
	$xtpl->assign("USER_ID", $current_user->id);
if (empty ($focus->id) && isset ($_REQUEST['contact_id']))
	$xtpl->assign("CONTACT_ID", $_REQUEST['contact_id']);

if (isset ($_REQUEST['return_module']))
	$xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
if (isset ($_REQUEST['return_action']))
	$xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
if (isset ($_REQUEST['return_id'])) {
	$xtpl->assign("RETURN_ID", $_REQUEST['return_id']);
} else {
	// if no $_REQUEST['return_id'] is set, and we press "Cancel" we get an error.
	// if RETURN_ACTION is "index", we just go back to module's default page.
	$xtpl->assign("RETURN_ACTION", "index");
}

if (isset($_REQUEST['isassoc_activity'])) $xtpl->assign("isassoc_activity", $_REQUEST['isassoc_activity']);
if (isset($_REQUEST['followup_for_id'])) $xtpl->assign("followup_for_id", $_REQUEST['followup_for_id']);

if(isset($_REQUEST['source_info'])) $xtpl->assign("SOURCE_INFO", $_REQUEST['source_info']);
if(isset($_REQUEST['source_info_id'])){
 $focus->fill_in_additional_parent_fields();
 $xtpl->assign("SOURCE_INFO_ID", $_REQUEST['source_info_id']);
}

$xtpl->assign("THEME", $theme);
$xtpl->assign("IMAGE_PATH", $image_path);
$xtpl->assign("PRINT_URL", "index.php?".$GLOBALS['request_string']);

require_once('include/QuickSearchDefaults.php');
$qsd = new QuickSearchDefaults();
$sqs_objects = array('parent_name' => $qsd->getQSParent(),
					'assigned_user_name' => $qsd->getQSUser(),
					'brand_name' => $qsd->getQSActivityBrand(),
					);
$quicksearch_js = $qsd->getQSScriptsJSONAlreadyDefined();
$quicksearch_js .= '<script type="text/javascript" language="javascript">sqs_objects = ' . $json->encode($sqs_objects) . '</script>';

$xtpl->assign("JAVASCRIPT", get_set_focus_js().get_validate_record_js(). $quicksearch_js);
$xtpl->assign("ID", $focus->id);
if (isset ($json_id) && !empty ($json_id)) {
	$xtpl->assign("JSON_ID", $json_id);
	$xtpl->assign('JSON_CONFIG_JAVASCRIPT', $json_config->get_static_json_server(false, true, 'Meetings', $json_id));
} else {
	$xtpl->assign("JSON_ID", $focus->id);
	$xtpl->assign('JSON_CONFIG_JAVASCRIPT', $json_config->get_static_json_server(false, true, 'Meetings', $focus->id));
}

$xtpl->assign("MODULE_NAME", "Meetings");
$xtpl->assign("PARENT_NAME", $focus->parent_name);
$xtpl->assign("PARENT_ID", $focus->parent_id);
$xtpl->assign("BRAND_NAME", $focus->brand_name);
$xtpl->assign("BRAND_ID", $focus->brand_id);

if (isset ($focus->name))
	$xtpl->assign("NAME", $focus->name);
else
	$xtpl->assign("NAME", "");

$xtpl->assign("LOCATION", $focus->location);
$xtpl->assign("DATE_START", $focus->date_start);
//$xtpl->assign("TIME_START", substr($focus->time_start,0,5));

if (isset($focus->time_hour_start)) {
	$time_start_hour = $focus->time_hour_start;
}
else {
	$time_start_hour = intval(substr($focus->time_start, 0, 2));
}

if (isset($focus->time_hour_exit)) {
	$time_exit_hour = $focus->time_hour_exit;
}
else {
	$time_exit_hour = intval(substr($focus->time_exit, 0, 2));
}

if (isset($focus->time_hour_in)) {
	$time_in_hour = $focus->time_hour_in;
}
else {
	$time_in_hour = intval(substr($focus->time_in, 0, 2));
}

if (isset($focus->time_minute_start)) {
	$time_start_minutes = $focus->time_minute_start;
}
else {
	$time_start_minutes = substr($focus->time_start, 3, 5);
	if ($time_start_minutes > 0 && $time_start_minutes < 15) {
		$time_start_minutes = "15";
	} else
		if ($time_start_minutes > 15 && $time_start_minutes < 30) {
			$time_start_minutes = "30";
		} else
			if ($time_start_minutes > 30 && $time_start_minutes < 45) {
				$time_start_minutes = "45";
			} else
				if ($time_start_minutes > 45) {
					$time_start_hour += 1;
					$time_start_minutes = "00";
				}
}

if (isset($focus->time_minute_exit)) {
	$time_exit_minutes = $focus->time_minute_exit;
}
else {
	$time_exit_minutes = substr($focus->time_exit, 3, 5);
	if ($time_exit_minutes > 0 && $time_exit_minutes < 15) {
		$time_exit_minutes = "15";
	} else
		if ($time_exit_minutes > 15 && $time_exit_minutes < 30) {
			$time_exit_minutes = "30";
		} else
			if ($time_exit_minutes > 30 && $time_exit_minutes < 45) {
				$time_exit_minutes = "45";
			} else
				if ($time_exit_minutes > 45) {
					$time_exit_hour += 1;
					$time_exit_minutes = "00";
				}
}

if (isset($focus->time_minute_in)) {
	$time_in_minutes = $focus->time_minute_in;
}
else {
	$time_in_minutes = substr($focus->time_in, 3, 5);
	if ($time_in_minutes > 0 && $time_in_minutes < 15) {
		$time_in_minutes = "15";
	} else
		if ($time_in_minutes > 15 && $time_in_minutes < 30) {
			$time_in_minutes = "30";
		} else
			if ($time_in_minutes > 30 && $time_in_minutes < 45) {
				$time_in_minutes = "45";
			} else
				if ($time_in_minutes > 45) {
					$time_in_hour += 1;
					$time_in_minutes = "00";
				}
}

$xtpl->assign("TIME_START", substr($focus->time_start, 0, 5));
$xtpl->assign("TIME_EXIT", substr($focus->time_exit, 0, 5));
$xtpl->assign("TIME_IN", substr($focus->time_in, 0, 5));
$time_meridiem = $timedate->AMPMMenu('', $focus->time_start, 'onchange="SugarWidgetScheduler.update_time();"');
$xtpl->assign("TIME_MERIDIEM", $time_meridiem);

$hours_arr = array ();
$num_of_hours = 13;
$start_at = 1;

if (empty ($time_meridiem)) {
	$num_of_hours = 24;
	$start_at = 0;
}

for ($i = $start_at; $i < $num_of_hours; $i ++) {
	$i = $i."";
	if (strlen($i) == 1) {
		$i = "0".$i;
	}
	$hours_arr[$i] = $i;
}
$parent_types = $app_list_strings['record_type_display'];
$disabled_parent_types = ACLController::disabledModuleList($parent_types,false, 'list');
foreach($disabled_parent_types as $disabled_parent_type){
	if($disabled_parent_type != $focus->parent_type){
		unset($parent_types[$disabled_parent_type]);
	}
}
/* begin Lampada change */
//Add Custom Fields
require_once('modules/DynamicFields/templates/Files/EditView.php');
/* end Lampada change */

$xtpl->assign("TIME_START_HOUR_OPTIONS", get_select_options_with_id($hours_arr, $time_start_hour));
$xtpl->assign("TIME_START_MINUTE_OPTIONS", get_select_options_with_id($focus->minutes_values, $time_start_minutes));

$xtpl->assign("TIME_EXIT_HOUR_OPTIONS", get_select_options_with_id($hours_arr, $time_exit_hour));
$xtpl->assign("TIME_EXIT_MINUTE_OPTIONS", get_select_options_with_id($focus->minutes_values, $time_exit_minutes));

$GLOBALS['log']->info("time in minutes ".$time_in_hour." minutes ".$time_in_minutes);
$xtpl->assign("TIME_IN_HOUR_OPTIONS", get_select_options_with_id($hours_arr, $time_in_hour));
$xtpl->assign("TIME_IN_MINUTE_OPTIONS", get_select_options_with_id($focus->minutes_values, $time_in_minutes));

$xtpl->assign("TIME_MERIDIEM", $timedate->AMPMMenu('', $focus->time_start, 'onchange="SugarWidgetScheduler.update_time();"'));
$time_format = $timedate->get_user_time_format();
if (preg_match('/\d([^\d])\d/', $time_format, $match)) {
	$xtpl->assign("TIME_SEPARATOR", $match[1]);
} else {
	$xtpl->assign("TIME_SEPARATOR", ":");
}
$xtpl->assign("TIME_FORMAT", '('.$time_format.')');
$xtpl->assign("USER_DATEFORMAT", '('.$timedate->get_user_date_format().')');
$xtpl->assign("CALENDAR_DATEFORMAT", $timedate->get_cal_date_format());
$xtpl->assign("DESCRIPTION", $focus->description);
$xtpl->assign("OUTCOME", $focus->outcome);

if (empty ($focus->assigned_user_id) && empty ($focus->id))
	$focus->assigned_user_id = $current_user->id;
if (empty($focus->assigned_name) && empty($focus->id))
	$focus->assigned_user_name = $current_user->user_name;
$xtpl->assign("ASSIGNED_USER_OPTIONS", get_select_options_with_id(get_user_array(TRUE, "Active", $focus->assigned_user_id), $focus->assigned_user_id));
$xtpl->assign("ASSIGNED_USER_NAME", $focus->assigned_user_name);
$xtpl->assign("ASSIGNED_USER_ID", $focus->assigned_user_id );
$xtpl->assign("DURATION_HOURS", $focus->duration_hours);
$xtpl->assign("TYPE_OPTIONS", get_select_options_with_id($parent_types, $focus->parent_type));
$xtpl->assign("STATUS_OPTIONS", get_select_options_with_id($app_list_strings['meeting_status_dom'], $focus->status));
$xtpl->assign("DURATION_MINUTES_OPTIONS", get_select_options_with_id($focus->minutes_values, $focus->duration_minutes));

///////////////////////////////////////////////////////////////////////////////
// jsclass_scheduler stuff
$titleHeader = str_replace("</p><p>", "", get_form_header($mod_strings['LBL_SCHEDULING_FORM_TITLE'], "", false));
$xtpl->assign("LBL_SCHEDULING_FORM_TITLE", $titleHeader);

if (isset ($focus->parent_type) && $focus->parent_type != "") {
	///////////////////////////////////////
	///
	/// SETUP PARENT POPUP
	$popup_request_data = array ('call_back_function' => 'set_return', 'form_name' => 'EditView', 'field_to_name_array' => array ('id' => 'parent_id', 'name' => 'parent_name',),);
	// must urlencode to put into the filter request string
	// because IE gets an out of memory error when it is passed
	// as the usual object literal
	$encoded_popup_request_data = urlencode($json->encode($popup_request_data));
	//
	///////////////////////////////////////
	$change_parent_button = "<input title='".$app_strings['LBL_SELECT_BUTTON_TITLE']."' tabindex='2' accessKey='".$app_strings['LBL_SELECT_BUTTON_KEY']."' type='button' class='button' value='".$app_strings['LBL_SELECT_BUTTON_LABEL']."'  name='change_parent' onclick='open_popup(document.EditView.parent_type.value, 600, 400, \"&request_data=$encoded_popup_request_data&tree=ProductsProd\", true, false, {});' />";
	$xtpl->assign("CHANGE_PARENT_BUTTON", $change_parent_button);
}

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

/// Brands Popup
$popup_request_data = array(
	'call_back_function' => 'set_return',
	'form_name' => 'EditView',
	'field_to_name_array' => array(
		'id' => 'brand_id',
		'name' => 'brand_name',
		),
	);
$xtpl->assign('encoded_brands_popup_request_data', $json->encode($popup_request_data));

if (is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty ($_SESSION['editinplace'])) {
	$record = '';
	if (!empty ($_REQUEST['record'])) {
		$record = $_REQUEST['record'];
	}
	$xtpl->assign("ADMIN_EDIT", "<a href='index.php?action=index&module=DynamicLayout&from_action=".$_REQUEST['action']."&from_module=".$_REQUEST['module']."&record=".$record."'>".get_image($image_path."EditLayout", "border='0' alt='Edit Layout' align='bottom'")."</a>");
}


















$xtpl->parse("main.open_source");




echo <<<EOQ
<script>
function changeQS() {
	new_module = document.EditView.parent_type.value;
	if(new_module == 'Contacts' || new_module == 'Leads' || typeof(disabledModules[new_module]) != 'undefined') {
		sqs_objects['parent_name']['disable'] = true;
		document.getElementById('parent_name').readOnly = true;
	}
	else {
		sqs_objects['parent_name']['disable'] = false;
		document.getElementById('parent_name').readOnly = false;
	}

	sqs_objects['parent_name']['module'] = new_module;
}
</script>
EOQ;

$reminder_time = $current_user->getPreference('reminder_time');

if (!empty ($focus->reminder_time)) {
	$reminder_time = $focus->reminder_time;
}
if (empty ($reminder_time)) {
	$reminder_time = 900;
}
$xtpl->assign("REMINDER_TIME_OPTIONS", get_select_options_with_id($app_list_strings['reminder_time_options'], $reminder_time));
if ($reminder_time > -1) {
	$xtpl->assign("REMINDER_TIME_DISPLAY", 'inline');
	$xtpl->assign("REMINDER_CHECKED", 'checked');
} else {
	$xtpl->assign("REMINDER_TIME_DISPLAY", 'none');
}
echo '<script>var disabledModules='. $json->encode($disabled_parent_types) . ';</script>';
$xtpl->parse("main");

$xtpl->out("main");
echo '<script>changeQS();checkParentType(document.EditView.parent_type.value, document.EditView.change_parent);</script>';
require_once ('include/javascript/javascript.php');
$javascript = new javascript();
$javascript->setFormName('EditView');
$javascript->setSugarBean($focus);
$javascript->addAllFields('');
$javascript->addToValidateBinaryDependency('assigned_user_name', 'alpha', $app_strings['ERR_SQS_NO_MATCH_FIELD'] . $app_strings['LBL_ASSIGNED_TO'], 'false', '', 'assigned_user_id');
$javascript->addToValidateBinaryDependency('parent_name', 'alpha', $app_strings['ERR_SQS_NO_MATCH_FIELD'] . $mod_strings['LBL_LIST_RELATED_TO'], 'false', '', 'parent_id');
echo $javascript->getScript();

require_once('modules/SavedSearch/SavedSearch.php');
$savedSearch = new SavedSearch();
$json = getJSONobj();
$savedSearchSelects = $json->encode(array($GLOBALS['app_strings']['LBL_SAVED_SEARCH_SHORTCUT'] . '<br>' . $savedSearch->getSelect('Meetings')));
$str = "<script>
YAHOO.util.Event.addListener(window, 'load', SUGAR.util.fillShortcuts, $savedSearchSelects);
</script>";
echo $str;
?>

