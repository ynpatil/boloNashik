<?php
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
 * Portions created by SugarCRM are Copyright (C) 2004 SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/
/*********************************************************************************
 * $Id: OpenListView.php,v 1.48.2.1 2005/05/05 02:38:26 robert Exp $
 ********************************************************************************/
//om
require_once('XTemplate/xtpl.php');
require_once("data/Tracker.php");
require_once("include/utils.php");
require_once("modules/Calls/Call.php");
require_once("modules/Tasks/Task.php");
require_once("modules/Contacts/Contact.php");
require_once("modules/Meetings/Meeting.php");
require_once("modules/Notes/Note.php");
require_once("modules/Emails/Email.php");
require_once("modules/Opportunities/Opportunity.php");
require_once("modules/Leads/Lead.php");
require_once("modules/Cases/Case.php");
require_once("modules/Project/Project.php");
require_once('modules/ActivityReport/config.php');
require_once("include/TimeDate.php");

$timedate = new TimeDate();
global $currentModule, $theme, $focus, $action, $open_status, $log;

global $app_strings;
//we don't want the parent module's string file, but rather the string file specifc to this subpanel
global $current_language;
$current_module_strings = return_module_language($current_language, 'ActivityReport');

$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$today = date("Y-m-d");
$this_month = date("F Y", strtotime("+0 month"));
$todayTime = date("H:i:s");
$log->debug("today is '$today'; this_month is '$this_month'; todayTime is '$todayTime';");
$first_day = null;

require_once("modules/ActivityReport/prereq.php");

$open_activity_list = array();
$total_call_hrs = 0;
$total_call_mts = 0;

$total_meeting_hrs = 0;
$total_meeting_mts = 0;
$count_first_half = 0;
$count_second_half = 0;

if(!isset($_REQUEST['activity_details_for']) || $_REQUEST['activity_details_for'] == "Meetings")
{
	require_once("modules/ActivityReport/MeetingListViewQuery.php");
	require_once("modules/ActivityReport/MeetingListViewDisplay.php");
}

if(!isset($_REQUEST['activity_details_for']) || $_REQUEST['activity_details_for'] == "Calls")
{
	require_once("modules/ActivityReport/CallListViewQuery.php");
	require_once("modules/ActivityReport/CallListViewDisplay.php");
}

if(!isset($_REQUEST['activity_details_for']) || $_REQUEST['activity_details_for'] == "Task")
{
	require_once("modules/ActivityReport/TaskListViewQuery.php");
	require_once("modules/ActivityReport/TaskListViewDisplay.php");
}

if(!isset($_REQUEST['activity_details_for']) || $_REQUEST['activity_details_for'] == "Accounts")
{
	require_once("modules/ActivityReport/AccountListViewQuery.php");
	require_once("modules/ActivityReport/AccountListViewDisplay.php");
}

if(!isset($_REQUEST['activity_details_for']) || $_REQUEST['activity_details_for'] == "Contacts")
{
	require_once("modules/ActivityReport/ContactListViewQuery.php");
	require_once("modules/ActivityReport/ContactListViewDisplay.php");
}

if(!isset($_REQUEST['activity_details_for']) || $_REQUEST['activity_details_for'] == "Notes")
{
	require_once("modules/ActivityReport/NoteListViewQuery.php");
	require_once("modules/ActivityReport/NoteListViewDisplay.php");
}

if(!isset($_REQUEST['activity_details_for']) || $_REQUEST['activity_details_for'] == "Emails")
{
	require_once("modules/ActivityReport/EmailListViewQuery.php");
	require_once("modules/ActivityReport/EmailListViewDisplay.php");
}

if(!isset($_REQUEST['activity_details_for']) || $_REQUEST['activity_details_for'] == "Opportunity")
{
	require_once("modules/ActivityReport/OpportunityListViewQuery.php");
	require_once("modules/ActivityReport/OpportunityListViewDisplay.php");
}

if(!isset($_REQUEST['activity_details_for']) || $_REQUEST['activity_details_for'] == "Leads")
{
	require_once("modules/ActivityReport/LeadListViewQuery.php");
	require_once("modules/ActivityReport/LeadListViewDisplay.php");
}

