<?php
/*
***** SugarTime *****
Developed by Paul K. Lynch, Everyday Interactive Networks (ein.com.au)
Mozilla Public License v1.1
*/

// Yes, I know.  This file is a mess.  I'll clean it up some day.

require_once('XTemplate/xtpl.php');
require_once("data/Tracker.php");
require_once('modules/sugartime/sugartime.php');
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/ListView/ListView.php');
require_once('include/TimeDate.php');

global $app_strings;
global $app_list_strings;
global $current_language;
$current_module_strings = return_module_language($current_language, 'sugartime');

$timedate = new TimeDate();

global $urlPrefix;

global $currentModule;

global $theme;
global $timedate; 

$header_text = "";
$where = "";
 
$seedTime =& new sugartime();

$userformat_date = str_replace("/", "-", $timedate->get_user_date_format());
$userformat_time = $timedate->get_user_time_format();

function cat_time($start, $end) {	
	$dif = $end - $start;
    $hours = floor($dif / 3600);
    $temp_remainder = $dif - ($hours * 3600);
    $minutes = floor($temp_remainder / 60);
    $temp_remainder = $temp_remainder - ($minutes * 60);
    $seconds = $temp_remainder;
    $min_lead=':';
    if($minutes <=9) {
		$min_lead .= '0';
    	$sec_lead=':';
	}
	if ($hours < 0) { $hours = 0; }
	if ($minutes < 0) { $minutes = 0; }
	
	return $hours.$min_lead.$minutes;
}

function dec_time($time) {
	$timepart = explode(":", $time);
	$dec = round(($timepart[1] / 60) * 100);
	$dec = str_pad($dec, 2, "0", STR_PAD_LEFT);
	return $timepart[0].".".$dec;
}

if(isset($_REQUEST['button']) && $_REQUEST['button'] == "Save") {
	// Save button clicked
	
	$newtime = new sugartime();
	
	if ($_REQUEST['r_id'] == "0") {
		//ADD
		$newtime->id = "";
	}
	elseif(isset($_REQUEST['r_id']) && trim($_REQUEST['r_id']) != "") {
		//EDIT
		$newtime->id = $_REQUEST['r_id'];

	}
	
	// Why did I put this here?
	// I want to move most of this junk into the sugartime class
	$now_date = $timedate->to_display_date(date('Y-m-d'), false);
	$now_time = $timedate->to_display_time(date('H-i-s'), false);
	
	// Clean up submitted data
	$_REQUEST['rdate'] = empty($_REQUEST['rdate']) ? $now_date : $_REQUEST['rdate'];
	if (strlen($_REQUEST['time_start']) < 3) {	$_REQUEST['time_start'] = $_REQUEST['time_start'].":00"; }
	if (strlen($_REQUEST['time_finish']) < 3) {	$_REQUEST['time_finish'] = $_REQUEST['time_finish'].":00"; }
	$_REQUEST['time_start'] = str_replace(".", ":", $_REQUEST['time_start']);
	$_REQUEST['time_finish'] = str_replace(".", ":", $_REQUEST['time_finish']);
	$_REQUEST['downtime_h'] = empty($_REQUEST['downtime_h']) ? 0 : $_REQUEST['downtime_h'];
	$_REQUEST['downtime_m'] = empty($_REQUEST['downtime_m']) ? "00" : $_REQUEST['downtime_m'];
	if (strlen($_REQUEST['downtime_m']) < 2) {	$_REQUEST['downtime_m'] = "0".$_REQUEST['downtime_m']; }
	if (substr($_REQUEST['time_start'],0,2) > 12 && (substr_count($userformat_time, "pm") == 1 || substr_count($userformat_time, "PM") == 1)) {
		$timepart = explode(":", $_REQUEST['time_start']);
		$new = $timepart[0] - 12;
		$_REQUEST['time_start'] = $new.":".$timepart[1];
	}
	if (substr($_REQUEST['time_finish'],0,2) > 12 && (substr_count($userformat_time, "pm") == 1 || substr_count($userformat_time, "PM") == 1)) {
		$timepart = explode(":", $_REQUEST['time_finish']);
		$new = $timepart[0] - 12;
		$_REQUEST['time_finish'] = $new.":".$timepart[1];
	}
	
	$newtime->assigned_user_id = $_REQUEST['r_userid'];
	if (substr_count($timedate->get_user_date_format(), '/') == 2) {
		$_REQUEST['rdate'] = str_replace("-", "/", $_REQUEST['rdate']);
	}
	$newtime->rdate = $_REQUEST['rdate'];
	$newtime->start_time = $_REQUEST['time_start'].$_REQUEST['start_meridiem'];
	$newtime->finish_time = $_REQUEST['time_finish'].$_REQUEST['finish_meridiem'];
	$newtime->downtime = $_REQUEST['downtime_h'].":".$_REQUEST['downtime_m'];
	
	$math_date = explode("-", $timedate->to_db_date($newtime->rdate, false));
	$math_stime = explode(":", $timedate->to_db_time($newtime->start_time, false));
	$math_ftime = explode(":", $timedate->to_db_time($newtime->finish_time, false));
	
	$math_sstamp = mktime($math_stime[0], $math_stime[1], $math_stime[2], $math_date[1], $math_date[2], $math_date[0]);
	$math_fstamp = mktime($math_ftime[0] - $_REQUEST['downtime_h'], $math_ftime[1] - $_REQUEST['downtime_m'], $math_ftime[2], $math_date[1], $math_date[2], $math_date[0]);
	$math_dstamp = mktime($math_ftime[0] - $_REQUEST['downtime_h'] - 8, $math_ftime[1] - $_REQUEST['downtime_m'], $math_ftime[2], $math_date[1], $math_date[2], $math_date[0]);
	
	$newtime->downtime = $_REQUEST['downtime_h'].":".$_REQUEST['downtime_m'];
	
	$newtime->ntotal = "0:00"; // Normal toal, needs to be worked out
	$newtime->total = cat_time($math_sstamp, $math_fstamp);
	$newtime->total_hours = dec_time($newtime->total);
	
	if ($newtime->total_hours > 8) {
		$newtime->overtime = cat_time($math_sstamp, $math_dstamp);
		$newtime->overtime_hours = dec_time($newtime->overtime);
	}
	else {
		$newtime->overtime = "0:00";
		$newtime->overtime_hours = 0;
	}
	
	$newtime->date_modified = $now_date." ".$now_time;
	$newtime->deleted = 0;
	
	// Save record
  	$newtime->save_me();
	
	// reset data
	$_REQUEST['r_id'] = "0";
	unset($_POST['button']);
}


