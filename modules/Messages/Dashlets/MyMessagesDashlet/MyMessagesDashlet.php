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
//om
// $Id: MyMessagesDashlet.php,v 1.13 2006/08/22 19:19:20 awu Exp $

require_once('include/Dashlets/DashletGeneric.php');
require_once('modules/Messages/Message.php');
require_once('MyMessagesDashlet.data.php');

class MyMessagesDashlet extends DashletGeneric {
    function MyMessagesDashlet($id, $def = null) {
        global $current_user, $app_strings, $dashletData;

        parent::DashletGeneric($id, $def);

        if(empty($def['title'])) $this->title = translate('LBL_LIST_MY_MESSAGES', 'Messages');
        $this->searchFields = $dashletData['MyMessagesDashlet']['searchFields'];
        $this->columns = $dashletData['MyMessagesDashlet']['columns'];
        $this->seedBean = new Message();
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

//				$GLOBALS['log']->debug("WidgetDef :".$name." = ".$widgetDef);

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

		array_push($returnArray, "messages_users.status_id IS NULL");

//        if($this->myItemsOnly) array_push($returnArray, $this->seedBean->table_name . '.' . "assigned_user_id = '" . $current_user->id . "'");

        return $returnArray;
    }

    function process() {
        global $current_language, $app_list_strings, $image_path, $current_user;
        $mod_strings = return_module_language($current_language, 'Messages');

 	    //$this->filters['assigned_user_id'] = $current_user->id;
        $this->filters['status_id'] = NULL;

        parent::process();
        $GLOBALS['log']->debug("echo OM");

        foreach($this->lvs->data['data'] as $num => $row) {

        	$this->lvs->data['data'][$num]['SET_IGNORE_LINKS'] = "<div id=\"ignore".$this->id."\"><a title=\"".
              "\" href=\"javascript:SUGAR.util.retrieveAndFill('index.php?module=Messages&to_pdf=1&action=SetIgnoreStatus&id=".
              $row['ID']."&object_type=Message&object_id=".$row['ID'] .
              "&ignore_status=Ignored', null, null, SUGAR.sugarHome.retrieveDashlet, '{$this->id}');\">".
              get_image($image_path."decline_inline","alt='Ignore' border='0'")."</a></div>";

        	//$GLOBALS['log']->debug("echo OM ".$this->lvs->data['data'][$num]['SET_IGNORE_LINKS']);
        }
    }
}

?>
