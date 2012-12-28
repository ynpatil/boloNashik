<?php

$entity = new User();

$query = $entity->get_summary_query($where);
$result = $entity->db->query($query, false);

$user_summary_list = array();
global $call_summary_list,$meeting_summary_list,$task_summary_list;

$branch_summary_list = array();
$suboffice_summary_list = array();

while (($row = $entity->db->fetchByAssoc($result)) != null) {
	$id = $row['assigned_user_id'];
	$total_count = 0;
	$meeting_count = $meeting_summary_list[$id];
	
        $meeting_count_late = $meeting_summary_list_late[$id];
	$call_count = $call_summary_list[$id];
	$call_count_late = $call_summary_list_late[$id];
	$task_count = $task_summary_list[$id];
	$task_count_late = $task_summary_list_late[$id];

	$account_count = $account_summary_list[$id];
	$contact_count = $contact_summary_list[$id];

	$total_count = $meeting_count + $call_count + $task_count + $account_count + $contact_count;
	$total_count_late = $meeting_count_late + $call_count_late + $task_count_late;
	
	$user_summary_list[$row['assigned_user_id']] = Array('id' => $id,'USER' => $row['assigned_user_name'],'REPORTS_TO_NAME' => $row['reports_to_name'],
	'USER_TYPE_NAME' => $row['user_type_name'],'VERTICAL' => $row['vertical_name'],'BRANCH' => $row['branch_name'],'MEETINGS' => $meeting_count,'MEETINGS_LATE' => $meeting_count_late,
	'CALLS' => $call_count,'CALLS_LATE' => $call_count_late,'TASKS' => $task_count,'TASKS_LATE' => $task_count_late,'ACCOUNTS' => $account_count,
	'SUBOFFICE' => $row['suboffice_name'],'CONTACTS' => $contact_count,'TOTAL_COUNT' => $total_count);
	
	$branch_summary_list[$row['branch_name']]['BRANCH'] = $row['branch_name'];

	$branch_summary_list[$row['branch_name']]['CALLS'] += $call_count;
	$branch_summary_list[$row['branch_name']]['CALLS_LATE'] += $call_count_late;

	$branch_summary_list[$row['branch_name']]['MEETINGS'] += $meeting_count;
	$branch_summary_list[$row['branch_name']]['MEETINGS_LATE'] += $meeting_count_late;

	$branch_summary_list[$row['branch_name']]['TASKS'] += $task_count;
	$branch_summary_list[$row['branch_name']]['TASKS_LATE'] += $task_count_late;

	$branch_summary_list[$row['branch_name']]['ACCOUNTS'] += $account_count;
	$branch_summary_list[$row['branch_name']]['CONTACTS'] += $contact_count;

	$branch_summary_list[$row['branch_name']]['TOTAL_COUNT'] += $total_count;
	$branch_summary_list[$row['branch_name']]['TOTAL_COUNT_LATE'] += $total_count_late;

	$suboffice_summary_list[$row['suboffice_name']]['SUBOFFICE'] = $row['suboffice_name'];

	$suboffice_summary_list[$row['suboffice_name']]['CALLS'] += $call_count;
	$suboffice_summary_list[$row['suboffice_name']]['CALLS_LATE'] += $call_count_late;

	$suboffice_summary_list[$row['suboffice_name']]['MEETINGS'] += $meeting_count;
	$suboffice_summary_list[$row['suboffice_name']]['MEETINGS_LATE'] += $meeting_count_late;

	$suboffice_summary_list[$row['suboffice_name']]['TASKS'] += $task_count;
	$suboffice_summary_list[$row['suboffice_name']]['TASKS_LATE'] += $task_count_late;

	$suboffice_summary_list[$row['suboffice_name']]['ACCOUNTS'] += $account_count;
	$suboffice_summary_list[$row['suboffice_name']]['CONTACTS'] += $contact_count;

        $suboffice_summary_list[$row['suboffice_name']]['TOTAL_COUNT'] += $total_count;
	$suboffice_summary_list[$row['suboffice_name']]['TOTAL_COUNT_LATE'] += $total_count_late;
}

//print_r($branch_summary_list);

//$GLOBALS['log']->debug("Query :".$meeting_summary_list);
?>
