<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
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

 // $Id: MyPipelineBySalesStageDashlet.php,v 1.6 2006/08/22 20:14:45 wayne Exp $

require_once('include/Dashlets/Dashlet.php');
require_once('include/Sugar_Smarty.php');
require_once('include/charts/Charts.php');
require_once('modules/Charts/code/Chart_pipeline_by_sales_stage.php');
require_once('modules/Dashboard/Forms.php');

class MyPipelineBySalesStageDashlet extends Dashlet {
    var $mypbss_date_start;
    var $mypbss_date_end;
    var $mypbss_sales_stages = null;
    var $refresh = false;
    
    function MyPipelineBySalesStageDashlet($id, $options) {
        global $timedate;
        parent::Dashlet($id);
        $this->isConfigurable = true;
        $this->isRefreshable = false;
        
        if(empty($options['mypbss_date_start'])) 
            $this->mypbss_date_start = date($timedate->get_date_format(), time());
        else
            $this->mypbss_date_start = $options['mypbss_date_start']; 
            
        if(empty($options['mypbss_date_end']))
            $this->mypbss_date_end = date($timedate->get_date_format(), strtotime('2010-01-01'));
        else
            $this->mypbss_date_end = $options['mypbss_date_end'];
            
        if(empty($options['mypbss_sales_stages']))
            $this->mypbss_sales_stages = array();
        else
            $this->mypbss_sales_stages = $options['mypbss_sales_stages'];

        if(empty($options['title'])) $this->title = translate('LBL_PIPELINE_FORM_TITLE', 'Home');
    }

    function saveOptions($req) {
        global $sugar_config, $timedate, $current_user, $theme;
        $options = array();
                
        $date_start = $this->mypbss_date_start;
        $date_end = $this->mypbss_date_end;
        $dateStartDisplay = strftime($timedate->get_user_date_format(), strtotime($date_start));
        $dateEndDisplay     = strftime($timedate->get_user_date_format(), strtotime($date_end));
        $seps               = array("-", "/");
        $dates              = array($dateStartDisplay, $dateEndDisplay);
        $dateFileNameSafe   = str_replace($seps, "_", $dates);
        if(is_file($sugar_config['tmp_dir'] . $current_user->getUserPrivGuid()."_".$theme."_my_pipeline_".$dateFileNameSafe[0]."_".$dateFileNameSafe[1].".xml"))
            unlink($sugar_config['tmp_dir'] . $current_user->getUserPrivGuid()."_".$theme."_my_pipeline_".$dateFileNameSafe[0]."_".$dateFileNameSafe[1].".xml");
            
        $options['mypbss_sales_stages'] = $_REQUEST['mypbss_sales_stages'];
        $options['mypbss_date_start'] = $_REQUEST['mypbss_date_start'];
        $options['mypbss_date_end'] = $_REQUEST['mypbss_date_end'];
        
        return $options;
    }

    function displayOptions() {
        global $timedate, $image_path, $app_strings, $current_user, $app_list_strings;
        
        $ss = new Sugar_Smarty();
        $ss->assign('id', $this->id);
        $ss->assign('LBL_DATE_START', translate('LBL_DATE_START', 'Charts'));
        $ss->assign('LBL_DATE_END', translate('LBL_DATE_END', 'Charts'));
        $ss->assign('LBL_SALES_STAGES', translate('LBL_SALES_STAGES', 'Charts'));
        $ss->assign('LBL_ENTER_DATE', translate('LBL_ENTER_DATE', 'Charts'));
        $ss->assign('LBL_SELECT_BUTTON_TITLE', $app_strings['LBL_SELECT_BUTTON_TITLE']);
        $ss->assign('image_path', $image_path);
        
        //get the dates to display
        $date_start = $this->mypbss_date_start;
        $date_end = $this->mypbss_date_end;
        
        $ss->assign('date_start', $date_start);
        $ss->assign('date_end', $date_end);
        
        $tempx = array();
        $datax = array();
        $selected_datax = array();
        //get list of sales stage keys to display
        $user_sales_stage = $this->mypbss_sales_stages;
        $tempx = $user_sales_stage;
        
        //set $datax using selected sales stage keys
        if (count($tempx) > 0) {
            foreach ($tempx as $key) {
                $datax[$key] = $app_list_strings['sales_stage_dom'][$key];
                array_push($selected_datax, $key);
            }
        }
        else {
            $datax = $app_list_strings['sales_stage_dom'];
            $selected_datax = array_keys($app_list_strings['sales_stage_dom']);
        }
        $ss->assign('selected_datax', get_select_options_with_id($app_list_strings['sales_stage_dom'], $selected_datax));
        $ss->assign('user_date_format', $timedate->get_user_date_format());
        $ss->assign('cal_dateformat', $timedate->get_cal_date_format());
        
        return parent::displayOptions() . $ss->fetch('modules/Charts/Dashlets/MyPipelineBySalesStageDashlet/MyPipelineBySalesStageConfigure.tpl');
    }
    
