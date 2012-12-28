<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Professional End User
 * License Agreement ("License") which can be viewed at
 * http://www.sugarcrm.com/crm/products/sugar-professional-eula.html
 * By installing or using this file, You have unconditionally agreed to the
 * terms and conditions of the License, and You may not use this file except in
 * compliance with the License.  Under the terms of the license, You shall not,
 * among other things: 1) sublicense, resell, rent, lease, redistribute, assign
 * or otherwise transfer Your rights to the Software, and 2) use the Software
 * for timesharing or service bureau purposes such as hosting the Software for
 * commercial gain and/or for the benefit of a third party.  Use of the Software
 * may be subject to applicable fees and any use of the Software without first
 * paying applicable fees is strictly prohibited.  You do not have the right to
 * remove SugarCRM copyrights from the source code or user interface.
 *
 * All copies of the Covered Code must include on each user interface screen:
 *  (i) the "Powered by SugarCRM" logo and
 *  (ii) the SugarCRM copyright notice
 * in the same form as they appear in the distribution.  See full license for
 * requirements.
 *
 * Your Warranty, Limitations of liability and Indemnity are expressly stated
 * in the License.  Please refer to the License for the specific language
 * governing these rights and limitations under the License.  Portions created
 * by SugarCRM are Copyright (C) 2004-2006 SugarCRM, Inc.; All Rights Reserved.
 ********************************************************************************/
/*********************************************************************************
 * $Id
 * Description:
 ********************************************************************************/
//om
require_once('modules/Transfers/Transfer.php');

global $current_user;

if(!is_superuser($current_user)){
	sugar_die("Unauthorized access...");
}

$user_from = $_REQUEST['user_from'];
$user_to = $_REQUEST['user_to'];

$modules = $_REQUEST['modules'];
global $beanFiles,$beanList;

echo "Starting transfer from User :<b>".$user_from."</b> to User :<b>".$user_to."</b><br/>";
$output = "";

foreach($modules as $module){
	
	echo "Transferring module :".$module;
	$bean = $beanList[$module];
	require_once($beanFiles[$bean]);
	$focus = new $bean();
	$query = $focus->create_list_query(''," $focus->table_name.assigned_user_id='$user_from'",FALSE);
//	echo "Query :".$query."<br/>";
	$list = $focus->process_full_list_query($query,FALSE);
	
	$output .= "Total ".$module." count ".count($list).". ";
	$count = 0;
	
	foreach($list as $bean){
		$bean->retrieve($bean->id);
		$bean->assigned_user_id = $user_to;
		$bean->save(true);
		$count++;
//		echo " Assigned call id :".$bean->id."<br/>";
	}
	echo " Completed transferring :".$count." records.<br/>";
	$output .= "Transferred :".$count."<br/>";
//	echo "Found records :".count($list)."<br/>";
}

//echo $output;

$focus = new Transfer();
$focus->user_from_id = $user_from;
$focus->user_to_id = $user_to;
$focus->name = $_REQUEST['name'];
$focus->modules = base64_encode(serialize($modules));
$focus->activity = $output;
$focus->save(FALSE);

echo "<a href=\"index.php?module=Transfers&action=index\">Back</a>";
?>
