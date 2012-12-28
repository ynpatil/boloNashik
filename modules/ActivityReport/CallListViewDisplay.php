<?php

$call = new Call();

$focus_calls_list = $call->get_full_list("time_start", $where);

//print("In CallDisplay ".count($focus_calls_list));

if (count($focus_calls_list)>0)
  foreach ($focus_calls_list as $call) {
    $recipients = $call->get_notification_recipients();
  	$td =  $timedate->merge_date_time($call->date_start, $call->time_start);
	$open_activity_list[] = Array('name' => $call->name,
								 'id' => $call->id,
								 'type' => "Call",
								 'module' => "Calls",
								 'status' => $call->status,
								 'parent_id' => $call->parent_id,
								 'parent_type' => $call->parent_type,
								 'parent_name' => $call->parent_name,
								 'contact_id' => $call->contact_id,
								 'contact_name' => $call->contact_name,
								 'date_start' =>  $timedate->to_display_date($td),
								 'normal_date_start' => $call->date_start,
								 'normal_time_start' => $call->time_start,
								 'time_start' =>$timedate->to_display_time($td,true),
								 'required' => $call->required,
								 'accept_status' => $call->accept_status,
								 'contacts_arr' => $recipients,
								 );
$total_call_hrs = ($total_call_hrs + $call->duration_hours);
//print("Mts :".$call->duration_minutes);
$total_call_mts = ($total_call_mts + $call->duration_minutes);
}

?>