<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');
/* * *******************************************************************************
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
 * ****************************************************************************** */
/* * *******************************************************************************
 * $Id: en_us.lang.php,v 1.53 2006/06/16 01:03:14 wayne Exp $
 * Description:  Defines the English language pack for the base application.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 * ****************************************************************************** */

$mod_strings = array(
    'LBL_MODULE_NAME' => 'Calls',
    'LBL_MODULE_TITLE' => 'Calls: Home',
    'LBL_SEARCH_FORM_TITLE' => 'Call Search',
    'LBL_LIST_FORM_TITLE' => 'Call List',
    'LBL_NEW_FORM_TITLE' => 'Schedule Call',
    'LBL_LIST_CLOSE' => 'Close',
    'LBL_LIST_SUBJECT' => 'Subject',
    'LBL_LIST_CONTACT' => 'Contact',
    'LBL_LIST_RELATED_TO' => 'Related to',
    'LBL_LIST_DATE' => 'Start Date',
    'LBL_LIST_TIME' => 'Start Time',
    'LBL_LIST_DURATION' => 'Duration',
    'LBL_LIST_DIRECTION' => 'Direction',
    'LBL_SUBJECT' => 'Subject:',
    'LBL_REMINDER' => 'Reminder:',
    'LBL_CONTACT_NAME' => 'Contact:',
    'LBL_DESCRIPTION_INFORMATION' => 'Description Information',
    'LBL_DESCRIPTION' => 'Description:',
    'LBL_STATUS' => 'Status:',
    'LBL_DIRECTION' => 'Direction:',
    'LBL_DATE' => 'Start Date:',
    'LBL_END_DATE' => 'End Date:',
    'LBL_DURATION' => 'Duration:',
    'LBL_DURATION_HOURS' => 'Duration Hours:',
    'LBL_DURATION_MINUTES' => 'Duration Minutes:',
    'LBL_HOURS_MINUTES' => '(hours/minutes)',
    'LBL_CALL' => 'Call:',
    'LBL_DATE_TIME' => 'Start Date & Time:',
    'LBL_TIME' => 'Start Time:',
    'LBL_END_TIME' => 'End Time:',
    'LBL_HOURS_ABBREV' => 'h',
    'LBL_MINSS_ABBREV' => 'm',
    'LBL_COLON' => ':',
    'LBL_DEFAULT_STATUS' => 'Planned',
    'LNK_NEW_CALL' => 'Schedule Call',
    'LNK_NEW_MEETING' => 'Schedule Meeting / DAR',
    'LNK_NEW_TASK' => 'Create Task',
    'LNK_NEW_REVIEW' => 'Create Review',
    'LNK_NEW_NOTE' => 'Create Note or Attachment',
    'LNK_NEW_EMAIL' => 'Archive Email',
    'LNK_CALL_LIST' => 'Calls',
    'LNK_MEETING_LIST' => 'Meetings',
    'LNK_TASK_LIST' => 'Tasks',
    'LNK_NOTE_LIST' => 'Notes',
    'LNK_EMAIL_LIST' => 'Emails',
    'LNK_VIEW_CALENDAR' => 'Today',
    'ERR_DELETE_RECORD' => 'A record number must be specified to delete the account.',
    'NTC_REMOVE_INVITEE' => 'Are you sure you want to remove this invitee from the call?',
    'LBL_INVITEE' => 'Invitees',
    'LBL_RELATED_TO' => 'Related To:',
    'LNK_NEW_APPOINTMENT' => 'Create Appointment',
    'LBL_SCHEDULING_FORM_TITLE' => 'Scheduling',
    'LBL_ADD_INVITEE' => 'Add Attendee/Contact',
    'LBL_NAME' => 'Name',
    'LBL_FIRST_NAME' => 'First Name',
    'LBL_LAST_NAME' => 'Last Name',
    'LBL_EMAIL' => 'Email',
    'LBL_PHONE' => 'Phone',
    'LBL_REMINDER' => 'Reminder:',
    'LBL_SEND_BUTTON_TITLE' => 'Send Invites [Alt+I]',
    'LBL_SEND_BUTTON_KEY' => 'I',
    'LBL_SEND_BUTTON_LABEL' => 'Send Invites',
    'LBL_DATE_END' => 'Date End',
    'LBL_TIME_END' => 'Time End',
    'LBL_REMINDER_TIME' => 'Reminder Time',
    'LBL_SEARCH_BUTTON' => 'Search',
    'LBL_ADD_BUTTON' => 'Add',
    'LBL_DEFAULT_SUBPANEL_TITLE' => 'Calls',
    'LBL_LOG_CALL' => 'Log Call',
    'LNK_SELECT_ACCOUNT' => 'Select Account',
    'LNK_NEW_ACCOUNT' => 'New Account',
    'LNK_NEW_OPPORTUNITY' => 'New Opportunity',
    'LBL_DEL' => 'Del',
    'LBL_CONTACTS_SUBPANEL_TITLE' => 'Contacts',
    'LBL_USERS_SUBPANEL_TITLE' => 'Users',
    'LBL_OUTLOOK_ID' => 'Outlook ID',
    'LBL_MEMBER_OF' => 'Member Of',
    'LBL_HISTORY_SUBPANEL_TITLE' => 'Notes',
    'LBL_LIST_ASSIGNED_TO_NAME' => 'Assigned User',
    'LBL_LIST_MY_CALLS' => 'My Calls',
    'LBL_LIST_STATUS' => 'Status',
    'LBL_LIST_DATE_MODIFIED' => 'Last Modified',
    'LBL_ACTIVITIES_SUBPANEL_TITLE' => 'Activities',
    'LBL_GROUP_CALLS' => 'Group Calls',
    'LBL_ONLY_ACCOUNT_CONTACTS' => 'Only Account Contacts',
    'LNK_CALL_REQUESTS' => 'Call Requests',
    'LBL_ACTIVITY_FOR_CAMPAIGN' => 'Activity For Campaign:',
    'LBL_REGION' => 'Region:',
    'LBL_CALL_BACK' => 'Call Back Date & Time:',
    'LBL_TOKEN' => 'Token No:',
    'LBL_ASSIGNED_VENDOR' => 'Assigned to Vendor',
    'LBL_BRANDS_SUBPANEL_TITLE' => 'Product',
    'LBL_LIST_CAMPAIGN' => 'Campaign',
    'LBL_TITLE_CAMPAIGN' => 'Campaigns',
    'LBL_LIST_END_DATE' => 'End Date',
    'LBL_LIST_PRODUCT_SOLD' => 'Products Sold',
    'LBL_LIST_PRODUCT_NAME' => 'Product Sold Name',
    'LBL_LIST_PRODUCT_PRICE' => 'Price',
);
?>
