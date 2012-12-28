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
 * $Id: Opportunity.php,v 1.170 2006/08/09 19:29:14 jenny Exp $
 * Description:
 ********************************************************************************/




require_once('data/SugarBean.php');
require_once('modules/Contacts/Contact.php');
require_once('modules/Tasks/Task.php');
require_once('modules/Notes/Note.php');
require_once('modules/Calls/Call.php');
require_once('modules/Leads/Lead.php');
require_once('modules/Emails/Email.php');
require_once('include/utils.php');

// Opportunity is used to store customer information.
class Opportunity extends SugarBean {
	var $field_name_map;
	// Stored fields
	var $id;
	var $lead_source;
	var $date_entered;
	var $date_modified;
	var $modified_user_id;
	var $assigned_user_id;
	var $created_by;
	var $created_by_name;
	var $modified_by_name;
	var $description;
	var $name;
	var $opportunity_type;
        var $opportunity_category;
	var $amount;
	var $amount_usdollar;
	var $currency_id;
	var $date_closed;
	var $next_step;
	var $sales_stage;
	var $probability;
	// These are related
    var $amount_backup;
	var $account_name;
	var $account_id;
	var $contact_id;
	var $task_id;
	var $note_id;
	var $meeting_id;
	var $call_id;
	var $email_id;
	var $assigned_user_name;

	var $table_name = "opportunities";
	var $rel_account_table = "accounts_opportunities";
	var $rel_contact_table = "opportunities_contacts";
	var $module_dir = "Opportunities";

	var $track_on_save=true;
	
	var $object_name = "Opportunity";

	// This is used to retrieve related fields from form posts.
	var $additional_column_fields = Array('assigned_user_name', 'assigned_user_id', 'account_name', 'account_id', 'contact_id', 'task_id', 'note_id', 'meeting_id', 'call_id', 'email_id'

	);

	var $relationship_fields = Array('task_id'=>'tasks', 'note_id'=>'notes', 'account_id'=>'accounts',
									'meeting_id'=>'meetings', 'call_id'=>'calls', 'email_id'=>'emails', 'project_id'=>'project',
									);
	
	function Opportunity() {
		parent::SugarBean();
		global $sugar_config;
		if(!$sugar_config['require_accounts']){
			unset($this->required_fields['account_name']);
		}
		global $current_user;
	}

	var $new_schema = true;
	
	function get_summary_text()
	{
		return "$this->name";
	}

	function create_list_query($order_by, $where, $show_deleted = 0)
	{
		
$custom_join = $this->custom_fields->getJOIN();
                $query = "SELECT ";
            
                $query .= "
                            accounts.id as account_id,
                            accounts.name as account_name,
                            accounts.assigned_user_id account_id_owner,
                            users.user_name as assigned_user_name ";
                
                            if($custom_join){
   								$query .= $custom_join['select'];
 							}
                            $query .= " ,opportunities.*
                            FROM opportunities ";

$query .= 			"LEFT JOIN users
                            ON opportunities.assigned_user_id=users.id ";

                            $query .= "LEFT JOIN $this->rel_account_table
                            ON opportunities.id=$this->rel_account_table.opportunity_id
                            LEFT JOIN accounts
                            ON $this->rel_account_table.account_id=accounts.id ";
			    if($custom_join){
  					$query .= $custom_join['join'];
				}
		$where_auto = '1=1';
		if($show_deleted == 0){	    
			$where_auto = "
			($this->rel_account_table.deleted is null OR $this->rel_account_table.deleted=0)
			AND (accounts.deleted is null OR accounts.deleted=0)  
			AND opportunities.deleted=0";
		}else 	if($show_deleted == 1){
				$where_auto = " opportunities.deleted=1";
		}

		if($where != "")
			$query .= "where ($where) AND ".$where_auto;
		else
			$query .= "where ".$where_auto;

		if($order_by != "")
			$query .= " ORDER BY $order_by";
		else
			$query .= " ORDER BY opportunities.name";

		return $query;
	}

