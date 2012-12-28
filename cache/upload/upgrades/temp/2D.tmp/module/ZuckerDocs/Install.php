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
require_once('dms/conf.inc');
 
echo "checking KnowledgeTree database connectivity to ".$dmsDns." ...<br/>";
require_once('dms/db.inc');
echo "success<br/>";

echo "checking KnowledgeTree filesystem access ...<br/>";
if (!file_exists($dmsRootDir)) {
	die("directory ".$dmsRootDir." doesn't exist, please check your KnowledgeTree installation");
}

$rs = dmsQuery("select * from ".$dmsDbName.".folders where parent_id = 0");

$row = $dmsLink->fetchByAssoc($rs);
if (!$row) {
	die("no root folder in database, please check your KnowledgeTree installation");
}
$rootFolderId = $row["id"];
$rootFolderName = $row["name"];
$rootFolder = $dmsRootDir.($row["name"]);
echo "found KnowledgeTree root folder ".$rootFolder."<br/>";

if (!is_dir($rootFolder) || !is_writable($rootFolder)) {
	die("directory ".$rootFolder." not writable by webserver user, please check your KnowledgeTree installation");
}
echo "success<br/>";

require_once('dms/sugarprovider.inc');
echo "checking KnowledgeTree Document Fields ...<br/>";
$docFields = array(SUGAR_PARENTID, SUGAR_CAT, SUGAR_PARENTTYPE, SUGAR_PARENTNAME, SUGAR_PARENTLINK);
foreach ($docFields as $docField) {
	$rs = dmsQuery("select * from ".$dmsDbName.".document_fields where name='".$docField."'");
	$row = $dmsLink->fetchByAssoc($rs);
	if (!$row) {
		$newId = getNewId("document_fields");
		dmsQuery("insert into ".$dmsDbName.".document_fields(id, name, data_type, is_generic, has_lookup) values('".$newId."', '".$docField."', 'STRING', 1, 0)");
		echo "Document Field ".$docField." created<br/>";
	} else {
		echo "Document Field ".$docField." present<br/>";
	}
}

echo "checking KnowledgeTree Document Types ...<br/>";
$rs = dmsQuery('select * from '.$dmsDbName.'.document_types_lookup where name="'.SUGAR_DOCTYPE.'"');
$row = $dmsLink->fetchByAssoc($rs);
if (!$row) {
	$docTypeId = getNewId("document_types_lookup");
	dmsQuery('insert into '.$dmsDbName.'.document_types_lookup(id, name) values("'.$docTypeId.'", "'.SUGAR_DOCTYPE.'")');
	echo "Document Type ".SUGAR_DOCTYPE." created<br/>";
} else {
	$docTypeId = $row->id;
	echo "Document Type ".SUGAR_DOCTYPE." present<br/>";
}

echo "checking KnowledgeTree Units ...<br/>";
$unitName = "ZuckerDocs Unit";
$rs = dmsQuery('select * from '.$dmsDbName.'.units_lookup where name="'.$unitName.'"');
$row = $dmsLink->fetchByAssoc($rs);
if (!$row) {
	$unitId = getNewId("units_lookup");
	$unitFolderId = getNewId("folders");
	
	dmsQuery('insert into '.$dmsDbName.'.units_lookup(id, name, folder_id) values("'.$unitId.'", "'.$unitName.'", "'.$unitFolderId.'")');
	
	$rs = dmsQuery('select * from '.$dmsDbName.'.organisations_lookup where name = "Default Organisation"');
	$row = $dmsLink->fetchByAssoc($rs);
	if (!$row) {
		die("Default Organisation not configured, please check your KnowledgeTree installation");
	}
	$orgId = $row["id"];
	$newId = getNewId("units_organisations_link");
	dmsQuery('insert into '.$dmsDbName.'.units_organisations_link(id, unit_id, organisation_id) values("'.$newId.'","'.$unitId.'", "'.$orgId.'")');
	
	
	$sql = "insert into ".$dmsDbName.".folders(id, name, description, parent_id, creator_id, parent_folder_ids, full_path, permission_folder_id) values (".
		"'".$unitFolderId."', ".
		"'".$unitName."', ".
		"'".$unitName." Root Folder', ".
		"'".$rootFolderId."', ".
		"'1', ".
		"'".$rootFolderId."', ".
		"'".$rootFolderName."', ".
		"'".$unitFolderId."')";
	dmsQuery($sql);
	
	$newId = getNewId("folder_doctypes_link");
	dmsQuery('insert into '.$dmsDbName.'.folder_doctypes_link(id, folder_id, document_type_id) values("'.$newId.'","'.$unitFolderId.'", "'.$docTypeId.'")');
	
	echo "Unit ".$unitName." created in database<br/>";
} else {
	echo "Unit ".$unitName." present in database<br/>";
	$unitId = $row["id"];
	$rs = dmsQuery('select * from '.$dmsDbName.'.folders where name = "'.$unitName.'" and parent_id="'.$rootFolderId.'"');
	$row = $dmsLink->fetchByAssoc($rs);
	$unitFolderId  = $row["id"];
}

