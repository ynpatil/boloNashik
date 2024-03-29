<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
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
/*********************************************************************************
* $Id: en_us.lang.php,v 1.37 2006/06/09 10:55:36 wayne Exp $
* Description:  Defines the English language pack for the base application.
* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
* All Rights Reserved.
* Contributor(s): ______________________________________..
********************************************************************************/

$mod_strings = array (
    //DON'T CONVERT THESE THEY ARE MAPPINGS
    'db_last_name' => 'LBL_LIST_LAST_NAME',
    'db_first_name' => 'LBL_LIST_FIRST_NAME',
    'db_title' => 'LBL_LIST_TITLE',
    'db_email1' => 'LBL_LIST_EMAIL_ADDRESS',
    'db_account_name' => 'LBL_LIST_ACCOUNT_NAME',
    'db_email2' => 'LBL_LIST_EMAIL_ADDRESS',
    //END DON'T CONVERT
    'ERR_DELETE_RECORD' => 'en_us A record number must be specified to delete the lead.',
    'LBL_ACCOUNT_DESCRIPTION'=> 'Account Description',
    'LBL_ACCOUNT_ID'=>'Account ID',
    'LBL_BRAND_ID'=>'Brand ID',    
    'LBL_ACCOUNT_NAME' => 'Account Name:',
    'LBL_ACTIVITIES_SUBPANEL_TITLE'=>'Activities',
    'LBL_ADD_BUSINESSCARD' => 'Add Business Card',
    'LBL_ADDRESS_INFORMATION' => 'Address Information',
    'LBL_ALT_ADDRESS_CITY' => 'Alt Address City',
    'LBL_ALT_ADDRESS_COUNTRY' => 'Alt Address Country',
    'LBL_ALT_ADDRESS_POSTALCODE' => 'Alt Address Postalcode',
    'LBL_ALT_ADDRESS_STATE' => 'Alt Address State',
    'LBL_ALT_ADDRESS_STREET_2' => 'Alt Address Street 2',
    'LBL_ALT_ADDRESS_STREET_3' => 'Alt Address Street 3',
    'LBL_ALT_ADDRESS_STREET' => 'Alt Address Street',
    'LBL_ALTERNATE_ADDRESS' => 'Other Address:',
    'LBL_ANY_ADDRESS' => 'Any Address:',
    'LBL_ANY_EMAIL' => 'Any Email:',
    'LBL_ANY_PHONE' => 'Any Phone:',
    'LBL_ASSIGNED_TO_NAME' => 'Assigned To Name',
    'LBL_BACKTOLEADS' => 'Back To Leads',
    'LBL_BUSINESSCARD' => 'Convert Lead',
    'LBL_CITY' => 'City:',
    'LBL_CONTACT_ID' => 'Contact ID',
    'LBL_CONTACT_INFORMATION' => 'Lead Information',
    'LBL_CONTACT_NAME' => 'Lead Name:',
    'LBL_CONTACT_OPP_FORM_TITLE' => 'Lead-Opportunity:',
    'LBL_CONTACT_ROLE' => 'Role:',
    'LBL_CONTACT' => 'Lead:',
    'LBL_CONVERTED_ACCOUNT'=>'Converted Account:',
    'LBL_CONVERTED_BRAND'=>'Converted Brand:',    
    'LBL_CONVERTED_CONTACT' => 'Converted Contact:',
    'LBL_CONVERTED_OPP'=>'Converted Opportunity:',
    'LBL_CONVERTED'=> 'Converted',
    'LBL_CONVERTLEAD_BUTTON_KEY' => 'V',
    'LBL_CONVERTLEAD_TITLE' => 'Convert Lead [Alt+V]',
    'LBL_CONVERTLEAD' => 'Convert Lead',
    'LBL_COUNTRY' => 'Country:',
    'LBL_CREATED_ACCOUNT' => 'Created a new account',
    'LBL_CREATED_CALL' => 'Created a new call',
    'LBL_CREATED_CONTACT' => 'Created a new contact',
    'LBL_CREATED_MEETING' => 'Created a new meeting',
    'LBL_CREATED_OPPORTUNITY' => 'Created a new opportunity',
    'LBL_CREATED_BRAND' => 'Created a new brand',    
    'LBL_DEFAULT_SUBPANEL_TITLE' => 'Leads',
    'LBL_DEPARTMENT' => 'Department:',
    'LBL_DESCRIPTION_INFORMATION' => 'Description Information',
    'LBL_DESCRIPTION' => 'Description:',
    'LBL_DO_NOT_CALL' => 'Do Not Call:',
    'LBL_DUPLICATE' => 'Similar Leads',
    'LBL_EMAIL_ADDRESS' => 'Email:',
    'LBL_EMAIL_OPT_OUT' => 'Email Opt Out:',
    'LBL_EXISTING_ACCOUNT' => 'Used an existing account',
    'LBL_EXISTING_BRAND' => 'Used an existing brand',    
    'LBL_EXISTING_CONTACT' => 'Used an existing contact',
    'LBL_EXISTING_OPPORTUNITY' => 'Used an existing opportunity',
    'LBL_FAX_PHONE' => 'Fax:',
    'LBL_FIRST_NAME' => 'First Name:',
    'LBL_FULL_NAME' => 'Full Name:',
    'LBL_HISTORY_SUBPANEL_TITLE'=>'History',
    'LBL_HOME_PHONE' => 'Home Phone:',
    'LBL_IMPORT_VCARD' => 'Import vCard',
    'LBL_IMPORT_VCARDTEXT' => 'Automatically create a new lead by importing a vCard from your file system.',
    'LBL_INVALID_EMAIL'=>'Invalid Email:',
    'LBL_INVITEE' => 'Direct Reports',
    'LBL_LAST_NAME' => 'Last Name:',
    'LBL_LEAD_SOURCE_DESCRIPTION' => 'Lead Source Description:',
    'LBL_LEAD_SOURCE' => 'Lead Source:',
    'LBL_LIST_ACCOUNT_NAME' => 'Account Name',
    'LBL_LIST_CONTACT_NAME' => 'Lead Name',
    'LBL_LIST_CONTACT_ROLE' => 'Role',
    'LBL_LIST_DATE_ENTERED' => 'Date Created',
    'LBL_LIST_EMAIL_ADDRESS' => 'Email',
    'LBL_LIST_FIRST_NAME' => 'First Name',
    'LBL_VIEW_FORM_TITLE' => 'Lead View',    
    'LBL_LIST_FORM_TITLE' => 'Lead List',
    'LBL_LIST_LAST_NAME' => 'Last Name',
    'LBL_LIST_LEAD_SOURCE_DESCRIPTION' => 'Lead Source Description',
    'LBL_LIST_LEAD_SOURCE' => 'Lead Source',
    'LBL_LIST_MY_LEADS' => 'My Leads',
    'LBL_LIST_NAME' => 'Name',
    'LBL_LIST_PHONE' => 'Office Phone',
    'LBL_LIST_REFERED_BY' => 'Referred By',
    'LBL_LIST_STATUS' => 'Status',
    'LBL_LIST_TITLE' => 'Title / Designation',
    'LBL_MOBILE_PHONE' => 'Mobile:',
    'LBL_MODULE_NAME' => 'Leads',
    'LBL_MODULE_TITLE' => 'Leads: Home',
    'LBL_NAME' => 'Name:',
    'LBL_NEW_FORM_TITLE' => 'New Lead',
    'LBL_NEW_PORTAL_PASSWORD' => 'New Portal Password:',
    'LBL_OFFICE_PHONE' => 'Office Phone:',
    'LBL_OPP_NAME' => 'Opportunity Name:',
    'LBL_OPPORTUNITY_AMOUNT' => 'Opportunity Amount:',
    'LBL_OPPORTUNITY_ID'=>'Opportunity ID',
    'LBL_OPPORTUNITY_NAME' => 'Opportunity Name:',
    'LBL_OTHER_EMAIL_ADDRESS' => 'Other Email:',
    'LBL_OTHER_PHONE' => 'Other Phone:',
    'LBL_PHONE' => 'Phone:',
    'LBL_PORTAL_ACTIVE' => 'Portal Active:',
    'LBL_PORTAL_APP'=> 'Portal Application',
    'LBL_PORTAL_INFORMATION' => 'Portal Information',
    'LBL_PORTAL_NAME' => 'Portal Name:',
    'LBL_PORTAL_PASSWORD_ISSET' => 'Portal Password Is Set:',
    'LBL_POSTAL_CODE' => 'Postal Code:',
    'LBL_PRIMARY_ADDRESS_CITY' => 'Primary Address City',
    'LBL_PRIMARY_ADDRESS_COUNTRY' => 'Primary Address Country',
    'LBL_PRIMARY_ADDRESS_POSTALCODE' => 'Primary Address Postalcode',
    'LBL_PRIMARY_ADDRESS_STATE' => 'Primary Address State',
    'LBL_PRIMARY_ADDRESS_STREET_2'=>'Primary Address Street 2',
    'LBL_PRIMARY_ADDRESS_STREET_3'=>'Primary Address Street 3',   
    'LBL_PRIMARY_ADDRESS_STREET' => 'Primary Address Street',
    'LBL_PRIMARY_ADDRESS' => 'Primary Address:',
    'LBL_REFERED_BY' => 'Referred By:',
    'LBL_REPORTS_TO_ID'=>'Reports To ID',
    'LBL_REPORTS_TO' => 'Reports To:',
    'LBL_SALUTATION' => 'Salutation',
    'LBL_SEARCH_FORM_TITLE' => 'Lead Search',
    'LBL_SELECT_CHECKED_BUTTON_LABEL' => 'Select Checked Leads',
    'LBL_SELECT_CHECKED_BUTTON_TITLE' => 'Select Checked Leads',
    'LBL_STATE' => 'State:',
    'LBL_STATUS_DESCRIPTION' => 'Status Description:',
    'LBL_STATUS' => 'Status:',
    'LBL_TITLE' => 'Title / Designation:',
    'LNK_IMPORT_VCARD' => 'Create From vCard',
    'LNK_LEAD_LIST' => 'Leads',
    'LNK_NEW_ACCOUNT' => 'Create Account',
    'LNK_NEW_BRAND' => 'Create Brand',
    'LNK_NEW_APPOINTMENT' => 'Create Appointment',
    'LNK_NEW_CONTACT' => 'Create Contact',
    'LNK_NEW_LEAD' => 'Create Lead',
    'LNK_NEW_NOTE' => 'Create Note or Attachment',
    'LNK_NEW_OPPORTUNITY' => 'Create Opportunity',
    'LNK_SELECT_ACCOUNT' => 'Select Account',
    'MSG_DUPLICATE' => 'Similar leads have been found. Please check the box of any leads you would like to associate with the Records that will be created from this conversion. Once you are done, please press next.',
    'NTC_COPY_ALTERNATE_ADDRESS' => 'Copy alternate address to primary address',
    'NTC_COPY_PRIMARY_ADDRESS' => 'Copy primary address to alternate address',
    'NTC_DELETE_CONFIRMATION' => 'Are you sure you want to delete this record?',
    'NTC_OPPORTUNITY_REQUIRES_ACCOUNT' => 'Creating an opportunity requires an account.\n Please either create a new one or select an existing one.',
    'NTC_REMOVE_CONFIRMATION' => 'Are you sure you want to remove this lead from this case?',
    'NTC_REMOVE_DIRECT_REPORT_CONFIRMATION' => 'Are you sure you want to remove this record as a direct report?',
    'LBL_CAMPAIGN_LIST_SUBPANEL_TITLE'=>'Campaigns',
    'LBL_BRAND_LIST_SUBPANEL_TITLE'=>'Products Sold',
    'LBL_TARGET_OF_CAMPAIGNS'=>'Successful Campaign:',
    'LBL_TARGET_BUTTON_LABEL'=>'Targeted',
    'LBL_TARGET_BUTTON_TITLE'=>'Targeted',
    'LBL_TARGET_BUTTON_KEY'=>'T',
    'LBL_CAMPAIGN_ID'=>'Campaign Id',
    'LBL_LIST_ASSIGNED_TO_NAME' => 'Assigned User',
    'LBL_LEAD_TYPE' => 'Lead Type',
    'LBL_USERS_SUBPANEL_TITLE' => 'Users',
    'LBL_USERS'=> 'Users',
    'LBL_USERS_SUBPANEL_TITLE' => 'User',
    'LBL_LOGIN' => 'Login Name:',
    'LBL_EXPERIENCE' => 'Experience:',
    'LBL_LEVEL' => 'Level:',
    'LBL_GENDER' => 'Gender:',   
    'LBL_DND_NAME' => 'Import DND',   
    'LBL_UPLOAD_FILE' => 'Upload File',   
);
?>
