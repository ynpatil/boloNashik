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

 * Description:  returns HTML for client-side image map.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): Ken Brill (TeamsOS)
 ********************************************************************************/

//UPDATED FOR TeamsOS 3.0c by Ken Brill Jan 7th, 2007

require_once('modules/Opportunities/Opportunity.php');
require_once('include/charts/Charts.php');
require_once('include/utils.php');


class Chart_lead_source_by_outcome
{
	var $modules = array('Opportunities');
	var $order = 0;
function Chart_lead_source_by_outcome()
{

}

function draw($extra_tools)
{

require_once('include/utils.php');

global $app_list_strings, $current_language, $sugar_config, $currentModule, $action,$theme;
$current_module_strings = return_module_language($current_language, 'Charts');


if (isset($_REQUEST['lsbo_refresh'])) { $refresh = $_REQUEST['lsbo_refresh']; }
else { $refresh = false; }

$tempx = array();
$datax = array();
$selected_datax = array();
//get list of sales stage keys to display

global $current_user;
$tempx = $current_user->getPreference('lsbo_lead_sources');
if (!empty($lsbo_lead_sources) && count($lsbo_lead_sources) > 0 && !isset($_REQUEST['lsbo_lead_sources'])) {
	$GLOBALS['log']->fatal("user->getPreference('lsbo_lead_sources') is:");
	$GLOBALS['log']->fatal($tempx);
}
elseif (isset($_REQUEST['lsbo_lead_sources']) && count($_REQUEST['lsbo_lead_sources']) > 0) {
	$tempx = $_REQUEST['lsbo_lead_sources'];
	$current_user->setPreference('lsbo_lead_sources', $_REQUEST['lsbo_lead_sources']);
	$GLOBALS['log']->fatal("_REQUEST['lsbo_lead_sources'] is:");
	$GLOBALS['log']->fatal($_REQUEST['lsbo_lead_sources']);
	$GLOBALS['log']->fatal("user->getPreference('lsbo_lead_sources') is:");
	$GLOBALS['log']->fatal($current_user->getPreference('lsbo_lead_sources'));
}
//set $datax using selected sales stage keys
if (!empty($tempx) && sizeof($tempx) > 0) {
	foreach ($tempx as $key) {
		$datax[$key] = $app_list_strings['lead_source_dom'][$key];
		array_push($selected_datax,$key);
	}
}
else {
	$datax = $app_list_strings['lead_source_dom'];
	$selected_datax = array_keys($app_list_strings['lead_source_dom']);
}

$ids =$current_user->getPreference('lsbo_ids');
//get list of user ids for which to display data
if (!empty($ids) && count($ids) != 0 && !isset($_REQUEST['lsbo_ids'])) {
	$GLOBALS['log']->debug("_SESSION['lsbo_ids'] is:");
	$GLOBALS['log']->debug($ids);
}
elseif (isset($_REQUEST['lsbo_ids']) && count($_REQUEST['lsbo_ids']) > 0) {
	$ids = $_REQUEST['lsbo_ids'];
	$current_user->setPreference('lsbo_ids', $_REQUEST['lsbo_ids']);
	$GLOBALS['log']->debug("_REQUEST['lsbo_ids'] is:");
	$GLOBALS['log']->debug($_REQUEST['lsbo_ids']);
	$GLOBALS['log']->debug("user->getPreference('lsbo_ids') is:");
	$GLOBALS['log']->debug($current_user->getPreference('lsbo_ids'));
}
else {
	$ids = get_user_array(false);
	$ids = array_keys($ids);
}

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


$seps				= array("-", "/");
$dates				= array(date('Y-m-d'), date('Y-m-d'));
$dateFileNameSafe	= str_replace($seps, "_", $dates);
$cache_file_name	= $current_user->getUserPrivGuid()."_lead_source_by_outcome_".$dateFileNameSafe[0]."_".$dateFileNameSafe[1].".xml";
$GLOBALS['log']->debug("cache file name is: $cache_file_name");

global $image_path;
$tools='<div align="right"><a href="index.php?module='.$currentModule.'&action='. $action .'&lsbo_refresh=true" class="chartToolsLink">'.get_image($image_path.'refresh','alt="Refresh"  border="0" align="absmiddle"').'&nbsp;'.$current_module_strings['LBL_REFRESH'].'</a>&nbsp;&nbsp;<a href="javascript: toggleDisplay(\'lsbo_edit\');" class="chartToolsLink">'.get_image($image_path.'edit','alt="Edit"  border="0"  align="absmiddle"').'&nbsp;'. $current_module_strings['LBL_EDIT'].'</a>&nbsp;&nbsp;'.$extra_tools.'</div>';
?>

<?php
echo '<span onmouseover="this.style.cursor=\'move\'" id="chart_handle_' . $this->order . '">' . get_form_header($current_module_strings['LBL_LEAD_SOURCE_BY_OUTCOME'],$tools,false) . '</span>';

if (empty($_SESSION['lsbo_ids'])) $_SESSION['lsbo_ids'] = "";
?>

<p>
<div id='lsbo_edit' style='display: none;'>
<form action="index.php" method="post" >
<input type="hidden" name="module" value="<?php echo $currentModule;?>">
<input type="hidden" name="action" value="<?php echo $action;?>">
<input type="hidden" name="lsbo_refresh" value="true">
<table cellpadding="0" cellspacing="0" border="0" class="chartForm" align="center">
<tr>
	<td valign='top' nowrap><b><?php echo $current_module_strings['LBL_LEAD_SOURCES'];?></b></td>
	<td valign='top'><select name="lsbo_lead_sources[]" multiple size='3'><?php echo get_select_options_with_id($app_list_strings['lead_source_dom'],$selected_datax); ?></select></td>
</tr>

<tr>
	<td valign='top' nowrap><b><?php echo $current_module_strings['LBL_USERS'];?></b></td>
	<td valign='top'><select name="lsbo_ids[]" multiple size='3'><?php echo get_select_options_with_id(get_user_array(false),$ids); ?></select></td>
</tr>

<tr>
<?php
global $app_strings;
?>
	<td align="right" colspan="2"> <input class="button" type="submit" title="<?php echo $app_strings['LBL_SELECT_BUTTON_TITLE']; ?>" accessKey="<?php echo $app_strings['LBL_SELECT_BUTTON_KEY']; ?>" value="<?php echo $app_strings['LBL_SELECT_BUTTON_LABEL']?>" /><input class="button" onClick="javascript: toggleDisplay('lsbo_edit');" type="button" title="<?php echo $app_strings['LBL_CANCEL_BUTTON_TITLE']; ?>" accessKey="<?php echo $app_strings['LBL_CANCEL_BUTTON_KEY'];?>" value="<?php echo $app_strings['LBL_CANCEL_BUTTON_LABEL']?>"/></td>
	</tr>
</table>
</form>
</div>
</p>
<?php

echo "<p align='center'>".$this->gen_xml($datax, $ids, $sugar_config['tmp_dir'].$cache_file_name, $refresh,$current_module_strings)."</p>";
echo "<P align='center'><span class='chartFootnote'>".$current_module_strings['LBL_LEAD_SOURCE_BY_OUTCOME_DESC']."</span></P>";


	if (file_exists($sugar_config['tmp_dir'].$cache_file_name)) {
global  $timedate;
		$file_date = date($timedate->get_date_format()." ".$timedate->get_time_format(), filemtime($sugar_config['tmp_dir'].$cache_file_name));
	}
	else {
		$file_date = '';
	}
?>
<span class='chartFootnote'>
<p align="right"><i><?php  echo $current_module_strings['LBL_CREATED_ON'].' '.$file_date; ?></i></p>
</span>
<?php

}




