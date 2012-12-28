<?php

$entity = new Account();

$query = $entity->get_summary_query($where);

$result = $entity->db->query($query, false);

global $account_summary_list;

while (($row = $entity->db->fetchByAssoc($result)) != null) {

	$account_summary_list[$row['assigned_user_id']] = $row['count'];
}

?>
