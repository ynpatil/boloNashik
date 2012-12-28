<?php

$project = new Project();

$focus_projects_list = $project->get_full_list("project.date_entered", $where);

//print("Total call hrs :".$total_call_hrs." ".$total_call_mts);

$project_list = array();

if(count($focus_projects_list)>0)
foreach ($focus_projects_list as $project) {
		$project_list[] = Array('id' => $project->id,
					 'name' => $project->name,
					 'type' => 'Project',
					 'module' => "Project",
					 'assigned_user_name' => $project->assigned_user_name,
					 'total_estimated_effort' => $project->total_estimated_effort,
					 'total_actual_effort' => $project->total_actual_effort,
					 );
}
?>