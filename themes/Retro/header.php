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
 * $Id: header.php,v 1.75 2006/08/03 18:49:18 jbenterou Exp $
 * Description:  Contains a variety of utility functions used to display UI
 * components such as form headers and footers.  Intended to be modified on a per
 * theme basis.
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once("data/Tracker.php");


global $currentModule;
global $moduleList;
global $theme, $action;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');
require_once("include/globalControlLinks.php");
require($theme_path.'config.php');



global $app_strings;

$module_path="modules/".$currentModule."/";
load_menu($module_path);
$default_charset = $sugar_config['default_charset'];
$xtpl=new XTemplate ($theme_path."header.html");
$xtpl->assign("APP", $app_strings);


if(isset($app_strings['LBL_CHARSET']))
{
	$xtpl->assign("LBL_CHARSET", $app_strings['LBL_CHARSET']);
}
else
{
	$xtpl->assign("LBL_CHARSET", $default_charset);
}

$xtpl->assign("THEME", $theme);
$xtpl->assign("IMAGE_PATH", $image_path);
$xtpl->assign("PRINT_URL", "index.php?".$GLOBALS['request_string']);
$xtpl->assign("MODULE_NAME", $currentModule);
$xtpl->assign("DATE", date("Y-m-d"));


if ($current_user->first_name != '') $xtpl->assign("CURRENT_USER", $current_user->first_name);
else $xtpl->assign("CURRENT_USER", $current_user->user_name);

$xtpl->assign("CURRENT_USER_ID", $current_user->id);


$i = 0;
foreach($global_control_links as $key => $value) {
	foreach ($value as $linkattribute => $attributevalue) {
		if($linkattribute == 'linkinfo') {
			foreach ($attributevalue as $label => $url) {
				$xtpl->assign("GCL_LABEL", $label);
				$xtpl->assign("GCL_URL", $url);
				if (isset($sub_menu[$key]) && $sub_menu[$key]) {
					$xtpl->assign("GCL_MENU", "id='".$key."Handle' onmouseover=' tbButtonMouseOver(this.id,18,\"\",0);'");
					$xtpl->assign("MENU_ARROW", "<img src='".$image_path."menuarrow.gif' alt='' align='absmiddle' id='".$key."Handle' style='margin-bottom: 1px; margin-left:2px; cursor: pointer; cursor: hand;' align='absmiddle' onmouseover='tbButtonMouseOver(this.id,18,\"\",0);'>");
				} else {
					$xtpl->assign("GCL_MENU", "");
					$xtpl->assign("MENU_ARROW", "");
				}
				if($i < sizeof($global_control_links)-1) {
					$xtpl->assign("SEPARATOR", "&nbsp;|&nbsp;");
				} else {
					$xtpl->assign("SEPARATOR", "");
				}
			}
		}

		if($linkattribute == 'submenu') {
			if (is_array($attributevalue)) {
				foreach ($attributevalue as $submenulinkkey => $submenulinkinfo) {
					foreach ($submenulinkinfo as $submenulinklabel => $submenulinkurl) {
					$xtpl->assign("GCL_SUBMENU_LINK_LABEL", $submenulinklabel);
					$xtpl->assign("GCL_SUBMENU_LINK_URL", $submenulinkurl);
					}	
					$xtpl->assign("GCL_SUBMENU_KEY", $key);
					$xtpl->assign("GCL_SUBMENU_LINK_KEY", $submenulinkkey);	
					$xtpl->parse("main.gcl_submenu.gcl_submenu_items");
				}
				
			}
			$xtpl->assign("GCL_SUBMENU_KEY", $key);
			$xtpl->parse("main.gcl_submenu");
		}
	}
	$xtpl->parse("main.global_control_links");
	$i++;
}

if (isset($_REQUEST['query_string'])) $xtpl->assign("SEARCH", $_REQUEST['query_string']);

