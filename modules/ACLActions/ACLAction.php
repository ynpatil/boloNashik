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
require_once('modules/ACLActions/actiondefs.php');
class ACLAction  extends SugarBean{
	var $module_dir = 'ACLActions';
	var $object_name = 'ACLAction';
	var $table_name = 'acl_actions';
	var $new_schema = true;

	function ACLAction(){
		parent::SugarBean();
	}

	/**
	 * static addActions($category, $type='module')
	 * Adds all default actions for a category/type
	 *
	 * @param STRING $category - the category (e.g module name - Accounts, Contacts)
	 * @param STRING $type - the type (e.g. 'module', 'field')
	 */
	function addActions($category, $type='module'){
		global $ACLActions;
		$db =& PearDatabase::getInstance();
		if(isset($ACLActions[$type])){
			foreach($ACLActions[$type]['actions'] as $action_name =>$action_def){

				$action = new ACLAction();
				$query = "SELECT * FROM " . $action->table_name . " WHERE name='$action_name' AND category = '$category' AND acltype='$type'";
				$result = $db->query($query);
				//only add if an action with that name and category don't exist
				$row=$db->fetchByAssoc($result);
				if ($row == null) {
					$action->name = $action_name;
					$action->category = $category;
					$action->aclaccess = $action_def['default'];
					$action->acltype = $type;
					$action->modified_user_id = 1;
					$action->created_by = 1;
					$action->save();

				}
			}

		}else{
			sugar_die("FAILED TO ADD: $category : $name - TYPE $type NOT DEFINED IN modules/ACLActions/actiondefs.php");
		}

	}

	/**
	 * static AccessColor($access)
	 *
	 * returns the color associated with an access level
	 * these colors exist in the definitions in modules/ACLActions/actiondefs.php
	 * @param INT $access - the access level you want the color for
	 * @return the color either name or hex representation or false if the level does not exist
	 */
	function AccessColor($access){
		global $ACLActionAccessLevels;
		if(isset($ACLActionAccessLevels[$access])){

			return $ACLActionAccessLevels[$access]['color'];
		}
		return false;

	}

	/**
	 * static AccessName($access)
	 *
	 * returns the translated name  associated with an access level
	 * these label definitions  exist in the definitions in modules/ACLActions/actiondefs.php
	 * @param INT $access - the access level you want the color for
	 * @return the translated access level name or false if the level does not exist
	 */
	function AccessName($access){
		global $ACLActionAccessLevels;
		if(isset($ACLActionAccessLevels[$access])){
			return translate($ACLActionAccessLevels[$access]['label'], 'ACLActions');
		}
		return false;

	}

	/**
	 * static getAccessOptions()
	 * this is used for building select boxes
	 * @return array containg access levels (ints) as keys and access names as values
	 */
	function getAccessOptions( $action, $type='module'){
		global $ACLActions;
		$options = array();

		if(empty($ACLActions[$type]['actions'][$action]['aclaccess']))return $options;
		foreach($ACLActions[$type]['actions'][$action]['aclaccess'] as $action){
			$options[$action] = ACLAction::AccessName($action);
		}
		return $options;

	}

	/**
	 * function static getDefaultActions()
	 * This function will return a list of acl actions with their default access levels
	 *
	 *
	 */
	function getDefaultActions($type='module', $action=''){
		$query = "SELECT * FROM acl_actions WHERE deleted=0 ";
		if(!empty($type)){
			$query .= " AND type='$type'";
		}
		if(!empty($action)){
			$query .= "AND name='$action'";
		}
		$query .= " ORDER BY category";

		$db =& PearDatabase::getInstance();
		$result = $db->query($query);
		$default_actions = array();
		while($row = $db->fetchByAssoc($result) ){
			$acl = new ACLAction();
			$acl->populateFromRow($row);
			$default_actions[] = $acl;
		}
		return $default_actions;
	}


	/**
	 * static getUserActions($user_id,$refresh=false, $category='', $action='')
	 * returns a list of user actions
	 * @param GUID $user_id
	 * @param BOOLEAN $refresh
	 * @param STRING $category
	 * @param STRING $action
	 * @return ARRAY of ACLActionsArray
	 */

