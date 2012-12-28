<?php
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
 * Portions created by SugarCRM are Copyright (C) 2004 SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/
/*********************************************************************************
 * $Id: Account.php,v 1.136.2.2 2005/05/27 23:31:01 ajay Exp $
 * Description:  Defines the Account SugarBean Account entity with the necessary
 * methods and variables.
 ********************************************************************************/
//om
include_once('config.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
require_once('data/SugarBean.php');

//om
// Account is used to store account information.
class SAPAccount extends SugarBean {
	var $field_name_map = array();
	// Stored fields
	var $date_entered;
	var $date_modified;
	var $modified_user_id;
	var $assigned_user_id;
	var $custom_fields;

	var $created_by;
	var $created_by_name;
	var $modified_by_name;

	// These are for related fields
	var $module_dir = 'SAPAccounts';
	var $table_name = "sap_account_details";
	var $object_name = "SAPAccount";

	var $new_schema = true;

	// This is used to retrieve related fields from form posts.
	var $additional_column_fields = Array();

	// This is the list of fields that are in the lists.
	var $list_fields = Array();

	// This is the list of fields that are required.
	var $required_fields =  array("gp_ref"=>1);
	var $gp_ref;
	var $name1;
	var $ispadrbsdn;
	var $hausn;
	var $stras;
	var $street2;
	var $ort01;
	var $pstlz;
	var $ispteld;
	var $telfx;
	var $ispemail;
	var $isphandy;
	
	function SAPAccount() {
        parent::SugarBean();

		$this->setupCustomFields('SAPAccounts');
		foreach ($this->field_defs as $field)
		{
			$this->field_name_map[$field['name']] = $field;
		}
	}

	function get_summary_text()
	{
		return $this->gp_ref;
	}

	function fill_in_additional_list_fields()
	{
		$this->created_by_name = get_assigned_user_name($this->created_by);
		$this->modified_by_name = get_assigned_user_name($this->modified_user_id);
	}

	function fill_in_additional_detail_fields()
	{
		$this->created_by_name = get_assigned_user_name($this->created_by);
		$this->modified_by_name = get_assigned_user_name($this->modified_user_id);
	}

	function save($check_notify = FALSE)
	{
//		unset($this->id);
		echo "In save of SAPAccount gp_ref= ".$this->gp_ref;

//		if(empty($this->id) || empty($this->gp_ref))
//		{
//			$GLOBALS['log']->debug("Returning -1");
//			return -1;
//		}
		
		$query = "DELETE from ".$this->table_name." where gp_ref='$this->gp_ref' ";
		$this->db->query($query,true);
		
		return parent::save($check_notify);
	}

	function bean_implements($interface){
		switch($interface){
			case 'ACL':return true;
		}
		return false;
	}
}
?>
