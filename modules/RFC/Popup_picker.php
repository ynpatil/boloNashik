<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * Popup Picker
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

// $Id: Popup_picker.php,v 1.14 2006/06/06 17:57:55 majed Exp $

require_once('XTemplate/xtpl.php');
require_once("data/Tracker.php");
require_once("include/upload_file.php");
require_once('include/modules.php');
require_once('include/utils/db_utils.php');
require_once('modules/RFC/RFC.php');

global $currentModule, $focus, $action, $app_strings, $app_list_strings, $current_language, $timedate;
//we don't want the parent module's string file, but rather the string file specifc to this subpanel

$current_module_strings = return_module_language($current_language, 'RFC');

$bean = $beanList[$_REQUEST['module_name']];
require_once($beanFiles[$bean]);
$focus = new $bean;

class Popup_Picker
{
	/*
	 *
	 */
	function Popup_Picker()
	{
	}

	/**
	 *
	 */
	function process_page()
	{
		global $theme;
		global $focus;
		global $mod_strings;
		global $app_strings;
		global $app_list_strings;
		global $currentModule;
		global $odd_bg;
 		global $even_bg;
 		global $image_path;
        global $audit;
        global $current_language;

 		$theme_path="themes/".$theme."/";
 		$image_path = 'themes/'.$theme.'/images/';
 		require_once($theme_path.'layout_utils.php');

		$audit_list =  RFC::get_rfc_list($_REQUEST['owner']);
		
		if($_REQUEST['owner'] && $_REQUEST['owner'] == "1")
        $xtpl=new XTemplate ('modules/RFC/Popup_picker.html');
		else
        $xtpl=new XTemplate ('modules/RFC/Popup_picker_readonly.html');
        		        
		$xtpl->assign('THEME', $theme);
		$xtpl->assign('MOD', $mod_strings);
		$xtpl->assign('APP', $app_strings);
		$xtpl->assign('OBJECT_TYPE', $focus->object_name);
		insert_popup_header($theme);

		//output header
		echo "<table width='100%' cellpadding='0' cellspacing='0'><tr><td>";
		$focus_mod_strings = return_module_language($current_language, $focus->module_dir);
		echo get_module_title($focus->module_dir, translate('LBL_MODULE_NAME', $focus->module_dir).": ".$focus->name, false);
		echo "</td><td align='right' class='moduleTitle'>";
		echo "<A href='javascript:print();' class='utilsLink'><img src='".$image_path."print.gif' width='13' height='13' alt='".$app_strings['LNK_PRINT']."' border='0' align='absmiddle'></a>&nbsp;<A href='javascript:print();' class='utilsLink'>".$app_strings['LNK_PRINT']."</A>\n";
		echo "</td></tr></table>";

		$oddRow = true;
		$audited_fields = $focus->field_defs;
		asort($audited_fields);
		$fields = '';
		$field_count = count($audited_fields);
		$start_tag = "<table><tr><td class=\"listViewPaginationLinkS1\">";
		$end_tag = "</td></tr></table>";
	
		foreach($audit_list as $audit)
		{
//			$GLOBALS['log']->debug("Audit list :".implode("/",array_keys($audit)));
			if(isset($audit['before_value_string']) || isset($audit['after_value_string']))
			{
				$before_value = $audit['before_value_string'];
				$after_value = $audit['after_value_string'];
			}
			else
			{
				$before_value = $audit['before_value_text'];
				$after_value = $audit['after_value_text'];
			}
			
			if(isset($audit['after_value_string_org']))
				$after_value_org = $audit['after_value_string_org'];
			else
				$after_value_org = $after_value;

//			$GLOBALS['log']->debug("After value org :".$after_value_org);
//			$GLOBALS['log']->debug("After value :".$after_value);
						
            $activity_fields = array(
                'ID' => $audit['id'],
			    'NAME' => $audit['field_name'],
                'BEFORE_VALUE' => $before_value,
                'AFTER_VALUE' => $after_value,
                'CREATED_BY' => $audit['created_by'],
                'DATE_CREATED' => $audit['date_created'],
                'STATUS' => $audit['status'],
                'COL_ID' => $audit['id'],
                'COL_IMG' => $audit['id']."_img",
        		'ACTION_PNG' => ($audit['status'] == "Accepted" || $audit['status'] == "Rejected")?"":"<form id=\"SetStatus_accept".$audit['id']."\">
				<input type=\"hidden\" name=\"is_ajax_call\" value=\"1\">
				<input type=\"hidden\" name=\"".$audit['field_name_db']."\" value=\"".$after_value_org."\">
				<div id=\"accept".$audit['id']."\"><a title=\"".
	              "\" href=\"javascript:SUGAR.util.retrieveAndFill('index.php?module=".$focus->module_dir."&is_ajax_call=1&action=Save&record=".
                $focus->id."&object_type=".$focus->object_name."&object_id=".$focus->id."', 'accept.".$audit['id']."','SetStatus_accept".$audit['id']."', setAcceptStatus, '{$audit['id']}');\">".
	             get_image($image_path."accept_inline","alt='Accept' border='0'")."</a></div></form>
				<div id=\"reject".$audit['id']."\"><a title=\"".
	              "\" href=\"javascript:setRejectStatus('".$audit['id']."');\">".
	             get_image($image_path."decline_inline","alt='Reject' border='0'")."</a></div>",
                );
            
//            $GLOBALS['log']->debug("Link :".$activity_fields['ACTION_PNG']);

			$xtpl->assign("ACTIVITY", $activity_fields);

			if($oddRow)
   			{
        		//todo move to themes
				$xtpl->assign("ROW_COLOR", 'oddListRow');
				$xtpl->assign("BG_COLOR", $odd_bg);
    		}
    		else
    		{
        		//todo move to themes
				$xtpl->assign("ROW_COLOR", 'evenListRow');
				$xtpl->assign("BG_COLOR", $even_bg);
    		}
   			$oddRow = !$oddRow;

			$xtpl->parse("audit.row");
		// Put the rows in.
        }//end foreach

		$xtpl->parse("audit");
		$xtpl->out("audit");
		insert_popup_footer();
    }
} // end of class Popup_Picker
?>
