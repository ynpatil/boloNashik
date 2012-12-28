<?php

global $theme_path;
global $image_path;
$GLOBALS['displayListView'] = true;

require_once('XTemplate/xtpl.php');
require_once("data/Tracker.php");
require_once('modules/Forums/Forum.php');
if (!defined('THEMEPATH'))
  define('THEMEPATH', $theme_path);
require_once(THEMEPATH.'layout_utils.php');

require_once('include/ListView/ListView.php');
require_once('modules/ForumTopics/ForumTopic.php');

if(!ACLController::checkAccess('Forums', 'list', true)){
    ACLController::displayNoAccess(false);
    sugar_cleanup(true);
}

global $app_strings;
global $app_list_strings;
global $current_language;
$current_module_strings = return_module_language($current_language, 'Forums');
echo get_form_header($current_module_strings['LBL_SEARCH_FORM_TITLE'], "", false);
$xtpl=new XTemplate ('modules/Forums/ForumsSearch.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("THEME", $theme);
$xtpl->assign("IMAGE_PATH", $image_path); $xtpl->assign("PRINT_URL", "index.php?".$GLOBALS['request_string']);
$xtpl->assign("QUERY_STRING_USER", get_select_options_with_id(get_user_array(TRUE), ''));
$xtpl->parse("main");
$xtpl->out("main");

global $urlPrefix;

$where_clauses = Array();

global $currentModule;

if (!isset($where))
  $where = "";

$forumForQuery = new Forum();

global $current_user;

//BEGIN: everything used to seperate forums into categories

$forumListQuery = getForumListQuery($forumForQuery);
//echo $forumListQuery."<BR>";

$result_set = $GLOBALS['db']->query($forumListQuery);

//first we pull all the rows into $rowarr and then sort them by category_ranking
$rowarr = array();
while($row = $GLOBALS['db']->fetchByAssoc($result_set))
  $rowarr[] = $row;

// if this returns true, then there are no forums
if(count($rowarr) < 1)
{
  print("<BR>".$mod_strings['LBL_NO_FORUMS']);
}
else
{
  $team_where = "";



  usort($rowarr, "sortByCategory");
  
  // now we display per category
  foreach($rowarr as $row)
  {
    $where_backup = $where;
    array_push($where_clauses, "forums.category = '".$GLOBALS['db']->quote($row['category'])."'");
  	foreach($where_clauses as $clause)
  	{
  		if($where != "")
  		$where .= " and ";
  		$where .= $clause;
  	}

    $where .= $team_where;


  //BEGIN: standard list view procedure
    $ListView = new ListView();
  
    $ListView->initNewXTemplate('modules/Forums/ListView.html',$current_module_strings);
  
    $ListView->setHeaderTitle($row['category']);

    if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){
    	$ListView->setHeaderText("<a href='index.php?action=index&module=DynamicLayout&from_action=ListView&from_module=".$_REQUEST['module'] ."'>".get_image($image_path."EditLayout","border='0' alt='Edit Layout' align='bottom'")."</a>" );
    }
    $ListView->show_mass_update = false;
    $ListView->show_mass_update_form = false;
    $ListView->records_per_page = 10000;
    $ListView->show_paging = false;
    $ListView->show_export_button = false;
    $ListView->setQuery($where, "", "", "FORUM");
    $ListView->processListView($forumForQuery, "main", "FORUM");
  //END: standard list view procedure
  
    array_pop($where_clauses);  
    $where = $where_backup;
  
  }
  //END: everything used to seperate forums into categories
}


function getForumListQuery($bean)
{



  $forumListQuery =   "select category ".
                      "from forums ".
                      "where deleted=0 ".



                      "group by category ";
  
  return $forumListQuery;
}



function sortByCategory($a, $b)
{
  $a_rank = ForumTopic::get_order($a['category']);
  $b_rank = ForumTopic::get_order($b['category']);
  
  if($a_rank == $b_rank)
    return 0;
  
  return ($a_rank < $b_rank) ? -1 : 1;
}

?>
