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
        //ini_set("max_execution_time", 100000000);
        set_time_limit(0);
        $GLOBALS['log']->info("GenerateVendorsDataFile: getLeads::");
        $CampaignObj = new Campaign();
        $TargetListObj = new ProspectList();
        $VendorObj = new TeamOS();


        $CampaignObj->retrieve($campaign_id);

        $CampaignObj->load_relationship('prospectlists');
        $TargetListIds = $CampaignObj->prospectlists->get();


        $CampaignObj->load_relationship('vendors');
        $VendorIds = $CampaignObj->vendors->get();


        //To get all the vendor id and persantage under that campaign
        //$vendor_list_arr = $CampaignObj->getVendorsDetails();

        foreach ($TargetListIds as $TargetListId) {
            $GLOBALS['log']->info("GenerateVendorsDataFile: TargetListIds::$TargetListId");            
            $TargetListObj->retrieve($TargetListId);
            $TargetListObj->load_relationship('leads');
            $LeadArray = $TargetListObj->leads->get();
            $TotalLeadCount = count($LeadArray);
            $TotalPercentage = 100;

                // 1. get leadArray 
                // count leads
                // count vendors
                $j = 0;
                foreach ($VendorIds as $VendorId) {
                    $GLOBALS['log']->info("GenerateVendorsDataFile: VendorIds::$VendorId");                    
                    $VendorObj->retrieve($VendorId);
                    $VendorPercentage = $VendorObj->getVendorsPercentage($CampaignObj->id);
                    //Calculation Part
                    $LeadValueCount = intval(($VendorPercentage / $TotalPercentage) * $TotalLeadCount);
                    if($TotalLeadCount>0){
                    $FileContent .= "Token No" . ",  " . "Mobile No" . "\n";
                    }  else {
                        $FileContent = "Leads are not available for  $TargetListObj->name  target List" . "\n";
                    }

                    $i = 0;
                    while ($i <= ($LeadValueCount - 1)) {
                        $GLOBALS['log']->info("GenerateVendorsDataFile: While::$i");                        
                        // Get percentage  eg.50 
                        // create callwith token
                        // get mobile no from lead
                        // save into vandor
                        // unset from lead array
                        set_time_limit(0);
                        $LeadObj = new Lead();
                        $CallObj = new Call();
                        $tokenid = get_tokenid(); //To Generate Token 10 Digit                   

                        $LeadObj->retrieve($LeadArray[$j]); //To retrive data from Lead

                        $CallObj->name = "Call to " . $LeadObj->first_name . " " . $LeadObj->last_name;
                        $CallObj->parent_type = "Leads";
                        $CallObj->status = "Planned";
                        $CallObj->parent_id = $LeadObj->id;
                        $CallObj->campaign_id = $CampaignObj->id;
                        $CallObj->tokan_no = $tokenid;
                        $CallObj->assigned_team_id_c = $VendorObj->id;
                        $CallObj->description = $CampaignObj->content;
                        $call_id = $CallObj->save();
                        $FileContent .= $tokenid . ",  " . $LeadObj->phone_mobile . "\n";
                        

                        /*  Start Functionality For Product into Call */
                        $VendorObj->load_relationship("brand");
                        $product_ids = $VendorObj->brand->get();
                        if (count($product_ids) > 0) {
                            $CallObj->load_relationship('brand');
                            foreach ($product_ids as $product_id) {
                                $GLOBALS['log']->info("GenerateVendorsDataFile: Functionality For Product into Call::$product_id");
                                //echo "<br>GenerateVendorsDataFile: Functionality For Product into Call::$product_id";
                                $CallObj->brand->add($product_id);
                                unset($product_id);
                            }
                            
                        }
                        /* END Functionality For Product into Call */
                        unset($LeadObj);
                        unset($CallObj);
                        unset($LeadArray[$j]);
                        $i++;
                        $j++;
                    }//END While loop  
                    //Functionality for Create Folder & CSV File and write content into it
                    if (!is_dir("custom/tmp/campaigns/" . $CampaignObj->id)) {
                        mkdir("custom/tmp/campaigns/" . $CampaignObj->id, 0777);
                    }
//                    echo "Count File Content".count($FileContent);
                    $DirectoryName = "custom/tmp/campaigns/" . $CampaignObj->id;
                    $FileName = $DirectoryName . "/" . $VendorObj->id . ".csv";
                    $FileHandle = fopen($FileName, 'a+') or die("can't open file");
                    fwrite($FileHandle, $FileContent,'100000000');
                    //fputcsv($FileHandle,$FileContent);
                    fclose($FileHandle);                    
                    ///END CSV File Fun
                    $GLOBALS['log']->info("GenerateVendorsDataFile: CSV File::$FileName");                    
                    unset($FileHandle);                    
                    unset($FileContent);
                    $TotalPercentage = $TotalPercentage - $VendorPercentage;
                    $TotalLeadCount = $TotalLeadCount - $LeadValueCount;
                    unset($LeadValueCount);
//                 echo "<br>";
//                 echo "<pre>";
//                 print_r($TotalLeadArrRecord);
//                 unset($TotalLeadArrRecord);
                }//END foreach vendor list

                unset($TotalPercentage);
                unset($TotalLeadCount);
                //unset($TotalLeadArrRecord);
        }//END foreach target list          
        //   return true;
    }
}

?>