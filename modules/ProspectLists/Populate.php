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
 * $Id: Delete.php,v 1.9 2006/06/06 17:58:33 majed Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('modules/ProspectLists/ProspectList.php');
require_once('include/utils.php');
//echo "<pre>";print_r($_REQUEST);exit;
$focus = new ProspectList();
if(!isset($_REQUEST['record']))
	sugar_die("A record number must be specified to delete the prospect list.");
$focus->retrieve($_REQUEST['record']);

/* Once you added this functionality into cron job ,make live this code
*/ 
if($_REQUEST['action']=='Populate' && $focus->populate_lead_status==0){
$focus->populate_lead_status=1;
$focus->save();
}


/***
 *   Following is for temp. functionality of populate leads
 
$where='';
if($focus->parent_type=='CityMaster'){
    $where.=" leads.primary_address_city='".$focus->parent_id."'";
}else if($focus->parent_type=='LevelMaster'){
     $where.=" leads.level='".$focus->parent_id."'";
}else if($focus->parent_type=='ExperienceMaster'){
    $exp_arr=getExperienceMinMaxById($focus->parent_id);
    $where.=" leads.experience >='".$exp_arr['min']."' and leads.experience <='".$exp_arr['max']."'";
}else if($focus->parent_type=='RegionMaster'){
    $city_arr=getCityIdByRegionId($focus->parent_id);
    $where.=" leads.primary_address_city in ('".implode("','", $city_arr)."')";
}
if($focus->start_date && $focus->end_date){
    $where.=" and (left(leads.date_modified,10)>='".getSQLDate2($focus->start_date)."' and left(leads.date_modified,10)<='".getSQLDate2($focus->end_date)."')";
    //$where.=" and leads.date_modified between '".$focus->start_date."' and '".$focus->end_date."'";
}
$lead_ids_array=getLeadIdsByWhereClause($where);

// if User RePopulate Leads or Updated Target List criteria then remove old one
//  $focus->load_relationship("leads"): Once you load relationship, you need not reload again.
//    you can perform other operation like delete,add
 
$focus->load_relationship("leads"); //
$old_leads=$focus->leads->get();
if(count($old_leads)> 0){
       $focus->leads->delete($focus->id);
}
if(count($lead_ids_array)>0){
    foreach($lead_ids_array as $key =>$lead_id){
    $focus->leads->add($lead_id);
    }
}

* 
 */

header("Location: index.php?module=".$_REQUEST['return_module']."&action=".$_REQUEST['return_action']."&record=".$_REQUEST['return_id']);


?>
