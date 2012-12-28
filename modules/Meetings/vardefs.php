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
//om
$dictionary['MeetingRequest'] = array ( 'table' => 'meetings_requests',
'audited'=>true, 'unified_search' => true, 'duplicate_merge'=>true,
                                    'fields' => array (
  'id' =>
  array (
    'name' => 'id',
    'vname' => 'LBL_USER_ID',
    'type' => 'id',
    'required'=>true,
  ),
  'deleted' =>
  array (
    'name' => 'deleted',
    'vname' => 'LBL_DELETED',
    'type' => 'bool',
    'required'=>true,
    'reportable'=>false,
  ),  
  'date_entered' =>
  array (
    'name' => 'date_entered',
    'vname' => 'LBL_DATE_ENTERED',
    'type' => 'datetime',
    'required'=>true,
  ),
  'created_by' =>
  array (
    'name' => 'created_by',
    'rname' => 'user_name',
    'id_name' => 'modified_user_id',
    'vname' => 'LBL_ASSIGNED_TO',
    'type' => 'assigned_user_name',
    'table' => 'users',
    'isnull' => 'false',
    'dbType' => 'id'
  ),  
   'name' =>
  array (
    'name' => 'name',
    'type' => 'name',
    'dbType' => 'varchar',
    'vname' => 'LBL_NAME',
    'len' => 150,
    'comment' => 'Subject',
    'unified_search' => true,
    'audited'=>false,
	'ucformat' => true,
  ),  
   'description' =>
  array (
    'name' => 'description',
    'vname' => 'LBL_DESCRIPTION',
    'type' => 'text',
    'comment' => 'Descriptive information about the account',
  ),
  'parent_type' =>
  array (
    'name' => 'parent_type',
    'type' => 'varchar',
    'len' => '25',
    'reportable'=>false,
  ),
  'parent_id' =>
  array (
    'name' => 'parent_id',
    'type' => 'id',
    'reportable'=>false,
  ),
  'location' =>
  array (
    'name' => 'location',
    'vname' => 'LBL_LOCATION',
    'type' => 'varchar',
    'len' => '50',
    'comment' => 'Meeting location'
  ), 
),
);
 
