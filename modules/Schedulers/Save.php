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
 * $Id: Save.php,v 1.23 2006/06/06 17:58:38 majed Exp $
 * Description:  
 ********************************************************************************/
require_once('modules/Schedulers/Scheduler.php');



$focus = new Scheduler();
$focus->retrieve($_REQUEST['record']);


// deal with empty values
if(!empty($_REQUEST['date_end']) && !empty($_REQUEST['time_hour_end']) && !empty($_REQUEST['time_minute_end']) ) {
	$date_time_end = $_REQUEST['date_end']." ".str_pad($_REQUEST['time_hour_end'],2,'0',STR_PAD_LEFT).":".str_pad($_REQUEST['time_minute_end'],2,'0',STR_PAD_LEFT).$_REQUEST['time_end_meridiem'];
} else {
	$date_time_end = '';
}
if( (!empty($_REQUEST['time_hour_from']) || $_REQUEST['time_hour_from'] == '0' ) && (!empty($_REQUEST['time_minute_from']) || $_REQUEST['time_minute_from'] == '0' ) ) {
	$time_from = str_pad($_REQUEST['time_hour_from'],2,'0',STR_PAD_LEFT).":".str_pad($_REQUEST['time_minute_from'],2,'0',STR_PAD_LEFT);
	if(!empty($_REQUEST['time_from_meridiem'])) {
		$time_from .= $_REQUEST['time_from_meridiem'];
	}
} else {
	$time_from = '';
}
if( (!empty($_REQUEST['time_hour_to']) || $_REQUEST['time_hour_to'] == '0') && (!empty($_REQUEST['time_minute_to']) || $_REQUEST['time_minute_to'] == '0') ) {
	$time_to = str_pad($_REQUEST['time_hour_to'],2,'0',STR_PAD_LEFT).":".str_pad($_REQUEST['time_minute_to'],2,'0',STR_PAD_LEFT);
	if(!empty($_REQUEST['time_to_meridiem'])) {
		$time_to .= $_REQUEST['time_to_meridiem'];	
	}
} else {
	$time_to = '';
}
$date_time_start = $_REQUEST['date_start']." ".str_pad($_REQUEST['time_hour_start'],2,'0',STR_PAD_LEFT).":".str_pad($_REQUEST['time_minute_start'],2,'0',STR_PAD_LEFT);
if(!empty($_REQUEST['time_start_meridiem'])) {
	$date_time_start .= $_REQUEST['time_start_meridiem'];
}
if(empty($_REQUEST['catch_up'])) {
	$focus->catch_up = 0;
} else {
	$focus->catch_up = 1;
}

$focus->date_time_start = $date_time_start;
$focus->date_time_end = $date_time_end;
$focus->time_from = $time_from;
$focus->time_to = $time_to;
$focus->status = $_REQUEST['status'];
$focus->name = $_REQUEST['name'];

///////////////////////////////////////////////////////////////////////////////
////	USE_ADV override
if($_REQUEST['use_adv'] == 'false') {
	// days of week
	$xtDays = array(0 => 'mon',
					1 => 'tue',
					2 => 'wed',
					3 => 'thu',
					4 => 'fri',
					5 => 'sat',
					6 => 'sun');
					
	if(	(isset($_REQUEST['mon']) && $_REQUEST['mon'] == 'true') && 
		(isset($_REQUEST['tue']) && $_REQUEST['tue'] == 'true') &&
		(isset($_REQUEST['wed']) && $_REQUEST['wed'] == 'true') &&
		(isset($_REQUEST['thu']) && $_REQUEST['thu'] == 'true') &&
		(isset($_REQUEST['fri']) && $_REQUEST['fri'] == 'true') &&
		(isset($_REQUEST['sat']) && $_REQUEST['sat'] == 'true') &&
		(isset($_REQUEST['sun']) && $_REQUEST['sun'] == 'true') ) {
		$_REQUEST['day_of_week'] = '*';
	} else {
		$day_string = '';
		foreach($xtDays as $k => $day) {
			if(isset($_REQUEST[$day]) && $_REQUEST[$day] == 'true') {
				if($day_string != '') {
					$day_string .= ',';
				}
				$day_string .= $k;
			}
		}
		$_REQUEST['day_of_week'] = $day_string;
	}
		
	
	if($_REQUEST['basic_period'] == 'min') {
		$_REQUEST['mins'] = '*/'.$_REQUEST['basic_interval'];
		$_REQUEST['hours'] = '*';	
	} else {
		$_REQUEST['hours'] = '*/'.$_REQUEST['basic_interval'];
		$_REQUEST['mins'] = '0';
	} 
}

////	END USE_ADV override
///////////////////////////////////////////////////////////////////////////////
//_ppd($_REQUEST);
$focus->job_interval = $_REQUEST['mins']."::".$_REQUEST['hours']."::".$_REQUEST['day_of_month']."::".$_REQUEST['months']."::".$_REQUEST['day_of_week'];




// deal with job types
// neither 
if ( ($_REQUEST['job_function'] == '') && ($_REQUEST['job_url'] == '' || $_REQUEST['job_url'] == 'http://') ) {
	$GLOBALS['log']->fatal('Scheduler save did not get a job_url or job_function');
} elseif ( ($_REQUEST['job_function'] != '') && ($_REQUEST['job_url'] != '' && $_REQUEST['job_url'] != 'http://') ) {
	$GLOBALS['log']->fatal('Scheduler got both a job_url and job_function');
}
//function 
if ( ($_REQUEST['job_function'] != '') && ($_REQUEST['job_url'] == '' || $_REQUEST['job_url'] == 'http://') ) {
	$focus->job = $_REQUEST['job_function'];
} elseif ( ($_REQUEST['job_function'] == '') && ($_REQUEST['job_url'] != '' && $_REQUEST['job_url'] != 'http://') ) { // url
	$focus->job = 'url::'.$_REQUEST['job_url'];
} // url wins if both passed

// save should refresh ALL jobs
$focus->save();
$return_id = $focus->id;

$edit='';
if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] != "") $return_module = $_REQUEST['return_module'];
else $return_module = "Schedulers";
if(isset($_REQUEST['return_action']) && $_REQUEST['return_action'] != "") $return_action = $_REQUEST['return_action'];
else $return_action = "DetailView";
if(isset($_REQUEST['return_id']) && $_REQUEST['return_id'] != "") $return_id = $_REQUEST['return_id'];
if(!empty($_REQUEST['edit'])) {
	$return_id='';
	$edit='edit=true';
}

$GLOBALS['log']->debug("Saved record with id of ".$return_id);

header("Location: index.php?action=$return_action&module=$return_module&record=$return_id&$edit");
?>
