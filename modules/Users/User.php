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
 * $Id: User.php,v 1.191 2006/08/28 21:56:54 wayne Exp $
 * Description:
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
//om
require_once ('log4php/LoggerManager.php');
require_once('modules/UserPreferences/UserPreference.php');
require_once ('data/SugarBean.php');

// User is used to store customer information.
class User extends SugarBean {
	// Stored fields
	var $name = '';
	var $full_name;
	var $id;
	var $user_name;
	var $user_hash;
	var $first_name;
	var $last_name;
	var $date_entered;
	var $date_modified;
	var $modified_user_id;
	var $created_by;
	var $created_by_name;
	var $modified_by_name;
	var $description;
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
	var $address_city_desc;
	var $address_state_desc;
	var $address_country_desc;
	var $status;
	var $title;
	var $portal_only;
	var $department;
	var $authenticated = false;
	var $error_string;
	var $is_admin;
	var $is_superuser;
	var $employee_status;
	var $messenger_id;
	var $messenger_type;
	var $is_group;
	var $accept_status; // to support Meetings

	var $receive_notifications;

	var $reports_to_name;
	var $reports_to_id;
	var $team_exists = false;
	var $table_name = "users";
	var $module_dir = 'Users';
	var $object_name = "User";
	var $user_preferences;

	var $savingpreferencetodb = false;
	var $encodeFields = Array ("first_name", "last_name", "description");

	// This is used to retrieve related fields from form posts.
	var $additional_column_fields = array ('reports_to_name');

	var $new_schema = true;

	var $suboffice_id;
	var $usertype_id;
	var $verticals_id;
	var $suboffice_name;
	var $usertype_name;
	var $verticals_name;

	function User() {
		//om
		parent :: SugarBean();
	}

	function get_summary_query($where)
	{
		return $query = "SELECT
				concat(users.first_name,' ',users.last_name) as assigned_user_name,concat(users1.first_name,' ',users1.last_name) as reports_to_name,
				users.id as assigned_user_id,branch_mast.name as branch_name,branch_mast.id as branch_id,suboffice_mast.name as suboffice_name,
				verticals_mast.name as vertical_name,usertype_mast.name as user_type_name 
				 FROM users LEFT JOIN users as users1 ON users.reports_to_id = users1.id 
								LEFT JOIN suboffice_mast ON users.suboffice_id = suboffice_mast.id
								LEFT JOIN suboffice_mast_cstm ON suboffice_mast.id = suboffice_mast_cstm.id_c
								LEFT JOIN branch_mast ON suboffice_mast_cstm.branch_id_c = branch_mast.id
								LEFT JOIN verticals_mast ON users.verticals_id = verticals_mast.id LEFT JOIN usertype_mast ON users.usertype_id = usertype_mast.id WHERE ".$where." ORDER BY branch_name,vertical_name";
	}

	function getReportsTo()
	{
		//om
//		$GLOBALS['log']->debug("Reports to".$this->reports_to);
		$rel_name = "user_reportsto";
		$this->load_relationship($rel_name);
		$id_array = $this->$rel_name->get();

		$present = false;
		foreach($id_array as $id){
			$this->reports_to[$id] = $this->retrieve_user_name($id);
			$present = true;
		}

		if(!$present)
		$this->reports_to[$this->id] = $this->retrieve_user_name($this->id);

		if(isset($this->reports_to_id))
		$this->reports_to[$this->reports_to_id] = $this->retrieve_user_name($this->reports_to_id);
		/*
		if(!isset($this->reports_to) || !is_array($this->reports_to) || count($this->reports_to) == 0)
		{
			$query = "SELECT parent_id from user_reports where child_id = '$this->id'";
			$result = $this->db->query($query,true,"Error getting user reports hierarchy");
			//echo "Query :".$query;
			$present = false;

			while(($row = $this->db->fetchByAssoc($result))!=null)
			{
				$present = true;
				//echo "Reports to Id1 :".$row['reports_to_id']."<br>";
				$this->reports_to[$row['parent_id']] = $this->retrieve_user_name($row['parent_id']);
			}

			if(!$present)
			$this->reports_to[$this->id] = $this->retrieve_user_name($this->id);

		}
		*/
		return $this->reports_to;//used in Review called to get Boss list
	}

	function retrieve_user_name($user_id)
	{
		//om
		$query = "SELECT user_name from users where id='$user_id' AND deleted=0";
		$result  =& $this->db->query($query, false,"Error retrieving user NAME ");
		$row = $this->db->fetchByAssoc($result);
		return $row['user_name'];
	}

    /**
     * returns an admin user
     */
    function getSystemUser() {
        if(null !== $this->retrieve('1')) {
            return $this;
        } else {
            // handle cases where someone deleted user with id "1"
            $q = "SELECT users.id FROM users WHERE users.status = 'Active' AND users.delted = 0 AND users.is_admin = 1";
            $r = $this->db->query($q);
            while($a = $this->db->fetchByAssoc($r)) {
                $this->retrieve($a['id']);
                return $this;
            }
        }
    }

