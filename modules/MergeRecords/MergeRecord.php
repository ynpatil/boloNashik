<?php
if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');
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
 * $Id: MergeRecord.php,v 1.7 2006/08/05 00:21:47 ajay Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

include_once ('config.php');
require_once ('log4php/LoggerManager.php');
require_once ('include/database/PearDatabase.php');
require_once ('data/SugarBean.php');
require_once ('include/utils.php');

class MergeRecord extends SugarBean {
    var $object_name = 'MergeRecord';
    var $module_dir = 'MergeRecords';

    var $merge_module;
    var $merge_bean_class;
    var $merge_bean_file_path;
    var $master_id;

    //these arrays store the fields and params to search on
    var $field_search_params = Array ();

    //this is a object for the bean you are merging on
    var $merge_bean;

    //store a copy of the merge bean related strings
    var $merge_bean_strings = Array ();

    function MergeRecord($merge_module = '', $merge_id = '') {
        global $sugar_config;
        parent :: SugarBean();

        if ($merge_module != '')
            $this->load_merge_bean($merge_module, $merge_id);
    }

    function retrieve($id) {
        if (isset ($_REQUEST['action']) && $_REQUEST['action'] == 'Step2')
            $this->load_merge_bean($this->merge_bean, false, $id);
        else
            parent :: retrieve($id);
    }

    function load_merge_bean($merge_module, $load_module_strings = false, $merge_id = '') {
        global $moduleList;
        global $beanList;
        global $beanFiles;
        global $current_language;

        $this->merge_module = $merge_module;
        $this->merge_bean_class = $beanList[$this->merge_module];
        $this->merge_bean_file_path = $beanFiles[$this->merge_bean_class];

        require_once ($this->merge_bean_file_path);
        $this->merge_bean = new $this->merge_bean_class();
        if ($merge_id != '')
            $this->merge_bean->retrieve($merge_id);
        //load master module strings
        if ($load_module_strings)
            $this->merge_bean_strings = return_module_language($current_language, $merge_module);
    }

    var $new_schema = true;

    //-----------------------------------------------------------------------
    //-------------Wrapping Necessary Merge Bean Calls-----------------------
    //-----------------------------------------------------------------------
    function create_list_query($order_by, $where, $show_deleted = 0) {
        $where_statement = $this->merge_bean->create_list_query($order_by, $where, $show_deleted = 0);
        return $where_statement;
    }
    function fill_in_additional_list_fields() {
        return $this->merge_bean->fill_in_additional_list_fields();
    }

    function fill_in_additional_detail_fields() {
        return $this->merge_bean->fill_in_additional_detail_fields();
    }

    function get_summary_text() {
        return $this->merge_bean->get_summary_text();
    }

    function get_list_view_data() {
        return $this->merge_bean->get_list_view_data();
    }
    //-----------------------------------------------------------------------
    //-----------------------------------------------------------------------
    //-----------------------------------------------------------------------

    /**
    	builds a generic search based on the query string using or
    	do not include any $this-> because this is called on without having the class instantiated
    */
    function build_generic_where_clause($the_query_string) {
        return $this->merge_bean->build_generic_where_clause($the_query_string);
    }

    //adding in 4.0+ acl function for possible acl stuff down the line
    function bean_implements($interface) {
        switch ($interface) {
            case 'ACL' :
                return true;
        }
        return false;
    }
    
    function ACLAccess($view,$is_owner='not_set'){
        global $current_user;

        //if the module doesn't implement ACLS    
        if(!$this->merge_bean->bean_implements('ACL'))return true;
        
        if($is_owner == 'not_set'){
            $is_owner = $this->merge_bean->isOwner($current_user->id);
        }
        return ACLController::checkAccess($this->merge_bean->module_dir,'edit', true);
    }
    

    //keep save function to handle anything special on merges
    function save($check_notify = FALSE) {
            //something here
    return parent :: save($check_notify);
    }

    function populate_search_params($search_params) {
       foreach ($this->merge_bean->field_defs as $key=>$value) {
            $searchFieldString=$key.'SearchField';
            $searchTypeString=$key.'SearchType';
             
            if (isset($search_params[$searchFieldString]) ) {

                if (isset($search_params[$searchFieldString]) == '') {
                    $this->field_search_params[$key]['value']='NULL';
                } else {
                    $this->field_search_params[$key]['value']=$search_params[$searchFieldString];
                }
                if (isset ($search_params[$searchTypeString])) {
                    $this->field_search_params[$key]['search_type'] = $search_params[$searchTypeString];
                } else {
                    $this->field_search_params[$key]['search_type'] = 'Exact';
                }
                //add field_def to the array.
                $this->field_search_params[$key] = array_merge($value,$this->field_search_params[$key] );
            }
       }
    }

    function create_where_statement() {
        $where_clauses = array ();
        foreach ($this->field_search_params as $merge_field => $vDefArray) {
            if (isset ($vDefArray['source']) && $vDefArray['source'] == 'custom_fields') {
                $table_name = $vDefArray['table'];
            } else {
                $table_name = $this->merge_bean->table_name;
            }

            //Should move these if's into a central location for extensibility and addition for other search filters
            //Must do the same for pulling values in js dropdown
            if (isset ($vDefArray['search_type']) && $vDefArray['search_type'] == 'like')
                array_push($where_clauses, $table_name.".".$merge_field." LIKE '%".PearDatabase :: quote($vDefArray['value'])."%'");
            elseif (isset ($vDefArray['search_type']) && $vDefArray['search_type'] == 'start') array_push($where_clauses, $table_name.".".$merge_field." LIKE '".PearDatabase :: quote($vDefArray['value'])."%'");
            else array_push($where_clauses, $table_name.".".$merge_field."='".PearDatabase :: quote($vDefArray['value'])."'");
        }

        array_push($where_clauses, $this->merge_bean->table_name.".id !='".PearDatabase :: quote($this->merge_bean->id)."'");


        return $where_clauses;
    }

    //duplicating utils function for now for possiblity of future or/and and
    //other functionality
    function generate_where_statement($where_clauses) {
        $where = '';

        foreach ($where_clauses as $clause) {
            if ($where != "")
                $where .= " AND ";
            $where .= $clause;
        }

        return $where;
    }
}
?>
