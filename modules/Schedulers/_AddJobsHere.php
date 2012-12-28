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
 * Description:
 * Created On: Oct 11, 2005
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): Chris Nojima
 * ****************************************************************************** */

/**
 * Set up an array of Jobs with the appropriate metadata
 * 'jobName' => array (
 * 		'X' => 'name',
 * )
 * 'X' should be an increment of 1
 * 'name' should be the EXACT name of your function
 *
 * Your function should not be passed any parameters
 * Always  return a Boolean. If it does not the Job will not terminate itself
 * after completion, and the webserver will be forced to time-out that Job instance.
 * DO NOT USE sugar_cleanup(); in your function flow or includes.  this will
 * break Schedulers.  That function is called at the foot of cron.php
 */
/**
 * This array provides the Schedulers admin interface with values for its "Job"
 * dropdown menu.
 */
$job_strings = array(
    0 => 'refreshJobs',
    1 => 'pollMonitoredInboxes',
    2 => 'runMassEmailCampaign',
    3 => 'pruneDatabase',
    /* 4 => 'securityAudit()', */
    5 => 'pollMonitoredInboxesForBouncedCampaignEmails',
    6 => 'TestJob',
    7 => 'ConvertOpportunityToDeal',
    8 => 'PushContactFeedback',
    9 => 'ChecknUpdateValidContactMailID',
    10 => 'PopulateLeadsForProspectList',
    11 => 'GenerateVendorDataFile',
    12 => 'ImportLeadCSVFileData',
    13 => 'PopulateLeadsForProspectList',
    14 => 'SendMailToVendors',
    15 => 'SendCallReminderMail',
    16 => 'ImportDND',
    17 => 'ImportProductSold',
    18 => 'ImportLeadCSVFileDataV1',
);
# Set return type should be array (0=>ture/false,1=>message|filepath)
/**
 * Job 0 refreshes all job schedulers at midnight
 * DEPRECATED
 */

function refreshJobs() {
    return true;
}

/**
 * Job 1
 */
function pollMonitoredInboxes() {

    $GLOBALS['log']->info('----->Scheduler fired job of type pollMonitoredInboxes()');
    global $dictionary;
    require_once('modules/InboundEmail/InboundEmail.php');

    $ie = new InboundEmail();
    $r = $ie->db->query('SELECT id, name FROM inbound_email WHERE deleted=0 AND status=\'Active\' AND mailbox_type != \'bounce\'');
    $GLOBALS['log']->debug('Just got Result from get all Inbounds of Inbound Emails');

    while ($a = $ie->db->fetchByAssoc($r)) {
        $GLOBALS['log']->debug('In while loop of Inbound Emails');


        $ieX = new InboundEmail();
        $ieX->retrieve($a['id']);
        $newMsgs = array();

        $GLOBALS['log']->debug('Trying to connect to mailserver for [ ' . $a['name'] . ' ]');
        if ($ieX->connectMailserver() == 'true') {
            $GLOBALS['log']->debug('Connected to mailserver');
        } else {
            $GLOBALS['log']->fatal('Failing job because we could not get an IMAP connection resource.');
            return false;
        }

        $newMsgs = $ieX->getNewMessageIds();
        if (is_array($newMsgs)) {
            $current = 1;
            $total = count($newMsgs);
            foreach ($newMsgs as $k => $msgNo) {
                $ieX->importOneEmail($msgNo);
                $GLOBALS['log']->debug('***** On message [ ' . $current . ' of ' . $total . ' ] *****');
                $current++;
            }
        }

        imap_expunge($ieX->conn);
        imap_close($ieX->conn, CL_EXPUNGE);
    }

    return true;
}

/**
 * Job 2
 */
function runMassEmailCampaign() {
    if (!class_exists('LoggerManager')) {
        require('log4php/LoggerManager.php');
    }
    $GLOBALS['log'] = LoggerManager::getLogger('emailmandelivery');
    $GLOBALS['log']->debug('Called:runMassEmailCampaign');

    if (!class_exists('PearDatabase')) {
        require('include/database/PearDatabase.php');
    }
    require_once('include/utils.php');
    global $beanList;
    global $beanFiles;
    require("config.php");
    require('include/modules.php');
    if (!class_exists('AclController')) {
        require('modules/ACL/ACLController.php');
    }

    require('modules/EmailMan/EmailManDelivery.php');
    return true;
}

/**
 *  Job 3
 */
function pruneDatabase() {
    $GLOBALS['log']->info('----->Scheduler fired job of type pruneDatabase()');
    $backupDir = 'cache/backups';
    $backupFile = 'backup-pruneDatabase-GMT0_' . gmdate('Y_m_d-H_i_s', strtotime('now')) . '.php';

    $db = PearDatabase::getInstance();
    $tables = $db->getTablesArray();

//_ppd($tables);	
    if (!empty($tables)) {
        foreach ($tables as $kTable => $table) {
            // find tables with deleted=1
            $qDel = 'SELECT * FROM ' . $table . ' WHERE deleted = 1';
            $rDel = $db->query($qDel); // OR continue; // continue if no 'deleted' column
            // make a backup INSERT query if we are deleting.
            while ($aDel = $db->fetchByAssoc($rDel)) {
                // build column names
                $rCols = $db->query('SHOW COLUMNS FROM ' . $table);
                $colName = array();

                while ($aCols = $db->fetchByAssoc($rCols)) {
                    $colName[] = $aCols['Field'];
                }

                $query = 'INSERT INTO ' . $table . ' (';
                $values = '';
                foreach ($colName as $kC => $column) {
                    $query .= $column . ', ';
                    $values .= '"' . $aDel[$column] . '", ';
                }

                $query = substr($query, 0, (strlen($query) - 2));
                $values = substr($values, 0, (strlen($values) - 2));
                $query .= ') VALUES (' . str_replace("'", "&#039;", $values) . ');';

                $queryString[] = $query;

                if (empty($colName)) {
                    $GLOBALS['log']->fatal('pruneDatabase() could not get the columns for table (' . $table . ')');
                }
            } // end aDel while()
            // now do the actual delete
            $db->query('DELETE FROM ' . $table . ' WHERE deleted = 1');
        } // foreach() tables
        // now output file with SQL
        if (!function_exists('mkdir_recursive')) {
            require_once('include/dir_inc.php');
        }
        if (!function_exists('write_array_to_file')) {
            require_once('include/utils/file_utils.php');
        }
        if (!file_exists($backupDir) || !file_exists($backupDir . '/' . $backupFile)) {
            // create directory if not existent
            mkdir_recursive($backupDir, false);
        }
        // write cache file

        write_array_to_file('pruneDatabase', $queryString, $backupDir . '/' . $backupFile);
        return true;
    }
    return false;
}

///**
// * Job 4
// */
//function securityAudit() {
//	// do something
//	return true;
//}

/* Job 5
 * 
 */
function pollMonitoredInboxesForBouncedCampaignEmails() {
    $GLOBALS['log']->info('----->Scheduler job of type pollMonitoredInboxesForBouncedCampaignEmails()');
    global $dictionary;
    require_once('modules/InboundEmail/InboundEmail.php');

    $ie = new InboundEmail();
    $r = $ie->db->query('SELECT id FROM inbound_email WHERE deleted=0 AND status=\'Active\' AND mailbox_type=\'bounce\'');

    while ($a = $ie->db->fetchByAssoc($r)) {
        $ieX = new InboundEmail();
        $ieX->retrieve($a['id']);
        $ieX->connectMailserver();

        $newMsgs = $ieX->getNewMessageIds();
        if (is_array($newMsgs)) {
            foreach ($newMsgs as $k => $msgNo) {
                $ieX->importOneEmail($msgNo);
            }
        }
        imap_expunge($ieX->conn);
        imap_close($ieX->conn);
    }

    return true;
}

/**
 * job 6
 */
function TestJob() {
    $GLOBALS['log']->info('Test Job Started');
    $GLOBALS['log']->info('I am in Test Job');
}

/**
 * Job 7
 */
