<?php
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
 * Portions created by SugarCRM are Copyright (C) 2004 SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/
/*********************************************************************************
 * $Id: Save.php,v 1.8 2005/03/17 00:48:01 majed Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('modules/Accounts/Account.php');
require_once('include/formbase.php');

$account_array = explode("|",$_REQUEST['account_data']);

foreach($account_array as $account_id){

$sugarbean = new Account();
$sugarbean->retrieve($account_id);
$sugarbean->linkage_id = $_REQUEST['record'];
$sugarbean->save();
}

$GLOBALS['log']->debug("Account data :".$_REQUEST['account_data']);

$refreshsubpanel=true;

if ($refreshsubpanel) {
	//refresh contents of the sub-panel.
	$GLOBALS['log']->debug("Location: index.php?sugar_body_only=1&module=".$_REQUEST['module']."&subpanel=".$_REQUEST['subpanel_module_name']."&action=SubPanelViewer&inline=1&record=".$_REQUEST['record']);
	if( empty($_REQUEST['refresh_page']) || $_REQUEST['refresh_page'] != 1){
		$inline = isset($_REQUEST['inline'])?$_REQUEST['inline']: $inline;
		header("Location: index.php?sugar_body_only=1&module=".$_REQUEST['return_module']."&subpanel=".$_REQUEST['subpanel_module_name']."&action=SubPanelViewer&inline=$inline&record=".$_REQUEST['return_id']);
//		header("Location: ".$_REQUEST['return_url']);
	}
}

exit;

?>
