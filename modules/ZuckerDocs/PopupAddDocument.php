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

global $theme;
require_once('modules/ZuckerDocs/ZuckerDocument.php');
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/logging.php');
require_once('XTemplate/xtpl.php');
require_once('include/utils.php');
require_once('include/ListView/ListView.php');

global $app_strings;
global $app_list_strings;
global $mod_strings;
$image_path = 'themes/'.$theme.'/images/';

$printForm = TRUE;

if ($_REQUEST['save'] == 'true') {

	$contents = $_REQUEST['contents'];
	$filename = $_REQUEST['filename'];
	$parentType = $_REQUEST['parent_module'];
	$parentId = $_REQUEST['parent_id'];
	$catName = $_REQUEST['cat_name'];
	$description = $_REQUEST['description'];
	
	if (empty($contents) || empty($filename)) {
		$err = new KT_DocumentsError(DOCERROR_FILENOTUPLOADED);
	} else if (empty($parentType) || empty($parentId)) {
		$err = new KT_DocumentsError(DOCERROR_PARENTNOTSPECIFIED);
	}
	if (isset($err)) {
		$errorMessage = KT_SugarProvider::formatError($err);
	} else {
		$res = KT_SugarProvider::addDocument(base64_decode($contents), $filename, $parentType, $parentId, $catName, $description);
		if (isDocumentsError($res)) {
			$errorMessage = KT_SugarProvider::formatError($res);
		} else {
			if (!empty($_REQUEST['success_url'])) {
				header('Location: '.$_REQUEST['success_url']);
				die;
			} else {
				$errorMessage = 'The document has been saved. <a href="javascript:window.close()">Fenster schlie�en</a>';
				$printForm = FALSE;
			}
		}
	}
} else if ($_REQUEST['save'] == 'cancel') {
	if (!empty($_REQUEST['cancel_url'])) {
		header('Location: '.$_REQUEST['cancel_url']);
		die;
	} else {
		$errorMessage = 'The process has been canceled. <a href="javascript:window.close()">Fenster schlie�en</a>';
		$printForm = FALSE;
	}
}

insert_popup_header($theme);
if (!empty($_REQUEST['title'])) {
	echo get_module_title($mod_strings['LBL_MODULE_NAME'], $_REQUEST['title'], true); 
} else {
	echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_DOCUMENT_NEW'], true); 
}

echo "<p/>";
if (!empty($errorMessage)) {
	echo $errorMessage."<p/>";
}

if ($printForm) {
	$form = new XTemplate ('modules/ZuckerDocs/PopupAddDocument.html');
	$form->assign("MOD", $mod_strings);
	$form->assign("APP", $app_strings);
	$form->assign("THEME", $theme);
	$form->assign("IMAGE_PATH", $image_path);
	$form->assign("MODULE_NAME", $currentModule);
	
	$types = array('', 
		'Folders' => $mod_strings['LBL_FOLDER'],
		'Contacts' => $app_list_strings['moduleList']['Contacts'],
		'Accounts' => $app_list_strings['moduleList']['Accounts'],
		'Opportunities' => $app_list_strings['moduleList']['Opportunities'],
		'Cases' => $app_list_strings['moduleList']['Cases'],
		'Notes' => $app_list_strings['moduleList']['Notes'],
		'Calls' => $app_list_strings['moduleList']['Calls'],
		'Emails' => $app_list_strings['moduleList']['Emails'],
		'Meetings' => $app_list_strings['moduleList']['Meetings'],
		'Tasks' => $app_list_strings['moduleList']['Tasks'],
		'Leads' => $app_list_strings['moduleList']['Leads'],
		'Bugs' => $app_list_strings['moduleList']['Bugs'],
		'Project' => $app_list_strings['moduleList']['Project'],
		'ProjectTask' => $app_list_strings['moduleList']['ProjectTask'],
	);
	$form->assign("TYPE_OPTIONS", get_select_options_with_id($types, $_REQUEST['parent_module']));
	if (!empty($_REQUEST['parent_module'])) {
		
		$parent_ids = array('');
		if ($_REQUEST['parent_module'] == 'Folders') {
			$rootFolder = KT_SugarProvider::getRootFolder();
			$si = strlen($rootFolder->full_path."/".$rootFolder->name);
			$folders = KT_SugarProvider::getSubFolders($rootFolder->id, TRUE, TRUE);
			foreach ($folders as $folder) {
				$parent_ids[$folder->id] = substr($folder->full_path."/".$folder->name, $si);
			}
		} else {
			global $beanList, $beanFiles;
		
			$type_name = $beanList[$_REQUEST['parent_module']];
			require_once($beanFiles[$type_name]);
			$seed = new $type_name;
			$list = $seed->get_full_list();
	
			if (count($list) > 0) {
				foreach ($list as $bean) {
					$parent_ids[$bean->id] = $bean->get_summary_text();
				}
			}
		}
		$form->assign("PARENTID_OPTIONS", get_select_options_with_id($parent_ids, $_REQUEST['parent_id']));
	}
	
	if (is_uploaded_file($_FILES['contents']['tmp_name'])) {
		$filename = $_FILES['contents']['name'];
		$contents = file_get_contents($_FILES['contents']['tmp_name']);
		$_REQUEST['contents'] = base64_encode($contents);
		$_REQUEST['filename'] = $filename;
	}
	$form->assign("CONTENTS", $_REQUEST['contents']);
	
	$form->assign("DESCRIPTION", $_REQUEST['description']);
	$form->assign("FILENAME", $_REQUEST['filename']);
	$form->assign("CATNAME_OPTIONS", get_select_options_with_id($app_list_strings['doc_category'], $_REQUEST['cat_name']));
	
	$form->assign("CANCEL_URL", $_REQUEST['cancel_url']);
	$form->assign("SUCCESS_URL", $_REQUEST['success_url']);
	$form->assign("TITLE", $_REQUEST['title']);
	$form->parse("main");
	$form->out("main");
}

insert_popup_footer();
?>
