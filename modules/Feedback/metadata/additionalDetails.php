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
 *********************************************************************************/
// NOT USED
function additionalDetailsFeedback($FeedbackObjArray) {
    static $mod_strings;

    if(empty($mod_strings)) {
        global $current_language;
        $mod_strings = return_module_language($current_language, 'Feedback');
    }

    $overlib_string = '';

   $GLOBALS['log']->debug("Feedback Obj array".print_r($FeedbackObjArray,true));
    if($FeedbackObjArray) {
        foreach ($FeedbackObjArray as $FeedbackObj) {
            //if(isset($FeedbackObj->rating))
                $overlib_string .= '<b>'. $mod_strings['LBL_RATING'] . '</b> ' . $FeedbackObj->rating . '<br>';

            if(empty($FeedbackObj->comments)) {$FeedbackObj->comments="---";}
            
                $overlib_string .= '<b>'. $mod_strings['LBL_FEEDBACK'] . '</b> ' . substr($FeedbackObj->comments, 0, 300);
                if(strlen($FeedbackObj->comments) > 300) $overlib_string .= '...';
                $overlib_string .= '<br>';
            //}
           
        }
    }
$GLOBALS['log']->debug("Feedback String :".$overlib_string);
    //$editLink = "index.php?action=EditView&module=Feedback&record={$fields['ID']}";
    $viewLink = "index.php?action=DetailView&module=Feedback&record={$fields['ID']}";

    $return_module = empty($_REQUEST['module']) ? 'Feedback' : $_REQUEST['module'];
    $return_action = empty($_REQUEST['action']) ? 'ListView' : $_REQUEST['action'];

   // $editLink .= "&return_module=$return_module&return_action=$return_action";
    $viewLink .= "&return_module=$return_module&return_action=$return_action";

    return array('fieldToAddTo' => 'NAME',
            'string' => $overlib_string,
            'editLink' => $editLink,
            'viewLink' => $viewLink);

}


?>


