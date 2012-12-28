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
 * $Id: Case.php,v 1.122 2006/08/18 20:19:20 chris Exp $
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
require_once('modules/Bugs/Bug.php');
require_once('include/utils.php');

// Case is used to store customer information.
class aCase extends SugarBean {
        var $field_name_map = array();
	// Stored fields
	var $id;
	var $date_entered;
	var $date_modified;
	var $modified_user_id;
	var $assigned_user_id;



	var $case_number;
	var $resolution;
	var $description;
	var $name;
	var $status;
	var $priority;
	

	var $created_by;
	var $created_by_name;
	var $modified_by_name;

	// These are related
	var $bug_id;
	var $account_name;
	var $account_id;
	var $contact_id;
	var $task_id;
	var $note_id;
	var $meeting_id;
	var $call_id;
	var $email_id;
	var $assigned_user_name;
	var $account_name1;





	var $table_name = "cases";
	var $rel_account_table = "accounts_cases";
	var $rel_contact_table = "contacts_cases";
	var $module_dir = 'Cases';
	var $object_name = "Case";
	/** "%1" is the case_number, for emails
	 * leave the %1 in if you customize this
	 * YOU MUST LEAVE THE BRACKETS AS WELL*/
	var $emailSubjectMacro = '[CASE:%1]'; 

	// This is used to retrieve related fields from form posts.
	var $additional_column_fields = Array('bug_id', 'assigned_user_name', 'assigned_user_id', 'contact_id', 'task_id', 'note_id', 'meeting_id', 'call_id', 'email_id');

	var $relationship_fields = Array('account_id'=>'account', 'bug_id' => 'bugs',
									'task_id'=>'tasks', 'note_id'=>'notes',
									'meeting_id'=>'meetings', 'call_id'=>'calls', 'email_id'=>'emails',									
									);

