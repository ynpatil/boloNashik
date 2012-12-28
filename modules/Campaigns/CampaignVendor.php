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
 * $Id: Campaign.php,v 1.44 2006/06/29 18:29:14 eddy Exp $
 * Description:
 * ****************************************************************************** */

require_once('data/SugarBean.php');
require_once('include/utils.php');
require_once('modules/ProspectLists/ProspectList.php');

class CampaignVendor extends SugarBean {

    var $field_name_map;
    // Stored fields
    var $id;
    var $date_entered;
    var $date_modified;
    var $modified_user_id;
    var $assigned_user_id;
    var $created_by;
    var $created_by_name;
    var $modified_by_name;
    var $deleted;
    var $campaign_id;
    var $campaign_name;
    var $vendor_id;
    var $vendor_name;
    var $percentage;
    // These are related
    var $assigned_user_name;
    // module name definitions and table relations
    var $table_name = " campaign_vendor";
    //var $rel_prospect_list_table = "prospect_list_campaigns";
    var $object_name = "CampaignVendor";
    var $module_dir = 'CampaignVendor';
    // This is used to retrieve related fields from form posts.
    var $additional_column_fields = array(
        'campaign_name', 'product_name', 'assigned_user_name', 'assigned_user_id', 'product_name',
    );
    var $relationship_fields = Array('prospect_list_id' => 'prospect_lists');

    function CampaignVendor() {
        global $sugar_config;
        parent::SugarBean();
    }

    var $new_schema = true;

    function list_view_parse_additional_sections(&$listTmpl) {
        // take $assigned_user_id and get the Username value to assign
//        $assId = $this->getFieldValue('assigned_user_id');
//
//        $query = "SELECT first_name, last_name FROM users WHERE id = '" . $assId . "'";
//        $result = $this->db->query($query);
//        $user = $this->db->fetchByAssoc($result);
//
//        if (!empty($user)) {
//            $fullName = $user["first_name"] . " " . $user["last_name"];
//            $listTmpl->assign('ASSIGNED_USER_NAME', $fullName);
//        }
    }

    function get_summary_text() {
        return "$this->name";
    }

    function create_list_query($order_by, $where, $show_deleted = 0) {
        $custom_join = $this->custom_fields->getJOIN();

        $query = "SELECT ";
        $query .= "teams.id , teams.name as vendor_name, ";
        $query .= "campaign_vendor.*";

        if ($custom_join) {
            $query .= $custom_join['select'];
        }

        $query .= " FROM campaign_vendor ";
        $query .= "LEFT JOIN teams ON campaign_vendor.vendor_id=teams.id ";

        if ($custom_join) {
            $query .= $custom_join['join'];
        }
        $where_auto = '1=1';
        if ($show_deleted == 0) {
            $where_auto = " $this->table_name.deleted=0 ";
        } else if ($show_deleted == 1) {
            $where_auto = " $this->table_name.deleted=1 ";
        }

        if ($where != "")
            $query .= "where $where AND " . $where_auto;
        else
            $query .= "where " . $where_auto;

        if ($order_by != "")
            $query .= " ORDER BY $order_by";
        else
            $query .= " ORDER BY teams.name";

        return $query;
    }

    function create_export_query($order_by, $where) {

//        $query = "SELECT campaigns.*, users.user_name as assigned_user_name ";
//        $query .= "FROM campaigns ";
//        $query .= "LEFT JOIN users
//                                ON campaigns.assigned_user_id=users.id";
//        $where_auto = " campaigns.deleted=0";
//        if ($where != "")
//            $query .= " where $where AND " . $where_auto;
//        else
//            $query .= " where " . $where_auto;
//
//        if ($order_by != "")
//            $query .= " ORDER BY $order_by";
//        else
//            $query .= " ORDER BY campaigns.name";
//        return $query;
    }

    function clear_campaign_prospect_list_relationship($campaign_id, $prospect_list_id = '') {
        if (!empty($prospect_list_id))
            $prospect_clause = " and prospect_list_id = '$prospect_list_id' ";
        else
            $prospect_clause = '';

        $query = "DELETE FROM $this->rel_prospect_list_table WHERE campaign_id='$campaign_id' AND deleted = '0' " . $prospect_clause;
        $this->db->query($query, true, "Error clearing campaign to prospect_list relationship: ");
    }

    function mark_relationships_deleted($id) {
        $this->clear_campaign_prospect_list_relationship($id);
    }

    function fill_in_additional_list_fields() {
        
    }

    function fill_in_additional_detail_fields() {
//        $this->assigned_user_name = get_assigned_user_name($this->assigned_user_id);
//        $this->created_by_name = get_assigned_user_name($this->created_by);
//        $this->modified_by_name = get_assigned_user_name($this->modified_user_id);
        //format numbers.
        //This Query for getting product name
        $query_pro = "SELECT name FROM teams WHERE id = '" . $this->vendor_id . "'";
        $result_pro = $this->db->query($query_pro);
        $row_pro = $this->db->fetchByAssoc($result_pro);
        $this->vendor_name = $row_pro['name'];
    }

