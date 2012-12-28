<?php

$meeting = new Meeting();

$focus_meetings_list = $meeting->get_full_list("time_start", $where);
$total_meeting_field_minutes = 0;

if (count($focus_meetings_list)>0)
  foreach ($focus_meetings_list as $meeting) {
  	$td =  $timedate->merge_date_time($meeting->date_start, $meeting->time_start);
	$open_activity_list[] = Array('name' => $meeting->name,
								 'id' => $meeting->id,
								 'type' => "Meeting",
								 'module' => "Meetings",
								 'status' => $meeting->status,
								 'parent_id' => $meeting->parent_id,
								 'parent_type' => $meeting->parent_type,
								 'parent_name' => $meeting->parent_name,
								 'contact_id' => $meeting->contact_id,
								 'contact_name' => $meeting->contact_name,
                                                                 'location' => $meeting->location,
								 'normal_date_start' => $meeting->date_start,
								 'date_start' => $timedate->to_display_date($td),
								 'normal_time_start' => $meeting->time_start,
								 'time_start' => $timedate->to_display_time($td,true),
								 'required' => $meeting->required,
                                                                 'duration' => $meeting->duration_hours .":".$meeting->duration_minutes,
								 'accept_status' => $meeting->accept_status,
								 );

//echo " ".$timedate->to_display_time($td,true)."value".intval(array_pop(array_reverse(split(":", $timedate->to_display_time($td,true)))));

if(intval(array_pop(array_reverse(split(":", $timedate->to_display_time($td,true))))) >12){
    $count_second_half += 1;
}
else{
    $count_first_half += 1;
}
$total_meeting_hrs = ($total_meeting_hrs + $meeting->duration_hours);
$total_meeting_mts = ($total_meeting_mts + $meeting->duration_minutes);
$total_meeting_field_minutes += $meeting->field_minutes;
}
?>