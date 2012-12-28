<?php
if(!defined('sugarEntry'))define('sugarEntry', true);
$GLOBALS['sugarEntry'] = true;

require_once('config.php');
require_once('include/logging.php');
require_once('include/utils.php');
require_once('include/nusoap/nusoap.php');
require_once('include/TimeDate.php');
require_once('modules/ACL/ACLController.php');
require_once('modules/Accounts/Account.php');
require_once('modules/Notes/Note.php');
require_once('modules/Users/User.php');
require_once('modules/Leads/Lead.php');
require_once('modules/Cases/Case.php');
require_once('modules/Opportunities/Opportunity.php');
require_once('modules/Project/Project.php');
require_once('modules/ProjectTask/ProjectTask.php');
require_once('modules/Emails/Email.php');

global $HTTP_RAW_POST_DATA;
ini_set('error_reporting', E_ERROR);

ob_start();

$log =& LoggerManager::getLogger('godocs');

$NAMESPACE = 'http://www.go-mobile.at/sugarcrm';
$server = new soap_server;
$server->configureWSDL('godocs', $NAMESPACE);
$server->wsdl->schemaTargetNamespace=$NAMESPACE;

//require_once('godocs_notes.php');
require_once('godocs_dms.php');

$current_user = new User();
$current_language = $sugar_config['default_language'];
$mod_strings = array();

define('SOAPFAULT_AUTH', '101');
define('SOAPFAULT_NOT_IMPLEMENTED', '501');

function authSoapFault() {
	return new soap_fault(SOAPFAULT_AUTH, "sugar", "Invalid username and/or password");
}
function notImplementedSoapFault($feature) {
	return new soap_fault(SOAPFAULT_NOT_IMPLEMENTED, "sugar", $feature." not implemented");
}

function isSoapFault($obj) {
	return strtolower(get_class($obj)) == 'soap_fault';
}

/************************************************************************************/
/****************** Object Definitions ************************************************/
/************************************************************************************/

$server->wsdl->addComplexType(
    'serverinfo',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'sugar_version' => array('name'=>'sugar_version','type'=>'xsd:string'),
        'storage_info' => array('name'=>'storage_info','type'=>'xsd:string'),
        'storage_caps' => array('name'=>'storage_caps','type'=>'xsd:string'),
    )
);

$server->wsdl->addComplexType(
    'account_detail',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'id' => array('name'=>'id','type'=>'xsd:string'),
        'name' => array('name'=>'name','type'=>'xsd:string'),
        'email' => array('name'=>'email','type'=>'xsd:string'),
        'web' => array('name'=>'web','type'=>'xsd:string'),
        'phone' => array('name'=>'phone','type'=>'xsd:string'),
        'city' => array('name'=>'city','type'=>'xsd:string'),
    )
);

$server->wsdl->addComplexType(
    'account_detail_array',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(
        array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:account_detail[]')
    ),
    'tns:account_detail'
);

$server->wsdl->addComplexType(
    'contact_detail',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'id' => array('name'=>'id','type'=>'xsd:string'),
        'first_name' => array('name'=>'first_name','type'=>'xsd:string'),
        'last_name' => array('name'=>'last_name','type'=>'xsd:string'),
        'email' => array('name'=>'email','type'=>'xsd:string'),
        'phone' => array('name'=>'phone','type'=>'xsd:string'),
        'account' => array('name'=>'account','type'=>'xsd:string'),
    )
);

$server->wsdl->addComplexType(
    'contact_detail_array',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(
        array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:contact_detail[]')
    ),
    'tns:contact_detail'
);

$server->wsdl->addComplexType(
    'lead_detail',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'id' => array('name'=>'id','type'=>'xsd:string'),
        'first_name' => array('name'=>'first_name','type'=>'xsd:string'),
        'last_name' => array('name'=>'last_name','type'=>'xsd:string'),
        'email' => array('name'=>'email','type'=>'xsd:string')
    )
);

$server->wsdl->addComplexType(
    'lead_detail_array',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(
        array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:lead_detail[]')
    ),
    'tns:lead_detail'
);

$server->wsdl->addComplexType(
    'opportunity_detail',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'id' => array('name'=>'id','type'=>'xsd:string'),
        'name' => array('name'=>'name','type'=>'xsd:string'),
        'amount' => array('name'=>'amount','type'=>'xsd:string'),
        'account' => array('name'=>'account','type'=>'xsd:string'),
    )
);

