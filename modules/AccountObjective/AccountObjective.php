<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * Data access layer for the account_objective table
 *
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
 */
//om
// $Id: AccountObjective.php,v 1.58 2006/06/29 18:30:47 eddy Exp $

require_once('data/SugarBean.php');
require_once('include/utils.php');

/**
 *
 */
class AccountObjective extends SugarBean {
	// database table columns
	var $id;
	var $date_entered;
	var $date_modified;
	var $assigned_user_id;
	var $modified_user_id;
	var $created_by;
	var $name;
	var $deleted;

	var $parent_desc;
	var $parent_type;
	var $mkt_obj;
	var $comm_obj;
	var $mkt_pri;
	var $latest_happen;

	var $new_with_id = false;

	// related information
	var $assigned_user_name;
	var $modified_by_name;
	var $created_by_name;

	var $object_name = 'AccountObjective';
	var $module_dir = 'AccountObjective';
	var $new_schema = false;
	var $table_name = 'account_objective';


	//////////////////////////////////////////////////////////////////
	// METHODS
	//////////////////////////////////////////////////////////////////

	/**
	 *
	 */
	function AccountObjective()
	{
		parent::SugarBean();
		//echo "Field name map :".count($this->field_name_map);

		static $loaded_defs;

		$GLOBALS['log']->debug("Checking whether additional_column_fields are set :".isset($loaded_defs[$this->object_name]['additional_column_fields']));

		if(!isset($loaded_defs[$this->object_name]['additional_column_fields'])){
	        $this->additional_column_fields = LoadCachedArray($this->module_dir, $this->object_name, 'additional_column_fields');
			$loaded_defs[$this->object_name]['additional_column_fields'] =& $this->additional_column_fields;
		}
		else
		$GLOBALS['log']->debug("Not reloading additional_column_fields");

		$this->additional_column_fields =& $loaded_defs[$this->object_name]['additional_column_fields'];

		$GLOBALS['log']->debug("Additional column fields :".implode(',',$this->additional_column_fields));
	}

	/**
	 *
	 */
	function fill_in_additional_detail_fields()
	{
		$this->assigned_user_name = get_assigned_user_name($this->assigned_user_id);
		$this->created_by_name = get_assigned_user_name($this->created_by);
		$this->modified_by_name = get_assigned_user_name($this->modified_user_id);
	}

	/**
	 *
	 */
	function fill_in_additional_list_fields()
	{
		$this->assigned_user_name = get_assigned_user_name($this->assigned_user_id);
		$this->created_by_name = get_assigned_user_name($this->created_by);
	}

	/**
	 *
	 */
	function get_summary_text()
	{
		return $this->parent_desc;
	}

	/**
	 *
	 */
	function build_generic_where_clause ($the_query_string)
	{
		$where_clauses = array();
		$the_query_string = PearDatabase::quote(from_html($the_query_string));

		$the_where = '';
		foreach($where_clauses as $clause)
		{
			if($the_where != '') $the_where .= " OR ";
			$the_where .= $clause;
		}

		return $the_where;
	}