        function create_export_query($order_by, $where)
        {
								$custom_join = $this->custom_fields->getJOIN();
                                $query = "SELECT
                                opportunities.*,
                                accounts.name as account_name,
                                users.user_name as assigned_user_name ";

								if($custom_join){
   									$query .= $custom_join['select'];
 								}
	                            $query .= "FROM opportunities ";

		$query .= 				"LEFT JOIN users
                                ON opportunities.assigned_user_id=users.id";

                                $query .= " LEFT JOIN $this->rel_account_table
                                ON opportunities.id=$this->rel_account_table.opportunity_id
                                LEFT JOIN accounts
                                ON $this->rel_account_table.account_id=accounts.id ";
								if($custom_join){
  									$query .= $custom_join['join'];
								}
		$where_auto = "
			($this->rel_account_table.deleted is null OR $this->rel_account_table.deleted=0)
			AND (accounts.deleted is null OR accounts.deleted=0)  
			AND opportunities.deleted=0";

        if($where != "")
                $query .= "where $where AND ".$where_auto;
        else
                $query .= "where ".$where_auto;

        if($order_by != "")
                $query .= " ORDER BY opportunities.$order_by";
        else
                $query .= " ORDER BY opportunities.name";
        return $query;
    }

	function fill_in_additional_list_fields()
	{
         if ( $this->force_load_details == true){
             $this->fill_in_additional_detail_fields();
         }
	}

