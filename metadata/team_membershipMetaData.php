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
$dictionary['team_membership'] = array (

	'table' => 'team_membership',

	'fields' => array (
		array (
			'name' => 'id',
			'type' => 'varchar',
			'len' => '36',
		),
		array (
			'name' => 'team_id',
			'type' => 'varchar',
			'len' => '36',
		),
		array (
			'name' => 'user_id',
			'type' => 'varchar',
			'len' => '36',
		),
		array (
			'name' => 'date_modified',
			'type' => 'datetime'),
		array (
			'name' => 'deleted',
			'type' => 'bool',
			'len' => '1',
			'default' => '0'
		),
	),
	
	'indices' => array (
		array (
			'name' => 'team_membership_pk',
			'type' => 'primary',
			'fields' => array ('id')
		),
		array (
			'name' => 'idx_team_id',
			'type' => 'index',
			'fields' => array ('team_id')
		),
		array (
			'name' => 'idx_user_id',
			'type' => 'index',
			'fields' => array ('user_id')
		),
	),

	'relationships' => array (
		'team_membership' => array(
			'lhs_module'=> 'TeamsOS',
			'lhs_table'=> 'teams',
			'lhs_key' => 'id',
			'rhs_module'=> 'Users',
			'rhs_table'=> 'users',
			'rhs_key' => 'id',
			'relationship_type'=> 'many-to-many',
			'join_table'=> 'team_membership',
			'join_key_lhs'=> 'team_id',
			'join_key_rhs'=> 'user_id'
		)
	),
)
?>
