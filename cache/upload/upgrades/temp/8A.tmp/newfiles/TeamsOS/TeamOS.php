<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('data/SugarBean.php');

class TeamOS extends SugarBean {
	// Stored fields
	var $module;
	var $id;
	var $name;
	var $private;
	var $deleted;
	var $date_entered;
	var $date_modified;
	var $table_name = 'teams';
	var $object_name = 'TeamOS';
	var $module_dir = 'TeamsOS';
	var $new_schema = true;
	var $department;

	var $column_fields = Array('id'
		,'name'
		,'private'
		,'deleted'
		,'date_entered'
		,'date_modified'
	);

	var $list_fields= array('name','private','deleted','date_entered','date_modified');
	var $required_fields = array('name');

	function TeamOS() {
		parent::SugarBean();
		$this->list_fields = $this->column_fields;
	}

	function get_xtemplate_data() {
		$return_array = array();
		global $current_user;
		foreach($this->column_fields as $field) {
			$return_array[strtoupper($field)] = $this->$field;
		}
		return $return_array;
	}

	function create_export_query(&$order_by, &$where) {
		$query = "SELECT teams.*";

		if($custom_join) {
			$query .= $custom_join['select'];
		}
		$query .= " FROM " . $this->table_name;

		$where_auto = " teams.deleted=0 ";

		if($where != "") {
			$query .= " where ($where) AND ". $where_auto;
		} else {
			$query .= " where ". $where_auto;
		}
		if(!empty($order_by)) {
			$query .= " ORDER BY $order_by";
		}

		return $query;
	}

	function get_list_view_data() {
		global $current_language, $current_user, $mod_strings, $app_list_strings, $sugar_config;
		$temp_array = $this->get_list_view_array();

		return $temp_array;
	}

	function get_summary_text() {
		return "$this->name";
	}

/*
	Altered this function to make is so that you will see your items even if the item
	belongs to a team that you don't.  In short ownership overrides teams
*/
	function setQuery($where, $module) {
		global $current_user;
		$moduleConfig = TeamOS::configureModules();
		if(isset($moduleConfig[strtoupper($module)])) {
			$teamArr=array();
			foreach ($_SESSION['team_id'] as $value) {
				$teamArr[] = "'".$value."'";
			}
			if($teamArr == "") {
				$new_where = "(" . $moduleConfig[$module] ."_cstm.assigned_team_id_c IS NULL OR ";
				$new_where .= $moduleConfig[$module] ."_cstm.assigned_team_id_c = \"None\" ";
				if($module!="DOCUMENT" && $module!="NOTE") {
					$new_where .= "OR ". $moduleConfig[$module] .".assigned_user_id = '" . $current_user->id . "')";
				} else {
					$new_where .= ")";
				}
			} else {
				$teamArr = join(",", $teamArr);
				$new_where = " (". $moduleConfig[$module] ."_cstm.assigned_team_id_c IN ($teamArr) OR ";
				$new_where .= $moduleConfig[$module] ."_cstm.assigned_team_id_c IS NULL OR ";
				$new_where .= $moduleConfig[$module] ."_cstm.assigned_team_id_c = \"None\" ";
				if($module!="DOCUMENT" && $module!="NOTE") {
					$new_where .= "OR ". $moduleConfig[$module] .".assigned_user_id = '" . $current_user->id . "')";
				} else {
					$new_where .= ")";
				}
			}
		}
		if(is_array($where)) {
			$where[] = $new_where;
		} else {
			if($where!="") {
				$where .= " AND ";
			}
			$where .= $new_where;
		}

		return $where;
	}

	function configureModules() {
		return array(
			'ACCOUNT'        => 'accounts',
			'BUG'            => 'bugs',
			'CALL'           => 'calls',
			'CAMPAIGN'       => 'campaigns',
			'CASE'           => 'cases',
			'CONTACT'        => 'contacts',
			'DOCUMENT'       => 'documents',
			'EMAIL'          => 'emails',
			'EMAIL_TEMPLATE' => 'email_templates',
			'LEAD'           => 'leads',
			'MEETING'        => 'meetings',
			'NOTE'           => 'notes',
			'OPPORTUNITY'    => 'opportunities',
			'PROJECT'        => 'project',
			'PROJECT_TASK'   => 'project_task',
			'PROJECTTASK'    => 'project_task',
			'PROSPECT'       => 'prospects',
			'TASK'           => 'tasks',
		);
	}

