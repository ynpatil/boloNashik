<?php

$where = " contacts_requests.created_by='".$current_user->id."'";
$where .= " and contacts_requests.deleted = 0";

?>