if (isset($_REQUEST['delete']) && isset($_REQUEST['r_id'])) {
	// Deletes this sucker
	$deletetime = new sugartime();
	$deletetime->id = $_REQUEST['r_id'];
	$deletetime->delete_me();
}


if(isset($_REQUEST['query']))
{
	// we have a query
	if (isset($_REQUEST['user'])) $user = $_REQUEST['user'];
	if (isset($_REQUEST['date_from'])) {
		if (substr_count($timedate->get_user_date_format(), '/') == 2) {
			$_REQUEST['date_from'] = str_replace("-", "/", $_REQUEST['date_from']);
		}
		$date_from = $timedate->to_db_date($_REQUEST['date_from'], false);
	}
	if (isset($_REQUEST['date_to'])) {
		if (substr_count($timedate->get_user_date_format(), '/') == 2) {
			$_REQUEST['date_to'] = str_replace("-", "/", $_REQUEST['date_to']);
		}
		$date_to = $timedate->to_db_date($_REQUEST['date_to'], false);
	}

	$where_clauses = Array();

	if(isset($user) && $user != "") array_push($where_clauses, "users.user_name like '".PearDatabase::quote($user)."%'");
	if(isset($date_from) && $date_from != "" && isset($date_to) && $date_to != "") 
		array_push($where_clauses, "sugartime.rdate >= '".PearDatabase::quote($date_from)."' AND sugartime.rdate <= '".PearDatabase::quote($date_to)."'");

	//$seedTime->custom_fields->setWhereClauses($where_clauses);

	$where = "";
	foreach($where_clauses as $clause)
	{
		if($where != "")
		$where .= " and ";
		$where .= $clause;
	}

	$GLOBALS['log']->info("Here is the where clause for the list view: $where");
}

