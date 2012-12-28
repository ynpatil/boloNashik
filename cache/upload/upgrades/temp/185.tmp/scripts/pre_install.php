<table width="90%" border="1" cellspacing="0" cellpadding="5">
  <tr>
    <td width="39%"><img src="http://www.crmupgrades.com/images/crmupgrades.jpg" width="342" height="335" alt="CRMUpgrads Logo" /></td>
    <td width="61%" align="center" valign="top"><div align="center"><span class="style1">
      <h1 class="style2">CRMUpgrades.com Presents.... </h1>
      </span>
        <p class="style2"><span class="style1">In / Out Board</span></p>
        <p align="center" class="style2"><span class="style1">For SugarCRM 4.5.0 and 4.5.1<br>Now TeamsOS Aware!</span></p>
        <div align="left">
          <ul>
            <li class="style4">
              Do you need help making your module upgrade safe?</li>
            <li class="style4">
              Do you need two or more modules merged so that they work together?</li>
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
	global $current_user;
	global $sugar_config;
	global $current_user;
	global $unzip_dir;

	//copy_images_to_themes($unzip_dir);

	echo "<p><strong>Finished.</strong></p>\n";
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
