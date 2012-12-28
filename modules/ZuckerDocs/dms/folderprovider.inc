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

require_once('document.inc');
require_once('db.inc');
require_once('fs.inc');
require_once('auth.inc');

class KT_FolderProvider {

	function getFolderDetails($username, $password, $folder) {
		global $dmsLink, $dmsLog;

		KT_AuthProvider::checkUser($username, $password);

		$userId = KT_AuthProvider::__getUserId($username);

		$sql = getFolderDetailsSql($folder->id);
		$rs = dmsQuery($sql);
		if ($row = $dmsLink->fetchByAssoc($rs)) {
			$folder->name = $row["name"];
			$folder->userhaswriteaccess = KT_AuthProvider::__userHasFolderWritePermission($userId, $folder->id);
			$folder->description = $row["description"];
			$folder->parent_id = $row["parent_id"];
			$folder->creator = $row["creator"];
			$folder->full_path = $row["full_path"];
			$folder->unit_id = $row["unit_id"];
			return $folder;
		} else {
			return new KT_DocumentsError(DOCERROR_FOLDERNOTFOUND, $folder->id);
		}
	}	
	
	function getSubFolderByName($username, $password, $parentFolder, $name) {
		global $dmsLink, $dmsLog;

		KT_AuthProvider::checkUser($username, $password);

		$userId = KT_AuthProvider::__getUserId($username);
		if (KT_AuthProvider::__userHasFolderReadPermission($userId, $parentFolder->id)) {
			$sql = getSubFolderByNameSql($parentFolder->id, $name);
			$rs = dmsQuery($sql);
			if ($row = $dmsLink->fetchByAssoc($rs)) {
				return KT_FolderProvider::getFolderDetails($username, $password, new KT_Folder($row["id"]));
			}
		} else {
			return new KT_DocumentsError(DOCERROR_DMSAUTH, $username);			
		}
	}
	
	function getWritableSubFolders($username, $password, $parentFolder, $orderAscByFolderName) {
		global $dmsLink, $dmsLog;

		KT_AuthProvider::checkUser($username, $password);

		$folderList = KT_FolderProvider::getSubFolders($username, $password, $parentFolder, $orderAscByFolderName);
		if (!isDocumentsError($folderList)) {
			$result = array();
			foreach ($folderList as $folder) {
				if ($folder->userhaswriteaccess) {
					$result[] = $folder;	
				}
			}
			return $result;
		} else {
			return $folderList;	
		}
	}

	function getSubFolders($username, $password, $parentFolder, $orderAscByFolderName, $recursive = FALSE) {
		global $dmsLink, $dmsLog;

		KT_AuthProvider::checkUser($username, $password);

		$userId = KT_AuthProvider::__getUserId($username);
		if (KT_AuthProvider::__userHasFolderReadPermission($userId, $parentFolder->id)) {
			$parentFolder = KT_FolderProvider::getFolderDetails($username, $password, $parentFolder);
		
			$folderList = array();
			if ($recursive) {
				$sql = getSubFoldersSqlRecursive($parentFolder->full_path."/".$parentFolder->name, $orderAscByFolderName);
			} else {
				$sql = getSubFoldersSql($parentFolder->id, $orderAscByFolderName);
			}
			$rs = dmsQuery($sql);
			while ($row = $dmsLink->fetchByAssoc($rs)) {
				$folderList[] = new KT_Folder($row["id"]);	
			}
			$result = array();
			foreach ($folderList as $folder) {
				$result[] = KT_FolderProvider::getFolderDetails($username, $password, $folder);
			}
			return $result;
		} else {
			return new KT_DocumentsError(DOCERROR_DMSAUTH, $username);			
		}
	}
	
	function getRootFolder($username, $password) {
		global $dmsLink, $dmsLog;

		KT_AuthProvider::checkUser($username, $password);
			
		$userId = KT_AuthProvider::__getUserId($username);

		$sql = getUserRootFolderSql($userId);
		$rs = dmsQuery($sql);
		$row = $dmsLink->fetchByAssoc($rs);
		if ($dmsLink->getRowCount($rs) > 0) {
			return KT_FolderProvider::getFolderDetails($username, $password, new KT_Folder($row["id"]));
		} else {
			$sql = getRootFolderGeneralSql($userId);
			$rs = dmsQuery($sql);
			$row = $dmsLink->fetchByAssoc($rs);
			if ($row) {
				return KT_FolderProvider::getFolderDetails($username, $password, new KT_Folder($row["id"]));
			}
		}
		return new KT_DocumentsError(DOCERROR_FOLDERNOTFOUND, 'root');			
	}

