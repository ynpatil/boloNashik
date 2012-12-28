<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

/*******************************************************************************
 * Display of ListView for Problem
 ******************************************************************************/

require_once('XTemplate/xtpl.php');
require_once('themes/' . $theme . '/layout_utils.php');
require_once('include/ListView/ListView.php');
require_once('log4php/LoggerManager.php');
require_once('include/modules.php');
require_once('modules/Problem/Problem.php');

global $current_language;
global $app_strings;

//include('include/QuickSearchDefaults.php');
//echo $qsScripts;

$mod_strings = return_module_language($current_language, 'Problem');

if (!isset($where)) $where = '';
$seedProblem = new Problem();
require_once('modules/MySettings/StoreQuery.php');
$storeQuery = new StoreQuery();
$advanced = false;
if($_REQUEST['action'] == 'index'){
 $advanced = true;
}

if($_REQUEST['action'] == 'index'){
	if(!isset($_REQUEST['query'])){
		$storeQuery->loadQuery($currentModule);
		$storeQuery->populateRequest();
	}else{
		$storeQuery->saveFromGet($currentModule);	
	}
}
//$seedProblem = new Problem();
require_once('modules/MySettings/StoreQuery.php');
$storeQuery = new StoreQuery();
if(!isset($_REQUEST['query'])){
	$storeQuery->loadQuery($currentModule);
	$storeQuery->populateRequest();
}else{
	$storeQuery->saveFromGet($currentModule);	
}

// Save associate Case ID
	if (isset($_REQUEST['asociate_id']))       $associate_id      = $_REQUEST['associate_id'];

if(isset($_REQUEST['query'])){
	// we have a query
	// get values from form to add where clauses
	if (isset($_REQUEST['name']))              $name              = $_REQUEST['name'];
	if (isset($_REQUEST['current_user_only'])) $current_user_only = $_REQUEST['current_user_only'];
	if (isset($_REQUEST['status']))            $status            = $_REQUEST['status'];
	if (isset($_REQUEST['class']))             $class             = $_REQUEST['class'];

	if (isset($_REQUEST['keyword1']))          $keyword1          = $_REQUEST['keyword1'];
	if (isset($_REQUEST['keyword2']))          $keyword2          = $_REQUEST['keyword2'];
	if (isset($_REQUEST['keyword3']))          $keyword3          = $_REQUEST['keyword3'];
	if (isset($_REQUEST['keyword4']))          $keyword4          = $_REQUEST['keyword4'];

	$where_clauses = array();
	$where_clauses_or = array();

	if(isset($name)              && $name != "")              array_push($where_clauses, "problem.name like '".PearDatabase::quote($name)."%'");
	if(isset($current_user_only) && $current_user_only != "") array_push($where_clauses, "problem.assigned_user_id='$current_user->id'");
	if(isset($status)            && $status != "")            array_push($where_clauses, "problem.status          = '".PearDatabase::quote($status)."'");
 	
	if(isset($class)             && $class  != "")
        array_push($where_clauses, "problem.class           = '".PearDatabase::quote($class) ."'");

	if((isset($keyword1)         && $keyword1 != "") OR
	   (isset($keyword2)         && $keyword2 != "") OR
	   (isset($keyword3)         && $keyword3 != "") OR
	   (isset($keyword4)         && $keyword4 != "")   ){
	
	$theseKeywords = "";
 	
	if(isset($keyword1)         && $keyword1 != "") array_push($where_clauses_or, "MATCH (problem.name,problem.description) AGAINST ('".$keyword1."' IN BOOLEAN MODE)");
 	if(isset($keyword2)         && $keyword2 != "") array_push($where_clauses_or, "MATCH (problem.name,problem.description) AGAINST ('".$keyword2."' IN BOOLEAN MODE)");
 	if(isset($keyword3)         && $keyword3 != "") array_push($where_clauses_or, "MATCH (problem.name,problem.description) AGAINST ('".$keyword3."' IN BOOLEAN MODE)");
 	if(isset($keyword4)         && $keyword4 != "") array_push($where_clauses_or, "MATCH (problem.name,problem.description) AGAINST ('".$keyword4."' IN BOOLEAN MODE)");
 	}
 	
	$seedProblem->custom_fields->setWhereClauses($where_clauses);

	$where = '';
	foreach($where_clauses as $clause){
		if($where != '')
		$where .= ' AND ';
		$where .= $clause;
	}

	foreach($where_clauses_or as $clause){
		if($where != '')
		$where .= ' OR ';
		$where .= $clause;
	}
		
	if (isset($assigned_user_id) && is_array($assigned_user_id)){
		$count = count($assigned_user_id);
		if ($count > 0 ) {
			if (!empty($where)) {
				$where .= " AND ";
			}
			$where .= "problem.assigned_user_id IN(";
			foreach ($assigned_user_id as $key => $val) {
				$where .= "'$val'";
				$where .= ($key == count($assigned_user_id) - 1) ? ")" : ", ";
			}
		}
	}

	$GLOBALS['log']->info("Here is the where clause for the list view: $where");
}

