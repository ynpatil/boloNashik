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
 * $Id: DetailView.php,v 1.108 2006/08/25 21:24:41 eddy Exp $
 * Description:  
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
//om
require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Accounts/Account.php');
require_once('include/DetailView/DetailView.php');

global $timedate;
global $mod_strings;
global $app_strings;
global $app_list_strings;
global $gridline;
$focus = new Account();
$detailView = new DetailView();

$offset=0;
if (isset($_REQUEST['offset']) or isset($_REQUEST['record'])) {
	$result = $detailView->processSugarBean("ACCOUNT", $focus, $offset);
	if($result == null) {
	    sugar_die($app_strings['ERROR_NO_RECORD']);
	}
	$focus=$result;
} else {
	header("Location: index.php?module=Accounts&action=index");
}

if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
}
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME'].": ".$focus->name, true);
echo "\n</p>\n";

$GLOBALS['log']->info("Account detail view");

$xtpl=new XTemplate ('modules/Accounts/DetailView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

$xtpl->assign("THEME", $theme);
$xtpl->assign("GRIDLINE", $gridline);
$xtpl->assign("IMAGE_PATH", $image_path);
$xtpl->assign("PRINT_URL", "index.php?".$GLOBALS['request_string']);

$qr_url = "http://respforce.timesgroup.com/qrcode2/gotourl.php?url=".add_http($focus->website);

$img_url = "<img src=\"$qr_url\" alt=\"qrcode\"/>";
$xtpl->assign("QR_IMG",$img_url);
$xtpl->assign("QR_URL",$qr_url);

$popup_request_data = array(
      'call_back_function' => 'set_return',
      'form_name' => 'EditView',
      'field_to_name_array' => array(),
);

$json = getJSONobj();
$encoded_popup_request_data = $json->encode($popup_request_data);
require_once("modules/RFC/RFC.php");
$focusRFC = new RFC();
$count = $focusRFC->_get_num_rows_in_query(RFC::get_new_rfc_count_query());
//echo "Count :".$count;
if($count>0)
$rfc_link = get_image($image_path."sqsWait","alt='New Request For Changes present'  border='0' align='absmiddle'");
$rfc_link .= "<a href='#' onclick='open_popup(\"RFC\", \"700\", \"400\", \"&record=".$_REQUEST['record']."&module_name=".$_REQUEST['module']."&owner=".$focus->isOwner($current_user->id)."\", true, false, $encoded_popup_request_data);' class=\"listViewPaginationLinkS1\">".$app_strings['LNK_VIEW_RFC_LOG']."</a>";
$xtpl->assign("RFC_LIST", $rfc_link);

if ($focus->annual_revenue != '')
{
	$xtpl->assign("ANNUAL_REVENUE", $sugar_config['default_currency_symbol'].$focus->annual_revenue);
}
$xtpl->assign("BILLING_ADDRESS_STREET", nl2br($focus->billing_address_street));
if (empty($focus->billing_address_state_desc))
{
	$xtpl->assign("BILLING_ADDRESS_CITY", $focus->billing_address_city_desc);
}
else
{
	$xtpl->assign("BILLING_ADDRESS_CITY", $focus->billing_address_city_desc.', ');
}
//echo "Billing ".$focus->billing_address_city_desc;
//echo "Shipping ".$focus->shipping_address_city_desc;
$xtpl->assign("BILLING_ADDRESS_STATE", $focus->billing_address_state_desc);
$xtpl->assign("BILLING_ADDRESS_POSTALCODE", $focus->billing_address_postalcode);
$xtpl->assign("BILLING_ADDRESS_COUNTRY", $focus->billing_address_country_desc);
$xtpl->assign("DATE_ENTERED", $focus->date_entered);
//echo "Assigned user name :".$focus->assigned_user_id;
$xtpl->assign("ASSIGNED_TO", $focus->assigned_user_name);
$xtpl->assign("DESCRIPTION", nl2br(url2html($focus->description)));
$xtpl->assign('EMAIL1_LINK', $current_user->getEmailLink('email1', $focus));
$xtpl->assign('EMAIL2_LINK', $current_user->getEmailLink('email2', $focus));
$xtpl->assign("EMAIL1", $focus->email1);
$xtpl->assign("EMAIL2", $focus->email2);
$xtpl->assign("EMPLOYEES", $focus->employees);
$xtpl->assign("ID", $focus->id);
//echo "Industry ".$focus->industry_name;

if ($focus->anniversary == '0000-00-00') $xtpl->assign("ANNIVERSARY", '');
else $xtpl->assign("ANNIVERSARY", $focus->anniversary);

$xtpl->assign("INDUSTRY", $focus->industry_name);
$xtpl->assign("LINKAGE_DESC_C", $focus->linkage_desc_c);

$xtpl->assign("NAME", $focus->name);
$xtpl->assign("OWNERSHIP", $app_list_strings['account_ownership_dom'][$focus->ownership]);
$xtpl->assign("PARENT_ID", $focus->parent_id);
$xtpl->assign("PARENT_NAME", $focus->parent_name);
$xtpl->assign("AOR_ID", $focus->aor_id);
$xtpl->assign("AOR_NAME", $focus->aor_name);
$xtpl->assign("CREATED_BY", $focus->created_by_name);
$xtpl->assign("MODIFIED_BY", $focus->modified_by_name);
$xtpl->assign("PARENT_NAME", $focus->parent_name);
$xtpl->assign("PARENT_ID", $focus->parent_id);
$xtpl->assign("PHONE_ALTERNATE", $focus->phone_alternate);
$xtpl->assign("PHONE_FAX", $focus->phone_fax);
$xtpl->assign("PHONE_OFFICE", $focus->phone_office);
$xtpl->assign("RATING", $focus->rating);
$xtpl->assign("SHIPPING_ADDRESS_STREET", nl2br($focus->shipping_address_street));

if (empty($focus->shipping_address_state_desc))
{
	$xtpl->assign("SHIPPING_ADDRESS_CITY", $focus->shipping_address_city_desc);
}
else
{
	$xtpl->assign("SHIPPING_ADDRESS_CITY", $focus->shipping_address_city_desc.', ');
}
$xtpl->assign("SHIPPING_ADDRESS_STATE", $focus->shipping_address_state_desc);
$xtpl->assign("SHIPPING_ADDRESS_COUNTRY", $focus->shipping_address_country_desc);
$xtpl->assign("SHIPPING_ADDRESS_POSTALCODE", $focus->shipping_address_postalcode);
$xtpl->assign("SIC_CODE", $focus->sic_code);
//$xtpl->assign("TICKER_SYMBOL", $focus->ticker_symbol);
$xtpl->assign("ACCOUNT_TYPE", $app_list_strings['account_type_dom'][$focus->account_type]);
if ($focus->website != '') $xtpl->assign("WEBSITE", $focus->website);
$xtpl->assign("DATE_MODIFIED", $focus->date_modified);
$xtpl->assign("DATE_ENTERED",$focus->date_entered);

global $current_user;
if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){
	$xtpl->assign("ADMIN_EDIT","<a href='index.php?action=index&module=DynamicLayout&from_action=".$_REQUEST['action'] ."&from_module=".$_REQUEST['module'] ."&record=".$_REQUEST['record']. "'>".get_image($image_path."EditLayout","border='0' alt='Edit Layout' align='bottom'")."</a>");
}

