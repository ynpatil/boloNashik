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
require_once('modules/Contacts/Contact.php');
require_once('modules/Opportunities/Opportunity.php');
require_once('modules/Cases/Case.php');
require_once('modules/Calls/Call.php');
require_once('modules/Notes/Note.php');
require_once('modules/Emails/Email.php');
require_once('modules/Bugs/Bug.php');
require_once('modules/TeamsOS/TeamOS.php');

// Account is used to store account information.
class Account extends SugarBean {
    var $field_name_map = array();
    // Stored fields
    var $date_entered;
    var $date_modified;
    var $modified_user_id;
    var $assigned_user_id;
    var $annual_revenue;
    var $billing_address_street;
    var $billing_address_city;
    var $billing_address_state;
    var $billing_address_country;
    var $billing_address_city_desc;
    var $billing_address_state_desc;
    var $billing_address_country_desc;
    var $billing_address_postalcode;

    var $billing_address_street_2;
    var $billing_address_street_3;
    var $billing_address_street_4;

    var $description;
    var $email1;
    var $email2;
    var $employees;
    var $id;
    var $industry;
    var $industry_name;
    var $linkage_id;
    var $linkage_desc_c;

    var $name;
    var $ownership;
    var $parent_id;
    var $aor_id;
    var $aor_name;
    var $anniversary;

    var $phone_alternate;
    var $phone_fax;
    var $phone_office;
    var $rating;
    var $shipping_address_street;
    var $shipping_address_city;
    var $shipping_address_state;
    var $shipping_address_country;
    var $shipping_address_city_desc;
    var $shipping_address_state_desc;
    var $shipping_address_country_desc;

    var $shipping_address_postalcode;

    var $shipping_address_street_2;
    var $shipping_address_street_3;
    var $shipping_address_street_4;

    var $sic_code;
    var $ticker_symbol;
    var $account_type;
    var $website;
    var $custom_fields;

    var $created_by;
    var $created_by_name;
    var $modified_by_name;

    // These are for related fields
    var $opportunity_id;
    var $case_id;
    var $contact_id;
    var $task_id;
    var $note_id;
    var $meeting_id;
    var $call_id;
    var $email_id;
    var $member_id;
    var $parent_name;
    var $assigned_user_name;
    var $account_id = '';
    var $account_name = '';
    var $bug_id ='';
    var $module_dir = 'Accounts';
    var $table_name = "accounts";
    var $object_name = "Account";

    var $new_schema = true;

    // This is used to retrieve related fields from form posts.
    var $additional_column_fields = Array('assigned_user_name', 'assigned_user_id', 'opportunity_id', 'bug_id', 'case_id', 'contact_id', 'task_id', 'note_id', 'meeting_id', 'call_id', 'email_id', 'parent_name', 'member_id'
    );
    var $relationship_fields = Array('opportunity_id'=>'opportunities', 'bug_id' => 'bugs', 'case_id'=>'cases',
            'contact_id'=>'contacts', 'task_id'=>'tasks', 'note_id'=>'notes',
            'meeting_id'=>'meetings', 'call_id'=>'calls', 'email_id'=>'emails','member_id'=>'members',
    );

    function Account() {
        //om
        parent::SugarBean();

        $this->setupCustomFields('Accounts');
        foreach ($this->field_defs as $field) {
            $this->field_name_map[$field['name']] = $field;
        }
    }

    function get_summary_query($where) {
        //om
        return $query = "SELECT
				count(*) count,
				users.id as assigned_user_id FROM accounts 
								LEFT JOIN users 
								ON accounts.assigned_user_id=users.id 
								LEFT JOIN accounts_cstm ON accounts.id = accounts_cstm.id_c 
								LEFT JOIN suboffice_mast ON users.suboffice_id = suboffice_mast.id 
								LEFT JOIN suboffice_mast_cstm ON suboffice_mast.id = suboffice_mast_cstm.id_c 
								LEFT JOIN branch_mast ON suboffice_mast_cstm.branch_id_c = branch_mast.id 
								LEFT JOIN verticals_mast ON users.verticals_id = verticals_mast.id WHERE ".$where." GROUP BY assigned_user_id ";
    }

    function get_summary_text() {
        //om
        return $this->name;
    }

    function get_contacts() {
        //om
        return $this->get_linked_beans('contacts','Contact');
    }

    function clear_account_case_relationship($account_id='', $case_id='') {
        if (empty($case_id)) $where = '';
        else $where = " and id = '$case_id'";
        $query = "UPDATE cases SET account_name = '', account_id = '' WHERE account_id = '$account_id' AND deleted = 0 " . $where;
        $this->db->query($query,true,"Error clearing account to case relationship: ");
    }

