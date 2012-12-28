<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
//om
require_once ('log4php/LoggerManager.php');
require_once ('data/SugarBean.php');

// User is used to store customer information.
class NewUser extends SugarBean {
    // Stored fields
    var $id;

    var $first_name;
    var $last_name;
    var $date_of_birth;
    var $phone_mobile;
    var $email_id;
    var $report_to_email_id;
    var $status;
    var $case_id;
    var $remark;

    var $date_entered;
    var $date_modified;
    var $modified_user_id;
    var $created_by;
    var $created_by_name;
    var $modified_by_name;
    var $deleted;
    var $table_name = 'register_user';
    var $module_dir = 'Users';
    var $object_name ='NewUser';
    var $new_schema = true;

    function NewUser() {
        //om
       parent::SugarBean();
        global $sugar_config;
        global $current_user;
        unset($this->custom_fields);
    }

    function checkRegisterUserApply($email_id) {
        $query = "SELECT id FROM register_user WHERE email_id='$email_id' AND status='6'  AND deleted=0";
        $result = $GLOBALS['db']->query($query, false, "Error retrieving user ID: ");
        $row = $GLOBALS['db']->fetchByAssoc($result);
        if($row['id']) {
            return true;
        }else {
            return false;
        }
    }
}
?>