	function getModifiedModules() {
		return array(
			'Accounts'       => 1,
			'Bugs'           => 1,
			'Calls'          => 1,
			'Campaigns'      => 1,
			'Cases'          => 1,
			'Contacts'       => 1,
			'Documents'      => 1,
			'Emails'         => 1,
			'EmailTemplates' => 1,
			'Leads'          => 1,
			'Meetings'       => 1,
			'Notes'          => 1,
			'Opportunities'  => 1,
			'Prospects'  	 => 1,
			'Project'        => 1,
			'ProjectTask'    => 1,
			'Tasks'          => 1,
		);
	}

	/** This function should be overridden in each module.  It marks an item as deleted.
	* If it is not overridden, then marking this type of item is not allowed
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	* All Rights Reserved..
	* Contributor(s): ______________________________________..
	*/
	function mark_deleted($id)
	{
		require_once('modules/TeamsOS/TeamFormBase.php');
		$form = new TeamFormBase();
		$query = "DELETE FROM $this->table_name WHERE id='$id'";
		$this->db->query($query, true,"Error marking record deleted: ");
		// Take the item off of the recently viewed lists.
		$tracker = new Tracker();
		$tracker->delete_item_history($id);
		$moduleList = $this->configureModules();
		foreach ($moduleList as $key=>$value) {
			$query="DELETE FROM " . $value . "_cstm WHERE assigned_team_id_c='$id'";
			$this->db->query($query, true,"Error Deleting record from " . $value . "_cstm");
		}
		$query="DELETE FROM team_membership WHERE team_id='$id'";
		$this->db->query($query, true,"Error Deleting record from team_membership");
		$form->update_dropdown();
	}

	function get_all_members($id) {
		global $mod_strings;
		require_once("modules/Users/User.php");
		$focus_users=new User();
		$query="SELECT * FROM team_membership WHERE team_id='$id'";
		$result = $this->db->query($query, true,"Error Getting Team: ");
		$ids=array();
		$temp_ids=array();
		while(($row=$this->db->fetchByAssoc($result)) != null) {
			//echo "user: " . $row['user_id'] . "<br>";
			if(!$this->private) {
				$ids=$this->get_reports_to($row['user_id'],$ids);
			}
			$temp_ids=$ids;
			unset($return_ids);
			$return_ids=array();
			foreach ($temp_ids AS $user_id) {
				if($user_id!=$row['user_id']) {
					array_push($return_ids,$user_id);
				}
			}
			$ids=$return_ids;
			//print_r($ids);
		}
		$ids=array_unique($ids);
		if(count($ids)>0) {
			$return_value=$mod_strings['LBL_TEAM_MEMBERS'];
			foreach($ids as $user_id) {
				$focus_users->retrieve($user_id);
				$return_value .= "<font color=blue>" . $focus_users->name . "</font>, ";
			}
		} else {
			$return_value="";
		}
		return substr($return_value,0,strlen($return_value)-2);
	}

	function isMember($user_id,$team_id) {
		$sql="SELECT * FROM team_membership WHERE user_id='$user_id' AND team_id='$team_id'";
		$result_check=$this->db->query($sql,true);
		if($this->db->getRowCount($result_check)>0) {
			return true;
		} else {
			return false;
		}
	}

