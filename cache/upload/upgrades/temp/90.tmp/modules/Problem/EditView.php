<?php
if(empty($GLOBALS['sugarEntry'])) die('Not A Valid Entry Point');
/*******************************************************************************
 * EditView for Problem
 ******************************************************************************/

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Problem/Problem.php');
require_once('include/time.php');
require_once('include/TimeDate.php');
require_once('modules/Problem/Forms.php');
require_once('include/JSON.php');

$timedate = new TimeDate();

global $app_strings;
global $app_list_strings;
global $current_language;
global $current_user;

$focus = new Problem();

if(!empty($_REQUEST['record'])){
 $focus->retrieve($_REQUEST['record']);
}

echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME'].": ".$focus->name, true);
echo "\n</p>\n";
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$GLOBALS['log']->info("Problem detail view");

$xtpl=new XTemplate ('modules/Problem/EditView.html');

/// Users Popup
$json = new JSON(JSON_LOOSE_TYPE);
$popup_request_data = array(
	'call_back_function'  => 'set_return',
	'form_name'           => 'EditView',
	'field_to_name_array' => array(
		'id'                     => 'assigned_user_id',
		'user_name'              => 'assigned_user_name',
		),
	);
$xtpl->assign('encoded_users_popup_request_data', $json->encode($popup_request_data));

/// Assign the template variables
///
$xtpl->assign('MOD',  $mod_strings);
$xtpl->assign('APP',  $app_strings);
$xtpl->assign('name', $focus->name);

if (!empty($_REQUEST['status'])) {
	$focus->status = $_REQUEST['status'];
}
elseif (empty($focus->status)) {
	$focus->status = $app_list_strings['problem_status_default_key'];
}
if (empty($focus->assigned_user_id) && empty($focus->id))  $focus->assigned_user_id   = $current_user->id;
if (empty($focus->assigned_name)    && empty($focus->id))  $focus->assigned_user_name = $current_user->user_name;
$xtpl->assign("ASSIGNED_USER_OPTIONS", get_select_options_with_id(get_user_array(TRUE, "Active", $focus->assigned_user_id), $focus->assigned_user_id));
$xtpl->assign("ASSIGNED_USER_NAME", $focus->assigned_user_name);
$xtpl->assign("ASSIGNED_USER_ID", $focus->assigned_user_id );

$xtpl->assign('status',       $focus->status);
$xtpl->assign('class',        $focus->class);
$xtpl->assign('all_keywords', $focus->all_keywords);
$xtpl->assign('description',  $focus->description);
$change_parent_button = "<input title='".$app_strings['LBL_SELECT_BUTTON_TITLE']
	."' accessKey='".$app_strings['LBL_SELECT_BUTTON_KEY']
	."' tabindex='2' type='button' class='button' value='"
	.$app_strings['LBL_SELECT_BUTTON_LABEL']
	."' name='button' LANGUAGE=javascript onclick='return window.open(\"index.php?module=\"+ document.EditView.parent_type.value + \"&action=Popup&html=Popup_picker&form=SolutionsEditView\",\"test\",\"width=600,height=400,resizable=1,scrollbars=1\");'>";
$xtpl->assign("CHANGE_PARENT_BUTTON", $change_parent_button);
if (!empty($_REQUEST['opportunity_name']) && empty($focus->name)) {
		$focus->name = $_REQUEST['opportunity_name'];
}
if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
}

$xtpl->assign("PROBLEM_STATUS_OPTIONS", get_select_options_with_id($app_list_strings['problem_status_options'], $focus->status));
if (empty($focus->class)) {
	$xtpl->assign("PROBLEM_CLASS_OPTIONS", get_select_options_with_id($app_list_strings['problem_class_options'], $app_list_strings['problem_class_default_key']));
}
else {
	$xtpl->assign("PROBLEM_CLASS_OPTIONS", get_select_options_with_id($app_list_strings['problem_class_options'], $focus->class));
}

if (isset($_REQUEST['return_module'])) $xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
if (isset($_REQUEST['return_action'])) $xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
if (isset($_REQUEST['return_id'])) $xtpl->assign("RETURN_ID", $_REQUEST['return_id']);
// handle Create $module then Cancel
if (empty($_REQUEST['return_id'])) {
	$xtpl->assign("RETURN_ACTION", 'index');
}
require_once('include/QuickSearchDefaults.php');
$sqs_objects = array('assigned_user_name' => $qsUser,
					'team_name' => $qsTeam);
$quicksearch_js = $qsScripts;
$quicksearch_js .= '<script type="text/javascript" language="javascript">sqs_objects = ' . $json->encode($sqs_objects) . '</script>';

$xtpl->assign("JAVASCRIPT", get_set_focus_js().get_validate_record_js() . $quicksearch_js);
$xtpl->assign("THEME", $theme);
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "index.php?".$GLOBALS['request_string']);
$xtpl->assign("ID", $focus->id);
$xtpl->assign("NAME", $focus->name);

//Add Custom Fields
require_once('modules/DynamicFields/templates/Files/EditView.php');

global $current_user;
if(is_admin($current_user)
	&& $_REQUEST['module'] != 'DynamicLayout'
	&& !empty($_SESSION['editinplace']))
{
	$record = '';
	if(!empty($_REQUEST['record']))
	{
		$record = 	$_REQUEST['record'];
	}

	$xtpl->assign("ADMIN_EDIT","<a href='index.php?action=index&module=DynamicLayout&from_action="
		.$_REQUEST['action'] ."&from_module=".$_REQUEST['module']
		."&record=".$record. "'>".get_image($image_path
		."EditLayout","border='0' alt='Edit Layout' align='bottom'")."</a>");	
}

$xtpl->parse("main.open_source");
$xtpl->parse("main");
$xtpl->out("main");
require_once('include/javascript/javascript.php');
$javascript = new javascript();
$javascript->setFormName('EditView');
$javascript->setSugarBean($focus);
$javascript->addAllFields('');
$javascript->addToValidateBinaryDependency('assigned_user_name', 'alpha', $app_strings['ERR_SQS_NO_MATCH_FIELD'] . $app_strings['LBL_ASSIGNED_TO'], 'false', '', 'assigned_user_id');

echo $javascript->getScript();

?>
