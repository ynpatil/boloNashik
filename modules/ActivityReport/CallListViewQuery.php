<?php

if(isset($user) && !empty($user))
$where = " calls.assigned_user_id IN($user)";
else
$where = " calls.assigned_user_id in (".implode(",",get_user_in_array()).")";

$where .= " and calls.assigned_user_id = users.id ";
$where .= " and ( calls.date_entered ".$date_text." or calls.date_modified ". $date_text.")";
$where .= " and calls.deleted = 0";

?>