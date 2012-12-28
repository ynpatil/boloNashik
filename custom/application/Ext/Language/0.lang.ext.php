<?php 
 //WARNING: The contents of this file are auto-generated


/*****************************************************************************
 * The contents of this file are subject to the RECIPROCAL PUBLIC LICENSE
 * Version 1.1 ("License"); You may not use this file except in compliance
 * with the License. You may obtain a copy of the License at
 * http://opensource.org/licenses/rpl.php. Software distributed under the
 * License is distributed on an "AS IS" basis, WITHOUT WARRANTY OF ANY KIND,
 * either express or implied.
 *
 * You may:
 * a) Use and distribute this code exactly as you received without payment or
 *    a royalty or other fee.
 * b) Create extensions for this code, provided that you make the extensions
 *    publicly available and document your modifications clearly.
 * c) Charge for a fee for warranty or support or for accepting liability
 *    obligations for your customers.
 *
 * You may NOT:
 * a) Charge for the use of the original code or extensions, including in
 *    electronic distribution models, such as ASP (Application Service
 *    Provider).
 * b) Charge for the original source code or your extensions other than a
 *    nominal fee to cover distribution costs where such distribution
 *    involves PHYSICAL media.
 * c) Modify or delete any pre-existing copyright notices, change notices,
 *    or License text in the Licensed Software
 * d) Assert any patent claims against the Licensor or Contributors, or
 *    which would in any way restrict the ability of any third party to use the
 *    Licensed Software.
 *
 * You must:
 * a) Document any modifications you make to this code including the nature of
 *    the change, the authors of the change, and the date of the change.
 * b) Make the source code for any extensions you deploy available via an
 *    Electronic Distribution Mechanism such as FTP or HTTP download.
 * c) Notify the licensor of the availability of source code to your extensions
 *    and include instructions on how to acquire the source code and updates.
 * d) Grant Licensor a world-wide, non-exclusive, royalty-free license to use,
 *    reproduce, perform, modify, sublicense, and distribute your extensions.
 *
 * The Original Code is: AnySoft Informatica
 *                       Marcelo Leite (aka Mr. Milk)
 *                       2005-10-01 mrmilk@anysoft.com.br
 *
 * The Initial Developer of the Original Code is AnySoft Informatica Ltda.
 * Portions created by AnySoft are Copyright (C) 2005 AnySoft Informatica Ltda
 * All Rights Reserved.
 ********************************************************************************/
$app_strings['LBL_ORGCHART_BUTTON_TITLE'] = 'OrgChart';
$app_strings['LBL_ORGCHART_REPORTS'] = 'REPORTS TO';
$app_strings['LBL_ORGCHART_NOONE'] = '---NO ONE---';



/*****************************************************************************
 * The contents of this file are subject to the RECIPROCAL PUBLIC LICENSE
 * Version 1.1 ("License"); You may not use this file except in compliance
 * with the License. You may obtain a copy of the License at
 * http://opensource.org/licenses/rpl.php. Software distributed under the
 * License is distributed on an "AS IS" basis, WITHOUT WARRANTY OF ANY KIND,
 * either express or implied.
 *
 * You may:
 * a) Use and distribute this code exactly as you received without payment or
 *    a royalty or other fee.
 * b) Create extensions for this code, provided that you make the extensions
 *    publicly available and document your modifications clearly.
 * c) Charge for a fee for warranty or support or for accepting liability
 *    obligations for your customers.
 *
 * You may NOT:
 * a) Charge for the use of the original code or extensions, including in
 *    electronic distribution models, such as ASP (Application Service
 *    Provider).
 * b) Charge for the original source code or your extensions other than a
 *    nominal fee to cover distribution costs where such distribution
 *    involves PHYSICAL media.
 * c) Modify or delete any pre-existing copyright notices, change notices,
 *    or License text in the Licensed Software
 * d) Assert any patent claims against the Licensor or Contributors, or
 *    which would in any way restrict the ability of any third party to use the
 *    Licensed Software.
 *
 * You must:
 * a) Document any modifications you make to this code including the nature of
 *    the change, the authors of the change, and the date of the change.
 * b) Make the source code for any extensions you deploy available via an
 *    Electronic Distribution Mechanism such as FTP or HTTP download.
 * c) Notify the licensor of the availability of source code to your extensions
 *    and include instructions on how to acquire the source code and updates.
 * d) Grant Licensor a world-wide, non-exclusive, royalty-free license to use,
 *    reproduce, perform, modify, sublicense, and distribute your extensions.
 *
 * The Original Code is: AnySoft Informatica
 *                       Marcelo Leite (aka Mr. Milk)
 *                       2005-10-01 mrmilk@anysoft.com.br
 *
 * The Initial Developer of the Original Code is AnySoft Informatica Ltda.
 * Portions created by AnySoft are Copyright (C) 2005 AnySoft Informatica Ltda
 * All Rights Reserved.
 ********************************************************************************/
