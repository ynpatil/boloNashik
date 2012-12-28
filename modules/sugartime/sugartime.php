<?php
/*
***** SugarTime *****
Developed by Paul K. Lynch, Everyday Interactive Networks (ein.com.au)
Mozilla Public License v1.1
*/

include_once('config.php');  
require_once('data/SugarBean.php');
require_once('include/utils.php'); 

class sugartime extends SugarBean {
	var $id;
	var $assigned_user_id;
	var $assigned_user_name;
	var $rdate; // record date
	var $start_time; // record start time
	var $finish_time; // record finish time
	var $downtime; // off time (hh:mm)
	var $overtime; // time over 8 hours
	var $overtime_hours; // hours over 8 hours, in decimal time 
	var $ntotal; // Non-overtime hours (not in use yet)
	var $total; // total time - downtime (hh:mm)
	var $total_hours; // total time - downtime in decimal time
	var $date_modified;
	var $deleted;

	var $table_name = 'sugartime';
	var $object_name = 'sugartime';
	var $module_dir = 'sugartime';
	var $new_schema = true;

	var $column_fields = Array(
			'id'
			,'assigned_user_id'
			,'assigned_user_name'
			,'rdate'
			,'start_time'
			,'finish_time'
			,'downtime'
			,'overtime'
			,'overtime_hours'
			,'ntotal'
			,'total'
			,'total_hours'
			,'date_modified'
			,'deleted'
	);

	var $additional_column_fields = Array();
	var $relationship_fields = Array();
	var $required_fields =  array('rdate'=>1);

	function sugartime() {
		parent::SugarBean();
		// BEGIN SUGARCRM PRO ONLY
		$this->disable_row_level_security=true;
		// END SUGARCRM PRO ONLY
	}

	function get_summary_text()
	{
		return "$this->title";
	}

	function create_list_query($order_by, $where)
	{
		$custom_join = $this->custom_fields->getJOIN();

        $query = "SELECT ";
		$query .= "sugartime.*, users.user_name ";

		// Custom fields not in use yet
        if($custom_join){
			$query .= $custom_join['select'];
		}

        $query .= " FROM sugartime, users ";

		// Custom fields not in use yet
		if($custom_join){
			$query .= $custom_join['join'];
		}

		$where_auto = " (sugartime.deleted=0)";
		if($where != "")
			$query .= "WHERE $where AND ".$where_auto;
		else
			$query .= "WHERE ".$where_auto;
		
		$query .= " AND sugartime.assigned_user_id = users.id";
		
		if($order_by != "")
			$query .= " ORDER BY $order_by";
		else
			$query .= " ORDER BY sugartime.rdate";
		
		return $query;
	}

	function create_export_query()
	{
		return $this->create_list_query();
	}
	
	function delete_me() {
		$this->mark_deleted($this->id);
	}
	
	function save_me() {
		$this->save();
	}
	
	function bean_implements($interface){
		switch($interface){
		case 'ACL':return true;
		}
		return false;
	}  
}
?>
