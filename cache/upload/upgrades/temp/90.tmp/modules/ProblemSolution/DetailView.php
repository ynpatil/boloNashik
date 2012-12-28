<?php
if(empty($GLOBALS['sugarEntry'])) die('Not A Valid Entry Point');
/**
 * The detailed view for a ProblemSolution
 */

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('include/time.php');
require_once('modules/ProblemSolution/ProblemSolution.php');
require_once('include/DetailView/DetailView.php');

global $app_strings;
global $app_list_strings;
global $mod_strings;
global $current_user;
global $theme;

$GLOBALS['log']->info("ProblemSolution detail view");
$theme_path = "themes/$theme/";
$image_path = "{$theme_path}images/";
$focus      = new Solution();

$detailView = new DetailView();
$offset=0;
if (isset($_REQUEST['offset']) or isset($_REQUEST['record'])) {
 $result = $detailView->processSugarBean("PROBLEM_SOLUTION", $focus, $offset);
 if($result == null) {
  sugar_die("Error retrieving record.  You may not be authorized to view this record.");
 }
 $focus=$result;
}else {
 header("Location: index.php?module=Accounts&action=index");
}
echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'],
 $mod_strings['LBL_MODULE_NAME'].": ".$focus->name, true);
echo "\n</p>\n";

require_once("{$theme_path}layout_utils.php");

$xtpl = new XTemplate('modules/ProblemSolution/DetailView.html');

if (isset($_REQUEST['return_module']))  $xtpl->assign('return_module', $_REQUEST['return_module']);
if (isset($_REQUEST['return_action']))  $xtpl->assign('return_action', $_REQUEST['return_action']);
if (isset($_REQUEST['return_id']))      $xtpl->assign('return_id', $_REQUEST['return_id']);
$xtpl->assign('MOD',                $mod_strings);
$xtpl->assign('APP',                $app_strings);
$xtpl->assign('THEME',              $theme);
$xtpl->assign('GRIDLINE',           $gridline);
$xtpl->assign('IMAGE_PATH',         $image_path);
$xtpl->assign('PRINT_URL',          "index.php?".$GLOBALS['request_string']);
$xtpl->assign('id',                 $focus->id);
$xtpl->assign('name',               $focus->name);
$xtpl->assign('assigned_user_name', $focus->assigned_user_name);
$xtpl->assign('status',             $app_list_strings['solution_status_options'][$focus->status]);
//$xtpl->assign('date_due',   $focus->date_due);
//$xtpl->assign('time_due',   $focus->time_due);
//$xtpl->assign('date_start', $focus->date_start);
//$xtpl->assign('time_start', $focus->time_start);
$xtpl->assign('parent_id',          $focus->parent_id);
$xtpl->assign('parent_name',        $focus->parent_name);
$xtpl->assign('priority',           $app_list_strings['solution_priority_options'][$focus->priority]);
$xtpl->assign('solution_number',    $focus->solution_number);
$xtpl->assign('depends_on_id',      $focus->depends_on_id);
$xtpl->assign('depends_on_name',    $focus->depends_on_name);
$xtpl->assign('order_number',       $focus->order_number);

//if(!empty($focus->milestone_flag) && $focus->milestone_flag == 'on')
//{
// $xtpl->assign('milestone_checked', 'checked="checked"');
//}

//$xtpl->assign('estimated_effort', $focus->estimated_effort);
//$xtpl->assign('actual_effort', $focus->actual_effort);
//$xtpl->assign('utilization', $focus->utilization);
//$xtpl->assign('percent_complete', $focus->percent_complete);
$xtpl->assign('description', nl2br(url2html($focus->description)));

if(is_admin($current_user)
 && $_REQUEST['module'] != 'DynamicLayout'
 && !empty($_SESSION['editinplace']))
{
 $xtpl->assign('ADMIN_EDIT',
  "<a href='index.php?action=index&module=DynamicLayout&from_action="
   .$_REQUEST['action']
   ."&from_module=".$_REQUEST['module'] ."&record="
   .$_REQUEST['record']. "'>"
   .get_image($image_path."EditLayout",
    "border='0' alt='Edit Layout' align='bottom'")."</a>");
}

$detailView->processListNavigation($xtpl, "PROBLEM_SOLUTION", $offset, $focus->is_AuditEnabled());
// adding custom fields:
require_once('modules/DynamicFields/templates/Files/DetailView.php');
$xtpl->parse("main.open_source");
$xtpl->assign('TAG', $focus->listviewACLHelper());
$xtpl->parse('main');
$xtpl->out('main');

$sub_xtpl = $xtpl;
$old_contents = ob_get_contents();
ob_end_clean();
ob_start();
echo $old_contents;

require_once('include/SubPanel/SubPanelTiles.php');
$subpanel = new SubPanelTiles($focus, 'ProblemSolution');
echo $subpanel->display();
?>