    function display() {
        global $app_list_strings, $current_language, $sugar_config, $currentModule, $action, $current_user, $theme, $timedate, $image_path;
        
        $this->loadLanguage('MyPipelineBySalesStageDashlet', 'modules/Charts/Dashlets/');
        $returnStr = '';
        
        $user_dateFormat = $timedate->get_date_format();
        $current_module_strings = return_module_language($current_language, 'Charts');
        
        if(isset($_REQUEST['mypbss_refresh'])) { 
            $refresh = $_REQUEST['mypbss_refresh']; 
        }
        else { 
            $refresh = false;
        }
        
        $date_start = $this->mypbss_date_start;
        $date_end = $this->mypbss_date_end;
        
        // cn: format date_start|end to user's preferred
        $dateStartDisplay = strftime($timedate->get_user_date_format(), strtotime($date_start));
        $dateEndDisplay     = strftime($timedate->get_user_date_format(), strtotime($date_end));
        $seps               = array("-", "/");
        $dates              = array($date_start, $date_end);
        $dateFileNameSafe   = str_replace($seps, "_", $dates);
        $dateXml[0]         = $timedate->swap_formats($date_start, $user_dateFormat, $timedate->dbDayFormat);
        $dateXml[1]         = $timedate->swap_formats($date_end, $user_dateFormat, $timedate->dbDayFormat);
        
        $datax = array();
        $selected_datax = array();
        //get list of sales stage keys to display
        $user_sales_stage = $this->mypbss_sales_stages;
        $tempx = $user_sales_stage;
        
        //set $datax using selected sales stage keys
        if (count($tempx) > 0) {
            foreach ($tempx as $key) {
                $datax[$key] = $app_list_strings['sales_stage_dom'][$key];
                array_push($selected_datax, $key);
            }
        }
        else {
            $datax = $app_list_strings['sales_stage_dom'];
            $selected_datax = array_keys($app_list_strings['sales_stage_dom']);
        }
        $GLOBALS['log']->debug("datax is:");
        $GLOBALS['log']->debug($datax);
        
        $ids = array($current_user->id);
        //create unique prefix based on selected users for image files
        $id_hash = '1';
        if (isset($ids)) {
            sort($ids);
            $id_hash = crc32(implode('',$ids));
            if($id_hash < 0)
            {
                $id_hash = $id_hash * -1;
            }
        }
        $GLOBALS['log']->debug("ids is:");
        $GLOBALS['log']->debug($ids);
        $id_md5 = substr(md5($current_user->id),0,9);
        $seps               = array("-", "/");
        $dates              = array($dateStartDisplay, $dateEndDisplay);
        $dateFileNameSafe   = str_replace($seps, "_", $dates);
        $cache_file_name = $current_user->getUserPrivGuid()."_".$theme."_my_pipeline_".$dateFileNameSafe[0]."_".$dateFileNameSafe[1].".xml";
        
        $GLOBALS['log']->debug("cache file name is: $cache_file_name");
              
//      echo get_form_header($mod_strings['LBL_PIPELINE_FORM_TITLE'], $tools , false);

        $returnStr .= "<p align='center'>".$this->gen_xml_pipeline_by_sales_stage($datax, $dateXml[0], $dateXml[1], $ids, $sugar_config['tmp_dir'].$cache_file_name, $refresh,'hBarS',$current_module_strings)."</p>";
        $returnStr .=  "<P align='center'><span class='chartFootnote'>".$current_module_strings['LBL_PIPELINE_FORM_TITLE_DESC']."</span></P>";

        if (file_exists($sugar_config['tmp_dir'].$cache_file_name)) {
            $file_date = date($timedate->get_date_format()." ".$timedate->get_time_format(), filemtime($sugar_config['tmp_dir'].$cache_file_name));
        }
        else {
            $file_date = '';
        }

        $returnStr .= "<span class='chartFootnote'>
            <p align='right'><i>{$current_module_strings['LBL_CREATED_ON']} {$file_date}</i></p>
            </span>";
        $returnStr .= get_validate_chart_js();
        
        return parent::display('<div align="center"><a href="#" onclick="SUGAR.sugarHome.retrieveDashlet(\'' . $this->id . '\', \'index.php?action=DisplayDashlet&module=Home&to_pdf=1&mypbss_refresh=true&id=' . $this->id . '\'); return false"; class="chartToolsLink">'.$this->dashletStrings['LBL_REFRESH'].'</a></div>') . $returnStr;
    }
    
