<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * Popup Picker
 *
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
 */

// $Id: Popup_picker.php,v 1.17 2006/08/25 01:25:45 chris Exp $

require_once('XTemplate/xtpl.php');
require_once("data/Tracker.php");
require_once("include/upload_file.php");
require_once('include/modules.php');
require_once('include/utils/db_utils.php');

global $currentModule;

global $focus;
global $action;

global $app_strings;
global $app_list_strings;
//we don't want the parent module's string file, but rather the string file specifc to this subpanel
global $current_language;
$current_module_strings = return_module_language($current_language, 'Activities');
	
// history_list is the means of passing data to a SubPanelView.
$bean = $beanList[$_REQUEST['module_name']];
require_once($beanFiles[$bean]);
$focus = new $bean;

class Popup_Picker
{
	
	
	/**
	 * sole constructor
	 */
	function Popup_Picker() {
	}
	
	/**
	 *
	 */
	function process_page() {
		global $theme;
		global $focus;
		global $mod_strings;
		global $app_strings;
		global $app_list_strings;
		global $currentModule;
		global $odd_bg;
 		global $even_bg;
 		global $image_path;
 		
 		$theme_path = "themes/".$theme."/";
 		$image_path = 'themes/'.$theme.'/images/';
 		require_once($theme_path.'layout_utils.php');

		$history_list = array();
		
		if(!empty($_REQUEST['record'])) {
   			$result = $focus->retrieve($_REQUEST['record']);
    		if($result == null)
    		{
    			sugar_die($app_strings['ERROR_NO_RECORD']);
    		}
		}

        if($focus->object_name  == "ProjectTask" || $focus->object_name  == "Project") {
            $focus_tasks_list = array();
        } else {
		    $focus_tasks_list = $focus->get_linked_beans('tasks','Task');
        }
        
		$focus_meetings_list = $focus->get_linked_beans('meetings','Meeting');
		$focus_calls_list = $focus->get_linked_beans('calls','Call');
		$focus_emails_list = $focus->get_linked_beans('emails','Email');
		$focus_notes_list = $focus->get_linked_beans('notes','Note');
		
		foreach ($focus_tasks_list as $task) {
			if ($task->date_due == '0000-00-00')
				$date_due = '';
			else
				$date_due = $task->date_due;

			if ($task->status != "Not Started" && $task->status != "In Progress" && $task->status != "Pending Input") {
				$history_list[] = array('name' => $task->name,
									 'id' => $task->id,
									 'type' => "Task",
									 'direction' => '',
									 'module' => "Tasks",
									 'status' => $task->status,
									 'parent_id' => $task->parent_id,
									 'parent_type' => $task->parent_type,
									 'parent_name' => $task->parent_name,
									 'contact_id' => $task->contact_id,
									 'contact_name' => $task->contact_name,
									 'date_modified' => $date_due,
									 'description' => $this->getTaskDetails($task),
									 'date_type' => 'Due:'
									 );
			} else {
				$open_activity_list[] = array('name' => $task->name,
									 'id' => $task->id,
									 'type' => "Task",
									 'direction' => '',
									 'module' => "Tasks",
									 'status' => $task->status,
									 'parent_id' => $task->parent_id,
									 'parent_type' => $task->parent_type,
									 'parent_name' => $task->parent_name,
									 'contact_id' => $task->contact_id,
									 'contact_name' => $task->contact_name,
									 'date_due' => $date_due,
									 'description' => $this->getTaskDetails($task),
									 'date_type' => 'Due:'
									 );
			}
		} // end Tasks

		foreach ($focus_meetings_list as $meeting) {
			if ($meeting->status != "Planned") {
				$history_list[] = array('name' => $meeting->name,
									 'id' => $meeting->id,
									 'type' => "Meeting",
									 'direction' => '',
									 'module' => "Meetings",
									 'status' => $meeting->status,
									 'parent_id' => $meeting->parent_id,
									 'parent_type' => $meeting->parent_type,
									 'parent_name' => $meeting->parent_name,
									 'contact_id' => $meeting->contact_id,
									 'contact_name' => $meeting->contact_name,
									 'date_modified' => $meeting->date_start,
									 'description' => $this->formatDescription($meeting->description),
									 'date_type' => 'Start:'
									 );
			} else {
				$open_activity_list[] = array('name' => $meeting->name,
									 'id' => $meeting->id,
									 'type' => "Meeting",
									 'direction' => '',
									 'module' => "Meetings",
									 'status' => $meeting->status,
									 'parent_id' => $meeting->parent_id,
									 'parent_type' => $meeting->parent_type,
									 'parent_name' => $meeting->parent_name,
									 'contact_id' => $meeting->contact_id,
									 'contact_name' => $meeting->contact_name,
									 'date_due' => $meeting->date_start,
									 'description' => $this->formatDescription($meeting->description),
									 'date_type' => 'Start:'
									 );
			}
		} // end Meetings

		foreach ($focus_calls_list as $call) {
			if ($call->status != "Planned") {
				$history_list[] = array('name' => $call->name,
									 'id' => $call->id,
									 'type' => "Call",
									 'direction' => $call->direction,
									 'module' => "Calls",
									 'status' => $call->status,
									 'parent_id' => $call->parent_id,
									 'parent_type' => $call->parent_type,
									 'parent_name' => $call->parent_name,
									 'contact_id' => $call->contact_id,
									 'contact_name' => $call->contact_name,
									 'date_modified' => $call->date_start,
									 'description' => $this->formatDescription($call->description),
									 'date_type' => 'Start:'
									 );
			} else {
				$open_activity_list[] = array('name' => $call->name,
									 'id' => $call->id,
									 'direction' => $call->direction,
									 'type' => "Call",
									 'module' => "Calls",
									 'status' => $call->status,
									 'parent_id' => $call->parent_id,
									 'parent_type' => $call->parent_type,
									 'parent_name' => $call->parent_name,
									 'contact_id' => $call->contact_id,
									 'contact_name' => $call->contact_name,
									 'date_due' => $call->date_start,
									 'description' => $this->formatDescription($call->description),
									 'date_type' => 'Start:'
									 );
			}
		} // end Calls

		foreach ($focus_emails_list as $email) {
			$history_list[] = array('name' => $email->name,
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
									 'date_modified' => $email->date_start." ".$email->time_start,
									 'description' => $this->getEmailDetails($email),
									 'date_type' => 'Sent:'
									 );
		} //end Emails

		foreach ($focus_notes_list as $note) {
			$history_list[] = array('name' => $note->name,
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
									 'date_modified' => $note->date_modified,
									 'description' => $this->formatDescription($note->description),
									 'date_type' => 'Modified:'
									 );
			if(!empty($note->filename)) {
				$count = count($history_list);
				$count--;
				$history_list[$count]['filename'] = $note->filename;
				$history_list[$count]['fileurl'] = UploadFile::get_url($note->filename,$note->id);
			}
		} // end Notes

		$xtpl=new XTemplate ('modules/Activities/Popup_picker.html');
		$xtpl->assign('THEME', $theme);
		$xtpl->assign('MOD', $mod_strings);
		$xtpl->assign('APP', $app_strings);
		insert_popup_header($theme);

		//output header
		echo "<table width='100%' cellpadding='0' cellspacing='0'><tr><td>";
		echo get_module_title($focus->module_dir, $focus->module_dir.": ".$focus->name, false);
		echo "</td><td align='right' class='moduleTitle'>";
		echo "<A href='javascript:print();' class='utilsLink'><img src='".$image_path."print.gif' width='13' height='13' alt='".$app_strings['LNK_PRINT']."' border='0' align='absmiddle'></a>&nbsp;<A href='javascript:print();' class='utilsLink'>".$app_strings['LNK_PRINT']."</A>\n";
		echo "</td></tr></table>";

		$oddRow = true;
		if (count($history_list) > 0) $history_list = array_csort($history_list, 'date_modified', SORT_DESC);
		foreach($history_list as $activity)
		{
			$activity_fields = array(
				'ID' => $activity['id'],
				'NAME' => $activity['name'],
				'MODULE' => $activity['module'],
				'CONTACT_NAME' => $activity['contact_name'],
				'CONTACT_ID' => $activity['contact_id'],
				'PARENT_TYPE' => $activity['parent_type'],
				'PARENT_NAME' => $activity['parent_name'],
				'PARENT_ID' => $activity['parent_id'],
				'DATE' => $activity['date_modified'],
				'DESCRIPTION' => $activity['description'],
				'DATE_TYPE' => $activity['date_type']
			);
			if (empty($activity['direction'])) {
				$activity_fields['TYPE'] = $app_list_strings['activity_dom'][$activity['type']];
			}
			else {
				$activity_fields['TYPE'] = $app_list_strings['call_direction_dom'][$activity['direction']].' '.$app_list_strings['activity_dom'][$activity['type']];
			}

			switch ($activity['type']) {
				case 'Call':
					$activity_fields['STATUS'] = $app_list_strings['call_status_dom'][$activity['status']];
					break;
				case 'Meeting':
					$activity_fields['STATUS'] = $app_list_strings['meeting_status_dom'][$activity['status']];
					break;
				case 'Task':
					$activity_fields['STATUS'] = $app_list_strings['task_status_dom'][$activity['status']];
					break;
			}

			if (isset($activity['location'])) $activity_fields['LOCATION'] = $activity['location'];
			if (isset($activity['filename'])) {
				$activity_fields['ATTACHMENT'] = "<a href='".$activity['fileurl']."' target='_blank'>".get_image($image_path."attachment","alt='".$activity['filename']."' border='0' align='absmiddle'")."</a>";
   			}

			if (isset($activity['parent_type'])) $activity_fields['PARENT_MODULE'] = $activity['parent_type'];

			$xtpl->assign("ACTIVITY", $activity_fields);
			$xtpl->assign("ACTIVITY_MODULE_PNG", get_image($image_path.$activity_fields['MODULE'].'','border="0" alt="'.$activity_fields['NAME'].'"'));

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

			$xtpl->parse("history.row");
		// Put the rows in.
}

		$xtpl->parse("history");
		$xtpl->out("history");
		insert_popup_footer();
	}
	
	function getEmailDetails($email){
		$details = "";

		if(!empty($email->to_addrs)){
			$details .= "To: ".$email->to_addrs."<br>";
		}
		if(!empty($email->from_addr)){
			$details .= "From: ".$email->from_addr."<br>";	
		}
		if(!empty($email->cc_addrs)){
			$details .= "CC: ".$email->cc_addrs."<br>";	
		}
		if(!empty($email->from_addr) || !empty($email->cc_addrs) || !empty($email->to_addrs)){
			$details .= "<br>";
		}
		
		// cn: bug 8433 - history does not distinguish b/t text/html emails
		$details .= empty($email->description_html) 
			? $this->formatDescription($email->description) 
			: $this->formatDescription(strip_tags(br2nl(from_html($email->description_html))));
		
		return $details;
	}
	
	function getTaskDetails($task){
		
		$details = "";
		if($task->date_start != '0000-00-00'){
			$details .= "Start: ".$task->date_start."<br>";
		}
		if(($task->date_start != '0000-00-00')){
			$details .= "<br>";	
		}
		$details .= $this->formatDescription($task->description);
		
		return $details;
	}
	
	function formatDescription($description){
		return nl2br($description);
	}
} // end of class Popup_Picker
?>