$app_strings['LBL_ORGCHART_BUTTON_TITLE'] = 'OrgChart';
$app_strings['LBL_ORGCHART_REPORTS'] = 'REPORTS TO';
$app_strings['LBL_ORGCHART_NOONE'] = '---NO ONE---';



$app_list_strings['moduleList']['Forums']='Forums';
$app_list_strings['moduleList']['Threads']='Threads';
$app_list_strings['moduleList']['Posts']='Posts';
$app_list_strings['moduleList']['ForumTopics']='ForumTopics';

// Added this to allow Threads to display as a subpanel without having it as a tab
global $modules_exempt_from_availability_check;
$modules_exempt_from_availability_check['Threads'] = 'Threads';




$app_strings['io_board_dom']['In'] = 'In';
$app_strings['io_board_dom']['Out'] = 'Out';
$app_strings['io_board_dom']['Lunch'] = 'Lunch';
$app_strings['io_board_dom']['Dinner'] = 'Dinner';
$app_strings['io_board_dom']['Meeting'] = 'Meeting';
$app_strings['io_board_dom']['Vacation'] = 'Vacation';
$app_strings['io_board_dom']['Out Of Town'] = 'Out Of Town';
$app_strings['io_board_dom']['Unavailable'] = 'Unavailable';

//Do not translate colors below!
$app_strings['io_board_color_dom']['In'] = 'green';
$app_strings['io_board_color_dom']['Out'] = 'red';
$app_strings['io_board_color_dom']['Lunch'] = 'yellow';
$app_strings['io_board_color_dom']['Dinner'] = 'yellow';
$app_strings['io_board_color_dom']['Meeting'] = 'yellow';
$app_strings['io_board_color_dom']['Vacation'] = 'red';
$app_strings['io_board_color_dom']['Out Of Town'] = 'red';
$app_strings['io_board_color_dom']['Unavailable'] = 'red';


/*********************************************************************************
 * APP strings and Dropdown fields for KnowledgeBase module.
 * Changes for record_type_display_notes dropdown:
 *             add ProblemSolution to the list of objects associated to Notes
 ********************************************************************************/
$app_list_strings['moduleList']['Problem']='Problems';

$app_strings['LBL_PROBLEM_SUBPANEL_TITLE']      = 'Problems';
$app_strings['LBL_CASES_SUBPANEL_TITLE']        = 'Cases';
$app_strings['LBL_SOLUTIONS_SUBPANEL_TITLE']    = 'Solutions';
$app_strings['LBL_LIST_NAME']                   = 'Name';
$app_strings['LBL_LIST_ASSIGNED_USER_ID']       = 'Assigned To';
$app_list_strings['problem_status_options']= array (
'Solved'          => 'Solved',
'Pending'         => 'Pending',
'Rejected'        => 'Rejected'
); 
$app_list_strings['problem_class_options']= array (
'Question'           => 'Question',
'Computer operation' => 'Computer operation',
'Installation'       => 'Software installation',
'Financial'          => 'Financial',
'Facilities'         => 'Facilities'
); 

