<?php

$contact = new Contact();
$focus_contact_list = $contact->get_full_list("contacts.date_entered", $where);

$contact_list = array();

if(count($focus_contact_list)>0)
foreach ($focus_contact_list as $contact) {
		$contact_list[] = Array('id' => $contact->id,
									 'type' => 'Contact',
									 'module' => "Contacts",
									 'first_name' => $contact->first_name,
									 'last_name' => $contact->last_name,
									 'account_id' => $contact->account_id,
									 'account_name' => $contact->account_name,
									 'email1' => $contact->email1,
									 'phone_work' => $contact->phone_work,
									 );
	}

?>
