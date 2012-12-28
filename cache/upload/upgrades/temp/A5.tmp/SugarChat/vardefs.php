<?php
include('modules/SugarChat/NewEntryPoint.php');
/*****************************************************************************
 * The contents of this file are subject to the RECIPROCAL PUBLIC LICENSE
 * Version 1.1 ("License"); You may not use this file except in compliance
 * with the License. You may obtain a copy of the License at
 * http://opensource.org/licenses/rpl.php. Software distributed under the
 * License is distributed on an "AS IS" basis, WITHOUT WARRANTY OF ANY KIND,
 * either express or implied.
 *
 * You may:
 * a) Use and distribute this code exactly as you received without payment or
 *    a royalty or other fee.
 * b) Create extensions for this code, provided that you make the extensions
 *    publicly available and document your modifications clearly.
 * c) Charge for a fee for warranty or support or for accepting liability
 *    obligations for your customers.
 *
 * You may NOT:
 * a) Charge for the use of the original code or extensions, including in
 *    electronic distribution models, such as ASP (Application Service
 *    Provider).
 * b) Charge for the original source code or your extensions other than a
 *    nominal fee to cover distribution costs where such distribution
 *    involves PHYSICAL media.
 * c) Modify or delete any pre-existing copyright notices, change notices,
 *    or License text in the Licensed Software
 * d) Assert any patent claims against the Licensor or Contributors, or
 *    which would in any way restrict the ability of any third party to use the
 *    Licensed Software.
 *
 * You must:
 * a) Document any modifications you make to this code including the nature of
 *    the change, the authors of the change, and the date of the change.
 * b) Make the source code for any extensions you deploy available via an
 *    Electronic Distribution Mechanism such as FTP or HTTP download.
 * c) Notify the licensor of the availability of source code to your extensions
 *    and include instructions on how to acquire the source code and updates.
 * d) Grant Licensor a world-wide, non-exclusive, royalty-free license to use,
 *    reproduce, perform, modify, sublicense, and distribute your extensions.
 *
 * The Original Code is: CommuniCore
 *                       Olavo Farias
 *                       2006-04-7 olavo.farias@gmail.com
 *
 * The Initial Developer of the Original Code is CommuniCore.
 * Portions created by CommuniCore are Copyright (C) 2005 CommuniCore Ltda
 * All Rights Reserved.
 ********************************************************************************/
/*******************************************************************************
 * Table definition file for the simple table
 *******************************************************************************/

