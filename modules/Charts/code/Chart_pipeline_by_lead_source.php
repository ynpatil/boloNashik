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

require_once('config.php');

require_once('modules/Opportunities/Opportunity.php');
require_once('include/charts/Charts.php');
require_once('include/utils.php');


class Chart_pipeline_by_lead_source
{
	var $order = 0;
	var $modules = array('Opportunities');

function Chart_pipeline_by_lead_source()
{
}

function draw($extra_tools)
{
require_once('include/utils.php');

global $app_list_strings, $current_language, $ids, $sugar_config ,$theme;
$current_module_strings = return_module_language($current_language, 'Charts');


if (isset($_REQUEST['pbls_refresh'])) { $refresh = $_REQUEST['pbls_refresh']; }
else { $refresh = false; }

$tempx = array();
$datax = array();
$selected_datax = array();
//get list of sales stage keys to display
global $current_user;
$user_tempx = $current_user->getPreference('pbls_lead_sources');
if (!empty($user_tempx) && count($user_tempx) > 0 && !isset($_REQUEST['pbls_lead_sources'])) {
	$tempx = $user_tempx;
	$GLOBALS['log']->debug("USER PREFERENCES['pbls_lead_sources'] is:");
	$GLOBALS['log']->debug($user_tempx);
}
elseif (isset($_REQUEST['pbls_lead_sources']) && count($_REQUEST['pbls_lead_sources']) > 0) {
	$tempx = $_REQUEST['pbls_lead_sources'];
	$current_user->setPreference('pbls_lead_sources', $_REQUEST['pbls_lead_sources']);
	$GLOBALS['log']->debug("_REQUEST['pbls_lead_sources'] is:");
	$GLOBALS['log']->debug($_REQUEST['pbls_lead_sources']);
	$GLOBALS['log']->debug("USER PREFERENCES['pbls_lead_sources'] is:");
	$GLOBALS['log']->debug($current_user->getPreference('pbls_lead_sources'));
}

//set $datax using selected sales stage keys
if (count($tempx) > 0) {
	foreach ($tempx as $key) {
		$datax[$key] = $app_list_strings['lead_source_dom'][$key];
		array_push($selected_datax,$key);
	}
}
else {
	$datax = $app_list_strings['lead_source_dom'];
	$selected_datax = array_keys($app_list_strings['lead_source_dom']);
}
$GLOBALS['log']->debug("datax is:");
$GLOBALS['log']->debug($datax);

$ids = array();
$user_ids = $current_user->getPreference('pbls_ids');
//get list of user ids for which to display data
if (!empty($user_ids) && count($user_ids) != 0 && !isset($_REQUEST['pbls_ids'])) {
	if(isset($_SESSION['pbls_ids'])) {$ids = $_SESSION['pbls_ids'];}
	$GLOBALS['log']->debug("USER PREFERENCES['pbls_ids'] is:");
	$GLOBALS['log']->debug($user_ids);
}
elseif (isset($_REQUEST['pbls_ids']) && count($_REQUEST['pbls_ids']) > 0) {
	$ids = $_REQUEST['pbls_ids'];
	$current_user->setPreference('pbls_ids', $ids);
	$GLOBALS['log']->debug("_REQUEST['pbls_ids'] is:");
	$GLOBALS['log']->debug($_REQUEST['pbls_ids']);
	$GLOBALS['log']->debug("USER PREFERENCES['pbls_ids'] is:");
	$GLOBALS['log']->debug($current_user->getPreference('pbls_ids'));
}
else {
	$ids = get_user_array_forassign(false);
	$ids = array_keys($ids);
}

//create unique prefix based on selected users for image files
$id_hash = '1';
if (isset($ids) && is_array($ids)) {
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
$cache_file_name	= $current_user->getUserPrivGuid()."_pipeline_by_lead_source_".$dateFileNameSafe[0]."_".$dateFileNameSafe[1].".xml";

$GLOBALS['log']->debug("cache file name is: $cache_file_name");
global $currentModule,$action,$image_path;
$tools='<div align="right"><a href="index.php?module='.$currentModule.'&action='. $action .'&pbls_refresh=true" class="chartToolsLink">'.get_image($image_path.'refresh','alt="Refresh"  border="0" align="absmiddle"').'&nbsp;'.$current_module_strings['LBL_REFRESH'].'</a>&nbsp;&nbsp;<a href="javascript: toggleDisplay(\'pbls_edit\');" class="chartToolsLink">'.get_image($image_path.'edit','alt="Edit"  border="0"  align="absmiddle"').'&nbsp;'. $current_module_strings['LBL_EDIT'].'</a>&nbsp;&nbsp;'.$extra_tools.'</div>';
?>

<?php
echo '<span onmouseover="this.style.cursor=\'move\'" id="chart_handle_' . $this->order . '">' . get_form_header($current_module_strings['LBL_LEAD_SOURCE_FORM_TITLE'],$tools,false) . '</span>';
if (empty($_SESSION['pbls_lead_sources'])) $_SESSION['pbls_lead_sources'] = "";
if (empty($_SESSION['pbls_ids'])) $_SESSION['pbls_ids'] = "";
?>

<p>
<div id='pbls_edit' style='display: none;'>
<form action="index.php" method="post" >
<input type="hidden" name="module" value="<?php echo $currentModule;?>">
<input type="hidden" name="action" value="<?php echo $action;?>">
<input type="hidden" name="pbls_refresh" value="true">
<table cellpadding="0" cellspacing="0" border="0" class="chartForm" align="center">
<tr>
	<td valign='top' nowrap><b><?php echo $current_module_strings['LBL_LEAD_SOURCES'];?></b></td>
	<td valign='top'><select name="pbls_lead_sources[]" multiple size='3'><?php echo get_select_options_with_id($app_list_strings['lead_source_dom'],$selected_datax); ?></select></td>
	<td valign='top' nowrap><b><?php echo $current_module_strings['LBL_USERS'];?></b></td>
	<td valign='top'><select name="pbls_ids[]" multiple size='3'><?php $allUsers = get_user_array_forassign(false); echo get_select_options_with_id($allUsers,$ids); ?></select></td>
<?php
global $app_strings;
?>
	<td align="right" valign="top"><input class="button" type="submit" title="<?php echo $app_strings['LBL_SELECT_BUTTON_TITLE']; ?>" accessKey="<?php echo $app_strings['LBL_SELECT_BUTTON_KEY']; ?>" value="<?php echo $app_strings['LBL_SELECT_BUTTON_LABEL']?>" /><input class="button" onClick="javascript: toggleDisplay('pbls_edit');" type="button" title="<?php echo $app_strings['LBL_CANCEL_BUTTON_TITLE']; ?>" accessKey="<?php echo $app_strings['LBL_CANCEL_BUTTON_KEY'];?>" value="<?php echo $app_strings['LBL_CANCEL_BUTTON_LABEL']?>"/></td>
</tr>
</table>
</form>
</div>
</p>
<?php
// draw table
echo "<p align='center'>".$this->gen_xml($datax, $ids, $sugar_config['tmp_dir'].$cache_file_name, $refresh,$current_module_strings)."</p>";
echo "<P align='center'><span class='chartFootnote'>".$current_module_strings['LBL_LEAD_SOURCE_FORM_DESC']."</span></P>";

	if (file_exists($sugar_config['tmp_dir'].$cache_file_name)) {
global $timedate;
		$file_date = date($timedate->get_date_format()." ".$timedate->get_time_format(), filemtime($sugar_config['tmp_dir'].$cache_file_name));
	}
	else {
		$file_date = '';
	}
?>
<span class='chartFootnote'>
<p align="right"><i><?php  echo $current_module_strings['LBL_CREATED_ON'].' '.$file_date; ?></i></p>
</span>
<?Php
}



	/**
	* Creates PIE CHART image of opportunities by lead_source.
	* param $datax- the sales stage data to display in the x-axis
	* param $datay- the sum of opportunity amounts for each opportunity in each sales stage
	* to display in the y-axis
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	* All Rights Reserved..
	* Contributor(s): ______________________________________..
	*/
	function gen_xml($legends=array('foo','bar'), $user_id=array('1'), $cache_file_name='a_file', $refresh=true,$current_module_strings) {
		global $app_strings, $charset, $lang, $pieChartColors, $current_user;
		require_once('modules/Currencies/Currency.php');
		$kDelim = $current_user->getPreference('num_grp_sep');

		if (!file_exists($cache_file_name) || $refresh == true) {
			;
			$GLOBALS['log']->debug("starting pipeline chart");
			$GLOBALS['log']->debug("legends is:");
			$GLOBALS['log']->debug($legends);
			$GLOBALS['log']->debug("user_id is: ");
			$GLOBALS['log']->debug($user_id);
			$GLOBALS['log']->debug("cache_file_name is: $cache_file_name");

			$opp = new Opportunity;
			//Now do the db queries
			//query for opportunity data that matches $legends and $user
			$where="";
			//build the where clause for the query that matches $user

			$count = count($user_id);
			$id = array();
			if ($count > 0 && !empty($user_id)) {
				foreach ($user_id as $the_id) {
					$id[] = "'".$the_id."'";
				}
				$ids = join(",",$id);
				$where .= "opportunities.assigned_user_id IN ($ids) ";

/* begin Lampada change */
				foreach ($_SESSION['team_id'] as $value) {
					$teamArr[] = "'".$value."'";
				}
				if($teamArr == "") {
					$where .= "AND (opportunities_cstm.assigned_team_id_c IS NULL OR opportunities_cstm.assigned_team_id_c = \"\") ";
				} else {
					$teamArr = join(",", $teamArr);
					$where .= " AND (opportunities_cstm.assigned_team_id_c IN ($teamArr) OR opportunities_cstm.assigned_team_id_c IS NULL OR opportunities_cstm.assigned_team_id_c = \"\") ";
				}
/* end Lampada change */

			}
			if(!empty($where)) $where .= 'AND';
			//build the where clause for the query that matches $datax
			$count = count($legends);
			$legendItem = array();
			if ($count > 0 && !empty($legends)) {

				foreach ($legends as $key=>$value) {
					$legendItem[] = "'".$key."'";
				}
				$legendItems = join(",",$legendItem);
				$where .= " opportunities.lead_source IN	($legendItems) ";
			}
/* begin Lampada change */
			$query = "SELECT lead_source,sum(amount_usdollar/1000) as total,count(*) as opp_count FROM opportunities 
			LEFT JOIN opportunities_cstm ON opportunities.id = opportunities_cstm.id_c ";
/* end Lampada change */

			$query .= "WHERE ".$where." AND opportunities.deleted=0 ";
			$query .= "GROUP BY lead_source ORDER BY total DESC";

			//build pipeline by lead source data
			$total = 0;
			$div = 1;
			global $sugar_config;
			$symbol = $sugar_config['default_currency_symbol'];
			global $current_user;
			if($current_user->getPreference('currency') ) {
				$currency = new Currency();
				$currency->retrieve($current_user->getPreference('currency'));
				$div = $currency->conversion_rate;
				$symbol = $currency->symbol;
			}
			$symbol="";
			$subtitle = $current_module_strings['LBL_OPP_SIZE'].' '.$symbol.'1 Unit';
			$fileContents = '';
			$fileContents .= '     <pie defaultAltText="'.$current_module_strings['LBL_ROLLOVER_WEDGE_DETAILS'].'" legendStatus="on">'."\n";
			$result = $opp->db->query($query)
			or sugar_die("Error selecting sugarbean: ".mysql_error());
			$leadSourceArr =  array();
			while($row = $opp->db->fetchByAssoc($result, -1, false))
			{
				if($row['lead_source'] == ''){
					$leadSource = $current_module_strings['NTC_NO_LEGENDS'];
				} else {
					$leadSource = $row['lead_source'];
				}
				if($row['total']*$div<=100){
					$sum = round($row['total']*$div, 2);
				} else {
					$sum = round($row['total']*$div);
				}
				
				$sum = $row['opp_count'];
				$leadSourceArr[$leadSource]['opp_count'] = $row['opp_count'];
				$leadSourceArr[$leadSource]['sum'] = $sum;
			}
			$i=0;
			foreach ($legends as $lead_source_key=>$translation) {
				if ($lead_source_key == '') {
					$lead_source_key = $current_module_strings['NTC_NO_LEGENDS'];
					$translation = $current_module_strings['NTC_NO_LEGENDS'];
				}
				if(!isset($leadSourceArr[$lead_source_key])) {
					$leadSourceArr[$lead_source_key] = $lead_source_key;
					$leadSourceArr[$lead_source_key]['sum'] = 0;
				}
				$color = generate_graphcolor($lead_source_key,$i);
				$fileContents .= '          <wedge title="'.$translation.'" kDelim="'.$kDelim.'" value="'.$leadSourceArr[$lead_source_key]['sum'].'" color="'.$color.'" labelText="'.$leadSourceArr[$lead_source_key]['sum'].'" url="index.php?module=Opportunities&action=index&lead_source='.urlencode($lead_source_key).'&query=true&searchFormTab=advanced_search" altText="'.format_number($leadSourceArr[$lead_source_key]['opp_count'], 0, 0).' '.$current_module_strings['LBL_OPPS_IN_LEAD_SOURCE'].' '.$translation.'"/>'."\n";
				if(isset($leadSourceArr[$lead_source_key])){$total += $leadSourceArr[$lead_source_key]['sum'];}
				$i++;
			}

			$fileContents .= '     </pie>'."\n";
			$fileContents .= '     <graphInfo>'."\n";
			$fileContents .= '          <![CDATA[]]>'."\n";
			$fileContents .= '     </graphInfo>'."\n";
			$fileContents .= '     <chartColors ';
			foreach ($pieChartColors as $key => $value) {
				$fileContents .= ' '.$key.'='.'"'.$value.'" ';
			}
			$fileContents .= ' />'."\n";
			$fileContents .= '</graphData>'."\n";
			$total = round($total, 2);
			$title = $current_module_strings['LBL_TOTAL_PIPELINE'].$total;
			$fileContents = '<graphData title="'.$title.'" subtitle="'.$subtitle.'">'."\n" . $fileContents;
			$GLOBALS['log']->debug("total is: $total");
			if ($total == 0) {
				return ($current_module_strings['ERR_NO_OPPS']);
			}

			save_xml_file($cache_file_name, $fileContents);
		}

		$return = create_chart('pieF',$cache_file_name);
		return $return;

	}
}
?>