$server->wsdl->addComplexType(
    'opportunity_detail_array',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(
        array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:opportunity_detail[]')
    ),
    'tns:opportunity_detail'
);

$server->wsdl->addComplexType(
    'case_detail',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'id' => array('name'=>'id','type'=>'xsd:string'),
        'name' => array('name'=>'name','type'=>'xsd:string'),
        'account' => array('name'=>'account','type'=>'xsd:string'),
    )
);

$server->wsdl->addComplexType(
    'case_detail_array',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(
        array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:case_detail[]')
    ),
    'tns:case_detail'
);

$server->wsdl->addComplexType(
    'project_detail',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'id' => array('name'=>'id','type'=>'xsd:string'),
        'name' => array('name'=>'name','type'=>'xsd:string'),
    )
);

$server->wsdl->addComplexType(
    'project_detail_array',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(
        array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:project_detail[]')
    ),
    'tns:project_detail'
);

$server->wsdl->addComplexType(
    'projecttask_detail',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'id' => array('name'=>'id','type'=>'xsd:string'),
        'name' => array('name'=>'name','type'=>'xsd:string'),
        'project' => array('name'=>'project','type'=>'xsd:string'),
    )
);

$server->wsdl->addComplexType(
    'projecttask_detail_array',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(
        array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:projecttask_detail[]')
    ),
    'tns:projecttask_detail'
);

$server->wsdl->addComplexType(
    'generic_search_result',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'account_detail_array' => array('name'=>'account_detail_array','type'=>'tns:account_detail_array'),
        'contact_detail_array' => array('name'=>'contact_detail_array','type'=>'tns:contact_detail_array'),
        'opportunity_detail_array' => array('name'=>'opportunity_detail_array','type'=>'tns:opportunity_detail_array'),
        'case_detail_array' => array('name'=>'case_detail_array','type'=>'tns:case_detail_array'),
        'lead_detail_array' => array('name'=>'lead_detail_array','type'=>'tns:lead_detail_array'),
        'project_detail_array' => array('name'=>'project_detail_array','type'=>'tns:project_detail_array'),
        'projecttask_detail_array' => array('name'=>'projecttask_detail_array','type'=>'tns:projecttask_detail_array'),
    )
);

$server->wsdl->addComplexType(
    'attached_document',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'id' => array('name'=>'id','type'=>'xsd:string'),
        'filename' => array('name'=>'filename','type'=>'xsd:string'),
        'last_modified' => array('name'=>'last_modified','type'=>'xsd:string'),
        'description' => array('name'=>'description','type'=>'xsd:string'),
        'status' => array('name'=>'status','type'=>'xsd:string'),
        'parent_type' => array('name'=>'parent_type','type'=>'xsd:string'),
        'parent_name' => array('name'=>'parent_name','type'=>'xsd:string'),
        'parent_id' => array('name'=>'parent_id','type'=>'xsd:string'),
        'cat_name' => array('name'=>'cat_name','type'=>'xsd:string'),
        'contents' => array('name'=>'contents','type'=>'xsd:string')
    )
);

$server->wsdl->addComplexType(
    'attached_document_array',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(
        array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:attached_document[]')
    ),
    'tns:attached_document'
);
		
$server->wsdl->addComplexType(
    'document_history',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'version' => array('name'=>'version','type'=>'xsd:string'),
        'user' => array('name'=>'user','type'=>'xsd:string'),
        'datetime' => array('name'=>'datetime','type'=>'xsd:string'),
        'comment' => array('name'=>'comment','type'=>'xsd:string'),
        'type' => array('name'=>'type','type'=>'xsd:string'),
    )
);

$server->wsdl->addComplexType(
    'document_history_array',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(
        array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:document_history[]')
    ),
    'tns:document_history'
);

$server->wsdl->addComplexType(
    'folder_detail',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'id' => array('name'=>'id','type'=>'xsd:string'),
        'name' => array('name'=>'name','type'=>'xsd:string'),
        'description' => array('name'=>'description','type'=>'xsd:string'),
    )
);

$server->wsdl->addComplexType(
    'folder_detail_array',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(
        array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:folder_detail[]')
    ),
    'tns:folder_detail'
);

$server->wsdl->addComplexType(
    'folder_contents',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'folder_detail_array' => array('name'=>'folder_detail_array','type'=>'tns:folder_detail_array'),
        'attached_document_array' => array('name'=>'attached_document_array','type'=>'tns:attached_document_array'),
    )
);

