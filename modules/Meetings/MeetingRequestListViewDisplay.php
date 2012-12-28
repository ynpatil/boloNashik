<?php

$account = new MeetingRequest();
$focus_account_list = $account->get_full_list("meetings_requests.date_entered", $where);

$account_list = array();

if(count($focus_account_list)>0)
foreach ($focus_account_list as $account) {
		$account_list[] = Array('id' => $account->id,
									 'type' => 'Meeting',
									 'module' => "Meetings",
									 'name' => $account->name,
									 'parent_id' => $account->parent_id,
									 'location' => $account->location,
									 'description' => $account->description,
									 'parent_type' => $account->parent_type,
									 'parent_name' => $account->parent_name,
									 );
	}
?>
