<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
//om
require_once ('log4php/LoggerManager.php');
require_once ('data/SugarBean.php');

// User is used to store customer information.
class Access extends SugarBean {
	// Stored fields
	var $id;
	var $user_id;
	var $full_name;
	var $access_to_user_id;
	var $access_to_module;
	var $date_entered;
	var $date_modified;
	var $modified_user_id;
	var $created_by;
	var $created_by_name;
	var $modified_by_name;
	var $deleted;
	var $table_name = 'users_access';
	var $module_dir = 'Users';
	var $object_name ='Access';
	var $new_schema = true;
	
	function Access() {
		//om
		parent :: SugarBean();
	}	
	
	function create_list_query($order_by, $where, $show_deleted = 0) {
		$query = 'SELECT '.$this->table_name.'.*,concat(users.first_name," ",users.last_name) as full_name 
		FROM '.$this->table_name.' INNER JOIN users ON '.$this->table_name.'.user_id = users.id ';

		if($show_deleted == 0) {
			$where_auto = 'users.DELETED=0 AND '.$this->table_name.'.deleted = 0';
		} elseif($show_deleted == 1) {
			$where_auto = 'users.DELETED=1  AND '.$this->table_name.'.deleted = 0';
		} else {
			$where_auto = '1=1';
		}
		
		if($where != "") {
			$query .= 'WHERE ('.$where.') AND '.$where_auto;
		} else {
			$query .= 'WHERE '.$where_auto;
		}

		if(!empty($order_by))
			$query .= ' ORDER BY '.$order_by;
		return $query;
	}	
	
	function get_access_user_list($user_id,$module){
		
		$user_ids = array();
		$query = "SELECT access_to_user_id,users.first_name,users.last_name from users_access INNER JOIN users ON users_access.access_to_user_id = users.id where users_access.user_id='$user_id' and access_to_module='$module' and users_access.deleted = 0";
		$GLOBALS['log']->debug("In get_access_user_list :".$query);

		$results = $GLOBALS['db']->query($query);
		while($row = $this->db->fetchByAssoc($results)) {
			$user_ids[$row['access_to_user_id']] = $row['first_name']." ".$row['last_name'];
		}
		return $user_ids;
	}

	function get_who_has_access_user_list($user_id,$module){
		
		$user_ids = array();
		$query = "SELECT user_id,users.first_name,users.last_name from users_access INNER JOIN users ON users_access.user_id = users.id where users_access.access_to_user_id='$user_id' and access_to_module='$module' and users_access.deleted = 0";
		$GLOBALS['log']->debug("In get_who_has_access_user_list :".$query);

		$results = $GLOBALS['db']->query($query);
		while($row = $this->db->fetchByAssoc($results)) {
			$user_ids[$row['user_id']] = $row['first_name']." ".$row['last_name'];
		}
		return $user_ids;
	}

}
?>
