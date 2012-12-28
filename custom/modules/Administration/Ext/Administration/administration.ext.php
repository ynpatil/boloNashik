<?php 
 //WARNING: The contents of this file are auto-generated


/*
$admin_option_defs=array();
$admin_option_defs['forums_config']= 
			array($image_path .'Forums',
			'LBL_FORUM_CONFIG_TITLE',
			'LBL_FORUM_CONFIG_DESC',
			'./index.php?module=Forums&action=config');
$admin_group_header[]= array('LBL_FORUM_CONFIG_HEADER','',false,$admin_option_defs);
*/

//Forum Topics
$admin_option_defs=array();
$admin_option_defs['forum_topics']= array($image_path . 'ForumTopics','LBL_FORUM_TOPICS_TITLE','LBL_FORUM_TOPICS_DESC','./index.php?module=ForumTopics&action=index');
$admin_group_header[]=array('LBL_FORUM_TOPICS_TITLE','',false,$admin_option_defs);





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