$dictionary['Meeting'] = array('table' => 'meetings', 'comment' => 'Meeting activities'
                               ,'fields' => array (
  'id' =>
  array (
    'name' => 'id',
    'vname' => 'LBL_NAME',
    'type' => 'id',
    'required'=>true,
    'reportable'=>false,
    'comment' => 'Unique identifier'
  ),
   'date_entered' =>
  array (
    'name' => 'date_entered',
    'vname' => 'LBL_DATE_ENTERED',
    'type' => 'datetime',
    'required'=>true,
    'comment' => 'Date record created'
  ),
  'date_modified' =>
  array (
    'name' => 'date_modified',
    'vname' => 'LBL_DATE_MODIFIED',
    'type' => 'datetime',
     'required'=>true,
     'comment' => 'Date record last modified'
  ),
  'assigned_user_id' =>
  array (
    'name' => 'assigned_user_id',
    'rname' => 'user_name',
    'id_name' => 'assigned_user_id',
    'vname' => 'LBL_ASSIGNED_TO',
    'type' => 'assigned_user_name',
    'table' => 'users',
    'isnull' => 'false',
    'dbType' => 'id',
    'comment' => 'User assigned to this record'
  ),
  'assigned_user_name' =>
  array (
    'name' => 'assigned_user_name',
    'vname' => 'LBL_ASSIGNED_TO',
    'type' => 'varchar',
    'reportable'=>false,
    'source'=>'nondb',
    'table' => 'users',
  ),
  'modified_user_id' =>
  array (
    'name' => 'modified_user_id',
    'rname' => 'user_name',
    'id_name' => 'modified_user_id',
    'vname' => 'LBL_MODIFIED_BY',
    'type' => 'assigned_user_name',
    'table' => 'users',
    'isnull' => 'false',
    'dbType' => 'id',
    'comment' => 'User who last modified record'
  ),
  'created_by' =>
  array (
    'name' => 'created_by',
    'rname' => 'user_name',
    'id_name' => 'modified_user_id',
    'vname' => 'LBL_CREATED_BY',
    'type' => 'assigned_user_name',
    'table' => 'users',
    'isnull' => 'false',
    'dbType' => 'id',
    'comment' => 'User who created record'
  ),
  'name' =>
  array (
    'name' => 'name',
    'vname' => 'LBL_SUBJECT',
    'type' => 'name',
    'dbType' => 'varchar',
    'len' => '50',
    'comment' => 'Meeting name',
    'ucformat' => true,    
  ),
  'accept_status' => array (
    'name' => 'accept_status',
    'vname' => 'LBL_SUBJECT',
    'type' => 'varchar',
    'dbType' => 'varchar',
    'len' => '20',
    'source'=>'non-db',
  ),
  'location' =>
  array (
    'name' => 'location',
    'vname' => 'LBL_LOCATION',
    'type' => 'varchar',
    'len' => '50',
    'comment' => 'Meeting location'
  ),
  'duration_hours' =>
  array (
    'name' => 'duration_hours',
    'vname' => 'LBL_DURATION_HOURS',
    'type' => 'int',
    'len' => '2',
    'comment' => 'Duration (hours)'
  ),/*
  'time_hour_exit' =>
  array (
    'name' => 'time_hour_exit',
    'vname' => 'LBL_DATE_EXIT',
    'type' => 'int',
    'len' => '2',
    'comment' => 'Exit Time(hours)'
  ),
  'time_minute_exit' =>
  array (
    'name' => 'time_minute_exit',
    'vname' => 'LBL_DATE_EXIT',
    'type' => 'int',
    'len' => '2',
    'comment' => 'Exit Time(minutes)'
  ),
  'time_hour_in' =>
  array (
    'name' => 'time_hour_in',
    'vname' => 'LBL_DATE_IN',
    'type' => 'int',
    'len' => '2',
    'comment' => 'In Time(hours)'
  ),
  'time_minute_in' =>
  array (
    'name' => 'time_minute_in',
    'vname' => 'LBL_DATE_IN',
    'type' => 'int',
    'len' => '2',
    'comment' => 'In Time(minutes)'
  ),*/
'duration_minutes' =>
  array (
    'name' => 'duration_minutes',
    'vname' => 'LBL_DURATION_MINUTES',
    'type' => 'int',
    'len' => '2',
    'comment' => 'Duration (minutes)'
  ),
  'date_start' =>
  array (
    'name' => 'date_start',
    'vname' => 'LBL_DATE',
    'type' => 'date',
    'rel_field' => 'time_start',
    'comment' => 'Date of start of meeting'
  ),
  'time_start' =>
  array (
    'name' => 'time_start',
    'vname' => 'LBL_TIME',
    'type' => 'time',
    'rel_field' => 'date_start',
    'comment' => 'Time of start of meeting'
  ),
  'date_end' =>
  array (
    'name' => 'date_end',
    'vname' => 'LBL_DATE_END',
    'type' => 'date',
    'massupdate'=>false,
    'comment' => 'Date meeting ends'
  ),
 'time_exit' =>
  array (
    'name' => 'time_exit',
    'vname' => 'LBL_DATE_EXIT',
    'type' => 'time',
    'massupdate'=>false,
    'comment' => 'Exit Time'
  ),
  'time_in' =>
  array (
    'name' => 'time_in',
    'vname' => 'LBL_DATE_IN',
    'type' => 'time',
    'massupdate'=>false,
    'comment' => 'In Time'
  ),
 'parent_type' =>
  array (
    'name' => 'parent_type',
    'vname'=>'LBL_LIST_RELATED_TO',
    'type' => 'varchar',
    'len' => '25',
    'reportable'=>false,
    'comment' => 'Module meeting is associated with'
  ),
  'status' =>
  array (
    'name' => 'status',
    'vname' => 'LBL_STATUS',
    'type' => 'enum',
    'len' => '25',
    'options' => 'call_status_dom',
    'comment' => 'Meeting status (ex: Planned, Held, Not held)'
  ),
  'parent_id' =>
  array (
    'name' => 'parent_id',
    'vname'=>'LBL_LIST_RELATED_TO',
    'type' => 'id',
    'reportable'=>false,
    'comment' => 'ID of item indicated by parent_type',
  	'required'=>true,
  ),
  'brand_id' =>
  array (
    'name' => 'brand_id',
    'vname'=>'LBL_ACTIVITY_FOR_BRAND',
    'type' => 'id',
    'reportable'=>false,
    'comment' => 'Brand ID of item',
	'required'=>false,
  ),
  'description' =>
  array (
    'name' => 'description',
    'vname' => 'LBL_DESCRIPTION',
    'type' => 'text',
    'comment' => 'Full description of meeting'
  ),
'outcome' =>
  array (
    'name' => 'outcome',
    'vname' => 'LBL_OUTCOME',
    'type' => 'text',
    'comment' => 'A full outcome of call'
  ),  
  'deleted' =>
  array (
    'name' => 'deleted',
    'vname' => 'LBL_DELETED',
    'type' => 'bool',
    'required'=>true,
    'reportable'=>false,
    'comment' => 'Record deletion indicator'
  ),
    'reminder_time' =>
  array (
    'name' => 'reminder_time',
    'vname' => 'LBL_REMINDER_TIME',
    'type' => 'int',
    'default'=>-1,
    'comment' => 'Specifies when a reminder alert should be issued; -1 means no alert; otherwise the number of seconds prior to the start'
  ),
   'outlook_id' =>
  array (
    'name' => 'outlook_id',
    'vname' => 'LBL_OUTLOOK_ID',
    'type' => 'varchar',
    'len' => '255',
    'reportable' => false,
    'comment' => 'When the Sugar Plug-in for Microsoft Outlook syncs an Outlook appointment, this is the Outlook appointment item ID'
  ),
  'field_minutes' =>
  array (
  'name' => 'field_minutes',
    'vname' => 'LBL_LIST_AVERAGE_FIELD_TIME',
    'type' => 'int',
    'reportable'=>false,
    'source'=>'nondb',
    ),
  'contact_name' =>
  array (
    'name' => 'contact_name',
    'rname' => 'last_name',
    'db_concat_fields'=> array(0=>'first_name', 1=>'last_name'),
    'id_name' => 'contact_id',
    'massupdate' => false,
    'vname' => 'LBL_CONTACT_NAME',
    'type' => 'relate',
    'link'=>'contacts',
    'table' => 'contacts',
    'isnull' => 'true',
    'module' => 'Contacts',
    'join_name' => 'contacts',
    'dbType' => 'varchar',
    'source'=>'non-db',
    'len' => 36,
	),
  'contacts' =>
  array (
  	'name' => 'contacts',
    'type' => 'link',
    'relationship' => 'meetings_contacts',
    'source'=>'non-db',
		'vname'=>'LBL_CONTACTS',
  ),
  'users' =>
  array (
  	'name' => 'users',
    'type' => 'link',
    'relationship' => 'meetings_users',
    'source'=>'non-db',
		'vname'=>'LBL_USERS',
  ),
  'account' =>
  array (
  	'name' => 'account',
    'type' => 'link',
    'relationship' => 'account_meetings',
    'source'=>'non-db',
		'vname'=>'LBL_ACCOUNT',
  ),
  'opportunity' =>
  array (
  	'name' => 'opportunity',
    'type' => 'link',
    'relationship' => 'opportunity_meetings',
    'source'=>'non-db',
		'vname'=>'LBL_OPPORTUNITY',
  ),
  'case' =>
  array (
  	'name' => 'case',
    'type' => 'link',
    'relationship' => 'case_meetings',
    'source'=>'non-db',
		'vname'=>'LBL_CASE',
  ),
    'notes' =>
  array (
  	'name' => 'notes',
    'type' => 'link',
    'relationship' => 'meetings_notes',
    'module'=>'Notes',
    'bean_name'=>'Note',
    'source'=>'non-db',
		'vname'=>'LBL_NOTES',
  ),

 'parent_obj_meetings' =>
  array (
  	'name' => 'parent_obj_meetings',
    'type' => 'link',
    'relationship' => 'parent_obj_meetings',
    'module'=>'Meetings',
    'bean_name'=>'Meeting',
    'source'=>'non-db',
	'vname'=>'LBL_MEETINGS',
  ),
 'parent_obj_meetings_calls' =>
  array (
  	'name' => 'parent_obj_meetings_calls',
    'type' => 'link',
    'relationship' => 'parent_obj_meetings_calls',
    'module'=>'Calls',
    'bean_name'=>'Call',
    'source'=>'non-db',
	'vname'=>'LBL_CALLS',
  ),
  'parent_obj_meetings_tasks' =>
    array (
    	'name' => 'parent_obj_meetings_tasks',
      'type' => 'link',
      'relationship' => 'parent_obj_meetings_tasks',
      'module'=>'Tasks',
      'bean_name'=>'Task',
      'source'=>'non-db',
  	'vname'=>'LBL_TASKS',
  ),
  'parent_obj_meetings_emails' =>
    array (
    	'name' => 'parent_obj_meetings_emails',
      'type' => 'link',
      'relationship' => 'parent_obj_meetings_emails',
      'module'=>'Emails',
      'bean_name'=>'Email',
      'source'=>'non-db',
  	'vname'=>'LBL_EMAILS',
  ),
  'meetings_comments' =>
    array (
      'name' => 'meetings_comments',
      'type' => 'link',
      'relationship' => 'meetings_comments',
      'module'=>'Comments',
      'bean_name'=>'Comment',
      'source'=>'non-db',
  	'vname'=>'LBL_COMMENTS',
  ),  
  'meetings_reviews' =>
    array (
      'name' => 'meetings_reviews',
      'type' => 'link',
      'relationship' => 'meetings_reviews',
      'module'=>'Reviews',
      'bean_name'=>'Review',
      'source'=>'non-db',
  	'vname'=>'LBL_REVIEWS',
  ),
  'meetings_tasks' =>
    array (
      'name' => 'tasks',
      'type' => 'link',
      'relationship' => 'meetings_tasks',
      'module'=>'Tasks',
      'bean_name'=>'Task',
      'source'=>'non-db',
  	'vname'=>'LBL_TASKS',
  ),
  'meetings_emails' =>
    array (
      'name' => 'meetings_emails',
      'type' => 'link',
      'relationship' => 'meetings_emails',
      'module'=>'Emails',
      'bean_name'=>'Email',
      'source'=>'non-db',
  	'vname'=>'LBL_EMAILS',
  ),

'meetings_meetings' =>
    array (
   	  'name' => 'meetings',
      'type' => 'link',
      'relationship' => 'meetings_meetings',
      'module'=>'Meetings',
      'bean_name'=>'Meeting',
      'source'=>'non-db',
  	'vname'=>'LBL_MEETINGS',
  ),
'meetings_calls' =>
    array (
   	  'name' => 'meetings_calls',
      'type' => 'link',
      'relationship' => 'meetings_calls',
      'module'=>'Calls',
      'bean_name'=>'Call',
      'source'=>'non-db',
  	'vname'=>'LBL_CALLS',
  ),
  'created_by_link' =>
  array (
        'name' => 'created_by_link',
    'type' => 'link',
    'relationship' => 'meetings_created_by',
    'vname' => 'LBL_CREATED_BY_USER',
    'link_type' => 'one',
    'module'=>'Users',
    'bean_name'=>'User',
    'source'=>'non-db',
  ),
  'modified_user_link' =>
  array (
        'name' => 'modified_user_link',
    'type' => 'link',
    'relationship' => 'meetings_modified_user',
    'vname' => 'LBL_MODIFIED_BY_USER',
    'link_type' => 'one',
    'module'=>'Users',
    'bean_name'=>'User',
    'source'=>'non-db',
  ),
  'assigned_user_link' =>
  array (
        'name' => 'assigned_user_link',
    'type' => 'link',
    'relationship' => 'meetings_assigned_user',
    'vname' => 'LBL_ASSIGNED_TO_USER',
    'link_type' => 'one',
    'module'=>'Users',
    'bean_name'=>'User',
    'source'=>'non-db',
  ),  
 'group_meetings' =>
  array (
  	'name' => 'group_meetings',
    'type' => 'link',
    'relationship' => 'group_meetings',
    'module'=>'Calls',
    'bean_name'=>'Call',
    'source'=>'non-db',
	'vname'=>'LBL_GROUP_MEETINGS',
  ),
      'meetings_feedback' =>
  array (
    'name' => 'meetings_feedback',
    'type' => 'link',
    'relationship' => 'meetings_feedback',
    'source'=>'non-db',
    'vname'=>'LBL_FEEDBACK',
  ),
  ),
 'relationships' => array (
	  'meetings_assigned_user' =>
   array('lhs_module'=> 'Users', 'lhs_table'=> 'users', 'lhs_key' => 'id',
   'rhs_module'=> 'Meetings', 'rhs_table'=> 'meetings', 'rhs_key' => 'assigned_user_id',
   'relationship_type'=>'one-to-many')
   , 
  'meetings_comments' =>
  array (
    'lhs_module' => 'Meetings',
    'lhs_table' => 'meetings',
    'lhs_key' => 'id',
    'rhs_module' => 'Comments',
    'rhs_table' => 'comments',
    'rhs_key' => 'id',
    'join_table' => 'assoc_activity',
    'join_key_lhs' => 'parent_id',
    'join_key_rhs' => 'child_id',
    'relationship_type' => 'many-to-many',
    'relationship_role_column' => 'relation_type',
    'relationship_role_column_value' => 'Comments',
  ),     
  'meetings_modified_user' =>
   array('lhs_module'=> 'Users', 'lhs_table'=> 'users', 'lhs_key' => 'id',
   'rhs_module'=> 'Meetings', 'rhs_table'=> 'meetings', 'rhs_key' => 'modified_user_id',
   'relationship_type'=>'one-to-many')

   ,'meetings_created_by' =>
   array('lhs_module'=> 'Users', 'lhs_table'=> 'users', 'lhs_key' => 'id',
   'rhs_module'=> 'Meetings', 'rhs_table'=> 'meetings', 'rhs_key' => 'created_by',
   'relationship_type'=>'one-to-many')

	,'meetings_notes' => array('lhs_module'=> 'Meetings', 'lhs_table'=> 'meetings', 'lhs_key' => 'id',
							  'rhs_module'=> 'Notes', 'rhs_table'=> 'notes', 'rhs_key' => 'parent_id',
							  'relationship_type'=>'one-to-many', 'relationship_role_column'=>'parent_type',
							  'relationship_role_column_value'=>'Meetings')
	
  ,'parent_obj_meetings_calls' =>
  array (
    'lhs_module' => 'Meetings',
    'lhs_table' => 'meetings',
    'lhs_key' => 'id',
    'rhs_module' => 'Calls',
    'rhs_table' => 'calls',
    'rhs_key' => 'id',
    'join_table' => 'assoc_activity',
    'join_key_lhs' => 'child_id',
    'join_key_rhs' => 'parent_id',
    'relationship_type' => 'many-to-many',
    'relationship_role_column' => 'relation_type',
    'relationship_role_column_value' => 'Meetings',
  ),
  'parent_obj_meetings' =>
  array (
    'lhs_module' => 'Meetings',
    'lhs_table' => 'meetings',
    'lhs_key' => 'id',
    'rhs_module' => 'Meetings',
    'rhs_table' => 'meetings',
    'rhs_key' => 'id',
    'join_table' => 'assoc_activity',
    'join_key_lhs' => 'parent_id',
    'join_key_rhs' => 'child_id',
    'relationship_type' => 'many-to-many',
    'relationship_role_column' => 'relation_type',
    'relationship_role_column_value' => 'Meetings',
  ),  
  'parent_obj_meetings_tasks' =>
  array (
    'lhs_module' => 'Meetings',
    'lhs_table' => 'meetings',
    'lhs_key' => 'id',
    'rhs_module' => 'Tasks',
    'rhs_table' => 'tasks',
    'rhs_key' => 'id',
    'join_table' => 'assoc_activity',
    'join_key_lhs' => 'child_id',
    'join_key_rhs' => 'parent_id',
    'relationship_type' => 'many-to-many',
    'relationship_role_column' => 'relation_type',
    'relationship_role_column_value' => 'Meetings',
  ),  
  'parent_obj_meetings_emails' =>
  array (
    'lhs_module' => 'Meetings',
    'lhs_table' => 'meetings',
    'lhs_key' => 'id',
    'rhs_module' => 'Emails',
    'rhs_table' => 'emails',
    'rhs_key' => 'id',
    'join_table' => 'assoc_activity',
    'join_key_lhs' => 'child_id',
    'join_key_rhs' => 'parent_id',
    'relationship_type' => 'many-to-many',
    'relationship_role_column' => 'relation_type',
    'relationship_role_column_value' => 'Meetings',
  ),  
  'meetings_reviews' =>
  array (
    'lhs_module' => 'Meetings',
    'lhs_table' => 'meetings',
    'lhs_key' => 'id',
    'rhs_module' => 'Reviews',
    'rhs_table' => 'reviews',
    'rhs_key' => 'id',
    'join_table' => 'assoc_activity',
    'join_key_lhs' => 'parent_id',
    'join_key_rhs' => 'child_id',
    'relationship_type' => 'many-to-many',
    'relationship_role_column' => 'relation_type',
    'relationship_role_column_value' => 'Reviews',
  ),  
  'meetings_calls' =>
  array (
    'lhs_module' => 'Meetings',
    'lhs_table' => 'meetings',
    'lhs_key' => 'id',
    'rhs_module' => 'Calls',
    'rhs_table' => 'calls',
    'rhs_key' => 'id',
    'join_table' => 'assoc_activity',
    'join_key_lhs' => 'parent_id',
    'join_key_rhs' => 'child_id',
    'relationship_type' => 'many-to-many',
    'relationship_role_column' => 'relation_type',
    'relationship_role_column_value' => 'Calls',
  ), 
  'meetings_meetings' =>
  array (
    'lhs_module' => 'Meetings',
    'lhs_table' => 'meetings',
    'lhs_key' => 'id',
    'rhs_module' => 'Meetings',
    'rhs_table' => 'meetings',
    'rhs_key' => 'id',
    'join_table' => 'assoc_activity',
    'join_key_lhs' => 'child_id',
    'join_key_rhs' => 'parent_id',
    'relationship_type' => 'many-to-many',
    'relationship_role_column' => 'relation_type',
    'relationship_role_column_value' => 'Meetings',
  ),   
  'meetings_tasks' =>
  array (
    'lhs_module' => 'Meetings',
    'lhs_table' => 'meetings',
    'lhs_key' => 'id',
    'rhs_module' => 'Tasks',
    'rhs_table' => 'tasks',
    'rhs_key' => 'id',
    'join_table' => 'assoc_activity',
    'join_key_lhs' => 'parent_id',
    'join_key_rhs' => 'child_id',
    'relationship_type' => 'many-to-many',
    'relationship_role_column' => 'relation_type',
    'relationship_role_column_value' => 'Tasks',
  ),  
  'meetings_emails' =>
  array (
    'lhs_module' => 'Meetings',
    'lhs_table' => 'meetings',
    'lhs_key' => 'id',
    'rhs_module' => 'Emails',
    'rhs_table' => 'emails',
    'rhs_key' => 'id',
    'join_table' => 'assoc_activity',
    'join_key_lhs' => 'parent_id',
    'join_key_rhs' => 'child_id',
    'relationship_type' => 'many-to-many',
    'relationship_role_column' => 'relation_type',
    'relationship_role_column_value' => 'Emails',
  ),
   'meetings_feedback' =>
  array (
    'lhs_module' => 'Meetings',
    'lhs_table' => 'meetings',
    'lhs_key' => 'id',
    'rhs_module' => 'Feedback',
    'rhs_table' => 'feedback_mast',
    'rhs_key' => 'parent_id',
    'join_table' => '',
    'join_key_lhs' => '',
    'join_key_rhs' => '',
    'relationship_type' => 'one-to-many',
    'relationship_role_column' => 'parent_type',
    'relationship_role_column_value' => 'Meetings',
  ),
 'group_meetings' => array('lhs_module'=> 'Meetings', 'lhs_table'=> 'meetings', 'lhs_key' => 'id',
							  'rhs_module'=> 'Meetings', 'rhs_table'=> 'meetings', 'rhs_key' => 'id',
							  'relationship_type'=>'many-to-many',
							  'join_table'=> 'meetings_group_meetings', 'join_key_lhs'=>'meeting_id', 'join_key_rhs'=>'group_meeting_id'),  
	)
       , 'indices' => array (
       array('name' =>'meetingspk', 'type' =>'primary', 'fields'=>array('id')),
       array('name' =>'idx_mtg_name', 'type'=>'index', 'fields'=>array('name')),
       array('name' =>'idx_meet_par_del', 'type'=>'index', 'fields'=>array('parent_id','parent_type','deleted')),
       array('name' =>'idx_meeting_assigned', 'type'=>'index', 'fields'=>array('assigned_user_id')),
                                                   )
//This enables optimistic locking for Saves From EditView
	,'optimistic_locking'=>true,
                            );
?>
