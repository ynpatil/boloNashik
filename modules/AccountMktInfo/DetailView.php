<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * The detailed view for a AccountMktInfo
 *
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
 */
//om
// $Id: DetailView.php,v 1.44.4.1 2006/09/13 00:50:39 jenny Exp $

global $current_user, $sugar_version, $sugar_config, $image_path;

require_once('include/Sugar_Smarty.php');
require_once("modules/AccountMktInfo/AccountMktInfo.php");

// build dashlet cache file if not found
/*
if(!is_file('cache/dashlets/dashlets.php')) {
    require_once('include/Dashlets/DashletCacheBuilder.php');

    $dc = new DashletCacheBuilder();
    $dc->buildCache();
}
*/

require_once('modules/AccountMktInfo/Dashlets/dashlets.php');

$columns = $current_user->getPreference('columns', 'accountmktinfo');
$dashlets = $current_user->getPreference('dashlets', 'accountmktinfo');

// fill in with default dashlet selection
if(!isset($columns) || !isset($dashlets)) {
    $dashlets = array();

    $defaultDashlets = array('MktSizeDashlet','MktShareDashlet','CompInfoDashlet','SeasonInfoDashlet','IndustryInfoDashlet',
    'AnnualBusinessInfoDashlet');//,'CommObjDashlet','MktPriDashlet','LatestHappenDashlet');

    foreach($defaultDashlets as $dashletName) {
        $dashlets[create_guid()] = array('className' => $dashletName,
                                         'fileLocation' => $dashletsFiles[$dashletName]['file']);
	//echo "File :".$dashletsFiles[$dashletName]['file'];
    }

    $count = 0;
    $columns = array();
    $columns[0] = array();
    $columns[0]['width'] = '60%';
    $columns[0]['dashlets'] = array();
    $columns[1] = array();
    $columns[1]['width'] = '40%';
    $columns[1]['dashlets'] = array();
    foreach($dashlets as $guid=>$dashlet) {
        if($count % 2 == 0) array_push($columns[0]['dashlets'], $guid);
        else array_push($columns[1]['dashlets'], $guid);
        $count++;
    }
    $current_user->setPreference('dashlets', $dashlets, 0, 'accountmktinfo');
    $current_user->setPreference('columns', $columns, 0, 'accountmktinfo');
}

$count = 0;
//$columns = array(0 => array('dashlets' => array(), 'width' => '60%'), 1 => array('dashlets' => array(), 'width' => '40%'));
$dashletIds = array(); // collect ids to pass to javascript
$display = array();

foreach($columns as $colNum => $column) {
    $display[$colNum]['width'] = $column['width'];
    $display[$colNum]['dashlets'] = array();
    foreach($column['dashlets'] as $num => $id) {
        if(!empty($id) && is_file($dashlets[$id]['fileLocation'])) {
            require_once($dashlets[$id]['fileLocation']);
            $dashlet = new $dashlets[$id]['className']($id,(isset($dashlets[$id]['options']) ? $dashlets[$id]['options'] : array()),$_REQUEST['record'],$_REQUEST['return_module']);

            array_push($dashletIds, $id);

            $dashlet->process();
            $display[$colNum]['dashlets'][$id]['display'] = $dashlet->display();
            if($dashlet->hasScript) {
                $display[$colNum]['dashlets'][$id]['script'] = $dashlet->displayScript();
            }
        }
    }
}

$sugar_smarty = new Sugar_Smarty();
if(!empty($sugar_config['lock_homepage']) && $sugar_config['lock_homepage'] == true) $sugar_smarty->assign('lock_homepage', true);
$sugar_smarty->assign('sugarVersion', $sugar_version);
$sugar_smarty->assign('sugarFlavor', $sugar_flavor);
$sugar_smarty->assign('currentLanguage', $GLOBALS['current_language']);
$sugar_smarty->assign('serverUniqueKey', $GLOBALS['server_unique_key']);
$sugar_smarty->assign('imagePath', $GLOBALS['image_path']);

$sugar_smarty->assign('jsCustomVersion', $sugar_config['js_custom_version']);
$sugar_smarty->assign('maxCount', empty($sugar_config['max_dashlets_homepage']) ? 15 : $sugar_config['max_dashlets_homepage']);
$sugar_smarty->assign('dashletCount', $count);
$sugar_smarty->assign('dashletIds', '["' . implode('","', $dashletIds) . '"]');
$sugar_smarty->assign('columns', $display);
$sugar_smarty->assign('lblAddDashlets', $mod_strings['LBL_ADD_DASHLETS']);
$sugar_smarty->assign('lblLnkHelp', $GLOBALS['app_strings']['LNK_HELP']);
$sugar_smarty->assign('parent_type',$_REQUEST['return_module']);
$sugar_smarty->assign('parent_desc',$_REQUEST['name']);
$sugar_smarty->assign('record',$_REQUEST['record']);
$sugar_smarty->assign('main_module', $mod_strings['LBL_MODULE_NAME']);

$focus = new AccountMktInfo();
$display_audit_link = $focus->is_AuditEnabled();

$html_text = "";

if($display_audit_link && (!isset($sugar_config['disc_client']) || $sugar_config['disc_client'] == false))
{
            //Audit link
            $popup_request_data = array(
		        'call_back_function' => 'set_return',
		        'form_name' => 'EditView',
		        'field_to_name_array' => array(),
		    );
            $json = getJSONobj();
            $encoded_popup_request_data = $json->encode($popup_request_data);
            $audit_link = "<a href='#' onclick='open_popup(\"Audit\", \"600\", \"400\", \"&record=".$_REQUEST['record']."&module_name=".$_REQUEST['module']."\", true, false, $encoded_popup_request_data);' class=\"listViewPaginationLinkS1\">".$app_strings['LNK_VIEW_CHANGE_LOG']."</a>";

		//$html_text .= "<tr>\n";
		//$html_text .= "<td COLSPAN=\"20\" style='padding: 0px;'>\n";
        //$html_text .= "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\"><tr><td style=\"text-align: left\" class='listViewPaginationTdS1'>&nbsp;".$audit_link."</td>\n";
		$html_text = $audit_link;
}

$sugar_smarty->assign('audit_link',$html_text);

echo $sugar_smarty->fetch('modules/AccountMktInfo/DetailView.tpl');

?>
