<?
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

require_once('conf.inc');
require_once('include/database/PearDatabase.php');

global $dmsLink;
global $dmsLog;

if ($dmsUseSugarMysqlServer) {
	$dmsLink = PearDatabase::getInstance();
} else {
	$dmsLink = new PearDatabase();
	$dmsLink->log = LoggerManager::getLogger('dms-db');
	$dmsLink->disconnect();
	$dmsLink->setDatabaseType($dmsDbType);
	$dmsLink->setUserName($dmsDbUser);
	$dmsLink->setUserPassword($dmsDbPwd);
	$dmsLink->setDatabaseHost($dmsDbHost);
	$dmsLink->setDatabaseName($dmsDbName);
	$dmsLink->dbOptions = $dmsDbOptions;
	$dmsLink->connect(true);
}

require_once('include/logging.php');
$dmsLog = LoggerManager::getLogger('dms');
if ($dmsQuietMode) {
	//$dmsLog->removeAllAppenders();
}

function dmsQuery($sql) {
	global $dmsLink, $dmsDbName, $dmsLog;
	
	$dmsLog->debug($sql);
	
	//echo $sql."<br/>";
	
	$rs = $dmsLink->query($sql, true, "ZuckerDocs: error with query");
	
	return $rs;	
}

function getNewId($table) {
	global $dmsLink, $dmsDbName;
	
	global $dmsLog;
	$dmsLog->debug("#$# db.inc::getNewId(...)");

	$sql = "select id from ".$dmsDbName.".zseq_".$table;
	$rs = dmsQuery($sql);
	if ($row = $dmsLink->fetchByAssoc($rs)) {
		$id = $row["id"] + 1;
		$sql = "update ".$dmsDbName.".zseq_".$table." set id = id + 1";
		dmsQuery($sql);
	} else {
		$id = 1;
		$sql = "insert into ".$dmsDbName.".zseq_".$table." values (1)";
		dmsQuery($sql);
	}
	return $id;
}

function getUserIdSql($username) {
	global $dmsLink, $dmsDbName, $dmsLog;

	$dmsLog->debug("#$# db.inc::getUserIdSql(...)");

	$sql = "select * from ".$dmsDbName.".users where username = '".$dmsLink->quote($username)."'";
	return $sql;
}
function getAuthUserIdSql($username, $password) {
	global $dmsLink, $dmsDbName;

	global $dmsLog;
	$dmsLog->debug("#$# db.inc::getAuthUserIdSql(...)");

	$sql = "select * from ".$dmsDbName.".users where username = '".$dmsLink->quote($username)."' and password = '".$dmsLink->quote($password)."'";
	return $sql;
}
function getUserSql($id) {
	global $dmsLink, $dmsDbName;

	global $dmsLog;
	$dmsLog->debug("#$# db.inc::getUserSql(...)");

	$sql = "select * from ".$dmsDbName.".users where id = '".$dmsLink->quote($id)."'";
	return $sql;
}
function getInsertUserSql($id, $username, $name, $password, $email) {
	global $dmsLink, $dmsDbName;
	
	global $dmsLog;
	$dmsLog->debug("#$# db.inc::getInsertUserSql(...)");

	$sql = "insert into ".$dmsDbName.".users(id, username, name, password, email, max_sessions) values(".
		"'".$id."', ".
		"'".$dmsLink->quote($username)."', ".
		"'".$dmsLink->quote($name)."', ".
		"'".$dmsLink->quote($password)."', ".
		"'".$dmsLink->quote($email)."', ".
		"1)";
	return $sql;
}

function getInsertUserGroupSql($id, $userId, $groupId) {
	global $dmsLink, $dmsDbName;
	
	global $dmsLog;
	$dmsLog->debug("#$# db.inc::getInsertUserGroupSql(...)");

	$sql = "insert into ".$dmsDbName.".users_groups_link(id, user_Id, group_id) values(".
		"'".$id."', ".
		"'".$userId."', ".
		"'".$groupId."')";
	return $sql;
}
function getGroupByNameSql($name) {
	global $dmsLink, $dmsDbName;

	global $dmsLog;
	$dmsLog->debug("#$# db.inc::getGroupByNameSql(...)");

	$sql = "select * from ".$dmsDbName.".groups_lookup where name = '".$dmsLink->quote($name)."'";
	return $sql;
}

function getRootFolderSql() {
	global $dmsLink, $dmsDbName;

	global $dmsLog;
	$dmsLog->debug("#$# db.inc::getRootFolderSql(...)");

	$sql = "select * from ".$dmsDbName.".folders where parent_id = 0";
	return $sql;
}
function getFolderDetailsSql($folderId) {
	global $dmsLink, $dmsDbName;

	global $dmsLog;
	$dmsLog->debug("#$# db.inc::getFolderDetailsSql(...)");

	$sql = "select f.*, c.username as creator from ".$dmsDbName.".folders f, ".$dmsDbName.".users c where f.creator_id = c.id and f.id = '".$dmsLink->quote($folderId)."'";
	return $sql;
}
function getFolderByPathSql($fullPath, $name) {
	global $dmsLink, $dmsDbName;

	global $dmsLog;
	$dmsLog->debug("#$# db.inc::getFolderByPathSql(...)");

	$sql = "select id from ".$dmsDbName.".folders where name = '".$dmsLink->quote($name)."' and full_path = '".$dmsLink->quote($fullPath)."'";
	return $sql;
}

// #$# Removed the query segement referring to 'groups_units_link' table - Relationship lost.
function getUserRootFolderSql($userId) {
	global $dmsLink, $dmsDbName;

	global $dmsLog;
	$dmsLog->debug("#$# db.inc::getUserRootFolderSql(...)");

//	$dmsLog->test24("trial");
//	die();

	/** #$# MOD-MAY08
	$sql = "select f.id from ".$dmsDbName.".folders f, ".$dmsDbName.".users u, ".$dmsDbName.".users_groups_link ugl, ".$dmsDbName.".groups_units_link gul, ".$dmsDbName.".units_lookup ul where ".
		"u.id = ugl.user_id and ugl.group_id = gul.group_id and gul.unit_id = ul.id and ".
		"ul.name = f.name and f.parent_id = 1 and u.id = '".$dmsLink->quote($userId)."'";
	*/	

	/** #$# MOD-MAY12 Modified the query to relate to root folder of the user correctly. relationship still doubtful
	$sql = "select f.id from ".$dmsDbName.".folders f, ".$dmsDbName.".users u, ".$dmsDbName.".users_groups_link ugl, ".$dmsDbName.".units_lookup ul where ".
		"u.id = ugl.user_id and ul.name = f.name and f.parent_id = 1 and u.id = '".$dmsLink->quote($userId)."'";
	*/

	$sql = "select f.id from ".$dmsDbName.".folders f, ".$dmsDbName.".users u, ".$dmsDbName.".users_groups_link ugl, ".$dmsDbName.".units_lookup ul where ".
		"u.id = ugl.user_id and ugl.group_id = ul.id and ul.name = f.name and f.parent_id = 1 and u.id = '".$dmsLink->quote($userId)."'";

	return $sql;
}


function getRootFolderGeneralSql() {
	global $dmsLink, $dmsDbName;

	global $dmsLog;
	$dmsLog->debug("#$# db.inc::getRootFolderGeneralSql(...)");

	$sql = "select id from ".$dmsDbName.".folders where parent_id = 1";
	return $sql;
}

