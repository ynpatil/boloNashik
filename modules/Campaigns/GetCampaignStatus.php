<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('modules/Campaigns/Campaign.php');
require_once('modules/Leads/Lead.php');
set_time_limit(0);

if ($_REQUEST['campaign_id'] && $_REQUEST['vendor_id'] && $_REQUEST['call_status']) {
    $VendorObj = new TeamOS();
    
    $VendorObj->retrieve($_REQUEST['vendor_id']);
    $VendorObj->name;
    $LeadIdArr = getleadIdByCampaignId($_REQUEST['campaign_id'], $_REQUEST['vendor_id'], $_REQUEST['call_status']);
    $DELIMITER="| ";
    $FileContent = "Login" . $DELIMITER . "First Name" . $DELIMITER . "Last Name" . $DELIMITER . "Alternate number" . $DELIMITER . "Contact number" . $DELIMITER . "Experience" . $DELIMITER . "Level" . $DELIMITER . "Email" . $DELIMITER . "Address" . $DELIMITER . "Region" . $DELIMITER . "Gender" . $DELIMITER . "Vendor Name" . $DELIMITER . "Status" . "\n";
    if (count($LeadIdArr) > 0) {
        foreach ($LeadIdArr as $key => $LeadId) {
            $LeadObj = new Lead();
            $LeadObj->retrieve($LeadId);
            $Address = str_replace("\n", " ", $LeadObj->primary_address_street);
            $Address = preg_replace("/[\n\r]/","",$Address);
            $FileContent .= $LeadObj->login . $DELIMITER . $LeadObj->first_name . $DELIMITER . $LeadObj->last_name . $DELIMITER . $LeadObj->phone_other . $DELIMITER . $LeadObj->phone_mobile . $DELIMITER . $LeadObj->experience . $DELIMITER . $LeadObj->level_name . $DELIMITER . $LeadObj->email1 . $DELIMITER . $Address  . $DELIMITER . $LeadObj->primary_address_city_desc . $DELIMITER . $LeadObj->gender . $DELIMITER . $VendorObj->name . $DELIMITER . $_REQUEST['call_status'] . "\n";
            unset($LeadObj);
        }
    } else {
        $FileContent = "Data Not Found";
    }

    if (!is_dir("custom/tmp/lead/" . $_REQUEST['campaign_id'])) {
        mkdir("custom/tmp/lead/" . $_REQUEST['campaign_id'], 0777);
    }
    $DirectoryName = "custom/tmp/lead/" . $_REQUEST['campaign_id'];
    $FileName = $DirectoryName . "/" . $VendorObj->name."_".$_REQUEST['call_status'] . ".csv";
    if(file_exists($FileName)){unlink($FileName);}
    $FileHandle = fopen($FileName, 'w') or die("can't open file");
    fwrite($FileHandle, $FileContent, '100000000');
    fclose($FileHandle);
    unset($FileContent);
    ///END CSV File Fun    
}

header("Location: index.php?module=Campaigns&return_module=Campaigns&action=StatusReport&FileLink=$FileName&vendor_id=$_REQUEST[vendor_id]&call_status=$_REQUEST[call_status]&record=$_REQUEST[record]");
?>