<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('XTemplate/xtpl.php');
require_once("data/Tracker.php");
require_once('modules/Tags/Tag.php');
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/ListView/ListViewSmarty.php');

if(file_exists('custom/modules/Tags/metadata/listviewdefs.php')) 
{
  require_once('custom/modules/Tags/metadata/listviewdefs.php');
}
else
{
  require_once('modules/Tags/metadata/listviewdefs.php');
}

require_once('modules/SavedSearch/SavedSearch.php');
require_once('include/SearchForm/SearchForm.php');

global $app_strings;
global $app_list_strings;
global $current_language;
global $urlPrefix;
global $currentModule;
global $theme;
global $current_user;
global $focus_list;

$current_module_strings = 
  return_module_language($current_language, $currentModule);

require_once('include/QuickSearchDefaults.php');
$qsd = new QuickSearchDefaults();

if( !empty($_REQUEST['clear_query']) && $_REQUEST['clear_query'] == 'true')  
{
  $current_user->setPreference('DisplayColumns', array(), 0, $currentModule);
}

$savedDisplayColumns = 
  $current_user->getPreference('DisplayColumns', $currentModule);

$json   = getJSONobj();
$tag    = new Tag();
$search = new SearchForm($currentModule, $tag);

$listViewSmarty = new ListViewSmarty();

$displayColumns = array();

if(!empty($_REQUEST['displayColumns'])) 
{
  foreach(explode('|', $_REQUEST['displayColumns']) as $num => $col) 
  {
    if(!empty($listViewDefs[$currentModule][$col])) 
    {
      $displayColumns[$col] = $listViewDefs[$currentModule][$col];
    }    
  }
}
elseif(!empty($savedDisplayColumns)) 
{
  $displayColumns = $savedDisplayColumns;
}
else 
{
  foreach($listViewDefs[$currentModule] as $col => $params) 
  {
    if(!empty($params['default']) && $params['default'])
    {
      $displayColumns[$col] = $params;
    }
  }
} 

$params = array ();

if(!empty($_REQUEST['orderBy'])) 
{
  $params['orderBy'] = $_REQUEST['orderBy']; // what about fake db fields?
  $params['overrideOrder'] = true;

  if(!empty($_REQUEST['sortOrder'])) 
  {
    $params['sortOrder'] = $_REQUEST['sortOrder']; 
  }
}

$listViewSmarty->displayColumns  = $displayColumns;
$listViewSmarty->export          = false;
$listViewSmarty->mailMerge       = false;
$listViewSmarty->multiSelect     = false;
$listViewSmarty->mergeduplicates = false;

$user_list = get_user_array_forassign(FALSE);
$other_user_list = getOtherUserIfAny(NULL,$seedCall->module_dir);
$user_list = array_merge($user_list,$other_user_list);

if(!empty($_REQUEST['search_form_only']) && $_REQUEST['search_form_only']) 
{
  switch($_REQUEST['search_form_view']) 
  {
    case 'basic_search':
      $search->setup($user_list);
      $search->displayBasic(false);
      break;
    case 'advanced_search':
      $search->setup($user_list);
      $search->displayAdvanced(false);
      break;
    case 'saved_views':
      echo $search->displaySavedViews($listViewDefs, $listViewSmarty, false);
      break;
  }
  return;
}

if(!isset($where)) 
{
  $where = "";
}

require_once('modules/MySettings/StoreQuery.php');
$storeQuery = new StoreQuery();

if(!isset($_REQUEST['query']))
{
  $storeQuery->loadQuery($currentModule);
  $storeQuery->populateRequest();
}
else
{
  $storeQuery->saveFromGet($currentModule);   
}

if(isset($_REQUEST['query']))
{
  $current_user->setPreference('DisplayColumns', 
                               $displayColumns, 
                               0, 
                               $currentModule); 

  if(  !empty($_SERVER['HTTP_REFERER']) 
    && preg_match('/action=EditView/', $_SERVER['HTTP_REFERER'])) 
  {
    $search->populateFromArray($storeQuery->query);
  }
  else 
  {
    $search->populateFromRequest();
  } 

  $where_clauses = $search->generateSearchWhere(true, $currentModule);

  $where = "";

  if(count($where_clauses) > 0)
  {
    $where= implode(' and ', $where_clauses);
  }

  $GLOBALS['log']->info("Here is the where clause for the list view: $where");
}

if(!isset($_REQUEST['search_form']) || $_REQUEST['search_form'] != 'false') 
{
  $search->setup($user_list);

  if(  isset($_REQUEST['searchFormTab']) 
    && $_REQUEST['searchFormTab'] == 'advanced_search') 
  {
    $search->displayAdvanced();
  }
  elseif(  isset($_REQUEST['searchFormTab']) 
        && $_REQUEST['searchFormTab'] == 'saved_views')
  {
    $search->displaySavedViews($listViewDefs, $listViewSmarty);
  }
  else 
  {
    $search->displayBasic();
  }
}

echo $qsd->GetQSScripts();
$listViewSmarty->setup($tag, 'include/ListView/ListViewGeneric.tpl', $where, $params);

// display 
$savedSearchName = empty($_REQUEST['saved_search_select_name']) 
    ? '' : (' - ' . $_REQUEST['saved_search_select_name']);

echo get_form_header($current_module_strings['LBL_LIST_FORM_TITLE'] . $savedSearchName, '', false);
echo $listViewSmarty->display();

$savedSearch = new SavedSearch();

$json = getJSONobj();

$savedSearchSelects = $json->encode(array($GLOBALS['app_strings']['LBL_SAVED_SEARCH_SHORTCUT'] . '<br>' . $savedSearch->getSelect($currentModule)));

$str = "
<script>
YAHOO.util.Event.addListener(window, 'load', SUGAR.util.fillShortcuts, $savedSearchSelects);
</script>";

echo $str;
?>
