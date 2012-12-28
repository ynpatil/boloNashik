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
if(isset($_REQUEST['user']))
$user = $_REQUEST['user'];
else
$user = null;

if (empty($_REQUEST['activity_report_filter']))
{
	//if ($current_user->getPreference('activity_report_filter') == '')
	//{
		$activity_report_filter = 'today';
	/*
	}
	else
	{
		$activity_report_filter = $current_user->getPreference('activity_report_filter');
	}
	*/
}
else
{
	$activity_report_filter = $_REQUEST['activity_report_filter'];
	$current_user->setPreference('activity_report_filter', $_REQUEST['activity_report_filter']);
}

if ($activity_report_filter == 'last this_month')
{
	$next_month = strftime("%B %Y", strtotime("+1 month"));
	$first_day = strftime("%d %B %Y", strtotime("first $next_month"));
	$log->debug("next_month is '$next_month'; first_day is '$first_day';");
	$appt_filter = strftime("%d %B %Y", strtotime("-1 day", strtotime($first_day)));
}
else
{
	$appt_filter = $activity_report_filter;
}

$later = "";
if($activity_report_filter != 'custom')
{
	$later = date("Y-m-d H:i:s", strtotime("$appt_filter"));
	//$log->debug("appt_filter is '$appt_filter'; later is '$later'");
	$later = $timedate->handle_offset($later, $timedate->dbDayFormat, true);
}

if ($activity_report_filter == 'last this_month')
{
	$first_day = strftime("%Y-%m-%d", strtotime("first $this_month"));
}
else if ($activity_report_filter == 'this Saturday')
	$first_day = strftime("%Y-%m-%d", strtotime("last Sunday"));
else if ($activity_report_filter == 'yesterday')
	$first_day = strftime("%Y-%m-%d", strtotime("-1 day"));
else
	$first_day = $later;

$log->debug("Later after offset :".$later." first day ".$first_day);
$date_text = "";

if($activity_report_filter == 'custom')
{
	$log->debug("Start Date ".$_REQUEST['start_date']);
	$start_date = explode('-',$_REQUEST['start_date']);
	$start_date = array_reverse($start_date);
	$start_date = implode('-',$start_date);

	$end_date = explode('-',$_REQUEST['end_date']);
	$end_date = array_reverse($end_date);
	$end_date = implode('-',$end_date);

	//$log->debug("Get DB Date :".$timestamp->to_db_date($_REQUEST['start_date'],FALSE));
	$date_text = " between ".db_convert($start_date,'date')." and ".db_convert($end_date.' 23:59:59','date');
}
else
	$date_text = " between ".db_convert($first_day,'date')." and ".db_convert($later.' 23:59:59','date');

$meeting = new Meeting();

if(isset($user) && !empty($user))
$where = " meetings.assigned_user_id ='$user'";
else
$where = " meetings.assigned_user_id in (".implode(",",get_user_in_array()).")";

$where .= " and ( meetings.date_entered ".$date_text." or meetings.date_modified ". $date_text.")";
$where .= " and meetings.deleted = 0";

//print("Where :".$where);

$focus_meetings_list = $meeting->get_full_list("time_start", $where);

$call = new Call();
$where = '(';
$or = false;
foreach ($open_status as $status)
{
	if ($or) $where .= ' OR ';
	$or = true;
	$where .= " calls.status = '$status' ";
}

//$where .= ") and calls.date_start <= ". db_convert($later, 'date') . " and calls_users.user_id='$current_user->id' ";
//$where .= " and calls_users.accept_status != 'decline'";

if(isset($user) && !empty($user))
$where = " calls.assigned_user_id ='$user'";
else
$where = " calls.assigned_user_id in (".implode(",",get_user_in_array()).")";

$where .= " and ( calls.date_entered ".$date_text." or calls.date_modified ". $date_text.")";
$where .= " and calls.deleted = 0";

//print("Where :".$where);

$focus_calls_list = $call->get_full_list("time_start", $where);

$task = new Task();

if(isset($user) && !empty($user))
$where = " tasks.assigned_user_id ='$user'";
else
$where = " tasks.assigned_user_id in (".implode(",",get_user_in_array()).")";

$where .= " and ( tasks.date_entered ".$date_text." or tasks.date_modified ". $date_text.")";
$where .= " and tasks.deleted = 0";

