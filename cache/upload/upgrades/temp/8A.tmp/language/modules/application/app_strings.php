<?PHP
$app_list_strings['moduleList']['TeamsOS']='TeamsOS';
$app_list_strings['moduleListSingular']['TeamsOS'] = 'TeamOS';

// Added this to allow Teams to display as a subpanel without having it as a tab
global $modules_exempt_from_availability_check;
global $app_list_strings;
$modules_exempt_from_availability_check['TeamsOS'] = 'TeamsOS';

$app_strings['LBL_TEAM']='Assigned Team:';
$app_strings['LBL_NO_TEAM']='--None--';

//orgchart addon
$app_strings['LBL_ORGCHART_BUTTON_TITLE'] = 'OrgChart';
$app_strings['LBL_ORGCHART_REPORTS'] = 'REPORTS TO';
$app_strings['LBL_ORGCHART_NOONE'] = '---NO ONE---';
?>
