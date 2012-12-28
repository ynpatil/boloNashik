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
$dictionary['ProspectList'] = array(
    'table' => 'prospect_lists',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'vname' => 'LBL_ID',
            'type' => 'id',
            'required' => true,
            'reportable' => false,
        ),
        'name' => array(
            'name' => 'name',
            'vname' => 'LBL_NAME',
            'type' => 'varchar',
            'len' => '50',
            'ucformat' => true,
        ),
//		'list_type' => array (
//		    'name' => 'list_type',
//		  	'vname' => 'LBL_TYPE',
//			'type' => 'enum',
//			'options' => 'prospect_list_type_dom',
//			'len'=>25,
//		),  
        'date_entered' => array(
            'name' => 'date_entered',
            'vname' => 'LBL_DATE_ENTERED',
            'type' => 'datetime',
        ),
        'date_modified' => array(
            'name' => 'date_modified',
            'vname' => 'LBL_DATE_MODIFIED',
            'type' => 'datetime',
        ),
        'modified_user_id' => array(
            'name' => 'modified_user_id',
            'rname' => 'user_name',
            'id_name' => 'modified_user_id',
            'vname' => 'LBL_MODIFIED',
            'type' => 'assigned_user_name',
            'table' => 'modified_user_id_users',
            'isnull' => 'false',
            'dbType' => 'id',
            'reportable' => true,
        ),
        'assigned_user_id' => array(
            'name' => 'assigned_user_id',
            'rname' => 'user_name',
            'id_name' => 'assigned_user_id',
            'vname' => 'LBL_ASSIGNED_TO',
            'type' => 'assigned_user_name',
            'table' => 'users',
            'isnull' => 'false',
            'dbType' => 'id',
            'reportable' => true,
        ),
        'created_by' => array(
            'name' => 'created_by',
            'rname' => 'user_name',
            'id_name' => 'created_by',
            'vname' => 'LBL_CREATED',
            'type' => 'assigned_user_name',
            'table' => 'created_by_users',
            'isnull' => 'false',
            'dbType' => 'id'
        ),
        'deleted' => array(
            'name' => 'deleted',
            'vname' => 'LBL_CREATED_BY',
            'type' => 'bool',
            'required' => true,
            'reportable' => false,
        ),
        'description' => array(
            'name' => 'description',
            'vname' => 'LBL_DESCRIPTION',
            'type' => 'text',
        ),
//		'list_type_value' => array (
//			'name' => 'list_type_value',
//			'vname' => 'LBL_LIST_TYPE_VALUE',
//			'type' => 'varchar',
//			'len' => '100',
//		),
        'start_date' => array(
            'name' => 'start_date',
            'vname' => 'LBL_START_DATE',
            'type' => 'date',
            'required' => true,
        ),
        'end_date' => array(
            'name' => 'end_date',
            'vname' => 'LBL_END_DATE',
            'type' => 'date',
            'required' => true,
        ),
        'entry_count' =>
        array(
            'name' => 'entry_count',
            'type' => 'int',
            'source' => 'non-db',
            'vname' => 'LBL_LIST_ENTRIES',
        ),
        'parent_name' => array(
            'name' => 'parent_name',
            'vname' => '',
            'type' => 'varchar',
            'source' => 'non-db',
            'required' => true,
        ),
        'prospects' =>
        array(
            'name' => 'prospects',
            'type' => 'link',
            'relationship' => 'prospect_list_prospects',
            'source' => 'non-db',
        ),
        'contacts' =>
        array(
            'name' => 'contacts',
            'type' => 'link',
            'relationship' => 'prospect_list_contacts',
            'source' => 'non-db',
        ),
        'leads' =>
        array(
            'name' => 'leads',
            'type' => 'link',
            'relationship' => 'prospect_list_leads',
            'source' => 'non-db',
        ),
        'campaigns' => array(
            'name' => 'campaigns',
            'type' => 'link',
            'relationship' => 'prospect_list_campaigns',
            'source' => 'non-db',
        ),
        'users' =>
        array(
            'name' => 'users',
            'type' => 'link',
            'relationship' => 'prospect_list_users',
            'source' => 'non-db',
        ),
        'marketing_id' => array(
            'name' => 'marketing_id',
            'vname' => 'LBL_MARKETING_ID',
            'type' => 'varchar',
            'len' => '36',
            'source' => 'non-db',
        ),
        'marketing_name' => array(
            'name' => 'marketing_name',
            'vname' => 'LBL_MARKETING_NAME',
            'type' => 'varchar',
            'len' => '255',
            'source' => 'non-db',
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
            //'reportable'=>false,
            'required' => true,
        ),
        'populate_lead_status' => array(
            'name' => 'populate_lead_status',
            'vname' => 'LBL_POPULATE_LEAD_STATUS',
            'type' => 'bool',
            'required' => true,
            'reportable' => false,
        ),
    ),
    'indices' => array(
        array(
            'name' => 'prospectlistsspk',
            'type' => 'primary',
            'fields' => array('id')
        ),
        array(
            'name' => 'idx_prospect_list_name',
            'type' => 'index',
            'fields' => array('name')
        ),
    ),
);
?>
