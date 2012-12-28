<?PHP
$app_list_strings['moduleList']['TeamsOS']='TeamsOS';
$app_list_strings['moduleListSingular']['TeamsOS'] = 'TeamOS';

// Added this to allow Teams to display as a subpanel without having it as a tab
global $modules_exempt_from_availability_check;
global $app_list_strings;
$modules_exempt_from_availability_check['TeamsOS'] = 'TeamsOS';

$app_strings['LBL_TEAM']='Asignado al equipo:';
$app_strings['LBL_NO_TEAM']='--Ninguno--';

//orgchart addon
$app_strings['LBL_ORGCHART_BUTTON_TITLE'] = 'Organigrama';
$app_strings['LBL_ORGCHART_REPORTS'] = 'INFORMES A';
$app_strings['LBL_ORGCHART_NOONE'] = '---NADIE---';
?>
