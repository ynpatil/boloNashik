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
require_once('include/utils.php');
require_once('dms/sugarprovider.inc');

global $current_language;
$current_module_strings = return_module_language($current_language, 'ZuckerDocs');

$parent_type = 'Folders';
$parent_id = FOLDER_MYDOCUMENTS_ID;
$skip_new_button = TRUE;
$skip_list_button = TRUE;
$header_title = $current_module_strings['LBL_MYDOCUMENTS'];
require_once('modules/ZuckerDocs/SubPanelView.php');

?>