if ($action == "EditView" || $action == "Login") $xtpl->assign("ONLOAD", 'onload="set_focus()"');
// Loop through the module list.
// For each tab that is off, parse a tab_off.
// For the current tab, parse a tab_on
$modListHeader = $moduleList;
if(isset($current_user->id))
{
	if($action=='Login') 
	{
		if ($sugar_config['login_nav']==false)

		$modListHeader = array();
	} 
	else
	{
		$modListHeader = query_module_access_list($current_user);
	}
} 
else 
{
	if($action=='Login' && $sugar_config['login_nav']==false) 
	{
	
		$modListHeader = array();
	} else{
		$modListHeader = query_module_access_list($current_user);
	}
}

$modListHeader = get_val_array($modListHeader);

if(isset($current_user->id))
{
	$user_max_tabs = $current_user->getPreference('max_tabs');
	if(intval($user_max_tabs) > 0)
		$max_tabs = intval($user_max_tabs);
}

$modListHeaderClone = $modListHeader;
require_once('modules/iFrames/iFrame.php');
$iFrame = new iFrame();
$frames = $iFrame->lookup_frames('tab');
foreach($frames as $name => $values){
	$modListHeaderClone[$name] = $values;
}

$numb_tabs=count($modListHeaderClone);
$moreListHeader = array_slice($modListHeaderClone, $max_tabs-1,$numb_tabs);
$defaultMore = array_slice($moreListHeader, 0,1);
if ($max_tabs==$numb_tabs) {
	$preListHeader = array_slice($modListHeaderClone, 0,$max_tabs);
} else {
	$preListHeader = array_slice($modListHeaderClone, 0,$max_tabs-1);
}
if (!isset($_SESSION['moreTab']) || !in_array($_SESSION['moreTab'],$modListHeaderClone)) {
	foreach ($defaultMore as $module){
		$_SESSION['moreTab'] = $module;
	}
}
if (isset($_SESSION['moreTab']) and in_array($_SESSION['moreTab'],$preListHeader)) {
	foreach ($defaultMore as $module){
		$_SESSION['moreTab'] = $module;
	}
}
if(in_array($currentModule,$moreListHeader)) {
	$_SESSION['moreTab'] = $currentModule;
} elseif($currentModule=="iFrames" and isset($_REQUEST['record']) and isset($_REQUEST['tab'])) {

	$frame = $iFrame->lookup_frame_by_record_id($_REQUEST['record']);
	foreach($frame as $name => $values){
	$_SESSION['moreTab'] = $values;
	}
}

if ($numb_tabs>$max_tabs){
	$preListHeader[] = $_SESSION['moreTab'];
}

// Associates Modules with tabs
$activities= array("Calls","Meetings","Tasks","Notes");

if (in_array("Calendar",$moduleList)) {

	$cal_activities= array("Calls","Meetings");
	if (in_array($currentModule,$cal_activities)) {
		$currentModule = "Calendar";
	} else  {
		if (in_array($currentModule,$activities)) {
			$currentModule = "Activities";
		}
	}


} else {
	if (in_array($currentModule,$activities)) {
		$currentModule = "Activities";

	}
}

$i=0;
foreach($preListHeader as $module_name)
{
		if (!is_array($module_name)) {
		$xtpl->assign("MODULE_NAME", $app_list_strings['moduleList'][$module_name]);
		$xtpl->assign("MODULE_KEY", $module_name);
	
		
		if($module_name == $currentModule && ($module_name != 'iFrames' || empty($_REQUEST['record'])|| (!empty($_REQUEST['tab']) && $_REQUEST['tab']=='false')))
		{	
			$xtpl->assign("TAB_CLASS", "currentTab");
			$xtpl->assign("OTHER_TAB", "currentTab");
		}
		else
		{	
			$xtpl->assign("TAB_CLASS", "otherTab");
			$xtpl->assign("OTHER_TAB", "currentTab");
		}

		} else {
		
		$xtpl->assign("MODULE_NAME", $module_name[4]);
		$xtpl->assign("MODULE_KEY", $module_name[3]);
	
		
		if($module_name[3] == $currentModule && (!empty($_REQUEST['record']) and $_REQUEST['record']==$module_name[0] and !empty($_REQUEST['tab'])))
		{	
			$xtpl->assign("TAB_CLASS", "currentTab");
			$xtpl->assign("OTHER_TAB", "currentTab");
		}
		else
		{	
			$xtpl->assign("TAB_CLASS", "otherTab");
			$xtpl->assign("OTHER_TAB", "currentTab");
		}

					
		}
		
		if ($i==$max_tabs-1 and $numb_tabs>$max_tabs) {
			$xtpl->assign("MORE", "<td class=\"emptyTabSpace\"><img src='".$image_path."more.gif' alt='' align='absmiddle' id='MoreHandle' style='margin-bottom: 0px; margin-left:2px; cursor: pointer; cursor: hand;' align='absmiddle' onmouseover='tbButtonMouseOver(this.id,70,\"\",\"0\");'></td>");
		} 
		$xtpl->parse("main.tab");
	$i++;
}

