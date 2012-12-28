<?php

$where = "";

if(isset($user) && !empty($user))
$where = " users.id IN ($user)";

if(!empty($where))
$where .= " and ";

$where .= " tasks.assigned_user_id = users.id ";

if(isset($branch) && !empty($branch))
$where .= " and branch_mast.id = ".$branch;

if(isset($vertical) && !empty($vertical))
$where .= " and verticals_mast.id = ".$vertical;

if(!empty($where))
$where .= " and ";

$where .= " ( tasks.date_start ".$date_text.")";
$where .= " and tasks.deleted = 0";

?>
