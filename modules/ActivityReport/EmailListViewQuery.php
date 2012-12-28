<?php

if(isset($user) && !empty($user))
$where = " emails.assigned_user_id IN($user)";
else
$where = " emails.assigned_user_id in (".implode(",",get_user_in_array()).")";

$where .= " and emails.assigned_user_id = users.id ";
$where .= " and ( emails.date_entered ".$date_text." or emails.date_modified ". $date_text.")";
$where .= " and emails.deleted = 0";

?>