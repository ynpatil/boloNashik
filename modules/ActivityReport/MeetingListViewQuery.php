<?php

if(isset($user) && !empty($user))
$where = " meetings.assigned_user_id IN ($user)";
$where .= " and meetings.assigned_user_id = users.id ";
$where .= " and ( meetings.date_entered ".$date_text." or meetings.date_modified ". $date_text.")";
$where .= " and meetings.deleted = 0";

#$GLOBALS['log']->debug("date_text :".$date_text);
?>