	function getDefaultSignature() {
		$where = '';
		if($defaultId = $this->getPreference('signature_default')) {
			$where = ' AND id = \''.$defaultId.'\'';
			$q = 'SELECT signature, signature_html FROM users_signatures WHERE user_id = \''.$this->id.'\''.$where;
			$r = $this->db->query($q);
			$a = $this->db->fetchByAssoc($r);

			return $a;
		} else {
			return '';
		}
	}

	/**
	 * retrieves any signatures that the User may have created as <select>
	 */
	function getSignatures($live=false, $defaultSig='') {
		$q = 'SELECT * FROM users_signatures WHERE user_id = \''.$this->id.'\' AND deleted = 0 ORDER BY name ASC';
		$r = $this->db->query($q);
		$sig = array(""=>"");
		while($a = $this->db->fetchByAssoc($r)) {
			$sig[$a['id']] = $a['name'];
		}
		$change = '';
		if(!$live) {
			$change = 'onChange="setSigEditButtonVisibility();" ';
		}
		$signs  = '<select '.$change.' id="signature_id" name="signature_id" tabindex="390">';
		$signs .= get_select_options($sig, $defaultSig).'</select>';
		return $signs;
	}

	/**
	 * returns buttons and JS for signatures
	 */
	function getSignatureButtons() {
		global $mod_strings;
		$butts 	= '<input class="button" onclick="javascript:open_email_signature_form(\'\')" value="'.$mod_strings['LBL_BUTTON_CREATE'].'" type="button" tabindex="391">&nbsp;';
		$butts .= '<span name="edit_sig" id="edit_sig" style="visibility:hidden;"><input class="button" onclick="javascript:open_email_signature_form(document.getElementById(\'signature_id\').value)" value="'.$mod_strings['LBL_BUTTON_EDIT'].'" type="button" tabindex="392">&nbsp;
					</span>';
		return $butts;
	}

	/**
	 * performs a rudimentary check to verify if a given user has setup personal
	 * InboundEmail
	 */
	function hasPersonalEmail() {
		$q = 'SELECT count(id) AS count FROM inbound_email WHERE group_id = \''.$this->id.'\'';
		$r = $this->db->query($q);
		$a = $this->db->fetchByAssoc($r);
		if($a['count'] > 0)
			return true;
		else
			return false;
	}

	/* Returns the User's private GUID; this is unassociated with the User's
	 * actual GUID.  It is used to secure file names that must be HTTP://
	 * accesible, but obfusicated.
	 */
	function getUserPrivGuid() {
        $userPrivGuid = $this->getPreference('userPrivGuid', 'global', $this);
		if ($userPrivGuid) {
			return $userPrivGuid;
		} else {
			$this->setUserPrivGuid();
			if (!isset ($_SESSION['setPrivGuid'])) {
				$_SESSION['setPrivGuid'] = true;
				$userPrivGuid = $this->getUserPrivGuid();
				return $userPrivGuid;
			} else {
				sugar_die("Breaking Infinite Loop Condition: Could not setUserPrivGuid.");
			}
		}
	}

	function setUserPrivGuid() {
		$privGuid = create_guid();
		//($name, $value, $nosession=0)
		$this->setPreference('userPrivGuid', $privGuid, 0, 'global', $this);
	}

	/**
	 * Alias for setPreference in modules/UserPreferences/UserPreference.php
	 *
	 */
	function setPreference($name, $value, $nosession = 0, $category = 'global', $user = null) {
        if(isset($user)) {
            UserPreference::setPreference($name, $value, $nosession, $category, $user);
        }
        else {
            UserPreference::setPreference($name, $value, $nosession, $category, $this);
        }
	}

	/**
	 * Alias for setPreference in modules/UserPreferences/UserPreference.php
	 *
	 */
	function resetPreferences($user = null, $category = null) {
        if(isset($user)) {
            UserPreference::resetPreferences($category, $user);
        }
        else {
            UserPreference::resetPreferences($category, $this);
        }
	}

	/**
	 * Alias for setPreference in modules/UserPreferences/UserPreference.php
	 * Prior to 4.5 this was mispelled.
	 */
	function savePreferecesToDB($user = null) {
        if(isset($user)) {
            UserPreference::savePreferencesToDB($user); // note the correct spelling!
        }
        else {
            UserPreference::savePreferencesToDB($this);
        }
	}

	/**
	 * Alias for setPreference in modules/UserPreferences/UserPreference.php
	 *
	 */
	function savePreferencesToDB($user = null) {
        if(isset($user)) {
            UserPreference::savePreferencesToDB($user); // note the correct spelling!
        }
        else {
            UserPreference::savePreferencesToDB($this);
        }
	}

	/**
	 * Alias for setPreference in modules/UserPreferences/UserPreference.php
	 *
	 */
	function getUserDateTimePreferences($user = null) {
        if(isset($user)) {
            return UserPreference::getUserDateTimePreferences($user);
        }
        else {
            return UserPreference::getUserDateTimePreferences($this);
        }

	}

	/**
	 * Alias for setPreference in modules/UserPreferences/UserPreference.php
	 *
	 */
	function loadPreferences($category = 'global', $user = null) {
        if(isset($user)) {
            UserPreference::loadPreferences('global', $user);
        }
        else {
            UserPreference::loadPreferences('global', $this);
        }
	}