$dictionary['SugarChat'] = array(
'table'    => 'sugarchat',
 'fields'   => array(
  'id'                 => array(
   'name'               => 'id',
   'vname'              => 'LBL_ID',
   'required'           => true,
   'type'               => 'id',
   'reportable'         => false,
  ),
  'date_entered'       => array(
   'name'               => 'date_entered',
   'vname'              => 'LBL_DATE_ENTERED',
   'type'               => 'datetime',
   'required'           => true,
  ),
  'date_modified'      => array(
   'name'               => 'date_modified',
   'vname'              => 'LBL_DATE_MODIFIED',
   'type'               => 'datetime',
   'required'           => true,
  ),
  'assigned_user_id'   => array(
   'name'               => 'assigned_user_id',
   'rname'              => 'user_name',
   'id_name'            => 'assigned_user_id',
   'type'               => 'assigned_user_name',
   'vname'              => 'LBL_ASSIGNED_USER_ID',
   'required'           => false,
   'len'                => 36,
   'dbType'             => 'id',
   'table'              => 'users',
   'isnull'             => false,
   'reportable'         => true,
  ),
  'modified_user_id'   => array(
   'name'               => 'modified_user_id',
   'rname'              => 'user_name',
   'id_name'            => 'modified_user_id',
   'vname'              => 'LBL_MODIFIED_USER_ID',
   'type'               => 'assigned_user_name',
   'table'              => 'users',
   'isnull'             => 'false',
   'dbType'             => 'id',
   'reportable'         => true,
  ),
  'created_by'         => array(
   'name'               => 'created_by',
   'rname'              => 'user_name',
   'id_name'            => 'modified_user_id',
   'vname'              => 'LBL_CREATED_BY',
   'type'               => 'assigned_user_name',
   'table'              => 'users',
   'isnull'             => 'false',
   'dbType'             => 'id',
  ),
  'name'               => array(
   'name'               => 'name',
   'vname'              => 'LBL_NAME',
   'required'           => true,
   'dbType'             => 'varchar',
   'type'               => 'name',
   'len'                => 50,
  ),
  'description'        => array(
   'name'               => 'description',
   'vname'              => 'LBL_DESCRIPTION',
   'required'           => false,
   'type'               => 'text',
  ),
  'deleted'            => array(
   'name'               => 'deleted',
   'vname'              => 'LBL_DELETED',
   'type'               => 'bool',
   'required'           => true,
   'default'            => '0',
  ), 
  'created_by_link'    => array (
   'name'               => 'created_by_link',
   'type'               => 'link',
   'relationship'       => 'SugarChat_created_by',
   'vname'              => 'LBL_CREATED_BY_USER',
   'link_type'          => 'one',
   'module'             => 'Users',
   'bean_name'          => 'User',
   'source'             => 'non-db',
  ),
  'modified_user_link' => array (
   'name'               => 'modified_user_link',
   'type'               => 'link',
   'relationship'       => 'SugarChat_modified_user',
   'vname'              => 'LBL_MODIFIED_BY_USER',
   'link_type'          => 'one',
   'module'             => 'Users',
   'bean_name'          => 'User',
   'source'             => 'non-db',
  ),
  'assigned_user_link' => array (
   'name'               => 'assigned_user_link',
   'type'               => 'link',
   'relationship'       => 'SugarChat_assigned_user',
   'vname'              => 'LBL_ASSIGNED_TO_USER',
   'link_type'          => 'one',
   'module'             => 'Users',
   'bean_name'          => 'User',
   'source'             => 'non-db',
  ),
  'assigned_user_name' => array (
   'name'               => 'assigned_user_name',
   'rname'              => 'user_name',
   'id_name'            => 'assigned_user_id',
   'vname'              => 'LBL_ASSIGNED_USER_NAME',
   'type'               => 'relate',
   'table'              => 'users',
   'module'             => 'Users',
   'dbType'             => 'varchar',
   'link'               => 'users',
   'len'                => '255',
   'source'             => 'non-db',
  ), 
//BUILDER: included fields
//BUILDER:END of fields 
 ),
 'indices' => array(
  array(
   'name'               =>'SugarChat_primary_key_index',
   'type'               =>'primary',
   'fields'             =>array('id')
  ),
//BUILDER:END of indices
 ),
//Relationships one-to-many
 'relationships' => array(
  'SugarChat_assigned_user' => array(
   'lhs_module'=> 'Users',  'lhs_table'=> 'users',  'lhs_key' => 'id',
   'rhs_module'=> 'SugarChat', 'rhs_table'=> 'sugarchat', 'rhs_key' => 'assigned_user_id',
   'relationship_type'=>'one-to-many'),

  'SugarChat_modified_user' => array(
   'lhs_module'=> 'Users',  'lhs_table'=> 'users',  'lhs_key' => 'id',
   'rhs_module'=> 'SugarChat', 'rhs_table'=> 'sugarchat', 'rhs_key' => 'modified_user_id',
   'relationship_type'=>'one-to-many'),

  'SugarChat_created_by' => array(
   'lhs_module'=> 'Users',  'lhs_table'=> 'users',  'lhs_key' => 'id',
   'rhs_module'=> 'SugarChat', 'rhs_table'=> 'sugarchat', 'rhs_key' => 'created_by',
   'relationship_type'=>'one-to-many'),
//BUILDER:END of relationships
 ),
);
?>
