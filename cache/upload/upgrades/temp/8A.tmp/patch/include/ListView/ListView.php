<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * Generic ListView class
 *
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
 */

class ListView
{
	var $local_theme= null;
	var $local_app_strings= null;
	var $local_image_path = null;
	var $local_current_module = null;
	var $local_mod_strings = null;
	var $records_per_page = 20;
	var $xTemplate = null;
	var $xTemplatePath = null;
	var $seed_data = null;
	var $query_where = null;
	var $query_limit = -1;
	var $query_orderby = null;
	var $header_title = '';
	var $header_text = '';
	var $initialized = false;
	var $show_export_button = true;
	var $show_paging = true;
	var $show_mass_update = true;
	var $show_mass_update_form = true;
	var $query_where_has_changed = false;
	var $display_header_and_footer = true;
	var $baseURL = '';
	var $is_dynamic = false;
	var $inline = false;
	var $start_link_wrapper = '';
	var $end_link_wrapper = '';
	var $list_field_defs = array();

	var $shouldProcess = false;
	var $data_array;
	var $related_field_name = '';
	var $child_focus = '';
	var $layout_manager = null;
	var $process_for_popups = false;
	var $multi_select_popup=false;
	var $_additionalDetails = false;
	var $additionalDetailsFunction = null;
	var $sort_order = '';
	var $force_mass_update=false;
	var $keep_mass_update_form_open=false;
function setDataArray($value){
	$this->data_array = $value;
}

function processListViewMulti($seed, $xTemplateSection, $html_varName) {

	$this->shouldProcess = true;

	echo "<form name='MassUpdate' method='post' action='index.php'>";
	$this->processListViewTwo($seed, $xTemplateSection, $html_varName);

	echo "<a class='listViewCheckLink' href='javascript:" . ((!$this->multi_select_popup) ? 'sListView.' : ''). "check_all(document.MassUpdate, \"mass[]\", true)'>".translate('LBL_CHECKALL')."</a> - <a class='listViewCheckLink' href='javascript:sListView.check_all(document.MassUpdate, \"mass[]\", false);'>".translate('LBL_CLEARALL')."</a>";
	echo '<br><br>';
}


function processListView($seed, $xTemplateSection, $html_varName)
{
	global $sugar_config;
	if(isset($seed->module_dir) && !empty($sugar_config['save_query']) && $sugar_config['save_query'] == 'populate_only'){
		if(empty($GLOBALS['displayListView']) && (!empty($_REQUEST['clear_query']) || $_REQUEST['module'] == $seed->module_dir && ((empty($_REQUEST['query']) || $_REQUEST['query'] == 'MSI' )&& (empty($_SESSION['last_search_mod']) || $_SESSION['last_search_mod'] != $seed->module_dir ) ))){
			$_SESSION['last_search_mod'] = $_REQUEST['module'] ;
			return;
		}
		
	}
	$_SESSION['last_search_mod'] = $_REQUEST['module'] ;
	
    //following session variable will track the detail view nvigation history.
    //needs to the reset after each search.
	$this->setLocalSessionVariable($html_varName,"DETAIL_NAV_HISTORY",false);

	require_once('include/MassUpdate.php');
	$mass = new MassUpdate();
	$add_acl_javascript = false;
	if (!isset($_REQUEST['action'])) {
		$this->shouldProcess=false;
	} else {
	$this->shouldProcess = is_subclass_of($seed, "SugarBean")
		&& (($_REQUEST['action'] == 'index') || ('ListView' == substr($_REQUEST['action'],0,8)) /* cn: to include all ListViewXXX.php type views */)
		&& ($_REQUEST['module'] == $seed->module_dir);
	}
		
	//when processing a multi-select popup.
	if ($this->process_for_popups && $this->multi_select_popup )  $this->shouldProcess =true;
	//mass update turned off?
	if (!$this->show_mass_update) $this->shouldProcess = false;
	if( is_subclass_of($seed, "SugarBean")){
		if($seed->bean_implements('ACL')){
			if(!ACLController::checkAccess($seed->module_dir,'list',true)){
				if($_REQUEST['module'] != 'Home'){
					ACLController::displayNoAccess();
				}
				return;
			}
			if(!ACLController::checkAccess($seed->module_dir,'export',true)){
				$sugar_config['disable_export']= true;
			}

		}
	}
	//force mass update form if requested.
	if ($this->force_mass_update) $this->shouldProcess=true;
	
	if($this->shouldProcess)
	{
		echo  $mass->getDisplayMassUpdateForm(true, $this->multi_select_popup);
		echo $mass->getMassUpdateFormHeader($this->multi_select_popup);
		$mass->setSugarBean($seed);
		
		//C.L. Fix for 10048, do not process handleMassUpdate for multi select popups
		if(!$this->multi_select_popup) $mass->handleMassUpdate(); 
	}

	$this->processListViewTwo($seed,$xTemplateSection, $html_varName);
	if($this->shouldProcess && empty($this->process_for_popups))
	{
		echo "<a class='listViewCheckLink' href='javascript:sListView.clear_all(document.MassUpdate, \"mass[]\");'>".translate('LBL_CLEARALL')."</a>";
		echo '<br><br>';
		// cn: preserves current functionality, exception is InboundEmail
		if($this->show_mass_update_form) {
			echo $mass->getMassUpdateForm();
		}
		if (!$this->keep_mass_update_form_open) {
		echo $mass->endMassUpdateForm();
	}
	}

}


function process_dynamic_listview($source_module, $sugarbean,$subpanel_def)
{
		$this->source_module = $source_module;
		$this->subpanel_module = $subpanel_def->name;
		if(!isset($this->xTemplate))
			$this->createXTemplate();

		$list_data = $this->processUnionBeans($sugarbean,$subpanel_def);

		$list = $list_data['list'];
		$parent_data = $list_data['parent_data'];

		if ($subpanel_def->isCollection()) {
			$thepanel=$subpanel_def->get_header_panel_def();
		} else {
			$thepanel=$subpanel_def;
		}

		$this->process_dynamic_listview_header($thepanel->get_module_name(), $thepanel);
		$this->process_dynamic_listview_rows($list,$parent_data, 'dyn_list_view', 'CELL',$subpanel_def);

		if($this->display_header_and_footer)
		{
			$this->getAdditionalHeader();
			if(!empty($this->header_title))
			{
				echo get_form_header($this->header_title, $this->header_text, false);
			}
		}

		$this->xTemplate->out('dyn_list_view');

		if($this->display_header_and_footer)
			echo get_form_footer();

		if(isset($_SESSION['validation']))
		{
			print base64_decode('PGEgaHJlZj0naHR0cDovL3d3dy5zdWdhcmNybS5jb20nPlBPV0VSRUQmbmJzcDtCWSZuYnNwO1NVR0FSQ1JNPC9hPg==');
		}
		if (isset($list_data['query'])) {
			return ($list_data['query']);
		}
	}

/**
 * @return void
 * @param unknown $data
 * @param unknown $xTemplateSection
 * @param unknown $html_varName
 * @desc INTERNAL FUNCTION handles the rows
 */
 function process_dynamic_listview_rows($data,$parent_data, $xtemplateSection, $html_varName, $subpanel_def )
 {
	 global $odd_bg;
	 global $even_bg;
	 global $hilite_bg;
	 global $click_bg;

	$this->xTemplate->assign("BG_HILITE", $hilite_bg);
	$this->xTemplate->assign('CHECKALL', "<img src='include/images/blank.gif' width=\"1\" height=\"1\" alt=\"\" />");
	//$this->xTemplate->assign("BG_CLICK", $click_bg);
	$oddRow = true;
	$count = 0;
	reset($data);

	//GETTING OFFSET
	$offset = $this->getOffset($html_varName);
	//$totaltime = 0;
	$processed_ids = array();

	$fill_additional_fields = array();
	//Either retrieve the is_fill_in_additional_fields property from the lone
	//subpanel or visit each subpanel's subpanels to retreive the is_fill_in_addition_fields
	//property
	$subpanel_list=array();
	if ($subpanel_def->isCollection()) {
		$subpanel_list=$subpanel_def->sub_subpanels;
	} else {
		$subpanel_list[]= $subpanel_def;
	}

	foreach($subpanel_list as $this_subpanel)
	{
		if($this_subpanel->is_fill_in_additional_fields())
		{
			$fill_additional_fields[] = $this_subpanel->bean_name;
			$fill_additional_fields[$this_subpanel->bean_name] = true;
		}
	}

	while(list($aVal, $aItem) = each($data))
	{
		$aItem->check_date_relationships_load();
		// TODO: expensive and needs to be removed and done better elsewhere
		//$microtime = microtime();

		if(!empty($fill_additional_fields[$aItem->object_name]) && $fill_additional_fields[$aItem->object_name])
		{
			$aItem->fill_in_additional_list_fields();
			//$aItem->fill_in_additional_detail_fields();
		}
		//$totaltime+= microtime_diff($microtime, microtime());

		if(isset($parent_data[$aItem->id])){

			$aItem->parent_name = $parent_data[$aItem->id]['parent_name'];
			if(!empty($parent_data[$aItem->id]['parent_name_owner'])){
			$aItem->parent_name_owner =  $parent_data[$aItem->id]['parent_name_owner'];
			$aItem->parent_name_mod =  $parent_data[$aItem->id]['parent_name_mod'];
		}}

		$fields = $aItem->get_list_view_data();

		if(isset($processed_ids[$aItem->id])) {
			continue;

		}else{
			$processed_ids[$aItem->id] = 1;
		}


		//ADD OFFSET TO ARRAY
		$fields['OFFSET'] = ($offset + $count + 1);

		if($this->shouldProcess){
			if($aItem->ACLAccess('EditView')){
			$this->xTemplate->assign('PREROW', "<input type='checkbox' class='checkbox' name='mass[]' value='". $fields['ID']. "' />");
			}else{
				$this->xTemplate->assign('PREROW', '');

			}
			if($aItem->ACLAccess('DetailView')){
				$this->xTemplate->assign('TAG_NAME','a');
			}else{
				$this->xTemplate->assign('TAG_NAME','span');
			}
			$this->xTemplate->assign('CHECKALL', "<input type='checkbox' class='checkbox' name='massall' value='' onclick='sListView.check_all(document.MassUpdate, \"mass[]\", this.checked);' />");
		}

		if($oddRow)
		{
			$ROW_COLOR = 'oddListRow';
			$BG_COLOR =  $odd_bg;
		}
		else
		{
			$ROW_COLOR = 'evenListRow';
			$BG_COLOR =  $even_bg;
		}
		$oddRow = !$oddRow;

		$this->xTemplate->assign("ROW_COLOR", $ROW_COLOR);
		$this->xTemplate->assign("BG_COLOR", $BG_COLOR);
		$layout_manager = $this->getLayoutManager();
		$layout_manager->setAttribute('context','List');
		$layout_manager->setAttribute('image_path',$this->local_image_path);
		$layout_manager->setAttribute('module_name', $subpanel_def->_instance_properties['module']);
		if(!empty($this->child_focus))
			$layout_manager->setAttribute('related_module_name',$this->child_focus->module_dir);

		//AG$subpanel_data = $this->list_field_defs;
		//$bla = array_pop($subpanel_data);
		//select which sub-panel to display here, the decision will be made based on the type of
		//the sub-panel and panel in the bean being processed.
		if ($subpanel_def->isCollection()) {
			$thepanel=$subpanel_def->sub_subpanels[$aItem->panel_name];
		} else {
			$thepanel=$subpanel_def;
		}
		//get data source name
		$linked_field=$thepanel->get_data_source_name();
		$linked_field_set=$thepanel->get_data_source_name(true);
		foreach( $thepanel->get_list_fields() as $field_name=>$list_field)
		{
			//add linked field attribute to the array.
			$list_field['linked_field']=$linked_field;
			$list_field['linked_field_set']=$linked_field_set;

			$usage = empty($list_field['usage']) ? '' : $list_field['usage'];
			if($usage != 'query_only')
			{
				$list_field['name']=$field_name;

				$module_field = $field_name.'_mod';
				$owner_field = $field_name.'_owner';
				if(!empty($aItem->$module_field)){

					$list_field['owner_id'] = $aItem->$owner_field;
					$list_field['owner_module'] = $aItem->$module_field;

				}else{
					$list_field['owner_id'] = false;
					$list_field['owner_module'] = false;
				}
				if (isset($list_field['alias'])) $list_field['name'] = $list_field['alias'];
				else $list_field['name']=$field_name;
				$list_field['fields'] = $fields;
				$list_field['module'] = $aItem->module_dir;
				$list_field['start_link_wrapper'] = $this->start_link_wrapper;
				$list_field['end_link_wrapper'] = $this->end_link_wrapper;
				$list_field['subpanel_id'] = $this->subpanel_id;
				$list_field['DetailView'] = $aItem->ACLAccess('DetailView');
				$list_field['ListView'] = $aItem->ACLAccess('ListView');
				$list_field['EditView'] = $aItem->ACLAccess('EditView');
				$list_field['Delete'] = $aItem->ACLAccess('Delete');
				$widget_contents = $layout_manager->widgetDisplay($list_field);
				static $count;
				if(!isset($count))$count = 0; else $count++;
                $this->xTemplate->assign('CELL_COUNT', $count);
				$this->xTemplate->assign('CELL', $widget_contents);
				$this->xTemplate->parse($xtemplateSection.".row.cell");
			}
		}

		$aItem->setupCustomFields($aItem->module_dir);
		$aItem->custom_fields->populateAllXTPL($this->xTemplate, 'detail', $html_varName, $fields);

		$count++;

		$this->xTemplate->parse($xtemplateSection.".row");
	}

	$this->xTemplate->parse($xtemplateSection);
}

/**sets whether or not to display the xtemplate header and footer
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
*/
function setDisplayHeaderAndFooter($bool){
		$this->display_header_and_footer = $bool;
}

/**initializes ListView
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
*/
 function ListView(){


	if(!$this->initialized){
		global $sugar_config;
		$this->records_per_page = $sugar_config['list_max_entries_per_page'] + 0;
		$this->initialized = true;
		global $theme, $app_strings, $image_path, $currentModule;
		$this->local_theme = $theme;
		$this->local_app_strings =$app_strings;
		$this->local_image_path = $image_path;
		$this->local_current_module = $currentModule;

		if(empty($this->local_image_path)){
			$this->local_image_path = 'themes/'.$theme.'/images';
		}
	}
}
/**sets how many records should be displayed per page in the list view
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
*/
 function setRecordsPerPage($count){
	$this->records_per_page = $count;
}
/**sets the header title */
 function setHeaderTitle($value){
	$this->header_title = $value;
}
/**sets the header text this is text thats appended to the header table and is usually used for the creation of buttons
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
*/
 function setHeaderText($value){
	$this->header_text = $value;
}
/**sets the path for the XTemplate HTML file to be used this is only needed to be set if you are allowing ListView to create the XTemplate
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
*/
 function setXTemplatePath($value){
	$this->xTemplatePath= $value;
}

/**this is a helper function for allowing ListView to create a new XTemplate it groups parameters that should be set into a single function
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
*/
 function initNewXTemplate($XTemplatePath, $modString, $imagePath = null){
	$this->setXTemplatePath($XTemplatePath);
	if(isset($modString))
		$this->setModStrings($modString);
	if(isset($imagePath))
		$this->setImagePath($imagePath);
}


function getOrderBy($varName,$defaultOrderBy='', $force_sortorder=''){
	$sortBy = $this->getSessionVariable($varName, "ORDER_BY") ;

	if(empty($sortBy)){
		$this->setUserVariable($varName, "ORDER_BY", $defaultOrderBy);
		$sortBy = $defaultOrderBy;
	}else{
		$this->setUserVariable($varName, "ORDER_BY", $sortBy);
	}
	if($sortBy == 'amount'){
		$sortBy = 'amount*1';
	}
	if($sortBy == 'amount_usdollar'){
		$sortBy = 'amount_usdollar*1';
	}

	$desc = $this->getSessionVariable($varName, $sortBy."S");

	if(empty($desc))
		$desc = false;
	if(isset($_REQUEST[$this->getSessionVariableName($varName,  "ORDER_BY")]))
		$last = $this->getSessionVariable($varName, "OBL");
		if(!empty($last) && $last == $sortBy){
			$desc = !$desc;
		}else {
			$this->setSessionVariable($varName, "OBL", $sortBy);
		}
	$this->setSessionVariable($varName, $sortBy."S", $desc);
	if(!empty($sortBy) ){
		if(empty($force_sortorder)){
			if(substr_count(strtolower($sortBy), ' desc') == 0 && substr_count(strtolower($sortBy), ' asc') == 0){
				if($desc){
					$this->query_orderby = $sortBy.' desc';
				}else{
					$this->query_orderby = $sortBy.' asc';
				}
			}

		}else{
			$this->query_orderby = $sortBy . ' ' . $force_sortorder;
		}
	}else {
		$this->query_orderby = "";
	}
	$this->sortby = $sortBy;
	return $this->query_orderby;

}


/**sets the parameters dealing with the db
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
*/
 function setQuery($where, $limit, $orderBy, $varName, $allowOrderByOveride=true){
	/* begin Lampada change */
	global $current_user;

	if(!is_admin($current_user) && !preg_match("/TEAM|EMPLOYEE/",$varName) && !$_SESSION['unified_search']) {
		require_once("modules/TeamsOS/TeamOS.php");
		$where = TeamOS::setQuery($where, $varName);
	}
	echo "Where clause ".$where."<br/>";
	/* end Lampada change */

	$this->query_where = $where;
	if($this->getSessionVariable("query", "where") != $where){
		$this->query_where_has_changed = true;
		$this->setSessionVariable("query", "where", $where);
	}

	$this->query_limit = $limit;
	if(!$allowOrderByOveride){
		$this->query_orderby = $orderBy;
		return;
 	}
 	$this->getOrderBy($varName, $orderBy);

    $this->setLocalSessionVariable($varName, "QUERY_WHERE", $where);

	//SETTING ORDER_BY FOR USE IN DETAILVIEW
    $this->setLocalSessionVariable($varName, "ORDER_BY_DETAIL", $this->query_orderby);
}

function displayArrow(){

}

/**sets the theme used only use if it is different from the global
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
*/
 function setTheme($theme){
	$this->local_theme = $theme;
	if(isset($this->xTemplate))$this->xTemplate->assign("THEME", $this->local_theme );
}

/**sets the AppStrings used only use if it is different from the global
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
*/
 function setAppStrings($app_strings){
	unset($this->local_app_strings);
	$this->local_app_strings = $app_strings;
	if(isset($this->xTemplate))$this->xTemplate->assign("APP", $this->local_app_strings );
}

/**sets the ModStrings used
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
*/
 function setModStrings($mod_strings){
	unset($this->local_module_strings);
	$this->local_mod_strings = $mod_strings;
	if(isset($this->xTemplate))$this->xTemplate->assign("MOD", $this->local_mod_strings );
}

/**sets the ImagePath used
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
*/
 function setImagePath($image_path){
	$this->local_image_path = $image_path;
	if(empty($this->local_image_path)){
		$this->local_image_path = 'themes/'.$this->local_theme.'/images';
	}
	if(isset($this->xTemplate))$this->xTemplate->assign("IMAGE_PATH", $this->local_image_path );
}

/**sets the currentModule only use if this is different from the global
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
*/
 function setCurrentModule($currentModule){
	unset($this->local_current_module);
	$this->local_current_module = $currentModule;
	if(isset($this->xTemplate))$this->xTemplate->assign("MODULE_NAME", $this->local_current_module );
}

/**INTERNAL FUNCTION creates an XTemplate DO NOT CALL THIS THIS IS AN INTERNAL FUNCTION
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
*/
 function createXTemplate(){
 	if(!isset($this->xTemplate)){
		if(isset($this->xTemplatePath)){
			$this->xTemplate = new XTemplate ($this->xTemplatePath);
			$this->xTemplate->assign("APP", $this->local_app_strings);
			if(isset($this->local_mod_strings))$this->xTemplate->assign("MOD", $this->local_mod_strings);
			$this->xTemplate->assign("THEME", $this->local_theme);
			$this->xTemplate->assign("IMAGE_PATH", $this->local_image_path);
			$this->xTemplate->assign("MODULE_NAME", $this->local_current_module);
		}else{
			$GLOBALS['log']->error("NO XTEMPLATEPATH DEFINED CANNOT CREATE XTEMPLATE");
		}
 	}
}

/**sets the XTemplate telling ListView to use newXTemplate as its current XTemplate
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
*/
 function setXTemplate($newXTemplate){
	$this->xTemplate = $newXTemplate;
}

/**returns the XTemplate
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
*/
 function getXTemplate(){
	return $this->xTemplate;
}

/**assigns a name value pair to the XTemplate
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
*/
 function xTemplateAssign($name, $value){

		if(!isset($this->xTemplate)){
			$this->createXTemplate();
		}
 		$this->xTemplate->assign($name, $value);

}

/**INTERNAL FUNCTION returns the offset first checking the querey then checking the session if the where clause has changed from the last time it returns 0
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
*/
 function getOffset($localVarName){
 	if($this->query_where_has_changed){
 		$this->setSessionVariable($localVarName,"offset", 0);
 	}
	$offset = $this->getSessionVariable($localVarName,"offset");
	if(isset($offset)){
		return $offset;
	}
	return 0;
}

/**INTERNAL FUNCTION sets the offset in the session
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
*/
 function setOffset($localVarName, $value){
		$this->setSessionVariable($localVarName, "offset", $value);
}

/**INTERNAL FUNCTION sets a session variable
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
*/
 function setSessionVariable($localVarName,$varName, $value){

		if(!($this->is_dynamic || $localVarName == 'CELL')){
			$_SESSION[$this->local_current_module."_".$localVarName."_".$varName] = $value;
		}else{
			global $stateless_session;
			$stateless_session[$this->local_current_module."_".$localVarName."_".$varName] = $value;
		}
}

function setUserVariable($localVarName,$varName, $value){
		if($this->is_dynamic ||  $localVarName == 'CELL')return;
		global $current_user;
		$current_user->setPreference($this->local_current_module."_".$localVarName."_".$varName, $value);
}

/**INTERNAL FUNCTION returns a session variable first checking the querey for it then checking the session
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
*/
 function getSessionVariable($localVarName,$varName){

		if(isset($_REQUEST[$this->getSessionVariableName($localVarName, $varName)])){
			$this->setSessionVariable($localVarName,$varName,$_REQUEST[$this->getSessionVariableName($localVarName, $varName)]);
		}
		if($this->is_dynamic ||  $localVarName == 'CELL'){
			global $stateless_session;
			if(isset($stateless_session[$this->getSessionVariableName($localVarName, $varName)])){
				return $stateless_session[$this->getSessionVariableName($localVarName, $varName)];
			}
			return '';
		}
		 if(isset($_SESSION[$this->getSessionVariableName($localVarName, $varName)])) {
		 	return $_SESSION[$this->getSessionVariableName($localVarName, $varName)];
		 }
		 return "";
}

function getUserVariable($localVarName, $varName){
	global $current_user;
	if($this->is_dynamic ||  $localVarName == 'CELL')return;
	if(isset($_REQUEST[$this->getSessionVariableName($localVarName, $varName)])){

			$this->setUserVariable($localVarName,$varName,$_REQUEST[$this->getSessionVariableName($localVarName, $varName)]);
	}
	return $current_user->getPreference($this->getSessionVariableName($localVarName, $varName));
}





/**

 * @return void
 * @param unknown $localVarName
 * @param unknown $varName
 * @desc INTERNAL FUNCTION returns the session/query variable name
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function getSessionVariableName($localVarName,$varName){
    return $this->local_current_module."_".$localVarName."_".$varName;
}

/**

 * @return void
 * @param unknown $seed
 * @param unknown $xTemplateSection
 * @param unknown $html_varName
 * @desc INTERNAL FUNCTION Handles List Views using seeds that extend SugarBean
	$XTemplateSection is the section in the XTemplate file that should be parsed usually main
	$html_VarName is the variable name used in the XTemplateFile e.g. TASK
	$seed is a seed that extends SugarBean
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
 */
 function processSugarBean($xtemplateSection, $html_varName, $seed){
	$current_offset = $this->getOffset($html_varName);
	$response = array();

	if(empty($this->related_field_name))
	{
		$response = $seed->get_list($this->query_orderby, $this->query_where, $current_offset, $this->query_limit);
	}
	else
	{
		$related_field_name = $this->related_field_name;
		$response = $seed->get_related_list($this->child_focus,$related_field_name, $this->query_orderby,
			$this->query_where, $current_offset, $this->query_limit);
	}
	$list = $response['list'];
	$row_count = $response['row_count'];
	$next_offset = $response['next_offset'];
	$previous_offset = $response['previous_offset'];
	if(!empty($response['current_offset']))$current_offset = $response['current_offset'];
	global $list_view_row_count;
	$list_view_row_count = $row_count;
	$this->processListNavigation( $xtemplateSection,$html_varName, $current_offset, $next_offset, $previous_offset, $row_count);

	return $list;
}

function processUnionBeans($sugarbean, $subpanel_def){




	$current_offset = $this->getOffset('CELL');
	$response = array();

	if(isset($_REQUEST['sort_order'])) {
		$this->sort_order = $_REQUEST['sort_order'];
	}	
		$this->sort_order = 'asc';
	if(isset($_REQUEST['sort_order'])) {
		$this->sort_order = $_REQUEST['sort_order'];
	}	
	else {
		if (isset($subpanel_def->_instance_properties['sort_order'])) $sort_order = $subpanel_def->_instance_properties['sort_order'];
		if(isset($_SESSION['last_sub' .$this->subpanel_module. '_url']) && $_SESSION['last_sub' .$this->subpanel_module. '_url'] == $this->getBaseURL('CELL')) {
			if(isset($_SESSION['last_sub' .$this->subpanel_module. '_order']) && $_SESSION['last_sub' .$this->subpanel_module. '_order'] == 'asc') {
				$this->sort_order = 'desc';
			} 
		}
		elseif(isset($sort_order)) {
			$this->sort_order = $sort_order;
		}
	}
	
	
	if(isset($subpanel_def->_instance_properties['sort_by'])) $this->query_orderby = $subpanel_def->_instance_properties['sort_by'];

	$this->getOrderBy('CELL',$this->query_orderby, $this->sort_order);
	
	$_SESSION['last_sub' .$this->subpanel_module. '_order'] = $this->sort_order;
	$_SESSION['last_sub' .$this->subpanel_module. '_url'] = $this->getBaseURL('CELL');
	

	$response = SugarBean::get_union_related_list($sugarbean,$this->sortby, $this->sort_order, $this->query_where, $current_offset, -1,-1,$this->query_limit,$subpanel_def);




	$list = $response['list'];
	$row_count = $response['row_count'];
	$next_offset = $response['next_offset'];
	$previous_offset = $response['previous_offset'];
	if(!empty($response['current_offset']))$current_offset = $response['current_offset'];
	global $list_view_row_count;
	$list_view_row_count = $row_count;
	$this->processListNavigation('dyn_list_view', 'CELL', $current_offset, $next_offset, $previous_offset, $row_count);

	return array('list'=>$list, 'parent_data'=>$response['parent_data'], 'query'=>$response['query']);
}

function getBaseURL($html_varName){
	$blockVariables = array('mass', 'uid', 'massupdate', 'delete', 'merge', 'selectCount');
	if(!empty($this->base_URL)){
		return $this->base_URL;
	}

		$baseurl = $_SERVER['PHP_SELF'];
		if(empty($baseurl)){
			$baseurl = 'index.php';
		}

		/*fixes an issue with deletes when doing a search*/

		foreach(array_merge($_GET, $_POST) as $name=>$value){
			if(!empty($value)){
			if($name != 'sort_order'&& $name != ListView::getSessionVariableName($html_varName,"ORDER_BY") && $name != ListView::getSessionVariableName($html_varName,"offset") && substr_count($name, "ORDER_BY")==0 && !in_array($name, $blockVariables) ){
				if (is_array($value)) {
					foreach($value as $valuename=>$valuevalue){
						if(substr_count( $baseurl, '?') > 0)
							$baseurl	.= "&{$name}[]=".$valuevalue;
						else
							$baseurl	.= "?{$name}[]=".$valuevalue;
					}
				} else {
					if ($name=='request_data') {  //this condition fixes an issues with popups that are invoked from the select button of a sub-panel, after performing a search the navigation buttons were behaving incorrectly.
						$value=urlencode($value);
					}
					if(substr_count( $baseurl, '?') > 0){
							$baseurl	.= "&$name=$value";
					}else{
						$baseurl	.= "?$name=$value";
					}
				}
			}
			}
		}


		//had an issue with deletes when doing searches
		/*if(isset($_SERVER['QUERY_STRING'])){
			$this->base_URL = ereg_replace("\&".$this->getSessionVariableName($html_varName,"ORDER_BY")."=[0-9a-zA-Z\_\.]*","",$this->base_URL .'?'.$_SERVER['QUERY_STRING']);
			$this->base_URL = ereg_replace("\&".$this->getSessionVariableName($html_varName,"offset")."=[0-9]*","",$this->base_URL);
		}*/
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			// at this point it is possible that the above foreach already executed resulting in double ?'s in the url
			if(substr_count( $baseurl, '?') == 0) {
				$baseurl .= '?';
			}
			if(isset($_REQUEST['action'])) $baseurl.= '&action='.$_REQUEST['action'];
			if(isset($_REQUEST['record'])) $baseurl .= '&record='.$_REQUEST['record'];
			if(isset($_REQUEST['module'])) $baseurl .= '&module='.$_REQUEST['module'];
		}