/************************************************************************************/
/****************** SOAP Conversion Functions *****************************************/
/************************************************************************************/


function convert_account_array($account) {
	return array(
		"id" => $account->id,
		"name" => $account->name,
		"email" => $account->email1,
		"web" => $account->website,
		"phone" => $account->phone_office,
		"city" => $account->billing_address_city);
}
function convert_contact_array($contact){
	 return Array(
		"id" => $contact->id,
		"first_name" => $contact->first_name,
		"last_name" => $contact->last_name,
		"email" => $contact->email1,
		"phone" => isset($contact->phone_work) ? $contact->phone_work : $contact->phone_mobile,
		"account" => $contact->account_name);
}

function convert_opportunity_array($opp){
	return  Array(
		"id" => $opp->id,
		"name" => $opp->name,
		"amount" => $opp->amount,
		"account" => $opp->account_name);
}

function convert_lead_array($lead){
	 return Array(
		"id" => $lead->id,
		"first_name" => $lead->first_name,
		"last_name" => $lead->last_name,
		"email" => $lead->email1);
}

function convert_case_array($case){
	return  Array(
		"id" => $case->id,
		"name" => $case->name,
		"account" => $case->account_name);
}

function convert_project_array($project){
	return  Array(
		"id" => $project->id,
		"name" => $project->name);
}

function convert_projecttask_array($projecttask){
	return  Array(
		"id" => $projecttask->id,
		"name" => $projecttask->name,
		"project" => $projecttask->parent_name);
}

function parse_sugar_ids($sugar_ids) {
	$result = array();
	
	$sugar_id_list = explode(";", $sugar_ids);
	foreach ($sugar_id_list as $sugar_id) {
		if (!empty($sugar_id)) {
			$a = explode(":", $sugar_id);
			$type = $a[0];
			$id = $a[1];
			$result[] = array("parent_type"=>$type, "parent_id"=>$id);
		}
	}
	return $result;
}

/************************************************************************************/
/****************** Login ***********************************************************/
/************************************************************************************/


function validate_user($user_name, $password){
	global $server, $log, $current_user;

	$log->debug("session => ".$_SESSION);
	
	$current_user->user_name = $user_name;
	if($current_user->authenticate_user($password)){
		$current_user = $current_user->retrieve($current_user->id);
		$current_user->loadPreferences();
		return true;
	} else{
		$log->fatal("SECURITY: failed attempted login for $user_name using godocs api");
		$server->setError("Invalid username and/or password");
		return false;
	}
}

/************************************************************************************/
/****************** Object Lookup ****************************************************/
/************************************************************************************/
 
$server->register(
    'get_serverinfo',
    array('user_name'=>'xsd:string','password'=>'xsd:string'),
    array('return'=>'tns:serverinfo'),
    $NAMESPACE);

$server->register(
    'get_accounts',
    array('user_name'=>'xsd:string','password'=>'xsd:string'),
    array('return'=>'tns:account_detail_array'),
    $NAMESPACE);
	
$server->register(
    'get_account',
    array('user_name'=>'xsd:string','password'=>'xsd:string', 'id'=>'xsd:string'),
    array('return'=>'tns:account_detail'),
    $NAMESPACE);
	
$server->register(
    'get_leads',
    array('user_name'=>'xsd:string','password'=>'xsd:string'),
    array('return'=>'tns:lead_detail_array'),
    $NAMESPACE);

$server->register(
    'get_lead',
    array('user_name'=>'xsd:string','password'=>'xsd:string', 'id'=>'xsd:string'),
    array('return'=>'tns:lead_detail'),
    $NAMESPACE);
    
$server->register(
    'get_contacts_by_account',
    array('user_name'=>'xsd:string','password'=>'xsd:string','account_id'=>'xsd:string'),
    array('return'=>'tns:contact_detail_array'),
    $NAMESPACE);

$server->register(
    'get_contact',
    array('user_name'=>'xsd:string','password'=>'xsd:string','id'=>'xsd:string'),
    array('return'=>'tns:contact_detail'),
    $NAMESPACE);

$server->register(
    'get_opportunities_by_account',
    array('user_name'=>'xsd:string','password'=>'xsd:string','account_id'=>'xsd:string'),
    array('return'=>'tns:opportunity_detail_array'),
    $NAMESPACE);

$server->register(
    'get_opportunity',
    array('user_name'=>'xsd:string','password'=>'xsd:string','id'=>'xsd:string'),
    array('return'=>'tns:opportunity_detail'),
    $NAMESPACE);

