<?php

$entity = new Call();

$query = $entity->get_summary_query($where);

$result = $entity->db->query($query, false);

global $call_summary_list;

while (($row = $entity->db->fetchByAssoc($result)) != null) {

	$call_summary_list[$row['assigned_user_id']] = $row['count'];
}

$query = $entity->get_summary_query($whereLateEntry);

$result = $entity->db->query($query, false);

global $call_summary_list_late;

while (($row = $entity->db->fetchByAssoc($result)) != null) {

	$call_summary_list_late[$row['assigned_user_id']] = $row['count'];
}

?>
