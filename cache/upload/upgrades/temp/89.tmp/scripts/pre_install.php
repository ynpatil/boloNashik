<table width="90%" border="1" cellspacing="0" cellpadding="5">
  <tr>
    <td width="39%"><a href="http://www.crmupgrades.com"><img src="http://www.crmupgrades.com/images/crmupgrades.jpg" width="342" height="335" alt="CRMUpgrads Logo" /></a></td>
    <td width="61%" align="center" valign="top"><div align="center"><span class="style1">
      <h1 class="style2">CRMUpgrades.com Presents.... </h1>
      </span>
        <p class="style2"><span class="style1">Lampada CRM's TeamsOS 3.0d</span></p>
        <p align="center" class="style2"><span class="style1">For SugarCRM 4.5.0</span></p>
        <div align="left">
          <ul>
            <li class="style4">Do you need help making your module upgrade safe?</li>
            <li class="style4">Do you need two or more modules merged so that they work together?</li>
            <li class="style4">Do you need Demo Data removed from your system?</li>
            <li class="style4">Do you need help upgrading your production system to the latest SugarCRM?</li>
            <li class="style4">Do you need a simple modification or bugfix for SugarCRM? </li>
          </ul>
        </div>
        </div></td>
  </tr>
  <tr>
    <td colspan="2"><div align="center">Visit <a href="http://www.crmupgrades.com">www.crmupgrades.com</a> to see all the services and products we offer! </div></td>
  </tr>
</table>
<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * This script executes after the files are copied during the install.
 *
 * LICENSE: The contents of this file are subject to the SugarCRM Professional
 * End User License Agreement ("License") which can be viewed at
 * http://www.sugarcrm.com/EULA.  By installing or using this file, You have
 * unconditionally agreed to the terms and conditions of the License, and You
 * may not use this file except in compliance with the License.  Under the
 * terms of the license, You shall not, among other things: 1) sublicense,
 * resell, rent, lease, redistribute, assign or otherwise transfer Your
 * rights to the Software, and 2) use the Software for timesharing or service
 * bureau purposes such as hosting the Software for commercial gain and/or for
 * the benefit of a third party.  Use of the Software may be subject to
 * applicable fees and any use of the Software without first paying applicable
 * fees is strictly prohibited.  You do not have the right to remove SugarCRM
 * copyrights from the source code or user interface.
 *
 * All copies of the Covered Code must include on each user interface screen:
 *  (i) the "Powered by SugarCRM" logo and
 *  (ii) the SugarCRM copyright notice
 * in the same form as they appear in the distribution.  See full license for
 * requirements.
 *
 * Your Warranty, Limitations of liability and Indemnity are expressly stated
 * in the License.  Please refer to the License for the specific language
 * governing these rights and limitations under the License.  Portions created
 * by SugarCRM are Copyright (C) 2005 SugarCRM, Inc.; All Rights Reserved.
 */


