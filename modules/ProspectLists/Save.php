<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');
/* * *******************************************************************************
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
 * ****************************************************************************** */
/* * *******************************************************************************
 * $Id: Save.php,v 1.9 2006/06/06 17:58:33 majed Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 * ****************************************************************************** */

require_once('modules/ProspectLists/ProspectList.php');

//$_REQUEST['list_type_value']=$_REQUEST[$_REQUSET['list_type']];
//echo"<pre>";print_r($_REQUEST);
//exit;
//print_r($_POST);
//exit;

$focus = new ProspectList();
$focus->retrieve($_POST['record']);

if (!empty($_POST['assigned_user_id']) && ($focus->assigned_user_id != $_POST['assigned_user_id']) && ($_POST['assigned_user_id'] != $current_user->id)) {
    $check_notify = TRUE;
} else {
    $check_notify = FALSE;
}
// If user is updating prospectlist then it will delete leads
if ($focus->id || $_POST['record']) {
    $focus->load_relationship("leads"); //
    $old_leads = $focus->leads->get();
    if (count($old_leads) > 0) {
        $focus->leads->delete($focus->id);
    }
    $focus->populate_lead_status=0;
}


foreach ($focus->column_fields as $field) {
    if (isset($_POST[$field])) {
        $value = $_POST[$field];
        $focus->$field = $value;
    }
}

foreach ($focus->additional_column_fields as $field) {
    if (isset($_POST[$field])) {
        $value = $_POST[$field];
        $focus->$field = $value;
    }
}

$focus->save($check_notify);
$return_id = $focus->id;

if (isset($_POST['return_module']) && $_POST['return_module'] != "")
    $return_module = $_POST['return_module'];
else
    $return_module = "ProspectLists";
if (isset($_POST['return_action']) && $_POST['return_action'] != "")
    $return_action = $_POST['return_action'];
else
    $return_action = "DetailView";
if (isset($_POST['return_id']) && $_POST['return_id'] != "")
    $return_id = $_POST['return_id'];

if ($return_action == "SaveCampaignProspectListRelationshipNew") {
    $prospect_list_id = $focus->id;

    header("Location: index.php?action=$return_action&module=$return_module&record=$return_id&prospect_list_id=$prospect_list_id");
} else {
    $GLOBALS['log']->debug("Saved record with id of " . $return_id);
    header("Location: index.php?action=$return_action&module=$return_module&record=$return_id");
}
?>
