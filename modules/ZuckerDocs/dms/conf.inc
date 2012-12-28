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

define("ZUCKERDOCS_VERSION", "1.4.5");

global $dmsUseSugarMysqlServer, $dmsDbType, $dmsDbHost, $dmsDbName, $dmsDbUser, $dmsDbPwd, $dmsDbOptions;
global $dmsResolveFilenameConflicts, $dmsCreateUsers, $dmsGroup, $dmsDefaultPassword, $dmsRootDir;
global $htmlarea_base, $htmlarea_lang_map, $sugar_url_base;


//In case your ZuckerDocs database uses the same mysql server as your Sugar server, set this to true. You only have to
//fill out the "dmsDbName" below to point to the database on your mysql server
//It is very important to do this, as otherwise mysql connection pooling will cause heavy problems!!!
$dmsUseSugarMysqlServer = true;

// Change settings to your mysql database used for ZuckerDocs
$dmsDbType = "mysql";
$dmsDbHost = "localhost";
$dmsDbName = "dms";
$dmsDbUser = "sugarcrm45b";
$dmsDbPwd = "sugarcrm45b";
$dmsDbOptions = "";

//resolve filename conflicts automatically by prepending the id in case of duplicate filename
$dmsResolveFilenameConflicts = FALSE;

//if set to yes, the current document title is used as filename when downloading document
$dmsDownloadUseTitle = true;


/*******************************************************************************************/
/*			Don't change anything beyond this line, unless you know what you are doing!		*/
/*******************************************************************************************/

// when set to TRUE, the SugarCRM users will be created in KnowledgeTree DMS on-the-fly (recommended)
$dmsCreateUsers = TRUE;
// the name of the KnowledgeTree DMS usergroup for which to create the SugarCRM users
$dmsGroup = 'ZuckerDocs Group';
// the default password of the SugarCRM users created in KnowledgeTree DMS 
$dmsDefaultPassword = 'sugar';

$dmsQuietMode = false;
$dmsRootDir = "modules/ZuckerDocs/Documents/";

//base url for the HTMLArea files
$htmlarea_base = "modules/ZuckerDocs/htmlarea/";

//language map from sugar language string to HTMLArea languages
$htmlarea_lang_map = array(
	"en_us" => "en",
	"ge_ge" => "de",
	"fr_fr" => "fr",
);

if (!empty($site_URL)) {
	//Sugar 2.5.0x
	$sugar_url_base = $site_URL;
} else {
	//Sugar 2.5.1x, 3.0x
	$sugar_url_base = $sugar_config['site_url'];
}
?>