	/**
	 * Alias for setPreference in modules/UserPreferences/UserPreference.php
	 *
	 */
	function getPreference($name, $category = 'global', $user = null) {
        if(isset($user)) {
            return UserPreference::getPreference($name, $category, $user);
        }
        else {
            return UserPreference::getPreference($name, $category, $this);
        }
	}

	function save($check_notify = false) {

		// wp: do not save user_preferences in this table, see user_preferences module
		$this->user_preferences = '';
		if($this->is_superuser)
		$this->is_admin = $this->is_superuser;

		if(isset($this->phone_mobile)){
			$this->phone_mobile = ereg_replace("[^0-9]", "", $this->phone_mobile);
			if(strlen($this->phone_mobile)<=10)
			$this->phone_mobile="91$this->phone_mobile";
		}

		parent :: save($check_notify);

        $this->savePreferencesToDB($this);
	}

    function get_summary_text() {
        $this->_create_proper_name_field();
        return $this->name;
	}

	/**
	* @return string encrypted password for storage in DB and comparison against DB password.
	* @param string $user_name - Must be non null and at least 2 characters
	* @param string $user_password - Must be non null and at least 1 character.
	* @desc Take an unencrypted username and password and return the encrypted password
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	*/
	function encrypt_password($user_password) {
		// encrypt the password.
		$salt = substr($this->user_name, 0, 2);
		$encrypted_password = crypt($user_password, $salt);

		return $encrypted_password;
	}

	function authenticate_user($password) {

		$query = "SELECT * from $this->table_name where user_name='$this->user_name' AND user_hash='$password' AND (portal_only IS NULL OR portal_only !='1') AND (is_group IS NULL OR is_group !='1') ";
		//$result = $this->db->requireSingleResult($query, false);
		$result = $this->db->limitQuery($query,0,1,false);
		$a = $this->db->fetchByAssoc($result);
		// set the ID in the seed user.  This can be used for retrieving the full user record later
		if (empty ($a)) {
			// already logging this in load_user() method
			//$GLOBALS['log']->fatal("SECURITY: failed login by $this->user_name");
			return false;
		} else {
			$this->id = $a['id'];
			return true;
		}
	}

    /**
     * retrieves an User bean
     * preformat name & full_name attribute with first/last
     * loads User's preferences
     *
     * @param string id ID of the User
     * @param bool encode encode the result
     * @return object User bean
     * @return null null if no User found
     */
	function retrieve($id, $encode = true) {
		global $locale;

		$ret = SugarBean :: retrieve($id, $encode);

		if ($ret) {
			// make a properly formatted first and last name
			$this->_create_proper_name_field();

			if (isset ($_SESSION)) {
				$this->loadPreferences();
			}
		}
		return $ret;
	}
	/**
	 * Load a user based on the user_name in $this
	 * @return -- this if load was successul and null if load failed.
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	 */
	function load_user($user_password) {
		global $login_error;
		unset($GLOBALS['login_error']);
		if(isset ($_SESSION['loginattempts'])) {
			$_SESSION['loginattempts'] += 1;
		} else {
			$_SESSION['loginattempts'] = 1;
		}
		if($_SESSION['loginattempts'] > 5) {
			$GLOBALS['log']->fatal('SECURITY: '.$this->user_name.' has attempted to login '.$_SESSION['loginattempts'].' times from IP address: '.$_SERVER['REMOTE_ADDR'].'.');
		}

		$GLOBALS['log']->debug("Starting user load for $this->user_name");

		if (!isset ($this->user_name) || $this->user_name == "" || !isset ($user_password) || $user_password == "")
			return null;

		checkAuthUserStatus();

		$user_hash = strtolower(md5($user_password));
		if($this->authenticate_user($user_hash)) {
			$query = "SELECT * from $this->table_name where id='$this->id'";
		} else {
			$GLOBALS['log']->fatal('SECURITY: User authentication for '.$this->user_name.' failed');
			return null;
		}
		$r = $this->db->limitQuery($query, 0, 1, false);
		$a = $this->db->fetchByAssoc($r);
		if(empty($a) || !empty ($GLOBALS['login_error'])) {
			$GLOBALS['log']->fatal('SECURITY: User authentication for '.$this->user_name.' failed - could not Load User from Database');
			return null;
		}

		// Get the fields for the user
		$row = $a;

		// If there is no user_hash is not present or is out of date, then create a new one.
		if (!isset ($row['user_hash']) || $row['user_hash'] != $user_hash) {
			$query = "UPDATE $this->table_name SET user_hash='$user_hash' where id='{$row['id']}'";
			$this->db->query($query, true, "Error setting new hash for {$row['user_name']}: ");
		}

		// now fill in the fields.
		foreach ($this->column_fields as $field) {
			$GLOBALS['log']->info($field);

			if (isset ($row[$field])) {
				$GLOBALS['log']->info("=".$row[$field]);

				$this-> $field = $row[$field];
			}
		}

		$this->loadPreferences($this);
		require_once ('modules/Administration/updater_utils.php');

		require_once ('modules/Versions/CheckVersions.php');
		$invalid_versions = get_invalid_versions();

		if (!empty ($invalid_versions)) {
			if (isset ($invalid_versions['Rebuild Relationships'])) {
				unset ($invalid_versions['Rebuild Relationships']);

				// flag for pickup in DisplayWarnings.php
				$_SESSION['rebuild_relationships'] = true;
			}

			if (isset ($invalid_versions['Rebuild Extensions'])) {
				unset ($invalid_versions['Rebuild Extensions']);

				// flag for pickup in DisplayWarnings.php
				$_SESSION['rebuild_extensions'] = true;
			}

			$_SESSION['invalid_versions'] = $invalid_versions;
		}
		$this->fill_in_additional_detail_fields();
		if ($this->status != "Inactive")
			$this->authenticated = true;

		unset ($_SESSION['loginattempts']);
		return $this;
	}

