<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Campaigns/Campaign.php');
require_once('modules/Campaigns/Forms.php');
require_once('include/DetailView/DetailView.php');
require_once('modules/Campaigns/GenerateVendorsDataFile.php');

global $mod_strings;
global $app_strings;
global $app_list_strings;
global $sugar_version, $sugar_config;

$CampaignObj = new Campaign();

$detailView = new DetailView();
$offset = 0;
$offset = 0;
if (isset($_REQUEST['offset']) or isset($_REQUEST['record'])) {
    
} else {
    header("Location: index.php?module=Accounts&action=index");
}

if ($_REQUEST['record']) {


    $CampaignObj->retrieve($_REQUEST['record']);
    $CampaignObj->vendor_file_status = 1; // file is 
    $CampaignObj->save();
    
}
if($CampaignObj->vendor_file_status==1){
    header("Location: index.php?module=Campaigns&action=DetailView&vendor_file_status=2&record=$_REQUEST[record]");
} else {
    header("Location: index.php?module=Campaigns&action=DetailView&record=$_REQUEST[record]");
}
?>