    function update_currency_id($fromid, $toid) {
        
    }

    function get_list_view_data() {
        $temp_array = $this->get_list_view_array();
        if ($this->campaign_type != 'Email') {
            $temp_array['OPTIONAL_LINK'] = "display:none";
        }
        return $temp_array;
    }

    /**
      builds a generic search based on the query string using or
      do not include any $this-> because this is called on without having the class instantiated
     */
    function build_generic_where_clause($the_query_string) {
        $where_clauses = Array();
        $the_query_string = PearDatabase::quote(from_html($the_query_string));
        array_push($where_clauses, "campaigns.name like '$the_query_string%'");

        $the_where = "";
        foreach ($where_clauses as $clause) {
            if ($the_where != "")
                $the_where .= " or ";
            $the_where .= $clause;
        }
        return $the_where;
    }

    function save($check_notify = FALSE) {
//        require_once('modules/Currencies/Currency.php');
//        //US DOLLAR
//        if (isset($this->amount) && !empty($this->amount)) {
//            $currency = new Currency();
//            $currency->retrieve($this->currency_id);
//            $this->amount_usdollar = $currency->convertToDollar($this->amount);
//        }
        $this->unformat_all_fields();
        return parent::save($check_notify);
    }

    function set_notification_body($xtpl, $camp) {
//        $xtpl->assign("CAMPAIGN_NAME", $camp->name);
//        $xtpl->assign("CAMPAIGN_AMOUNT", $camp->budget);
//        $xtpl->assign("CAMPAIGN_CLOSEDATE", $camp->end_date);
//        $xtpl->assign("CAMPAIGN_STATUS", $camp->status);
//        $xtpl->assign("CAMPAIGN_DESCRIPTION", $camp->content);
//        return $xtpl;
    }

    function track_log_entries($type = array()) {
        if (empty($type))
            $type[0] = 'targeted';
        $this->load_relationship('log_entries');
        $query_array = $this->log_entries->getQuery(true);
        $query_array['select'] = "SELECT campaign_log.* ";
        $query_array['where'] = $query_array['where'] . " AND activity_type='{$type[0]}' AND archived=0";
        return (implode(" ", $query_array));
    }

    function get_queue_items() {
//        $this->load_relationship('queueitems');
//        $query_array = $this->queueitems->getQuery(true);
//        //get select query from email man.
//        require_once('modules/EmailMan/EmailMan.php');
//        $man = new EmailMan();
//        $listquery = $man->create_list_query('', str_replace(array("WHERE", "where"), "", $query_array['where']));
//        return ($listquery);
    }

    function bean_implements($interface) {
        switch ($interface) {
            case 'ACL':return true;
        }
        return false;
    }

    function Save2CampaignVendor($campaign_id, $VendorIds) {

        if (is_array($VendorIds)) {
            foreach ($VendorIds as $key => $VendorId) {
                $query = "SELECT count( id ) rec_count,id FROM campaign_vendor WHERE deleted='0' AND campaign_id = '" . $campaign_id . "' AND  vendor_id = '" . $VendorId . "' ORDER BY `date_modified` ASC";
                $GLOBALS['log']->debug("Save bean_name Query Save2CampaignVendor query=> $query");
                $result = $this->db->query($query);
                $row = $this->db->fetchByAssoc($result);
                $GLOBALS['log']->debug("Save bean_name Query Save2CampaignVendor Row=> $row[rec_count]");
                if ($row['rec_count'] > 1) {
                    $query = "DELETE FROM campaign_vendor WHERE id='$row[id]'";
                    $result = $this->db->query($query);
                }
            }
        }
        $query = "SELECT count( id ) rec_count,id FROM campaign_vendor WHERE deleted='0' AND campaign_id = '" . $campaign_id . "' AND  vendor_id = '" . $VendorIds . "' ORDER BY `date_modified` ASC";
        $GLOBALS['log']->debug("Save bean_name Query Save2CampaignVendor query=> $query");
        $result = $this->db->query($query);
        $row = $this->db->fetchByAssoc($result);
        $GLOBALS['log']->debug("Save bean_name Query Save2CampaignVendor Row=> $row[rec_count]");
        if ($row['rec_count'] > 1) {
            $query = "DELETE FROM campaign_vendor WHERE id='$row[id]'";
            $result = $this->db->query($query);
        }

        $where = "campaign_vendor.campaign_id= '$campaign_id'";
        $getListResult = $this->get_full_list($order_by, $where, $check_dates);
        $CampaignVendorCount = count($getListResult);
        $persantage = intval(100 / $CampaignVendorCount);
        for ($i = 0; $i < $CampaignVendorCount; $i++) {
            $this->percentage = $persantage;
            $this->id = $getListResult[$i]->id;
            $this->save();
            $TotalPercentage = $TotalPercentage + $persantage;
        }
        if ($TotalPercentage < 100) {
            $DifferencePercentage = 100 - $TotalPercentage;
            $this->percentage = $this->percentage + $DifferencePercentage;
            $this->save();
        }
    }

}

?>