	/**
	* @param string $user name - Must be non null and at least 1 character.
	* @param string $user_password - Must be non null and at least 1 character.
	* @param string $new_password - Must be non null and at least 1 character.
	* @return boolean - If passwords pass verification and query succeeds, return true, else return false.
	* @desc Verify that the current password is correct and write the new password to the DB.
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	*/
	function change_password($user_password, $new_password) {
		global $mod_strings;
		global $current_user;
		$GLOBALS['log']->debug("Starting password change for $this->user_name");

		if (!isset ($new_password) || $new_password == "") {
			$this->error_string = $mod_strings['ERR_PASSWORD_CHANGE_FAILED_1'].$current_user['user_name'].$mod_strings['ERR_PASSWORD_CHANGE_FAILED_2'];
			return false;
		}

		$old_user_hash = strtolower(md5($user_password));

		if (!is_admin($current_user)) {
			//check old password first
			$query = "SELECT user_name FROM $this->table_name WHERE user_hash='$old_user_hash' AND id='$this->id'";
			$result = $this->db->query($query, true);
			$row = $this->db->fetchByAssoc($result);
			$GLOBALS['log']->debug("select old password query: $query");
			$GLOBALS['log']->debug("return result of $row");

			if ($row == null) {
				$GLOBALS['log']->warn("Incorrect old password for ".$this->user_name."");
				$this->error_string = $mod_strings['ERR_PASSWORD_INCORRECT_OLD_1'].$this->user_name.$mod_strings['ERR_PASSWORD_INCORRECT_OLD_2'];
				return false;
			}
		}

		$user_hash = strtolower(md5($new_password));

		//set new password
		$query = "UPDATE $this->table_name SET user_hash='$user_hash' where id='$this->id'";
		$this->db->query($query, true, "Error setting new password for $this->user_name: ");
		return true;
	}

        function save_vacation($date_start,$date_end) {
		global $mod_strings;
		global $current_user;
		$GLOBALS['log']->debug("Starting vacation change for $this->user_name");

		if (!isset ($date_start) || $date_end == "") {
			$this->error_string = "Vacation changes failed for ".$this->user_name;
			return false;
		}

		//check old password first
                $vacation_id = create_guid();
        	$query = "insert into users_vacation(id,user_id,start_date,end_date,deleted) values('".$vacation_id."','".$this->id."',STR_TO_DATE('".$date_start."','%d-%m-%Y'),STR_TO_DATE('".$date_end."','%d-%m-%Y'),0)";
		$result = $this->db->query($query);
		return true;
	}

        function delete_vacation($id){
            global $current_user;
            $query = "update users_vacation set deleted=1 WHERE id='".$id."' AND user_id='".$current_user->id."'";
            $result = $this->db->query($query);
            return true;
        }

        function get_vacation($user_id){
            global $timedate;
            $query = "SELECT * from users_vacation WHERE user_id='".$user_id."' and deleted=0";
            $result = $GLOBALS['db']->query($query, true, "Error in get_vacation ".$user_id);

            $list = array();

            while($row = $this->db->fetchByAssoc($result))
            {
                $list[] = array('id'=>$row['id'],'start_date'=>$timedate->to_display($row['start_date'],"Y-m-d","d-m-Y"),'end_date'=>$timedate->to_display($row['end_date'],"Y-m-d","d-m-Y"));
            }
            return $list;
        }

	function is_authenticated() {
		return $this->authenticated;
	}

	function fill_in_additional_list_fields() {
		$this->fill_in_additional_detail_fields();
	}

	function fill_in_additional_detail_fields() {
		global $locale;

//		$this->full_name = $locale->getLocaleFormattedName($this->first_name, $this->last_name);

		$query = "SELECT u1.first_name, u1.last_name from users  u1, users  u2 where u1.id = u2.reports_to_id AND u2.id = '$this->id' and u1.deleted=0";
		$result = $this->db->query($query, true, "Error filling in additional detail fields");

		$row = $this->db->fetchByAssoc($result);
//		$GLOBALS['log']->debug("additional detail query results: $row");

		if ($row != null) {
			$this->reports_to_name = stripslashes($row['first_name'].' '.$row['last_name']);
		} else {
			$this->reports_to_name = '';
		}

		$this->fill_in_other_details();
		$this->_create_proper_name_field();
	}

