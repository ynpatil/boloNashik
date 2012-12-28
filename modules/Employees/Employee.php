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

 * Description: TODO:  To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/



require_once('data/SugarBean.php');

// Employee is used to store customer information.
class Employee extends SugarBean {
	// Stored fields
	var $name = '';
	var $id;
	var $is_admin;
	var $first_name;
	var $last_name;
	var $full_name;
	var $user_name;
	var $title;
	var $description;
	var $department;
	var $reports_to_id;
	var $reports_to_name;
	//kbrill
	var $iostatus_options;
	var $teams_list;

	var $phone_home;
	var $phone_mobile;
	var $phone_work;
	var $phone_other;
	var $phone_fax;
	var $email1;
	var $email2;
	var $address_street;
	var $address_city;
	var $address_state;
	var $address_postalcode;
	var $address_country;
	var $date_entered;
	var $date_modified;
	var $modified_user_id;
	var $created_by;
	var $created_by_name;
	var $modified_by_name;
	var $employee_status;
	var $messenger_id;
	var $messenger_type;
	var $error_string;

	var $module_dir = "Employees";

	var $table_name = "users";

	var $object_name = "User";
	var $user_preferences;

	var $encodeFields = Array("first_name", "last_name", "description");

	// This is used to retrieve related fields from form posts.
	var $additional_column_fields = Array('reports_to_name');



	var $new_schema = true;

	function Employee() {
		parent::SugarBean();
		//$this->setupCustomFields('Employees');
	}

	// need to override to have a name field created for this class
	function retrieve($id = -1, $encode=true)
	{
		$ret_val = parent::retrieve($id, $encode);

		// make a properly formatted first and last name

		$full_name = '';

		if(!empty($this->first_name))
		{
			$full_name = $this->first_name;
		}

		if(!empty($full_name) && !empty($this->last_name))
		{
			$full_name .= ' ' . $this->last_name;
		}
		elseif(empty($full_name) && !empty($this->last_name))
		{
			$full_name = $this->last_name;
		}

		$this->name = $full_name;

		return $ret_val;
	}

	function save($check_notify = false){
		parent::save($check_notify);
	}



	function get_summary_text() {
        $this->_create_proper_name_field();
        return $this->name;
    }

	function fill_in_additional_list_fields() {
		global $mod_strings;
		global $app_strings;
		global $current_user;
		static $formnumber;
		$formnumber++;

		if($formnumber==1) {
			$this->iostatus_options = "\n\n<input type='hidden' name='module' value='Employees'>\n";
			$this->iostatus_options .= "<input type='hidden' name='action' value='ListView'>\n";
			$this->iostatus_options .= "<input type='hidden' name='io_id' value=''>\n";
			$this->iostatus_options .= "<input type='hidden' name='io_status' value=''>\n";
			$this->iostatus_options .= "<input type='hidden' name='io_msg' value=''>\n";
		}
		$query="SELECT * FROM users_cstm WHERE id_c='$this->id'";
		$result = $this->db->query($query, true, "Error filling in additional list fields");
		$row = $this->db->fetchByAssoc($result);
		$current_status = $row['io_status_c'];
		if(!empty($row['io_msg_c']) && $row['io_msg_c']!="" && $row['io_msg_c']!="null") {
			$this->iomsg_text = "<tr><td>&nbsp;</td><td>&nbsp;</td><td colspan='99'><b>".$row['io_msg_c']."</b><td><tr>\n";
		} else {
			$this->iomsg_text = "";
		}

		if($current_status=="") {
			$current_status="In";
		}
		if($this->id!=$current_user->id && !is_admin($current_user)) {
			$disabled="disabled";
		} else {
			$disabled="";
		}
		$this->iostatus_options .= "<select name='iostatus" . $formnumber . "' style='background-color:" . $app_strings['io_board_color_dom'][$current_status] . "; color:black' onchange=\"document.MassUpdate.io_id.value='$this->id';document.MassUpdate.io_status.value=document.MassUpdate.iostatus".$formnumber.".value;if(document.MassUpdate.iostatus".$formnumber.".value!='In') {document.MassUpdate.io_msg.value=prompt('Message','')};document.MassUpdate.submit();\" $disabled>\n";
		foreach ($app_strings['io_board_dom'] as $iostatus_dom) {
			if ($current_status==$iostatus_dom) {
				$selected="Selected /";
			} else {
				$selected="/";
			}
			$this->iostatus_options .= "<option value='" . $iostatus_dom . "' style='background-color:" . $app_strings['io_board_color_dom'][$iostatus_dom] . "; color:black' $selected>" . $iostatus_dom . "</option>\n";
		}
		$this->iostatus_options .= "</select>\n\n";
		$this->fill_in_additional_detail_fields();
	}

