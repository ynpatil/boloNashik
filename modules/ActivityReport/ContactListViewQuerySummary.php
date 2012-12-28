<?php

$where = "";

if(isset($user) && !empty($user))
$where = " users.id IN ($user)";

if(!empty($where))
$where .= " and ";

$where .= " contacts.assigned_user_id = users.id ";

if(isset($branch) && !empty($branch))
$where .= " and branch_mast.id = ".$branch;

if(isset($vertical) && !empty($vertical))
$where .= " and verticals_mast.id = ".$vertical;

$where .= " and ( contacts.date_entered ".$date_text." or contacts.date_modified ". $date_text.")";
$where .= " and contacts.deleted = 0";

?>