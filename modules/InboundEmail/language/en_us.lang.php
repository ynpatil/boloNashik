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
 * $Id: en_us.lang.php,v 1.43 2006/08/18 20:19:20 chris Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

$mod_strings = array(



	
	'ERR_BAD_LOGIN_PASSWORD'=> 'Login or Password Incorrect',
	'ERR_BODY_TOO_LONG'		=> '\rBody text too long to capture FULL email.  Trimmed.',
	'ERR_INI_ZLIB'			=> 'Could not turn off Zlib compression temporarily.  "Test Settings" may fail.',
	'ERR_MAILBOX_FAIL'		=> 'Could not retreive any mailboxes.',
	'ERR_NO_IMAP'			=> 'No IMAP libraries found.  Please resolve this before continuing with InboundEmail',
	'ERR_NO_OPTS_SAVED'		=> 'No Optimums were saved with your InboundEmail mailbox.  Please review the settings',
	'ERR_TEST_MAILBOX'		=> 'Please check your settings and try again.',
	
	'LBL_APPLY_OPTIMUMS'	=> 'Apply Optimums',
	'LBL_ASSIGN_TO_USER'	=> 'Assign To User',
	'LBL_AUTOREPLY_OPTIONS'	=> 'Auto-Reply Options',
	'LBL_AUTOREPLY'			=> 'Auto-Reply Template',
	'LBL_BASIC'				=> 'Basic Setup',
	'LBL_CASE_MACRO'		=> 'Case Macro',
	'LBL_CASE_MACRO_DESC'	=> 'Set the macro which will be parsed and used to link imported email to a Case.',
	'LBL_CASE_MACRO_DESC2'	=> 'Set this to any value, but preserve the <b>"%1"</b>.',
	'LBL_CERT_DESC'			=> 'Force validation of the mail server\'s Security Certificate - do not use if self-signing.',
	'LBL_CERT'				=> 'Validate Certificate',
	'LBL_CLOSE_POPUP'		=> 'Close Window',
	'LBL_CREATE_NEW_GROUP'	=> '--Create Mailbox Group On Save--',
	'LBL_CREATE_TEMPLATE'	=> 'Create',
	'LBL_DEFAULT_FROM_ADDR'	=> 'Default: ',
	'LBL_DEFAULT_FROM_NAME'	=> 'Default: ',
	'LBL_EDIT_TEMPLATE'		=> 'Edit',
	'LBL_EMAIL_OPTIONS'		=> 'Email Handling Options',
	'LBL_FILTER_DOMAIN_DESC'=> 'Do not send Auto-replies to this domain.',
	'LBL_FILTER_DOMAIN'		=> 'No Auto-reply to Domain',
	'LBL_FIND_OPTIMUM_KEY'	=> 'f',
	'LBL_FIND_OPTIMUM_MSG'	=> '<br>Finding optimum connection variables.',
	'LBL_FIND_OPTIMUM_TITLE'=> 'Find Optimum Configuration',
	'LBL_FIND_SSL_WARN'		=> '<br>Testing SSL may take a long time.  Please be patient.<br>',
	'LBL_FORCE_DESC'		=> 'Some IMAP/POP3 servers require special switches. Check to force a negative switch when connecting (i.e., /notls)',
	'LBL_FORCE'				=> 'Force Negative',
	'LBL_FOUND_MAILBOXES'	=> 'Found the following usable folders.<br>Click one to choose it:',
	'LBL_FOUND_OPTIMUM_MSG'	=> '<br>Found optimum settings.  Press the button below to apply them to your Mailbox.',
	'LBL_FROM_ADDR'			=> '"From" Address',
	'LBL_FROM_NAME_ADDR'	=> 'Reply Name/Email',
	'LBL_FROM_NAME'			=> '"From" Name',
	'LBL_GROUP_QUEUE'		=> 'Assign To Group',
    'LBL_HOME'              => 'Home',
	'LBL_LIST_MAILBOX_TYPE'	=> 'Mailbox Usage',
	'LBL_LIST_NAME'			=> 'Name:',
	'LBL_LIST_SERVER_URL'	=> 'Mail Server:',
	'LBL_LIST_STATUS'		=> 'Status:',
	'LBL_LOGIN'				=> 'User Name',
	'LBL_MAILBOX_DEFAULT'	=> 'INBOX',
	'LBL_MAILBOX_SSL_DESC'	=> 'Use SSL when connecting. If this does not work, check that your PHP installation included "--with-imap-ssl" in the configuration.',
	'LBL_MAILBOX_SSL'		=> 'Use SSL',
	'LBL_MAILBOX_TYPE'		=> 'Possible Actions',
	'LBL_MAILBOX'			=> 'Monitored Folder',
	'LBL_MARK_READ_DESC'	=> 'Mark messages read on mail server on import; do not delete.',
	'LBL_MARK_READ_NO'		=> 'Email marked deleted after import',
	'LBL_MARK_READ_YES'		=> 'Email left on server after import',
	'LBL_MARK_READ'			=> 'Leave Messages On Server',
	'LBL_MODULE_NAME'		=> 'Inbound Email Setup',
	'LBL_MODULE_TITLE'		=> 'Inbound Email',
	'LBL_NAME'				=> 'Name',
	'LBL_NO_OPTIMUMS'		=> 'Could not find optimums.  Please check your settings and try again.',
	'LBL_ONLY_SINCE_DESC'	=> 'When using POP3, PHP cannot filter for New/Unread messages.  This flag allows the request to check for messages SINCE the last time the mailbox was polled.  This will significantly improve performance if your mail server cannot support IMAP.', 
	'LBL_ONLY_SINCE_NO'		=> 'No. Check against all emails on mail server.',
	'LBL_ONLY_SINCE_YES'	=> 'Yes.',
	'LBL_ONLY_SINCE'		=> 'Import Only Since Last Check:',
	'LBL_PASSWORD_CHECK'	=> 'Password Check',
	'LBL_PASSWORD'			=> 'Password',
	'LBL_POP3_SUCCESS'		=> 'Your POP3 test connection was successful.',
	'LBL_POPUP_FAILURE'		=> 'Test connection failed. The error is shown below.',
	'LBL_POPUP_SUCCESS'		=> 'Test connection successful.  Your settings are working.',
	'LBL_POPUP_TITLE'		=> 'Test Settings',
	'LBL_PORT'				=> 'Mail Server Port',
	'LBL_QUEUE'				=> 'Mailbox Queue',
	'LBL_SERVER_OPTIONS'	=> 'Advanced Setup',
	'LBL_SERVER_TYPE'		=> 'Mail Server Protocol',
	'LBL_SERVER_URL'		=> 'Mail Server Address',
	'LBL_SSL_DESC'			=> 'If your mail server supports secure socket connections, enabling this will force SSL connections when importing email.',
	'LBL_SSL'				=> 'Use SSL',
	'LBL_STATUS'			=> 'Status',
	'LBL_SYSTEM_DEFAULT'	=> 'System Default',
	'LBL_TEST_BUTTON_KEY'	=> 't',
	'LBL_TEST_BUTTON_TITLE'	=> 'Test [Alt+T]',
	'LBL_TEST_SETTINGS'		=> 'Test Settings',
	'LBL_TEST_SUCCESSFUL'	=> 'Connection completed successfully.',
	'LBL_TEST_WAIT_MESSAGE'	=> 'One moment please...',
	'LBL_TLS_DESC'			=> 'Use Transport Layer Security when connecting to the mail server - only use this if your mail server supports this protocol.',
	'LBL_TLS'				=> 'Use TLS',
	'LBL_WARN_IMAP_TITLE'	=> 'Inbound Email Disabled',
	'LBL_WARN_IMAP'			=> 'Warnings:',
	'LBL_WARN_NO_IMAP'		=> 'Inbound Email <b>cannot</b> function without the IMAP c-client libraries enabled/compiled with the PHP module.  Please contact your administrator to resolve this issue.',
	
	'LNK_CREATE_GROUP'		=> 'Create New Group',
	'LNK_LIST_CREATE_NEW'	=> 'Monitor New Mailbox',
	'LNK_LIST_MAILBOXES'	=> 'All Mailboxes',
	'LNK_LIST_QUEUES'		=> 'All Queues',
	'LNK_LIST_QUEUES'		=> 'All Queues',
	'LNK_LIST_SCHEDULER'	=> 'Schedulers',
	'LNK_LIST_TEST_IMPORT'	=> 'Test Email Import',
	'LNK_NEW_QUEUES'		=> 'Create New Queue',
	'LNK_NEW_QUEUES'		=> 'Create New Queue',
	'LNK_SEED_QUEUES'		=> 'Seed Queues From Teams',
);

?>