$unitFolder = $rootFolder."/".$unitName;
if (is_dir($unitFolder)) {
	echo "Unit ".$unitName." present in filesystem on ".$unitFolder."<br/>";
} else {
	if (!mkdir($unitFolder, 0755)) {
		die("unable to create folder ".$unitFolder);
	}
	echo "Unit ".$unitName." created in filesystem on ".$unitFolder."<br/>";
}
if (!is_writable($unitFolder)) {
	die("directory ".$unitFolder." not writable by webserver user, please check your KnowledgeTree installation");
}
echo "success<br/>";


echo "checking KnowledgeTree Groups ...<br/>";
$rs = dmsQuery('select * from '.$dmsDbName.'.groups_lookup where name="'.$dmsGroup.'"');
$row = $dmsLink->fetchByAssoc($rs);
if (!$row) {
	echo "creating Group ".$dmsGroup."<br/>";

	$groupId = getNewId("groups_lookup");
	dmsQuery('insert into '.$dmsDbName.'.groups_lookup(id, name, is_sys_admin, is_unit_admin) values("'.$groupId.'", "'.$dmsGroup.'", 0, 1)');
	
	$newId = getNewId("groups_units_link");
	dmsQuery('insert into '.$dmsDbName.'.groups_units_link(id, group_id, unit_id) values("'.$newId.'","'.$groupId.'", "'.$unitId.'")');

	$newId = getNewId("groups_folders_link");
	dmsQuery('insert into '.$dmsDbName.'.groups_folders_link(id, group_id, folder_id, can_read, can_write) values("'.$newId.'","'.$groupId.'", "'.$unitFolderId.'", "1", "1")');

	echo "Group ".$dmsGroup." created<br/>";
} else {
	echo "Group ".$dmsGroup." present in database<br/>";
	$groupId = $row["id"];
}

echo "checking KnowledgeTree SysAdmin Units ...<br/>";
$rs = dmsQuery('select * from '.$dmsDbName.'.groups_lookup where name="System Administrators"');
$row = $dmsLink->fetchByAssoc($rs);
$sysGroupId = $row["id"];

$rs = dmsQuery('select * from '.$dmsDbName.'.groups_units_link where group_id = "'.$sysGroupId.'"');
$row = $dmsLink->fetchByAssoc($rs);
if ($row && $row["unit_id"] != $unitId) {
	dmsQuery('update '.$dmsDbName.'.groups_units_link set unit_id = "'.$unitId.'" where group_id = "'.$sysGroupId.'"');
	echo "updated KnowledgeTree SysAdmin Units<br/>";
} else {
	$newId = getNewId("groups_units_link");
	dmsQuery('insert '.$dmsDbName.'.into groups_units_link(id, group_id, unit_id) values("'.$newId.'","'.$sysGroupId.'", "'.$unitId.'")');
	echo "inserted KnowledgeTree SysAdmin Units<br/>";
}
echo "success<br/>";

echo "<h4>Your KnowledgeTree Installation is configured correctly</h4><br/>";
echo "<h4>Please edit modules/ZuckerDocs/Menu.php, remove the Installation menu item and make modules/ZuckerDocs/Install.php unreadable to prevent misconfiguration.</h4>";
?>