    // This method is used to provide backward compatibility with old data that was prefixed with http://
    // We now automatically prefix http://
    function remove_redundant_http() {
        if(eregi("http://", $this->website)) {
            $this->website = substr($this->website, 7);
        }
    }

    function fill_in_additional_list_fields() {
        // Fill in the assigned_user_name
        $this->assigned_user_name = get_assigned_user_name($this->assigned_user_id);
        $this->remove_redundant_http();

        if(empty($this->billing_address_city_desc) && !empty($this->billing_address_city)) {
            $query = "SELECT name from city_mast where id = '$this->billing_address_city' AND deleted=0";
            $result = $this->db->query($query, true, "Error filling in other detail fields");

            $row = $this->db->fetchByAssoc($result);
//			$GLOBALS['log']->debug("additional detail query results: $row");

            if ($row != null) {
                $this->billing_address_city_desc = $row['name'];
            } else {
                $this->billing_address_city_desc = '';
            }
        }
        if(isset($this->industry)) {
            //$query="select industry_mast.name as industry_name,industry_mast_cstm.sector_id_c as sector_id from industry_mast INNER JOIN industry_mast_cstm ON  industry_mast.id = industry_mast_cstm.id_c where industry_mast.id='".$this->industry."'"; This is Old Query
            $query="SELECT industry_mast.name as industry_name FROM industry_mast WHERE id='".$this->industry."'";
            $GLOBALS['log']->debug("QUERY=>get Industry name : $query");
            $result = $this->db->query($query,true," Error filling in additional detail fields: ");
            $row = $this->db->fetchByAssoc($result);

            //This  For  Getting  Sector:  Name
            $query_sector = "select industry_mast.name as industry_name,industry_mast_cstm.sector_id_c as sector_id from industry_mast INNER JOIN industry_mast_cstm ON  industry_mast.id = industry_mast_cstm.id_c where industry_mast.id='".$this->industry."'";
            $result_sector = $this->db->query($query_sector,true," Error filling in additional detail fields: ");
            $row_sector = $this->db->fetchByAssoc($result_sector);

            if($row != null) {
                $this->industry_name = $row['industry_name'];
                $this->sector_id = $row_sector['sector_id'];
            }else {
                $this->industry_name = '';
                $this->sector_id='';
            }


            if($this->sector_id) {
                $query = "SELECT name from sector_mast where id = '$this->sector_id' and deleted=0";
                $GLOBALS['log']->debug("QUERY=>get Sector name : $query");

                $result = $this->db->query($query,true," Error filling in additional detail fields: ");
                // Get the id and the name.
                $row1 = $this->db->fetchByAssoc($result);
                if($row1 != null) {
                    $this->sector = $row1['name'];
                }else {
                    $this->sector = '';
                }
            }
        }
    }

