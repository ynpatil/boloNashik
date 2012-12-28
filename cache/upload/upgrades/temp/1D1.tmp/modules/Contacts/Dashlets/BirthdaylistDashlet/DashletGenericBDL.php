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
require_once('include/Dashlets/Dashlet.php');
require_once('ListViewSmartyBDL.php');
require_once('include/generic/LayoutManager.php');

class DashletGenericBDL extends Dashlet {
   /**
     * Fields that are searchable
     * @var array
     */        
    var $searchFields;
    /**
     * Displayable columns (ones available to display)
     * @var array
     */    
    var $columns;
    /**
     * Bean file used in this Dashlet
     * @var bean
     */    
    var $seedBean;
    /**
     * collection of filters to apply 
     * @var array
     */    
    var $filters = null;
    /**
     * Number of Rows to display 
     * @var int
     */    
    var $displayRows = '5';
    /**
     * Actual columns to display, will be a subset of $columns 
     * @var array
     */    
    var $displayColumns = null;
    /**
     * Flag to display only the current users's items. 
     * @var bool
     */    
    var $myItemsOnly = true;
    /**
     * location of Smarty template file for display
     * @var string
     */
    var $displayTpl = 'include/Dashlets/DashletGenericDisplay.tpl';
    /**
     * location of smarty template file for configuring
     * @var string
     */
    var $configureTpl = 'include/Dashlets/DashletGenericConfigure.tpl';
    /**
     * smarty object for the generic configuration template
     * @var string
     */    
    var $configureSS;
    /** search inputs to be populated in configure template.
     *  modify this after processDisplayOptions, but before displayOptions to modify search inputs
     *  @var array
     */
    var $currentSearchFields;
    /**
     * ListView Smarty Class
     * @var Smarty
     */
    var $lvs;
    var $layoutManager;
    
    function DashletGenericBDL($id, $options = null) {
        parent::Dashlet($id);
        $this->isConfigurable = true;
        if(isset($options)) {
            if(!empty($options['filters'])) $this->filters = $options['filters'];
            if(!empty($options['title'])) $this->title = $options['title'];
            if(!empty($options['displayRows'])) $this->displayRows = $options['displayRows'];
            if(!empty($options['displayColumns'])) $this->displayColumns = $options['displayColumns'];
            if(isset($options['myItemsOnly'])) $this->myItemsOnly = $options['myItemsOnly'];
        }
        
        $this->layoutManager = new LayoutManager();
        $this->layoutManager->setAttribute('context', 'Report');
        // fake a reporter object here just to pass along the db type used in many widgets.
        // this should be taken out when sugarwidgets change
        $temp = (object) array('db' => &$GLOBALS['db'], 'report_def_str' => '');
        $this->layoutManager->setAttributePtr('reporter', $temp);
        $this->lvs = new ListViewSmartyBDL();
        
    }
    