function ConvertOpportunityToDeal() {
    //global $app_list_strings,$current_user;
    include_once 'custom/include/language/en_us.lang.php';
    require_once ('modules/Users/User.php');
    $current_user = new User();

    $GLOBALS['log']->info('ConvertOpportunityToDeal----->Scheduler fired job of type ConvertLeadToDeal()');
    // Get all leads details
    require_once ('modules/Opportunities/OpportunityToDealQueue.php');

    $responseArr = OpportunityToDealQueue::get_list("id", "processed_status=0");
    if ($responseArr) {
        foreach ($responseArr as $OpportunityToDealQueueObjArr) {
            foreach ($OpportunityToDealQueueObjArr as $OpportunityToDealQueueObj) {
                $GLOBALS['log']->info('------>OpportunityToDealQueueObj->opportunity_id :' . $OpportunityToDealQueueObj->opportunity_id);
                $current_user->retrieve($OpportunityToDealQueueObj->created_by);
                $current_user->set_created_by == true;
                // Get Opportunity Details
                $GLOBALS['log']->info('Get Opportunity Details');
                require_once ('modules/Opportunities/Opportunity.php');
                $OpportunityObj = new Opportunity();
                $OpportunityObj->retrieve($OpportunityToDealQueueObj->opportunity_id);
                $OpportunityObj->format_all_fields();
                $OpportunityArr = $OpportunityObj->toArray();

                // Save Deals Details
                require_once ('modules/Deals/Deals.php');
                $DealsObj = new Deals();
                $DealsObj->fromArray($OpportunityArr);
                $DealsObj->id = '';
                $DealsObj->created_by = $OpportunityToDealQueueObj->created_by;
                $DealsObj->assigned_user_id = $OpportunityToDealQueueObj->created_by;
                $DealsObj->modified_user_id = $OpportunityToDealQueueObj->created_by;
                $DealsObj->date_entered = '';
                $DealsObj->opportunity_id = $OpportunityToDealQueueObj->opportunity_id;
                $DealsObj_id = $DealsObj->save();
                $GLOBALS['log']->info('Save Deals Details : Deal Id :' . $DealsObj_id);

                // Get Opportunity Contacts Details
                $OpportunityObj->load_relationship('contacts');
                $ContactIds = $OpportunityObj->contacts->get();
                $GLOBALS['log']->info('Opportunity Contacts Details :' . print_r($ContactIds, true));
                if (count($ContactIds) > 0) {
                    $DealsObj->load_relationship('contacts');
                    foreach ($ContactIds as $contact_id) {
                        $DealsObj->contacts->add($contact_id);
                    }
                }
                // Get Opportunity Documents Details
                $OpportunityObj->load_relationship('documents');
                $DocumentIds = $OpportunityObj->documents->get();
                $GLOBALS['log']->info('Opportunity Document Details :' . print_r($DocumentIds, true));
                if (count($DocumentIds) > 0) {
                    $DealsObj->load_relationship('documents');
                    foreach ($DocumentIds as $DocumentId) {
                        $DealsObj->documents->add($DocumentId);
                    }
                }
                // Get Opportunity Users Details
                $OpportunityObj->load_relationship('users');
                $UserIds = $OpportunityObj->users->get();
                $GLOBALS['log']->info('Opportunity User Details :' . print_r($UserIds, true));
                if (count($UserIds) > 0) {
                    $GLOBALS['log']->info('Opportunity User Details Available');
                    $DealsObj->load_relationship('users');
                    foreach ($UserIds as $UserId) {
                        $DealsObj->users->add($UserId);
                    }
                }

                // Get Opportunity Structure Details
                $GLOBALS['log']->info('Save Deals Details');
                require_once('modules/OpportunitiesStructure/OpportunityStructure.php');
                require_once('modules/OpportunitiesStructure/PostMoneyValuationConfig.php');
                $OpportunityStructureObj = new OpportunityStructure();
                $responseArr = $OpportunityObj->get_linked_beans('opportunity_opportunity_structure', 'OpportunityStructure');
                if ($responseArr) {
                    $OpportunityStructureObj = $responseArr[0];
                    $OpportunityStructureArr = $OpportunityStructureObj->toArray();
                }

                // Save Deals Structure Details
                $GLOBALS['log']->info('INCLUDED DealsStructure');
                require_once('modules/DealsStructure/DealsStructure.php');
                $DealsStructureObj = new DealsStructure();
                #$GLOBALS['log']->info('DealsStructure::fromArray(OpportunityStructureArr)'.print_r($OpportunityStructureArr,true));
                $DealsStructureObj->fromArray($OpportunityStructureArr);
                #$GLOBALS['log']->info('DealsStructure::fromArray(OpportunityStructureArr)'.print_r($DealsStructureObj,true));
                $DealsStructureObj->id = '';
                $DealsStructureObj->created_by = $OpportunityToDealQueueObj->created_by;
                $DealsStructureObj->assigned_user_id = $OpportunityToDealQueueObj->created_by;
                $DealsStructureObj->modified_user_id = $OpportunityToDealQueueObj->created_by;
                $DealsStructureObj->date_entered = '';
                $DealsStructureObj->deal_id = $DealsObj_id;
                $DealsStructureObj_id = $DealsStructureObj->save();
                $GLOBALS['log']->info('Save Deals Details:DealsStructureObj_id=' . $DealsStructureObj_id);


                // Get Exception Details
                $GLOBALS['log']->info('Save Deals Details');
                $GLOBALS['log']->info('INCLUDED Exceptions.php');
                require_once('modules/OpportunitiesStructure/Exceptions.php');
                $OpportunityExceptionObj = new Exceptions();
                require_once('modules/DealsStructure/DealExceptions.php');
                $DealExceptionObj = new DealExceptions();

                $responseArr = $OpportunityStructureObj->get_linked_beans('exceptions', 'Exceptions');
                if (count($responseArr) > 0) {
                    foreach ($responseArr as $OpportunityExceptionObj) {
                        $OpportunityExceptionArr = $OpportunityExceptionObj->toArray();
                        $DealExceptionObj->fromArray($OpportunityExceptionArr);
                        $DealExceptionObj->id = '';
                        $DealExceptionObj->created_by = $OpportunityToDealQueueObj->created_by;
                        $DealExceptionObj->assigned_user_id = $OpportunityToDealQueueObj->created_by;
                        $DealExceptionObj->modified_user_id = $OpportunityToDealQueueObj->created_by;
                        $DealExceptionObj->date_entered = '';
                        $DealExceptionObj->deal_structure_id = $DealsStructureObj_id;
                        $DealExceptionObj_id = $DealExceptionObj->save();
                        $GLOBALS['log']->info('Save Deals Details:DealException_id=' . $DealExceptionObj_id);
                    }
                }



                if ($OpportunityStructureObj->deal_type && $OpportunityStructureObj->deal_structure) {
                    // Get Risk Asset of Deal Type
                    $GLOBALS['log']->info('Get Risk Asset of Deal Type==>' . $OpportunityStructureObj->deal_type);

                    $risk_asset_str = $app_list_strings['deal_risk_asset_mapping'][$OpportunityStructureObj->deal_type . "_" . $OpportunityStructureObj->deal_structure];
                    $risk_asset_types = explode(",", $risk_asset_str);
                    $GLOBALS['log']->info('====>Risk Asset String==>' . $risk_asset_str);
                    $GLOBALS['log']->info('====>Risk Asset Type' . print_r($app_list_strings['deal_risk_asset_mapping'], true));
                    foreach ($risk_asset_types as $risk_asset_key_name) {
                        // Get Opportunity Structure Risk Asset Details
                        $GLOBALS['log']->info('Get Opportunity Structure Risk Asset Details');
                        $GLOBALS['log']->info('Opportunity Structure Risk Asset Key Name:' . $risk_asset_key_name);
                        $risk_asset_bean_name = $RISK_ASSETS[$risk_asset_key_name]['bean_name'];
                        $structure_relationship_name = $RISK_ASSETS[$risk_asset_key_name]['structure_relationship_name'];

                        if ($risk_asset_bean_name && $structure_relationship_name) {
                            $responseObjArr = $OpportunityStructureObj->get_linked_beans($structure_relationship_name, $risk_asset_bean_name);
                            // Save Deals Structure Risk Asset Details
                            $bean_name = 'Deal' . $risk_asset_bean_name;
                            require_once('modules/DealsStructure/' . $bean_name . '.php');
                            $GLOBALS['log']->info('Get Deal Structure Risk Asset Bean Name:' . $bean_name);
                            $DealsRiskAssetObj = new $bean_name();

                            foreach ($responseObjArr as $responseObj) {
                                $OpportunityStructureRiskAssetArr = $responseObj->toArray();
                                $GLOBALS['log']->info('Deal Structure Risk Asset Array:' . print_r($OpportunityStructureRiskAssetArr, true));
                                $DealsRiskAssetObj->fromArray($OpportunityStructureRiskAssetArr);
                                $DealsRiskAssetObj->id = '';
                                $DealsRiskAssetObj->created_by = $OpportunityToDealQueueObj->created_by;
                                $DealsRiskAssetObj->assigned_user_id = $OpportunityToDealQueueObj->created_by;
                                $DealsRiskAssetObj->modified_user_id = $OpportunityToDealQueueObj->created_by;

                                $DealsRiskAssetObj->date_entered = '';
                                $DealsRiskAssetObj->deal_structure_id = $DealsStructureObj_id;
                                $DealsRiskAssetObj_id = $DealsRiskAssetObj->save();
                                $GLOBALS['log']->info('Save Deals Structure Risk Asset Details: DealsRiskAssetObj_id=' . $DealsRiskAssetObj_id);

                                if ($bean_name == 'DealRealEstate') {
                                    require_once('modules/DealsStructure/DealRealEstateAddress.php');
                                    $DealRealEstateAddressObj = new DealRealEstateAddress();
                                    $resultObjArr = $responseObj->get_linked_beans('real_estate_address', 'RealEstateAddress');
                                    foreach ($resultObjArr as $OppRealEstateAddrObj) {
                                        $RealEstateAddrArr = $OppRealEstateAddrObj->toArray();
                                        $DealRealEstateAddressObj->fromArray($RealEstateAddrArr);
                                        $DealRealEstateAddressObj->id = '';
                                        $DealRealEstateAddressObj->created_by = $OpportunityToDealQueueObj->created_by;
                                        $DealRealEstateAddressObj->assigned_user_id = $OpportunityToDealQueueObj->created_by;
                                        $DealRealEstateAddressObj->modified_user_id = $OpportunityToDealQueueObj->created_by;
                                        $DealRealEstateAddressObj->date_entered = '';
                                        $DealRealEstateAddressObj->deal_structure_real_estate_id = $DealsRiskAssetObj_id;
                                        $DealRealEstateAddressObj_id = $DealRealEstateAddressObj->save();
                                        $GLOBALS['log']->info('Save Deals Structure Risk Asset Details: DealRealEstateAddressObj_id=' . $DealRealEstateAddressObj_id);
                                    }
                                }
                            }
                            //Get Risk Asset Record Count
                            $risk_asset_count[$val] = count($responseObjArr);
                        }
                    }

                    $GLOBALS['log']->info('Checking Deal Type ' . $OpportunityStructureObj->deal_type . ' For PMV' . print_r($postmoneyvaluation, true));
                    if (in_array($OpportunityStructureObj->deal_type, $postmoneyvaluation)) {
                        $GLOBALS['log']->info('Get Risk Asset Record Count:' . array_sum($risk_asset_count));
                        if (array_sum($risk_asset_count) > 0) {
                            // Get Opportunity PostMoneyValuation Details
                            $GLOBALS['log']->info('Get Opportunity PostMoneyValuation Details');
                            $responseObjArr = $OpportunityStructureObj->get_linked_beans('post_money_valuation', 'PostMoneyValuation');

                            // Save Deals PostMoneyValuation Details
                            require_once('modules/DealsStructure/DealPostMoneyValuation.php');
                            $DealPostMoneyValuationObj = new DealPostMoneyValuation();
                            if ($responseObjArr) {
                                foreach ($responseObjArr as $responseObj) {
                                    $OpportunityStructurePostMoneyValuationArr = $responseObj->toArray();
                                    $DealPostMoneyValuationObj->fromArray($OpportunityStructurePostMoneyValuationArr);
                                    $DealPostMoneyValuationObj->id = '';
                                    $DealPostMoneyValuationObj->created_by = $OpportunityToDealQueueObj->created_by;
                                    $DealPostMoneyValuationObj->assigned_user_id = $OpportunityToDealQueueObj->created_by;
                                    $DealPostMoneyValuationObj->modified_user_id = $OpportunityToDealQueueObj->created_by;
                                    $DealPostMoneyValuationObj->date_entered = '';
                                    $DealPostMoneyValuationObj->deal_structure_id = $DealsStructureObj_id;
                                    $DealPostMoneyValuationObj_id = $DealPostMoneyValuationObj->save();
                                    $GLOBALS['log']->info('Save Deals PostMoneyValuation Details:DealPostMoneyValuationObj_id=' . $DealPostMoneyValuationObj_id);
                                }
                            }
                        }
                    } else {
                        $GLOBALS['log']->info('Deal Type:' . $OpportunityStructureObj->deal_type . " is not valid for Post Money Valuation");
                    }
                }

                if ($DealsObj_id && $DealsStructureObj_id) {
                    $GLOBALS['log']->info('Update LeadToDealQueue->processed_status ');
                    $OpportunityToDealQueueObj->processed_status = 1;
                    $OpportunityToDealQueueObj->save();
                } else {
                    $GLOBALS['log']->info("Update LeadToDealQueue->processed_status failed,DealsObj_id($DealsObj_id) && DealsStructureObj_id($DealsStructureObj_id) is not generated for opportunity_id (" . $OpportunityToDealQueueObj->opportunity_id . ")");
                }
            }
        }
    }

    return true;
}

