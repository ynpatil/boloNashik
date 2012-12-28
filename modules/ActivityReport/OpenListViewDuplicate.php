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
//om
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

$call_summary_list = array();
$meeting_summary_list = array();
$unique_account_list = array();
$duplicate_meeting_list = array();
$duplicate_call_list = array();

if(!$_REQUEST['new']){
if(!isset($_REQUEST['activity_details_for']) || $_REQUEST['activity_details_for'] == "Meetings")
{
	require_once("modules/ActivityReport/MeetingListViewQuerySummary.php");
	require_once("modules/ActivityReport/MeetingListViewDisplayDuplicate.php");
}

if(!isset($_REQUEST['activity_details_for']) || $_REQUEST['activity_details_for'] == "Calls")
{
	require_once("modules/ActivityReport/CallListViewQuerySummary.php");
	require_once("modules/ActivityReport/CallListViewDisplayDuplicate.php");
}

if(!isset($_REQUEST['activity_details_for']) || $_REQUEST['activity_details_for'] == "Users")
{
	require_once("modules/ActivityReport/UserListViewQuerySummary.php");
	require_once("modules/ActivityReport/UserListViewDisplayDuplicate.php");
}

}
if(!isset($_REQUEST['activity_details_for']))
{
	if(isset($user)){
	$user_trimmed = explode("'",$user);
//	echo "User :".$user_trimmed[1];
	}
	$filter = get_select_options_with_id($current_module_strings['activity_filter_dom'], $activity_report_filter );
	$user_list = get_user_array_forassign(true);
	$userlist = get_select_options_with_id($user_list, (isset($user_trimmed)?$user_trimmed[1]:''));

	if(isset($branch)){
		$branch_trimmed = explode("'",$branch);
	}
	
	$branch_list = get_table_array('id','name','branch_mast');
	$branch_list[''] = '';
	
//	$GLOBALS['log']->debug("Branch :".$branch);
	$branchlist = get_select_options_with_id($branch_list, (isset($branch_trimmed[1])?$branch_trimmed[1]:''));
	
	if(isset($vertical)){
		$vertical_trimmed = explode("'",$vertical);
	}

	$vertical_list = get_table_array('id','name','verticals_mast');
	$vertical_list[''] = '';
	$verticallist = get_select_options_with_id($vertical_list, (isset($vertical_trimmed[1])?$vertical_trimmed[1]:''));
		
	$search_form=new XTemplate ('modules/ActivityReport/SearchFormDuplicate.html');
	$search_form->assign("MOD", $current_module_strings);
	$search_form->assign("APP", $app_strings);
	$search_form->assign("THEME", $theme);

	$search_form->assign("USER_OPTIONS",$userlist);
	$search_form->assign("BRANCH_OPTIONS",$branchlist);
	$search_form->assign("VERTICAL_OPTIONS",$verticallist);	

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
if(count($branch_summary_list)>0 && !(isset($user) && !empty($user)))
{
echo "\n<p>Branch-wise duplicate summary</p>\n";
echo "\n<p>\n";

$xtpl=new XTemplate ('modules/ActivityReport/OpenListViewDuplicateBranch.html');
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
$xtpl->assign("USER_NAME",$user_list[$user_trimmed[1]].$date_text_display);
$return_url = "&return_module=".$currentModule."&return_action=index";
$xtpl->assign("RETURN_URL",$return_url);

//if (count($user_summary_list) > 0) $user_summary_list = array_csort($user_summary_list, 'TOTAL_COUNT', SORT_DESC);

foreach($branch_summary_list as $activity)
{
$xtpl->assign("ACTIVITY", $activity);
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

$xtpl->parse("open_activity");
if (count($user_summary_list)>0) $xtpl->out("open_activity");
else echo "<i>".$current_module_strings['NTC_NONE_SCHEDULED']."</i>";
// Stick on the form footer
//echo get_form_footer();

echo "\n</p>\n";
}


//print("Activity count :".count($open_activity_list));
if(count($suboffice_summary_list)>0 && !(isset($user) && !empty($user)))
{
echo "\n<p>Suboffice-wise duplicate summary</p>\n";

echo "\n<p>\n";

$xtpl=new XTemplate ('modules/ActivityReport/OpenListViewDuplicateSubOffice.html');
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
$xtpl->assign("USER_NAME",$user_list[$user_trimmed[1]].$date_text_display);
$return_url = "&return_module=".$currentModule."&return_action=index";
$xtpl->assign("RETURN_URL",$return_url);

//if (count($user_summary_list) > 0) $user_summary_list = array_csort($user_summary_list, 'TOTAL_COUNT', SORT_DESC);

foreach($suboffice_summary_list as $activity)
{
$xtpl->assign("ACTIVITY", $activity);
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

$xtpl->parse("open_activity");
if (count($user_summary_list)>0) $xtpl->out("open_activity");
else echo "<i>".$current_module_strings['NTC_NONE_SCHEDULED']."</i>";
// Stick on the form footer
//echo get_form_footer();

echo "\n</p>\n";
}

//print("Activity count :".count($open_activity_list));
if(count($user_summary_list)>0)
{
echo "\n<p>User-wise duplicate summary</p>\n";
echo "\n<p>\n";

$xtpl=new XTemplate ('modules/ActivityReport/OpenListViewDuplicate.html');
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
$xtpl->assign("USER_NAME",$user_list[$user_trimmed[1]].$date_text_display);
$return_url = "&return_module=".$currentModule."&return_action=index";
$xtpl->assign("RETURN_URL",$return_url);

if (count($user_summary_list) > 0) $user_summary_list = array_csort($user_summary_list, 'TOTAL_COUNT', SORT_DESC);

foreach($user_summary_list as $activity)
{
$xtpl->assign("ACTIVITY", $activity);
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

$xtpl->parse("open_activity");
if (count($user_summary_list)>0) $xtpl->out("open_activity");
else echo "<i>".$current_module_strings['NTC_NONE_SCHEDULED']."</i>";
// Stick on the form footer
//echo get_form_footer();

echo "\n</p>\n";
}

	if($_REQUEST['print_pdf'] == 'true'){

		require_once("pdf/duplicate.php");

		$pdf=new PDF();
		$pdf->SetFont('Arial','',8);
		$pdf->SetRightMargin(2);
		$pdf->AddPage();
		$pdf->Cell(100,3,"Duplicate Entries / Unique Accounts Summary ".$date_text);
		$pdf->Ln(7);
                $pdf->SetAutoPageBreak(false);
		$pdf->y0=$pdf->GetY();
		$pdf->SetFont('Arial','',8);
		$header = array('USER','REPORTS_TO','BRANCH/SUBOFFICE','VERTICAL','MEETINGS','DUPLI_MEETING','CALLS','DUPLI_CALLS','UNIQUE_ACCOUNTS');
		/*'RESPONSIBILITY'*/
		$entries = array();
		$max_len = 20;
		
		foreach($user_summary_list as $activity){
					
			$entries[] = Array(
                                                         'USER' => strlen($activity['USER'])>$max_len?substr($activity['USER'],0,$max_len)."...":$activity['USER'],
                                                         'REPORTS_TO_NAME' => strlen($activity['REPORTS_TO_NAME'])>$max_len?substr($activity['REPORTS_TO_NAME'],0,$max_len)."...":$activity['REPORTS_TO_NAME'],
							 'BRANCH/SUBOFFICE' => $activity['BRANCH'].'/'.$activity['SUBOFFICE'],
							 'VERTICAL' => strlen($activity['VERTICAL'])>$max_len?substr($activity['VERTICAL'],0,$max_len)."...":$activity['VERTICAL'],
                                                         /*'RESPONSIBILITY' => strlen($activity['USER_TYPE_NAME'])>$max_len?substr($activity['USER_TYPE_NAME'],0,$max_len)."...":$activity['USER_TYPE_NAME'],*/
							 'MEETINGS' => $activity['MEETINGS'],
                                                         'DUPLI_MEETING' => $activity['DUPLICATE_MEETING_COUNT'],
							 'CALLS' => $activity['CALLS'],
                                                         'DUPLI_CALLS' => $activity['DUPLICATE_CALL_COUNT'],
							 'UNIQUE_ACCOUNTS' => $activity['UNIQUE_ACCOUNT_COUNT'],
							 );
							 
			//$GLOBALS['log']->debug("Array elements :".implode(",",array_keys($activity)));
		}
				
		$pdf->BasicTable($header,$entries);
				$pdf->BasicTable($header,$entries);
		$filename = "DuplicateReport_Summary".$current_user->id.date('d-m-Y-His').".pdf";
		$pdf->Output($filename,"F");
		echo "<script>window.open(\"".$sugar_config['site_url']."/".$filename."\");</script>";
	}
?>
