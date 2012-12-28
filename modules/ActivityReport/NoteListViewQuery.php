<?php

if(isset($user) && !empty($user))
$where = " notes.created_by IN($user)";
else
$where = " notes.created_by in (".implode(",",get_user_in_array()).")";

//$where .= " and notes.created_by = users.id ";
$where .= " and ( notes.date_entered ".$date_text." or notes.date_modified ". $date_text.")";
$where .= " and notes.deleted = 0";

?>