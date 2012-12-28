<?php
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
		$assigned_teams = $focus_teams->retrieve_team_list($current_user->id);
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