	function get_team_select($default_value) {
		$query="SELECT id,name FROM teams ORDER BY name";
		$result = $this->db->query($query, true,"Error Getting Team: ");
		if(empty($default_value)) {
			$return_values = "<option value=''></option>\n";
		} else {
			$return_values = "";
		}
		while(($row=$this->db->fetchByAssoc($result)) != null) {
			if($default_value==$row['id']) {
				$selected="SELECTED";
			} else {
				$selected="";
			}
			$return_values .= "<option value='" . $row['id'] . "' $selected>" . $row['name'] . "</option>\n";
		}
		return $return_values;
	}

function get_default_team_select($default_value,$selected_user) {
	global $app_strings;
	global $current_user;
	$has_teams=false;
	$test_values="";
	if(is_array($default_value)) {
		foreach ($default_value as $value) {
			$test_values .= "'" . $value . "',";
		}
	} else {
		$test_values=$default_value;
	}
	if($current_user->show_all_teams_c || is_admin($current_user)) {
		$query="SELECT id AS team_id,name FROM teams WHERE deleted=0";
	} else {
		$query="SELECT tm.user_id AS user_id,tm.team_id AS team_id,teams.name AS name
		         FROM team_membership AS tm, teams
		         WHERE tm.user_id='$selected_user' AND
		               tm.team_id=teams.id AND
		               teams.deleted=0
		         ORDER BY teams.name";
	}
	$result = $this->db->query($query, true, "Error Getting default Teams: ");
	$return_values = "<option value=''>" . $app_strings['LBL_NO_TEAM'] . "</option>\n";
	while(($row=$this->db->fetchByAssoc($result)) != null) {
		if(stristr($test_values,$row['team_id'])===false) {
			$selected="";
		} else {
			$selected="SELECTED";
		}
		$return_values .= "<option value='" . $row['team_id'] . "' $selected>" . $row['name'] . "</option>\n";
		$has_teams=true;
	}
	if($has_teams==false) {
		$return_values = "<option value=''>-- " . $app_strings['LBL_NO_TEAM'] . " --</option>\n";
	}
	return $return_values;
}

	/**
	 * Save the bean.  All changes to this bean will be recorded in the data store.
	 */
	/**
	* This method implements a generic insert and update logic for any SugarBean
	* This method only works for subclasses that implement the same variable names.
	* This method uses the presence of an id field that is not null to signify and update.
	* The id field should not be set otherwise.
	* todo - Add support for field type validation and encoding of parameters.
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
	* All Rights Reserved.
	* Contributor(s): ______________________________________..
	*/