/**
 * Job 8
 */
function PushContactFeedback() {

    #1. Get All contact from contact master
    $GLOBALS['log']->info("************************PushContactFeedback JOB STARTED***************************************");
    include_once 'include/utils.php';
    include_once 'modules/Feedback/config.php';
    include_once 'modules/Feedback/ContactFeedback.php';
    include_once 'modules/Feedback/ContactLastFeedback.php';

    $date_pattern = 'd-m-Y';
    $today_date = date($date_pattern);

    $ContactFeedbackObj = new ContactFeedback();
    $ContactLastFeedbackObj = new ContactLastFeedback();

    $ContactIdArray = $ContactLastFeedbackObj->getNewContactIdsForFeedback();

    $GLOBALS['log']->info("TODAY DATE :" . $today_date);

    if (count($ContactIdArray) > 0) {
        foreach ($ContactIdArray as $values) {
            $last_feedback_date = $values['last_feedback_date'];
            $end_date = null;
            $start_date = null;

            if (!$last_feedback_date) {
                $ContactLastFeedbackObj->new_with_id = TRUE;
                $start_date_local = strtotime(-$PERIOD . " day", strtotime($today_date));
                $GLOBALS['log']->info("START LOCAL DATE :" . $start_date_local);
                $start_date = date($date_pattern, $start_date_local);
                $end_date = $today_date;
            } else if ($strtotime("$PERIOD day", strtotime($last_feedback_date)) < $today_date) {
                $start_date_local = strtotime("1 day", strtotime($last_feedback_date));
                $start_date = date($date_pattern, $start_date_local);
                $end_date_local = strtotime("$PERIOD day", $start_date_local);
                $end_date = date($date_pattern, $end_date_local);
                $ContactLastFeedbackObj->id = $values['contact_last_feedback_id'];
            } else {
                continue;
            }

            $GLOBALS['log']->info("START DATE :" . $start_date . "END DATE:" . $end_date);

            $ContactLastFeedbackObj->contact_id = $values['id'];

            $userArray = $ContactLastFeedbackObj->getValidUsersForFeedback($start_date, $end_date, $THRESHOLD);
            if ($userArray) {
                $token_id = create_guid();
                foreach ($userArray as $user) {
                    $ContactFeedbackObj->contact_id = $ContactLastFeedbackObj->contact_id;
                    $ContactFeedbackObj->user_id = $user['user_id'];
                    $ContactFeedbackObj->token_id = $token_id;
                    $ContactFeedbackObj->email_send_status = 0;
                    $ContactFeedbackObj->no_feedback_flag = 0;
                    $ContactFeedbackObj->new_with_id = TRUE;
                    $ContactFeedbackObj->save();
                }
                $ContactLastFeedbackObj->last_feedback_date = $end_date;
                $ContactLastFeedbackObj->save();
            }
        }
    }

    $GLOBALS['log']->info("***************************************************************");
    return true;
}

function PushContactFeedbackV1() {

    #1. Get All contact from contact master
    $GLOBALS['log']->info("************************PushContactFeedback JOB STARTED***************************************");
    include_once 'include/utils.php';
    include_once 'modules/Feedback/config.php';
    include_once 'modules/Feedback/ContactFeedback.php';
    include_once 'modules/Feedback/ContactLastFeedback.php';

    //$date_pattern='d-m-Y';
    $date_pattern = 'Y-m-d';
    $today_date = date($date_pattern);


    $ContactLastFeedbackObj = new ContactLastFeedback();

    $LastFeedbackDetails = $ContactLastFeedbackObj->geLastRunFeedbackDate();
    //$ContactIdArray=$ContactLastFeedbackObj->geContactUserIds($PERIOD);
    $GLOBALS['log']->info("LastFeedbackDetails :" . print_r($LastFeedbackDetails, TRUE));
    $GLOBALS['log']->info("TODAY DATE :" . $today_date);
    $GLOBALS['log']->info("LAST RUN FEEDBACK DATE :" . $LastFeedbackDetails['last_feedback_date']);

    $end_date = null;
    $start_date = null;

    if (!$LastFeedbackDetails['last_feedback_date']) {
        //$ContactLastFeedbackObj->new_with_id=TRUE;
        $ContactLastFeedbackObj->id = '';
        $start_date_local = strtotime(-$PERIOD . " day", strtotime($today_date));
        $GLOBALS['log']->info("START LOCAL DATE :" . $start_date_local);
        $start_date = date($date_pattern, $start_date_local);
        $end_date = $today_date;
    } elseif (strtotime("$PERIOD day", strtotime($LastFeedbackDetails['last_feedback_date'])) < $today_date) {
        $GLOBALS['log']->info("NOT VALID TIME FOR PUSH FEEDBACK");
        $start_date_local = strtotime("1 day", strtotime($LastFeedbackDetails['last_feedback_date']));
        $start_date = date($date_pattern, $start_date_local);
        $end_date_local = strtotime("$PERIOD day", $start_date_local);
        $end_date = date($date_pattern, $end_date_local);
        $ContactLastFeedbackObj->id = $LastFeedbackDetails['contact_last_feedback_id'];
    } else {
        $GLOBALS['log']->info("NOT VALID TIME FOR PUSH FEEDBACK");
        // return true;
    }

    $GLOBALS['log']->info("START DATE :" . $start_date . "END DATE:" . $end_date);

    if ($start_date && $end_date) {

        $ContactLastFeedbackObj->insertTempContactUserIds($start_date, $end_date, $THRESHOLD);

        $ContactArray = $ContactLastFeedbackObj->getTempContactIds();
        if ($ContactArray) {
            $ContactFeedbackObj = new ContactFeedback();
            foreach ($ContactArray as $contact_id) {
                $ContactFeedbackObj->contact_id = $contact_id;
                $token_id = create_guid();
                $GLOBALS['log']->info("TOKEN ID:" . $token_id);
                $UserArray = $ContactLastFeedbackObj->getTempUserIds($contact_id);
                if ($UserArray) {
                    foreach ($UserArray as $user_id) {
                        $ContactFeedbackObj->user_id = $user_id;
                        $ContactFeedbackObj->token_id = $token_id;
                        $ContactFeedbackObj->email_send_status = 0;
                        $ContactFeedbackObj->no_feedback_flag = 0;
                        //$ContactFeedbackObj->new_with_id=TRUE;
                        $ContactFeedbackObj->id = '';
                        $ContactFeedbackObj->save();
                    }
                }
            }
            $ContactLastFeedbackObj->last_feedback_date = $end_date;
            $ContactLastFeedbackObj->save();
        }
    }

    $GLOBALS['log']->info("***************************************************************");
    return true;
}

/**
 * Job 9
 */
function ChecknUpdateValidContactMailID() {

    $db = PearDatabase::getInstance();
    $GLOBALS['log']->info("ChecknUpdateValidContactMailID JOB STARTED **********************");

//    include_once 'modules/Contacts/Contact.php';
//    $ContactObj=new Contact();

    $db->query("UPDATE contacts SET invalid_email=1 WHERE deleted=0 and invalid_emailis NULL");
    $result = $db->query("SELECT count(*) as cnt FROM contacts WHERE deleted=0 and invalid_email=0");
    $row = $db->fetchByAssoc($result);
    $tot_row_count = $row['cnt'];
    $num_of_row = 1000;

    for ($i = 0; $i < $tot_row_count; $i = $i + $num_of_row) {
        $result = $db->query("SELECT id,email1 FROM contacts WHERE deleted=0 and invalid_email=0 limit $i,$num_of_row");
        while ($row = $db->fetchByAssoc($result)) {
            $GLOBALS['log']->info("contact id is " . $row['id']);
            if ($row['email1']) {
                // check Valid Mail id
                $GLOBALS['log']->info("Email id:" . $row['email1']);
                if (preg_match("/[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}/i", $row['email1']) > 0) {
                    // if(preg_match("/[a-zA-Z0-9-.+]+@[a-zA-Z0-9-]+.[a-zA-Z]+/", $row['email1'] ) > 0 ) {
                    $GLOBALS['log']->info("Valid Email id:" . $row['id'] . "=" . $row['email1']);
                } else {
                    $db->query("UPDATE contacts SET invalid_email=1 WHERE id='" . $row['id'] . "' and deleted=0");
                    $GLOBALS['log']->info("Invalid Email id:" . $row['email1']);
                }
            } else {
                $GLOBALS['log']->info("Email id is empty");
                $db->query("UPDATE contacts SET invalid_email=1 WHERE id='" . $row['id'] . "' and deleted=0");
            }
        }
    }
    $GLOBALS['log']->info("ChecknUpdateValidContactMailID JOB END**********************");
    return array(true);
}

