<?php

if(isset($user) && !empty($user))
$where = " tasks.assigned_user_id IN($user)";
else
$where = " tasks.assigned_user_id in (".implode(",",get_user_in_array()).")";

$where .= " and ( tasks.date_start ".$date_text.")";
$where .= " and tasks.deleted = 0";

?>