<?php

$where = "";

if(isset($user) && !empty($user))
$where = " meetings.assigned_user_id IN ($user)";

if(!empty($where))
$where .= " and ";

$where .= " meetings.assigned_user_id = users.id ";

if(isset($branch) && !empty($branch))
$where .= " and branch_mast.id = ".$branch;

if(isset($vertical) && !empty($vertical))
$where .= " and verticals_mast.id = ".$vertical;

$where .= " and ( meetings.date_start ".$date_text.")";
$where .= " and meetings.deleted = 0";

$GLOBALS['log']->debug("date_text :".$date_text);
?>
