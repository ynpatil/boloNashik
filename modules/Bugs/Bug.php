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
 * $Id: Bug.php,v 1.59 2006/08/21 00:45:49 wayne Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/




require_once('data/SugarBean.php');
require_once('modules/Contacts/Contact.php');
require_once('modules/Tasks/Task.php');
require_once('modules/Notes/Note.php');
require_once('modules/Calls/Call.php');
require_once('modules/Emails/Email.php');
require_once('modules/Cases/Case.php');
require_once('modules/Accounts/Account.php');
require_once('include/utils.php');

// Bug is used to store customer information.
class Bug extends SugarBean {
        var $field_name_map = array();
	// Stored fields
	var $id;
	var $date_entered;
	var $date_modified;
	var $modified_user_id;
	var $assigned_user_id;



	var $bug_number;
	var $description;
	var $name;
	var $status;
	var $priority;

	// These are related
	var $resolution;
	var $found_in_release;
	var $release_name;
	var $fixed_in_release_name;
	var $created_by;
	var $created_by_name;
	var $modified_by_name;
	var $account_id;
	var $contact_id;
	var $case_id;
	var $task_id;
	var $note_id;
	var $meeting_id;
	var $call_id;
	var $email_id;
	var $assigned_user_name;
	var $type;





	//BEGIN Additional fields being added to Bug Tracker
	
	var $fixed_in_release;
	var $work_log;
	var $source;
	var $product_category;
	//END Additional fields being added to Bug Tracker
	
	var $module_dir = 'Bugs';
	var $table_name = "bugs";
	var $rel_account_table = "accounts_bugs";
	var $rel_contact_table = "contacts_bugs";
	var $rel_case_table = "cases_bugs";

	var $object_name = "Bug";

	// This is used to retrieve related fields from form posts.
	var $additional_column_fields = Array('assigned_user_name', 'assigned_user_id', 'case_id', 'account_id', 'contact_id', 'task_id', 'note_id', 'meeting_id', 'call_id', 'email_id');

	var $relationship_fields = Array('case_id'=>'cases', 'account_id' => 'accounts', 'contact_id'=>'contacts',
									'task_id'=>'tasks', 'note_id'=>'notes', 'meeting_id'=>'meetings',
									'call_id'=>'calls', 'email_id'=>'emails');

	function Bug() {
		parent::SugarBean();
		

		$this->setupCustomFields('Bugs');

		foreach ($this->field_defs as $field)
                {
                        $this->field_name_map[$field['name']] = $field;
                }





	}

	var $new_schema = true;

	

	

	function get_summary_text()
	{
		return "$this->name";
	}

	function create_list_query($order_by, $where, $show_deleted = 0)
	{
		// Fill in the assigned_user_name
//		$this->assigned_user_name = get_assigned_user_name($this->assigned_user_id);

		$custom_join = $this->custom_fields->getJOIN();
		
                $query = "SELECT ";
                
		$query .= "
                               bugs.*

                                ,users.user_name as assigned_user_name, releases.id release_id, releases.name release_name";



                                 if($custom_join){
                               		 $query .= $custom_join['select'];
                                }
                                $query .= " FROM bugs ";
                               





		$query .= "				LEFT JOIN releases ON bugs.found_in_release=releases.id
								LEFT JOIN users
                                ON bugs.assigned_user_id=users.id";



                                $query .= "  ";
								if($custom_join){
                               		 $query .= $custom_join['join'];
                                }
            $where_auto = '1=1';
			if($show_deleted == 0){
            	$where_auto = " $this->table_name.deleted=0 ";
			}else if($show_deleted == 1){
				$where_auto = " $this->table_name.deleted=1 ";	
			}
          

		if($where != "")
			$query .= "where $where AND ".$where_auto;
		else
			$query .= "where ".$where_auto;
		if(substr_count($order_by, '.') > 0){
			$query .= " ORDER BY $order_by";
		}
		else if($order_by != "")
			$query .= " ORDER BY $order_by";
		else
			$query .= " ORDER BY bugs.name";
		
		return $query;
	}

        function create_export_query($order_by, $where)
        {
				$custom_join = $this->custom_fields->getJOIN();
                $query = "SELECT
                                bugs.*,

                                users.user_name assigned_user_name";
                                 if($custom_join){
									$query .=  $custom_join['select'];
								}
                                $query .= " FROM bugs ";




		$query .= "				LEFT JOIN releases ON bugs.found_in_release =releases.id
								LEFT JOIN users
                                ON bugs.assigned_user_id=users.id";
                                 if($custom_join){
									$query .=  $custom_join['join'];
								}
                                $query .= "";
                $where_auto = "  bugs.deleted=0
                ";

                if($where != "")
                        $query .= " where $where AND ".$where_auto;
                else
                        $query .= " where ".$where_auto;

                if($order_by != "")
                        $query .= " ORDER BY $order_by";
                else
                        $query .= " ORDER BY bugs.bug_number";

                return $query;
        }
	function fill_in_additional_list_fields()
	{
		// Fill in the assigned_user_name
		//$this->assigned_user_name = get_assigned_user_name($this->assigned_user_id);



		
//	   $this->set_fixed_in_release();
	}

