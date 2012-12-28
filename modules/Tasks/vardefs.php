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
$dictionary['TaskRequest'] = array ( 'table' => 'tasks_requests',
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
    'comment' => 'Name of the account',
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
),
);
 
$dictionary['Task'] = array('table' => 'tasks'
                               ,'fields' => array (
   'id' =>
  array (
    'name' => 'id',
    'vname' => 'LBL_ID',
    'type' => 'id',
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
  'date_modified' =>
  array (
    'name' => 'date_modified',
    'vname' => 'LBL_DATE_MODIFIED',
    'type' => 'datetime',
    'required'=>true,
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
    'reportable'=>true,
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
    'vname' => 'LBL_MODIFIED',
    'type' => 'assigned_user_name',
    'table' => 'users',
    'isnull' => 'false',
    'dbType' => 'id',
    'reportable'=>true,
  ),
  'created_by' =>
  array (
    'name' => 'created_by',
    'rname' => 'user_name',
    'id_name' => 'modified_user_id',
    'vname' => 'LBL_CREATED',
    'type' => 'assigned_user_name',
    'table' => 'users',
    'isnull' => 'false',
    'dbType' => 'id'
  ),
'parent_obj_tasks' =>
  array (
  	'name' => 'parent_obj_tasks',
    'type' => 'link',
    'relationship' => 'parent_obj_tasks',
    'module'=>'Tasks',
    'bean_name'=>'Task',
    'source'=>'non-db',
	'vname'=>'LBL_TASKS',
  ),
 'parent_obj_tasks_calls' =>
  array (
  	'name' => 'parent_obj_tasks_calls',
    'type' => 'link',
    'relationship' => 'parent_obj_tasks_calls',
    'module'=>'Calls',
    'bean_name'=>'Call',
    'source'=>'non-db',
	'vname'=>'LBL_CALLS',
  ),
 'parent_obj_tasks_emails' =>
  array (
  	'name' => 'parent_obj_tasks_emails',
    'type' => 'link',
    'relationship' => 'parent_obj_tasks_emails',
    'module'=>'Emails',
    'bean_name'=>'Email',
    'source'=>'non-db',
	'vname'=>'LBL_EMAILS',
  ),

  'tasks_meetings' =>
    array (
      'name' => 'tasks_meetings',
      'type' => 'link',
      'relationship' => 'tasks_meetings',
      'module'=>'Meetings',
      'bean_name'=>'Meeting',
      'source'=>'non-db',
  	'vname'=>'LBL_TASKS',
  ),
  'tasks_emails' =>
    array (
      'name' => 'tasks_emails',
      'type' => 'link',
      'relationship' => 'tasks_emails',
      'module'=>'Emails',
      'bean_name'=>'Email',
      'source'=>'non-db',
  	'vname'=>'LBL_EMAILS',
  ),

'tasks_tasks' =>
    array (
   	  'name' => 'tasks_tasks',
      'type' => 'link',
      'relationship' => 'tasks_tasks',
      'module'=>'Tasks',
      'bean_name'=>'Tasks',
      'source'=>'non-db',
  	'vname'=>'LBL_TASKS',
  ),
'tasks_calls' =>
    array (
   	  'name' => 'tasks_calls',
      'type' => 'link',
      'relationship' => 'tasks_calls',
      'module'=>'Calls',
      'bean_name'=>'Call',
      'source'=>'non-db',
  	'vname'=>'LBL_CALLS',
  ),






















  'name' =>
  array (
    'name' => 'name',
    'vname' => 'LBL_SUBJECT',
    'dbType' => 'varchar',
    'type' => 'name',
    'len' => '50',
    'ucformat' => true,    
  ),
  'status' =>
  array (
    'name' => 'status',
    'vname' => 'LBL_STATUS',
    'type' => 'enum',
    'options' => 'task_status_dom',
    'len'=>25,
  ),
  'date_due_flag' =>
  array (
    'name' => 'date_due_flag',
    'vname' => 'LBL_DATE_DUE_FLAG',
    'type' =>'bool',
    'dbType'=>'enum',
    'options'=>'on|off',
    'default'=>'on',
    'len'=>'5'
  ),
  'date_due' =>
  array (
    'name' => 'date_due',
    'vname' => 'LBL_DUE_DATE',
    'type' => 'date',
    'rel_field' => 'time_due',
  ),
  'time_due' =>
  array (
    'name' => 'time_due',
    'vname' => 'LBL_DUE_TIME',
    'type' => 'time',
    'rel_field' => 'date_due',
  ),
  'date_start_flag' =>
  array (
    'name' => 'date_start_flag',
    'vname' => 'LBL_DATE_START_FLAG',
    'type' =>'bool',
    'dbType'=>'enum',
    'options'=>'on|off',
    'default'=>'on',
    'len'=>'5'
  ),
  'date_start' =>
  array (
    'name' => 'date_start',
    'vname' => 'LBL_START_DATE',
    'type' => 'date',
    'rel_field' => 'time_start',
  ),
  'time_start' =>
  array (
    'name' => 'time_start',
    'vname' => 'LBL_START_TIME',
    'type' => 'time',
    'rel_field' => 'date_start',
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
  'brand_id' =>
  array (
    'name' => 'brand_id',
    'vname'=>'LBL_ACTIVITY_FOR_BRAND',
    'type' => 'id',
    'reportable'=>false,
    'comment' => 'Brand ID of item',
	'required'=>false,
  ),
  'contact_id' =>
  array (
    'name' => 'contact_id',
    'type' => 'id',
    'reportable'=>false,
  ),
    'contact_name' =>
  array (
    'name' => 'contact_name',
    'rname'=>'last_name',
    'db_concat_fields'=> array(0=>'first_name', 1=>'last_name'),
    'source' => 'non-db',
    'len' => '510',
    'vname' => '',
    'reportable'=>false,
    'join_name' => 'contacts',
    'type' => 'relate',
    'link'=>'contacts',

  ),
  'priority' =>
  array (
    'name' => 'priority',
    'vname' => 'LBL_PRIORITY',
    'type' => 'enum',
    'options' => 'task_priority_dom',
    'len'=>25,
  ),
  'description' =>
  array (
    'name' => 'description',
    'vname' => 'LBL_DESCRIPTION',
    'type' => 'text',
  ),
  'outcome' =>
  array (
    'name' => 'outcome',
    'vname' => 'LBL_OUTCOME',
    'type' => 'text',
  ),  
  'deleted' =>
  array (
    'name' => 'deleted',
    'vname' => 'LBL_DELETED',
    'type' => 'bool',
    'reportable'=>false,
    'required'=>true,
  ),
	'contacts'=>	array(
		'name' => 'contacts',
		'type' => 'link',
		'relationship' => 'contact_tasks',
		'source'=>'non-db',
		'side'=>'right',
		'vname'=>'LBL_CONTACT',
	),
  'accounts' =>
  array (
  	'name' => 'accounts',
    'type' => 'link',
    'relationship' => 'account_tasks',
    'source'=>'non-db',
		'vname'=>'LBL_ACCOUNT',
  ),
  'opportunities' =>
  array (
    'name' => 'opportunities',
    'type' => 'link',
    'relationship' => 'opportunity_tasks',
    'source'=>'non-db',
    'vname'=>'LBL_TASKS',
  ),













  'created_by_link' =>
  array (
        'name' => 'created_by_link',
    'type' => 'link',
    'relationship' => 'tasks_created_by',
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
    'relationship' => 'tasks_modified_user',
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
    'relationship' => 'tasks_assigned_user',
    'vname' => 'LBL_ASSIGNED_TO_USER',
    'link_type' => 'one',
    'module'=>'Users',
    'bean_name'=>'User',
    'source'=>'non-db',
  ),
)
,
 'relationships' => array (

  'tasks_assigned_user' =>
   array('lhs_module'=> 'Users', 'lhs_table'=> 'users', 'lhs_key' => 'id',
   'rhs_module'=> 'Tasks', 'rhs_table'=> 'tasks', 'rhs_key' => 'assigned_user_id',
   'relationship_type'=>'one-to-many')

   ,'tasks_modified_user' =>
   array('lhs_module'=> 'Users', 'lhs_table'=> 'users', 'lhs_key' => 'id',
   'rhs_module'=> 'Tasks', 'rhs_table'=> 'tasks', 'rhs_key' => 'modified_user_id',
   'relationship_type'=>'one-to-many')

   ,'tasks_created_by' =>
   array('lhs_module'=> 'Users', 'lhs_table'=> 'users', 'lhs_key' => 'id',
   'rhs_module'=> 'Tasks', 'rhs_table'=> 'tasks', 'rhs_key' => 'created_by',
   'relationship_type'=>'one-to-many')   
   ,
   
  'tasks_emails' =>
  array ('lhs_module' => 'Tasks','lhs_table' => 'tasks','lhs_key' => 'id',
    'rhs_module' => 'Emails','rhs_table' => 'emails','rhs_key' => 'id',
    'join_table' => 'assoc_activity','join_key_lhs' => 'parent_id',
    'join_key_rhs' => 'child_id','relationship_type' => 'many-to-many',
    'relationship_role_column' => 'relation_type',
    'relationship_role_column_value' => 'Emails',
  ),   

   'parent_obj_tasks' =>
  array (
    'lhs_module' => 'Tasks', 'lhs_table' => 'tasks', 'lhs_key' => 'id',
    'rhs_module' => 'Tasks', 'rhs_table' => 'tasks', 'rhs_key' => 'id',
    'join_table' => 'assoc_activity', 'join_key_lhs' => 'parent_id',
    'join_key_rhs' => 'child_id', 'relationship_type' => 'many-to-many',
    'relationship_role_column' => 'relation_type',
    'relationship_role_column_value' => 'Tasks',
  ),
  'parent_obj_tasks_calls' =>
  array (
    'lhs_module' => 'Tasks', 'lhs_table' => 'tasks', 'lhs_key' => 'id',
    'rhs_module' => 'Calls','rhs_table' => 'calls',  'rhs_key' => 'id',
    'join_table' => 'assoc_activity', 'join_key_lhs' => 'child_id',
    'join_key_rhs' => 'parent_id', 'relationship_type' => 'many-to-many',
    'relationship_role_column' => 'relation_type',
    'relationship_role_column_value' => 'Tasks',

  ),
  'parent_obj_tasks_emails' =>
  array (
    'lhs_module' => 'Tasks', 'lhs_table' => 'tasks','lhs_key' => 'id',
    'rhs_module' => 'Emails', 'rhs_table' => 'emails', 'rhs_key' => 'id',
    'join_table' => 'assoc_activity',  'join_key_lhs' => 'child_id',
    'join_key_rhs' => 'parent_id', 'relationship_type' => 'many-to-many',
    'relationship_role_column' => 'relation_type',
    'relationship_role_column_value' => 'Tasks',
  ),

    'tasks_calls' =>
  array (
    'lhs_module' => 'Tasks', 'lhs_table' => 'tasks', 'lhs_key' => 'id',
    'rhs_module' => 'Calls', 'rhs_table' => 'calls', 'rhs_key' => 'id',
    'join_table' => 'assoc_activity',  'join_key_lhs' => 'parent_id',
    'join_key_rhs' => 'child_id', 'relationship_type' => 'many-to-many',
    'relationship_role_column' => 'relation_type',
    'relationship_role_column_value' => 'Calls',
  ),

    'tasks_tasks' =>
  array (
    'lhs_module' => 'Tasks', 'lhs_table' => 'tasks', 'lhs_key' => 'id',
    'rhs_module' => 'Tasks', 'rhs_table' => 'tasks', 'rhs_key' => 'id',
    'join_table' => 'assoc_activity', 'join_key_lhs' => 'child_id',
    'join_key_rhs' => 'parent_id','relationship_type' => 'many-to-many',
    'relationship_role_column' => 'relation_type',
    'relationship_role_column_value' => 'Tasks',
  ),

  'tasks_meetings' =>
  array (
    'lhs_module' => 'Tasks', 'lhs_table' => 'tasks', 'lhs_key' => 'id',
    'rhs_module' => 'Meetings',  'rhs_table' => 'meetings',  'rhs_key' => 'id',
    'join_table' => 'assoc_activity',  'join_key_lhs' => 'parent_id',
    'join_key_rhs' => 'child_id',  'relationship_type' => 'many-to-many',
    'relationship_role_column' => 'relation_type',
    'relationship_role_column_value' => 'Meetings',
  ),  
  'tasks_emails' =>
  array (
    'lhs_module' => 'Tasks',
    'lhs_table' => 'tasks',
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
  )
                                                      , 'indices' => array (
       array('name' =>'taskspk', 'type' =>'primary', 'fields'=>array('id')),
       array('name' =>'idx_tsk_name', 'type'=>'index', 'fields'=>array('name')),
       array('name' =>'idx_task_con_del', 'type'=>'index', 'fields'=>array('contact_id','deleted')),
       array('name' =>'idx_task_par_del', 'type'=>'index', 'fields'=>array('parent_id','parent_type','deleted')),
		 array('name' =>'idx_task_assigned', 'type'=>'index', 'fields'=>array('assigned_user_id')),
             )

        //This enables optimistic locking for Saves From EditView
	,'optimistic_locking'=>true,
                            );
?>