	/**
	* Creates lead_source_by_outcome pipeline image as a HORIZONAL accumlated bar graph for multiple users.
	* param $datay- the lead source data to display in the x-axis
	* param $ids - list of assigned users of opps to find
	* param $cache_file_name - file name to write image to
	* param $refresh - boolean whether to rebuild image if exists
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	* All Rights Reserved..
	* Contributor(s): ______________________________________..
	*/
	function  gen_xml($datay=array('foo','bar'), $user_id=array('1'), $cache_file_name='a_file', $refresh=false,$current_module_strings) {
		global $app_strings, $charset, $lang, $barChartColors,$app_list_strings, $current_user;
		require_once('modules/Currencies/Currency.php');
		$kDelim = $current_user->getPreference('num_grp_sep');

		if (!file_exists($cache_file_name) || $refresh == true) {
			$GLOBALS['log']->debug("datay is:");
			$GLOBALS['log']->debug($datay);
			$GLOBALS['log']->debug("user_id is: ");
			$GLOBALS['log']->debug($user_id);
			$GLOBALS['log']->debug("cache_file_name is: $cache_file_name");
			$opp = new Opportunity();
			$where="";
			//build the where clause for the query that matches $user
			$count = count($user_id);
			$id = array();
			if ($count>0) {
				foreach ($user_id as $the_id) {
					$id[] = "'".$the_id."'";
				}
				$ids = join(",",$id);
				$where .= "opportunities.assigned_user_id IN ($ids) ";

			}

			//build the where clause for the query that matches $datay
			$count = count($datay);
			$datayArr = array();
			if ($count>0) {

				foreach ($datay as $key=>$value) {
					$datayArr[] = "'".$key."'";
				}
				$datayArr = join(",",$datayArr);
				$where .= "AND opportunities.lead_source IN	($datayArr) ";

/* begin Lampada change */
				foreach ($_SESSION['team_id'] as $value) {
					$teamArr[] = "'".$value."'";
				}
				if($teamArr == "") {
					$where .= "AND opportunities_cstm.assigned_team_id_c IS NULL ";
				} else {
					$teamArr = join(",", $teamArr);
					$where .= " AND (opportunities_cstm.assigned_team_id_c IN ($teamArr) OR opportunities_cstm.assigned_team_id_c IS NULL) ";
				}

			}
			$query = "SELECT lead_source,sales_stage,sum(amount_usdollar/1000) as total,count(*) as opp_count FROM opportunities,opportunities_cstm ";
/* end Lampada change */



			$query .= "WHERE " .$where." AND opportunities.deleted=0 ";
			$query .= " GROUP BY sales_stage,lead_source ORDER BY lead_source,sales_stage";
			//Now do the db queries
			//query for opportunity data that matches $datay and $user

			$result = $opp->db->query($query)
			or sugar_die("Error selecting sugarbean: ".mysql_error());
			//build pipeline by sales stage data
			$total = 0;
			$div = 1;
			global $sugar_config;
			$symbol = $sugar_config['default_currency_symbol'];
			$other = $current_module_strings['LBL_LEAD_SOURCE_OTHER'];
			$rowTotalArr = array();
			$rowTotalArr[] = 0;
			global $current_user;
			$salesStages = array("Closed Lost"=>$app_list_strings['sales_stage_dom']["Closed Lost"],"Closed Won"=>$app_list_strings['sales_stage_dom']["Closed Won"],"Other"=>$other);
			if($current_user->getPreference('currency') ){
				require_once('modules/Currencies/Currency.php');
				$currency = new Currency();
				$currency->retrieve($current_user->getPreference('currency'));
				$div = $currency->conversion_rate;
				$symbol = $currency->symbol;
			}
			$fileContents = '     <yData defaultAltText="'.$current_module_strings['LBL_ROLLOVER_DETAILS'].'">'."\n";
			$leadSourceArr = array();
			while($row = $opp->db->fetchByAssoc($result, -1, false))
			{
				if($row['total']*$div<=100){
					$sum = round($row['total']*$div, 2);
				} else {
					$sum = round($row['total']*$div);
				}
				if($row['lead_source'] == ''){
					$row['lead_source'] = $current_module_strings['NTC_NO_LEGENDS'];
				}
				if($row['sales_stage'] == 'Closed Won' || $row['sales_stage'] == 'Closed Lost'){
					$salesStage = $row['sales_stage'];
					$salesStageT = $app_list_strings['sales_stage_dom'][$row['sales_stage']];
				} else {
					$salesStage = "Other";
					$salesStageT = $other;
				}
				if(!isset($leadSourceArr[$row['lead_source']]['row_total'])) {$leadSourceArr[$row['lead_source']]['row_total']=0;}
				$leadSourceArr[$row['lead_source']][$salesStage]['opp_count'][] = $row['opp_count'];
				$leadSourceArr[$row['lead_source']][$salesStage]['total'][] = $sum;
				$leadSourceArr[$row['lead_source']]['outcome'][$salesStage]=$salesStageT;
				$leadSourceArr[$row['lead_source']]['row_total'] += $sum;

				$total += $sum;
			}
			foreach ($datay as $key=>$translation) {
				if ($key == '') {
					$key = $current_module_strings['NTC_NO_LEGENDS'];
					$translation = $current_module_strings['NTC_NO_LEGENDS'];
				}
				if(!isset($leadSourceArr[$key])){
					$leadSourceArr[$key] = $key;
				}
				if(isset($leadSourceArr[$key]['row_total'])){$rowTotalArr[]=$leadSourceArr[$key]['row_total'];}
				if(isset($leadSourceArr[$key]['row_total']) && $leadSourceArr[$key]['row_total']>100){
					$leadSourceArr[$key]['row_total'] = round($leadSourceArr[$key]['row_total']);
				}
				$fileContents .= '          <dataRow title="'.$translation.'" endLabel="'.currency_format_number($leadSourceArr[$key]['row_total'], array('currency_symbol' => true)) . '">'."\n";
				if(is_array($leadSourceArr[$key]['outcome'])){
					foreach ($leadSourceArr[$key]['outcome'] as $outcome=>$outcome_translation){
						$fileContents .= '               <bar id="'.$outcome.'" totalSize="'.array_sum($leadSourceArr[$key][$outcome]['total']).'" altText="'.format_number(array_sum($leadSourceArr[$key][$outcome]['opp_count']),0,0).' '.$current_module_strings['LBL_OPPS_WORTH'].' '.currency_format_number(array_sum($leadSourceArr[$key][$outcome]['total']),array('currency_symbol' => true)).$current_module_strings['LBL_OPP_THOUSANDS'].' '.$current_module_strings['LBL_OPPS_OUTCOME'].' '.$outcome_translation.'" url="index.php?module=Opportunities&action=index&lead_source='.$key.'&sales_stage='.urlencode($outcome).'&query=true&searchFormTab=advanced_search"/>'."\n";
					}
				}
				$fileContents .= '          </dataRow>'."\n";
			}
			$fileContents .= '     </yData>'."\n";
			$max = get_max($rowTotalArr);
			$fileContents .= '     <xData min="0" max="'.$max.'" length="10" kDelim="'.$kDelim.'" prefix="'.$symbol.'" suffix=""/>' . "\n";
			$fileContents .= '     <colorLegend status="on">'."\n";
			$i=0;

				foreach ($salesStages as $outcome=>$outcome_translation) {
					$color = generate_graphcolor($outcome,$i);
					$fileContents .= '          <mapping id="'.$outcome.'" name="'.$outcome_translation.'" color="'.$color.'"/>'."\n";
					$i++;
				}
			$fileContents .= '     </colorLegend>'."\n";
			$fileContents .= '     <graphInfo>'."\n";
			$fileContents .= '          <![CDATA['.$current_module_strings['LBL_OPP_SIZE'].' '.$symbol.'1'.$current_module_strings['LBL_OPP_THOUSANDS'].']]>'."\n";
			$fileContents .= '     </graphInfo>'."\n";
			$fileContents .= '     <chartColors ';
			foreach ($barChartColors as $key => $value) {
				$fileContents .= ' '.$key.'='.'"'.$value.'" ';
			}
			$fileContents .= ' />'."\n";
			$fileContents .= '</graphData>'."\n";
			$total = round($total, 2);
			$title = '<graphData title="'.$current_module_strings['LBL_ALL_OPPORTUNITIES'].currency_format_number($total, array('currency_symbol' => true)).$app_strings['LBL_THOUSANDS_SYMBOL'].'">'."\n";
			$fileContents = $title.$fileContents;

			save_xml_file($cache_file_name, $fileContents);
		}
		$return = create_chart('hBarF',$cache_file_name);
		return $return;
	}
}

?>
