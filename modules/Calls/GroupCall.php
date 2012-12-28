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
 * $Id: Call.php,v 1.165 2006/08/09 19:28:41 jenny Exp $
 * Description:  
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('data/SugarBean.php');
require_once('modules/Contacts/Contact.php');
require_once('modules/Users/User.php');
require_once('modules/Calls/Call.php');

// Call is used to store customer information.
class GroupCall extends Call {
	var $field_name_map;
	// Stored fields
	var $id;
	var $call_id;
	var $date_entered;
	var $date_modified;
	var $assigned_user_id;
	var $modified_user_id;
	var $description;
	var $outcome;
	var $name;
	var $status;
	var $date_start;
	var $time_start;
	var $duration_hours;
	var $duration_minutes;
	var $date_end;
	var $parent_type;
	var $parent_id;
	var $campaign_id;
	var $campaign_name;
	var $contact_id;
	var $user_id;
	var $direction;
	var $reminder_time;
	var $required;
	var $accept_status;
	var $created_by;
	var $created_by_name;
	var $modified_by_name;
	var $parent_name;
	var $contact_name;
	var $contact_phone;
	var $contact_email;
	var $account_id;
	var $opportunity_id;
	var $case_id;
	var $assigned_user_name;
	var $note_id;
    var $outlook_id;
	var $update_vcal = true;
	var $contacts_arr;
	var $users_arr;
	var $minutes_value_default = 15;
	var $table_name = "group_calls";
	var $module_dir = 'Calls';
	var $object_name = "GroupCall";
	var $new_schema = true;
/*
	var $column_fields = array("id","call_id"
		, "date_entered"
		, "date_modified"
		, "assigned_user_id"
		, "modified_user_id"
		, "created_by"
		, "description"
		, "outcome"
		, "status"
		, "direction"
		, "name"
		, "date_start"
		, "time_start"
		, "duration_hours"
		, "duration_minutes"
		, "date_end"
		, "parent_type"
		, "parent_id"
		, "campaign_id"		
		,'reminder_time'
		,'outlook_id'
		);
*/										
	function GroupCall() {
		parent::SugarBean();
	}

	function copy($call)
	{
//		print("No of column fields :".count($call->column_fields));

		foreach($call->column_fields as $field)
		{
			$this->$field = $call->$field;
		}

		$this->call_id = $call->id;
		unset($this->id);
//		$this->type = 0;
	}

	function setAssignedUser($assigned_user_id)
	{
		$this->assigned_user_id = $assigned_user_id;
	}	
}
?>