	function getUserActions($user_id,$refresh=false, $category='',$type='', $action=''){
		//check in the session if we already have it loaded
		if(!$refresh && !empty($_SESSION['ACL'][$user_id])){
			if(empty($category) && empty($action)){
				return $_SESSION['ACL'][$user_id];
			}else{
				if(!empty($category) && isset($_SESSION['ACL'][$user_id][$category])){
					if(empty($action)){
						if(empty($type)){
							return $_SESSION['ACL'][$user_id][$category];
						}
						return $_SESSION['ACL'][$user_id][$category][$type];
					}else if(!empty($type) && isset($_SESSION['ACL'][$user_id][$category][$type][$action])){
						return $_SESSION['ACL'][$user_id][$category][$type][$action];
					}
				}
			}
		}
		//if we don't have it loaded then lets check against the db
		$additional_where = '';
		$db =& PearDatabase::getInstance();
		if(!empty($category)){
			$additional_where .= " AND $this->table_name.category = '$category' ";
		}
		if(!empty($action)){
			$additional_where .= " AND $this->table_name.name = '$action' ";
		}
		if(!empty($type)){
			$additional_where .= " AND $this->table_name.acltype = '$type' ";
		}
		$query = "SELECT acl_actions .*, acl_roles_actions.access_override
					FROM acl_actions
					LEFT JOIN acl_roles_users ON acl_roles_users.user_id = '$user_id' AND  acl_roles_users.deleted = 0
					LEFT JOIN acl_roles_actions ON acl_roles_actions.role_id = acl_roles_users.role_id AND acl_roles_actions.action_id = acl_actions.id AND acl_roles_actions.deleted=0
					WHERE acl_actions.deleted=0 $additional_where ORDER BY category,name";

		$result = $db->query($query);
		$selected_actions = array();
		while($row = $db->fetchByAssoc($result) ){
			$acl = new ACLAction();
			$isOverride  = false;
			$acl->populateFromRow($row);
			if(!empty($row['access_override'])){
				$acl->aclaccess = $row['access_override'];
				$isOverride = true;
			}
			if(!isset($selected_actions[$acl->category])){
				$selected_actions[$acl->category] = array();

			}

			if(!isset($selected_actions[$acl->category][$acl->acltype][$acl->name])
				|| ($selected_actions[$acl->category][$acl->acltype][$acl->name]['aclaccess'] > $acl->aclaccess
					 && $isOverride
					)

				)
			{


				$selected_actions[$acl->category][$acl->acltype][$acl->name] = $acl->toArray();
				$selected_actions[$acl->category][$acl->acltype][$acl->name]['isDefault'] = !$isOverride;
			}

		}

		//only set the session variable if it was a full list;
		if(empty($category) && empty($action)){
			if(!isset($_SESSION['ACL'])){
				$_SESSION['ACL'] = array();
			}
			$_SESSION['ACL'][$user_id] = $selected_actions;
		}else{
			if(empty($action) && !empty($category)){
				if(!empty($type)){
					$_SESSION['ACL'][$user_id][$category][$type] = $selected_actions[$category][$type];}
				$_SESSION['ACL'][$user_id][$category] = $selected_actions[$category];
			}else{
				if(!empty($action) && !empty($category) && !empty($type)){
				$_SESSION['ACL'][$user_id][$category][$type][$action] = $selected_actions[$category][$action];

			}
			}
		}
		return $selected_actions;
	}
	/**
	 * (static/ non-static)function hasAccess($is_owner= false , $access = 0)
	 * checks if a user has access to this acl if the user is an owner it will check if owners have access
	 *
	 * This function may either be used statically or not. If used staticlly a user must pass in an access level not equal to zero
	 * @param boolean $is_owner
	 * @param int $access
	 * @return true or false
	 */
	function hasAccess($is_owner=false, $access = 0){

	//	echo "In hasAccess with is_owner:".$is_owner." Access ".$access."<br/>";
               // echo "<br>".$this->aclaccess."===";
		if(($access != 0 && $access == ACL_ALLOW_ALL) || ($is_owner && $access == ACL_ALLOW_OWNER)
		|| ($access == ACL_ALLOW_MYLEADTEAM) || ($access == ACL_ALLOW_MYTEAM) || ($access == ACL_ALLOW_OWNER_OR_CREATOR)
                        || ($access == ACL_ALLOW_MYVENDORTEAM))return true;

               
		if(isset($this) && isset($this->aclaccess)){
			if(($this->aclaccess == ACL_ALLOW_ALL) || ($is_owner && $this->aclaccess == ACL_ALLOW_OWNER)
			|| ($this->aclaccess == ACL_ALLOW_MYLEADTEAM) || ($this->aclaccess == ACL_ALLOW_MYTEAM) || ($this->aclaccess == ACL_ALLOW_OWNER_OR_CREATOR)|| ($this->aclaccess == ACL_ALLOW_MYVENDORTEAM))return true;
		}
		return false;
	}

