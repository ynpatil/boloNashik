<?php
//om
if(isset($user) && !empty($user))
$where = " (leads.assigned_user_id IN($user) or leads.created_by IN ($user))";
else
$where = " (leads.assigned_user_id in (".implode(",",get_user_in_array()).") or leads.created_by in (".implode(",",get_user_in_array())."))";

$where .= " and leads.assigned_user_id = users.id ";
$where .= " and ( leads.date_entered ".$date_text." or leads.date_modified ". $date_text.")";
$where .= " and leads.deleted = 0";

?>