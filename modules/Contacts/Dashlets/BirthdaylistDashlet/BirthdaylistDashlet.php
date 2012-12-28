<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
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

//require_once('include/Dashlets/DashletGeneric.php');
require_once('DashletGenericBDL.php');
require_once('modules/Contacts/Contact.php');
require_once('modules/Accounts/Account.php');
require_once('modules/Contacts/Dashlets/BirthdaylistDashlet/BirthdaylistDashlet.data.php');

require_once('include/ListView/ListView.php');

class BirthdaylistDashlet extends DashletGenericBDL { 
    
    function BirthdaylistDashlet($id, $def = null) {
        global $current_user, $app_strings, $dashletData;
        $this->loadLanguage('BirthdaylistDashlet', 'modules/Contacts/Dashlets/'); // load the language strings here
        
        parent::DashletGenericBDL($id, $def);

        if(empty($def['title'])) $this->title = $this->dashletStrings['LBL_BIRTHDAYLIST_TITLE'];        
		
		  	// ----------Calculate the filters-------------

		global $current_user, $timedate, $current_module_strings, $appt_filter;
	
		// First, the filter is set to the user's preference, or 'today' if there is no preference.
		if (empty($_REQUEST['birthday_filter'])) {
		  if ($current_user->getPreference('birthday_filter') == '') $this->birthday_filter = 'today';	
		  else $this->birthday_filter = $current_user->getPreference('birthday_filter');       
		}
		else {
		  $this->birthday_filter = $_REQUEST['birthday_filter'];
		  $current_user->setPreference('birthday_filter', $_REQUEST['birthday_filter']);
		}	 
	
		// this week
		if($this->birthday_filter == 'this Saturday' || $this->birthday_filter == 'this Sunday') {
			$appt_filter = strftime("%d %B %Y", strtotime("this Sunday"));
		// next week  
		} elseif ($this->birthday_filter == 'next Saturday' || $this->birthday_filter == 'next Sunday') {
			$sunday = strftime("%d %B %Y", strtotime("this Sunday"));
			$appt_filter = strftime("%d %B %Y", strtotime("+1 week", strtotime($sunday)));
		// this month  
		} elseif ($this->birthday_filter == 'last this_month') {
	  		$next_month = "01 ".strftime("%B %Y", strtotime("+1 month"));
		  	//$first_day = strftime("%d %B %Y", $next_month);
		  	$appt_filter = strftime("%d %B %Y", strtotime("-1 day", strtotime($next_month)));
		// next month
		} elseif ($this->birthday_filter == 'last next_month') {	
		  	$next_month = "01 ".strftime("%B %Y", strtotime("+2 month"));
		  	$first_day = "01 ".strftime("%B %Y", strtotime($next_month));
		  	$appt_filter = strftime("%d %B %Y", strtotime("-1 day", strtotime($next_month)));
		  	$GLOBALS['log']->debug("next_month is '$next_month'; first_day is '$first_day';");
		} else {
		  	$appt_filter = $this->birthday_filter;
		}

		// Formating the filter into a date in the correct format (yyyy-mm-dd).
		$gm_later = gmdate("Y-m-d H:i:s", strtotime("$appt_filter"));
		$this->later = $timedate->handle_offset($gm_later, $timedate->dbDayFormat, true);
		$db_later = $timedate->handle_offset($gm_later, $timedate->dbDayFormat, true);
		$this->laterWhere = $timedate->handleOffsetMax($this->later, $timedate->dbDayFormat, true);
		$GLOBALS['log']->debug("appt_filter is '$appt_filter'; later is '$this->later'");
		
		// Creating the form in which the user will choose the period-filter.
		$this->filter = get_select_options_with_id($this->dashletStrings['birthday_filter_dom'], $this->birthday_filter );		
		$this->day_filter = "<select name='birthday_filter' language='JavaScript' onchange='this.form.submit();'>$this->filter</select>";

		if (empty($_REQUEST['entity_type_filter'])) {
		  if ($current_user->getPreference('entity_type_filter') == '') $this->entity_type_filter = 'Contacts';	
		  else $this->entity_type_filter = $current_user->getPreference('entity_type_filter');       
		}
		else {
		  $this->entity_type_filter = $_REQUEST['entity_type_filter'];
		  $current_user->setPreference('entity_type_filter', $_REQUEST['entity_type_filter']);
		}

		$this->entity_types_filter = get_select_options_with_id($this->dashletStrings['entity_type_filter_dom'], $this->entity_type_filter);
		$this->entity_type_html = "<select name='entity_type_filter' language='JavaScript' onchange='this.form.submit();'>$this->entity_types_filter</select>";
   	 }
    
