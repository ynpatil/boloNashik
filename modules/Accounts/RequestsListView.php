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
 * $Id: OpenListView.php,v 1.48.2.1 2005/05/05 02:38:26 robert Exp $
 ********************************************************************************/
//om
require_once('XTemplate/xtpl.php');
require_once("data/Tracker.php");
require_once("include/utils.php");
require_once("include/TimeDate.php");
require_once("modules/Accounts/AccountRequest.php");

$timedate = new TimeDate();
global $currentModule, $theme, $focus, $action, $open_status, $log;

global $app_strings;
//we don't want the parent module's string file, but rather the string file specifc to this subpanel
global $current_language;
$current_module_strings = return_module_language($current_language, 'Accounts');

$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

if(!isset($_REQUEST['activity_details_for']) || $_REQUEST['activity_details_for'] == "Accounts")
{
	require_once("modules/Accounts/AccountRequestListViewQuery.php");
	require_once("modules/Accounts/AccountRequestListViewDisplay.php");
}

if(count($account_list)>0)
{
echo "\n<p>\n";

$xtpl=new XTemplate ('modules/Accounts/SubPanelAccountRequestView.html');
$current_module_strings = return_module_language($current_language, 'Accounts');

$xtpl->assign("MOD", $current_module_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("THEME",$theme);
$xtpl->assign("IMAGE_PATH", $image_path);
$xtpl->assign("RETURN_URL", "&return_module=$currentModule&return_action=DetailView&return_id=" . ((is_object($focus) && ! empty($focus->id)) ? $focus->id : ""));
$xtpl->assign("EDIT_INLINE_PNG",  get_image($image_path.'edit_inline','align="absmiddle" alt="'.$app_strings['LNK_EDIT'].'" border="0"'));
$xtpl->assign("DELETE_INLINE_PNG",  get_image($image_path.'delete_inline','align="absmiddle" alt="'.$app_strings['LNK_DELETE'].'" border="0"'));

$oddRow = true;

$xtpl->assign("RETURN_URL",$return_url);

foreach($account_list as $account)
{
	//print("Time :".$time."<br>");
	$activity_fields = array(
		'ID' => $account['id'],
		'NAME' => $account['name'],
		'PHONE_OFFICE' => $account['phone_office'],
		'DESCRIPTION' => $account['description'],
	);

 $xtpl->assign("ACCOUNT", $activity_fields);

 global $odd_bg;
 global $even_bg;
 global $hilite_bg;
 global $click_bg;

$xtpl->assign("BG_HILITE", $hilite_bg);
$xtpl->assign("BG_CLICK", $click_bg);

	if($oddRow)
    {
        //todo move to themes
		$xtpl->assign("ROW_COLOR", 'oddListRow');
		$xtpl->assign("BG_COLOR", $odd_bg);
    }
    else
    {
        //todo move to themes
		$xtpl->assign("ROW_COLOR", 'evenListRow');
		$xtpl->assign("BG_COLOR", $even_bg);
    }
    $oddRow = !$oddRow;

	$xtpl->parse("main.row");
// Put the rows in.
}

$xtpl->parse("main");
if (count($account_list)>0)
{
	echo get_form_header('New Requests for '.$current_module_strings['LBL_MODULE_NAME'], '','', false);
	$xtpl->out("main");
}
echo "\n</p>\n";
}
else
echo "<i>No records found</i>";

?>
