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
 * $Id: Save2.php,v 1.23 2006/06/06 17:57:52 majed Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 * ****************************************************************************** */

/*
  ARGS:
  $_REQUEST['method']; : options: 'SaveRelationship','Save','DeleteRelationship','Delete'
  $_REQUEST['module']; : the module associated with this Bean instance (will be used to get the class name)
  $_REQUEST['record']; : the id of the Bean instance
  // $_REQUEST['related_field']; : the field name on the Bean instance that contains the relationship
  // $_REQUEST['related_record']; : the id of the related record
  // $_REQUEST['related_']; : the
  // $_REQUEST['return_url']; : the URL to redirect to
  //$_REQUEST['return_type']; : when set the results of a report will be linked with the parent.
 */


require_once('include/utils.php');
require_once('include/formbase.php');
include 'modules/CityMaster/City.php';
$CityObj = new City();

function add_prospects_to_prospect_list($query, $parent_module, $parent_type, $parent_id, $child_id, $link_attribute, $link_type) {

    $GLOBALS['log']->debug('add_prospects_to_prospect_list:parameters:' . $query);
    $GLOBALS['log']->debug('add_prospects_to_prospect_list:parameters:' . $parent_module);
    $GLOBALS['log']->debug('add_prospects_to_prospect_list:parameters:' . $parent_type);
    $GLOBALS['log']->debug('add_prospects_to_prospect_list:parameters:' . $parent_id);
    $GLOBALS['log']->debug('add_prospects_to_prospect_list:parameters:' . $child_id);
    $GLOBALS['log']->debug('add_prospects_to_prospect_list:parameters:' . $link_attribute);
    $GLOBALS['log']->debug('add_prospects_to_prospect_list:parameters:' . $link_type);


    if (!class_exists($parent_type)) {
        require_once('modules/' . $parent_module . '/' . $parent_type . '.php');
    }
    $focus = new $parent_type();
    $focus->retrieve($parent_id);

    //if link_type is default then load relationship once and add all the child ids.
    $relationship_attribute = $link_attribute;

    //find all prospects based on the query
    $db = & PearDatabase::getInstance();
    $result = $db->query($query);
    while (($row = $db->fetchByAssoc($result)) != null) {

        $GLOBALS['log']->debug('target_id' . $row[$child_id]);

        if ($link_type != 'default') {
            $relationship_attribute = strtolower($row[$link_attribute]);
        }

        $GLOBALS['log']->debug('add_prospects_to_prospect_list:relationship_attribute:' . $relationship_attribute);

        //load relationship for the first time or on change of relationship atribute.
        if (empty($focus->$relationship_attribute)) {
            $focus->load_relationship($relationship_attribute);
        }
        //add
        $focus->$relationship_attribute->add($row[$child_id]);
    }
}

//Link rows returned by a report to parent record.
function save_from_report($report_id, $parent_id, $module_name, $relationship_attr_name) {
    global $beanFiles;
    global $beanList;

    $GLOBALS['log']->debug("Save2: Linking with report output");
    $GLOBALS['log']->debug("Save2:Report ID=" . $report_id);
    $GLOBALS['log']->debug("Save2:Parent ID=" . $parent_id);
    $GLOBALS['log']->debug("Save2:Module Name=" . $module_name);
    $GLOBALS['log']->debug("Save2:Relationship Attribute Name=" . $relationship_attr_name);

    $bean_name = $beanList[$module_name];
    $GLOBALS['log']->debug("Save2:Bean Name=" . $bean_name);
    require_once($beanFiles[$bean_name]);
    $focus = new $bean_name();

    $focus->retrieve($parent_id);
    $focus->load_relationship($relationship_attr_name);

    //fetch report definition.
    global $current_language, $report_modules, $modules_report;

    $mod_strings = return_module_language($current_language, "Reports");

    require_once('modules/Reports/SavedReport.php');
    $saved = new SavedReport();
    $saved->disable_row_level_security = true;
    $saved->retrieve($report_id, false);

    //initiailize reports engine with the report definition.
    require_once('modules/Reports/Report.php');
    $report = new Report($saved->content);
    $report->run_query();

    $sql = $report->query_list[0];
    $GLOBALS['log']->debug("Save2:Report Query=" . $sql);
    $result = $report->db->query($sql);
    while ($row = $report->db->fetchByAssoc($result)) {
        $focus->$relationship_attr_name->add($row['primaryid']);
    }
}

$refreshsubpanel = true;

