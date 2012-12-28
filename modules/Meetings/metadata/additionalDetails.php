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

function additionalDetailsMeeting($fields) {
	static $mod_strings;
	if(empty($mod_strings)) {
		global $current_language;
		$mod_strings = return_module_language($current_language, 'Meetings');
	}
		
	$overlib_string = '';
	
	if(!empty($fields['DATE_START']) && !empty($fields['TIME_START'])) 
		$overlib_string .= '<b>'. $mod_strings['LBL_DATE_TIME'] . '</b> ' . $fields['DATE_START'] . ' ' . $fields['TIME_START'] . '<br>';
	if(isset($fields['DURATION_HOURS']) && isset($fields['DURATION_MINUTES']))
		$overlib_string .= '<b>'. $mod_strings['LBL_DURATION'] . '</b> ' . $fields['DURATION_HOURS'] . $mod_strings['LBL_HOURS_ABBREV'] 
								. ' ' . $fields['DURATION_MINUTES'] . $mod_strings['LBL_MINSS_ABBREV'] . '<br>';
	
	if(!empty($fields['DESCRIPTION'])) { 
		$overlib_string .= '<b>'. $mod_strings['LBL_DESCRIPTION'] . '</b> ' . substr($fields['DESCRIPTION'], 0, 300);
		if(strlen($fields['DESCRIPTION']) > 300) $overlib_string .= '...';
		$overlib_string .= '<br>';
	}
	
	$editLink = "index.php?action=EditView&module=Meetings&record={$fields['ID']}"; 
	$viewLink = "index.php?action=DetailView&module=Meetings&record={$fields['ID']}";	

	$return_module = empty($_REQUEST['module']) ? 'Meetings' : $_REQUEST['module'];
	$return_action = empty($_REQUEST['action']) ? 'ListView' : $_REQUEST['action'];
	
	$editLink .= "&return_module=$return_module&return_action=$return_action";
	$viewLink .= "&return_module=$return_module&return_action=$return_action";
	
	return array('fieldToAddTo' => 'NAME', 
				 'string' => $overlib_string, 
				 'editLink' => $editLink, 
				 'viewLink' => $viewLink);
	
}

function additionalDetailsActivityReportMeeting($bean) {
	static $mod_strings;
	if(empty($mod_strings)) {
		global $current_language;
		$mod_strings = return_module_language($current_language, 'Meetings');
	}
	
	$overlib_string = '';
	$recipients = $bean->get_contacts();
		
	if(is_array($recipients)){	
		$overlib_string .= '<b>'. $mod_strings['LBL_CONTACTS_SUBPANEL_TITLE'] . '</b><br/> ';

		if(count($recipients)>0){		
			foreach($recipients as $recipient){
				$overlib_string .=  $recipient->name. '<br/>';
			}	
		}
		else{
			$overlib_string .= 'No contact selected<br/>';
		}
		
	}

	$recipients = $bean->get_meeting_users();
		
	if(is_array($recipients)){	
		$overlib_string .= '<b>'. $mod_strings['LBL_USERS_SUBPANEL_TITLE'] . '</b><br/> ';
		
		foreach($recipients as $recipient){
			$overlib_string .=  $recipient->name. '<br/>';
		}	
	}
			
	$editLink = "index.php?action=EditView&module=Calls&record={$bean->id}"; 
	$viewLink = "index.php?action=DetailView&module=Calls&record={$bean->id}";	

	$return_module = empty($_REQUEST['module']) ? 'Calls' : $_REQUEST['module'];
	$return_action = empty($_REQUEST['action']) ? 'ListView' : $_REQUEST['action'];
	
	$editLink .= "&return_module=$return_module&return_action=$return_action";
	$viewLink .= "&return_module=$return_module&return_action=$return_action";
	
	return array('fieldToAddTo' => 'NAME', 
				 'string' => $overlib_string, 
				 'editLink' => $editLink, 
				 'viewLink' => $viewLink);
}

function additionalDetailsMeetingFeedback($FeedbackObjArray) {
    static $mod_strings;

    if(empty($mod_strings)) {
        global $current_language;
        $mod_strings = return_module_language($current_language, 'Feedback');
    }

    $overlib_string = '';

    //$GLOBALS['log']->debug("Feedback Obj array".print_r($FeedbackObjArray,true));
    if($FeedbackObjArray) {
        foreach ($FeedbackObjArray as $FeedbackObj) {
            $FeedbackObj->fill_in_additional_detail_fields();
            if(empty($FeedbackObj->rating)) {
                $FeedbackObj->rating=0;
            }
            if(empty($FeedbackObj->comments)) {
                $FeedbackObj->comments='--';
            }
             $rating='';
            $user_rating=$FeedbackObj->rating;
            for($i=1;$i<=5;$i++) {
                if($user_rating>0) {
                    $rating.="<img src='./themes/Sugar/images/redstar.gif' style='width:10px; height:10px;'/>";
                    $user_rating--;
                }else {
                    $rating.="<img src='./themes/Sugar/images/greystar.gif' style='width:10px; height:10px; '/>";
                }
            }

            $overlib_string .= '<b>'. $mod_strings['LBL_CONTACT_NAME'] . '</b> ' . $FeedbackObj->contact_name . '<br>';
            $overlib_string .= '<b>'. $mod_strings['LBL_RATING'] . '</b> ' . $rating . '<br>';


            $overlib_string .= '<b>'. $mod_strings['LBL_FEEDBACK'] . '</b> ' . substr($FeedbackObj->comments, 0, 300);
            if(strlen($FeedbackObj->comments) > 300) $overlib_string .= '...';
            $overlib_string .= '<br>';

            $overlib_string .= '<hr>';
        }
    }

    //$editLink = "index.php?action=EditView&module=Feedback&record={$fields['ID']}";
    // $viewLink = "index.php?action=DetailView&module=Feedback&record={$fields['ID']}";

    $return_module = empty($_REQUEST['module']) ? 'Feedback' : $_REQUEST['module'];
    $return_action = empty($_REQUEST['action']) ? 'ListView' : $_REQUEST['action'];

    // $editLink .= "&return_module=$return_module&return_action=$return_action";
    //$viewLink .= "&return_module=$return_module&return_action=$return_action";

    return array('fieldToAddTo' => 'NAME',
            'string' => $overlib_string,
            'editLink' => $editLink,
            'viewLink' => $viewLink);

}

 ?>
 
 
