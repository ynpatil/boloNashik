<?php 
 //WARNING: The contents of this file are auto-generated

 
 //WARNING: The contents of this file are auto-generated


/*****************************************************************************
 * The contents of this file are subject to the RECIPROCAL PUBLIC LICENSE
 * Version 1.1 ("License"); You may not use this file except in compliance
 * with the License. You may obtain a copy of the License at
 * http://opensource.org/licenses/rpl.php. Software distributed under the
 * License is distributed on an "AS IS" basis, WITHOUT WARRANTY OF ANY KIND,
 * either express or implied.
 *
 * You may:
 * a) Use and distribute this code exactly as you received without payment or
 *    a royalty or other fee.
 * b) Create extensions for this code, provided that you make the extensions
 *    publicly available and document your modifications clearly.
 * c) Charge for a fee for warranty or support or for accepting liability
 *    obligations for your customers.
 *
 * You may NOT:
 * a) Charge for the use of the original code or extensions, including in
 *    electronic distribution models, such as ASP (Application Service
 *    Provider).
 * b) Charge for the original source code or your extensions other than a
 *    nominal fee to cover distribution costs where such distribution
 *    involves PHYSICAL media.
 * c) Modify or delete any pre-existing copyright notices, change notices,
 *    or License text in the Licensed Software
 * d) Assert any patent claims against the Licensor or Contributors, or
 *    which would in any way restrict the ability of any third party to use the
 *    Licensed Software.
 *
 * You must:
 * a) Document any modifications you make to this code including the nature of
 *    the change, the authors of the change, and the date of the change.
 * b) Make the source code for any extensions you deploy available via an
 *    Electronic Distribution Mechanism such as FTP or HTTP download.
 * c) Notify the licensor of the availability of source code to your extensions
 *    and include instructions on how to acquire the source code and updates.
 * d) Grant Licensor a world-wide, non-exclusive, royalty-free license to use,
 *    reproduce, perform, modify, sublicense, and distribute your extensions.
 *
 * The Original Code is: SugarCubed Extensions
 *                       Kenneth brill
 *                       2006-03-04 ken.brill@gmail.com
 *
 * The Initial Developer of the Original Code is SugarCubed / Kenneth Brill
 * Portions created by Kenneth Brill are Copyright (C) 2005 Kenneth Brill
 * All Rights Reserved.
 ********************************************************************************/

global $mod_strings;
global $focus;
global $current_user;
/*
	if(!empty($focus->primary_address_street)) {
//		$module_menu[] =Array("http://maps.msn.com/home.aspx?strt1=$focus->primary_address_street&city1=$focus->primary_address_city&stnm1=$focus->primary_address_state&zipc1=$focus->primary_address_postalcode&cnty1=0", $mod_strings['LBL_MAP'],"Import", 'Contacts');
		$module_menu[] =Array("http://maps.google.com/maps?oi=map&q=$focus->primary_address_street+$focus->primary_address_city+$focus->primary_address_state+$focus->primary_address_postalcode", $mod_strings['LBL_MAP'],"Map", 'Contacts');
		if(!empty($current_user->address_street)) {
				$module_menu[] =Array("http://maps.google.com/maps?saddr=$focus->primary_address_street+$focus->primary_address_city+$focus->primary_address_state+$focus->primary_address_postalcode&daddr=$current_user->address_street+$current_user->address_city+$current_user->address_state+$current_user->address_postalcode", $mod_strings['LBL_DIRECTIONS'],"Directions", 'Contacts');
		}
	}
*/



//**********************************************************************************
//
//   TeamsOS Menu File
//
//**********************************************************************************
//OK, this looks like crap on rice, but it seems to work,  I will go over it later
//and condense it into something resembling PHP code in the next release.
//I would give my left arm for a pre_render hook.....
require_once('modules/TeamsOS/TeamOS.php');
global $app_list_strings;
global $current_user;
global $mod_strings;
global $app_strings;

$new_array = array();
$no_teams_flag=true;

$focus_teams = new TeamOS();

//Get the correct list of teams without blanks
if($current_user->show_all_teams_c || $current_user->is_admin) {
	$teams_list=$focus_teams->getTeamList('ALL');
} else {
	$teams_list=$focus_teams->getTeamList('MEMBER');
}

if(empty($_REQUEST['record'])) {
	//This is a new record and the default record needs
	//to be placed at the top of the array and the '' record
	//removed.
	if(!empty($current_user->default_team_id_c)) {
		$app_list_strings['teams_array'] = array_merge(
			array ($current_user->default_team_id_c => $current_user->default_team_id_c),
			       $teams_list,
			       array('None'=>$app_strings['LBL_NO_TEAM']));
	} else {
		$app_list_strings['teams_array'] = array_merge(
					$teams_list,
					array(''=>$app_strings['LBL_NO_TEAM']));
	}
} else {
	//This is an existing record and we are just going to
	//let SugarCRM to handle the selected value
		$app_list_strings['teams_array'] = array_merge(
					array(''=>$app_strings['LBL_NO_TEAM']),
					$teams_list);
}

if(count($app_list_strings['teams_array'])==0) {
	$app_list_strings['teams_array']=array(''=>$app_strings['LBL_NO_TEAM']);
}

//On the users and employees detail view show all the teams that this user
//belongs to.
if($_REQUEST['module']=="Users" || $_REQUEST['module']=="Employees") {
	if($_REQUEST['action']=='DetailView') {
		$assigned_teams = $focus_teams->retrieve_team_id($current_user->id);
		$teams_text="<b>";
		foreach($assigned_teams as $value) {
			if($value!=$app_strings['LBL_NO_TEAM']) {
				$teams_text .= $value . "</b>, <b>";
			}
		}
		$teams_text .= substr($teams_text,0,strlen($teams_text)-2) . "</b>";
		$mod_strings['LBL_DEFAULT_TEAM_DESC'] .= $teams_text;
	}
}

//If this is a DetailView from Contacts, Accounts, Users or Employees then
//add a menu item to the left hand menu panel.
if(file_exists("orgchart.php") &&
   $_REQUEST['action']=="DetailView" &&
   strpos("Accounts#Contacts#Users#Employees", $_REQUEST['module'])!==false) {
	$module_menu[] = Array("#\" OnClick=\"javascript:window.open('orgchart.php?&module=". $_REQUEST['module'] . "&record=". $_REQUEST['record'] . "', 'orgchart', 'width=640, height=480,resizable=1,scrollbars=1');", $app_strings['LBL_ORGCHART_BUTTON_TITLE'], "MyReports");
}
//**********************************************************************************
//
//   TeamsOS Menu File
//   End
//
//**********************************************************************************

?>