<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * ZuckerDocs by go-mobile
 * Copyright (C) 2005 Florian Treml, go-mobile
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the 
 * GNU General Public License as published by the Free Software Foundation; either version 2 of the 
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even 
 * the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General 
 * Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program; if not, 
 * write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 */

require_once('XTemplate/xtpl.php');
require_once('modules/ZuckerDocs/ZuckerDocument.php');

global $current_user;

$xtplMenu = new XTemplate ('modules/ZuckerDocs/DocumentMenu.html');
	
if (isset($_REQUEST['return_module'])) $xtplMenu->assign("RETURN_MODULE", $_REQUEST['return_module']);
if (isset($_REQUEST['return_action'])) $xtplMenu->assign("RETURN_ACTION", $_REQUEST['return_action']);
if (isset($_REQUEST['return_id'])) $xtplMenu->assign("RETURN_ID", $_REQUEST['return_id']);
$xtplMenu->assign("MOD", $mod_strings);
$xtplMenu->assign("APP", $app_strings);
$xtplMenu->assign("THEME", $theme);
$xtplMenu->assign("GRIDLINE", $gridline);
$xtplMenu->assign("IMAGE_PATH", $image_path);

$xtplMenu->assign("ID", $focus->id);
$xtplMenu->assign("PARENT_NAME", $focus->parent_name);
$xtplMenu->assign("PARENT_MODULE", $focus->parent_type);
$xtplMenu->assign("PARENT_ID", $focus->parent_id);
$xtplMenu->assign("PARENT_LINK", $focus->parent_link);

$xtplMenu->parse("documentmenu.detailview");
if(is_admin($current_user)){
	$xtplMenu->parse("documentmenu.editmeta");
	$xtplMenu->parse("documentmenu.deletedocument");

	$editcontentEnabled = false;
	if (!$focus->is_checked_out || (strtolower($current_user->user_name) == strtolower($focus->checkedout_username))) {
		if (strstr($focus->mimetype, "text/")) {
			$xtplMenu->parse("documentmenu.editcontent");
			$editcontentEnabled = true;
		}
	}
}	
$quickviewEnabled = false;
if (strstr($focus->mimetype, "text/") || strstr($focus->mimetype, "image/")) {
	$xtplMenu->parse("documentmenu.quickview");
	$quickviewEnabled = true;
}

echo "\n<p>\n";
echo ZuckerDocument::get_root_line_links($focus->folder_id);
echo "\n</p>\n";
$xtplMenu->parse("documentmenu");
$xtplMenu->out("documentmenu");
?>
