<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
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

 // $Id: AuthenticationController.php,v 1.6 2006/08/22 20:56:11 awu Exp $
class AuthenticationController {
	var $loggedIn = false; //if a user has attempted to login
	var $authenticated = false;
	var $loginSuccess = false;// if a user has successfully logged in

	/**
	 * Creates an instance of the authentication controller and loads it
	 *
	 * @param STRING $type - the authentication Controller - default to SugarAuthenticate
	 * @return AuthenticationController - 
	 */
	function AuthenticationController($type = 'SugarAuthenticate') {
		if(!file_exists('modules/Users/authentication/'.$type.'/' . $type . '.php'))$type = 'SugarAuthenticate';

		
		if($type == 'SugarAuthenticate' && !empty($GLOBALS['system_config']->settings['system_ldap_enabled']) && empty($_SESSION['sugar_user'])){
			$type = 'LDAPAuthenticate';
		}
		
	
		
		require_once ('modules/Users/authentication/'.$type.'/' . $type . '.php');
		$this->authController = new $type();
	}


	/**
	 * Returns an instance of the authentication controller
	 *
	 * @param STRING $type this is the type of authetnication you want to use default is SugarAuthenticate
	 * @return an instance of the authetnciation controller
	 */
	function &getInstance($type='SugarAuthenticate'){
		static $authcontroller;
		if(empty($authcontroller)){
			$authcontroller = new AuthenticationController($type);
		}
		return $authcontroller;
	}

	/**
	 * This function is called when a user initially tries to login. 
	 * It will return true if the user successfully logs in or false otherwise.
	 *
	 * @param STRING $username
	 * @param STRING $password
	 * @param ARRAY $PARAMS
	 * @return boolean
	 */
	function login($username, $password, $PARAMS = array ()) {
		$SESSION['loginAttempts'] = (isset($SESSION['loginAttempts']))? $SESSION['loginAttempts'] + 1: 1;
		unset($GLOBALS['login_error']);
		
		if($this->loggedIn)return $this->loginSuccess;
		
		$this->loginSuccess = $this->authController->loginAuthenticate($username, $password, $PARAMS);
		$this->loggedIn = true;
		
		if($this->loginSuccess){
			//Ensure the user is authorized
			checkAuthUserStatus();
			
			loginLicense();
			if(!empty($GLOBALS['login_error'])){
				session_unregister('authenticated_user_id');
				$GLOBALS['log']->fatal('FAILED LOGIN: potential hack attempt');
				$this->loginSuccess = false;
				return false;
			}
			$ut = $GLOBALS['current_user']->getPreference('ut');
			if(empty($ut) && $_REQUEST['action'] != 'SaveTimezone') {
				$GLOBALS['module'] = 'Users';
				$GLOBALS['action'] = 'SetTimezone';
				ob_clean();
				header("Location: index.php?module=Users&action=SetTimezone");
				sugar_cleanup(true);
			}
			
			
		}else{
			$GLOBALS['log']->fatal('FAILED LOGIN:attempts[' .$SESSION['loginAttempts'] .'] - '. $username);
		}
		return $this->loginSuccess;
	}

	/**
	 * This is called on every page hit. 
	 * It returns true if the current session is authenticated or false otherwise
	 * @return booelan
	 */
	function sessionAuthenticate() {
	   
		if(!$this->authenticated){
			$this->authenticated = $this->authController->sessionAuthenticate();
		}
		if($this->authenticated){
			if(!isset($_SESSION['userStats']['pages'])){
			    $_SESSION['userStats']['loginTime'] = time();
			    $_SESSION['userStats']['pages'] = 0;
			}
			$_SESSION['userStats']['lastTime'] = time();
			$_SESSION['userStats']['pages']++;
			
		}
		return $this->authenticated;
	}

	/**
	 * Called when a user requests to logout. Should invalidate the session and redirect
	 * to the login page.
	 *
	 */
	function logout(){
		$this->authController->logout();
	}


}
?>
