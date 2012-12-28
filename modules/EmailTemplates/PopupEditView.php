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
 * $Header: /var/cvsroot/sugarcrm/modules/EmailTemplates/PopupEditView.php,v 1.14 2006/06/06 17:58:20 majed Exp $
 * Description: TODO:  To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/EmailTemplates/EmailTemplate.php');
require_once('modules/EmailTemplates/Forms.php');
require_once("data/Tracker.php");
require_once('include/utils/db_utils.php');
require_once('modules/Campaigns/utils.php');

global $app_strings;
global $app_list_strings;
global $curent_language;
$mod_strings= return_module_language($current_language, $currentModule);
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$no_campaign=false;
if (!isset($_REQUEST['campaign_id']) or empty($_REQUEST['campaign_id'])) {
	$no_campaign=true;
}

$focus = new EmailTemplate();

if(isset($_REQUEST['record']) && !empty($_REQUEST['record'])) {
    $focus->retrieve($_REQUEST['record']);
}

$old_id = '';

if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') 
{
	if(!empty($focus->filename) )
	{	
	 $old_id = $focus->id;
	}
	$focus->id = "";
}



//setting default flag value so due date and time not required
if(!isset($focus->id)) $focus->date_due_flag = 'on';

//needed when creating a new case with default values passed in
if(isset($_REQUEST['contact_name']) && is_null($focus->contact_name)) {
	$focus->contact_name = $_REQUEST['contact_name'];
}
if(isset($_REQUEST['contact_id']) && is_null($focus->contact_id)) {
	$focus->contact_id = $_REQUEST['contact_id'];
}
if(isset($_REQUEST['parent_name']) && is_null($focus->parent_name)) {
	$focus->parent_name = $_REQUEST['parent_name'];
}
if(isset($_REQUEST['parent_id']) && is_null($focus->parent_id)) {
	$focus->parent_id = $_REQUEST['parent_id'];
}
if(isset($_REQUEST['parent_type'])) {
	$focus->parent_type = $_REQUEST['parent_type'];
}
elseif(!isset($focus->parent_type)) {
	$focus->parent_type = $app_list_strings['record_type_default_key'];
}

if(isset($_REQUEST['filename']) && $_REQUEST['isDuplicate'] != 'true') {
        $focus->filename = $_REQUEST['filename'];
}

echo insert_popup_header($theme);

echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME'].": ".$focus->name, true); 
echo "\n</p>\n";

$GLOBALS['log']->info("EmailTemplate detail view");

$xtpl=new XTemplate ('modules/EmailTemplates/EditView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("DEFAULT_MODULE","Contacts");
	
$xtpl->assign("CANCEL_SCRIPT", "window.close()");

if(isset($_REQUEST['return_module'])) $xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
if(isset($_REQUEST['return_action'])) $xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
if(isset($_REQUEST['return_id'])) $xtpl->assign("RETURN_ID", $_REQUEST['return_id']);
// handle Create $module then Cancel
if(empty($_REQUEST['return_id'])) {
	$xtpl->assign("RETURN_ACTION", 'index');
}
$xtpl->assign("INPOPUPWINDOW",'true');
$xtpl->assign("THEME", $theme);
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "index.php?".$GLOBALS['request_string']);
$xtpl->assign("JAVASCRIPT", get_set_focus_js().get_validate_record_js());
$xtpl->assign("ID", $focus->id);
if(isset($focus->name)) $xtpl->assign("NAME", $focus->name); else $xtpl->assign("NAME", "");
if(isset($focus->description)) $xtpl->assign("DESCRIPTION", $focus->description); else $xtpl->assign("DESCRIPTION", "");
if(isset($focus->subject)) $xtpl->assign("SUBJECT", $focus->subject); else $xtpl->assign("SUBJECT", "");
if( $focus->published == 'on')
{
$xtpl->assign("PUBLISHED","CHECKED");
}







include_once('modules/Contacts/Contact.php');
$contact = new Contact();
$fields = array();

$field_defs_js = "var field_defs = {'Contacts':[";
foreach($contact->field_defs as $field_def)
{

	if( ( $field_def['type'] == 'relate' && empty($field_def['custom_type']) ) || $field_def['type'] == 'assigned_user_name' || $field_def['type'] == 'link')
	{
		continue;
	}

 $field_def['vname'] = preg_replace('/:$/','',translate($field_def['vname'],'Contacts'));
 array_push($fields,"{name:'contact_".$field_def['name']."',value:'". $field_def['vname']."'}");
}
$field_defs_js .= implode(",\n",$fields);
$field_defs_js .= "],";

