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
 * $Id: UsersLastImport.php,v 1.52 2006/06/29 18:32:13 eddy Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/



require_once('data/SugarBean.php');




$imported_ids = array();

// Contact is used to store customer information.
class UsersLastImport extends SugarBean
{
	// Stored fields
	var $id;
	var $assigned_user_id;
	var $bean_type;
	var $bean_id;
	var $module_dir = 'Import';
	var $table_name = "users_last_import";
	var $object_name = "UsersLastImport";
	var $column_fields = Array("id"
		,"assigned_user_id"
		,"bean_type"
		,"bean_id"
		,"deleted"
		);

	var $new_schema = true;

	var $additional_column_fields = Array();

	function UsersLastImport() {
		;
		parent::SugarBean();



	}

	function fill_in_additional_detail_fields()
	{

	}
	
	function listviewACLHelper(){
		$array_assign = parent::listviewACLHelper();
		$is_owner = false;
		if(!ACLController::moduleSupportsACL('Accounts') || ACLController::checkAccess('Accounts', 'view', $is_owner)){
			$array_assign['ACCOUNT'] = 'a';
		}else{
			$array_assign['ACCOUNT'] = 'span';		
		}
		return $array_assign;
	}

	function mark_deleted_by_user_id($user_id)
        {

                $query = "delete from $this->table_name where assigned_user_id='$user_id'";
                $this->db->query($query,true,"Error marking last imported accounts deleted: ");

        }

	function create_list_query($order_by, $where, $show_deleted = 0)
	{
		global $current_user;
		$query = '';

		if ($this->bean_type == 'Contacts')
		{
			$query = "SELECT distinct
				accounts.name as account_name,
				accounts.id as account_id,
				contacts.id,
				contacts.assigned_user_id,
				contacts.first_name,
				contacts.last_name,
				contacts.phone_work,
				contacts.title,
				contacts.email1,
                		users.user_name as assigned_user_name ";



				$query.=" FROM users_last_import,contacts
                                LEFT JOIN users
                                ON contacts.assigned_user_id=users.id
				LEFT JOIN accounts_contacts
				ON contacts.id=accounts_contacts.contact_id
				LEFT JOIN accounts
				ON accounts_contacts.account_id=accounts.id";



				$query.=" WHERE
				users_last_import.assigned_user_id=
					'{$current_user->id}'
				AND users_last_import.bean_type='Contacts'
				AND users_last_import.bean_id=contacts.id
				AND users_last_import.deleted=0
				AND contacts.deleted=0
			";

		}
		if ($this->bean_type == 'Prospects')
		{
			$query = "SELECT distinct
				prospects.id,
				prospects.assigned_user_id,
				prospects.first_name,
				prospects.last_name,
				prospects.phone_work,
				prospects.title,
				prospects.email1,
                                users.user_name as assigned_user_name
				FROM users_last_import,prospects
                                LEFT JOIN users
                                ON prospects.assigned_user_id=users.id
				WHERE
				users_last_import.assigned_user_id=
					'{$current_user->id}'
				AND users_last_import.bean_type='Prospects'
				AND users_last_import.bean_id=prospects.id
				AND users_last_import.deleted=0
				AND prospects.deleted=0
			";

		}
		else if ($this->bean_type == 'Accounts')
		{
			$query = "SELECT distinct accounts.id,accounts.name, 
							accounts.billing_address_city,
							accounts.billing_address_state, 
								accounts.phone_office,
								accounts.assigned_user_id,
                                users.user_name as assigned_user_name ";
                



				
				$query.=" FROM users_last_import,accounts
                LEFT JOIN users ON accounts.assigned_user_id=users.id";
                



				
				$query.="	WHERE
				users_last_import.assigned_user_id=
					'{$current_user->id}'
				AND users_last_import.bean_type='Accounts'
				AND users_last_import.bean_id=accounts.id
				AND users_last_import.deleted=0
				AND accounts.deleted=0";
		}
		else if ($this->bean_type == 'Opportunities')
		{

			$query = "SELECT distinct
                                accounts.id as account_id,
                                accounts.name as account_name,
                                users.user_name as assigned_user_name,
                                opportunities.* ";



			
			$query .= " FROM users_last_import,opportunities
                                LEFT JOIN users
                                ON opportunities.assigned_user_id=users.id
                                LEFT JOIN accounts_opportunities
                                ON opportunities.id=accounts_opportunities.opportunity_id
                                LEFT JOIN accounts
                                ON accounts_opportunities.account_id=accounts.id ";




                                
			$query .= " WHERE
				users_last_import.assigned_user_id=
					'{$current_user->id}'
				AND users_last_import.bean_type='Opportunities'
				AND users_last_import.bean_id=opportunities.id
				AND users_last_import.deleted=0
				AND accounts_opportunities.deleted=0
				AND accounts.deleted=0
				AND opportunities.deleted=0
			";


		}
		else if ($this->bean_type == 'Leads')
		{
                        $query = "SELECT
                                leads.account_name,
                                leads.account_id,
                                leads.status,
                                users.user_name as assigned_user_name,
                                leads.id,
                                leads.first_name,
                                leads.last_name,
                                leads.phone_work,
                                leads.lead_source,
                                leads.title,
                                leads.email1,
                                leads.date_entered
                                FROM users_last_import,leads
                                LEFT JOIN users
                                ON leads.assigned_user_id=users.id
                        	WHERE
				users_last_import.assigned_user_id=
					'{$current_user->id}'
				AND users_last_import.bean_type='Leads'
				AND users_last_import.bean_id=leads.id
				AND users_last_import.deleted=0
                                AND leads.deleted=0 ";

        	}

































































		else if ($this->bean_type == 'Notes')
		{
                        
			if ( ( $this->db->dbType == 'mysql' ) or ( $this->db->dbType == 'oci8' ) )
			{
		        $query = "SELECT notes.*,
					contacts.id as contact_id,
					CONCAT(CONCAT(contacts.first_name, ' ' ), contacts.last_name) as contact_name";
			}
			if( $this->db->dbType == 'mssql' )  // RPS
			{
		        $query = "SELECT notes.*,
					contacts.id as contact_id,
					contacts.first_name + ' ' + contacts.last_name as contact_name";
			}

            $query .= "                                FROM users_last_import,notes
				LEFT JOIN contacts ON 
				contacts.id = notes.contact_id
                        	WHERE
				users_last_import.assigned_user_id=
					'{$current_user->id}'
				AND users_last_import.bean_type='Notes'
				AND users_last_import.bean_id=notes.id
				AND users_last_import.deleted=0
				AND (contacts.deleted IS NULL OR contacts.deleted=0)
                                AND notes.deleted=0 ";

        	}


		if(! empty($order_by))
		{
			$query .= " ORDER BY $order_by";
		}

		return $query;

	}
	function list_view_parse_additional_sections(&$list_form)
	{
                return $list_form;

        }

