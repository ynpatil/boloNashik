<?php
include('modules/SugarChat/NewEntryPoint.php');
/*****************************************************************************
 * The contents of this file are subject to the RECIPROCAL PUBLIC LICENSE
 * Version 1.1 ("License"); You may not use this file except in compliance
 * with the License. You may obtain a copy of the License at
 * http://opensource.org/licenses/rpl.php. Software distributed under the
 * License is distributed on an "AS IS" basis, WITHOUT WARRANTY OF ANY KIND,
 * either express or implied.
 *
 * You may:
 * a) Use and distribute this code exactly as you received without payment or
 *    a royalty or other fee.
 * b) Create extensions for this code, provided that you make the extensions
 *    publicly available and document your modifications clearly.
 * c) Charge for a fee for warranty or support or for accepting liability
 *    obligations for your customers.
 *
 * You may NOT:
 * a) Charge for the use of the original code or extensions, including in
 *    electronic distribution models, such as ASP (Application Service
 *    Provider).
 * b) Charge for the original source code or your extensions other than a
 *    nominal fee to cover distribution costs where such distribution
 *    involves PHYSICAL media.
 * c) Modify or delete any pre-existing copyright notices, change notices,
 *    or License text in the Licensed Software
 * d) Assert any patent claims against the Licensor or Contributors, or
 *    which would in any way restrict the ability of any third party to use the
 *    Licensed Software.
 *
 * You must:
 * a) Document any modifications you make to this code including the nature of
 *    the change, the authors of the change, and the date of the change.
 * b) Make the source code for any extensions you deploy available via an
 *    Electronic Distribution Mechanism such as FTP or HTTP download.
 * c) Notify the licensor of the availability of source code to your extensions
 *    and include instructions on how to acquire the source code and updates.
 * d) Grant Licensor a world-wide, non-exclusive, royalty-free license to use,
 *    reproduce, perform, modify, sublicense, and distribute your extensions.
 *
 * The Original Code is: CommuniCore
 *                       Olavo Farias
 *                       2006-04-7 olavo.farias@gmail.com
 *
 * The Initial Developer of the Original Code is CommuniCore.
 * Portions created by CommuniCore are Copyright (C) 2005 CommuniCore Ltda
 * All Rights Reserved.
 ********************************************************************************/
/*******************************************************************************
 * Data access layer for the simple module template table
 *******************************************************************************/

 require_once('data/SugarBean.php');
 require_once('include/database/PearDatabase.php');
 require_once('include/utils.php');
 include_once('config.php');
 require_once('log4php/LoggerManager.php');
 require_once('modules/Meetings/Meeting.php');
 require_once('modules/Notes/Note.php');
 require_once('modules/Emails/Email.php');
 
 class SugarChat extends SugarBean {
//Database table columns
  var $id;
  var $date_entered;
  var $date_modified;
  var $assigned_user_id;
  var $modified_user_id;
  var $created_by;
  var $name;
  var $description;
  var $deleted;
//BUILDER:START of table columns 
//BUILDER: included table columns
 //BUILDER:END of table columns 
 
//Related information
  var $assigned_user_name;
  var $modified_by_name;
  var $created_by_name;
  var $email_id;
//BUILDER:START of related information 
//BUILDER:END of related information 

//Calculated information
 
  var $object_name = 'SugarChat';
  var $module_dir  = 'SugarChat';
  var $new_schema  = true;
  var $table_name  = 'sugarchat';
//BUILDER:START of table names 
//BUILDER:END of table names 
 
  // This is used to retrieve related fields from form posts.
  var $additional_column_fields = array(
//BUILDER:START of additional column fields 
//BUILDER:END of additional column fields 
  );
 
  var $relationship_fields = array(
//BUILDER:START of relationship fields 
//BUILDER:END of relationship fields 
  );
 
//METHODS ----------------------------------------------------------------------
  function SugarChat(){
   parent::SugarBean();
  }
 
  /******************************************************************************
   * overriding the base class function to do a join with users table
   ******************************************************************************/
  function create_list_query($order_by, $where, $show_deleted = 0){
   $custom_join = $this->custom_fields->getJOIN();
   $query       = "SELECT 
                   users.user_name assigned_user_name, 
                   sugarchat.*
                  ";

   if($custom_join){ $query .=  $custom_join['select']; }

   $query .= "     FROM sugarchat ";
   $query .= "LEFT JOIN users ON sugarchat.assigned_user_id=users.id ";

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

   return $query;
  }

function create_export_query($order_by, $where){
   $custom_join = $this->custom_fields->getJOIN();
   $query       = "SELECT users.user_name assigned_user_name, sugarchat.*";
   if($custom_join){ $query .=  $custom_join['select']; }
   $query .= " FROM sugarchat ";
   $query .= "LEFT JOIN users ON sugarchat.assigned_user_id=users.id ";
// $query .= "LEFT JOIN simple_relation ON sugarchat.id=simple_relation.simple_id ";
   if($custom_join){ $query .=  $custom_join['join']; }
   $where_auto  = '1=1';
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
//  die($query);
   return $query;
}

  function fill_in_additional_detail_fields(){
   $this->assigned_user_name = get_assigned_user_name($this->assigned_user_id);
//BUILDER:START fill_in_additional_detail_fields 
   $this->format_all_fields();
//BUILDER:END fill_in_additional_detail_fields 
  }
 
  function fill_in_additional_list_fields(){
   $this->assigned_user_name     = get_assigned_user_name($this->assigned_user_id);
  }
 
  function get_summary_text(){
   return $this->name;
  }
 
  function build_generic_where_clause ($the_query_string){
   $where_clauses    = array();
   $the_query_string = PearDatabase::quote(from_html($the_query_string));
   array_push($where_clauses, "sugarchat.name LIKE '%$the_query_string%'");
   $the_where = '';
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

	function save($check_notify = FALSE) {
		require_once('modules/Currencies/Currency.php');
		//US DOLLAR
		if(isset($this->amount) && !empty($this->amount)){
			$currency = new Currency();
			$currency->retrieve($this->currency_id);
//			$this->amount_usdollar = $currency->convertToDollar($this->amount);
		}
		$this->unformat_all_fields();
		return parent::save($check_notify);
	}

  function bean_implements($interface){
   switch($interface){
    case 'ACL':return true;
   }
   return false;
  }
 }

 ?>
