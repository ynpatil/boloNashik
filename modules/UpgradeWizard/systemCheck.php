<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version
 * 1.1.3 ("License"); You may not use this file except in compliance with the
 * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an "AS IS" basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied.  See the License
 * for the specific language governing rights and limitations under the
 * License.
 *
 * All copies of the Covered Code must include on each user interface screen:
 *    (i) the "Powered by SugarCRM" logo and
 *    (ii) the SugarCRM copyright notice
 * in the same form as they appear in the distribution.  See full license for
 * requirements.
 *
 * The Original Code is: SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) 2004-2006 SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/
/*********************************************************************************
 * $Id: systemCheck.php,v 1.19 2006/08/12 00:58:54 chris Exp $
 * Description:
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc. All Rights
 * Reserved. Contributor(s): ______________________________________..
 * *******************************************************************************/
logThis('[At systemCheck.php]');

$stop = false; // flag to prevent going to next step

///////////////////////////////////////////////////////////////////////////////
////	FILE CHECKS
logThis('Starting file permission check...');
$filesNotWritable = array();
$filesNWPerms = array();

// add directories here that should be skipped when doing file permissions checks (cache/upload is the nasty one)
$skipDirs = array(
	$sugar_config['upload_dir'], 
);
$files = uwFindAllFiles(getcwd(), array(), true, $skipDirs);

$i=0;
$filesOut = "
	<a href='javascript:void(0); toggleNwFiles(\"filesNw\");'>{$mod_strings['LBL_UW_SHOW_NW_FILES']}</a>
	<div id='filesNw' style='display:none;'>
	<table cellpadding='3' cellspacing='0' border='0'>
	<tr>
		<th align='left'>{$mod_strings['LBL_UW_FILE']}</th>
		<th align='left'>{$mod_strings['LBL_UW_FILE_PERMS']}</th>
		<th align='left'>{$mod_strings['LBL_UW_FILE_OWNER']}</th>
		<th align='left'>{$mod_strings['LBL_UW_FILE_GROUP']}</th>
	</tr>";

$isWindows = is_windows();
foreach($files as $file) {
	if($isWindows) {
		if(!is_writable_windows($file)) {
			logThis('WINDOWS: File ['.$file.'] not readable - saving for display');
			// don't warn yet - we're going to use this to check against replacement files
			$filesNotWritable[$i] = $file;
			$filesNWPerms[$i] = substr(sprintf('%o',fileperms($file)), -4);
			$filesOut .= "<tr>".
							"<td><span class='error'>{$file}</span></td>".
							"<td>{$filesNWPerms[$i]}</td>".
							"<td>".$mod_strings['ERR_UW_CANNOT_DETERMINE_USER']."</td>".
							"<td>".$mod_strings['ERR_UW_CANNOT_DETERMINE_GROUP']."</td>".
						  "</tr>";
		}		
	} else {
		if(!is_writable($file)) {
			logThis('File ['.$file.'] not writable - saving for display');
			// don't warn yet - we're going to use this to check against replacement files
			$filesNotWritable[$i] = $file;
			$filesNWPerms[$i] = substr(sprintf('%o',fileperms($file)), -4);
			$owner = posix_getpwuid(fileowner($file));
			$group = posix_getgrgid(filegroup($file));
			$filesOut .= "<tr>".
							"<td><span class='error'>{$file}</span></td>".
							"<td>{$filesNWPerms[$i]}</td>".
							"<td>".$owner['name']."</td>".
							"<td>".$group['name']."</td>".
						  "</tr>";
		}
	}
	$i++;
}

$filesOut .= '</table></div>';
// not a stop error
$errors['files']['filesNotWritable'] = (count($filesNotWritable) > 0) ? true : false;
if(count($filesNotWritable) < 1) {
	$filesOut = "<b>{$mod_strings['LBL_UW_FILE_NO_ERRORS']}</b>";
}

logThis('Finished file permission check.');
////	END FILE CHECKS
///////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
////	DATABASE CHECKS
logThis('Starting database permissions check...');
$dbOut = " 
	<a href='javascript:void(0); toggleNwFiles(\"dbPerms\");'>{$mod_strings['LBL_UW_SHOW_DB_PERMS']}</a>
	<div id='dbPerms' style='display:none;'>
	<table cellpadding='3' cellspacing='0' border='0'>
	<tr>
		<th align='left'>{$mod_strings['LBL_UW_DB_PERMS']}</th>
	</tr>";

$db =& PearDatabase::getInstance();
$outs = array();
$outs['skip'] = false;
$outs['db'] = array();
$outs['dbOut'] = $dbOut;
$outs = testPermsCreate($db->dbType, $outs);
$outs = testPermsInsert($db->dbType, $outs, $outs['skip']);
$outs = testPermsUpdate($db->dbType, $outs, $outs['skip']);
$outs = testPermsSelect($db->dbType, $outs, $outs['skip']);
$outs = testPermsDelete($db->dbType, $outs, $outs['skip']);
$outs = testPermsAlterTableAdd($db->dbType, $outs, $outs['skip']);
$outs = testPermsAlterTableChange($db->dbType, $outs, $outs['skip']);
$outs = testPermsAlterTableDrop($db->dbType, $outs, $outs['skip']);
$outs = testPermsDropTable($db->dbType, $outs, $outs['skip']);
$outs['dbOut'] .= '</table>';


