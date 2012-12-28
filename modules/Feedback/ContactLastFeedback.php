<?php

require_once('data/SugarBean.php');
require_once('include/utils.php');

Class ContactLastFeedback extends SugarBean {
    var $id;
    //var $contact_id;
    var $last_feedback_date;
    var $date_entered;
    var $date_modified;
    var $new_schema = true;
    var $table_name = "contact_last_feedback";
    var $module_dir = "Feedback";
    var $object_name = "ContactLastFeedback";

    function ContactLastFeedback() {
        parent::SugarBean();
        global $sugar_config;
        global $current_user;
        foreach ($this->field_defs as $field) {
            $this->field_name_map[$field['name']] = $field;
        }
    }

    function getNewContactIdsForFeedback() {

        $query="SELECT count(*) as cnt
            FROM contacts b left join contact_last_feedback a ON b.id=a.contact_id
            WHERE b.invalid_email=0 and b.email_opt_out=0 and b.deleted=0";
        $result =$this->db->query($query,true," Error filling in additional detail fields: ");
        $row = $this->db->fetchByAssoc($result);
        $num_of_row=$row['cnt'];
        $row_limit=10000;

        $GLOBALS['log']->debug("NUM OF ROW COUNT=>".$num_of_row);
        $contacts_array=array();
        if($num_of_row>0) {
            for($i=0;$i<=$num_of_row;$i=$i+$row_limit) {
                $query="SELECT b.id,a.last_feedback_date,a.id as contact_last_feedback_id
                FROM contacts b left join contact_last_feedback a ON b.id=a.contact_id
                WHERE b.invalid_email=0 and b.email_opt_out=0 and b.deleted=0
                limit $i,$row_limit";
                $result1 =$this->db->query($query,true," Error filling in additional detail fields: ");
                while($row1 = $this->db->fetchByAssoc($result1)) {
                    $contacts_array[]=$row1;
                }
            }
            if(count($contacts_array)>0) {
                return $contacts_array;
            }else {
                return false;
            }
        }
    }
    

    function getValidUsersForFeedback($start_date,$end_date,$THRESHOLD) {

        $query="SELECT a.user_id, count( a.meeting_id ) AS meeting_count,e.feedback_option
        FROM meetings_users a
        LEFT JOIN meetings m ON a.meeting_id = m.id
        LEFT JOIN meetings_contacts b ON a.meeting_id = b.meeting_id
        LEFT JOIN users c ON a.user_id = c.id
        LEFT JOIN suboffice_mast_cstm d ON d.id_c = c.suboffice_id
        LEFT JOIN branch_mast e ON e.id = d.branch_id_c
        WHERE a.deleted =0
        AND b.deleted =0
        AND c.deleted =0
        AND e.deleted =0
        AND e.feedback_option=1
        AND m.date_start between '$start_date' and '$end_date'
        AND b.contact_id = '$this->contact_id'
        GROUP BY a.user_id
        HAVING count( a.meeting_id ) >=$THRESHOLD";
        $result =$this->db->query($query,true," Error filling in additional detail fields: ");
        while($row = $this->db->fetchByAssoc($result)) {
            $result[]=$row;
        }

        if(count($result)>1) {
            return $result;
        }else {
            return false;
        }

    }

    function geLastRunFeedbackDate() {
        $query="select id,last_feedback_date from contact_last_feedback where deleted=0";
        $result =$this->db->query($query,true," Error filling in additional detail fields: ");
        $row = $this->db->fetchByAssoc($result);
        if($row['last_feedback_date']) {
            return array("last_feedback_date"=>$row['last_feedback_date'],"contact_last_feedback_id"=>$row['id']);
        }else {
            return FALSE;
        }
    }

    function insertTempContactUserIds($start_date,$end_date,$THRESHOLD) {
        $query="truncate table temp_feedback";
        $this->db->query($query,true," Error filling in additional detail fields: ");
        
        $query="
        INSERT INTO temp_feedback(contact_id,user_id) SELECT
        c.contact_id,
        b.user_id
        FROM meetings a
            LEFT JOIN meetings_users b ON a.id = b.meeting_id
            LEFT JOIN meetings_contacts c ON a.id = c.meeting_id
            LEFT JOIN contacts d ON c.contact_id = d.id
            LEFT JOIN users e ON b.user_id = e.id
            LEFT JOIN suboffice_mast_cstm f ON f.id_c = e.suboffice_id
            LEFT JOIN branch_mast g ON g.id = f.branch_id_c
        WHERE a.date_start  BETWEEN '$start_date'  AND '$end_date' AND a.deleted =0
            AND b.deleted =0
            AND c.deleted =0
            AND d.invalid_email =0 AND d.email_opt_out =0 AND d.deleted =0
            AND e.deleted =0
            AND g.deleted =0 AND g.feedback_option =1
        GROUP BY b.user_id
        HAVING count( b.meeting_id ) >=$THRESHOLD";

        $result =$this->db->query($query,true," Error filling in additional detail fields: ");
        if($result) {
            return TRUE;
        }else {
            return FALSE;
        }
    }

    function getTempContactIds(){
        $query="SELECT DISTINCT contact_id
            FROM temp_feedback";
        $result=$this->db->query($query,true," Error filling in additional detail fields: ");
        while($row1 = $this->db->fetchByAssoc($result)) {
                    $contacts_array[]=$row1['contact_id'];
        }
        if(count($contacts_array)>0){
            return $contacts_array;
        }else{
            return FALSE;
        }
    }

    function getTempUserIds($contact_id){
        $query="SELECT DISTINCT user_id
            FROM temp_feedback
            WHERE contact_id='$contact_id'";
        $result=$this->db->query($query,true," Error filling in additional detail fields: ");
        while($row1 = $this->db->fetchByAssoc($result)) {
                    $users_array[]=$row1['user_id'];
        }
        if(count($users_array)>0){
            return $users_array;
        }else{
            return FALSE;
        }
    }

}
?>
