<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * SugarWidgetSubPanelTopCreateAccountNameButton
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
 
// $Id: SugarWidgetSubPanelTopCreateLeadNameButton.php,v 1.3 2006/06/08 18:34:53 wayne Exp $

require_once('include/generic/SugarWidgets/SugarWidgetSubPanelTopButtonQuickCreate.php');

class SugarWidgetSubPanelTopCreateLeadNameButton extends SugarWidgetSubPanelTopButtonQuickCreate
{
	function display($defines)
	{
	
		
		global $app_strings;
		global $currentModule;

		$title = $app_strings['LBL_NEW_BUTTON_TITLE'];
		$accesskey = $app_strings['LBL_NEW_BUTTON_KEY'];
		$value = $app_strings['LBL_NEW_BUTTON_LABEL'];
		$this->module = 'Leads';
		if( ACLController::moduleSupportsACL($defines['module'])  && !ACLController::checkAccess($defines['module'], 'edit', true)){
			$button = "<input title='$title'class='button' type='button' name='button' value='  $value  ' disabled/>\n";
			return $button;
		}
		
		$additionalFormFields = array();
		
		//from accounts
		if ($defines['focus']->object_name == 'Account') {
			if(isset($defines['focus']->billing_address_street)) 
				$additionalFormFields['primary_address_street'] = $defines['focus']->billing_address_street;
			if(isset($defines['focus']->billing_address_city)) 
				$additionalFormFields['primary_address_city'] = $defines['focus']->billing_address_city;						  		
			if(isset($defines['focus']->billing_address_state)) 
				$additionalFormFields['primary_address_state'] = $defines['focus']->billing_address_state;
			if(isset($defines['focus']->billing_address_country)) 
				$additionalFormFields['primary_address_country'] = $defines['focus']->billing_address_country;
			if(isset($defines['focus']->billing_address_postalcode)) 
				$additionalFormFields['primary_address_postalcode'] = $defines['focus']->billing_address_postalcode;
			if(isset($defines['focus']->phone_office)) 
				$additionalFormFields['phone_work'] = $defines['focus']->phone_office;
			if(isset($defines['focus']->id)) 
				$additionalFormFields['account_id'] = $defines['focus']->id;
		}
		//from contacts
		if ($defines['focus']->object_name == 'Contact') {
			if(isset($defines['focus']->salutation)) 
				$additionalFormFields['salutation'] = $defines['focus']->salutation;
			if(isset($defines['focus']->first_name)) 
				$additionalFormFields['first_name'] = $defines['focus']->first_name;
			if(isset($defines['focus']->last_name)) 
				$additionalFormFields['last_name'] = $defines['focus']->last_name;
			if(isset($defines['focus']->primary_address_street)) 
				$additionalFormFields['primary_address_street'] = $defines['focus']->primary_address_street;
			if(isset($defines['focus']->primary_address_city)) 
				$additionalFormFields['primary_address_city'] = $defines['focus']->primary_address_city;						  		
			if(isset($defines['focus']->primary_address_state)) 
				$additionalFormFields['primary_address_state'] = $defines['focus']->primary_address_state;
			if(isset($defines['focus']->primary_address_country)) 
				$additionalFormFields['primary_address_country'] = $defines['focus']->primary_address_country;
			if(isset($defines['focus']->primary_address_postalcode)) 
				$additionalFormFields['primary_address_postalcode'] = $defines['focus']->primary_address_postalcode;
			if(isset($defines['focus']->phone_work)) 
				$additionalFormFields['phone_work'] = $defines['focus']->phone_work;
			if(isset($defines['focus']->id)) 
				$additionalFormFields['contact_id'] = $defines['focus']->id;
		}
		
		//from opportunities
		if ($defines['focus']->object_name == 'Opportunity') {
			if(isset($defines['focus']->id)) 
				$additionalFormFields['opportunity_id'] = $defines['focus']->id;
			if(isset($defines['focus']->account_name)) 
				$additionalFormFields['account_name'] = $defines['focus']->account_name;
		}
		
		$button = $this->_get_form($defines, $additionalFormFields);
		$button .= "<input title='$title' accesskey='$accesskey' class='button' type='submit' name='button' value='  $value  '/>\n";
		$button .= "</form>";
		return $button;
	}
}
?>