$focus_tasks_list = $task->get_full_list("time_start", $where);

$contact = new Contact();

if(isset($user) && !empty($user))
$where = " contacts.assigned_user_id ='$user'";
else
$where = " contacts.assigned_user_id in (".implode(",",get_user_in_array()).")";

$where .= " and ( contacts.date_entered ".$date_text." or contacts.date_modified ". $date_text.")";
$where .= " and contacts.deleted = 0";

$focus_contact_list = $contact->get_full_list("contacts.date_entered", $where);
//print("Contact list ".count($focus_contact_list));

$note = new Note();

if(isset($user) && !empty($user))
$where = " notes.created_by ='$user'";
else
$where = " notes.created_by in (".implode(",",get_user_in_array()).")";

$where .= " and ( notes.date_entered ".$date_text." or notes.date_modified ". $date_text.")";
$where .= " and notes.deleted = 0";

$focus_notes_list = $note->get_full_list("notes.date_entered", $where);
//print("Count Notes :".count($focus_notes_list));

$email = new Email();

if(isset($user) && !empty($user))
$where = " emails.assigned_user_id ='$user'";
else
$where = " emails.assigned_user_id in (".implode(",",get_user_in_array()).")";

$where .= " and ( emails.date_entered ".$date_text." or emails.date_modified ". $date_text.")";
$where .= " and emails.deleted = 0";

$focus_emails_list = $email->get_full_list("emails.date_entered", $where);
//print("Count Emails :".count($focus_emails_list));

$opportunity = new Opportunity();

if(isset($user) && !empty($user))
$where = " opportunities.assigned_user_id ='$user'";
else
$where = " opportunities.assigned_user_id in (".implode(",",get_user_in_array()).")";

$where .= " and ( opportunities.date_entered ".$date_text." or opportunities.date_modified ". $date_text.")";
$where .= " and opportunities.deleted = 0";

$focus_opportunities_list = $opportunity->get_full_list("opportunities.date_entered", $where);

$lead = new Lead();

if(isset($user) && !empty($user))
$where = " (leads.assigned_user_id ='$user' or leads.created_by='$user')";
else
$where = " (leads.assigned_user_id in (".implode(",",get_user_in_array()).") or leads.created_by in (".implode(",",get_user_in_array())."))";

$where .= " and ( leads.date_entered ".$date_text." or leads.date_modified ". $date_text.")";
$where .= " and leads.deleted = 0";

//print("Where :".$where);
$focus_leads_list = $lead->get_full_list("leads.date_entered", $where);

$case = new aCase();

if(isset($user) && !empty($user))
$where = " cases.assigned_user_id ='$user'";
else
$where = " cases.assigned_user_id in (".implode(",",get_user_in_array()).")";

$where .= " and ( cases.date_entered ".$date_text." or cases.date_modified ". $date_text.")";
$where .= " and cases.deleted = 0";

$focus_cases_list = $case->get_full_list("date_entered", $where);

$project = new Project();

if(isset($user) && !empty($user))
$where = " project.assigned_user_id ='$user'";
else
$where = " project.assigned_user_id in (".implode(",",get_user_in_array()).")";

$where .= " and ( project.date_entered ".$date_text." or project.date_modified ". $date_text.")";
$where .= " and project.deleted = 0";

$focus_projects_list = $project->get_full_list("project.date_entered", $where);

$open_activity_list = array();
$total_call_hrs = 0;
$total_call_mts = 0;

$total_meeting_hrs = 0;
$total_meeting_mts = 0;

if (count($focus_meetings_list)>0)
  foreach ($focus_meetings_list as $meeting) {
  	$td =  $timedate->merge_date_time($meeting->date_start, $meeting->time_start);
	$open_activity_list[] = Array('name' => $meeting->name,
								 'id' => $meeting->id,
								 'type' => "Meeting",
								 'module' => "Meetings",
								 'status' => $meeting->status,
								 'parent_id' => $meeting->parent_id,
								 'parent_type' => $meeting->parent_type,
								 'parent_name' => $meeting->parent_name,
								 'contact_id' => $meeting->contact_id,
								 'contact_name' => $meeting->contact_name,
								 'normal_date_start' => $meeting->date_start,
								 'date_start' => $timedate->to_display_date($td),
								 'normal_time_start' => $meeting->time_start,
								 'time_start' => $timedate->to_display_time($td,true),
								 'required' => $meeting->required,
								 'accept_status' => $meeting->accept_status,
								 );
$total_meeting_hrs = ($total_meeting_hrs + $meeting->duration_hours);
//print("Mts :".$meeting->duration_minutes);
$total_meeting_mts = ($total_meeting_mts + $meeting->duration_minutes);
}

