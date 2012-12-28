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
//om
$dictionary['Campaign'] = array('audited' => true,
    'comment' => 'Campaigns are a series of operations undertaken to accomplish a purpose, usually acquiring leads',
    'table' => 'campaigns',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'vname' => 'LBL_ID',
            'type' => 'id',
            'required' => true,
            'reportable' => false,
            'comment' => 'Unique identifier'
        ),
        'tracker_key' => array(
            'name' => 'tracker_key',
            'vname' => 'LBL_TRACKER_KEY',
            'type' => 'int',
            'required' => true,
            'len' => '11',
            'auto_increment' => true,
            'comment' => 'The internal ID of the tracker used in a campaign; no longer used as of 4.2 (see campaign_trkrs)'
        ),
        'tracker_count' => array(
            'name' => 'tracker_count',
            'vname' => 'LBL_TRACKER_COUNT',
            'type' => 'int',
            'len' => '11',
            'default' => '0',
            'comment' => 'The number of accesses made to the tracker URL; no longer used as of 4.2 (see campaign_trkrs)'
        ),
        'name' => array(
            'name' => 'name',
            'vname' => 'LBL_CAMPAIGN_NAME',
            'type' => 'varchar',
            'len' => '50',
            'comment' => 'The name of the campaign',
            'ucformat' => true,
        ),
        'refer_url' => array(
            'name' => 'refer_url',
            'vname' => 'LBL_REFER_URL',
            'type' => 'varchar',
            'len' => '255',
            'default' => 'http://',
            'comment' => 'The URL referenced in the tracker URL; no longer used as of 4.2 (see campaign_trkrs)'
        ),
        'tracker_text' => array(
            'name' => 'tracker_text',
            'vname' => 'LBL_TRACKER_TEXT',
            'type' => 'varchar',
            'len' => '255',
            'comment' => 'The text that appears in the tracker URL; no longer used as of 4.2 (see campaign_trkrs)'
        ),
        'date_entered' => array(
            'name' => 'date_entered',
            'vname' => 'LBL_DATE_ENTERED',
            'type' => 'datetime',
            'comment' => 'Date record created'
        ),
        'date_modified' => array(
            'name' => 'date_modified',
            'vname' => 'LBL_DATE_MODIFIED',
            'type' => 'datetime',
            'comment' => 'Date record last modified'
        ),
        'modified_user_id' => array(
            'name' => 'modified_user_id',
            'rname' => 'user_name',
            'id_name' => 'modified_user_id',
            'vname' => 'LBL_MODIFIED',
            'type' => 'assigned_user_name',
            'table' => 'modified_user_id_users',
            'isnull' => 'false',
            'reportable' => true,
            'dbType' => 'id',
            'comment' => 'User who last modified record'
        ),
        'assigned_user_id' => array(
            'name' => 'assigned_user_id',
            'rname' => 'user_name',
            'id_name' => 'assigned_user_id',
            'vname' => 'LBL_ASSIGNED_TO',
            'type' => 'assigned_user_name',
            'table' => 'users',
            'isnull' => 'false',
            'reportable' => true,
            'dbType' => 'id',
            'audited' => true,
            'comment' => 'User ID assigned to record'
        ),
        'assigned_user_name' => array(
            'name' => 'assigned_user_name',
            'vname' => 'LBL_ASSIGNED_TO',
            'type' => 'varchar',
            'reportable' => false,
            'source' => 'nondb',
            'table' => 'users'
        ),
        'created_by' => array(
            'name' => 'created_by',
            'rname' => 'user_name',
            'id_name' => 'created_by',
            'vname' => 'LBL_CREATED',
            'type' => 'assigned_user_name',
            'table' => 'created_by_users',
            'isnull' => 'false',
            'dbType' => 'id',
            'comment' => 'User ID who created record'
        ),
        'modified_user_link' =>
        array(
            'name' => 'modified_user_link',
            'type' => 'link',
            'relationship' => 'campaign_modified_user',
            'vname' => 'LBL_MODIFIED_BY_USER',
            'link_type' => 'one',
            'module' => 'Users',
            'bean_name' => 'User',
            'source' => 'non-db',
        ),
        'deleted' => array(
            'name' => 'deleted',
            'vname' => 'LBL_CREATED_BY',
            'type' => 'bool',
            'required' => true,
            'reportable' => false,
            'comment' => 'Record deletion indicator'
        ),
        'start_date' => array(
            'name' => 'start_date',
            'vname' => 'LBL_CAMPAIGN_START_DATE',
            'type' => 'date',
            'audited' => true,
            'comment' => 'Starting date of the campaign'
        ),
        'end_date' => array(
            'name' => 'end_date',
            'vname' => 'LBL_CAMPAIGN_END_DATE',
            'type' => 'date',
            'audited' => true,
            'comment' => 'Ending date of the campaign'
        ),
        'status' => array(
            'name' => 'status',
            'vname' => 'LBL_CAMPAIGN_STATUS',
            'type' => 'enum',
            'options' => 'campaign_status_dom',
            'len' => '25',
            'audited' => true,
            'comment' => 'Status of the campaign'
        ),
        'currency_id' =>
        array(
            'name' => 'currency_id',
            'vname' => 'LBL_CURRENCY_ID',
            'type' => 'id',
            'required' => false,
            'do_report' => false,
            'reportable' => false,
            'comment' => 'Currency in use for the campaign'
        ),
        'product_id' =>
        array(
            'name' => 'product_id',
            'vname' => 'LBL_PRODUCT_ID',
            'type' => 'id',
            'len' => '36',
            'audited' => true,
            'required' => false,
            'do_report' => false,
            'reportable' => false,
            'comment' => 'Product in use for the campaign'
        ),
         'product_name' =>
                array (
                        'name' => 'product_name',
                        'rname' => 'name',
                        'id_name' => 'product_id',
                        'vname' => 'LBL_PRODUCT_NAME',
                        'type' => 'relate',
                        'table' => 'brands',
                        'join_name'=>'brands',
                        'isnull' => 'true',
                        'module' => 'Brands',
                        'dbType' => 'varchar',
                        'link'=>'brands',
                        'len' => '255',
                        'source'=>'non-db',
                        //'unified_search' => true,
                ),
