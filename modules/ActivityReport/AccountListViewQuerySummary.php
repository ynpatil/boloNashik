<?php

$where = "";

if(isset($user) && !empty($user))
$where = " users.id IN ($user)";

if(!empty($where))
$where .= " and ";

$where .= " accounts.assigned_user_id = users.id ";

if(isset($branch) && !empty($branch))
$where .= " and branch_mast.id = ".$branch;

if(isset($vertical) && !empty($vertical))
$where .= " and verticals_mast.id = ".$vertical;

$where .= " and ( accounts.date_entered ".$date_text." or accounts.date_modified ". $date_text.")";
$where .= " and accounts.deleted = 0";

?>