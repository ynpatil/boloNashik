<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * Data access layer for the suboffice_mast table
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
// $Id: SubofficeMaster.php,v 1.58 2006/06/29 18:30:47 eddy Exp $

require_once('data/SugarBean.php');
require_once('include/utils.php');
/**
 *
 */
class Suboffice extends SugarBean {
// database table columns
    var $id;
    var $date_entered;
    var $date_modified;
    var $assigned_user_id;
    var $modified_user_id;
    var $created_by;
    var $name;
    var $office_detail;
    var $branch_id_c;
    var $branch_id_c_name;
    var $longitude;
    var $latitude;
    var $pin_code;
    var $phone_no;
    var $fax_no;

    var $deleted;

    // related information
    var $assigned_user_name;
    var $modified_by_name;
    var $created_by_name;

    var $object_name = 'Suboffice';
    var $module_dir = 'SubofficeMaster';
    var $new_schema = true;
    var $table_name = 'suboffice_mast';

    //////////////////////////////////////////////////////////////////
    // METHODS
    //////////////////////////////////////////////////////////////////

    /**
     *
     */
    function Suboffice() {
        parent::SugarBean();

        static $loaded_defs;

        $GLOBALS['log']->debug("Checking whether additional_column_fields are set :".isset($loaded_defs[$this->object_name]['additional_column_fields']));

        if(!isset($loaded_defs[$this->object_name]['additional_column_fields'])) {
            $this->additional_column_fields = LoadCachedArray($this->module_dir, $this->object_name, 'additional_column_fields');
            $loaded_defs[$this->object_name]['additional_column_fields'] =& $this->additional_column_fields;
        }
        else
            $GLOBALS['log']->debug("Not reloading additional_column_fields");

        $this->additional_column_fields =& $loaded_defs[$this->object_name]['additional_column_fields'];

        $GLOBALS['log']->debug("Additional column fields :".implode(',',$this->additional_column_fields));
    }

