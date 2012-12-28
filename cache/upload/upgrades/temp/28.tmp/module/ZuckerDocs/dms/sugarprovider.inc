<?
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

require_once('conf.inc');
require_once('auth.inc');
require_once('docprovider.inc');
require_once('folderprovider.inc');
require_once('db.inc');

define('SUGAR_PARENTTYPE', 'sugar_parent_type');
define('SUGAR_PARENTID', 'sugar_parent_id');
define('SUGAR_PARENTNAME', 'sugar_parent_name');
define('SUGAR_PARENTLINK', 'sugar_parent_link');
define('SUGAR_CAT', 'sugar_cat');
define('SUGAR_DOCTYPE', 'ZuckerDoc');

define('FOLDER_MYDOCUMENTS', 'MyDocuments');
define('FOLDER_MYDOCUMENTS_ID', '__mydocuments');

class KT_SugarProvider {

	function formatError($err) {
		global $current_language, $dmsLog;

		$dmsLog->debug("#$# KT_SugarProvider::formatError(".$err.")");

		$mod_strings = return_module_language($current_language, 'ZuckerDocs');
		
		$key = 'LBL_ERRORMESSAGE_'.$err->errorCode;
		$value = $mod_strings[$key];
		if ($value == '') {
			$value = '%s %s %s %s %s';
			$result = sprintf($value, $err->errorCode, $err->arg1, $err->arg2, $err->arg3, $err->arg4);
		} else {
			$result = sprintf($value, $err->arg1, $err->arg2, $err->arg3, $err->arg4);
		}
		return $result;
	}

	function addDocument($contents, $filename, $parentType, $parentId, $catName = '', $description = '') {
		global $current_user, $dmsLog;

		$dmsLog->debug("#$# KT_SugarProvider::addDocument(".$contents.", ".$filename.", ".$parentType.", ".$parentId.", ".$catName.", ".$description.")");

		//echo $parentType."-".$parentId;
		
		$parentName = KT_SugarProvider::__getParentName($parentType, $parentId);
		$parentFolderName = KT_SugarProvider::__getParentFolderName($parentType, $parentId);
		
		$rootFolder = KT_FolderProvider::getRootFolder($current_user->user_name, $current_user->user_password);
		if (isDocumentsError($rootFolder)) {
			return $rootFolder;	
		}
		$typeFolder = KT_FolderProvider::createNewFolder($current_user->user_name, $current_user->user_password, $rootFolder, $parentType, 'created by ZuckerDocs');
		if (isDocumentsError($typeFolder)) {
			return $typeFolder;	
		}
		$idFolder = KT_FolderProvider::createNewFolder($current_user->user_name, $current_user->user_password, $typeFolder, $parentFolderName, 'created by ZuckerDocs');
		if (isDocumentsError($idFolder)) {
			return $idFolder;	
		}
		$doc = KT_DocumentProvider::addDocument($current_user->user_name, $current_user->user_password, $contents, $idFolder, SUGAR_DOCTYPE, $filename, array(), array(), $description);
		if (isDocumentsError($doc)) {
			return $doc;
		}
		$doc->parentType = $parentType;
		$doc->parentId = $parentId;
		$doc->category = $catName;
		KT_SugarProvider::__saveMetaData($doc);
		return $doc->id;
	}
	
	function addDocumentToFolder($contents, $filename, $folderId, $catName = '', $description = '') {
		global $current_user, $dmsLog;

		$dmsLog->debug("#$# KT_SugarProvider::addDocumentToFolder(".$contents.", ".$filename.", ".$folderId.", ".$catName.", ".$description.")");

		$parent = KT_SugarProvider::__getParentTypeAndId($folderId);
		if ($parent) {
			return KT_SugarProvider::addDocument($contents, $filename, $parent[0], $parent[1], $catName, $description);
		} else {
			$doc = KT_DocumentProvider::addDocument($current_user->user_name, $current_user->user_password, $contents, new KT_Folder($folderId), SUGAR_DOCTYPE, $filename, array(), array(), $description);
			if (isDocumentsError($doc)) {
				return $doc;
			}
			$doc->parentType = NULL;
			$doc->parentId = NULL;
			$doc->category = $catName;
			KT_SugarProvider::__saveMetaData($doc);
			return $doc->id;
		}
	}	

	function addFolder($folderId, $name, $description = '') {
		global $current_user, $dmsLog;
		$dmsLog->debug("#$# KT_SugarProvider::addFolder(".$folderId.", ".$name.", ".$description.")");
		
		if (empty($description)) {
			$description = 'created by ZuckerDocs';
		}
	
		return KT_FolderProvider::createNewFolder($current_user->user_name, $current_user->user_password, new KT_Folder($folderId), $name, $description);
	}

