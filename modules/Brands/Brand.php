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
 * $Id: Brand.php,v 1.173 2006/08/09 18:39:44 jenny Exp $
 * Description:  Defines the Brand SugarBean Brand entity with the necessary
 * methods and variables.
 ********************************************************************************/

require_once('data/SugarBean.php');
require_once('modules/Contacts/Contact.php');
require_once('modules/Opportunities/Opportunity.php');
require_once('modules/Cases/Case.php');
require_once('modules/Calls/Call.php');
require_once('modules/Notes/Note.php');
require_once('modules/Emails/Email.php');
require_once('modules/Bugs/Bug.php');

// Brand is used to store brand information.
class Brand extends SugarBean {
	
	var $field_name_map = array();
	// Stored fields
	var $date_entered;
	var $date_modified;
	var $modified_user_id;
	var $assigned_user_id;

	var $id;
	var $name;
	var $parent_id;
	var $parent_name;
	var $account_name;
	var $account_id;
	var $created_by;
	var $created_by_name;
	var $modified_by_name;
	var $brand_pos;
	var $prod_hier_id;
	var $prod_hier_desc;
        var $price;
        var $faq;
	
	// These are for related fields
	var $assigned_user_name;

	var $module_dir = 'Brands';
	var $table_name = "brands";

	var $object_name = "Brand";

	var $new_schema = true;

	// This is used to retrieve related fields from form posts.
    var $additional_column_fields = Array('account_name','account_id');
	var $relationship_fields = Array('account_id'=> 'accounts');
	
	function Brand() {
//		om
		parent::SugarBean();

        $this->setupCustomFields('Brands');
		foreach ($this->field_defs as $field)
		{
			$this->field_name_map[$field['name']] = $field;
		}
	}
	
	function get_summary_text()
	{
		//om
		return $this->name;
	}

	function add_list_count_joins(&$query, $where)
	{
		//om
		// accounts.name
		if(eregi("accounts.name", $where))
		{
			// add a join to the accounts table.
			$query .= "
	            LEFT JOIN accounts_brands
	            ON brands.id=accounts_brands.brand_id
	            LEFT JOIN accounts
	            ON accounts_brands.account_id=accounts.id
			";
		}
		$custom_join = $this->custom_fields->getJOIN();
		if($custom_join){
  				$query .= $custom_join['join'];
		}
	}
	
	function fill_in_additional_list_fields()
	{
		$this->fill_in_account_details();
//		echo "Account Name ".$this->account_name;
	}

	function fill_in_additional_detail_fields()
	{
		// Fill in the assigned_user_name
		$this->assigned_user_name = get_assigned_user_name($this->assigned_user_id);
		$this->created_by_name = get_assigned_user_name($this->created_by);
		$this->modified_by_name = get_assigned_user_name($this->modified_user_id);

		$query = "SELECT a1.name from brands a1, brands a2 where a1.id = a2.parent_id and a2.id = '$this->id' and a1.deleted=0";
		$result = $this->db->query($query,true," Error filling in additional detail fields: ");

		// Get the id and the name.
		$row = $this->db->fetchByAssoc($result);

		if($row != null)
		{
			$this->parent_name = $row['name'];
		}
		else
		{
			$this->parent_name = '';
		}
		
		$this->fill_in_account_details();
	}
	
	function fill_in_account_details()
	{
		$query = "SELECT acc.id, acc.name from brands
		left join accounts_brands a_b on a_b.brand_id = '".$this->id."' and a_b.deleted=0
		left join accounts acc on a_b.account_id = acc.id and acc.deleted=0
		where brands.id = '".$this->id."'";
		
		$result = $this->db->query($query,true," Error filling in additional detail fields: ");

		// Get the id and the name.
		$row = $this->db->fetchByAssoc($result);

		if($row != null)
		{
			$this->account_id = $row['id'];
			$this->account_name = $row['name'];
		}
		else
		{
			$this->account_id = '';
			$this->account_name = '';
		}
		
	}
	
