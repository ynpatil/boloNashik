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

class SugarWidgetSubPanelHtmlentities extends SugarWidgetReportField {

   function displayList($layout_def)
 {
       
      // echo "<pre>";print_r($layout_def);exit;
       $export_file_name = explode("|",$layout_def['fields'][strtoupper($layout_def['name'])]);

       //echo <pre>;print_r($export_file_name);exit;
       if(is_array($export_file_name)){
               if(is_file($export_file_name[1])){
                        return "$export_file_name[0] <a href='$export_file_name[1]' class='listViewTdLinkS1'>LogFile</a>";
               }else{
                   return $export_file_name[0];//"Log File is deleted by admin";
               }
        }else{
                return $layout_def['fields'][strtoupper($layout_def['name'])];
        }

	
 }
}
?>

