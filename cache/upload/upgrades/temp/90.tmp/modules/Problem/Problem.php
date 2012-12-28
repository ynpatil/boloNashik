<?php
if(empty($GLOBALS['sugarEntry'])) die('Not A Valid Entry Point');
/*******************************************************************************
 * Data access layer for the Problem table
 ******************************************************************************/

require_once('data/SugarBean.php');
require_once('include/database/PearDatabase.php');
require_once('include/utils.php');
include_once('config.php');
require_once('log4php/LoggerManager.php');
//require_once('modules/Calls/Call.php');
require_once('modules/Notes/Note.php');
require_once('modules/Emails/Email.php');

class Problem extends SugarBean {
 // database table columns
 var $id;
 var $date_entered;
 var $date_modified;
 var $assigned_user_id;
 var $modified_user_id;
 var $created_by;
 var $name;
 var $description;
 var $deleted;
 var $status;
 var $class;
 var $all_keywords;

 // related information
 var $assigned_user_name;
 var $modified_by_name;
 var $created_by_name;
 var $keyword1;
 var $keyword2;
 var $keyword3;
 var $keyword4;
 var $associate_id;
 
 var $object_name = 'Problem';
 var $module_dir  = 'Problem';
 var $new_schema  =  true;
 var $table_name  = 'problem';

 // This is used to retrieve related fields from form posts.
 var $additional_column_fields = array(
  'associate_id',
  'keyword1',
  'keyword2',
  'keyword3',
  'keyword4',
 );

 //////////////////////////////////////////////////////////////////
 // METHODS
 //////////////////////////////////////////////////////////////////

 function Problem()
 {
  parent::SugarBean();
 }

 /**
  * overriding the base class function to do a join with users table
  */
 function create_list_query($order_by, $where, $show_deleted = 0){
 $GLOBALS['log']->info("================ create_list_query: $where");

  $custom_join = $this->custom_fields->getJOIN();
  $query = "SELECT users.user_name assigned_user_name, problem.*";
  if($custom_join){ $query .=  $custom_join['select']; }
  $query .= " FROM problem ";
  $query .= "LEFT JOIN users ON problem.assigned_user_id=users.id AND users.deleted = 0 ";
//$query .= "LEFT JOIN problem_relation ON problem.id=problem_relation.problem_id ";
  if($custom_join){ $query .=  $custom_join['join']; }
   $where_auto = '1=1';
    if($show_deleted == 0){
     $where_auto = "$this->table_name.deleted=0";
    }else if($show_deleted == 1){
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

 function fill_in_additional_detail_fields()
 {
  $this->assigned_user_name = get_assigned_user_name($this->assigned_user_id);
 }

 function fill_in_additional_list_fields()
 {
  $this->assigned_user_name = get_assigned_user_name($this->assigned_user_id);
 }

 function get_summary_text(){
  return $this->name;
 }

 function build_generic_where_clause ($the_query_string){
  $where_clauses    = array();
  $the_query_string = PearDatabase::quote(from_html($the_query_string));
  array_push($where_clauses, "problem.name LIKE '%$the_query_string%'");
  $the_where        = '';
  foreach($where_clauses as $clause){
   if($the_where != '') $the_where .= " OR ";
   $the_where .= $clause;
  }
  return $the_where;
 }
 
 function get_list_view_data(){
  $field_list                       = $this->get_list_view_array();
  $field_list['USER_NAME']          = empty($this->user_name) ? '' : $this->user_name;
  $field_list['ASSIGNED_USER_NAME'] = $this->assigned_user_name;
  return $field_list;
 }
 function bean_implements($interface){
  switch($interface){
   case 'ACL':return true;
  }
  return false;
 }
}
?>