$server->register(
    'get_cases_by_account',
    array('user_name'=>'xsd:string','password'=>'xsd:string','account_id'=>'xsd:string'),
    array('return'=>'tns:case_detail_array'),
    $NAMESPACE);

$server->register(
    'get_case',
    array('user_name'=>'xsd:string','password'=>'xsd:string','id'=>'xsd:string'),
    array('return'=>'tns:case_detail'),
    $NAMESPACE);

$server->register(
    'get_project',
    array('user_name'=>'xsd:string','password'=>'xsd:string','id'=>'xsd:string'),
    array('return'=>'tns:project_detail'),
    $NAMESPACE);

$server->register(
    'get_projecttask',
    array('user_name'=>'xsd:string','password'=>'xsd:string','id'=>'xsd:string'),
    array('return'=>'tns:projecttask_detail'),
    $NAMESPACE);

$server->register(
    'get_projecttask_by_project',
    array('user_name'=>'xsd:string','password'=>'xsd:string','project_id'=>'xsd:string'),
    array('return'=>'tns:projecttask_detail_array'),
    $NAMESPACE);	
	
$server->register(
    'generic_search',
    array('user_name'=>'xsd:string','password'=>'xsd:string','text'=>'xsd:string', 'object_types'=>'xsd:string'),
    array('return'=>'tns:generic_search_result'),
    $NAMESPACE);

$server->register(
    'get_folder_contents',
    array('user_name'=>'xsd:string','password'=>'xsd:string','folder_id'=>'xsd:string'),
    array('return'=>'tns:folder_contents'),
    $NAMESPACE);	

	
function get_serverinfo($user_name, $password) {
	if(!validate_user($user_name, $password)){
		return authSoapFault();
	}
	require_once('sugar_version.php');
	global $sugar_version;
	$result = array();
	$result['sugar_version'] = $sugar_version;
	$result['storage_info'] = getStorageInfo();
	$result['storage_caps'] = getStorageCaps();
	return $result;
}
	
	
function get_accounts($user_name, $password) {
	if(!validate_user($user_name, $password)){
		return authSoapFault();
	}

	global $log;

	$obj_account = new Account();
	$accountList = $obj_account->get_full_list("name");

	$output_list = Array();

	foreach($accountList as $account)
	{
		$output_list[] = convert_account_array($account);
	}
	return $output_list;
}

function get_account($user_name, $password, $id) {
	if(!validate_user($user_name, $password)){
		return authSoapFault();
	}

	global $log;

	$obj_account = new Account();
	$obj_account->retrieve($id);
	return convert_account_array($obj_account);
}

function get_leads($user_name, $password) {
	if(!validate_user($user_name, $password)){
		return authSoapFault();
	}

	global $log;
	$obj_lead = new Lead();
	$leadList = $obj_lead->get_full_list("last_name");

	$output_list = Array();

	foreach($leadList as $lead)
	{
		$output_list[] = convert_lead_array($lead);
	}
	return $output_list;
}

function get_lead($user_name, $password, $id) {
	if(!validate_user($user_name, $password)){
		return authSoapFault();
	}

	global $log;

	$obj_lead = new Lead();
	$obj_lead->retrieve($id);
	return convert_lead_array($obj_lead);
}

function get_contacts_by_account($user_name, $password, $account_id) {
 	if(!validate_user($user_name, $password)){
		return authSoapFault();
	}
       	
       	global $log;
        $obj_account = new Account();
	$obj_account->retrieve($account_id);
        $contactList = $obj_account->get_contacts();

        $output_list = Array();

        foreach($contactList as $contact)
        {
                $output_list[] = convert_contact_array($contact);
        }
        return $output_list;
}

function get_contact($user_name, $password, $id) {
	if(!validate_user($user_name, $password)){
		return authSoapFault();
	}

	global $log;

	$obj_contact = new Contact();
	$obj_contact->retrieve($id);
	return convert_contact_array($obj_contact);
}

function get_opportunities_by_account($user_name, $password, $account_id) {
	if(!validate_user($user_name, $password)){
		return authSoapFault();
	}

        global $log;
        $obj_account = new Account();
        $obj_account->retrieve($account_id);
        $oppList = $obj_account->get_opportunities();

        $output_list = Array();

        foreach($oppList as $opp)
        {
                $output_list[] = convert_opportunity_array($opp);
        }
        return $output_list;
}

