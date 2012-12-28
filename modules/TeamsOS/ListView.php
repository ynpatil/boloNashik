<?php

require_once('XTemplate/xtpl.php');
require_once("data/Tracker.php");
require_once('modules/TeamsOS/TeamOS.php');
require_once('themes/'.$theme.'/layout_utils.php');
require_once('log4php/LoggerManager.php');
require_once('include/ListView/ListView.php');
require_once('include/database/PearDatabase.php');

global $app_strings;
global $app_list_strings;
global $current_language;
$current_module_strings = return_module_language($current_language, 'TeamsOS');

global $urlPrefix;

global $currentModule;

global $theme;

global $focus_list;

if (!isset($where)) $where = "";
$seedTeam = new TeamOS();
require_once('modules/MySettings/StoreQuery.php');
$storeQuery = new StoreQuery();
if(!isset($_REQUEST['query'])) {
	$storeQuery->loadQuery($currentModule);
	$storeQuery->populateRequest();
} else {
	$storeQuery->saveFromGet($currentModule);	
}

$category = "";
if(isset($_REQUEST['query'])) {
	$where_clauses = Array();
	
	if(isset($_REQUEST['name']) && $_REQUEST['name'] != "") {
		array_push($where_clauses, "teams.name like '%".PearDatabase::quote($_REQUEST['name'])."%'");
	}
	$where = "";
	foreach($where_clauses as $clause) {
		if($where != "")
		$where .= " and ";
		$where .= $clause;
	}

	$GLOBALS['log']->info("Here is the where clause for the list view: $where");
}

if(!isset($_REQUEST['search_form']) || $_REQUEST['search_form'] != 'false') {
	$search_form=new XTemplate ('modules/TeamsOS/SearchForm.html');
	$search_form->assign("MOD", $current_module_strings);
	$search_form->assign("APP", $app_strings);
	$search_form->assign("IMAGE_PATH", $image_path);
	$search_form->assign("JAVASCRIPT", get_clear_form_js());

	if (isset($_REQUEST['name'])) $search_form->assign("NAME", $_REQUEST['name']);
	if(isset($_REQUEST['$current_user_only'])) $search_form->assign("CURRENT_USER_ONLY", "checked");
	$header_text = '';
	if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])) {
		$header_text = "<a href='index.php?action=index&module=DynamicLayout&from_action=SearchForm&from_module=".$_REQUEST['module'] ."'>".get_image($image_path."EditLayout","border='0' alt='Edit Layout' align='bottom'")."</a>";
	}
	echo get_form_header($current_module_strings['LBL_SEARCH_FORM_TITLE'], $header_text, false);
	$search_form->parse("main");
	$search_form->out("main");
	echo get_form_footer();
	echo "\n<BR>\n";
}

$ListView = new ListView();
$ListView->initNewXTemplate('modules/TeamsOS/ListView.html',$current_module_strings);
$ListView->setHeaderTitle($current_module_strings['LBL_LIST_FORM_TITLE']);
global $current_user;
if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])) {
	$ListView->setHeaderText("<a href='index.php?action=index&module=DynamicLayout&from_action=ListView&from_module=".$_REQUEST['module'] ."'>".get_image($image_path."EditLayout","border='0' alt='Edit Layout' align='bottom'")."</a>" );
}
$ListView->setQuery($where, "", "name", "TEAM");
$ListView->processListView($seedTeam, "main", "TEAM");
?>
