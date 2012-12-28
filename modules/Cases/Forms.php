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
 * Contributor(s): Ken Brill (TeamsOS)
 ********************************************************************************/

//UPDATED FOR TeamsOS 3.0c by Ken Brill Jan 7th, 2007

/**
 * Create javascript to validate the data entered into a record.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function get_validate_record_js () {

}

/**
 * Create HTML form to enter a new record with the minimum necessary fields.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function get_new_record_form () {
global $mod_strings;
global $app_strings;
global $app_list_strings;
global $theme;
global $current_user;
global $sugar_version, $sugar_config;

$lbl_required_symbol = $app_strings['LBL_REQUIRED_SYMBOL'];
$lbl_default_status = $app_list_strings['case_status_default_key'];
$lbl_subject = $mod_strings['LBL_SUBJECT'];
$lbl_save_button_title = $app_strings['LBL_SAVE_BUTTON_TITLE'];
$lbl_save_button_key = $app_strings['LBL_SAVE_BUTTON_KEY'];
$lbl_save_button_label = $app_strings['LBL_SAVE_BUTTON_LABEL'];
$user_id = $current_user->id;




$the_form = get_left_form_header($mod_strings['LBL_NEW_FORM_TITLE']);
$the_form .= '<script type="text/javascript" src="include/javascript/popup_parent_helper.js?s=' . $sugar_version . '&c=' . $sugar_config['js_custom_version'] . '"></script>';
$the_form .= <<<EOQ
		<form name="CaseSave" onSubmit="return check_form('CaseSave')" method="POST" action="index.php">
			<input type="hidden" name="module" value="Cases">
			<input type="hidden" name="record" value="">
			<input type="hidden" name="priority" value="P2">
			<input type="hidden" name="status" value="${lbl_default_status}">
			<input type="hidden" name="assigned_user_id" value='${user_id}'>
			<input type="hidden" name="action" value="Save">



		${lbl_subject}&nbsp;<span class="required">${lbl_required_symbol}</span><br>
		<p><input style='width: 120px' name='name' type="text" size='27' maxlength="255"value=""><br>
EOQ;
global $sugar_config;
if($sugar_config['require_accounts']){

///////////////////////////////////////
///
/// SETUP ACCOUNT POPUP

$popup_request_data = array(
	'call_back_function' => 'set_return',
	'form_name' => "CaseSave",
	'field_to_name_array' => array(
		'id' => 'account_id',
		'name' => 'account_name',
		),
	);

$json = getJSONobj();
$encoded_popup_request_data = $json->encode($popup_request_data);

//
///////////////////////////////////////
/* begin Lampada change */
$the_form .= <<<EOQ
		${mod_strings['LBL_ACCOUNT_NAME']}&nbsp;<span class="required">${lbl_required_symbol}</span><br>
<input style='width: 100px' type="text" name="account_name" id="account_name" readonly="readonly" value="" size="16">
<input type="hidden" name="account_id" id="account_id" value="">
<input type="button" name="btn1" class="button" title="{$app_strings['LBL_SELECT_BUTTON_TITLE']}" accesskey="{$app_strings['LBL_SELECT_BUTTON_KEY']}" value='..'
	onclick='open_popup("Accounts", 600, 400, "", true, false, {$encoded_popup_request_data});' /><br>
EOQ;
}


$lbl_teams = $mod_strings['Assigned_to_Team_c'];
//print_r($mod_strings);
require_once('modules/TeamsOS/TeamOS.php');
$focus_team = new TeamOS();
//$the_form = substr($form,0,strlen($form)-strlen(strrchr($form,"<br>"))); //remove the trailing <br>
$the_form .= $lbl_teams . "<br><select name='assigned_team_id_c' id='assigned_team_id_c' style='width: 120px'>";
$the_form .= $focus_team->get_default_team_select($current_user->default_team_id_c, $current_user->id);
$the_form .= "</select><br>";
/* end Lampada change */

$the_form .= <<<EOQ
<p>		<input title="${lbl_save_button_title}" accessKey="${lbl_save_button_key}" class="button" type="submit" name="button" value="  ${lbl_save_button_label}  " ></p>

		</form>
EOQ;
require_once('include/javascript/javascript.php');
require_once('modules/Cases/Case.php');
$javascript = new javascript();
$javascript->setFormName('CaseSave');
$javascript->setSugarBean(new aCase());
$javascript->addRequiredFields('');
$the_form .=$javascript->getScript();
$the_form .= get_left_form_footer();

return $the_form;
}

?>
