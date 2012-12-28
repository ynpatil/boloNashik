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
 * $Id: Menu.php,v 1.8 2005/02/09 07:08:54 andrew Exp $
 * Description:  TODO To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

global $mod_strings, $app_strings;
$module_menu = Array(
        Array("index.php?module=Calls&action=EditView&return_module=Calls&return_action=DetailView", $mod_strings['LNK_NEW_CALL'],"CreateCalls"),
        Array("index.php?module=Meetings&action=EditView&return_module=Meetings&return_action=DetailView", $mod_strings['LNK_NEW_MEETING'],"CreateMeetings"),
        Array("index.php?module=Tasks&action=EditView&return_module=Tasks&return_action=DetailView", $mod_strings['LNK_NEW_TASK'],"CreateTasks"),
        Array("index.php?module=Notes&action=EditView&return_module=Notes&return_action=DetailView", $mod_strings['LNK_NEW_NOTE'],"CreateNotes"),
        Array("index.php?module=Emails&action=EditView&return_module=Emails&return_action=DetailView", $mod_strings['LNK_NEW_EMAIL'],"CreateEmails"),
        Array("index.php?module=Calls&action=index&return_module=Calls&return_action=DetailView", $mod_strings['LNK_CALL_LIST'],"Calls"),
        Array("index.php?module=Meetings&action=index&return_module=Meetings&return_action=DetailView", $mod_strings['LNK_MEETING_LIST'],"Meetings"),
        Array("index.php?module=Tasks&action=index&return_module=Tasks&return_action=DetailView", $mod_strings['LNK_TASK_LIST'],"Tasks"),
        Array("index.php?module=Notes&action=index&return_module=Notes&return_action=DetailView", $mod_strings['LNK_NOTE_LIST'],"Notes"),
        Array("index.php?module=Emails&action=index&return_module=Emails&return_action=DetailView", $mod_strings['LNK_EMAIL_LIST'],"Emails"),
	Array("index.php?module=Comments&action=index&return_module=Comments&return_action=DetailView", $app_strings['LNK_COMMENT_LIST'],"Comments"),
        Array("index.php?module=Calendar&action=index&view=day", $mod_strings['LNK_VIEW_CALENDAR'],"Calendar"),
 		Array("index.php?module=Notes&action=Import&step=1&return_module=Notes&return_action=index", $mod_strings['LNK_IMPORT_NOTES'],"Import"),
		Array("index.php?module=ActivityReport&action=index",$app_strings['LNK_ACTIVITY_REPORT'],"ActivityReport"),
       );

global $current_user;
if(is_admin($current_user) || is_supersenior($current_user)){
	$module_menu[] = Array("index.php?module=ActivityReport&action=summary&new=true",$app_strings['LNK_ACTIVITY_REPORT_SUMMARY'],"ActivityReport");
        $module_menu[] = Array("index.php?module=ActivityReport&action=movementRegister&new=true",$app_strings['LNK_MOVEMENT_REGISTER'],"ActivityReport");
        $module_menu[] = Array("index.php?module=ActivityReport&action=movementRegisterSummary&new=true",$app_strings['LNK_MOVEMENT_REGISTER_SUMMARY'],"ActivityReport");
        $module_menu[] = Array("index.php?module=ActivityReport&action=duplicate&new=true",$app_strings['LNK_DUPLICATE_SUMMARY'],"ActivityReport");
}

?>
