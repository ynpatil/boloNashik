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
 * $Id: EditView.php,v 1.84 2006/08/03 00:01:13 wayne Exp $
 * Description: 
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
//om
require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Brands/Brand.php');
require_once('include/utils.php');
require_once('modules/Brands/Forms.php');
require_once('modules/Brands/TreeData.php');

global $app_strings;
global $app_list_strings;
global $mod_strings;
global $current_user;
global $sugar_version, $sugar_config;

$focus = new Brand();

if(isset($_REQUEST['record'])) {
    $focus->retrieve($_REQUEST['record']);
}
if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
}

echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME'].": ".$focus->name, true);
echo "\n</p>\n";
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$GLOBALS['log']->info("Brand detail view");

$xtpl=new XTemplate ('modules/Brands/EditView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);


///////////////////////////////////////
///
/// SETUP PARENT POPUP
/// Brand Popup
$popup_request_data = array(
	'call_back_function' => 'set_return',
	'form_name' => 'EditView',
	'field_to_name_array' => array(
		'id' => 'parent_id',
		'name' => 'parent_name',
		),
	);


$json = getJSONobj();

$xtpl->assign('encoded_brands_popup_request_data', $json->encode($popup_request_data));

$popup_request_data = array(
	'call_back_function' => 'set_return',
	'form_name' => 'EditView',
	'field_to_name_array' => array(
		'id' => 'account_id',
		'name' => 'account_name',
		),
	);

$xtpl->assign('encoded_accounts_popup_request_data', $json->encode($popup_request_data));

/// Users Popup
$popup_request_data = array(
	'call_back_function' => 'set_return',
	'form_name' => 'EditView',
	'field_to_name_array' => array(
		'id' => 'assigned_user_id',
		'user_name' => 'assigned_user_name',
		),
	);
$xtpl->assign('encoded_users_popup_request_data', $json->encode($popup_request_data));

/////////////////////////////////////////

if(isset($_REQUEST['return_module'])) $xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
if(isset($_REQUEST['return_action'])) $xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
if (isset($_REQUEST['return_id'])) $xtpl->assign("RETURN_ID", $_REQUEST['return_id']);
// handle Create $module then Cancel
if (empty($_REQUEST['return_id'])) {
	$xtpl->assign("RETURN_ACTION", 'index');
}
if(isset($_REQUEST['bug_id'])) $xtpl->assign("BUG_ID", $_REQUEST['bug_id']);
if(isset($_REQUEST['email_id'])) $xtpl->assign("EMAIL_ID", $_REQUEST['email_id']);

require_once('include/QuickSearchDefaults.php');
$qsd = new QuickSearchDefaults();
$sqs_objects = array('parent_name' => $qsd->getQSBrand(),
					'assigned_user_name' => $qsd->getQSUser(),
					'account_name' => $qsd->getQSAccount(),
					);
$quicksearch_js = $qsd->getQSScripts();
$quicksearch_js .= '<script type="text/javascript" language="javascript">sqs_objects = ' . $json->encode($sqs_objects) . '</script>';

$xtpl->assign("JAVASCRIPT", get_set_focus_js().get_validate_record_js().$quicksearch_js);
$xtpl->assign("THEME", $theme);
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "index.php?".$GLOBALS['request_string']);
$xtpl->assign("DATE_ENTERED", $focus->date_entered);
$xtpl->assign("ID", $focus->id);
$xtpl->assign("PARENT_ID", $focus->parent_id);
$xtpl->assign("PARENT_NAME", $focus->parent_name);
$xtpl->assign("ACCOUNT_ID", $focus->account_id);
$xtpl->assign("ACCOUNT_NAME", $focus->account_name);
$xtpl->assign("BRAND_POS", $focus->brand_pos);
$xtpl->assign("PROD_HIER_ID", $focus->prod_hier_id);
$xtpl->assign("PROD_HIER_DESC", $focus->prod_hier_desc);
$xtpl->assign("PRICE", $focus->price);

if (isset($focus->name)) $xtpl->assign("NAME", $focus->name);
else $xtpl->assign("NAME", "");
$xtpl->assign("SUGAR_VERSION", $sugar_version);
$xtpl->assign("JS_CUSTOM_VERSION", $sugar_config['js_custom_version']);

