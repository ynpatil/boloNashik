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
 * $Id: Menu.php,v 1.37 2006/06/09 12:34:50 wayne Exp $
 * Description:  TODO To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
//om
global $current_language, $app_strings;

$parent_type = $_REQUEST['return_module'];
$mod_strings = return_module_language($current_language, $parent_type);

if(ACLController::checkAccess($parent_type, 'edit', true))$module_menu[]=Array("index.php?module=$parent_type&action=EditView&return_module=$parent_type&return_action=DetailView", $mod_strings['LNK_NEW_'.$parent_type],"Create".$parent_type, $parent_type);

if(ACLController::checkAccess($parent_type, 'list', true))$module_menu[]=Array("index.php?module=$parent_type&action=index&return_module=$parent_type&return_action=DetailView", $mod_strings['LNK_'.$parent_type.'_LIST'],$parent_type, $parent_type);

if(ACLController::checkAccess($parent_type, 'import', true))$module_menu[]=Array("index.php?module=$parent_type&action=Import&step=1&return_module=$parent_type&return_action=index", $app_strings['LBL_IMPORT'],"Import", $parent_type);
if(ACLController::checkAccess($parent_type,'list', true)) $module_menu[] = Array('#', '<span style="display: none">wp_shortcut_fill_0</span>', '');
?>
