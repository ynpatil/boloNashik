<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

/**
 * Display of ListView for Solution
 */

global $current_language;
global $app_strings;

include('include/QuickSearchDefaults.php');
echo $qsScripts;

require_once('XTemplate/xtpl.php');
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/ListView/ListView.php');
require_once('log4php/LoggerManager.php');
require_once('include/modules.php');
require_once('modules/ProblemSolution/ProblemSolution.php');

if (!isset($where)) $where = '';
require_once('modules/MySettings/StoreQuery.php');
$storeQuery = new StoreQuery();
if($_REQUEST['action'] == 'index')
{
 if(!isset($_REQUEST['query'])){
  $storeQuery->loadQuery($currentModule);
  $storeQuery->populateRequest();
 }else{
  $storeQuery->saveFromGet($currentModule); 
 }
}
$seedSolution = new Solution();

if(isset($_REQUEST['query']))
{
 // we have a query
 $where_clauses = array();
 if(isset($_REQUEST['name'])) {
  $name = $_REQUEST['name'];
  array_push($where_clauses, "problem_solution.name LIKE '"
   .PearDatabase::quote($name)."%'");
 }
 if(isset($_REQUEST['parent_name'])){
  $parent_name = $_REQUEST['parent_name'];
  array_push($where_clauses,
   "problem.name LIKE '$parent_name%'");
 }

 if(isset($_REQUEST['current_user_only'])){
  $current_user_only = $_REQUEST['current_user_only'];
  array_push($where_clauses, "problem_solution.assigned_user_id='$current_user->id'");
 }
 $seedSolution->custom_fields->setWhereClauses($where_clauses);
 foreach($where_clauses as $clause){
  if($where != ''){
   $where .= ' AND ';
  }
  $where .= $clause;
 }
 $GLOBALS['log']->info("Here is the where clause for the list view: $where");
}

$search_form = new XTemplate('modules/ProblemSolution/SearchForm.html');
$mod_strings = return_module_language($current_language, 'ProblemSolution');

// the title label and arrow pointing to the module search form
$header_text = '';
if(is_admin($current_user)
 && $_REQUEST['module'] != 'DynamicLayout'
 && !empty($_SESSION['editinplace']))
{
 $header_text = "<a href='index.php?action=index&module=DynamicLayout&from_action=SearchForm&from_module="
  .$_REQUEST['module'] ."'>"
  .get_image($image_path."EditLayout","border='0' alt='Edit Layout' align='bottom'")
  ."</a>";
}
$header = get_form_header($mod_strings['LBL_SEARCH_FORM_TITLE'], $header_text, false);
$search_form->assign('header',  $header);
$search_form->assign('mod',     $mod_strings);
$search_form->assign('app',     $app_strings);
$search_form->assign("JAVASCRIPT", get_clear_form_js());

/// keep the old values of the search form
if(isset($current_user_only)){
 $search_form->assign('CURRENT_USER_ONLY', 'checked="checked"');
}

if(isset($name)){
 $search_form->assign('name', $name);
}

if(isset($parent_name)){
 $search_form->assign('parent_name', $parent_name);
}

/// take care of output of search form
$search_form->parse('main');
$search_form->out('main');

/// take care of output of rest of listview
$theme_path    = "themes/$theme";
$img_path      = "$theme_path/images";
$seed_solution = new Solution();
$where_clause  = '';
$listview      = new ListView();
$listview->initNewXTemplate('modules/ProblemSolution/ListView.html', $mod_strings);
$listview->setHeaderTitle($mod_strings['LBL_LIST_FORM_TITLE']);

if(is_admin($current_user)
 && $_REQUEST['module'] != 'DynamicLayout'
 && !empty($_SESSION['editinplace']))
{
 $listview->setHeaderText("<a href='index.php?action=index&module=DynamicLayout&from_action=ListView&from_module="
  .$_REQUEST['module'] ."'>"
  .get_image($image_path."EditLayout","border='0' alt='Edit Layout' align='bottom'")
  ."</a>" );
}

$listview->setQuery($where, '', 'name', 'PROBLEM_SOLUTION');
$listview->setAdditionalDetails();
$listview->processListView($seed_solution,  'main', 'PROBLEM_SOLUTION');

?>
