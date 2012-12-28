<?php

$entity = new Task();

$query = $entity->get_summary_query($where);

$result = $entity->db->query($query, false);

global $task_summary_list;

while (($row = $entity->db->fetchByAssoc($result)) != null) {

	$task_summary_list[$row['assigned_user_id']] = $row['count'];
}

?>