	function undo($user_id)
	{
		$count = 0;

		$count += $this->undo_contacts($user_id);
		$count += $this->undo_prospects($user_id);
		$count += $this->undo_accounts($user_id);
		$count += $this->undo_opportunities($user_id);
		$count += $this->undo_leads($user_id);






		$count += $this->undo_notes($user_id);

		return $count;
	}

	function undo_contacts($user_id)
	{
		$count = 0;
		$query1 = "select bean_id from users_last_import
		where assigned_user_id='$user_id'
		AND bean_type='Contacts' AND deleted=0";

		$GLOBALS['log']->info($query1);

		$result1 = $this->db->query($query1)
			or sugar_die("Error getting last import for undo");

		while ( $row1 = $this->db->fetchByAssoc($result1))
		{
			$query2 = "delete from contacts where contacts.id='{$row1['bean_id']}'";

			$GLOBALS['log']->info($query2);

			$result2 = $this->db->query($query2)
				or sugar_die("Error undoing last import");

			$count = $this->db->getAffectedRowCount($result2);

			$query3 = "delete from accounts_contacts where accounts_contacts.contact_id='{$row1['bean_id']}' AND accounts_contacts.deleted=0";

			$GLOBALS['log']->info($query3);

			$result3 = $this->db->query($query3)
				or sugar_die("Error undoing last import");

			$query4 = "delete from opportunities_contacts where opportunities_contacts.contact_id='{$row1['bean_id']}' AND opportunities_contacts.deleted=0";

			$GLOBALS['log']->info($query4);

			$result4 = $this->db->query($query4)
				or sugar_die("Error undoing last import");

		}
		return $count;
	}

	function undo_prospects($user_id)
	{
		$count = 0;
		$query1 = "select bean_id from users_last_import
		where assigned_user_id='$user_id'
		AND bean_type='Prospects' AND deleted=0";

		$GLOBALS['log']->info($query1);

		$result1 = $this->db->query($query1)
			or sugar_die("Error getting last import for undo");

		while ( $row1 = $this->db->fetchByAssoc($result1))
		{
			$query2 = "delete from prospects where prospects.id='{$row1['bean_id']}'";

			$GLOBALS['log']->info($query2);

			$result2 = $this->db->query($query2)
				or sugar_die("Error undoing last import");

			$count = $this->db->getAffectedRowCount($result2);

			//delete any associations that prospect has with 
			//a target/prospect list.
			$query3="delete from prospect_lists_prospects where related_id='{$row1['bean_id']}'";
			_pp($query3);
			$result3 = $this->db->query($query3)
				or sugar_die("Error undoing last import");

		}
		return $count;
	}