function PopulateLeadsForProspectList() {
    require_once('modules/ProspectLists/ProspectList.php');
    require_once('include/utils.php');
    $GLOBALS['log']->info("PopulateLeadsForProspectList:JOB STARTED");
    ini_set("memory_limit", '200M');
    set_time_limit(0);
    $ProspectListObj = new ProspectList();
    $ProspectListArray = $ProspectListObj->get_list("date_entered", " (prospect_lists.populate_lead_status='1') and prospect_lists.deleted=0", "", "", 1000);
    // print_r($ProspectListArray['list']);
    $i = 0;
    if (count($ProspectListArray['list']) > 0) {
        foreach ($ProspectListArray['list'] as $ProspectListObj) {
            try {
                setPopulateLeadStatusForProspectList($ProspectListObj->id, 2);
                /* $ProspectListObjNew = new ProspectList();
                  $ProspectListObjNew->id = $ProspectListObj->id;
                  $ProspectListObjNew->populate_lead_status = 2;
                  $ProspectListObjNew->save();
                  unset($ProspectListObjNew); */

                #echo "<pre><br>".$ProspectListObj->id;
                #echo "<br>".$ProspectListObj->start_date ."&&". $ProspectListObj->end_date;
                $where = '';
                if ($ProspectListObj->parent_type == 'CityMaster') {
                    $where.=" leads.primary_address_city='" . $ProspectListObj->parent_id . "'";
                } else if ($ProspectListObj->parent_type == 'StateMaster') {
                    $where.=" leads.primary_address_state='" . $ProspectListObj->parent_id . "'";
                } else if ($ProspectListObj->parent_type == 'LevelMaster') {
                    $where.=" leads.level='" . $ProspectListObj->parent_id . "'";
                } else if ($ProspectListObj->parent_type == 'ExperienceMaster') {
                    $exp_arr = getExperienceMinMaxById($ProspectListObj->parent_id);
                    $where.=" leads.experience >='" . $exp_arr['min'] . "' and leads.experience <='" . $exp_arr['max'] . "'";
                } else if ($ProspectListObj->parent_type == 'RegionMaster') {
                    $city_arr = getCityIdByRegionId($ProspectListObj->parent_id);
                    $where.=" leads.primary_address_city in ('" . implode("','", $city_arr) . "')";
                }
                if ($ProspectListObj->start_date && $ProspectListObj->end_date) {
                    if (getSQLDate2($ProspectListObj->start_date) && getSQLDate2($ProspectListObj->end_date)) {
                        $where.=" and (left(leads.date_modified,10)>='" . getSQLDate2($ProspectListObj->start_date) . "' and left(leads.date_modified,10)<='" . getSQLDate2($ProspectListObj->end_date) . "')";
                    } else {
                        $where.=" and (left(leads.date_modified,10)>='" . $ProspectListObj->start_date . "' and left(leads.date_modified,10)<='" . $ProspectListObj->end_date . "')";
                    }
                }
                $lead_ids_array = getLeadIdsByWhereClause($where);
                /* if User RePopulate Leads or Updated Target List criteria then remove old one
                 *  $focus->load_relationship("leads"): Once you load relationship, you need not reload again.
                  you can perform other operation like delete,add
                 */
                $ProspectListObj->load_relationship("leads"); //
                $old_leads = $ProspectListObj->leads->get();
                if (count($old_leads) > 0) {
                    $ProspectListObj->leads->delete($ProspectListObj->id);
                }
                if (count($lead_ids_array) > 0) {
                    foreach ($lead_ids_array as $key => $lead_id) {
                        $ProspectListObj->leads->add($lead_id);
                    }
                } else {
                    throw new Exception("No leads found for Prospectlist id" . $ProspectListObj->id);
                }
            } catch (Exception $e) {
                $GLOBALS['log']->error("PopulateLeadsForProspectList :: Exception:" . $e);
                setPopulateLeadStatusForProspectList($ProspectListObj->id, 4);
                /* $ProspectListObjNew = new ProspectList();
                  $ProspectListObjNew->id = $ProspectListObj->id;
                  $ProspectListObjNew->populate_lead_status = 4;
                  $ProspectListObjNew->save();
                  unset($ProspectListObjNew); */
                continue;
            }
            $newIds = $ProspectListObj->leads->get();
            #print_r($newIds);
            if (count($newIds) > 0) {
                setPopulateLeadStatusForProspectList($ProspectListObj->id, 3);
                /* $ProspectListObjNew = new ProspectList();
                  $ProspectListObjNew->id = $ProspectListObj->id;
                  $ProspectListObjNew->populate_lead_status = 3;
                  $ProspectListObjNew->save();
                  unset($ProspectListObjNew); */
            } else {
                setPopulateLeadStatusForProspectList($ProspectListObj->id, 4);
                /* $ProspectListObjNew = new ProspectList();
                  $ProspectListObjNew->id = $ProspectListObj->id;
                  $ProspectListObjNew->populate_lead_status = 4;
                  $ProspectListObjNew->save();
                  unset($ProspectListObjNew); */
            }
            $i++;
            $GLOBALS['log']->debug("PopulateLeadsForProspectList :: counter:" . $i);
            unset($ProspectListObj);
        } // END FOREACH
    }
    $GLOBALS['log']->info("PopulateLeadsForProspectList:JOB END");
    return array(true);
}

function setPopulateLeadStatusForProspectList($id, $status) {
    if (!$id) {
        return false;
    }
    require_once('modules/ProspectLists/ProspectList.php');
    $ProspectListObjNew = new ProspectList();
    $ProspectListObjNew->id = $id;
    $ProspectListObjNew->populate_lead_status = $status;
    $ProspectListObjNew->save();
    unset($ProspectListObjNew);
}

function GenerateVendorDataFile() {

    require_once('modules/Campaigns/Campaign.php');
    require_once('modules/Campaigns/Forms.php');
    require_once('modules/ProspectLists/ProspectList.php');
    include_once 'include/utils.php';
    require_once('modules/Calls/Call.php');
    require_once('modules/Campaigns/GenerateVendorsDataFile.php');
    //require_once'modules/TeamOS/TeamOS.php';

    $GLOBALS['log']->info("GenerateVendorDataFile:JOB STARTED");

    $CampaignObj = new Campaign();
    $CampaignListArray = $CampaignObj->get_list("date_entered", " (campaigns.vendor_file_status='1') AND campaigns.deleted=0", "", "", 1000);
    if (count($CampaignListArray['list']) > 0) {
        foreach ($CampaignListArray['list'] as $CampaignListObj) {
            try {
                $CampaignVendorObj = new GenerateVendorsDataFile();
                $CampaignVendorObj->getLeads($CampaignListObj->id);

                // Save Status
                $CampaignObj->id = $CampaignListObj->id;
                $CampaignObj->vendor_file_status = 2; // file is 
                $CampaignObj->save();
            } catch (Exception $e) {
                $GLOBALS['log']->info("GenerateVendorDataFile:: Exception" . $e);
                continue;
            }
        }
    }
    $GLOBALS['log']->info("GenerateVendorDataFile:JOB END");
    return array(true);
}