		$baseurl .= "&".ListView::getSessionVariableName($html_varName,"offset")."=";
		return $baseurl;
}
/**
 * @return void
 * @param unknown $data
 * @param unknown $xTemplateSection
 * @param unknown $html_varName
 * @desc INTERNAL FUNCTION process the List Navigation
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
 function processListNavigation( $xtemplateSection, $html_varName, $current_offset, $next_offset, $previous_offset, $row_count ){
 	global $image_path, $export_module, $sugar_config, $current_user, $currentModule;
	$start_record = $current_offset + 1;
	if($row_count == 0)
		$start_record = 0;

	$end_record = $start_record + $this->records_per_page;
	// back up the the last page.
	if($end_record > $row_count+1)
	{
		$end_record = $row_count+1;
	}
	// Deterime the start location of the last page
	if($row_count == 0)
		$number_pages = 0;
	else
		$number_pages = floor(($row_count - 1) / $this->records_per_page);
	$last_offset = $number_pages * $this->records_per_page;

	if(empty($this->query_limit)  || $this->query_limit > $this->records_per_page){
		$this->base_URL = $this->getBaseURL($html_varName);
		$dynamic_url = '';
		if($this->is_dynamic){

			$dynamic_url .='&'. $this->getSessionVariableName('CELL','ORDER_BY') . '='. $this->getSessionVariable('CELL','ORDER_BY').'&sort_order='.$this->sort_order.'&to_pdf=true&action=SubPanelViewer&subpanel=' . $this->subpanel_module;
		}
		$current_URL = $this->base_URL.$current_offset.$dynamic_url;
		$start_URL = $this->base_URL."0".$dynamic_url;
		$previous_URL  = $this->base_URL.$previous_offset.$dynamic_url;
		$next_URL  = $this->base_URL.$next_offset.$dynamic_url;
		$end_URL  = $this->base_URL.'-100'.$dynamic_url;
		if ( ! empty($this->start_link_wrapper ))
		{
			$current_URL = $this->start_link_wrapper.$current_URL.$this->end_link_wrapper;
			$start_URL = $this->start_link_wrapper.$start_URL.$this->end_link_wrapper;
			$previous_URL = $this->start_link_wrapper.$previous_URL.$this->end_link_wrapper;
			$next_URL = $this->start_link_wrapper.$next_URL.$this->end_link_wrapper;
			$end_URL = $this->start_link_wrapper.$end_URL.$this->end_link_wrapper;

		}
		
		$moduleString = "{$currentModule}_{$html_varName}_offset";
		if($this->shouldProcess && !$this->multi_select_popup) {
			// check the checkboxes onload
			echo '<script>YAHOO.util.Event.addListener(window, "load", sListView.check_boxes);</script>';				
			
			$uids = (empty($_REQUEST['uid']) || (!empty($_REQUEST['massupdate']) && $_REQUEST['massupdate'] == 'true')) ? '' : $_REQUEST['uid'];
			echo "<textarea style='display: none' name='uid'>{$uids}</textarea>
			<input type='hidden' name='{$moduleString}' value='0'>";
		}

		$GLOBALS['log']->debug("Offsets: (start, previous, next, last)(0, $previous_offset, $next_offset, $last_offset)");

		if(0 == $current_offset) {
			$start_link = get_image($image_path."start_off","alt='".$this->local_app_strings['LNK_LIST_START']."'  border='0' align='absmiddle'")."&nbsp;".$this->local_app_strings['LNK_LIST_START'];
			$previous_link = get_image($image_path."previous_off","alt='".$this->local_app_strings['LNK_LIST_PREVIOUS']."'  border='0' align='absmiddle'")."&nbsp;".$this->local_app_strings['LNK_LIST_PREVIOUS'];
		}
		else {
			if($this->multi_select_popup) {// nav links for multiselect popup, submit form to save checks. 
				$start_link = "<a href=\"#\" onclick=\"javascript:save_checks(0, '{$moduleString}')\" class=\"listViewPaginationLinkS1\">".get_image($image_path."start","alt='".$this->local_app_strings['LNK_LIST_START']."'  border='0' align='absmiddle'")."&nbsp;".$this->local_app_strings['LNK_LIST_START']."</a>";
				$previous_link = "<a href=\"#\" onclick=\"javascript:save_checks($previous_offset, '{$moduleString}')\" class=\"listViewPaginationLinkS1\">".get_image($image_path."previous","alt='".$this->local_app_strings['LNK_LIST_PREVIOUS']."'  border='0' align='absmiddle'")."&nbsp;".$this->local_app_strings['LNK_LIST_PREVIOUS']."</a>";
			}
			elseif($this->shouldProcess) {
				// TODO: make popups / listview check saving the same 
				$start_link = "<a href=\"$start_URL\" onclick=\"javascript:return sListView.save_checks(0, '{$moduleString}')\" class=\"listViewPaginationLinkS1\">".get_image($image_path."start","alt='".$this->local_app_strings['LNK_LIST_START']."'  border='0' align='absmiddle'")."&nbsp;".$this->local_app_strings['LNK_LIST_START']."</a>";
				$previous_link = "<a href=\"$previous_URL\" onclick=\"javascript:return sListView.save_checks($previous_offset, '{$moduleString}')\" class=\"listViewPaginationLinkS1\">".get_image($image_path."previous","alt='".$this->local_app_strings['LNK_LIST_PREVIOUS']."'  border='0' align='absmiddle'")."&nbsp;".$this->local_app_strings['LNK_LIST_PREVIOUS']."</a>";
			}
			else {
				$start_link = "<a href=\"$start_URL\" class=\"listViewPaginationLinkS1\">".get_image($image_path."start","alt='".$this->local_app_strings['LNK_LIST_START']."'  border='0' align='absmiddle'")."&nbsp;".$this->local_app_strings['LNK_LIST_START']."</a>";
				$previous_link = "<a href=\"$previous_URL\" class=\"listViewPaginationLinkS1\">".get_image($image_path."previous","alt='".$this->local_app_strings['LNK_LIST_PREVIOUS']."'  border='0' align='absmiddle'")."&nbsp;".$this->local_app_strings['LNK_LIST_PREVIOUS']."</a>";
			}
		}

		if($last_offset <= $current_offset) {
			$end_link = $this->local_app_strings['LNK_LIST_END']."&nbsp;".get_image($image_path."end_off","alt='".$this->local_app_strings['LNK_LIST_END']."'  border='0' align='absmiddle'");
			$next_link = $this->local_app_strings['LNK_LIST_NEXT']."&nbsp;".get_image($image_path."next_off","alt='".$this->local_app_strings['LNK_LIST_NEXT']."'  border='0' align='absmiddle'");
		}
		else {
			if($this->multi_select_popup) { // nav links for multiselect popup, submit form to save checks.
				
				$end_link = "<a href=\"#\" onclick=\"javascript:save_checks($last_offset, '{$moduleString}')\" class=\"listViewPaginationLinkS1\">".$this->local_app_strings['LNK_LIST_END']."&nbsp;".get_image($image_path."end","alt='".$this->local_app_strings['LNK_LIST_END']."'  border='0' align='absmiddle'")."</a>";
				if(!empty($sugar_config['disable_count_query'])){
					$end_link = '';
				}
				$next_link = "<a href=\"#\" onclick=\"javascript:save_checks($next_offset, '{$moduleString}')\" class=\"listViewPaginationLinkS1\">".$this->local_app_strings['LNK_LIST_NEXT']."&nbsp;".get_image($image_path."next","alt='".$this->local_app_strings['LNK_LIST_NEXT']."'  border='0' align='absmiddle'")."</a>";
			}
			elseif($this->shouldProcess) {
				$end_link = "<a href=\"$end_URL\" onclick=\"javascript:return sListView.save_checks($last_offset, '{$moduleString}')\" class=\"listViewPaginationLinkS1\">".$this->local_app_strings['LNK_LIST_END']."&nbsp;".get_image($image_path."end","alt='".$this->local_app_strings['LNK_LIST_END']."'  border='0' align='absmiddle'")."</a>";
				$next_link = "<a href=\"$next_URL\" onclick=\"javascript:return sListView.save_checks($next_offset, '{$moduleString}')\" class=\"listViewPaginationLinkS1\">".$this->local_app_strings['LNK_LIST_NEXT']."&nbsp;".get_image($image_path."next","alt='".$this->local_app_strings['LNK_LIST_NEXT']."'  border='0' align='absmiddle'")."</a>";
			}
			else {
				$end_link = "<a href=\"$end_URL\" class=\"listViewPaginationLinkS1\">".$this->local_app_strings['LNK_LIST_END']."&nbsp;".get_image($image_path."end","alt='".$this->local_app_strings['LNK_LIST_END']."'  border='0' align='absmiddle'")."</a>";
				$next_link = "<a href=\"$next_URL\" class=\"listViewPaginationLinkS1\">".$this->local_app_strings['LNK_LIST_NEXT']."&nbsp;".get_image($image_path."next","alt='".$this->local_app_strings['LNK_LIST_NEXT']."'  border='0' align='absmiddle'")."</a>";
			}
		}
		$GLOBALS['log']->info("Offset (next, current, prev)($next_offset, $current_offset, $previous_offset)");
		$GLOBALS['log']->info("Start/end records ($start_record, $end_record)");

		$end_record = $end_record-1;
		// put overlib strings into functions to avoid backslash plague!
		echo "<script> 
			function export_overlib() {
				return overlib('<a style=\'width: 150px\' class=\'menuItem\' onmouseover=\'hiliteItem(this,\"yes\");\' onmouseout=\'unhiliteItem(this);\' onclick=\'return sListView.send_form(true, \"{$_REQUEST['module']}\", \"export.php\", \"{$this->local_app_strings['LBL_LISTVIEW_NO_SELECTED']}\")\' href=\'#\'>{$this->local_app_strings['LBL_LISTVIEW_OPTION_SELECTED']}</a>"  
			. "<a style=\'width: 150px\' class=\'menuItem\' onmouseover=\'hiliteItem(this,\"yes\");\' onmouseout=\'unhiliteItem(this);\' onclick=\'return sListView.send_form(false, \"{$_REQUEST['module']}\", \"export.php\", \"{$this->local_app_strings['LBL_LISTVIEW_NO_SELECTED']}\")\' href=\'#\'>{$this->local_app_strings['LBL_LISTVIEW_OPTION_CURRENT']}</a>"
			. "<a style=\'width: 150px\' class=\'menuItem\' onmouseover=\'hiliteItem(this,\"yes\");\' onmouseout=\'unhiliteItem(this);\' href=\'export.php?module={$_REQUEST['module']}\'>{$this->local_app_strings['LBL_LISTVIEW_OPTION_ENTIRE']}</a>"			
			. "', CAPTION, '" . $this->local_app_strings['LBL_EXPORT']
			. "', STICKY, MOUSEOFF, 3000, CLOSETEXT, '<img border=0 src=" . $this->local_image_path 
			. "close_inline.gif>', WIDTH, 150, CLOSETITLE, '" . $this->local_app_strings['LBL_ADDITIONAL_DETAILS_CLOSE_TITLE'] . "', CLOSECLICK, FGCLASS, 'olOptionsFgClass', "
			. "CGCLASS, 'olOptionsCgClass', BGCLASS, 'olBgClass', TEXTFONTCLASS, 'olFontClass', CAPTIONFONTCLASS, 'olOptionsCapFontClass', CLOSEFONTCLASS, 'olOptionsCloseFontClass');
			}
			</script>";

		$export_link = "<a id='export_link' onclick='return export_overlib();' href=\"#\" class=\"listViewPaginationLinkS1\">".get_image($image_path."export","alt='".$this->local_app_strings['LBL_EXPORT']."'  border='0' align='absmiddle'")."&nbsp;".$this->local_app_strings['LBL_EXPORT']."</a>";
    require_once("modules/Administration/Administration.php");
    $admin = new Administration();
	$admin->retrieveSettings('system');
   
			$user_merge = $current_user->getPreference('mailmerge_on');
				if ($user_merge == 'on' && isset($admin->settings['system_mailmerge_on']) && $admin->settings['system_mailmerge_on']){
			echo "<script>
				function mailmerge_overlib() {
					return overlib('<a style=\'width: 150px\' class=\'menuItem\' onmouseover=\'hiliteItem(this,\"yes\");\' onmouseout=\'unhiliteItem(this);\' onclick=\'return sListView.send_form(true, \"MailMerge\", \"index.php\", \"{$this->local_app_strings['LBL_LISTVIEW_NO_SELECTED']}\")\' href=\'#\'>{$this->local_app_strings['LBL_LISTVIEW_OPTION_SELECTED']}</a>"  
						. "<a style=\'width: 150px\' class=\'menuItem\' onmouseover=\'hiliteItem(this,\"yes\");\' onmouseout=\'unhiliteItem(this);\' href=\'index.php?action=index&module=MailMerge\'>{$this->local_app_strings['LBL_LISTVIEW_OPTION_CURRENT']}</a>"
						. "<a style=\'width: 150px\' class=\'menuItem\' onmouseover=\'hiliteItem(this,\"yes\");\' onmouseout=\'unhiliteItem(this);\' href=\'index.php?action=index&module=MailMerge&entire=true\'>{$this->local_app_strings['LBL_LISTVIEW_OPTION_ENTIRE']}</a>"			
						. "', CAPTION, '" . $this->local_app_strings['LBL_MAILMERGE']
						. "', STICKY, MOUSEOFF, 3000, CLOSETEXT, '<img border=0 src=" . $this->local_image_path 
						. "close_inline.gif>', WIDTH, 150, CLOSETITLE, '" . $this->local_app_strings['LBL_ADDITIONAL_DETAILS_CLOSE_TITLE'] . "', CLOSECLICK, FGCLASS, 'olOptionsFgClass', "
						. "CGCLASS, 'olOptionsCgClass', BGCLASS, 'olBgClass', TEXTFONTCLASS, 'olFontClass', CAPTIONFONTCLASS, 'olOptionsCapFontClass', CLOSEFONTCLASS, 'olCloseFontClass');
				}				
			</script>";
				$merge_link = "&nbsp;|&nbsp;<a id='mailmerge_link' onclick='return mailmerge_overlib()'; href=\"#\" class=\"listViewPaginationLinkS1\">".$this->local_app_strings['LBL_MAILMERGE']."</a>";
			}
			else{
				$merge_link = "&nbsp;";
			}

		$selected_objects_span = "&nbsp;|&nbsp;{$this->local_app_strings['LBL_LISTVIEW_SELECTED_OBJECTS']}<input class='listViewPaginationTdS1' style='border: 0px; background: transparent; font-size: inherit; color: inherit' type='text' readonly name='selectCount[]' value='0' />";
		if ($_REQUEST['module']== 'Home' || $this->local_current_module == 'Import'



			|| $this->show_export_button == false
			|| (!empty($sugar_config['disable_export']))
			|| (!empty($sugar_config['admin_export_only']) 
			&& !(
			         is_admin($current_user) 
			         || ( ACLController::moduleSupportsACL($_REQUEST['module']) 
			             && ACLAction::getUserAccessLevel($current_user->id,$_REQUEST['module'], 'access') == ACL_ALLOW_ENABLED 
			             && ACLAction::getUserAccessLevel($current_user->id, $_REQUEST['module'], 'admin') == ACL_ALLOW_ADMIN))))
		{
			$selected_objects_span = '';
			$export_link = "&nbsp;";
			$merge_link = "&nbsp;";
		}
		else if($_REQUEST['module'] != "Accounts" && $_REQUEST['module'] != "Cases" && $_REQUEST['module'] != "Contacts" && $_REQUEST['module'] != "Leads" && $_REQUEST['module'] != "Opportunities")
		{
			$merge_link = "&nbsp;";
		}

	if ( $this->show_paging == true)
	{
		if(!empty($sugar_config['disable_count_query'])){
			
			if($row_count > $end_record){
				$row_count .= '+';
			}
		}
		$html_text = '';
		$html_text .= "<tr>\n";
		$html_text .= "<td COLSPAN=\"20\" align=\"right\">\n";
		$html_text .= "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\"><tr><td align=\"left\"  class=\"listViewPaginationTdS1\">$export_link$merge_link$selected_objects_span</td>\n";
		$html_text .= "<td nowrap align=\"right\"  class=\"listViewPaginationTdS1\" id='listViewPaginationButtons'>".$start_link."&nbsp;&nbsp;".$previous_link."&nbsp;&nbsp;<span class='pageNumbers'>(".$start_record." - ".$end_record." ".$this->local_app_strings['LBL_LIST_OF']." ".$row_count.")</span>&nbsp;&nbsp;".$next_link."&nbsp;&nbsp;".$end_link."</td></tr></table>\n";
		$html_text .= "</td>\n";
		$html_text .= "</tr>\n";
		$this->xTemplate->assign("PAGINATION",$html_text);
	}

	$_SESSION['export_where'] = $this->query_where;

	$this->xTemplate->parse($xtemplateSection.".list_nav_row");

	}

}

function processOrderBy($html_varName){

	if(!isset($this->base_URL)){
		$this->base_URL = $_SERVER['PHP_SELF'];
		if(isset($_SERVER['QUERY_STRING'])){
			$this->base_URL = ereg_replace("\&".$this->getSessionVariableName($html_varName,"ORDER_BY")."=[0-9a-zA-Z\_\.]*","",$this->base_URL .'?'.$_SERVER['QUERY_STRING']);
			$this->base_URL = ereg_replace("\&".$this->getSessionVariableName($html_varName,"offset")."=[0-9]*","",$this->base_URL);
		}
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$this->base_URL .= '?';
			if(isset($_REQUEST['action'])) $this->base_URL .= '&action='.$_REQUEST['action'];
			if(isset($_REQUEST['record'])) $this->base_URL .= '&record='.$_REQUEST['record'];
			if(isset($_REQUEST['module'])) $this->base_URL .= '&module='.$_REQUEST['module'];
		}


		$this->base_URL .= "&".$this->getSessionVariableName($html_varName,"offset")."=";
		}
		if($this->is_dynamic){

				$this->base_URL.='&to_pdf=true&action=SubPanelViewer&subpanel=' . $this->source_module;
		}

		$sort_URL_base = $this->base_URL. "&".$this->getSessionVariableName($html_varName,"ORDER_BY")."=";

		if($sort_URL_base !== "")
		{


			$this->xTemplate->assign("ORDER_BY", $sort_URL_base);
			return $sort_URL_base;
		} else {
			return '';
		}
/*
		if (! empty($this->start_link_wrapper))
		{
			$this->xTemplate->assign("START_JS_WRAPPER", $this->start_link_wrapper);
			$this->xTemplate->assign("END_JS_WRAPPER", $this->end_link_wrapper);
		}
*/

}
function getAdditionalHeader(){
}


