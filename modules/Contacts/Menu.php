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
 * $Id: Menu.php,v 1.38 2006/06/09 12:36:16 wayne Exp $
 * Description:  TODO To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

global $mod_strings, $app_strings;
	if(ACLController::checkAccess('Contacts', 'edit', true))$module_menu[] = Array("index.php?module=Contacts&action=EditView&return_module=Contacts&return_action=DetailView", $mod_strings['LNK_NEW_CONTACT'],"CreateContacts", 'Contacts');
	if(ACLController::checkAccess('Contacts', 'edit', true))$module_menu[] =Array("index.php?module=Contacts&action=BusinessCard", $mod_strings['LBL_ADD_BUSINESSCARD'],"CreateContacts", 'Contacts');
	if(ACLController::checkAccess('Contacts', 'import', true))$module_menu[] =Array("index.php?module=Contacts&action=ImportVCard", $mod_strings['LNK_IMPORT_VCARD'],"CreateContacts", 'Contacts');
	if(ACLController::checkAccess('Contacts', 'list', true))$module_menu[] =Array("index.php?module=Contacts&action=index&return_module=Contacts&return_action=DetailView", $app_strings['LNK_CONTACT_LIST_MOBILE'],"Contacts", 'Contacts');
	//if(ACLController::checkAccess('Contacts', 'list', true))$module_menu[]=Array("index.php?module=Contacts&action=RequestsListView&return_module=Contacts&return_action=DetailView", $mod_strings['LNK_CONTACT_REQUESTS'],"Contacts", 'Contacts');



	if(ACLController::checkAccess('Contacts', 'import', true))$module_menu[] =Array("index.php?module=Contacts&action=Import&step=1&return_module=Contacts&return_action=index", $app_strings['LBL_IMPORT'],"Import", 'Contacts');
    if(ACLController::checkAccess('Contacts','list', true)) $module_menu[] = Array('#', '<span style="display: none">wp_shortcut_fill_0</span>', '');

//	echo "Om Tagged";
?>
