<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');
/* * *******************************************************************************
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
 * ****************************************************************************** */
$dictionary['CallRequest'] = array('table' => 'calls_requests',
    'audited' => true, 'unified_search' => true, 'duplicate_merge' => true,
    'fields' => array(
        'id' =>
        array(
            'name' => 'id',
            'vname' => 'LBL_USER_ID',
            'type' => 'id',
            'required' => true,
        ),
        'deleted' =>
        array(
            'name' => 'deleted',
            'vname' => 'LBL_DELETED',
            'type' => 'bool',
            'required' => true,
            'reportable' => false,
        ),
        'date_entered' =>
        array(
            'name' => 'date_entered',
            'vname' => 'LBL_DATE_ENTERED',
            'type' => 'datetime',
            'required' => true,
        ),
        'created_by' =>
        array(
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
        array(
            'name' => 'name',
            'type' => 'name',
            'dbType' => 'varchar',
            'vname' => 'LBL_NAME',
            'len' => 150,
            'comment' => 'Subject',
            'unified_search' => true,
            'audited' => false,
            'ucformat' => true,
        ),        
        'description' =>
        array(
            'name' => 'description',
            'vname' => 'LBL_DESCRIPTION',
            'type' => 'text',
            'comment' => 'Descriptive information about the account',
        ),
        'parent_type' =>
        array(
            'name' => 'parent_type',
            'type' => 'varchar',
            'len' => '25',
            'reportable' => false,
        ),
        'parent_id' =>
        array(
            'name' => 'parent_id',
            'type' => 'id',
            'reportable' => false,
        ),
    ),
);

$dictionary['Call'] = array('table' => 'calls', 'comment' => 'A Call is an activity representing a phone call',
    'unified_search' => true, 'fields' => array(
        'id' =>
        array(
            'name' => 'id',
            'vname' => 'LBL_NAME',
            'type' => 'id',
            'required' => true,
            'reportable' => false,
            'comment' => 'Unique identifier'
        ),
        'date_entered' =>
        array(
            'name' => 'date_entered',
            'vname' => 'LBL_DATE_ENTERED',
            'type' => 'datetime',
            'required' => true,
            'comment' => 'Date record was created'
        ),
        'date_modified' =>
        array(
            'name' => 'date_modified',
            'vname' => 'LBL_DATE_MODIFIED',
            'type' => 'datetime',
            'required' => true,
            'comment' => 'Date record was last modified'
        ),
        'assigned_user_id' =>
        array(
            'name' => 'assigned_user_id',
            'rname' => 'user_name',
            'id_name' => 'assigned_user_id',
            'vname' => 'LBL_ASSIGNED_TO',
            'type' => 'assigned_user_name',
            'table' => 'users',
            'isnull' => 'false',
            'reportable' => true,
            'dbType' => 'id',
            'comment' => 'User assigned to the record'
        ),
        'assigned_user_name' =>
        array(
            'name' => 'assigned_user_name',
            'vname' => 'LBL_ASSIGNED_TO',
            'type' => 'varchar',
            'reportable' => false,
            'source' => 'nondb',
            'table' => 'users',
        ),
        'modified_user_id' =>
        array(
            'name' => 'modified_user_id',
            'rname' => 'user_name',
            'id_name' => 'modified_user_id',
            'vname' => 'LBL_MODIFIED',
            'type' => 'assigned_user_name',
            'table' => 'modified_user_id_users',
            'isnull' => 'false',
            'reportable' => true,
            'dbType' => 'id',
            'comment' => 'User who last modified the record'
        ),
        'created_by' =>
        array(
            'name' => 'created_by',
            'rname' => 'user_name',
            'id_name' => 'created_by',
            'vname' => 'LBL_CREATED',
            'type' => 'assigned_user_name',
            'table' => 'created_by_users',
            'isnull' => 'false',
            'dbType' => 'id',
            'comment' => 'User who created the record'
        ),
        'name' =>
        array(
            'name' => 'name',
            'vname' => 'LBL_SUBJECT',
            'dbType' => 'varchar',
            'type' => 'name',
            'len' => '50',
            'comment' => 'Brief description of the call',            
            'ucformat' => true,
        ),
        'tokan_no' =>
        array(
            'name' => 'tokan_no',
            'dbType' => 'varchar',
            'vname' => 'LBL_TOKEN_NO',
            'len' => '50',
            'comment' => 'Token No',
            'unified_search' => true,                       
        ),
        'duration_hours' =>
        array(
            'name' => 'duration_hours',
            'vname' => 'LBL_DURATION_HOURS',
            'type' => 'int',
            'len' => '2',
            'comment' => 'Call duration, hours portion'
        ),
        'duration_minutes' =>
        array(
            'name' => 'duration_minutes',
            'vname' => 'LBL_DURATION_MINUTES',
            'type' => 'int',
            'len' => '2',
            'comment' => 'Call duration, minutes portion'
        ),
        'date_start' =>
        array(
            'name' => 'date_start',
            'vname' => 'LBL_DATE',
            'type' => 'date',
            'rel_field' => 'time_start',
            'comment' => 'Date in which call is schedule to (or did) start'
        ),
        'time_start' =>
        array(
            'name' => 'time_start',
            'vname' => 'LBL_TIME',
            'type' => 'time',
            'rel_field' => 'date_start',
            'comment' => 'Time in which call is scheduled to (or did) start'
        ),        
        'call_back_date' =>
        array(
            'name' => 'call_back_date',
            'vname' => 'LBL_END_DATE',
            'type' => 'date',            
            'comment' => 'Date record was created'
        ),
        'call_back_time' =>
        array(
            'name' => 'call_back_time',
            'vname' => 'LBL_END_TIME',
            'type' => 'time',            
            'comment' => 'Date record was created'
        ),        
        'not_interested' =>
        array(
            'name' => 'not_interested',
            'vname' => 'LBL_NOT_INTERESTED',
            'type' => 'varchar',
            'required' => false,
            'reportable' => false,
            'len' => 50,
            'comment' => 'Not interested'
        ),
        'date_end' =>
        array(
            'name' => 'date_end',
            'vname' => 'LBL_DATE_END',
            'type' => 'date',
            'massupdate' => false,
            'comment' => 'Date is which call is scheduled to (or did) end'
        ),
        'parent_type' =>
        array(
            'name' => 'parent_type',
            'vname' => 'LBL_LIST_RELATED_TO',
            'type' => 'varchar',
            'required' => false,
            'reportable' => false,
            'len' => 25,
            'comment' => 'The Sugar object to which the call is related'
        ),
        'status' =>
        array(
            'name' => 'status',
            'vname' => 'LBL_STATUS',
            'type' => 'enum',
            'len' => '25',
            'options' => 'call_status_dom',
            'required' => false,
            'comment' => 'The status of the call (Held, Not Held, etc.)'
        ),
        'direction' =>
        array(
            'name' => 'direction',
            'vname' => 'LBL_DIRECTION',
            'type' => 'enum',
            'len' => '25',
            'options' => 'call_direction_dom',
            'comment' => 'Indicates whether call is inbound or outbound'
        ),
        'parent_id' =>
        array(
            'name' => 'parent_id',
            'vname' => 'LBL_LIST_RELATED_TO',
            'type' => 'id',
            'reportable' => false,
            'comment' => 'The ID of the parent Sugar object identified by parent_type'
        ),
        'campaign_id' =>
        array(
            'name' => 'campaign_id',
            'vname' => 'LBL_ACTIVITY_FOR_COMPAIGN',
            'type' => 'id',
            'reportable' => false,
            'comment' => 'Compaign ID of item',
            'required' => false,
        ),
        'description' =>
        array(
            'name' => 'description',
            'vname' => 'LBL_DESCRIPTION',
            'type' => 'text',
            'comment' => 'A full description of the purpose'
        ),
        'outcome' =>
        array(
            'name' => 'outcome',
            'vname' => 'LBL_OUTCOME',
            'type' => 'text',
            'comment' => 'A full outcome of call'
        ),
        'deleted' =>
        array(
            'name' => 'deleted',
            'vname' => 'LBL_DELETED',
            'type' => 'bool',
            'required' => true,
            'reportable' => false,
            'comment' => 'Record deltion indicator'
        ),
        'reminder_time' => array(
            'name' => 'reminder_time',
            'vname' => 'LBL_REMINDER_TIME',
            'type' => 'int',
            'required' => false,
            'default' => -1,
            'len' => '4',
            'comment' => 'Specifies when a reminder alert should be issued; -1 means no alert; otherwise the number of seconds prior to the start'
        ),
        'outlook_id' =>
        array(
            'name' => 'outlook_id',
            'vname' => 'LBL_OUTLOOK_ID',
            'type' => 'varchar',
            'len' => '255',
            'reportable' => false,
            'comment' => 'When the Sugar Plug-in for Microsoft Outlook syncs an Outlook appointment, this is the Outlook appointment item ID'
        ),
        'accept_status' => array(
            'name' => 'accept_status',
            'vname' => 'LBL_SUBJECT',
            'dbType' => 'varchar',
            'type' => 'varchar',
            'len' => '20',
            'source' => 'non-db',
        ),
        'contact_name' =>
        array(
            'name' => 'contact_name',
            'rname' => 'last_name',
            'db_concat_fields' => array(0 => 'first_name', 1 => 'last_name'),
            'id_name' => 'contact_id',
            'massupdate' => false,
            'vname' => 'LBL_CONTACT_NAME',
            'type' => 'relate',
            'link' => 'contacts',
            'table' => 'contacts',
            'isnull' => 'true',
            'module' => 'Contacts',
            'join_name' => 'contacts',
            'dbType' => 'varchar',
            'source' => 'non-db',
            'len' => 36,
        ),
        'account' =>
        array(
            'name' => 'account',
            'type' => 'link',
            'relationship' => 'account_calls',
            'link_type' => 'one',
            'source' => 'non-db',
            'vname' => 'LBL_ACCOUNT',
        ),
        'opportunity' =>
        array(
            'name' => 'opportunity',
            'type' => 'link',
            'relationship' => 'opportunity_calls',
            'source' => 'non-db',
            'link_type' => 'one',
            'vname' => 'LBL_OPPORTUNITY',
        ),
        'case' =>
        array(
            'name' => 'case',
            'type' => 'link',
            'relationship' => 'case_calls',
            'source' => 'non-db',
            'link_type' => 'one',
            'vname' => 'LBL_CASE',
        ),
        'accounts' =>
        array(
            'name' => 'accounts',
            'type' => 'link',
            'relationship' => 'account_calls',
            'module' => 'Accounts',
            'bean_name' => 'Account',
            'source' => 'non-db',
            'vname' => 'LBL_ACCOUNT',
        ),
        'contacts' =>
        array(
            'name' => 'contacts',
            'type' => 'link',
            'relationship' => 'calls_contacts',
            'source' => 'non-db',
            'vname' => 'LBL_CONTACTS',
        ),
        'users' =>
        array(
            'name' => 'users',
            'type' => 'link',
            'relationship' => 'calls_users',
            'source' => 'non-db',
            'vname' => 'LBL_USERS',
        ),
        'notes' =>
        array(
            'name' => 'notes',
            'type' => 'link',
            'relationship' => 'calls_notes',
            'module' => 'Notes',
            'bean_name' => 'Note',
            'source' => 'non-db',
            'vname' => 'LBL_NOTES',
        ),
        'calls_calls' =>
        array(
            'name' => 'calls_calls',
            'type' => 'link',
            'relationship' => 'calls_calls',
            'module' => 'Calls',
            'bean_name' => 'Call',
            'source' => 'non-db',
            'vname' => 'LBL_CALLS',
        ),
        'parent_obj_calls' =>
        array(
            'name' => 'parent_obj_calls',
            'type' => 'link',
            'relationship' => 'parent_obj_calls',
            'module' => 'Calls',
            'bean_name' => 'Call',
            'source' => 'non-db',
            'vname' => 'LBL_CALLS',
        ),
        'parent_obj_calls_meetings' =>
        array(
            'name' => 'parent_obj_calls_meetings',
            'type' => 'link',
            'relationship' => 'parent_obj_calls_meetings',
            'module' => 'Meetings',
            'bean_name' => 'Meeting',
            'source' => 'non-db',
            'vname' => 'LBL_MEETINGS',
        ),
        'parent_obj_calls_tasks' =>
        array(
            'name' => 'parent_obj_calls_tasks',
            'type' => 'link',
            'relationship' => 'parent_obj_calls_tasks',
            'module' => 'Tasks',
            'bean_name' => 'Task',
            'source' => 'non-db',
            'vname' => 'LBL_TASKS',
        ),
        'parent_obj_calls_emails' =>
        array(
            'name' => 'parent_obj_calls_emails',
            'type' => 'link',
            'relationship' => 'parent_obj_calls_emails',
            'module' => 'Emails',
            'bean_name' => 'Email',
            'source' => 'non-db',
            'vname' => 'LBL_EMAILS',
        ),
        'calls_tasks' =>
        array(
            'name' => 'calls_tasks',
            'type' => 'link',
            'relationship' => 'calls_tasks',
            'module' => 'Tasks',
            'bean_name' => 'Task',
            'source' => 'non-db',
            'vname' => 'LBL_TASKS',
        ),
        'calls_emails' =>
        array(
            'name' => 'calls_emails',
            'type' => 'link',
            'relationship' => 'calls_emails',
            'module' => 'Emails',
            'bean_name' => 'Email',
            'source' => 'non-db',
            'vname' => 'LBL_EMAILS',
        ),
        'calls_reviews' =>
        array(
            'name' => 'calls_reviews',
            'type' => 'link',
            'relationship' => 'calls_reviews',
            'module' => 'Reviews',
            'bean_name' => 'Review',
            'source' => 'non-db',
            'vname' => 'LBL_REVIEWS',
        ),
        'calls_comments' =>
        array(
            'name' => 'calls_comments',
            'type' => 'link',
            'relationship' => 'calls_comments',
            'module' => 'Comments',
            'bean_name' => 'Comment',
            'source' => 'non-db',
            'vname' => 'LBL_COMMENTS',
        ),
        'calls_meetings' =>
        array(
            'name' => 'calls_meetings',
            'type' => 'link',
            'relationship' => 'calls_meetings',
            'module' => 'Meetings',
            'bean_name' => 'Meeting',
            'source' => 'non-db',
            'vname' => 'LBL_MEETINGS',
        ),
        'created_by_link' =>
        array(
            'name' => 'created_by_link',
            'type' => 'link',
            'relationship' => 'calls_created_by',
            'vname' => 'LBL_CREATED_BY_USER',
            'link_type' => 'one',
            'module' => 'Users',
            'bean_name' => 'User',
            'source' => 'non-db',
        ),
        'modified_user_link' =>
        array(
            'name' => 'modified_user_link',
            'type' => 'link',
            'relationship' => 'calls_modified_user',
            'vname' => 'LBL_MODIFIED_BY_USER',
            'link_type' => 'one',
            'module' => 'Users',
            'bean_name' => 'User',
            'source' => 'non-db',
        ),
        'assigned_user_link' =>
        array(
            'name' => 'assigned_user_link',
            'type' => 'link',
            'relationship' => 'calls_assigned_user',
            'vname' => 'LBL_ASSIGNED_TO_USER',
            'link_type' => 'one',
            'module' => 'Users',
            'bean_name' => 'User',
            'source' => 'non-db',
        ),
        'group_calls' =>
        array(
            'name' => 'group_calls',
            'type' => 'link',
            'relationship' => 'group_calls',
            'module' => 'Calls',
            'bean_name' => 'Call',
            'source' => 'non-db',
            'vname' => 'LBL_GROUP_CALLS',
        ),
        'brand' =>
        array(
            'name' => 'brand',
            'type' => 'link',
            'relationship' => 'call_brand',
            'source' => 'non-db',
            'module' => 'Brands',
            'bean_name' => 'Brands',
            'source' => 'non-db',
            'vname' => 'LBL_BRAND',
        ),
    )
    , 'indices' => array(
        array('name' => 'callspk', 'type' => 'primary', 'fields' => array('id')),
        array('name' => 'idx_call_name', 'type' => 'index', 'fields' => array('name')),
        array('name' => 'idx_call_assigned', 'type' => 'index', 'fields' => array('assigned_user_id')),
    )
    , 'relationships' => array(
        'calls_assigned_user' => array(
            'lhs_module' => 'Users',
            'lhs_table' => 'users',
            'lhs_key' => 'id',
            'rhs_module' => 'Calls',
            'rhs_table' => 'calls',
            'rhs_key' => 'assigned_user_id',
            'relationship_type' => 'one-to-many'
        ),
        'calls_modified_user' => array(
            'lhs_module' => 'Users',
            'lhs_table' => 'users',
            'lhs_key' => 'id',
            'rhs_module' => 'Calls',
            'rhs_table' => 'calls',
            'rhs_key' => 'modified_user_id',
            'relationship_type' => 'one-to-many'
        ),
        'calls_created_by' => array(
            'lhs_module' => 'Users',
            'lhs_table' => 'users',
            'lhs_key' => 'id',
            'rhs_module' => 'Calls',
            'rhs_table' => 'calls',
            'rhs_key' => 'created_by',
            'relationship_type' => 'one-to-many'
        ),
        'calls_notes' => array(
            'lhs_module' => 'Calls',
            'lhs_table' => 'calls',
            'lhs_key' => 'id',
            'rhs_module' => 'Notes',
            'rhs_table' => 'notes',
            'rhs_key' => 'parent_id',
            'relationship_type' => 'one-to-many',
        ),
        'parent_obj_calls' =>
        array(
            'lhs_module' => 'Calls',
            'lhs_table' => 'calls',
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
        'parent_obj_calls_meetings' =>
        array(
            'lhs_module' => 'Calls',
            'lhs_table' => 'calls',
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
        'parent_obj_calls_tasks' =>
        array(
            'lhs_module' => 'Calls',
            'lhs_table' => 'calls',
            'lhs_key' => 'id',
            'rhs_module' => 'Tasks',
            'rhs_table' => 'tasks',
            'rhs_key' => 'id',
            'join_table' => 'assoc_activity',
            'join_key_lhs' => 'child_id',
            'join_key_rhs' => 'parent_id',
            'relationship_type' => 'many-to-many',
            'relationship_role_column' => 'relation_type',
            'relationship_role_column_value' => 'Calls',
        ),
        'parent_obj_calls_emails' =>
        array(
            'lhs_module' => 'Calls',
            'lhs_table' => 'calls',
            'lhs_key' => 'id',
            'rhs_module' => 'Emails',
            'rhs_table' => 'emails',
            'rhs_key' => 'id',
            'join_table' => 'assoc_activity',
            'join_key_lhs' => 'child_id',
            'join_key_rhs' => 'parent_id',
            'relationship_type' => 'many-to-many',
            'relationship_role_column' => 'relation_type',
            'relationship_role_column_value' => 'Calls',
        ),
        'calls_calls' =>
        array(
            'lhs_module' => 'Calls',
            'lhs_table' => 'calls',
            'lhs_key' => 'id',
            'rhs_module' => 'Calls',
            'rhs_table' => 'calls',
            'rhs_key' => 'id',
            'join_table' => 'assoc_activity',
            'join_key_lhs' => 'child_id',
            'join_key_rhs' => 'parent_id',
            'relationship_type' => 'many-to-many',
            'relationship_role_column' => 'relation_type',
            'relationship_role_column_value' => 'Calls',
        ),
        'calls_meetings' =>
        array(
            'lhs_module' => 'Calls',
            'lhs_table' => 'calls',
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
        'calls_tasks' =>
        array(
            'lhs_module' => 'Calls',
            'lhs_table' => 'calls',
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
        'calls_emails' =>
        array(
            'lhs_module' => 'Calls',
            'lhs_table' => 'calls',
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
        'calls_reviews' =>
        array(
            'lhs_module' => 'Calls',
            'lhs_table' => 'calls',
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
        'calls_comments' =>
        array(
            'lhs_module' => 'Calls',
            'lhs_table' => 'calls',
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
        'call_brand' => array(
            'lhs_module' => 'Calls',
            'lhs_table' => 'calls',
            'lhs_key' => 'id',
            'rhs_module' => 'Brands',
            'rhs_table' => 'brands',
            'rhs_key' => 'id',
            'relationship_type' => 'many-to-many',
            'join_table' => 'call_brand',
            'join_key_lhs' => 'call_id',
            'join_key_rhs' => 'brand_id',
            'relationship_role_column' => NULL,
            'relationship_role_column_value' => NULL
        ),
        'group_calls' => array('lhs_module' => 'Calls', 'lhs_table' => 'calls', 'lhs_key' => 'id',
            'rhs_module' => 'Calls', 'rhs_table' => 'calls', 'rhs_key' => 'id',
            'relationship_type' => 'many-to-many',
            'join_table' => 'calls_group_calls', 'join_key_lhs' => 'call_id', 'join_key_rhs' => 'group_call_id'),
    ),
//This enables optimistic locking for Saves From EditView
    'optimistic_locking' => true,
);
?>