if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){
	$record = '';
	if(!empty($_REQUEST['record'])){
		$record = 	$_REQUEST['record'];
	}
	$xtpl->assign("ADMIN_EDIT","<a href='index.php?action=index&module=DynamicLayout&from_action=".$_REQUEST['action'] ."&from_module=".$_REQUEST['module'] ."&record=".$record. "'>".get_image($image_path."EditLayout","border='0' alt='Edit Layout' align='bottom'")."</a>");
}

if (empty($focus->assigned_user_id) && empty($focus->id))  $focus->assigned_user_id = $current_user->id;
if (empty($focus->assigned_name) && empty($focus->id))  $focus->assigned_user_name = $current_user->user_name;
$xtpl->assign("ASSIGNED_USER_OPTIONS", get_select_options_with_id(get_user_array(TRUE, "Active", $focus->assigned_user_id), $focus->assigned_user_id));
$xtpl->assign("ASSIGNED_USER_NAME", $focus->assigned_user_name);
$xtpl->assign("ASSIGNED_USER_ID", $focus->assigned_user_id );
//$xtpl->assign("ACCOUNT_TYPE_OPTIONS", get_select_options_with_id($app_list_strings['account_type_dom'], $focus->account_type));
//Add Custom Fields
require_once('modules/DynamicFields/templates/Files/EditView.php');

if(isset($_REQUEST['parent_id'])) $xtpl->assign("PARENT_ID", $_REQUEST['parent_id']);
if(isset($_REQUEST['parent_name'])) $xtpl->assign("PARENT_NAME", urldecode($_REQUEST['parent_name']));

/*
require_once('include/ytree/Tree.php');
require_once('include/ytree/Node.php');

$href_string = "javascript:select_node('prod_hier')";
$initial_nodes = get_category_nodes($href_string);
$tree = new Tree('prod_hier');
$tree->set_param('module','Brands');

$root_node = new Node("root", "Product Hierarchy");
$root_node->set_property("href", $href_string);
$root_node->expanded = false;
$root_node->dynamic_load = false;

$tree->add_node($root_node);
if(is_array($initial_nodes)){
foreach ($initial_nodes as $node) {
  $root_node->add_node($node);
}
}

$xtpl->assign("TREEHEADER",$tree->generate_header());
$xtpl->assign("TREEINSTANCE",$tree->generate_nodes_array());
*/
$sugar_config['site_url'] = preg_replace('/^http(s)?\:\/\/[^\/]+/',"http$1://".$_SERVER['HTTP_HOST'],$sugar_config['site_url']);
        if(!empty($_SERVER['SERVER_PORT']) &&$_SERVER['SERVER_PORT'] == '443') {
            $sugar_config['site_url'] = preg_replace('/^http\:/','https:',$sugar_config['site_url']);
       }
$site_data = "<script> var site_url= {\"site_url\":\"".$sugar_config['site_url']."\"};</script>\n";
echo $site_data;
$xtpl->assign("SITEURL",$site_data);

$xtpl->parse("main");
$xtpl->out("main");

require_once('include/javascript/javascript.php');
$javascript = new javascript();
$javascript->setFormName('EditView');
$javascript->setSugarBean($focus);
$javascript->addAllFields('');
$javascript->addToValidateBinaryDependency('parent_name', 'alpha', $app_strings['ERR_SQS_NO_MATCH_FIELD'] . $mod_strings['LBL_MEMBER_OF'], 'false', '', 'parent_id');

$javascript->addToValidateBinaryDependency('assigned_user_name', 'alpha', $app_strings['ERR_SQS_NO_MATCH_FIELD'] . $app_strings['LBL_ASSIGNED_TO'], 'false', '', 'assigned_user_id');
echo $javascript->getScript();

require_once('modules/SavedSearch/SavedSearch.php');
$savedSearch = new SavedSearch();
$json = getJSONobj();
$savedSearchSelects = $json->encode(array($GLOBALS['app_strings']['LBL_SAVED_SEARCH_SHORTCUT'] . '<br>' . $savedSearch->getSelect('Brands')));
$str = "<script>
YAHOO.util.Event.addListener(window, 'load', SUGAR.util.fillShortcuts, $savedSearchSelects);
</script>";
echo $str;

?>