foreach ($moreListHeader as $module_name) {
	if (!is_array($module_name)) {
		if($module_name!=$currentModule and $_SESSION['moreTab']!=$module_name){
			$xtpl->assign("MODULE_NAME", $app_list_strings['moduleList'][$module_name]);
			$xtpl->assign("MODULE_KEY", $module_name);
			$xtpl->parse("main.more");
		} 
	} else {
		if (!isset($_REQUEST['record']) or $_REQUEST['record']!=$module_name[0]) {
			$xtpl->assign("MODULE_NAME", $module_name[4]);
			$xtpl->assign("MODULE_KEY", $module_name[3]);
			$xtpl->assign("MODULE_QUERY", '&record='.$module_name[0].'&tab='.$module_name[2]);
			$xtpl->parse("main.more");
		}
		
	}

}
	unset($name);
	unset($id);
// Assign the module name back to the current module.
$xtpl->assign("MODULE_NAME", $currentModule);
$numb_tabs=count($module_menu);
$pre_module_menu = array_slice($module_menu,0,$max_sub_tabs);
$post_module_menu = array_slice($module_menu,$max_sub_tabs,$numb_tabs);
$i=0;
if (isset($current_user->user_name)) {
	$subModuleCheck = 0;
	$subModuleCheckArray = array("Tasks", "Calls", "Meetings", "Notes","Prospects");
	foreach($pre_module_menu as $menu_item)
	{
		if(isset($menu_item[3]))
		{
			if(in_array($menu_item[3], $subModuleCheckArray) && 			(array_key_exists("Calendar", $modListHeader) ||
			array_key_exists("Activities", $modListHeader)))
			$subModuleCheck = 1;
		}

		if(!isset($menu_item[3])|| !isset($modListHeader) || (isset($menu_item[3]) && (key_exists($menu_item[3],$modListHeader) || $subModuleCheck))){
			$after_this = current($module_menu);

			if ($menu_item[1] != 'Deleted Items') {
				$xtpl->assign("URL", $menu_item[0]);
                /* Only add nb spaces if the label has no HTML. */
				$xtpl->assign("LABEL", strpos($menu_item[1], '<')?str_replace(' ','&nbsp;',$menu_item[1]):$menu_item[1]);
				$xtpl->assign("SC_MODULE_NAME", $menu_item[2]);
				$xtpl->assign("SC_IMAGE", get_image($image_path.$menu_item[2],"alt='".$menu_item[1]."'  border='0' align='absmiddle'"));
				if ($i==$max_sub_tabs-1 and $numb_tabs>$max_sub_tabs) {
					$xtpl->assign("SEPARATOR", "<img src='".$image_path."shortcutSeparator.gif' width='2' height='18' border='0' align='absmiddle' style='margin-left: 10px; margin-right: 6px; margin-top: 2px;'><img src='".$image_path."moreSub.gif' width='18' height='18' border='0' align='absmiddle' onmouseover='tbButtonMouseOver(this.id,90,\"\",\"0\");' id='SubMoreHandle'>");
				} else {
					if ($i!=$numb_tabs-1) {
						$xtpl->assign("SEPARATOR", "<img src='".$image_path."shortcutSeparator.gif' width='2' height='18' border='0' align='absmiddle' style='margin-left: 6px; margin-right: 6px; margin-top: 2px;'>");
					} else {
						$xtpl->assign("SEPARATOR", "");
					}
				}
			}
			else {
				$xtpl->assign("DELETED_ITEMS_URL", $menu_item[0]);
				$xtpl->assign("DELETED_ITEMS_LABEL", $menu_item[1]);
			}

			$xtpl->parse("main.sub_menu.sub_menu_item");
		}
		$i++;
	}
	$xtpl->parse("main.sub_menu");
	foreach($post_module_menu as $menu_item) {
		if(isset($menu_item[3]))
		{
			if(in_array($menu_item[3], $subModuleCheckArray) && 			(array_key_exists("Calendar", $modListHeader) ||
			array_key_exists("Activities", $modListHeader)))
			$subModuleCheck = 1;
		}
		if(!isset($menu_item[3])|| !isset($modListHeader) || (isset($menu_item[3]) && (key_exists($menu_item[3],$modListHeader) || $subModuleCheck))){
			$xtpl->assign("URL", $menu_item[0]);
			$xtpl->assign("LABEL", str_replace(' ','&nbsp;',$menu_item[1]));
			$xtpl->assign("SC_MODULE_NAME", $menu_item[2]);
			$xtpl->assign("SC_IMAGE", get_image($image_path.$menu_item[2],"alt='".$menu_item[1]."'  border='0' align='absmiddle'"));
			$xtpl->parse("main.sub_more.sub_more_item");
		}
	}
	$xtpl->parse("main.sub_more");
}
	

