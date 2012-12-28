<?php

require_once('data/SugarBean.php');
require_once('include/utils.php');

Class Feedback extends SugarBean {
    var $id;
    var $parent_id;
    var $parent_type;
    var $contact_id;
    var $comments;
    var $rating;
    var $send_status;
    var $received_status;
    var $forward_status;
    var $date_entered;
    var $date_modified;

    var $contact_name;

    var $new_schema = true;
    var $table_name = "feedback_mast";
    var $module_dir = "Feedback";
    var $object_name = "Feedback";

    function Feedback() {
        parent::SugarBean();
        global $sugar_config;
        global $current_user;
        foreach ($this->field_defs as $field) {
            $this->field_name_map[$field['name']] = $field;
        }
    }

    function fill_in_additional_detail_fields() {
        $query	= "SELECT contacts.first_name, contacts.last_name, contacts.phone_work, contacts.email1, contacts.id FROM contacts ";
        $query .= " WHERE contacts.id='$this->contact_id' and contacts.deleted=0";
        $result =$this->db->query($query,true," Error filling in additional detail fields: ");

        $row = $this->db->fetchByAssoc($result);
        if($row != null) {
            $this->contact_name = return_name($row, 'first_name', 'last_name');
      
        }


    }
}
?>
