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
 * $Id: en_us.lang.php,v 1.37 2006/09/02 01:25:29 chris Exp $
 * Description:
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc. All Rights
 * Reserved. Contributor(s): ______________________________________..
 * *******************************************************************************/

$mod_strings = array(








	


































	
	'DEFAULT_CHARSET'					=> 'UTF-8',
	'ERR_ADMIN_PASS_BLANK'				=> 'SugarCRM admin password cannot be blank.',
	'ERR_CHECKSYS_CALL_TIME'			=> 'Allow Call Time Pass Reference is Off (please enable in php.ini)',
	'ERR_CHECKSYS_CURL'					=> 'Not found: Sugar Scheduler will run with limited functionality.',
	'ERR_CHECKSYS_IMAP'					=> 'Not found: InboundEmail and Campaigns (Email) require the IMAP libraries. Neither will be functional.',
	'ERR_CHECKSYS_MSSQL_MQGPC'			=> 'Magic Quotes GPC cannot be turned "On" when using MS SQL Server.',
	'ERR_CHECKSYS_MBSTRING'				=> 'Not found: SugarCRM will not be able to process multi-byte characters.  This will impact receiving emails in character sets other than UTF-8.',
	'ERR_CHECKSYS_MEM_LIMIT_1'			=> 'Warning: $memory_limit (Set this to ',
	'ERR_CHECKSYS_MEM_LIMIT_2'			=> 'M or larger in your php.ini file)',
	'ERR_CHECKSYS_MYSQL_VERSION'		=> 'Minimum Version 4.1.2 - Found: ',
	'ERR_CHECKSYS_NO_SESSIONS'			=> 'Failed to write and read session variables.  Unable to proceed with the installation.',
	'ERR_CHECKSYS_NOT_VALID_DIR'		=> 'Not A Valid Directory',
	'ERR_CHECKSYS_NOT_WRITABLE'			=> 'Warning: Not Writable',
	'ERR_CHECKSYS_PHP_INVALID_VER'		=> 'Invalid PHP Version Installed: ( ver',
	'ERR_CHECKSYS_PHP_JSON'				=> 'Not found: The PHP-JSON PHP module reaps enormous performance benefits.',
	'ERR_CHECKSYS_PHP_JSON_VERSION'		=> 'Only PHP-JSON version 1.1.1 is supported in SugarCRM at this time.  Please up/down-grade your version. SugarCRM will use the slower PHP code for AJAX-style interactions.',
	'ERR_CHECKSYS_PHP_UNSUPPORTED'		=> 'Unsupported PHP Version Installed: ( ver',
	'ERR_CHECKSYS_SAFE_MODE'			=> 'Safe Mode is On (please disable in php.ini)',
	'ERR_CHECKSYS_ZLIB'					=> 'Not Found: SugarCRM reaps enormous performance benefits with zlib compression.',
	'ERR_DB_ADMIN'						=> 'Database admin user name and/or password is invalid (Error ',
	'ERR_DB_EXISTS_NOT'					=> 'Database specified does not exist.',
	'ERR_DB_EXISTS_WITH_CONFIG'			=> 'Database already exists with config data.  To run an install with the chosen database, please re-run the install and choose: "Drop and recreate existing SugarCRM tables?"  To upgrade, use the Upgrade Wizard in the Admin Console.  Please read the upgrade documentation located <a href="http://www.sugarforge.org/content/downloads/" target="_new">here</a>.',
	'ERR_DB_EXISTS'						=> 'Database name already exists--cannot create another one with the same name.',
	'ERR_DB_HOSTNAME'					=> 'Host name cannot be blank.',
	'ERR_DB_INVALID'					=> 'Invalid database type selected.',
	'ERR_DB_LOGIN_FAILURE_MYSQL'		=> 'SugarCRM database user name and/or password is invalid (Error ',
	'ERR_DB_LOGIN_FAILURE_MSSQL'		=> 'SugarCRM database user name and/or password is invalid.',
	'ERR_DB_MYSQL_VERSION1'				=> 'MySQL version ',
	'ERR_DB_MYSQL_VERSION2'				=> ' is not supported.  Only MySQL 4.1.x and higher is supported.',
	'ERR_DB_NAME'						=> 'Database name cannot be blank.',
	'ERR_DB_NAME2'						=> "Database name cannot contain a '\\', '/', or '.'",
	'ERR_DB_PASSWORD'					=> 'Passwords for SugarCRM do not match.',
	'ERR_DB_PRIV_USER'					=> 'Database admin user name is required.',
	'ERR_DB_USER_EXISTS'				=> 'User name for SugarCRM already exists--cannot create another one with the same name.',
	'ERR_DB_USER'						=> 'User name for SugarCRM cannot be blank.',
	'ERR_DBCONF_VALIDATION'				=> 'Please fix the following errors before proceeding:',
	'ERR_ERROR_GENERAL'					=> 'The following errors were encountered:',
	'ERR_LANG_CANNOT_DELETE_FILE'		=> 'Cannot delete file: ',
	'ERR_LANG_MISSING_FILE'				=> 'Cannot find file: ',
	'ERR_LANG_NO_LANG_FILE'			 	=> 'No language pack file found at include/language inside: ',
	'ERR_LANG_UPLOAD_1'					=> 'There was a problem with your upload.  Please try again.',
	'ERR_LANG_UPLOAD_2'					=> 'Language Packs must be ZIP archives.',
	'ERR_LANG_UPLOAD_3'					=> 'PHP could not move the temp file to the upgrade directory.',
	'ERR_LICENSE_MISSING'				=> 'Missing Required Fields',
	'ERR_LICENSE_NOT_FOUND'				=> 'License file not found!',
	'ERR_LOG_DIRECTORY_NOT_EXISTS'		=> 'Log directory provided is not a valid directory.',
	'ERR_LOG_DIRECTORY_NOT_WRITABLE'	=> 'Log directory provided is not a writable directory.',
	'ERR_LOG_DIRECTORY_REQUIRED'		=> 'Log directory is required if you wish to specify your own.',
	'ERR_NO_DIRECT_SCRIPT'				=> 'Unable to process script directly.',
	'ERR_NO_SINGLE_QUOTE'				=> 'Cannot use the single quotation mark for ',
	'ERR_PASSWORD_MISMATCH'				=> 'Passwords for SugarCRM admin do not match.',
	'ERR_PERFORM_CONFIG_PHP_1'			=> 'Cannot write to the <span class=stop>config.php</span> file.',
	'ERR_PERFORM_CONFIG_PHP_2'			=> 'You can continue this installation by manually creating the config.php file and pasting the configuration information below into the config.php file.  However, you <strong>must </strong>create the config.php file before you continue to the next step.',
	'ERR_PERFORM_CONFIG_PHP_3'			=> 'Did you remember to create the config.php file?',
	'ERR_PERFORM_CONFIG_PHP_4'			=> 'Warning: Could not write to config.php file.  Please ensure it exists.',
	'ERR_PERFORM_HTACCESS_1'			=> 'Cannot write to the ',
	'ERR_PERFORM_HTACCESS_2'			=> ' file.',
	'ERR_PERFORM_HTACCESS_3'			=> 'If you want to secure your log file from being accessible via browser, create an .htaccess file in your log directory with the line:',
	'ERR_PERFORM_NO_TCPIP'				=> '<b>We could not detect an internet connection.</b> When you do have a connection, please visit <a href=\"http://www.sugarcrm.com/home/index.php?option=com_extended_registration&task=register\">http://www.sugarcrm.com/home/index.php?option=com_extended_registration&task=register</a> to register with SugarCRM. By letting us know a little bit about how your company plans to use SugarCRM, we can ensure we are always delivering the right application for your business needs.',
	'ERR_SESSION_DIRECTORY_NOT_EXISTS'	=> 'Session directory provided is not a valid directory.',
	'ERR_SESSION_DIRECTORY'				=> 'Session directory provided is not a writable directory.',
	'ERR_SESSION_PATH'					=> 'Session path is required if you wish to specify your own.',
	'ERR_SI_NO_CONFIG'					=> 'You did not include config_si.php in the document root, or you did not define $sugar_config_si in config.php',
	'ERR_SITE_GUID'						=> 'Application ID is required if you wish to specify your own.',
	'ERR_UPLOAD_MAX_FILESIZE'			=> 'Warning: Your PHP configuration must be changed to allow files of at least 6MB to be uploaded.',
	'ERR_URL_BLANK'						=> 'URL cannot be blank.',
	'ERR_UW_NO_UPDATE_RECORD'			=> 'Could not locate installation record of',
	'ERROR_FLAVOR_INCOMPATIBLE'			=> 'The uploaded file is not compatible with this flavor (Open Source, Professional, or Enterprise) of Sugar Suite: ',
	'ERROR_LICENSE_EXPIRED'				=> "Error: Your license expired ",
	'ERROR_LICENSE_EXPIRED2'			=> " day(s) ago.   Please go to the <a href='index.php?action=LicenseSettings&module=Administration'>'\"License Management\"</a>  in the Admin screen to enter your new license key.  If you do not enter a new license key within 30 days of your license key expiration, you will no longer be able to log into this application.",
	'ERROR_MANIFEST_TYPE'				=> 'Manifest file must specify the package type.',
	'ERROR_PACKAGE_TYPE'				=> 'Manifest file specifies an unrecognized package type',
	'ERROR_VALIDATION_EXPIRED'			=> "Error: Your validation key expired ",
	'ERROR_VALIDATION_EXPIRED2'			=> " day(s) ago.   Please go to the <a href='index.php?action=LicenseSettings&module=Administration'>'\"License Management\"</a> in the Admin screen to enter your new validation key.  If you do not enter a new validation key within 30 days of your validation key expiration, you will no longer be able to log into this application.",
	'ERROR_VERSION_INCOMPATIBLE'		=> 'The uploaded file is not compatible with this version of Sugar Suite: ',
	
	'LBL_BACK'							=> 'Back',
	'LBL_CHECKSYS_1'					=> 'In order for your SugarCRM installation to function properly, please ensure all of the system check items listed below are green. If any are red, please take the necessary steps to fix them.',
	'LBL_CHECKSYS_CACHE'				=> 'Writable Cache Sub-Directories',
	'LBL_CHECKSYS_CALL_TIME'			=> 'PHP Allow Call Time Pass Reference Turned On',
	'LBL_CHECKSYS_COMPONENT'			=> 'Component',
	'LBL_CHECKSYS_COMPONENT_OPTIONAL'	=> 'Optional Components',
	'LBL_CHECKSYS_CONFIG'				=> 'Writable SugarCRM Configuration File (config.php)',
	'LBL_CHECKSYS_CURL'					=> 'cURL Module',
	'LBL_CHECKSYS_CUSTOM'				=> 'Writable Custom Directory',
	'LBL_CHECKSYS_DATA'					=> 'Writable Data Sub-Directories',
	'LBL_CHECKSYS_IMAP'					=> 'IMAP Module',
	'LBL_CHECKSYS_MQGPC'				=> 'Magic Quotes GPC',
	'LBL_CHECKSYS_MBSTRING'				=> 'MB Strings Module',
	'LBL_CHECKSYS_MEM_OK'				=> 'OK (No Limit)',
	'LBL_CHECKSYS_MEM_UNLIMITED'		=> 'OK (Unlimited)',
	'LBL_CHECKSYS_MEM'					=> 'PHP Memory Limit >= ',
	'LBL_CHECKSYS_MODULE'				=> 'Writable Modules Sub-Directories and Files',
	'LBL_CHECKSYS_MYSQL_VERSION'		=> 'MySQL Version',
	'LBL_CHECKSYS_NOT_AVAILABLE'		=> 'Not Available',
	'LBL_CHECKSYS_OK'					=> 'OK',
	'LBL_CHECKSYS_PHP_INI'				=> '<b>Note:</b> Your php configuration file (php.ini) is located at:',
	'LBL_CHECKSYS_PHP_JSON'				=> 'PHP-JSON Module', 
	'LBL_CHECKSYS_PHP_OK'				=> 'OK (ver ',
	'LBL_CHECKSYS_PHPVER'				=> 'PHP Version',
	'LBL_CHECKSYS_RECHECK'				=> 'Re-check',
	'LBL_CHECKSYS_SAFE_MODE'			=> 'PHP Safe Mode Turned Off',
	'LBL_CHECKSYS_SESSION'				=> 'Writable Session Save Path (',
	'LBL_CHECKSYS_STATUS'				=> 'Status',
	'LBL_CHECKSYS_TITLE'				=> 'System Check Acceptance',
	'LBL_CHECKSYS_VER'					=> 'Found: ( ver ',
	'LBL_CHECKSYS_XML'					=> 'XML Parsing',
	'LBL_CHECKSYS_ZLIB'					=> 'ZLIB Compression Module',
	'LBL_CLOSE'							=> 'Close',
	'LBL_CONFIRM_BE_CREATED'			=> 'be created',
	'LBL_CONFIRM_DB_TYPE'				=> 'Database Type',
	'LBL_CONFIRM_DIRECTIONS'			=> 'Please confirm the settings below.  If you would like to change any of the values, click "Back" to edit.  Otherwise, click "Next" to start the installation.',
	'LBL_CONFIRM_LICENSE_TITLE'			=> 'License Information',
	'LBL_CONFIRM_NOT'					=> 'not',
	'LBL_CONFIRM_TITLE'					=> 'Confirm Settings',
	'LBL_CONFIRM_WILL'					=> 'will',
	'LBL_DBCONF_CREATE_DB'				=> 'Create Database',
	'LBL_DBCONF_CREATE_USER'			=> 'Create User',
	'LBL_DBCONF_DB_DROP_CREATE_WARN'	=> 'Caution: All Sugar data will be erased<br>if this box is checked.',
	'LBL_DBCONF_DB_DROP_CREATE'			=> 'Drop and Recreate Existing Sugar tables?',
	'LBL_DBCONF_DB_NAME'				=> 'Database Name',
	'LBL_DBCONF_DB_PASSWORD'			=> 'Database Password',
	'LBL_DBCONF_DB_PASSWORD2'			=> 'Re-enter Database Password',
	'LBL_DBCONF_DB_USER'				=> 'Database Username',
	'LBL_DBCONF_DEMO_DATA'				=> 'Populate Database with Demo Data?',
	'LBL_DBCONF_HOST_NAME'				=> 'Host Name / Host Instance',
	'LBL_DBCONF_INSTRUCTIONS'			=> 'Please enter your database configuration information below. If you are unsure of what to fill in, we suggest that you use the default values.',
	'LBL_DBCONF_MB_DEMO_DATA'			=> 'Use multi-byte text in demo data?',
	'LBL_DBCONF_PRIV_PASS'				=> 'Privileged Database User Password',
	'LBL_DBCONF_PRIV_USER_2'			=> 'Database Account Above Is a Privileged User?',
	'LBL_DBCONF_PRIV_USER_DIRECTIONS'	=> 'This privileged database user must have the proper permissions tocreate a database, drop/create tables, and create a user.  This privileged database user will only be used to perform these tasks as needed during the installation process.  You may also use the same database user as above if that user has sufficient privileges.',
	'LBL_DBCONF_PRIV_USER'				=> 'Privileged Database User Name',
	'LBL_DBCONF_TITLE'					=> 'Database Configuration',
	'LBL_DISABLED_DESCRIPTION_2'		=> 'After this change has been made, you may click the "Start" button below to begin your installation.  <i>After the installation is complete, you will want to change the value for \'installer_locked\' to \'true\'.</i>',
	'LBL_DISABLED_DESCRIPTION'			=> 'The installer has already been run once.  As a safety measure, it has been disabled from running a second time.  If you are absolutely sure you want to run it again, please go to your config.php file and locate (or add) a variable called \'installer_locked\' and set it to \'false\'.  The line should look like this:',
	'LBL_DISABLED_HELP_1'				=> 'For installation help, please visit the SugarCRM',
	'LBL_DISABLED_HELP_2'				=> 'support forums',
	'LBL_DISABLED_TITLE_2'				=> 'SugarCRM Installation has been Disabled',
	'LBL_DISABLED_TITLE'				=> 'SugarCRM Installation Disabled',
	'LBL_EMAIL_CHARSET_DESC'			=> 'Set this to the character set most commonly used in your locale',
	'LBL_EMAIL_CHARSET_TITLE'			=> 'Outbound Email Character Set',
	'LBL_HELP'							=> 'Help',
	'LBL_LANG_1'						=> 'If you would like to install a language pack other than the default of US-English, please do so below.  Otherwise, click "Next" to continue to the next step.',
	'LBL_LANG_BUTTON_COMMIT'			=> 'Install',
	'LBL_LANG_BUTTON_REMOVE'			=> 'Remove',
	'LBL_LANG_BUTTON_UNINSTALL'			=> 'Uninstall',
	'LBL_LANG_BUTTON_UPLOAD'			=> 'Upload',
	'LBL_LANG_NO_PACKS'					=> 'none',
	'LBL_LANG_PACK_INSTALLED'			=> 'The following language packs have been installed: ',
	'LBL_LANG_PACK_READY'				=> 'The following language packs are ready to be installed: ',
	'LBL_LANG_SUCCESS'					=> 'The language pack was successfully uploaded.',
	'LBL_LANG_TITLE'			   		=> 'Language Pack',
	'LBL_LANG_UPLOAD'					=> 'Upload a Language Pack',
	'LBL_LICENSE_ACCEPTANCE'			=> 'License Acceptance',
	'LBL_LICENSE_DIRECTIONS'			=> 'If you have your license information, please enter it in the fields below.',
	'LBL_LICENSE_DOWNLOAD_KEY'			=> 'Download Key',
	'LBL_LICENSE_EXPIRY'				=> 'Expiration Date',
	'LBL_LICENSE_I_ACCEPT'				=> 'I Accept',
	'LBL_LICENSE_NUM_USERS'				=> 'Number of Users',
	'LBL_LICENSE_OC_DIRECTIONS'			=> 'Please enter the number of purchased offline clients.',
	'LBL_LICENSE_OC_NUM'				=> 'Number of Offline Client Licenses',
	'LBL_LICENSE_OC'					=> 'Offline Client Licenses',
	'LBL_LICENSE_PRINTABLE'				=> ' Printable View ',
	'LBL_LICENSE_TITLE_2'				=> 'SugarCRM License',
	'LBL_LICENSE_TITLE'					=> 'License Information',
	'LBL_LICENSE_USERS'					=> 'Licensed Users',
	
	'LBL_LOCALE_CURRENCY'				=> 'Currency Settings',
	'LBL_LOCALE_CURR_DEFAULT'			=> 'Default Currency',
	'LBL_LOCALE_CURR_SYMBOL'			=> 'Currency Symbol',
	'LBL_LOCALE_CURR_ISO'				=> 'Currency Code (ISO 4217)',
	'LBL_LOCALE_CURR_1000S'				=> '1000s Separator',
	'LBL_LOCALE_CURR_DECIMAL'			=> 'Decimal Separator',
	'LBL_LOCALE_CURR_EXAMPLE'			=> 'Example',
	'LBL_LOCALE_CURR_SIG_DIGITS'		=> 'Significant Digits',
	'LBL_LOCALE_DATEF'					=> 'Default Date Format',
	'LBL_LOCALE_DESC'					=> 'Adjust your SugarCRM Locale settings below.',
	'LBL_LOCALE_EXPORT'					=> 'Import/Export Character Set <i>(Email, .csv, vCard, PDF, data import)</i>',
	'LBL_LOCALE_EXPORT_DELIMITER'		=> 'Export (.csv) Delimiter',
	'LBL_LOCALE_EXPORT_TITLE'			=> 'Export Settings',
	'LBL_LOCALE_LANG'					=> 'Default Language',
	'LBL_LOCALE_NAMEF'					=> 'Default Name Format',
	'LBL_LOCALE_NAMEF_DESC'				=> '"s" Salutation<br />"f" First Name<br />"l" Last Name',
	'LBL_LOCALE_NAME_FIRST'				=> 'David',
	'LBL_LOCALE_NAME_LAST'				=> 'Livingstone',
	'LBL_LOCALE_NAME_SALUTATION'		=> 'Dr.',
	'LBL_LOCALE_TIMEF'					=> 'Default Time Format',
	'LBL_LOCALE_TITLE'					=> 'Locale Settings',
	'LBL_LOCALE_UI'						=> 'User Interface',
	
	'LBL_ML_ACTION'						=> 'Action',
	'LBL_ML_DESCRIPTION'				=> 'Description',
	'LBL_ML_INSTALLED'					=> 'Date Installed',
	'LBL_ML_NAME'						=> 'Name',
	'LBL_ML_PUBLISHED'					=> 'Date Published',
	'LBL_ML_TYPE'						=> 'Type',
	'LBL_ML_UNINSTALLABLE'				=> 'Uninstallable',
	'LBL_ML_VERSION'					=> 'Version',
	'LBL_MSSQL'							=> 'SQL Server',
	'LBL_MYSQL'							=> 'MySQL',
	'LBL_NEXT'							=> 'Next',
	'LBL_NO'							=> 'No',
	'LBL_ORACLE'						=> 'Oracle',
	'LBL_PERFORM_ADMIN_PASSWORD'		=> 'Setting site admin password',
	'LBL_PERFORM_AUDIT_TABLE'			=> 'audit table / ',
	'LBL_PERFORM_CONFIG_PHP'			=> 'Creating Sugar configuration file',
	'LBL_PERFORM_CREATE_DB_1'			=> 'Creating the database ',
	'LBL_PERFORM_CREATE_DB_2'			=> ' on ',
	'LBL_PERFORM_CREATE_DB_USER'		=> 'Creating the Database username and password...',
	'LBL_PERFORM_CREATE_DEFAULT'		=> 'Creating default Sugar data',
	'LBL_PERFORM_CREATE_LOCALHOST'		=> 'Creating the Database username and password for localhost...',
	'LBL_PERFORM_CREATE_RELATIONSHIPS'	=> 'Creating Sugar relationship tables',
	'LBL_PERFORM_CREATING'				=> 'creating / ',
	'LBL_PERFORM_DEFAULT_REPORTS'		=> 'Creating default reports',
	'LBL_PERFORM_DEFAULT_SCHEDULER'		=> 'Creating default scheduler jobs',
	'LBL_PERFORM_DEFAULT_SETTINGS'		=> 'Inserting default settings',
	'LBL_PERFORM_DEFAULT_USERS'			=> 'Creating default users',
	'LBL_PERFORM_DEMO_DATA'				=> 'Populating the database tables with demo data (this may take a little while)...',
	'LBL_PERFORM_DONE'					=> 'done<br>',
	'LBL_PERFORM_DROPPING'				=> 'dropping / ',
	'LBL_PERFORM_FINISH'				=> 'Finish',
	'LBL_PERFORM_LICENSE_SETTINGS'		=> 'Updating license information',
	'LBL_PERFORM_OUTRO_1'				=> 'The setup of Sugar ',
	'LBL_PERFORM_OUTRO_2'				=> ' is now complete.',
	'LBL_PERFORM_OUTRO_3'				=> 'Total time: ',
	'LBL_PERFORM_OUTRO_4'				=> ' seconds.',
	'LBL_PERFORM_OUTRO_5'				=> 'Approximate memory used: ',
	'LBL_PERFORM_OUTRO_6'				=> ' bytes.',
	'LBL_PERFORM_OUTRO_7'				=> 'Your system is now installed and configured for use.',
	'LBL_PERFORM_REL_META'				=> 'relationship meta ... ',
	'LBL_PERFORM_SUCCESS'				=> 'Success!',
	'LBL_PERFORM_TABLES'				=> 'Creating Sugar application tables, audit tables, and relationship metadata...',
	'LBL_PERFORM_TITLE'					=> 'Perform Setup',
	'LBL_PRINT'							=> 'Print',
	'LBL_REG_CONF_1'					=> 'Please take a moment to register with SugarCRM. By letting us know a little bit about how your company plans to use SugarCRM, we can ensure we are always delivering the right product for your business needs.',
	'LBL_REG_CONF_2'					=> 'Your name and email address are the only required fields for registration. All other fields are optional, but very helpful. We do not sell, rent, share, or otherwise distribute the information collected here to third parties.',
	'LBL_REG_CONF_3'					=> 'Thank you for registering. Click on the Finish button to login to SugarCRM. You will need to log in for the first time using the username "admin" and the password you entered in step 2.',
	'LBL_REG_TITLE'						=> 'Registration',
	'LBL_REQUIRED'						=> '* Required field',
	'LBL_SITECFG_ADMIN_PASS_2'			=> 'Re-enter Sugar <em>Admin</em> Password',
	'LBL_SITECFG_ADMIN_PASS_WARN'		=> 'Caution: This will override the admin password of any previous installation.',
	'LBL_SITECFG_ADMIN_PASS'			=> 'Sugar <em>Admin</em> Password',
	'LBL_SITECFG_APP_ID'				=> 'Application ID',
	'LBL_SITECFG_CUSTOM_ID_DIRECTIONS'	=> 'Override the auto-generated application ID that prevents sessions from one instance of Sugar from being used on another instance.  If you have a cluster of Sugar installations, they all must share the same application ID.',
	'LBL_SITECFG_CUSTOM_ID'				=> 'Provide Your Own Application ID',
	'LBL_SITECFG_CUSTOM_LOG_DIRECTIONS'	=> 'Override the default directory where the Sugar log resides.  No matter where the log file resides, access to it via browser will be restricted via an .htaccess redirect.',
	'LBL_SITECFG_CUSTOM_LOG'			=> 'Use a Custom Log Directory',
	'LBL_SITECFG_CUSTOM_SESSION_DIRECTIONS'	=> 'Provide a secure folder for storing Sugar session information to prevent session data from being vulnerable on shared servers.',
	'LBL_SITECFG_CUSTOM_SESSION'		=> 'Use a Custom Session Directory for Sugar',
	'LBL_SITECFG_DIRECTIONS'			=> 'Please enter your site configuration information below. If you are unsure of the fields, we suggest that you use the default values.',
	'LBL_SITECFG_FIX_ERRORS'			=> 'Please fix the following errors before proceeding:',
	'LBL_SITECFG_LOG_DIR'				=> 'Log Directory',
	'LBL_SITECFG_SESSION_PATH'			=> 'Path to Session Directory<br>(must be writable)',
	'LBL_SITECFG_SITE_SECURITY'			=> 'Advanced Site Security',
	'LBL_SITECFG_SUGAR_UP_DIRECTIONS'	=> 'If checked, the system will periodically check to see if updated versions of the application are available.',
	'LBL_SITECFG_SUGAR_UP'				=> 'Automatically Check For Updates?',
	'LBL_SITECFG_SUGAR_UPDATES'			=> 'Sugar Updates Config',
	'LBL_SITECFG_TITLE'					=> 'Site Configuration',
	'LBL_SITECFG_URL'					=> 'URL of Sugar Instance',
	'LBL_SITECFG_USE_DEFAULTS'			=> 'Use Defaults?',
	'LBL_SITECFG_ANONSTATS'             => 'Send Anonymous Usage Statistics?',
	'LBL_SITECFG_ANONSTATS_DIRECTIONS'        => 'If checked, Sugar will send anonymous statistics about your installation to SugarCRM Inc. every time your system checks for new versions. This information will help us better understand how the application is used and guide improvements to the product.',
	'LBL_START'							=> 'Start',
	'LBL_STEP'							=> 'Step',
	'LBL_TITLE_WELCOME'					=> 'Welcome to the SugarCRM ',
	'LBL_WELCOME_1'						=> 'This installer creates the SugarCRM database tables and sets the configuration variables that you need to start. The entire process should take about ten minutes.',
	'LBL_WELCOME_2'						=> 'For installation help, please visit the SugarCRM <a href="http://www.sugarcrm.com/forums/" target="_blank">support forums</a>.',
	'LBL_WELCOME_CHOOSE_LANGUAGE'		=> 'Choose your language',
	'LBL_WELCOME_SETUP_WIZARD'			=> 'Setup Wizard',
	'LBL_WELCOME_TITLE_WELCOME'			=> 'Welcome to the SugarCRM ',
	'LBL_WELCOME_TITLE'					=> 'SugarCRM Setup Wizard',
	'LBL_WIZARD_TITLE'					=> 'SugarCRM Setup Wizard: Step ',
	'LBL_YES'							=> 'Yes',
	// OOTB Scheduler Job Names:
	'LBL_OOTB_WORKFLOW'		=> 'Process Workflow Tasks',
	'LBL_OOTB_REPORTS'		=> 'Run Report Generation Scheduled Tasks',
	'LBL_OOTB_IE'			=> 'Check Inbound Mailboxes',
	'LBL_OOTB_BOUNCE'		=> 'Run Nightly Process Bounced Campaign Emails',
	'LBL_OOTB_CAMPAIGN'		=> 'Run Nightly Mass Email Campaigns',
	'LBL_OOTB_PRUNE'		=> 'Prune Database on 1st of Month',
);

?>
