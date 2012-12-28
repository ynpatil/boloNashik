<?php

$where = " accounts_requests.created_by='".$current_user->id."'";
$where .= " and accounts_requests.deleted = 0";

?>