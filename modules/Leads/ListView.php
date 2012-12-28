<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version
 * 1.1.3 ("License"); You may not use this file except in compliance with the
 * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an "AS IS" basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied.  See the License
 * for the specific language governing rights and limitations under the
 * License.
 *
 * All copies of the Covered Code must include on each user interface screen:
 *    (i) the "Powered by SugarCRM" logo and
 *    (ii) the SugarCRM copyright notice
 * in the same form as they appear in the distribution.  See full license for
 * requirements.
 *
 * The Original Code is: SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) 2004-2006 SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/
/*********************************************************************************
 * $Id: ListView.php,v 1.57 2006/08/03 00:09:49 wayne Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once("data/Tracker.php");
require_once('modules/Leads/Lead.php');
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/ListView/ListView.php');
require_once('modules/CustomFields/CustomFields.php');
require_once('include/ListView/ListViewSmarty.php');
if(file_exists('custom/modules/Leads/metadata/listviewdefs.php')){
	require_once('custom/modules/Leads/metadata/listviewdefs.php');	
}else{
	require_once('modules/Leads/metadata/listviewdefs.php');
}
require_once('modules/SavedSearch/SavedSearch.php');
require_once('include/SearchForm/SearchForm.php');

$custom_fields = new CustomFields();

global $app_strings;
global $app_list_strings;
global $current_language;
$current_module_strings = return_module_language($current_language, 'Leads');

global $urlPrefix;

$header_text = '';
global $currentModule;

global $theme;

require_once('include/QuickSearchDefaults.php');
$qsd = new QuickSearchDefaults();

$json = getJSONobj();

$seedLeads = new Lead();
$searchForm = new SearchForm('Leads', $seedLeads);

// setup listview smarty
$lv = new ListViewSmarty();

$displayColumns = array();
if(!empty($_REQUEST['displayColumns'])) {
    foreach(explode('|', $_REQUEST['displayColumns']) as $num => $col) {
        if(!empty($listViewDefs['Leads'][$col])) 
            $displayColumns[$col] = $listViewDefs['Leads'][$col];
    }    
}
else {
    foreach($listViewDefs['Leads'] as $col => $params) {
        if(!empty($params['default']) && $params['default'])
            $displayColumns[$col] = $params;
    }
} 
$params = array('massupdate' => true);
if(!empty($_REQUEST['orderBy'])) {
    $params['orderBy'] = $_REQUEST['orderBy'];
    $params['overrideOrder'] = true;
    if(!empty($_REQUEST['sortOrder'])) $params['sortOrder'] = $_REQUEST['sortOrder'];
}

$lv->displayColumns = $displayColumns;

if(!empty($_REQUEST['search_form_only']) && $_REQUEST['search_form_only']) {
    switch($_REQUEST['search_form_view']) {
        case 'basic_search':
            $searchForm->setup();
            $searchForm->displayBasic(false);
            break;
        case 'advanced_search':
            $searchForm->setup();
        if (!empty($_REQUEST['address_city']))
			$searchForm->xtpl->assign("CITY_FILTER", get_select_options_with_id(get_city_array(TRUE), $_REQUEST['address_city']));
		else
			$searchForm->xtpl->assign("CITY_FILTER", get_select_options_with_id(get_city_array(TRUE), ''));

		if (!empty($_REQUEST['address_state']))
			$searchForm->xtpl->assign("STATE_FILTER", get_select_options_with_id(get_state_array(TRUE), $_REQUEST['address_state']));
		else
			$searchForm->xtpl->assign("STATE_FILTER", get_select_options_with_id(get_state_array(TRUE), ''));

		if (!empty($_REQUEST['address_country']))
			$searchForm->xtpl->assign("COUNTRY_FILTER", get_select_options_with_id(get_country_array(TRUE), $_REQUEST['address_country']));
		else
			$searchForm->xtpl->assign("COUNTRY_FILTER", get_select_options_with_id(get_country_array(TRUE), ''));
            $searchForm->displayAdvanced(false);
            break;
        case 'saved_views':
            echo $searchForm->displaySavedViews($listViewDefs, $lv, false);
            break;
    }
    return;
}


if (!isset($where)) $where = "";
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
    $searchForm->populateFromRequest();

    $where_clauses = $searchForm->generateSearchWhere($_REQUEST, true, "Leads");
    $where = "";
    if (count($where_clauses) > 0 )$where= implode(' and ', $where_clauses);
    $GLOBALS['log']->info("Here is the where clause for the list view: $where");
}


if(!isset($_REQUEST['search_form']) || $_REQUEST['search_form'] != 'false') {
    $searchForm->setup();
    if(isset($_REQUEST['searchFormTab']) && $_REQUEST['searchFormTab'] == 'advanced_search') {
        if (!empty($_REQUEST['address_city']))
			$searchForm->xtpl->assign("CITY_FILTER", get_select_options_with_id(get_city_array(TRUE), $_REQUEST['address_city']));
		else
			$searchForm->xtpl->assign("CITY_FILTER", get_select_options_with_id(get_city_array(TRUE), ''));

		if (!empty($_REQUEST['address_state']))
			$searchForm->xtpl->assign("STATE_FILTER", get_select_options_with_id(get_state_array(TRUE), $_REQUEST['address_state']));
		else
			$searchForm->xtpl->assign("STATE_FILTER", get_select_options_with_id(get_state_array(TRUE), ''));

		if (!empty($_REQUEST['address_country']))
			$searchForm->xtpl->assign("COUNTRY_FILTER", get_select_options_with_id(get_country_array(TRUE), $_REQUEST['address_country']));
		else
			$searchForm->xtpl->assign("COUNTRY_FILTER", get_select_options_with_id(get_country_array(TRUE), ''));
    	$searchForm->displayAdvanced();
    }
    elseif(isset($_REQUEST['searchFormTab']) && $_REQUEST['searchFormTab'] == 'saved_views'){
        $searchForm->displaySavedViews($listViewDefs, $lv);
    }
    else {
        $searchForm->displayBasic();
    }
}


echo $qsd->GetQSScripts();


$lv->setup($seedLeads, 'include/ListView/ListViewGeneric.tpl', $where, $params);

$savedSearchName = empty($_REQUEST['saved_search_select_name']) ? '' : (' - ' . $_REQUEST['saved_search_select_name']);
echo get_form_header($current_module_strings['LBL_LIST_FORM_TITLE'] . $savedSearchName, '', false);
echo $lv->display();

$savedSearch = new SavedSearch();
$savedSearchSelects = $json->encode(array($GLOBALS['app_strings']['LBL_SAVED_SEARCH_SHORTCUT'] . '<br>' . $savedSearch->getSelect('Leads')));
$str = "<script>
YAHOO.util.Event.addListener(window, 'load', SUGAR.util.fillShortcuts, $savedSearchSelects);
</script>";
echo $str;
?>
