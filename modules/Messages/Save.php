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
 * $Id: Save.php,v 1.20 2006/06/06 17:57:58 majed Exp $
 * Description:  Base Form For Notes
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
require_once('modules/Messages/Message.php');

require_once('include/formbase.php');
require_once('include/upload_file.php');

global $mod_strings;
$mod_strings = return_module_language($current_language, 'Messages');

$prefix='';

$do_final_move = 0;

$Message = new Message();
if (isset($_REQUEST['record'])) {
	$Message->retrieve($_REQUEST['record']);
}

if(!$Message->ACLAccess('Save')){
		ACLController::displayNoAccess(true);
		sugar_cleanup(true);
}

$Message = populateFromPost($prefix, $Message);

if (!isset($_POST[$prefix.'is_template'])) $Message->is_template = 0;
else $Message->is_template = 1;

$upload_file = new UploadFile('uploadfile');

$do_final_move = 0;

//$_FILES['uploadfile']['name'] = $_REQUEST['escaped_message_name'];
if (isset($_FILES['uploadfile']) && $upload_file->confirm_upload())
{
    $Message->filename = $upload_file->get_stored_file_name();
    $Message->file_mime_type = $upload_file->mime_type;
	$Message->file_ext = $upload_file->file_ext;
 	$do_final_move = 1;
 	$GLOBALS['log']->debug("Uploaded file ".$Message->filename);
} else {

	$GLOBALS['log']->debug("Not uploading file");
}

if (isset($GLOBALS['check_notify'])) {
	$check_notify = $GLOBALS['check_notify'];
}
else {
	$check_notify = FALSE;
}

$return_id = $Message->save($check_notify);

if ($do_final_move) {
	$upload_file->final_move($Message->id);
}

$GLOBALS['log']->debug("Saved record with id of ".$return_id);
handleRedirect($return_id, "Messages");
?>
