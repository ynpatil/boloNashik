<?php
if(!defined('sugarEntry') || !sugarEntry)
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

require_once ('log4php/LoggerManager.php');

require_once ('data/SugarBean.php');
require_once ('include/upload_file.php');
require_once ('include/TimeDate.php');
require_once ('modules/TeamsOS/TeamOS.php');

// User is used to store Forecast information.
class Message extends SugarBean {

	var $id;
	var $message_name;
	var $description;
	var $category_id;
	var $status_id;
	var $created_by;
	var $created_by_name;
	var $date_entered;
	var $date_modified;
	var $modified_user_id;
	var $active_date;
	var $exp_date;
	var $filename;
	var $file_mime_type;
	var $file_ext;

	var $img_name;
	var $img_name_bare;
	var $assigned_user_id;

	//additional fields.
	var $file_url;
	var $file_url_noimage;

	var $table_name = "messages";
	var $object_name = "Message";
	var $user_preferences;

	var $encodeFields = Array ();

	// This is used to retrieve related fields from form posts.
	var $additional_column_fields = Array ('status_id',);

	var $new_schema = true;
	var $module_dir = 'Messages';
	var $team_members;
	
	function get_summary_text() {
		return "$this->message_name";
	}

	function is_authenticated() {
		return $this->authenticated;
	}

	function fill_in_additional_list_fields() {
		$this->fill_in_additional_detail_fields();
	}

	function list_view_parse_additional_sections(& $list_form, $xTemplateSection) {
		return $list_form;
	}

	function create_export_query($order_by, $where) {
		$query = "SELECT
						messages.*";
		$query .= " FROM messages ";

		$where_auto = " messages.deleted = 0";

		if ($where != "")
			$query .= " WHERE $where AND ".$where_auto;
		else
			$query .= " WHERE ".$where_auto;

		if ($order_by != "")
			$query .= " ORDER BY $order_by";
		else
			$query .= " ORDER BY messages.message_name";

		return $query;
	}

	function get_list_view_data() {
		global $current_language;
		$app_list_strings = return_app_list_strings_language($current_language);

		$message_fields = $this->get_list_view_array();
		$message_fields['FILE_URL'] = $this->file_url;
		$message_fields['FILE_URL_NOIMAGE'] = $this->file_url_noimage;
		$message_fields['CATEGORY_ID'] = empty ($this->category_id) ? "" : $app_list_strings['message_category_dom'][$this->category_id];
		$message_fields['STATUS_ID'] = empty ($this->status_id) ? "" : $app_list_strings['message_status_dom'][$this->status_id];
		//echo " Created by :".$message_fields['CREATED_BY_NAME'];
		return $message_fields;
	}

	function bean_implements($interface) {
		switch ($interface) {
			case 'ACL' :
				return true;
		}
		return false;
	}

	function fill_in_additional_detail_fields()
	{
		global $theme;
		global $current_language;

		$mod_strings=return_module_language($current_language, 'Messages');

		//populate the file url.
		//image is selected based on the extension name <ext>_icon_inline, extension is stored in document_revisions.
		//if file is not found then default image file will be used.
		global $img_name;
		global $img_name_bare;
		if (!empty($row['file_ext'])) {
			$img_name = "themes/".$theme."/images/{$row['file_ext']}_image_inline.gif";
			$img_name_bare = "{$row['file_ext']}_image_inline";
		}

		//set default file name.
		if (!empty($img_name) && file_exists($img_name)) {
			$img_name = $img_name_bare;
		}
		else {
			$img_name = "def_image_inline";  //todo change the default image.
		}

		if(isset($this->filename) && strlen($this->filename)>0)
		{
			$this->file_url = "<a href='".UploadFile::get_url($this->filename,$this->id)."' target='_blank'>".get_image('themes/'.$theme.'/images/'.$img_name,'alt="'.$mod_strings['LBL_LIST_VIEW_MESSAGE'].'"  border="0"')."</a>";
			$this->file_url_noimage=UploadFile::get_url($this->filename,$this->id);
		}
		else
		{
			$this->file_url = "";
			$this->file_url_noimage = "";
		}
		$this->assigned_user_name = get_assigned_user_name($this->assigned_user_id);
	}

