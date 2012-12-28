<?php

$entity = new Contact();

$query = $entity->get_summary_query($where);

$result = $entity->db->query($query, false);

global $contact_summary_list;

while (($row = $entity->db->fetchByAssoc($result)) != null) {

	$contact_summary_list[$row['assigned_user_id']] = $row['count'];
}

?>
