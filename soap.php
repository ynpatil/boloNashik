<?php
if(!defined('sugarEntry'))define('sugarEntry', true);
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version
 * 1.1.3 ("License"); You may not use this file except in compliance with the
 * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an "AS IS" basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied.  See the License
 * for the specific language governing rights and limitations under the
 * License.
 *
 * All copies of the Covered Code must include on each user interface screen:
 *    (i) the "Powered by SugarCRM" logo and
 *    (ii) the SugarCRM copyright notice
 * in the same form as they appear in the distribution.  See full license for
 * requirements.
 *
 * The Original Code is: SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) 2004-2006 SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/

require_once('include/entryPoint.php');
require_once('include/utils/file_utils.php');
require_once('include/utils.php');
ob_start();

require_once('soap/SoapError.php');
require_once('include/nusoap/nusoap.php');

require_once('modules/Contacts/Contact.php');
require_once('modules/Accounts/Account.php');
require_once('modules/Opportunities/Opportunity.php');
require_once('modules/Cases/Case.php');
require_once('modules/SubofficeMaster/Suboffice.php');

require_once('modules/BranchMaster/Branch.php');
//ignore notices
error_reporting(E_ALL ^ E_NOTICE);


global $HTTP_RAW_POST_DATA;
global $mod_strings;


//$administrator = new Administration();
//$administrator->retrieveSettings();

$NAMESPACE = $sugar_config['site_url'];
$server = new soap_server;
//$server->configureWSDL('sugarsoap', $NAMESPACE, $sugar_config['site_url'].'/soap_test.php?wsdl');
$server->configureWSDL('sugarsoap', $NAMESPACE, 'http://respforce.timesgroup.com/sfa/soap.php?wsdl');
//$server->configureWSDL('sugarsoap', $NAMESPACE, 'http://10.100.109.181/sfa/soap.php?wsdl');

