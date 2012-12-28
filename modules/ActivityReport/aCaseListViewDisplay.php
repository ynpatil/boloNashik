<?php

$case = new aCase();
$focus_cases_list = $case->get_full_list("date_entered", $where);

$case_list = array();

if(count($focus_cases_list)>0)
foreach ($focus_cases_list as $case) {
		$case_list[] = Array('id' => $case->id,
									 'name' => $case->name,
									 'type' => 'Case',
									 'module' => "Cases",
									 'number' => $case->number,
									 'account_id' => $case->account_id,
									 'account_name' => $case->account_name,
									 'status' => $case->status,
									 );
}

?>