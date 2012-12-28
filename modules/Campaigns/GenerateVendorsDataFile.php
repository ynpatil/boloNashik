<?php

define('sugarEntry', true);
if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
set_time_limit(0);
ini_set("max_execution_time", 10000000000);
require_once('data/SugarBean.php');
require_once('include/utils.php');
require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Campaigns/Campaign.php');
require_once('modules/Campaigns/Forms.php');
require_once('modules/ProspectLists/ProspectList.php');

include_once 'include/utils.php';
//require_once('modules/Leads/Lead.php');
require_once('modules/Calls/Call.php');

class GenerateVendorsDataFile extends SugarBean {

    var $target_list_count;
    var $TotalLeadCount;
    var $TotalPercentage;

    function GenerateVendorsDataFile() {
        //
    }

    function getLeads($campaign_id) {
        global $current_user;
        //ini_set("max_execution_time", 100000000);
        set_time_limit(0);
        //$GLOBALS['log']->info("GenerateVendorsDataFile: getLeads::");
        $CampaignObj = new Campaign();
        $TargetListObj = new ProspectList();
        $VendorObj = new TeamOS();


        $CampaignObj->retrieve($campaign_id);

        $CampaignObj->load_relationship('prospectlists');
        $TargetListIds = $CampaignObj->prospectlists->get();
        if($TargetListIds)
        $LeadArray = $CampaignObj->getUniqueLeadIdsByTargetListIds($TargetListIds);

        $CampaignObj->load_relationship('vendors');
        $VendorIds = $CampaignObj->vendors->get();

       
        //To get all the vendor id and persantage under that campaign
        //$vendor_list_arr = $CampaignObj->getVendorsDetails();
//        foreach ($TargetListIds as $TargetListId) {
//            //$GLOBALS['log']->info("GenerateVendorsDataFile: TargetListIds::$TargetListId");            
//            $TargetListObj->retrieve($TargetListId);
//            $TargetListObj->load_relationship('leads');
//            $LeadArray = $TargetListObj->leads->get();
//            
//         }//END foreach target list  


        $TotalLeadCount = count($LeadArray);
        $TotalPercentage = 100;

        if ($TotalLeadCount == 0) {
            return false;
        }
        // 1. get leadArray 
        // count leads
        // count vendors
        $j = 0;
        if (count($VendorIds) > 0) {
            foreach ($VendorIds as $VendorId) {
                $GLOBALS['log']->info("GenerateVendorsDataFile: VendorIds::$VendorId");
                $VendorObj->retrieve($VendorId);
                $VendorPercentage = $VendorObj->getVendorsPercentage($CampaignObj->id);
                //Calculation Part
                $LeadValueCount = intval(($VendorPercentage / $TotalPercentage) * $TotalLeadCount);
                if ($TotalLeadCount > 0) {
                    $FileContent .= "Token No" . ",  " . "Mobile No" . "\n";
                } else {
                    $FileContent = "Leads are not available for  $TargetListObj->name  target List" . "\n";
                }

                //$VendorObj->load_relationship("brand");
                //$product_ids = $VendorObj->brand->get();

                $i = 0;
                while ($i <= ($LeadValueCount - 1)) {
                    // $GLOBALS['log']->info("GenerateVendorsDataFile: While::$i");                        
                    // Get percentage  eg.50 
                    // create callwith token
                    // get mobile no from lead
                    // save into vandor
                    // unset from lead array
                    set_time_limit(0);

                    $tokenid = get_tokenid(); //To Generate Token 10 Digit    
                    $call_id = create_guid();
                    //Lead select Query
                    $query_lead = "SELECT id,first_name,last_name,phone_mobile FROM leads WHERE id='$LeadArray[$j]' ";
                    $result_lead = $GLOBALS['db']->query($query_lead, true, "Error filling in lead array: ");
                    $row_lead = $GLOBALS['db']->fetchByAssoc($result_lead);

                    //Insert Data into call table
                    $current_date_time = date("Y-m-d H:i:s");
                    $query_call = "INSERT INTO calls SET 
                                        id = '$call_id'
                                        ,name = 'Call to $row_lead[first_name] $row_lead[last_name]'
                                        ,status = 'Planned'
                                        ,parent_type = 'Leads'
                                        ,parent_id = '$row_lead[id]'
                                        ,campaign_id = '$CampaignObj->id'
                                        ,tokan_no = '$tokenid'
                                        ,created_by = '$current_user->id'
                                        ,modified_user_id = '$current_user->id'
                                        ,date_entered = '$current_date_time'
                                        ,date_modified = '$current_date_time'
                                        ,description = '$CampaignObj->content'                                            
                                         ";
                    $result_call = $GLOBALS['db']->query($query_call, true, "Error filling in call array: ");
                    //Insert Data into CSTM Table                        
                    $query_call_cstm = "INSERT INTO calls_cstm (id_c ,assigned_team_id_c ) VALUES ('$call_id' ,'$VendorObj->id' )";
                    $result_call_cstm = $GLOBALS['db']->query($query_call_cstm, true, "Error filling in call array: ");

                    $FileContent .= $tokenid . ",  " . $row_lead['phone_mobile'] . "\n";

                    /*  Start Functionality For Product into Call */
//                        if (count($product_ids) > 0) {                            
//                            foreach ($product_ids as $product_id) {
//                                $call_brand_id = create_guid();               
//                                //$GLOBALS['log']->info("GenerateVendorsDataFile: Functionality For Product into Call::$product_id");
//                                //echo "<br>GenerateVendorsDataFile: Functionality For Product into Call::$product_id";
//                                //$CallObj->brand->add($product_id);
//                                $query_call_brand = "INSERT INTO call_brand (id ,call_id , brand_id , date_modified)
//                                                     VALUES ('$call_brand_id','$call_id' ,'$product_id','$current_date_time' )";                       
//                                $GLOBALS['db']->query($query_call_brand, true, "Error filling in call array: ");
//                                unset($product_id);
//                            }                            
//                        }
                    /* END Functionality For Product into Call */

                    /* Create Lead - Campaign relationship */
                    $campaign_lead_id = create_guid();
                    $query_campaign_lead = "INSERT INTO campaigns_leads (id ,lead_id , campaign_id , date_modified)
                                                     VALUES ('$campaign_lead_id','$row_lead[id]' ,'$CampaignObj->id','$current_date_time' )";
                    $GLOBALS['db']->query($query_campaign_lead, true, "Error filling in  campaigns_leads");
                    /* END  */

                    /* Assign Lead To vendor */
                    $chk_lead_sql = "select id_c from leads_cstm where id_c='$row_lead[id]'";
                    $result_lead = $GLOBALS['db']->query($chk_lead_sql, true, "Error filling in lead array: ");
                    $row = $GLOBALS['db']->fetchByAssoc($result_lead);
                    if ($row['id_c']) {
                        $sql = "UPDATE  leads_cstm SET assigned_team_id_c='$VendorObj->id' WHERE id_c='$row_lead[id]' ";
                    } else {
                        $sql = "INSERT INTO leads_cstm (id_c ,assigned_team_id_c ) VALUES ('$row_lead[id]' ,'$VendorObj->id' )";
                    }
                    $GLOBALS['db']->query($sql, true, "Error filling in lead array: ");

                    /* End */

                    unset($row_lead);
                    unset($LeadArray[$j]);
                    $i++;
                    $j++;
                }//END While loop  
                //unset($product_ids);
                //Functionality for Create Folder & CSV File and write content into it
                if (!is_dir("custom/tmp/campaigns/" . $CampaignObj->id)) {
                    mkdir("custom/tmp/campaigns/" . $CampaignObj->id, 0777);
                }
                $DirectoryName = "custom/tmp/campaigns/" . $CampaignObj->id;
                $FileName = $DirectoryName . "/" . $VendorObj->id . ".csv";
                $FileHandle = fopen($FileName, 'a+') or die("can't open file");
                fwrite($FileHandle, $FileContent, '100000000');
                //fputcsv($FileHandle,$FileContent);
                fclose($FileHandle);
                ///END CSV File Fun
                $GLOBALS['log']->info("GenerateVendorsDataFile: CSV File::$FileName");
                unset($FileHandle);
                unset($FileContent);
                $TotalPercentage = $TotalPercentage - $VendorPercentage;
                $TotalLeadCount = $TotalLeadCount - $LeadValueCount;
                unset($LeadValueCount);
            }//END foreach vendor list
        }
        unset($TotalPercentage);
        unset($TotalLeadCount);
    }

   

}

?>