	function fill_in_other_details(){

		if(!isset($this->suboffice_name) && !empty($this->suboffice_id)){
			$query = "SELECT name from suboffice_mast where id = '$this->suboffice_id' AND deleted=0";
			$result = $this->db->query($query, true, "Error filling in other detail fields");

			$row = $this->db->fetchByAssoc($result);
//			$GLOBALS['log']->debug("additional detail query results: $row");

			if ($row != null) {
				$this->suboffice_name = $row['name'];
			} else {
				$this->suboffice_name = '';
			}
		}

		if(!isset($this->usertype_name) && !empty($this->usertype_id)){
			$query = "SELECT name from usertype_mast where id = '$this->usertype_id' AND deleted=0";
			$result = $this->db->query($query, true, "Error filling in other detail fields");

			$row = $this->db->fetchByAssoc($result);
//			$GLOBALS['log']->debug("additional detail query results: $row");

			if ($row != null) {
				$this->usertype_name = $row['name'];
			} else {
				$this->usertype_name = '';
			}
		}

		if(!isset($this->verticals_name) && !empty($this->verticals_id)){
			$query = "SELECT name from verticals_mast where id = '$this->verticals_id' AND deleted=0";
			$result = $this->db->query($query, true, "Error filling in other detail fields");

			$row = $this->db->fetchByAssoc($result);
//			$GLOBALS['log']->debug("additional detail query results: $row");

			if ($row != null) {
				$this->verticals_name = $row['name'];
			} else {
				$this->verticals_name = '';
			}
		}

		if(!isset($this->address_city_desc) && !empty($this->address_city)){
			$query = "SELECT name from city_mast where id = '$this->address_city' AND deleted=0";
			$result = $this->db->query($query, true, "Error filling in other detail fields");

			$row = $this->db->fetchByAssoc($result);
//			$GLOBALS['log']->debug("additional detail query results: $row");

			if ($row != null) {
				$this->address_city_desc = $row['name'];
			} else {
				$this->address_city_desc = '';
			}
		}

		if(!isset($this->address_state_desc) && !empty($this->address_state)){
			$query = "SELECT name from state_mast where id = '$this->address_state' AND deleted=0";
			$result = $this->db->query($query, true, "Error filling in other detail fields");

			$row = $this->db->fetchByAssoc($result);
//			$GLOBALS['log']->debug("additional detail query results: $row");

			if ($row != null) {
				$this->address_state_desc = $row['name'];
			} else {
				$this->address_state_desc = '';
			}
		}

		if(!isset($this->address_country_desc) && !empty($this->address_country)){
			$query = "SELECT name from country_mast where id = '$this->address_country' AND deleted=0";
			$result = $this->db->query($query, true, "Error filling in other detail fields");

			$row = $this->db->fetchByAssoc($result);
//			$GLOBALS['log']->debug("additional detail query results: $row");

			if ($row != null) {
				$this->address_country_desc = $row['name'];
			} else {
				$this->address_country_desc = '';
			}
		}
	}

	function retrieve_user_id($user_name) {
		$query = "SELECT id from users where user_name='$user_name' AND deleted=0";
		$result = $this->db->query($query, false, "Error retrieving user ID: ");
		$row = $this->db->fetchByAssoc($result);
		return $row['id'];
	}

	/**
	 * @return -- returns a list of all users in the system.
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	 */
	function verify_data($ieVerified=true) {
		global $mod_strings, $current_user;
		$verified = TRUE;

		if (!empty ($this->id)) {
			// Make sure the user doesn't report to themselves.
			$reports_to_self = 0;
			$check_user = $this->reports_to_id;
			$already_seen_list = array ();
			while (!empty ($check_user)) {
				if (isset ($already_seen_list[$check_user])) {
					// This user doesn't actually report to themselves
					// But someone above them does.
					$reports_to_self = 1;
					break;
				}
				if ($check_user == $this->id) {
					$reports_to_self = 1;
					break;
				}
				$already_seen_list[$check_user] = 1;
				$query = "SELECT reports_to_id FROM users WHERE id='".PearDatabase :: quote($check_user)."'";
				$result = $this->db->query($query, true, "Error checking for reporting-loop");
				$row = $this->db->fetchByAssoc($result);
				echo ("fetched: ".$row['reports_to_id']." from ".$check_user."<br>");
				$check_user = $row['reports_to_id'];
			}

			if ($reports_to_self == 1) {
				$this->error_string .= $mod_strings['ERR_REPORT_LOOP'];
				$verified = FALSE;
			}
		}

		$query = "SELECT user_name from users where user_name='$this->user_name' AND deleted=0";
		if(!empty($this->id))$query .=  " AND id<>'$this->id'";
		$result = $this->db->query($query, true, "Error selecting possible duplicate users: ");
		$dup_users = $this->db->fetchByAssoc($result);

		if (!empty($dup_users)) {
			$this->error_string .= $mod_strings['ERR_USER_NAME_EXISTS_1'].$this->user_name.$mod_strings['ERR_USER_NAME_EXISTS_2'];
			$verified = FALSE;
		}

		if (($current_user->is_admin == "on")) {
            if($this->db->dbType == 'mssql'){
                $query = "SELECT user_name from users where is_admin = 1 AND deleted=0";
            }else{
                $query = "SELECT user_name from users where is_admin = 'on' AND deleted=0";
            }
			$result = $this->db->query($query, true, "Error selecting possible duplicate users: ");
			$remaining_admins = $this->db->getRowCount($result);

			if (($remaining_admins <= 1) && ($this->is_admin != "on") && ($this->id == $current_user->id)) {
				$GLOBALS['log']->debug("Number of remaining administrator accounts: {$remaining_admins}");
				$this->error_string .= $mod_strings['ERR_LAST_ADMIN_1'].$this->user_name.$mod_strings['ERR_LAST_ADMIN_2'];
				$verified = FALSE;
			}
		}
		///////////////////////////////////////////////////////////////////////
		////	InboundEmail verification failure
		if(!$ieVerified) {
			$verified = false;
			$this->error_string .= '<br />'.$mod_strings['ERR_EMAIL_NO_OPTS'];
		}

		return $verified;
	}


