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
 // To make Logging in ZuckerDocument.php
//#$# Start
include_once('config.php');
require_once('include/modules.php');
require_once('include/database/PearDatabase.php');
require_once('data/SugarBean.php');
require_once('modules/Accounts/Account.php');
require_once('modules/Contacts/Contact.php');
require_once('modules/Leads/Lead.php');
require_once('modules/Cases/Case.php');
require_once('modules/Emails/Email.php');
require_once('modules/Calls/Call.php');
require_once('modules/Opportunities/Opportunity.php');
require_once('dms/sugarprovider.inc');


/*
// #$# Not sure whether I can safely use the name 'dms' for logger here, so keeping the original name.
if(empty($GLOBALS['log'])) {
	$GLOBALS['log'] = LoggerManager::getLogger('SugarCRM');
}
*/
require_once('include/logging.php');
//require_once('log4php/LoggerManager.php');

function unhtmlentities($string) {
   $trans_tbl = get_html_translation_table(HTML_ENTITIES);
   $trans_tbl = array_flip($trans_tbl);
   return strtr($string, $trans_tbl);
}

class ZuckerDocumentTransaction extends SugarBean {
	var $version;
	var $user;
	var $datetime;
	var $comment;
	var $type;
	
	

	var $object_name = "ZuckerDocumentTransaction";
	var $module_dir = "ZuckerDocs";
	var $new_schema = false;

	function ZuckerDocumentTransaction($dt) {
		parent::SugarBean();

		$this->version = $dt->version;
		$this->user = $dt->user;
		$this->datetime = $dt->datetime;
		$this->comment = $dt->comment;
		$this->type = $dt->type;
	}
}


class FolderItem extends SugarBean {
	var $id;
	var $name;
	var $description;
	var $creator;
	
	var $object_name = "FolderItem";
	var $module_dir = "ZuckerDocs";
	var $new_schema = false;

	function FolderItem() {
		parent::SugarBean();
	}
	
	function fromFolder($folder) {
		$this->id = $folder->id;
		$this->name = $folder->name;
		$this->description = $folder->description;
		$this->creator = $folder->creator;
	}
}

class ZuckerDocument extends SugarBean  {
	
	var $id;
	var $name;
	var $filename;
	var $description;
	var $modified;
	var $is_checked_out;
	var $checkedout_username;
	var $version;
	var $mimetype;
	var $folder_id;
	
	var $parent_type;
	var $parent_id;
	var $parent_name;
	var $parent_link;
	var $parent_action;
	var $cat_name;
	var $cat_description;
	var $icon_path;
	
	var $url;
	var $status;
	
	var $score;
	
	var $errorMessage;
	
	var $object_name = "ZuckerDocument";
	var $module_dir = "ZuckerDocs";
	var $new_schema = false;

	//#$#
	//var $dmsLog;

	function ZuckerDocument() {
		parent::SugarBean();
	}

	function retrieve($id = -1, $encode=true) {
		//global $dmsLog;

		//#$#
		//$this->dmsLog = &LoggerManager::getLogger('dms');
		//$this->dmsLog->fatal("#$# Test Msg Inside retrieve of ZD");

		if ($id >= 0) {
			$this->id = $id;
		}
		$doc = KT_SugarProvider::getDocument($this->id);
		if (isDocumentsError($doc)) {
			$this->errorMessage = KT_SugarProvider::formatError($doc);
		} else {
			$this->fromDocument($doc);
			return $this;
		}
	}
	
    function mark_deleted($id) {
		$doc = KT_SugarProvider::deleteDocument($id);
		if (isDocumentsError($doc)) {
			$this->errorMessage = KT_SugarProvider::formatError($doc);
		}
	}
	
	function fromDocument($doc) {
		global $dmsLog;
		$dmsLog->fatal("#$# Test Msg Inside retrieve of ZD");

		$this->id = $doc->id;
		$this->name = $doc->name;
		$this->filename = $doc->filename;
		$this->description = $doc->description;
		$this->modified = $doc->modified;
		$this->is_checked_out = $doc->is_checked_out;
		$this->checkedout_username = $doc->checkedout_username;
		$this->version = $doc->version;
		$this->mimetype = $doc->mimetype;
		$this->folder_id = $doc->folder_id;
		$this->cat_name = $doc->category;

		if ($doc->icon_path == '' ||  $doc->icon_path == NULL) {
			$this->icon_path = 'unknown';
			/** MOD-MAY18 removing extension.
				$this->icon_path = 'unknown.gif';
			*/
		} else {
			$this->icon_path = $doc->icon_path;
		}
		$this->score = $doc->score;
		
		/** #$# MOD-MAY09 Modified to roughly translate the path of the icon
			#$# TODO Replicate iconPath resolution technique used by KT.
		$this->icon_path = "modules/ZuckerDocs/".($this->icon_path);
		*/

		$this->icon_path = "modules/ZuckerDocs/icons/".($this->icon_path).".gif";
		
		$this->parent_type = $doc->parentType;
		$this->parent_id = $doc->parentId;
		$this->parent_name = $doc->parentName;
		$this->fill_in_additional_detail_fields();	
	}
	