$app_list_strings['moduleList']['ProblemSolution']     ='Solutions';
$app_list_strings['solution_status_options']= array (
'Approved'        => 'Approved',
'Approving'       => 'Approving',
'Rejected'        => 'Rejected'
); 
$app_list_strings['solution_priority_options']= array (
'High'            => 'High',
'Medium'          => 'Medium',
'Low'             => 'Low'
); 
$app_list_strings['record_type_display_notes']= array (
'Accounts'        => 'Account',
'Contacts'        => 'Contact',
'Opportunities'   => 'Opportunity',
'Cases'           => 'Case',
'Leads'           => 'Lead',
'Bugs'            => 'Bug',
'Emails'          => 'Email',
'Project'         => 'Project',
'ProjectTask'     => 'Project Task',
'Meetings'        => 'Meeting',
'Calls'           => 'Call',
'ProblemSolution' => 'Solution'
);
 


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



$app_list_strings['moduleList']['ZuckerDocs']='ZuckerDocs';

if (!array_key_exists('doc_category', $app_list_strings)) {
	$app_list_strings['doc_category']= array (
			'Generic' => 'Generic',
			'Business Reports' => 'Business Reports',
			'Bug Description' => 'Bug Descriptions',
			'Document Template' => 'Document Templates'
		);
}


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



$app_list_strings['moduleList']['ZuckerDocs']='ZuckerDocs';

if (!array_key_exists('doc_category', $app_list_strings)) {
	$app_list_strings['doc_category']= array (
			'Generic' => 'General',
			'Business Reports' => 'Informes de negocio',
			'Bug Description' => 'Descripci�n de incidencias',
			'Document Template' => 'Plantillas de documento'
		);
}


$app_list_strings['moduleList']['TeamsOS']='TeamsOS';
$app_list_strings['moduleListSingular']['TeamsOS'] = 'TeamOS';

// Added this to allow Teams to display as a subpanel without having it as a tab
global $modules_exempt_from_availability_check;
global $app_list_strings;
$modules_exempt_from_availability_check['TeamsOS'] = 'TeamsOS';

$app_strings['LBL_TEAM']="Assign&eacute; &agrave; l'&eacute;quipe:";
$app_strings['LBL_NO_TEAM']='--Aucune--';

//orgchart addon
$app_strings['LBL_ORGCHART_BUTTON_TITLE'] = 'OrgChart';
$app_strings['LBL_ORGCHART_REPORTS'] = 'REPORTS TO';
$app_strings['LBL_ORGCHART_NOONE'] = '---PERSONNE---';



/*****************************************************************************
 * The contents of this file are subject to the RECIPROCAL PUBLIC LICENSE
 * Version 1.1 ("License"); You may not use this file except in compliance
 * with the License. You may obtain a copy of the License at
 * http://opensource.org/licenses/rpl.php. Software distributed under the
 * License is distributed on an "AS IS" basis, WITHOUT WARRANTY OF ANY KIND,
 * either express or implied.
 *
 * You may:
 * a) Use and distribute this code exactly as you received without payment or
 *    a royalty or other fee.
 * b) Create extensions for this code, provided that you make the extensions
 *    publicly available and document your modifications clearly.
 * c) Charge for a fee for warranty or support or for accepting liability
 *    obligations for your customers.
 *
 * You may NOT:
 * a) Charge for the use of the original code or extensions, including in
 *    electronic distribution models, such as ASP (Application Service
 *    Provider).
 * b) Charge for the original source code or your extensions other than a
 *    nominal fee to cover distribution costs where such distribution
 *    involves PHYSICAL media.
 * c) Modify or delete any pre-existing copyright notices, change notices,
 *    or License text in the Licensed Software
 * d) Assert any patent claims against the Licensor or Contributors, or
 *    which would in any way restrict the ability of any third party to use the
 *    Licensed Software.
 *
 * You must:
 * a) Document any modifications you make to this code including the nature of
 *    the change, the authors of the change, and the date of the change.
 * b) Make the source code for any extensions you deploy available via an
 *    Electronic Distribution Mechanism such as FTP or HTTP download.
 * c) Notify the licensor of the availability of source code to your extensions
 *    and include instructions on how to acquire the source code and updates.
 * d) Grant Licensor a world-wide, non-exclusive, royalty-free license to use,
 *    reproduce, perform, modify, sublicense, and distribute your extensions.
 *
 * The Original Code is: AnySoft Informatica
 *                       Marcelo Leite (aka Mr. Milk)
 *                       2005-10-01 mrmilk@anysoft.com.br
 *
 * The Initial Developer of the Original Code is AnySoft Informatica Ltda.
 * Portions created by AnySoft are Copyright (C) 2005 AnySoft Informatica Ltda
 * All Rights Reserved.
 ********************************************************************************/
