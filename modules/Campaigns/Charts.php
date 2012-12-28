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
 * $Id: Charts.php,v 1.11 2006/06/06 17:57:56 majed Exp $
 * Description:  Includes the functions for Customer module specific charts.
 * ****************************************************************************** */

require_once('config.php');

require_once('modules/Campaigns/Campaign.php');
require_once('include/charts/Charts.php');
require_once('include/utils.php');

class charts {

    /**
     * Creates opportunity pipeline image as a VERTICAL accumlated bar graph for multiple users.
     * param $datax- the month data to display in the x-axis
     * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
     * All Rights Reserved..
     * Contributor(s): ______________________________________..
     */
    function campaign_response_by_activity_type($datay = array(), $targets = array(), $campaign_id, $cache_file_name = 'a_file', $refresh = false) {
        global $app_strings, $current_module_strings, $charset, $lang, $barChartColors, $app_list_strings;

        if (!file_exists($cache_file_name) || $refresh == true) {
            $GLOBALS['log']->debug("datay is:");
            $GLOBALS['log']->debug($datay);
            $GLOBALS['log']->debug("user_id is: ");
            $GLOBALS['log']->debug("cache_file_name is: $cache_file_name");

            $focus = new Campaign();

            $query = "SELECT activity_type,target_type, count(*) hits ";
            $query.= " FROM campaign_log ";
            $query.= " WHERE campaign_id = '$campaign_id' AND archived=0 AND deleted=0";
            $query.= " GROUP BY  activity_type, target_type";
            $query.= " ORDER BY  activity_type, target_type";

            $result = $focus->db->query($query);

            $leadSourceArr = array();
            $total = 0;
            $total_targeted = 0;
            while ($row = $focus->db->fetchByAssoc($result, -1, false)) {
                if (!isset($leadSourceArr[$row['activity_type']]['row_total'])) {
                    $leadSourceArr[$row['activity_type']]['row_total'] = 0;
                }

                $leadSourceArr[$row['activity_type']][$row['target_type']]['hits'][] = $row['hits'];
                $leadSourceArr[$row['activity_type']][$row['target_type']]['total'][] = $row['hits'];
                $leadSourceArr[$row['activity_type']]['outcome'][$row['target_type']] = $row['target_type'];
                $leadSourceArr[$row['activity_type']]['row_total'] += $row['hits'];

                if (!isset($leadSourceArr['all_activities'][$row['target_type']])) {
                    $leadSourceArr['all_activities'][$row['target_type']] = array('total' => 0);
                }

                $leadSourceArr['all_activities'][$row['target_type']]['total'] += $row['hits'];

                $total += $row['hits'];
                if ($row['activity_type'] == 'targeted') {
                    $targeted[$row['target_type']] = $row['hits'];
                    $total_targeted+=$row['hits'];
                }
            }
            $fileContents = '     <yData defaultAltText="' . 'Rollover a bar to view details.' . '">' . "\n";
            foreach ($datay as $key => $translation) {
                if ($key == '') {
                    $key = $current_module_strings['NTC_NO_LEGENDS'];
                    $translation = $current_module_strings['NTC_NO_LEGENDS'];
                }
                if (!isset($leadSourceArr[$key])) {
                    $leadSourceArr[$key] = $key;
                }
                if (isset($leadSourceArr[$key]['row_total'])) {
                    $rowTotalArr[] = $leadSourceArr[$key]['row_total'];
                }
                if (isset($leadSourceArr[$key]['row_total']) && $leadSourceArr[$key]['row_total'] > 100) {
                    $leadSourceArr[$key]['row_total'] = round($leadSourceArr[$key]['row_total']);
                }
                $fileContents .= '          <dataRow title="' . $translation . '" endLabel="' . $leadSourceArr[$key]['row_total'] . '">' . "\n";

                if (is_array($leadSourceArr[$key]['outcome'])) {
                    foreach ($leadSourceArr[$key]['outcome'] as $outcome => $outcome_translation) {
                        //create alternate text.
                        $alttext = $targets[$outcome] . ': Targeted ' . $targeted[$outcome] . ', Total Targeted ' . $total_targeted . ".";
                        if ($key != 'targeted') {
                            $alttext.=" $translation " . array_sum($leadSourceArr[$key][$outcome]['hits']);
                        }
                        $fileContents .= '               <bar id="' . $outcome . '" totalSize="' . array_sum($leadSourceArr[$key][$outcome]['total']) . '" altText="' . $alttext . '" url="#' . $key . '"/>' . "\n";
                    }
                }
                $fileContents .= '          </dataRow>' . "\n";
            }
            $fileContents .= '     </yData>' . "\n";
            $max = get_max($rowTotalArr);
            $fileContents .= '     <xData min="0" max="' . $max . '" length="10" prefix="' . '' . '" suffix=""/>' . "\n";
            $fileContents .= '     <colorLegend status="on">' . "\n";
            $i = 0;

            foreach ($targets as $outcome => $outcome_translation) {
                $color = generate_graphcolor($outcome, $i);
                $fileContents .= '          <mapping id="' . $outcome . '" name="' . $outcome_translation . '" color="' . $color . '"/>' . "\n";
                $i++;
            }
            $fileContents .= '     </colorLegend>' . "\n";
            $fileContents .= '     <graphInfo>' . "\n";
            $fileContents .= '          <![CDATA[' . ' ' . ']]>' . "\n";
            $fileContents .= '     </graphInfo>' . "\n";
            $fileContents .= '     <chartColors ';
            foreach ($barChartColors as $key => $value) {
                $fileContents .= ' ' . $key . '=' . '"' . $value . '" ';
            }
            $fileContents .= ' />' . "\n";
            $fileContents .= '</graphData>' . "\n";
            $total = round($total, 2);
            $title = '<graphData title="' . 'Campaign Response by Recipient Activity' . '">' . "\n";
            $fileContents = $title . $fileContents;

            save_xml_file($cache_file_name, $fileContents);
        }
        $return = create_chart('hBarF', $cache_file_name);
        return $return;
    }

