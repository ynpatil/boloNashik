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
require_once('modules/ACLRoles/ACLRole.php');
require_once('modules/ACLActions/ACLAction.php');
require_once('modules/ACL/ACLJSController.php');
class ACLController {
		
	function checkAccess($category, $action, $is_owner=false){
		
		global $current_user;
		if(is_admin($current_user))return true;
		//calendar is a special case since it has 3 modules in it (calls, meetings, tasks)
		
		if($category == 'Calendar'){
			return ACLAction::userHasAccess($current_user->id, 'Calls', $action,'module', $is_owner) || ACLAction::userHasAccess($current_user->id, 'Meetings', $action,'module', $is_owner) || ACLAction::userHasAccess($current_user->id, 'Tasks', $action,'module', $is_owner);
		}
		if($category == 'Activities'){
			return ACLAction::userHasAccess($current_user->id, 'Calls', $action,'module', $is_owner) || ACLAction::userHasAccess($current_user->id, 'Meetings', $action,'module', $is_owner) || ACLAction::userHasAccess($current_user->id, 'Tasks', $action,'module', $is_owner)|| ACLAction::userHasAccess($current_user->id, 'Emails', $action,'module', $is_owner)|| ACLAction::userHasAccess($current_user->id, 'Notes', $action,'module', $is_owner);
		}
		return ACLAction::userHasAccess($current_user->id, $category, $action,'module', $is_owner);
	}
	
	function requireOwner($category, $value){
			global $current_user;
			if(is_admin($current_user))return false;
			return ACLAction::userNeedsOwnership($current_user->id, $category, $value,'module');
	}

	function requireOwnerOrCreator($category, $value){
			global $current_user;
			if(is_admin($current_user))return false;
			$status = ACLAction::userNeedsOwnerOrCreatorship($current_user->id, $category, $value,'module');
			//$GLOBALS['log']->debug("In ACLController.requireOwnerOrCreator :".$status);			
			return $status;
	}

	function requireMyTeam($category, $value){
			global $current_user;
			if(is_admin($current_user))return false;
			return ACLAction::userNeedsMyTeam($current_user->id, $category, $value,'module');
	}
	
        function requireMyLeadTeam($category, $value){
			global $current_user;
			if(is_admin($current_user))return false;                                               
			return ACLAction::userNeedsMyLeadTeam($current_user->id, $category, $value,'module');
	}
        // Added By Yogesh
        function requireMyVendorTeam($category, $value){
			global $current_user;
			if(is_admin($current_user))return false;                                               
			return ACLAction::userNeedsMyVendorTeam($current_user->id, $category, $value,'module');
	}
        
	function filterModuleList(&$moduleList, $by_value=true){
		
		global $aclModuleList, $current_user;
		$actions = ACLAction::getUserActions($current_user->id, false);
		
		$compList = array();
		if($by_value){
			foreach($moduleList as $key=>$value){
				$compList[$value]= $key;
			}
		}else{
			$compList =& $moduleList;
		}
		foreach($actions as $action_name=>$action){
			
			if(!empty($action['module'])){
				$aclModuleList[$action_name] = $action_name;
				if(isset($compList[$action_name])){
					if($action['module']['access']['aclaccess'] < ACL_ALLOW_ENABLED){
						if($by_value){
							unset($moduleList[$compList[$action_name]]);
						}else{
							unset($moduleList[$action_name]);
						}
					}
				}
			}
		}
		if(isset($compList['Calendar']) && 
			!( ACLController::checkModuleAllowed('Calls', $actions) || ACLController::checkModuleAllowed('Meetings', $actions) || ACLController::checkModuleAllowed('Tasks', $actions)))
	    {
			if($by_value){
				unset($moduleList[$compList['Calendar']]);
			}else{
				unset($moduleList['Calendar']);
			}
			if(isset($compList['Activities']) && 
				!( ACLController::checkModuleAllowed('Notes', $actions) || ACLController::checkModuleAllowed('Notes', $actions))){
				if($by_value){
					unset($moduleList[$compList['Activities']]);
				}else{
					unset($moduleList['Activities']);
				}
			}
		}
		
	}
	