    function fill_in_additional_detail_fields() {
        // Fill in the assigned_user_name
        $this->assigned_user_name = get_assigned_user_name($this->assigned_user_id);

        $query = "SELECT a1.name from accounts a1, accounts a2 where a1.id = a2.parent_id and a2.id = '$this->id' and a1.deleted=0";
        $result = $this->db->query($query,true," Error filling in additional detail fields: ");

        // Get the id and the name.
        $row = $this->db->fetchByAssoc($result);

        if($row != null) {
            $this->parent_name = $row['name'];
        }
        else {
            $this->parent_name = '';
        }

        if(isset($this->industry)) {
            $query = "SELECT name from industry_mast where id = '$this->industry' and deleted=0";
            $result = $this->db->query($query,true," Error filling in additional detail fields: ");

            // Get the id and the name.
            $row = $this->db->fetchByAssoc($result);

            if($row != null) {
                $this->industry_name = $row['name'];
            }
            else {
                $this->industry_name = '';
            }
        }

        if(isset($this->linkage_id)) {
            $query = "SELECT name from linkage_mast where id = '$this->linkage_id' and deleted=0";
            $result = $this->db->query($query,true," Error filling in additional detail fields: ");

            // Get the id and the name.
            $row = $this->db->fetchByAssoc($result);

            if($row != null) {
                $this->linkage_desc_c = $row['name'];
            }
            else {
                $this->linkage_desc_c = '';
            }
        }

        if(isset($this->aor_id)) {
            $query = "SELECT name from accounts where id = '$this->aor_id' and deleted=0";
            $result = $this->db->query($query,true," Error filling in additional detail fields: ");

            // Get the id and the name.
            $row = $this->db->fetchByAssoc($result);

            if($row != null) {
                $this->aor_name = $row['name'];
            }
            else {
                $this->aor_name = '';
            }
        }

        if(isset($this->billing_address_city)) {
            $query = "SELECT name from city_mast where id = '$this->billing_address_city' AND deleted=0";
            $result = $this->db->query($query, true, "Error filling in other detail fields");

            $row = $this->db->fetchByAssoc($result);
//			$GLOBALS['log']->debug("additional detail query results:".print_r($row,true));

            if ($row != null) {
                $this->billing_address_city_desc = $row['name'];
            } else {
                $this->billing_address_city_desc = '';
            }
        }

        if(isset($this->billing_address_state)) {
            $query = "SELECT name from state_mast where id = '$this->billing_address_state' AND deleted=0";
            $result = $this->db->query($query, true, "Error filling in other detail fields");

            $row = $this->db->fetchByAssoc($result);
//			$GLOBALS['log']->debug("additional detail query results: $row");

            if ($row != null) {
                $this->billing_address_state_desc = $row['name'];
            } else {
                $this->billing_address_state_desc = '';
            }
        }

        if(isset($this->billing_address_country)) {
            $query = "SELECT name from country_mast where id = '$this->billing_address_country' AND deleted=0";
            $result = $this->db->query($query, true, "Error filling in other detail fields");

            $row = $this->db->fetchByAssoc($result);
//			$GLOBALS['log']->debug("additional detail query results: $row");

            if ($row != null) {
                $this->billing_address_country_desc = $row['name'];
            } else {
                $this->billing_address_country_desc = '';
            }
        }

        if(isset($this->shipping_address_city)) {
            $query = "SELECT name from city_mast where id = '$this->shipping_address_city' AND deleted=0";
            $result = $this->db->query($query, true, "Error filling in other detail fields");

            $row = $this->db->fetchByAssoc($result);
//			$GLOBALS['log']->debug("additional detail query results: $row");

            if ($row != null) {
                $this->shipping_address_city_desc = $row['name'];
            } else {
                $this->shipping_address_city_desc = '';
            }
        }

        if(isset($this->shipping_address_state)) {
            $query = "SELECT name from state_mast where id = '$this->shipping_address_state' AND deleted=0";
            $result = $this->db->query($query, true, "Error filling in other detail fields");

            $row = $this->db->fetchByAssoc($result);
//			$GLOBALS['log']->debug("additional detail query results: $row");

            if ($row != null) {
                $this->shipping_address_state_desc = $row['name'];
            } else {
                $this->shipping_address_state_desc = '';
            }
        }

        if(isset($this->shipping_address_country)) {
            $query = "SELECT name from country_mast where id = '$this->shipping_address_country' AND deleted=0";
            $result = $this->db->query($query, true, "Error filling in other detail fields");

            $row = $this->db->fetchByAssoc($result);
//			$GLOBALS['log']->debug("additional detail query results: $row");

            if ($row != null) {
                $this->shipping_address_country_desc = $row['name'];
            } else {
                $this->shipping_address_country_desc = '';
            }
        }

        $this->created_by_name = get_assigned_user_name($this->created_by);
        $this->modified_by_name = get_assigned_user_name($this->modified_user_id);

        $this->remove_redundant_http();
    }