    function campaign_response_by_call_status_and_tot_lead($datay = array(), $campaign_id, $cache_file_name = 'a_file', $refresh = false) {
        global $app_strings, $current_module_strings, $charset, $lang, $barChartColors, $app_list_strings;

        if (!file_exists($cache_file_name) || $refresh == true) {
            $GLOBALS['log']->debug("datay is:");
            $GLOBALS['log']->debug($datay);
            $GLOBALS['log']->debug("user_id is: ");
            $GLOBALS['log']->debug("cache_file_name is: $cache_file_name");

            $focus = new Campaign();

            $dataxArr = array();
            $count = count($datay);
            if ($count > 0) {
                foreach ($datay as $key => $value) {
                    $dataxArr[] = "'" . $key . "'";
                }
                $dataxArr = join(",", $dataxArr);
                //$where .= " AND status IN ($dataxArr) ";
            }

         $query="
                select 
                    status ,count(*) hits,calls.campaign_id,a.assigned_team_id_c as vendor_id ,b.name
                from 
                    calls, calls_cstm a ,teams b
                where 
                    a.id_c=calls.id and a.assigned_team_id_c=b.id and
                    calls.campaign_id = '$campaign_id' and 
                    status IN ($dataxArr) and 
                    calls.deleted=0 and b.deleted=0
                    group by status,assigned_team_id_c,calls.campaign_id";

            
            $result = $focus->db->query($query);

            $campaign_callSourceArr = array();
            $total = 0;
            $total_targeted = 0;
            while ($row = $focus->db->fetchByAssoc($result, -1, false)) {

                if (!isset($campaign_callSourceArr[$row['status']]['row_total'])) {
                    $campaign_callSourceArr[$row['status']]['row_total'] = 0;
                }
                
                $campaign_callSourceArr[$row['status']][$row['vendor_id']]['hits'][] = $row['hits'];
                $campaign_callSourceArr[$row['status']][$row['vendor_id']]['total'][] = $row['hits'];
                $campaign_callSourceArr[$row['status']]['name'][$row['vendor_id']] = $row['name'];

                $campaign_callSourceArr[$row['status']]['row_total'] += $row['hits'];
                $usernameArr[$row['vendor_id']] = $row['name'];
                $total += $row['hits'];
            }
            $fileContents = '     <yData defaultAltText="' . 'Total Targeted ' . $total . '">' . "\n";
            foreach ($datay as $key => $translation) {
                if ($key == '') {
                    $key = $current_module_strings['NTC_NO_LEGENDS'];
                    $translation = $current_module_strings['NTC_NO_LEGENDS'];
                }
                if (!isset($campaign_callSourceArr[$key])) {
                    $campaign_callSourceArr[$key] = $key;
                }
                if (isset($campaign_callSourceArr[$key]['row_total'])) {
                    $rowTotalArr[] = $campaign_callSourceArr[$key]['row_total'];
                }

                if (isset($campaign_callSourceArr[$key]['row_total']) && $campaign_callSourceArr[$key]['row_total'] > 100) {
                    $campaign_callSourceArr[$key]['row_total'] = round($campaign_callSourceArr[$key]['row_total']);
                }
                $fileContents .= '          <dataRow title="' . $translation . '" endLabel="' . $campaign_callSourceArr[$key]['row_total'] . '">' . "\n";

                if (isset($campaign_callSourceArr[$key]['name'])) {
//                    asort($campaign_callSourceArr[$key]['name']);
//                    reset($campaign_callSourceArr[$key]['name']);
//                    
                    foreach ($campaign_callSourceArr[$key]['name'] as $nameKey => $nameValue) {
                        $color = generate_graphcolor($key, $i);
                        $alttext =' Total Targeted ' . $total . ".";
                        if ($key != 'targeted') {
                            $alttext.=" $translation " . array_sum($campaign_callSourceArr[$key][$nameKey]['hits']);
                        }
                        $fileContents .= '<bar id="' . $nameKey . '" totalSize="' . array_sum($campaign_callSourceArr[$key][$nameKey]['total']) . '" altText="' . $alttext . '" url="#' . $key . '" color="' . $color . '"/>' . "\n";
                    }
                }

                $fileContents .= '          </dataRow>' . "\n";
            }
            $fileContents .= '     </yData>' . "\n";

            $max = get_max($rowTotalArr);
            $fileContents .= '     <xData min="0" max="' . $max . '" length="10" prefix="' . '' . '" suffix=""/>' . "\n";
            $fileContents .= '     <colorLegend status="on">' . "\n";
            $i = 0;

            //foreach ($datay as $outcome => $outcome_translation) {
            foreach ($usernameArr as $outcome => $outcome_translation) {
                $color = generate_graphcolor($outcome, $i);
                $fileContents .= '          <mapping id="' . $outcome . '" name="' . $outcome_translation . '" color="' . $color . '"/>' . "\n";
                //$fileContents .= '<mapping id="46aa542a-4c37-eaf9-f472-50780ef3ecd8" name="' . $outcome_translation . '" color="' . $color . '"/>' . "\n";
                $i++;
            }
            $fileContents .= '     </colorLegend>' . "\n";
            $fileContents .= '     <graphInfo>' . "\n";
            $fileContents .= '          <![CDATA[' . ' ' . ']]>' . "\n";
            $fileContents .= '     </graphInfo>' . "\n";
            $fileContents .= '     <chartColors ';
            foreach ($barChartColors as $key => $value) {
                $fileContents .= ' ' . $key . '=' . '"' . $value . '" ';
            }
            $fileContents .= ' />' . "\n";
            $fileContents .= '</graphData>' . "\n";
            $total = round($total, 2);
            $title = '<graphData title="' . 'Campaign Response by Vendors' . '">' . "\n";
            $fileContents = $title . $fileContents;

            save_xml_file($cache_file_name, $fileContents);
        }
        $return = create_chart('hBarF', $cache_file_name);
        return $return;
    }

