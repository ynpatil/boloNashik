<?php 
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/*******************************************************************************
 * The detailed view for a Problem
 ******************************************************************************/

// $Id: DetailView.php,v 1.41.6.1 2006/01/08 04:36:05 majed Exp $

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('include/time.php');
require_once('modules/Problem/Problem.php');
require_once('include/DetailView/DetailView.php');

global $app_strings;
global $mod_strings;
global $theme;
global $current_user;

$GLOBALS['log']->info('Problem detail view');
$focus = new Problem();

// only load a record if a record id is given;
// a record id is not given when viewing in layout editor
$detailView = new DetailView();
$offset     =0;
if (isset($_REQUEST['offset']) or isset($_REQUEST['record'])) {
 $result = $detailView->processSugarBean("PROBLEM", $focus, $offset);
 if($result == null) {
     sugar_die("Error retrieving record.  You may not be authorized to view this record.");
 }
 $focus=$result;
} else {
 header("Location: index.php?module=Accounts&action=index");
}
echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'],
 $mod_strings['LBL_MODULE_NAME'] . ': ' . $focus->name, true);
echo "\n</p>\n";

$theme_path = 'themes/' . $theme . '/';
$image_path = $theme_path . 'images/';

require_once($theme_path.'layout_utils.php');

$xtpl = new XTemplate('modules/Problem/DetailView.html');

/// Assign the template variables
///
$xtpl->assign('MOD', $mod_strings);
$xtpl->assign('APP', $app_strings);
if(isset($_REQUEST['return_module'])){
 $xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
}

if(isset($_REQUEST['return_action'])){
 $xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
}

if(isset($_REQUEST['return_id'])){
 $xtpl->assign("RETURN_ID", $_REQUEST['return_id']);
}

$xtpl->assign('PRINT_URL', "index.php?".$GLOBALS['request_string']);
$xtpl->assign('THEME',                      $theme);
$xtpl->assign('GRIDLINE',                   $gridline);
$xtpl->assign('IMAGE_PATH',                 $image_path);
$xtpl->assign('id',                         $focus->id);
$xtpl->assign('name',                       $focus->name);
$xtpl->assign('assigned_user_name',         $focus->assigned_user_name);
$xtpl->assign('class',                      $focus->class);
$xtpl->assign('status',                     $focus->status);
$xtpl->assign('all_keywords',               $focus->all_keywords);
$xtpl->assign('description', nl2br(url2html($focus->description)));

if(is_admin($current_user)
 && $_REQUEST['module'] != 'DynamicLayout'
 && !empty($_SESSION['editinplace']))
{
 $xtpl->assign('ADMIN_EDIT',
  '<a href="index.php?action=index&module=DynamicLayout&from_action='
  . $_REQUEST['action'] . '&from_module=' . $_REQUEST['module']
  . '&record=' . $_REQUEST['record'] . '">'
  . get_image($image_path . 'EditLayout',
    'border="0" alt="Edit Layout" align="bottom"') . '</a>');
}

$detailView->processListNavigation($xtpl, "PROBLEM", $offset, $focus->is_AuditEnabled());
// adding custom fields
require_once('modules/DynamicFields/templates/Files/DetailView.php');
$xtpl->parse('main.open_source');
$xtpl->assign('TAG', $focus->listviewACLHelper());
$xtpl->parse('main');
$xtpl->out('main');

$sub_xtpl     = $xtpl;
$old_contents = ob_get_contents();
ob_end_clean();
ob_start();
echo $old_contents;

require_once('include/SubPanel/SubPanelTiles.php');
$subpanel = new SubPanelTiles($focus, 'Problem');
echo $subpanel->display();

?>
