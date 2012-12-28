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
 * $Id: DetailView.php,v 1.77 2005/04/20 01:27:12 joey Exp $
 * Description: 
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
//om
//om
//om
require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/SAPAccounts/SAPAccount.php');
require_once('include/TimeDate.php');
$timedate = new TimeDate();
global $mod_strings;
global $app_strings;
global $app_list_strings;
global $gridline;
$focus =& new SAPAccount();
$focus->id = $_REQUEST['record'];

if(!empty($_REQUEST['record'])) {
    $result = $focus->retrieve($_REQUEST['record']);
    if($result == null)
    {
    	//sugar_die("Error retrieving record.  You may not be authorized to view this record.");
    }
}
else {
	header("Location: index.php?module=Accounts&action=index");
}

if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
}

echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_SAP_ACCOUNT_MODULE_NAME'].": ".$focus->name1, true);
echo "\n</p>\n";
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$GLOBALS['log']->info("SAP Account detail view");

$xtpl=new XTemplate ('modules/SAPAccounts/SAPAccountDetailView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

if(isset($focus->assigned_user_id))
$xtpl->assign("DISABLED",(can_edit($focus->assigned_user_id)?"":"DISABLED"));

if(isset($_REQUEST['return_module'])) $xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
if(isset($_REQUEST['return_action'])) $xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
if(isset($_REQUEST['return_id'])) $xtpl->assign("RETURN_ID", $_REQUEST['return_id']);

$xtpl->assign("THEME", $theme);
$xtpl->assign("GRIDLINE", $gridline);
$xtpl->assign("IMAGE_PATH", $image_path);
$xtpl->assign("PRINT_URL", "index.php?".$GLOBALS['request_string']);
$xtpl->assign("DATE_ENTERED", $focus->date_entered);
$xtpl->assign("CREATED_BY", $focus->created_by_name);
$xtpl->assign("DATE_ENTERED",$focus->date_entered);
$xtpl->assign("GP_REF",$focus->gp_ref);
//echo "GP _REF :".$focus->gp_ref;
$xtpl->assign("NAME1",$focus->name1);
$xtpl->assign("ISPADRBSND",$focus->ispadrbsnd);
$xtpl->assign("HAUSN",$focus->hausn);
$xtpl->assign("STRAS",$focus->stras);
$xtpl->assign("STREET2",$focus->street2);
$xtpl->assign("ORT01",$focus->ort01);
$xtpl->assign("PSTLZ",$focus->pstlz);
$xtpl->assign("ISPTELD",$focus->ispteld);
$xtpl->assign("TELFX",$focus->telfx);
$xtpl->assign("ISPEMAIL",$focus->ispemail);
$xtpl->assign("ISPHANDY",$focus->isphandy);
$xtpl->assign("ID",$focus->id);

global $current_user;
if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){

	$xtpl->assign("ADMIN_EDIT","<a href='index.php?action=index&module=DynamicLayout&from_action=".$_REQUEST['action'] ."&from_module=".$_REQUEST['module'] ."&record=".$_REQUEST['record']. "'>".get_image($image_path."EditLayout","border='0' alt='Edit Layout' align='bottom'")."</a>");
}

// adding custom fields:
require_once('modules/DynamicFields/templates/Files/DetailView.php');

$xtpl->parse("main.open_source");

$xtpl->parse("main");
$xtpl->out("main");
?>