// #$# Removed the query segement referring to 'groups_folders_link' table - Relationship lost.
function getUserHasGroupWritePermissionForFolderSql($userId, $folderId) {
	global $dmsLink, $dmsDbName;

	global $dmsLog;
	$dmsLog->debug("#$# db.inc::getUserHasGroupWritePermissionForFolderSql(...)");

	/** #$# MOD-MAY08	
	$sql = "select ugl.* from ".$dmsDbName.".groups_folders_link gfl, ".$dmsDbName.".users_groups_link ugl where ".
		"gfl.group_id = ugl.group_id and ".
		"gfl.can_write = 1 and gfl.folder_id = '".$dmsLink->quote($folderId)."' and ugl.user_id = '".$dmsLink->quote($userId)."'";
	*/

	$sql = "select ugl.* from ".$dmsDbName.".users_groups_link ugl where ".
		"ugl.user_id = '".$dmsLink->quote($userId)."'";

	return $sql;
}

// #$# Removed the query segement referring to 'groups_folders_link' table - Relationship lost.
function getUserHasGroupReadPermissionForFolderSql($userId, $folderId) {
	global $dmsLink, $dmsDbName;

	global $dmsLog;
	$dmsLog->debug("#$# db.inc::getUserHasGroupReadPermissionForFolderSql(...)");

	/** #$# MOD-MAY08
	$sql = "select ugl.* from ".$dmsDbName.".groups_folders_link gfl, ".$dmsDbName.".users_groups_link ugl where ".
		"gfl.group_id = ugl.group_id and ".
		"gfl.can_read = 1 and gfl.folder_id = '".$dmsLink->quote($folderId)."' and ugl.user_id = '".$dmsLink->quote($userId)."'";
	*/

	$sql = "select ugl.* from ".$dmsDbName.".users_groups_link ugl where ".
		"ugl.user_id = '".$dmsLink->quote($userId)."'";

	return $sql;
}

function getUserIsSystemAdministratorSql($userId) {
	global $dmsLink, $dmsDbName;

	global $dmsLog;
	$dmsLog->debug("#$# db.inc::getUserIsSystemAdministratorSql(...)");

	$sql = "select gl.* from ".$dmsDbName.".users_groups_link ugl, ".$dmsDbName.".groups_lookup gl where ".
		"gl.id = ugl.group_id and ".
		"gl.is_sys_admin = 1 and ugl.user_id = '".$dmsLink->quote($userId)."'";
	return $sql;
}

function getUserIsUnitAdminForFolderSql($userId, $folderId) {
	global $dmsLink, $dmsDbName;

	global $dmsLog;
	$dmsLog->debug("#$# db.inc::getUserIsUnitAdminForFolderSql(...)");

	$sql = "select f.* from ".$dmsDbName.".folders f, ".$dmsDbName.".groups_units_link gul, ".$dmsDbName.".groups_lookup gl, ".$dmsDbName.".users_groups_link ugl where ".
		"f.unit_id = gul.unit_id and gul.group_id = gl.id and gl.id = ugl.group_id and ".
		"gl.is_unit_admin = 1 and ugl.user_id = '".$dmsLink->quote($userId)."' and f.id = '".$dmsLink->quote($folderId)."'";
	return $sql;
}

function getSubFoldersSql($folderId, $orderAscByFolderName) {
	global $dmsLink, $dmsDbName;

	global $dmsLog;
	$dmsLog->debug("#$# db.inc::getSubFoldersSql(...)");

	$sql = "select id from ".$dmsDbName.".folders where parent_id = '".$dmsLink->quote($folderId)."'";
	if ($orderAscByFolderName) {
		$sql .= " order by name asc";	
	}
	return $sql;
}		
function getSubFoldersSqlRecursive($path, $orderAscByFolderName) {
	global $dmsLink, $dmsDbName;

	global $dmsLog;
	$dmsLog->debug("#$# db.inc::getSubFoldersSqlRecursive(...)");

	$sql = "select id, concat(full_path, name) as sortcol from ".$dmsDbName.".folders where full_path like '".$dmsLink->quote($path)."%'";
	if ($orderAscByFolderName) {
		$sql .= " order by sortcol asc";
	}
	return $sql;
}

function getSubFolderByNameSql($folderId, $name) {
	global $dmsLink, $dmsDbName;

	global $dmsLog;
	$dmsLog->debug("#$# db.inc::getSubFolderByNameSql(...)");

	$sql = "select id from ".$dmsDbName.".folders where parent_id = '".$dmsLink->quote($folderId)."' and name = '".$dmsLink->quote($name)."'";
	return $sql;
}

function getDeleteFolderSql($folderId) {
	global $dmsLink, $dmsDbName;

	global $dmsLog;
	$dmsLog->debug("#$# db.inc::getDeleteFolderSql(...)");

	$sql = "delete from ".$dmsDbName.".folders where id = '".$dmsLink->quote($folderId)."'";
	return $sql;
}


function getFolderDocumentTypesSql($folderId) {
	global $dmsLink, $dmsDbName;

	global $dmsLog;
	$dmsLog->debug("#$# db.inc::getFolderDocumentTypesSql(...)");

	$sql = "select dtl.* from ".$dmsDbName.".document_types_lookup dtl, ".$dmsDbName.".folder_doctypes_link fdl where ".
		"dtl.id = fdl.document_type_id and fdl.folder_id = '".$dmsLink->quote($folderId)."'";
	return $sql;
}
function getDocumentTypeSql($name) {
	global $dmsLink, $dmsDbName;

	global $dmsLog;
	$dmsLog->debug("#$# db.inc::getDocumentTypeSql(...)");

	$sql = "select * from ".$dmsDbName.".document_types_lookup where name = '".$dmsLink->quote($name)."'";
	return $sql;
}
function getMimeTypeSql($name) { 
	global $dmsLink, $dmsDbName;

	global $dmsLog;
	$dmsLog->debug("#$# db.inc::getMimeTypeSql(...)");

	$sql = "select * from ".$dmsDbName.".mime_types where filetypes = '".$dmsLink->quote($name)."'";
	return $sql;
}
function getMimeTypeByDocIdSql($id) { 
	global $dmsLink, $dmsDbName;

	global $dmsLog;
	$dmsLog->debug("#$# db.inc::getMimeTypeByDocIdSql(...)");

	$sql = "select m.* from ".$dmsDbName.".mime_types m, ".$dmsDbName.".documents d where d.mime_id = m.id and d.id = '".$dmsLink->quote($id)."'";
	return $sql;
}
function getDefaultMimeTypeSql() {
	global $dmsLink, $dmsDbName;

	global $dmsLog;
	$dmsLog->debug("#$# db.inc::getDefaultMimeTypeSql(...)");

	$sql = "select * from ".$dmsDbName.".mime_types where mimetypes = 'text/plain'";
	return $sql;
}

function getInsertDocumentTypeLinkSql($id, $folderId, $dtId) {
	global $dmsLink, $dmsDbName;

	global $dmsLog;
	$dmsLog->debug("#$# db.inc::getInsertDocumentTypeLinkSql(...)");

	$sql = "insert into ".$dmsDbName.".folder_doctypes_link(id, folder_id, document_type_id) values (".
		"'".$id."', ".
		"'".$folderId."', ".
		"'".$dtId."')";
	return $sql;
}

