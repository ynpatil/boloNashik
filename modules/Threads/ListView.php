<?php

require_once('XTemplate/xtpl.php');
require_once("data/Tracker.php");
require_once('modules/Threads/Thread.php');
require_once('themes/'.$theme.'/layout_utils.php');

require_once('include/ListView/ListView.php');

if(!ACLController::checkAccess('Threads', 'list', true)){
    ACLController::displayNoAccess(false);
    sugar_cleanup(true);
}

global $app_strings;
global $app_list_strings;
global $current_language;
$current_module_strings = return_module_language($current_language, 'Threads');

global $urlPrefix;

global $currentModule;

global $theme;

if (!isset($where))
  $where = "";

$seedThread =& new Thread();
require_once('modules/MySettings/StoreQuery.php');
$storeQuery = new StoreQuery();
if(!isset($_REQUEST['query'])){
	$storeQuery->loadQuery($currentModule);
	$storeQuery->populateRequest();
}else{
	$storeQuery->saveFromGet($currentModule);
}
if(isset($_REQUEST['query']))
{
	// we have a query
	if (isset($_REQUEST['title']))
      $title = $_REQUEST['title'];

	if (isset($_REQUEST['body']))
      $body = $_REQUEST['body'];
    
	$where_clauses = Array();

	if(isset($title) && $title != "")
      array_push($where_clauses, "threads.title like '".$GLOBALS['db']->quote($title)."%'");

	if(isset($body) && $body != "")
      array_push($where_clauses, "threads.body like '".$GLOBALS['db']->quote($body)."%'"); 

	$seedThread->custom_fields->setWhereClauses($where_clauses);

	$where = "";
	foreach($where_clauses as $clause)
	{
		if($where != "")
		$where .= " and ";
		$where .= $clause;
	}
}

if (!isset($_REQUEST['search_form']) || $_REQUEST['search_form'] != 'false') {
	// Stick the form header out there.
	$search_form=new XTemplate ('modules/Threads/SearchForm.html');
	$search_form->assign("MOD", $current_module_strings);
	$search_form->assign("APP", $app_strings);
	$search_form->assign("IMAGE_PATH", $image_path);
	$search_form->assign("ADVANCED_SEARCH_PNG", get_image($image_path.'advanced_search','alt="'.$app_strings['LNK_ADVANCED_SEARCH'].'"  border="0"'));
	$search_form->assign("BASIC_SEARCH_PNG", get_image($image_path.'basic_search','alt="'.$app_strings['LNK_BASIC_SEARCH'].'"  border="0"'));
	if(isset($title)) $search_form->assign("TITLE", $title);
	if(isset($body)) $search_form->assign("BODY", $body);

	$search_form->assign("JAVASCRIPT", get_clear_form_js());
	$header_text = '';

  // 01-14-2006 -- added condition to remove search form for 'include listview directly from forums'	
  if(!isset($_REQUEST['record']))
  	echo get_form_header($current_module_strings['LBL_SEARCH_FORM_TITLE'], $header_text, false);

	if (isset($_REQUEST['advanced']) && $_REQUEST['advanced'] == 'true')
    {
		$seedThread->custom_fields->populateXTPL($search_form, 'search' );
        
        // 01-14-2006 -- added condition to remove search form for 'include listview directly from forums'	
        if(!isset($_REQUEST['record']))
        {
		  $search_form->parse("advanced");
		  $search_form->out("advanced");
		}
	}
	else 
	{
        // adding custom fields:
        $seedThread->custom_fields->populateXTPL($search_form, 'search' );

        // 01-14-2006 -- added condition to remove search form for 'include listview directly from forums'	
        if(!isset($_REQUEST['record']))
        {
  		  $search_form->parse("main");
	  	  $search_form->out("main");
	    }
	}
	
	
	echo get_form_footer();
	echo "\n<BR>\n";
}

$ListView = new ListView();

$ListView->show_export_button = false;

$ListView->initNewXTemplate( 'modules/Threads/ListView.html',$current_module_strings);
$ListView->setHeaderTitle($current_module_strings['LBL_LIST_FORM_TITLE']);

global $current_user;
if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){
	$ListView->setHeaderText("<a href='index.php?action=index&module=DynamicLayout&from_action=ListView&from_module=".$_REQUEST['module'] ."'>".get_image($image_path."EditLayout","border='0' alt='Edit Layout' align='bottom'")."</a>" );
}
$ListView->setQuery($where, "", "", "THREAD");
if(!isset($_REQUEST['record']))
	$ListView->processListView($seedThread, "main_thread_list", "THREAD");
else
	$ListView->processListView($seedThread, "main_for_forum", "THREAD");
?>