if (count($focus_calls_list)>0)
  foreach ($focus_calls_list as $call) {
  	$td =  $timedate->merge_date_time($call->date_start, $call->time_start);
	$open_activity_list[] = Array('name' => $call->name,
								 'id' => $call->id,
								 'type' => "Call",
								 'module' => "Calls",
								 'status' => $call->status,
								 'parent_id' => $call->parent_id,
								 'parent_type' => $call->parent_type,
								 'parent_name' => $call->parent_name,
								 'contact_id' => $call->contact_id,
								 'contact_name' => $call->contact_name,
								 'date_start' =>  $timedate->to_display_date($td),
								 'normal_date_start' => $call->date_start,
								 'normal_time_start' => $call->time_start,
								 'time_start' =>$timedate->to_display_time($td,true),
								 'required' => $call->required,
								 'accept_status' => $call->accept_status,
								 );
$total_call_hrs = ($total_call_hrs + $call->duration_hours);
//print("Mts :".$call->duration_minutes);
$total_call_mts = ($total_call_mts + $call->duration_minutes);
}

//print("Total call hrs :".$total_call_hrs." ".$total_call_mts);

if(count($focus_tasks_list)>0)
foreach ($focus_tasks_list as $task) {
  		$td =  $timedate->merge_date_time($task->date_start, $task->time_start);

		$open_activity_list[] = Array('name' => $task->name,
									 'id' => $task->id,
									 'type' => 'Task',
									 'module' => "Tasks",
									 'status' => $task->status,
									 'parent_id' => $task->parent_id,
									 'parent_type' => $task->parent_type,
									 'parent_name' => $task->parent_name,
									 'contact_id' => $task->contact_id,
									 'contact_name' => $task->contact_name,
									 'date_due' => $date_due,
									 'normal_date_start' => $task->date_start,
									 'date_start' => $timedate->to_display_date($td),
									 'normal_time_start' => $task->time_start,
									 'time_start' => $timedate->to_display_time($td,true),
									 );
	}



if(count($focus_emails_list)>0)
foreach ($focus_emails_list as $email) {
	$open_activity_list[] = Array('name' => $email->name,
									 'id' => $email->id,
									 'type' => "Email",
									 'direction' => '',
									 'module' => "Emails",
									 'status' => '',
									 'parent_id' => $email->parent_id,
									 'parent_type' => $email->parent_type,
									 'parent_name' => $email->parent_name,
									 'contact_id' => $email->contact_id,
									 'contact_name' => $email->contact_name,
									 'date_modified' => $email->date_start." ".$email->time_start
									 );
}

if(count($focus_notes_list)>0)
foreach ($focus_notes_list as $note) {
	$open_activity_list[] = Array('name' => $note->name,
									 'id' => $note->id,
									 'type' => "Note",
									 'direction' => '',
									 'module' => "Notes",
									 'status' => '',
									 'parent_id' => $note->parent_id,
									 'parent_type' => $note->parent_type,
									 'parent_name' => $note->parent_name,
									 'contact_id' => $note->contact_id,
									 'contact_name' => $note->contact_name,
									 'date_modified' => $note->date_modified
									 );
	if (!empty($note->filename))
	{
		$count = count($open_activity_list);
		$count--;
		$open_activity_list[$count]['filename'] = $note->filename;
		$open_activity_list[$count]['fileurl'] = UploadFile::get_url($note->filename,$note->id);
	}
}


$contact_list = array();

if(count($focus_contact_list)>0)
foreach ($focus_contact_list as $contact) {
		$contact_list[] = Array('id' => $contact->id,
									 'type' => 'Contact',
									 'module' => "Contacts",
									 'first_name' => $contact->first_name,
									 'last_name' => $contact->last_name,
									 'account_id' => $contact->account_id,
									 'account_name' => $contact->account_name,
									 'email1' => $contact->email1,
									 'phone_work' => $contact->phone_work,
									 );
	}


$opportunity_list = array();