	/**
	 * static function userHasAccess($user_id, $category, $action, $is_owner = false)
	 *
	 * @param GUID $user_id the user id who you want to check access for
	 * @param STRING $category the category you would like to check access for
	 * @param STRING $action the action of that category you would like to check access for
	 * @param BOOLEAN OPTIONAL $is_owner if the object is owned by the user you are checking access for
	 */
	function userHasAccess($user_id, $category, $action,$type='module', $is_owner = false){
		//check if we don't have it set in the cache if not lets reload the cache

		if(ACLAction::getUserAccessLevel($user_id, $category, 'access') < ACL_ALLOW_ENABLED){
//			echo "Returning false from user accesss level ";
			return false;
		}

//		else
//		echo "Not Returning false from user accesss level ";
		if(empty($_SESSION['ACL'][$user_id][$category][$type][$action])){
//			echo "In getUserActions ".$user_id;
			ACLAction::getUserActions($user_id, false);
		}

		if(!empty($_SESSION['ACL'][$user_id][$category][$type][$action])){
			$result = ACLAction::hasAccess($is_owner, $_SESSION['ACL'][$user_id][$category][$type][$action]['aclaccess']);
//			echo "In hasAccess Category ".$category." type ".$type." action :".$action." with result :".$result."Access level ".$_SESSION['ACL'][$user_id][$category][$type][$action]['aclaccess']."<br/>";
			return $result;
		}
		return false;

	}
	/**
	 * function getUserAccessLevel($user_id, $category, $action,$type='module')
	 * returns the access level for a given category and action
	 *
	 * @param GUID  $user_id
	 * @param STRING $category
	 * @param STRING $action
	 * @param STRING $type
	 * @return INT (ACCESS LEVEL)
	 */
	function getUserAccessLevel($user_id, $category, $action,$type='module'){
		if(empty($_SESSION['ACL'][$user_id][$category][$type][$action])){
			ACLAction::getUserActions($user_id, false);

		}
		if(!empty($_SESSION['ACL'][$user_id][$category][$type][$action])){
			return  $_SESSION['ACL'][$user_id][$category][$type][$action]['aclaccess'];
		}
	}

	/**
	 * STATIC function userNeedsMyTeam($user_id, $category, $action,$type='module')
	 * checks if a user should have ownership to do an action
	 *
	 * @param GUID $user_id
	 * @param STRING $category
	 * @param STRING $action
	 * @param STRING $type
	 * @return boolean
	 */
	function userNeedsMyTeam($user_id, $category, $action,$type='module'){
		//check if we don't have it set in the cache if not lets reload the cache

		if(empty($_SESSION['ACL'][$user_id][$category][$type][$action])){
			ACLAction::getUserActions($user_id, false);
		}

		if(!empty($_SESSION['ACL'][$user_id][$category][$type][$action])){
			return $_SESSION['ACL'][$user_id][$category][$type][$action]['aclaccess'] == ACL_ALLOW_MYTEAM;
		}
		return false;
	}

/**
	 * STATIC function userNeedsMyDealTeam($user_id, $category, $action,$type='module')
	 * checks if a user should have ownership to do an action
	 *
	 * @param GUID $user_id
	 * @param STRING $category
	 * @param STRING $action
	 * @param STRING $type
	 * @return boolean
	 */
	function userNeedsMyLeadTeam($user_id, $category, $action,$type='module'){
		//check if we don't have it set in the cache if not lets reload the cache
                $GLOBALS['log']->debug("ACL ACTION : ".print_r($_SESSION['ACL'][$user_id][$category][$type][$action],true));
		if(empty($_SESSION['ACL'][$user_id][$category][$type][$action])){
			ACLAction::getUserActions($user_id, false);
		}

		if(!empty($_SESSION['ACL'][$user_id][$category][$type][$action])){
			return $_SESSION['ACL'][$user_id][$category][$type][$action]['aclaccess'] == ACL_ALLOW_MYLEADTEAM;
		}
		return false;
	}
        
        /**
	 * STATIC function userNeedsMyDealTeam($user_id, $category, $action,$type='module')
	 * checks if a user should have ownership to do an action
	 *
	 * @param GUID $user_id
	 * @param STRING $category
	 * @param STRING $action
	 * @param STRING $type
	 * @return boolean
	 */
	function userNeedsMyVendorTeam($user_id, $category, $action,$type='module'){
		//check if we don't have it set in the cache if not lets reload the cache
                $GLOBALS['log']->debug("ACL ACTION : ".print_r($_SESSION['ACL'][$user_id][$category][$type][$action],true));
		if(empty($_SESSION['ACL'][$user_id][$category][$type][$action])){
			ACLAction::getUserActions($user_id, false);
		}

		if(!empty($_SESSION['ACL'][$user_id][$category][$type][$action])){
			return $_SESSION['ACL'][$user_id][$category][$type][$action]['aclaccess'] == ACL_ALLOW_MYVENDORTEAM;
		}
		return false;
	}

