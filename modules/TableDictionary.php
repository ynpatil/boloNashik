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
//om
// $Id: TableDictionary.php,v 1.53 2006/07/30 05:09:58 awu Exp $

include_once("metadata/accounts_brandsMetaData.php");
include_once("metadata/sap_accountsMetaData.php");
include_once("metadata/accounts_bugsMetaData.php");
include_once("metadata/accounts_casesMetaData.php");
include_once("metadata/accounts_contactsMetaData.php");
include_once("metadata/accounts_opportunitiesMetaData.php");
include_once("metadata/brands_contactsMetaData.php");
include_once("metadata/calls_contactsMetaData.php");
include_once("metadata/calls_usersMetaData.php");
include_once("metadata/cases_bugsMetaData.php");
include_once("metadata/configMetaData.php");
include_once("metadata/contacts_brandsMetaData.php");
include_once("metadata/contacts_bugsMetaData.php");
include_once("metadata/contacts_casesMetaData.php");
include_once("metadata/contacts_usersMetaData.php");
include_once("metadata/custom_fieldsMetaData.php");
include_once("metadata/emails_accountsMetaData.php");
include_once("metadata/emails_bugsMetaData.php");
include_once("metadata/emails_casesMetaData.php");
include_once("metadata/emails_contactsMetaData.php");
include_once("metadata/emails_leadsMetaData.php");
include_once("metadata/emails_opportunitiesMetaData.php");
include_once("metadata/emails_project_tasksMetaData.php");
include_once("metadata/emails_projectsMetaData.php");
include_once("metadata/emails_prospectsMetaData.php");
include_once("metadata/emails_tasksMetaData.php");
include_once("metadata/emails_usersMetaData.php");
include_once("metadata/filesMetaData.php");
include_once("metadata/import_mapsMetaData.php");
include_once("metadata/meetings_contactsMetaData.php");
include_once("metadata/meetings_usersMetaData.php");
include_once("metadata/opportunities_contactsMetaData.php");
include_once("metadata/user_feedsMetaData.php");
include_once("metadata/trackerMetaData.php");
include_once("metadata/prospect_list_campaignsMetaData.php");
include_once("metadata/prospect_lists_prospectsMetaData.php");
include_once("metadata/roles_modulesMetaData.php");
include_once("metadata/roles_usersMetaData.php");
include_once("metadata/project_relationMetaData.php");
include_once("metadata/document_relationMetaData.php");


//ACL RELATIONSHIPS
include_once("metadata/acl_roles_actionsMetaData.php");
include_once("metadata/acl_roles_usersMetaData.php");
// INBOUND EMAIL
include_once("metadata/inboundEmail_autoreplyMetaData.php");
include_once("metadata/email_marketing_prospect_listsMetaData.php");
include_once("metadata/users_signaturesMetaData.php");
//linked documents.
include_once("metadata/linked_documentsMetaData.php");


if(file_exists('custom/application/Ext/TableDictionary/tabledictionary.ext.php')){
	include_once('custom/application/Ext/TableDictionary/tabledictionary.ext.php');
}
?>
