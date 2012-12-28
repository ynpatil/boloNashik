<?php

if(isset($user) && !empty($user))
$where = " opportunities.assigned_user_id IN($user)";
else
$where = " opportunities.assigned_user_id in (".implode(",",get_user_in_array()).")";

if(isset($_REQUEST['fromsession']))
{
	if($_REQUEST['fromsession'] == 'salesstage_user')
	{
		$where .= " and ( opportunities.date_closed >='".$_SESSION['cbss_date_start']."' and opportunities.date_closed<='".$_SESSION['cbss_date_end']."')";
		$where .=" and opportunities.sales_stage='".$_REQUEST['sales_stage']."'";
	}
	else
		$where .= " and ( opportunities.date_entered ".$date_text." or opportunities.date_modified ". $date_text.")";
}

$where .= " and opportunities.deleted = 0";

?>
