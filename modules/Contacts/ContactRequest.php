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
 * $Id: Account.php,v 1.173 2006/08/09 18:39:44 jenny Exp $
 * Description:  Defines the Account SugarBean Account entity with the necessary
 * methods and variables.
 ********************************************************************************/
//om
require_once('data/SugarBean.php');

class ContactRequest extends SugarBean {
	var $field_name_map = array();
	// Stored fields
	var $date_entered;
	var $description;
	var $id;
	var $first_name;
	var $last_name;
	var $phone_work;
	var $phone_mobile;
	var $email1;
	var $custom_fields;
	var $account_id;
	var $account_name;
	var $created_by;
	var $created_by_name;

	var $module_dir = 'Contacts';
	var $table_name = "contacts_requests";
	var $object_name = "ContactRequest";

	var $new_schema = true;

	function ContactRequest() {
		//om
		parent::SugarBean();

		foreach ($this->field_defs as $field)
		{
			$this->field_name_map[$field['name']] = $field;
		}
	}
	
	function fill_in_additional_list_fields()
	{
		$this->fill_in_additional_parent_fields();
	}

	function fill_in_additional_parent_fields()
	{
		global $app_strings;
		$this->account_name = '';

		require_once("modules/Accounts/Account.php");
		$parent = new Account();
		$query = "SELECT name from $parent->table_name where id = '$this->account_id'";

		$result =$this->db->query($query,true, $app_strings['ERR_CREATING_FIELDS']);

		// Get the id and the name.

		$row = $this->db->fetchByAssoc($result);
	
		if($row != null)
		{
			if ($row['name'] != '') $this->parent_name = stripslashes($row['name']);
		}
	}
}
?>