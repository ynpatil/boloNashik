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

 * Description:  Base form for contact
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): Ken Brill (TeamsOS)
 ********************************************************************************/

//UPDATED FOR TeamsOS 3.0c by Ken Brill Jan 7th, 2007

class ContactRFCFormBase {

function handleSave($prefix,$redirect=true, $useRequired=false){

	require_once('modules/Contacts/Contact.php');
    require_once ('include/utils.php');
	require_once('include/formbase.php');

	$focus = new Contact();
	
	$focus = populateFromPost($prefix, $focus);

	$dataChanges=array();
   	if (!isset($focus->fetched_row)) {
       	$GLOBALS['log']->debug('RFQ: Retrieve was not called, audit record will not be created.');
   	}
   	else {
   		$dataChanges=$focus->dbManager->helper->getDataChangesForRFC($focus);
   	}

    if (!empty($dataChanges) && is_array($dataChanges)) {
     	foreach ($dataChanges as $change) {
     		if($change['field_name'] == 'date_entered' || $change['field_name'] == 'date_modified')
     		continue;
   			$focus->dbManager->helper->save_rfc_records($focus,$change);
     	}
    }
     
	$return_id = $focus->id;
	$GLOBALS['log']->debug("RFQ record with id of ".$return_id);

	if($redirect){
		handleRedirect($return_id,'Accounts');
	}else{
		return $focus;
	}
}
	
}

?>
