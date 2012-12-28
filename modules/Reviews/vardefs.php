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
$dictionary['Review'] = array('table' => 'reviews','unified_search' => true,
                               'fields' => array (
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

  'name' =>
  array (
    'name' => 'name',
    'vname' => 'LBL_SUBJECT',
    'dbType' => 'varchar',
    'type' => 'name',
    'len' => '50',
    'unified_search' => true,
    'ucformat' => true,
  ),
  'status' =>
  array (
    'name' => 'status',
    'vname' => 'LBL_STATUS',
    'type' => 'enum',
    'options' => 'review_status_dom',
    'len'=>25,
  ),
 'parent_type'=>
  array(
  	'name'=>'parent_type',
  	'vname'=>'LBL_LIST_RELATED_TO',
  	'type'=>'varchar',
  	'required'=>false,
    'reportable'=>false,
  	'len'=>25,
      'comment' => 'The Sugar object to which the review is related'
  	),
  'parent_id'=>
  array(
  	'name'=>'parent_id',
  	'vname'=>'LBL_LIST_RELATED_TO',
  	'type'=>'id',
	'reportable'=>false,
    'comment' => 'The ID of the parent Sugar object identified by parent_type'
  	),
   'description' =>
  array (
    'name' => 'description',
    'vname' => 'LBL_DESCRIPTION',
    'type' => 'text',
  ),
  'advice' =>
  array (
    'name' => 'advice',
    'vname' => 'LBL_ADVICE',
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
  'created_by_link' =>
  array (
        'name' => 'created_by_link',
    'type' => 'link',
    'relationship' => 'reviews_created_by',
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
    'relationship' => 'reviews_modified_user',
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
    'relationship' => 'reviews_assigned_user',
    'vname' => 'LBL_ASSIGNED_TO_USER',
    'link_type' => 'one',
    'module'=>'Users',
    'bean_name'=>'User',
    'source'=>'non-db',
  ),
  'calls_reviews' =>
    array (
    	'name' => 'calls_reviews',
      'type' => 'link',
      'relationship' => 'calls_reviews',
      'module'=>'Reviews',
      'bean_name'=>'Review',
      'source'=>'non-db',
  	'vname'=>'LBL_REVIEWS',
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
  )
,
 'relationships' => array (

  'reviews_assigned_user' =>
   array('lhs_module'=> 'Users', 'lhs_table'=> 'users', 'lhs_key' => 'id',
   'rhs_module'=> 'Reviews', 'rhs_table'=> 'reviews', 'rhs_key' => 'assigned_user_id',
   'relationship_type'=>'one-to-many')

   ,'reviews_modified_user' =>
   array('lhs_module'=> 'Users', 'lhs_table'=> 'users', 'lhs_key' => 'id',
   'rhs_module'=> 'Reviews', 'rhs_table'=> 'reviews', 'rhs_key' => 'modified_user_id',
   'relationship_type'=>'one-to-many')

   ,'reviews_created_by' =>
   array('lhs_module'=> 'Users', 'lhs_table'=> 'users', 'lhs_key' => 'id',
   'rhs_module'=> 'Reviews', 'rhs_table'=> 'reviews', 'rhs_key' => 'created_by',
   'relationship_type'=>'one-to-many')   
   , 
)
      , 'indices' => array (
       array('name' =>'reviewspk', 'type' =>'primary', 'fields'=>array('id')),
       array('name' =>'idx_review_name', 'type'=>'index', 'fields'=>array('name')),
       array('name' =>'idx_review_par_del', 'type'=>'index', 'fields'=>array('parent_id','parent_type','deleted')),
		 array('name' =>'idx_review_assigned', 'type'=>'index', 'fields'=>array('assigned_user_id')),
             )

        //This enables optimistic locking for Saves From EditView
	,'optimistic_locking'=>true,
);
?>