$seed_problem = new Problem();
if (!isset($_REQUEST['search_form']) || $_REQUEST['search_form'] != 'false') {
	$search_form = new XTemplate('modules/Problem/SearchForm.html');
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

	$search_form->assign('header', $header);
	$search_form->assign('MOD',    $mod_strings);
	$search_form->assign('APP',    $app_strings);
	$search_form->assign("ADVANCED_SEARCH_PNG", get_image($image_path.'advanced_search','alt="'.$app_strings['LNK_ADVANCED_SEARCH'].'"  border="0"'));
	$search_form->assign("BASIC_SEARCH_PNG",    get_image($image_path.'basic_search','alt="'.$app_strings['LNK_BASIC_SEARCH'].'"  border="0"'));
 $search_form->assign("JAVASCRIPT",          get_clear_form_js());

	$search_form->assign('associate_id', $associate_id);
 
	if(isset($current_user_only))
	{
		$search_form->assign('CURRENT_USER_ONLY', 'checked="checked"');
	}
	$name = isset($_REQUEST['name']) ? $_REQUEST['name'] : '';
	$search_form->assign('name', $name);

	$problem_status_options = $app_list_strings['problem_status_options'];
	array_unshift($problem_status_options, '');
	if (isset($status)) $search_form->assign("PROBLEM_STATUS_OPTIONS", get_select_options_with_id($problem_status_options, $status));
	else $search_form->assign("PROBLEM_STATUS_OPTIONS", get_select_options_with_id($problem_status_options, ''));

	$problem_class_options = $app_list_strings['problem_class_options'];
	array_unshift($problem_class_options, '');
	if (isset($class)) $search_form->assign("PROBLEM_CLASS_OPTIONS", get_select_options_with_id($problem_class_options, $class));
	else $search_form->assign("PROBLEM_CLASS_OPTIONS", get_select_options_with_id($problem_class_options, ''));

	if (isset($_REQUEST['advanced']) && $_REQUEST['advanced'] == 'true') {
  $search_form->assign("KEYWORD1", $keyword1);
  $search_form->assign("KEYWORD2", $keyword2);
  $search_form->assign("KEYWORD3", $keyword3);
  $search_form->assign("KEYWORD4", $keyword4);
  
/*
		$problem_status_options = $app_list_strings['problem_status_options'];
		array_unshift($problem_status_options, '');
		if (isset($status)) $search_form->assign("PROBLEM_STATUS_OPTIONS", get_select_options_with_id($problem_status_options, $status));
		else $search_form->assign("PROBLEM_STATUS_OPTIONS", get_select_options_with_id($problem_status_options, ''));

		$problem_class_options = $app_list_strings['problem_class_options'];
		array_unshift($problem_class_options, '');
		if (isset($class)) $search_form->assign("PROBLEM_CLASS_OPTIONS", get_select_options_with_id($problem_class_options, $class));
		else $search_form->assign("PROBLEM_CLASS_OPTIONS", get_select_options_with_id($problem_class_options, ''));

		if (!empty($assigned_user_id)) $search_form->assign("USER_FILTER", get_select_options_with_id(get_user_array(FALSE), $assigned_user_id));
		else $search_form->assign("USER_FILTER", get_select_options_with_id(get_user_array(FALSE), ''));
*/

		 // adding custom fields:
		$seedProblem->custom_fields->populateXTPL($search_form, 'search' );

		$search_form->parse("advanced");
		$search_form->out("advanced");
	}else {
  // adding custom fields:
 	$seedProblem->custom_fields->populateXTPL($search_form, 'search' );
 	$search_form->parse('main');
	 $search_form->out('main');
 }
}

$theme_path = "themes/$theme";
$img_path   = "$theme_path/images";

$listview = new ListView();
$listview->initNewXTemplate('modules/Problem/ListView.html', $mod_strings);
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

$listview->setQuery($where, '', 'name', 'PROBLEM');
$listview->setAdditionalDetails();
$listview->processListView($seed_problem,  'main', 'PROBLEM');

?>
