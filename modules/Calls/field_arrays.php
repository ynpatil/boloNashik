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
 * $Id: field_arrays.php,v 1.3 2006/06/06 17:57:56 majed Exp $
 * Description:  Contains field arrays that are used for caching
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
 $fields_array['CallRequest'] = array (
	'column_fields' => array(
		'id'
		,'name'
		,'description'
		,'parent_type'
		,'parent_id'
		,'date_entered'
		,'created_by'
		,'deleted'
	),
	'list_fields' => array(
		'id'
		,'name'
		,'parent_name'
	),
);

$fields_array['Call'] = array ('column_fields' => Array("id"
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
                , "tokan_no"
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
                ,'call_back_date'
		,'call_back_time'
		,'not_interested'
		),
        'list_fields' => Array('id', 'duration_hours', 'direction', 'status', 'name', 'parent_type', 'parent_name', 'parent_id', 'date_start', 'time_start', 'assigned_user_name', 'assigned_user_id', 'contact_name', 'contact_id','first_name','last_name','required','outlook_id','accept_status'
		),
        'required_fields' => array("name"=>1, "date_start"=>2, "time_start"=>3,),
);

$fields_array['GroupCall'] = array ('column_fields' => Array("id","call_id"
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
		),
        'list_fields' => Array('id', 'duration_hours', 'direction', 'status', 'name', 'parent_type', 'parent_name', 'parent_id', 'date_start', 'time_start', 'assigned_user_name', 'assigned_user_id', 'contact_name', 'contact_id','first_name','last_name','required','outlook_id','accept_status'
		),
        'required_fields' => array("name"=>1, "date_start"=>2, "time_start"=>3,),
);

?>
