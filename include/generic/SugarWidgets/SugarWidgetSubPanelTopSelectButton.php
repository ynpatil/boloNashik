<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * SugarWidgetSubPanelTopSelectButton
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

// $Id: SugarWidgetSubPanelTopSelectButton.php,v 1.31 2006/06/06 17:57:53 majed Exp $

require_once('include/generic/SugarWidgets/SugarWidgetSubPanelTopButton.php');

class SugarWidgetSubPanelTopSelectButton extends SugarWidgetSubPanelTopButton
{
	//button_properties is a collection of properties associated with the widget_class definition. layoutmanager
	function SugarWidgetSubPanelTopSelectButton($button_properties=array())
	{
		$this->button_properties=$button_properties;
	}

	//widget_data is the collection of attributes assoicated with the button in the layout_defs file.
	function display(&$widget_data)
	{
		global $app_strings;
		$initial_filter = '';
	
		$this->title = $app_strings['LBL_SELECT_BUTTON_TITLE'];
		$this->accesskey = $app_strings['LBL_SELECT_BUTTON_KEY'];
		$this->value = $app_strings['LBL_SELECT_BUTTON_LABEL'];		
//		echo "Button properties ".implode("/",$this->button_properties);
		
		if (is_array($this->button_properties)) {
			if( isset($this->button_properties['title'])) {
				$this->title = $app_strings[$this->button_properties['title']];
			}
			if( isset($this->button_properties['accesskey'])) {
				$this->accesskey = $app_strings[$this->button_properties['accesskey']];
			}
			if( isset($this->button_properties['form_value'])) {
				$this->value = $app_strings[$this->button_properties['form_value']];
			}

			if( isset($this->button_properties['module'])) {
				$this->module_name = $this->button_properties['module'];
			}
		}
		
//		echo "OM Button ".$this->module_name;
//   	    $GLOBALS['log']->debug("Button properties ".$this->button_properties['module']);
		
		$focus = $widget_data['focus'];
		if(ACLController::moduleSupportsACL($widget_data['module']) && !ACLController::checkAccess($widget_data['module'], 'list', true)){
			$button = ' <input type="button" name="select_button" id="select_button" class="button"' . "\"\n"
			. ' title="' . $this->title . '"'
			. ' value="' . $this->value . "\"\n"
			.' disabled />';
			return $button;
		}

		//refresh the whole page after end of action?
		$refresh_page = 0;
		if(!empty($widget_data['subpanel_definition']->_instance_properties['refresh_page'])){
			$refresh_page = 1;
		}

		$subpanel_definition = $widget_data['subpanel_definition'];
		$button_definition = $subpanel_definition->get_buttons();
		$subpanel_name = $subpanel_definition->get_module_name();
		if (empty($this->module_name)) {
			$this->module_name = $subpanel_name;
		}
//		echo "OM Button ".$this->module_name;		
		$link_field_name = $subpanel_definition->get_data_source_name(true);
		$popup_mode='Single';
		if(isset($widget_data['mode'])){
			$popup_mode=$widget_data['mode'];
		}
		if(isset($widget_data['initial_filter_fields'])){
			if (is_array($widget_data['initial_filter_fields'])) {
				foreach ($widget_data['initial_filter_fields'] as $value=>$alias) {
					if (isset($focus->$value) and !empty($focus->$value)) {
						$initial_filter.="&".$alias . '='.$focus->$value;
					}
				}
			}
		}
				
		$create="true";
		if(isset($widget_data['create'])){
			$create=$widget_data['create'];
		}
		$return_module = $_REQUEST['module'];
		$return_action = 'SubPanelViewer';
		$return_id = $_REQUEST['record']; 
		
		//field_to_name_array
		$fton_array= array('id' => 'subpanel_id');
		if(isset($widget_data['field_to_name_array']) && is_array($widget_data['field_to_name_array'])){
			$fton_array=array_merge($fton_array,$widget_data['field_to_name_array']);
		}
			
		$return_url = "index.php?module=$return_module&action=$return_action&subpanel=$subpanel_name&record=$return_id&sugar_body_only=1";

		$popup_request_data = array(
			'call_back_function' => 'set_return_and_save_background',
			'form_name' => 'DetailView',
			'field_to_name_array' => $fton_array,
			'passthru_data' => array(
				'child_field' => $subpanel_name,
				'return_url' => urlencode($return_url),
				'link_field_name' => $link_field_name,
				'module_name' => $subpanel_name,
				'refresh_page'=>$refresh_page,
			),
		);

		if (is_array($this->button_properties) && !empty($this->button_properties['add_to_passthru_data'])) {
			$popup_request_data['passthru_data']= array_merge($popup_request_data['passthru_data'],$this->button_properties['add_to_passthru_data']);
		}		
		
		if (is_array($this->button_properties) && !empty($this->button_properties['add_to_passthru_data']['return_type'])) {
			
			if ($this->button_properties['add_to_passthru_data']['return_type']=='report') {
				$initial_filter = "&module_name=${widget_data['module']}";
			}
			
			if ($this->button_properties['add_to_passthru_data']['return_type']=='addtoprospectlist') {
				if (isset($widget_data['query'])) {
					$popup_request_data['passthru_data']['query']=$widget_data['query'];
				}
			}
		}
		
		$json_encoded_php_array = $this->_create_json_encoded_popup_request($popup_request_data);
//		$GLOBALS['log']->debug("Encoded php array ".$json_encoded_php_array);
		
		return '<form action="index.php">' . "\n"
			. ' <input type="button" name="select_button" id="select_button" class="button"' . "\"\n"
				. ' title="' . $this->title . '"'
			. ' accesskey="' . $this->accesskey . '"'
			. ' value="' . $this->value . "\"\n"
			. " onclick='open_popup(\"$this->module_name\",600,400,\"$initial_filter\",true,true,$json_encoded_php_array,\"$popup_mode\",$create);' /></form>\n";
	}
}
?>