//        'product_name' =>
//        array(
//            'name' => 'product_name',
//            'vname' => 'LBL_PRODUCT_NAME',
//            'type' => 'varchar',
//            'source' => 'nondb',
//            'table' => 'brands',
//            'comment' => 'Product in use for the campaign'
//        ),
        'vendor_file_status' => array(
            'name' => 'vendor_file_status',
            'vname' => 'LBL_VENDOR_FILE_STATUS',
            'type' => 'int',
            'len' => '11',
            'comment' => 'Vendor File Status of the campaign'
        ),
        'send_email' => array(
            'name' => 'send_email',
            'vname' => 'LBL_SEND_EMAIL',
            'type' => 'int',
            'len' => '10',
            'comment' => 'Send Email'
        ),
        'budget' => array(
            'name' => 'budget',
            'vname' => 'LBL_CAMPAIGN_BUDGET',
            'type' => 'float',
            'dbtype' => 'double',
            'comment' => 'Budgeted amount for the campaign'
        ),
        'expected_cost' => array(
            'name' => 'expected_cost',
            'vname' => 'LBL_CAMPAIGN_EXPECTED_COST',
            'type' => 'float',
            'dbtype' => 'double',
            'comment' => 'Expected cost of the campaign'
        ),
        'actual_cost' => array(
            'name' => 'actual_cost',
            'vname' => 'LBL_CAMPAIGN_ACTUAL_COST',
            'type' => 'float',
            'dbtype' => 'double',
            'comment' => 'Actual cost of the campaign'
        ),
        'expected_revenue' => array(
            'name' => 'expected_revenue',
            'vname' => 'LBL_CAMPAIGN_EXPECTED_REVENUE',
            'type' => 'float',
            'dbtype' => 'double',
            'comment' => 'Expected revenue stemming from the campaign'
        ),
        'campaign_type' => array(
            'name' => 'campaign_type',
            'vname' => 'LBL_CAMPAIGN_TYPE',
            'type' => 'enum',
            'options' => 'campaign_type_dom',
            'len' => '25',
            'audited' => true,
            'comment' => 'The type of campaign'
        ),
        'objective' => array(
            'name' => 'objective',
            'vname' => 'LBL_CAMPAIGN_OBJECTIVE',
            'type' => 'text',
            'comment' => 'The objective of the campaign'
        ),
        'content' => array(
            'name' => 'content',
            'vname' => 'LBL_CAMPAIGN_CONTENT',
            'type' => 'text',
            'comment' => 'The campaign description'
        ),
        'prospectlists' => array(
            'name' => 'prospectlists',
            'type' => 'link',
            'relationship' => 'prospect_list_campaigns',
            'source' => 'non-db',
        ),
        'emailmarketing' => array(
            'name' => 'emailmarketing',
            'type' => 'link',
            'relationship' => 'campaign_email_marketing',
            'source' => 'non-db',
        ),
        'queueitems' => array(
            'name' => 'queueitems',
            'type' => 'link',
            'relationship' => 'campaign_emailman',
            'source' => 'non-db',
        ),
        'log_entries' => array(
            'name' => 'log_entries',
            'type' => 'link',
            'relationship' => 'campaign_campaignlog',
            'source' => 'non-db',
        ),
        'tracked_urls' => array(
            'name' => 'tracked_urls',
            'type' => 'link',
            'relationship' => 'campaign_campaigntrakers',
            'source' => 'non-db',
            'vname' => 'LBL_TRACKED_URLS',
        ),
        'assigned_user_link' => array(
            'name' => 'assigned_user_link',
            'type' => 'link',
            'relationship' => 'campaign_assigned_user',
            'vname' => 'LBL_ASSIGNED_TO_USER',
            'link_type' => 'one',
            'module' => 'Users',
            'bean_name' => 'User',
            'source' => 'non-db',
        ),
        'vendors' =>
        array(
            'name' => 'vendors',
            'type' => 'link',
            'relationship' => 'campaign_vendors',
            'source' => 'non-db',
            //'module' => 'TeamsOS',
            //'bean_name' => 'TeamsOS',
            //'source' => 'non-db',
            'vname' => 'LBL_VENDOR',
        ),
    ),
    'indices' => array(
        array(
            'name' => 'campaignspk',
            'type' => 'primary',
            'fields' => array('id')
        ),
        array(
            'name' => 'camp_auto_tracker_key',
            'type' => 'index',
            'fields' => array('tracker_key')
        ),
        array(
            'name' => 'idx_campaign_name',
            'type' => 'index',
            'fields' => array('name')
        ),
    ),
    'relationships' => array(
        'campaign_vendors' => array(
            'lhs_module' => 'Campaigns',
            'lhs_table' => 'campaigns',
            'lhs_key' => 'id',
            'rhs_module' => 'TeamsOS',
            'rhs_table' => 'teams',
            'rhs_key' => 'id',
            'relationship_type' => 'many-to-many',
            'join_table' => 'campaign_vendor',
            'join_key_lhs' => 'campaign_id',
            'join_key_rhs' => 'vendor_id',
            'relationship_role_column' => NULL,
            'relationship_role_column_value' => NULL
        ),
//         'campaign_vendors' => array(
//            'lhs_module' => 'TeamsOS',
//            'lhs_table' => 'teams',
//            'lhs_key' => 'id',
//            'rhs_module' => 'Campaigns',
//             // old configuration
//            'rhs_table' => 'campaigns',
//            'rhs_key' => 'id',
//            'relationship_type' => 'one-to-many',
//            'join_table' => 'campaign_vendor',
//            'join_key_rhs' => 'campaign_id',
//            'join_key_lhs' => 'vendor_id',
//            //'relationship_role_column' => NULL,
//            //'relationship_role_column_value' => NULL
//        ),

        'campaign_email_marketing' => array('lhs_module' => 'Campaigns', 'lhs_table' => 'campaigns', 'lhs_key' => 'id',
            'rhs_module' => 'EmailMarketing', 'rhs_table' => 'email_marketing', 'rhs_key' => 'campaign_id',
            'relationship_type' => 'one-to-many'),
        'campaign_emailman' => array('lhs_module' => 'Campaigns', 'lhs_table' => 'campaigns', 'lhs_key' => 'id',
            'rhs_module' => 'EmailMan', 'rhs_table' => 'emailman', 'rhs_key' => 'campaign_id',
            'relationship_type' => 'one-to-many'),
        'campaign_campaignlog' => array('lhs_module' => 'Campaigns', 'lhs_table' => 'campaigns', 'lhs_key' => 'id',
            'rhs_module' => 'CampaignLog', 'rhs_table' => 'campaign_log', 'rhs_key' => 'campaign_id',
            'relationship_type' => 'one-to-many'),
        'campaign_assigned_user' => array('lhs_module' => 'Users', 'lhs_table' => 'users', 'lhs_key' => 'id',
            'rhs_module' => 'Campaigns', 'rhs_table' => 'campaigns', 'rhs_key' => 'assigned_user_id',
            'relationship_type' => 'one-to-many'),
        'campaign_modified_user' => array('lhs_module' => 'Users', 'lhs_table' => 'users', 'lhs_key' => 'id',
            'rhs_module' => 'Campaigns', 'rhs_table' => 'campaigns', 'rhs_key' => 'modified_user_id',
            'relationship_type' => 'one-to-many'),
    )
);