$app_strings['LBL_ORGCHART_BUTTON_TITLE'] = 'OrgChart';
$app_strings['LBL_ORGCHART_REPORTS'] = 'REPORTS TO';
$app_strings['LBL_ORGCHART_NOONE'] = '---NO ONE---';



$app_list_strings['moduleList']['TeamsOS']='TeamsOS';
$app_list_strings['moduleListSingular']['TeamsOS'] = 'TeamOS';

// Added this to allow Teams to display as a subpanel without having it as a tab
global $modules_exempt_from_availability_check;
global $app_list_strings;
$modules_exempt_from_availability_check['TeamsOS'] = 'TeamsOS';

$app_strings['LBL_TEAM']='Zugewiesenes Team:';
$app_strings['LBL_NO_TEAM']='--Kein--';

//orgchart addon
$app_strings['LBL_ORGCHART_BUTTON_TITLE'] = 'OrgChart';
$app_strings['LBL_ORGCHART_REPORTS'] = 'BERICHTET AN';
$app_strings['LBL_ORGCHART_NOONE'] = '---KEINE---';



$app_list_strings['moduleList']['ZuckerDocs']='ZuckerDocs';

if (!array_key_exists('doc_category', $app_list_strings)) {
	$app_list_strings['doc_category']= array (
			'Generic' => 'Allgemein',
			'Business Reports' => 'Gesch&auml;ftsberichte',
			'Bug Description' => 'Fehlerbeschreibungen',
			'Document Template' => 'Dokumentenvorlagen'
		);
}


$app_list_strings['moduleList']['TeamsOS']='TeamsOS';
$app_list_strings['moduleListSingular']['TeamsOS'] = 'TeamOS';

// Added this to allow Teams to display as a subpanel without having it as a tab
global $modules_exempt_from_availability_check;
global $app_list_strings;
$modules_exempt_from_availability_check['TeamsOS'] = 'TeamsOS';

$app_strings['LBL_TEAM']='Atribu�do � equipe:';
$app_strings['LBL_NO_TEAM']='--Nenhum--';

//orgchart addon
$app_strings['LBL_ORGCHART_BUTTON_TITLE'] = 'Organograma';
$app_strings['LBL_ORGCHART_REPORTS'] = 'RESPONDE PARA';
$app_strings['LBL_ORGCHART_NOONE'] = '---NINGU�M---';



$app_list_strings['moduleList']['TeamsOS']='TeamsOS';
$app_list_strings['moduleListSingular']['TeamsOS'] = 'TeamOS';

// Added this to allow Teams to display as a subpanel without having it as a tab
global $modules_exempt_from_availability_check;
global $app_list_strings;
$modules_exempt_from_availability_check['TeamsOS'] = 'TeamsOS';

//orgchart addon
$app_strings['LBL_ORGCHART_BUTTON_TITLE'] = 'Структура';
$app_strings['LBL_ORGCHART_REPORTS'] = 'Подчинить:';
$app_strings['LBL_ORGCHART_NOONE'] = '---NO ONE---';


?>