	function fill_in_additional_detail_fields()
	{
		// Fill in the assigned_user_name
		$this->assigned_user_name = get_assigned_user_name($this->assigned_user_id);





		$this->created_by_name = get_assigned_user_name($this->created_by);
		$this->modified_by_name = get_assigned_user_name($this->modified_user_id);
		$this->set_release();
		$this->set_fixed_in_release();
	}


	function set_release() {
			$query = "SELECT r1.name from releases r1, $this->table_name i1 where r1.id = i1.found_in_release and i1.id = '$this->id' and i1.deleted=0 and r1.deleted=0";
			$result = $this->db->query($query,true," Error filling in additional detail fields: ");

			// Get the id and the name.
			$row = $this->db->fetchByAssoc($result);

			if($row != null)
			{
				$this->release_name = $row['name'];
			}
			else
			{
				$this->release_name = '';
			}
	}

	
	function set_fixed_in_release() {
			$query = "SELECT r1.name from releases r1, $this->table_name i1 where r1.id = i1.fixed_in_release and i1.id = '$this->id' and i1.deleted=0 and r1.deleted=0";
			$result = $this->db->query($query,true," Error filling in additional detail fields: ");

			// Get the id and the name.
			$row = $this->db->fetchByAssoc($result);

			
			
			if($row != null)
			{
				$this->fixed_in_release_name = $row['name'];
			}
			else
			{
				$this->fixed_in_release_name = '';
			}
			
			
	}
	
	
	function get_list_view_data(){
		global $current_language;
		$the_array = parent::get_list_view_data();
		$app_list_strings = return_app_list_strings_language($current_language);
		$mod_strings = return_module_language($current_language, 'Bugs');

		$this->set_release();
	    
        // The new listview code only fetches columns that we're displaying and not all
        // the columns so we need these checks. 
	   $the_array['NAME'] = (($this->name == "") ? "<em>blank</em>" : $this->name);
        if (!empty($this->priority))
    	   $the_array['PRIORITY'] = $app_list_strings['bug_priority_dom'][$this->priority];
        if (!empty($this->status))           
    	   $the_array['STATUS'] =$app_list_strings['bug_status_dom'][$this->status];
	   $the_array['RELEASE']= $this->release_name;
        if (!empty($this->type))           
        	$the_array['TYPE']=  $app_list_strings['bug_type_dom'][$this->type];
	   $the_array['BUG_NUMBER'] = $this->bug_number;
	   $the_array['ENCODED_NAME']=$this->name;



    			
    	return  $the_array;
	}

	/**
		builds a generic search based on the query string using or
		do not include any $this-> because this is called on without having the class instantiated
	*/
	function build_generic_where_clause ($the_query_string) {
	$where_clauses = Array();
	$the_query_string = PearDatabase::quote(from_html($the_query_string));
	array_push($where_clauses, "bugs.name like '$the_query_string%'");
	if (is_numeric($the_query_string)) array_push($where_clauses, "bugs.bug_number like '$the_query_string%'");

	$the_where = "";
	foreach($where_clauses as $clause)
	{
		if($the_where != "") $the_where .= " or ";
		$the_where .= $clause;
	}

	return $the_where;
	}

	function set_notification_body($xtpl, $bug)
	{
		global $mod_strings, $app_list_strings;

		$bug->set_release();

		$xtpl->assign("BUG_SUBJECT", $bug->name);
		$xtpl->assign("BUG_TYPE", $app_list_strings['bug_type_dom'][$bug->type]);
		$xtpl->assign("BUG_PRIORITY", $app_list_strings['bug_priority_dom'][$bug->priority]);
		$xtpl->assign("BUG_STATUS", $app_list_strings['bug_status_dom'][$bug->status]);
		$xtpl->assign("BUG_RESOLUTION", $app_list_strings['bug_resolution_dom'][$bug->resolution]);
		$xtpl->assign("BUG_RELEASE", $bug->release_name);
		$xtpl->assign("BUG_DESCRIPTION", $bug->description);
		$xtpl->assign("BUG_WORK_LOG", $bug->work_log);
		$xtpl->assign("BUG_BUG_NUMBER", $bug->bug_number);
		return $xtpl;
	}
	
	function bean_implements($interface){
		switch($interface){
			case 'ACL':return true;
		}
		return false;
	}
	
	function save($check_notify = FALSE){













		return parent::save($check_notify);
	}
}
?>