/**
 * @return void
 * @param unknown $data
 * @param unknown $xTemplateSection
 * @param unknown $html_varName
 * @desc INTERNAL FUNCTION handles the rows
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function processListRows($data, $xtemplateSection, $html_varName)
{
	global $odd_bg;
	global $even_bg;
	global $hilite_bg;
	global $theme;
	global $app_strings, $sugar_version, $sugar_config;
	global $currentModule;
	
	static $overlib_included;
	if(!$overlib_included) { 
		echo '<script type="text/javascript" src="include/javascript/overlibmws.js"></script>
			<script type="text/javascript" src="include/javascript/overlibmws_iframe.js"></script>
			<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>';
		$overlib_included = true;
	}


	$this->xTemplate->assign('BG_HILITE', $hilite_bg);
	$this->xTemplate->assign('CHECKALL', "<img src='include/images/blank.gif' width=\"1\" height=\"1\" alt=\"\" />");
//$this->xTemplate->assign("BG_CLICK", $click_bg);
	$oddRow = true;
	$count = 0;
	reset($data);

	//GETTING OFFSET
	$offset = $this->getOffset($html_varName);
    $timeStamp = $this->unique_id();
    $_SESSION[$html_varName."_FROM_LIST_VIEW"] = $timeStamp;

    $associated_row_data = array();

    //mail merge list
    $mergeList = array();
    $module = '';
	//todo what is this?  It is using an array as a boolean
	while(list($aVal, $aItem) = each($data))
	{
		if(isset($this->data_array)){
			$fields = $this->data_array;
		}else{
			$aItem->check_date_relationships_load();
			$fields = $aItem->get_list_view_data();
		}
		
		if(is_object($aItem)) { // cn: bug 5349
			//add item id to merge list, if the button is clicked
			$mergeList[] = $aItem->id;
			if(empty($module)){
				$module = $aItem->module_dir;
			}
		}
		//ADD OFFSET TO ARRAY

			$fields['OFFSET'] = ($offset + $count + 1);

        $fields['STAMP'] = $timeStamp;
		if($this->shouldProcess){

		$prerow = '';
		if(!isset($this->data_array)){
			$prerow .= "<input onclick='sListView.check_item(this, document.MassUpdate)' type='checkbox' class='checkbox' name='mass[]' value='". $fields['ID']. "'>";
		}
		$this->xTemplate->assign('PREROW', $prerow);

		$this->xTemplate->assign('CHECKALL', "<input type='checkbox' class='checkbox' name='massall' value='' onclick='sListView.check_all(document.MassUpdate, \"mass[]\", this.checked)'>");
		}
		if(!isset($this->data_array)){
			$tag = $aItem->listviewACLHelper();
			$this->xTemplate->assign('TAG',$tag) ;
		}

		if($oddRow)
		{
			$ROW_COLOR = 'oddListRow';
			$BG_COLOR =  $odd_bg;
		}
		else
		{
			$ROW_COLOR = 'evenListRow';
			$BG_COLOR =  $even_bg;
		}
		$oddRow = !$oddRow;

		$this->xTemplate->assign('ROW_COLOR', $ROW_COLOR);
		$this->xTemplate->assign('BG_COLOR', $BG_COLOR);

		if(isset($this->data_array))
		{
			$this->xTemplate->assign('KEY', $aVal);
			$this->xTemplate->assign('VALUE', $aItem);
			$this->xTemplate->assign('INDEX', $count);

		}
		else
		{
//AED -- some modules do not have their additionalDetails.php established. Add a check to ensure require_once does not fail
// Bug #2786
			if($this->_additionalDetails && $aItem->ACLAccess('DetailView') &&
			  file_exists('modules/' . $aItem->module_dir . '/metadata/additionalDetails.php')) {

				require_once('modules/' . $aItem->module_dir . '/metadata/additionalDetails.php');
				$ad_function = (empty($this->additionalDetailsFunction) ? 'additionalDetails' : $this->additionalDetailsFunction) . $aItem->object_name;
				$results = $ad_function($fields);
				$results['string'] = str_replace(array("&#039", "'"), '\&#039', $results['string']); // no xss!

				if(trim($results['string']) == '') $results['string'] = $app_strings['LBL_NONE'];
				$extra = " <img border='0' onmouseover=\"return overlib('" . 
					str_replace(array("\rn", "\r", "\n"), array('','','<br />'), $results['string'])
					. "', CAPTION, '<div style=\'float:left\'>{$this->local_app_strings['LBL_ADDITIONAL_DETAILS']}</div><div style=\'float: right\'>";
				if($aItem->ACLAccess('EditView')) $extra .= (!empty($results['editLink']) ? "<a title=\'{$app_strings['LBL_EDIT_BUTTON']}\' href={$results['editLink']}><img style=\'margin-top: 2px\' border=0 src={$this->local_image_path}edit_inline.gif></a>" : '');
				$extra .= (!empty($results['viewLink']) ? "<a title=\'{$app_strings['LBL_VIEW_BUTTON']}\' href={$results['viewLink']}><img style=\'margin-left: 2px; margin-top: 2px\' border=0 src={$this->local_image_path}view_inline.gif></a>" : '')
					. "</div>', DELAY, 200, STICKY, MOUSEOFF, 1000, WIDTH, " 
					. (empty($results['width']) ? '300' : $results['width']) 
					. ", CLOSETEXT, '<img border=0 src={$this->local_image_path}close_inline.gif>', "
					. "CLOSETITLE, '{$this->local_app_strings['LBL_ADDITIONAL_DETAILS_CLOSE_TITLE']}', CLOSECLICK, FGCLASS, 'olFgClass', "
					. "CGCLASS, 'olCgClass', BGCLASS, 'olBgClass', TEXTFONTCLASS, 'olFontClass', CAPTIONFONTCLASS, 'olCapFontClass', CLOSEFONTCLASS, 'olCloseFontClass');\" "
					. "onmouseout=\"return nd(1000);\" src='themes/$theme/images/MoreDetail.png' width=8 height=7>";

				$fields[$results['fieldToAddTo']] = $extra.'&nbsp;'.$fields[$results['fieldToAddTo']].'</a>';
			}
			
			$this->xTemplate->assign($html_varName, $fields);
			$aItem->setupCustomFields($aItem->module_dir);
			$aItem->custom_fields->populateAllXTPL($this->xTemplate, 'detail', $html_varName, $fields);
		}
		if(!isset($this->data_array) && $aItem->ACLAccess('DetailView')){
			$count++;
		}
		if(isset($this->data_array)){
			$count++;
		}
		if(!isset($this->data_array)){
			$aItem->list_view_parse_additional_sections($this->xTemplate, $xtemplateSection);

			if ($this->xTemplate->exists($xtemplateSection.'.row.pro')) {
				$this->xTemplate->parse($xtemplateSection.'.row.pro');
			}
		}
		$this->xTemplate->parse($xtemplateSection . '.row');

		if (isset($fields['ID'])) {
			$associated_row_data[$fields['ID']] = $fields;
		}
	}

	global $timedate;
	if (empty($timeDate)) $timedate = new TimeDate();

	$microtime = $timedate->get_microtime_string();
	$_SESSION['MAILMERGE_RECORDS'] = $mergeList;
	$_SESSION['MAILMERGE_MODULE_FROM_LISTVIEW'] = $module;
    if(empty($_REQUEST['action']) || $_REQUEST['action'] != 'Popup'){
        $_SESSION['MAILMERGE_MODULE'] = $module;   
    }
	
	if($this->process_for_popups)
	{
		$json = getJSONobj();
		$associated_javascript_data = '<script type="text/javascript">' . "\n"
			. '<!-- // associated javascript data generated by ListView' . "\n"
			. 'var associated_javascript_data = '
			. $json->encode($associated_row_data) . ";\n"
			. '-->' . "\n"
			. '</script>';
		$this->xTemplate->assign('ASSOCIATED_JAVASCRIPT_DATA', $associated_javascript_data);
	}

	$this->xTemplate->parse($xtemplateSection);
}


function getLayoutManager()
{
	require_once('include/generic/LayoutManager.php');
	if ( $this->layout_manager == null)
	{
		$this->layout_manager = new LayoutManager();
	}
	return $this->layout_manager;
}


function process_dynamic_listview_header($source_module, $subpanel_def)
{
    

	$layout_manager = $this->getLayoutManager();
	$layout_manager->setAttribute('order_by_link',$this->processOrderBy('CELL'));
	$layout_manager->setAttribute('context','HeaderCell');
	$layout_manager->setAttribute('image_path',$this->local_image_path);
	$layout_manager->setAttribute('html_varName','CELL');
	$layout_manager->setAttribute('module_name', $source_module);
	list($orderBy,$desc) = $this->getOrderByInfo('CELL');

	if($orderBy == 'amount*1')
	{
		$orderBy=  'amount';
	}

	foreach( $subpanel_def->get_list_fields() as $column_name=>$widget_args)
	{
		$usage = empty($widget_args['usage']) ? '' : $widget_args['usage'];
		if($usage != 'query_only')
		{
			$imgArrow = '';

			if ($orderBy == $column_name || (isset($widget_args['sort_by']) && str_replace('.','_',$widget_args['sort_by']) == $orderBy))
			{
				$imgArrow = "_down";
				if($this->sort_order == 'desc'){
					$imgArrow = "_up";
				}
			}
			$widget_args['name']=$column_name;
			$widget_args['sort'] = $imgArrow;
			$widget_args['start_link_wrapper'] = $this->start_link_wrapper;
			$widget_args['end_link_wrapper'] = $this->end_link_wrapper;
			$widget_args['subpanel_module'] = $this->subpanel_module;

			$widget_contents = $layout_manager->widgetDisplay($widget_args);
			$cell_width = empty($widget_args['width']) ? '' : $widget_args['width'];
			$this->xTemplate->assign('HEADER_CELL', $widget_contents);
			static $count;
		  if(!isset($count))$count = 0; else $count++;
			$this->xTemplate->assign('CELL_COUNT', $count);
			$this->xTemplate->assign('CELL_WIDTH', $cell_width);
			$this->xTemplate->parse('dyn_list_view.header_cell');
		}
	}

}


/**
 * @return void
 * @param unknown $seed
 * @param unknown $xTemplateSection
 * @param unknown $html_varName
 * @desc PUBLIC FUNCTION Handles List Views using seeds that extend SugarBean
	$XTemplateSection is the section in the XTemplate file that should be parsed usually main
	$html_VarName is the variable name used in the XTemplateFile e.g. TASK
	$seed is a seed there are two types of seeds one is a subclass of SugarBean, the other is a list usually created from a sugar bean using get_list
	if no XTemplate is set it will create  a new XTemplate
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
 */

 function processListViewTwo($seed, $xTemplateSection, $html_varName){
	if(!isset($this->xTemplate))
		$this->createXTemplate();
	$isSugarBean = is_subclass_of($seed, "SugarBean");
	$list = null;
	if($isSugarBean){

		$list =	$this->processSugarBean($xTemplateSection, $html_varName, $seed);

	}else{
		$list =$seed;
	}
	
	if ( $this->is_dynamic )
	{
		$this->processHeaderDynamic($xTemplateSection,$html_varName);

		$this->processListRows($list,$xTemplateSection, $html_varName);
	}
	else
	{
		$this->processSortArrows($html_varName);

		if ($isSugarBean) {
			$seed->parse_additional_headers($this->xTemplate, $xTemplateSection);
		}
		$this->xTemplateAssign('CHECKALL', "<img src='include/images/blank.gif' width=\"1\" height=\"1\" al=\"\">");

		// Process the  order by before processing the pro_nav.  The pro_nav requires the order by values to be set
		$this->processOrderBy($html_varName);






		$this->processListRows($list,$xTemplateSection, $html_varName);
	}

	if($this->display_header_and_footer){
		$this->getAdditionalHeader();
		if ( ! empty($this->header_title) )
		{
			echo get_form_header( $this->header_title, $this->header_text, false);
		}
	}
	$this->xTemplate->out($xTemplateSection);
	if($this->display_header_and_footer)
		echo get_form_footer();
	if($isSugarBean )
		//echo "</td></tr>\n</table>\n";

	if(isset($_SESSION['validation'])){
		print base64_decode('PGEgaHJlZj0naHR0cDovL3d3dy5zdWdhcmNybS5jb20nPlBPV0VSRUQmbmJzcDtCWSZuYnNwO1NVR0FSQ1JNPC9hPg==');
}


}