// #$# Modified SQL as per the new version of the 'folders' table
function getInsertFolderSql($id, $name, $description, $parentRow, $userId) {
	global $dmsLink, $dmsDbName;

	global $dmsLog;
	$dmsLog->debug("#$# db.inc::getInsertFolderSql(...)");

	/* #$# MOD-MAY08
	$sql = "insert into ".$dmsDbName.".folders(id, name, description, parent_id, creator_id, unit_id, parent_folder_ids, full_path, inherit_parent_folder_permission, permission_folder_id) values (".
		"'".$id."', ".
		"'".$dmsLink->quote($name)."', ".
		"'".$dmsLink->quote($description)."', ".
		"'".$parentRow["id"]."', ".
		"'".$userId."', ".
		"'".$parentRow["unit_id"]."', ".
		"'".($parentRow["parent_folder_ids"]).",".$parentRow["id"]."', ".
		"'".($parentRow["full_path"])."/".$parentRow["name"]."', ".
		"'1', ".
		($parentRow["permission_folder_id"]).")";
	*/

	// #$# Removed fields obsolete in current version of 'folders' table, Added newly added fields to SQL.
	$sql = "insert into ".$dmsDbName.".folders(id, name, description, parent_id, creator_id, parent_folder_ids, full_path, permission_object_id, permission_lookup_id) values (".
		"'".$id."', ".
		"'".$dmsLink->quote($name)."', ".
		"'".$dmsLink->quote($description)."', ".
		"'".$parentRow["id"]."', ".
		"'".$userId."', ".
		"'".($parentRow["parent_folder_ids"]).",".$parentRow["id"]."', ".
		"'".($parentRow["full_path"])."/".$parentRow["name"]."', ".
		"'".($parentRow["permission_object_id"])."',".
		"'".($parentRow["permission_lookup_id"])."'".
		")";

	return $sql;
}

function getDocumentFieldByNameSql($name) {
	global $dmsLink, $dmsDbName;

	global $dmsLog;
	$dmsLog->debug("#$# db.inc::getDocumentFieldByNameSql(...)");

	$sql = "select * from ".$dmsDbName.".document_fields where name = '".$dmsLink->quote($name)."'";
	return $sql;
}

// #$# 
function getDocumentFieldLinkSql($docId, $name) {
	global $dmsLink, $dmsDbName, $dmsLog;
	
	global $dmsLog;
	$dmsLog->debug("#$# db.inc::getDocumentFieldLinkSql(...)");

	/** #$# MOD-MAY09 removed reference to the field 'Document_fields_link.document_id'
	$sql = "select dfl.* from ".$dmsDbName.".document_fields_link dfl, ".$dmsDbName.".document_fields df where ".
		" dfl.document_field_id = df.id and dfl.document_id = '".$docId."' and df.name = '".$dmsLink->quote($name)."'";
	*/
	
	/** #$# MOD-MAY10 Restored reference to the field 'Document_fields_link.document_id' by work around 
 	$sql = "select dfl.* from ".$dmsDbName.".document_fields_link dfl, ".$dmsDbName.".document_fields df where ".
		" dfl.document_field_id = df.id and df.name = '".$dmsLink->quote($name)."'";
	*/

 	$sql = "select dfl.* from ".$dmsDbName.".document_fields_link dfl, ".$dmsDbName.".document_fields df, ".$dmsDbName.".documents d  where ".
		" dfl.document_field_id = df.id and d.id = '".$docId."' and dfl.metadata_version_id = d.metadata_version_id and df.name = '".$dmsLink->quote($name)."'";

	// $dmsLog->debug("#$# Select Query for DocumentFiledLink: ".$sql);

	return $sql;
}

function getUpdateDocumentFieldValueSql($id, $value) {
	global $dmsLink, $dmsDbName;

	global $dmsLog;
	$dmsLog->debug("#$# db.inc::getUpdateDocumentFieldValueSql(...)");

	$sql = "update ".$dmsDbName.".document_fields_link dfl set value = '".$dmsLink->quote($value)."' where id = '".$id."'";
	return $sql;
}

// #$# 
function getDocumentDetailsSql($docId) {
	global $dmsLink, $dmsDbName;

	global $dmsLog;
	$dmsLog->debug("#$# db.inc::getDocumentDetailsSql(...)");

	/** #$# MOD-MAY09 Modified to remove mime_id from 'documents' table.
	$sql = "select d.*, m.mimetypes as mimetype, m.icon_path, co.username as checkedout_username, cr.username as author from ".$dmsDbName.".users cr, ".$dmsDbName.".mime_types m, ".$dmsDbName.".documents d left outer join ".$dmsDbName.".users co on d.checked_out_user_id = co.id where d.creator_id = cr.id and d.mime_id = m.id and d.id = '".$dmsLink->quote($docId)."'";
	*/
	
	/** #$# MOD-MAY09 to fetch fulname and name 
	$sql = "select d.*, m.mimetypes as mimetype, m.icon_path, co.username as checkedout_username, cr.username as author from ".$dmsDbName.".users cr, ".$dmsDbName.".mime_types m, ".$dmsDbName.".documents d left outer join ".$dmsDbName.".users co on d.checked_out_user_id = co.id where d.creator_id = cr.id and d.id = '".$dmsLink->quote($docId)."'";
	*/

	/** #$# MOD-MAY09 to fetch rest of the fields of documents
	$sql = "select d.*, dcv.filename, dmv.name, m.mimetypes as mimetype, m.icon_path, co.username as checkedout_username, cr.username as author from ".$dmsDbName.".users cr, ".$dmsDbName.".document_content_version dcv, ".$dmsDbName.".document_metadata_version dmv, ".$dmsDbName.".mime_types m, ".$dmsDbName.".documents d left outer join ".$dmsDbName.".users co on d.checked_out_user_id = co.id where d.creator_id = cr.id and d.id = '".$dmsLink->quote($docId)."' and dcv.document_id = d.id and dmv.document_id = d.id";
	*/ 

	/** #$# MOD-MAY22 to avoid fetching multiple records from DCV and DMV
	// Still missing fields - 'security' as in old table
	$sql = "select d.*, dmv.document_type_id, dcv.filename, dmv.name, dcv.size, dmv.description, dcv.mime_id, dcv.major_version, dcv.minor_version, m.mimetypes as mimetype, m.icon_path, co.username as checkedout_username, cr.username as author from ".$dmsDbName.".users cr, ".$dmsDbName.".document_content_version dcv, ".$dmsDbName.".document_metadata_version dmv, ".$dmsDbName.".mime_types m, ".$dmsDbName.".documents d left outer join ".$dmsDbName.".users co on d.checked_out_user_id = co.id where d.creator_id = cr.id and d.id = '".$dmsLink->quote($docId)."' and dcv.document_id = d.id and dmv.document_id = d.id and m.id = dcv.mime_id";
	*/ 

	$dcv_id = getDCVID($dmsLink->quote($docId));
	$dmv_id = getDMDVID($dmsLink->quote($docId));
	$sql = "select d.*, dmv.document_type_id, dcv.filename, dmv.name, dcv.size, dmv.description, dcv.mime_id, dcv.major_version, dcv.minor_version, m.mimetypes as mimetype, m.icon_path, co.username as checkedout_username, cr.username as author from ".$dmsDbName.".users cr, ".$dmsDbName.".document_content_version dcv, ".$dmsDbName.".document_metadata_version dmv, ".$dmsDbName.".mime_types m, ".$dmsDbName.".documents d left outer join ".$dmsDbName.".users co on d.checked_out_user_id = co.id where d.creator_id = cr.id and d.id = '".$dmsLink->quote($docId)."' and dcv.id = '".$dcv_id."' and dmv.id = '".$dmv_id."' and m.id = dcv.mime_id";

	return $sql;
}

