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
require_once('modules/Tags/Forms.php');
require_once('include/DetailView/DetailView.php');

global $mod_strings;
global $app_strings;
global $theme;

$tag        = new Tag();
$detailView = new DetailView();
$offset     = 0;

if(isset($_REQUEST['offset']) || isset($_REQUEST['record'])) 
{
  $result = $detailView->processSugarBean("TAG", $tag, $offset);

  if($result == null) 
  {
    sugar_die($app_strings['ERROR_NO_RECORD']);
  }

  $tag=$result;
} 
else 
{
  header("Location: index.php?module=Tags&action=index");
}

if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') 
{
  $tag->id = "";
}

if(!defined('THEMEPATH'))
{
  define('THEMEPATH', $theme_path);
}

require_once(THEMEPATH.'layout_utils.php');

$xtpl=new XTemplate('modules/Tags/DetailView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("RETURN_MODULE", "Tags");
$xtpl->assign("RETURN_ACTION", "DetailView");
$xtpl->assign("ID", $tag->id);
$xtpl->assign("CREATED_BY_USER", get_assigned_user_name($tag->created_by));
$xtpl->assign("MODIFIED_BY_USER", get_assigned_user_name($tag->modified_user_id));
$xtpl->assign("DATE_ENTERED", $tag->date_entered);
$xtpl->assign("DATE_MODIFIED", $tag->date_modified);
$xtpl->assign("TITLE", $tag->title);

$xtpl->assign("THEME_MODULE_TITLE",  get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME'].": ".$tag->title, true));

$xtpl->assign("DESCRIPTION", nl2br($tag->description));

require_once('modules/DynamicFields/templates/Files/DetailView.php');

if($tag->ACLAccess('delete'))
{
  $xtpl->parse("main.can_delete");
}

if($tag->ACLAccess('edit'))
{
  $xtpl->parse("main.can_edit");
}

$xtpl->parse("main");
$xtpl->out("main");

require_once('include/SubPanel/SubPanelTiles.php');
$subpanel = new SubPanelTiles($focus, 'Tags');

echo $subpanel->display(/* showContainer */ false, 
                        /* forceTabless  */ true);

?>
