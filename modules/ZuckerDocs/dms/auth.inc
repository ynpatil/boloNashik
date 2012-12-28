<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/***
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

require_once('db.inc');

class KT_AuthProvider {

	function checkUser($username, $password = '') {
		global $dmsLink, $dmsLog;
		global $dmsGroup, $dmsCreateUsers, $dmsDefaultPassword;
	
		$sql = getUserIdSql($username);
		$rs = dmsQuery($sql);
		if ($dmsLink->getRowCount($rs) > 0) {
			$dmsLog->debug("user ".$username." valid");
			return TRUE;
		} else if ($dmsCreateUsers) {
			$sql = getGroupByNameSql($dmsGroup);
			$rs = dmsQuery($sql);
			$groupRow = $dmsLink->fetchByAssoc($rs);
				
			$userId = getNewId('users');
			$ugId = getNewId('users_groups_link');
				
			$sql = getInsertUserSql($userId, $username, $username, md5($dmsDefaultPassword), '');
			dmsQuery($sql);
				
			$sql = getInsertUserGroupSql($ugId, $userId, $groupRow["id"]);
			dmsQuery($sql);
	
			$dmsLog->debug("user ".$username." created");
			return TRUE;
		}
		die("dms auth failed");
	}

	function __getUserId($username) {
		global $dmsLink, $dmsLog;

 		$sql = getUserIdSql($username);
		$rs = dmsQuery($sql);
		$row = $dmsLink->fetchByAssoc($rs);
		return $row["id"];
	}
	function __getUserName($id) {
		global $dmsLink, $dmsLog;

		$sql = getUserSql($id);
		$rs = dmsQuery($sql);
		$row = $dmsLink->fetchByAssoc($rs);
		return $row["username"];
			
	}
	
	function __userHasFolderWritePermission($userId, $folderId) {
		global $dmsLink, $dmsLog;

		$result = FALSE;
		
		$sql = getUserHasGroupWritePermissionForFolderSql($userId, $folderId);
		$rs = dmsQuery($sql);
		if ($dmsLink->getRowCount($rs) > 0) {
			$result = TRUE;
		}
		if (!$result) {
			$sql = getUserIsSystemAdministratorSql($userId);
			$rs = dmsQuery($sql);
			if ($dmsLink->getRowCount($rs) > 0) {
				$result = TRUE;
			}
		}
		if (!$result) {
			$sql = getUserIsUnitAdminForFolderSql($userId, $folderId);
			$rs = dmsQuery($sql);
			if ($dmsLink->getRowCount($rs) > 0) {
				$result = TRUE;
			}
		}
		$dmsLog->debug("KT_AuthProvider::__userHasFolderWritePermission(".$userId.", ".$folderId.") => ".$result);
		return $result;
	}		

	function __userHasFolderReadPermission($userId, $folderId) {
		global $dmsLink, $dmsLog;

		$result = FALSE;

		$sql = getUserHasGroupReadPermissionForFolderSql($userId, $folderId);
		$rs = dmsQuery($sql);
		if ($dmsLink->getRowCount($rs) > 0) {
			$result = TRUE;
		}
		if (!$result) {
			$sql = getUserIsSystemAdministratorSql($userId);
			$rs = dmsQuery($sql);
			if ($dmsLink->getRowCount($rs) > 0) {
				$result = TRUE;
			}
		}
		if (!$result) {
			$sql = getUserIsUnitAdminForFolderSql($userId, $folderId);
			$rs = dmsQuery($sql);
			if ($dmsLink->getRowCount($rs) > 0) {
				$result = TRUE;
			}
		}
		$dmsLog->debug("KT_AuthProvider::__userHasFolderReadPermission(".$userId.", ".$folderId.") => ".$result);
		return $result;
	}	
}
?>