if(!isset($_REQUEST['activity_details_for']) || $_REQUEST['activity_details_for'] == "Cases")
{
	require_once("modules/ActivityReport/aCaseListViewQuery.php");
	require_once("modules/ActivityReport/aCaseListViewDisplay.php");
}

if(!isset($_REQUEST['activity_details_for']) || $_REQUEST['activity_details_for'] == "Project")
{
	require_once("modules/ActivityReport/ProjectListViewQuery.php");
	require_once("modules/ActivityReport/ProjectListViewDisplay.php");
}

if(!isset($_REQUEST['activity_details_for']))
{
	if(isset($user)){
	$user_trimmed = explode("'",$user);
//	echo "User :".$user_trimmed[1];
	}
	$filter = get_select_options_with_id($current_module_strings['activity_filter_dom'], $activity_report_filter );
	$user_list = get_user_array_forassign(FALSE);
	$userlist = get_select_options_with_id($user_list, (isset($user_trimmed)?$user_trimmed[1]:''));

	$search_form=new XTemplate ('modules/ActivityReport/SearchForm.html');
	$search_form->assign("MOD", $current_module_strings);
	$search_form->assign("APP", $app_strings);
	$search_form->assign("THEME", $theme);
	$search_form->assign("USER_OPTIONS",$userlist);
	$search_form->assign("ACTIVITY_REPORT_FILTER_OPTIONS",$filter);
	if(isset($_REQUEST['start_date'])) $search_form->assign("START_DATE", to_html($_REQUEST['start_date']));
	if(isset($_REQUEST['end_date'])) $search_form->assign("END_DATE", to_html($_REQUEST['end_date']));

	$search_form->assign("DISPLAY", ($activity_report_filter == 'custom'?'block':'none'));
	$search_form->assign("CALENDAR_DATEFORMAT", "%d-%m-%Y");

	$search_form->parse("main");
	echo "\n<p>\n";

	echo get_form_header($current_module_strings['LBL_ACTIVITY_REPORT'], '','', false);
	$search_form->out("main");
	//echo get_form_footer();
	echo "\n</p>\n";
}

$contents = '';