function pre_install() {
	require_once('include/utils.php');
	require_once('ModuleInstall/ModuleInstaller.php');
	global $current_user;
	global $sugar_config;
	global $current_user;
	global $unzip_dir;

	$path_parts = pathinfo($_SERVER["SCRIPT_FILENAME"]);
	$path_name=$path_parts['dirname'];

	$TeamsOS_File = $path_name."/modules/TeamsOS/TeamOS.php";

	if(!file_exists($TeamsOS_File)) {
		copy_images_to_themes($unzip_dir);
	//	check_logic_hook_file("MODULE","LOGIC HOOK NAME",array(ORDER,'NAME','FILE','CLASS','FUNCTION'));
		check_logic_hook_file("Accounts","before_save",array(1,'teamsOS_subpanel_add_team','modules/TeamsOS/TeamFormBase.php','TeamFormBase','add_team_if_needed'));
		check_logic_hook_file("Contacts","before_save",array(1,'teamsOS_subpanel_add_team','modules/TeamsOS/TeamFormBase.php','TeamFormBase','add_team_if_needed'));
		check_logic_hook_file("Documents","before_save",array(1,'teamsOS_subpanel_add_team','modules/TeamsOS/TeamFormBase.php','TeamFormBase','add_team_if_needed'));
		check_logic_hook_file("Bugs","before_save",array(1,'teamsOS_subpanel_add_team','modules/TeamsOS/TeamFormBase.php','TeamFormBase','add_team_if_needed'));
		check_logic_hook_file("Calls","before_save",array(1,'teamsOS_subpanel_add_team','modules/TeamsOS/TeamFormBase.php','TeamFormBase','add_team_if_needed'));
		check_logic_hook_file("Campaigns","before_save",array(1,'teamsOS_subpanel_add_team','modules/TeamsOS/TeamFormBase.php','TeamFormBase','add_team_if_needed'));
		check_logic_hook_file("Cases","before_save",array(1,'teamsOS_subpanel_add_team','modules/TeamsOS/TeamFormBase.php','TeamFormBase','add_team_if_needed'));
		check_logic_hook_file("Leads","before_save",array(1,'teamsOS_subpanel_add_team','modules/TeamsOS/TeamFormBase.php','TeamFormBase','add_team_if_needed'));
		check_logic_hook_file("Meetings","before_save",array(1,'teamsOS_subpanel_add_team','modules/TeamsOS/TeamFormBase.php','TeamFormBase','add_team_if_needed'));
		check_logic_hook_file("Notes","before_save",array(1,'teamsOS_subpanel_add_team','modules/TeamsOS/TeamFormBase.php','TeamFormBase','add_team_if_needed'));
		check_logic_hook_file("Opportunities","before_save",array(1,'teamsOS_subpanel_add_team','modules/TeamsOS/TeamFormBase.php','TeamFormBase','add_team_if_needed'));
		check_logic_hook_file("Project","before_save",array(1,'teamsOS_subpanel_add_team','modules/TeamsOS/TeamFormBase.php','TeamFormBase','add_team_if_needed'));
		check_logic_hook_file("ProjectTask","before_save",array(1,'teamsOS_subpanel_add_team','modules/TeamsOS/TeamFormBase.php','TeamFormBase','add_team_if_needed'));
		check_logic_hook_file("Prospects","before_save",array(1,'teamsOS_subpanel_add_team','modules/TeamsOS/TeamFormBase.php','TeamFormBase','add_team_if_needed'));
		check_logic_hook_file("Tasks","before_save",array(1,'teamsOS_subpanel_add_team','modules/TeamsOS/TeamFormBase.php','TeamFormBase','add_team_if_needed'));
		check_logic_hook_file("Users","before_save",array(1,'teamsOS_subpanel_add_team','modules/TeamsOS/TeamFormBase.php','TeamFormBase','add_team_if_needed'));

		check_logic_hook_file("Users","after_retrieve",array(1,'teamsOS_create_array','modules/TeamsOS/TeamFormBase.php','TeamFormBase','create_team_array'));
		check_logic_hook_file("Accounts","after_retrieve",array(1,'teamsOS_subpanel_add_team','modules/TeamsOS/TeamFormBase.php','TeamFormBase','Security_Check'));
		check_logic_hook_file("Contacts","after_retrieve",array(1,'teamsOS_subpanel_add_team','modules/TeamsOS/TeamFormBase.php','TeamFormBase','Security_Check'));
		check_logic_hook_file("Documents","after_retrieve",array(1,'teamsOS_subpanel_add_team','modules/TeamsOS/TeamFormBase.php','TeamFormBase','Security_Check'));
		check_logic_hook_file("Bugs","after_retrieve",array(1,'teamsOS_subpanel_add_team','modules/TeamsOS/TeamFormBase.php','TeamFormBase','Security_Check'));
		check_logic_hook_file("Calls","after_retrieve",array(1,'teamsOS_subpanel_add_team','modules/TeamsOS/TeamFormBase.php','TeamFormBase','Security_Check'));
		check_logic_hook_file("Campaigns","after_retrieve",array(1,'teamsOS_subpanel_add_team','modules/TeamsOS/TeamFormBase.php','TeamFormBase','Security_Check'));
		check_logic_hook_file("Cases","after_retrieve",array(1,'teamsOS_subpanel_add_team','modules/TeamsOS/TeamFormBase.php','TeamFormBase','Security_Check'));
		check_logic_hook_file("Leads","after_retrieve",array(1,'teamsOS_subpanel_add_team','modules/TeamsOS/TeamFormBase.php','TeamFormBase','Security_Check'));
		check_logic_hook_file("Meetings","after_retrieve",array(1,'teamsOS_subpanel_add_team','modules/TeamsOS/TeamFormBase.php','TeamFormBase','Security_Check'));
		check_logic_hook_file("Notes","after_retrieve",array(1,'teamsOS_subpanel_add_team','modules/TeamsOS/TeamFormBase.php','TeamFormBase','Security_Check'));
		check_logic_hook_file("Opportunities","after_retrieve",array(1,'teamsOS_subpanel_add_team','modules/TeamsOS/TeamFormBase.php','TeamFormBase','Security_Check'));
		check_logic_hook_file("Project","after_retrieve",array(1,'teamsOS_subpanel_add_team','modules/TeamsOS/TeamFormBase.php','TeamFormBase','Security_Check'));
		check_logic_hook_file("ProjectTask","after_retrieve",array(1,'teamsOS_subpanel_add_team','modules/TeamsOS/TeamFormBase.php','TeamFormBase','Security_Check'));
		check_logic_hook_file("Prospects","after_retrieve",array(1,'teamsOS_subpanel_add_team','modules/TeamsOS/TeamFormBase.php','TeamFormBase','Security_Check'));
		check_logic_hook_file("Tasks","after_retrieve",array(1,'teamsOS_subpanel_add_team','modules/TeamsOS/TeamFormBase.php','TeamFormBase','Security_Check'));
	} else {
		uninstall_menus();
		if(!file_exists($path_name."/modules/TeamsOS/db_updated.lck")) {
			$installdefs = array('custom_fields'=>array(
				array('name'=>'default_team_id',
							'label'=>'Default Team',
							'type'=>'enum',
							'max_size'=>36,
							'require_option'=>'optional',
							'default_value'=>'',
							'ext1'=>'teams_array',
							'ext2'=>'',
							'ext3'=>'',
							'audited'=>1,
							'mass_update'=>0,
							'module'=>'Users'
				)
			  )
			);
			uninstall_custom_fields($installdefs['custom_fields']);
			$fp=fopen($path_name."/modules/TeamsOS/db_updated.lck","w");
			fwrite($fp,"3.0c");
			fclose($fp);
		}
		echo "<font size=+1>Updating Teams 3.0a Install</font><br>";
	}
	echo "<p><strong>Finished.</strong></p>\n";
}