// copy to contacts
if(ACLController::checkAccess('Contacts', 'edit', true)) {
	$push_billing = '<input class="button" title="' . $mod_strings['LBL_PUSH_CONTACTS_BUTTON_LABEL'] .
						 '" type="button" onclick=\'open_popup("Contacts", 600, 600, "&account_name=' .
						 $focus->name . '&html=change_address' .
						 '&primary_address_street=' . str_replace(array("\rn", "\r", "\n"), array('','','<br>'), $focus->billing_address_street) .
						 '&primary_address_city=' . $focus->billing_address_city .
						 '&primary_address_state=' . $focus->billing_address_state .
						 '&primary_address_postalcode=' . $focus->billing_address_postalcode .
						 '&primary_address_country=' . $focus->billing_address_country .
						 '", true, false);\' value="' . $mod_strings['LBL_PUSH_CONTACTS_BUTTON_TITLE']. '">';
	$push_shipping = '<input class="button" title="' . $mod_strings['LBL_PUSH_CONTACTS_BUTTON_LABEL'] .
						 '" type="button" onclick=\'open_popup("Contacts", 600, 600, "&account_name=' .
						 $focus->name . '&html=change_address' .
						 '&primary_address_street=' . str_replace(array("\rn", "\r", "\n"), array('','','<br>'), $focus->shipping_address_street) .
						 '&primary_address_city=' . $focus->shipping_address_city .
						 '&primary_address_state=' . $focus->shipping_address_state .
						 '&primary_address_postalcode=' . $focus->shipping_address_postalcode .
						 '&primary_address_country=' . $focus->shipping_address_country .
						 '", true, false);\' value="' . $mod_strings['LBL_PUSH_CONTACTS_BUTTON_TITLE'] . '">';
} else {
	$push_billing = '';
	$push_shipping = '';
}
$xtpl->assign("PUSH_CONTACTS_BILLING", $push_billing);
$xtpl->assign("PUSH_CONTACTS_SHIPPING", $push_shipping);

$detailView->processListNavigation($xtpl, "ACCOUNT", $offset, $focus->is_AuditEnabled());
// adding custom fields:
require_once('modules/DynamicFields/templates/Files/DetailView.php');

$xtpl->parse("main.open_source");

$xtpl->parse("main");
$xtpl->out("main");
$sub_xtpl = $xtpl;

if($_REQUEST['show_subpanel'] == "true"){

require_once('include/SubPanel/SubPanelTiles.php');
//echo "Id from detailView ".$focus->id;
$subpanel = new SubPanelTiles($focus, 'Accounts');
echo $subpanel->display();
}
else{
$params = $GLOBALS['query_string'];
$params = explode("&",$params);
$params[] = "show_subpanel=true";

echo "<a href=\"index.php?".implode("&",$params)."\">Show complete information</a>";
}

require_once('modules/SavedSearch/SavedSearch.php');
$savedSearch = new SavedSearch();
$json = getJSONobj();
$savedSearchSelects = $json->encode(array($GLOBALS['app_strings']['LBL_SAVED_SEARCH_SHORTCUT'] . '<br>' . $savedSearch->getSelect('Accounts')));
$str = "<script>
YAHOO.util.Event.addListener(window, 'load', SUGAR.util.fillShortcuts, $savedSearchSelects);
</script>";
echo $str;
//include("modules/Zuckerdocs/SubPanelView.php");
?>