   function campaign_response_by_call_status_and_vendor($datay = array(), $vendor_id, $cache_file_name = 'a_file', $refresh = false,$campaign_id) {
        global $app_strings, $current_module_strings, $charset, $lang, $barChartColors, $app_list_strings;

        if (!file_exists($cache_file_name) || $refresh == true) {
            $GLOBALS['log']->debug("datay is:");
            $GLOBALS['log']->debug($datay);
            $GLOBALS['log']->debug("user_id is: ");
            $GLOBALS['log']->debug("cache_file_name is: $cache_file_name");

            $focus = new Campaign();

            $dataxArr = array();
            $count = count($datay);
            if ($count > 0) {
                foreach ($datay as $key => $value) {
                    $dataxArr[] = "'" . $key . "'";
                }
                $dataxArr = join(",", $dataxArr);
                //$where .= " AND status IN ($dataxArr) ";
            }

            //echo $query = "select status ,count(*) hits,campaign_id from calls where campaign_id = '$campaign_id' and status IN ($dataxArr) and deleted=0 group by status";
            $sql="select name from teams where id='$vendor_id' and deleted=0";
            $data= $focus->db->fetchByAssoc($focus->db->query($sql), -1, false);
            $vendor_name=$data['name'];
            
            $query="
                select 
                    status ,count(*) hits,calls.campaign_id,a.assigned_team_id_c as vendor_id ,b.name
                from 
                    calls, calls_cstm a ,teams b
                where 
                    a.id_c=calls.id and a.assigned_team_id_c=b.id and
                    calls.campaign_id = '$campaign_id' and 
                    a.assigned_team_id_c='$vendor_id' and
                    status IN ($dataxArr) and 
                    calls.deleted=0 and b.deleted=0
                    group by status,assigned_team_id_c,calls.campaign_id";

            
            $result = $focus->db->query($query);

            $campaign_callSourceArr = array();
            $total = 0;
            $total_targeted = 0;
            while ($row = $focus->db->fetchByAssoc($result, -1, false)) {

                if (!isset($campaign_callSourceArr[$row['status']]['row_total'])) {
                    $campaign_callSourceArr[$row['status']]['row_total'] = 0;
                }
                
                $campaign_callSourceArr[$row['status']][$row['vendor_id']]['hits'][] = $row['hits'];
                $campaign_callSourceArr[$row['status']][$row['vendor_id']]['total'][] = $row['hits'];
                $campaign_callSourceArr[$row['status']]['name'][$row['vendor_id']] = $row['name'];

                $campaign_callSourceArr[$row['status']]['row_total'] += $row['hits'];
                $usernameArr[$row['vendor_id']] = $row['name'];
                $total += $row['hits'];
            }
            $fileContents = '     <yData defaultAltText="' . 'Total Targeted ' . $total . '">' . "\n";
            foreach ($datay as $key => $translation) {
                if ($key == '') {
                    $key = $current_module_strings['NTC_NO_LEGENDS'];
                    $translation = $current_module_strings['NTC_NO_LEGENDS'];
                }
                if (!isset($campaign_callSourceArr[$key])) {
                    $campaign_callSourceArr[$key] = $key;
                }
                if (isset($campaign_callSourceArr[$key]['row_total'])) {
                    $rowTotalArr[] = $campaign_callSourceArr[$key]['row_total'];
                }

                if (isset($campaign_callSourceArr[$key]['row_total']) && $campaign_callSourceArr[$key]['row_total'] > 100) {
                    $campaign_callSourceArr[$key]['row_total'] = round($campaign_callSourceArr[$key]['row_total']);
                }
                $fileContents .= '          <dataRow title="' . $translation . '" endLabel="' . $campaign_callSourceArr[$key]['row_total'] . '">' . "\n";

                if (isset($campaign_callSourceArr[$key]['name'])) {
//                    asort($campaign_callSourceArr[$key]['name']);
//                    reset($campaign_callSourceArr[$key]['name']);
//                    
                    foreach ($campaign_callSourceArr[$key]['name'] as $nameKey => $nameValue) {
                        $color = generate_graphcolor($key, $i);
                        $alttext =' Total Targeted ' . $total . ".";
                        if ($key != 'targeted') {
                            $alttext.=" $translation " . array_sum($campaign_callSourceArr[$key][$nameKey]['hits']);
                        }
                        $fileContents .= '<bar id="' . $key . '" totalSize="' . array_sum($campaign_callSourceArr[$key][$nameKey]['total']) . '" altText="' . $alttext . '" url="#' . $key . '" color="' . $color . '"/>' . "\n";
                    }
                }

                $fileContents .= '          </dataRow>' . "\n";
            }
            $fileContents .= '     </yData>' . "\n";

            $max = get_max($rowTotalArr);
            $fileContents .= '     <xData min="0" max="' . $max . '" length="10" prefix="' . '' . '" suffix=""/>' . "\n";
            $fileContents .= '     <colorLegend status="on">' . "\n";
            $i = 0;

            //foreach ($datay as $outcome => $outcome_translation) {
            foreach ($datay as $outcome => $outcome_translation) {
                $color = generate_graphcolor($outcome, $i);
                $fileContents .= '          <mapping id="' . $outcome . '" name="' . $outcome_translation . '" color="' . $color . '"/>' . "\n";
                //$fileContents .= '<mapping id="46aa542a-4c37-eaf9-f472-50780ef3ecd8" name="' . $outcome_translation . '" color="' . $color . '"/>' . "\n";
                $i++;
            }
            $fileContents .= '     </colorLegend>' . "\n";
            $fileContents .= '     <graphInfo>' . "\n";
            $fileContents .= '          <![CDATA[' . ' ' . ']]>' . "\n";
            $fileContents .= '     </graphInfo>' . "\n";
            $fileContents .= '     <chartColors ';
            foreach ($barChartColors as $key => $value) {
                $fileContents .= ' ' . $key . '=' . '"' . $value . '" ';
            }
            $fileContents .= ' />' . "\n";
            $fileContents .= '</graphData>' . "\n";
            $total = round($total, 2);
            $title = '<graphData title="' . 'Campaign Call Response From Vendor: '.$vendor_name . '">' . "\n";
            $fileContents = $title . $fileContents;

            save_xml_file($cache_file_name, $fileContents);
        }
        $return = create_chart('hBarF', $cache_file_name);
        return $return;
    }

}

// end charts class
?>
