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
 * $Id: Save.php,v 1.20 2006/06/06 17:57:58 majed Exp $
 * Description:  Base Form For Notes
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 * ****************************************************************************** */
//om

require_once('include/formbase.php');
require_once('include/upload_file.php');

$prefix = '';
$do_final_move = 0;

$upload_file = new UploadFile('sold_file');

$do_final_move = 0;

//$_FILES['uploadfile']['name'] = $_REQUEST['escaped_document_name'];
if (isset($_FILES['sold_file']) && $upload_file->confirm_upload()) {
    set_time_limit(0);
    $upload_file->get_stored_file_name();
    $upload_file->final_move($_FILES['sold_file']['name']);
    
    $tmp_file_name = $sugar_config['upload_dir'] . "/" . $_FILES['sold_file']['name'];
    $destination_file_name = $sugar_config['upload_dir']."/ProductSold.csv";
    
    copy_uploaded_file($tmp_file_name,$destination_file_name);
//    $FileHandle = fopen($sugar_config['upload_dir']."/".$_FILES['sold_file']['name'], 'r') or die("can't open file");
//    $FileObj = fread($FileHandle,  '100000000');    
//    fclose($FileHandle);
//    $FileObj = explode ( "\n", $FileObj ); 
//    foreach ($FileObj as $key => $FileValue) {
//        $DataArr = explode ( ",", $FileValue); 
//        
//        $LeadId = getLeadIdByMobileNo($DataArr[0]);
//        $BrandId = getBrandIdByBrandName($DataArr[1]);
//        if($LeadId && $BrandId){
//            //inser data into lead_brand_sold  table
//            $current_date_time = date("Y-m-d H:i:s");
//            $lead_brand_sold_id = create_guid();
//            $query_lead_brand_sold = "INSERT INTO lead_brand_sold SET 
//                                        id = '$lead_brand_sold_id' 
//                                        ,lead_id = '$LeadId'
//                                        ,brand_id = '$BrandId'
//                                        ,created_by = '$current_user->id'
//                                        ,date_entered = '$current_date_time'
//                                        ,modified_user_id = '$current_user->id'
//                                        ,assigned_user_id = '$current_user->id'
//                                        ,date_modified = '$current_date_time'
//            ";            
//            $result_lead_brand_sold = $GLOBALS['db']->query($query_lead_brand_sold, true, "Error filling in lead_brand_sold array: ");
//        }
//    }    
    $file_massege = "upload file successfully, its processing for scheduler job";
} else {
     $file_massege = "ERROR: can't upload file";
    die("ERROR: can't upload file.");
}
header("LOCATION: index.php?module=Brands&action=ProductSold&step=1&return_module=Brands&return_action=index&msg=$file_massege")
//handleRedirect($return_id, "Brands");
?>