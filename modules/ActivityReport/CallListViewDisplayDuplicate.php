<?php

$entity = new Call();

$query = $entity->get_duplicate_records_count_userwise($where);

$result = $entity->db->query($query, false);

global $call_summary_list,$unique_account_list,$duplicate_call_list;

while (($row = $entity->db->fetchByAssoc($result)) != null) {
	$call_summary_list[$row['assigned_user_id']] += $row['count'];
        if($row['count']>1)
        $duplicate_call_list[$row['assigned_user_id']] += 1;
        else
        $duplicate_call_list[$row['assigned_user_id']] += 0;

        $unique_account_list[$row['assigned_user_id']][$row['parent_id']] = 1;
}
?>
