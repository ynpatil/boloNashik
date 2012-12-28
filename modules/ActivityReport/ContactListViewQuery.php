<?php

if(isset($user) && !empty($user))
$where = " contacts.assigned_user_id IN($user)";
else
$where = " contacts.assigned_user_id in (".implode(",",get_user_in_array()).")";

$where .= " and contacts.assigned_user_id = users.id ";
$where .= " and ( contacts.date_entered ".$date_text." or contacts.date_modified ". $date_text.")";
$where .= " and contacts.deleted = 0";

?>