<?php

if(isset($user) && !empty($user))
$where = " accounts.assigned_user_id IN($user)";
else
$where = " accounts.assigned_user_id in (".implode(",",get_user_in_array()).")";

$where .= " and accounts.assigned_user_id = users.id ";
$where .= " and ( accounts.date_entered ".$date_text." or accounts.date_modified ". $date_text.")";
$where .= " and accounts.deleted = 0";

?>