    /**
     * overriding the base class function to do a join with users table
     */
    function create_list_query($order_by, $where, $show_deleted = 0) {
        $custom_join = $this->custom_fields->getJOIN();

        $query = "SELECT users.user_name assigned_user_name,suboffice_mast.*";

        if($custom_join) { $query .=  $custom_join['select']; }
        $query .= " FROM suboffice_mast ";

        $query .= "LEFT JOIN users ON suboffice_mast.assigned_user_id=users.id ";

        if($custom_join) { $query .=  $custom_join['join']; }

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
        array_push($where_clauses, "suboffice_mast.name LIKE '%$the_query_string%'");

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
	$field_list['BRANCH_ID_C_NAME'] = $this->getBranchId($this->branch_id_c);
        return $field_list;
    }
    function bean_implements($interface) {
        switch($interface) {
            case 'ACL':return true;
        }
        return false;
    }
    function get_nearest__office_address($latitude, $lonitude) {
        $latitude1 = $latitude+1;
        $latitude2 = $latitude-1;
        $lonitude1 = $lonitude+1;
        $lonitude2 = $lonitude-1;
        $db = & PearDatabase::getInstance();
       /* $query = " SELECT
                     som.office_detail,som.name as description,som.latitude,som.longitude, bm.name as branch_name,somc.id_c,somc.branch_id_c
                   FROM suboffice_mast as som
                       LEFT JOIN suboffice_mast_cstm as somc ON som.id = somc.id_c
                       LEFT JOIN branch_mast as bm ON somc.branch_id_c = bm.id
                   WHERE
                        (som.latitude BETWEEN ".$latitude2." AND ".$latitude1." )
                         and   (som.longitude between ".$lonitude2." AND ".$lonitude1." ) AND som.deleted='0'
                   ORDER BY som.latitude DESC LIMIT 0 , 2";*/
/*
                $query = " SELECT
                     som.office_detail,som.name as description,som.latitude,som.longitude, bm.name as branch_name,somc.id_c,somc.branch_id_c
                   FROM suboffice_mast as som
                       LEFT JOIN suboffice_mast_cstm as somc ON som.id = somc.id_c
                       LEFT JOIN branch_mast as bm ON somc.branch_id_c = bm.id
                   WHERE
                        (som.latitude <= ".$latitude." AND som.longitude <= ".$lonitude.")
                         or   (som.latitude >= ".$latitude." and som.longitude >= ".$lonitude.") AND som.deleted='0'
                   ";*/

        /*
                $query = " SELECT
                     som.office_detail,som.name as description,som.latitude,som.longitude, bm.name as branch_name,somc.id_c,somc.branch_id_c
                   FROM suboffice_mast as som
                       LEFT JOIN suboffice_mast_cstm as somc ON som.id = somc.id_c
                       LEFT JOIN branch_mast as bm ON somc.branch_id_c = bm.id
                   WHERE
                        (som.latitude <= ".$latitude." AND som.longitude <= ".$lonitude.")
                         or   (som.latitude >= ".$latitude." and som.longitude >= ".$lonitude.") AND som.deleted='0'
                   ";*/

       /* $sql1 = " SELECT
                     som.office_detail,som.name as description,som.latitude,som.longitude, bm.name as branch_name,somc.id_c,somc.branch_id_c
                   FROM suboffice_mast as som
                       LEFT JOIN suboffice_mast_cstm as somc ON som.id = somc.id_c
                       LEFT JOIN branch_mast as bm ON somc.branch_id_c = bm.id
                   WHERE
                        (som.latitude <= ".$latitude." AND som.longitude <= ".$lonitude.") AND som.deleted='0'
                   ORDER BY som.latitude DESC LIMIT 0 , 1";

        $sql2="SELECT
                     som.office_detail,som.name as description,som.latitude,som.longitude, bm.name as branch_name,somc.id_c,somc.branch_id_c
                   FROM suboffice_mast as som
                       LEFT JOIN suboffice_mast_cstm as somc ON som.id = somc.id_c
                       LEFT JOIN branch_mast as bm ON somc.branch_id_c = bm.id
                   WHERE
                      (som.latitude >= ".$latitude." and som.longitude >= ".$lonitude.")
                         AND som.deleted='0' order by latitude asc limit 0,1";

        $query= "$sql1 UNION $sql2 "; */

        # Final query : added by Yogesh
        $query="SELECT
                som.office_detail,som.name as description,som.latitude,som.longitude, bm.name as branch_name,somc.id_c,somc.branch_id_c,
                (3959 * acos(cos(radians(".$latitude.")) * cos(radians(som.latitude)) * cos(radians(som.longitude) -
                radians(".$lonitude.")) + sin( radians(".$latitude.")) * sin(radians(som.latitude)))) AS distance
        FROM  suboffice_mast as som
                LEFT JOIN suboffice_mast_cstm as somc ON som.id = somc.id_c
                LEFT JOIN branch_mast as bm ON somc.branch_id_c = bm.id
        WHERE  som.latitude is not NULL and som.longitude is not  NULL order by distance asc limit 0,2 ";


        //             $query = " SELECT som.name as description,som.latitude,som.longitude, bm.name as branch_name,somc.id_c,somc.branch_id_c,
        //                       ( 3959 * acos( cos( radians(37) ) * cos( radians( $latitude ) ) * cos( radians( $lonitude )
        //                       - radians(-122) ) + sin( radians(37) ) * sin( radians( $latitude ) ) ) ) AS distance
        //                       FROM suboffice_mast as som
        //                       LEFT JOIN suboffice_mast_cstm as somc ON som.id = somc.id_c
        //                       LEFT JOIN branch_mast as bm ON somc.branch_id_c = bm.id
        //                       WHERE  som.deleted='0' ORDER BY distance LIMIT 0 , 2";
        $i=0;
        $GLOBALS['log']->info('Soap :: get_nearest_office_address : query=>'.$query);
        $result = $db->query($query, true,"Error filling in Near Office Address details: ");
        $ret_array=array();
        if($db->getRowCount($result) > 0) {
            while($row = $db->fetchByAssoc($result)) {
                  $ret_array[]=$row;

//                $ret_array[$i]['description'] = $row['description'];
//                $ret_array[$i]['branch'] = $row['branch_name'];
//                $ret_array[$i]['latitude'] = $row['latitude'];
//                $ret_array[$i]['longitude'] = $row['longitude'];
//                $ret_array[$i]['massage'] = 'Success';
//                $i=1;
            }
        }else{
//                $ret_array[$i]['description'] = 'Not find';
//                $ret_array[$i]['branch'] = 'Not find';
//                $ret_array[$i]['latitude'] = 'not find';
//                $ret_array[$i]['longitude'] = 'Not Find';
//                $ret_array[$i]['massage'] = 'Fail';
        }
        $GLOBALS['log']->info('Soap :: get_nearest_office_address : ret_array ==>'.print_r($ret_array,true));
        return $ret_array;
    }

	function getBranchId($branch_id) {
        $db = & PearDatabase::getInstance();
        $query="SELECT bm.name as branch_name
                FROM  suboffice_mast as som
                LEFT JOIN suboffice_mast_cstm as somc ON som.id = somc.id_c
                LEFT JOIN branch_mast as bm ON somc.branch_id_c = bm.id
                WHERE  somc.branch_id_c='".$branch_id."' ";
        $GLOBALS['log']->info('SubOffice :: getBranchId : query=>'.$query);
        $result = $db->query($query, true,"Error filling in Near Office Address details: ");
        if($db->getRowCount($result) > 0) {
            $row = $db->fetchByAssoc($result);
            return $row['branch_name'];
        }
    }
    
    function getSubOfficeId($sub_office_name){
        $db = & PearDatabase::getInstance();
        $query="SELECT id FROM  suboffice_mast WHERE  name='".$sub_office_name."' ";
        $GLOBALS['log']->info('SubOffice :: get_SubOfficeId : query=>'.$query);
        $result = $db->query($query, true,"Error filling in Near Office Address details: ");
        if($db->getRowCount($result) > 0) {
            $row = $db->fetchByAssoc($result);
            return $row['id'];
        }
    }
    function create_export_query(&$order_by, &$where) {
        $custom_join = $this->custom_fields->getJOIN();
        return create_export_master(&$order_by, &$where, $custom_join, $this->table_name);
    }
}
?>