function ImportLeadCSVFileData() {
    //require_once ('modules/Leads/ImportLead.php');
    global $current_user, $import_bean_map;
    global $import_file_name;
    global $outlook_contacts_field_map;
    global $users_field_map;
    global $sugar_config;

    //ini_set("max_execution_time", 3600000);
    ini_set("memory_limit", '200M');
    set_time_limit(0);

    require_once ('data/Tracker.php');
    require_once ('modules/Import/ImportMap.php');
    require_once ('modules/Import/UsersLastImport.php');
    require_once ('modules/Import/parse_utils.php');
    require_once ('include/ListView/ListView.php');
    require_once ('modules/Import/config.php');
    require_once ('include/utils.php');
    require_once('modules/Import/Forms.php');
    require_once('include/utils.php');

    $tmp_file_name = $sugar_config['import_dir'] . "Lead.csv";

    if (!is_dir($sugar_config['import_dir'] . "uploaded")) {
        mkdir($sugar_config['import_dir'] . "uploaded", 0777);
    }

    $max_lines = -1;
    $ret_value = 0;
    $has_header = 1;
    $delimiter = ",";

    $job_name = __FUNCTION__;
    $today = getdate();
    $timeOfDay = $today['mday'] . "_" . $today['mon'] . "_" . $today[year] . "_" . $today['hours'] . "h_" . $today['minutes'] . "m";
    $ImportErrorFile = "cache/import/ImportErrorFile_Leads_" . $timeOfDay . ".csv";
    $ImportLogFile = "cache/import/ImportLog_Leads_" . $timeOfDay . ".log";
    log_into_file($ImportErrorFile, "Login, First Name, Last Name, Alternate number, Contact number,Experience, Level, Email, Address, Region, NA, Gender,Error Message\n");
    log_into_file($ImportLogFile, "=====================IMPORT STARTED : $timeOfDay ================\n");

    if (is_file($tmp_file_name)) {
        $ret_value = parse_import($tmp_file_name, $delimiter, $max_lines, $has_header); // Old function 

        $rows = $ret_value['rows'];
        $ret_field_count = $ret_value['field_count'];

        $saved_ids = array();
        $updated_ids = array();
        $firstrow = 0;

        if ($has_header == 1) {
            $firstrow = array_shift($rows);
        }


        $field_map = $outlook_contacts_field_map;
        $header_field_count = 0;
        foreach ($firstrow as $key => $value) {
            if ($outlook_contacts_field_map[$value]) {
                $import_field_array['colnum' . $key] = $outlook_contacts_field_map[$value];
                $header_field_count++;
            } else {
                $import_field_array['colnum' . $key] = "-1";
            }
        }

        if ($header_field_count == 0) {
            log_into_file($ImportErrorFile, "Fields Header is not set,Header should be in following format  \n");
            log_into_file($ImportErrorFile, "Login, First Name, Last Name, Alternate number, Contact number,Experience, Level, Email, Address, Region, NA, Gender\n");
            $log_file = $ImportErrorFile;
            return array(0 => true, 1 => " :CSV Count=" . count($rows) . ",Inserted=" . count($saved_ids) . ",Updated=" . count($updated_ids) . "|$log_file");
        }

        $bean = $import_bean_map['Leads'];
        require_once ("modules/Import/$bean.php");
        $focus = new $bean ();

        //name of duplicate import log file, append it with module and date stamp to insure unique name

        $importable_fields = array();
        $translated_column_fields = array();
        get_importable_fields($focus, $importable_fields, $translated_column_fields);

        foreach ($import_field_array as $name => $value) {
            // only look for var names that start with "colnum"
            if (strncasecmp($name, "colnum", 6) != 0) {
                continue;
            }
            if ($value == "-1") {
                continue;
            }
            // this value is a user defined field name
            $user_field = $value;
            // pull out the column position for this field name
            $pos = substr($name, 6);

            unset($module_custom_fields_def);
            $module_custom_fields_def = $focus->custom_fields->avail_fields;
            foreach ($module_custom_fields_def as $name => $field_def) {
                if ($name != 'id_c')
                    $importable_fields[$field_def['name']] = 1;
            }
            if (isset($importable_fields[$user_field])) {
                // now mark that we've seen this field
                $field_to_pos[$user_field] = $pos;
                $col_pos_to_field[$pos] = $user_field;
            }
        }

        // Moving file to uploaded dir 
        if (file_exists($tmp_file_name)) {
            #echo $tmp_file_name."===".$sugar_config['import_dir'] ."uploaded/Lead.csv";
            $new_file_name = "Lead_" . $timeOfDay . ".csv";
            if (copy($tmp_file_name, $sugar_config['import_dir'] . "uploaded/" . $new_file_name)) {
                unlink($tmp_file_name);
            }
            log_into_file($ImportLogFile, "File is deleted [$tmp_file_name]\n");
        }
        unset($fieldDefs);
        $fieldDefs = $focus->getFieldDefinitions();

        foreach ($rows as $row) {

            unset($focus);
            $focus = & new $bean ();
            $focus->save_from_post = false;

            $do_save = 1;

            for ($field_count = 0; $field_count < $ret_field_count; $field_count++) {
                if (isset($col_pos_to_field[$field_count])) {
                    if (!isset($row[$field_count])) {
                        continue;
                    }
                    // TODO: add check for user input
                    // addslashes, striptags, etc..
                    unset($field);
                    $field = $col_pos_to_field[$field_count];

                    // handle _dom based values
                    if ($fieldDefs[$field]['type'] == 'enum') {
                        // we found a _dom type value - compare and assign, or drop if not found
                        foreach ($app_list_strings[$fieldDefs[$field]['options']] as $key => $value) {
                            if ((strtolower($row[$field_count]) == strtolower($value)) && ($value != "")) {
                                $row[$field_count] = $value;
                            }
                        }
                    }
                    $focus->$field = str_replace('"', "", $row[$field_count]);
                }
            }
            //unset($var_def_indexes);
            //$var_def_indexes = $dictionary[$focus->object_name]['indices'];
            //"now do any special processing";
            // $focus->process_special_fields();

            $focus->get_names_from_full_name();
            $focus->add_create_assigned_user_name();
            $focus->add_salutation();
            $focus->add_lead_status();
            $focus->add_lead_source();
            $focus->add_do_not_call();
            $focus->add_email_opt_out();
            $focus->add_primary_address_streets();
            $focus->add_alt_address_streets();
            $focus->add_mobile_phone();

            unset($import_city);
            $import_city = $focus->primary_address_city;
            $focus->add_primary_address_city();
            #Check into master
            if ($import_city && !$focus->primary_address_city) {
                $badline = implode(",", $row);
                $not_imported_str = $badline . ",$import_city is not found into City master Database \n";
                log_into_file($ImportErrorFile, $not_imported_str);
                continue;
            }
            unset($import_level);
            $import_level = $focus->level;
            $focus->add_level();
            if ($import_level && !$focus->level) {
                $badline = implode(",", $row);
                $not_imported_str = $badline . ",$import_level is not found into Level master Database \n";
                log_into_file($ImportErrorFile, $not_imported_str);
                continue;
            }

            if (strstr($focus->phone_mobile, 'E+')) {
                $badline = implode(",", $row);
                $not_imported_str = $badline . ",Mobile phone number($focus->phone_mobile) format is not correct \n";
                log_into_file($ImportErrorFile, $not_imported_str);
                continue;
            }



            $no_required = 0;
            foreach ($focus->required_fields as $field => $notused) {
                if (!isset($focus->$field) || $focus->$field == '') {
                    $do_save = 0;
                    $skip_required_count++;
                    $badline = implode(",", $row);
                    $not_imported_str = $badline . ",$field is required\n";
                    log_into_file($ImportErrorFile, $not_imported_str);
                    $GLOBALS['log']->info("[IMPORT][NOT IMPORTED]:[" . $not_imported_str . "]");
                    $no_required = 1;
                    break;
                }
            }
            // If required fields are not available then log into file with error msg and continue execution 
            if ($no_required == 1) {
                continue;
            }

            # Checking duplicates entry and update with new one.
            # if isUnique is false then do update 
            $isUnique = checkForDupesAndSetID($focus, $row);

            if (!$isUnique) {
                $do_save = 0;
            }

            if ($do_save) {
                /* if (!isset($focus->assigned_user_id) || $focus->assigned_user_id == '') {
                  $focus->assigned_user_id = $current_user->id;
                  }
                  if (!isset($focus->modified_user_id) || $focus->modified_user_id == '') {
                  $focus->modified_user_id = $current_user->id;
                  }
                  $focus->save();
                 */
                $lead_id = create_guid();
                $current_date_time = date("Y-m-d H:i:s");

                $query_lead = "INSERT INTO leads SET 
                                        id = '$lead_id'
                                        ,date_entered = '$current_date_time'                                        
                                        ,date_modified = '$current_date_time'
                                        ,modified_user_id = '$current_user->id'
                                        ,assigned_user_id = '$current_user->id'
                                        ,created_by = '$current_user->id'
                                        ,salutation = '$focus->salutation'
                                        ,first_name = '$focus->first_name'
                                        ,last_name = '$focus->last_name'
                                        ,title = '$focus->title'
                                        ,refered_by = '$focus->refered_by'
                                        ,lead_source = '$focus->lead_source'
                                        ,lead_type = '$focus->lead_type'
                                        ,lead_source_description = '$focus->lead_source_description'
                                        ,status = '$focus->status'
                                        ,status_description = '$focus->status_description'
                                        ,department = '$focus->department'
                                        ,reports_to_id = '$focus->reports_to_id'
                                        ,do_not_call = '$focus->do_not_call'
                                        ,phone_home = '$focus->phone_home'
                                        ,phone_mobile = '$focus->phone_mobile'
                                        ,phone_work = '$focus->phone_work'
                                        ,phone_other = '$focus->phone_other'
                                        ,phone_fax = '$focus->phone_fax'
                                        ,email1 = '$focus->email1'
                                        ,email2 = '$focus->email2'
                                        ,primary_address_street = '" . trim(addslashes($focus->primary_address_street)) . "'
                                        ,primary_address_city = '$focus->primary_address_city'
                                        ,primary_address_state = '$focus->primary_address_state'
                                        ,primary_address_postalcode = '$focus->primary_address_postalcode'
                                        ,primary_address_country = '$focus->primary_address_country'
                                        ,alt_address_street = '" . trim(addslashes($focus->alt_address_street)) . "'
                                        ,alt_address_city = '$focus->alt_address_city'
                                        ,alt_address_state = '$focus->alt_address_state'
                                        ,alt_address_postalcode = '$focus->alt_address_postalcode'
                                        ,alt_address_country = '$focus->alt_address_country'
                                        ,description = '$focus->description'
                                        ,account_name = '$focus->account_name'
                                        ,account_description = '$focus->account_description'
                                        ,contact_id = '$focus->contact_id'
                                        ,account_id = '$focus->account_id'
                                        ,opportunity_id = '$focus->opportunity_id'
                                        ,brand_id = '$focus->brand_id'
                                        ,opportunity_name = '$focus->opportunity_name'
                                        ,opportunity_amount = '$focus->opportunity_amount'
                                        ,campaign_id = '$focus->campaign_id'
                                        ,portal_name = '$focus->portal_name'
                                        ,portal_app = '$focus->portal_app'
                                        ,invalid_email = '$focus->invalid_email'
                                        ,login = '$focus->login'
                                        ,experience = '$focus->experience'
                                        ,level = '$focus->level'
                                        ,gender = '$focus->gender'
                                ";
                $result_lead = $GLOBALS['db']->query($query_lead, true, "Error filling in call array: ");
                //CSTM Table
                $lead_cstm_id = create_guid();
                $query_lead_cstm = "INSERT INTO leads_users (id ,user_id ,lead_id , date_modified ) VALUES ('$lead_cstm_id' ,'$current_user->id' ,'$lead_id' ,'$current_date_time')";
                $result_lead_cstm = $GLOBALS['db']->query($query_lead_cstm, true, "Error filling in call array: ");
                //$GLOBALS['log']->info("Lead Data Inserted into Lead Table count=>".$count);
                array_push($saved_ids, $lead_id);
                $count++;
            } else {
                // Update record 
                if (!$focus->id) {
                    continue;
                }
                $current_date_time = date("Y-m-d H:i:s");

                $query_lead = "UPDATE leads SET 
                                        date_modified = '$current_date_time'
                                        ,modified_user_id = '$current_user->id'
                                        ,assigned_user_id = '$current_user->id'
                                        ,created_by = '$current_user->id'
                                        ,salutation = '$focus->salutation'
                                        ,first_name = '$focus->first_name'
                                        ,last_name = '$focus->last_name'
                                        ,title = '$focus->title'
                                        ,refered_by = '$focus->refered_by'
                                        ,lead_source = '$focus->lead_source'
                                        ,lead_type = '$focus->lead_type'
                                        ,lead_source_description = '$focus->lead_source_description'
                                        ,status = '$focus->status'
                                        ,status_description = '$focus->status_description'
                                        ,department = '$focus->department'
                                        ,reports_to_id = '$focus->reports_to_id'
                                        ,do_not_call = '$focus->do_not_call'
                                        ,phone_home = '$focus->phone_home'
                                        ,phone_mobile = '$focus->phone_mobile'
                                        ,phone_work = '$focus->phone_work'
                                        ,phone_other = '$focus->phone_other'
                                        ,phone_fax = '$focus->phone_fax'
                                        ,email1 = '$focus->email1'
                                        ,email2 = '$focus->email2'
                                        ,primary_address_street = '" . trim(addslashes($focus->primary_address_street)) . "'
                                        ,primary_address_city = '$focus->primary_address_city'
                                        ,primary_address_state = '$focus->primary_address_state'
                                        ,primary_address_postalcode = '$focus->primary_address_postalcode'
                                        ,primary_address_country = '$focus->primary_address_country'
                                        ,alt_address_street = '" . trim(addslashes($focus->alt_address_street)) . "'
                                        ,alt_address_city = '$focus->alt_address_city'
                                        ,alt_address_state = '$focus->alt_address_state'
                                        ,alt_address_postalcode = '$focus->alt_address_postalcode'
                                        ,alt_address_country = '$focus->alt_address_country'
                                        ,description = '$focus->description'
                                        ,account_name = '$focus->account_name'
                                        ,account_description = '$focus->account_description'
                                        ,contact_id = '$focus->contact_id'
                                        ,account_id = '$focus->account_id'
                                        ,opportunity_id = '$focus->opportunity_id'
                                        ,brand_id = '$focus->brand_id'
                                        ,opportunity_name = '$focus->opportunity_name'
                                        ,opportunity_amount = '$focus->opportunity_amount'
                                        ,campaign_id = '$focus->campaign_id'
                                        ,portal_name = '$focus->portal_name'
                                        ,portal_app = '$focus->portal_app'
                                        ,invalid_email = '$focus->invalid_email'
                                        ,login = '$focus->login'
                                        ,experience = '$focus->experience'
                                        ,level = '$focus->level'
                                        ,gender = '$focus->gender'
                                            
                                        where
                                          id = '$focus->id'
                                ";
                $result_lead = $GLOBALS['db']->query($query_lead, true, "Error filling in call array: ");
                array_push($updated_ids, $focus->id);
            }
        }
    } else {
        log_into_file($ImportErrorFile, "File Lead.csv is not available at location " . $sugar_config['import_dir'] . "\n");
        echo "<b>File Lead.csv is not available at location " . $sugar_config['import_dir'] . "</b>";
    }

    //echo "<br><b>Total CSV file Record: " . count($rows) . "<br>";
    //echo "<br><b>Total inserted Record: " . count($saved_ids) . "</b>";
    //echo "<br><b>Total Updated Record: " . count($updated_ids) . "</b>";
    //echo "<pre>";print_r($updated_ids);
    log_into_file($ImportLogFile, "Total CSV file Record: " . count($rows) . "\n");
    log_into_file($ImportLogFile, "Total inserted Record: " . count($saved_ids) . "\n");
    log_into_file($ImportLogFile, "Total Updated Record: " . count($updated_ids) . "\n");
    //echo "<br><b>Import Log File Location </b>: " . $sugar_config['site_url'] . "/" . $ImportLogFile;
    //echo "<br><b>Import Error File Location </b>:  " . $sugar_config['site_url'] . "/" . $ImportErrorFile;
    #insert_into_lead_import_scheduler($job_name,count($rows),count($saved_ids),count($updated_ids),$ImportErrorFile);
    #return true;
    //$log_file="<a href='".$ImportErrorFile."' class='listViewTdLinkS1'>LogFile</a>";
    $log_file = $ImportErrorFile;
    return array(0 => true, 1 => " CSV Count=" . count($rows) . ",Inserted=" . count($saved_ids) . ",Updated=" . count($updated_ids) . "|" . $log_file);
}

