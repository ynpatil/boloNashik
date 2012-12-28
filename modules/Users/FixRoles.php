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

require_once('modules/Users/User.php');
require_once('modules/ACLRoles/ACLRole.php');
require_once('modules/MySettings/TabController.php');

global $app_list_strings;

$focus = new User();
$users = $focus->get_full_list();

echo "Users count ".count($users);

$role = new ACLRole();
$general_role = new ACLRole();
$general_role->retrieve('7c1d6949-ced3-1717-ef68-45279b793cda');

$rel_name = "aclroles";
$display_modules = $app_list_strings['moduleList'];
unset($display_modules['Masters']);

//echo implode('/',$display_modules);
$master = array('Masters'=>'Masters');

$tabs = new TabController();

foreach($users as $user){

$result = $GLOBALS['db']->query("select role_id from acl_roles_users where user_id='$user->id' and deleted=0");
$row = $GLOBALS['db']->fetchByAssoc($result);
if(empty($row['role_id'])){

	echo "Empty for user :".$user->user_name." Fixing it<br/>";
	$focus = new User();
	$focus->retrieve($user->id);
	$focus->load_relationship($rel_name);
	$focus->$rel_name->add($general_role->id);
	$focus->setPreference('display_tabs', $display_modules, 0, 'global', $focus);
	$focus->setPreference('hide_tabs', array(), 0, 'global', $focus);
	$focus->setPreference('remove_tabs', $master, 0, 'global', $focus);
	$focus->save();
}

}

?>