function get_opportunity($user_name, $password, $id) {
	if(!validate_user($user_name, $password)){
		return authSoapFault();
	}

	global $log;

	$obj_opp = new Opportunity();
	$obj_opp->retrieve($id);
	return convert_opportunity_array($obj_opp);
}

function get_cases_by_account($user_name, $password, $account_id) {
	if(!validate_user($user_name, $password)){
		return authSoapFault();
	}

        global $log;
        $obj_account = new Account();
        $obj_account->retrieve($account_id);
        $caseList = $obj_account->get_cases();

        $output_list = Array();

        foreach($caseList as $case)
        {
                $output_list[] = convert_case_array($case);
        }
        return $output_list;
}

function get_case($user_name, $password, $id) {
	if(!validate_user($user_name, $password)){
		return authSoapFault();
	}

	global $log;

	$obj_case = new aCase();
	$obj_case->retrieve($id);
	return convert_case_array($obj_case);
}

function get_project($user_name, $password, $id) {
	if(!validate_user($user_name, $password)){
		return authSoapFault();
	}

	global $log;

	$obj_prj = new Project();
	$obj_prj->retrieve($id);
	return convert_project_array($obj_prj);
}

function get_projecttask($user_name, $password, $id) {
	if(!validate_user($user_name, $password)){
		return authSoapFault();
	}

	global $log;

	$obj_pt = new ProjectTask();
	$obj_pt->retrieve($id);
	return convert_projecttask_array($obj_pt);
}

function get_projecttask_by_project($user_name, $password, $project_id) {
	if(!validate_user($user_name, $password)){
		return authSoapFault();
	}

    $obj_prj = new Project();
    $obj_prj->retrieve($project_id);
    $ptList = $obj_prj->get_project_tasks();

    $output_list = Array();
    foreach($ptList as $pt) {
        $output_list[] = convert_projecttask_array($pt);
    }
    return $output_list;
}

function generic_search($user_name, $password, $text, $object_types = '') {
	if(!validate_user($user_name, $password)){
		return authSoapFault();
	}

	global $log;
	
	$result = array();

	if (empty($object_types) || strstr($object_types, 'Accounts')) {
		$obj_account = new Account();
		$response = $obj_account->get_list("name", $obj_account->build_generic_where_clause($text), 0);
		$accountList = $response['list'];
	
		$account_list = Array();
	
		foreach($accountList as $account) {
			$account_list[] = convert_account_array($account);
		}
		$result['account_detail_array'] = $account_list;
	}
	
	if (empty($object_types) || strstr($object_types, 'Contacts')) {
		$obj_contact = new Contact();
		$response = $obj_contact->get_list("last_name, first_name", $obj_contact->build_generic_where_clause($text), 0);
		$contactList = $response['list'];
	
		$contact_list = Array();
		foreach($contactList as $contact)
		{
			$contact_list[] = convert_contact_array($contact);
		}
	
		$result['contact_detail_array'] = $contact_list;
	}
	
	if (empty($object_types) || strstr($object_types, 'Leads')) {
	
		$obj_lead = new Lead();
		$response = $obj_lead->get_list("last_name, first_name", $obj_lead->build_generic_where_clause($text), 0);
		$leadList = $response['list'];
	
		$lead_list = Array();
		foreach($leadList as $lead)
		{
			$lead_list[] = convert_lead_array($lead);
		}
	
		$result['lead_detail_array'] = $lead_list;
	}
	
	if (empty($object_types) || strstr($object_types, 'Cases')) {
		
		$obj_case = new aCase();
		$response = $obj_case->get_list("name", $obj_case->build_generic_where_clause($text), 0);
		$caseList = $response['list'];
	
		$case_list = Array();
		foreach($caseList as $case)
		{
			$case_list[] = convert_case_array($case);
		}
	
		$result['case_detail_array'] = $case_list;
	}
	
	if (empty($object_types) || strstr($object_types, 'Opportunities')) {
	
		$obj_opp = new Opportunity();
		$response = $obj_opp->get_list("name", $obj_opp->build_generic_where_clause($text), 0);
		$oppList = $response['list'];
	
		$opp_list = Array();
		foreach($oppList as $opp)
		{
			$opp_list[] = convert_opportunity_array($opp);
		}
	
		$result['opportunity_detail_array'] = $opp_list;
	}

	if (empty($object_types) || strstr($object_types, 'Project')) {
	
		$obj_prj = new Project();
		$response = $obj_prj->get_list("name", $obj_prj->build_generic_where_clause($text), 0);
		$prjList = $response['list'];
	
		$prj_list = Array();
		foreach($prjList as $prj)
		{
			$prj_list[] = convert_project_array($prj);
		}
	
		$result['project_detail_array'] = $prj_list;
	}

	if (empty($object_types) || strstr($object_types, 'ProjectTask')) {
	
		$obj_pt = new ProjectTask();
		$response = $obj_pt->get_list("name", $obj_pt->build_generic_where_clause($text), 0);
		$ptList = $response['list'];
	
		$pt_list = Array();
		foreach($ptList as $pt)
		{
			$pt_list[] = convert_projecttask_array($pt);
		}
	
		$result['projecttask_detail_array'] = $pt_list;
	}
	
	return $result;
}

