<?php

if(isset($user) && !empty($user))
$where = " project.assigned_user_id IN($user)";
else
$where = " project.assigned_user_id in (".implode(",",get_user_in_array()).")";

$where .= " and project.assigned_user_id = users.id ";
$where .= " and ( project.date_entered ".$date_text." or project.date_modified ". $date_text.")";
$where .= " and project.deleted = 0";

?>