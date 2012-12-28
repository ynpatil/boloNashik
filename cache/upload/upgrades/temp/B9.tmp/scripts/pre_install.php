<?php
/*
This script updates the DetailsViews of Accounts/Contacts and Leads. It only updates the {NAME} fields and donot overwrite the files. So, all your existing fields are kept intact! 
Please read Install.txt for Installation instructions and README.txt for further details.

Released under GPL
InsideView Inc.
We think, you sell
http://www.insideview.com
*/
include("class.insideview.php");

if(!defined('sugarEntry') || !sugarEntry) 
{
	die('Not A Valid Entry Point');
}
//pre_install();

//This function is called before the Install
function pre_install() 
{
	global $current_user;
	global $sugar_config;
	global $current_user;
	global $unzip_dir;
		
	$iv = new InsideView;
	$iv->createIvDir();
	
	$modules = array("Accounts", "Contacts", "Leads");
	echo"<br><h4>You can <strong>safely ignore</strong> if there are <strong>any errors</strong> during the installation</h4><br>";
	foreach ($modules as $module) 
	{
		$iv->initialize($module);
		echo "<br> ************<strong> Updating ".$module."</strong>************** <br>";
		$iv->backupModule($module);
		$iv->updateModule();
		$iv->updateTemplate();
		$iv->updateCustomWorkingModule();
		$iv->updateCache();
	}
	echo "<br><strong>**************************************</strong><br>";
	echo "<br><strong>Congratulations, InsideView for Sugar has been successfully Installed</strong>";
}
?>