if (isset($_SESSION["authenticated_user_id"])) {
$xtpl->assign("LEFT_FORM_OTD", '<td valign="top" style="width: 10px;"><img style="cursor: pointer; cursor: hand;" id="HideHandle" name="HideHandle" src="'.$image_path.'show.gif" alt="" onclick=\'hideLeftCol("leftCol");closeMenus();\' onmouseover="showSubMenu(\'leftCol\')"></td><td valign="top" style="width: 160px;"><div style="display: none;" id="leftCol"><table cellpadding="0" cellspacing="0" border="0" width="160"><tr><td style="padding-right: 10px;">');
$xtpl->assign("LEFT_FORM_CTD", '<img src="'.$image_path.'blank.gif" alt="" width="160" height="1" border="0"></td></tr></table></div></td>');
$xtpl->assign("TITLE", $app_strings['LBL_SEARCH']);
$xtpl->parse("main.left_form.left_form_search");
$xtpl->parse("main.left_form");

$xtpl->assign("TITLE", $app_strings['LBL_LAST_VIEWED']);

$tracker = new Tracker();
$history = $tracker->get_recently_viewed($current_user->id);

$current_row=1;

if (count($history) > 0) {
	foreach($history as $row) {
		$xtpl->assign("RECENT_LABEL", getTrackerSubstring($row['item_summary']));
		$xtpl->assign("MODULE_NAME",$row['module_name']);
		$xtpl->assign("ROW_NUMBER",$current_row);
		$xtpl->assign("RL_IMAGE",get_image($image_path.$row['module_name'],'border="0" align="absmiddle" alt="'.$row['item_summary'].'"'));
		$xtpl->assign("RECENT_URL","index.php?module=$row[module_name]&action=DetailView&record=$row[item_id]");
		$xtpl->parse("main.hide");
		$xtpl->parse("main.left_form.left_form_recent_view.left_form_recent_view_row");
		$current_row++;
	}
}
else {
		$xtpl->parse("main.hide_empty");
		$xtpl->parse("main.left_form.left_form_recent_view.left_form_recent_view_empty");
}

$xtpl->parse("main.left_form.left_form_recent_view");
$xtpl->parse("main.left_form");

require_once("modules/".$currentModule."/Forms.php");
if ($currentModule && $action == "index" && function_exists('get_new_record_form')) {
	$xtpl->assign("NEW_RECORD", get_new_record_form());
	$xtpl->parse("main.left_form_new_record");
}
}
$xtpl->parse("main");
$xtpl->out("main");

?>
