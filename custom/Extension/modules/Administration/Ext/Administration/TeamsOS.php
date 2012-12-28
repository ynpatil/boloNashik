<?php
//**********************************************************************************
//
//   TeamsOS Admin Menu File
//
//**********************************************************************************
$admin_option_defs=array();
$admin_option_defs['team_management']= array($image_path . 'Teams','LBL_MANAGE_TEAMS_TITLE','LBL_MANAGE_TEAMS','./index.php?module=TeamsOS&action=index');
$admin_group_header[]=array('LBL_MANAGE_TEAMS','',false,$admin_option_defs);
//**********************************************************************************
//
//   END TeamsOS Admin Menu File
//
//**********************************************************************************
?>