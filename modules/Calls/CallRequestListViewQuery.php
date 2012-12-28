<?php

$where = " calls_requests.created_by='".$current_user->id."'";
$where .= " and calls_requests.deleted = 0";

?>