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
$dictionary['accounts_brands'] = array ( 'table' => 'accounts_brands'
                                  , 'fields' => array (
       array('name' =>'id', 'type' =>'varchar', 'len'=>'36')
      , array('name' =>'brand_id', 'type' =>'varchar', 'len'=>'36')
      , array('name' =>'account_id', 'type' =>'varchar', 'len'=>'36')
      , array ('name' => 'date_modified','type' => 'datetime')
      , array('name' =>'deleted', 'type' =>'bool', 'len'=>'1', 'required'=>true, 'default'=>'0')
                                                      )                                  , 'indices' => array (
       array('name' =>'accounts_brandspk', 'type' =>'primary', 'fields'=>array('id'))
      , array('name' =>'idx_acc_brand_acc', 'type' =>'index', 'fields'=>array('account_id'))
      , array('name' =>'idx_acc_brand_brand', 'type' =>'index', 'fields'=>array('brand_id'))
      , array('name' => 'idx_account_brand', 'type'=>'alternate_key', 'fields'=>array('account_id','brand_id'))            
      )
      
 	  , 'relationships' => array ('accounts_brands' => array('lhs_module'=> 'Accounts', 'lhs_table'=> 'accounts', 'lhs_key' => 'id',
							  'rhs_module'=> 'Brands', 'rhs_table'=> 'brands', 'rhs_key' => 'id',
							  'relationship_type'=>'many-to-many',
							  'join_table'=> 'accounts_brands', 'join_key_lhs'=>'account_id', 'join_key_rhs'=>'brand_id'))
)
?>
