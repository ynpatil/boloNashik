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
 * $Id: Comment.php,v 1.134 2006/07/20 22:52:03 wayne Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
//om
require_once('data/SugarBean.php');

// Comment is used to store customer information.
class Comment extends SugarBean {
    var $field_name_map;

	// Stored fields
	var $id;
	var $date_entered;
	var $date_modified;
	var $assigned_user_id;
	var $modified_user_id;
	var $created_by;
	var $created_by_name;
	var $modified_by_name;
	var $description;
	var $assigned_user_name;
	var $parent_id;
	var $parent_name;
	var $parent_type;

	var $default_comment_name_values = array('Please comment...');

	var $table_name = "comments";

	var $object_name = "Comment";
	var $module_dir = 'Comments';
	var $comment_for_module;

	// This is used to retrieve related fields from form posts.
	var $additional_column_fields = Array('assigned_user_name', 'assigned_user_id');

	function Comment() {
		//om
		parent::SugarBean();
	}

	var $new_schema = true;

	function get_summary_text()
	{
		//om
		return "$this->name";
	}

	function create_list_query($order_by, $where, $show_deleted = 0)
	{
		$custom_join = $this->custom_fields->getJOIN();
                $query = "SELECT ";

                $query .= "
			$this->table_name.*,
            users.user_name as assigned_user_name";

            if($custom_join){
   				$query .= $custom_join['select'];
 			}
            $query .= " FROM comments ";

                            $query .= " LEFT JOIN users
                            ON comments.assigned_user_id=users.id ";
	   if($custom_join){
   				$query .= $custom_join['join'];
 			}
 			 $where_auto = '1=1';
				if($show_deleted == 0){
                	$where_auto = " comments.deleted=0 ";
				}else if($show_deleted == 1){
                	$where_auto = "$this->table_name.deleted=1";
				}

				//GROUP BY comments.id";

		if($where != "")
			$query .= "where $where AND ".$where_auto;
		else
			$query .= "where ".$where_auto;

		if($order_by != "")
			$query .=  " ORDER BY ". $this->process_order_by($order_by, null);
		else
			$query .= " ORDER BY comments.name";
		return $query;
	}

        function create_export_query(&$order_by, &$where)
        {
				$custom_join = $this->custom_fields->getJOIN();
                 $query = 'SELECT comments.*';

                  if($custom_join){
						$query .= $custom_join['select'];
					}
    	               $query .= ' FROM comments ';
                       $where_auto = "comments.deleted=0";

				if($custom_join){
   				$query .= $custom_join['join'];
				}

                if($where != "")
                        $query .= "where $where AND ".$where_auto;
                else
                        $query .= "where ".$where_auto;

                if($order_by != "")
                        $query .=  " ORDER BY ". $this->process_order_by($order_by, null);
                else
                        $query .= " ORDER BY comments.name";
                return $query;

        }

	function fill_in_additional_list_fields()
	{
//		echo "Parent id ".$this->parent_type;
		if(!empty($this->parent_id) && !empty($this->parent_type)){

			global $beanList,$beanFiles;
			$beanClass = $beanList[$this->parent_type];
			$beanFile = $beanFiles[$beanClass];
			if(!file_exists($beanFile))return;
			require_once($beanFile);

			$bean = new $beanClass();
			$bean->retrieve($this->parent_id);
			$this->parent_name = $bean->get_summary_text();
		}
	}

	function fill_in_additional_detail_fields()
	{
		// Fill in the assigned_user_name
		$this->assigned_user_name = get_assigned_user_name($this->assigned_user_id);

		$this->created_by_name = get_assigned_user_name($this->created_by);
		$this->modified_by_name = get_assigned_user_name($this->modified_user_id);

		$this->fill_in_additional_list_fields();
//		$this->load_relationships();
//
//		$rel_name = "calls_comments";
//		$result = $this->fill_comment_for_details($rel_name);
//		if($result)return;
//
//		$rel_name = "meetings_comments";
//		$result = $this->fill_comment_for_details($rel_name);
//		if($result)return;

	}

	function fill_comment_for_details($rel_name){
		if(isset($this->$rel_name))
		{
			$result = $this->$rel_name->get();
			global $beanList,$beanFiles;
			foreach($result as $key){
				$this->parent_id = $key;
				$this->comment_for_module = $this->$rel_name->getRelatedModuleName();
				$beanName = $beanList[$this->comment_for_module];
				$beanFile = $beanFiles[$beanName];
				if(file_exists($beanFile)){
					require_once($beanFile);
					$seed = new $beanName();
					$seed->retrieve($this->parent_id);
					$this->parent_name = $seed->get_summary_text();
					return true;
				}
			}
		}
		return false;
	}

	function get_list_view_data(){
		global $action, $currentModule, $focus, $current_module_strings, $app_list_strings, $image_path, $timedate;
		$today = $timedate->handle_offset(date("Y-m-d H:i:s", time()), $timedate->dbDayFormat, true);

		$task_fields = $this->get_list_view_array();
//		echo "Parent name ".$this->parent_name;
		$task_fields['PARENT_NAME'] = $this->parent_name;
		$task_fields['PARENT_ID'] = $this->parent_id;
		$task_fields['PARENT_MODULE'] = $this->parent_type;
		return $task_fields;
	}

	function set_notification_body($xtpl, $task)
	{
		global $app_list_strings;

		$xtpl->assign("COMMENT_DESCRIPTION", $task->description);

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
		if(!empty($this->parent_name)){
			if(!empty($this->parent_name_owner)){
				global $current_user;
				$is_owner = $current_user->id == $this->parent_name_owner;
			}
		}

//			if(!ACLController::moduleSupportsACL($this->parent_type) || ACLController::checkAccess($this->parent_type, 'view', $is_owner)){
//				$array_assign['PARENT'] = 'a';
//			}else{
//				$array_assign['PARENT'] = 'span';
//			}
//		$is_owner = false;
//		if(!empty($this->contact_name)){
//			if(!empty($this->contact_name_owner)){
//				global $current_user;
//				$is_owner = $current_user->id == $this->contact_name_owner;
//			}
//		}
//
//		if( ACLController::checkAccess('Contacts', 'view', $is_owner)){
//				$array_assign['CONTACT'] = 'a';
//		}else{
//				$array_assign['CONTACT'] = 'span';
//		}

		return $array_assign;
	}

	function saveAssociatedActivity($parent_activity_id)
	{
		$id = create_guid();
		$query = "insert into assoc_activity(id,parent_id,child_id,relation_type) values('$id','$parent_activity_id', '$this->id','$this->module_dir')";
		$GLOBALS['log']->debug("Associated insert query ".$query);
		$this->db->query($query,true,"Error inserting Assoc Call: "."<BR>$query");
	}
}
?>