if(count($outs['db']) < 1) {
	logThis('No permissions errors found!');
	$outs['dbOut'] = "<b>".$mod_strings['LBL_UW_DB_NO_ERRORS']."</b>";
}
logThis('Finished database permissions check.');
$dbOut = $outs['dbOut'];
////	END DATABASE CHECKS
///////////////////////////////////////////////////////////////////////////////


///////////////////////////////////////////////////////////////////////////////
////	INSTALLER TYPE CHECKS
$result = checkSystemCompliance();
$checks = array(
	'phpVersion'		=> $mod_strings['LBL_UW_COMPLIANCE_PHP_VERSION'],
	'mysqlVersion'		=> $mod_strings['LBL_UW_COMPLIANCE_MYSQL'],
	'mssqlStatus'		=> $mod_strings['LBL_UW_COMPLIANCE_MSSQL_MAGIC_QUOTES'],
	'xmlStatus'			=> $mod_strings['LBL_UW_COMPLIANCE_XML'],
	'curlStatus'		=> $mod_strings['LBL_UW_COMPLIANCE_CURL'],
	'imapStatus'		=> $mod_strings['LBL_UW_COMPLIANCE_IMAP'],
	'mbstringStatus'	=> $mod_strings['LBL_UW_COMPLIANCE_MBSTRING'],
	'safeModeStatus'	=> $mod_strings['LBL_UW_COMPLIANCE_SAFEMODE'],
	'callTimeStatus'	=> $mod_strings['LBL_UW_COMPLIANCE_CALLTIME'],
	'memory_msg'		=> $mod_strings['LBL_UW_COMPLIANCE_MEMORY'],
);
if($result['error_found'] == true) {
	$stop = true;
	$phpIniLocation = get_cfg_var("cfg_file_path");

	$sysCompliance  = "<a href='javascript:void(0); toggleNwFiles(\"sysComp\");'>{$mod_strings['LBL_UW_SHOW_COMPLIANCE']}</a>";
	$sysCompliance .= "<div id='sysComp' style='display:none;'>";
	$sysCompliance .= "<table cellpadding='0' cellspacing='0' border='0'>";
	foreach($result as $k => $v) {
		if($k == 'error_found')
			continue;
		$sysCompliance .= "<tr><td valign='top'>{$checks[$k]}</td>";
		$sysCompliance .= "<td valign='top'>{$v}</td></tr>";
	}
	$sysCompliance .= "<tr><td valign='top'>{$mod_strings['LBL_UW_COMPLIANCE_PHP_INI']}</td>";
	$sysCompliance .= "<td valign='top'><b>{$phpIniLocation}</b></td></tr>";
	$sysCompliance .= "</table></div>";
} else {
	$sysCompliance = "<b>{$mod_strings['LBL_UW_COMPLIANCE_ALL_OK']}</b>";
}

////	END INSTALLER CHECKS
///////////////////////////////////////////////////////////////////////////////

////	stop on all errors
foreach($errors as $k => $type) {
	if(is_array($type) && count($type) > 0) {
		foreach($type as $k => $subtype) {
			if($subtype == true) {
				$stop = true;
			}
		}
	}
	
	if($type === true) {
		logThis('Found errors during system check - disabling forward movement.');
		$stop = true;	
	}
}

$showBack		= true;
$showCancel		= true;
$showRecheck	= true;
$showNext		= ($stop) ? false : true;

$stepBack		= $_REQUEST['step'] - 1;
$stepNext		= $_REQUEST['step'] + 1;
$stepCancel		= -1;
$stepRecheck	= $_REQUEST['step'];

$_SESSION['step'][$steps['files'][$_REQUEST['step']]] = ($stop) ? 'failed' : 'success';


///////////////////////////////////////////////////////////////////////////////
////	OUTPUT

$uwMain =<<<eoq
<style>
.stop {
	color: #cc0000;
	}
.go {
	color: #00cc00;
	}

</style>
<table cellpadding="3" cellspacing="0" border="0">
	<tr>
		<th align="left" colspan="2">
			{$mod_strings['LBL_UW_FILE_ISSUES']}:
			<hr>
		</th>
	</tr>
	<tr>
		<td align="left" valign="top">
			{$mod_strings['LBL_UW_FILE_ISSUES_PERMS']}
		</td>
		<td>
			{$filesOut}
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<p>&nbsp;</p>
		</td>
	</tr>
	<tr>
		<th align="left" colspan="2">
			{$mod_strings['LBL_UW_DB_ISSUES']}:
			<hr>
		</th>
	</tr>
	<tr>
		<td align="left" valign="top">
			{$mod_strings['LBL_UW_DB_ISSUES_PERMS']}
		</td>
		<td>
			{$dbOut}
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<p>&nbsp;</p>
		</td>
	</tr>
	<tr>
		<th align="left" colspan="2">
			{$mod_strings['LBL_UW_COMPLIANCE_TITLE']}:
			<hr>
		</th>
	</tr>
	<tr>
		<td align="left" valign="top">
			{$mod_strings['LBL_UW_COMPLIANCE_TITLE2']}
		</td>
		<td>
			{$sysCompliance}
		</td>
	</tr>
</table>

eoq;

?>