    /**
     * Sets up the display options template
     * 
     * @return string HTML that shows options 
     */
    function processDisplayOptions() {
         require_once('include/templates/TemplateGroupChooser.php');
       
        $this->configureSS = new Sugar_Smarty();
        // column chooser
        $chooser = new TemplateGroupChooser();
        
        $chooser->args['id'] = 'edit_tabs';
        $chooser->args['left_size'] = 5;
        $chooser->args['right_size'] = 5;
        $chooser->args['values_array'][0] = array();
        $chooser->args['values_array'][1] = array();
        
        $this->addCustomFields();
        
        if($this->displayColumns) {
             // columns to display
             foreach($this->displayColumns as $num => $name) {
                    // defensive code for array being returned
                    $translated = translate($this->columns[$name]['label'], $this->seedBean->module_dir);
                    if(is_array($translated)) $translated = $this->columns[$name]['label'];
                    $chooser->args['values_array'][0][$name] = trim($translated, ':');
             }
             // columns not displayed
             foreach(array_diff(array_keys($this->columns), array_values($this->displayColumns)) as $num => $name) {
                    // defensive code for array being returned
                    $translated = translate($this->columns[$name]['label'], $this->seedBean->module_dir);
                    if(is_array($translated)) $translated = $this->columns[$name]['label'];
                    $chooser->args['values_array'][1][$name] = trim($translated, ':');
             }
        }
        else {
             foreach($this->columns as $name => $val) {
                // defensive code for array being returned
                $translated = translate($this->columns[$name]['label'], $this->seedBean->module_dir);
                if(is_array($translated)) $translated = $this->columns[$name]['label'];
                if(!empty($val['default']) && $val['default'])  
                    $chooser->args['values_array'][0][$name] = trim($translated, ':');
                else 
                    $chooser->args['values_array'][1][$name] = trim($translated, ':');
            }
        }

        $chooser->args['left_name'] = 'display_tabs';
        $chooser->args['right_name'] = 'hide_tabs';
        $chooser->args['max_left'] = '6';
        
        $chooser->args['left_label'] =  $GLOBALS['app_strings']['LBL_DISPLAY_COLUMNS'];
        $chooser->args['right_label'] =  $GLOBALS['app_strings']['LBL_HIDE_COLUMNS'];
        $chooser->args['title'] =  '';
        $this->configureSS->assign('columnChooser', $chooser->display());
        
        $query = false;
        $count = 0;
        
        if(!is_array($this->filters)) {
            // use default search params
            $this->filters = array();
            foreach($this->searchFields as $name => $params) {
                if(!empty($params['default']))
                    $this->filters[$name] = $params['default'];
            }
        }
        foreach($this->searchFields as $name=>$params) {
            if(!empty($name)) {
                $name = strtolower($name);
                $currentSearchFields[$name] = array();
            
                $widgetDef = $this->seedBean->field_defs[$name]; 
                if($widgetDef['type'] == 'enum') $widgetDef['remove_blank'] = true; // remove the blank option for the dropdown
                 
                $widgetDef['input_name0'] = empty($this->filters[$name]) ? '' : $this->filters[$name]; 
                $currentSearchFields[$name]['label'] = translate($widgetDef['vname'], $this->seedBean->module_dir);
                $currentSearchFields[$name]['input'] = $this->layoutManager->widgetDisplayInput($widgetDef, true, (empty($this->filters[$name]) ? '' : $this->filters[$name]));
            }
            else { // ability to create spacers in input fields
                $currentSearchFields['blank' + $count]['label'] = '';
                $currentSearchFields['blank' + $count]['input'] = '';
                $count++;
            }
        }
        $this->currentSearchFields = $currentSearchFields;
        
        $this->configureSS->assign('strings', array('general' => $GLOBALS['mod_strings']['LBL_DASHLET_CONFIGURE_GENERAL'],
                                     'filters' => $GLOBALS['mod_strings']['LBL_DASHLET_CONFIGURE_FILTERS'],
                                     'myItems' => $GLOBALS['mod_strings']['LBL_DASHLET_CONFIGURE_MY_ITEMS_ONLY'],
                                     'displayRows' => $GLOBALS['mod_strings']['LBL_DASHLET_CONFIGURE_DISPLAY_ROWS'],
                                     'title' => $GLOBALS['mod_strings']['LBL_DASHLET_CONFIGURE_TITLE'],
                                     'save' => $GLOBALS['app_strings']['LBL_SAVE_BUTTON_LABEL']));
        $this->configureSS->assign('id', $this->id);
        $this->configureSS->assign('myItemsOnly', $this->myItemsOnly);
        $this->configureSS->assign('searchFields', $this->currentSearchFields);        
        // title
        $this->configureSS->assign('dashletTitle', $this->title);
        
        // display rows
        $displayRowOptions = $GLOBALS['sugar_config']['dashlet_display_row_options'];
        $this->configureSS->assign('displayRowOptions', $displayRowOptions);
        $this->configureSS->assign('displayRowSelect', $this->displayRows);
    }
    /**
     * Displays the options for this Dashlet
     * 
     * @return string HTML that shows options 
     */
    function displayOptions() {
        $this->processDisplayOptions();
        return parent::displayOptions() . $this->configureSS->fetch($this->configureTpl);
    }

