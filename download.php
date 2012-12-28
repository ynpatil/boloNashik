<?php
if(!defined('sugarEntry'))define('sugarEntry', true);
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
require_once('include/entryPoint.php');
global $sugar_config;
global $locale;

session_cache_limiter('public');
session_start();

if(empty($_REQUEST['id']) || empty($_REQUEST['type']) || !isset($_SESSION['authenticated_user_id'])) {
	die("Not a Valid Entry Point");
} else {
	// cn: bug 8753: current_user's preferred export charset not being honored
	$current_user->retrieve($_SESSION['authenticated_user_id']);
	$current_language = $_SESSION['authenticated_user_language'];
	$app_strings = return_application_language($current_language);
	$local_location = $sugar_config['upload_dir']."/".$_REQUEST['id'];

	if(!file_exists( $local_location )) {
		die($app_strings['ERR_INVALID_FILE_REFERENCE']);
	} elseif(strpos($local_location, "../") || strpos($local_location, "..\\") ) {
		die($app_strings['ERR_INVALID_FILE_REFERENCE']);
	} else {
		if(strtolower($_REQUEST['type']) == 'documents') {
			// cn: bug 9674 document_revisions table has no 'name' column.
			$query = "SELECT filename name FROM document_revisions WHERE id = '" . $_REQUEST['id'] ."'";
		} elseif(strtolower($_REQUEST['type']) == 'notes') {
			$query = "SELECT filename name FROM notes WHERE id = '" . $_REQUEST['id'] ."'";
		}

		$rs = $db->query($query);
		$row = $db->fetchByAssoc($rs);

		if(empty($row)){
			die($app_strings['ERR_INVALID_FILE_REFERENCE']);
		}

		// cn: leave name charset translation to the browsers - they will handle it better than 2nd guessing.
		$emailStrings = return_module_language($current_language, 'Emails');
		$name = urldecode(str_replace($emailStrings['LBL_EMAIL_ATTACHMENT'].': ', '', $row['name']));

		/*
		if(isset($_SERVER['HTTP_USER_AGENT']) && preg_match("/MSIE/", $_SERVER['HTTP_USER_AGENT'])) {
			// cn: bug 7870 IE cannot handle MBCS in filenames gracefully. set $name var to filename
			$name = str_replace("+", "_", $row['name']);
			$name = $locale->translateCharset($name, 'UTF-8', $locale->getOutboundEmailCharset());
		} else {
			// ff 1.5+
			$name = mb_encode_mimeheader($name, $locale->getOutboundEmailCharset(), 'Q');
		}
		*/
		$download_location = $sugar_config['upload_dir']."/".$_REQUEST['id'];

		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-type: application/force-download");
		header("Content-Length: " . filesize($local_location));
		header("Content-disposition: attachment; filename=\"".$name."\";");
//		header("Pragma: ");
		header("Expires: 0");
		set_time_limit(0);

		@ob_end_clean();
		ob_start();
		if(filesize($local_location) < 2097152) {
			readfile($download_location);
		} else {
			readfile_chunked($download_location);
		}
		@ob_flush();
	}
}
?>