//print("Activity count :".count($open_activity_list));
if(count($open_activity_list)>0)
{
echo "\n<p>\n";

$xtpl=new XTemplate ('modules/ActivityReport/OpenListView.html');
$xtpl->assign("MOD", $current_module_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("THEME",$theme);
$xtpl->assign("IMAGE_PATH", $image_path);
$xtpl->assign("RETURN_URL", "&return_module=$currentModule&return_action=DetailView&return_id=" . ((is_object($focus) && ! empty($focus->id)) ? $focus->id : ""));
$xtpl->assign("EDIT_INLINE_PNG",  get_image($image_path.'edit_inline','align="absmiddle" alt="'.$app_strings['LNK_EDIT'].'" border="0"'));
$xtpl->assign("DELETE_INLINE_PNG",  get_image($image_path.'delete_inline','align="absmiddle" alt="'.$app_strings['LNK_DELETE'].'" border="0"'));

if($activity_report_filter == 'custom')
{
	if(isset($_REQUEST['start_date']))
	$xtpl->assign("START_DATE",$_REQUEST['start_date']);

	if(isset($_REQUEST['end_date']))
	$xtpl->assign("END_DATE",$_REQUEST['end_date']);
}

$oddRow = true;

//echo "User :".$user_list[$user_trimmed[1]];
$xtpl->assign("REPORT_TYPE","Activity Report");
$xtpl->assign("USER_NAME",$user_list[$user_trimmed[1]].$date_text_display);
$xtpl->assign("MEETING_COUNT",count($focus_meetings_list));
$xtpl->assign("CALL_COUNT",count($focus_calls_list));

$return_url = "&return_module=".$currentModule."&return_action=index";
$xtpl->assign("RETURN_URL",$return_url);

$activities_log = array();

if (count($open_activity_list) > 0) $open_activity_list = array_csort($open_activity_list, 'normal_date_start', 'normal_time_start', SORT_ASC);

foreach($open_activity_list as $activity)
{
	if( $activity['normal_date_start']	< $today ||  ($activity['normal_date_start'] ==  $today && $activity['normal_time_start'] < $todayTime))
	{
		$time = "<font class='overdueTask'>".$activity['date_start'].' '.$activity['time_start']."</font>";
	}
	else if( $activity['normal_date_start']	== $today )
	{
		$time = "<font class='todaysTask'>".$activity['date_start'].' '.$activity['time_start']."</font>";
	}else
	{
		$time = "<font class='futureTask'>".$activity['date_start'].' '.$activity['time_start']."</font>";
	}

	//print("Time :".$time."<br>");
	$activity_fields = array(
		'ID' => $activity['id'],
		'NAME' => $activity['name'],
		'TYPE' => $activity['type'],
		'MODULE' => $activity['module'],
		'STATUS' => $activity['status'],
		'CONTACT_NAME' => $activity['contact_name'],
		'CONTACT_ID' => $activity['contact_id'],
		'PARENT_TYPE' => $activity['parent_type'],
		'PARENT_NAME' => $activity['parent_name'],
		'PARENT_ID' => $activity['parent_id'],
		'TIME' => $time." ".$activity['duration']
	);
		
		if($activity_fields['MODULE'] == 'Calls' || $activity_fields['MODULE'] == 'Meetings'){
			$results = "<span id='adspan_" . $activity_fields['ID']. "' onmouseout=\"return SUGAR.util.clearAdditionalDetailsCall()\" onmouseover=\"return SUGAR.util.getAdditionalDetailsActivityReport('" . $activity_fields['MODULE']. "', '" . $activity_fields['ID'] . "', 'adspan_" . $activity_fields['ID'] . "')\" "
				. "onmouseout=\"return nd(1000);\"><img style='padding: 0px 5px 0px 2px' border='0' src='themes/$theme/images/MoreDetail.png' width='8' height='7'></span>";
		
			//$GLOBALS['log']->debug("Results :".$results);
	
			$activity_fields['MORE_INFO'] = $results;
		}
		else
		$activity_fields['MORE_INFO'] = '';
			
	switch ($activity['parent_type']) {
		case 'Accounts':
			$activity_fields['PARENT_MODULE'] = 'Accounts';
			break;
		case 'Contacts':
			$activity_fields['PARENT_MODULE'] = 'Contacts';
			break;
		case 'Cases':
			$activity_fields['PARENT_MODULE'] = 'Cases';
			break;
		case 'Opportunities':
			$activity_fields['PARENT_MODULE'] = 'Opportunities';
			break;
		case 'Quotes':
			$activity_fields['PARENT_MODULE'] = 'Quotes';
			break;
		case 'ProjectTask':
			$activity_fields['PARENT_MODULE'] = 'ProjectTask';
			break;
	}

//	print("Parent module :".$activity_fields['PARENT_ID'].".");

	if(($activity['status'] != 'Completed') && ($activity['status'] != 'Held'))
	{
		//print("Activity type :"+$activity['type']."<br>");
		switch ($activity['type'])
		{
		case 'Call':
			$activity_fields['SET_COMPLETE'] = "<a href='index.php?return_module=$currentModule&return_action=$action&return_id=" . ((is_object($focus) && ! empty($focus->id)) ? $focus->id : "")."&action=EditView&module=Calls&status=Held&record=".$activity['id']."&status=Held'>".get_image($image_path."close_inline","alt='Close' border='0'")."</a>";
			break;
		case 'Meeting':
			$activity_fields['SET_COMPLETE'] = "<a href='index.php?return_module=$currentModule&return_action=$action&return_id=" . ((is_object($focus) && ! empty($focus->id)) ? $focus->id : "")."&action=EditView&module=Meetings&status=Held&record=".$activity['id']."&status=Held'>".get_image($image_path."close_inline","alt='Close' border='0'")."</a>";
			break;
		case 'Task':
			$activity_fields['SET_COMPLETE'] = "<a href='index.php?return_module=$currentModule&return_action=$action&return_id=" . ((is_object($focus) && ! empty($focus->id)) ? $focus->id : "")."&action=EditView&module=Tasks&status=Completed&record=".$activity['id']."&status=Completed'>".get_image($image_path."close_inline","alt='Close' border='0'")."</a>";
			break;
		}
		//print("Set complete :".$activity_fields['SET_COMPLETE']);
	}

$activity_fields['TITLE'] = '';
if (!empty($activity['contact_name'])) {
	$activity_fields['TITLE'] .= $current_module_strings['LBL_LIST_CONTACT'].": ".$activity['contact_name'];
}
if (!empty($activity['parent_name'])) {
	$activity_fields['TITLE'] .= "\n".$app_list_strings['record_type_display'][$activity['parent_type']].": ".$activity['parent_name'];
}

$xtpl->assign("ACTIVITY_MODULE_PNG", get_image($image_path.$activity_fields['MODULE'].'','border="0" alt="'.$activity_fields['NAME'].'"'));
	$xtpl->assign("ACTIVITY", $activity_fields);
 global $odd_bg;
 global $even_bg;
 global $hilite_bg;
 global $click_bg;

$xtpl->assign("BG_HILITE", $hilite_bg);
$xtpl->assign("BG_CLICK", $click_bg);

	if($oddRow)
    {
        //todo move to themes
		$xtpl->assign("ROW_COLOR", 'oddListRow');
		$xtpl->assign("BG_COLOR", $odd_bg);
    }
    else
    {
        //todo move to themes
		$xtpl->assign("ROW_COLOR", 'evenListRow');
		$xtpl->assign("BG_COLOR", $even_bg);
    }
    $oddRow = !$oddRow;

	$xtpl->parse("open_activity.row");
// Put the rows in.

	if($_REQUEST['print_pdf'] == 'true'){	
		$activity_fields['ACTUAL_TIME'] = $activity['date_start'].' '.$activity['time_start'];
		$activities_log[] = $activity_fields;
	}
}

$total_call_hrs = $total_call_hrs + floor($total_call_mts/60);
$total_meeting_hrs = $total_meeting_hrs + floor($total_meeting_mts/60);

$xtpl->assign("TOTAL_CALL_HRS", $total_call_hrs);
$xtpl->assign("TOTAL_CALL_MTS", $total_call_mts % 60);
$xtpl->assign("TOTAL_MEETING_HRS", $total_meeting_hrs);
$xtpl->assign("TOTAL_MEETING_MTS", $total_meeting_mts % 60);
$xtpl->assign("TOTAL_FIRST_HALF", $count_first_half);
$xtpl->assign("TOTAL_SECOND_HALF", $count_second_half);

$xtpl->parse("open_activity");
if (count($open_activity_list)>0) $xtpl->out("open_activity");
else echo "<i>".$current_module_strings['NTC_NONE_SCHEDULED']."</i>";
// Stick on the form footer
//echo get_form_footer();

echo "\n</p>\n";
}

if(count($account_list)>0)
{
echo "\n<p>\n";

$xtpl=new XTemplate ('modules/Accounts/SubPanelView.html');
$current_module_strings = return_module_language($current_language, 'Accounts');

$xtpl->assign("MOD", $current_module_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("THEME",$theme);
$xtpl->assign("IMAGE_PATH", $image_path);
$xtpl->assign("RETURN_URL", "&return_module=$currentModule&return_action=DetailView&return_id=" . ((is_object($focus) && ! empty($focus->id)) ? $focus->id : ""));
$xtpl->assign("EDIT_INLINE_PNG",  get_image($image_path.'edit_inline','align="absmiddle" alt="'.$app_strings['LNK_EDIT'].'" border="0"'));
$xtpl->assign("DELETE_INLINE_PNG",  get_image($image_path.'delete_inline','align="absmiddle" alt="'.$app_strings['LNK_DELETE'].'" border="0"'));

$oddRow = true;

$xtpl->assign("RETURN_URL",$return_url);

foreach($account_list as $account)
{
	//print("Time :".$time."<br>");
	$activity_fields = array(
		'ID' => $account['id'],
		'NAME' => $account['name'],
		'BILLING_ADDRESS_CITY' => $account['billing_address_city'],
		'BILLING_ADDRESS_STATE' => $account['billing_address_state'],
		'PHONE_OFFICE' => $account['phone_office'],
	);

 $xtpl->assign("ACCOUNT", $activity_fields);

 global $odd_bg;
 global $even_bg;
 global $hilite_bg;
 global $click_bg;

$xtpl->assign("BG_HILITE", $hilite_bg);
$xtpl->assign("BG_CLICK", $click_bg);

	if($oddRow)
    {
        //todo move to themes
		$xtpl->assign("ROW_COLOR", 'oddListRow');
		$xtpl->assign("BG_COLOR", $odd_bg);
    }
    else
    {
        //todo move to themes
		$xtpl->assign("ROW_COLOR", 'evenListRow');
		$xtpl->assign("BG_COLOR", $even_bg);
    }
    $oddRow = !$oddRow;

	$xtpl->parse("main.row");
// Put the rows in.
}

$xtpl->parse("main");
if (count($account_list)>0)
{
	echo get_form_header($current_module_strings['LBL_MODULE_NAME'], '','', false);
	$xtpl->out("main");
}
else echo "<i>".$current_module_strings['NTC_NONE_SCHEDULED']."</i>";
// Stick on the form footer
//echo get_form_footer();
echo "\n</p>\n";
}

if(count($contact_list)>0)
{
echo "\n<p>\n";

$xtpl=new XTemplate ('modules/Contacts/SubPanelViewContact.html');
$current_module_strings = return_module_language($current_language, 'Contacts');

$xtpl->assign("MOD", $current_module_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("THEME",$theme);
$xtpl->assign("IMAGE_PATH", $image_path);
$xtpl->assign("RETURN_URL", "&return_module=$currentModule&return_action=DetailView&return_id=" . ((is_object($focus) && ! empty($focus->id)) ? $focus->id : ""));
$xtpl->assign("EDIT_INLINE_PNG",  get_image($image_path.'edit_inline','align="absmiddle" alt="'.$app_strings['LNK_EDIT'].'" border="0"'));
$xtpl->assign("DELETE_INLINE_PNG",  get_image($image_path.'delete_inline','align="absmiddle" alt="'.$app_strings['LNK_DELETE'].'" border="0"'));

$oddRow = true;

$xtpl->assign("RETURN_URL",$return_url);

foreach($contact_list as $contact)
{
	//print("Time :".$time."<br>");
	$activity_fields = array(
		'ID' => $contact['id'],
		'FIRST_NAME' => $contact['first_name'],
		'LAST_NAME' => $contact['last_name'],
		'TYPE' => $contact['type'],
		'MODULE' => $contact['module'],
		'ACCOUNT_ID' => $contact['account_id'],
		'ACCOUNT_NAME' => $contact['account_name'],
		'PHONE_WORK' => $contact['phone_work'],
		'EMAIL1' => $contact['email1'],
	);

 $xtpl->assign("CONTACT", $activity_fields);

 global $odd_bg;
 global $even_bg;
 global $hilite_bg;
 global $click_bg;

$xtpl->assign("BG_HILITE", $hilite_bg);
$xtpl->assign("BG_CLICK", $click_bg);

	if($oddRow)
    {
        //todo move to themes
		$xtpl->assign("ROW_COLOR", 'oddListRow');
		$xtpl->assign("BG_COLOR", $odd_bg);
    }
    else
    {
        //todo move to themes
		$xtpl->assign("ROW_COLOR", 'evenListRow');
		$xtpl->assign("BG_COLOR", $even_bg);
    }
    $oddRow = !$oddRow;

	$xtpl->parse("contacts.row");
// Put the rows in.
}

$xtpl->parse("contacts");
if (count($contact_list)>0)
{

	echo get_form_header($current_module_strings['LBL_MODULE_NAME'], '','', false);
	$xtpl->out("contacts");
}
else echo "<i>".$current_module_strings['NTC_NONE_SCHEDULED']."</i>";
// Stick on the form footer
//echo get_form_footer();
echo "\n</p>\n";
}

if(count($opportunity_list)>0)
{
echo "\n<p>\n";

$xtpl=new XTemplate ('modules/Opportunities/SubPanelView.html');
$current_module_strings = return_module_language($current_language, 'Opportunities');
$xtpl->assign("MOD", $current_module_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("THEME",$theme);
$xtpl->assign("IMAGE_PATH", $image_path);
$xtpl->assign("RETURN_URL", "&return_module=$currentModule&return_action=DetailView&return_id=" . ((is_object($focus) && ! empty($focus->id)) ? $focus->id : ""));
$xtpl->assign("EDIT_INLINE_PNG",  get_image($image_path.'edit_inline','align="absmiddle" alt="'.$app_strings['LNK_EDIT'].'" border="0"'));
$xtpl->assign("DELETE_INLINE_PNG",  get_image($image_path.'delete_inline','align="absmiddle" alt="'.$app_strings['LNK_DELETE'].'" border="0"'));

$oddRow = true;

$xtpl->assign("RETURN_URL",$return_url);

foreach($opportunity_list as $opportunity)
{
	//print("Time :".$time."<br>");
	$activity_fields = array(
		'ID' => $opportunity['id'],
		'NAME' => $opportunity['name'],
		'TYPE' => $opportunity['type'],
		'MODULE' => $opportunity['module'],
		'ACCOUNT_ID' => $opportunity['account_id'],
		'ACCOUNT_NAME' => $opportunity['account_name'],
		'DATE_CLOSED' => $opportunity['date_closed'],
	);

 $xtpl->assign("OPPORTUNITY", $activity_fields);

 global $odd_bg;
 global $even_bg;
 global $hilite_bg;
 global $click_bg;

$xtpl->assign("BG_HILITE", $hilite_bg);
$xtpl->assign("BG_CLICK", $click_bg);

	if($oddRow)
    {
        //todo move to themes
		$xtpl->assign("ROW_COLOR", 'oddListRow');
		$xtpl->assign("BG_COLOR", $odd_bg);
    }
    else
    {
        //todo move to themes
		$xtpl->assign("ROW_COLOR", 'evenListRow');
		$xtpl->assign("BG_COLOR", $even_bg);
    }
    $oddRow = !$oddRow;

	$xtpl->parse("main.row");
// Put the rows in.
}

$xtpl->parse("main");
if (count($opportunity_list)>0)
{
	echo get_form_header($current_module_strings['LBL_MODULE_NAME'], '','', false);
	$xtpl->out("main");
}
else echo "<i>".$current_module_strings['NTC_NONE_SCHEDULED']."</i>";
// Stick on the form footer
//echo get_form_footer();
echo "\n</p>\n";
}

if(count($lead_list)>0)
{
echo "\n<p>\n";

$xtpl=new XTemplate ('modules/Leads/SubPanelView.html');
$current_module_strings = return_module_language($current_language, 'Leads');
$xtpl->assign("MOD", $current_module_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("THEME",$theme);
$xtpl->assign("IMAGE_PATH", $image_path);
$xtpl->assign("RETURN_URL", "&return_module=$currentModule&return_action=DetailView&return_id=" . ((is_object($focus) && ! empty($focus->id)) ? $focus->id : ""));
$xtpl->assign("EDIT_INLINE_PNG",  get_image($image_path.'edit_inline','align="absmiddle" alt="'.$app_strings['LNK_EDIT'].'" border="0"'));
$xtpl->assign("DELETE_INLINE_PNG",  get_image($image_path.'delete_inline','align="absmiddle" alt="'.$app_strings['LNK_DELETE'].'" border="0"'));

$oddRow = true;

$xtpl->assign("RETURN_URL",$return_url);

foreach($lead_list as $lead)
{
	//print("Lead Source Desc :".$lead->lead_source_description."<br>");
	$activity_fields = array(
		'ID' => $lead['id'],
		'FIRST_NAME' => $lead['first_name'],
		'LAST_NAME' => $lead['last_name'],
		'TYPE' => $lead['type'],
		'MODULE' => $lead['module'],
		'REFERED_BY' => $lead['refered_by'],
		'LEAD_SOURCE' => $lead['lead_source'],
		'LEAD_SOURCE_DESCRIPTION' => $lead['lead_source_description'],
	);

 $xtpl->assign("LEAD", $activity_fields);

 global $odd_bg;
 global $even_bg;
 global $hilite_bg;
 global $click_bg;

$xtpl->assign("BG_HILITE", $hilite_bg);
$xtpl->assign("BG_CLICK", $click_bg);

	if($oddRow)
    {
        //todo move to themes
		$xtpl->assign("ROW_COLOR", 'oddListRow');
		$xtpl->assign("BG_COLOR", $odd_bg);
    }
    else
    {
        //todo move to themes
		$xtpl->assign("ROW_COLOR", 'evenListRow');
		$xtpl->assign("BG_COLOR", $even_bg);
    }
    $oddRow = !$oddRow;

	$xtpl->parse("main.row");
// Put the rows in.
}

$xtpl->parse("main");
if (count($lead_list)>0)
{
	echo get_form_header($current_module_strings['LBL_MODULE_NAME'], '','', false);
	$xtpl->out("main");
}
else echo "<i>".$current_module_strings['NTC_NONE_SCHEDULED']."</i>";
// Stick on the form footer
//echo get_form_footer();
echo "\n</p>\n";
}


if(count($case_list)>0)
{
echo "\n<p>\n";

$xtpl=new XTemplate ('modules/Cases/SubPanelView.html');
$current_module_strings = return_module_language($current_language, 'Cases');
$xtpl->assign("MOD", $current_module_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("THEME",$theme);
$xtpl->assign("IMAGE_PATH", $image_path);
$xtpl->assign("RETURN_URL", "&return_module=$currentModule&return_action=DetailView&return_id=" . ((is_object($focus) && ! empty($focus->id)) ? $focus->id : ""));
$xtpl->assign("EDIT_INLINE_PNG",  get_image($image_path.'edit_inline','align="absmiddle" alt="'.$app_strings['LNK_EDIT'].'" border="0"'));
$xtpl->assign("DELETE_INLINE_PNG",  get_image($image_path.'delete_inline','align="absmiddle" alt="'.$app_strings['LNK_DELETE'].'" border="0"'));

$oddRow = true;

$xtpl->assign("RETURN_URL",$return_url);

foreach($case_list as $case)
{
	//print("Lead Source Desc :".$lead->lead_source_description."<br>");
	$activity_fields = array(
		'ID' => $case['id'],
		'NAME' => $case['name'],
		'NUMBER' => $case['number'],
		'ACCOUNT_ID' => $case['account_id'],
		'ACCOUNT_NAME' => $case['account_name'],
		'TYPE' => $case['type'],
		'MODULE' => $case['module'],
		'STATUS' => $case['status'],
	);

 $xtpl->assign("CASE", $activity_fields);

 global $odd_bg;
 global $even_bg;
 global $hilite_bg;
 global $click_bg;

$xtpl->assign("BG_HILITE", $hilite_bg);
$xtpl->assign("BG_CLICK", $click_bg);

	if($oddRow)
    {
        //todo move to themes
		$xtpl->assign("ROW_COLOR", 'oddListRow');
		$xtpl->assign("BG_COLOR", $odd_bg);
    }
    else
    {
        //todo move to themes
		$xtpl->assign("ROW_COLOR", 'evenListRow');
		$xtpl->assign("BG_COLOR", $even_bg);
    }
    $oddRow = !$oddRow;

	$xtpl->parse("main.row");
// Put the rows in.
}

$xtpl->parse("main");
if (count($case_list)>0)
{
	echo get_form_header($current_module_strings['LBL_MODULE_NAME'], '','', false);
	$xtpl->out("main");
}
else echo "<i>".$current_module_strings['NTC_NONE_SCHEDULED']."</i>";
// Stick on the form footer
//echo get_form_footer();
echo "\n</p>\n";
}

if(count($project_list)>0)
{
echo "\n<p>\n";

$xtpl=new XTemplate ('modules/Project/SubPanelView.html');
$current_module_strings = return_module_language($current_language, 'Project');
$xtpl->assign("MOD", $current_module_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("THEME",$theme);
$xtpl->assign("IMAGE_PATH", $image_path);
$xtpl->assign("RETURN_URL", "&return_module=$currentModule&return_action=DetailView&return_id=" . ((is_object($focus) && ! empty($focus->id)) ? $focus->id : ""));
$xtpl->assign("EDIT_INLINE_PNG",  get_image($image_path.'edit_inline','align="absmiddle" alt="'.$app_strings['LNK_EDIT'].'" border="0"'));
$xtpl->assign("DELETE_INLINE_PNG",  get_image($image_path.'delete_inline','align="absmiddle" alt="'.$app_strings['LNK_DELETE'].'" border="0"'));

$oddRow = true;

$xtpl->assign("RETURN_URL",$return_url);

foreach($project_list as $project)
{
	//print("Lead Source Desc :".$lead->lead_source_description."<br>");
	$activity_fields = array(
		'ID' => $project['id'],
		'NAME' => $project['name'],
		'ASSIGNED_USER_NAME' => $project['assigned_user_name'],
		'TOTAL_ESTIMATED_EFFORT' => $project['total_estimated_effort'],
		'TOTAL_ACTUAL_EFFORT' => $project['total_actual_effort'],
	);

 $xtpl->assign("PROJECT", $activity_fields);

 global $odd_bg;
 global $even_bg;
 global $hilite_bg;
 global $click_bg;

$xtpl->assign("BG_HILITE", $hilite_bg);
$xtpl->assign("BG_CLICK", $click_bg);

	if($oddRow)
    {
        //todo move to themes
		$xtpl->assign("ROW_COLOR", 'oddListRow');
		$xtpl->assign("BG_COLOR", $odd_bg);
    }
    else
    {
        //todo move to themes
		$xtpl->assign("ROW_COLOR", 'evenListRow');
		$xtpl->assign("BG_COLOR", $even_bg);
    }
    $oddRow = !$oddRow;

	$xtpl->parse("main.row");
// Put the rows in.
}

$xtpl->parse("main");
if (count($project_list)>0)
{
	echo get_form_header($current_module_strings['LBL_MODULE_NAME'], '','', false);
	$xtpl->out("main");
}
else echo "<i>".$current_module_strings['NTC_NONE_SCHEDULED']."</i>";

echo "\n</p>\n";
}

	if($_REQUEST['print_pdf'] == 'true'){
		require_once("pdf/tuto6.php");

		$pdf=new PDF();
		$pdf->SetFont('Arial','',14);	
		$pdf->SetRightMargin(2);
		$pdf->AddPage();
		$pdf->Cell(40,3,"Movement Register for :".$user_list[$user_trimmed[1]].$date_text_display);
		$pdf->Ln(5);
		$pdf->y0=$pdf->GetY();
		$pdf->SetFont('Arial','',10);			
		$header = array('Subject','Status','Start Date','Contact','Related To');
		
		$entries = array();
		$max_len = 25;
		foreach($activities_log as $activity){
					
			$entries[] = Array(
							 'NAME' => strlen($activity['NAME'])>$max_len?substr($activity['NAME'],0,$max_len)."...":$activity['NAME'],
							 'STATUS' => $activity['STATUS'],
							 'ACTUAL_TIME' => $activity['ACTUAL_TIME'],
							 'CONTACT_NAME' => $activity['CONTACT_NAME'],
							 'TYPE' => $activity['TYPE'],							 
							 'PARENT_NAME' => strlen($activity['PARENT_NAME'])>20?substr($activity['PARENT_NAME'],0,20)."...":$activity['PARENT_NAME'],
							 'PARENT_TYPE' => $activity['PARENT_TYPE'],							 
							 );
							 
			//$GLOBALS['log']->debug("Array elements :".implode(",",array_keys($activity)));
		}
				
		$pdf->BasicTable($header,$entries);
		$filename = "ActivityReport_".$user_trimmed[1].date('d-m-Y-His').".pdf";
		$pdf->Output($filename,"F");
		echo "<script>window.open(\"".$sugar_config['site_url']."/".$filename."\");</script>";
	}
?>