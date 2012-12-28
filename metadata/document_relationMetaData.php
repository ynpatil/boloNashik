<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * Table definition file for the project_relation table
 *
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
 */

// $Id: document_relationMetaData.php,v 1.6 2006/08/19 01:02:51 majed Exp $

$dictionary['document_relation'] = array(
	'table' => 'document_relation',
	'fields' => array(
		'id' => array(
			'name' => 'id',
			'vname' => 'LBL_ID',
			'required' => true,
			'type' => 'id',
		),
		'document_id' => array(
			'name' => 'document_id',
			'vname' => 'LBL_DOCUMENT_ID',
			'required' => true,
			'type' => 'id',
		),
		'relation_id' => array(
			'name' => 'relation_id',
			'vname' => 'LBL_DOCUMENT_NAME',
			'required' => true,
			'type' => 'id',
		),
		'relation_type' => array(
			'name' => 'relation_type',
			'vname' => 'LBL_DOCUMENT_NAME',
			'required' => true,
			'type' => 'enum',
			'options' => 'document_relation_type_options',
		),
		'deleted' => array(
			'name' => 'deleted',
			'vname' => 'LBL_DELETED',
			'type' => 'bool',
			'required' => true,
			'default' => '0',
		),
	    'date_modified' => array (
    		'name' => 'date_modified',
    		'vname' => 'LBL_DATE_MODIFIED',
    		'type' => 'datetime',
    		'required'=>true,
  		),
	),
	'indices' => array(
		array(
			'name' =>'document_rel_pk',
			'type' =>'primary',
			'fields'=>array('id')
		),
	),

 	'relationships' => 
 		array ('documents_accounts' => array('lhs_module'=> 'Accounts', 'lhs_table'=> 'accounts', 'lhs_key' => 'id',
		'rhs_module'=> 'Documents', 'rhs_table'=> 'documents', 'rhs_key' => 'id',
		'relationship_type'=>'many-to-many',
		'join_table'=> 'document_relation', 'join_key_lhs'=>'relation_id', 'join_key_rhs'=>'document_id',
		'relationship_role_column'=>'relation_type','relationship_role_column_value'=>'Accounts'),
		'documents_contacts' => array('lhs_module'=> 'Contacts', 'lhs_table'=> 'contacts', 'lhs_key' => 'id',
		'rhs_module'=> 'Documents', 'rhs_table'=> 'documents', 'rhs_key' => 'id',
		'relationship_type'=>'many-to-many',
		'join_table'=> 'document_relation', 'join_key_lhs'=>'relation_id', 'join_key_rhs'=>'document_id',
		'relationship_role_column'=>'relation_type','relationship_role_column_value'=>'Contacts'),
		
		),
);

?>