     // Called when Dashlet is displayed
      
     // This override also adds the form that captures the user's choice of period-filter.
              
    function getTitle($text) {
        global $image_path, $app_strings, $sugar_config, $timedate, $current_module_strings, $current_user;
                       
        if($this->isConfigurable) { 
        	$additionalTitle = '<table width="100%" cellspacing="0" cellpadding="0" border="0"><tr><td width="99%">' . $text 
                               . '</td><td nowrap width="1%"><div style="width: 100%;text-align:right"><a href="#" onclick="SUGAR.sugarHome.configureDashlet(\'' 
                               . $this->id . '\'); return false;" class="chartToolsLink">'    
                               . get_image($image_path.'edit','title="Edit Dashlet" alt="Edit Dashlet"  border="0"  align="absmiddle"').'</a> ' 
                               . '';
        } else { 
            $additionalTitle = '<table border=1 width="100%" cellspacing="0" cellpadding="0" border="0"><tr><td width="99%">' . $text 
                   . '</td><td nowrap width="1%"><div style="width: 100%;text-align:right">';
        }
        
		if($this->isRefreshable) {
            $additionalTitle .= '<a href="#" onclick="SUGAR.sugarHome.retrieveDashlet(\'' 
                                . $this->id . '\'); return false;"><img width="13" height="13" border="0" align="absmiddle" title="Refresh Dashlet" alt="Refresh Dashlet" src="' 
                                . $image_path . 'refresh.gif"/></a> ';
        }
		
		$additionalTitle .= '<a href="#" onclick="SUGAR.sugarHome.deleteDashlet(\'' 
                            . $this->id . '\'); return false;"><img width="13" height="13" border="0" align="absmiddle" title="Delete Dashlet" alt="Delete Dashlet" src="' 
                            . $image_path . 'close_dashboard.gif"/></a></div></td></tr></table>';
            
        if(!function_exists('get_form_header')) {
            global $theme;
            require_once('themes/'.$theme.'/layout_utils.php');
        }
        
        $str = '<div ';
        if(empty($sugar_config['lock_homepage']) || $sugar_config['lock_homepage'] == false) $str .= ' onmouseover="this.style.cursor = \'move\';"';
        $str .= 'id="dashlet_header_' . $this->id . '">' . "<form method='POST' action='index.php'>\n".
			"<input type='hidden' name='module' value='Home'>\n".
			"<input type='hidden' name='action' value='index'>\n".get_form_header($this->title, $this->dashletStrings['LBL_BIRTHDAYLIST_FORM_TITLE'].$this->day_filter.' ('.$timedate->to_display_date($this->later, false).") for ".$this->entity_type_html.' </td><td>'.$additionalTitle, false) ."</form>". '</div>';
        return $str;
    }
        