function getDocumentByTextSql($text, $statusId) {
	global $dmsLink, $dmsDbName;

	global $dmsLog;
	$dmsLog->debug("#$# db.inc::getDocumentByTextSql(...)");

	// $#$ commented by surya on 16th May to changethe table from document_text to document_transaction_text
	//	$sql = "select distinct d.id, round(match(dt.document_text) against ('".$dmsLink->quote($text)."' IN BOOLEAN MODE)) AS score FROM ".$dmsDbName.".documents d INNER JOIN ".$dmsDbName.".document_text AS dt ON d.id = dt.document_id WHERE MATCH(dt.document_text) AGAINST ('".$dmsLink->quote($text)."' IN BOOLEAN MODE) AND d.status_id = ".$statusId." ORDER BY score DESC";
		$sql = "select distinct d.id, round(match(dt.document_text) against ('".$dmsLink->quote($text)."' IN BOOLEAN MODE)) AS score FROM ".$dmsDbName.".documents d INNER JOIN ".$dmsDbName.".document_transaction_text AS dt ON d.id = dt.document_id WHERE MATCH(dt.document_text) AGAINST ('".$dmsLink->quote($text)."' IN BOOLEAN MODE) AND d.status_id = ".$statusId." ORDER BY score DESC";
	return $sql;
}

// #$#
function getDocumentsInFolderSql($folderId, $statusId, $orderByDocumentName) {
	global $dmsLink, $dmsDbName;

	global $dmsLog;
	$dmsLog->debug("#$# db.inc::getDocumentsInFolderSql(...)");

	/** #$# MOD-MAY30 - Rupesh K to selectively sort documents by document name 
	$sql = "select id from ".$dmsDbName.".documents d where d.folder_id = '".$dmsLink->quote($folderId)."'";
	if ($statusId > 0) {
	 	$sql .= " and d.status_id = '".$dmsLink->quote($statusId)."'";
	}
	*/

	/** #$# name field is no longer available in 'Documents' table
	    #$# MOD-MAY08
	if ($orderByDocumentName) {
		$sql .= " order by d.name asc";	
	}
	*/ 
	
	if ($orderByDocumentName) {
		$sql = "select d.id from ".$dmsDbName.".documents d, ".$dmsDbName.".document_metadata_version dmv ".
			"where d.folder_id = '".$dmsLink->quote($folderId)."'".
			" and dmv.id = d.metadata_version_id";
		if ($statusId > 0) {
	 		$sql .= " and d.status_id = '".$dmsLink->quote($statusId)."'";
		}
		$sql .= " order by dmv.name asc";
	} else {
		$sql = "select id from ".$dmsDbName.".documents d where d.folder_id = '".$dmsLink->quote($folderId)."'";
		if ($statusId > 0) {
	 		$sql .= " and d.status_id = '".$dmsLink->quote($statusId)."'";
		}
	}
	
	return $sql;
}

// #$#
function getAllDocumentsInFolderSql($folderId, $orderByDocumentName) {
	global $dmsLink, $dmsDbName;

	global $dmsLog;
	$dmsLog->debug("#$# db.inc::getAllDocumentsInFolderSql(...)");

	$sql = "select id from ".$dmsDbName.".documents d where d.folder_id = '".$dmsLink->quote($folderId)."'";
 	$sql .= " and d.status_id != '".$dmsLink->quote(DOCSTATUS_DELETED)."'";
	
	/** #$# name field is no longer available in 'Documents' table
	    #$# MOD-MAY08
	if ($orderByDocumentName) {
		$sql .= " order by d.name asc";
	}
	*/
	return $sql;
}

function getDocumentInFolderSql($folderId, $filename) {
	global $dmsLink, $dmsDbName;

	global $dmsLog;
	$dmsLog->debug("#$# db.inc::getDocumentInFolderSql(...)");

//	#$# commented by surya on 12th May to get the filename from table document_content_version
//	$sql = "select d.id from ".$dmsDbName.".documents d where d.folder_id = '".$dmsLink->quote($folderId)."' and d.filename = '".$dmsLink->quote($filename)."' and d.status_id != '".$dmsLink->quote(DOCSTATUS_DELETED)."'";
	$sql = "select d.id from ".$dmsDbName.".documents d,".$dmsDbName.".document_content_version dcv where d.id=dcv.document_id and d.folder_id = '".$dmsLink->quote($folderId)."' and dcv.filename = '".$dmsLink->quote($filename)."' and d.status_id != '".$dmsLink->quote(DOCSTATUS_DELETED)."'";
	return $sql;
}

function getUpdateDocumentCheckedOutStatusSql($docId, $checkedout, $userId) {
	global $dmsLink, $dmsDbName;

	global $dmsLog;
	$dmsLog->debug("#$# db.inc::getUpdateDocumentCheckedOutStatusSql(...)");

	/** #$# MOD-MAY26 modified not to be updated
	$sql = "update ".$dmsDbName.".documents set is_checked_out = '".$dmsLink->quote($checkedout)."', checked_out_user_id = '".$dmsLink->quote($userId)."', modified = '".date('Y-m-d H:i:s')."' where ".
		"id = '".$dmsLink->quote($docId)."'";
	*/

	$sql = "update ".$dmsDbName.".documents set is_checked_out = '".$dmsLink->quote($checkedout)."', checked_out_user_id = '".$dmsLink->quote($userId)."' where ".
		"id = '".$dmsLink->quote($docId)."'";

	return $sql;
}

// #$# MOD-MAY28 Removed Older function completely
function getUpdateStatementCheckedInStatusSql($docId, $majorVersion, $minorVersion,$newMDVID) {
	global $dmsLink, $dmsDbName;

	global $dmsLog;
	$dmsLog->debug("#$# db.inc::getUpdateStatementCheckedInStatusSql(...)");

	/**#$# MOD-MAY 12 To remove obsolete fields from documents table.
	(major_version and minor_version are removed)
	$sql = "update ".$dmsDbName.".documents set is_checked_out = '0', checked_out_user_id = '-1', ".
			"modified = '".date('Y-m-d H:i:s')."', major_version = '".$majorVersion."', ".
			"minor_version = '".$minorVersion."' where id = '".$dmsLink->quote($docId)."'";
	*/
	// metadata_version_id
	
	$sql = "update ".$dmsDbName.".documents set is_checked_out = '0', metadata_version_id = '$newMDVID',checked_out_user_id = '-1', ".
			"modified = '".date('Y-m-d H:i:s')."' where id = '".$dmsLink->quote($docId)."'";

	return $sql;
}
function getUpdateDocumentDataSql($docId, $name, $description) {
	global $dmsLink, $dmsDbName;

	global $dmsLog;
	$dmsLog->debug("#$# db.inc::getUpdateDocumentDataSql(...)");
	/**#$# MOD-MAY13 To remove obsolete fields from documents table and update them in relevant table.
	(Removed name from documents table and name field in document_metadata_version table is updated.)
	$sql = "update ".$dmsDbName.".documents set name = '".$dmsLink->quote($name)."', description = '".$dmsLink->quote($description)."', modified = '".date('Y-m-d H:i:s')."' where ".
			"id = '".$dmsLink->quote($docId)."'";
	*/
	
	// #$# MOD-MAY18 Code is removed and moved to new functuion editMetaData()
	//New Row tobe inserted into document_metadata_version
	editMetaData($docId, $name, $description);
	
	// Getting newly added metadata_version_id.
	$dmv_id = getDMDVID($docId);
	$dmsLog->debug("#$# db.inc::getUpdateDocumentDataSql - new metadata_version_id: ".$dmv_id);

	// To Update description and modified fields in documents table. 
	$sql = "update ".$dmsDbName.".documents set modified = '".date('Y-m-d H:i:s')."', metadata_version_id = '".$dmv_id."' where ".
	"id = '".$dmsLink->quote($docId)."'";

	/**	 #$# MOD-MAY24 Not updating document_metadata_version table. Was added earlier by us.
	$newSql = "update ".$dmsDbName.".document_metadata_version set name = '".$dmsLink->quote($name)."', description = '".$dmsLink->quote($description)."' where document_id = '".$dmsLink->quote($docId)."'";
	dmsQuery($newSql);
	*/
	
	return $sql;
}
function getUpdateDocumentStatusSql($docId, $statusId) {
	global $dmsLink, $dmsDbName;

	global $dmsLog;
	$dmsLog->debug("#$# db.inc::getUpdateDocumentStatusSql(...)");
	/**
	  #$# MOD-MAY26 Not to modify modified field. We need to update only status_id of the documents table.
	  $sql = "update ".$dmsDbName.".documents set status_id = '".$statusId."', modified = '".date('Y-m-d H:i:s')."' where ".
			"id = '".$dmsLink->quote($docId)."'";
	*/
	$sql = "update ".$dmsDbName.".documents set status_id = '".$statusId."' where ".
			"id = '".$dmsLink->quote($docId)."'";
	return $sql;
}

