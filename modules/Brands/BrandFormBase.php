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
 * $Id: BrandFormBase.php,v 1.52 2006/08/25 22:42:05 jenny Exp $
 * Description:  base form for brand
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

class BrandFormBase{

function checkForDuplicates($prefix){
	require_once('include/formbase.php');
	require_once('modules/Brands/Brand.php');
	$focus = new Brand();
	$query = '';
	$baseQuery = 'select id, name from brands where deleted!=1 and ';
	if(!empty($_POST[$prefix.'name'])){
		$query = $baseQuery ."  name like '".$_POST[$prefix.'name']."%'";
	}

	if(!empty($query)){
		$rows = array();
		global $db;
		$result = $db->query($query);
		$i=-1;
		while(($row=$db->fetchByAssoc($result)) != null) {
			$i++;
			$rows[$i] = $row;
		}
		if ($i==-1) return null;

		return $rows;
	}
	return null;
}

function buildTableForm($rows, $mod='Brands'){
	if(!ACLController::checkAccess('Brands', 'edit', true)){
		return '';
	}
	global $odd_bg, $even_bg, $action;
	if(!empty($mod)){
	global $current_language;
	$mod_strings = return_module_language($current_language, $mod);
	}else global $mod_strings;
	global $app_strings;
	$cols = sizeof($rows[0]) * 2 + 1;
	if ($action != 'ShowDuplicates')
	{
		$form = "<form action='index.php' method='post' id='dupBrands'  name='dupBrands'><input type='hidden' name='selectedBrand' value=''>";
		$form .= '<table width="100%"><tr><td>'.$mod_strings['MSG_DUPLICATE']. '</td></tr><tr><td height="20"></td></tr></table>';
		unset($_POST['selectedBrand']);
	}
	else
	{
		$form = '<table width="100%"><tr><td>'.$mod_strings['MSG_SHOW_DUPLICATES']. '</td></tr><tr><td height="20"></td></tr></table>';
	}

	$form .=  get_form_header($mod_strings['LBL_DUPLICATE'], "", '');
	$form .= "<table width='100%' cellpadding='0' cellspacing='0'>	<tr class='listViewThS1'>	";
	if ($action != 'ShowDuplicates')
	{
		$form .= "<td class='listViewThS1'> &nbsp;</td>";
	}
	require_once('include/formbase.php');
	$form .= getPostToForm();
	if(isset($rows[0])){
		foreach ($rows[0] as $key=>$value){
			if($key != 'id'){

					$form .= "<td class='listViewThS1'>". $mod_strings[$mod_strings['db_'.$key]]. "</td>";
		}}

		$form .= "</tr>";
	}

	$bgcolor = $odd_bg;
	$rowColor = 'oddListRowS1';
	foreach($rows as $row){

		$form .= "<tr class='$rowColor'  bgcolor='$bgcolor'>";
		if ($action != 'ShowDuplicates')
		{
		$form .= "<td width='1%' class='$rowColor' nowrap><a href='#' onclick='document.dupBrands.selectedBrand.value=\"${row['id']}\"; document.dupBrands.submit(); '>[${app_strings['LBL_SELECT_BUTTON_LABEL']}]</a>&nbsp;&nbsp;</td>\n";
		}
		foreach ($row as $key=>$value){
				if($key != 'id'){

					$form .= "<td class='$rowColor'><a target='_blank' href='index.php?module=Brands&action=DetailView&record=${row['id']}'>$value</a></td>\n";

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

	// handle buttons
	if ($action == 'ShowDuplicates') {
		$return_action = 'ListView'; // cn: bug 6658 - hardcoded return action break popup -> create -> duplicate -> cancel
		$return_action = (isset($_REQUEST['return_action']) && !empty($_REQUEST['return_action'])) ? $_REQUEST['return_action'] : $return_action;
		$form .= "</table><br><input type='hidden' name='selectedBrand' id='selectedBrand' value=''><input title='${app_strings['LBL_SAVE_BUTTON_TITLE']}' accessKey='${app_strings['LBL_SAVE_BUTTON_KEY']}' class='button' onclick=\"this.form.action.value='Save';\" type='submit' name='button' value='  ${app_strings['LBL_SAVE_BUTTON_LABEL']}  '>";

        if (!empty($_REQUEST['return_module']) && !empty($_REQUEST['return_action']) && !empty($_REQUEST['return_id']))
            $form .= "<input title='${app_strings['LBL_CANCEL_BUTTON_TITLE']}' accessKey='${app_strings['LBL_CANCEL_BUTTON_KEY']}' class='button' onclick=\"location.href='index.php?module=".$_REQUEST['return_module']."&action=".$_REQUEST['return_action']."&record=".$_REQUEST['return_id']."'\" type='button' name='button' value='  ${app_strings['LBL_CANCEL_BUTTON_LABEL']}  '></form>";
        else
            $form .= "<input title='${app_strings['LBL_CANCEL_BUTTON_TITLE']}' accessKey='${app_strings['LBL_CANCEL_BUTTON_KEY']}' class='button' onclick=\"location.href='index.php?module=Brands&action=ListView'\" type='button' name='button' value='  ${app_strings['LBL_CANCEL_BUTTON_LABEL']}  '></form>";
	} else {
		$form .= "</table><BR><input type='submit' class='button' name='ContinueBrand' value='${mod_strings['LNK_NEW_BRAND']}'></form>\n";
	}
	return $form;
}

function getForm($prefix, $mod='', $form=''){
	if(!ACLController::checkAccess('Brands', 'edit', true)){
		return '';
	}
if(!empty($mod)){
	global $current_language;
	$mod_strings = return_module_language($current_language, $mod);
}else global $mod_strings;
global $app_strings;
$lbl_save_button_title = $app_strings['LBL_SAVE_BUTTON_TITLE'];
$lbl_save_button_key = $app_strings['LBL_SAVE_BUTTON_KEY'];
$lbl_save_button_label = $app_strings['LBL_SAVE_BUTTON_LABEL'];

$the_form = get_left_form_header($mod_strings['LBL_NEW_FORM_TITLE']);
$the_form .= <<<EOQ
		<form name="${prefix}BrandSave" onSubmit="return check_form('${prefix}BrandSave');" method="POST" action="index.php">
			<input type="hidden" name="${prefix}module" value="Brands">
			<input type="hidden" name="${prefix}action" value="Save">
EOQ;
$the_form .= $this->getFormBody($prefix, $mod, $prefix."BrandSave");
$the_form .= <<<EOQ
		<p><input title="$lbl_save_button_title" accessKey="$lbl_save_button_key" class="button" type="submit" name="button" value="  $lbl_save_button_label  " ></p>
		</form>

EOQ;
$the_form .= get_left_form_footer();
$the_form .= get_validate_record_js();

return $the_form;
}

function getFormBody($prefix,$mod='', $formname=''){
	if(!ACLController::checkAccess('Brands', 'edit', true)){
		return '';
	}
global $mod_strings;
$temp_strings = $mod_strings;
if(!empty($mod)){
	global $current_language;
	$mod_strings = return_module_language($current_language, $mod);
}
	global $app_strings;
global $current_user;

$lbl_required_symbol = $app_strings['LBL_REQUIRED_SYMBOL'];
$lbl_brand_name = $mod_strings['LBL_BRAND_NAME'];
$lbl_primary_account = $mod_strings['LBL_PRIMARY_ACCOUNT'];
$lbl_save_button_title = $app_strings['LBL_SAVE_BUTTON_TITLE'];
$lbl_save_button_key = $app_strings['LBL_SAVE_BUTTON_KEY'];
$lbl_save_button_label = $app_strings['LBL_SAVE_BUTTON_LABEL'];
$user_id = $current_user->id;
$account_options = get_select_options_with_id(get_account_array(), urldecode($_REQUEST['account_id']));
	$form = <<<EOQ
			<p><input type="hidden" name="record" value="">
			<input type="hidden" name="assigned_user_id" value='${user_id}'>
			<input type="hidden" name="action" value="Save">
		$lbl_brand_name&nbsp;<span class="required">$lbl_required_symbol</span><br>
		<input name='name' type="text" value=""><br>
		$lbl_primary_account&nbsp;<span class="required">$lbl_required_symbol</span><br>
		<select name="account_id">
		$account_options
		</select>		
</p>

EOQ;
require_once('include/javascript/javascript.php');
require_once('modules/Brands/Brand.php');
$javascript = new javascript();
$javascript->setFormName($formname);
$javascript->setSugarBean(new Brand());
$javascript->addRequiredFields($prefix);
$form .=$javascript->getScript();
$mod_strings = $temp_strings;
return $form;
}

function getWideFormBody($prefix, $mod='',$formname=''){
	if(!ACLController::checkAccess('Brands', 'edit', true)){
		return '';
	}
	require_once('modules/Brands/Brand.php');
	
	global $mod_strings;
	$temp_strings = $mod_strings;
	if(!empty($mod)){
		global $current_language;
		$mod_strings = return_module_language($current_language, $mod);
	}
	global $app_strings;
	global $current_user;
	
	$brand = new Brand();
	
	$lbl_required_symbol = $app_strings['LBL_REQUIRED_SYMBOL'];
	$lbl_name = $mod_strings['LBL_NAME'];
	$user_id = $current_user->id;
	$form="";

	$form .= <<<EOQ
		<input type="hidden" name="${prefix}record" value="">
		<input type="hidden" name="${prefix}assigned_user_id" value='${user_id}'>
		<table width='100%' border="0" cellpadding="0" cellspacing="0">
		<tr>
		<td width="20%" nowrap class="dataLabel">$lbl_name&nbsp;<span class="required">$lbl_required_symbol</span></td>
		<TD width="80%" nowrap class="dataLabel">
		<input name='{$prefix}name' type="text" value="$brand->name" size="30"></TD>
		</tr>
		<tr>
		<td nowrap class="dataField">{$mod_strings['LBL_BRAND_POSITIONING']}</td>
		<TD class="dataField"><textarea name='{$prefix}brand_pos' rows='6' cols='50'></textarea></TD>
		</tr>
EOQ;
	//carry forward custom lead fields common to accounts during Lead Conversion
	$tempBrand = new Brand();
	if (method_exists($brand, 'convertCustomFieldsForm')) $brand->convertCustomFieldsForm($form, $tempBrand, $prefix);
	unset($tempBrand);
	$form .= <<<EOQ
	</TABLE>
EOQ;
	require_once('include/javascript/javascript.php');
	require_once('modules/Brands/Brand.php');
	$javascript = new javascript();
	$javascript->setFormName($formname);
	$javascript->setSugarBean(new Brand());
	$javascript->addRequiredFields($prefix);
	$form .=$javascript->getScript();
	$mod_strings = $temp_strings;
	return $form;
}

function handleSave($prefix,$redirect=true, $useRequired=false){

	require_once('modules/Brands/Brand.php');
    require_once ('include/utils.php');
	require_once('include/formbase.php');

	$focus = new Brand();

	if($useRequired &&  !checkRequired($prefix, array_keys($focus->required_fields))){
		return null;
	}
	$focus = populateFromPost($prefix, $focus);
	if (isset($GLOBALS['check_notify'])) {
		$check_notify = $GLOBALS['check_notify'];
	}
	else {
		$check_notify = FALSE;
	}

	if (empty($_POST['record']) && empty($_POST['dup_checked'])) {
		$duplicateBrands = $this->checkForDuplicates($prefix);
		if(isset($duplicateBrands)){
			$get='module=Brands&action=ShowDuplicates';

			//add all of the post fields to redirect get string
			foreach ($focus->column_fields as $field)
			{
				if (!empty($focus->$field))
				{
					$get .= "&Brands$field=".urlencode($focus->$field);
				}
			}

			foreach ($focus->additional_column_fields as $field)
			{
				if (!empty($focus->$field))
				{
					$get .= "&Brands$field=".urlencode($focus->$field);
				}
			}

			//create list of suspected duplicate brand id's in redirect get string
			$i=0;
			foreach ($duplicateBrands as $brand)
			{
				$get .= "&duplicate[$i]=".$brand['id'];
				$i++;
			}

			//add return_module, return_action, and return_id to redirect get string
			$get .= '&return_module=';
			if(!empty($_POST['return_module'])) $get .= $_POST['return_module'];
			else $get .= 'Brands';
			$get .= '&return_action=';
			if(!empty($_POST['return_action'])) $get .= $_POST['return_action'];
			else $get .= 'DetailView';
			if(!empty($_POST['return_id'])) $get .= '&return_id='.$_POST['return_id'];
			if(!empty($_POST['popup'])) $get .= '&popup='.$_POST['popup'];
			if(!empty($_POST['create'])) $get .= '&create='.$_POST['create'];

			//echo $get;
			//die;
			//now redirect the post to modules/Brands/ShowDuplicates.php
            if (!empty($_POST['is_ajax_call']) && $_POST['is_ajax_call'] == '1')
            {
                $json = getJSONobj();
                echo $json->encode(array('status' => 'dupe',
                                         'get' => $get));
            }
            else {
                if(!empty($_POST['to_pdf'])) $get .= '&to_pdf='.$_POST['to_pdf'];
                header("Location: index.php?$get");
            }
			return null;
		}
	}
	if(!$focus->ACLAccess('Save')){
		ACLController::displayNoAccess(true);
		sugar_cleanup(true);
	}
	$focus->save($check_notify);
	$return_id = $focus->id;
	$GLOBALS['log']->debug("Saved record with id of ".$return_id);


    if (!empty($_POST['is_ajax_call']) && $_POST['is_ajax_call'] == '1') {
        $json = getJSONobj();
        echo $json->encode(array('status' => 'success',
                                 'get' => ''));
        return null;
    }

	if(isset($_POST['popup']) && $_POST['popup'] == 'true') {
		$get = '&module=';
		if(!empty($_POST['return_module'])) $get .= $_POST['return_module'];
		else $get .= 'Brands';
		$get .= '&action=';
		if(!empty($_POST['return_action'])) $get .= $_POST['return_action'];
		else $get .= 'Popup';
		if(!empty($_POST['return_id'])) $get .= '&return_id='.$_POST['return_id'];
		if(!empty($_POST['popup'])) $get .= '&popup='.$_POST['popup'];
		if(!empty($_POST['create'])) $get .= '&create='.$_POST['create'];
		if(!empty($_POST['to_pdf'])) $get .= '&to_pdf='.$_POST['to_pdf'];
		$get .= '&name=' . $focus->name;
		$get .= '&query=true';
		header("Location: index.php?$get");
		return;
	}

	if($redirect){
		handleRedirect($return_id,'Brands');
	}else{
		return $focus;
	}
}


}
?>
