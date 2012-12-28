<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');
/* * *******************************************************************************
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
 * ****************************************************************************** */
/* * *******************************************************************************
 * $Id: DetailView.php,v 1.42 2006/08/03 00:05:39 wayne Exp $
 * Description:  
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 * ****************************************************************************** */

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Campaigns/Campaign.php');
require_once('modules/Campaigns/Forms.php');
require_once('include/DetailView/DetailView.php');
//require_once('modules/Leads/Lead.php');
//require_once('modules/Calls/Call.php');
//define('sugarEntry', true);
//include_once 'include/utils.php';

global $mod_strings;
global $app_strings;
global $app_list_strings;
global $sugar_version, $sugar_config;

$focus = new Campaign();

$detailView = new DetailView();
$offset = 0;
$offset = 0;
if (isset($_REQUEST['offset']) or isset($_REQUEST['record'])) {
    $result = $detailView->processSugarBean("CAMPAIGN", $focus, $offset);
    if ($result == null) {
        sugar_die($app_strings['ERROR_NO_RECORD']);
    }
    $focus = $result;
} else {
    header("Location: index.php?module=Accounts&action=index");
}

echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME'] . ": " . $focus->name, true);
echo "\n</p>\n";
global $theme;
$theme_path = "themes/" . $theme . "/";
$image_path = $theme_path . "images/";
require_once($theme_path . 'layout_utils.php');

//$GLOBALS['log']->info("Campaign detail view");

$xtpl = new XTemplate('modules/Campaigns/DetailView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

$xtpl->assign("THEME", $theme);
$xtpl->assign("GRIDLINE", $gridline);
$xtpl->assign("IMAGE_PATH", $image_path);
$xtpl->assign("PRINT_URL", "index.php?" . $GLOBALS['request_string']);
$xtpl->assign("ID", $focus->id);
$xtpl->assign("ASSIGNED_TO", $focus->assigned_user_name);
$xtpl->assign("STATUS", $app_list_strings['campaign_status_dom'][$focus->status]);
$xtpl->assign("NAME", $focus->name);
//$xtpl->assign("TYPE", $app_list_strings['campaign_type_dom'][$focus->campaign_type]);
$xtpl->assign("START_DATE", $focus->start_date);
$xtpl->assign("END_DATE", $focus->end_date);

$xtpl->assign("BUDGET", $focus->budget);
$xtpl->assign("ACTUAL_COST", $focus->actual_cost);
$xtpl->assign("EXPECTED_COST", $focus->expected_cost);
$xtpl->assign("EXPECTED_REVENUE", $focus->expected_revenue);

$xtpl->assign("OBJECTIVE", nl2br($focus->objective));
$xtpl->assign("CONTENT", nl2br(url2html($focus->content)));
$xtpl->assign("DATE_MODIFIED", $focus->date_modified);
$xtpl->assign("DATE_ENTERED", $focus->date_entered);

$xtpl->assign("CREATED_BY", $focus->created_by_name);
$xtpl->assign("MODIFIED_BY", $focus->modified_by_name);
$xtpl->assign("TRACKER_URL", $sugar_config['site_url'] . '/campaign_tracker.php?track=' . $focus->tracker_key);
$xtpl->assign("TRACKER_COUNT", intval($focus->tracker_count));
$xtpl->assign("TRACKER_TEXT", $focus->tracker_text);
$xtpl->assign("REFER_URL", $focus->refer_url);
$xtpl->assign("PRODUCT_NAME", $focus->product_name);
$xtpl->assign("TOTAL_LEAD_COUNT", $focus->getCampaignLeadCount());
if ($focus->campaign_type == 'Email') {
    $xtpl->assign("ADD_BUTTON_STATE", "submit");
} else {
    $xtpl->assign("ADD_BUTTON_STATE", "hidden");
    $xtpl->assign("DISABLE_LINK", "display:none");
}


//if($_REQUEST[vendor_file_status]==2){
//    $xtpl->assign("GENERATE_FILE_REQUEST", "Your request send for vendor file generation");
//}
if($focus->send_email==1){
    $xtpl->assign("EMAIL_STATUS", "In process");
}
if($focus->send_email==2){
    $xtpl->assign("EMAIL_STATUS", "Sent");
}
if($focus->vendor_file_status==0){
    $xtpl->assign("GENERATE_FILE_STATUS", "Waiting for generate vendor file");
}
if($focus->vendor_file_status==1){
    $xtpl->assign("GENERATE_FILE_VALUE", "disabled");
    $xtpl->assign("GENERATE_FILE_STATUS", "In process");
}

/*  Adjust Persantage      */
require_once('modules/Campaigns/CampaignVendor.php');
$CampaignVendorObj = new CampaignVendor();

$where = "campaign_vendor.campaign_id= '$focus->id'";
$CampaignVendorArr = $CampaignVendorObj->get_full_list($order_by, $where, $check_dates);
if (is_array($CampaignVendorArr)) {
    $xtpl->assign("DIV_VALUE", "display : block");
    for ($j = 0; $j < count($CampaignVendorArr); $j++) {
        $CampaignVendorObj->vendor_id = $CampaignVendorArr[$j]->vendor_id;
        $CampaignVendorObj->fill_in_additional_detail_fields();
        $xtpl->assign("VENDOR_ID", $CampaignVendorObj->vendor_id);
        $xtpl->assign("VENDOR_NAME", $CampaignVendorObj->vendor_name);
        $xtpl->assign("PERSANTAGE", $CampaignVendorArr[$j]->percentage);
        $xtpl->assign("CAMP_VENDOR_ID", $CampaignVendorArr[$j]->id);
        if($focus->vendor_file_status==2){            
            $xtpl->assign("GENERATE_FILE_VALUE", "disabled");
            $xtpl->assign("GENERATE_FILE_STATUS", "Completed");
            $xtpl->assign("DOWNLOAD_FILE_LINK", "<a href='custom/tmp/campaigns/$focus->id/$CampaignVendorObj->vendor_id.csv' class='listViewTdLinkS1'>Download File</a>");
        }
        $xtpl->parse("main.CampaignVendor");
    }
}else {
    $xtpl->assign("DIV_VALUE", "display : none");
}
/*  END Adjust Persantage     */

/* START Button Send Email*/
if($focus->send_email==0 AND $focus->vendor_file_status==2){    
    $xtpl->parse("main.email_status");    
} 
/*  END */


/* START Button Generate Vendor Data */
$ProspectListArr = $focus->get_prospect_list_entries_count();
if(count($ProspectListArr) > 0 && count($CampaignVendorArr) > 0){    
    $xtpl->parse("main.generate_vendor_data");    
} 
/*  END */

require_once('modules/Currencies/Currency.php');
$currency = new Currency();
if (isset($focus->currency_id) && !empty($focus->currency_id)) {
    $currency->retrieve($focus->currency_id);
    if ($currency->deleted != 1) {
        $xtpl->assign("CURRENCY", $currency->iso4217 . ' ' . $currency->symbol);
    }else
        $xtpl->assign("CURRENCY", $currency->getDefaultISO4217() . ' ' . $currency->getDefaultCurrencySymbol());
}else {

    $xtpl->assign("CURRENCY", $currency->getDefaultISO4217() . ' ' . $currency->getDefaultCurrencySymbol());
}
global $current_user;
if (is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])) {

    $xtpl->assign("ADMIN_EDIT", "<a href='index.php?action=index&module=DynamicLayout&from_action=" . $_REQUEST['action'] . "&from_module=" . $_REQUEST['module'] . "&record=" . $_REQUEST['record'] . "'>" . get_image($image_path . "EditLayout", "border='0' alt='Edit Layout' align='bottom'") . "</a>");
}