	/**
	 * Generate the name field from the first_name and last_name fields.
	 */
	function _create_proper_name_field() {
        global $locale;
        if(!isset($locale))
        $full_name = $this->first_name.' '.$this->last_name;
        else
        $full_name = $locale->getLocaleFormattedName($this->first_name, $this->last_name);
        $this->name = $full_name;
        $this->full_name = $full_name;
	}

	function get_list_view_data() {
		global $image_path;
		$this->_create_proper_name_field();
		$user_fields = $this->get_list_view_array();
		if ($this->is_admin)
			$user_fields['IS_ADMIN_IMAGE'] = get_image($image_path.'check_inline', '');
		elseif (!$this->is_admin) $user_fields['IS_ADMIN'] = '';
		if ($this->is_group)
			$user_fields['IS_GROUP_IMAGE'] = get_image($image_path.'check_inline', '');
		else
			$user_fields['IS_GROUP_IMAGE'] = '';

		$user_fields['NAME'] = empty ($this->name) ? '' : $this->name;
		$user_fields['REPORTS_TO_NAME'] = $this->reports_to_name;

		return $user_fields;
	}

	function list_view_parse_additional_sections(& $list_form, $xTemplateSection) {
		return $list_form;
	}

	function save_relationship_changes($is_update) {

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

		if ($where != "")
			$query .= " WHERE $where AND ".$where_auto;
		else
			$query .= " WHERE ".$where_auto;

		if ($order_by != "")
			$query .= " ORDER BY $order_by";
		else
			$query .= " ORDER BY users.user_name";

		return $query;
	}

	/** Returns a list of the associated users
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	*/
	function get_meetings() {
		// First, get the list of IDs.
		$query = "SELECT meeting_id as id from meetings_users where user_id='$this->id' AND deleted=0";
		return $this->build_related_list($query, new Meeting());
	}
	function get_calls() {
		// First, get the list of IDs.
		$query = "SELECT call_id as id from calls_users where user_id='$this->id' AND deleted=0";
		return $this->build_related_list($query, new Call());
	}

	/**
	 * generates Javascript to display I-E mail counts, both personal and group
	 */
	function displayEmailCounts() {
		global $theme;
		$new = translate('LBL_NEW', 'Emails');
		$default = 'index.php?module=Emails&action=ListView&assigned_user_id='.$this->id;
		$count = '';
		$verts = array('Love', 'Links', 'Pipeline', 'RipCurl', 'SugarLite');

		if($this->hasPersonalEmail()) {
			$r = $this->db->query('SELECT count(*) AS c FROM emails WHERE deleted=0 AND assigned_user_id = \''.$this->id.'\' AND type = \'inbound\' AND status = \'unread\'');
			$a = $this->db->fetchByAssoc($r);
			if(in_array($theme, $verts)) {
				$count .= '<br />';
			} else {
				$count .= '&nbsp;&nbsp;&nbsp;&nbsp;';
			}
			$count .= '<a href='.$default.'&type=inbound>'.translate('LBL_LIST_TITLE_MY_INBOX', 'Emails').': ('.$a['c'].' '.$new.')</a>';

			if(!in_array($theme, $verts)) {
				$count .= ' - ';
			}
		}

		$r = $this->db->query('SELECT id FROM users WHERE users.is_group = 1 AND deleted = 0');
		$groupIds = '';
		$groupNew = '';
		while($a = $this->db->fetchByAssoc($r)) {
			if($groupIds != '') {$groupIds .= ', ';}
			$groupIds .= "'".$a['id']."'";
		}

		$total = 0;
		if(strlen($groupIds) > 0) {
			$groupQuery = 'SELECT count(*) AS c FROM emails ';

			$groupQuery .= ' WHERE emails.deleted=0 AND emails.assigned_user_id IN ('.$groupIds.') AND emails.type = \'inbound\' AND emails.status = \'unread\'';
			$r = $this->db->query($groupQuery);
			if(is_resource($r)) {
				$a = $this->db->fetchByAssoc($r);
				if($a['c'] > 0) {
					$total = $a['c'];
				}
			}
		}
		if(in_array($theme, $verts)) $count .= '<br />';
		if(empty($count)) $count .= '&nbsp;&nbsp;&nbsp;&nbsp;';
		$count .= '<a href=index.php?module=Emails&action=ListViewGroup>'.translate('LBL_LIST_TITLE_GROUP_INBOX', 'Emails').': ('.$total.' '.$new.')</a>';

		$out  = '<script type="text/javascript" language="Javascript">';
		$out .= 'var welcome = document.getElementById("welcome");';
		$out .= 'var welcomeContent = welcome.innerHTML;';
		$out .= 'welcome.innerHTML = welcomeContent + "'.$count.'";';
		$out .= '</script>';

		echo $out;
	}

