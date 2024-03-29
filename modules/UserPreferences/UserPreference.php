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
 * $Id: UserPreference.php,v 1.22 2006/09/01 22:50:09 jacob Exp $
 * Description: Handles the User Preferences and stores them in a seperate table. 
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
// cn: was breaking JSON calls
if(!class_exists('SugarBean')) {
	require_once('data/SugarBean.php');
}
// Do not actually declare, use the functions statically
class UserPreference extends SugarBean {
	var $db;
    var $field_name_map;

	// Stored fields
	var $id;
	var $date_entered;
	var $date_modified;
	var $assigned_user_id;
	var $assigned_user_name;
	var $name;
	var $category;
	var $contents;
	var $deleted;
	
	var $object_name = 'UserPreference';
	var $table_name = 'user_preferences';

    var $disable_row_level_security = true;
	var $module_dir = 'UserPreferences';
	var $field_defs = array();
	var $field_defs_map = array();
	var $new_schema = true;

	// Do not actually declare, use the functions statically
	function UserPreference() {
		parent::SugarBean();
	
	}

	/**
	 * Get preference by name and category. Lazy loads preferences from the database per category
	 * 
	 * @global user will use current_user if no user specificed in $user param
	 * @param string $name name of the preference to retreive
	 * @param string $category name of the category to retreive, defaults to global scope
	 * @param user $user User object to retrieve, otherwise user current_user
	 * @return mixed the value of the preference (string, array, int etc)
	 */
	function getPreference($name, $category = 'global', $user = null) {
        global $sugar_config;

		if(!isset($user)) $user = $GLOBALS['current_user'];

        // if the unique key in session doesn't match the app or prefereces are empty
		if(!isset($_SESSION[$user->user_name.'_PREFERENCES'][$category]) || (!empty($_SESSION['unique_key']) && $_SESSION['unique_key'] != $sugar_config['unique_key'])) {
			UserPreference::loadPreferences($category, $user);
		}
		
		if(isset($_SESSION[$user->user_name.'_PREFERENCES'][$category][$name])) {
			return $_SESSION[$user->user_name.'_PREFERENCES'][$category][$name];
		}
		return null;
	}

	/**
	 * Set preference by name and category. Saving will be done in utils.php -> sugar_cleanup
	 * 
	 * @global user will use current_user if no user specificed in $user param
	 * @param string $name name of the preference to retreive
	 * @param mixed $value value of the preference to set
	 * @param int @nosession no longer supported
	 * @param string $category name of the category to retreive, defaults to global scope
	 * @param user $user User object to retrieve, otherwise user current_user
	 * 
	 */
	function setPreference($name, $value, $nosession = 0, $category = 'global', $user = null) {  
		if(!isset($user)) $user = $GLOBALS['current_user'];
		
		if(!isset($_SESSION[$user->user_name.'_PREFERENCES'][$category])) {
			if(!$user->loadPreferences($category, $user)) 
                $_SESSION[$user->user_name.'_PREFERENCES'][$category] = array();			
		}
		
		// preferences changed or a new preference, save it to DB
		if(!isset($_SESSION[$user->user_name.'_PREFERENCES'][$category][$name]) 
			|| (isset($_SESSION[$user->user_name.'_PREFERENCES'][$category][$name]) && $_SESSION[$user->user_name.'_PREFERENCES'][$category][$name] != $value)) {
				$GLOBALS['savePreferencesToDB'] = true;
				if(!isset($GLOBALS['savePreferencesToDBCats'])) $GLOBALS['savePreferencesToDBCats'] = array();
				$GLOBALS['savePreferencesToDBCats'][$category] = true; 
		}
		
		$_SESSION[$user->user_name.'_PREFERENCES'][$category][$name] = $value;
	}
	
	/**
	 * Loads preference by category from database. Saving will be done in utils.php -> sugar_cleanup
	 * 
	 * @global user will use current_user if no user specificed in $user param
	 * @param string $category name of the category to retreive, defaults to global scope
	 * @param user $user User object to retrieve, otherwise user current_user
	 * @return bool successful?
	 *  
	 */
	function loadPreferences($category = 'global', $user = null) {
        global $sugar_config;
        
		if(!isset($user)) $user = $GLOBALS['current_user'];
		
		if(!empty($user->id) && (!isset($_SESSION[$user->user_name . '_PREFERENCES'][$category]) || (!empty($_SESSION['unique_key']) && $_SESSION['unique_key'] != $sugar_config['unique_key']))) {
			// cn: moving this to only log when valid - throwing errors on install
			$GLOBALS['log']->debug('Loading Preferences DB ' . $user->user_name);
			if(!isset($_SESSION[$user->user_name . '_PREFERENCES'])) $_SESSION[$user->user_name . '_PREFERENCES'] = array();
			if(!isset($user->user_preferences)) $user->user_preferences = array();
			$result = $GLOBALS['db']->query("SELECT contents FROM user_preferences WHERE assigned_user_id='$user->id' AND category = '" . $category . "' AND deleted = 0", false, 'Failed to load user preferences');
			$row = $GLOBALS['db']->fetchByAssoc($result);
			if ($row) {
				$_SESSION[$user->user_name . '_PREFERENCES'][$category] = unserialize(base64_decode($row['contents']));				
				$user->user_preferences[$category] = unserialize(base64_decode($row['contents']));
				return true;
			}
		}
		return false;
	}
	
