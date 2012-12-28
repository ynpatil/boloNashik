<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');
/* * *******************************************************************************
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
 * ****************************************************************************** */
/* * *******************************************************************************
 * Description:
 * Created On: Sep 29, 2005
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): Chris Nojima
 * ****************************************************************************** */
require_once('modules/Schedulers/Scheduler.php');
require_once('include/DetailView/DetailView.php');
require_once('XTemplate/xtpl.php');
include "modules/Schedulers/_AddJobsHere.php";
require_once('modules/SchedulersJobs/SchedulersJob.php');
global $mod_strings;
global $app_strings;
global $timedate;

/* start standard DetailView layout process */
$GLOBALS['log']->info("Schedulers DetailView");
$focus = new Scheduler();
$focus->checkCurl();
$detailView = new DetailView();
$job = new SchedulersJob();
$offset = 0;
if (isset($_REQUEST['offset']) or isset($_REQUEST['record'])) {
    $result = $detailView->processSugarBean("SCHEDULER", $focus, $offset);
    if ($result == null) {
        sugar_die($app_strings['ERROR_NO_RECORD']);
    }
    $focus = $result;
} else {
    header("Location: index.php?module=Schedulers&action=index");
}
if (isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
    $focus->id = "";
}
echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_TITLE'], $mod_strings['LBL_MODULE_TITLE'] . ": " . $focus->name, true);
echo "\n</p>\n";
global $theme;
$theme_path = "themes/" . $theme . "/";
$image_path = $theme_path . "images/";
/* end standard DetailView layout process */


$job_status=$job->fireSelf($focus->id);

$xtpl = new XTemplate('modules/Schedulers/TestJob.html');
// custom assigns


$focus->created_by_name = get_assigned_user_name($focus->created_by);
$focus->modified_by_name = get_assigned_user_name($focus->modified_user_id);

$xtpl->assign('RETURN_ID', $_REQUEST['record']);
$xtpl->assign('MOD', $mod_strings);
$xtpl->assign('APP', $app_strings);
$xtpl->assign('CREATED_BY', $focus->created_by_name);
$xtpl->assign('MODIFIED_BY', $focus->modified_by_name);
$xtpl->assign('THEME', $theme);
$xtpl->assign('GRIDLINE', $gridline);

$xtpl->parse('main');
$xtpl->out('main');

echo "<BR><B>JOB:   $JobFunctionName is Executed.<BR><br> STATUS : " . $job_status . "</b>";
?>