// #$#
function getDocumentTransactionsSql($docId) {
	global $dmsLink, $dmsDbName;

	global $dmsLog;
	$dmsLog->debug("#$# db.inc::getDocumentTransactionsSql(...)");

	/** #$# MOD-MAY09 Removed obsolete field dt.transaction_id
	$sql = "select dt.*, u.username as username, l.name as type from ".$dmsDbName.".document_transactions dt, ".$dmsDbName.".users u, ".$dmsDbName.".document_transaction_types_lookup l where ".
		"dt.transaction_id = l.id and dt.user_id = u.id and dt.document_id = '".$dmsLink->quote($docId)."' order by dt.datetime desc";
	*/

	/** #$# MOD-MAY24 Restored mapping with transaction lookup table.
	$sql = "select dt.*, u.username as username, l.name as type from ".$dmsDbName.".document_transactions dt, ".$dmsDbName.".users u, ".$dmsDbName.".document_transaction_types_lookup l where ".
		"dt.user_id = u.id and dt.document_id = '".$dmsLink->quote($docId)."' order by dt.datetime desc";
	*/
	
	$sql = "select dt.*, u.username as username, l.name as type from ".$dmsDbName.".document_transactions dt, ".$dmsDbName.".users u, ".$dmsDbName.".document_transaction_types_lookup l where ".
		"dt.user_id = u.id and dt.document_id = '".$dmsLink->quote($docId)."' and l.namespace = dt.transaction_namespace order by dt.datetime desc";
		
	return $sql;
}

function getRecentlyChangedSql($start, $limit) {
	global $dmsLink, $dmsDbName;

	global $dmsLog;
	$dmsLog->debug("#$# db.inc::getRecentlyChangedSql(...)");

	if (empty($start)) $start = "0";
	
	$sql = "select d.id from ".$dmsDbName.".documents d where d.status_id != '".$dmsLink->quote(DOCSTATUS_DELETED)."' order by d.modified desc limit ".$start.",".$limit;
	return $sql;
}

