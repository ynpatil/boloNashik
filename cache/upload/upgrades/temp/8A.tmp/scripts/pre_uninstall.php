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

function pre_uninstall() {
	global $current_user;
	global $sugar_config;
	global $current_user;
	global $unzip_dir;

	delete_images_from_themes($unzip_dir);

	remove_logic_hook_file("Accounts","before_save",array(1,'teamsOS_subpanel_add_team','modules/TeamsOS/TeamFormBase.php','TeamFormBase','add_team_if_needed'));
	remove_logic_hook_file("Contacts","before_save",array(1,'teamsOS_subpanel_add_team','modules/TeamsOS/TeamFormBase.php','TeamFormBase','add_team_if_needed'));
	remove_logic_hook_file("Documents","before_save",array(1,'teamsOS_subpanel_add_team','modules/TeamsOS/TeamFormBase.php','TeamFormBase','add_team_if_needed'));
	remove_logic_hook_file("Bugs","before_save",array(1,'teamsOS_subpanel_add_team','modules/TeamsOS/TeamFormBase.php','TeamFormBase','add_team_if_needed'));
	remove_logic_hook_file("Calls","before_save",array(1,'teamsOS_subpanel_add_team','modules/TeamsOS/TeamFormBase.php','TeamFormBase','add_team_if_needed'));
	remove_logic_hook_file("Campaigns","before_save",array(1,'teamsOS_subpanel_add_team','modules/TeamsOS/TeamFormBase.php','TeamFormBase','add_team_if_needed'));
	remove_logic_hook_file("Cases","before_save",array(1,'teamsOS_subpanel_add_team','modules/TeamsOS/TeamFormBase.php','TeamFormBase','add_team_if_needed'));
	remove_logic_hook_file("Leads","before_save",array(1,'teamsOS_subpanel_add_team','modules/TeamsOS/TeamFormBase.php','TeamFormBase','add_team_if_needed'));
	remove_logic_hook_file("Meetings","before_save",array(1,'teamsOS_subpanel_add_team','modules/TeamsOS/TeamFormBase.php','TeamFormBase','add_team_if_needed'));
	remove_logic_hook_file("Notes","before_save",array(1,'teamsOS_subpanel_add_team','modules/TeamsOS/TeamFormBase.php','TeamFormBase','add_team_if_needed'));
	remove_logic_hook_file("Opportunities","before_save",array(1,'teamsOS_subpanel_add_team','modules/TeamsOS/TeamFormBase.php','TeamFormBase','add_team_if_needed'));
	remove_logic_hook_file("Project","before_save",array(1,'teamsOS_subpanel_add_team','modules/TeamsOS/TeamFormBase.php','TeamFormBase','add_team_if_needed'));
	remove_logic_hook_file("ProjectTask","before_save",array(1,'teamsOS_subpanel_add_team','modules/TeamsOS/TeamFormBase.php','TeamFormBase','add_team_if_needed'));
	remove_logic_hook_file("Prospects","before_save",array(1,'teamsOS_subpanel_add_team','modules/TeamsOS/TeamFormBase.php','TeamFormBase','add_team_if_needed'));
	remove_logic_hook_file("Tasks","before_save",array(1,'teamsOS_subpanel_add_team','modules/TeamsOS/TeamFormBase.php','TeamFormBase','add_team_if_needed'));
	remove_logic_hook_file("Users","before_save",array(1,'teamsOS_subpanel_add_team','modules/TeamsOS/TeamFormBase.php','TeamFormBase','add_team_if_needed'));

	remove_logic_hook_file("Users","after_retrieve",array(1,'teamsOS_create_array','modules/TeamsOS/TeamFormBase.php','TeamFormBase','create_team_array'));

	echo "<p><strong>Finished.</strong></p>\n";
}

/*
*
* This function quickly and easily removes new image files from all the theme
* directories.
*
* Function delete_images_from_themes
* Author: Ken Brill ken.brill@gmail.com
* Date: 08-05-2006
* Copyright: All Rights Reserved ©2006
*/
function delete_images_from_themes($unzip_dir) {
	$path_parts = pathinfo($_SERVER["SCRIPT_FILENAME"]);
	$path=$path_parts['dirname']."/";

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
		if($theme_name!="." && $theme_name!=".." && is_dir($path.$theme_dir.$theme_name)) {
			echo "Removing images from $theme_name<br>";
			foreach ($images as $image_name) {
				if($image_name!="." && $image_name!=".." ) {
					$success = @unlink($path.$theme_dir.$theme_name."/images/".$image_name);
					if(!$success) {
						echo "<font color=red><b>Copy Error!</b></font> Cannot remove file $image_name in theme $theme_name<br>";
					}
					if(is_file($path.$theme_dir.$theme_name."/images/".$image_name.".sugarcubed")) {
						//Restore the back up file
						$success=@copy($path.$theme_dir.$theme_name."/images/".$image_name.".sugarcubed",$path.$theme_dir.$theme_name."/images/".$image_name);
						if(!$success) {
							echo "<font color=red><b>Copy Error!</b></font> Cannot restore backup file $image_name in theme $theme_name<br>";
						}
						$success=@unlink($path.$theme_dir.$theme_name."/images/".$image_name.".sugarcubed");
						if(!$success) {
							echo "<font color=red><b>Copy Error!</b></font> Cannot delete backup file $image_name in theme $theme_name<br>";
						}
					}
				}
			}
		}
	}
}

function remove_logic_hook_file($module_name, $event, $action_array){
	require_once('include/utils/logic_utils.php');

	if(file_exists("custom/modules/$module_name/logic_hooks.php")){
		$hook_array = get_hook_array($module_name);
		if(check_existing_element($hook_array, $event, $action_array)==true){
			$new_contents = remove_existing_element($hook_array, $event, $action_array);
			if(empty($new_contents)) {
				unlink("custom/modules/$module_name/logic_hooks.php");
			} else {
				write_logic_file($module_name, $new_contents);
			}
		}
	}

//end function check_logic_hook_file
}

//This is not the real way to do this, I should do it in the array but I couldn't make that work
//and do the bare minimum work around the house needed not to end up in divorce court.... so here it is.
//quick and dirty
function remove_existing_element($hook_array, $event, $action_array){
	$hook_contents = "";
	$empty_file=true;

	$final_array .= "// Do not store anything in this file that is not part of the array or the hook version.  This file will	\n";
	$final_array .= "// be automatically rebuilt in the future. \n ";
	$final_array .= "\$hook_version = 1; \n";
	$final_array .= "\$hook_array = Array(); \n";
	$final_array .= "// position, file, function \n";

	foreach($hook_array as $event_array => $events){
		$add_item=true;
		$hook_contents = "\$hook_array['".$event_array."'] = Array(); \n";

		foreach($events as $second_key => $elements){

			$hook_contents .= "\$hook_array['".$event_array."'][] = ";
			$hook_contents .= "Array(".$elements[0].", '".$elements[1]."', '".$elements[2]."','".$elements[3]."', '".$elements[4]."'); \n";

			if($event==$event_array && $elements[1]==$action_array[1]) {
				$add_item=false;
			}
		}

		if($add_item==true) {
			$empty_file=false;
			$final_array .= $hook_contents;
		}

	}
	$final_array .= "\n\n";
	if($empty_file==true) {
		return '';
	} else {
		return $final_array;
	}
}
?>