    function get_list_view_data() {
        global $system_config;
        $temp_array = $this->get_list_view_array();
        $temp_array["ENCODED_NAME"]=$this->name;
//		$temp_array["ENCODED_NAME"]=htmlspecialchars($this->name, ENT_QUOTES);
        $GLOBALS['log']->debug("In get_list_view_data() :".$temp_array["BILLING_ADDRESS_CITY"]."=".$temp_array["BILLING_ADDRESS_CITY_DESC"]);
        if(isset($temp_array["BILLING_ADDRESS_CITY"]) && !empty($temp_array["BILLING_ADDRESS_CITY"])) {
            $details = get_city_details($this->billing_address_city);
            $temp_array["BILLING_ADDRESS_CITY_ID"] = $this->billing_address_city;
            $temp_array["BILLING_ADDRESS_STATE_ID"] = $this->billing_address_state;
            $temp_array["BILLING_ADDRESS_COUNTRY_ID"] = $this->billing_address_country;
            $temp_array["BILLING_ADDRESS_CITY"] = $details['city_description'];
            $temp_array["BILLING_ADDRESS_STATE"] = $details['state_description'];
            $temp_array["BILLING_ADDRESS_COUNTRY"] = $details['country_description'];
        }
        else if(isset($temp_array["BILLING_ADDRESS_STATE"]) && !empty($temp_array["BILLING_ADDRESS_STATE"])) {
            $details = get_state_details($this->billing_address_state);
            $temp_array["BILLING_ADDRESS_STATE_ID"] = $this->billing_address_state;
            $temp_array["BILLING_ADDRESS_COUNTRY_ID"] = $this->billing_address_country;
            $temp_array["BILLING_ADDRESS_STATE"] = $details['state_description'];
            $temp_array["BILLING_ADDRESS_COUNTRY"] = $details['country_description'];
        }
        else if(isset($temp_array["BILLING_ADDRESS_COUNTRY"]) && !empty($temp_array["BILLING_ADDRESS_COUNTRY"])) {
            $details = get_country_details($this->billing_address_country);
            $temp_array["BILLING_ADDRESS_COUNTRY_ID"] = $this->billing_address_country;
            $temp_array["BILLING_ADDRESS_COUNTRY"] = $details['country_description'];
        }

        if(isset($temp_array["LINKAGE_ID"])) {
            $query = "SELECT name from linkage_mast where id = '".$temp_array["LINKAGE_ID"]."' and deleted=0";
            $result = $this->db->query($query,true," Error filling in additional detail fields: ");

            // Get the id and the name.
            $row = $this->db->fetchByAssoc($result);

            if($row != null) {
                $temp_array["LINKAGE_DESC_C"] = $row['name'];
            }
            else {
                $temp_array["LINKAGE_DESC_C"] = '';
            }
        }
        $temp_array["INDUSTRY"] = $this->industry_name;
//		$GLOBALS['log']->debug("City details ".$this->billing_address_city_desc);
//		$GLOBALS['log']->debug("City details :".$temp_array['CITY']);
        $temp_array["BILLING_ADDRESS_STREET"]  = preg_replace("/[\r]/",'',$this->billing_address_street);
        $temp_array["SHIPPING_ADDRESS_STREET"] = preg_replace("/[\r]/",'',$this->shipping_address_street);
        $temp_array["BILLING_ADDRESS_STREET"]  = preg_replace("/[\n]/",'\n',$temp_array["BILLING_ADDRESS_STREET"] );
        $temp_array["SHIPPING_ADDRESS_STREET"] = preg_replace("/[\n]/",'\n',$temp_array["SHIPPING_ADDRESS_STREET"] );
        if(isset($system_config->settings['system_skypeout_on']) && $system_config->settings['system_skypeout_on'] == 1) {
            if(!empty($temp_array['PHONE_OFFICE']) && skype_formatted($temp_array['PHONE_OFFICE'])) {
                $temp_array['PHONE_OFFICE'] = '<a href="callto://' . $temp_array['PHONE_OFFICE']. '">'.$temp_array['PHONE_OFFICE']. '</a>' ;
            }
        }

        return $temp_array;
    }

    /**
     builds a generic search based on the query string using or
     do not include any $this-> because this is called on without having the class instantiated
     */
    function build_generic_where_clause ($the_query_string) {
        $where_clauses = Array();
        $the_query_string = PearDatabase::quote(from_html($the_query_string));
        array_push($where_clauses, "accounts.name like '$the_query_string%'");
        if (is_numeric($the_query_string)) {
            array_push($where_clauses, "accounts.phone_alternate like '%$the_query_string%'");
            array_push($where_clauses, "accounts.phone_fax like '%$the_query_string%'");
            array_push($where_clauses, "accounts.phone_office like '%$the_query_string%'");
        }

        $the_where = "";
        foreach($where_clauses as $clause) {
            if(!empty($the_where)) $the_where .= " or ";
            $the_where .= $clause;
        }

        return $the_where;
    }

    function create_export_query(&$order_by, &$where) {
        $custom_join = $this->custom_fields->getJOIN();
        $query = "SELECT
					accounts.*,
                    users.user_name as assigned_user_name ";

        if($custom_join) {
            $query .=  $custom_join['select'];
        }
        $query .= "FROM accounts ";

        if($custom_join) {
            $query .=  $custom_join['join'];
        }
        $query .= " LEFT JOIN users
                    	ON accounts.assigned_user_id=users.id ";

        $where_auto = " accounts.deleted=0 ";

        if($where != "")
            $query .= "where ($where) AND ".$where_auto;
        else
            $query .= "where ".$where_auto;

        if(!empty($order_by)) {
            //check to see if order by variable already has table name by looking for dot "."
            $table_defined_already = strpos($order_by, ".");

            if($table_defined_already === false) {
                //table not defined yet, define accounts to avoid "ambigous column" SQL error
                $query .= " ORDER BY $order_by";
            }else {
                //table already defined, just add it to end of query
                $query .= " ORDER BY $order_by";
            }

        }

        return $query;
    }