function get_folder_contents($user_name, $password, $folder_id) {
	if(!validate_user($user_name, $password)){
		return authSoapFault();
	}
	
	return get_folder_contents_impl($folder_id);
}


/************************************************************************************/
/****************** Object Creation Functions ******************************************/
/************************************************************************************/

$server->register(
	'create_contact',
    array('user_name'=>'xsd:string','password'=>'xsd:string', 'account_id'=>'xsd:string', 'first_name'=>'xsd:string', 'last_name'=>'xsd:string', 'email_address'=>'xsd:string'),
    array('return'=>'xsd:string'),
    $NAMESPACE);

$server->register(
	'create_lead',
    array('user_name'=>'xsd:string','password'=>'xsd:string', 'first_name'=>'xsd:string', 'last_name'=>'xsd:string', 'email_address'=>'xsd:string'),
    array('return'=>'xsd:string'),
    $NAMESPACE);

$server->register(
	'create_account',
    array('user_name'=>'xsd:string','password'=>'xsd:string', 'name'=>'xsd:string', 'email_address'=>'xsd:string', 'phone'=>'xsd:string', 'website'=>'xsd:string'),
    array('return'=>'xsd:string'),
    $NAMESPACE);

$server->register(
	'create_opportunity',
    array('user_name'=>'xsd:string','password'=>'xsd:string', 'account_id'=>'xsd:string', 'name'=>'xsd:string', 'amount'=>'xsd:string'),
    array('return'=>'xsd:string'),
    $NAMESPACE);

$server->register(
	'create_case',
    array('user_name'=>'xsd:string','password'=>'xsd:string', 'account_id'=>'xsd:string', 'name'=>'xsd:string'),
    array('return'=>'xsd:string'),
    $NAMESPACE);
    
function create_contact($user_name,$password, $account_id, $first_name, $last_name, $email_address) {
	if(!validate_user($user_name, $password)){
		return authSoapFault();
	}

	global $log;

	$obj_user = new User();
	$user_id = $obj_user->retrieve_user_id($user_name);

	$contact = new Contact();
	$contact->first_name = $first_name;
	$contact->last_name = $last_name;
	$contact->email1 = $email_address;
	$contact->assigned_user_id = $user_id;
	$contact->assigned_user_name = $user_name;
	$contact->account_id = $account_id;
	return $contact->save();
}

function create_lead($user_name,$password, $first_name, $last_name, $email_address) {
	if(!validate_user($user_name, $password)){
		return authSoapFault();
	}

	global $log;

	$obj_user = new User();
	$user_id = $obj_user->retrieve_user_id($user_name);

	$lead = new Lead();
	$lead->first_name = $first_name;
	$lead->last_name = $last_name;
	$lead->email1 = $email_address;
	$lead->assigned_user_id = $user_id;
	$lead->assigned_user_name = $user_name;
	return $lead->save();
}

function create_account($user_name, $password, $name, $email_address, $phone, $website) {
	if(!validate_user($user_name, $password)){
		return authSoapFault();
	}

	global $log;

	$obj_user = new User();
	$user_id = $obj_user->retrieve_user_id($user_name);

	$account = new Account();
	$account->name = $name;
	$account->email1 = $email_address;
	$account->phone_office = $phone;
	$account->website = $website;
	$account->assigned_user_id = $user_id;
	$account->assigned_user_name = $user_name;
	return $account->save();
}

function create_case($user_name, $password, $account_id, $name) {
	if(!validate_user($user_name, $password)){
		return authSoapFault();
	}

	global $log;

	$obj_user = new User();
	$user_id = $obj_user->retrieve_user_id($user_name);


	$case = new aCase();
	$case->assigned_user_id = $user_id;
	$case->assigned_user_name = $seed_user->user_name;
	$case->name = $name;
	$case->assigned_user_id = $user_id;
	$case->assigned_user_name = $user_name;
	$case->account_id = $account_id;
	return $case->save();
}