$detailView->processListNavigation($xtpl, "CAMPAIGN", $offset, $focus->is_AuditEnabled());
// adding custom fields:
require_once('modules/DynamicFields/templates/Files/DetailView.php');

$xtpl->parse("main.open_source");

$xtpl->parse("main");
$xtpl->out("main");

$sub_xtpl = $xtpl;
$old_contents = ob_get_contents();
ob_end_clean();
ob_start();
echo $old_contents;


require_once('include/SubPanel/SubPanelTiles.php');


$subpanel = new SubPanelTiles($focus, 'Campaigns');
if ($focus->campaign_type != 'Email') {//_ppd($subpanel->subpanel_definitions->layout_defs);
    $subpanel->subpanel_definitions->exclude_tab('emailmarketing');
}
$alltabs = $subpanel->subpanel_definitions->get_available_tabs();
if (!empty($alltabs)) {

    foreach ($alltabs as $name) {
        if ($name != 'prospectlists' and $name != 'emailmarketing' and $name != 'tracked_urls' and $name != 'vendors') {
            $subpanel->subpanel_definitions->exclude_tab($name);
        }
    }
}
echo $subpanel->display();

require_once('modules/SavedSearch/SavedSearch.php');
$savedSearch = new SavedSearch();
$json = getJSONobj();
$savedSearchSelects = $json->encode(array($GLOBALS['app_strings']['LBL_SAVED_SEARCH_SHORTCUT'] . '<br>' . $savedSearch->getSelect('Campaigns')));
$str = "<script>
YAHOO.util.Event.addListener(window, 'load', SUGAR.util.fillShortcuts, $savedSearchSelects);
</script>";
echo $str;
?>