	function getPreferredEmail() {
		$ret = array ();
		$prefName = $this->getPreference('mail_fromname');
		$prefAddr = $this->getPreference('mail_fromaddress');

		if (isset ($prefAddr) && !empty ($prefAddr)) {
			$ret['name'] = $prefName;
			$ret['email'] = $prefAddr;
		}
		elseif (isset ($this->email1) && !empty ($this->email1)) {
			$ret['name'] = trim($this->first_name.' '.$this->last_name);
			$ret['email'] = $this->email1;
		}
		elseif (isset ($this->email2) && !empty ($this->email2)) {
			$ret['name'] = trim($this->first_name.' '.$this->last_name);
			$ret['email'] = $this->email2;
		} else {
			require_once ('modules/Emails/Email.php');
			$email = new Email();
			$ret = $email->getSystemDefaultEmail();
		}

		return $ret;
	}

	/**
	 * sets User email default in config.php if not already set by install - i.
	 * e., upgrades
	 */
	function setDefaultsInConfig() {
		global $sugar_config;
		$sugar_config['email_default_client'] = 'sugar';
		$sugar_config['email_default_editor'] = 'html';
		write_array_to_file('sugar_config', $sugar_config, 'config.php');
		return $sugar_config;
	}

    /**
     * returns User's email address based on descending order of preferences
     *
     * @param string id GUID of target user if needed
     * @return array Assoc array for an email and name
     */
    function getEmailInfo($id='') {
        $user = $this;
        if(!empty($id)) {
            $user = new User();
            $user->retrieve($id);
        }

        // from name
        $fromName = $user->getPreference('mail_fromname');
        if(empty($fromName)) {
            $fromName = trim($user->first_name.' '.$user->last_name);
        }

        // from address
        $fromAddr = $user->getPreference('mail_fromaddress');
        if(empty($fromAddr)) {
            if(!empty($user->email1) && isset($user->email1)) {
                $fromAddr = $user->email1;
            } elseif(!empty($user->email2) && isset($user->email2)) {
                $fromAddr = $user->email2;
            } else {
                $r = $user->db->query("SELECT value FROM config WHERE name = 'fromaddress'");
                $a = $user->db->fetchByAssoc($r);
                $fromAddr = $a['value'];
            }
        }

        $ret['name'] = $fromName;
        $ret['email'] = $fromAddr;

        return $ret;
    }

	/**
	 * returns opening <a href=xxxx for a contact, account, etc
	 * cascades from User set preference to System-wide default
	 * @return string	link
	 * @param attribute the email addy
	 * @param focus the parent bean
	 * @param contact_id
	 * @param return_module
	 * @param return_action
	 * @param return_id
	 * @param class
	 */
	function getEmailLink($attribute, &$focus, $contact_id='', $ret_module='', $ret_action='DetailView', $ret_id='', $class='tabDetailViewDFLink') {
		$emailLink = '';
		global $sugar_config;

		if(!isset($sugar_config['email_default_client'])) {
			$this->setDefaultsInConfig();
		}

		$userPref = $this->getPreference('email_link_type');
		$defaultPref = $sugar_config['email_default_client'];
		if($userPref != '') {
			$client = $userPref;
		} else {
			$client = $defaultPref;
		}

		if($client == 'sugar') {
			$salutation = '';
			$fullName = '';
			$email = '';
			$to_addrs_ids = '';
			$to_addrs_names = '';
			$to_addrs_emails = '';

			if(!empty($focus->salutation)) $salutation = $focus->salutation;

			if(!empty($focus->first_name)) {
				$fullName = trim($salutation.' '.$focus->first_name.' '.$focus->last_name);
			} elseif(!empty($focus->name)) {
				$fullName = $focus->name;
			}
			if(!empty($focus->$attribute)) {
				$email = $focus->$attribute;
			}

			if(empty($ret_module)) $ret_module = $focus->module_dir;
			if(empty($ret_id)) $ret_id = $focus->id;
			if($focus->object_name == 'Contact') {
				$contact_id = $focus->id;
				$to_addrs_ids = $focus->id;
				$to_addrs_names = $fullName;
				$to_addrs_emails = $focus->email1;
			}

			$emailLink = '<a href="index.php?module=Emails&action=EditView&type=out'.
				'&contact_id='.$contact_id.
				'&parent_type='.$focus->module_dir.
				'&parent_id='.$focus->id.
				'&parent_name='.urlencode($fullName).
				'&to_addrs_ids='.$to_addrs_ids.
				'&to_addrs_names='.urlencode($to_addrs_names).
				'&to_addrs_emails='.urlencode($to_addrs_emails).
				'&to_email_addrs='.urlencode($fullName . '&nbsp;&lt;' . $email . '&gt;').
				'&return_module='.$ret_module.
				'&return_action='.$ret_action.
				'&return_id='.$ret_id.'" '.
				'class="'.$class.'">';
		} else {
			// straight mailto:
			$emailLink = '<a href="mailto:'.$focus->$attribute.'" class="'.$class.'">';
		}

		return $emailLink;
	}


