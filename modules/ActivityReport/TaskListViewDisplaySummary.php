<?php

$entity = new Task();

$query = $entity->get_summary_query($where);

$result = $entity->db->query($query, false);

global $task_summary_list;

while (($row = $entity->db->fetchByAssoc($result)) != null) {

	$task_summary_list[$row['assigned_user_id']] = $row['count'];
}

$query = $entity->get_summary_query($whereLateEntry);

$result = $entity->db->query($query, false);

global $task_summary_list_late;

while (($row = $entity->db->fetchByAssoc($result)) != null) {

	$task_summary_list_late[$row['assigned_user_id']] = $row['count'];
}

//$GLOBALS['log']->debug("Query :".$meeting_summary_list);
?>
