<?php

require_once('data/SugarBean.php');
require_once('include/utils.php');

Class ContactFeedback extends SugarBean {
    var $id;
    var $contact_id;
    var $email_send_status;
    var $no_feedback_flag;
    var $token_id;
    var $user_id;

    var $date_entered;
    var $date_modified;

    var $new_schema = true;
    var $table_name = "contact_feedback";
    var $module_dir = "Feedback";
    var $object_name = "ContactFeedback";

    function ContactFeedback() {
        parent::SugarBean();
        global $sugar_config;
        global $current_user;
        foreach ($this->field_defs as $field) {
            $this->field_name_map[$field['name']] = $field;
        }
    }
}
?>