if (isset($_REQUEST['return_type']) && $_REQUEST['return_type'] == 'report') {
    save_from_report($_REQUEST['subpanel_id'] //report_id
            , $_REQUEST['record'] //parent_id
            , $_REQUEST['module'] //module_name
            , $_REQUEST['subpanel_field_name'] //link attribute name
    );
} else if (isset($_REQUEST['return_type']) && $_REQUEST['return_type'] == 'addtoprospectlist') {

    $GLOBALS['log']->debug(print_r($_REQUEST, true));
    add_prospects_to_prospect_list(urldecode($_REQUEST['query']), $_REQUEST['parent_module'], $_REQUEST['parent_type'], $_REQUEST['subpanel_id'], $_REQUEST['child_id'], $_REQUEST['link_attribute'], $_REQUEST['link_type']);

    $refreshsubpanel = false;
} else {

    global $beanFiles, $beanList;
    $bean_name = $beanList[$_REQUEST['module']];
    require_once($beanFiles[$bean_name]);
    $focus = new $bean_name();

    $focus->retrieve($_REQUEST['record']);

    if ($bean_name == 'Team') {
        $subpanel_id = $_REQUEST['subpanel_id'];
        if (is_array($subpanel_id)) {
            foreach ($subpanel_id as $id) {
                $focus->add_user_to_team($id);
            }
        } else {
            $focus->add_user_to_team($subpanel_id);
        }
    } else {
        //find request paramters with with prefix of REL_ATTRIBUTE_
        //convert them into an array of name value pairs add pass them as 
        //parameters to the add metod.
        $add_values = array();
        foreach ($_REQUEST as $key => $value) {
            if (strpos($key, "REL_ATTRIBUTE_") !== false) {
                $add_values[substr($key, 14)] = $value;
            }
        }
        $focus->load_relationship($_REQUEST['subpanel_field_name']);
        $focus->$_REQUEST['subpanel_field_name']->add($_REQUEST['subpanel_id'], $add_values);

        //This Functionality Added under Campaign Module  for Adjust Persantage
        if ($_REQUEST['module'] == "Campaigns" && $_REQUEST['subpanel_module_name'] == "TeamsOS" && $_REQUEST['child_field'] == "TeamsOS" && $_REQUEST['subpanel_field_name'] == "vendors") {
            require_once('modules/Campaigns/CampaignVendor.php');
            $CampaignVendorObj = new CampaignVendor();
            $CampaignVendorObj->Save2CampaignVendor($_REQUEST['record'],$_REQUEST['subpanel_id']);
        }//END If
        //Added Functinality for Add City as per the region [Only for vendor Module]
        if ($_REQUEST['module'] == "TeamsOS" && $_REQUEST['subpanel_module_name'] == "RegionMaster" && $_REQUEST['child_field'] == "RegionMaster" && $_REQUEST['subpanel_field_name'] == "region") {

            $city_result = getCityIdByRegionId($_REQUEST['subpanel_id']);
            $GLOBALS['log']->debug("Save bean_name Query Row city_result=>" . print_r($city_result, true));
            if (count($city_result) > 0) {  //Start if               
                $focus->load_relationship('city');
                $focus->city->add($city_result);
                $GLOBALS['log']->debug("Save bean_name Query Row Array=>" . print_r($_REQUEST['subpanel_id'], true));
                //This functionality for Added State as per the City
                foreach ($city_result as $key => $city_id) {
                    $CityObj->retrieve($city_id);
                    $CityObj->state_id_c;
                    $focus->load_relationship('state');
                    $focus->state->add($CityObj->state_id_c);
                }//END For Each
            }//End if 
        }//End If    
        //This functionality for Added State as per the City
        if ($_REQUEST['module'] == "TeamsOS" && $_REQUEST['subpanel_module_name'] == "CityMaster" && $_REQUEST['child_field'] == "CityMaster" && $_REQUEST['subpanel_field_name'] == "city") {
            if (is_array($_REQUEST['subpanel_id'])) {
                foreach ($_REQUEST['subpanel_id'] as $key => $city_id) {
                    $GLOBALS['log']->debug("Save bean_name Query subpanel_id Array=>" . print_r($_REQUEST['subpanel_id'], true));
                    $CityObj->retrieve($city_id);
                    $CityObj->state_id_c;
                    $focus->load_relationship('state');
                    $focus->state->add($CityObj->state_id_c);
                }//end foreach
            } else {
                $CityObj->retrieve($_REQUEST['subpanel_id']);
                $CityObj->state_id_c;
                $focus->load_relationship('state');
                $focus->state->add($CityObj->state_id_c);
            }//end else
        }//End If
        //This Functionality Used for added City as per add state
       if ($_REQUEST['module'] == "TeamsOS" && $_REQUEST['subpanel_module_name'] == "StateMaster" && $_REQUEST['child_field'] == "StateMaster" && $_REQUEST['subpanel_field_name'] == "state") {           
            $city_result = getCityIdByStateId($_REQUEST['subpanel_id']);
            $GLOBALS['log']->debug("Save bean_name Query Row state_result=>" . print_r($city_result, true));
            if (count($city_result) > 0) {  //Start if               
                $focus->load_relationship('city');
                $focus->city->add($city_result);
            }//End If
        }//End main If        
    }
}

if ($refreshsubpanel) {
    //refresh contents of the sub-panel.
    $GLOBALS['log']->debug("Location: index.php?sugar_body_only=1&module=" . $_REQUEST['module'] . "&subpanel=" . $_REQUEST['subpanel_module_name'] . "&action=SubPanelViewer&inline=1&record=" . $_REQUEST['record']);
    if (empty($_REQUEST['refresh_page']) || $_REQUEST['refresh_page'] != 1) {
        $inline = isset($_REQUEST['inline']) ? $_REQUEST['inline'] : $inline;
        header("Location: index.php?sugar_body_only=1&module=" . $_REQUEST['module'] . "&subpanel=" . $_REQUEST['subpanel_module_name'] . "&action=SubPanelViewer&inline=$inline&record=" . $_REQUEST['record']);
    }
}
exit;
?>