function uninstall_custom_fields($fields){
	global $beanList, $beanFiles;
	echo "Removing default_team_id from Users module.  You will have to reset all of your users Teams.<br>";
	require_once('modules/DynamicFields/DynamicField.php');
	$dyField = new DynamicField();

	foreach($fields as $field){
		$class = $beanList[ $field['module']];
		if(file_exists($beanFiles[$class])){
				require_once($beanFiles[$class]);
				$mod = new $class();
				$dyField->bean = $mod;
				$dyField->module = $field['module'];
				$dyField->dropField($field['name']);
		}
	}
}

function uninstall_menus() {
	$modInst = new ModuleInstaller();
	$installdefs = array(
			'menu'=> array(
				array('from'=> '<basepath>/newfiles/TeamsOS_menu_code.php',
					  'to_module'=> 'Accounts'
				),
				array('from'=> '<basepath>/newfiles/TeamsOS_menu_code.php',
					  'to_module'=> 'Documents'
				),
				array('from'=> '<basepath>/newfiles/TeamsOS_menu_code.php',
					  'to_module'=> 'Contacts'
				),
				array('from'=> '<basepath>/newfiles/TeamsOS_menu_code.php',
					  'to_module'=> 'Bugs'
				),
				array('from'=> '<basepath>/newfiles/TeamsOS_menu_code.php',
					  'to_module'=> 'Calls'
				),
				array('from'=> '<basepath>/newfiles/TeamsOS_menu_code.php',
					  'to_module'=> 'Campaigns'
				),
				array('from'=> '<basepath>/newfiles/TeamsOS_menu_code.php',
					  'to_module'=> 'Cases'
				),
				array('from'=> '<basepath>/newfiles/TeamsOS_menu_code.php',
					  'to_module'=> 'Employees'
				),
				array('from'=> '<basepath>/newfiles/TeamsOS_menu_code.php',
					  'to_module'=> 'Leads'
				),
				array('from'=> '<basepath>/newfiles/TeamsOS_menu_code.php',
					  'to_module'=> 'Meetings'
				),
				array('from'=> '<basepath>/newfiles/TeamsOS_menu_code.php',
					  'to_module'=> 'Notes'
				),
				array('from'=> '<basepath>/newfiles/TeamsOS_menu_code.php',
					  'to_module'=> 'Opportunities'
				),
				array('from'=> '<basepath>/newfiles/TeamsOS_menu_code.php',
					  'to_module'=> 'Project'
				),
				array('from'=> '<basepath>/newfiles/TeamsOS_menu_code.php',
					  'to_module'=> 'ProjectTask'
				),
				array('from'=> '<basepath>/newfiles/TeamsOS_menu_code.php',
					  'to_module'=> 'Prospects'
				),
				array('from'=> '<basepath>/newfiles/TeamsOS_menu_code.php',
					  'to_module'=> 'Tasks'
				),
				array('from'=> '<basepath>/newfiles/TeamsOS_menu_code.php',
					  'to_module'=> 'Users'
				),
			)
	);
	if(isset($installdefs['menu'])){
		foreach($installdefs['menu'] as $menu){
			echo "Uninstalling menu from module: " . $menu['to_module'] . "<br>";
			$path = 'custom/Extension/modules/' . $menu['to_module']. '/Ext/Menus';
			if($menu['to_module'] == 'application'){
				$path ='custom/Extension/' . $menu['to_module']. '/Ext/Menus';
			}
			echo " --> Delete: " . $path . "<br>";
			rmdir_recursive( $path );
		}
		$modInst->rebuild_menus();
	}
}
/*
*
* This function quickly and easily copys new image files to all the theme
* directories.  This is needed when you add menu items or items to the
* admin menu and Sugar looks in the {current_theme}/images directory ONLY
* for the little "icon" graphics.  This means that if you want to add an "icon"
* you have to copy it to all themes, and users may have theme that you don't know about
* so this function copies images to all themes, not just the default ones. All
* icon files MUST be gif files.  I would like to suggest that a function be written
* that would allow images to be either gif or png.
*
* Function copy_images_to_themes
* Author: Ken Brill ken.brill@gmail.com
* Date: 08-05-2006
* Copyright: All Rights Reserved ©2006
*/
function copy_images_to_themes($unzip_dir) {
	$path_parts = pathinfo($_SERVER["SCRIPT_FILENAME"]);
	$path_name=$path_parts['dirname']."/";

	$tmp_dir = $unzip_dir."/themes/images/";
	$theme_dir = "themes/";
	if(function_exists("scandir")) {
		$images = scandir($tmp_dir);
		$themes = scandir($theme_dir);
	} else {
		$dh  = opendir($tmp_dir);
		while (false !== ($filename = readdir($dh))) {
		   $images[] = $filename;
		}
		sort($images);
		closedir($dh);
		$dh  = opendir($theme_dir);
		while (false !== ($filename = readdir($dh))) {
		   $themes[] = $filename;
		}
		sort($themes);
		closedir($dh);
	}

	foreach ($themes as $theme_name) {
		if($theme_name!="." && $theme_name!=".." ) {
			echo "Updating $theme_name<br>";
			ob_flush();
			foreach ($images as $image_name) {
				if($image_name!="." && $image_name!=".." && is_dir($path_name.$theme_dir.$theme_name)) {
					//echo ">  Coping " . $image_name . "<br>";
					if(is_file($path_name.$theme_dir.$theme_name."/images/".$image_name)) {
						//Backup the original file for uninstall purposes
						$success = @copy($path_name.$theme_dir.$theme_name."/images/".$image_name,$path_name.$theme_dir.$theme_name."/images/".$image_name.".sugarcubed");
						if(!$success) {
							echo "<font color=red><b>Copy Error!</b></font> Cannot backup file $image_name in theme $theme_name<br>";
						}
					}
					$success = @copy($tmp_dir.$image_name,$path_name.$theme_dir.$theme_name."/images/".$image_name);
					if(!$success) {
						echo "<font color=red><b>Copy Error!</b></font> Cannot copy file $image_name into theme $theme_name<br>";
					}
				}
			}
		}
	}
}
?>
