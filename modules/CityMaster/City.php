<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');
/**
 * Data access layer for the city_mast table
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
// $Id: CityMaster.php,v 1.58 2006/06/29 18:30:47 eddy Exp $

require_once('data/SugarBean.php');
require_once('include/utils.php');

/**
 *
 */
class City extends SugarBean {

    // database table columns
    var $id;
    var $date_entered;
    var $date_modified;
    var $assigned_user_id;
    var $modified_user_id;
    var $created_by;
    var $name;
    var $state_id_c;
    var $state_id_c_name;
    var $deleted;
    // related information
    var $assigned_user_name;
    var $modified_by_name;
    var $created_by_name;
    var $object_name = 'City';
    var $module_dir = 'CityMaster';
    var $new_schema = true;
    var $table_name = 'city_mast';

    //////////////////////////////////////////////////////////////////
    // METHODS
    //////////////////////////////////////////////////////////////////

    /**
     *
     */
    function City() {
        parent::SugarBean();

        static $loaded_defs;

        $GLOBALS['log']->debug("Checking whether additional_column_fields are set :" . isset($loaded_defs[$this->object_name]['additional_column_fields']));

        if (!isset($loaded_defs[$this->object_name]['additional_column_fields'])) {
            $this->additional_column_fields = LoadCachedArray($this->module_dir, $this->object_name, 'additional_column_fields');
            $loaded_defs[$this->object_name]['additional_column_fields'] = & $this->additional_column_fields;
        }
        else
            $GLOBALS['log']->debug("Not reloading additional_column_fields");

        $this->additional_column_fields = & $loaded_defs[$this->object_name]['additional_column_fields'];

        $GLOBALS['log']->debug("Additional column fields :" . implode(',', $this->additional_column_fields));
    }

    /**
     * overriding the base class function to do a join with users table
     */
    function create_list_query($order_by, $where, $show_deleted = 0) {
        $custom_join = $this->custom_fields->getJOIN();

        $query = "SELECT users.user_name assigned_user_name,city_mast.* "; //,state_mast.id as state_id,state_mast.name as state_name,country_mast.id as country_id,country_mast.name as country_name ";

        if ($custom_join) {
            $query .= $custom_join['select'];
        }
        $query .= " FROM city_mast ";

        $query .= "LEFT JOIN users ON city_mast.assigned_user_id=users.id ";
//          $query .= "LEFT JOIN state_mast ON city_mast.assigned_user_id=users.id ";
//          $query .= "LEFT JOIN users ON city_mast.assigned_user_id=users.id ";

        if ($custom_join) {
            $query .= $custom_join['join'];
        }

        $where_auto = '1=1';
        if ($show_deleted == 0) {
            $where_auto = "$this->table_name.deleted=0";
        } else if ($show_deleted == 1) {
            $where_auto = "$this->table_name.deleted=1";
        }

        if ($where != '')
            $query .= "WHERE ($where) AND " . $where_auto;
        else
            $query .= "WHERE " . $where_auto;

        if (!empty($order_by))
            $query .= " ORDER BY $order_by";

//        $GLOBALS['log']->debug("Query state_mast :".$query);
        //die($query);
        return $query;
    }

    /**
     *
     */
    function fill_in_additional_detail_fields() {
        $this->assigned_user_name = get_assigned_user_name($this->assigned_user_id);
        $this->created_by_name = get_assigned_user_name($this->created_by);
        $this->modified_by_name = get_assigned_user_name($this->modified_user_id);
    }

    /**
     *
     */
    function fill_in_additional_list_fields() {
        $this->assigned_user_name = get_assigned_user_name($this->assigned_user_id);
        $this->created_by_name = get_assigned_user_name($this->created_by);
    }

    /**
     *
     */
    function get_summary_text() {
        return $this->name;
    }

    /**
     *
     */
    function build_generic_where_clause($the_query_string) {
        $where_clauses = array();
        $the_query_string = PearDatabase::quote(from_html($the_query_string));
        array_push($where_clauses, "city_mast.name LIKE '%$the_query_string%'");

        $the_where = '';
        foreach ($where_clauses as $clause) {
            if ($the_where != '')
                $the_where .= " OR ";
            $the_where .= $clause;
        }

        return $the_where;
    }

    function get_list_view_data() {
        $field_list = $this->get_list_view_array();
        $field_list['USER_NAME'] = empty($this->user_name) ? '' : $this->user_name;
        $field_list['CREATED_BY'] = $this->created_by_name;
//		$GLOBALS['log']->debug("State id :".$field_list['STATE_ID_C']);
        $additionalDetails = get_state_details($field_list['STATE_ID_C']);
        $field_list["STATE_ID_C_NAME"] = preg_replace("/[\r]/", '', $additionalDetails['state_description']);
        $field_list["COUNTRY_ID_C"] = preg_replace("/[\r]/", '', $additionalDetails['country_id']);
        $field_list["COUNTRY_ID_C_NAME"] = preg_replace("/[\r]/", '', $additionalDetails['country_description']);

        return $field_list;
    }

    function bean_implements($interface) {
        switch ($interface) {
            case 'ACL':return true;
        }
        return false;
    }

    function create_export_query(&$order_by, &$where) {
        //$custom_join = $this->custom_fields->getJOIN();
        return create_export_master(&$order_by, &$where, '', $this->table_name);
    }
}
?>