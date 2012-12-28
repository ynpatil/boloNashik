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
 * Description: 
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

global $mod_strings, $app_strings;

if(ACLController::checkAccess('Brands', 'edit', true))$module_menu[]=Array("index.php?module=Brands&action=EditView&return_module=Brands&return_action=DetailView", $mod_strings['LNK_NEW_BRAND'],"CreateBrands", 'Brands');
if(ACLController::checkAccess('Brands', 'list', true))$module_menu[]=Array("index.php?module=Brands&action=index&return_module=Brands&return_action=DetailView", $mod_strings['LNK_BRAND_LIST'],"Brands", 'Brands');
//if(ACLController::checkAccess('Brands', 'import', true))$module_menu[]=Array("index.php?module=Brands&action=Import&step=1&return_module=Brands&return_action=index", $app_strings['LBL_IMPORT'],"Import", 'Brands');
if(ACLController::checkAccess('Brands', 'sold', true))$module_menu[]=Array("index.php?module=Brands&action=ProductSold&step=1&return_module=Brands&return_action=index", $app_strings['LBL_SOLD'],"Sold", 'Brands');
if(ACLController::checkAccess('Brands','list', true)) $module_menu[] = Array('#', '<span style="display: none">wp_shortcut_fill_0</span>', '');

if(file_exists("brandorgchart.php") &&
   $_REQUEST['action']=="DetailView" &&
   strpos("Brands", $_REQUEST['module'])!==false) {
	$module_menu[] = Array("#\" OnClick=\"javascript:window.open('brandorgchart.php?module=". $_REQUEST['module'] . "&record=". $_REQUEST['record'] . "', 'orgchart', 'width=640, height=480,resizable=1,scrollbars=1');", $app_strings['LBL_ORGCHART_BUTTON_TITLE'], "MyReports");
}
?>
