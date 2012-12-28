<?php

$entity = new User();

$query = $entity->get_summary_query($where);
$result = $entity->db->query($query, false);

$user_summary_list = array();
global $call_summary_list,$meeting_summary_list;
global $unique_account_list,$duplicate_meeting_list;
global $duplicate_call_list;

$branch_summary_list = array();
$suboffice_summary_list = array();

while (($row = $entity->db->fetchByAssoc($result)) != null) {
    $id = $row['assigned_user_id'];
    $total_count = 0;
    $meeting_count = $meeting_summary_list[$id];

    $call_count = $call_summary_list[$id];
    $unique_account_count = count($unique_account_list[$id]);
    $duplicate_meeting_count = $duplicate_meeting_list[$id];
    $duplicate_call_count = $duplicate_call_list[$id];

    $total_count = $meeting_count + $call_count;
    $user_summary_list[$row['assigned_user_id']] = Array('id' => $id,'USER' => $row['assigned_user_name'],'REPORTS_TO_NAME' => $row['reports_to_name'],
        'USER_TYPE_NAME' => $row['user_type_name'],'VERTICAL' => $row['vertical_name'],'BRANCH' => $row['branch_name'],'MEETINGS' => $meeting_count,
        'CALLS' => $call_count,'TASKS' => $task_count,'UNIQUE_ACCOUNT_COUNT' => $unique_account_count,'SUBOFFICE' => $row['suboffice_name'],
        'DUPLICATE_MEETING_COUNT'=> $duplicate_meeting_count,'DUPLICATE_CALL_COUNT'=> $duplicate_call_count,'TOTAL_COUNT'=> $total_count);

    $branch_summary_list[$row['branch_name']]['BRANCH'] = $row['branch_name'];

    $branch_summary_list[$row['branch_name']]['CALLS'] += $call_count;
    $branch_summary_list[$row['branch_name']]['CALLS_DUPLICATE'] += $duplicate_call_count;

    $branch_summary_list[$row['branch_name']]['MEETINGS'] += $meeting_count;
    $branch_summary_list[$row['branch_name']]['MEETINGS_DUPLICATE'] += $duplicate_meeting_count;

    $branch_summary_list[$row['branch_name']]['UNIQUE_ACCOUNTS'] += $unique_account_count;

    $suboffice_summary_list[$row['suboffice_name']]['SUBOFFICE'] = $row['suboffice_name'];

    $suboffice_summary_list[$row['suboffice_name']]['CALLS'] += $call_count;
    $suboffice_summary_list[$row['suboffice_name']]['CALLS_DUPLICATE'] += $duplicate_call_count;

    $suboffice_summary_list[$row['suboffice_name']]['MEETINGS'] += $meeting_count;
    $suboffice_summary_list[$row['suboffice_name']]['MEETINGS_DUPLICATE'] += $duplicate_meeting_count;

    $suboffice_summary_list[$row['suboffice_name']]['UNIQUE_ACCOUNTS'] += $unique_account_count;
}

?>