function create_opportunity($user_name, $password, $account_id, $name, $amount) {
	if(!validate_user($user_name, $password)){
		return authSoapFault();
	}

	global $log;

	$obj_user = new User();
	$user_id = $obj_user->retrieve_user_id($user_name);

	$opp = new Opportunity();
	$opp->name = $name;
	$opp->amount = $amount;
	$opp->assigned_user_id = $user_id;
	$opp->assigned_user_name = $user_name;
	$opp->account_id = $account_id;
	return $opp->save();
}

/************************************************************************************/
/****************** Document Function ************************************************/
/************************************************************************************/

$server->register(
    'attach_document',
    array('user_name'=>'xsd:string','password'=>'xsd:string','filename'=>'xsd:string','description'=>'xds:string','sugar_ids'=>'xsd:string','contents'=>'xsd:string'),
    array('return'=>'xsd:string'),
    $NAMESPACE);
    
$server->register(
    'attach_document_to_account',
    array('user_name'=>'xsd:string','password'=>'xsd:string','filename'=>'xsd:string','description'=>'xds:string','account_id'=>'xsd:string','contents'=>'xsd:string'),
    array('return'=>'xsd:string'),
    $NAMESPACE);

$server->register(
    'attach_document_to_contact',
    array('user_name'=>'xsd:string','password'=>'xsd:string','filename'=>'xsd:string','description'=>'xds:string','contact_id'=>'xsd:string','contents'=>'xsd:string'),
    array('return'=>'xsd:string'),
    $NAMESPACE);

$server->register(
    'attach_document_to_case',
    array('user_name'=>'xsd:string','password'=>'xsd:string','filename'=>'xsd:string','description'=>'xds:string','case_id'=>'xsd:string','contents'=>'xsd:string'),
    array('return'=>'xsd:string'),
    $NAMESPACE);
    
$server->register(
    'attach_document_to_opportunity',
    array('user_name'=>'xsd:string','password'=>'xsd:string','filename'=>'xsd:string','description'=>'xds:string','opportunity_id'=>'xsd:string','contents'=>'xsd:string'),
    array('return'=>'xsd:string'),
    $NAMESPACE);

$server->register(
    'attach_document_to_lead',
    array('user_name'=>'xsd:string','password'=>'xsd:string','filename'=>'xsd:string','description'=>'xds:string','lead_id'=>'xsd:string','contents'=>'xsd:string'),
    array('return'=>'xsd:string'),
    $NAMESPACE);

$server->register(
    'attach_document_to_folder',
    array('user_name'=>'xsd:string','password'=>'xsd:string','filename'=>'xsd:string','description'=>'xds:string','full_path'=>'xsd:string','contents'=>'xsd:string'),
    array('return'=>'xsd:string'),
    $NAMESPACE);
	
$server->register(
    'attached_document_search',
    array('user_name'=>'xsd:string','password'=>'xsd:string','filename'=>'xsd:string'),
    array('return'=>'tns:attached_document_array'),
    $NAMESPACE);
    
$server->register(
    'get_attached_documents',
    array('user_name'=>'xsd:string','password'=>'xsd:string','sugar_ids'=>'xsd:string'),
    array('return'=>'tns:attached_document_array'),
    $NAMESPACE);

$server->register(
    'get_document_history',
    array('user_name'=>'xsd:string','password'=>'xsd:string','id'=>'xsd:string'),
    array('return'=>'tns:document_history_array'),
    $NAMESPACE);
	
$server->register(
    'load_attached_document',
    array('user_name'=>'xsd:string','password'=>'xsd:string','id'=>'xsd:string', 'lock'=>'xsd:string',),
    array('return'=>'tns:attached_document'),
    $NAMESPACE);


	
function attach_document($user_name, $password, $filename, $description, $sugar_ids, $contents) {
	if(!validate_user($user_name, $password)){
		return authSoapFault();
	}

	$result = '';
	
	$sugar_id_list = parse_sugar_ids($sugar_ids);
	foreach ($sugar_id_list as $sugar_id) {
		$result .= "|".attach_document_impl($filename, $description, $sugar_id['parent_type'], $sugar_id['parent_id'], $contents);		
	}
	return $result;
}