	function get_history($id = -1) {
		global $dmsLog;
		$dmsLog->fatal("#$# Test Msg Inside retrieve of ZD");

		$lid = $this->id;
		if ($id >= 0) {
			$lid = $id;
		}
		$history = KT_SugarProvider::getDocumentTransactions($lid);
		if (isDocumentsError($history)) {
			return KT_SugarProvider::formatError($history);
		} else {
			$result = array();
			foreach ($history as $h) {
				$mt = new ZuckerDocumentTransaction($h);
				if ($h->file_exists) {
					$mt->version = '<a href="download.php?module=ZuckerDocs&action=ViewDocument&record='.$lid.'&version='.$mt->version.'" target="_blank">'.$mt->version.'</a>';
				}
				$result[] = $mt;
			}
			return $result;
		}
	}
	
	function find_for_parent($parent_type, $parent_id) {
		global $dmsLog;
		$dmsLog->fatal("#$# Test Msg Inside retrieve of ZD");

		if ($parent_type == 'Folders') {
			$folder = KT_SugarProvider::getFolderDetails($parent_id);
			if (isDocumentsError($folder)) {
				return KT_SugarProvider::formatError($folder);
			}
			$docs = KT_SugarProvider::getSubDocuments($folder->id, TRUE);
		} else {
			$docs = KT_SugarProvider::getDocumentsForParent($parent_type, $parent_id, 'name', TRUE, 0, 20);
		}

		if (isDocumentsError($docs)) {
			return KT_SugarProvider::formatError($docs);
		} else {
			$beans = array();
			foreach ($docs as $doc) {
				$bean = new ZuckerDocument();
				$bean->id = $doc->id;
				$bean->fromDocument($doc);
				$beans[] = $bean;
			}
			return $beans;
		}
	}

	function find($parent_type, $parent_id, $cat_name, $filename, $orderBy, $orderByAsc) {
		global $dmsLog;
		$dmsLog->fatal("#$# Test Msg Inside retrieve of ZD");

		$docs = KT_SugarProvider::searchDocuments($parent_type, $parent_id, $filename, $cat_name, $orderBy, $orderByAsc);
		if (isDocumentsError($docs)) {
			return KT_SugarProvider::formatError($docs);
		} else {
			$beans = array();
			foreach ($docs as $doc) {
				$bean = new ZuckerDocument();
				$bean->id = $doc->id;
				$bean->fromDocument($doc);
				$beans[] = $bean;
			}
			return $beans;
		}
	}
	
	function findByText($text) {
		global $dmsLog;
		$dmsLog->fatal("#$# Test Msg Inside retrieve of ZD");

		$docs = KT_SugarProvider::searchDocumentsText($text);
		if (isDocumentsError($docs)) {
			return KT_SugarProvider::formatError($docs);
		} else {
			$beans = array();
			foreach ($docs as $doc) {
				$bean = new ZuckerDocument();
				$bean->id = $doc->id;
				$bean->fromDocument($doc);
				$beans[] = $bean;
			}
			return $beans;
		}
	}

	function getRecentlyChangedDocuments() {
		global $dmsLog;
		$dmsLog->fatal("#$# Test Msg Inside retrieve of ZD");

		$docs = KT_SugarProvider::getRecentlyChangedDocuments();
		if (isDocumentsError($docs)) {
			return KT_SugarProvider::formatError($docs);
		} else {
			$beans = array();
			foreach ($docs as $doc) {
				$bean = new ZuckerDocument();
				$bean->id = $doc->id;
				$bean->fromDocument($doc);
				$beans[] = $bean;
			}
			return $beans;
		}
	}
	
	function find_linked_documents($id = -1) {
		global $dmsLog;
		$dmsLog->fatal("#$# Test Msg Inside retrieve of ZD");

		$lid = $this->id;
		if ($id >= 0) {
			$lid = $id;
		}

		$docs = KT_SugarProvider::getLinkedDocuments($lid);
		if (isDocumentsError($docs)) {
			return KT_SugarProvider::formatError($docs);
		} else {
			$beans = array();
			foreach ($docs as $doc) {
				$bean = new ZuckerDocument();
				$bean->id = $doc->id;
				$bean->fromDocument($doc);
				$beans[] = $bean;
			}
			return $beans;
		}
	}
	function find_linking_documents($id = -1) {
		global $dmsLog;
		$dmsLog->fatal("#$# Test Msg Inside retrieve of ZD");

		$lid = $this->id;
		if ($id >= 0) {
			$lid = $id;
		}

		$docs = KT_SugarProvider::getLinkingDocuments($lid);
		if (isDocumentsError($docs)) {
			return KT_SugarProvider::formatError($docs);
		} else {
			$beans = array();
			foreach ($docs as $doc) {
				$bean = new ZuckerDocument();
				$bean->id = $doc->id;
				$bean->fromDocument($doc);
				$beans[] = $bean;
			}
			return $beans;
		}
	}	
	
	function fill_in_additional_list_fields() {
		global $dmsLog;
		$dmsLog->fatal("#$# Test Msg Inside retrieve of ZD");

		$this->fill_in_additional_detail_fields();
	}

