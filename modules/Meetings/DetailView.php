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
 * $Id: DetailView.php,v 1.83 2006/08/03 00:11:55 wayne Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
//om
require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Meetings/Meeting.php');
require_once('include/DetailView/DetailView.php');


global $app_strings;

$focus = new Meeting();

$detailView = new DetailView();
$offset=0;
if (isset($_REQUEST['offset']) or isset($_REQUEST['record'])) {
	$result = $detailView->processSugarBean("MEETING", $focus, $offset);
	if($result == null) {
	    sugar_die($app_strings['ERROR_NO_RECORD']);
	}
	$focus=$result;
} else {
	header("Location: index.php?module=Accounts&action=index");
}

if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
}

//needed when creating a new meeting with default values passed in
if (isset($_REQUEST['contact_name']) && is_null($focus->contact_name)) {
	$focus->contact_name = $_REQUEST['contact_name'];
}
if (isset($_REQUEST['contact_id']) && is_null($focus->contact_id)) {
	$focus->contact_id = $_REQUEST['contact_id'];
}
if (isset($_REQUEST['opportunity_name']) && is_null($focus->parent_name)) {
	$focus->parent_name = $_REQUEST['opportunity_name'];
}
if (isset($_REQUEST['opportunity_id']) && is_null($focus->parent_id)) {
	$focus->parent_id = $_REQUEST['opportunity_id'];
}
if (isset($_REQUEST['account_name']) && is_null($focus->parent_name)) {
	$focus->parent_name = $_REQUEST['account_name'];
}
if (isset($_REQUEST['account_id']) && is_null($focus->parent_id)) {
	$focus->parent_id = $_REQUEST['account_id'];
}


// FEEDBACK START
$feedback_option_status=checkFeedbackOptionEnabledForUserBranch($current_user);
if($feedback_option_status) {
    $responseObjArr=$focus->get_linked_beans('meetings_feedback','Feedback');

    $GLOBALS['log']->debug("FEEDBACK RAting array".$responseObjArr);
    if($responseObjArr) {
        foreach ($responseObjArr as $feedbackObj) {
            $user_meeting_rating[]=$feedbackObj->rating;
        }
        $avg_rating=round(array_sum($user_meeting_rating)/count($user_meeting_rating));

        $GLOBALS['log']->debug("FEEDBACK RAting".$avg_rating);
        for($i=1;$i<=5;$i++) {
            if($avg_rating>0) {
                $rating.="<img src='./themes/Sugar/images/redstar.gif'  id='1' style='width:10px; height:10px; '/>";
                $avg_rating--;
            }else {
                $rating.="<img src='./themes/Sugar/images/greystar.gif'  id='1' style='width:10px; height:10px;'/>";
            }
        }
        $feedback_rating.="<span id='adspan_".$focus->id."' onmouseover=\"return SUGAR.util.getAdditionalDetailsMeetingFeedback('Meetings', '".$focus->id."', 'adspan_".$focus->id."')\" onmouseout=\"return SUGAR.util.clearAdditionalDetailsCall()\">$rating</span>";
    }
}

// FEEDBACK END

echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME'].":".$focus->name."  ".($feedback_option_status?$feedback_rating:""), true);
echo "\n</p>\n";

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$GLOBALS['log']->info("Meeting detail view");

$xtpl=new XTemplate ('modules/Meetings/DetailView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

if(isset($_REQUEST['return_module'])) $xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
if(isset($_REQUEST['return_action'])) $xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
if (isset($_REQUEST['return_id'])) $xtpl->assign("RETURN_ID", $_REQUEST['return_id']);
// handle Create $module then Cancel
if (empty($_REQUEST['return_id'])) {
	$xtpl->assign("RETURN_ACTION", 'index.php');
}$xtpl->assign("THEME", $theme);
$xtpl->assign("GRIDLINE", $gridline);
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "index.php?".$GLOBALS['request_string']);
$xtpl->assign("ID", $focus->id);
$xtpl->assign("PARENT_NAME", $focus->parent_name);
$xtpl->assign("BRAND_ID", $focus->brand_id);
$xtpl->assign("BRAND_NAME", $focus->brand_name);
if (isset($focus->parent_type)) $xtpl->assign("PARENT_MODULE", $focus->parent_type);
$xtpl->assign("PARENT_TYPE", $app_list_strings['record_type_display'][$focus->parent_type]);
$xtpl->assign("PARENT_ID", $focus->parent_id);
$xtpl->assign("NAME", $focus->name);
$xtpl->assign("ASSIGNED_TO", $focus->assigned_user_name);