	function fill_in_additional_detail_fields()
	{
		$query = "SELECT u1.first_name, u1.last_name from users u1, users u2 where u1.id = u2.reports_to_id AND u2.id = '$this->id' and u1.deleted=0";
		$result =$this->db->query($query, true, "Error filling in additional detail fields") ;

		$row = $this->db->fetchByAssoc($result);
		$GLOBALS['log']->debug("additional detail query results: $row");

		if($row != null)
		{
			$this->reports_to_name = stripslashes($row['first_name'].' '.$row['last_name']);
		}
		else
		{
			$this->reports_to_name = '';
		}
	}

	function retrieve_employee_id($employee_name)
	{
		$query = "SELECT id from users where user_name='$user_name' AND deleted=0";
		$result  = $this->db->query($query, false,"Error retrieving employee ID: ");
		$row = $this->db->fetchByAssoc($result);
		return $row['id'];
	}

	/**
	 * @return -- returns a list of all employees in the system.
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	 */
	function verify_data()
	{
		//none of the checks from the users module are valid here since the user_name and
		//is_admin_on fields are not editable.
		return TRUE;
	}

	function get_list_view_data(){
		global $image_path;
		$this->_create_proper_name_field(); // create proper NAME (by combining first + last)
		$user_fields = $this->get_list_view_array();
		// Copy over the reports_to_name
		$user_fields['REPORTS_TO_NAME'] = $this->reports_to_name;
		$user_fields['NAME'] = empty($this->name) ? '' : $this->name;
		return $user_fields;
	}

	function list_view_parse_additional_sections(&$list_form, $xTemplateSection){
		return $list_form;
	}

	function create_list_query($order_by, $where, $show_deleted=0)
	{
		global $current_user;
		$custom_join = $this->custom_fields->getJOIN();
		$query = "SELECT tm.team_id, users.*, reports.first_name as reports_first_name, reports.last_name as reports_last_name";
		if($custom_join){
			$query .= $custom_join['select'];
		}
		$query .= " FROM users";

		$query .= " LEFT JOIN users  reports ON reports.id=users.reports_to_id";
		$query .= " LEFT JOIN users_cstm ON users.id=users_cstm.id_c";

		//TeamsOS Integration
		if(isset($_SESSION['team_id'])) {
			$query .= " LEFT JOIN team_membership tm ON users.id=tm.user_id";
		}

		if($custom_join){
			$query .= $custom_join['join'];
		}
		$where_auto = '1=1';

		if($show_deleted == 0) {
			$where_auto = " users.deleted = 0";
			if(!is_admin($current_user)) {
					$where_auto .= " AND users.employee_status<>'Terminated'";
			}
		}
		else if($show_deleted == 1){
				$where_auto = " users.deleted = 1";
		}

		// cn: adding clause for group users;
		(empty($where)) ? $where = $where : $where .= ' AND ';
		$where .= "users.is_group=0";

		//TeamsOS Integration
		$teams_search=$_REQUEST['search_teams'];
		if(isset($_SESSION['team_id']) &&
		  (!is_admin($current_user) || count($teams_search)>0)) {
			$teamArr=array();
			if(count($teams_search)>0) {
				foreach ($teams_search as $value) {
					$teamArr[] = "'".$value."'";
				}
			} else {
				foreach ($_SESSION['team_id'] as $value) {
					$teamArr[] = "'".$value."'";
				}
			}
			if(count($teamArr)>0){
				$team_array=join(",",$teamArr);
				$where .= " AND (tm.team_id IN ($team_array) AND tm.deleted=0)";
			}
		}

		if($where != "")
			$query .= " WHERE $where AND " . $where_auto;
		else
			$query .= " WHERE " . $where_auto;

		//TeamsOS Integration
		if(isset($_SESSION['team_id'])) {
			$query .= " GROUP BY users.id";
		}

		if($order_by != "")
			$query .= " ORDER BY $order_by";
		else
			$query .= " ORDER BY users.user_name";

		return $query;
	}

	function create_export_query($order_by, $where) {
		include('modules/Employees/field_arrays.php');

		$cols = '';
		foreach($fields_array['User']['export_fields'] as $field) {
			$cols .= (empty($cols)) ? '' : ', ';
			$cols .= $field;
		}

		$query = "SELECT {$cols} FROM users ";

		$where_auto = " users.deleted = 0";

		if($where != "")
			$query .= " WHERE $where AND " . $where_auto;
		else
			$query .= " WHERE " . $where_auto;

		if($order_by != "")
			$query .= " ORDER BY $order_by";
		else
			$query .= " ORDER BY users.user_name";

		return $query;
	}

	/**
	 * Generate the name field from the first_name and last_name fields.
	 */
	function _create_proper_name_field() {
        global $locale;
        $full_name = $locale->getLocaleFormattedName($this->first_name, $this->last_name);
        $this->name = $full_name;
        $this->full_name = $full_name;
	}
}

?>