	function fill_in_additional_detail_fields() {
		global $dmsLog;
		$dmsLog->fatal("#$# Test Msg Inside retrieve of ZD");

		global $dmsBase, $beanList, $beanFiles, $mod_strings, $app_list_strings, $current_language;
		
		$this->cat_description = $app_list_strings['doc_category'][$this->cat_name];

		$this->status = ($this->is_checked_out ? "c/o (".$this->checkedout_username.")" : "c/i");
		$this->url = $dmsBase."/control.php?action=viewDocument&fDocumentID=".$this->id;

		
		
		if ($this->parent_type == 'Folders') {
			$this->parent_link = "index.php?module=ZuckerDocs&action=FoldersView&record=".($this->parent_id);
			$this->parent_action = "FoldersView";
		} else {
			$this->parent_link = "index.php?module=".($this->parent_type)."&action=DetailView&record=".($this->parent_id);
			$this->parent_action = "DocView";
		}
	}
	
	function save($check_notify = FALSE) {
		global $dmsBase;
		
		if ($this->id) {
			$doc = new KT_Document($this->id);
			$doc->name = $this->name;
			$doc->description = $this->description;
			$doc->parentType = $this->parent_type;
			$doc->parentId = $this->parent_id;
			$doc->category = $this->cat_name;

			$res = KT_SugarProvider::updateDocument($doc);
			if (isDocumentsError($res)) {
				$this->errorMessage = KT_SugarProvider::formatError($res);
			}
		} else {
			$filename = $_REQUEST["filename"];
			$contents = unhtmlentities($_REQUEST["contents"]);
			if (is_uploaded_file($_FILES['uploadfile']['tmp_name'])) {
				$filename = $_FILES['uploadfile']['name'];
				$contents = file_get_contents($_FILES['uploadfile']['tmp_name']);
			}
			//if (!empty($filename) && !empty($contents)) {
			//ft 22.06.06: should be possible to create empty documents
			if (!empty($filename)) {
				if ($this->parent_type == 'Folders') {
					$res = KT_SugarProvider::addDocumentToFolder($contents, $filename, $this->parent_id, $this->cat_name, $this->description);
				} else {
					$res = KT_SugarProvider::addDocument($contents, $filename, $this->parent_type, $this->parent_id, $this->cat_name, $this->description);
				}
				
				if (isDocumentsError($res)) {
					$this->errorMessage = KT_SugarProvider::formatError($res);
				} else {
					$this->id = $res;
				}
			}
		}
		if ($this->id) {
			$this->retrieve($this->id);
		}
	}
	
	function newDocument($parentType, $parentId) {
		$doc = new KT_Document('-1');
		$doc->parentType = $parentType;
		$doc->parentId = $parentId;
		$doc = KT_SugarProvider::__checkParent($doc);
		$this->fromDocument($doc);
	}
	
	function handleSave() {
		global $current_language;
		
		if (isset($_REQUEST['parent_id']) && $_REQUEST['parent_id'] != '') {
			$this->parent_id = $_REQUEST['parent_id'];
		}
		if (isset($_REQUEST['parent_module']) && $_REQUEST['parent_module'] != '') {
			$this->parent_type = $_REQUEST['parent_module'];
		}
		if (isset($_REQUEST['name']) && $_REQUEST['name'] != '') {
			$this->name = $_REQUEST['name'];
		}
		if (isset($_REQUEST['description']) && $_REQUEST['description'] != '') {
			$this->description = $_REQUEST['description'];
		}
		if (isset($_REQUEST['cat_name']) && $_REQUEST['cat_name'] != '') {
			$this->cat_name = $_REQUEST['cat_name'];
		}
		
		if (empty($this->cat_name)) {
			$current_module_strings = return_module_language($current_language, 'ZuckerDocs');
			$this->cat_name = $current_module_strings['DOC_CATEGORY_DEFAULT'];
		}
		$this->save();
	}


	function get_summary_text() {
		return $this->name;
	}


	function get_root_line_links($folderId, $action = "FoldersView") {
		global $dmsLog;
		$dmsLog->fatal("#$# Test Msg Inside retrieve of ZD");

		$root_line = ZuckerDocument::get_root_line($folderId);
		$links = array();
		foreach ($root_line as $obj) {
			$links[] = '<a href="index.php?module=ZuckerDocs&action='.$action.'&record='.($obj->id).'">'.$obj->name.'</a>';
		}
		return join("->", $links);
	}
	

	function get_root_line($folderId) {
		global $dmsLog;
		$dmsLog->fatal("#$# Test Msg Inside retrieve of ZD");

		global $mod_strings;
	
		$result = array();
		
		$rootFolder = KT_SugarProvider::getRootFolder();
		
		$obj = KT_SugarProvider::getFolderDetails($folderId);
		$result[] = $obj;
		while (true) {
			if (empty($obj->id) || $obj->id == $rootFolder->id) {
				break;
			}
			$obj = KT_SugarProvider::getFolderDetails($obj->parent_id);
			$result[] = $obj;
		}
		return array_reverse($result);
	}
}
?>