    function buildWhere() {
        global $current_user;
        
        $returnArray = array();

        if(!is_array($this->filters)) {
            // use defaults
            $this->filters = array();
            foreach($this->searchFields as $name => $params) {
                if(!empty($params['default']))
                    $this->filters[$name] = $params['default'];
            }
        }
        foreach($this->filters as $name=>$params) {
            if(!empty($params)) {
                if($name == 'assigned_user_id' && $this->myItemsOnly) continue; // don't handle assigned user filter if filtering my items only
                $widgetDef = $this->seedBean->field_defs[$name];

                $widgetClass = $this->layoutManager->getClassFromWidgetDef($widgetDef, true);
                $widgetDef['table'] = $this->seedBean->table_name;
                $widgetDef['table_alias'] = $this->seedBean->table_name;
                
                switch($widgetDef['type']) {// handle different types
                    case 'date':
                    case 'datetime':
                        if(!empty($params['date'])) 
                            $widgetDef['input_name0'] = $params['date'];
                        $filter = 'queryFilter' . $params['type'];
                        array_push($returnArray, $widgetClass->$filter($widgetDef, true));
                        break;
                    default:
                        $widgetDef['input_name0'] = $params;
                        if(is_array($params) && !empty($params)) { // handle array query
                            array_push($returnArray, $widgetClass->queryFilterone_of($widgetDef, false));
                        }
                        else {
                            array_push($returnArray, $widgetClass->queryFilterStarts_With($widgetDef, true));
                        }
                        $widgetDef['input_name0'] = $params;
                    break;
                }
            }
        }
        
        if($this->myItemsOnly) array_push($returnArray, $this->seedBean->table_name . '.' . "assigned_user_id = '" . $current_user->id . "'");

        return $returnArray;
    }
    
    /**
     * Does all dashlet processing, here's your chance to modify the rows being displayed!
     */
    function process($lvsParams = array()) {
        $currentSearchFields = array();
        $configureView = true; // configure view or regular view
        $query = false;
        $whereArray = array();
        $lvsParams['massupdate'] = false;
        
        // apply filters
        if(isset($this->filters) || $this->myItemsOnly) {
            $whereArray = $this->buildWhere();
        }
        
        $this->lvs->export = false;
        $this->lvs->multiSelect = false;
        
        $this->addCustomFields();
        
        // columns
        $displayColumns = array();
        if(isset($this->displayColumns)) { // use user specified columns
            foreach($this->displayColumns as $name => $val) {
                $displayColumns[strtoupper($val)] = $this->columns[$val];
                $displayColumns[strtoupper($val)]['label'] = trim($displayColumns[strtoupper($val)]['label'], ':');// strip : at the end of headers
            }
        }
        else { // use the default
            foreach($this->columns as $name => $val) {
                if(!empty($val['default']) && $val['default']) {
                    $displayColumns[strtoupper($name)] = $val;
                    $displayColumns[strtoupper($name)]['label'] = trim($displayColumns[strtoupper($name)]['label'], ':');
                }
            }
        }

        $this->lvs->displayColumns = $displayColumns;
        
        $this->lvs->lvd->setVariableName($this->seedBean->object_name, array());        
        $lvdOrderBy = $this->lvs->lvd->getOrderBy(); // has this list been ordered, if not use default
        if(empty($lvdOrderBy['orderBy'])) {
            foreach($displayColumns as $colName => $colParams) {
                if(!empty($colParams['defaultOrderColumn'])) { 
                    $lvsParams['overrideOrder'] = true;
                    $lvsParams['orderBy'] = $colName;
                    $lvsParams['sortOrder'] = $colParams['defaultOrderColumn']['sortOrder'];
                }
            }
        }

        
        if(!empty($this->displayTpl))
        {
            $this->lvs->setup($this->seedBean, $this->displayTpl, implode(' AND ', $whereArray), $lvsParams, 0, $this->displayRows/*, $filterFields*/);
            if(in_array('CREATED_BY', array_keys($displayColumns))) { // handle the created by field
                foreach($this->lvs->data['data'] as $row => $data) {
                    $this->lvs->data['data'][$row]['CREATED_BY'] = get_assigned_user_name($data['CREATED_BY']);
                }
            }
            // assign a baseURL w/ the action set as DisplayDashlet
            foreach($this->lvs->data['pageData']['urls'] as $type => $url) {
                if($type == 'orderBy')
                    $this->lvs->data['pageData']['urls'][$type] = preg_replace('/(action=.*&)/Ui', 'action=DisplayDashlet&', $url);
                else
                    $this->lvs->data['pageData']['urls'][$type] = preg_replace('/(action=.*&)/Ui', 'action=DisplayDashlet&', $url) . '&sugar_body_only=1&id=' . $this->id; 
            }
    
            $this->lvs->ss->assign('dashletId', $this->id);
            
        }
    }
    
