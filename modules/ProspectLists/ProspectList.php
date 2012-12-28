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
 * $Id: ProspectList.php,v 1.27 2006/06/06 17:58:33 majed Exp $
 * Description:
 * ****************************************************************************** */




require_once('data/SugarBean.php');
require_once('include/utils.php');

class ProspectList extends SugarBean {

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
    //var $list_type;
    //var $list_type_value;
    var $start_date;
    var $end_date;
    var $name;
    var $description;
    var $parent_id;
    var $parent_name;
    var $parent_type;
    // These are related
    var $assigned_user_name;
    var $prospect_id;
    var $contact_id;
    var $lead_id;
    var $populate_lead_status;
    // module name definitions and table relations
    var $table_name = "prospect_lists";
    var $module_dir = 'ProspectLists';
    var $rel_prospects_table = "prospect_lists_prospects";
    var $object_name = "ProspectList";
    // This is used to retrieve related fields from form posts.
    var $additional_column_fields = array(
        'assigned_user_name', 'assigned_user_id', 'campaign_id',
    );
    var $relationship_fields = array(
        'campaign_id' => 'campaigns',
    );

    function ProspectList() {
        global $sugar_config;
        parent::SugarBean();
    }

    var $new_schema = true;

    function get_summary_text() {
        return "$this->name";
    }

    function create_list_query($order_by, $where, $show_deleted = 0) {
        $custom_join = $this->custom_fields->getJOIN();

        $query = "SELECT ";
        $query .= "users.user_name as assigned_user_name, ";
        $query .= "prospect_lists.*";

        if ($custom_join) {
            $query .= $custom_join['select'];
        }



        $query .= " FROM prospect_lists ";





        $query .= "LEFT JOIN users
					ON prospect_lists.assigned_user_id=users.id ";




        if ($custom_join) {
            $query .= $custom_join['join'];
        }

        $where_auto = '1=1';
        if ($show_deleted == 0) {
            $where_auto = "$this->table_name.deleted=0";
        } else if ($show_deleted == 1) {
            $where_auto = "$this->table_name.deleted=1";
        }

        if ($where != "")
            $query .= "where $where AND " . $where_auto;
        else
            $query .= "where " . $where_auto;

        if ($order_by != "")
            $query .= " ORDER BY $order_by";
        else
            $query .= " ORDER BY prospect_lists.name";

        return $query;
    }

    function create_export_query($order_by, $where) {

        $query = "SELECT
                                prospect_lists.*,
                                users.user_name as assigned_user_name ";



        $query .= "FROM prospect_lists ";




        $query .= "LEFT JOIN users
                                ON prospect_lists.assigned_user_id=users.id";




        $where_auto = " prospect_lists.deleted=0";

        if ($where != "")
            $query .= " WHERE $where AND " . $where_auto;
        else
            $query .= " WHERE " . $where_auto;

        if ($order_by != "")
            $query .= " ORDER BY $order_by";
        else
            $query .= " ORDER BY prospect_lists.name";
        return $query;
    }

    function save_relationship_changes($is_update) {
        parent::save_relationship_changes($is_update);
        if ($this->lead_id != "")
            $this->set_prospect_relationship($this->id, $this->lead_id, "lead");
        if ($this->contact_id != "")
            $this->set_prospect_relationship($this->id, $this->contact_id, "contact");
        if ($this->prospect_id != "")
            $this->set_prospect_relationship($this->id, $this->contact_id, "prospect");
    }

    function set_prospect_relationship($prospect_list_id, &$link_ids, $link_name) {
        $link_field = sprintf("%s_id", $link_name);

        foreach ($link_ids as $link_id) {
            $this->set_relationship('prospect_lists_prospects', array($link_field => $link_id, 'prospect_list_id' => $prospect_list_id));
        }
    }

    function set_prospect_relationship_single($prospect_list_id, $link_id, $link_name) {
        $link_field = sprintf("%s_id", $link_name);

        $this->set_relationship('prospect_lists_prospects', array($link_field => $link_id, 'prospect_list_id' => $prospect_list_id));
    }

    function clear_prospect_relationship($prospect_list_id, $link_id, $link_name) {
        $link_field = sprintf("%s_id", $link_name);
        $where_clause = " AND $link_field = '$link_id' ";

        $query = sprintf("DELETE FROM prospect_lists_prospects WHERE prospect_list_id='%s' AND deleted = '0' %s", $prospect_list_id, $where_clause);

        $this->db->query($query, true, "Error clearing prospect/prospect_list relationship: ");
    }

    function mark_relationships_deleted($id) {
        
    }

    function fill_in_additional_list_fields() {
        
    }

    function fill_in_additional_detail_fields() {
        $this->assigned_user_name = get_assigned_user_name($this->assigned_user_id);
        $this->created_by_name = get_assigned_user_name($this->created_by);
        $this->modified_by_name = get_assigned_user_name($this->modified_user_id);

        $this->fill_in_additional_parent_fields();
    }

