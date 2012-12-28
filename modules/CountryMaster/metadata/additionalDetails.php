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
 *********************************************************************************/

require_once('include/utils.php');

function additionalDetailsCountry($fields) {
	static $mod_strings;
	if(empty($mod_strings)) {
		global $current_language;
		$mod_strings = return_module_language($current_language, 'CountryMaster');
	}

	$overlib_string = '';

	if(!empty($fields['NAME'])) {
		$overlib_string .= '<b>'. $mod_strings['LBL_NAME'] . '</b> ' . substr($fields['NAME'], 0, 300)."<br/>";
		//if(strlen($fields['NAME']) > 300) $overlib_string .= '...';
	}

	if(!empty($fields['CREATED_BY'])) {
		$overlib_string .= '<b>'. $mod_strings['LBL_CREATED_BY'] . '</b> ' .$fields['CREATED_BY_NAME']."<br/>";
	}

	if(!empty($fields['ASSIGNED_USER_NAME'])) {
		$overlib_string .= '<b>'. $mod_strings['LBL_ASSIGNED_USER_NAME'] . '</b> ' .$fields['ASSIGNED_USER_NAME']."<br/>";
	}

	if(!empty($fields['DATE_MODIFIED'])) {
		$overlib_string .= '<b>'. $mod_strings['LBL_DATE_MODIFIED'] . '</b> ' .$fields['DATE_MODIFIED']."<br/>";
	}

	if(!empty($fields['DATE_ENTERED'])) {
		$overlib_string .= '<b>'. $mod_strings['LBL_DATE_ENTERED'] . '</b> ' .$fields['DATE_ENTERED']."<br/>";
	}

	return array('fieldToAddTo' => 'NAME',
				 'string' => $overlib_string,
				 'editLink' => "index.php?action=EditView&module=CountryMaster&return_module=CountryMaster&record={$fields['ID']}",
				 'viewLink' => "index.php?action=DetailView&module=CountryMaster&return_module=CountryMaster&record={$fields['ID']}");
}

?>