	function deleteFolder($folderId) {
		global $current_user, $dmsLog;

		$dmsLog->debug("#$# KT_SugarProvider::deleteFolder(".$folderId.")");

		return KT_FolderProvider::deleteFolder($current_user->user_name, $current_user->user_password, new KT_Document($folderId));
	}
	
	function updateDocument($document) {
		global $current_user, $dmsLog;

		$dmsLog->debug("#$# KT_SugarProvider::updateDocument(".$document.")");

		$result = KT_DocumentProvider::updateDocument($current_user->user_name, $current_user->user_password, $document);
		if (isDocumentsError($result)) {
			return $result;
		}
		KT_SugarProvider::__saveMetaData($document);
	}

	function deleteDocument($id) {
		global $current_user, $dmsLog;

		$dmsLog->debug("#$# KT_SugarProvider::deleteDocument(".$id.")");

		return KT_DocumentProvider::deleteDocument($current_user->user_name, $current_user->user_password, new KT_Document($id));
	}
	
	function getDocument($id) {
		global $current_user, $dmsLog;

		$dmsLog->debug("#$# KT_SugarProvider::getDocument(".$id.")");

		$doc = KT_DocumentProvider::getDocumentDetails($current_user->user_name, $current_user->user_password, new KT_Document($id));
		
		$dmsLog->debug("CHECK FOR DOWNLOAD KT_SugarProvider::'.$doc->name.'");

		if (isDocumentsError($doc)) {
			return $doc;
		}
		return KT_SugarProvider::__fillinMetaData($doc);
	}
	
	function getDocumentTransactions($id) {
		global $current_user, $dmsLog;

		$dmsLog->debug("#$# KT_SugarProvider::getDocumentTransactions(".$id.")");

		return KT_DocumentProvider::getDocumentTransactions($current_user->user_name, $current_user->user_password, new KT_Document($id));
	}

	function getRootFolder() {
		global $current_user, $dmsLog;
		
		$dmsLog->debug("KT_SugarProvider::getRootFolder()");
		
		return KT_FolderProvider::getRootFolder($current_user->user_name, $current_user->user_password);
	}
	
	function getFolderDetails($folderId) {
		global $current_user, $dmsLog;
		$dmsLog->debug("KT_SugarProvider::getFolderDetails(".$folderId.")");
		
		if ($folderId == FOLDER_MYDOCUMENTS_ID) {
			$rootFolder = KT_FolderProvider::getRootFolder($current_user->user_name, $current_user->user_password);
			if (isDocumentsError($rootFolder)) {
				return $rootFolder;	
			}
			$container = KT_FolderProvider::createNewFolder($current_user->user_name, $current_user->user_password, $rootFolder, FOLDER_MYDOCUMENTS, 'created by ZuckerDocs');
			if (isDocumentsError($container)) {
				return $container;	
			}
			return KT_FolderProvider::createNewFolder($current_user->user_name, $current_user->user_password, $container, $current_user->user_name, 'created by ZuckerDocs');
		} else {
			return KT_FolderProvider::getFolderDetails($current_user->user_name, $current_user->user_password, new KT_Folder($folderId));
		}
	}
	
	function getSubFolders($folderId, $orderAscByFolderName = TRUE, $recursive = FALSE) {
		global $current_user, $dmsLog;
		$dmsLog->debug("#$# KT_SugarProvider::getSubFolders(".$folderId.", ".$orderAscByFolderName.", ".$recursive.")");

		return KT_FolderProvider::getSubFolders($current_user->user_name, $current_user->user_password, new KT_Folder($folderId), $orderAscByFolderName, $recursive);
	}
	function getSubFolder($folderId, $name) {
		global $current_user, $dmsLog;
		$dmsLog->debug("KT_SugarProvider::getSubFolder(".$folderId.", ".$name.")");

		$result = KT_FolderProvider::createNewFolder($current_user->user_name, $current_user->user_password, new KT_Folder($folderId), $name, 'created by ZuckerDocs');
		return $result;
	}
	
	function getSubDocuments($folderId, $orderAscByFolderName = TRUE) {
		global $current_user, $dmsLog;
		$dmsLog->debug("KT_SugarProvider::getSubDocuments(".$folderId.", ".$orderAscByFolderName.")");

		return KT_SugarProvider::__fillinMetaDataList(KT_DocumentProvider::getDocuments($current_user->user_name, $current_user->user_password, new KT_Folder($folderId), $orderAscByFolderName));
	}

