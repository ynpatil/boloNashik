<?php
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
 * Portions created by SugarCRM are Copyright (C) 2004 SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/
/*********************************************************************************
 * $Id: Save.php,v 1.8 2005/03/17 00:48:01 majed Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('modules/SubofficeMaster/Suboffice.php');
require_once('include/formbase.php');
if($_POST['sub_office_id']) {

    $focus = new Suboffice();
    $focus->retrieve($_POST['sub_office_id']);
    $focus->latitude = $_POST[latitude];
    $focus->longitude = $_POST[longitude];
    $focus->save(true);
    
//    $query = "SELECT id,name FROM suboffice_mast WHERE id='".$_POST['sub_office_name']."' AND deleted=0";
//    $result = $GLOBALS['db']->query($query, false, "Error retrieving user ID: ");
//    $row = $GLOBALS['db']->fetchByAssoc($result);
//
//
//    $query = "DELETE suboffice_location WHERE id='".$_POST['sub_office_name']."'";
//    $result = $GLOBALS['db']->query($query, false, "Error retrieving user ID: ");
//    $GLOBALS['db']->fetchByAssoc($result);
//
//    if($row['id'] && $row['name']) {
//        $query = "INSERT INTO suboffice_location SET
//                id='".$row['id']."',
//                name='".$row['name']."',
//                latitude='".$_POST[latitude]."',
//                longitude='".$_POST[longitude]."'";
//        $result = $GLOBALS['db']->query($query, false, "Error retrieving user ID: ");
//        $GLOBALS['db']->fetchByAssoc($result);
//    }
}

$redirect = "index.php?action=location&module=Users";
$_SESSION['sub_office massages']="You have save record successfully!";
header("Location: {$redirect}");
?>
