<?php

$entity = new Meeting();

$query = $entity->get_duplicate_records_count_userwise($where);
$result = $entity->db->query($query, false);

global $meeting_summary_list,$unique_account_list,$duplicate_meeting_list;

while (($row = $entity->db->fetchByAssoc($result)) != null) {
	$meeting_summary_list[$row['assigned_user_id']] += $row['count'];
        if($row['count']>1)
        $duplicate_meeting_list[$row['assigned_user_id']] += 1;
        else
        $duplicate_meeting_list[$row['assigned_user_id']] += 0;
                
        $unique_account_list[$row['assigned_user_id']][$row['parent_id']] = 1;
}

?>