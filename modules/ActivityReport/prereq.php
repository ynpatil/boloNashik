<?php
//om
if(isset($_REQUEST['user']) && !empty($_REQUEST['user']))
$user = "'".$_REQUEST['user']."'";
else if(isset($_REQUEST['assigned_user_id']) && !empty($_REQUEST['assigned_user_id']))
$user = "'".$_REQUEST['assigned_user_id']."'";
else
$user = null;

//for summary
if(isset($_REQUEST['branch']) && !empty($_REQUEST['branch']))
$branch = "'".$_REQUEST['branch']."'";
else
$branch = null;

//for summary
if(isset($_REQUEST['vertical']) && !empty($_REQUEST['vertical']))
$vertical = "'".$_REQUEST['vertical']."'";
else
$vertical = null;

if (empty($_REQUEST['activity_report_filter']))
{
	if ($current_user->getPreference('activity_report_filter') == '')
	{
		$activity_report_filter = 'today';
	}
	else
	{
		$activity_report_filter = $current_user->getPreference('activity_report_filter');
	}
}
else
{
	$activity_report_filter = $_REQUEST['activity_report_filter'];
	$current_user->setPreference('activity_report_filter', $_REQUEST['activity_report_filter']);
}

if ($activity_report_filter == 'last this_month')
{
	$next_month = "01 ".strftime("%B %Y", strtotime("+1 month"));
//	$first_day = strftime("%d %B %Y", strtotime("first $next_month"));
//	echo "next_month is ".$next_month." first_day is ".$first_day;
	$appt_filter = strftime("%d %B %Y", strtotime("-1 day", strtotime($next_month)));	
}
else
{
	$appt_filter = $activity_report_filter;
}
$later = "";
if($activity_report_filter != 'custom')
{
	$later = date("Y-m-d", strtotime("$appt_filter"));
	//$later = $timedate->handle_offset($later, $timedate->dbDayFormat, true);
}

if ($activity_report_filter == 'last this_month')
{
	$first_day = strftime("%Y-%m")."-01";
}
else if ($activity_report_filter == 'this Saturday')
	$first_day = strftime("%Y-%m-%d", strtotime("last Sunday"));
else if ($activity_report_filter == 'yesterday')
	$first_day = strftime("%Y-%m-%d", strtotime("-1 day"));
else
	$first_day = $later;

$date_text = "";
$date_text_display = "";

$from_date = "";
$to_date = "";

if($activity_report_filter == 'custom')
{
	$start_date = implode("-",array_reverse(explode("-",$_REQUEST['start_date'])));
	$end_date = implode("-",array_reverse(explode("-",$_REQUEST['end_date'])));
			
	$date_text = " between '".$start_date."' and '".$end_date." 23:59:59'";
	$date_text_display = " between ".$_REQUEST['start_date']." and ".$_REQUEST['end_date'];	
	
	$from_date = $start_date;
	$to_date = $end_date;
}
else{
	$date_text = " between '".$first_day."' and '".$later." 23:59:59'";
	$date_text_display = " between ".implode("-",array_reverse(explode("-",$first_day)))." and ".implode("-",array_reverse(explode("-",$later)));
	
	$from_date = $first_day;
	$to_date = $later;
}
	
//echo "Date text :".$date_text;
?>