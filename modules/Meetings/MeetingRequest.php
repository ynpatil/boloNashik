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

class MeetingRequest extends SugarBean {
	var $field_name_map = array();
	// Stored fields
	var $date_entered;
	var $description;
	var $location;
	var $id;
	var $name;
	var $parent_id;
	var $parent_type;
	var $parent_name;
	var $custom_fields;

	var $created_by;
	var $created_by_name;

	var $module_dir = 'Meetings';
	var $table_name = "meetings_requests";
	var $object_name = "MeetingRequest";

	var $new_schema = true;

	function MeetingRequest() {
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
		$this->parent_name = '';

		if ($this->parent_type == "Opportunities") {
			require_once("modules/Opportunities/Opportunity.php");
			$parent = new Opportunity();
			$query = "SELECT name , assigned_user_id parent_name_owner from $parent->table_name where id = '$this->parent_id'";

			$result =$this->db->query($query,true, $app_strings['ERR_CREATING_FIELDS']);

			// Get the id and the name.

			$row = $this->db->fetchByAssoc($result);
			if ($row && !empty($row['parent_name_owner'])){
			$this->parent_name_owner = $row['parent_name_owner'];
			$this->parent_name_mod = $this->parent_type;
		}

			if($row != null)
			{
				if ($row['name'] != '') $this->parent_name = stripslashes($row['name']);
			}
		}
		elseif ($this->parent_type == "Cases") {
			require_once("modules/Cases/Case.php");
			$parent = new aCase();
			$query = "SELECT name , assigned_user_id parent_name_owner from $parent->table_name where id = '$this->parent_id'";

			$result =$this->db->query($query,true,$app_strings['ERR_CREATING_FIELDS']);


			// Get the id and the name.

			$row = $this->db->fetchByAssoc($result);

			if ($row && !empty($row['parent_name_owner'])){
			$this->parent_name_owner = $row['parent_name_owner'];
			$this->parent_name_mod = $this->parent_type;
		}
			if($row != null)
			{
				if ($row['name'] != '') $this->parent_name = stripslashes($row['name']);
			}
		}
		elseif ($this->parent_type == "Bugs") {
			require_once("modules/Bugs/Bug.php");
			$parent = new Bug();
			$query = "SELECT name , assigned_user_id parent_name_owner  from $parent->table_name where id = '$this->parent_id'";

			$result =$this->db->query($query,true,$app_strings['ERR_CREATING_FIELDS']);


			// Get the id and the name.

			$row = $this->db->fetchByAssoc($result);

			if ($row && !empty($row['parent_name_owner'])){
			$this->parent_name_owner = $row['parent_name_owner'];
			$this->parent_name_mod = $this->parent_type;
		}
			if($row != null)
			{
				if ($row['name'] != '') $this->parent_name = stripslashes($row['name']);
			}
		}
		elseif ($this->parent_type == "Project") {
			require_once("modules/Project/Project.php");
			$parent = new Project();
			$query = "SELECT name , assigned_user_id parent_name_owner  from $parent->table_name where id = '$this->parent_id'";

			$result =$this->db->query($query,true,$app_strings['ERR_CREATING_FIELDS']);


			// Get the id and the name.

			$row = $this->db->fetchByAssoc($result);

			if ($row && !empty($row['parent_name_owner'])){
			$this->parent_name_owner = $row['parent_name_owner'];
			$this->parent_name_mod = $this->parent_type;
		}
			if($row != null)
			{
				if ($row['name'] != '') $this->parent_name = stripslashes($row['name']);
			}
		}
		elseif ($this->parent_type == "ProjectTask") {
			require_once("modules/ProjectTask/ProjectTask.php");
			$parent = new ProjectTask();
			$query = "SELECT name, assigned_user_id parent_name_owner  from $parent->table_name where id = '$this->parent_id'";

			$result =$this->db->query($query,true,$app_strings['ERR_CREATING_FIELDS']);


			// Get the id and the name.

			$row = $this->db->fetchByAssoc($result);
			if ($row && !empty($row['parent_name_owner'])){
			$this->parent_name_owner = $row['parent_name_owner'];
			$this->parent_name_mod = $this->parent_type;
		}

			if($row != null)
			{
				if ($row['name'] != '') $this->parent_name = stripslashes($row['name']);
			}
		}
		elseif ($this->parent_type == "Accounts") {
			require_once("modules/Accounts/Account.php");
			$parent = new Account();
			$query = "SELECT name, assigned_user_id parent_name_owner  from $parent->table_name where id = '$this->parent_id'";

			$result =$this->db->query($query,true, $app_strings['ERR_CREATING_FIELDS']);


			// Get the id and the name.

			$row = $this->db->fetchByAssoc($result);
			if ($row && !empty($row['parent_name_owner'])){
			$this->parent_name_owner = $row['parent_name_owner'];
			$this->parent_name_mod = $this->parent_type;
		}

			if($row != null)
			{
				if ($row['name'] != '') $this->parent_name = stripslashes($row['name']);
			}
		}
		elseif ($this->parent_type == "Issues") {
        	require_once("modules/Issues/Issue.php");
            $parent = new Issue();

            $query = "SELECT name, assigned_user_id parent_name_owner  from $parent->table_name where id = '$this->parent_id'";
			$result = $this->db->query($query, TRUE, "Error filling in additional detail fields: ");
			$row = $this->db->fetchByAssoc($result);
			if ($row && !empty($row['parent_name_owner'])){
			$this->parent_name_owner = $row['parent_name_owner'];
			$this->parent_name_mod = $this->parent_type;
		}
			if (!is_null($row)) {
 				$this->parent_name = '';
				if (!empty($row['name'])) $this->parent_name .= stripslashes($row['name']);
			}
		}
		elseif ($this->parent_type == "Leads") {
			require_once("modules/Leads/Lead.php");
			$parent = new Lead();
			$query = "SELECT first_name, last_name, assigned_user_id parent_name_owner  from $parent->table_name where id = '$this->parent_id'";

			$result =$this->db->query($query,true, $app_strings['ERR_CREATING_FIELDS']);


			// Get the id and the name.

			$row = $this->db->fetchByAssoc($result);

			if ($row && !empty($row['parent_name_owner'])){
			$this->parent_name_owner = $row['parent_name_owner'];
			$this->parent_name_mod = $this->parent_type;
		}
			if($row != null)
			{
				$this->parent_name = '';
				if ($row['first_name'] != '') $this->parent_name .= stripslashes($row['first_name']). ' ';
				if ($row['last_name'] != '') $this->parent_name .= stripslashes($row['last_name']);
			}
		}

		elseif ($this->parent_type == "Contacts") {
			require_once("modules/Contacts/Contact.php");
			$parent = new Contact();
			$query = "SELECT first_name, last_name, assigned_user_id parent_name_owner  from $parent->table_name where id = '$this->parent_id'";

			$result =$this->db->query($query,true, $app_strings['ERR_CREATING_FIELDS']);


			// Get the id and the name.

			$row = $this->db->fetchByAssoc($result);

			if ($row && !empty($row['parent_name_owner'])){
			$this->parent_name_owner = $row['parent_name_owner'];
			$this->parent_name_mod = $this->parent_type;
		}
			if($row != null)
			{
				$this->parent_name = '';
				if ($row['first_name'] != '') $this->parent_name .= stripslashes($row['first_name']). ' ';
				if ($row['last_name'] != '') $this->parent_name .= stripslashes($row['last_name']);
			}
		}
	}	
}
?>