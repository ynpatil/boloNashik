<?PHP 
/* This script uninstalls InsideView */
include("include/InsideView/class.insideview.php");
//post_uninstall();

if(!defined('sugarEntry') || !sugarEntry) 
	die('Not A Valid Entry Point');

function pre_uninstall() 
{
	$iv = new InsideView;
	$modules = array("Accounts", "Contacts", "Leads");	
		
	echo"<br><h4> You can <strong>safely ignore</strong> if there are <strong>any errors</strong> during Uninstallation</h4><br>";
	foreach ($modules as $module) 
	{
		$iv->getFiles($module); //Get the names of the files to be patched
		echo "<br> ************ Uninstalling ".$module." Module ************** <br>";
		$iv->uninstallModule();
		$iv->uninstallTemplate();
		$iv->uninstallCache();
		$iv->uninstallCutomWorkingModule();
	}
	$iv->removeFiles();
	$iv->removeIvDir();
	echo "<br><br> ########### SUCCESSFULLY UNINSTALLED INSIDEVIEW FOR SUGAR ############# <br>";
}
?>