	/**
	 * Loads users timedate preferences
	 * 
	 * @global user will use current_user if no user specificed in $user param
	 * @param string $category name of the category to retreive, defaults to global scope
	 * @param user $user User object to retrieve, otherwise user current_user
	 * @return array 'date' - date format for user ; 'time' - time format for user  
	 *  
	 */	
	function getUserDateTimePreferences($user = null) {
		global $sugar_config, $db, $timezones;
		
		if(!isset($user)) $user = $GLOBALS['current_user'];
		
		$prefDate = array();
		
		if(!empty($user) && UserPreference::loadPreferences('global', $user)) {
				// forced to set this to a variable to compare b/c empty() wasn't working
				$timeZone = $user->getPreference("timezone");
				$timeFormat = $user->getPreference("timef");
				$dateFormat = $user->getPreference("datef");
				
				// cn: bug xxxx cron.php fails because of missing preference when admin hasn't logged in yet
				$timeZone = empty($timeZone) ? 'America/Los_Angeles' : $timeZone;

				if(empty($timeZone)) $timeZone = '';
				if(empty($timeFormat)) $timeFormat = $sugar_config['default_time_format'];
				if(empty($dateFormat)) $dateFormat = $sugar_config['default_date_format'];

				$equinox = date('I');

				$serverHourGmt = date('Z') / 60 / 60;
				
				$userOffsetFromServerHour = $user->getPreference("timez");

				$userHourGmt = $serverHourGmt + $userOffsetFromServerHour;

				$prefDate['date'] = $dateFormat;
				$prefDate['time'] = $timeFormat;
				$prefDate['userGmt'] = "(GMT".($timezones[$timeZone]['gmtOffset'] / 60).")";
				$prefDate['userGmtOffset'] = $timezones[$timeZone]['gmtOffset'] / 60;

				return $prefDate;
		}
		else
		{	
			$prefDate['date'] = $sugar_config['default_date_format'];
			$prefDate['time'] = $sugar_config['default_time_format'];
            if(!empty($user)){
                $timeZone = $user->getPreference("timezone");
                $prefDate['userGmt'] = "(GMT".($timezones[$timeZone]['gmtOffset'] / 60).")";
                $prefDate['userGmtOffset'] = $timezones[$timeZone]['gmtOffset'] / 60;

            }
			
			return $prefDate;
		}
	}
	
	/**
	 * Saves all preferences into the database that are in the session. Expensive, this is called by default in 
	 * sugar_cleanup if a setPreference has been called during one round trip.
	 * 
	 * @global user will use current_user if no user specificed in $user param
	 * @param user $user User object to retrieve, otherwise user current_user
	 * @param bool $all save all of the preferences? (Dangerous)
	 * 
	 */
	function savePreferencesToDB($user = null, $all = false) {
        global $sugar_config;
		$GLOBALS['savePreferencesToDB'] = false;
		
		global $db;
        
		if(!isset($user)) { 
            if(!empty($GLOBALS['current_user'])) {
                $user = $GLOBALS['current_user'];
            }
            else {
                $GLOBALS['log']->fatal('No User Defined: UserPreferences::savePreferencesToDB');
                return; // no user defined, sad panda
            }
        }
		
        // these are not the preferences you are looking for [ hand waving ]
        if(!empty($_SESSION['unique_key']) && $_SESSION['unique_key'] != $sugar_config['unique_key']) return;
		
        $GLOBALS['log']->debug('Saving Preferences to DB ' . $user->user_name);
		
		if(isset($_SESSION[$user->user_name. '_PREFERENCES']) && is_array($_SESSION[$user->user_name. '_PREFERENCES'])) {
             $GLOBALS['log']->debug("Saving Preferences to DB: {$user->user_name}");
			// only save the categories that have been modified or all?
			if(!$all && isset($GLOBALS['savePreferencesToDBCats']) && is_array($GLOBALS['savePreferencesToDBCats'])) {
				$catsToSave = array();
				foreach($GLOBALS['savePreferencesToDBCats'] as $category => $value) {
					$catsToSave[$category] = $_SESSION[$user->user_name. '_PREFERENCES'][$category];
				}
			}
			else {
				$catsToSave = $_SESSION[$user->user_name. '_PREFERENCES'];
			}

            $focus = new UserPreference();
			foreach($catsToSave as $category => $contents) {
                unset($focus->id);
				$query = "SELECT id, contents FROM user_preferences WHERE assigned_user_id = '" . $user->id . "' AND category = '"
						. $category . "'";
				$result = $db->query($query);
				$row = $db->fetchByAssoc($result);                
                
				if(!empty($row['id'])) { // update
					$focus->retrieve($row['id']);
					$focus->deleted = 0;
					$focus->contents = base64_encode(serialize($contents));
				}
				else { // insert new
					$focus->assigned_user_id = $user->id;
					$focus->contents = base64_encode(serialize($contents));
					$focus->category = $category;              
				}
				$focus->save();
			}
		}
	}
	
	/**
	 * Resets preferences for a particular user. If $category is null all user preferences will be reset 
	 * 
	 * @global user will use current_user if no user specificed in $user param
	 * @param string $category category to reset
     * @param user $user User object to retrieve, otherwise user current_user
	 * 
	 */
	function resetPreferences($category = null, $user = null) {
		global $db;
		if(!isset($user)) $user = $GLOBALS['current_user'];
		
		$GLOBALS['log']->debug('Reseting Preferences for user ' . $user->user_name);
		
		$remove_tabs = $this->getPreference('remove_tabs');
		
		$query = "UPDATE user_preferences SET deleted = 1 WHERE assigned_user_id = '" . $user->id . "'";
        if($category) $query .= " AND category = '" . $category . "'"; 
		$db->query($query);
		
		if($user->id == $GLOBALS['current_user']->id) {
			if($category) {
	            unset($_SESSION[$this->user_name."_PREFERENCES"][$category]);
	        }
	        else {
	            unset($_SESSION[$this->user_name."_PREFERENCES"]);
	    		session_destroy();
	            $this->setPreference('remove_tabs', $remove_tabs, 1);
	            header('Location: index.php');
	        }
		}
	}
}

?>
