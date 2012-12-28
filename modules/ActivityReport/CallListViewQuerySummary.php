<?php

$where = "";

if(isset($user) && !empty($user))
$where = " users.id IN ($user)";

if(!empty($where))
$where .= " and ";

$where .= " calls.assigned_user_id = users.id ";

if(isset($branch) && !empty($branch))
$where .= " and branch_mast.id = ".$branch;

if(isset($vertical) && !empty($vertical))
$where .= " and verticals_mast.id = ".$vertical;

if(!empty($where))
$where .= " and ";

$where .= " ( calls.date_entered ".$date_text." or calls.date_modified ". $date_text.")";
$where .= " and calls.deleted = 0";

$whereLateEntry = $where;
$whereLateEntry .= " and datediff( calls.date_entered, calls.date_start ) >4";

//$GLOBALS['log']->debug("date_text :".$date_text);
?>
