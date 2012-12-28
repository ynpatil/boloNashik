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
$dictionary['SAPAccount'] = array('table' => 'sap_account_details'
                               ,'fields' => array (
  'id' =>
  array (
    'name' => 'id',
    'vname' => 'LBL_ID',
    'required'=>true,
    'reportable'=>false,
    'type' => 'id',
  ),
   'date_entered' =>
  array (
    'name' => 'date_entered',
    'vname' => 'LBL_DATE_ENTERED',
    'type' => 'datetime',
    'required'=>true
  ),
  'date_modified' =>
  array (
    'name' => 'date_modified',
    'vname' => 'LBL_DATE_MODIFIED',
    'type' => 'datetime',
    'required'=>true,
  ),
    'modified_user_id' =>
  array (
    'name' => 'modified_user_id',
    'rname' => 'user_name',
    'id_name' => 'modified_user_id',
    'vname' => 'LBL_MODIFIED',
    'type' => 'assigned_user_name',
    'table' => 'modified_user_id_users',
    'reportable'=>true,
    'isnull' => 'false',
    'dbType' => 'id',
    'required'=> true,
    'default' =>'',
    'len' => 36,
  ),
    'created_by' =>
  array (
    'name' => 'created_by',
    'rname' => 'user_name',
    'id_name' => 'created_by',
    'vname' => 'LBL_CREATED',
    'type' => 'assigned_user_name',
    'table' => 'created_by_users',
    'isnull' => 'false',
    'dbType' => 'id',
    'len' => 36,
  ),
   'name1' =>
  array (
    'name' => 'name1',
    'type' => 'varchar',
    'vname' => 'LBL_NAME',
    'len' => 150,
  ),
   'gp_ref' =>
  array (
    'name' => 'gp_ref',
    'type' => 'varchar',
    'vname' => 'LBL_SAP_ACCOUNT_CODE',
    'len' => 10,
  ),
  'ispemail' =>
  array (
    'name' => 'ispemail',
    'vname' => 'LBL_EMAIL_ADDRESS',
    'type' => 'email',
    'dbType' => 'varchar',
    'len' => 50,
    'comment' => 'Email Address specified in SAP System',
  ),
  'telfx' =>
  array (
    'name' => 'telfx',
    'vname' => 'LBL_PHONE_FAX',
    'type' => 'phone',
    'dbType' => 'varchar',
    'len' => 31,
    'unified_search' => false,
    'comment' => 'The fax phone number specified in SAP System',
  ),
  'ispteld' =>
  array (
    'name' => 'ispteld',
    'vname' => 'LBL_PHONE_FAX',
    'type' => 'phone',
    'dbType' => 'varchar',
    'len' => 20,
    'unified_search' => false,
    'comment' => 'The fax phone number specified in SAP System',
  ),
  'phone_fax' =>
  array (
    'name' => 'phone_fax',
    'vname' => 'LBL_PHONE_FAX',
    'type' => 'phone',
    'dbType' => 'varchar',
    'len' => 25,
    'unified_search' => true,
    'comment' => 'The fax phone number of this account',
  ),
  'isphandy' =>
  array (
    'name' => 'isphandy',
    'vname' => 'LBL_PHONE_MOBILE',
    'type' => 'phone',
    'dbType' => 'varchar',
    'len' => 25,
    'unified_search' => true,
    'comment' => 'The fax phone number of this account',
  ),
  'hausn' =>
  array (
    'name' => 'hausn',
    'vname' => 'LBL_BILLING_ADDRESS',
    'type' => 'varchar',
    'len' => 10,
  ),
  'stras' =>
  array (
    'name' => 'stras',
    'vname' => 'LBL_BILLING_ADDRESS',
    'type' => 'varchar',
    'len' => 35,
  ),
  'street2' =>
  array (
    'name' => 'street2',
    'vname' => 'LBL_BILLING_ADDRESS',
    'type' => 'varchar',
    'len' =>35,
  ),
  'ort01' =>
  array (
    'name' => 'ort01',
    'vname' => 'LBL_BILLING_ADDRESS',
    'type' => 'varchar',
    'len' => 35,
  ),
  'pstlz' =>
  array (
    'name' => 'pstlz',
    'vname' => 'LBL_BILLING_ADDRESS',
    'type' => 'varchar',
    'len' => 10,
  ), 

  )
);

?>
