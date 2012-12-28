<?php

$entity = new Meeting();

$query = $entity->get_summary_query($where);
//echo "Query for movement :".$query;
$result = $entity->db->query($query, false);

global $meeting_summary_list;

while (($row = $entity->db->fetchByAssoc($result)) != null) {
	$meeting_summary_list[$row['assigned_user_id']] = Array('count'=>$row['count'],'field_minutes'=>$row['field_minutes']);
}
?>
