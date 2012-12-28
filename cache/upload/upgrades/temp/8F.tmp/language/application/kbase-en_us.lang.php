<?PHP
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
 
?>