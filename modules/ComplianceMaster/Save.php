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

require_once('modules/ComplianceMaster/Compliance.php');
require_once('include/formbase.php');

$sugarbean = new Compliance();
$sugarbean->delete_all();

global $current_user;
global $app_list_strings;

$parent_types = $app_list_strings['record_type_module'];
$GLOBALS['log']->debug("In Save.php ".implode(",",$parent_types));

foreach($parent_types as $entity1){

    foreach($parent_types as $entity2){
        if(!empty($_POST[$entity1."-".$entity2])){
            $GLOBALS['log']->debug("Set ".$entity1."-".$entity2);
            $sugarbean = new Compliance();
            $sugarbean->created_by = $current_user;
//            $sugarbean->modified_user_id = $current_user;
            $sugarbean->branch_id = "877e0090-19cd-7d9b-00a8-44a803f43d5e";
            $sugarbean->entity1 = $entity1;
            $sugarbean->entity2 = $entity2;
            $sugarbean->benchmark = $_POST[$entity1."-".$entity2];
//            $sugarbean->assigned_user_id = $current_user;
            $sugarbean->save(FALSE);
        }
        else{
            $GLOBALS['log']->debug("Not set ".$entity1."-".$entity2);
        }
    }
}

header("Location: index.php?action=ListView&module=ComplianceMaster");
?>
