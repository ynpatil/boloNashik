 
<?php
require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/TeamsOS/TeamOS.php');
require_once('include/TimeDate.php');
require_once('include/DetailView/DetailView.php');

$timedate = new TimeDate();
global $mod_strings;
global $app_strings;
global $app_list_strings;
global $gridline;
$focus = new TeamOS();

$detailView = new DetailView();
$offset=0;
if (isset($_REQUEST['offset']) or isset($_REQUEST['record'])) {
	$result = $detailView->processSugarBean("TEAM", $focus, $offset);
	if($result == null) {
	    sugar_die("Error retrieving record.  You may not be authorized to view this record.");
	}
	$focus=$result;
} else {
	header("Location: index.php?module=TeamsOS&action=index");
}

if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = $_GET["record"];
}
echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME'].": ".$focus->name, true);
echo "\n</p>\n";
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$log->info("Team detail view");

$xtpl=new XTemplate ('modules/TeamsOS/DetailView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("THEME", $theme);
$xtpl->assign("GRIDLINE", $gridline);
$xtpl->assign("ID", $focus->id);
$xtpl->assign("NAME", $focus->name);
if($focus->private==1) {
	$xtpl->assign('PRIVATE','CHECKED');
}
$xtpl->assign("PRINT_URL", "index.php?".$GLOBALS['request_string']);
$xtpl->assign("Team", $focus->get_xtemplate_data());
global $current_user;
if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){
	$xtpl->assign("ADMIN_EDIT","<a href='index.php?action=index&module=DynamicLayout&from_action=".$_REQUEST['action'] ."&from_module=".$_REQUEST['module'] ."&record=".$_REQUEST['record']. "'>".get_image($image_path."EditLayout","border='0' alt='Edit Layout' align='bottom'")."</a>");
}

$detailView->processListNavigation($xtpl, "TEAM", $offset);

$xtpl->parse("main");
$xtpl->out("main");

require_once('include/SubPanel/SubPanelTiles.php');
$subpanel = new SubPanelTiles($focus, 'TeamsOS');
echo $subpanel->display();

echo $focus->get_all_members($focus->id);
?>