function insert_into_lead_import_scheduler($job_name, $rows, $saved_ids, $updated_ids, $ImportErrorFile) {
    # LOG INTO SCHEDULER TIME

    $scheduler_sql = "select id from schedulers where job like '%$job_name%' and deleted=0";
    $scheduler_result = $GLOBALS['db']->query($scheduler_sql, true, "Error filling in query : ");
    $scheduler = $GLOBALS['db']->fetchByAssoc($scheduler_result);
    $scheduler_id = $scheduler['id'];

    $t_id = create_guid();
    $current_date_time = date("Y-m-d H:i:s");
    $insert_scheduler_sql = "INSERT INTO lead_import_schedulers_times SET
        id='$t_id',
        deleted=0, 
        date_entered='$current_date_time',
        date_modified='$current_date_time',
        scheduler_id='$scheduler_id',
        execute_time='$current_date_time',
        status='Completed',
        tot_csv_record='" . $rows . "',
        tot_inserted_record='" . $saved_ids . "',
        tot_updated_record='" . $updated_ids . "',
        log_file='" . $ImportErrorFile . "';
        ";
    $GLOBALS['db']->query($insert_scheduler_sql, true, "Error filling in call array: ");

    return true;
}

function checkForDupesAndSetID(&$focus, $import_row) {
    $sql = "select * from {$focus->table_name} where ";

//    if ($focus->email1 && $focus->phone_mobile && $focus->last_name) {
//        $sql.=" email1='{$focus->email1}' and phone_mobile='{$focus->phone_mobile}' and last_name='{$focus->last_name}'";
//        $result = $focus->db->query($sql);
//        $row = $focus->db->fetchByAssoc($result);
//    } else
    if ($focus->email1 && $focus->phone_mobile) {
        $sql.=" email1='{$focus->email1}' and phone_mobile='{$focus->phone_mobile}'";
        $result = $focus->db->query($sql);
        $row = $focus->db->fetchByAssoc($result);
    }
    if ($row[id]) {
        $focus->id = $row[id];
        return false;
    }
    return TRUE;
}

function log_into_file($file_name, $str) {
    $fh = fopen($file_name, 'a+');
    fwrite($fh, $str);
    fclose($fh);
}

function SendMailToVendors() {
    global $timedate;
    require_once('modules/Campaigns/Campaign.php');
    include_once 'include/utils.php';
    require_once('modules/Emails/Email.php');

    $GLOBALS['log']->info("SendMailToVendors:JOB STARTED");
    $campaign_dir = "custom/tmp/campaigns/";
    $CampaignObj = new Campaign();
    $CampaignListArray = $CampaignObj->get_list("date_entered", " (campaigns.send_email='1' and campaigns.vendor_file_status='2') AND campaigns.deleted=0", "", "", 1000);
    // print_r($CampaignListArray);
    if (count($CampaignListArray['list']) > 0) {
        foreach ($CampaignListArray['list'] as $CampaignObj) {
            $CampaignObj->load_relationship('vendors');
            $VendorIds = $CampaignObj->vendors->get();
            if (count($VendorIds) > 0) {
                foreach ($VendorIds as $VendorId) {
                    $VendorObj = new TeamOS();
                    $VendorObj->retrieve($VendorId);
                    $VendorObj->email = $VendorObj->fetched_row['email'];
                    //echo "<pre>";print_r($VendorObj);echo "<br>>>>>".$VendorObj->email;

                    echo "<br>" . $LeadDataFilePath = $campaign_dir . $CampaignObj->id . "/" . $VendorId . ".csv";
                    if (file_exists($LeadDataFilePath) && is_file($LeadDataFilePath)) {
                        unset($EmailObj);
                        $EmailObj = new Email();
                        $EmailObj->name = "Lead Data For Campaign " . $CampaignObj->name; //Subject
                        $EmailObj->description_html = "Dear Vendor <b><u>$VendorObj->name</u></b>,<br>
                           <br> Lead Data file is generated for Campaign <b><u>" . $CampaignObj->name . "</u></b><br><br>----- System Generated ------"; //Massages
                        $EmailObj->from_addr = "jaiganesh.girinathan@timesgroup.com"; //timesgroup.com";
                        $EmailObj->from_name = "T@BForce";
                        //$EmailObj->to_addrs_arr = $EmailObj->parse_addrs("ynpatil@gmail.com;pankajkambleengg@gmail.com", $VendorObj->id, "The Email Name", $VendorObj->email . ";pankajkambleengg@gmail.com");
                        $EmailObj->to_addrs_arr = $EmailObj->parse_addrs($VendorObj->email, $VendorObj->id, $VendorObj->name, $VendorObj->email);

                        //AddAttachment
                        //if($EmailObj->send()) {
                        if ($EmailObj->sendWithFileAttachment($LeadDataFilePath, $CampaignObj->name . ".csv")) {
                            $EmailObj->status = 'sent';
                            $today = gmdate('Y-m-d H:i:s');
                            $EmailObj->date_start = $timedate->to_display_date($today);
                            $EmailObj->time_start = $timedate->to_display_time($today, true);
                            unset($CampaignObjNew);
                            $CampaignObjNew = new Campaign();
                            $CampaignObjNew->id = $CampaignObj->id;
                            $CampaignObjNew->send_email = 2;
                            $CampaignObjNew->save();
                        } else {
                            echo "<br>" . $EmailObj->status = 'send_error';
                        }
                        $EmailObj->to_addrs = $VendorObj->id;
                        $EmailObj->save();
                        echo "<br>Email Send Status:" . $EmailObj->status;
                        //echo"<pre>";print_r($EmailObj);
                    }
                }
            } else {
                echo "<br>" . $msg = "Vendors is not available for Campaign id " . $CampaignObj->id;
            }
        }
    }
    return array(true);
}

function SendCallReminderMail() {
    global $timedate;

    require_once('modules/Calls/Call.php');
    require_once('modules/Emails/Email.php');
    require_once('modules/Leads/Lead.php');
    require_once('modules/TeamsOS/TeamOS.php');

    $CallObj = new Call();
    $VendorObj = new TeamOS();
    $LeadObj = new Lead();
    $GLOBALS['log']->info("SendCallReminderMail:JOB STARTED");

    $CallList = $CallObj->get_list("id", " calls.status='call_back' and calls.call_back_date=CURRENT_DATE and calls.call_back_time>=CURRENT_TIME", "", "", 500);
    $GLOBALS['log']->info("SendCallReminderMail: CallList List =>" . print_r($CallList['list'], true));
    if (count($CallList['list']) > 0) {
        foreach ($CallList['list'] as $CallListObj) {
            try {
                $CallListObj->fill_in_additional_parent_fields();
                $VendorObj->retrieve($CallListObj->assigned_team_id_c);
                $LeadObj->retrieve($CallListObj->parent_id);
                $EmailObj = new Email();
                $EmailObj->name = "Calling Date File " . $CallListObj->name; //Subject

                $d1y = substr($CallListObj->call_back_date, 0, 4);
                $d1m = substr($CallListObj->call_back_date, 5, 2);
                $d1d = substr($CallListObj->call_back_date, 8, 2);

                $CallDate = date("D M j  Y", mktime(0, 0, 0, $d1m, $d1d, $d1y));
                $text = "<br> <b> PFA </b><br><br>
                        --------- System Generated --------
                    ";
                $EmailObj->description_html = "Dear <b>$VendorObj->name</b>, " . $text; //Massages
                $EmailObj->from_addr = "jaiganesh.girinathan@timesgroup.com"; //DEFAULT_MAIL_ID;
//              $EmailObj->from_name = "Administrator";
                //$EmailObj->from_addr = "timesgroup.com";
                $EmailObj->from_name = "T@BForce";
                $email_address = str_replace(",", ";", $VendorObj->email);
                //$EmailObj->to_addrs_arr = $EmailObj->parse_addrs($email_address, $VendorObj->id, $VendorObj->name, $email_address);
                $EmailObj->to_addrs_arr = $EmailObj->parse_addrs($VendorObj->email, $VendorObj->id, $VendorObj->name, $VendorObj->email);


                $CallBackFilePath = "custom/tmp/call/";
                $FileName = $CallBackFilePath . $CallListObj->name . ".csv";
                $FileContent = $CallListObj->tokan_no . ",  " . $LeadObj->phone_mobile . "\n";

                $FileHandle = fopen($FileName, 'a+') or die("can't open file");
                fwrite($FileHandle, $FileContent, '100000');
                fclose($FileHandle);

                if ($EmailObj->sendWithFileAttachment($FileName, $CallListObj->name . ".csv")) {
                    $EmailObj->status = 'sent';
                    $today = gmdate('Y-m-d H:i:s');
                    $EmailObj->date_start = $timedate->to_display_date($today);
                    $EmailObj->time_start = $timedate->to_display_time($today, true);
                } else {
                    $EmailObj->status = 'send_error';
                }
                $EmailObj->to_addrs = $VendorObj->id;
                $EmailObj->save(FALSE);
                echo "<br>Email Send Status:" . $EmailObj->status;
                //echo"<pre>";print_r($EmailObj);

                $GLOBALS['log']->info("SendCallReminderMail:JOB END");
                unset($EmailObj);
            } catch (Exception $e) {
                $GLOBALS['log']->error("SendCallReminderMail :: Exception:" . $e);
                continue;
            }
        }
    }

    return array(true);
}

function ImportDND() {

    require_once('include/formbase.php');
    require_once('include/upload_file.php');
    require_once('modules/Import/parse_utils.php');

    $GLOBALS['log']->info("ImportDND:JOB STARTED");

    $tmp_file_name = $sugar_config['upload_dir'] . "/DND.csv"; // . $_FILES['dnd_file']['name'];

    $timeOfDay = $today['mday'] . "_" . $today['mon'] . "_" . $today[year] . "_" . $today['hours'] . "h_" . $today['minutes'] . "m";
    $ImportDNDErrorFile = "cache/import/DNDErrorFile_" . $timeOfDay . ".csv";
    log_into_file($ImportDNDErrorFile, "MOBILE_NO, ERROR_MSG\n");
    if (file_exists($tmp_file_name)) {
        $max_lines = -1;
        $ret_value = 0;
        $has_header = 1;
        $delimiter = "|";
        try {
            set_time_limit(0);
//        $ret_value = parse_import($tmp_file_name, $delimiter, $max_lines, $has_header);
//        $rows = $ret_value['rows'];
//        $ret_field_count = $ret_value['field_count'];
//        $saved_ids = array();
//        $firstrow = 0;
//        if ($has_header == 1) {
//            $firstrow = array_shift($rows);
//        }//
//        foreach ($rows as $key => $FileValue) {


            $FileHandle = fopen($tmp_file_name, 'r') or die("can't open file");
            $FileObj = fread($FileHandle, '100000000');
            fclose($FileHandle);
            $FileObj = explode("\n", $FileObj);
            foreach ($FileObj as $key => $FileValue) {
                $TotalRecordCount++;
                $LeadId = getLeadIdByMobileNo($FileValue);
                if (!$LeadId) {
                    $TotatalSkipRecord++; //Skip
                    $not_imported_str = $FileValue . ", Mobile No. is not found \n";
                    log_into_file($ImportDNDErrorFile, $not_imported_str);
                    continue;
                }
                if ($LeadId) {
                    $TotatalUpdateRecord++;
                    //inser data into lead_brand_sold  table
                    $current_date_time = date("Y-m-d H:i:s");
                    $lead_brand_sold_id = create_guid();
                    $query_lead_brand_sold = "UPDATE leads SET do_not_call = 'on' WHERE id = '$LeadId'";
                    $result_lead_brand_sold = $GLOBALS['db']->query($query_lead_brand_sold, true, "Error filling in lead_brand_sold array: ");
                }
            }
            if (file_exists($tmp_file_name)) {
                #echo $tmp_file_name."===".$sugar_config['import_dir'] ."uploaded/Lead.csv";
                $new_file_name = "DND_" . $timeOfDay . ".csv";
                if (copy($tmp_file_name, $sugar_config['import_dir'] . "uploaded/" . $new_file_name)) {
                    unlink($tmp_file_name);
                }
            }
        } catch (Exception $e) {
            $GLOBALS['log']->error("ImportDND :: Exception:" . $e);
            // continue;
        }
    } else {
        log_into_file($ImportDNDErrorFile, "File DND.csv is not available at location " . $sugar_config['import_dir'] . "\n");
    }

    $log_file = $ImportDNDErrorFile;
    return array(0 => true, 1 => " CSV Count=" . ($TotalRecordCount) . ",Update=" . ($TotatalUpdateRecord) . ",Skip=" . ($TotatalSkipRecord) . "|" . $log_file);
}

