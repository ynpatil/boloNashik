<?php

$email = new Email();

$focus_emails_list = $email->get_full_list("emails.date_entered", $where);

if(count($focus_emails_list)>0)
foreach ($focus_emails_list as $email) {
	$open_activity_list[] = Array('name' => $email->name,
									 'id' => $email->id,
									 'type' => "Email",
									 'direction' => '',
									 'module' => "Emails",
									 'status' => '',
									 'parent_id' => $email->parent_id,
									 'parent_type' => $email->parent_type,
									 'parent_name' => $email->parent_name,
									 'contact_id' => $email->contact_id,
									 'contact_name' => $email->contact_name,
									 'date_modified' => $email->date_start." ".$email->time_start
									 );
}

?>