	/**
	 * Check to see if the module is available for this user.
	 *
	 * @param String $module_name
	 * @return true if they are allowed.  false otherwise.
	 */
	function checkModuleAllowed($module_name, $actions)
	{
		if(!empty($actions[$module_name]['module']['access']['aclaccess']) && 
			ACL_ALLOW_ENABLED == $actions[$module_name]['module']['access']['aclaccess'])
		{
			return true;
		}
		
		return false;
	}
	
	function disabledModuleList($moduleList, $by_value=true,$view='list'){
		global $aclModuleList, $current_user;
		$actions = ACLAction::getUserActions($current_user->id, false);
		$disabled = array();
		$compList = array();

		if($by_value){
			foreach($moduleList as $key=>$value){
				$compList[$value]= $key;
			}
		}else{
			$compList =& $moduleList;
		}
		if(isset($moduleList['ProductTemplates'])){
			$moduleList['Products'] ='Products';
		}
		
		foreach($actions as $action_name=>$action){
					
			if(!empty($action['module'])){
				$aclModuleList[$action_name] = $action_name;
				if(isset($compList[$action_name])){
					if($action['module']['access']['aclaccess'] < ACL_ALLOW_ENABLED || $action['module'][$view]['aclaccess'] < 0){
						if($by_value){
							$disabled[$compList[$action_name]] =$compList[$action_name] ;
						}else{
							$disabled[$action_name] = $action_name;
						}
					}
				}
			}
		}
		if(isset($compList['Calendar'])  && !( ACL_ALLOW_ENABLED == $actions['Calls']['module']['access']['aclaccess'] || ACL_ALLOW_ENABLED == $actions['Meetings']['module']['access']['aclaccess'] || ACL_ALLOW_ENABLED == $actions['Tasks']['module']['access']['aclaccess'])){
			if($by_value){
							$disabled[$compList['Calendar']]  = $compList['Calendar'];
			}else{
							$disabled['Calendar']  = 'Calendar';
			}
			if(isset($compList['Activities'])  &&!( ACL_ALLOW_ENABLED == $actions['Notes']['module']['access']['aclaccess'] || ACL_ALLOW_ENABLED == $actions['Notes']['module']['access']['aclaccess'] )){
				if($by_value){
							$disabled[$compList['Activities']]  = $compList['Activities'];
				}else{
							$disabled['Activities']  = 'Activities';
				}
			}
		}
		if(isset($disabled['Products'])){
			$disabled['ProductTemplates'] = 'ProductTemplates';
		}
		
		
		return $disabled;
		
	}
		
	function addJavascript($category,$form_name='', $is_owner=false){
		$jscontroller = new ACLJSController($category, $form_name, $is_owner);
		echo $jscontroller->getJavascript();
	}
	
	function moduleSupportsACL($module){
		static $checkModules = array();
		global $beanFiles, $beanList;
		if(isset($checkModules[$module])){
			return $checkModules[$module];
		}
		if(!isset($beanList[$module])){
			$checkModules[$module] = false;
			
		}else{
			$class = $beanList[$module];
			require_once($beanFiles[$class]);
			$mod = new $class();
			if(!is_subclass_of($mod, 'SugarBean')){
				$checkModules[$module] = false;
			}else{
				$checkModules[$module] = $mod->bean_implements('ACL');
			}
		}
		return $checkModules[$module] ;
	}
	
	function displayNoAccess($redirect_home = false){
		echo '<script>function set_focus(){}</script><p class="error">' . translate('LBL_NO_ACCESS', 'ACL') . '</p>';
		if($redirect_home)echo 'Redirect to Home in <span id="seconds_left">3</span> seconds<script> function redirect_countdown(left){document.getElementById("seconds_left").innerHTML = left; if(left == 0){document.location.href = "index.php";}else{left--; setTimeout("redirect_countdown("+ left+")", 1000)}};setTimeout("redirect_countdown(3)", 1000)</script>';
	}
	
}

?>
