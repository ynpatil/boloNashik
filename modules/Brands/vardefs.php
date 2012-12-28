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
$dictionary['Brand'] = array('table' => 'brands', 'audited' => true, 'unified_search' => true, 'duplicate_merge' => true,
    'comment' => 'Brands are organizations or entities that are the target of selling, support, and marketing activities, or have already purchased products or services',
    'fields' => array(
        'id' =>
        array(
            'name' => 'id',
            'vname' => 'LBL_ID',
            'required' => true,
            'reportable' => false,
            'type' => 'id',
            'comment' => 'Unique identifier',
        ),
        'date_entered' =>
        array(
            'name' => 'date_entered',
            'vname' => 'LBL_DATE_ENTERED',
            'type' => 'datetime',
            'required' => true,
            'comment' => 'Date record created',
        ),
        'date_modified' =>
        array(
            'name' => 'date_modified',
            'vname' => 'LBL_DATE_MODIFIED',
            'type' => 'datetime',
            'required' => true,
            'comment' => 'Date record last modified',
        ),
        'modified_user_id' =>
        array(
            'name' => 'modified_user_id',
            'rname' => 'user_name',
            'id_name' => 'modified_user_id',
            'vname' => 'LBL_MODIFIED',
            'type' => 'assigned_user_name',
            'table' => 'modified_user_id_users',
            'reportable' => true,
            'isnull' => 'false',
            'dbType' => 'id',
            'required' => true,
            'len' => 36,
            'comment' => 'User ID that last modified record',
        ),
        'assigned_user_id' =>
        array(
            'name' => 'assigned_user_id',
            'rname' => 'user_name',
            'id_name' => 'assigned_user_id',
            'vname' => 'LBL_ASSIGNED_TO',
            'type' => 'assigned_user_name',
            'reportable' => true,
            'table' => 'users',
            'isnull' => 'false',
            'dbType' => 'id',
            'len' => 36,
            'audited' => true,
            'comment' => 'User ID of the assigned-to user',
            'duplicate_merge' => 'disabled'
        ),
        'assigned_user_name' =>
        array(
            'name' => 'assigned_user_name',
            'vname' => 'LBL_ASSIGNED_TO_NAME',
            'type' => 'relate',
            'reportable' => false,
            'source' => 'nondb',
            'table' => 'users',
            'id_name' => 'assigned_user_id',
            'module' => 'Users',
            'duplicate_merge' => 'disabled'
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
            'len' => 36,
            'comment' => 'User name who created record',
        ),
        'name' =>
        array(
            'name' => 'name',
            'type' => 'name',
            'dbType' => 'varchar',
            'vname' => 'LBL_NAME',
            'len' => 150,
            'comment' => 'Name of the brand',
            'unified_search' => true,
            'audited' => true,
            'required' => true,
            'ucformat' => true,
        ),
        'account_id' =>
        array(
            'name' => 'account_id',
            'vname' => 'LBL_PRIMARY_ACCOUNT',
            'type' => 'id',
            'required' => true,
            'reportable' => false,
            'audited' => true,
            'comment' => 'Account for this brand',
        ),
        'prod_hier_id' =>
        array(
            'name' => 'prod_hier_id',
            'vname' => 'LBL_PROD_HIER',
            'type' => 'id',
            'required' => false,
            'reportable' => false,
            'audited' => true,
            'comment' => 'Product Hierarchy id for this brand',
        ),
        'prod_hier_desc' =>
        array(
            'name' => 'prod_hier_desc',
            'vname' => 'LBL_PROD_HIER',
            'type' => 'id',
            'required' => false,
            'reportable' => false,
            'audited' => true,
            'comment' => 'Product Hierarchy desc for this brand',
        ),
        'account_name' =>
        array(
            'name' => 'account_name',
            'rname' => 'name',
            'id_name' => 'account_id',
            'vname' => 'LBL_PRIMARY_ACCOUNT',
            'join_name' => 'accounts',
            'type' => 'relate',
            'link' => 'accounts',
            'table' => 'accounts',
            'isnull' => 'true',
            'module' => 'Accounts',
            'dbType' => 'varchar',
            'len' => '255',
            'source' => 'non-db',
//			'unified_search' => true, 
        ),
        'contact_name' =>
        array(
            'name' => 'contact_name',
            'rname' => 'name',
            'id_name' => 'contact_id',
            'vname' => 'LBL_CONTACT',
            'join_name' => 'contacts',
            'type' => 'relate',
            'link' => 'contacts',
            'table' => 'contacts',
            'isnull' => 'true',
            'module' => 'Contacts',
            'dbType' => 'varchar',
            'len' => '255',
            'source' => 'non-db',
//			'unified_search' => true, 
        ),
        'accounts' =>
        array(
            'name' => 'accounts',
            'type' => 'link',
            'relationship' => 'accounts_brands',
            'link_type' => 'one',
            'source' => 'non-db',
            'vname' => 'LBL_ACCOUNT',
        ),
        'parent_id' =>
        array(
            'name' => 'parent_id',
            'vname' => 'LBL_PARENT_BRAND_ID',
            'type' => 'id',
            'required' => false,
            'reportable' => false,
            'audited' => true,
            'comment' => 'Parent ID',
        ),
        'parent_name' =>
        array(
            'name' => 'parent_name',
            'type' => 'link',
            'relationship' => 'member_brands',
            'vname' => 'LBL_PARENT_BRAND',
            'link_type' => 'one',
            'module' => 'Brands',
            'bean_name' => 'Brand',
            'source' => 'non-db',
            'duplicate_merge' => 'enabled',
            'rname' => 'name',
            'id_name' => 'parent_id',
            'table' => 'brands',
        ),
        'brand_pos' =>
        array(
            'name' => 'brand_pos',
            'vname' => 'LBL_BRAND_POSITIONING',
            'type' => 'text',
            'comment' => 'Brand Positioning information about the Brand',
        ),
        'deleted' =>
        array(
            'name' => 'deleted',
            'vname' => 'LBL_DELETED',
            'type' => 'bool',
            'required' => true,
            'reportable' => false,
            'default' => '0',
            'comment' => 'Record deletion indicator',
        ),
        'faq' =>
        array(
            'name' => 'faq',
            'vname' => 'LBL_FAQ',
            'type' => 'test',            
            'reportable' => false,            
            'comment' => 'Record deletion indicator',
        ),
        'tasks' =>
        array(
            'name' => 'tasks',
            'type' => 'link',
            'relationship' => 'brand_tasks',
            'module' => 'Tasks',
            'bean_name' => 'Task',
            'source' => 'non-db',
            'vname' => 'LBL_TASKS',
        ),
        'notes' =>
        array(
            'name' => 'notes',
            'type' => 'link',
            'relationship' => 'brand_notes',
            'module' => 'Notes',
            'bean_name' => 'Note',
            'source' => 'non-db',
            'vname' => 'LBL_NOTES',
        ),
        'meetings' =>
        array(
            'name' => 'meetings',
            'type' => 'link',
            'relationship' => 'brand_meetings',
            'module' => 'Meetings',
            'bean_name' => 'Meeting',
            'source' => 'non-db',
            'vname' => 'LBL_MEETINGS',
        ),
        'calls' =>
        array(
            'name' => 'calls',
            'type' => 'link',
            'relationship' => 'brand_calls',
            'module' => 'Calls',
            'bean_name' => 'Call',
            'source' => 'non-db',
            'vname' => 'LBL_CALLS',
        ),
        'emails' =>
        array(
            'name' => 'emails',
            'type' => 'link',
            'relationship' => 'brand_emails', /* reldef in emails */
            'module' => 'Emails',
            'bean_name' => 'Email',
            'source' => 'non-db',
            'vname' => 'LBL_EMAILS',
        ),
        'contacts' =>
        array(
            'name' => 'contacts',
            'type' => 'link',
            'relationship' => 'brands_contacts', //defined in /root/metadata
            'module' => 'Contacts',
            'bean_name' => 'Contact',
            'source' => 'non-db',
            'vname' => 'LBL_CONTACTS',
        ),
        'opportunities' =>
        array(
            'name' => 'opportunities',
            'type' => 'link',
            'relationship' => 'accounts_opportunities',
            'module' => 'Opportunities',
            'bean_name' => 'Opportunity',
            'source' => 'non-db',
            'vname' => 'LBL_OPPORTUNITY',
        ),
        'leads' =>
        array(
            'name' => 'leads',
            'type' => 'link',
            'relationship' => 'brand_leads',
            'module' => 'Leads',
            'bean_name' => 'Lead',
            'source' => 'non-db',
            'vname' => 'LBL_LEADS',
        ),
        'created_by_link' =>
        array(
            'name' => 'created_by_link',
            'type' => 'link',
            'relationship' => 'brands_created_by',
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
            'relationship' => 'brands_modified_user',
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
            'relationship' => 'brands_assigned_user',
            'vname' => 'LBL_ASSIGNED_TO_USER',
            'link_type' => 'one',
            'module' => 'Users',
            'bean_name' => 'User',
            'source' => 'non-db',
            'duplicate_merge' => 'enabled',
            'rname' => 'user_name',
            'id_name' => 'assigned_user_id',
            'table' => 'users',
        ),
        'price' =>
        array(
            'name' => 'price',
            'vname' => 'LBL_PRICE',
            'type' => 'varchar',
            'len' => 50,
            'comment' => 'Price for per Product',
        ),
    )
    , 'indices' => array(
        array('name' => 'brandspk', 'type' => 'primary', 'fields' => array('id')),
        array('name' => 'idx_brand_id_del', 'type' => 'index', 'fields' => array('id', 'deleted')),
        array('name' => 'idx_brand_assigned_del', 'type' => 'index', 'fields' => array('deleted', 'assigned_user_id')),
        array('name' => 'idx_brand_parent_id', 'type' => 'index', 'fields' => array('parent_id')),
    )
    , 'relationships' => array(
        'member_brands' => array('lhs_module' => 'Brands', 'lhs_table' => 'brands', 'lhs_key' => 'id',
            'rhs_module' => 'Brands', 'rhs_table' => 'brands', 'rhs_key' => 'parent_id',
            'relationship_type' => 'one-to-many')
        , 'brand_tasks' => array('lhs_module' => 'Brands', 'lhs_table' => 'brands', 'lhs_key' => 'id',
            'rhs_module' => 'Tasks', 'rhs_table' => 'tasks', 'rhs_key' => 'brand_id',
            'relationship_type' => 'one-to-many')
        , 'brand_notes' => array('lhs_module' => 'Brands', 'lhs_table' => 'brands', 'lhs_key' => 'id',
            'rhs_module' => 'Notes', 'rhs_table' => 'notes', 'rhs_key' => 'brand_id',
            'relationship_type' => 'one-to-many')
        , 'brand_meetings' => array('lhs_module' => 'Brands', 'lhs_table' => 'brands', 'lhs_key' => 'id',
            'rhs_module' => 'Meetings', 'rhs_table' => 'meetings', 'rhs_key' => 'brand_id',
            'relationship_type' => 'one-to-many')
        , 'brand_calls' => array(
            'lhs_module' => 'Brands',
            'lhs_table' => 'brands',
            'lhs_key' => 'id',
            'rhs_module' => 'Calls',
            'rhs_table' => 'calls',
            'rhs_key' => 'id',
            'relationship_type' => 'many-to-many',
            'join_table' => 'call_brand',
            'join_key_lhs' => 'brand_id',
            'join_key_rhs' => 'call_id',
            'relationship_role_column' => NULL,
            'relationship_role_column_value' => NULL
        )
        
        , 'brand_emails' => array('lhs_module' => 'Brands', 'lhs_table' => 'brands', 'lhs_key' => 'id',
            'rhs_module' => 'Emails', 'rhs_table' => 'emails', 'rhs_key' => 'brand_id',
            'relationship_type' => 'one-to-many')
        , 'brand_leads' => array('lhs_module' => 'Brands', 'lhs_table' => 'brands', 'lhs_key' => 'id',
            'rhs_module' => 'Leads', 'rhs_table' => 'leads', 'rhs_key' => 'brand_id',
            'relationship_type' => 'one-to-many')
        ,
        'brands_assigned_user' =>
        array('lhs_module' => 'Users', 'lhs_table' => 'users', 'lhs_key' => 'id',
            'rhs_module' => 'Brands', 'rhs_table' => 'brands', 'rhs_key' => 'assigned_user_id',
            'relationship_type' => 'one-to-many'),
        'brands_modified_user' =>
        array('lhs_module' => 'Users', 'lhs_table' => 'users', 'lhs_key' => 'id',
            'rhs_module' => 'Brands', 'rhs_table' => 'brands', 'rhs_key' => 'modified_user_id',
            'relationship_type' => 'one-to-many'),
        'brands_created_by' =>
        array('lhs_module' => 'Users', 'lhs_table' => 'users', 'lhs_key' => 'id',
            'rhs_module' => 'Brands', 'rhs_table' => 'brands', 'rhs_key' => 'created_by',
            'relationship_type' => 'one-to-many'),
    )
    //This enables optimistic locking for Saves From EditView
    , 'optimistic_locking' => true,
);
?>
