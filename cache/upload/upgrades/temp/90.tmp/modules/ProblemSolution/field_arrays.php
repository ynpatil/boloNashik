<?php
if(empty($GLOBALS['sugarEntry'])) die('Not A Valid Entry Point');
/*********************************************************************************
 * 
 ********************************************************************************/
$fields_array['ProblemSolution'] = array ('column_fields' => array(
		'id',
		'date_entered',
		'date_modified',
		'assigned_user_id',
		'modified_user_id',
		'created_by',
		'name',
		'status',
//		'date_due',
//		'time_due',
//		'date_start',
//		'time_start',
		'parent_id',
//		'priority',
		'description',
		'order_number',
		'solution_number',
		'depends_on_id',
//		'milestone_flag',
//		'estimated_effort',
//		'actual_effort',
//		'utilization',
//		'percent_complete',
		'deleted',
	),
        'list_fields' =>  array(
		'id',
		'parent_id',
		'parent_name',
//		'priority',
		'name',
		'order_number',
//		'date_start',
//		'date_due',
//		'percent_complete',
		'status',
		'assigned_user_id',
		'depends_on_id',
		'assigned_user_name',
	),
    'required_fields' =>  array('name'=>1, 'parent_id'=>2,),
);
?>
