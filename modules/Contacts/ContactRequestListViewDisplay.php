<?php

$account = new ContactRequest();
$focus_account_list = $account->get_full_list("contacts_requests.date_entered", $where);

$account_list = array();

if(count($focus_account_list)>0)
foreach ($focus_account_list as $account) {
		$account_list[] = Array('id' => $account->id,
									 'type' => 'Contact',
									 'module' => "Contacts",
									 'first_name' => $account->first_name,
									 'last_name' => $account->last_name,
									 'phone_work' => $account->phone_work,
									 'phone_mobile' => $account->phone_mobile,
									 'email1' => $account->email1,
									 'description' => $account->description,
									 );
	}
?>
