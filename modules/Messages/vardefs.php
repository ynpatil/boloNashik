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
$dictionary['Message'] = array('table' => 'messages'
                               ,'fields' => array (
  'id' =>
  array (
    'name' => 'id',
    'vname' => 'LBL_MESSAGE_ID',
    'type' => 'varchar',
    'len' => '36',
    'required'=>true,
    'reportable'=>false,
  ),

  'message_name' =>
  array (
    'name' => 'message_name',
    'vname' => 'LBL_NAME',
    'type' => 'varchar',
    'len' => '255',
    'required'=>true,
    'ucformat' => true, 
  ),
'active_date' =>
  array (
    'name' => 'active_date',
    'vname' => 'LBL_DOC_ACTIVE_DATE',
    'type' => 'date',
  ),

'exp_date' =>
  array (
    'name' => 'exp_date',
    'vname' => 'LBL_DOC_EXP_DATE',
    'type' => 'date',
  ),

  'description' =>
  array (
    'name' => 'description',
    'vname' => 'LBL_DESCRIPTION',
    'type' => 'text',
  ),

  'category_id' =>
  array (
    'name' => 'category_id',
    'vname' => 'LBL_SF_CATEGORY',
    'type' => 'enum',
    'len' => '25',
    'options' => 'message_category_dom',
    'reportable'=>false,
  ),

  'date_entered' =>
  array (
    'name' => 'date_entered',
    'vname' => 'LBL_DATE_ENTERED',
    'type' => 'datetime',
  ),

  'date_modified' =>
  array (
    'name' => 'date_modified',
    'vname' => 'LBL_DATE_MODIFIED',
    'type' => 'datetime',
  ),

  'deleted' =>
  array (
    'name' => 'deleted',
    'vname' => 'LBL_DELETED',
    'type' => 'bool',
    'default'=> 0,
    'reportable'=>false,
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
    'reportable'=>true,
    'dbType' => 'id'
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


 'created_by_name' =>
  array (
	    'name' => 'created_by_name',
	    'rname'=>'user_name',
	    'vname' => 'LBL_CREATED',
	    'type' => 'relate',
	    'reportable'=>false,
	    'source'=>'nondb',
	    'link'=>'created_by_link'
 ),

 'status_id' =>
    array (
      'name' => 'status_id',
      'rname' => 'status_id',
      'vname' => 'LBL_STATUS',
      'type' => 'relate',
      'reportable'=>false,
      'source'=>'nondb',
	  'link'=>'status_id_link'
  ),

'user_id' =>
    array (
      'name' => 'user_id',
      'rname' => 'user_id',
      'vname' => 'LBL_STATUS',
      'type' => 'relate',
      'reportable'=>false,
      'source'=>'nondb',
	  'link'=>'user_id_link'
  ),
 'status_id_link' =>
   array (
     'name' => 'status_id_link',
     'type' => 'link',
     'relationship' => 'messages_users',
     'vname' => 'LBL_STATUS',
     'link_type' => 'one',
     'module'=>'Messages',
     'bean_name'=>'Message',
     'source'=>'non-db',
  ),
  'assigned_user_name' =>
  array (
    'name' => 'assigned_user_name',
    'vname' => 'LBL_ASSIGNED_TO_NAME',
    'type' => 'relate',
    'reportable'=>false,
    'source'=>'nondb',
    'table' => 'users',
    'id_name' => 'assigned_user_id',
    'module'=>'Users',
    'duplicate_merge'=>'disabled'

  ),
  'created_by_link' =>
  array (
    'name' => 'created_by_link',
    'type' => 'link',
    'relationship' => 'messages_created_by',
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
    'relationship' => 'messages_modified_user',
    'vname' => 'LBL_MODIFIED_BY_USER',
    'link_type' => 'one',
    'module'=>'Users',
    'bean_name'=>'User',
    'source'=>'non-db',
  ),

//BEGIN field used for contract document subpanel.
  'linked_id'=>
  array (
    'name' => 'linked_id',
    'type' => 'varchar',
    'reportable'=>false,
    'source'=>'non-db'
  ),

  'filename' =>
  array (
    'name' => 'filename',
    'vname' => 'LBL_FILENAME',
    'type' => 'varchar',
    'required'=>true,
    'len' => '255',
  ),
  'file_ext' =>
  array (
    'name' => 'file_ext',
    'vname' => 'LBL_FILE_EXTENSION',
    'type' => 'varchar',
    'len' => '25',
  ),
  'file_mime_type' =>
  array (
    'name' => 'file_mime_type',
    'vname' => 'LBL_MIME',
    'type' => 'varchar',
    'len' => '100',
  ),
),
 'indices' => array (
       array('name' =>'messagespk', 'type' =>'primary', 'fields'=>array('id')),
       array('name' =>'idx_doc_cat', 'type' =>'index', 'fields'=>array('category_id')),
       ),
);
?>
