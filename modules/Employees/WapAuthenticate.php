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
 * $Id: WapAuthenticate.php,v 1.8 2006/06/06 17:58:20 majed Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
require_once('modules/Users/User.php');


global $mod_strings;



$focus = new User();

// Add in defensive code here.
$focus->user_name = $_REQUEST['user_name'];
$user_password = $_REQUEST['user_password'];

$focus->load_user($user_password);

if($focus->is_authenticated())
{
	// save the user information into the session
	// go to the home screen
	if (!empty($_POST['login_record'])) {
		$login_direction = "module={$_POST['login_module']}&action={$_POST['login_action']}&record={$_POST['login_record']}";
	}
	else {
		$login_direction = "action=index&module=Home";
	}

	header("Location: index.php?{$login_direction}");
	session_unregister('login_password');
	session_unregister('login_error');
	session_unregister('login_user_name');

	$_SESSION['authenticated_user_id'] = $focus->id;

	// store the user's theme in the session
	if (isset($_REQUEST['login_theme'])) {
		$authenticated_user_theme = $_REQUEST['login_theme'];
	}
	elseif (isset($_REQUEST['ck_login_theme_20']))  {
		$authenticated_user_theme = $_REQUEST['ck_login_theme_20'];
	}
	else {
		$authenticated_user_theme = $sugar_config['default_theme'];
	}

	// store the user's language in the session
	if (isset($_REQUEST['login_language'])) {
		$authenticated_user_language = $_REQUEST['login_language'];
	}
	elseif (isset($_REQUEST['ck_login_language_20']))  {
		$authenticated_user_language = $_REQUEST['ck_login_language_20'];
	}
	else {
		$authenticated_user_language = $sugar_config['default_language'];
	}

	// If this is the default user and the default user theme is set to reset, reset it to the default theme value on each login
	if($reset_theme_on_default_user && $focus->user_name == $sugar_config['default_user_name'])
	{
		$authenticated_user_theme = $sugar_config['default_theme'];
	}
	if(isset($reset_language_on_default_user) && $reset_language_on_default_user && $focus->user_name == $sugar_config['default_user_name'])
	{
		$authenticated_user_language = $sugar_config['default_language'];
	}

	$_SESSION['authenticated_user_theme'] = $authenticated_user_theme;
	$_SESSION['authenticated_user_language'] = $authenticated_user_language;

	$GLOBALS['log']->debug("authenticated_user_theme is $authenticated_user_theme");
	$GLOBALS['log']->debug("authenticated_user_language is $authenticated_user_language");

// Clear all uploaded import files for this user if it exists

	$tmp_file_name = $sugar_config['import_dir']. "IMPORT_".$focus->id;

	if (file_exists($tmp_file_name))
	{
		unlink($tmp_file_name);
	}

}
else
{
	$_SESSION['login_user_name'] = $focus->user_name;
	$_SESSION['login_password'] = $user_password;
	$_SESSION['login_error'] = $mod_strings['ERR_INVALID_PASSWORD'];

	// go back to the login screen.
	// create an error message for the user.
	header("Location: index.php");
}

?>
