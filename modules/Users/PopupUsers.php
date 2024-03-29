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
 * $Id: PopupUsers.php,v 1.9 2006/06/06 17:58:53 majed Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

global $theme;
global $app_strings;
global $app_list_strings;
global $mod_strings;
global $urlPrefix;
global $currentModule;

require_once('modules/Users/User.php');
require_once('themes/'.$theme.'/layout_utils.php');

require_once('XTemplate/xtpl.php');
require_once('include/utils.php');
require_once('include/ListView/ListView.php');

$current_module_strings = return_module_language($current_language, 'Users');
$seed_object = new User();

$where = "";
if(isset($_REQUEST['query']))
{
	$search_fields = Array("first_name", "last_name", "user_name");

	$where_clauses = Array();

	append_where_clause($where_clauses, "first_name", "users.first_name");
	append_where_clause($where_clauses, "last_name", "users.last_name");
	append_where_clause($where_clauses, "user_name", "users.user_name");

	$where = generate_where_statement($where_clauses);
}

$image_path = 'themes/'.$theme.'/images/';

////////////////////////////////////////////////////////
// Start the output
////////////////////////////////////////////////////////

$from_form = empty($_REQUEST['form']) ? '' : $_REQUEST['form'];
$form_submit = !empty($_REQUEST['form_submit']) && $_REQUEST['form_submit'] != 'false' ? true : false;
$parent_id = empty($_REQUEST['parent_id']) ? 'parent_id' : $_REQUEST['parent_id'];
$parent_name = empty($_REQUEST['parent_name']) ? 'parent_name' : $_REQUEST['parent_name'];

$button  = "<form action='index.php' method='post' name='form' id='form'>\n";
$button .= "<input type='hidden' name='record' value='". $_REQUEST['record'] ."'>\n";
$button .= "<input type='hidden' name='module' value='Roles'>\n";
$button .= "<input type='hidden' name='action' value='SaveUserRelationship'>\n";
$button .= "<input type='submit' name='button' class='button' title='".$current_module_strings['LBL_SELECT_CHECKED_BUTTON_TITLE']."' value='  ".$current_module_strings['LBL_SELECT_CHECKED_BUTTON_LABEL']."  ' />\n";
$button .= "<input type='submit' name='button' class='button' title='".$app_strings['LBL_DONE_BUTTON_TITLE']."' accesskey='".$app_strings['LBL_DONE_BUTTON_KEY']."' onclick=\"window.close();\" value='  ".$app_strings['LBL_DONE_BUTTON_LABEL']."  ' />\n";

$form =new XTemplate ('modules/Users/Popup_Users_picker.html');
$GLOBALS['log']->debug("using file modules/Users/Popup_Users_picker.html");
$form->assign("MOD", $mod_strings);
$form->assign("APP", $app_strings);
$form->assign("THEME", $theme);
$form->assign("IMAGE_PATH", $image_path);
$form->assign("MODULE_NAME", $currentModule);
$form->assign("parent_id", $parent_id);
$form->assign("parent_name", $parent_name);
if (isset($_REQUEST['form_submit'])) $form->assign("FORM_SUBMIT", $_REQUEST['form_submit']);
$form->assign("FORM", $from_form);
$form->assign("RECORD_VALUE", $_REQUEST['record']);

if (isset($_REQUEST['first_name'])) $last_search['FIRST_NAME'] = $_REQUEST['first_name'];
if (isset($_REQUEST['last_name'])) $last_search['LAST_NAME'] = $_REQUEST['last_name'];
if (isset($_REQUEST['user_name'])) $last_search['USER_NAME'] = $_REQUEST['user_name'];

insert_popup_header($theme);

// Quick search.
echo "<form>";
echo get_form_header($mod_strings['LBL_SEARCH_FORM_TITLE'], "", false);

$form->parse("main.SearchHeader");
$form->out("main.SearchHeader");

echo get_form_footer();

$form->parse("main.SearchHeaderEnd");
$form->out("main.SearchHeaderEnd");

// Reset the sections that are already in the page so that they do not print again later.
$form->reset("main.SearchHeader");
$form->reset("main.SearchHeaderEnd");

$ListView = new ListView();
$ListView->setXTemplate($form);
$ListView->setHeaderTitle($current_module_strings['LBL_LIST_FORM_TITLE']);
$ListView->setHeaderText($button);
$ListView->setQuery($where, "", "user_name", "USER");
$ListView->setModStrings($current_module_strings);
$ListView->processListViewMulti($seed_object, "main", "USER");

echo get_form_footer();
insert_popup_footer();
?>