	function get_list_view_data(){
		global $system_config;
		$temp_array = $this->get_list_view_array();
		$temp_array["ENCODED_NAME"]=$this->name;
		return $temp_array;
	}

	/**
		builds a generic search based on the query string using or
		do not include any $this-> because this is called on without having the class instantiated
	*/

	function build_generic_where_clause ($the_query_string) {
	$where_clauses = Array();
	$the_query_string = PearDatabase::quote(from_html($the_query_string));
	array_push($where_clauses, "brands.name like '$the_query_string%'");

	$the_where = "";
	foreach($where_clauses as $clause)
	{
		if(!empty($the_where)) $the_where .= " or ";
		$the_where .= $clause;
	}

	return $the_where;
}

	function create_export_query(&$order_by, &$where)
        {
        	$custom_join = $this->custom_fields->getJOIN();
			$query = "SELECT
					brands.*,
                    users.user_name as assigned_user_name ";

                     if($custom_join){
						$query .=  $custom_join['select'];
					}
                    $query .= "FROM brands ";

			if($custom_join){
					$query .=  $custom_join['join'];
				}
            $query .= " LEFT JOIN users
                    	ON brands.assigned_user_id=users.id ";

            $where_auto = " brands.deleted=0 ";

            if($where != "")
                    $query .= "where ($where) AND ".$where_auto;
            else
                    $query .= "where ".$where_auto;

           if(!empty($order_by)){
            	//check to see if order by variable already has table name by looking for dot "."
            	$table_defined_already = strpos($order_by, ".");

            	if($table_defined_already === false){
            		//table not defined yet, define accounts to avoid "ambigous column" SQL error
            		$query .= " ORDER BY $order_by";
            	}else{
            		//table already defined, just add it to end of query
            	    $query .= " ORDER BY $order_by";
            	}
            }

            return $query;
        }

        function create_list_query($order_by, $where, $show_deleted= 0)
        {
			$custom_join = $this->custom_fields->getJOIN();

                $query = "SELECT ";

                $query .= "
                    users.user_name assigned_user_name,
                    brands.* ";
                 if($custom_join){
					$query .=  $custom_join['select'];
				}

             $query .= " FROM brands ";

			 $query .= "LEFT JOIN users
                    	ON brands.assigned_user_id=users.id ";
//			 $query .= "LEFT JOIN accounts_brands ON brands.id = accounts_brands.brand_id ";
//			 $query .= "LEFT JOIN accounts ON accounts.id = accounts_brands.account_id ";
			 
             if($custom_join){
					$query .=  $custom_join['join'];
				}

     		$where_auto = '1=1';
			if($show_deleted == 0){
            	$where_auto = " brands.deleted=0 ";
			}else if($show_deleted == 1){
				$where_auto = " brands.deleted=1 ";
			}

            if($where != "")
                    $query .= "where ($where) AND ".$where_auto;
            else
                    $query .= "where ".$where_auto;

        if($order_by != "")
			$query .= " ORDER BY $order_by";
		else
			$query .= " ORDER BY $this->table_name.name";
                    return $query;
    }

	function set_notification_body($xtpl, $brand)
	{
		$xtpl->assign("BRAND_NAME", $brand->name);
		return $xtpl;
	}

	function save_relationship_changes($is_update) {
		
		//if account_id was replaced unlink the previous account_id.
		//this rel_fields_before_value is populated by sugarbean during the retrieve call.
		if (!empty($this->account_id) and !empty($this->rel_fields_before_value['account_id']) and 
				(trim($this->account_id) != trim($this->rel_fields_before_value['account_id']))) {
				//unlink the old record.
				$this->load_relationship('accounts');							
				$this->accounts->delete($this->id,$this->rel_fields_before_value['account_id']);		    					    		    				
		}
		parent::save_relationship_changes($is_update);
	}
	
	function bean_implements($interface){
		switch($interface){
			case 'ACL':return true;
		}
		return false;
	}
}

?>
