<?php

$entity = new User();

$query = $entity->get_summary_query($where);
$result = $entity->db->query($query, false);

$user_summary_list = array();
global $call_summary_list,$meeting_summary_list,$task_summary_list;

$branch_summary_list = array();
$suboffice_summary_list = array();

global $from_date,$to_date;
$no_of_working_days = get_working_days($from_date, $to_date);
$GLOBALS['log']->info("No of working days ".$no_of_working_days);

$compliance = new Compliance();
$compliance_summary_list = $compliance->get_compliance_data();

while (($row = $entity->db->fetchByAssoc($result)) != null) {
    $id = $row['assigned_user_id'];
    $total_count = 0;
    //        $GLOBALS['log']->info("Meeting :".$meeting_summary_list[$id]);
    $meeting_count = $meeting_summary_list[$id]['count'];
    $call_count = $call_summary_list[$id];
    $task_count = $task_summary_list[$id];

    $average_field_time = round($meeting_summary_list[$id]['field_minutes']/$no_of_working_days,2);
    //	$account_count = $account_summary_list[$id];
    //	$contact_count = $contact_summary_list[$id];

    $total_count = $meeting_count+ $call_count + $task_count;// + $account_count + $contact_count;

    $compliance_percentage = array();
    foreach($compliance_summary_list as $compliance=>$value) {
        if($compliance == "Meetings-Meetings") {
            $benchmark_value = intval($value);
//            $GLOBALS['log']->info("Compliance Value for Branch id ".$compliance." = ".$benchmark_value);

            if($benchmark_value>0)
                $benchmark_percentage = round((intval($meeting_count) / ($benchmark_value*$no_of_working_days)) * 100);
            else
                $benchmark_percentage = "N.A";

            $compliance_percentage[$compliance] = $benchmark_percentage;
        }
        else if($compliance == "Meetings-Calls") {
            $benchmark_value = intval($value);
//            $GLOBALS['log']->info("Compliance Value for Branch id ".$compliance." = ".$benchmark_value);

            if($benchmark_value>0)
                $benchmark_percentage = round(( (intval($meeting_count) + intval($call_count)) / ($benchmark_value*$no_of_working_days)) * 100);
            else
                $benchmark_percentage = "N.A";

            $compliance_percentage[$compliance] = $benchmark_percentage;
        }
        else if($compliance == "Meetings-Tasks") {
            $benchmark_value = intval($value);
//            $GLOBALS['log']->info("Compliance Value for Branch id ".$compliance." = ".$benchmark_value);

            if($benchmark_value>0)
                $benchmark_percentage = round(( (intval($meeting_count) + intval($task_count)) / ($benchmark_value*$no_of_working_days)) * 100);
            else
                $benchmark_percentage = "N.A";

            $compliance_percentage[$compliance] = $benchmark_percentage;
        }
    }
    
    $user_summary_list[$row['assigned_user_id']] = Array('id' => $id,'USER' => $row['assigned_user_name'],'REPORTS_TO_NAME' => $row['reports_to_name'],
        'USER_TYPE_NAME' => $row['user_type_name'],'VERTICAL' => $row['vertical_name'],'BRANCH' => $row['branch_name'],'MEETINGS' => $meeting_count,
        'CALLS' => $call_count,'TASKS' => $task_count,'SUBOFFICE' => $row['suboffice_name'],'AVERAGE_FIELD_TIME' => $average_field_time,
        'COMPLIANCE' => $compliance_percentage,'TOTAL_COUNT' => $total_count);

    $branch_summary_list[$row['branch_name']]['BRANCH'] = $row['branch_name'];
    $branch_summary_list[$row['branch_name']]['CALLS'] += $call_count;
    $branch_summary_list[$row['branch_name']]['MEETINGS'] += $meeting_count;
    $branch_summary_list[$row['branch_name']]['TASKS'] += $task_count;
    //	$branch_summary_list[$row['branch_name']]['ACCOUNTS'] += $account_count;
    //	$branch_summary_list[$row['branch_name']]['CONTACTS'] += $contact_count;
    $branch_summary_list[$row['branch_name']]['TOTAL_COUNT'] += $total_count;

    $suboffice_summary_list[$row['suboffice_name']]['SUBOFFICE'] = $row['suboffice_name'];
    $suboffice_summary_list[$row['suboffice_name']]['CALLS'] += $call_count;
    $suboffice_summary_list[$row['suboffice_name']]['MEETINGS'] += $meeting_count;
    $suboffice_summary_list[$row['suboffice_name']]['TASKS'] += $task_count;
    //	$suboffice_summary_list[$row['suboffice_name']]['ACCOUNTS'] += $account_count;
    //	$suboffice_summary_list[$row['suboffice_name']]['CONTACTS'] += $contact_count;
    $suboffice_summary_list[$row['suboffice_name']]['TOTAL_COUNT'] += $total_count;
//    break;
}

//print_r($branch_summary_list);

//$GLOBALS['log']->debug("Query :".$meeting_summary_list);
?>