// *** List Filter table ***
echo "\n<p>\n";
echo get_form_header($current_module_strings['LBL_FILTER_TABLE'], $header_text, false);

$filtertable=new XTemplate ('modules/sugartime/temp_filter.html');
$filtertable->assign("MOD", $current_module_strings);
$filtertable->assign("APP", $app_strings);

if (isset($_REQUEST['date_from'])) $filtertable->assign("DATE_FROM", $_REQUEST['date_from']);
if (isset($_REQUEST['date_to'])) $filtertable->assign("DATE_TO", $_REQUEST['date_to']);
if (isset($_REQUEST['user'])) $filtertable->assign("USER", $_REQUEST['user']);

$filtertable->assign("CALENDAR_LANG", "en");
$filtertable->assign("CALENDAR_DATEFORMAT", parse_calendardate("(".$userformat_date.")"));

$filtertable->assign("THEME", $theme);
$filtertable->assign("IMAGE_PATH", $image_path);
$filtertable->assign("JAVASCRIPT", get_clear_form_js());

if(isset($total) && $total != "") $filtertable->assign("TOTAL", $total);

$filtertable->parse("main");
$filtertable->out("main");

echo "\n</p>\n";

// *** Edit/Add table ***
echo "\n<p>\n";
echo get_form_header($current_module_strings['LBL_EDIT_TABLE'], $header_text, false);

$edittable=new XTemplate ('modules/sugartime/temp_editform.html');
$edittable->assign("MOD", $current_module_strings);
$edittable->assign("APP", $app_strings);

if (isset($_REQUEST['r_stime'])) {
	$edittable->assign("TIME_START", substr($_REQUEST['r_stime'],0,5));
	$stime = $_REQUEST['r_stime'];
}
else {
	$stime = '00:00:00';
}
if (isset($_REQUEST['r_ftime'])) {
	$edittable->assign("TIME_FINISH", substr($_REQUEST['r_ftime'],0,5));
	$ftime = $_REQUEST['r_ftime'];
}
else {
	$ftime = $timedate->to_display_time('13:00:00', true, false);
}


if(!isset($_REQUEST['r_username']) || (isset($_REQUEST['button']) && $_REQUEST['button'] == "Save")) {
	$edittable->assign("USER", $current_user->user_name);
	$edittable->assign("USERID", $current_user->id);
}
else {
	$edittable->assign("USER", $_REQUEST['r_username']);
	$edittable->assign("USERID", $_REQUEST['r_userid']);
}


$edittable->assign("START_TIME_MERIDIEM", $timedate->AMPMMenu('start_', $stime));
$edittable->assign("FINISH_TIME_MERIDIEM", $timedate->AMPMMenu('finish_', $ftime));
$edittable->assign("TIME_FORMAT", '('. $timedate->get_user_time_format().')');
$edittable->assign("DATE_FORMAT", '('. $timedate->get_user_date_format().')');
$edittable->assign("CALENDAR_LANG", "en");
$edittable->assign("CALENDAR_DATEFORMAT", parse_calendardate("(".$userformat_date.")"));


$edittable->assign("THEME", $theme);
$edittable->assign("IMAGE_PATH", $image_path);
$edittable->assign("JAVASCRIPT", get_clear_form_js().get_validate_record_js());

if (isset($_REQUEST['r_id'])) { $edittable->assign("RID", $_REQUEST['r_id']); }
else { $edittable->assign("RID", 0); }
if (isset($_REQUEST['r_date'])) $edittable->assign("RDATE", $_REQUEST['r_date']);
if (isset($_REQUEST['r_down'])) {
	$dt = explode(":", $_REQUEST['r_down']);
	$edittable->assign("DOWNTIMEH", $dt[0]);
	$edittable->assign("DOWNTIMEM", $dt[1]);
}

$edittable->parse("main");
$edittable->out("main");

// *** List table ***

$ListView = new ListView();
$ListView->initNewXTemplate( 'modules/sugartime/temp_listtimes.html',$current_module_strings);
$ListView->setHeaderTitle($current_module_strings['LBL_LIST_TABLE'] );
global $current_user;


$ListView->setQuery($where, "", "", "TIME");
$ListView->processListView($seedTime, "main", "TIME");

echo "\n</p>\n";

?>