<?php

$task = new Task();

$focus_tasks_list = $task->get_full_list("time_start", $where);

if(count($focus_tasks_list)>0)
foreach ($focus_tasks_list as $task) {
  		$td =  $timedate->merge_date_time($task->date_start, $task->time_start);

		$open_activity_list[] = Array('name' => $task->name,
									 'id' => $task->id,
									 'type' => 'Task',
									 'module' => "Tasks",
									 'status' => $task->status,
									 'parent_id' => $task->parent_id,
									 'parent_type' => $task->parent_type,
									 'parent_name' => $task->parent_name,
									 'contact_id' => $task->contact_id,
									 'contact_name' => $task->contact_name,
									 'date_due' => $date_due,
									 'normal_date_start' => $task->date_start,
									 'date_start' => $timedate->to_display_date($td),
									 'normal_time_start' => $task->time_start,
									 'time_start' => $timedate->to_display_time($td,true),
									 );
	}

?>
