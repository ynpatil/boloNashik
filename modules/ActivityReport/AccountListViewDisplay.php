<?php

$account = new Account();
$focus_account_list = $account->get_full_list("accounts.date_entered", $where);

$account_list = array();

if(count($focus_account_list)>0)
foreach ($focus_account_list as $account) {
		$account_list[] = Array('id' => $account->id,
									 'type' => 'Account',
									 'module' => "Accounts",
									 'name' => $account->name,
									 'phone_office' => $account->phone_office,
									 'billing_address_city' => $account->billing_address_city_desc,
									 'billing_address_state' => $account->billing_address_state_desc,
									 );
	}
?>