	/**
	 * gets a human-readable explanation of the format macro
	 * @return string Human readable name format
	 */
	function getLocaleFormatDesc() {
		global $locale;
		global $mod_strings;
		global $app_strings;

		$format['f'] = $mod_strings['LBL_LOCALE_DESC_FIRST'];
		$format['l'] = $mod_strings['LBL_LOCALE_DESC_LAST'];
		$format['s'] = $mod_strings['LBL_LOCALE_DESC_SALUTATION'];

		$name['f'] = $app_strings['LBL_LOCALE_NAME_EXAMPLE_FIRST'];
		$name['l'] = $app_strings['LBL_LOCALE_NAME_EXAMPLE_LAST'];
		$name['s'] = $app_strings['LBL_LOCALE_NAME_EXAMPLE_SALUTATION'];

		$macro = $locale->getLocaleFormatMacro();

		$ret1 = '';
		$ret2 = '';

		for($i=0; $i<strlen($macro); $i++) {
			if(array_key_exists($macro{$i}, $format)) {
				$ret1 .= "<i>".$format[$macro{$i}]."</i>";
				$ret2 .= "<i>".$name[$macro{$i}]."</i>";
			} else {
				$ret1 .= $macro{$i};
				$ret2 .= $macro{$i};
			}
		}
		return $ret1."<br />".$ret2;
	}

	function create_list_query($order_by, $where, $show_deleted = 0)
	{
		global $current_user;

		//$GLOBALS['log']->debug("In create_list_query for user $current_user->id ".is_admin($current_user));
		if(is_admin($current_user))
		{
			return parent::create_list_query($order_by,$where,$show_deleted);
		}

		$custom_join = false;

		if(isset($this->custom_fields))
			$custom_join = $this->custom_fields->getJOIN();
			$query = "SELECT DISTINCT ";

		if($custom_join){
			$query .= " $this->table_name.*". $custom_join['select']. " FROM $this->table_name " . $custom_join['join'];
		}else{
			$query .= " $this->table_name.* FROM $this->table_name ";
		}

//		$query .=" INNER JOIN users as user_self ON (users.id = user_self.id AND users.id='$current_user->id')";

//		if(!is_admin($current_user))//not required actually as its never going to come here incase of Admin user
//		$query .= " LEFT JOIN users a ON (a.reports_to_id = users.id AND users.id='$current_user->id') ";

//		$query .= " right JOIN users c ON users.id = c.reports_to_id ";
		$query .= " INNER JOIN user_reports ON (users.id = user_reports.child_id AND ";
		$query .= "(user_reports.parent_id='$current_user->id' OR user_reports.child_id='$current_user->id') AND user_reports.deleted=0) ";

		 $where_auto = '1=1';
				if($show_deleted == 0){
                	$where_auto = "$this->table_name.deleted=0";
				}else if($show_deleted == 1){
                	$where_auto = "$this->table_name.deleted=1";
				}

		if($where != "")
		$query .= "where ($where) AND $where_auto";
		else
		$query .= "where $where_auto";

//		$query .= " OR users.reports_to_id='$current_user->id'";
//		if(!empty($order_by))
//		$query .= " ORDER BY $order_by";

//		$query .= ")";
		$query .= " UNION ALL ";
		$query .= "SELECT DISTINCT ";
		if($custom_join){
			$query .= " $this->table_name.*". $custom_join['select']. " FROM $this->table_name " . $custom_join['join'];
		}else{
			$query .= " $this->table_name.* FROM $this->table_name ";
		}
		$query .= " where $this->table_name.reports_to_id = '$current_user->id' OR $this->table_name.id = '$current_user->id' AND deleted=0 ";

//		if(!empty($order_by))
//		$query .= " ORDER BY $order_by";
//		$query .= ")";
		//$GLOBALS['log']->debug("Query for user list :".$query);

		return $query;
	}

	function get_all_list($order_by = "", $where = "", $row_offset = 0, $limit=-1, $max=-1, $show_deleted = 0) {
		$GLOBALS['log']->debug("get_all_list:  order_by = '$order_by' and where = '$where' and limit = '$limit'");
		if(isset($_SESSION['show_deleted'])){
			$show_deleted = 1;
		}
		$order_by=$this->process_order_by($order_by, null);

		$query = parent::create_list_query($order_by, $where, $show_deleted);

		return $this->process_list_query($query, $row_offset, $limit, $max, $where);
	}
        
        function getUserDefaultRole(){
            $sql="select id from acl_roles where lower(name)='general' and deleted=0";
            $r=$this->db->query($sql);
            $row = $this->db->fetchByAssoc($r);
            if($row['id']){
                return $row['id'];
            }else{
                return false;
            }
            
        }

} // end class definition
?>