	function getRecentlyChangedDocuments($start = "0", $limit = "20") {
		global $current_user, $dmsLog;

		$dmsLog->debug("#$# KT_SugarProvider::getSubDocuments(".$start.", ".$limit.")");

		return KT_SugarProvider::__fillinMetaDataList(KT_DocumentProvider::getRecentlyChangedDocuments($current_user->user_name, $current_user->user_password, $start, $limit));
	}
	
	function searchDocumentsText($text) {
		global $current_user, $dmsLog;
		$dmsLog->debug("KT_SugarProvider::searchDocumentsText(".$text.")");

		return KT_SugarProvider::__fillinMetaDataList(KT_DocumentProvider::getDocumentsByText($current_user->user_name, $current_user->user_password, $text, DOCSTATUS_LIVE));
	}

	// #$#
	function searchDocuments($parentType = '', $parentId = '', $filename = '', $catName = '', $orderBy = '', $orderByAsc = TRUE, $startIndex = 0, $limit = -1) {
		global $dmsLink, $dmsDbName, $dmsLog;
		global $current_user;
		
		$dmsLog->debug("KT_SugarProvider::searchDocuments(...)");

		$dmsLog->debug("user: ".$current_user->user_name);
		KT_AuthProvider::checkUser($current_user->user_name, $current_user->user_password);

		$filename = str_replace('*', '%', $filename);
		
		$sql = "select d.id from ".$dmsDbName.".documents d, ".$dmsDbName.".document_metadata_version dmv, ".$dmsDbName.". document_content_version dcv ";
		$where = "where d.status_id = ".DOCSTATUS_LIVE." and dmv.id = d.metadata_version_id and dmv.document_id = d.id and dcv.document_id = d.id ";
		
		if ($parentType != '') {
			$sql .= ", ".$dmsDbName.".document_fields_link dfl_t, ".$dmsDbName.".document_fields df_t ";
			$where .= " and dmv.id = dfl_t.metadata_version_id and dfl_t.document_field_id = df_t.id and df_t.name = '".SUGAR_PARENTTYPE."' and dfl_t.value = '".$parentType."' ";
		}
		if ($parentId != '') {
			$sql .= ", ".$dmsDbName.".document_fields_link dfl_i, ".$dmsDbName.".document_fields df_i ";
			$where .= " and dmv.id = dfl_i.metadata_version_id and dfl_i.document_field_id = df_i.id and df_i.name = '".SUGAR_PARENTID."' and dfl_i.value = '".$parentId."' ";
		}
		if ($catName != '') {
			$sql .= ", ".$dmsDbName.".document_fields_link dfl_c, ".$dmsDbName.".document_fields df_c ";
			$where .= " and dmv.id = dfl_c.metadata_version_id and dfl_c.document_field_id = df_c.id and df_c.name = '".SUGAR_CAT."' and dfl_c.value = '".$catName."' ";
		}

		/** #$# MOD-MAY09 filename and name fields are moved to new tables.
		if ($filename != '') {
			$where .= " and d.filename like '%".$filename."%' or d.name like '%".$filename."%'";
		}
		*/
		
		if ($filename != '') {
		// $#$ commented by surya on 16th May and make conditions of OR in bracket
		//	$where .= " and dmv.document_id = d.id and dcv.document_id = d.id and dcv.filename like '%".$filename."%' or dmv.name like '%".$filename."%'";
		$where .= " and (dcv.filename like '%".$filename."%' or dmv.name like '%".$filename."%')";
		}

		if ($orderBy != '') {
			$dir = $orderByAsc ? "asc" : "desc";
			// $#$ commented by surya on 16th May and make orderby from document_metadata_version table
			// $order = " order by d.".$orderBy." ".$dir;
			$order = " order by dmv.".$orderBy." ".$dir;
		}
		$sql .= $where;
		$sql .= $order;
		
		if ($startIndex >= 0 && $limit > 0) {
				$sql .= "limit ".$startIndex.",".$limit;
		}
		
		$docIds = array();

		$rs = dmsQuery($sql);
		while ($row = $dmsLink->fetchByAssoc($rs)) {
			$docIds[] = $row["id"];
		}
		$result = array();
		foreach ($docIds as $docId) {
			$doc = KT_SugarProvider::getDocument($docId);
			if (!isDocumentsError($doc)) {
				$result[] = $doc;
			}
		}
		return $result;
	}
	function getDocumentsForParent($parentType, $parentId, $orderByFilenameAsc = TRUE, $startIndex = 0, $limit = -1) {
		global $dmsLog;
		$dmsLog->debug("#$# KT_SugarProvider::getDocumentsForParent(...)");
		
		return KT_SugarProvider::searchDocuments($parentType, $parentId, NULL, NULL, 'name', $orderByFilenameAsc, $startIndex, $limit);
	
	}
	function getDocumentContents($docId, $version = '') {
		global $current_user, $dmsLog;

		$dmsLog->debug("#$# KT_SugarProvider::getDocumentContents(...)");

		$doc = new KT_Document($docId);
		$contents = KT_DocumentProvider::getDocumentContents($current_user->user_name, $current_user->user_password, $doc, $version);
		if (!isDocumentsError($contents)) {
			$dmsLog->debug("KT_SugarProvider::getDocumentContents found ".strlen($contents)." for ".$docId);
		}
		return $contents;
	}

