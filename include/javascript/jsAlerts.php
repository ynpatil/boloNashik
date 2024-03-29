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

require_once("include/utils.php");
require_once("include/utils/db_utils.php");

class jsAlerts{
	var $script;

	function jsAlerts(){
		$this->script .= <<<EOQ
		if(window.addEventListener){
			window.addEventListener("load", checkAlerts, false);
		}else{
			window.attachEvent("onload", checkAlerts);
		}

EOQ;
		$this->addActivities();
		$this->addAlert('System', 'Session Timeout','', 'Your session is about to timeout in 2 minutes. Please save your work.', (session_cache_expire() - 2) * 3600 );
		$this->addAlert('System', 'Session Timeout','', 'Your session has timed out.', (session_cache_expire()) * 3600 , 'index.php');
	}
	function addAlert($type, $name, $subtitle, $description, $countdown, $redirect=''){
		$this->script .= 'addAlert("' . addslashes($type) .'", "' . addslashes($name). '","' . addslashes($subtitle). '", "'. addslashes(str_replace(array("\r", "\n"), array('','<br>'),$description)) . '",' . $countdown . ',"'.addslashes($redirect).'")' . "\n";
	}

	function getScript(){
		return "<script>" . $this->script . "</script>";
	}

	function addActivities(){
		global $app_list_strings, $timedate, $current_user;

		// cn: get a boundary limiter
		$dateTimeMax = gmdate('Y-m-d H:i:s', time() + $app_list_strings['reminder_max_time']);
		$dateTimeNow = gmdate('Y-m-d H:i:s');
		$dateMax = gmdate('Y-m-d', time() + $app_list_strings['reminder_max_time']);
		$todayGMT = gmdate('Y-m-d');

		global $db;
		// Prep Meetings Query
		if ($db->dbType == 'mysql') {
			$selectMeetings = "
				SELECT meetings.id, name,reminder_time, description,location, date_start, time_start,
					CONCAT( date_start, CONCAT(' ', time_start ) ) AS dateTime
				FROM meetings LEFT JOIN meetings_users ON meetings.id = meetings_users.meeting_id
				WHERE meetings_users.user_id ='".$current_user->id."'
					AND meetings.reminder_time != -1
					AND meetings_users.deleted != 1
				HAVING dateTime >= '".$dateTimeNow."'"; // HAVING because we're comparing against an aggregate column

			// if we're looking at bridging into the next day as
			if($dateMax == $todayGMT) {
				$selectMeetings .= " AND dateTime <= '".$dateTimeMax."'";
			}
		}

		elseif ($db->dbType == 'oci8')
		{

		}elseif($db->dbType == 'mssql')
		{
			$selectMeetings = "
				SELECT meetings.id, name,reminder_time, CAST(description AS varchar(8000)),location, date_start, time_start
				FROM meetings LEFT JOIN meetings_users ON meetings.id = meetings_users.meeting_id
				WHERE meetings_users.user_id ='".$current_user->id."'
					AND meetings.reminder_time != -1
					AND meetings_users.deleted != 1
				GROUP BY meetings.id, meetings.name, meetings.reminder_time ,CAST(meetings.description AS varchar(8000)), meetings.location, meetings.date_start, meetings.time_start
				HAVING date_start + ' ' + time_start  >= '".$dateTimeNow."'"; // HAVING because we're comparing against an aggregate column

			// if we're looking at bridging into the next day as
			if($dateMax == $todayGMT)
			{
				$selectMeetings .= " AND date_start + ' ' + time_start <= '".$dateTimeMax."'";
			}
		}

		$result = $db->query($selectMeetings);

		while($row = $db->fetchByAssoc($result)) {
			$row['time_start'] = from_db_convert($row['time_start'], 'time');
			$row['date_start'] = from_db_convert($row['date_start'], 'date');
			// need to concatenate since GMT times can bridge two local days
			$timeStart = strtotime($row['date_start']." ".$row['time_start']);
			$timeRemind = $row['reminder_time'];
			$timeStart -= $timeRemind;
            if (isset($row['description']))
			 $this->addAlert('Meeting', $row['name'], 'Time:'.$timedate->to_display_date_time($timedate->merge_date_time($row['date_start'], $row['time_start'])), 'Location:'.$row['location']. "\nDescription:".$row['description']. "\nClick OK to view this meeting or click Cancel to dismiss this message.", $timeStart - strtotime($dateTimeNow), 'index.php?action=DetailView&module=Meetings&record=' . $row['id']);
           else
             $this->addAlert('Meeting', $row['name'], 'Time:'.$timedate->to_display_date_time($timedate->merge_date_time($row['date_start'], $row['time_start'])), 'Location:'.$row['location']. "\nClick OK to view this meeting or click Cancel to dismiss this message.", $timeStart - strtotime($dateTimeNow), 'index.php?action=DetailView&module=Meetings&record=' . $row['id']);
		}

		// Prep Calls Query
		if ($db->dbType == 'mysql') {

			$selectCalls = "
				SELECT calls.id, name, reminder_time, description, date_start, time_start,
					CONCAT( date_start, CONCAT(' ', time_start) ) AS dateTime
				FROM calls LEFT JOIN calls_users ON calls.id = calls_users.call_id
				WHERE calls_users.user_id ='".$current_user->id."'
					AND calls.reminder_time != -1
					AND calls_users.deleted != 1
				HAVING dateTime >= '".$dateTimeNow."'"; // HAVING because we're comparing against an aggregate column

			if($dateMax == $todayGMT) {
				$selectCalls .= " AND dateTime <= '".$dateTimeMax."'";
			}
		}elseif ($db->dbType == 'oci8')
		{
		}elseif ($db->dbType == 'mssql')
		{
			$selectCalls = "
				SELECT calls.id, name, reminder_time, CAST(description AS varchar(8000)), date_start, time_start
				FROM calls LEFT JOIN calls_users ON calls.id = calls_users.call_id
				WHERE calls_users.user_id ='".$current_user->id."'
					AND calls.reminder_time != -1
					AND calls_users.deleted != 1
				GROUP BY calls.id, name, reminder_time, CAST(description AS varchar(8000)) , date_start, time_start
				HAVING date_start + ' ' +  time_start >= '".$dateTimeNow."'"; // HAVING because we're comparing against an aggregate column

			if($dateMax == $todayGMT) {
				$selectCalls .= " AND date_start + ' ' +  time_start <= '".$dateTimeMax."'";
			}
		}

		global $db;
		$result = $db->query($selectCalls);

		while($row = $db->fetchByAssoc($result)){
			$row['time_start'] = from_db_convert($row['time_start'], 'time');
			$row['date_start'] = from_db_convert($row['date_start'], 'date');
			// need to concatenate since GMT times can bridge two local days
			$timeStart = strtotime($row['date_start']." ".$row['time_start']);
			$timeRemind = $row['reminder_time'];
			$timeStart -= $timeRemind;
			$row['description'] = (isset($row['description'])) ? $row['description'] : '';
			$this->addAlert('Call', $row['name'], 'Time:'.$timedate->to_display_date_time($timedate->merge_date_time($row['date_start'], $row['time_start'])) , "Description:".$row['description']. "\nClick OK to view this call or click Cancel to dismiss this message." , $timeStart - strtotime($dateTimeNow), 'index.php?action=DetailView&module=Calls&record=' . $row['id']);
		}
	}
}

?>
