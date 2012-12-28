<?php

$where = "";

if(isset($user) && !empty($user))
$where = " users.id IN ($user)";

if(isset($branch) && !empty($branch)){

if(!empty($where))
$where .= " and ";

$where .= " branch_mast.id = ".$branch;
}

if(isset($vertical) && !empty($vertical)){

if(!empty($where))
$where .= " and ";

$where .= " verticals_mast.id = ".$vertical;
}

if(!empty($where))
$where .= " and ";

$where .= " users.deleted = 0 and users.status = 'Active'";

//$GLOBALS['log']->debug("date_text :".$date_text);
?>
