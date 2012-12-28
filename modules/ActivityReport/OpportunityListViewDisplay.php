<?php
//om
$opportunity = new Opportunity();

//print("Where clause :".$where);
$focus_opportunities_list = $opportunity->get_full_list("opportunities.date_entered", $where);

$opportunity_list = array();

if(count($focus_opportunities_list)>0)
foreach ($focus_opportunities_list as $opportunity) {
		$opportunity_list[] = Array('id' => $opportunity->id,
									 'type' => 'Opportunity',
									 'module' => "Opportunities",
									 'name' => $opportunity->name,
									 'account_id' => $opportunity->account_id,
									 'account_name' => $opportunity->account_name,
									 'date_closed' => $opportunity->date_closed,
									 );
	}

?>