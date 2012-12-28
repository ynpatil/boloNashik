<?php

$where = " tasks_requests.created_by='".$current_user->id."'";
$where .= " and tasks_requests.deleted = 0";

?>