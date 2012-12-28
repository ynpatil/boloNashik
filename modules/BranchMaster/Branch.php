<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * Data access layer for the branch_mast table
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
// $Id: BranchMaster.php,v 1.58 2006/06/29 18:30:47 eddy Exp $

require_once('data/SugarBean.php');
require_once('include/utils.php');
/**
 *
 */
class Branch extends SugarBean {
    // database table columns
    var $id;
    var $date_entered;
    var $date_modified;
    var $assigned_user_id;
    var $modified_user_id;
    var $created_by;
    var $name;
    var $deleted;

    // related information
    var $assigned_user_name;
    var $modified_by_name;
    var $created_by_name;

    var $object_name = 'Branch';
    var $module_dir = 'BranchMaster';
    var $new_schema = true;
    var $table_name = 'branch_mast';
    var $feedback_option;

    //////////////////////////////////////////////////////////////////
    // METHODS
    //////////////////////////////////////////////////////////////////

    /**
     *
     */
    function Branch() {
        parent::SugarBean();
    }

    /**
     * overriding the base class function to do a join with users table
     */
    function create_list_query($order_by, $where, $show_deleted = 0) {
        $custom_join = $this->custom_fields->getJOIN();

        $query = "SELECT users.user_name assigned_user_name, branch_mast.*";

        if($custom_join) {
            $query .=  $custom_join['select'];
        }
        $query .= " FROM branch_mast ";

        $query .= "LEFT JOIN users ON branch_mast.assigned_user_id=users.id ";

        if($custom_join) {
            $query .=  $custom_join['join'];
        }

        $where_auto = '1=1';
        if($show_deleted == 0) {
            $where_auto = "$this->table_name.deleted=0";
        }else if($show_deleted == 1) {
            $where_auto = "$this->table_name.deleted=1";
        }

        if($where != '')
            $query .= "WHERE ($where) AND ".$where_auto;
        else
            $query .= "WHERE ".$where_auto;

        if(!empty($order_by))
            $query .= " ORDER BY $order_by";
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
    function build_generic_where_clause ($the_query_string) {
        $where_clauses = array();
        $the_query_string = PearDatabase::quote(from_html($the_query_string));
        array_push($where_clauses, "branch_mast.name LIKE '%$the_query_string%'");

        $the_where = '';
        foreach($where_clauses as $clause) {
            if($the_where != '') $the_where .= " OR ";
            $the_where .= $clause;
        }

        return $the_where;
    }

    function get_list_view_data() {
        $field_list = $this->get_list_view_array();
        $field_list['USER_NAME'] = empty($this->user_name) ? '' : $this->user_name;
        $field_list['CREATED_BY'] = $this->created_by_name;
        return $field_list;
    }
    function bean_implements($interface) {
        switch($interface) {
            case 'ACL':return true;
        }
        return false;
    }
    function get_all_branches_address() {

        $db = & PearDatabase::getInstance();
        # Final query : added by Yogesh
        $query="SELECT id as branch_id, name as branch_name
                FROM  branch_mast
                WHERE  deleted=0 ORDER BY name";
        $i=0;
        $GLOBALS['log']->info('Soap :: get_all_branches_address : query=>'.$query);
        $result = $db->query($query, true,"Error filling in get_all_branches_address: ".$query);
        $ret_array=array();
        if($db->getRowCount($result) > 0) {
            while($row = $db->fetchByAssoc($result)) {
                $ret_array[]=$row;
            }
        }else {

        }
        $GLOBALS['log']->info('Soap :: get_all_branches_address : ret_array ==>'.print_r($ret_array,true));
        return $ret_array;
    }

    function get_all_suboffice_for_branch($branch_id) {
        $db = & PearDatabase::getInstance();

        $query="SELECT som.office_detail,som.name as description,som.latitude,som.longitude, bm.name as branch_name
        FROM  suboffice_mast as som
                LEFT JOIN suboffice_mast_cstm as somc ON som.id = somc.id_c
                LEFT JOIN branch_mast as bm ON somc.branch_id_c = bm.id
        WHERE   somc.branch_id_c='".$branch_id."' AND som.deleted=0";

        $GLOBALS['log']->info('Soap :: get_nearest_office_address : query=>'.$query);
        $result = $db->query($query, true,"Error filling in Near Office Address details: ");
        $ret_array=array();
        if($db->getRowCount($result) > 0) {
            while($row = $db->fetchByAssoc($result)) {
                $ret_array[]=$row;
            }
        }
        return $ret_array;
    }
    
    function create_export_query(&$order_by, &$where) {
        $custom_join = $this->custom_fields->getJOIN();            
        return create_export_master(&$order_by, &$where,$custom_join,$this->table_name);        
    }
}
?>