    function fill_in_additional_parent_fields() {
        global $app_strings, $beanFiles, $beanList;

        if (!isset($beanList[$this->parent_type])) {
            $this->parent_name = '';
            return;
        }

        $beanType = $beanList[$this->parent_type];
        require_once($beanFiles[$beanType]);
        $parent = new $beanType();
        if ($this->parent_type == "Leads" || $this->parent_type == "Contacts") {
            $query = "SELECT first_name, last_name, assigned_user_id parent_name_owner from $parent->table_name where id = '$this->parent_id'";
        } else {
            $query = "SELECT name ";
            if (isset($parent->field_defs['assigned_user_id'])) {
                $query .= " , assigned_user_id parent_name_owner ";
            } else {
                $query .= " , created_by parent_name_owner ";
            }
            $query .= " from $parent->table_name where id = '$this->parent_id'";
        }
        $result = $this->db->query($query, true, " Error filling in additional detail fields: ");

        // Get the id and the name.
        $row = $this->db->fetchByAssoc($result);
        if ($row && !empty($row['parent_name_owner'])) {
            $this->parent_name_owner = $row['parent_name_owner'];
            $this->parent_name_mod = $this->parent_type;
        }

        if (($this->parent_type == "Leads" || $this->parent_type == "Contacts") and $row != null) {
            $this->parent_name = '';
            if ($row['first_name'] != '')
                $this->parent_name .= stripslashes($row['first_name']) . ' ';
            if ($row['last_name'] != '')
                $this->parent_name .= stripslashes($row['last_name']);
        }
        elseif ($row != null) {
            $this->parent_name = stripslashes($row['name']);
        } else {
            $this->parent_name = '';
        }
    }

    function update_currency_id($fromid, $toid) {
        
    }

    function get_entry_count() {
        $query = "SELECT count(*) AS num FROM prospect_lists_prospects WHERE prospect_list_id='$this->id' AND deleted = '0'";
        $result = $this->db->query($query, true, "Grabbing prospect_list entry count");

        $row = $this->db->fetchByAssoc($result);

        if ($row)
            return $row['num'];
        else
            return 0;
    }

    function get_list_view_data() {

        $temp_array = $this->get_list_view_array();
        $temp_array["ENTRY_COUNT"] = $this->get_entry_count();
        return $temp_array;
    }

    /**
      builds a generic search based on the query string using or
      do not include any $this-> because this is called on without having the class instantiated
     */
    function build_generic_where_clause($the_query_string) {
        $where_clauses = Array();
        $the_query_string = PearDatabase::quote(from_html($the_query_string));
        array_push($where_clauses, "prospect_lists.name like '$the_query_string%'");

        $the_where = "";
        foreach ($where_clauses as $clause) {
            if ($the_where != "")
                $the_where .= " or ";
            $the_where .= $clause;
        }


        return $the_where;
    }

    function save($check_notify = FALSE) {

        return parent::save($check_notify);
    }

    function bean_implements($interface) {
        switch ($interface) {
            case 'ACL':return true;
        }
        return false;
    }

    function get_lead_dnd_count() {
        $where = '';
        if ($this->parent_type == 'CityMaster') {
            $where.=" leads.primary_address_city='" . $this->parent_id . "'";
        } else if ($ProspectListObj->parent_type == 'StateMaster') {
            $where.=" leads.primary_address_state='" . $this->parent_id . "'";
        } else if ($this->parent_type == 'LevelMaster') {
            $where.=" leads.level='" . $this->parent_id . "'";
        } else if ($this->parent_type == 'ExperienceMaster') {
            $exp_arr = getExperienceMinMaxById($this->parent_id);
            $where.=" leads.experience >='" . $exp_arr['min'] . "' and leads.experience <='" . $exp_arr['max'] . "'";
        } else if ($this->parent_type == 'RegionMaster') {
            $city_arr = getCityIdByRegionId($this->parent_id);
            $where.=" leads.primary_address_city in ('" . implode("','", $city_arr) . "')";
        }
        if ($this->start_date && $this->end_date) {
            if ($this->getSQLDate3($this->start_date) && $this->getSQLDate3($this->end_date)) {
                $where.=" and (left(leads.date_modified,10)>='" . $this->getSQLDate3($this->start_date) . "' and left(leads.date_modified,10)<='" . $this->getSQLDate3($this->end_date) . "')";
            } else {
                $where.=" and (left(leads.date_modified,10)>='" . $this->start_date . "' and left(leads.date_modified,10)<='" . $this->end_date . "')";
            }
        }
        $db = & PearDatabase::getInstance();
        $sql = "SELECT count(*) as tot_dnd FROM leads WHERE (do_not_call='on') and deleted=0 and " . $where;
        $result = $db->query($sql, true, "Error filling in include/Utils.php =>getLeadIdsByWhereClause");
        $row = $db->fetchByAssoc($result);
        if ($row['tot_dnd']) {
            return $row['tot_dnd'];
        } else {
            return 0;
        }
    }
// follow function on for test purpose later need to move or update into utils.php
    function getSQLDate3($date_str) {
        if (preg_match('/^(\d{1,2})-(\d{1,2})-(\d{4})$/', $date_str, $match)) {
            if (strlen($match[2]) == 1) {
                $match[2] = "0" . $match[2];
            }
            if (strlen($match[1]) == 1) {
                $match[1] = "0" . $match[1];
            }
            return "{$match[3]}-{$match[2]}-{$match[1]}";
        } else if (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/', $date_str, $match)) {
            if (strlen($match[2]) == 1) {
                $match[2] = "0" . $match[2];
            }
            if (strlen($match[1]) == 1) {
                $match[1] = "0" . $match[1];
            }
            return "{$match[3]}-{$match[2]}-{$match[1]}";
        } else if (preg_match('/^(\d{1,2}).(\d{1,2}).(\d{4})$/', $date_str, $match)) {
            if (strlen($match[2]) == 1) {
                $match[2] = "0" . $match[2];
            }
            if (strlen($match[1]) == 1) {
                $match[1] = "0" . $match[1];
            }
            return "{$match[3]}-{$match[2]}-{$match[1]}";
        } else {
            return "";
        }
    }

}

?>