	function fill_in_additional_detail_fields()
	{
		require_once('modules/Currencies/Currency.php');
		$currency = new Currency();
		$currency->retrieve($this->currency_id);
		if($currency->id != $this->currency_id || $currency->deleted == 1){
				$this->amount = $this->amount_usdollar;
				$this->currency_id = $currency->id;
		}

		// Fill in the assigned_user_name
		$this->assigned_user_name = get_assigned_user_name($this->assigned_user_id);
		$this->account_name = '';
		$this->account_id = '';
		$ret_values=Opportunity::get_account_detail($this->id);
		if (!empty($ret_values)) {
			$this->account_name=$ret_values['name'];
			$this->account_id=$ret_values['id'];
			$this->account_id_owner =$ret_values['assigned_user_id'];
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
		$query_array['select']="SELECT contacts.id, contacts.first_name, contacts.last_name, contacts.title, contacts.email1, contacts.phone_work, opportunities_contacts.contact_role as opportunity_role, opportunities_contacts.id as opportunity_rel_id ";
	
		$query='';
		foreach ($query_array as $qstring) {
			$query.=' '.$qstring;
		}	
	    $temp = Array('id', 'first_name', 'last_name', 'title', 'email1', 'phone_work', 'opportunity_role', 'opportunity_rel_id');
		return $this->build_related_list2($query, new Contact(), $temp);
	}

	function update_currency_id($fromid, $toid){
		$idequals = '';
		require_once('modules/Currencies/Currency.php');
		$currency = new Currency();
		$currency->retrieve($toid);
		foreach($fromid as $f){
			if(!empty($idequals)){
				$idequals .=' or ';
			}
			$idequals .= "currency_id='$f'";
		}

		if(!empty($idequals)){
			$query = "select amount, id from opportunities where (". $idequals. ") and deleted=0 and opportunities.sales_stage <> 'Closed Won' AND opportunities.sales_stage <> 'Closed Lost';";
			$result = $this->db->query($query);
			while($row = $this->db->fetchByAssoc($result)){

				$query = "update opportunities set currency_id='".$currency->id."', amount_usdollar='".$currency->convertToDollar($row['amount'])."' where id='".$row['id']."';";
				$this->db->query($query);

			}
	}
	}

	function get_list_view_data(){
		global $current_language, $current_user, $mod_strings, $app_list_strings, $sugar_config;
		$app_strings = return_application_language($current_language);
		require_once('modules/Currencies/Currency.php');
		
		$temp_array = $this->get_list_view_array();

		$temp_array['SALES_STAGE'] = empty($temp_array['SALES_STAGE']) ? '' : $temp_array['SALES_STAGE'];
		$temp_array['AMOUNT'] =  format_number($this->amount_usdollar, 2, 2, array('convert' => true, 'currency_symbol' => true));
	    $temp_array["ENCODED_NAME"]=$this->name;
//		$temp_array["ENCODED_NAME"]=htmlspecialchars($this->name, ENT_QUOTES);
		return $temp_array;
	}
	/**
		builds a generic search based on the query string using or
		do not include any $this-> because this is called on without having the class instantiated
	*/
	function build_generic_where_clause ($the_query_string) {
	$where_clauses = Array();
	$the_query_string = PearDatabase::quote(from_html($the_query_string));
	array_push($where_clauses, "opportunities.name like '$the_query_string%'");
	array_push($where_clauses, "accounts.name like '$the_query_string%'");

	$the_where = "";
	foreach($where_clauses as $clause)
	{
		if($the_where != "") $the_where .= " or ";
		$the_where .= $clause;
	}

	return $the_where;
}

	function save($check_notify = FALSE) {
		require_once('modules/Opportunities/SaveOverload.php');
		
		perform_save($this);
		$this->unformat_all_fields();
		return parent::save($check_notify);

	}
	
	function save_relationship_changes($is_update)
	{
		//if account_id was replaced unlink the previous account_id.
		//this rel_fields_before_value is populated by sugarbean during the retrieve call.
		if (!empty($this->account_id) and !empty($this->rel_fields_before_value['account_id']) and 
				(trim($this->account_id) != trim($this->rel_fields_before_value['account_id']))) {
				//unlink the old record.
				$this->load_relationship('accounts');							
				$this->accounts->delete($this->id,$this->rel_fields_before_value['account_id']);		    					    		    				
		}

		parent::save_relationship_changes($is_update);
		
		if (!empty($this->contact_id)) {
			$this->set_opportunity_contact_relationship($this->contact_id);
		}
                                
                 //purpose : add user (all superior) hierarchy for new created opportunities only
        $rel_name = "users";                
        if(!$is_update) {
            $this->load_relationship($rel_name);
            $user_array=get_user_all_hier_array();
            $GLOBALS['log']->info("save_relationship_changes:User Hierarchy Array  :".print_r($user_array,true));
            if(is_array($user_array)) {
                foreach($user_array as $user_name=>$user_id) {
                    $this->$rel_name->add($user_id);
                }
            }
        }
        
        $this->load_relationship($rel_name);
        $user_array = $this->$rel_name->get();
        
        $GLOBALS['log']->debug("In save_relationship_changes user array ".count($user_array));
        
        $user_present = false;
	foreach($user_array as $user){
        	if($user->user_id == $this->assigned_user_id){
                    $user_present = true;
                    break;               
                }
	}
        
        if(!$user_present){
            $this->$rel_name->add($this->assigned_user_id);
        }
        
	}

	function set_opportunity_contact_relationship($contact_id)
	{
		global $app_list_strings;
		$default = $app_list_strings['opportunity_relationship_type_default_key'];
		$this->load_relationship('contacts');
		$this->contacts->add($contact_id,array('contact_role'=>$default));
	}

	function set_notification_body($xtpl, $oppty)
	{
		global $app_list_strings;
		
		$xtpl->assign("OPPORTUNITY_NAME", $oppty->name);
		$xtpl->assign("OPPORTUNITY_AMOUNT", $oppty->amount);
		$xtpl->assign("OPPORTUNITY_CLOSEDATE", $oppty->date_closed);
		$xtpl->assign("OPPORTUNITY_STAGE", (isset($oppty->sales_stage)?$app_list_strings['sales_stage_dom'][$oppty->sales_stage]:""));
		$xtpl->assign("OPPORTUNITY_DESCRIPTION", $oppty->description);

		return $xtpl;
	}

	function bean_implements($interface){
		switch($interface){
			case 'ACL':return true;
		}
		return false;
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
	
	/**
	 * Static helper function for getting releated account info.
	 */
	function get_account_detail($opp_id) {
		$ret_array = array();
		$db = & PearDatabase::getInstance();
		$query = "SELECT acc.id, acc.name, acc.assigned_user_id "
			. "FROM accounts acc, accounts_opportunities a_o "
			. "WHERE acc.id=a_o.account_id"
			. " AND a_o.opportunity_id='$opp_id'"
			. " AND a_o.deleted=0"
			. " AND acc.deleted=0";
		$result = $db->query($query, true,"Error filling in opportunity account details: ");
		$row = $db->fetchByAssoc($result);
		if($row != null) {
			$ret_array['name'] = $row['name'];
			$ret_array['id'] = $row['id'];
			$ret_array['assigned_user_id'] = $row['assigned_user_id'];
		}
		return $ret_array;
	}
}


?>