	function createNewFolder($username, $password, $parentFolder, $name, $description) {
		global $dmsLink, $dmsLog;

		KT_AuthProvider::checkUser($username, $password);

		$userId = KT_AuthProvider::__getUserId($username);
		if (KT_AuthProvider::__userHasFolderWritePermission($userId, $parentFolder->id)) {
			$folder = KT_FolderProvider::getSubFolderByName($username, $password, $parentFolder, $name);
			if (!$folder) {
				$sql = getFolderDetailsSql($parentFolder->id);
				$rs = dmsQuery($sql);
				$parentFolderRow = $dmsLink->fetchByAssoc($rs);
	
				$fId = getNewId("folders");
				$sql = getInsertFolderSql($fId, $name, $description, $parentFolderRow, $userId);
				$rs = dmsQuery($sql);
	
				/* ft, 22.06.06: seems as this is not needed anymore
				$dts = KT_FolderProvider::__getDocumentTypes($parentFolder);
				foreach ($dts as $dt) {
					$newId = getNewId("folder_doctypes_link");
					$sql = getInsertDocumentTypeLinkSql($newId, $fId, $dt->id);
					dmsQuery($sql);
				}
				*/
				$folder = KT_FolderProvider::getFolderDetails($username, $password, new KT_Folder($fId));
			}
	
			
			$full_path = KT_FileProvider::getFullPath($folder);
			if (!is_dir($full_path)) {
				mkdir($full_path, 0755);
			}
			return $folder;
		} else {
			return new KT_DocumentsError(DOCERROR_DMSAUTH, $username);			
		}
	}

	function deleteFolder($username, $password, $folder) {
		global $dmsLink, $dmsLog;

		KT_AuthProvider::checkUser($username, $password);

		$userId = KT_AuthProvider::__getUserId($username);

		$folder = KT_FolderProvider::getFolderDetails($username, $password, $folder);
		if (isDocumentsError($folder)) {
			return $folder;
		}
		$rootFolder = KT_FolderProvider::getRootFolder($username, $password);
		if (isDocumentsError($rootFolder)) {
			return $rootFolder;
		}
		$parentFolder = KT_FolderProvider::getFolderDetails($username, $password, new KT_Folder($folder->parent_id));
		if (isDocumentsError($parentFolder)) {
			return $parentFolder;
		}

		$id = $folder->id;
		$rootId = $rootFolder->id;
		
		if ($rootId == $id) {
			return new KT_DocumentsError(DOCERROR_DMSAUTH, $username);	
			
		} else if (KT_AuthProvider::__userHasFolderWritePermission($userId, $folder->id) &&
					KT_AuthProvider::__userHasFolderWritePermission($userId, $parentFolder->id)) {

			$sql = getSubFoldersSql($folder->id, FALSE);
			$rs = dmsQuery($sql);
			if ($dmsLink->fetchByAssoc($rs)) {
				return new KT_DocumentsError(DOCERROR_FOLDERNOTEMPTY, $folder->full_path."/".$folder->name);
			}
			$sql = getAllDocumentsInFolderSql($folder->id, FALSE);
			$rs = dmsQuery($sql);
			if ($dmsLink->fetchByAssoc($rs)) {
				return new KT_DocumentsError(DOCERROR_FOLDERNOTEMPTY, $folder->full_path."/".$folder->name);	
			}
			$sql = getDeleteFolderSql($folder->id);
			dmsQuery($sql);
					
			$full_path = KT_FileProvider::getFullPath($folder);
			if (is_dir($full_path)) {
				rmdir($full_path);
			}
		} else {
			return new KT_DocumentsError(DOCERROR_DMSAUTH, $username);	
		}
	}
	
	function __getDocumentTypes($folder) {
		global $dmsLink, $dmsLog;

		$docTypes = array();

		$sql = getFolderDocumentTypesSql($folder->id);
		$rs = dmsQuery($sql);
		while ($row = $dmsLink->fetchByAssoc($rs)) {
			$docTypes[] = new KT_DocumentType($row["id"], $row["name"]);
		}
		return $docTypes;
	}
	function __getDocumentType($name) {
		global $dmsLink, $dmsLog;

		$sql = getDocumentTypeSql($name);
		$rs = dmsQuery($sql);
		if ($row = $dmsLink->fetchByAssoc($rs)) {
			return new KT_DocumentType($row["id"], $row["name"]);
		}
	}

}
		
		
?>