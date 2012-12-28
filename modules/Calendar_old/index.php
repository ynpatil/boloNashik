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
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once ($theme_path."layout_utils.php");

require_once('include/utils.php');
require_once('templates/templates_calendar.php');
require_once('modules/Calendar/Calendar.php');
setlocale( LC_TIME ,$current_language);
if(!ACLController::checkAccess('Calendar', 'list', true)){
	ACLController::displayNoAccess(true);
}

echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_TITLE'], true);
echo "\n<BR>\n";

$args['IMAGE_PATH'] = $image_path;

if ( empty($_REQUEST['view']))
{
	$_REQUEST['view'] = 'day';
}

$date_arr = array();

if ( isset($_REQUEST['ts']))
{
	$date_arr['ts'] = $_REQUEST['ts'];
}

if ( isset($_REQUEST['day']))
{

	$date_arr['day'] = $_REQUEST['day'];
}

if ( isset($_REQUEST['month']))
{
	$date_arr['month'] = $_REQUEST['month'];
}

if ( isset($_REQUEST['week']))
{
	$date_arr['week'] = $_REQUEST['week'];
}

if ( isset($_REQUEST['year']))
{
	if ($_REQUEST['year'] > 2037 || $_REQUEST['year'] < 1970)
	{
		print("Sorry, calendar cannot handle the year you requested");
		print("<br>Year must be between 1970 and 2037");
		exit;
	}
	$date_arr['year'] = $_REQUEST['year'];
}

// today adjusted for user's timezone
if(empty($date_arr)) {
	global $timedate;
    $gmt_today = $timedate->get_gmt_db_datetime();
    $user_today = $timedate->handle_offset($gmt_today, 'Y-m-d H:i:s');
	preg_match('/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/',$user_today,$matches);

    $date_arr = array(
      'year'=>$matches[1],
      'month'=>$matches[2],
      'day'=>$matches[3],
      'hour'=>$matches[4],
      'min'=>$matches[5]);
} 

$args['calendar'] = new Calendar($_REQUEST['view'], $date_arr);
if ($_REQUEST['view'] == 'day' || $_REQUEST['view'] == 'week' || $_REQUEST['view'] == 'month')
{
	global $current_user;
	
	$user_list = get_user_array_forassign(FALSE);
	$user_list = array_keys($user_list);
	
	$userFocus = new User();
	
//	foreach($user_list as $user_id){
	
		$userFocus->retrieve("9080b04b-7ab9-d8b2-7431-47721ee5e48b");
		
		$args['calendar']->add_activities($userFocus);
	//}	
}

$args['view'] = $_REQUEST['view'];

?>
<script type="text/javascript" language="JavaScript">
<!-- Begin
function toggleDisplay(id){

	if(this.document.getElementById( id).style.display=='none'){
		this.document.getElementById( id).style.display='inline'
		if(this.document.getElementById(id+"link") != undefined){
			this.document.getElementById(id+"link").style.display='none';
		}
	}else{
		this.document.getElementById(  id).style.display='none'
		if(this.document.getElementById(id+"link") != undefined){
			this.document.getElementById(id+"link").style.display='inline';
		}
	}
}
		//  End -->
	</script>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td valign=top width="70%" style="padding-right: 10px; padding-top: 2px;">
<?php template_calendar($args); ?>
</td>
<?php if ($_REQUEST['view'] == 'day') { ?>
<td valign=top width="30%">
<?php include("modules/Calendar/TasksListView.php") ;?>
</td>
<?php } ?>
</tr>
</table>