function getArrowStart($image_path)
{
	return "&nbsp;<img border='0' src='".$image_path."arrow";
}

function getArrowEnd($image_path)
{
	global $png_support;
	if ($png_support == false)
	$ext = "gif";
	else
	$ext = "png";

	list($width,$height) = ListView::getArrowImageSize($image_path,$ext);
	return ".$ext' width='$width' height='$height' align='absmiddle' alt='Sort'>";
}

function getArrowImageSize($image_path,$ext)
{
    // just get the non-sort image's size.. the up and down have be the same.
	$image=$image_path."arrow";
	
	$cache_key = 'arrow_size.'.$image.'.'.$ext;
	
	// Check the cache
	$result = sugar_cache_retrieve($cache_key);
	if(!empty($result))
	   return $result;

	// No cache hit.  Calculate the value and return.
	$result = getimagesize($image.'.'.$ext);
	sugar_cache_put($cache_key, $result); 
	return $result;
}

function getOrderByInfo($html_varName)
{
	$orderBy = $this->getSessionVariable($html_varName, "OBL");
	$desc = $this->getSessionVariable($html_varName, $orderBy.'S');
	$orderBy = str_replace('.', '_', $orderBy);
	return array($orderBy,$desc);
}

function processSortArrows($html_varName)
{

	$this->xTemplateAssign("arrow_start", $this->getArrowStart($this->local_image_path));

	list($orderBy,$desc) = $this->getOrderByInfo($html_varName);

	$imgArrow = "_down";
	if($desc){
		$imgArrow = "_up";
	}

	if($orderBy == 'amount*1')
	{
		$this->xTemplateAssign('amount_arrow', $imgArrow);
	}
	else if($orderBy == 'amount_usdollar*1')
	{
		$this->xTemplateAssign('amount_usdollar_arrow', $imgArrow);
	}
	else
	{
		$this->xTemplateAssign($orderBy.'_arrow', $imgArrow);
	}

	$this->xTemplateAssign('arrow_end', $this->getArrowEnd($this->local_image_path));
}

	// this is where translation happens for dynamic list views
	function loadListFieldDefs(&$subpanel_fields,&$child_focus)
	{
		$this->list_field_defs = $subpanel_fields;

		for($i=0;$i < count($this->list_field_defs);$i++)
		{
			$list_field = $this->list_field_defs[$i];
			$field_def = null;
			$key = '';
			if ( ! empty($list_field['vname']) )
			{
				$key = $list_field['vname'];
			} else if (isset($list_field['name']) &&  isset($child_focus->field_defs[$list_field['name']] ))
			{
					$field_def = $child_focus->field_defs[$list_field['name']];
					$key = $field_def['vname'];
			}
			if (! empty( $key ))
			{
				$list_field['label'] = translate($key,$child_focus->module_dir);
				$this->list_field_defs[$i]['label'] = preg_replace('/:$/','',$list_field['label']);
			}
			else
			{
				$this->list_field_defs[$i]['label'] ='&nbsp;';
			}
		}
	}

    function unique_id() {
       global $timedate;
       return $timedate->get_microtime_string();
    }

     /**INTERNAL FUNCTION sets a session variable keeping it local to the listview
     not the current_module
     * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
     * All Rights Reserved.
     * Contributor(s): ______________________________________.
     */
     function setLocalSessionVariable($localVarName,$varName, $value){
        $_SESSION[$localVarName."_".$varName] = $value;
     }

     /**INTERNAL FUNCTION returns a session variable that is local to the listview,
     not the current_module
     * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
     * All Rights Reserved.
     * Contributor(s): ______________________________________.
     */
 function getLocalSessionVariable($localVarName,$varName){
    if(isset($_SESSION[$localVarName."_".$varName])){
        return $_SESSION[$localVarName."_".$varName];
	}
    else{
        return "";
    }
 }

 /* Set to true if you want Additional Details to appear in the listview
  */
 function setAdditionalDetails($value = true, $function = ''){
 	if(!empty($function)) $this->additionalDetailsFunction = $function;
 	$this->_additionalDetails = $value;
 }

}
?>
