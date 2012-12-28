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
 * $Id: ContactOpportunityRelationshipEdit.php,v 1.30 2006/06/06 17:57:56 majed Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once('modules/Contacts/ContactOpportunityRelationship.php');
require_once('modules/Contacts/Forms.php');
require_once('include/utils.php');

global $app_strings;
global $app_list_strings;
global $mod_strings;
global $sugar_version, $sugar_config;

$focus = new ContactOpportunityRelationship();

if(isset($_REQUEST['record'])) {
    $focus->retrieve($_REQUEST['record']);
}

if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
}

// Prepopulate either side of the relationship if passed in.
safe_map('opportunity_name', $focus);
safe_map('opportunity_id', $focus);
safe_map('contact_name', $focus);
safe_map('contact_id', $focus);
safe_map('contact_role', $focus);


$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$GLOBALS['log']->info("Contact opportunity relationship");

$json = getJSONobj();
require_once('include/QuickSearchDefaults.php');
$qsd = new QuickSearchDefaults();
$sqs_objects = array('opportunity_name' => $qsd->getQSParent());
$sqs_objects['opportunity_name']['populate_list'] = array('opportunity_name', 'opportunity_id');
$quicksearch_js = $qsd->getQSScripts();
$quicksearch_js .= '<script type="text/javascript" language="javascript">sqs_objects = ' . $json->encode($sqs_objects) . '</script>';
echo $quicksearch_js;

$xtpl=new XTemplate ('modules/Contacts/ContactOpportunityRelationshipEdit.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

$xtpl->assign("RETURN_URL", "&return_module=$currentModule&return_action=DetailView&return_id=$focus->id");
$xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
$xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
$xtpl->assign("RETURN_ID", $_REQUEST['return_id']);
$xtpl->assign("THEME", $theme);
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "index.php?".$GLOBALS['request_string']);
$xtpl->assign("ID", $focus->id);
$xtpl->assign("CONTACT",$contactName = Array("NAME" => $focus->contact_name, "ID" => $focus->contact_id));
$xtpl->assign("OPPORTUNITY",$oppName = Array("NAME" => $focus->opportunity_name, "ID" => $focus->opportunity_id));

echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_CONTACT_OPP_FORM_TITLE']." ".$contactName['NAME'] . " - ". $oppName['NAME'], true);
echo "\n</p>\n";

$xtpl->assign("CONTACT_ROLE_OPTIONS", get_select_options_with_id($app_list_strings['opportunity_relationship_type_dom'], $focus->contact_role));




$xtpl->parse("main");

$xtpl->out("main");

require_once('include/javascript/javascript.php');
$javascript = new javascript();
$javascript->setFormName('EditView');
$javascript->setSugarBean($focus);
$javascript->addToValidateBinaryDependency('opportunity_name', 'alpha', $app_strings['ERR_SQS_NO_MATCH_FIELD'] . $mod_strings['LBL_OPP_NAME'], 'false', '', 'opportunity_id');
echo $javascript->getScript();


?>