    function create_list_query($order_by, $where, $show_deleted= 0) {
        $custom_join = $this->custom_fields->getJOIN();

        $query = "SELECT ";

        $query .= "
                    users.user_name assigned_user_name,
                    accounts.*,city_mast_billing.name as billing_address_city_desc,state_mast_billing.name as billing_address_state_desc,country_mast_billing.name as billing_address_country_desc ";
        if($custom_join) {
            $query .=  $custom_join['select'];
        }

        $query .= " FROM  accounts ";
        $query .= "LEFT JOIN users
                    	ON accounts.assigned_user_id=users.id ";

        $query .= "LEFT JOIN city_mast as city_mast_billing
                    	ON accounts.billing_address_city = city_mast_billing.id ";
        $query .= "LEFT JOIN state_mast as state_mast_billing
                    	ON accounts.billing_address_state = state_mast_billing.id ";
        $query .= "LEFT JOIN country_mast as country_mast_billing
                    	ON accounts.billing_address_country = country_mast_billing.id ";

        if($custom_join) {
            $query .=  $custom_join['join'];
        }

        $where_auto = '1=1';
        if($show_deleted == 0) {
            $where_auto = " accounts.deleted=0 ";
        }else if($show_deleted == 1) {
            $where_auto = " accounts.deleted=1 ";
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

    function set_notification_body($xtpl, $account) {
        $xtpl->assign("ACCOUNT_NAME", $account->name);
        $xtpl->assign("ACCOUNT_TYPE", $account->account_type);
        $xtpl->assign("ACCOUNT_DESCRIPTION", $account->description);

        return $xtpl;
    }

    function bean_implements($interface) {
        switch($interface) {
            case 'ACL':return true;
        }
        return false;
    }

    function getBrandsForAccount() {

        $sql = "SELECT brands.id,brands.name,NULL as contact_id,NULL as contact_name FROM brands INNER JOIN accounts_brands ON (brands.id=accounts_brands.brand_id AND accounts_brands.account_id='$this->id')
		 WHERE accounts_brands.deleted=0 AND brands.deleted=0  ";
        $sql .= " UNION ALL ";
        $sql .= "SELECT brands.id,brands.name,contacts.id as contact_id,concat(contacts.first_name,' ',contacts.last_name) as contact_name from brands INNER JOIN brands_contacts ON (brands.id = brands_contacts.brand_id)
		INNER JOIN accounts_contacts ON (brands_contacts.contact_id = accounts_contacts.contact_id AND accounts_contacts.account_id='$this->id')
		INNER JOIN contacts ON accounts_contacts.contact_id = contacts.id where brands_contacts.deleted=0 AND accounts_contacts.deleted=0 AND contacts.deleted=0 AND brands.deleted=0";

//		$GLOBALS['log']->debug("SQL in getBrandsForAccount :".$sql);
//		return $this->brands->getQuery();

        return $sql;
    }

    //not used for now from DeleteRelationship
    function mark_account_brand_deleted($record,$brand_id) {
        $sql="UPDATE accounts_brands SET deleted=1 WHERE account_id='$record' AND brand_id='$brand_id'";
        $GLOBALS['log']->debug("SQL :".$sql);
        $GLOBALS['db']->query($sql);
    }

    function getSAPAccounts() {

        $sql = "SELECT sap_account_details.id as id,sap_account_details.name1 as name1 FROM accounts INNER JOIN sap_account_details ON (accounts.id=sap_account_details.account_id AND accounts.id='$this->id')
		 WHERE sap_account_details.deleted=0 AND accounts.deleted=0  ";

        $GLOBALS['log']->debug("SQL in getSAPAccounts :".$sql);

        return $sql;
    }


    /*
	this function is overridden because we need to introduce check
	for is team member also.
	Author : Jai Ganesh Girinathan
    */
    function isOwner($user_id) {

        if(parent::isOwner($user_id))
            return true;

//		$GLOBALS['log']->debug("In Account.isOwner ".$this->assigned_team_id_c);
        if(isset($this->assigned_team_id_c))
            return TeamOS::isMember($user_id,$this->assigned_team_id_c);
    }

    function get_rfc_table_name() {
        return $this->getTableName().'_rfc';
    }
}

?>
