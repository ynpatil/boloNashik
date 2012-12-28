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
global $app_strings;
global $app_list_strings;
global $theme;
require_once('XTemplate/xtpl.php');
$error_msg = '';
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');
global $current_language;
$mod_strings = return_module_language($current_language, 'Contacts');
echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME'].": ".$mod_strings['LBL_SAVE_CONTACT'], true);
echo "\n</p>\n";
$xtpl=new XTemplate ('modules/Contacts/ShowDuplicates.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "index.php?".$GLOBALS['request_string']);

$xtpl->assign("MODULE", $_REQUEST['module']);
if ($error_msg != '')
{
	$xtpl->assign("ERROR", $error_msg);
	$xtpl->parse("main.error");
}

if(isset($_REQUEST['popup']) && $_REQUEST['popup'] == 'true') insert_popup_header($theme);

require_once('modules/Contacts/Contact.php');
$contact = new Contact();
require_once('modules/Contacts/ContactFormBase.php');
$contactForm = new ContactFormBase();
$GLOBALS['check_notify'] = FALSE;


$query = 'select id,first_name, last_name, title, email1, email2  from contacts where deleted=0 ';
$duplicates = $_GET['duplicate']; 
$count = count($duplicates);
if ($count > 0)
{
	$query .= "and (";
	$first = true; 
	foreach ($duplicates as $duplicate_id) 
	{
		if (!$first) $query .= ' OR ';
		$first = false;
		$query .= "id='$duplicate_id' ";
	}
	$query .= ')';
}

$duplicateContacts = array();

$db = & PearDatabase::getInstance();
$result = $db->query($query);
$i=0;
while (($row=$db->fetchByAssoc($result)) != null) {
	$duplicateContacts[$i] = $row;
	$i++;
}
//echo $contactForm->buildTableForm($duplicateContacts,  'Contacts');
$xtpl->assign('FORMBODY', $contactForm->buildTableForm($duplicateContacts,  'Contacts'));

$input = '';
foreach ($contact->column_fields as $field)
{	
	if (!empty($_GET['Contacts'.$field])) {
		$input .= "<input type='hidden' name='$field' value='${_GET['Contacts'.$field]}'>\n";
	}
}
foreach ($contact->additional_column_fields as $field)
{	
	if (!empty($_GET['Contacts'.$field])) {
		$input .= "<input type='hidden' name='$field' value='${_GET['Contacts'.$field]}'>\n";
	}
}
$get = '';
if(!empty($_GET['return_module'])) $xtpl->assign('RETURN_MODULE', $_GET['return_module']);
else $get .= "Contacts";
$get .= "&return_action=";
if(!empty($_GET['return_action'])) $xtpl->assign('RETURN_ACTION', $_GET['return_action']);
else $get .= "DetailView";

///////////////////////////////////////////////////////////////////////////////
////	INBOUND EMAIL WORKFLOW
if(isset($_REQUEST['inbound_email_id'])) {
	$xtpl->assign('INBOUND_EMAIL_ID', $_REQUEST['inbound_email_id']);
	$xtpl->assign('RETURN_MODULE', 'Emails');	
	$xtpl->assign('RETURN_ACTION', 'EditView');
	if(isset($_REQUEST['start'])) {
		$xtpl->assign('START', $_REQUEST['start']);
	}
		
}
////	END INBOUND EMAIL WORKFLOW
///////////////////////////////////////////////////////////////////////////////



if(!empty($_GET['popup'])) 
	$input .= '<input type="hidden" name="popup" value="'.$_GET['popup'].'">';
else 
	$input .= '<input type="hidden" name="popup" value="false">';

if(!empty($_GET['to_pdf'])) 
	$input .= '<input type="hidden" name="to_pdf" value="'.$_GET['to_pdf'].'">';
else 
	$input .= '<input type="hidden" name="to_pdf" value="false">';
	
if(!empty($_GET['create'])) 
	$input .= '<input type="hidden" name="create" value="'.$_GET['create'].'">';
else 
	$input .= '<input type="hidden" name="create" value="false">';

if(!empty($_GET['return_id'])) $xtpl->assign('RETURN_ID', $_GET['return_id']);

$xtpl->assign('INPUT_FIELDS',$input);
$xtpl->parse('main');
$xtpl->out('main');

?>