$server->wsdl->addComplexType(
        'contact_detail',
        'complexType',
        'struct',
        'all',
        '',
        array(
        'email_address' => array('name'=>'email_address','type'=>'xsd:string'),
        'name1' => array('name'=>'name1','type'=>'xsd:string'),
        'name2' => array('name'=>'name2','type'=>'xsd:string'),
        'association' => array('name'=>'association','type'=>'xsd:string'),
        'id' => array('name'=>'id','type'=>'xsd:string'),
        'msi_id' => array('name'=>'id','type'=>'xsd:string'),
        'type' => array('name'=>'type','type'=>'xsd:string'),
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
        'address_detail',
        'complexType',
        'struct',
        'all',
        '',
        array(
        'description' => array('name'=>'description','type'=>'xsd:string'),
        'branch' => array('name'=>'branch','type'=>'xsd:string'),
        'latitude' => array('name'=>'latitude','type'=>'xsd:string'),
        'longitude' => array('name'=>'longitude','type'=>'xsd:string'),
        'address' => array('name'=>'address','type'=>'xsd:string'),
        'message' => array('name'=>'message','type'=>'xsd:string'),
        )
);

$server->wsdl->addComplexType(
        'branches_detail',
        'complexType',
        'struct',
        'all',
        '',
        array(
        'branch_id' => array('name'=>'branch_id','type'=>'xsd:string'),
        'branch_name' => array('name'=>'branch_name','type'=>'xsd:string'),
        'message' => array('name'=>'message','type'=>'xsd:string'),
        )
);

$server->wsdl->addComplexType(
        'suboffice_for_branch_detail',
        'complexType',
        'struct',
        'all',
        '',
        array(
        'description' => array('name'=>'description','type'=>'xsd:string'),
        'branch' => array('name'=>'branch','type'=>'xsd:string'),
        'latitude' => array('name'=>'latitude','type'=>'xsd:string'),
        'longitude' => array('name'=>'longitude','type'=>'xsd:string'),
        'address' => array('name'=>'address','type'=>'xsd:string'),
        'message' => array('name'=>'message','type'=>'xsd:string'),
        )
);
$server->wsdl->addComplexType(
        'address_detail_array',
        'complexType',
        'array',
        '',
        'SOAP-ENC:Array',
        array(),
        array(
        array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:address_detail[]')
        ),
        'tns:address_detail'
);

$server->wsdl->addComplexType(
        'branches_detail_array',
        'complexType',
        'array',
        '',
        'SOAP-ENC:Array',
        array(),
        array(
        array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:branches_detail[]')
        ),
        'tns:branches_detail'
);
$server->wsdl->addComplexType(
        'suboffice_for_branch_detail_array',
        'complexType',
        'array',
        '',
        'SOAP-ENC:Array',
        array(),
        array(
        array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:suboffice_for_branch_detail[]')
        ),
        'tns:suboffice_for_branch_detail'
);
$server->wsdl->addComplexType(
        'user_detail',
        'complexType',
        'struct',
        'all',
        '',
        array(
        'email_address' => array('name'=>'email_address','type'=>'xsd:string'),
        'user_name' => array('name'=>'user_name', 'type'=>'xsd:string'),
        'first_name' => array('name'=>'first_name','type'=>'xsd:string'),
        'last_name' => array('name'=>'last_name','type'=>'xsd:string'),
        'department' => array('name'=>'department','type'=>'xsd:string'),
        'id' => array('name'=>'id','type'=>'xsd:string'),
        'title' => array('name'=>'title','type'=>'xsd:string'),
        )
);

$server->wsdl->addComplexType(
        'user_detail_array',
        'complexType',
        'array',
        '',
        'SOAP-ENC:Array',
        array(),
        array(
        array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:user_detail[]')
        ),
        'tns:user_detail'
);

//added by sanjay for account_details method

$server->wsdl->addComplexType(
        'account_detail',
        'complexType',
        'struct',
        'all',
        '',
        array(
        'created_by_name' => array('name'=>'created_by_name','type'=>'xsd:string'),
        'reports_to_name' => array('name'=>'reports_to_name', 'type'=>'xsd:string')
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

//added by sanjay for
$server->wsdl->addComplexType(
        'case_list',
        'complexType',
        'struct',
        'all',
        '',
        array(
        'case_id' => array('name'=>'case_id','type'=>'xsd:string'),
        'case_name' => array('name'=>'case_name','type'=>'xsd:string'),
        'process_name' => array('name'=>'process_name','type'=>'xsd:string'),
        'sent_by' => array('name'=>'sent_by','type'=>'xsd:string'),
        'case_status' => array('name'=>'case_status','type'=>'xsd:string'),
        'create_date' => array('name'=>'create_date','type'=>'xsd:string')
        )
);


$server->wsdl->addComplexType(
        'case_list_array',
        'complexType',
        'array',
        '',
        'SOAP-ENC:Array',
        array(),
        array(
        array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:case_list[]')
        ),
        'tns:case_list'
);


$server->wsdl->addComplexType(
        'get_variables_list',
        'complexType',
        'struct',
        'all',
        '',
        array(
        'key' => array('name'=>'name','type'=>'xsd:string'),
        'value' => array('name'=>'value','type'=>'xsd:string')
        )
        //    array()
);


$server->wsdl->addComplexType(
        'get_variables_list_array',
        'complexType',
        'array',
        '',
        'SOAP-ENC:Array',
        array(),
        array(
        array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:get_variables_list[]')
        ),
        'tns:get_variables_list'
);

$server->wsdl->addComplexType(
        'get_input_doc_list_array',
        'complexType',
        'array',
        '',
        'SOAP-ENC:Array',
        array(),
        array(
        array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:get_variables_list[]')
        ),
        'tns:get_variables_list'
);

//$server->wsdl->addComplexType(
//    'address_detail',
//    'complexType',
//    'struct',
//    'all',
//    '',
//    array(
//    'description' => array('name'=>'description','type'=>'xsd:string'),
//    'branch' => array('name'=>'branch','type'=>'xsd:string'),
//    'latitude' => array('name'=>'latitude','type'=>'xsd:string'),
//    'longitude' => array('name'=>'longitude','type'=>'xsd:string'),
//    'message' => array('name'=>'message','type'=>'xsd:string'),
//
//    )
//);
//$server->wsdl->addComplexType(
//    'address_detail_array',
//    'complexType',
//    'array',
//    '',
//    'SOAP-ENC:Array',
//    array(),
//    array(
//    array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:address_detail[]')
//    ),
//    'tns:address_detail'
//);


//

$server->register(
        'create_session',
        array('user_name'=>'xsd:string','password'=>'xsd:string'),
        array('return'=>'xsd:string'),
        $NAMESPACE);

$server->register(
        'get_nearest_address',
        array('latitude'=>'xsd:string','longitude'=>'xsd:string'),
        array('return'=>'tns:address_detail_array'),
        $NAMESPACE);

$server->register(
        'get_all_branches',
        array(),
        array('return'=>'tns:branches_detail_array'),
        //array('return'=>'xsd:string'),
        $NAMESPACE);

$server->register(
        'get_all_suboffice_for_branch',
        array('branch_id'=>'xsd:string'),
        array('return'=>'tns:suboffice_for_branch_detail_array'),
        $NAMESPACE);

$server->register(
        'end_session',
        array('user_name'=>'xsd:string'),
        array('return'=>'xsd:string'),
        $NAMESPACE);

$server->register(
        'contact_by_email',
        array('user_name'=>'xsd:string','password'=>'xsd:string', 'email_address'=>'xsd:string'),
        array('return'=>'tns:contact_detail_array'),
        $NAMESPACE);

$server->register(
        'user_list',
        array('user_name'=>'xsd:string','password'=>'xsd:string'),
        array('return'=>'tns:user_detail_array'),
        $NAMESPACE);

$server->register(
        'search',
        array('user_name'=>'xsd:string','password'=>'xsd:string', 'name'=>'xsd:string'),
        array('return'=>'tns:contact_detail_array'),
        $NAMESPACE);

$server->register(
        'track_email',
        array('user_name'=>'xsd:string','password'=>'xsd:string','parent_id'=>'xsd:string', 'contact_ids'=>'xsd:string', 'date_sent'=>'xsd:date', 'email_subject'=>'xsd:string', 'email_body'=>'xsd:string'),
        array('return'=>'xsd:string'),
        $NAMESPACE);

$server->register(
        'create_contact',
        array('user_name'=>'xsd:string','password'=>'xsd:string', 'first_name'=>'xsd:string', 'last_name'=>'xsd:string', 'email_address'=>'xsd:string'),
        array('return'=>'xsd:string'),
        $NAMESPACE);

$server->register(
        'create_lead',
        array('user_name'=>'xsd:string','password'=>'xsd:string', 'first_name'=>'xsd:string', 'last_name'=>'xsd:string', 'email_address'=>'xsd:string'),
        array('return'=>'xsd:string'),
        $NAMESPACE);

$server->register(
        'create_account',
        array('user_name'=>'xsd:string','password'=>'xsd:string', 'name'=>'xsd:string', 'phone'=>'xsd:string', 'website'=>'xsd:string'),
        array('return'=>'xsd:string'),
        $NAMESPACE);

$server->register(
        'create_opportunity',
        array('user_name'=>'xsd:string','password'=>'xsd:string', 'name'=>'xsd:string', 'amount'=>'xsd:string'),
        array('return'=>'xsd:string'),
        $NAMESPACE);


$server->register(
        'create_case',
        array('user_name'=>'xsd:string','password'=>'xsd:string', 'name'=>'xsd:string'),
        array('return'=>'xsd:string'),
        $NAMESPACE);


$server->register(
        'upload_document',
        array('user_name'=>'xsd:string','password'=>'xsd:string', 'module_name'=>'xsd:string','module_entity_id'=>'xsd:string','document_title'=>'xsd:string','document_access'=>'xsd:string','document_type'=>'xsd:string','document_name'=>'xsd:string','tmp_name'=>'xsd:string','title'=>'xsd:string'),
        array('return'=>'xsd:string'),
        $NAMESPACE);

$server->register(
        'account_details',
        array('account_name'=>'xsd:string','phone_office'=>'xsd:string','website'=>'xsd:string'),
        array('return'=>'tns:account_detail_array'),
        $NAMESPACE);


$server->register(
        'save_approve_document',
        array('user_name'=>'xsd:string','password'=>'xsd:string', 'module_name'=>'xsd:string','module_entity_id'=>'xsd:string','document_title'=>'xsd:string','document_access'=>'xsd:string','document_type'=>'xsd:string','document_name'=>'xsd:string','tmp_name'=>'xsd:string','title'=>'xsd:string', 'opportunity_id'=>'xsd:string', 'rating_total'=>'xsd:string', 'investment_grade'=>'xsd:string'),
        array('return'=>'xsd:string'),
        $NAMESPACE);

$server->register(
        'update_case_status',
        array('case_id'=>'xsd:string','case_status'=>'xsd:string','document_type'=>'xsd:string'),
        array('return'=>'xsd:string'),
        $NAMESPACE);

$server->register(
        'update_intermediary_account_approval_status',
        array('case_id'=>'xsd:string','status'=>'xsd:string'),
        array('return'=>'xsd:string'),
        $NAMESPACE);

//$opportunity_id,$icrating_total,$investment_grading

$server->register(
        'wf_case_list',
        array('user_name'=>'xsd:string','password'=>'xsd:string','start_index'=>'xsd:string','end_index'=>'xsd:string'),
        array('return'=>'tns:case_list_array'),
        $NAMESPACE);

$server->register(
        'wf_case_list_total',
        array('user_name'=>'xsd:string','password'=>'xsd:string'),
        array('return'=>'xsd:int'),
        $NAMESPACE);

$server->register(
        'wf_case_getvariables',
        array('user_name'=>'xsd:string','password'=>'xsd:string', 'caseId'=>'xsd:string', 'process_name'=>'xsd:string'),
        array('return'=>'tns:get_variables_list_array'),
        $NAMESPACE);

$server->register(
        'wf_case_update_status',
        array('user_name'=>'xsd:string','password'=>'xsd:string', 'caseId'=>'xsd:string', 'process_name'=>'xsd:string', 'approval_status'=>'xsd:string'),
        array('return'=>'xsd:string'),
        $NAMESPACE);

$server->register(
        'test1',
        array('user_name'=>'xsd:string'),
        array('return'=>'xsd:string'),
        $NAMESPACE);

$server->register(
        'wf_case_input_document_list',
        array('user_name'=>'xsd:string','password'=>'xsd:string', 'caseId'=>'xsd:string', 'process_name'=>'xsd:string'),
        array('return'=>'tns:get_input_doc_list_array'),
        $NAMESPACE);
//New API is in these files
//if(!empty($administrator->settings['portal_on'])) {
//	require_once('soap/SoapPortalUsers.php');
//}

//$server->register(
//    'get_nearest_office_address',
//    array('latitude'=>'xsd:string', 'longitude`'=>'xsd:string'),
//    array('return'=>'tns:address_detail_array'),
//    $NAMESPACE);
//
//$server->register(
//    'nearest_office_address',
//     array('latitude'=>'xsd:string', 'longitude`'=>'xsd:string'),
//    array('return'=>'xsd:string'),
//    $NAMESPACE);

/**
 * Create a new session.  This method is required before calling any other functions.
 *
 * @param string $user_name -- the user name for the session
 * @param string $password -- MD5 of user password
 * @return "Success" if the session is created
 * @return "Failed" if the session creation failed.
 */
function create_session($user_name, $password) {

    if(validate_user($user_name, $password)) {
        return "Success";
    }

    return "Failed";
}

function get_nearest_address($latitude,$longitude) {

//    if(validate_user($user_name, $password)) {
//        return "Success";
//    }

    //creating sub office object
    $seed_sub_office = new Suboffice();

    $output_list = Array();

    //
    if($latitude && $longitude) {
        $output_list1 = $seed_sub_office->get_nearest__office_address($latitude,$longitude);
        $GLOBALS['log']->debug('Soap :: get_nearest_office_address : output_list '.print_r($output_list,true));
        foreach($output_list1 as $row) {
            $output_list[]=get_nearest_office_array($row);
        }
        //return $output_list;
    }
    return $output_list;
}

function get_all_branches() {

    //creating Branch  object
    $seed_branch = new Branch();

    $output_list = Array();

    $output_list1 = $seed_branch->get_all_branches_address();
    $GLOBALS['log']->debug('Soap :: get_nearest_office_address : output_list '.print_r($output_list,true));
    foreach($output_list1 as $row) {
        $output_list[]=get_all_branches_array($row);
    }
    return $output_list;
}

function get_all_suboffice_for_branch($branch_id) {

    //creating Branch  object
    $seed_branch = new Branch();
    $output_list = Array();


    if($branch_id) {
        $output_list1 = $seed_branch->get_all_suboffice_for_branch($branch_id);
        $GLOBALS['log']->debug('Soap :: get_all_suboffice_for_branch : output_list '.print_r($output_list1,true));
        foreach($output_list1 as $row) {
            $output_list[]=get_all_suboffice_for_branch_array($row);
        }
    }
    return $output_list;
}
/**
 * End a session.  This method will end the SOAP session.
 *
 * @param string $user_name -- the user name for the session
 * @return "Success" if the session is destroyed
 * @return "Failed" if the session destruction failed.
 */
function end_session($user_name) {
// get around optimizer warning
    $user_name = $user_name;
    return "Success";
}

/**
 * Validate the user session based on user name and password hash.
 *
 * @param string $user_name -- The user name to create a session for
 * @param string $password -- The MD5 sum of the user's password
 * @return true -- If the session is created
 * @return false -- If the session is not created
 */
function validate_user($user_name, $password) {
    global $server, $current_user;
    $password=md5($password);
    $user = new User();
    $user->user_name = $user_name;

    // Check to see if the user name and password are consistent.
    if($user->authenticate_user($password)) {
        // we also need to set the current_user.
        $user->retrieve($user->id);
        $current_user = $user;

        return true;
    }else {
        $GLOBALS['log']->fatal("SECURITY: failed attempted login for $user_name using SOAP api");
        $server->setError("Invalid username and/or password");
        return false;
    }

}

/**
 * Internal: When building a response to the plug-in for Microsoft Outlook, find
 * all contacts that match the email address that was provided.
 *
 * @param array by ref $output_list -- The list of matching beans.  New contacts that match
 *   the email address are appended to the $output_list
 * @param string $email_address -- an email address to search for
 * @param Contact $seed_contact -- A template SugarBean.  This is a blank Contact
 * @param ID $msi_id -- Index Count
 */
function add_contacts_matching_email_address(&$output_list, $email_address, &$seed_contact, &$msi_id) {
// escape the email address
    $safe_email_address = addslashes($email_address);
    global $current_user;

    // Verify that the user has permission to see Contact list views
    if(!$seed_contact->ACLAccess('ListView')) {
        return;
    }

    $where = "contacts.email1 like '$safe_email_address' OR contacts.email2 like '$safe_email_address'";
    $response = $seed_contact->get_list("last_name, first_name", $where, 0);
    $contactList = $response['list'];

    // create a return array of names and email addresses.
    foreach($contactList as $contact) {
        $output_list[] = Array("name1"	=> $contact->first_name,
                "name2" => $contact->last_name,
                "association" => $contact->account_name,
                "type" => 'Contact',
                "id" => $contact->id,
                "msi_id" => $msi_id,
                "email_address" => $contact->email1);

        $accounts = $contact->get_linked_beans('accounts','Account');
        foreach($accounts as $account) {
            $output_list[] = get_account_array($account, $msi_id);
        }

        $opps = $contact->get_linked_beans('opportunities','Opportunity');
        foreach($opps as $opp) {
            $output_list[] = get_opportunity_array($opp, $msi_id);
        }

        $cases = $contact->get_linked_beans('cases','aCase');
        foreach($cases as $case) {
            $output_list[] = get_case_array($case, $msi_id);
        }

        $bugs = $contact->get_linked_beans('bugs','Bug');
        foreach($bugs as $bug) {
            $output_list[] = get_bug_array($bug, $msi_id);
        }

        $msi_id = $msi_id + 1;
    }
}

/**
 * Internal: Add Leads that match the specified email address to the result array
 *
 * @param Array $output_list -- List of matching detail records
 * @param String $email_address -- Email address
 * @param Bean $seed_lead -- Seed Lead Bean
 * @param int $msi_id -- output array offset.
 */
function add_leads_matching_email_address(&$output_list, $email_address, &$seed_lead, &$msi_id) {
    $safe_email_address = addslashes($email_address);
    if(!$seed_lead->ACLAccess('ListView')) {
        return;
    }
    $where = "leads.email1 like '$safe_email_address' OR leads.email2 like '$safe_email_address'";
    $response = $seed_lead->get_list("last_name, first_name", $where, 0);
    $leadList = $response['list'];

    // create a return array of names and email addresses.
    foreach($leadList as $lead) {
        $output_list[] = Array("name1"	=> $lead->first_name,
                "name2" => $lead->last_name,
                "association" => $lead->account_name,
                "type" => 'Lead',
                "id" => $lead->id,
                "msi_id" => $msi_id,
                "email_address" => $lead->email1);

        $msi_id = $msi_id + 1;
    }
}

// Define a global current user
$current_user = null;

/**
 * Return a list of contact and lead detail records based on a single email
 * address or a  list of email addresses separated by '; '.
 *
 * This function does not require a session be created first.
 *
 * @param string $user_name -- User name to authenticate with
 * @param string $password -- MD5 of the user password
 * @param string $email_address -- Single email address or '; ' separated list of email addresses (e.x "test@example.com; test2@example.com"
 * @return contact detail array along with associated objects.
 */
function contact_by_email($user_name, $password, $email_address) {
    if(!validate_user($user_name, $password)) {
        return array();
    }

    $seed_contact = new Contact();
    $seed_lead = new Lead();
    $output_list = Array();
    $email_address_list = explode("; ", $email_address);

    // remove duplicate email addresses
    $non_duplicate_email_address_list = Array();
    foreach( $email_address_list as $single_address) {
        // Check to see if the current address is a match of an existing address
        $found_match = false;
        foreach( $non_duplicate_email_address_list as $non_dupe_single) {
            if(strtolower($single_address) == $non_dupe_single) {
                $found_match = true;
                break;
            }
        }

        if($found_match == false) {
            $non_duplicate_email_address_list[] = strtolower($single_address);
        }
    }

    // now copy over the non-duplicated list as the original list.
    $email_address_list =$non_duplicate_email_address_list;

    // Track the msi_id
    $msi_id = 1;

    foreach( $email_address_list as $single_address) {
        // verify that contacts can be listed
        if($seed_contact->ACLAccess('ListView')) {
            add_contacts_matching_email_address($output_list, $single_address, $seed_contact, $msi_id);
        }

        // verify that leads can be listed
        if($seed_lead->ACLAccess('ListView')) {
            add_leads_matching_email_address($output_list, $single_address, $seed_lead, $msi_id);
        }
    }

    return $output_list;
}

/**
 * Internal: convert a bean into an array
 *
 * @param Bean $bean -- The bean to convert
 * @param int $msi_id -- Russult array index
 * @return An associated array containing the detail fields.
 */
function get_contact_array($contact, $msi_id = '0') {
    return Array("name1"	=> $contact->first_name,
            "name2" => $contact->last_name,
            "association" => $contact->account_name,
            "type" => 'Contact',
            "id" => $contact->id,
            "msi_id" => $msi_id,
            "email_address" => $contact->email1);

}

/**
 * Internal: Convert a user into an array
 *
 * @param User $user -- The user to convert
 * @return An associated array containing the detail fields.
 */
function get_user_list_array($user) {
    return Array('email_address' => $user->email1,
            'user_name' => $user->user_name,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'department' => $user->department,
            'id' => $user->id,
            'title' => $user->title);
}

/**
 * Get a full user list.
 *
 * This function does not require a session be created first.
 *
 * @param string $user -- user name for validation
 * @param password $password -- MD5 hash of the user password for validation
 * @return User Array -- An array of user detail records
 */
function user_list($user, $password) {
    if(!validate_user($user, $password)) {
        return array();
    }

    $seed_user = new User();
    $output_list = Array();
    if(!$seed_user->ACLAccess('ListView')) {
        return $output_list;
    }
    $userList = $seed_user->get_full_list();


    foreach($userList as $user) {
        $output_list[] = get_user_list_array($user);
    }

    return $output_list;
}

/**
 * Internal: Search for contacts based on the specified name and where clause.
 * Currently only the name is used.
 *
 * @param string $name -- Name to search for.
 * @param string $where -- Where clause defaults to ''
 * @param int $msi_id -- Response array index
 * @return array -- Resturns a list of contacts that have the provided name.
 */
function contact_by_search($name, $where = '', $msi_id = '0') {
    $seed_contact = new Contact();
    if($where == '') {
        $where = $seed_contact->build_generic_where_clause($name);
    }
    if(!$seed_contact->ACLAccess('ListView')) {
        return array();
    }
    $response = $seed_contact->get_list("last_name, first_name", $where, 0);
    $contactList = $response['list'];

    $output_list = Array();

    // create a return array of names and email addresses.
    foreach($contactList as $contact) {
        $output_list[] = get_contact_array($contact, $msi_id);
    }
    return $output_list;
}

/**
 * Internal: convert a bean into an array
 *
 * @param Bean $bean -- The bean to convert
 * @param int $msi_id -- Russult array index
 * @return An associated array containing the detail fields.
 */
function get_lead_array($lead, $msi_id = '0') {
    return Array("name1"	=> $lead->first_name,
            "name2" => $lead->last_name,
            "association" => $lead->account_name,
            "type" => 'Lead',
            "id" => $lead->id,
            "msi_id" => $msi_id,
            "email_address" => $lead->email1);
}

function lead_by_search($name, $where = '', $msi_id = '0') {
    $seed_lead = new Lead();
    if($where == '') {
        $where = $seed_lead->build_generic_where_clause($name);
    }
    if(!$seed_lead->ACLAccess('ListView')) {
        return array();
    }
    $response = $seed_lead->get_list("last_name, first_name", $where, 0);
    $lead_list = $response['list'];

    $output_list = Array();

    // create a return array of names and email addresses.
    foreach($lead_list as $lead) {
        $output_list[] = get_lead_array($lead, $msi_id);
    }
    return $output_list;
}

/**
 * Internal: convert a bean into an array
 *
 * @param Bean $bean -- The bean to convert
 * @param int $msi_id -- Russult array index
 * @return An associated array containing the detail fields.
 */
//function get_account_array($account, $msi_id){
//	return Array("name1"	=> '',
//			"name2" => $account->name,
//			"association" => $account->billing_address_city,
//			"type" => 'Account',
//			"id" => $account->id,
//			"msi_id" => $msi_id,
//			"email_address" => $account->email1);
//}

function account_by_search($name, $where = '', $msi_id = '0') {
    $seed_account = new Account();
    if(!$seed_account->ACLAccess('ListView')) {
        return array();
    }
    if($where == '') {
        $where = $seed_account->build_generic_where_clause($name);
    }
    $response = $seed_account->get_list("name", $where, 0);
    $accountList = $response['list'];

    $output_list = Array();

    // create a return array of names and email addresses.
    foreach($accountList as $account) {
        $output_list[] = get_account_array($account, $msi_id);
    }
    return $output_list;
}

/**
 * Internal: convert a bean into an array
 *
 * @param Bean $bean -- The bean to convert
 * @param int $msi_id -- Russult array index
 * @return An associated array containing the detail fields.
 */
function get_opportunity_array($value, $msi_id = '0') {
    return  Array("name1"	=> '',
            "name2" => $value->name,
            "association" => $value->account_name,
            "type" => 'Opportunity',
            "id" => $value->id,
            "msi_id" => $msi_id,
            "email_address" => '');

}

function opportunity_by_search($name, $where = '', $msi_id = '0') {
    $seed = new Opportunity();
    if(!$seed->ACLAccess('ListView')) {
        return array();
    }
    if($where == '') {
        $where = $seed->build_generic_where_clause($name);
    }
    $response = $seed->get_list("name", $where, 0);
    $list = $response['list'];

    $output_list = Array();

    // create a return array of names and email addresses.
    foreach($list as $value) {
        $output_list[] = get_opportunity_array($value, $msi_id);
    }
    return $output_list;
}

/**
 * Internal: convert a bean into an array
 *
 * @param Bean $bean -- The bean to convert
 * @param int $msi_id -- Russult array index
 * @return An associated array containing the detail fields.
 */
function get_bug_array($value, $msi_id) {
    return Array("name1" => '',
            "name2" => $value->name,
            "association" => '',
            "type" => 'Bug',
            "id" => $value->id,
            "msi_id" => $msi_id,
            "email_address" => '');

}

/**
 * Internal: convert a bean into an array
 *
 * @param Bean $bean -- The bean to convert
 * @param int $msi_id -- Russult array index
 * @return An associated array containing the detail fields.
 */
function get_case_array($value, $msi_id) {
    return Array("name1" => '',
            "name2" => $value->name,
            "association" => $value->account_name,
            "type" => 'Case',
            "id" => $value->id,
            "msi_id" => $msi_id,
            "email_address" => '');

}

function bug_by_search($name, $where = '', $msi_id='0') {
    $seed = new Bug();
    if(!$seed->ACLAccess('ListView')) {
        return array();
    }
    if($where == '') {
        $where = $seed->build_generic_where_clause($name);
    }
    $response = $seed->get_list("name", $where, 0);
    $list = $response['list'];

    $output_list = Array();

    // create a return array of names and email addresses.
    foreach($list as $value) {
        $output_list[] = get_bug_array($value, $msi_id);
    }
    return $output_list;
}

function case_by_search($name, $where = '', $msi_id='0') {
    $seed = new aCase();
    if(!$seed->ACLAccess('ListView')) {
        return array();
    }
    if($where == '') {
        $where = $seed->build_generic_where_clause($name);
    }
    $response = $seed->get_list("name", $where, 0);
    $list = $response['list'];

    $output_list = Array();

    // create a return array of names and email addresses.
    foreach($list as $value) {
        $output_list[] = get_case_array($value, $msi_id);
    }
    return $output_list;
}

/**
 * Record and email message and associated it with the specified parent bean and contact ids.
 *
 * This function does not require a session be created first.
 *
 * @param string $user_name -- Name of the user to authenticate
 * @param string $password -- MD5 hash of the user password for authentication
 * @param id $parent_id -- [optional] The parent record to link the email to.
 * @param unknown_type $contact_ids
 * @param string $date_sent -- Date/time the email was sent in Visual Basic Date format. (e.g. '7/22/2004 9:36:31 AM')
 * @param string $email_subject -- The subject of the email
 * @param string $email_body -- The body of the email
 * @return "Invalid username and/or password"
 * @return -1 If the authenticated user does not have ACL access to save Email.
 */
function track_email($user_name, $password,$parent_id, $contact_ids, $date_sent, $email_subject, $email_body) {
    if(!validate_user($user_name, $password)) {
        return "Invalid username and/or password";
    }
    global $current_user;

    $GLOBALS['log']->info("In track email: username: $user_name contacts: $contact_ids date_sent: $date_sent");

    // translate date sent from VB format 7/22/2004 9:36:31 AM
    // to yyyy-mm-dd 9:36:31 AM

    $date_sent = ereg_replace("([0-9]*)/([0-9]*)/([0-9]*)( .*$)", "\\3-\\1-\\2\\4", $date_sent);

    require_once('modules/Users/User.php');
    $seed_user = new User();

    $user_id = $seed_user->retrieve_user_id($user_name);
    $seed_user->retrieve($user_id);
    $current_user = $seed_user;
    require_once('modules/Emails/Email.php');

    $email = new Email();
    if(!$email->ACLAccess('Save')) {
        return -1;
    }
    $email->description = $email_body;
    $email->name = $email_subject;
    $email->user_id = $user_id;
    $email->assigned_user_id = $user_id;
    $email->assigned_user_name = $user_name;
    $email->date_start = $date_sent;

    // Save one copy of the email message
    $parent_id_list = explode(";", $parent_id);
    $parent_id = explode(':', $parent_id_list[0]);

    // Having a parent object is optional.  If it is set, then associate it.
    if(isset($parent_id[0]) && isset($parent_id[1])) {
        $email->parent_type = $parent_id[0];
        $email->parent_id = $parent_id[1];
    }

    $email->save();
    // for each contact, add a link between the contact and the email message
    $id_list = explode(";", $contact_ids);

    foreach( $id_list as $id) {
        if(!empty($id))
            $email->set_emails_contact_invitee_relationship($email->id, $id);
    }

    return "Succeeded";
}

function create_contact($user_name,$password, $first_name, $last_name, $email_address) {
    if(!validate_user($user_name, $password)) {
        return 0;
    }

    require_once('modules/Users/User.php');
    $seed_user = new User();
    $user_id = $seed_user->retrieve_user_id($user_name);
    $seed_user->retrieve($user_id);

    require_once('modules/Contacts/Contact.php');
    $contact = new Contact();
    if(!$contact->ACLAccess('Save')) {
        return -1;
    }
    $contact->first_name = $first_name;
    $contact->last_name = $last_name;
    $contact->email1 = $email_address;
    $contact->assigned_user_id = $user_id;
    $contact->assigned_user_name = $user_name;

    return $contact->save();
}

function create_lead($user_name,$password, $first_name, $last_name, $email_address) {
    if(!validate_user($user_name, $password)) {
        return 0;
    }

    //todo make the activity body not be html encoded

    require_once('modules/Users/User.php');
    $seed_user = new User();
    $user_id = $seed_user->retrieve_user_id($user_name);

    require_once('modules/Leads/Lead.php');
    $lead = new Lead();
    if(!$lead->ACLAccess('Save')) {
        return -1;
    }
    $lead->first_name = $first_name;
    $lead->last_name = $last_name;
    $lead->email1 = $email_address;
    $lead->assigned_user_id = $user_id;
    $lead->assigned_user_name = $user_name;
    return $lead->save();
}

function create_account($user_name,$password, $name, $phone, $website) {
    if(!validate_user($user_name, $password)) {
        return 0;
    }

    //todo make the activity body not be html encoded

    require_once('modules/Users/User.php');
    $seed_user = new User();
    $user_id = $seed_user->retrieve_user_id($user_name);
    $account = new Account();
    if(!$account->ACLAccess('Save')) {
        return -1;
    }
    $account->name = $name;
    $account->phone_office = $phone;
    $account->website = $website;
    $account->assigned_user_id = $user_id;
    $account->assigned_user_name = $user_name;
    return $account->save();

}

function create_case($user_name,$password, $name) {
    if(!validate_user($user_name, $password)) {
        return 0;
    }

    //todo make the activity body not be html encoded

    require_once('modules/Users/User.php');
    $seed_user = new User();
    $user_id = $seed_user->retrieve_user_id($user_name);
    $case = new aCase();
    if(!$case->ACLAccess('Save')) {
        return -1;
    }
    $case->assigned_user_id = $user_id;
    $case->assigned_user_name = $user_name;
    $case->name = $name;
    return $case->save();
}

function create_opportunity($user_name,$password, $name, $amount) {
    if(!validate_user($user_name, $password)) {
        return 0;
    }

    require_once('modules/Users/User.php');
    $seed_user = new User();
    $user_id = $seed_user->retrieve_user_id($user_name);
    $opp = new Opportunity();
    if(!$opp->ACLAccess('Save')) {
        return -1;
    }
    $opp->name = $name;
    $opp->amount = $amount;
    $opp->assigned_user_id = $user_id;
    $opp->assigned_user_name = $user_name;
    return $opp->save();
}

function search($user_name, $password,$name) {
    if(!validate_user($user_name, $password)) {
        return array();
    }
    $name_list = explode("; ", $name);
    $list = array();
    foreach( $name_list as $single_name) {
        $list = array_merge($list, contact_by_search($single_name));
        $list = array_merge($list, lead_by_search($single_name));
        $list = array_merge($list, account_by_search($single_name));
        $list = array_merge($list, case_by_search($single_name));
        $list = array_merge($list, opportunity_by_search($single_name));
        $list = array_merge($list, bug_by_search($single_name));
    }
    return $list;
}

function test1($user_name) {
    return "return value ".$user_name;
}

//Start functions added by Sanjay

function upload_document($user_name,$password, $module_name, $module_entity_id, $document_title, $document_access, $document_type, $document_name, $tmp_name, $title) {

//$GLOBALS['log']->debug("IN SIDE WS ");
    require_once('modules/Documents/Document.php');
    require_once('include/formbase.php');
    require_once('include/upload_file.php');
    require_once('modules/DocumentRevisions/DocumentRevision.php');


    $document_access='Private';
    $prefix='';

    $do_final_move = 0;

    $Revision = new DocumentRevision();

    require_once('modules/Users/User.php');

    $seed_user = new User();
    $user_id = $seed_user->retrieve_user_id($user_name);

    $Document = new Document();
    if(!$Document->ACLAccess('Save')) {
        return -1;
    }
    $Document->document_name = $document_title;
    $Document->created_by=$user_id;
    $Document->status_id="Active";



    $do_final_move = 0;


    $Revision->filename = $document_title;
    $Revision->file_mime_type = $mime_type;
    $Revision->file_ext = $ext;
    $Revision->created_by=$user_id;

    $do_final_move = 1;


    $return_id = $Document->save();


    //save revision.
    $Revision->change_log = $mod_strings['DEF_CREATE_LOG'];
    $Revision->revision = $Document->revision;
    $Revision->document_id = $Document->id;
    $Revision->save();



    //update document with latest revision id
    $Document->process_save_dates=false; //make sure that conversion does not happen again.
    $Document->document_revision_id = $Revision->id;
    $Document->save();


    //$GLOBALS['log']->debug("WS 1".$title);
    //set relationship field values if parent_id is passed.
    //this happens only in the new mode because document edit view
    //does not have a way to change the parent.
    //also we want to stamp the document revision only once.

    $save_revision['document_revision_id']=$Document->document_revision_id;
    switch ($module_name) {

        case "contracts" :
            $Document->load_relationship('contracts');
            $Document->contracts->add($module_entity_id,$save_revision);
            break;

        //todo remove leads case.
        case "leads" :
            $Document->load_relationship('leads');
            $Document->leads->add($module_entity_id,$save_revision);
            break;

        case "accounts" :
            $Document->load_relationship('accounts');
            $Document->accounts->add($module_entity_id,$save_revision);
            break;

        case "contacts" :
            $Document->load_relationship('contacts');
            $Document->contacts->add($module_entity_id,$save_revision);
            break;

        case "financials" :

            $Document->load_relationship('financials');
            //Load relationship not done for time being
            //$Document-> contacts->add($module_entity_id,$save_revision);
            break;
    }
    //Load relationship not done for time being
    //after loading the signed document, delete the relationship between
    //the template and the contract.
    //$query="update linked_documents set deleted=1 where id='".$_POST['load_signed_id']."'";
    //$Document->db->query($query);
    //$GLOBALS['log']->debug("WS 2".$title);

    if ($do_final_move) {
        global $sugar_config;

        $upload_file = new UploadFile();

        $upload_file->set_for_soap($document_name, $tmp_name);
        // $GLOBALS['log']->debug("WS 3".$title);
        $dms_document_id=$upload_file->final_move($Revision->id,$user_name,$password,$tmp_name,$document_name,$title);
        // $GLOBALS['log']->debug("WS 4".$title);
        //update dms document id to ptforce documents table
        $query="update document_revisions set dms_document_id=".$dms_document_id." where id='".$Revision->id."'";
        $Document->db->query($query);
    }


    return $Revision->id;
}


function account_details($account_name, $phone_office, $website) {

    require_once('config.php');
    global $sugar_config;
    $user_name=$sugar_config['respforce_admin_username'];
    $password=md5($sugar_config['respforce_admin_password']);
    $GLOBALS['log']->debug("WS USERNAME".$sugar_config['respforce_admin_username']);


    $output_list=Array();
    if(!validate_user($user_name, $password)) {
        return 0;
    }
    //user details need to be retrieved for creating active session
    require_once('modules/Users/User.php');
    $seed_user = new User();
    $user_id = $seed_user->retrieve_user_id($user_name);

    require_once('modules/Accounts/Account.php');


    $account=new Account();
    if(empty($website)) {

        if(empty($phone_office)) {
            $fields_array=array("name"=>$account_name);
        }else {
            $fields_array=array("name"=>$account_name,"phone_office"=>$phone_office);
        }

    }
    else {

        if(empty($phone_office)) {
            $fields_array=array("name"=>$account_name,"website"=>$website);
        }else {
            $fields_array=array("name"=>$account_name,"phone_office"=>$phone_office,"website"=>$website);
        }

    }

    if(!$account->ACLAccess('Save')) {
        return -1;
    }

    $account->retrieve_by_string_fields($fields_array);

    $reporting_user = new User();
    $reporting_user->retrieve($account->created_by);

    $output_list[]=Array('created_by_name' => $account->created_by_name,
            'reports_to_name' =>  $reporting_user->reports_to_name             //$seed_user->reports_to_name
    );

    return $output_list;

//return $account_name;

}

// 
function save_approve_document($user_name,$password, $module_name, $module_entity_id, $document_title, $document_access, $document_type, $document_name, $tmp_name, $title ,$opportunity_id,$rating_total,$investment_grade) {
    $GLOBALS['log']->debug("save_approve_document :: Params :Usre:$user_name pasword :$password,module name: $module_name, $module_entity_id, $document_title, $document_access, $document_type, $document_name, $tmp_name, $title ,$opportunity_id,$rating_total,$investment_grade");
    $GLOBALS['log']->debug("APPROVE DOC".$document_type);
    $GLOBALS['log']->debug("APPROVE DOC".$tmp_name);
    require_once('modules/Documents/Document.php');
    require_once('include/formbase.php');
    require_once('include/upload_file.php');
    require_once('modules/DocumentRevisions/DocumentRevision.php');

    $document_access='Private';
    $prefix='';
    $do_final_move = 0;
    $Revision = new DocumentRevision();
    $output_list=Array();
    if(!validate_user($user_name,md5($password))) {
        return 0;
    }
    //user details need to be retrieved for creating active session
    require_once('modules/Users/User.php');
    $seed_user = new User();
    $user_id = $seed_user->retrieve_user_id($user_name);
    $seed_user=$seed_user->retrieve($user_id);

    $reports_to = new User();
    $reports_to = $reports_to->retrieve($seed_user->reports_to_id);

    $Document = new Document();
    if(!$Document->ACLAccess('Save')) {
        return -1;
    }
    $Document->document_name = $document_title;
    $Document->created_by=$user_id;
    $Document->status_id="Active";


    $do_final_move = 0;

    $Revision->filename = $document_title;
    $Revision->file_mime_type = $mime_type;
    $Revision->file_ext = $ext;
    $Revision->created_by=$user_id;
    $do_final_move = 1;

    $return_id = $Document->save();


    //save revision.
    $Revision->change_log = $mod_strings['DEF_CREATE_LOG'];
    $Revision->revision = $Document->revision;
    $Revision->document_id = $Document->id;
    $Revision->save();



    //update document with latest revision id
    $Document->process_save_dates=false; //make sure that conversion does not happen again.
    $Document->document_revision_id = $Revision->id;
    $Document->save();


    $save_revision['document_revision_id']=$Document->document_revision_id;

    $GLOBALS['log']->debug("Revision ID".$Revision->id);
    if ($do_final_move) {
        global $sugar_config;

        $upload_file = new UploadFile();
        $upload_file->set_for_soap($document_name, $tmp_name);

        $dms_id=$upload_file->final_move($Revision->id,$user_name,$password,$tmp_name,$document_name,$title);
        $GLOBALS['log']->debug("WS 4".$title);
        //update dms document id to ptforce documents table
        $query="update document_revisions set dms_document_id=".$dms_id." where id='".$Revision->id."'";
        $Document->db->query($query);
    }

    require_once('include/workflow/Opportunities/PMDocumentApproval.php');
    include_once 'include/utils.php';
    $new_case=new PMDocumentApproval();
    switch($document_type) {

        case 'icrating':

            $GLOBALS['log']->debug(" PT DOCUMENT ".$dms_id);

            $sql="SELECT opp.name opp_name, acc.name acc_name  FROM opportunities opp, accounts acc WHERE opp.id LIKE '$opportunity_id' and acc.id IN (SELECT account_id FROM accounts_opportunities WHERE opportunity_id = '$opportunity_id')";
            $result=$GLOBALS['db']->query($sql);
            $row = $GLOBALS['db']->fetchByAssoc($result);
            $account_name=$row['acc_name'];
            $opportunity_name=$row['opp_name'];

            $GLOBALS['log']->debug(" ACCOUNT NAME ".$account_name);
            $GLOBALS['log']->debug(" OPP NAME ".$opportunity_name);

            $_SESSION['login_c_password'] = $password;

            $GLOBALS['log']->debug(" Created by ".$seed_user->workflow_id);
            $GLOBALS['log']->debug(" Reports to ".$reports_to->workflow_id);

            # No Need to approval for IC RATING  | commented on 29 mar 2011 | request by Ashish
            #$case_Id=$new_case->newCase($seed_user->workflow_id, $reports_to->workflow_id , $dms_id, $rating_total, $account_name, $opportunity_name, $document_type,$sugar_config['ptforce_soap']);
            #$GLOBALS['log']->debug(" icrating case_Id:".$case_Id);
            $case_Id=NULL;
            //flag for checking that the session is opened once
            $new_case->session_open='T';


            // Insert into IC_rating_result table
            //            $ch = curl_init();
            //            curl_setopt($ch, CURLOPT_URL,UID_URL);
            //            curl_setopt($ch, CURLOPT_HEADER, 0);
            //            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            //            curl_setopt($ch, CURLOPT_TIMEOUT,60);
            //            $icrating_result_id=curl_exec($ch);
            //            curl_close($ch);

            $icrating_result_id = create_guid();

            $GLOBALS['log']->debug("IF DONE".$icrating_result_id);
            $sql="insert into IC_rating_result set
            id='$icrating_result_id',
            date_entered=now(),
            date_modified=now(),
            assigned_user_id='',
            modified_user_id='',
            created_by='$user_id',
            rating_total='$rating_total',
            investment_grade='$investment_grade',
            opportunity_id='$opportunity_id',
            document_id='$dms_id',
            revision_id='$Revision->id',
            case_id='$case_Id',
            case_status=2,
            deleted=0";
            $GLOBALS['log']->debug("IN SIDE WS SQL".$sql);
            $GLOBALS['db']->query($sql);
            //////////     END ICRATING     ///////////////////////
            break;

        case 'farprism':

            $GLOBALS['log']->debug(" PT DOCUMENT ".$dms_id);

            $sql="SELECT opp.name opp_name, acc.name acc_name  FROM opportunities opp, accounts acc WHERE opp.id LIKE '$opportunity_id' and acc.id IN (SELECT account_id FROM accounts_opportunities WHERE opportunity_id = '$opportunity_id')";
            $result=$GLOBALS['db']->query($sql);
            $row = $GLOBALS['db']->fetchByAssoc($result);
            $account_name=$row['acc_name'];
            $opportunity_name=$row['opp_name'];

            $GLOBALS['log']->debug(" ACCOUNT NAME ".$account_name);
            $GLOBALS['log']->debug(" OPP NAME ".$opportunity_name);

            $_SESSION['login_c_password'] = $password;
            $GLOBALS['log']->debug(" save_approve_document ::Created by ".$seed_user->workflow_id);
            $GLOBALS['log']->debug(" save_approve_document :: Reports to ".$reports_to->workflow_id);

            # No Need to approval for FAR prism  | commented on 29 mar 2011 | request by Ashish
            //$case_Id=$new_case->newCase($seed_user->workflow_id, $reports_to->workflow_id , $dms_id, $rating_total, $account_name, $opportunity_name, $document_type,$sugar_config['ptforce_soap']);

            #flag for checking that the session is opened once
            $GLOBALS['log']->debug(" farprism case_Id:".$case_Id);
            $new_case->session_open='T';

            //insert into prism results table
            //            $ch = curl_init();
            //            curl_setopt($ch, CURLOPT_URL,UID_URL);
            //            curl_setopt($ch, CURLOPT_HEADER, 0);
            //            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            //            curl_setopt($ch, CURLOPT_TIMEOUT,60);
            //            $prism_result_id=curl_exec($ch);
            $prism_result_id = create_guid();

            $prism_value=$rating_total;

            $sql="insert into FAR_prism_result set
            id='$prism_result_id',
            date_entered=now(),
            date_modified=now(),
            assigned_user_id='',
            modified_user_id='',
            created_by='$user_id',
            prism_value='$prism_value',
            opportunity_id='$opportunity_id',
            document_id='$dms_id',
            case_id='$case_Id',
            case_status=2,
            revision_id='$Revision->id',
            deleted=0
                    ";
            $GLOBALS['log']->debug("IN SIDE WS SQL".$sql);
            $GLOBALS['db']->query($sql);


            break;

    }

    ////
    if($new_case->session_open=='T') {
        $GLOBALS['log']->debug("IN ASSOCIATE");
        $new_case->associateInputdocumenttoCase($user_name, $password, $dms_id , $case_Id, $seed_user->workflow_id, $user_name);
    }

////


}

function update_case_status($case_id,$case_status,$document_type) {

    $GLOBALS['log']->debug(" CASE ID -  ".$case_id);
    $GLOBALS['log']->debug(" STATUS ".$case_status);
    $GLOBALS['log']->debug(" DOCUMENT TYPE ".$document_type);


    switch($document_type) {

        case 'icrating':
        //  $case_status=1;
            $sql="update IC_rating_result set case_status=".$case_status." where case_id='".$case_id."'";
            if($GLOBALS['db']->query($sql)) {
                return '1';
            }
            break;

        case 'farprism':
        //  $case_status=1;
            $sql="update FAR_prism_result set case_status=".$case_status." where case_id='".$case_id."'";
            if($GLOBALS['db']->query($sql)) {
                return '1';
            }
            break;

        case 'ictemplate':
        //  $case_status=1;
            $sql="update IC_template_users set case_status=".$case_status." where case_id='".$case_id."'";
            if($GLOBALS['db']->query($sql)) {
                return '1';
            }
            break;

    }

    return '0';
}


function update_intermediary_account_approval_status($case_id, $status) {

    switch($status) {

        case 'Yes':
            $case_status=1;
            break;

        case 'No':
            $case_status=0;
            break;
    }


    $sql="update accounts_approvals set status=".$case_status." where case_id='".$case_id."'";
    if($GLOBALS['db']->query($sql)) {
        return '1';
    }

}

//function to call workflow case list
function wf_case_list($user_name,$password,$start_index,$end_index) {

    $GLOBALS['log']->debug(" CASE LIST FINAL  ");
    require_once('modules/Users/User.php');
    if(!validate_user($user_name, md5($password))) {
        return "Invalid username and/or password";
    }

    require_once('include/workflow/Users/PMCaseHandler.php');
    $case_list=new PMCaseHandler();
    $result=$case_list->caseList($start_index,$end_index);
    $GLOBALS['log']->debug(" CASE LIST FINAL  ".print_r($result,true));

    foreach($result as $row) {

        $GLOBALS['log']->debug(" CASE LIST FINAL CASEID  ".$row->caseId);
        $output_list[]=Array('case_id' => $row->caseId ,'case_name' => $row->caseName, 'process_name'=> $row->processName , 'sent_by'=> $row->caseCreatorUserName , 'case_status'=>$row->caseStatus , 'create_date'=>$row->createDate );

    }

    //$output_list[]=Array('case_id' => $row->caseId ,'case_name' => $row->caseName, 'process_name'=> $row->processName , 'sent_by'=> $row->caseCreatorUserName , 'case_status'=>$row->caseStatus , 'create_date'=>$row->createDate );

    return $output_list;


}

function wf_case_list_total($user_name,$password) {

    $GLOBALS['log']->debug(" CASE LIST FINAL  ");
    require_once('modules/Users/User.php');
    if(!validate_user($user_name, md5($password))) {
        return "Invalid username and/or password";
    }

    require_once('include/workflow/Users/PMCaseHandler.php');
    $case_list=new PMCaseHandler();
    $result=$case_list->caseListTotal();
    $GLOBALS['log']->debug(" CASE LIST TOTAL  ".$result);

    return $result;

}



function wf_case_getvariables($user_name,$password,$caseId,$process_name) {

    $GLOBALS['log']->debug("wf_case_getvariables: CASE LIST FINAL  ");
    require_once('modules/Users/User.php');
    if(!validate_user($user_name, md5($password))) {
        $GLOBALS['log']->debug(" wf_case_getvariables :Invalid username and/or password  ");
        return "Invalid username and/or password";
    }
    require_once('include/workflow/Users/PMCaseHandler.php');
    $case_list=new PMCaseHandler();
    $result=$case_list->caseVariables($caseId, $process_name);
    $GLOBALS['log']->debug(" CASE Variables  ".print_r($result,true));
    if($result->status_code==0) {
        foreach($result->variables as $row) {
            $GLOBALS['log']->debug(" CASE Variables: name  ".$row->name);
            $GLOBALS['log']->debug(" CASE Variables: value  ".$row->value);
            $output_list[]=Array('key' => $row->name ,'value' => $row->value);
        }
        return $output_list;
    }else {
        return $result;
    }

}

function wf_case_update_status($user_name,$password,$caseId,$process_name,$approval_status) {
    $GLOBALS['log']->debug(" UPDATE CASE STATUS  ");
    require_once('modules/Users/User.php');
    if(!validate_user($user_name, md5($password))) {
        return "Invalid username and/or password";
    }
    require_once('include/workflow/Users/PMCaseHandler.php');
    $case_list=new PMCaseHandler();
    $result=$case_list->update_case($caseId,$process_name,$approval_status);
    $GLOBALS['log']->debug(" UPDATE CASE STATUS  ".print_r($result,true));

    return $result;
}

//End functions added by Sanjay

function wf_case_input_document_list($user_name,$password,$caseId,$process_name) {
    $GLOBALS['log']->debug("wf_case_input_document_list: CASE LIST FINAL  ");
    require_once('modules/Users/User.php');
    if(!validate_user($user_name, md5($password))) {
        $GLOBALS['log']->debug(" wf_case_getvariables :Invalid username and/or password  ");
        return "Invalid username and/or password";
    }
    include_once ( "include/workflow/wsConfig.php" );
    include_once ( "include/workflow/wsClient.php" );
    $result=ws_InputDocumentList($caseId);

    //   RESULT stdClass Object
    //(
    //    [documents] => stdClass Object
    //        (
    //            [guid] => 9848661804daefe75d09791057867135
    //            [filename] => abc.txt(1)
    //            [docId] => 6751762974d5bb1c8d2b5d5004396362
    //            [version] => 1
    //            [createDate] => 2011-04-20 11:40:37
    //            [createBy] => BD User1
    //            [type] => INPUT
    //            [index] => 1
    //            [link] => cases/../knowledgeTree/services/documentShow?a=9848661804daefe75d09791057867135&b=516&t=&r=2858
    //        )
    //
    //)

    $GLOBALS['log']->debug(" CASE Variables  ".print_r($result,true));
    if($result) {
        foreach($result->documents as $name=>$value) {
            $output_list[]=Array('key' => $name ,'value' => $value);
        }

        //$output_list[]=Array('key' =>'wf_dld_prefix_url' ,'value' =>WF_DOC_DOWNLOAD_URL);
        $output_list[]=Array('key' =>'wf_dld_prefix_url' ,'value' =>'http://bcforce.timesgroup.com/sysworkflow/en/green/');

        return $output_list;
    }else {
        return $result;
    }
}

// Added by pankaj
//function get_nearest_office_address($latitude, $longitude) {
//    return "success 2";
//
//
////To Check User Name & Password
//    $GLOBALS['log']->debug("get_nearest_office_address :: input parameter : $latitude, $longitude");
//    //	if(!validate_user($user_name, $password)){
//    //
//    //		return array('');
//    //	}else{
//    //            	return array('Result Not available');
//    //        }
//
//    require_once('modules/SubofficeMaster/Suboffice.php');
//    //creating sub office object
//    $seed_sub_office = new Suboffice();
//
//    $output_list = Array();
//
//    //
//    if($latitude && $longitude) {
//        $output_list1 = $seed_sub_office->get_nearest__office_address($latitude,$longitude);
//        $GLOBALS['log']->debug('Soap :: get_nearest_office_address : output_list '.print_r($output_list,true));
//        foreach($output_list1 as $row){
//            $output_list[]=get_nearest_office_array($row);
//        }
//       return $output_list;
//    }
//}
//
//function nearest_office_address($latitude, $longitude){
//    return "sucess 3";
//}
//

function get_nearest_office_array($row) {
    return Array("description"	=> $row['description'],
            "branch" => $row['branch_name'],
            "latitude" => $row['latitude'],
            "longitude" => $row['longitude'],
            "address"=>$row['office_detail'],
            "message" => 'success',
    );

}

function get_all_branches_array($row) {
    return Array("branch_id"	=> $row['branch_id'],
            "branch_name" => $row['branch_name'],
            "message" => 'success',
    );
}

function get_all_suboffice_for_branch_array($row) {
    return Array("description"	=> $row['description'],
            "branch" => $row['branch_name'],
            "latitude" => $row['latitude'],
            "longitude" => $row['longitude'],
            "address"=>$row['office_detail'],
            "message" => 'success',
    );
}


require_once('soap/SoapSugarUsers.php');

require_once('soap/SoapData.php');

/* Begin the HTTP listener service and exit. */
ob_clean();
$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
$server->service($HTTP_RAW_POST_DATA);

ob_end_flush();
sugar_cleanup();
exit();



?>