	function retrieve($id = -1, $encode=true,$show_deleted=false) {

		if ($id == -1) {
			$id = $this->id;
		}

		if(isset($this->parent_type))
		{
			global $beanList,$beanFiles;
			$beanClass = $beanList[$this->parent_type];
//			echo "Bean file :".$beanFiles[$beanClass]." class ".$beanClass;

			require_once($beanFiles[$beanClass]);

			$bean = new $beanClass();
			$bean->retrieve($id,$encode);
			$this->parent_desc = $bean->name;
		}

		if(isset($this->custom_fields))
		{
			$custom_join = $this->custom_fields->getJOIN();

		}else $custom_join = false;

		if($custom_join){
			$query = "SELECT $this->table_name.*". $custom_join['select']. " FROM $this->table_name ";
		}else{
			$query = "SELECT $this->table_name.* FROM $this->table_name ";
		}

		if($custom_join){
			$query .= ' ' . $custom_join['join'];
		}
		$query .= " WHERE $this->table_name.id = '$id'";

		if($show_deleted)
		$query .= "AND deleted=1";//added since records are not coming from ListView Jai Ganesh
		else
		$query .= "AND deleted=0";

		$GLOBALS['log']->debug("Retrieve $this->object_name : ".$query);
        //requireSingleResult has beeen deprecated.
		//$result = $this->db->requireSingleResult($query, true, "Retrieving record by id $this->table_name:$id found ");
		$result = $this->db->limitQuery($query,0,1,true, "Retrieving record by id $this->table_name:$id found ");

		if(empty($result)) {
			$GLOBALS['log']->debug("New with id value :".$this->new_with_id);
			$this->new_with_id = true;
		   	$this->id = $_REQUEST['record'];
			return null;
		}

		$row = $this->db->fetchByAssoc($result, -1, $encode);
		if(empty($row)){
			$this->new_with_id = true;
		   	$this->id = $_REQUEST['record'];
			$GLOBALS['log']->debug("New with id value empty row :".$this->new_with_id);
			return null;
		}

		//make copy of the fetched row for construction of audit record and for business logic/workflow
		$this->fetched_row=$row;

		$this->populateFromRow($row);

		global $module, $action;
		//Just to get optimistic locking working for this release
		if($this->optimistic_lock && $module == $this->module_dir && $action =='EditView' ){
			$_SESSION['o_lock_id']= $id;
			$_SESSION['o_lock_dm']= $this->date_modified;
			$_SESSION['o_lock_on'] = $this->object_name;
		}

		$this->processed_dates_times = array();
		$this->check_date_relationships_load();

		if($custom_join){
			$this->custom_fields->fill_relationships();
		}
		$this->fill_in_additional_detail_fields();

		//make a copy of fields in the relatiosnhip_fields array. these field values will be used to
		//clear relatioship.
    	if (isset($this->relationship_fields) && is_array($this->relationship_fields)) {
    		foreach ($this->relationship_fields as $rel_id=>$rel_name) {
    			if (isset($this->$rel_id))
					$this->rel_fields_before_value[$rel_id]=$this->$rel_id;
				else
					$this->rel_fields_before_value[$rel_id]=null;
    		}
    	}

		// call the custom business logic
		$custom_logic_arguments['id'] = $id;
		$custom_logic_arguments['encode'] = $encode;
		$this->call_custom_logic("after_retrieve", $custom_logic_arguments);
		unset($custom_logic_arguments);

		$this->new_with_id = false;
		$this->deleted = 0;

		return $this;
	}

	function inherit($id = -1,$field,$parent_type)
	{
		global $beanFiles,$beanList;

		$GLOBALS['log']->debug("In inherit with :".$id." field :".$field." parent type :".$parent_type);

		$beanClass = $beanList[$parent_type];

		if(file_exists($beanFiles[$beanClass]))
		{
		require_once($beanFiles[$beanClass]);
		$bean = new $beanClass();

		if($bean)
		{
			$bean->retrieve($id);

			$GLOBALS['log']->debug("Parent ID found :".$bean->parent_id);

			if(isset($bean->parent_id))
			{
				$requestedMethod = "getSpecificData";
				if(method_exists($this, $requestedMethod))
				$bean = $this->$requestedMethod($bean->parent_id,$field);
				if($bean)
				return $bean->$field;
			}
		}
		}

		return null;
	}

	function getSpecificData($id = -1,$field, $encode=true,$show_deleted=false) {

		if ($id == -1) {
			$id = $this->id;
		}

		$query = "SELECT $this->table_name.id,$this->table_name.$field FROM $this->table_name ";

		$query .= " WHERE $this->table_name.id = '$id'";

		if($show_deleted)
		$query .= "AND deleted=1";//added since records are not coming from ListView Jai Ganesh
		else
		$query .= "AND deleted=0";

		$GLOBALS['log']->debug("Retrieve $this->object_name : ".$query);
		$result = $this->db->limitQuery($query,0,1,true, "Retrieving record by id $this->table_name:$id found ");

		if(empty($result)) {
			$GLOBALS['log']->debug("New with id value :".$this->new_with_id);
			$this->new_with_id = true;
		   	$this->id = $_REQUEST['record'];
			$this->fetched_row = null;
			return null;
		}

		$row = $this->db->fetchByAssoc($result, -1, $encode);
		if(empty($row)){
			$this->new_with_id = true;
		   	$this->id = $_REQUEST['record'];
			$GLOBALS['log']->debug("New with id value empty row :".$this->new_with_id);
			$this->fetched_row = null;
			return null;
		}

		//make copy of the fetched row for construction of audit record and for business logic/workflow
		$this->fetched_row=$row;

		$this->populateFromRow($row);

		global $module, $action;
		//Just to get optimistic locking working for this release
		if($this->optimistic_lock && $module == $this->module_dir && $action =='EditView' ){
			$_SESSION['o_lock_id']= $id;
			$_SESSION['o_lock_dm']= $this->date_modified;
			$_SESSION['o_lock_on'] = $this->object_name;
		}

		$this->processed_dates_times = array();
		$this->check_date_relationships_load();

		$this->new_with_id = false;
		$this->deleted = 0;

		return $this;
	}