$xtpl->assign("CREATED_BY", $focus->created_by_name);
$xtpl->assign("MODIFIED_BY", $focus->modified_by_name);

$xtpl->assign("CONTACT_ID",$focus->contact_id);

$xtpl->assign("LOCATION", $focus->location);
$xtpl->assign("DATE_START", $focus->date_start);
$xtpl->assign("TIME_START", $focus->time_start);
$xtpl->assign("TIME_EXIT", $focus->time_exit);
$xtpl->assign("TIME_IN", $focus->time_in);

$xtpl->assign("DESCRIPTION", nl2br(url2html($focus->description)));
$xtpl->assign("OUTCOME", nl2br(url2html($focus->outcome)));
$reminder_time = -1;
if(!empty($focus->reminder_time)){
	$reminder_time = $focus->reminder_time;	
}
if($reminder_time != -1){
	$xtpl->assign("REMINDER_CHECKED", 'checked');
	$xtpl->assign("REMINDER_TIME", translate('reminder_time_options', '', $reminder_time));
}
$xtpl->assign("DATE_MODIFIED", $focus->date_modified);
$xtpl->assign("DATE_ENTERED", $focus->date_entered);
$xtpl->assign("DURATION_HOURS", $focus->duration_hours);
$xtpl->assign("STATUS", $app_list_strings['meeting_status_dom'][$focus->status]);
if ($app_list_strings['meeting_status_dom'][$focus->status] != "Held")
{
    $close_and_create_button = '<input title="'.$app_strings['LBL_CLOSE_AND_CREATE_BUTTON_TITLE'].'" ' .
            'accessKey="'.$app_strings['LBL_CLOSE_AND_CREATE_BUTTON_KEY'] .'" class="button" ' .
            'onclick="this.form.status.value=\'Held\';this.form.action.value=\'Save\';this.form.return_module.value=\'Meetings\';' .
            'this.form.isDuplicate.value=true;this.form.isSaveAndNew.value=true;this.form.return_action.value=\'EditView\'; ' .
            'this.form.isDuplicate.value=true;this.form.return_id.value=\''.$focus->id.'\';" type="submit" name="button" ' .
            'value="' .$app_strings['LBL_CLOSE_AND_CREATE_BUTTON_LABEL'].  '" '.
            ((ACLController::checkAccess($focus->module_dir,'edit', $focus->isOwner($current_user->id)))?"":"DISABLED")
            .'>';            
    $xtpl->assign("CLOSE_AND_CREATE_BUTTON", $close_and_create_button);
}


if(isset($focus->minutes_values[$focus->duration_minutes])){
$xtpl->assign("DURATION_MINUTES", $focus->minutes_values[$focus->duration_minutes]);
}else{
	$xtpl->assign("DURATION_MINUTES", "00");
}
global $current_user;
if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){

	$xtpl->assign("ADMIN_EDIT","<a href='index.php?action=index&module=DynamicLayout&from_action=".$_REQUEST['action'] ."&from_module=".$_REQUEST['module'] ."&record=".$_REQUEST['record']. "'>".get_image($image_path."EditLayout","border='0' alt='Edit Layout' align='bottom'")."</a>");
}
$xtpl->assign("TAG", $focus->listviewACLHelper());
$detailView->processListNavigation($xtpl, "MEETING", $offset);
// adding custom fields:
require_once('modules/DynamicFields/templates/Files/DetailView.php');






$xtpl->parse("main.open_source");




$xtpl->parse("main");
$xtpl->out("main");

$sub_xtpl = $xtpl;
$old_contents = ob_get_contents();
ob_end_clean();
ob_start();
echo $old_contents;

$show_who_has_access = "true";

require_once('include/SubPanel/SubPanelTiles.php');
$subpanel = new SubPanelTiles($focus, 'Meetings');
echo $subpanel->display();

require_once('modules/SavedSearch/SavedSearch.php');
$savedSearch = new SavedSearch();
$json = getJSONobj();
$savedSearchSelects = $json->encode(array($GLOBALS['app_strings']['LBL_SAVED_SEARCH_SHORTCUT'] . '<br>' . $savedSearch->getSelect('Meetings')));
$str = "<script>
YAHOO.util.Event.addListener(window, 'load', SUGAR.util.fillShortcuts, $savedSearchSelects);
</script>";
echo $str;
?>
