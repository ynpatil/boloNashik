<?php

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
 * Portions created by SugarCRM are Copyright (C) 2004 SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 * ****************************************************************************** */
$dictionary['Experience'] = array('table' => 'experience_mast',
    'audited' => true, 'unified_search' => true, 'duplicate_merge' => true,
    'comment' => 'Master to maintain Experience records',
    'fields' => array(
        'id' =>
        array(
            'name' => 'id',
            'vname' => 'LBL_ID',
            'type' => 'id',
            'required' => true,
            'reportable' => false,
        ),
        'deleted' =>
        array(
            'name' => 'deleted',
            'vname' => 'LBL_DELETED',
            'type' => 'bool',
            'required' => 'true',
            'default' => '0',
            'reportable' => false,
        ),
        'date_entered' =>
        array(
            'name' => 'date_entered',
            'vname' => 'LBL_DATE_ENTERED',
            'type' => 'datetime',
            'required' => 'true',
        ),
        'date_modified' =>
        array(
            'name' => 'date_modified',
            'vname' => 'LBL_DATE_MODIFIED',
            'type' => 'datetime',
            'required' => 'true',
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
            'dbType' => 'id'
        ),
        'created_by_name' =>
        array(
            'name' => 'created_by_name',
            'vname' => 'LBL_CREATED_BY_NAME',
            'type' => 'relate',
            'reportable' => false,
            'source' => 'nondb',
            'table' => 'users',
            'id_name' => 'created_by',
            'module' => 'Users',
            'duplicate_merge' => 'disabled'
        ),
        'name' =>
        array(
            'name' => 'name',
            'vname' => 'LBL_NAME',
            'type' => 'varchar',
            'audited' => true,
            'len' => '50',
            'ucformat' => true,
        ),
        'exp_min' =>
        array(
            'name' => 'exp_min',
            'vname' => 'LBL_EXP_MIN',
            'type' => 'int',
            'audited' => true,
            'required' => true,
            'len' => '3',            
            'comment' => 'Minimam Experience ',
        ),
        'exp_max' =>
        array(
            'name' => 'max_min',
            'vname' => 'LBL_EXP_MAX',
            'type' => 'int',
            'audited' => true,
            'required' => true,
            'len' => '3',
            'comment' => 'Maximam Experience',
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
    )
    , 'indices' => array(
        array('name' => 'jobprofilepk', 'type' => 'primary', 'fields' => array('id')),
        array('name' => 'idx_jobprofile_name', 'type' => 'index', 'fields' => array('name', 'deleted')),
    )
);
?>
