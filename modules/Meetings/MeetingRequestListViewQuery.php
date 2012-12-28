<?php

$where = " meetings_requests.created_by='".$current_user->id."'";
$where .= " and meetings_requests.deleted = 0";

?>