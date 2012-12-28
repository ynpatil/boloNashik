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
 * $Id: DetailView.php,v 1.64 2006/08/29 20:53:08 awu Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Notes/Note.php');
require_once('modules/Notes/Forms.php');
require_once('include/upload_file.php');
require_once('include/DetailView/DetailView.php');


global $app_strings;
global $mod_strings;

$focus = new Note();

$detailView = new DetailView();
$offset=0;
if (isset($_REQUEST['offset']) or isset($_REQUEST['record'])) {
	$result = $detailView->processSugarBean("NOTE", $focus, $offset);
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

//needed when creating a new note with default values passed in
if (isset($_REQUEST['contact_name']) && is_null($focus->contact_name)) {
	$focus->contact_name = $_REQUEST['contact_name'];
}
if (isset($_REQUEST['contact_id']) && is_null($focus->contact_id)) {
	$focus->contact_id = $_REQUEST['contact_id'];
}
if (isset($_REQUEST['opportunity_name']) && is_null($focus->parent_name)) {
	$focus->parent_name = $_REQUEST['opportunity_name'];
}
if (isset($_REQUEST['opportunity_id']) && is_null($focus->parent_id)) {
	$focus->parent_id = $_REQUEST['opportunity_id'];
}
if (isset($_REQUEST['account_name']) && is_null($focus->parent_name)) {
	$focus->parent_name = $_REQUEST['account_name'];
}
if (isset($_REQUEST['account_id']) && is_null($focus->parent_id)) {
	$focus->parent_id = $_REQUEST['account_id'];
}
if (isset($_REQUEST['meeting_name']) && is_null($focus->parent_name)) {
	$focus->parent_name = $_REQUEST['meeting_name'];
}
if (isset($_REQUEST['meeting_id']) && is_null($focus->parent_id)) {
	$focus->parent_id = $_REQUEST['meeting_id'];
}
if (isset($_REQUEST['call_name']) && is_null($focus->parent_name)) {
	$focus->parent_name = $_REQUEST['call_name'];
}
if (isset($_REQUEST['call_id']) && is_null($focus->parent_id)) {
	$focus->parent_id = $_REQUEST['call_id'];
}
echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME'].": ".$focus->name, true);
echo "\n</p>\n";
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$GLOBALS['log']->info("Note detail view");

$xtpl=new XTemplate ('modules/Notes/DetailView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

$img_url = "<img src=\"http://10.100.109.124/qrcode/email.php?emailto=".$focus->contact_email."&subject=".$focus->name."&msg=".$focus->description."\" alt=\"qrcode\"/>";
$xtpl->assign("QR_IMG",$img_url);

if (isset($_REQUEST['return_module'])) $xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
if (isset($_REQUEST['return_action'])) $xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
if (isset($_REQUEST['return_id'])) $xtpl->assign("RETURN_ID", $_REQUEST['return_id']);
$xtpl->assign("THEME", $theme);
$xtpl->assign("GRIDLINE", $gridline);
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "index.php?".$GLOBALS['request_string']);
$xtpl->assign("ID", $focus->id);
$xtpl->assign("CONTACT_NAME", $focus->contact_name);
$xtpl->assign("CONTACT_PHONE", $focus->contact_phone);
$xtpl->assign("CONTACT_EMAIL", $focus->contact_email);
$xtpl->assign("CONTACT_ID", $focus->contact_id);
// While getting the parent module, translate it into the name of the module folder from the key
if (! empty($focus->parent_type))
{
	$xtpl->assign("PARENT_TYPE", $app_list_strings['record_type_display_notes'][$focus->parent_type]);
	$xtpl->assign("PARENT_MODULE", $focus->parent_type);
}

$xtpl->assign("CREATED_BY", $focus->created_by_name);
$xtpl->assign("MODIFIED_BY", $focus->modified_by_name);

$xtpl->assign("PARENT_NAME", $focus->parent_name);
$xtpl->assign("PARENT_ID", $focus->parent_id);

$xtpl->assign("BRAND_ID", $focus->brand_id);
$xtpl->assign("BRAND_NAME", $focus->brand_name);

$xtpl->assign("NAME", $focus->name);
$xtpl->assign("DATE_MODIFIED", $focus->date_modified);
$xtpl->assign("DATE_ENTERED", $focus->date_entered);
global $current_user;
if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){

	$xtpl->assign("ADMIN_EDIT","<a href='index.php?action=index&module=DynamicLayout&from_action=".$_REQUEST['action'] ."&from_module=".$_REQUEST['module'] ."&record=".$_REQUEST['record']. "'>".get_image($image_path."EditLayout","border='0' alt='Edit Layout' align='bottom'")."</a>");
}
if ( isset($focus->filename) && $focus->filename != '')
{
	$save_file = urlencode(basename(UploadFile::get_url($focus->filename,$focus->id)));
	
	$xtpl->assign("SAVE_FILE", $save_file);
	$xtpl->assign("FILE_NAME", $focus->filename);
	$xtpl->assign("FILELINK", "<a href='download.php?id=".$save_file."&type=Notes' class='tabDetailViewDFLink'>".$focus->filename."</a>&nbsp");
}
$xtpl->assign("DESCRIPTION", nl2br(url2html($focus->description)));







$xtpl->parse("main.open_source");




$detailView->processListNavigation($xtpl, "NOTE", $offset);
// adding custom fields:
require_once('modules/DynamicFields/templates/Files/DetailView.php');

$admin = new Administration();
$admin->retrieveSettings();
if ($admin->settings['portal_on'])
{
	if ($focus->portal_flag) 
	{
		$xtpl->assign("PORTAL_FLAG", "checked='checked'");
	}
	$xtpl->parse("main.portal_on");
}
else 
{
	$xtpl->parse("main.portal_off");
}
$xtpl->assign("TAG", $focus->listviewACLHelper());
$xtpl->parse("main");

$xtpl->out("main");

?>