	function save($check_notify = FALSE)
	{
		global $timedate;
		global $current_user, $action;
		$isUpdate = true;
		if(empty($this->id)) {
			$isUpdate = false;
		}

		if ( $this->new_with_id == true ) {
			$isUpdate = false;
		}

		if(empty($this->date_modified) || $this->update_date_modified) {
			$this->date_modified = gmdate("Y-m-d H:i:s");
		}

		if($this->optimistic_lock && !isset($_SESSION['o_lock_fs'])) {

			if(isset($_SESSION['o_lock_id']) &&
			         $_SESSION['o_lock_id'] == $this->id &&
			         $_SESSION['o_lock_on'] == $this->object_name){

				if($action == 'Save' &&
				   $isUpdate &&
				   isset($this->modified_user_id) &&
				   $this->has_been_modified_since($_SESSION['o_lock_dm'], $this->modified_user_id)) {
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
				} else {
						unset ($_SESSION['o_lock_object']);
						unset ($_SESSION['o_lock_id']);
						unset ($_SESSION['o_lock_dm']);
				}
			}
		} else {
			if(isset($_SESSION['o_lock_object']))	{ unset ($_SESSION['o_lock_object']); }
			if(isset($_SESSION['o_lock_id']))		{ unset ($_SESSION['o_lock_id']); }
			if(isset($_SESSION['o_lock_dm']))		{ unset ($_SESSION['o_lock_dm']); }
			if(isset($_SESSION['o_lock_fs']))		{ unset ($_SESSION['o_lock_fs']); }
			if(isset($_SESSION['o_lock_save']))		{ unset ($_SESSION['o_lock_save']); }
		}

		if($this->update_modified_by) {
			$this->modified_user_id = 1;
			if (!empty($current_user)) {
				$this->modified_user_id = $current_user->id;
			}
		}

		if ($this->deleted != 1) {
			$this->deleted = 0;
		}

		if($isUpdate) {
			$query = "Update ";
			if(!$this->update_date_entered){
				unset($this->date_entered);
			}
		} else {
			if (empty($this->date_entered)) {
				$this->date_entered = $this->date_modified;
			}
			if($this->set_created_by == true) {
            	// created by should always be this user
				$this->created_by = (isset($current_user)) ? $current_user->id : "";
			}
			if($this->new_schema &&
			   $this->new_with_id == false) {
				$this->id = create_guid();
			}
			$query = "INSERT into ";
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
        	if ($isUpdate && !isset($this->fetched_row)) {
        		$GLOBALS['log']->debug('Auditing: Retrieve was not called, audit record will not be created.');
        	} else {
        		$dataChanges=$this->dbManager->helper->getDataChanges($this);
        	}
        }

       	if(isset($this->custom_fields)){
   			 $this->custom_fields->bean =& $this;
   			 $this->custom_fields->save($isUpdate);
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
	    			if(isset($this->field_name_map) &&
	    			   !empty($this->field_name_map[$field]['custom_type'])) {
	    			   	continue;
	    			}

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
	    				if(0 == $firstPass) {
	    					$firstPass = 1;
	    				} else {
	    					$query .= ", ";
	    				}

						if(is_null($this->$field)) {
							$query .= $field."=null";
						} else {
	    					$query .= $field."='".PearDatabase::quote(from_html($this->$field))."'";
	    				}
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
		if ($this->db->dbType == 'mssql') {
			if($isUpdate) {
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
                                //added check for ints because sql-server does not like casting varchar with a decimal value
                                //into an int.
                                if(isset($value['type']) and $value['type']=='int') {
                                    $query .= $field."=".PearDatabase::quote(from_html($this->$field));
                                } else {
                                    $query .= $field."='".PearDatabase::quote(from_html($this->$field))."'";
                                }
							}
						}
					}
				}
				$query = $query." WHERE ID = '$this->id'";
    			$GLOBALS['log']->info("Update $this->object_name: ".$query);
			} else {
	              $colums = array();
                  $values = array();
				foreach($this->field_defs as $field=>$value) {
						if(!isset($value['source']) || $value['source'] == 'db') {
						// Do not write out the id field on the update statement.
						// We are not allowed to change ids.
						//if($isUpdate && ('id' == $field)) continue;
						//custom fields handle there save seperatley

						if(isset($this->field_name_map) && !empty($this->field_name_map[$field]['custom_type']))
							continue;


						// Only assign variables that have been set.
						if(isset($this->$field)) {
                            //trim the value in case empty space is passed in.
                            //this will allow default values set in db to take effect, otherwise
                            //will insert blanks into db
                                $trimmed_field = trim($this->$field);
                                //if this value is empty, do not include the field value in statement
                                if($trimmed_field =='') {
                                    continue;
                                }

                                //added check for ints because sql-server does not like casting varchar with a decimal value
                                //into an int.
                                if(isset($value['type']) and $value['type']=='int') {
                                    $values[] = PearDatabase::quote(from_html($this->$field));
                                } else {
                                    $values[] = "'".PearDatabase::quote(from_html($this->$field))."'";
                                }
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
		if (isset($this->track_on_save) && $this->track_on_save == true && isset($this->module_dir)) {
			$this->track_view($current_user->id, $this->module_dir);
		}
		return $this->id;
	}

	function getTeamList($scope) {
		global $current_user;
		$return_array=array();
		$team_array=array();

		switch ($scope) {
			case 'ALL':
				$sql="SELECT * FROM " . $this->table_name . " WHERE deleted=0";
				$result=$this->db->query($sql);
				while(($row=$this->db->fetchByAssoc($result)) != null) {
						$return_array[$row['id']]=$row['name'];
				}
				break;

			case 'MEMBER':
				$team_array=$this->retrieve_team_id($current_user->id);
				foreach ($team_array as $team_id) {
					if(!empty($team_id) && $team_id!="None") {
						echo "Return team ids ".$team_id;
						$return_array[$team_id]=$this->get_team_name($team_id);
					}
				}
				break;

			default:
				$team_array=$this->retrieve_team_id($current_user->id);
				foreach ($team_array as $team_id) {
					if(!empty($team_id) && $team_id!="None") {
						$return_array[$team_id]=$this->get_team_name($team_id);
					}
				}
				break;

		}
		return $return_array;
	}

	function get_team_name($team_id) {
		$sql="SELECT * FROM teams WHERE id='$team_id'";
		$result=$this->db->query($sql,true);
		$hash=$this->db->fetchByAssoc($result);
		return $hash['name'];
	}

	function get_assigned_team_name($assigned_team_id, $is_group = ' AND is_group=0 ') {
		static $saved_team_list = null;
		$blankvalue = '';

		if(empty($saved_team_list)) {
			$saved_team_list = $this->get_team_array(false, '', '', false, null, $is_group);
		}

		if(isset($saved_team_list[$assigned_team_id])) {
			return $saved_team_list[$assigned_team_id];
		}

		return $blankvalue;
	}

	function get_team_array($add_blank=true, $status="0", $assigned_team="", $use_real_name=false, $team_name_begins = null, $is_group=' AND is_group=0 ') {
		global $app_strings;
		$team_array = get_register_value('team_array', $add_blank. $status . $assigned_team);

		if(!$team_array) {
			$temp_result = Array();
			// Including deleted users for now.
			if (empty($status)) {
				$query = "SELECT id, name from teams WHERE 1=1";
			}
			else {
				$query = "SELECT id, name from teams WHERE deleted=$status";
			}

			if (!empty($team_name_begins)) {
				$query .= " AND name LIKE '$team_name_begins%' ";
			}
			if (!empty($assigned_team)) {
				$query .= " OR id='$assigned_team'";
			}
			$query = $query.' order by name asc';

			$GLOBALS['log']->debug("get_team_array query: $query");
			$result = $this->db->query($query, true, "Error filling in team array: ");

			if ($add_blank==true) {
				// Add in a blank row
				$temp_result[$app_strings['LBL_NO_TEAM']] = '';
			}

			// Get the id and the name.
			while($row = $this->db->fetchByAssoc($result)) {
				$temp_result[$row['id']] = $row['name'];
			}

			$team_array = $temp_result;
			set_register_value('team_array', $add_blank. $status . $assigned_team, $temp_result);
		}

		return $team_array;
	}

	function get_reports_to($id, $ids) {
		global $current_user;
		if(!$_SESSION['teams_reports_to']) {
			$_SESSION['teams_reports_to'] = $id;
		}
		if(isset($id) && ! is_null($id) && $id != "" && ! in_array($id, $ids)) {
			array_push($ids, $id);
			$current_user->retrieve($id);
			$ids = $this->get_reports_to($current_user->reports_to_id, $ids);
		}
		$current_user->retrieve($_SESSION['teams_reports_to']);

		return $ids;
	}

	function get_is_boss_of($id, $ids) {
		global $current_user;
		$is_boss_of = array();
		$query  = "SELECT id FROM users WHERE reports_to_id = '$id'";
		$result = $this->db->query($query, false, "Error retrieving reports_to_id: ");

		while($a = $this->db->fetchByAssoc($result)) {
			array_push($ids, $a['id']);
			$ids = $this->get_is_boss_of($a['id'], $ids);
		}

		return $ids;
	}

	function retrieve_team_id($user_id='') {
			global $current_user;
			global $app_strings;

			if($user_id=='') {
				$user_id=$current_user->id;
			}

			$return_string="";
			$ids=array();

			//Get all the non private teams of my bosses and their bosses and so on forever and ever.......
			$ids=$this->get_is_boss_of($user_id,$ids);
			foreach ($ids as $new_id) {
				$return_string .= "'" . $new_id . "',";
			}
			$return_string=substr($return_string,0,strlen($return_string)-1);
			$query_team = "SELECT teams.id as team_id
			                FROM team_membership AS tm,teams
			                WHERE tm.team_id=teams.id AND
			                      tm.user_id IN (". $return_string .") AND
			                      teams.deleted=0 AND teams.private=0";
			$GLOBALS['log']->debug("Team Membership query: $query_team");
			$team_id = array();
			$result = $this->db->query($query_team, false, "Error retrieving team ID: ");

			while($a = $this->db->fetchByAssoc($result)) {
				$GLOBALS['log']->debug("--> Added Team: " . $this->get_team_name($a['team_id']));
				$team_id[] = $a['team_id'];
			}

			//OK Now add my teams to the list
			$query_team = "SELECT teams.id as team_id
			                FROM team_membership AS tm, teams
			                WHERE tm.team_id=teams.id AND
			                      tm.user_id = '". $user_id ."' AND
			                      teams.deleted=0";
			$GLOBALS['log']->debug("Team Membership query: $query_team");
			$result = $this->db->query($query_team, false, "Error retrieving team ID: ");
			while($a = $this->db->fetchByAssoc($result)) {
				$GLOBALS['log']->debug("--> Added Team: " . $this->get_team_name($a['team_id']));
				$team_id[] = $a['team_id'];
			}

			//Remove the duplicates
			$team_id=array_unique($team_id);

			//$team_id[]=$app_strings['LBL_NO_TEAM'];
			return $team_id;
		}

}
?>
