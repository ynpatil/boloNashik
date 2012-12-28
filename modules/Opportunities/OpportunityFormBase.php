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



class OpportunityFormBase{

function checkForDuplicates($prefix){
	require_once('include/formbase.php');
	require_once('modules/Opportunities/Opportunity.php');
	$focus = new Opportunity();
	$query = '';
	$baseQuery = 'select id, name, sales_stage,amount, date_closed  from opportunities where deleted!=1 and (';

	if(isset($_POST[$prefix.'name']) && !empty($_POST[$prefix.'name'])){
		$query = $baseQuery ."  name like '%".$_POST[$prefix.'name']."%'";
		$query .= getLikeForEachWord('name', $_POST[$prefix.'name']);
	}
	if(!empty($query)){
		$rows = array();

		$db = & PearDatabase::getInstance();
		$result = $db->query($query.')');
		if($db->getRowCount($result) == 0){
			return null;
		}
		for($i = 0; $i < $db->getRowCount($result); $i++){
			$rows[$i] = $db->fetchByAssoc($result, $i);
		}
		return $rows;
	}
	return null;
}

function buildTableForm($rows, $mod='Opportunities'){
	global $odd_bg, $even_bg;
	if(!empty($mod)){
	global $current_language;
	$mod_strings = return_module_language($current_language, $mod);
	}else global $mod_strings;
	global $app_strings;
	$cols = sizeof($rows[0]) * 2 + 1;
	$form = '<table width="100%"><tr><td>'.$mod_strings['MSG_DUPLICATE']. '</td></tr><tr><td height="20"></td></tr></table>';

	$form .= "<form action='index.php' method='post' name='dupOpps'><input type='hidden' name='selectedOpportunity' value=''>";
	$form .=  get_form_header($mod_strings['LBL_DUPLICATE'], "", '');
	$form .= "<table width='100%' cellpadding='0' cellspacing='0'>	<tr class='listViewThS1'>	<td class='listViewThS1'>&nbsp;</TD>";
	require_once('include/formbase.php');
	$form .= getPostToForm();
	if(isset($rows[0])){
		foreach ($rows[0] as $key=>$value){
			if($key != 'id'){
					$form .= "<td scope='col' class='listViewThS1'>". $mod_strings[$mod_strings['db_'.$key]]. "</td>";
		}}
		$form .= "</tr>";
	}

	$bgcolor = $odd_bg;
	$rowColor = 'oddListRowS1';
	foreach($rows as $row){

		$form .= "<tr class='$rowColor' bgcolor='$bgcolor'>";

		$form .= "<td width='1%' class='$rowColor' nowrap><a href='#' onclick='document.dupOpps.selectedOpportunity.value=\"${row['id']}\";document.dupOpps.submit();'>[${app_strings['LBL_SELECT_BUTTON_LABEL']}]</a>&nbsp;&nbsp;</td>";
		$wasSet = false;
		foreach ($row as $key=>$value){
				if($key != 'id'){
					if(!$wasSet){
					$form .= "<td scope='row' class='$rowColor'><a target='_blank' href='index.php?module=Opportunities&action=DetailView&record=${row['id']}'>$value</a></td>";
					$wasSet = true;
					}else{
					$form .= "<td class='$rowColor'><a target='_blank' href='index.php?module=Opportunities&action=DetailView&record=${row['id']}'>$value</a></td>";
					}
				}}

		if($rowColor == 'evenListRowS1'){
			$rowColor = 'oddListRowS1';
			$bgcolor = $odd_bg;
		}else{
			 $rowColor = 'evenListRowS1';
			 $bgcolor = $even_bg;
		}
		$form .= "</tr>";
	}
			$form .= "<tr class='listViewThS1'><td colspan='$cols' class='blackline'></td></tr>";
	$form .= "</table><BR><input type='submit' class='button' name='ContinueOpportunity' value='${mod_strings['LNK_NEW_OPPORTUNITY']}'></form>";

	return $form;

}

function getForm($prefix, $mod='Opportunities'){
	if(!ACLController::checkAccess('Opportunities', 'edit', true)){
		return '';
	}
if(!empty($mod)){
	global $current_language;
	$mod_strings = return_module_language($current_language, $mod);
}else global $mod_strings;
global $app_strings;
global $sugar_version, $sugar_config;


$lbl_save_button_title = $app_strings['LBL_SAVE_BUTTON_TITLE'];
$lbl_save_button_key = $app_strings['LBL_SAVE_BUTTON_KEY'];
$lbl_save_button_label = $app_strings['LBL_SAVE_BUTTON_LABEL'];


$the_form = get_left_form_header($mod_strings['LBL_NEW_FORM_TITLE']);
$the_form .= '<script type="text/javascript" src="include/javascript/popup_parent_helper.js?s=' . $sugar_version . '&c=' . $sugar_config['js_custom_version'] . '"></script>';
$the_form .= <<<EOQ
		<form name="{$prefix}OppSave" onSubmit="return check_form('{$prefix}OppSave')" method="POST" action="index.php">
			<input type="hidden" name="{$prefix}module" value="Opportunities">
			<input type="hidden" name="${prefix}action" value="Save">
EOQ;
$the_form .= $this->getFormBody($prefix, $mod, "{$prefix}OppSave");
$the_form .= <<<EOQ
		<input title="$lbl_save_button_title" accessKey="$lbl_save_button_key" class="button" type="submit" name="button" value="  $lbl_save_button_label  " >
		</form>

EOQ;
$the_form .= get_left_form_footer();
$the_form .= get_validate_record_js();

return $the_form;
}

function getWideFormBody($prefix, $mod='Opportunities', $formname='', $lead='', $showaccount = true){
	if(!ACLController::checkAccess('Opportunities', 'edit', true)){
		return '';
	}
	require_once('modules/Leads/Lead.php');
	if(empty($lead)){
		$lead = new Lead();
	}
global $mod_strings, $sugar_config;
$showaccount = $showaccount && $sugar_config['require_accounts'];
$temp_strings = $mod_strings;
if(!empty($mod)){
	global $current_language;
	$mod_strings = return_module_language($current_language, $mod);
}

global $app_strings;
global $app_list_strings;
global $theme;
global $current_user;
global $timedate;
// Unimplemented until jscalendar language files are fixed
// global $current_language;
// global $default_language;
// global $cal_codes;

$lbl_required_symbol = $app_strings['LBL_REQUIRED_SYMBOL'];
$lbl_opportunity_name = $mod_strings['LBL_OPPORTUNITY_NAME'];
$lbl_sales_stage = $mod_strings['LBL_SALES_STAGE'];
$lbl_date_closed = $mod_strings['LBL_DATE_CLOSED'];
$lbl_amount = $mod_strings['LBL_AMOUNT'];

$ntc_date_format = $timedate->get_user_date_format();
$cal_dateformat = $timedate->get_cal_date_format();
if (isset($lead->assigned_user_id)) {
	$user_id=$lead->assigned_user_id;
} else {
	$user_id = $current_user->id;
}








// Unimplemented until jscalendar language files are fixed
// $cal_lang = (empty($cal_codes[$current_language])) ? $cal_codes[$default_language] : $cal_codes[$current_language];
$cal_lang = "en";

$the_form="";



if (isset($lead->opportunity_amount)) {
	$opp_amount=$lead->opportunity_amount;
} else {
 	$opp_amount='';
}
$the_form .= <<<EOQ

			<input type="hidden" name="{$prefix}record" value="">
			<input type="hidden" name="{$prefix}account_name">
			<input type="hidden" name="{$prefix}assigned_user_id" value='${user_id}'>

<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
    <td width="20%" class="dataLabel">$lbl_opportunity_name&nbsp;<span class="required">$lbl_required_symbol</span></td>
    <td width="80%" class="dataLabel">{$mod_strings['LBL_DESCRIPTION']}</td>
</tr>
<tr>
    <td class="dataField"><input name='{$prefix}name' type="text" value="{$lead->opportunity_name}"></td>
	<td class="dataField" rowspan="7"><textarea name='{$prefix}description' rows='5' cols='50'></textarea></td>
</tr>
<tr>
    <td class="dataLabel">$lbl_date_closed&nbsp;<span class="required">$lbl_required_symbol</span></td>
</tr>
<tr>
    <td class="dataField"><input name='{$prefix}date_closed' onblur="parseDate(this, '$cal_dateformat');" size='12' maxlength='10' id='${prefix}jscal_field' type="text" value="">&nbsp;<img src="themes/$theme/images/jscalendar.gif" alt="{$app_strings['LBL_ENTER_DATE']}"  id="${prefix}jscal_trigger" align="absmiddle"></td>
</tr>
EOQ;
if($showaccount){
	$the_form .= <<<EOQ
<tr>
    <td class="dataLabel">${mod_strings['LBL_ACCOUNT_NAME']}&nbsp;<span class="required">${lbl_required_symbol}</span></td>
</tr>
<tr>
    <td class="dataField"><input readonly id='qc_account_name' name='account_name' type='text' value="" size="16"><input id='qc_account_id' name='account_id' type="hidden" value=''>&nbsp;<input  title="{$app_strings['LBL_SELECT_BUTTON_TITLE']}" accessKey="{$app_strings['LBL_SELECT_BUTTON_KEY']}" type="button" class="button" value='{$app_strings['LBL_SELECT_BUTTON_LABEL']}' name=btn1 LANGUAGE=javascript onclick='return window.open("index.php?module=Accounts&action=Popup&html=Popup_picker&form={$formname}&form_submit=false","","width=600,height=400,resizable=1,scrollbars=1");'></td>
</tr>
EOQ;
}
$the_form .= <<<EOQ
<tr>
    <td class="dataLabel">$lbl_sales_stage&nbsp;<span class="required">$lbl_required_symbol</span></td>
</tr>
<tr>
    <td class="dataField"><select name='{$prefix}sales_stage'>
EOQ;
$the_form .= get_select_options_with_id($app_list_strings['sales_stage_dom'], "");
$the_form .= <<<EOQ
		</select></td>
</tr>

EOQ;

//carry forward custom lead fields to opportunities during Lead Conversion
	$tempOpp = new Opportunity();
	if (method_exists($lead, 'convertCustomFieldsForm')) $lead->convertCustomFieldsForm($the_form, $tempOpp, $prefix);
	unset($tempOpp);

$the_form .= <<<EOQ

</table>

		<script type="text/javascript">
		Calendar.setup ({
			inputField : "{$prefix}jscal_field", ifFormat : "$cal_dateformat", showsTime : false, button : "${prefix}jscal_trigger", singleClick : true, step : 1
		});
		</script>


EOQ;

require_once('include/javascript/javascript.php');
require_once('modules/Opportunities/Opportunity.php');
$javascript = new javascript();
$javascript->setFormName($formname);
$javascript->setSugarBean(new Opportunity());
$javascript->addRequiredFields($prefix);
$the_form .=$javascript->getScript();
$mod_strings = $temp_strings;
return $the_form;

} // end getWideFormBody

function getFormBody($prefix, $mod='Opportunities', $formname=''){
	if(!ACLController::checkAccess('Opportunities', 'edit', true)){
		return '';
	}
if(!empty($mod)){
	global $current_language;
	$mod_strings = return_module_language($current_language, $mod);
}else global $mod_strings;
global $app_strings;
global $app_list_strings;
global $theme;
global $current_user;
global $sugar_config;
global $timedate;
// Unimplemented until jscalendar language files are fixed
// global $current_language;
// global $default_language;
// global $cal_codes;

$lbl_required_symbol = $app_strings['LBL_REQUIRED_SYMBOL'];
$lbl_opportunity_name = $mod_strings['LBL_OPPORTUNITY_NAME'];
$lbl_sales_stage = $mod_strings['LBL_SALES_STAGE'];
$lbl_date_closed = $mod_strings['LBL_DATE_CLOSED'];
$lbl_amount = $mod_strings['LBL_AMOUNT'];

$ntc_date_format = $timedate->get_user_date_format();
$cal_dateformat = $timedate->get_cal_date_format();

$user_id = $current_user->id;




// Unimplemented until jscalendar language files are fixed
// $cal_lang = (empty($cal_codes[$current_language])) ? $cal_codes[$default_language] : $cal_codes[$current_language];
$cal_lang = "en";

$the_form = <<<EOQ
<p>
			<input type="hidden" name="{$prefix}record" value="">
			<input type="hidden" name="{$prefix}assigned_user_id" value='${user_id}'>




		$lbl_opportunity_name&nbsp;<span class="required">$lbl_required_symbol</span><br>
		<input style='width: 110px' name='{$prefix}name' type="text" value=""><br>
EOQ;
if($sugar_config['require_accounts']){

///////////////////////////////////////
///
/// SETUP ACCOUNT POPUP

$popup_request_data = array(
	'call_back_function' => 'set_return',
	'form_name' => "{$prefix}OppSave",
	'field_to_name_array' => array(
		'id' => 'account_id',
		'name' => 'account_name',
		),
	);

$json = getJSONobj();
$encoded_popup_request_data = $json->encode($popup_request_data);

//
///////////////////////////////////////

$the_form .= <<<EOQ
		${mod_strings['LBL_ACCOUNT_NAME']}&nbsp;<span class="required">${lbl_required_symbol}</span><br>
		<input id='qc_account_id' name='account_id' type="hidden" value=''>
		<input style='width: 110px' class='sqsEnabled' autocomplete='off' id='qc_account_name' name='account_name' type='text' value="" size="16">
		<input title="{$app_strings['LBL_SELECT_BUTTON_TITLE']}"
			   style='width: 20px'
		       accessKey="{$app_strings['LBL_SELECT_BUTTON_KEY']}"
		       type="button"
		       class="button"
		       value='..'
		       name=btn1
			   onclick='open_popup("Accounts", 600, 400, "", true, false, {$encoded_popup_request_data});' />
		<br>
EOQ;
}
$the_form .= <<<EOQ
		$lbl_date_closed&nbsp;<span class="required">$lbl_required_symbol</span> <br>
		<input name='{$prefix}date_closed' size='10' maxlength='10' id='{$prefix}jscal_field' type="text" value=""> <img src="themes/$theme/images/jscalendar.gif" alt="{$app_strings['LBL_ENTER_DATE']}"  id="jscal_trigger" align="absmiddle">
		<span class="dateFormat"><font size=1>$ntc_date_format</font></span><br>
		$lbl_sales_stage&nbsp;<span class="required">$lbl_required_symbol</span><br>
		<select style='width: 110px' name='{$prefix}sales_stage'>
EOQ;
$the_form .= get_select_options_with_id($app_list_strings['sales_stage_dom'], "");
$the_form .= <<<EOQ
		</select><br>
		$lbl_amount&nbsp;<span class="required">$lbl_required_symbol</span><br>
		<input style='width: 110px' name='{$prefix}amount' type="text"></p>
		<input type='hidden' name='lead_source' value=''>
		<script type="text/javascript">
		Calendar.setup ({
			inputField : "{$prefix}jscal_field", ifFormat : "$cal_dateformat", showsTime : false, button : "jscal_trigger", singleClick : true, step : 1
		});
		</script>
EOQ;

/* begin Lampada change */
$lbl_teams = $mod_strings['Assigned_to_Team_c'];
//print_r($mod_strings);
require_once('modules/TeamsOS/TeamOS.php');
$focus_team = new TeamOS();
//$the_form = substr($form,0,strlen($form)-strlen(strrchr($form,"</p>"))); //remove the trailing <br>
$the_form .= $lbl_teams . "<br><select name='assigned_team_id_c' id='assigned_team_id_c' style='width: 110px'>";
$the_form .= $focus_team->get_default_team_select($current_user->default_team_id_c, $current_user->id);
$the_form .= "</select></p>";
/* end Lampada change */

require_once('include/QuickSearchDefaults.php');
$qsd = new QuickSearchDefaults();
$sqs_objects = array('qc_account_name' => $qsd->getQSParent());
$sqs_objects['qc_account_name']['populate_list'] = array('qc_account_name', 'qc_account_id');
$quicksearch_js = $qsd->getQSScripts();
$quicksearch_js .= '<script type="text/javascript" language="javascript">sqs_objects = ' . $json->encode($sqs_objects) . '</script>';
$the_form .= $quicksearch_js;

require_once('include/javascript/javascript.php');
require_once('modules/Opportunities/Opportunity.php');
$javascript = new javascript();
$javascript->setFormName($formname);
$javascript->setSugarBean(new Opportunity());
$javascript->addRequiredFields($prefix);
$the_form .=$javascript->getScript();


return $the_form;

}

function handleSave($prefix,$redirect=true, $useRequired=false){
    global $current_user;
	require_once('modules/Opportunities/Opportunity.php');

	require_once('include/formbase.php');

	$focus = new Opportunity();
	if($useRequired &&  !checkRequired($prefix, array_keys($focus->required_fields))){
		return null;
	}

    if(empty($_POST['currency_id'])){
        $currency_id = $current_user->getPreference('currency');
        if(isset($currency_id)){
            $focus->currency_id =   $currency_id;
        }
    }
	$focus = populateFromPost($prefix, $focus);
	if( !ACLController::checkAccess($focus->module_dir, 'edit', $focus->isOwner($current_user->id))){
		ACLController::displayNoAccess(true);
	}
	$check_notify = FALSE;
	if (isset($GLOBALS['check_notify'])) {
		$check_notify = $GLOBALS['check_notify'];
	}

	$focus->save($check_notify);

	if(!empty($_POST['duplicate_parent_id'])){
		clone_relationship($focus->db, array('opportunities_contacts'),'opportunity_id',  $_POST['duplicate_parent_id'], $focus->id);
	}
	$return_id = $focus->id;
	
	///review creation when opportunity is closed///
	
//        $rel_name = "users";
//	$focus->load_relationship($rel_name);
//        $user_array = $focus->$rel_name->get();
//        
//        $user_present = false;
//	foreach($user_array as $user){
//        	if($user->user_id == $focus->assigned_user_id){
//                    $user_present = true;
//                    break;                    
//                }
//	}
//        
//        if(!$user_present){
//            $focus->$rel_name->add($focus->assigned_user_id);
//        }
//
//        $GLOBALS['log']->debug("Sales Stage ".$focus->sales_stage);
        
	if($focus->sales_stage == "Closed Won" || $focus->sales_stage == "Closed Lost"){
		
		$rel_name = "opportunity_reviews";
		$focus->load_relationship($rel_name);
		$data = $focus->$rel_name->get();

		if(count($data) == 0){			
			require_once("modules/Reviews/Review.php");
			$focusReview = new Review();
			$focusReview->name = "Review for ".$focus->name;
			$focusReview->assigned_user_id = ((isset($current_user->reports_to_id) && !empty($current_user->reports_to_id))?$current_user->reports_to_id:$current_user->id);
			$focusReview->parent_type = $focus->module_dir;
			$focusReview->parent_id = $return_id;
			$focusReview->save($check_notify);
			$focus->$rel_name->add($focusReview->id);
		}
	}
	
	$GLOBALS['log']->debug("Saved record with id of ".$return_id);
	if($redirect){
		handleRedirect($return_id,"Opportunities" );
	}else{
		return $focus;
	}
}

}
?>
