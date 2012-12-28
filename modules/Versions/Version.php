<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
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
 * $Header: /var/cvsroot/sugarcrm/modules/Versions/Version.php,v 1.14 2006/09/05 22:27:37 majed Exp $
 * Description:
 ********************************************************************************/





require_once('data/SugarBean.php');


require_once('include/utils.php');

class Version extends SugarBean {
	// Stored fields
	var $id;
	var $deleted;
	var $date_entered;
	var $date_modified;
	var $modified_user_id;
	var $created_by;
	var $created_by_name;
	var $modified_by_name;
	var $field_name_map;
	var $name;
	var $file_version;
	var $db_version;
	var $table_name = 'versions';
	var $module_dir = 'Versions';
	var $object_name = "version";

	var $new_schema = true;

	// This is used to retrieve related fields from form posts.
	var $additional_column_fields = Array();

	function Version() {
		parent::SugarBean();




	}

	


	
	/**
		builds a generic search based on the query string using or
		do not include any $this-> because this is called on without having the class instantiated
	*/
	function build_generic_where_clause ($the_query_string) {
	$where_clauses = Array();
	$the_query_string = addslashes($the_query_string);
	array_push($where_clauses, "name like '$the_query_string%'");
	$the_where = "";
	foreach($where_clauses as $clause)
	{
		if($the_where != "") $the_where .= " or ";
		$the_where .= $clause;
	}


	return $the_where;
}


function is_expected_version($expected_version){
	foreach($expected_version as $name=>$val){
		if($this->$name != $val){
			return false;	
		}	
	}
	return true;
		
}
/**
 * Updates the version info based on the information provided
 */
function mark_upgraded($name, $dbVersion, $fileVersion){
	$query = "DELETE FROM versions WHERE name='$name'";
	$GLOBALS['db']->query($query);
	$version = new Version();
	$version->name = $name;
	$version->file_version = $fileVersion;
	$version->db_version = $dbVersion;
	$version->save();
	unset($_SESSION['invalid_versions'][$name]);
}

function get_profile(){
	return array('name'=> $this->name, 'file_version'=> $this->file_version, 'db_version'=>$this->db_version);	
}






}

?>
