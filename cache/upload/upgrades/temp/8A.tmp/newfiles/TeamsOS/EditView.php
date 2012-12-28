<?php
require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/TeamsOS/TeamOS.php');
require_once('modules/TeamsOS/Forms.php');
require_once('include/JSON.php');

global $app_strings;
global $app_list_strings;
global $mod_strings;

$focus = new TeamOS();

if (!is_admin($current_user) && $_REQUEST['record'] != $current_user->id) sugar_die("Unauthorized access to administration.");

if(isset($_REQUEST['record'])) {
    $focus->retrieve($_REQUEST['record']);
}
if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
	$focus->name = "";
}
global $theme;
$theme_path='themes/'.$theme.'/';
$image_path=$theme_path.'images/';
include_once($theme_path.'layout_utils.php');

echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME'] .": ". $focus->name, true);
echo "\n</p>\n";

$GLOBALS['log']->info('Team edit view');
$xtpl=new XTemplate ('modules/TeamsOS/EditView.html');
$xtpl->assign('MOD', $mod_strings);
$xtpl->assign('APP', $app_strings);

if (isset($_REQUEST['error_string'])) $xtpl->assign('ERROR_STRING', '<span class="error">Error: '.$_REQUEST['error_string'].'</span>');
if (isset($_REQUEST['return_module'])) $xtpl->assign('RETURN_MODULE', $_REQUEST['return_module']);
if (isset($_REQUEST['return_action'])) $xtpl->assign('RETURN_ACTION', $_REQUEST['return_action']);
if (isset($_REQUEST['return_id'])) $xtpl->assign('RETURN_ID', $_REQUEST['return_id']);
else { $xtpl->assign('RETURN_ACTION', 'ListView'); }

$xtpl->assign('ID', $focus->id);

require_once($theme_path.'config.php');

require_once('modules/DynamicFields/templates/Files/EditView.php');
if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){
	$record = '';
	if(!empty($_REQUEST['record'])){
		$record = 	$_REQUEST['record'];
	}
	$xtpl->assign("ADMIN_EDIT","<a href='index.php?action=index&module=DynamicLayout&from_action=".$_REQUEST['action'] ."&from_module=".$_REQUEST['module'] ."&record=".$record. "'>".get_image($image_path."EditLayout","border='0' alt='Edit Layout' align='bottom'")."</a>");
}

$xtpl->assign("THEME", $theme);

require_once('include/QuickSearchDefaults.php');

$xtpl->assign('Teams', $focus->get_xtemplate_data());
$xtpl->assign("NAME", $focus->name);
if($focus->private==1) {
	$xtpl->assign('PRIVATE','CHECKED');
}
$xtpl->parse("main");
$xtpl->out("main");
require_once('include/javascript/javascript.php');
$javascript = new javascript();
$javascript->setFormName('EditView');
$javascript->setSugarBean($focus);
$javascript->addAllFields('');
echo $javascript->getScript();
?>