	/**
	 * STATIC function userNeedsOwnerOrCreatorship($user_id, $category, $action,$type='module')
	 * checks if a user should have ownership to do an action
	 *
	 * @param GUID $user_id
	 * @param STRING $category
	 * @param STRING $action
	 * @param STRING $type
	 * @return boolean
	 */
	function userNeedsOwnerOrCreatorship($user_id, $category, $action,$type='module'){
		//check if we don't have it set in the cache if not lets reload the cache

		if(empty($_SESSION['ACL'][$user_id][$category][$type][$action])){
			$GLOBALS['log']->debug("In userNeedsOwnerOrCreatorship :".$user_id);
			ACLAction::getUserActions($user_id, false);
		}

		$GLOBALS['log']->debug("In userNeedsOwnerOrCreatorship permission :".$_SESSION['ACL'][$user_id][$category][$type][$action]['aclaccess']);

		if(!empty($_SESSION['ACL'][$user_id][$category][$type][$action])){
			return $_SESSION['ACL'][$user_id][$category][$type][$action]['aclaccess'] == ACL_ALLOW_OWNER_OR_CREATOR;
		}
		return false;
	}
	/**
	 * STATIC function userNeedsOwnership($user_id, $category, $action,$type='module')
	 * checks if a user should have ownership to do an action
	 *
	 * @param GUID $user_id
	 * @param STRING $category
	 * @param STRING $action
	 * @param STRING $type
	 * @return boolean
	 */
	function userNeedsOwnership($user_id, $category, $action,$type='module'){
		//check if we don't have it set in the cache if not lets reload the cache

		if(empty($_SESSION['ACL'][$user_id][$category][$type][$action])){
			ACLAction::getUserActions($user_id, false);

		}

		if(!empty($_SESSION['ACL'][$user_id][$category][$type][$action])){
			return $_SESSION['ACL'][$user_id][$category][$type][$action]['aclaccess'] == ACL_ALLOW_OWNER;
		}
		return false;

	}
	/**
	 *
	 * static pass by ref setupCategoriesMatrix(&$categories)
	 * takes in an array of categories and modifes them adding display information
	 *
	 * @param unknown_type $categories
	 */
	function setupCategoriesMatrix(&$categories){
		global $ACLActions, $current_user;
		$names = array();
		$disabled = array();
		foreach($categories as $cat_name=>$category){
	foreach($category as $type_name=>$type){
		$setup_names = false;
		if(empty($names)){
			$names = array();
			$setup_names = true;
			if(!empty($names))$tdwidth = 100 / sizeof($names);
		}
	foreach($type as $act_name=>$action){
		if($setup_names){

			$names[] = translate($ACLActions[$type_name]['actions'][$act_name]['label'], 'ACLActions');
		}

		$categories[$cat_name][$type_name][$act_name]['accessColor'] = ACLAction::AccessColor($action['aclaccess']);
		if($type_name== 'module'){

			if($act_name != 'aclaccess' && $categories[$cat_name]['module']['access']['aclaccess'] == ACL_ALLOW_DISABLED){
				$categories[$cat_name][$type_name][$act_name]['accessColor'] = 'darkgray';
				$disabled[] = $cat_name;
			}

		}
		$categories[$cat_name][$type_name][$act_name]['accessName'] = ACLAction::AccessName($action['aclaccess']);
		$categories[$cat_name][$type_name][$act_name]['accessOptions'] =  ACLAction::getAccessOptions($act_name, $type_name);

	}
	}



}
if(!is_admin($current_user)){
	foreach($disabled as $cat_name){
		unset($categories[$cat_name]);
	}
}
	return $names;

	}



	/**
	 * function toArray()
	 * returns this acl as an array
	 *
	 * @return array of fields with id, name, access and category
	 */
	function toArray(){
		$array_fields = array('id', 'aclaccess');
		$arr = array();
		foreach($array_fields as $field){
			$arr[$field] = $this->$field;
		}
		return $arr;
	}

	/**
	 * function fromArray($arr)
	 * converts an array into an acl mapping name value pairs into files
	 *
	 * @param Array $arr
	 */
	function fromArray($arr){
		foreach($arr as $name=>$value){
			$this->$name = $value;
		}
	}

	/**
	 * function clearSessionCache()
	 * clears the session variable storing the cache information for acls
	 *
	 */
	function clearSessionCache(){
		unset($_SESSION['ACL']);
	}













}


?>