	function checkoutDocument($docId, $comment = '') {
		global $current_user, $dmsLog;

		$dmsLog->debug("#$# KT_SugarProvider::checkoutDocument(...)");

		$doc = new KT_Document($docId);
		$contents = KT_DocumentProvider::checkOut($current_user->user_name, $current_user->user_password, $doc, $comment);
		return $contents;
	}
	function checkinDocument($docId, $contents, $comment = '', $isMajorRevision = false) {
		global $current_user, $dmsLog;

		$dmsLog->debug("#$# KT_SugarProvider::checkinDocument(...)");

		$doc = new KT_Document($docId);
		return KT_DocumentProvider::checkIn($current_user->user_name, $current_user->user_password, $contents, $doc, $comment, ($isMajorRevision ? CHECKIN_MAJOR : CHECKIN_MINOR));
	}

	function getLinkedDocuments($docId) {
		global $current_user, $dmsLog;

		$dmsLog->debug("#$# KT_SugarProvider::getLinkedDocuments(...)");

		$doc = new KT_Document($docId);
		return KT_SugarProvider::__fillinMetaDataList(KT_DocumentProvider::getLinkedDocuments($current_user->user_name, $current_user->user_password, $doc));
	}

	function getLinkingDocuments($docId) {
		global $current_user, $dmsLog;

		$dmsLog->debug("#$# KT_SugarProvider::getLinkingDocuments(...)");

		$doc = new KT_Document($docId);
		return KT_SugarProvider::__fillinMetaDataList(KT_DocumentProvider::getLinkingDocuments($current_user->user_name, $current_user->user_password, $doc));
	}
		
	function addDocumentLink($docId, $linkedDocId) {
		global $current_user, $dmsLog;

		$dmsLog->debug("#$# KT_SugarProvider::addDocumentLink(...)");

		$doc = new KT_Document($docId);
		$linkedDoc = new KT_Document($linkedDocId);
		return KT_DocumentProvider::addDocumentLink($current_user->user_name, $current_user->user_password, $doc, $linkedDoc);
	}
	
	function deleteDocumentLink($docId, $linkedDocId) {
		global $current_user, $dmsLog;

		$dmsLog->debug("#$# KT_SugarProvider::deleteDocumentLink(...)");

		$doc = new KT_Document($docId);
		$linkedDoc = new KT_Document($linkedDocId);
		return KT_DocumentProvider::deleteDocumentLink($current_user->user_name, $current_user->user_password, $doc, $linkedDoc);
	}
	
	function __checkParent($doc) {
		global $beanList, $beanFiles, $current_language, $mod_strings, $dmsLog;

		$dmsLog->debug("#$# KT_SugarProvider::__checkParent(...)");

		if (!empty($doc->parentType) && !empty($doc->parentId) && $doc->parentType != 'Folders') {
			$type_name = $beanList[$doc->parentType];
			$class_file = $beanFiles[$type_name];
			if (!empty($class_file)) {
				require_once($class_file);
				$seed = new $type_name;
				if ($seed->retrieve($doc->parentId) != null) {
					$doc->parentName = $seed->get_summary_text();
				}
			}
		}
		if (empty($doc->parentName)) {
			$doc->parentType = 'Folders';
			if (!empty($doc->folder_id)) {
				$doc->parentId = $doc->folder_id;
			}
			$folder = KT_SugarProvider::getFolderDetails($doc->parentId);
			$doc->parentName = $mod_strings['LBL_FOLDER'].": ".$folder->name;
		}
		return $doc;
	}