if(count($focus_opportunities_list)>0)
foreach ($focus_opportunities_list as $opportunity) {
		$opportunity_list[] = Array('id' => $opportunity->id,
									 'type' => 'Opportunity',
									 'module' => "Opportunities",
									 'name' => $opportunity->name,
									 'account_id' => $opportunity->account_id,
									 'account_name' => $opportunity->account_name,
									 'date_closed' => $opportunity->date_closed,
									 );
	}


$lead_list = array();

if(count($focus_leads_list)>0)
foreach ($focus_leads_list as $lead) {
		$lead_list[] = Array('id' => $lead->id,
									 'type' => 'Lead',
									 'module' => "Leads",
									 'first_name' => $lead->first_name,
									 'last_name' => $lead->last_name,
									 'refered_by' => $lead->refered_by,
									 'lead_source' => $lead->lead_source,
									 'lead_source_description' => $lead->lead_source_description,
									 );
	}


$case_list = array();

if(count($focus_cases_list)>0)
foreach ($focus_cases_list as $case) {
		$case_list[] = Array('id' => $case->id,
									 'name' => $case->name,
									 'type' => 'Case',
									 'module' => "Cases",
									 'number' => $case->number,
									 'account_id' => $case->account_id,
									 'account_name' => $case->account_name,
									 'status' => $case->status,
									 );
}

$project_list = array();

if(count($focus_projects_list)>0)
foreach ($focus_projects_list as $project) {
		$project_list[] = Array('id' => $project->id,
									 'name' => $project->name,
									 'type' => 'Project',
									 'module' => "Project",
									 'assigned_user_name' => $project->assigned_user_name,
									 'total_estimated_effort' => $project->total_estimated_effort,
									 'total_actual_effort' => $project->total_actual_effort,
									 );
}

$filter = get_select_options_with_id($current_module_strings['activity_filter_dom'], $activity_report_filter );

$userlist = get_select_options_with_id(get_user_array(FALSE), (isset($user)?$user:''));

$search_form=new XTemplate ('modules/ActivityReport/SearchForm.html');
$search_form->assign("MOD", $current_module_strings);
$search_form->assign("APP", $app_strings);
$search_form->assign("THEME", $theme);
$search_form->assign("USER_OPTIONS",$userlist);
$search_form->assign("ACTIVITY_REPORT_FILTER_OPTIONS",$filter);
if(isset($_REQUEST['start_date'])) $search_form->assign("START_DATE", to_html($_REQUEST['start_date']));
if(isset($_REQUEST['end_date'])) $search_form->assign("END_DATE", to_html($_REQUEST['end_date']));

$search_form->assign("DISPLAY", ($activity_report_filter == 'custom'?'block':'none'));
$search_form->assign("CALENDAR_DATEFORMAT", $timedate->get_cal_date_format());

$search_form->parse("main");
echo "\n<p>\n";

echo get_form_header($current_module_strings['LBL_ACTIVITY_REPORT'], '','', false);
$search_form->out("main");
//echo get_form_footer();
echo "\n</p>\n";

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

$return_url = "&return_module=".$currentModule."&return_action=index";
$xtpl->assign("RETURN_URL",$return_url);

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
		'TIME' => $time
	);

	switch ($activity['parent_type']) {
		case 'Accounts':
			$activity_fields['PARENT_MODULE'] = 'Accounts';
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

	//print("Status :".$activity['status'].".");

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
}

$xtpl->assign("TOTAL_CALL_HRS", $total_call_hrs);
$xtpl->assign("TOTAL_CALL_MTS", $total_call_mts);

$xtpl->assign("TOTAL_MEETING_HRS", $total_meeting_hrs);
$xtpl->assign("TOTAL_MEETING_MTS", $total_meeting_mts);

$xtpl->parse("open_activity");
if (count($open_activity_list)>0) $xtpl->out("open_activity");
else echo "<i>".$current_module_strings['NTC_NONE_SCHEDULED']."</i>";
// Stick on the form footer
//echo get_form_footer();

echo "\n</p>\n";


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
if (count($case_list)>0)
{
	echo get_form_header($current_module_strings['LBL_MODULE_NAME'], '','', false);
	$xtpl->out("main");
}
else echo "<i>".$current_module_strings['NTC_NONE_SCHEDULED']."</i>";

echo "\n</p>\n";
}

?>