function attach_document_to_account($user_name, $password, $filename, $description, $account_id, $contents) {
	if(!validate_user($user_name, $password)){
		return authSoapFault();
	}

	return attach_document_impl($filename, $description, 'Accounts', $account_id, $contents);
}

function attach_document_to_contact($user_name, $password, $filename, $description, $contact_id, $contents) {
	if(!validate_user($user_name, $password)){
		return authSoapFault();
	}

	return attach_document_impl($filename, $description, 'Contacts', $contact_id, $contents);
}

function attach_document_to_case($user_name, $password, $filename, $description, $case_id, $contents) {
	if(!validate_user($user_name, $password)){
		return authSoapFault();
	}

	return attach_document_impl($filename, $description, 'Cases', $case_id, $contents);
}

function attach_document_to_opportunity($user_name, $password, $filename, $description, $opportunity_id, $contents) {
	if(!validate_user($user_name, $password)){
		return authSoapFault();
	}

	return attach_document_impl($filename, $description, 'Opportunities', $opportunity_id, $contents);
}

function attach_document_to_lead($user_name, $password, $filename, $description, $lead_id, $contents) {
	if(!validate_user($user_name, $password)){
		return authSoapFault();
	}

	return attach_document_impl($filename, $description, 'Leads', $lead_id, $contents);
}

function attach_document_to_folder($user_name, $password, $filename, $description, $full_path, $contents) {
	if(!validate_user($user_name, $password)){
		return authSoapFault();
	}
	return attach_document_impl($filename, $description, 'Folders', $full_path, $contents);
}

function get_attached_documents($user_name, $password, $sugar_ids) {
	if(!validate_user($user_name, $password)){
		return authSoapFault();
	}
	return get_attached_documents_impl($sugar_ids);
}
function get_document_history($user_name, $password, $id) {
	if(!validate_user($user_name, $password)){
		return authSoapFault();
	}
	return get_document_history_impl($id);
}
function attached_document_search($user_name, $password, $filename) {
	if(!validate_user($user_name, $password)){
		return authSoapFault();
	}
	
	return attached_document_search_impl($filename);
}
function load_attached_document($user_name, $password, $id, $lock) {
 	if(!validate_user($user_name, $password)){
		return authSoapFault();
	}
	return load_attached_document_impl($id, $lock);
}

ob_clean();
ob_start();

if (strstr($_SERVER["CONTENT_TYPE"], "multipart/form-data")) {
	header("Content-Type: text/xml");
	$action = $_REQUEST["action"];
	$user_name = $_REQUEST["user_name"];
	$password = $_REQUEST["password"];
	if(!validate_user($user_name, $password)){
		echo "<result>FAILED: Invalid username and/or password</result>";
	} else if ($action == "upload") {
		$filename = $_REQUEST["filename"];
		$description = $_REQUEST["description"];
		$sugar_ids = $_REQUEST["sugar_ids"];
		$contents = $_REQUEST["contents"];
		
		if (is_uploaded_file($_FILES['uploadfile']['tmp_name'])) {

			$filename = $_FILES['uploadfile']['name'];
			$contents = file_get_contents($_FILES['uploadfile']['tmp_name']);
			$sugar_id_list = parse_sugar_ids($sugar_ids);
			$result = "OK";
			foreach ($sugar_id_list as $sugar_id) {
				$obj = attach_document_impl($filename, $description, $sugar_id['parent_type'], $sugar_id['parent_id'], $contents, FALSE);		
				if (isSoapFault($obj)) {
					$result = "FAILED:".$obj->faultstring;
					break;
				}
			}
			echo "<result>".$result."</result>";
			
		} else {
			echo "<result>FAILED: no file given</result>";
		}
	} else if ($action == "download") {
		$id = $_REQUEST["id"];
		$lock = $_REQUEST["lock"];
		
		$obj = load_attached_document_impl($id, $lock, FALSE);
		if (isSoapFault($obj)) {
			header("ZD_result: FAILED");
			echo $obj->faultstring;
		} else {
			header("ZD_result: OK");
			header("ZD_id: ".$obj["id"]);
			header("ZD_downloadfile: ".$obj["filename"]);
			header("ZD_last_modified: ".$obj["last_modified"]);
			header("ZD_status: ".$obj["status"]);
			header("ZD_description: ".$obj["description"]);
			echo $obj["contents"];
		}
	}
} else if (strstr($_SERVER["CONTENT_TYPE"], "text/xml")) {
	$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
	$server->service($HTTP_RAW_POST_DATA);
}

$log->debug(ob_get_flush());

exit();
?>