	function undo_accounts($user_id)
	{
		// this should just be a loop foreach module type
		$count = 0;
		$query1 = "select bean_id from users_last_import
		where assigned_user_id='$user_id'
		AND bean_type='Accounts' AND deleted=0";

		$GLOBALS['log']->info($query1);

		$result1 = $this->db->query($query1)
			or sugar_die("Error getting last import for undo");

		while ( $row1 = $this->db->fetchByAssoc($result1))
		{
			$query2 = "delete from accounts where accounts.id='{$row1['bean_id']}'";

			$GLOBALS['log']->info($query2);

			$result2 = $this->db->query($query2)
				or sugar_die("Error undoing last import");

			$count = $this->db->getAffectedRowCount($result2);

			$query3 = "delete from accounts_contacts where accounts_contacts.account_id='{$row1['bean_id']}' AND accounts_contacts.deleted=0";

			$GLOBALS['log']->info($query3);

			$result3 = $this->db->query($query3)
				or sugar_die("Error undoing last import");

			$query4 = "delete from accounts_opportunities where accounts_opportunities.account_id='{$row1['bean_id']}' AND accounts_opportunities.deleted=0";

			$GLOBALS['log']->info($query4);

			$result4 = $this->db->query($query4)
				or sugar_die("Error undoing last import");

		}
		return $count;
	}

	function undo_opportunities($user_id)
	{
		// this should just be a loop foreach module type
		$count = 0;
		$query1 = "select bean_id from users_last_import
		where assigned_user_id='$user_id'
		AND bean_type='Opportunities' AND deleted=0";

		$GLOBALS['log']->info($query1);

		$result1 = $this->db->query($query1)
			or sugar_die("Error getting last import for undo: ");

		while ( $row1 = $this->db->fetchByAssoc($result1))
		{
			$query2 = "delete from opportunities where opportunities.id='{$row1['bean_id']}'";

			$GLOBALS['log']->info($query2);

			$result2 = $this->db->query($query2)
				or sugar_die("Error undoing last import");

			$count = $this->db->getAffectedRowCount($result2);

			$query3 = "delete from opportunities_contacts where opportunities_contacts.opportunity_id='{$row1['bean_id']}' AND opportunities_contacts.deleted=0";

			$GLOBALS['log']->info($query3);

			$result3 = $this->db->query($query3)
				or sugar_die("Error undoing last import");


			$query4 = "delete from accounts_opportunities where accounts_opportunities.opportunity_id='{$row1['bean_id']}' AND accounts_opportunities.deleted=0";

			$GLOBALS['log']->info($query4);

			$result4 = $this->db->query($query4)
				or sugar_die("Error undoing last import");

		}
		return $count;
	}

        function undo_leads($user_id)
        {
                // this should just be a loop foreach module type
                $count = 0;
                $query1 = "select bean_id from users_last_import
                where assigned_user_id='$user_id'
                AND bean_type='Leads' AND deleted=0";

                $GLOBALS['log']->info($query1);

                $result1 = $this->db->query($query1)
                        or sugar_die("Error getting last import for undo");

                while ( $row1 = $this->db->fetchByAssoc($result1))
                {
                        $query2 = "delete from leads where leads.id='{$row1['bean_id']}'";

                        $GLOBALS['log']->info($query2);

                        $result2 = $this->db->query($query2)
                                or sugar_die("Error undoing last import");

                        $count = $this->db->getAffectedRowCount($result2);

		}
		return $count;
	}





















































































































        function undo_notes($user_id)
        {
                // this should just be a loop foreach module type
                $count = 0;
                $query1 = "select bean_id from users_last_import
                where assigned_user_id='$user_id'
                AND bean_type='Notes' AND deleted=0";

                $GLOBALS['log']->info($query1);

                $result1 = $this->db->query($query1)
                        or sugar_die("Error getting last import for undo: ");

                while ( $row1 = $this->db->fetchByAssoc($result1))
                {
                        $query2 = "delete from notes where notes.id='{$row1['bean_id']}'";

                        $GLOBALS['log']->info($query2);

                        $result2 = $this->db->query($query2)
                                or sugar_die("Error undoing last import: ");

                        $count = $this->db->getAffectedRowCount($result2);

		}
		return $count;
	}

}
?>