$field_defs_js .= "'Accounts':[";
include_once('modules/Accounts/Account.php');
$account = new Account();
$fields = array();
foreach($account->field_defs as $field_def)
{
	if( ( $field_def['type'] == 'relate' && empty($field_def['custom_type']) ) || $field_def['type'] == 'assigned_user_name' || $field_def['type'] == 'link')
	{
		continue;
	}

 $field_def['vname'] = preg_replace('/:$/','',translate($field_def['vname'],'Accounts'));
 array_push($fields,"{name:'account_".$field_def['name']."',value:'". $field_def['vname']."'}");
}
$field_defs_js .= implode(",\n",$fields);
$field_defs_js .= "]};";
$xtpl->assign("FIELD_DEFS_JS", $field_defs_js );
$xtpl->assign("LBL_CONTACT",$app_list_strings['moduleList']['Contacts']);
$xtpl->assign("LBL_ACCOUNT",$app_list_strings['moduleList']['Accounts']);

$xtpl->assign("OLD_ID", $old_id );
global $current_user;
if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])) {	
	$record = '';
	if(!empty($_REQUEST['record'])) {
		$record = 	$_REQUEST['record'];
	}
	$xtpl->assign("ADMIN_EDIT","<a href='index.php?action=index&module=DynamicLayout&from_action=".$_REQUEST['action'] ."&from_module=".$_REQUEST['module'] ."&record=".$record. "'>".get_image($image_path."EditLayout","border='0' alt='Edit Layout' align='bottom'")."</a>");	
}
if(isset($focus->parent_type) && $focus->parent_type != "") {
	$change_parent_button = "<input title='".$app_strings['LBL_SELECT_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_SELECT_BUTTON_KEY']."' tabindex='3' type='button' class='button' value='".$app_strings['LBL_SELECT_BUTTON_LABEL']."' name='button' LANGUAGE=javascript onclick='return window.open(\"index.php?module=\"+ document.EditView.parent_type.value + \"&action=Popup&html=Popup_picker&form=TasksEditView\",\"test\",\"width=600,height=400,resizable=1,scrollbars=1\");'>";
	$xtpl->assign("CHANGE_PARENT_BUTTON", $change_parent_button);
}
if($focus->parent_type == "Account") $xtpl->assign("DEFAULT_SEARCH", "&query=true&account_id=$focus->parent_id&account_name=".urlencode($focus->parent_name));

$xtpl->assign("DESCRIPTION", $focus->description);
$xtpl->assign("TYPE_OPTIONS", get_select_options_with_id($app_list_strings['record_type_display'], $focus->parent_type));


if(isset($focus->body)) $xtpl->assign("BODY", $focus->body); else $xtpl->assign("BODY", "");
if(isset($focus->body_html)) $xtpl->assign("BODY_HTML", $focus->body_html); else $xtpl->assign("BODY_HTML", "");
if( file_exists("include/FCKeditor/fckeditor.php")) {
  include("include/FCKeditor_Sugar/FCKeditor_Sugar.php") ;
  ob_start();
  $instancename='body_html';
  $oFCKeditor = new FCKeditor_Sugar($instancename) ;
  if( !empty($focus->body_html)) {
    $oFCKeditor->Value = $focus->body_html ;
  }
  $oFCKeditor->Create() ;
  $htmlarea_src =  ob_get_contents();
  $xtpl->assign("HTMLAREA",$htmlarea_src);
  $xtpl->parse("main.htmlarea");
  ob_end_clean();

 echo <<<EOQ
	  <SCRIPT>
	  function insert_variable_html(text) {
	  	var oEditor = FCKeditorAPI.GetInstance('{$instancename}') ;
	  	oEditor.InsertHtml(text);
	  }
	  function insert_variable_html_link(text) {
	  	var oEditor = FCKeditorAPI.GetInstance('{$instancename}') ;
	  	thelink="<a href='" + text + "''" + ">{$mod_strings['LBL_DEFAULT_LINK_TEXT']}</a>";
	  	oEditor.InsertHtml(thelink);
	  }
	  </SCRIPT>
EOQ;
  $xtpl->assign("INSERT_VARIABLE_ONCLICK", "insert_variable_html(document.EditView.variable_text.value)");
  $xtpl->assign("INSERT_URL_ONCLICK", "insert_variable_html_link(document.EditView.tracker_url.value)");

if (!$no_campaign) {
  $campaign_urls=get_campaign_urls($_REQUEST['campaign_id']);
  if (!empty($campaign_urls)) {
  	$xtpl->assign("DEFAULT_URL_TEXT",key($campaign_urls)); 
  }
  $xtpl->assign("TRACKER_KEY_OPTIONS", get_select_options_with_id($campaign_urls, null));
  $xtpl->parse("main.tracker_url");
}

  $xtpl->parse("main.variable_button");
}
else
{
  $xtpl->parse("main.textarea");
}


//Add Custom Fields
require_once('modules/DynamicFields/templates/Files/EditView.php');

$xtpl->parse("main");

$xtpl->out("main");
require_once('include/javascript/javascript.php');
$javascript = new javascript();
$javascript->setFormName('EditView');
$javascript->setSugarBean($focus);
$javascript->addAllFields('');
echo $javascript->getScript();
 
?>
