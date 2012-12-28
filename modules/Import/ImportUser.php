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
 * $Id: ImportContact.php,v 1.32 2006/06/06 17:58:21 majed Exp $
 * Description:  TODO: To be written.
 ********************************************************************************/


require_once('modules/Users/User.php');
require_once('modules/Import/UsersLastImport.php');

global $app_list_strings;

// Contact is used to store customer information.
class ImportUser extends User {
	// these are fields that may be set on import
	// but are to be processed and incorporated
	// into fields of the parent class
	var $db;

	var $required_fields =  array("first_name"=>1,"last_name"=>2,"user_name"=>3);
       // This is the list of the functions to run when importing
        var $special_functions =  array(
		"check_user_name_duplicate",
		"set_defaults"
		);

		function set_defaults()
		{
			$this->user_hash = strtolower(md5('init'));
			$this->status = 'Active';
		}

        function check_user_name_duplicate()
        {
		// global is defined in UsersLastImport.php
		global $imported_ids;
                global $current_user;

		if ( empty($this->user_name))
		{
			sugar_die("Error no user name specified ");
		}

		$user_name = $this->user_name;

		$this->suboffice_id = trim($this->suboffice_id);
		$this->verticals_id = trim($this->verticals_id);

		$query = "select id from suboffice_mast where name='$this->suboffice_id'";
        $result = $this->db->query($query);
        $row = $this->db->fetchByAssoc($result, -1, false);

		$this->suboffice_id = $row['id'];

		$query = "select id from verticals_mast where name='$this->verticals_id'";
        $result = $this->db->query($query);
        $row = $this->db->fetchByAssoc($result, -1, false);

		$this->verticals_id = $row['id'];

		$query = "select id from usertype_mast where name='$this->usertype_id'";
        $result = $this->db->query($query);
        $row = $this->db->fetchByAssoc($result, -1, false);

		$this->usertype_id = $row['id'];

		$query = "select id from users where user_name='$this->reports_to_id'";
        $result = $this->db->query($query);
        $row = $this->db->fetchByAssoc($result, -1, false);

		$this->reports_to_id = $row['id'];

		$query = "select id from acl_roles where name='general'";
        $result = $this->db->query($query);
        $row = $this->db->fetchByAssoc($result, -1, false);

//		$rel_name = "acl_roles_users";
//		$this->load_relationship($rel_name);
//		$this->$rel_name->add($row['id']);

		// check if it already exists
        $focus = new User();

       	$query = "select * from {$focus->table_name} WHERE user_name='{$user_name}'";

        $GLOBALS['log']->info($query);

        $result = $this->db->query($query);
        $row = $this->db->fetchByAssoc($result, -1, false);

		// we found a row with that id
                if (isset($row['id']) && $row['id'] != -1)
                {
                        // if it exists but was deleted, just remove it entirely
                        if ( isset($row['deleted']) && $row['deleted'] == 1)
                        {
//                                $query2 = "delete from {$focus->table_name} WHERE id='". PearDatabase::quote($row['id'])."'";

                                $GLOBALS['log']->info($query2);

                                $result2 = $this->db->query($query2)
                                        or sugar_die("Error deleting existing sugarbean: ");

                        }

			// else just use this id to link the user to the contact
                        else
                        {
							sugar_die("User Name already exists ".$user_name);
                        }
                }
        }

	//removed importable_fields, this array is now generated in the import wizard. and The array is based
	//on the meta defined in the vardef file for the contacts module.

	//module prefix used by ImportSteplast when calling ListView.php
	var $list_view_prefix = 'USER';

	//columns to be displayed in listview for displaying user's last import in ImportSteplast.php
	var $list_fields = Array(
	  		'id',
			'first_name',
			'last_name',
			'user_name',
			'suboffice_id','usertype_id',
			'verticals_id');

	//this list defines what beans get populated during an import of contacts
	var $related_modules = array("Roles",);

	function ImportUser() {
		parent::User();
	}

	function create_list_query($order_by, $where, $show_deleted = 0)
	{
		global $current_user;
		$query = '';

			$query = "SELECT distinct
				users.first_name as first_name,
				users.last_name as last_name,
				users.id as user_id ";

				$query.=" FROM users_last_import,users ";

				$query.=" WHERE
				users_last_import.assigned_user_id=
					'{$current_user->id}'
				AND users_last_import.bean_type='Users'
				AND users_last_import.bean_id=users.id
				AND users_last_import.deleted=0
				AND users.deleted=0
			";
		if(! empty($order_by))
		{
			$query .= " ORDER BY $order_by";
		}

		return $query;
	}
}
?>