	function __getParentName($parentType, $parentId) {
		global $beanList, $beanFiles, $current_language, $mod_strings, $dmsLog;

		$dmsLog->debug("#$# KT_SugarProvider::__getParentName(...)");

		$result = '';
		
		if (!empty($parentType) && !empty($parentId) && $parentType != 'Folders') {
			$type_name = $beanList[$parentType];
			$class_file = $beanFiles[$type_name];
			if (!empty($class_file)) {
				require_once($class_file);
				$seed = new $type_name;
				if ($seed->retrieve($parentId) != null) {
					$result = $seed->get_summary_text();
				}
			}
		}
		if (empty($result)) {
			$folder = KT_SugarProvider::getFolderDetails($parentId);
			$result = $mod_strings['LBL_FOLDER'].": ".$folder->name;
		}
		return $result;
	}

	function __getParentFolderName($parentType, $parentId) {
		global $dmsLog;

		$dmsLog->debug("#$# KT_SugarProvider::__getParentFolderName(...)");
		
		$result = '';

		$parentName = KT_SugarProvider::__getParentName($parentType, $parentId);
		return $parentName."_".$parentId;
	}

	function __getParentTypeAndId($folderId) {
		global $moduleList, $dmsLog;
	
		$dmsLog->debug("#$# KT_SugarProvider::__getParentTypeAndId(...)");

		$folder = KT_SugarProvider::getFolderDetails($folderId);
		$parentFolder = KT_SugarProvider::getFolderDetails($folder->parent_id);
		
		if (in_array($parentFolder->name, $moduleList)) {
			$parentType = $parentFolder->name;
		
			$parts = split("_", $folder->name);
			$parentId = $parts[count($parts) - 1];

			return array($parentType, $parentId);
		} else {
			return NULL;
		}
	}
	
	function __fillinMetaDataList($docs) {
		global $dmsLog;

		$dmsLog->debug("#$# KT_SugarProvider::__fillinMetaDataList(...)");

		$result = array();
		foreach ($docs as $doc) {
			KT_SugarProvider::__fillinMetaData(&$doc);
			$result[] = $doc;
		}
		return $result;
	}
	
	function __fillinMetaData($doc) {
		global $dmsLog;

		$dmsLog->debug("#$# KT_SugarProvider::__fillinMetaData(...)");

		$doc->parentType = KT_DocumentProvider::__getMetaDataField($doc->id, SUGAR_PARENTTYPE);
		$doc->parentId = KT_DocumentProvider::__getMetaDataField($doc->id, SUGAR_PARENTID);
		$doc->category = KT_DocumentProvider::__getMetaDataField($doc->id, SUGAR_CAT);
		$doc = KT_SugarProvider::__checkParent($doc);
		return $doc;
	}
	function __saveMetaData($doc) {
		global $sugar_url_base, $dmsLog;

		$dmsLog->debug("#$# KT_SugarProvider::__fillinMetaData(...)");

		$doc = KT_SugarProvider::__checkParent($doc);

		// #$# MOD-MAY24 We need metadata_version_id instead document_id was passed previously.
		$mdvID = getDMDVID($doc->id);
		
		if (!empty($doc->parentType) && !empty($doc->parentId) && $doc->parentType != 'Folders') {
			$link = $sugar_url_base."/index.php?module=".$doc->parentType."&action=DocView&record=".$doc->parentId;
			KT_DocumentProvider::__setMetaDataField($mdvID, SUGAR_PARENTTYPE, $doc->parentType);
			KT_DocumentProvider::__setMetaDataField($mdvID, SUGAR_PARENTID, $doc->parentId);
			KT_DocumentProvider::__setMetaDataField($mdvID, SUGAR_PARENTNAME, $doc->parentName);
			KT_DocumentProvider::__setMetaDataField($mdvID, SUGAR_PARENTLINK, '<a href="'.$link.'" target="_sugar">Open in ZuckerDocs</a>');
		} else {
			KT_DocumentProvider::__setMetaDataField($mdvID, SUGAR_PARENTTYPE, '');
			KT_DocumentProvider::__setMetaDataField($mdvID, SUGAR_PARENTID, '');
			KT_DocumentProvider::__setMetaDataField($mdvID, SUGAR_PARENTNAME, '');
			KT_DocumentProvider::__setMetaDataField($mdvID, SUGAR_PARENTLINK, '');
		}
		KT_DocumentProvider::__setMetaDataField($mdvID, SUGAR_CAT, $doc->category);
	}
}

?>