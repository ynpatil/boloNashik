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
/*********************************************************************************
 * $Id: field_arrays.php,v 1.10 2006/08/12 07:20:04 chris Exp $
 * Description:  Contains field arrays that are used for caching
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
$fields_array['Access'] = array (
	'column_fields' => array(
		'id'
		,'user_id'
		,'access_to_user_id'
		,'access_to_module'
		,'date_entered'
		,'date_modified'
		,'modified_user_id'
		,'created_by'
		,'deleted'
	),
	'list_fields' => array(
		'id'
		,'full_name'
		,'access_to_module'
	),
);

$fields_array['User'] = array (
	'column_fields' => array(
		'id',
		'full_name',
		'user_name'
		,'user_hash'
		,'first_name'
		,'last_name'
		,'description'
		,'date_entered'
		,'date_modified'
		,'modified_user_id'
		, 'created_by'
		,'title'
		,'department'
		,'is_admin'
		,'is_superuser'
		,'phone_home'
		,'phone_mobile'
		,'phone_work'
		,'phone_other'
		,'phone_fax'
		,'email1'
		,'email2'
		,'address_street'
		,'address_city'
		,'address_state'
		,'address_postalcode'
		,'address_country'
		,'reports_to_id'
		,'portal_only'
		,'status'
		,'receive_notifications'
		,'employee_status'
		,'messenger_id'
		,'messenger_type'
		,'is_group'
        ,'suboffice_id'
        ,'usertype_id'
        ,'verticals_id',
	),
    'list_fields' => array(
    	'full_name',
		'id', 
		'first_name', 
		'last_name', 
		'user_name', 
		'status', 
		'department', 
		'is_admin', 
		'email1', 
		'phone_work', 
		'title', 
		'reports_to_name', 
		'reports_to_id', 
		'is_group'
	),
	'export_fields' => array(
		'id',
		'user_name'
		,'first_name'
		,'last_name'
		,'description'
		,'date_entered'
		,'date_modified'
		,'modified_user_id'
		,'created_by'
		,'title'
		,'department'
		,'is_admin'
		,'phone_home'
		,'phone_mobile'
		,'phone_work'
		,'phone_other'
		,'phone_fax'
		,'email1'
		,'email2'
		,'address_street'
		,'address_city'
		,'address_state'
		,'address_postalcode'
		,'address_country'
		,'reports_to_id'
		,'portal_only'
		,'status'
		,'receive_notifications'
		,'employee_status'
		,'messenger_id'
		,'messenger_type'
		,'is_group'



	),
);

$fields_array['UserSignature'] = array(
	'column_fields' => array(
		'id',
		'date_entered',
		'date_modified',
		'deleted',
		'user_id',
		'name',
		'signature',
	),
	'list_fields' => array(
		'id',
		'date_entered',
		'date_modified',
		'deleted',
		'user_id',
		'name',
		'signature',
	),
);
?>
