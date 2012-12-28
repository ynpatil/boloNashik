<?php

if(isset($user) && !empty($user))
$where = " cases.assigned_user_id IN($user)";
else
$where = " cases.assigned_user_id in (".implode(",",get_user_in_array()).")";

$where .= " and cases.assigned_user_id = users.id ";
$where .= " and ( cases.date_entered ".$date_text." or cases.date_modified ". $date_text.")";
$where .= " and cases.deleted = 0";

?>