	function save($check_notify = FALSE)//allows for part data save from AccountObjectiveDashlet
	{
		global $timedate;
		global $current_user, $action;
		$isUpdate = true;
		if(empty($this->id))
		{
			$isUpdate = false;
		}

		if ( $this->new_with_id == true )
		{
			$isUpdate = false;
		}

		$GLOBALS['log']->debug("In save with new_with_id = ".$this->new_with_id." is update ".$isUpdate." id = ".$this->id);

		if(empty($this->date_modified) || $this->update_date_modified){
			$this->date_modified = gmdate("Y-m-d H:i:s");
		}

		if($this->optimistic_lock && !isset($_SESSION['o_lock_fs'])){

			if(isset($_SESSION['o_lock_id']) && $_SESSION['o_lock_id'] == $this->id && $_SESSION['o_lock_on'] == $this->object_name){

				 if($action == 'Save' && $isUpdate && isset($this->modified_user_id) && $this->has_been_modified_since($_SESSION['o_lock_dm'], $this->modified_user_id)){

			 		$_SESSION['o_lock_class'] = get_class($this);
			 		$_SESSION['o_lock_module'] = $this->module_dir;
			 		$_SESSION['o_lock_object'] = $this->toArray();
			 		$saveform = "<form name='save' id='save' method='POST'>";
			 		foreach($_POST as $key=>$arg){
			 			$saveform .= "<input type='hidden' name='". addslashes($key) ."' value='". addslashes($arg) ."'>";
			 		}
			 		$saveform .= "</form><script>document.getElementById('save').submit();</script>";
			 		$_SESSION['o_lock_save'] = $saveform;
			 		header('Location: index.php?module=OptimisticLock&action=LockResolve');
					die();
				 }	else{
							unset ($_SESSION['o_lock_object']);
							unset ($_SESSION['o_lock_id']);
							unset ($_SESSION['o_lock_dm']);
					 }
			}
		}else{
			if(isset($_SESSION['o_lock_object']))	{ unset ($_SESSION['o_lock_object']); }
			if(isset($_SESSION['o_lock_id']))		{ unset ($_SESSION['o_lock_id']); }
			if(isset($_SESSION['o_lock_dm']))		{ unset ($_SESSION['o_lock_dm']); }
			if(isset($_SESSION['o_lock_fs']))		{ unset ($_SESSION['o_lock_fs']); }
			if(isset($_SESSION['o_lock_save']))		{ unset ($_SESSION['o_lock_save']); }
		}

		if($this->update_modified_by)
		{
			$this->modified_user_id = 1;

			if (!empty($current_user))
			{
				$this->modified_user_id = $current_user->id;
			}
		}

		if ($this->deleted != 1) $this->deleted = 0;

		if($isUpdate)
		{
			$query = "Update ";
		}
		else
		{
			if (empty($this->date_entered))
			{
				$this->date_entered = $this->date_modified;
			}

			if($this->set_created_by == true){
            	// created by should always be this user
				$this->created_by = (isset($current_user)) ? $current_user->id : "";
			}

			if($this->new_schema &&
			$this->new_with_id == false)
			{
				$this->id = create_guid();
			}

			$query = "INSERT into ";
		}

		if($isUpdate && !$this->update_date_entered){
			unset($this->date_entered);
		}

		// call the custom business logic
		$custom_logic_arguments['check_notify'] = $check_notify;
		$this->call_custom_logic("before_save", $custom_logic_arguments);
		unset($custom_logic_arguments);

		// use the db independent query generator

        $this->check_date_relationships_save();

        //construct the SQL to create the audit record if auditing is enabled.
		$dataChanges=array();
        if ($this->is_AuditEnabled()) {
        	//$GLOBALS['log']->debug("Fetched row :".$this->fetched_row." isset :".isset($this->fetched_row));

        	if ($isUpdate && !isset($this->fetched_row)) {
        		$GLOBALS['log']->debug('Auditing: Retrieve was not called, audit record will not be created.');
        	} else if($isUpdate) {
        		$dataChanges=$this->dbManager->helper->getDataChanges($this);
        	}
        }

		// send assignment notifications AND invites for activities
		if($check_notify) { // cn: bug 5795 - no invites sent to Contacts
			require_once("modules/Administration/Administration.php");
			$admin = new Administration();
			$admin->retrieveSettings();
			$sendNotifications = false;

			if ($admin->settings['notify_on']) {
				$GLOBALS['log']->info("Notifications: user assignment has changed, checking if user receives notifications");
				$sendNotifications = true;
			} elseif(isset($_REQUEST['send_invites']) && $_REQUEST['send_invites'] == 1) {
				// cn: bug 5795 Send Invites failing for Contacts
				$sendNotifications = true;
			} else {
				$GLOBALS['log']->info("Notifications: not sending e-mail, notify_on is set to OFF");
			}


			if($sendNotifications == true) {
				$notify_list = $this->get_notification_recipients();
				foreach ($notify_list as $notify_user) {
					$this->send_assignment_notifications($notify_user, $admin);
				}
			}
		}

       	if(isset($this->custom_fields)){
   			 $this->custom_fields->bean =& $this;
   			 $this->custom_fields->save($isUpdate);
       	}
        if ($this->db->dbType == "oci8"){

        }
        if ($this->db->dbType == 'mysql')
        {
    		// write out the SQL statement.
	       	$query .= $this->table_name." set ";

    		$firstPass = 0;

    		foreach($this->field_defs as $field=>$value) {
	    		if(!isset($value['source']) || $value['source'] == 'db') {
	    			// Do not write out the id field on the update statement.
	    			// We are not allowed to change ids.
	    			if($isUpdate && ('id' == $field)) continue;
	    			//custom fields handle there save seperatley
	    			if(isset($this->field_name_map) && !empty($this->field_name_map[$field]['custom_type']))
	    				continue;

	    			// Only assign variables that have been set.
	    			if(isset($this->$field)) {
	    				if(strlen($this->$field) <= 0) {
	    					if(!$isUpdate && isset($value['default']) && (strlen($value['default']) > 0)) {
	    						$this->$field = $value['default'];
	    					}
	    					else {
	    						$this->$field = null;
	    					}
	    				}

						if(is_null($this->$field)) {
							continue;
						}

	    				// Try comparing this element with the head element.
	    				if(0 == $firstPass) $firstPass = 1;
	    				else $query .= ", ";

	    					$query .= $field."='".PearDatabase::quote(from_html($this->$field))."'";
	    			}
    			}
    		}

    		if($isUpdate)
    		{
    			$query = $query." WHERE ID = '$this->id'";
    			$GLOBALS['log']->info("Update $this->object_name: ".$query);
    		} else  {
    			$GLOBALS['log']->info("Insert: ".$query);
    		}

            $GLOBALS['log']->info("Save: $query");
    		$this->db->query($query, true);
        }

        //process if type is set to mssql
		if ($this->db->dbType == 'mssql')
		{
			if($isUpdate)
			{
				// build out the SQL UPDATE statement.
				$query = "UPDATE " . $this->table_name." SET ";
				$firstPass = 0;

				foreach($this->field_defs as $field=>$value) {
					if(!isset($value['source']) || $value['source'] == 'db') {
						// Do not write out the id field on the update statement.
						// We are not allowed to change ids.
						if($isUpdate && ('id' == $field)) continue;

                        // If the field is an auto_increment field, then we shouldn't be setting it.  This was added
                        // specially for Bugs and Cases which have a number associated with them.
                        if ($isUpdate && isset($this->field_name_map[$field]['auto_increment']) && $this->field_name_map[$field]['auto_increment'] == true)
                            continue;

                        //custom fields handle their save seperatley
						if(isset($this->field_name_map) && !empty($this->field_name_map[$field]['custom_type']))
							continue;

						// Only assign variables that have been set.
						if(isset($this->$field)) {
							if(strlen($this->$field) <= 0) {
								if(!$isUpdate && isset($value['default']) && (strlen($value['default']) > 0)) {
									$this->$field = $value['default'];
								}
								else {
									$this->$field = null;
								}
							}
							// Try comparing this element with the head element.
							if(0 == $firstPass) $firstPass = 1;
							else $query .= ", ";

							if(is_null($this->$field)) {
								$query .= $field."=null";
							}
							else {
								$query .= $field."='".PearDatabase::quote(from_html($this->$field))."'";
							}
						}
					}
				}
				$query = $query." WHERE ID = '$this->id'";
    			$GLOBALS['log']->info("Update $this->object_name: ".$query);
			}
			else
			{
	              $colums = array();
                  $values = array();
				foreach($this->field_defs as $field=>$value)
				{

						if(!isset($value['source']) || $value['source'] == 'db')
						{
						// Do not write out the id field on the update statement.
						// We are not allowed to change ids.
						//if($isUpdate && ('id' == $field)) continue;
						//custom fields handle there save seperatley

						if(isset($this->field_name_map) && !empty($this->field_name_map[$field]['custom_type']))
							continue;


						// Only assign variables that have been set.
						if(isset($this->$field))
						{
                            //trim the value in case empty space is passed in.
                            //this will allow default values set in db to take effect, otherwise
                            //will insert blanks into db
                                $trimmed_field = trim($this->$field);
                                //if this value is empty, do not include the field value in statement
                                if($trimmed_field ==''){
                                    continue;
                                }
							$values[] = "'".PearDatabase::quote(from_html($this->$field))."'";
                            $columns[] = $field;
						}
					}
				}
                // build out the SQL INSERT statement.
                $query = "INSERT INTO $this->table_name (" .implode("," , $columns). " ) VALUES ( ". implode("," , $values). ')';
    			$GLOBALS['log']->info("Insert: ".$query);
			}

            $GLOBALS['log']->info("Save: $query");
    		$this->db->query($query, true);
        }

        if (!empty($dataChanges) && is_array($dataChanges)) {
        	foreach ($dataChanges as $change) {
       			$this->dbManager->helper->save_audit_records($this,$change);
        	}
        }
		// let subclasses save related field changes
		$this->save_relationship_changes($isUpdate);

		//if track_on_save is set ot true create the track record.
		/*
		if (isset($this->track_on_save) && $this->track_on_save == true && isset($this->module_dir)) {
			$this->track_view($current_user->id, $this->module_dir);
		}
		*/

		return $this->id;
	}

	/**
	 * function isOwner($user_id)
	 *
	 * returns true of false if the user_id passed is the owner
	 *
	 * @param GUID $user_id
	 * @return boolean
	 */
	function isOwner($user_id){

		global $beanList,$beanFiles;

		//$GLOBALS['log']->debug("In isOwner :type ".$this->parent_type." user id ".$user_id);

		if(!isset($this->parent_type) || empty($this->parent_type))
		return parent::isOwner($user->id);

		global $current_user;

		if(is_admin($current_user))
		return true;

		$beanClass = $beanList[$this->parent_type];

		if(file_exists($beanFiles[$beanClass]))
		{
			require_once($beanFiles[$beanClass]);
			$bean = new $beanClass();

			if($bean)
			{
				$bean->retrieve($this->id);
				return $bean->isOwner($user_id);
			}
		}

		return false;
	}

	function get_list_view_data()
	{
		$field_list = $this->get_list_view_array();
		$field_list['USER_NAME'] = empty($this->user_name) ? '' : $this->user_name;
		$field_list['CREATED_BY'] = $this->created_by_name;
		return $field_list;
	}
	  function bean_implements($interface){
		switch($interface){
			case 'ACL':return true;
		}
		return false;
	}
}
?>