	function aCase() {
		parent::SugarBean();
		global $sugar_config;
		if(!$sugar_config['require_accounts']){
			unset($this->required_fields['account_name']);
		}

		

		;
		
		 $this->setupCustomFields('Cases');
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
	
	function listviewACLHelper(){
		$array_assign = parent::listviewACLHelper();
		$is_owner = false;
		if(!empty($this->account_id)){
			
			if(!empty($this->account_id_owner)){
				global $current_user;
				$is_owner = $current_user->id == $this->account_id_owner;
			}
		}
			if(!ACLController::moduleSupportsACL('Accounts') || ACLController::checkAccess('Accounts', 'view', $is_owner)){
				$array_assign['ACCOUNT'] = 'a';
			}else{
				$array_assign['ACCOUNT'] = 'span';
			}
		
		return $array_assign;
	}

	function create_list_query($order_by, $where, $show_deleted = 0)
	{
		// Fill in the assigned_user_name
//		$this->assigned_user_name = get_assigned_user_name($this->assigned_user_id);

				$custom_join = $this->custom_fields->getJOIN();
                $query = "SELECT ";
            

		$query .= "
                                cases.*,
                                accounts.name as account_name1,
                                accounts.assigned_user_id account_name1_owner,
                                users.user_name as assigned_user_name";



                            	if($custom_join){
   									$query .= $custom_join['select'];
 								}
                                $query .= " FROM cases ";





		$query .= "				LEFT JOIN users
                                ON cases.assigned_user_id=users.id";



                                $query .= " LEFT JOIN accounts
                                ON cases.account_id=accounts.id ";

								if($custom_join){
  									$query .= $custom_join['join'];
								}
			$where_auto = '1=1';
			if($show_deleted == 0){
            	$where_auto = " $this->table_name.deleted=0  AND  accounts.deleted=0 ";
			}else if($show_deleted == 1){
				$where_auto = " $this->table_name.deleted=1 ";	
			}
                



		if($where != "")
			$query .= "where $where AND ".$where_auto;
		else
			$query .= "where ".$where_auto;

		if($order_by != "")
			$query .= " ORDER BY $order_by";
		else
			$query .= " ORDER BY cases.name";
		
		return $query;
	}

        function create_export_query($order_by, $where)
        {
				$custom_join = $this->custom_fields->getJOIN();
                $query = "SELECT
                                cases.*,
                                accounts.name as account_name1,
                                users.user_name as assigned_user_name";
             					if($custom_join){
   									$query .= $custom_join['select'];
 								}
                                $query .= " FROM cases ";




		$query .= "				LEFT JOIN users
                                ON cases.assigned_user_id=users.id";
                                $query .= " LEFT JOIN accounts
                                ON cases.account_id=accounts.id";
                                
                 			if($custom_join){
  								$query .= $custom_join['join'];
							}	
                $where_auto = " accounts.deleted=0
                                AND cases.deleted=0
                ";

                if($where != "")
                        $query .= " where $where AND ".$where_auto;
                else
                        $query .= " where ".$where_auto;

                if($order_by != "")
                        $query .= " ORDER BY $order_by";
                else
                        $query .= " ORDER BY cases.name";
                return $query;
        }

	function save_relationship_changes($is_update)
	{
		parent::save_relationship_changes($is_update);
		
		if (!empty($this->contact_id)) {
			$this->set_case_contact_relationship($this->contact_id);
		}
	}

	function set_case_contact_relationship($contact_id)
	{
		global $app_list_strings;
		$default = $app_list_strings['case_relationship_type_default_key'];
		$this->load_relationship('contacts');
		$this->contacts->add($contact_id,array('contact_role'=>$default));
	}

	function fill_in_additional_list_fields()
	{
		// Fill in the assigned_user_name
		//$this->assigned_user_name = get_assigned_user_name($this->assigned_user_id);



	}

	function fill_in_additional_detail_fields()
	{
		// Fill in the assigned_user_name
		$this->assigned_user_name = get_assigned_user_name($this->assigned_user_id);





		$query = "SELECT acc.id, acc.name from accounts  acc, cases  where acc.id = cases.account_id and cases.id = '$this->id' and cases.deleted=0 and acc.deleted=0";
		$result = $this->db->query($query,true," Error filling in additional detail fields: ");

		// Get the id and the name.
		$row = $this->db->fetchByAssoc($result);

		if($row != null)
		{
			$this->account_name = stripslashes($row['name']);
			$this->account_id 	= $row['id'];
		}
		else
		{
			$this->account_name = '';
			$this->account_id 	= '';
		}

		$this->created_by_name = get_assigned_user_name($this->created_by);
		$this->modified_by_name = get_assigned_user_name($this->modified_user_id);
	}


	/** Returns a list of the associated contacts
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	*/
	function get_contacts()
	{
		$this->load_relationship('contacts');
		$query_array=$this->contacts->getQuery(true);
		
		//update the select clause in the retruned query.
		$query_array['select']="SELECT contacts.id, contacts.first_name, contacts.last_name, contacts.title, contacts.email1, contacts.phone_work, contacts_cases.contact_role as case_role, contacts_cases.id as case_rel_id ";
	
		$query='';
		foreach ($query_array as $qstring) {
			$query.=' '.$qstring;
		}	
	    $temp = Array('id', 'first_name', 'last_name', 'title', 'email1', 'phone_work', 'case_role', 'case_rel_id');
		return $this->build_related_list2($query, new Contact(), $temp);
	}

	function get_list_view_data(){
		global $current_language, $image_path;
		$app_list_strings = return_app_list_strings_language($current_language);
		
		if($this->account_name == "") { $this->fill_in_additional_detail_fields(); } // cn: bug 4977
		
		$temp_array = $this->get_list_view_array();
		$temp_array['NAME'] = (($this->name == "") ? "<em>blank</em>" : $this->name);
		$temp_array['PRIORITY'] = empty($this->priority)? "" : $app_list_strings['case_priority_dom'][$this->priority];
		$temp_array['STATUS'] = empty($this->status)? "" : $app_list_strings['case_status_dom'][$this->status];
		$temp_array['ENCODED_NAME'] = $this->name;
		$temp_array['CASE_NUMBER'] = $this->case_number;
		$temp_array['SET_COMPLETE'] =  "<a href='index.php?return_module=Home&return_action=index&action=EditView&module=Cases&record=$this->id&status=Closed'>".get_image($image_path."close_inline","alt='Close' border='0'")."</a>";
		$temp_array['ACCOUNT_NAME'] = $this->account_name; //overwrites the account_name value returned from the cases table.



		return $temp_array;
	}

	/**
		builds a generic search based on the query string using or
		do not include any $this-> because this is called on without having the class instantiated
	*/
	function build_generic_where_clause ($the_query_string) {
	$where_clauses = Array();
	$the_query_string = PearDatabase::quote(from_html($the_query_string));
	array_push($where_clauses, "cases.name like '$the_query_string%'");
	array_push($where_clauses, "accounts.name like '$the_query_string%'");

	if (is_numeric($the_query_string)) array_push($where_clauses, "cases.case_number like '$the_query_string%'");

	$the_where = "";

	foreach($where_clauses as $clause)
	{
		if($the_where != "") $the_where .= " or ";
		$the_where .= $clause;
	}
	
	if($the_where != ""){
		$the_where = "(".$the_where.")";	
	}
	
	return $the_where;
	}

	function set_notification_body($xtpl, $case)
	{
		global $app_list_strings;		
		
		$xtpl->assign("CASE_SUBJECT", $case->name);
		$xtpl->assign("CASE_PRIORITY", (isset($case->priority) ? $app_list_strings['case_priority_dom'][$case->priority]:""));
		$xtpl->assign("CASE_STATUS", (isset($case->status) ? $app_list_strings['case_status_dom'][$case->status]:""));
		$xtpl->assign("CASE_DESCRIPTION", $case->description);

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
	
	/**
	 * retrieves the Subject line macro for InboundEmail parsing
	 * @return string
	 */
	function getEmailSubjectMacro() {
		global $sugar_config;
		return (isset($sugar_config['inbound_email_case_subject_macro']) && !empty($sugar_config['inbound_email_case_subject_macro'])) ?
			$sugar_config['inbound_email_case_subject_macro'] : $this->emailSubjectMacro; 
	}
}
?>
