<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

/*******************************************************************************
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
 *
 * Contributor(s): George Neill <gneill@aiminstitute.org>, 
 *                 AIM Institute <http://www.aiminstitute.org>
 ******************************************************************************/

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Tags/Tag.php');

global $app_strings;
global $mod_strings;
global $theme;

$tag = new Tag();

if(!$tag->ACLAccess('edit'))
{
  ACLController::displayNoAccess(false);
  sugar_cleanup(true);
}

if(isset($_REQUEST['record']) || !empty($_REQUEST['record']))
{
  $tag->retrieve($_REQUEST['record']);
  $module_title = $tag->title;
}
else
{
  $module_title = $mod_strings['LNK_NEW_TAG'];
}

if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == '1') 
{
  $tag->id = "";
}

$theme_path="themes/".$theme."/";

if (!defined('THEMEPATH'))
{
  define('THEMEPATH', $theme_path);
}

require_once(THEMEPATH.'layout_utils.php');

$xtpl=new XTemplate ('modules/Tags/EditView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

$xtpl->assign("THEME_MODULE_TITLE",  get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME'].": ".$tag->title, true));

if(isset($_REQUEST['return_module']))
{
  $xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
}

if(isset($_REQUEST['return_action'])) 
{
  $xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
}

if(isset($_REQUEST['return_id']))
{
  $xtpl->assign("RETURN_ID", $_REQUEST['return_id']);
}

$xtpl->assign("ID", $tag->id);
$xtpl->assign("TITLE", $tag->title);
$xtpl->assign("DESCRIPTION", $tag->description);

require_once('modules/DynamicFields/templates/Files/EditView.php');

$xtpl->assign("THEME", $theme);
$xtpl->parse("main");
$xtpl->out("main");

?>