function ImportProductSold() {

    require_once('include/formbase.php');
    require_once('include/upload_file.php');
    $GLOBALS['log']->info("ImportProductSold:JOB STARTED");

    $timeOfDay = $today['mday'] . "_" . $today['mon'] . "_" . $today[year] . "_" . $today['hours'] . "h_" . $today['minutes'] . "m";
    $ProductSoldFileName = $sugar_config['upload_dir'] . "/ProductSold.csv"; // . $_FILES['sold_file']['name'];

    $ImportProductSoldErrorFile = "cache/import/ProductSoldErrorFile_" . $timeOfDay . ".csv";
    log_into_file($ImportProductSoldErrorFile, "MOBILE_NO,PRODUCT_NAME, ERROR_MSG\n");

    if (file_exists($ProductSoldFileName)) {
        $max_lines = -1;
        $ret_value = 0;
        $has_header = 1;
        $delimiter = "|";
        try {
            $FileHandle = fopen($ProductSoldFileName, 'r') or die("can't open file");
            $FileObj = fread($FileHandle, '100000000');
            fclose($FileHandle);
            $FileObj = explode("\n", $FileObj);
            foreach ($FileObj as $key => $FileValue) {
                $TotalRecordCount;
                $DataArr = explode(",", $FileValue);
                $LeadId = getLeadIdByMobileNo($DataArr[0]);
                $BrandId = getBrandIdByBrandName($DataArr[1]);
                if (!$LeadId) {
                    $TotatalSkipRecord++; //Skip
                    $not_imported_str = $FileValue . ", Mobile ($DataArr[0]) is not found \n";
                    log_into_file($ImportProductSoldErrorFile, $not_imported_str);
                    continue;
                }
                if (!$BrandId) {
                    $TotatalSkipRecord++; //Skip
                    $not_imported_str = $FileValue . ", Product Name ($DataArr[1]) is not found \n";
                    log_into_file($ImportProductSoldErrorFile, $not_imported_str);
                    continue;
                }
                if ($LeadId && $BrandId) {
                    $TotatalInsertRecord++; //total add
                    //inser data into lead_brand_sold  table
                    $current_date_time = date("Y-m-d H:i:s");
                    $lead_brand_sold_id = create_guid();
                    $query_lead_brand_sold = "INSERT INTO lead_brand_sold SET 
                                        id = '$lead_brand_sold_id' 
                                        ,lead_id = '$LeadId'
                                        ,brand_id = '$BrandId'
                                        ,created_by = '$current_user->id'
                                        ,date_entered = '$current_date_time'
                                        ,modified_user_id = '$current_user->id'
                                        ,assigned_user_id = '$current_user->id'
                                        ,date_modified = '$current_date_time'
            ";
                    $result_lead_brand_sold = $GLOBALS['db']->query($query_lead_brand_sold, true, "Error filling in lead_brand_sold array: ");
                }
            }
            // Moving file to uploaded dir 

            if (file_exists($ProductSoldFileName)) {
                #echo $tmp_file_name."===".$sugar_config['import_dir'] ."uploaded/Lead.csv";
                $new_file_name = "ProductSold_" . $timeOfDay . ".csv";
                if (copy($ProductSoldFileName, $sugar_config['import_dir'] . "uploaded/" . $new_file_name)) {
                    unlink($ProductSoldFileName);
                }
            }
        } catch (Exception $e) {
            $GLOBALS['log']->error("ImportDND :: Exception:" . $e);
            // continue;
        }
    } else {
        log_into_file($ImportProductSoldErrorFile, "File ProductSold.csv is not available at location " . $sugar_config['import_dir'] . "\n");
    }
    $log_file = $ImportProductSoldErrorFile;
    return array(0 => true, 1 => " CSV Count=" . ($TotalRecordCount) . ",Inserted=" . ($TotatalInsertRecord) . ",Skip=" . ($TotatalSkipRecord) . "|" . $log_file);
}

