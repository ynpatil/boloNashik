<?php

$account = new CallRequest();
$focus_account_list = $account->get_full_list("calls_requests.date_entered", $where);

$account_list = array();

if(count($focus_account_list)>0)
foreach ($focus_account_list as $account) {
		$account_list[] = Array('id' => $account->id,
									 'type' => 'Call',
									 'module' => "Calls",
									 'name' => $account->name,
									 'parent_id' => $account->parent_id,
									 'description' => $account->description,
									 'parent_type' => $account->parent_type,
									 'parent_name' => $account->parent_name,
									 );
	}
?>
