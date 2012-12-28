<?php
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
 * Portions created by SugarCRM are Copyright (C) 2004 SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/
/*********************************************************************************
 * $Id: EditView.php,v 1.48 2005/04/14 18:03:43 lam Exp $
 * Description:  
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
//om
require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/SAPAccounts/SAPAccount.php');
require_once('modules/Accounts/Account.php');
require_once('modules/SAPAccounts/FormsObj.php');

global $app_strings;
global $app_list_strings;
global $mod_strings;
global $current_user;

$focus = new Account();

if(isset($_REQUEST['return_id']))
{
    $focus->retrieve($_REQUEST['return_id']);
//	echo "Got for id ".$_REQUEST['return_id'];
}

//$focus->id = $_REQUEST['return_id'];

echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_SAP_ACCOUNT_MODULE_NAME'].": ".$focus->name1, true);
echo "\n</p>\n";
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$GLOBALS['log']->debug("SAP Account detail view");

$xtpl=new XTemplate ('modules/SAPAccounts/SAPAccountEditView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

//if(isset($focus->assigned_user_id))
//echo "Disabled ".ACLController::checkAccess('Accounts', 'edit',true);
//$xtpl->assign("DISABLED",(ACLController::checkAccess('Accounts', 'edit',true)?"":"DISABLED"));

global $odd_bg;
global $even_bg;
global $hilite_bg;
global $click_bg;

//$xtpl->assign("ODD_BG", $odd_bg);
//$xtpl->assign("EVEN_BG", $even_bg);
//$xtpl->assign("BG_HILITE", $hilite_bg);
//$xtpl->assign("BG_CLICK", $click_bg);

if(isset($_REQUEST['return_module'])) $xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
if(isset($_REQUEST['return_action'])) $xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
if(isset($_REQUEST['return_id'])) $xtpl->assign("RETURN_ID", $_REQUEST['return_id']);
if(isset($_REQUEST['bug_id'])) $xtpl->assign("BUG_ID", $_REQUEST['bug_id']);
$xtpl->assign("JAVASCRIPT", get_set_focus_js().get_validate_record_js1());
$xtpl->assign("THEME", $theme);
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "index.php?".$GLOBALS['request_string']);
$xtpl->assign("PRINT_URL", "index.php?".$GLOBALS['request_string']);

global $current_user;
if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){
	$record = '';
	if(!empty($_REQUEST['record'])){
		$record = 	$_REQUEST['record'];
	}
	$xtpl->assign("ADMIN_EDIT","<a href='index.php?action=index&module=DynamicLayout&from_action=".$_REQUEST['action'] ."&from_module=".$_REQUEST['module'] ."&record=".$record. "'>".get_image($image_path."EditLayout","border='0' alt='Edit Layout' align='bottom'")."</a>");
}

//if ($focus->assigned_user_id == '' && (!isset($focus->id) || $focus->id=0)) $focus->assigned_user_id = $current_user->id;
//Add Custom Fields
require_once('modules/DynamicFields/templates/Files/EditView.php');

$xtpl->parse("main");

$xtpl->out("main");
require_once('include/javascript/javascript.php');
$javascript = new javascript();
$javascript->setFormName('SAPAccountEditView');
$javascript->setSugarBean(new SAPAccount());
$javascript->addAllFields('');
echo $javascript->getScript();

$script = "";

if(ACLController::checkAccess('Accounts', 'edit', true)){
$script .= <<<EOQ
			<script>
			if(typeof(document.EditView) != 'undefined'){
				if(typeof(document.EditView.elements['Save']) != 'undefined'){
					document.EditView.elements['Save'].disabled = 'disabled';
				}
			}
			</script>

EOQ;
}
//echo $script;
?>