function ImportLeadCSVFileDataV1() {

    global $current_user, $import_bean_map;
    global $import_file_name;
    global $outlook_contacts_field_map;
    global $users_field_map;
    global $sugar_config;

    ini_set("memory_limit", '200M');
    set_time_limit(0);

    require_once ('data/Tracker.php');
    require_once ('modules/Import/ImportMap.php');
    require_once ('modules/Import/UsersLastImport.php');
    require_once ('modules/Import/parse_utils.php');
    require_once ('include/ListView/ListView.php');
    require_once ('modules/Import/config.php');
    require_once ('include/utils.php');
    require_once('modules/Import/Forms.php');
    require_once('include/utils.php');

    $tmp_file_name = $sugar_config['import_dir'] . "Lead.csv";

    if (!is_dir($sugar_config['import_dir'] . "uploaded")) {
        mkdir($sugar_config['import_dir'] . "uploaded", 0777);
    }

    $max_lines = -1;
    $ret_value = 0;
    $has_header = 1;
    $delimiter = ",";

    $job_name = __FUNCTION__;
    $today = getdate();
    $timeOfDay = $today['mday'] . "_" . $today['mon'] . "_" . $today[year] . "_" . $today['hours'] . "h_" . $today['minutes'] . "m";
    $ImportErrorFile = "cache/import/ImportErrorFile_Leads_" . $timeOfDay . ".csv";
    $ImportLogFile = "cache/import/ImportLog_Leads_" . $timeOfDay . ".log";
    log_into_file($ImportErrorFile, "Login, First Name, Last Name, Alternate number, Contact number,Experience, Level, Email, Address, Region, NA, Gender,Error Message\n");
    log_into_file($ImportLogFile, "=====================IMPORT STARTED : $timeOfDay ================\n");

    if (is_file($tmp_file_name)) {
        $ret_value = parse_import($tmp_file_name, $delimiter, $max_lines, $has_header); // Old function 

        $rows = $ret_value['rows'];
        $ret_field_count = $ret_value['field_count'];

        $saved_ids = array();
        $updated_ids = array();
        $firstrow = 0;

        if ($has_header == 1) {
            $firstrow = array_shift($rows);
        }


        $field_map = $outlook_contacts_field_map;
        $header_field_count = 0;
        foreach ($firstrow as $key => $value) {
            if ($outlook_contacts_field_map[$value]) {
                $import_field_array['colnum' . $key] = $outlook_contacts_field_map[$value];
                $header_field_count++;
            } else {
                $import_field_array['colnum' . $key] = "-1";
            }
        }

        if ($header_field_count == 0) {
            log_into_file($ImportErrorFile, "Fields Header is not set,Header should be in following format  \n");
            log_into_file($ImportErrorFile, "Login, First Name, Last Name, Alternate number, Contact number,Experience, Level, Email, Address, Region, NA, Gender\n");
            $log_file = $ImportErrorFile;
            return array(0 => true, 1 => " :CSV Count=" . count($rows) . ",Inserted=" . count($saved_ids) . ",Updated=" . count($updated_ids) . "|$log_file");
        }

        $bean = $import_bean_map['Leads'];
        require_once ("modules/Import/$bean.php");
        $focus = new $bean ();

        //name of duplicate import log file, append it with module and date stamp to insure unique name

        $importable_fields = array();
        $translated_column_fields = array();
        get_importable_fields($focus, $importable_fields, $translated_column_fields);

        foreach ($import_field_array as $name => $value) {
            // only look for var names that start with "colnum"
            if (strncasecmp($name, "colnum", 6) != 0) {
                continue;
            }
            if ($value == "-1") {
                continue;
            }
            // this value is a user defined field name
            $user_field = $value;
            // pull out the column position for this field name
            $pos = substr($name, 6);

            unset($module_custom_fields_def);
            $module_custom_fields_def = $focus->custom_fields->avail_fields;
            foreach ($module_custom_fields_def as $name => $field_def) {
                if ($name != 'id_c')
                    $importable_fields[$field_def['name']] = 1;
            }
            if (isset($importable_fields[$user_field])) {
                // now mark that we've seen this field
                $field_to_pos[$user_field] = $pos;
                $col_pos_to_field[$pos] = $user_field;
            }
        }

        // Moving file to uploaded dir 
        if (file_exists($tmp_file_name)) {
            $new_file_name = "Lead_" . $timeOfDay . ".csv";
            if (copy($tmp_file_name, $sugar_config['import_dir'] . "uploaded/" . $new_file_name)) {
                unlink($tmp_file_name);
            }
            log_into_file($ImportLogFile, "File is deleted [$tmp_file_name]\n");
        }
        unset($fieldDefs);
        $fieldDefs = $focus->getFieldDefinitions();

        foreach ($rows as $row) {

            unset($focus);
            $focus = & new $bean ();
            $focus->save_from_post = false;

            $do_save = 1;

            for ($field_count = 0; $field_count < $ret_field_count; $field_count++) {
                if (isset($col_pos_to_field[$field_count])) {
                    if (!isset($row[$field_count])) {
                        continue;
                    }
                    // TODO: add check for user input
                    unset($field);
                    $field = $col_pos_to_field[$field_count];

                    // handle _dom based values
                    if ($fieldDefs[$field]['type'] == 'enum') {
                        // we found a _dom type value - compare and assign, or drop if not found
                        foreach ($app_list_strings[$fieldDefs[$field]['options']] as $key => $value) {
                            if ((strtolower($row[$field_count]) == strtolower($value)) && ($value != "")) {
                                $row[$field_count] = $value;
                            }
                        }
                    }
                    $focus->$field = str_replace('"', "", $row[$field_count]);
                }
            }

            # Checking duplicates entry and update with new one.
            # if isUnique is false then do update 
            $isUnique = checkForDupesAndSetID($focus, $row);

            if (!$isUnique) {
                $do_save = 0;
                $focus->retrieve($focus->id);
                // Here reassing new  values
                for ($field_count = 0; $field_count < $ret_field_count; $field_count++) {
                    if (isset($col_pos_to_field[$field_count])) {
                        if (!isset($row[$field_count])) {
                            continue;
                        }
                        // TODO: add check for user input
                        unset($field);
                        $field = $col_pos_to_field[$field_count];
                        // handle _dom based values
                        if ($fieldDefs[$field]['type'] == 'enum') {
                            // we found a _dom type value - compare and assign, or drop if not found
                            foreach ($app_list_strings[$fieldDefs[$field]['options']] as $key => $value) {
                                if ((strtolower($row[$field_count]) == strtolower($value)) && ($value != "")) {
                                    $row[$field_count] = $value;
                                }
                            }
                        }
                        $focus->$field = str_replace('"', "", $row[$field_count]);
                    }
                } //END FOR
            }

            $focus->get_names_from_full_name();
            $focus->add_create_assigned_user_name();
            $focus->add_salutation();
            $focus->add_lead_status();
            $focus->add_lead_source();
            $focus->add_do_not_call();
            $focus->add_email_opt_out();
            $focus->add_primary_address_streets();
            $focus->add_alt_address_streets();
            $focus->add_mobile_phone();

            unset($import_city);
            $import_city = $focus->primary_address_city;
            $focus->add_primary_address_city();
            #Check into master
            if ($import_city && !$focus->primary_address_city) {
                $badline = implode(",", $row);
                $not_imported_str = $badline . ",$import_city is not found into City master Database \n";
                log_into_file($ImportErrorFile, $not_imported_str);
                continue;
            }
            unset($import_level);
            $import_level = $focus->level;
            $focus->add_level();
            if ($import_level && !$focus->level) {
                $badline = implode(",", $row);
                $not_imported_str = $badline . ",$import_level is not found into Level master Database \n";
                log_into_file($ImportErrorFile, $not_imported_str);
                continue;
            }

            if (strstr($focus->phone_mobile, 'E+')) {
                $badline = implode(",", $row);
                $not_imported_str = $badline . ",Mobile phone number($focus->phone_mobile) format is not correct \n";
                log_into_file($ImportErrorFile, $not_imported_str);
                continue;
            }

            // If required fields are not available then log into file with error msg and continue execution 
            $no_required = 0;
            foreach ($focus->required_fields as $field => $notused) {
                if (!isset($focus->$field) || $focus->$field == '') {
                    $do_save = 0;
                    $skip_required_count++;
                    $badline = implode(",", $row);
                    $not_imported_str = $badline . ",$field is required\n";
                    log_into_file($ImportErrorFile, $not_imported_str);
                    $GLOBALS['log']->info("[IMPORT][NOT IMPORTED]:[" . $not_imported_str . "]");
                    $no_required = 1;
                    break;
                }
            }
            if ($no_required == 1) {
                continue;
            }


            if ($do_save) {
                /* if (!isset($focus->assigned_user_id) || $focus->assigned_user_id == '') {
                  $focus->assigned_user_id = $current_user->id;
                  }
                  if (!isset($focus->modified_user_id) || $focus->modified_user_id == '') {
                  $focus->modified_user_id = $current_user->id;
                  }
                  $focus->save();
                 */
                $lead_id = create_guid();
                $current_date_time = date("Y-m-d H:i:s");

                $query_lead = "INSERT INTO leads SET 
                                        id = '$lead_id'
                                        ,date_entered = '$current_date_time'                                        
                                        ,date_modified = '$current_date_time'
                                        ,modified_user_id = '$current_user->id'
                                        ,assigned_user_id = '$current_user->id'
                                        ,created_by = '$current_user->id'
                                        ,salutation = '$focus->salutation'
                                        ,first_name = '$focus->first_name'
                                        ,last_name = '$focus->last_name'
                                        ,title = '$focus->title'
                                        ,refered_by = '$focus->refered_by'
                                        ,lead_source = '$focus->lead_source'
                                        ,lead_type = '$focus->lead_type'
                                        ,lead_source_description = '$focus->lead_source_description'
                                        ,status = '$focus->status'
                                        ,status_description = '$focus->status_description'
                                        ,department = '$focus->department'
                                        ,reports_to_id = '$focus->reports_to_id'
                                        ,do_not_call = '$focus->do_not_call'
                                        ,phone_home = '$focus->phone_home'
                                        ,phone_mobile = '$focus->phone_mobile'
                                        ,phone_work = '$focus->phone_work'
                                        ,phone_other = '$focus->phone_other'
                                        ,phone_fax = '$focus->phone_fax'
                                        ,email1 = '$focus->email1'
                                        ,email2 = '$focus->email2'
                                        ,primary_address_street = '" . trim(addslashes($focus->primary_address_street)) . "'
                                        ,primary_address_city = '$focus->primary_address_city'
                                        ,primary_address_state = '$focus->primary_address_state'
                                        ,primary_address_postalcode = '$focus->primary_address_postalcode'
                                        ,primary_address_country = '$focus->primary_address_country'
                                        ,alt_address_street = '" . trim(addslashes($focus->alt_address_street)) . "'
                                        ,alt_address_city = '$focus->alt_address_city'
                                        ,alt_address_state = '$focus->alt_address_state'
                                        ,alt_address_postalcode = '$focus->alt_address_postalcode'
                                        ,alt_address_country = '$focus->alt_address_country'
                                        ,description = '$focus->description'
                                        ,account_name = '$focus->account_name'
                                        ,account_description = '$focus->account_description'
                                        ,contact_id = '$focus->contact_id'
                                        ,account_id = '$focus->account_id'
                                        ,opportunity_id = '$focus->opportunity_id'
                                        ,brand_id = '$focus->brand_id'
                                        ,opportunity_name = '$focus->opportunity_name'
                                        ,opportunity_amount = '$focus->opportunity_amount'
                                        ,campaign_id = '$focus->campaign_id'
                                        ,portal_name = '$focus->portal_name'
                                        ,portal_app = '$focus->portal_app'
                                        ,invalid_email = '$focus->invalid_email'
                                        ,login = '$focus->login'
                                        ,experience = '$focus->experience'
                                        ,level = '$focus->level'
                                        ,gender = '$focus->gender'
                                ";
                $result_lead = $GLOBALS['db']->query($query_lead, true, "Error filling in call array: ");
                //CSTM Table
                $lead_cstm_id = create_guid();
                $query_lead_cstm = "INSERT INTO leads_users (id ,user_id ,lead_id , date_modified ) VALUES ('$lead_cstm_id' ,'$current_user->id' ,'$lead_id' ,'$current_date_time')";
                $result_lead_cstm = $GLOBALS['db']->query($query_lead_cstm, true, "Error filling in call array: ");
                //$GLOBALS['log']->info("Lead Data Inserted into Lead Table count=>".$count);
                array_push($saved_ids, $lead_id);
                $count++;
            } else {
                // Update record 
                if (!$focus->id) {
                    continue;
                }

                $focus->save();
                $current_date_time = date("Y-m-d H:i:s");

                /* $query_lead = "UPDATE leads SET 
                  date_modified = '$current_date_time'
                  ,modified_user_id = '$current_user->id'
                  ,assigned_user_id = '$current_user->id'
                  ,created_by = '$current_user->id'
                  ,salutation = '$focus->salutation'
                  ,first_name = '$focus->first_name'
                  ,last_name = '$focus->last_name'
                  ,title = '$focus->title'
                  ,refered_by = '$focus->refered_by'
                  ,lead_source = '$focus->lead_source'
                  ,lead_type = '$focus->lead_type'
                  ,lead_source_description = '$focus->lead_source_description'
                  ,status = '$focus->status'
                  ,status_description = '$focus->status_description'
                  ,department = '$focus->department'
                  ,reports_to_id = '$focus->reports_to_id'
                  ,do_not_call = '$focus->do_not_call'
                  ,phone_home = '$focus->phone_home'
                  ,phone_mobile = '$focus->phone_mobile'
                  ,phone_work = '$focus->phone_work'
                  ,phone_other = '$focus->phone_other'
                  ,phone_fax = '$focus->phone_fax'
                  ,email1 = '$focus->email1'
                  ,email2 = '$focus->email2'
                  ,primary_address_street = '" . trim(addslashes($focus->primary_address_street)) . "'
                  ,primary_address_city = '$focus->primary_address_city'
                  ,primary_address_state = '$focus->primary_address_state'
                  ,primary_address_postalcode = '$focus->primary_address_postalcode'
                  ,primary_address_country = '$focus->primary_address_country'
                  ,alt_address_street = '" . trim(addslashes($focus->alt_address_street)) . "'
                  ,alt_address_city = '$focus->alt_address_city'
                  ,alt_address_state = '$focus->alt_address_state'
                  ,alt_address_postalcode = '$focus->alt_address_postalcode'
                  ,alt_address_country = '$focus->alt_address_country'
                  ,description = '$focus->description'
                  ,account_name = '$focus->account_name'
                  ,account_description = '$focus->account_description'
                  ,contact_id = '$focus->contact_id'
                  ,account_id = '$focus->account_id'
                  ,opportunity_id = '$focus->opportunity_id'
                  ,brand_id = '$focus->brand_id'
                  ,opportunity_name = '$focus->opportunity_name'
                  ,opportunity_amount = '$focus->opportunity_amount'
                  ,campaign_id = '$focus->campaign_id'
                  ,portal_name = '$focus->portal_name'
                  ,portal_app = '$focus->portal_app'
                  ,invalid_email = '$focus->invalid_email'
                  ,login = '$focus->login'
                  ,experience = '$focus->experience'
                  ,level = '$focus->level'
                  ,gender = '$focus->gender'

                  where
                  id = '$focus->id'
                  ";
                  $result_lead = $GLOBALS['db']->query($query_lead, true, "Error filling in call array: "); */
                array_push($updated_ids, $focus->id);
            }
        }
    } else {
        log_into_file($ImportErrorFile, "File Lead.csv is not available at location " . $sugar_config['import_dir'] . "\n");
        echo "<b>File Lead.csv is not available at location " . $sugar_config['import_dir'] . "</b>";
    }


    log_into_file($ImportLogFile, "Total CSV file Record: " . count($rows)-1 . "\n");
    log_into_file($ImportLogFile, "Total inserted Record: " . count($saved_ids) . "\n");
    log_into_file($ImportLogFile, "Total Updated Record: " . count($updated_ids) . "\n");

    $log_file = $ImportErrorFile;
    return array(0 => true, 1 => " CSV Count=" . count($rows) . ",Inserted=" . count($saved_ids) . ",Updated=" . count($updated_ids) . "|" . $log_file);
}

?>