function getInsertDocumentTransactionSql($id, $docId, $version, $userId, $filename, $comment, $transactionLookupId,$parentFolderRow='') {
	global $dmsLink, $dmsDbName;

	global $dmsLog;
	$dmsLog->debug("#$# db.inc::getInsertDocumentTransactionSql(...)");

	/** #$# MOD-MAY11 Removed obsolete field dt.transaction_id 
	//  also added ip field by surya on 17th May 2006 and also change the filepath
	$sql = "insert into ".$dmsDbName.".document_transactions(id, document_id, version, user_id, datetime, ip, filename, comment, transaction_id) values(".
		"'".$id."', ".
		"'".$docId."', ".
		"'".$version."', ".
		"'".$userId."', ".
		"'".date('Y-m-d H:i:s')."', ".
		"'".$filename."', ".
		"'".$comment."', ".
		"'".$transactionId."')";
	*/
	// get the ip address of the machine
		$ipAddress = $_SERVER['REMOTE_ADDR'];
		
	// remove the file extension from the filename
	//$filename = removeExtensionFromFilename($filename);
		
		
	$transaction_namespace = getTransactionNamespace($transactionLookupId);
	
	$sql = "insert into ".$dmsDbName.".document_transactions(id, document_id, version, user_id, datetime, ip, filename, comment, transaction_namespace) 
	values(".
			"'".$id."', ".
			"'".$docId."', ".
			"'".$version."', ".
			"'".$userId."', ".
			"'".date('Y-m-d H:i:s')."', ".
			"'".$ipAddress."', ".
			"'".$filename."', ".
//			"'".($parentFolderRow["full_path"])."/".$parentFolderRow["name"]."/".$filename."',". 
			"'".$comment."',".
			"'".$transaction_namespace."')";
	return $sql;
}

function getInsertWebsiteSql($id, $docId, $unitId) {
	global $dmsLink, $dmsDbName;

	global $dmsLog;
	$dmsLog->debug("#$# db.inc::getInsertWebsiteSql(...)");

	$sql = "insert into ".$dmsDbName.".web_documents (id, document_id, web_site_id, unit_id, status_id, datetime) values(".
		"'".$id."', ".
		"'".$docId."', ".
		"'-1', ".
		"'".$unitId."', ".
		"'3', ".
		"'".date('Y-m-d H:i:s')."')";
	return $sql;
}

function getInsertDocumentSql($id, $name, $filename, $docTypeId, $size, $userId, $description, $mimeTypeId, $parentFolderRow, 
		$majorVersion, $minorVersion,$metadataVersionId,$permission_object_id,$permission_lookup_id,$modified_user_id) {

	global $dmsLink, $dmsDbName;

	global $dmsLog;
	$dmsLog->debug("#$# db.inc::getInsertDocumentSql(...)");
//	#$# commented by surya to change the insert query for documents
/*	$sql = "insert into ".$dmsDbName.".documents(id, name, filename, document_type_id, size, creator_id, modified, created, description, security, mime_id, ".
		" folder_id, major_version, minor_version, is_checked_out, parent_folder_ids, full_path, checked_out_user_id, status_id) values(".
		"'".$id."', ".
		"'".$dmsLink->quote($name)."', ".
		"'".$dmsLink->quote($filename)."', ".
		"'".$docTypeId."', ".
		"'".$size."', ".
		"'".$userId."', ".
		"'".date('Y-m-d H:i:s')."', ".
		"'".date('Y-m-d H:i:s')."', ".
		"'".$dmsLink->quote($description)."', ".
		"'0', ".
		"'".$mimeTypeId."', ".
		"'".$parentFolderRow["id"]."', ".
		"'".$majorVersion."', ".
		"'".$minorVersion."', ".
		"'0', ".
		"'".($parentFolderRow["parent_folder_ids"]).",".$parentFolderRow["id"]."', ".
		"'".($parentFolderRow["full_path"])."/".$parentFolderRow["name"]."', ".
		"'-1', ".
		"'".DOCSTATUS_LIVE."')"; */
	$sql = "insert into ".$dmsDbName.".documents(id, creator_id, modified, created, folder_id, is_checked_out,".
		" parent_folder_ids, full_path, checked_out_user_id,metadata_version_id,permission_object_id,permission_lookup_id,modified_user_id,status_id) values(".
		"'".$id."', ".
		"'".$userId."', ".
		"'".date('Y-m-d H:i:s')."', ".
		"'".date('Y-m-d H:i:s')."', ".
		"'".$parentFolderRow["id"]."', ".
		"'0', ".
		"'".($parentFolderRow["parent_folder_ids"]).",".$parentFolderRow["id"]."', ".
		"'".($parentFolderRow["full_path"])."/".$parentFolderRow["name"]."', ".
		"'-1', ".
		"'".$metadataVersionId."', ".
		"'".$permission_object_id."', ".
		"'".$permission_lookup_id."', ".
		"'".$modified_user_id."', ".		
		"'".DOCSTATUS_LIVE."')"; 	
	return $sql;
}

function getInsertDocumentFieldLinkSql($id, $metadataVersionId, $fieldId, $value) {
	global $dmsLink, $dmsDbName;

	global $dmsLog;
	$dmsLog->debug("#$# db.inc::getInsertDocumentFieldLinkSql(...)");
	/** #$# MOD-MAY13 Removed documentid from the field list and metadata_version_id is newly inserted.
		$sql = "insert into ".$dmsDbName.".document_fields_link(id, document_id, document_field_id, value) values(".
		"'".$id."', ".
		"'".$docId."', ".
		"'".$fieldId."', ".
		"'".$dmsLink->quote($value)."')";
	*/
	
	$mdvID  = getDMDVID($docId);
		
	$sql = "insert into ".$dmsDbName.".document_fields_link(id, document_field_id, value, metadata_version_id) values(".
	"'".$id."', ".
	"'".$fieldId."', ".
	"'".$dmsLink->quote($value)."', ".
	"'".$metadataVersionId."')";
	
	return $sql;
}

// #$#
function getLinkedDocumentsSql($docId, $orderByDocumentName) {
	global $dmsLink, $dmsDbName;

	global $dmsLog;
	$dmsLog->debug("#$# db.inc::getLinkedDocumentsSql(...)");

	$sql = "select d.id from ".$dmsDbName.".documents d, ".$dmsDbName.".document_link dl where dl.parent_document_id = '".$dmsLink->quote($docId)."'";
 	$sql .= " and d.status_id != '".$dmsLink->quote(DOCSTATUS_DELETED)."' and d.id = dl.child_document_id";
	
	/** #$# MOD-MAY09 d.name is obsolete
	if ($orderByDocumentName) {
		$sql .= " order by d.name asc";
	}
	*/

	return $sql;
}

// #$#
function getLinkingDocumentsSql($docId, $orderByDocumentName) {
	global $dmsLink, $dmsDbName;

	global $dmsLog;
	$dmsLog->debug("#$# db.inc::getLinkingDocumentsSql(...)");

	$sql = "select d.id from ".$dmsDbName.".documents d, ".$dmsDbName.".document_link dl where dl.child_document_id = '".$dmsLink->quote($docId)."'";
 	$sql .= " and d.status_id != '".$dmsLink->quote(DOCSTATUS_DELETED)."' and d.id = dl.parent_document_id";

	/** #$# MOD-MAY09 d.name is obsolete
	if ($orderByDocumentName) {
		$sql .= " order by d.name asc";
	}
	*/

	return $sql;
}

function getInsertDocumentLinkSql($id, $parentDocId, $childDocId, $dmsLinkTypeId) {
	global $dmsLink, $dmsDbName;

	global $dmsLog;
	$dmsLog->debug("#$# db.inc::getInsertDocumentLinkSql(...)");

	$sql = "insert into ".$dmsDbName.".document_link (id, parent_document_id, child_document_id, link_type_id) values(".
		"'".$id."', ".
		"'".$parentDocId."', ".
		"'".$childDocId."', ".
		"'".$dmsLinkTypeId."')";
	return $sql;
}

function getDocumentLinkSql($parentDocId, $childDocId) {
	global $dmsLink, $dmsDbName;

	global $dmsLog;
	$dmsLog->debug("#$# db.inc::getDocumentLinkSql(...)");

	$sql = "select dl.id from ".$dmsDbName.".document_link dl where dl.parent_document_id = '".$dmsLink->quote($parentDocId)."' and dl.child_document_id = '".$dmsLink->quote($childDocId)."'";
	return $sql;
}

function getDeleteDocumentLinkSql($parentDocId, $childDocId) {
	global $dmsLink, $dmsDbName;

	global $dmsLog;
	$dmsLog->debug("#$# db.inc::getDeleteDocumentLinkSql(...)");

	$sql = "delete from ".$dmsDbName.".document_link where parent_document_id = '".$dmsLink->quote($parentDocId)."' and child_document_id = '".$dmsLink->quote($childDocId)."'";
	return $sql;
}

// #$# function added by surya to insert the record in document_content_version table
function getInsertDocumentContentVersionSql($id, $name, $filename, $docId, $size, $userId, $description, $mimeTypeId, $parentFolderRow, 
		$majorVersion, $minorVersion) {

	global $dmsLink, $dmsDbName;

	global $dmsLog;
	$sql = "insert into ".$dmsDbName.".document_content_version(id, filename, document_id, size, mime_id, ".
		" major_version, minor_version, storage_path) values(".
		"'".$id."', ".
		"'".$dmsLink->quote($filename)."', ".
		"'".$docId."', ".
		"'".$size."', ".
		"'".$mimeTypeId."', ".
		"'".$majorVersion."', ".
		"'".$minorVersion."', ".
		"'".($parentFolderRow["full_path"])."/".$parentFolderRow["name"]."/".$dmsLink->quote($filename)."')"; 
	return $sql;
}

// #$# function added by surya to insert the record in document_metadata_version table
function getInsertDocumentMetadataVersionSql($id, $name, $filename, $docId, $size, $userId, $description, $mimeTypeId, $parentFolderRow, 
		$majorVersion, $minorVersion,$docContentVersionId,$docTypeId) {

	global $dmsLink, $dmsDbName;
	
	// remove the file extension from the filename
	$filename = removeExtensionFromFilename($filename);
	
	global $dmsLog;
	$sql = "insert into ".$dmsDbName.".document_metadata_version(id, document_id, content_version_id, document_type_id, name, ".
		" description, status_id , metadata_version,version_created,version_creator_id) values(".
		"'".$id."', ".
		"'".$docId."', ".
		"'".$docContentVersionId."', ".
		"'".$docTypeId."', ".
//		"'".$dmsLink->quote($filename)."', ".
		"'".$filename."', ".
		"'".$description."', ".
		"'".DOCSTATUS_LIVE."', ".
		"'0', ".
		"'".date('Y-m-d H:i:s')."', ".
		"'".$userId."')"; 
	return $sql;
}

/**
author : Sriram.V
date   : May-12-2006
ADD-MAY12 #$# This function returns the id from DCV by accepting document id.
*/
function getDCVID($docId)
{
	global $dmsLink, $dmsDbName;
	$sql="SELECT max(id) as dcv_id FROM ".$dmsDbName.".document_content_version WHERE document_id = '$docId'";
	$rs = dmsQuery($sql);
	while ($row = $dmsLink->fetchByAssoc($rs)) 
	{
		$DCVID = $row["dcv_id"];
	}
	return $DCVID;
}

function getDCVIDForVersion($docId, $majorVersion, $minorVersion)
{
	global $dmsLink, $dmsDbName;
	$sql="SELECT id FROM ".$dmsDbName.".document_content_version WHERE document_id = '$docId' and major_version='$majorVersion' and minor_version='$minorVersion'";

	$rs = dmsQuery($sql);
	while ($row = $dmsLink->fetchByAssoc($rs)) 
	{
		$DCVID = $row["id"];
	}
	return $DCVID;
}



// function added by surya to remove the file extension from the filename
function removeExtensionFromFilename($filename)
{
	if (strstr($filename, ".")) {
		$fileArray = explode(".",$filename);
		for($i=0;$i<count($fileArray)-1;$i++)
		{
			$fileName .= $fileArray[$i];
			if($i<count($fileArray)-2)
			{
				$fileName .= ".";
			}
		}		
		return $fileName;
	} else {
		return $filename;
	}
}

// $#$ function written to insert record in document text table
function getInsertDocumentTextSql($docId, $contents)
{
	global $dmsLink, $dmsDbName;
	global $dmsLog;

	$sql = "insert into ".$dmsDbName.".document_text(document_id, document_text) values(".
		"'".$docId."', ".
		"'".$contents."')"; 
	return $sql;	
}

// $#$ function written to insert/modify record in document text table
function updateTransactionText($docId, $mode)
{
	global $dmsLink, $dmsDbName;
	global $dmsLog;
		
	if($mode == "insert")
	{
		$contents = "Document created";
		$sql = "insert into ".$dmsDbName.".document_transaction_text(document_id, document_text) values(".
		"'".$docId."', ".
		"'".$contents."')"; 
	}
	else if($mode=="download")
	{
		$contents = "Document created"."\n"."Document downloaded";
		$sql = "update ".$dmsDbName.".document_transaction_text set document_text='".$contents."' where document_id='".$docId."'"; 
	}	
	return $sql;	
}

// $#$ function written to insert/modify record in document searchable text table
function updateSearchableText($docId, $mode, $filename, $description)
{
	global $dmsLink, $dmsDbName;
	global $dmsLog;

	if($mode == "insert")
	{
		$contents = "Document created ".$filename." ".$description;
		$sql = "insert into ".$dmsDbName.".document_searchable_text(document_id, document_text) values(".
		"'".$docId."', ".
		"'".$contents."')"; 
	}
	else if($mode=="download")
	{
		$contents = "Document downloaded ".$filename." ".$description;
		$sql = "update ".$dmsDbName.".document_searchable_text set document_text='".$contents."' where document_id='".$docId."'"; 
	}	
	return $sql;	
}

/**
author : Sriram.V
date   : May-13-2006
ADD-MAY13 #$# This function returns the metadata_version_id from documents table by accepting document id.
*/
function getDMDVID($docId)
{
		global $dmsLink, $dmsDbName, $rs1;
		global $dmsLog;
		
		/** MOD-MAY24 Retreiving metadata_version_id from documents_metadata_version table. Useful while edit metadata 
		$sql = "select metadata_version_id from ".$dmsDbName.". documents where id='$docId'";
 		$rs1 = dmsQuery($sql);
		while ($row1 = $dmsLink->fetchByAssoc($rs1)) 
		{
			$MDVID = $row1["metadata_version_id"];
			$dmsLog->debug("#$# db.inc::current metadata_version_id: ".$MDVID);
		}
		*/

		$sql = "select id from ".$dmsDbName.".document_metadata_version where document_id='$docId'";
 		$rs1 = dmsQuery($sql);
		while ($row1 = $dmsLink->fetchByAssoc($rs1)) 
		{
			$MDVID = $row1["id"];
			$dmsLog->debug("#$# db.inc::current metadata_version_id: ".$MDVID);
		}
		return $MDVID;
}

/**
author : Sriram.V
date   : May-19-2006
ADD-MAY19 #$# This function returns the statusid from documents table by accepting document id.
*/
function getStatusID($docId)
{
		global $dmsLink, $dmsDbName, $rs1;
		$sql = "select status_id from ".$dmsDbName.". documents where id='$docId'";
 		$rs1 = dmsQuery($sql);
		while ($row1 = $dmsLink->fetchByAssoc($rs1)) 
		{
			$statusID = $row1["status_id"];
		}
		return $statusID;
}

/**
author : Sriram.V
date   : May-19-2006
ADD-MAY19 #$# This function returns the metadata_version from documents table by accepting document id.
*/
function getMetaDataVersion($docId)
{
		global $dmsLink, $dmsDbName, $rs1;
		$sql = "select metadata_version from ".$dmsDbName.". documents where id='$docId'";
 		$rs1 = dmsQuery($sql);
		while ($row1 = $dmsLink->fetchByAssoc($rs1)) 
		{
			$metadata_version = $row1["metadata_version"];
		}
		return $metadata_version;
}

/**
author : Sriram.V
date   : May-19-2006
ADD-MAY19 #$# This function is called whenever metadata is being edited from ZuckerDocs.
*/
function editMetaData($docId, $name, $description)
{
	global $dmsLink, $dmsDbName;
	// #$# Added on May-19 to get metadata_version_id
	$docMetadataVersionId = getNewId("document_metadata_version");

	//#$# Added on May-19 To get documentTypeID
	// #$# Start
	
	$docTypeName = SUGAR_DOCTYPE;
	$docType = KT_FolderProvider::__getDocumentType($docTypeName);
	
	// $docTypeId will be having dcoumentTypeId
	$docTypeId = $docType->id;
	// #$# End
	
	//#$# Added on May-19 To get version_creator_id
	// #$# Start
	global $current_user;
	$userName = $current_user->user_name;
	$userId = KT_AuthProvider::__getUserId($userName);
	$version_creator_id = $userId;
	// #$# End
	
	// #$# To get content_version_id
	$content_version_id = getDCVID($docId);
	$status_ID = getStatusID($docId);
	$metadata_version = getMetaDataVersion($docId);
	
	$insertQuery = 	"INSERT INTO ".$dmsDbName.".document_metadata_version (id, document_id, 
				content_version_id, document_type_id, name,description, status_id, metadata_version, 
				version_created, version_creator_id) values(".
				"'".$docMetadataVersionId."',".
				"'".$docId."', ".
				"'".$content_version_id."',".
				"'".$docTypeId."',".
				"'".$dmsLink->quote($name)."', ".
				"'".$description."',".
				"'".$status_ID."',".
				"'".$metadata_version."',".
				"'".date('Y-m-d H:i:s')."',".
				"'".$version_creator_id."')"; 
	dmsQuery($insertQuery);
	//#$# Added to insert into document_transactions table.
	$transId = getNewId("document_transactions");
	$version = getVersion($docId);
	$newVersion = $version + 0.1 ;
	
	$sql = getFolderDetailsSql($folder->id);
	$rs = dmsQuery($sql);
	$folderRow = $dmsLink->fetchByAssoc($rs);

	/** MOD-MAY26 - Rupesh K - providing transaction_namespace	
		$sql = getInsertDocumentTransactionSql($transId, $docId, $newVersion, $userId, $name, 'update metadata', 'zuckerdocs.update', $folderRow);
	*/
	
	$sql = getInsertDocumentTransactionSql($transId, $docId, $newVersion, $userId, $name, 'update metadata', $HIST_UPDATE, $folderRow);	
	dmsQuery($sql);
}

/**
author : Sriram.V
date   : May-19-2006
ADD-MAY19 #$# This function returns the version from documents_transactions table by accepting document id.
*/
function getVersion($docId)
{
		global $dmsLink, $dmsDbName, $rs1;
		$sql = "select version from ".$dmsDbName.". document_transactions where document_id='$docId'";
 		$rs1 = dmsQuery($sql);
		while ($row1 = $dmsLink->fetchByAssoc($rs1)) 
		{
			$version = $row1["version"];
		}
		return $version;
}
/**
author : Sriram.V
date   : May-26-2006
ADD-MAY26 #$# This function returns sql query to insert a row into document_metadata_version table.
*/
function getInsertDMDVSQL($docId,$name)
{
	global $dmsLink, $dmsDbName;
	// #$# Added on May-26 to get metadata_version_id
	$docMetadataVersionId = getNewId("document_metadata_version");

	//#$# Added on May-26 To get documentTypeID
	// #$# Start
	
	$docTypeName = SUGAR_DOCTYPE;
	$docType = KT_FolderProvider::__getDocumentType($docTypeName);
	
	// $docTypeId will be having dcoumentTypeId
	$docTypeId = $docType->id;
	// #$# End
	
	//#$# Added on May-26 To get version_creator_id
	// #$# Start
	global $current_user;
	$userName = $current_user->user_name;
	$userId = KT_AuthProvider::__getUserId($userName);
	$version_creator_id = $userId;
	// #$# End
	
	// #$# To get content_version_id
	$content_version_id = getDCVID($docId);
	$status_ID = getStatusID($docId);
	$metadata_version = getMetaDataVersion($docId);
	
	$description = 'ZuckerDocs';
	$insertQuery = 	"INSERT INTO ".$dmsDbName.".document_metadata_version (id, document_id, 
				content_version_id, document_type_id, name,description, status_id, metadata_version, 
				version_created, version_creator_id) values(".
				"'".$docMetadataVersionId."',".
				"'".$docId."', ".
				"'".$content_version_id."',".
				"'".$docTypeId."',".
				"'".$dmsLink->quote($name)."', ".
				"'".$description."',".
				"'".$status_ID."',".
				"'".$metadata_version."',".
				"'".date('Y-m-d H:i:s')."',".
				"'".$version_creator_id."')"; 
	return $insertQuery;
}
/**
author : Sriram.V
date   : May-26-2006
ADD-MAY26 #$# This function returns namespace by accepting id from document_transaction_types_lookup table.
*/
function getTransactionNamespace($id)
{
		global $dmsLink, $dmsDbName, $rs, $dmsLog;
		$sql = "select  namespace from ".$dmsDbName.". document_transaction_types_lookup where id='$id'";
 		$rs = dmsQuery($sql);
		while ($row = $dmsLink->fetchByAssoc($rs)) 
		{
			$namespace = $row["namespace"];
		}
		if($namespace=="") {
			$namespace = "ktcore.transactions.event";
		}
		
//		$dmsLog("db.inc _ getTransactionNamespace - for look up id:".$id.", returning namespace:".$namespace);

		return $namespace;
}

/**
author : Sriram.V
date   : May-26-2006
ADD-MAY26 #$# This function updates the status_id of DMDV by document_id 
*/
function upDateDMDV($docId)
{
	global $dmsDbName;
	/**	 
	#$# MOD-MAY24 To update document_metadata_version table.
	*/
	$newStatusId = getStatusID($docId);
	$newSql = "update ".$dmsDbName.".document_metadata_version set  status_id = '".$newStatusId."' where document_id = '".$docId."'";
	dmsQuery($newSql);
}
/**
author : Sriram.V
date   : May-27-2006
ADD-MAY26 #$# This function returns sql query to insert a row into document_metadata_version table.

*/
function getNewInsertDMDVSQL($id,$docId,$name)
{
	global $dmsLink, $dmsDbName;
	
	//#$# Added on May-27 To get documentTypeID
	// #$# Start
	$docTypeName = SUGAR_DOCTYPE;
	$docType = KT_FolderProvider::__getDocumentType($docTypeName);
	
	// $docTypeId will be having dcoumentTypeId
	$docTypeId = $docType->id;
	// #$# End
	
	//#$# Added on May-27 To get version_creator_id
	// #$# Start
	global $current_user;
	$userName = $current_user->user_name;
	$userId = KT_AuthProvider::__getUserId($userName);
	$version_creator_id = $userId;
	// #$# End
	
	// #$# To get content_version_id
	$content_version_id = getDCVID($docId);
	$status_ID = getStatusID($docId);
	$metadata_version = getMetaDataVersion($docId);
	
	$description = 'ZuckerDocs';
	$insertQuery = 	"INSERT INTO ".$dmsDbName.".document_metadata_version (id, document_id, 
				content_version_id, document_type_id, name,description, status_id, metadata_version, 
				version_created, version_creator_id) values(".
				"'".$id."',".
				"'".$docId."', ".
				"'".$content_version_id."',".
				"'".$docTypeId."',".
				"'".$dmsLink->quote($name)."', ".
				"'".$description."',".
				"'".$status_ID."',".
				"'".$metadata_version."',".
				"'".date('Y-m-d H:i:s')."',".
				"'".$version_creator_id."')"; 
	return $insertQuery;
}
/**
author : Sriram.V
date   : May-26-2006
ADD-MAY26 #$# This function returns the  mime_id of DCV by document_id 
*/
function getMimeId($docId)
{
	/**	 
	#$# MOD-MAY27 To get mime_id.
	*/
	global $dmsLink, $dmsDbName, $rs1;
		$sql = "select  mime_id from ".$dmsDbName.". document_content_version where document_id='$docId'";
 		$rs1 = dmsQuery($sql);
		while ($row1 = $dmsLink->fetchByAssoc($rs1)) 
		{
			$mime_id = $row1["mime_id"];
		}
		return $mime_id;
}
/**
author : Sriram.V
date   : May-27-2006
ADD-MAY27 #$# This function inserts into DCV. Calling this From docProvider.inc ::Checkin
*/
function getNewInsertDCV($id,$name,$docId,$size,$mimeTypeId,$majorVersion,$minorVersion,$path)
{
global $dmsDbName;
$insertDCVSQL = "insert into ".$dmsDbName.".document_content_version(id, filename, document_id, size, mime_id, ".
				" major_version, minor_version, storage_path) values(".
				"'".$id."', ".
				"'".$name."', ".
				"'".$docId."', ".
				"'".$size."', ".
				"'".$mimeTypeId."', ".
				"'".$majorVersion."', ".
				"'".$minorVersion."', ".
				"'".$path."')"; 
				return $insertDCVSQL;
}
/**
author : Rupesh K
date   : 05-30-2006
#$# ADD-MAY30 This function inserts into DCV. Calling this From docProvider.inc ::Checkin
*/
function getFolderId($docId)
{
	global $dmsLink, $dmsDbName, $rs1;
		$sql = "select  folder_id from ".$dmsDbName.".documents where id='$docId'";
 		$rs1 = dmsQuery($sql);
		while ($row1 = $dmsLink->fetchByAssoc($rs1)) 
		{
			$folder_id = $row1["folder_id"];
		}
		return $folder_id;
}
?>