    /**
    * Creates opportunity pipeline image as a HORIZONTAL accumlated BAR GRAPH for multiple users.
    * param $datax- the sales stage data to display in the x-axis
    * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
    * All Rights Reserved..
    * Contributor(s): ______________________________________..
    */
    function gen_xml_pipeline_by_sales_stage($datax=array('foo','bar'), $date_start='2071-10-15', $date_end='2071-10-15', $user_id=array('1'), $cache_file_name='a_file', $refresh=false,$chart_size='hBarF',$current_module_strings) {
        global $app_strings, $charset, $lang, $barChartColors, $current_user, $theme;
        require_once('themes/' . $theme . '/layout_utils.php');
        require_once('modules/Currencies/Currency.php');
        $kDelim = $current_user->getPreference('num_grp_sep');
        global $timedate;

        if (!file_exists($cache_file_name) || $refresh == true) {

            $GLOBALS['log']->debug("starting pipeline chart");
            $GLOBALS['log']->debug("datax is:");
            $GLOBALS['log']->debug($datax);
            $GLOBALS['log']->debug("user_id is: ");
            $GLOBALS['log']->debug($user_id);
            $GLOBALS['log']->debug("cache_file_name is: $cache_file_name");
            $opp = new Opportunity;
            $where="";
            //build the where clause for the query that matches $user
            $count = count($user_id);
            $id = array();
            $user_list = get_user_array(false);
            foreach ($user_id as $key) {
                $new_ids[$key] = $user_list[$key];
            }
            if ($count>0) {
                foreach ($new_ids as $the_id=>$the_name) {
                    $id[] = "'".$the_id."'";
                }
                $ids = join(",",$id);
                $where .= "opportunities.assigned_user_id IN ($ids) ";

            }
            //build the where clause for the query that matches $datax
            $count = count($datax);
            $dataxArr = array();
            if ($count>0) {

                foreach ($datax as $key=>$value) {
                    $dataxArr[] = "'".$key."'";
                }
                $dataxArr = join(",",$dataxArr);
                $where .= "AND opportunities.sales_stage IN ($dataxArr) ";
            }

            //build the where clause for the query that matches $date_start and $date_end
            $where .= " AND opportunities.date_closed >= ". db_convert("'".$date_start."'",'date'). " 
                        AND opportunities.date_closed <= ".db_convert("'".$date_end."'",'date') ;
            $where .= " AND opportunities.assigned_user_id = users.id  AND opportunities.deleted=0 ";

            //Now do the db queries
            //query for opportunity data that matches $datax and $user
            $query = "  SELECT opportunities.sales_stage,
                            users.user_name,
                            opportunities.assigned_user_id,
                            count( * ) AS opp_count,
                            sum(amount_usdollar/1000) AS total
                        FROM users,opportunities  ";

            $query .= "WHERE " .$where;
            $query .= " GROUP BY opportunities.sales_stage,users.user_name,opportunities.assigned_user_id";
            
            $result = $opp->db->query($query)
            or sugar_die("Error selecting sugarbean: ".mysql_error());
            //build pipeline by sales stage data
            $total = 0;
            $div = 1;
            global $sugar_config;
            $symbol = $sugar_config['default_currency_symbol'];
            global $current_user;
            if($current_user->getPreference('currency') ){
                require_once('modules/Currencies/Currency.php');
                $currency = new Currency();
                $currency->retrieve($current_user->getPreference('currency'));
                $div = $currency->conversion_rate;
                $symbol = $currency->symbol;
            }
            $symbol = "";
            // cn: adding user-pref date handling
            $dateStartDisplay = date($timedate->get_date_format(), strtotime($date_start));
            $dateEndDisplay = date($timedate->get_date_format(), strtotime($date_end));
            
            $fileContents = '     <yData defaultAltText="'.$current_module_strings['LBL_ROLLOVER_DETAILS'].'">'."\n";
            $stageArr = array();
            $usernameArr = array();
            $rowTotalArr = array();
            $rowTotalArr[] = 0;
            while($row = $opp->db->fetchByAssoc($result, -1, false))
            {
                if($row['total']*$div<=100){
                    $sum = round($row['total']*$div, 2);
                } else {
                    $sum = round($row['total']*$div);
                }

            	$sum = $row['opp_count'];
            	
                if(!isset($stageArr[$row['sales_stage']]['row_total'])) {$stageArr[$row['sales_stage']]['row_total']=0;}
                $stageArr[$row['sales_stage']][$row['assigned_user_id']]['opp_count'] = $row['opp_count'];
                $stageArr[$row['sales_stage']][$row['assigned_user_id']]['total'] = $sum;
                $stageArr[$row['sales_stage']]['people'][$row['assigned_user_id']] = $row['user_name'];
                $stageArr[$row['sales_stage']]['row_total'] += $sum;

                $usernameArr[$row['assigned_user_id']] = $row['user_name'];
                $total += $sum;
            }
            foreach ($datax as $key=>$translation) {
                if(isset($stageArr[$key]['row_total'])){$rowTotalArr[]=$stageArr[$key]['row_total'];}
                if(isset($stageArr[$key]['row_total']) && $stageArr[$key]['row_total']>100) {
                    $stageArr[$key]['row_total'] = round($stageArr[$key]['row_total']);
                }
                $fileContents .= '     <dataRow title="'.$translation.'" endLabel="';
                if(isset($stageArr[$key]['row_total'])){$fileContents .= $stageArr[$key]['row_total'];}
                $fileContents .= '">'."\n";
                if(isset($stageArr[$key]['people'])){
                    asort($stageArr[$key]['people']);
                    reset($stageArr[$key]['people']);
                    foreach ($stageArr[$key]['people'] as $nameKey=>$nameValue) {
                        $fileContents .= '          <bar id="'.$nameKey.'" totalSize="'.$stageArr[$key][$nameKey]['total'].'" altText="'.$nameValue.': '.$stageArr[$key][$nameKey]['opp_count'].' '.$current_module_strings['LBL_OPPS_WORTH'].' '.$current_module_strings['LBL_OPPS_IN_STAGE'].' '.$translation.'" url="index.php?module=Opportunities&action=index&assigned_user_id[]='.$nameKey.'&sales_stage='.urlencode($key).'&date_start='.$date_start.'&date_closed='.$date_end.'&query=true"/>'."\n";
                    }
                }
                $fileContents .= '     </dataRow>'."\n";
            }
            $fileContents .= '     </yData>'."\n";
            $max = get_max($rowTotalArr);
            if($chart_size=='hBarF'){
                $length = "10";
            }else{
                $length = "4";
            }
            $fileContents .= '     <xData min="0" max="'.$max.'" length="'.$length.'" kDelim="'.$kDelim.'" prefix="'.$symbol.'" suffix=""/>'."\n";
            $fileContents .= '     <colorLegend status="on">'."\n";
            $i=0;
            asort($new_ids);
            foreach ($new_ids as $key=>$value) {
            $color = generate_graphcolor($key,$i);
            $fileContents .= '          <mapping id="'.$key.'" name="'.$value.'" color="'.$color.'"/>'."\n";
            $i++;
            }
            $fileContents .= '     </colorLegend>'."\n";
            $fileContents .= '     <graphInfo>'."\n";
            $fileContents .= '          <![CDATA['.$current_module_strings['LBL_DATE_RANGE'].' '.$dateStartDisplay.' '.$current_module_strings['LBL_DATE_RANGE_TO'].' '.$dateEndDisplay.'<BR/>'.$current_module_strings['LBL_OPP_SIZE'].' '.$symbol.'1 Unit'.']]>'."\n";
            $fileContents .= '     </graphInfo>'."\n";
            $fileContents .= '     <chartColors ';
            foreach ($barChartColors as $key => $value) {
                $fileContents .= ' '.$key.'='.'"'.$value.'" ';
            }
            $fileContents .= ' />'."\n";
            $fileContents .= '</graphData>'."\n";
            $total = $total;
            $title = '<graphData title="'.$current_module_strings['LBL_TOTAL_PIPELINE'].format_number($total, 2, 2).'">'."\n";
            $fileContents = $title.$fileContents;

            save_xml_file($cache_file_name, $fileContents);
        }

        if($chart_size=='hBarF'){
            $width = "800";
            $height = "400";
        } else {
            $width = "350";
            $height = "400";
        }
        $return = create_chart($chart_size,$cache_file_name,$width,$height);
        return $return;
    }
}

?>
