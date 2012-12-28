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
	if(empty($_FILES)){
echo $mod_strings['LBL_IMPORT_CUSTOM_FIELDS_DESC'];
echo <<<EOQ
<br>
<br>
<form enctype="multipart/form-data" action="index.php" method="POST">
   	<input type='hidden' name='module' value='Administration'>
   	<input type='hidden' name='action' value='ImportCustomFieldStructure'>
   {$mod_strings['LBL_IMPORT_CUSTOM_FIELDS_STRUCT']}: <input name="sugfile" type="file" />
    <input type="submit" value="{$mod_strings['LBL_ICF_IMPORT_S']}" class='button'/>
</form>
EOQ;

	
	}else{
	require_once('modules/EditCustomFields/FieldsMetaData.php');
	$fmd = new FieldsMetaData();
	$fmd->db->query("Truncate $fmd->table_name");
	echo $mod_strings['LBL_ICF_DROPPING'] . '<br>';
	$lines = file($_FILES['sugfile']['tmp_name']);
	$cur = array();
	foreach($lines as $line){

		if(trim($line) == 'DONE'){
			echo $mod_strings['LBL_ICF_DROPPING'] .$cur['custom_module'] . ' ' . $cur['name'] . '<br>';
			$fmd->db->query("INSERT INTO $fmd->table_name (" . implode(array_keys($cur), ',') . ") VALUES ('" . implode(array_values($cur), "','") . "')");
			$cur = array();
		}else{

			$ln = explode(':::', $line ,2); 
			if(sizeof($ln) == 2){
				$cur[trim($ln[0])] = trim($ln[1]);
			}
		}	
		
	}
	$result = $fmd->db->query("SELECT * FROM $fmd->table_name");
	echo 'Total Custom Fields :' . $fmd->db->getAffectedRowCount($result) . '<br>';
	include('modules/Administration/UpgradeFields.php');
	}
?>
