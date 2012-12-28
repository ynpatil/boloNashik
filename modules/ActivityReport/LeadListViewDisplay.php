<?php
//om
$lead = new Lead();

$focus_leads_list = $lead->get_full_list("leads.date_entered", $where);

$lead_list = array();

if(count($focus_leads_list)>0)
foreach ($focus_leads_list as $lead) {
		$lead_list[] = Array('id' => $lead->id,
									 'type' => 'Lead',
									 'module' => "Leads",
									 'first_name' => $lead->first_name,
									 'last_name' => $lead->last_name,
									 'refered_by' => $lead->refered_by,
									 'lead_source' => $lead->lead_source,
									 'lead_source_description' => $lead->lead_source_description,
									 );
	}

?>