	//static function.
	function get_message_name($doc_id){
		if (empty($doc_id)) return null;

		$db = & PearDatabase::getInstance();
		$query="select message_name from messages where id='$doc_id'";
		$result=$db->query($query);
		if (!empty($result)) {
			$row=$db->fetchByAssoc($result);
			if (!empty($row)) {
				return $row['message_name'];
			}
		}
		return null;
	}

 function set_viewed_status(&$user,$status)
  {
    if ( $user->object_name == 'User')
    {
      $relate_values = array('user_id'=>$user->id,'message_id'=>$this->id);
      $data_values = array('status_id'=>$status);
      $this->set_relationship("messages_users", $relate_values, true, true,$data_values);
	}
  }
   function getTeamNoticeMessages()
   {
		global $current_user;
	   	if(!isset($this->team_members)){
		    $teamBean = new TeamOS();
    	    $team_id = $teamBean->retrieve_team_id();
        
        	if(isset($team_id)){
        		$this->team_members = $teamBean->get_all_members_for_team($team_id);
	        }
	        
			$user_array = get_user_hier_array(NULL);
			foreach($user_array as $key=>$value){
				
				if($key =="Self")
				array_push($this->team_members,$current_user->id);
				else
				array_push($this->team_members,$key);
			}	
	   	}
	   	
		$sql = "SELECT messages.*,messages_users.status_id,users.user_name as created_by_name ";
		$sql .= " FROM messages LEFT JOIN users ON messages.created_by = users.id LEFT JOIN messages_users ON messages.id = messages_users.message_id AND messages_users.user_id='$current_user->id' ";
		$sql .= " WHERE (messages.category_id IN ('MyTeam') AND messages.created_by IN ('".implode("','",$this->team_members)."')) AND  (messages_users.status_id IS NULL) ";
		$sql .= "and messages.deleted=0 ";
		
		$result = $this->db->query($sql, true);
		$str = "";
		$count = 1;
		while($row = $this->db->fetchByAssoc($result)) 
			$str .= "<b>".($count++).") ".$row['created_by_name']." :</b> ".$row['description']."<hr/>";
		return $str;		
   }
   
   function create_new_list_query($order_by, $where,$filter=array(),$params=array(), $show_deleted = 0,$join_type='', $return_array = false,$parentbean, $singleSelect = false){

		global $current_user;
		$user_array = get_user_hier_array(NULL);
		$user_in_clause = "'".implode("','",array_keys($user_array))."'";

		$ret_array = array();
		$ret_array['select'] = "SELECT messages.*,messages_users.status_id,users.user_name as created_by_name ";
		$ret_array['from'] = " from messages LEFT JOIN users ON messages.created_by = users.id LEFT JOIN messages_users ON messages.id = messages_users.message_id AND messages_users.user_id='$current_user->id' ";
		$ret_array['where'] = " where (messages.category_id IN ('Global') OR (messages.category_id IN ('Private') AND messages.created_by='$current_user->id') OR (messages.category_id IN ('MyTeam') AND messages.created_by IN (".$user_in_clause.")) ) AND ";

		if(!empty($order_by)){
			//make call to process the order by clause
			$ret_array['order_by'] = " ORDER BY ". $this->process_order_by($order_by, null);
		}

 		$where_auto = '1=1';
				if($show_deleted == 0){
                	$where_auto = "$this->table_name.deleted=0";
				}else if($show_deleted == 1){
                	$where_auto = "$this->table_name.deleted=1";
				}

		if($where != "")
		$ret_array['where'] .= " ($where) AND $where_auto";
		else
		$ret_array['where'] .= " $where_auto";

		if($return_array)
			return $ret_array;

		return  $ret_array['select'] . $ret_array['from'] . $ret_array['where']. $ret_array['order_by'];
	}

  function set_ignore_status(&$user,$status)
  {
    if ( $user->object_name == 'User')
    {
      $relate_values = array('user_id'=>$user->id,'message_id'=>$this->id);
      $data_values = array('status_id'=>$status);
      $this->set_relationship("messages_users", $relate_values, true, true,$data_values);
	}
  }
}
?>