$dictionary['CampaignVendor'] = array('audited' => true,
    'comment' => 'Campaigns are a series of operations undertaken to accomplish a purpose, usually acquiring leads',
    'table' => 'campaign_vendor',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'vname' => 'LBL_ID',
            'type' => 'id',
            'required' => true,
            'reportable' => false,
            'comment' => 'Unique identifier'
        ),
        'date_entered' => array(
            'name' => 'date_entered',
            'vname' => 'LBL_DATE_ENTERED',
            'type' => 'datetime',
            'comment' => 'Date record created'
        ),
        'date_modified' => array(
            'name' => 'date_modified',
            'vname' => 'LBL_DATE_MODIFIED',
            'type' => 'datetime',
            'comment' => 'Date record last modified'
        ),
        'modified_user_id' => array(
            'name' => 'modified_user_id',
            'rname' => 'user_name',
            'id_name' => 'modified_user_id',
            'vname' => 'LBL_MODIFIED',
            'type' => 'assigned_user_name',
            'table' => 'modified_user_id_users',
            'isnull' => 'false',
            'reportable' => true,
            'dbType' => 'id',
            'comment' => 'User who last modified record'
        ),
        'assigned_user_id' => array(
            'name' => 'assigned_user_id',
            'rname' => 'user_name',
            'id_name' => 'assigned_user_id',
            'vname' => 'LBL_ASSIGNED_TO',
            'type' => 'assigned_user_name',
            'table' => 'users',
            'isnull' => 'false',
            'reportable' => true,
            'dbType' => 'id',
            'audited' => true,
            'comment' => 'User ID assigned to record'
        ),
        'assigned_user_name' => array(
            'name' => 'assigned_user_name',
            'vname' => 'LBL_ASSIGNED_TO',
            'type' => 'varchar',
            'reportable' => false,
            'source' => 'nondb',
            'table' => 'users'
        ),
        'created_by' => array(
            'name' => 'created_by',
            'rname' => 'user_name',
            'id_name' => 'created_by',
            'vname' => 'LBL_CREATED',
            'type' => 'assigned_user_name',
            'table' => 'created_by_users',
            'isnull' => 'false',
            'dbType' => 'id',
            'comment' => 'User ID who created record'
        ),
        'modified_user_link' =>
        array(
            'name' => 'modified_user_link',
            'type' => 'link',
            'relationship' => 'campaign_modified_user',
            'vname' => 'LBL_MODIFIED_BY_USER',
            'link_type' => 'one',
            'module' => 'Users',
            'bean_name' => 'User',
            'source' => 'non-db',
        ),
        'deleted' => array(
            'name' => 'deleted',
            'vname' => 'LBL_CREATED_BY',
            'type' => 'bool',
            'required' => true,
            'reportable' => false,
            'comment' => 'Record deletion indicator'
        ),
        'campaign_id' =>
        array(
            'name' => 'campaign_id',
            'vname' => 'LBL_CAMPAIGN_ID',
            'type' => 'id',
            'len' => '36',
            'audited' => true,
            'required' => false,
            'do_report' => false,
            'reportable' => false,
            'comment' => 'Campaign  Id in use for the join campaign & vendor'
        ),
        'vendor_id' =>
        array(
            'name' => 'vendor_id',
            'vname' => 'LBL_VENDOR_ID',
            'type' => 'id',
            'len' => '36',
            'audited' => true,
            'required' => false,
            'do_report' => false,
            'reportable' => false,
            'comment' => 'Vendor ID in use for the join campaign & vendor'
        ),
        'percentage' =>
        array(
            'name' => 'percentage',
            'vname' => 'LBL_P_ID',
            'type' => 'double',
            'len' => '10,2',
            'audited' => true,
            'required' => false,
            'do_report' => false,
            'reportable' => false,
            'comment' => 'Added Percentage for vendor'
        ),
    ),
    'indices' => array(
        array(
            'name' => 'campaignspk',
            'type' => 'primary',
            'fields' => array('id')
        ),
        array(
            'name' => 'camp_auto_tracker_key',
            'type' => 'index',
            'fields' => array('idx_campaign_id')
        ),
        array(
            'name' => 'idx_campaign_name',
            'type' => 'index',
            'fields' => array('idx_vendor_id')
        ),
    ),
);
?>