    // This function builds the part of the where-clause that limits the data according to the chosen period-filter.
	function build_birthday_where() {
		global $appt_filter;
	  	if( date("m")=="12" && strftime("%B", strtotime($appt_filter))=="January") {
			$birthday_where = 
"( ( month(contacts.birthdate) = 12 AND day(contacts.birthdate) >= day(now()) )
 OR
( month(contacts.birthdate) = 1 AND day(contacts.birthdate) <= day('".$this->laterWhere["date"]."') ) )"; 					  
		} else {
  			$birthday_where = 
" ( ( month(contacts.birthdate) > month(now()) 
 OR
  month(contacts.birthdate) = month(now()) AND day(contacts.birthdate) >= day(now()) )
 AND
  ( month(contacts.birthdate) < month('".$this->laterWhere["date"]."')
 OR
 month(contacts.birthdate) = month('".$this->laterWhere["date"]."') AND day(contacts.birthdate) <= day('".$this->laterWhere["date"]."	') )	
 OR 
 ( month(contacts.birthdate) = 02 AND day(contacts.birthdate) = 29 AND month(now()) = 02 AND day(now()) = 28 ) )";  
		}
	
		return $birthday_where;
	}

    // This function builds the part of the where-clause that limits the data according to the chosen period-filter for accounts.
	function build_anniversary_where() {
		global $appt_filter;
	  	if( date("m")=="12" && strftime("%B", strtotime($appt_filter))=="January") {
			$birthday_where = 
"( ( month(accounts.anniversary) = 12 AND day(accounts.anniversary) >= day(now()) )
 OR
( month(accounts.anniversary) = 1 AND day(accounts.anniversary) <= day('".$this->laterWhere["date"]."') ) )"; 					  
		} else {
  			$birthday_where = 
" ( ( month(accounts.anniversary) > month(now()) 
 OR
  month(accounts.anniversary) = month(now()) AND day(accounts.anniversary) >= day(now()) )
 AND
  ( month(accounts.anniversary) < month('".$this->laterWhere["date"]."')
 OR
 month(accounts.anniversary) = month('".$this->laterWhere["date"]."') AND day(accounts.anniversary) <= day('".$this->laterWhere["date"]."	') )	
 OR 
 ( month(accounts.anniversary) = 02 AND day(accounts.anniversary) = 29 AND month(now()) = 02 AND day(now()) = 28 ) )";  
		}
			
		return $birthday_where;
	}
        
    // This function builds the where-clause, represented in an array.
    function buildWhere($type='Contacts') {
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
		if($type == 'Contacts')
		array_push($returnArray, $this->build_birthday_where());
		else
		array_push($returnArray, $this->build_anniversary_where());

//echo '<pre>';
//print_r($returnArray);
//echo '</pre>';
        return $returnArray;
    }
    
     // Does all dashlet processing, here's your chance to modify the rows being displayed!
     
    function process($lvsParams = array()) {    	

        global $dashletData;
		
		if($this->entity_type_filter == 'Contacts'){        
			$this->title = $this->dashletStrings['LBL_BIRTHDAYLIST_TITLE'];
			
    	    $this->searchFields = $dashletData['BirthdaylistDashlet']['searchFields'];
	        $this->columns = $dashletData['BirthdaylistDashlet']['columns']['Contacts'];
    	    $this->seedBean = new Contact();          
    		$this->process1($lvsParams,'Contacts');
    	}
    	else{
			$this->title = $this->dashletStrings['LBL_ANNIVERSARYLIST_TITLE'];
		    $this->searchFields = $dashletData['BirthdaylistDashlet']['searchFields'];
	        $this->columns = $dashletData['BirthdaylistDashlet']['columns']['Accounts'];    	
	        $this->seedBean = new Account();       	
    		$this->process1($lvsParams,'Accounts');
		}
    }
    
    function process1($lvsParams,$type){
        $currentSearchFields = array();
        $configureView = true; // configure view or regular view
        $query = false;
        $whereArray = array();
        $lvsParams['massupdate'] = false;
        
        // apply filters
        if(isset($this->filters) || $this->myItemsOnly) {
            $whereArray = $this->buildWhere($type);
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
        // Here the sort default is set to the birthdate column.
		$lvsParams['overrideOrder'] = true;
		if($type == 'Contacts')
		$lvsParams['orderBy'] = 'birthdate';
		else 
		$lvsParams['orderBy'] = 'anniversary';
		
		$lvsParams['sortOrder'] = 'ASC';
		
        if(!empty($this->displayTpl))
        {
            $this->lvs->setup($this->seedBean, $this->displayTpl, implode(' AND ', $whereArray), $lvsParams, 0, $this->displayRows);
            if(in_array('CREATED_BY', array_keys($displayColumns))) { // handle the created by field
                foreach($this->lvs->data['data'] as $row => $data) {
                    $this->lvs->data['data'][$row]['CREATED_BY'] = get_assigned_user_name($data['CREATED_BY']);
                }
            }
            // assign a baseURL w/ the action set as DisplayDashlet

//echo "<pre>";
//print_r($this->lvs->data['data']);
//echo "</pre>";

            foreach($this->lvs->data['pageData']['urls'] as $type => $url) {
                if($type == 'orderBy')
                    $this->lvs->data['pageData']['urls'][$type] = preg_replace('/(action=.*&)/Ui', 'action=DisplayDashlet&', $url);
                else
                    $this->lvs->data['pageData']['urls'][$type] = preg_replace('/(action=.*&)/Ui', 'action=DisplayDashlet&', $url) . '&sugar_body_only=1&id=' . $this->id; 
            }
    
            $this->lvs->ss->assign('dashletId', $this->id);
            
        }
    }
}

?>