   /**
     * Displays the Dashlet, must call process() prior to calling this
     * 
     * @return string HTML that displays Dashlet 
     */
    function display() {
        return parent::display() . $this->lvs->display(false);
    }
    
    /**
     * Filter the $_REQUEST and only save only the needed options
     * @param array $req the array to pull options from
     * 
     * @return array options array
     */
    function saveOptions($req) {
        $options = array();
        
        foreach($req as $name => $value) {
            if(!is_array($value)) $req[$name] = trim($value);
        }
        $options['filters'] = array();
        foreach($this->searchFields as $name=>$params) {
            $widgetDef = $this->seedBean->field_defs[$name];
            if($widgetDef['type'] == 'datetime' || $widgetDef['type'] == 'date') { // special case datetime types
                $options['filters'][$widgetDef['name']] = array();
                if(!empty($req['type_' . $widgetDef['name']])) { // save the type of date filter
                    $options['filters'][$widgetDef['name']]['type'] = $req['type_' . $widgetDef['name']];
                }
                if(!empty($req['date_' . $widgetDef['name']])) { // save the date
                    $options['filters'][$widgetDef['name']]['date'] = $req['date_' . $widgetDef['name']];
                }
            }
            elseif(!empty($req[$widgetDef['name']])) {
                $options['filters'][$widgetDef['name']] = $req[$widgetDef['name']]; 
            } 
        }
        if(!empty($req['dashletTitle'])) {
            $options['title'] = $req['dashletTitle'];
        }
        
        if(!empty($req['myItemsOnly'])) {
            $options['myItemsOnly'] = $req['myItemsOnly'];
        }
        else {
           $options['myItemsOnly'] = false;
        }
        $options['displayRows'] = empty($req['displayRows']) ? '5' : $req['displayRows'];
        // displayColumns
        if(!empty($req['displayColumnsDef'])) {
            $options['displayColumns'] = explode('|', $req['displayColumnsDef']); 
        }
        return $options;
    }
    
    /**
     * Internal function to add custom fields
     * 
     */
    function addCustomFields() {
        if(!empty($this->seedBean->added_custom_field_defs) && $this->seedBean->added_custom_field_defs) {
            foreach($this->seedBean->field_defs as $fieldName => $def) {
                if(isset($def['vname'])) {
                    $translated = translate($def['vname'], $this->seedBean->module_dir);
                    if(is_array($translated)) $translated = $def['vname'];
                    if(!empty($def['source']) && $def['source'] == 'custom_fields') {
                        $this->columns[$fieldName] = array('width' => '10',
                                                           'label' => $translated);
                    }
                }
            }
        }
    }
}
?>
