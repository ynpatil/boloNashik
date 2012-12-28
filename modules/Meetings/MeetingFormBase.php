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
 * $Id: MeetingFormBase.php,v 1.54 2006/08/26 20:21:05 jenny Exp $
 * Description:  Base Form For Meetings
 * Portions created by SugarCRM are Copyright(C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 * ****************************************************************************** */

class MeetingFormBase {

    function getFormBody($prefix, $mod = '', $formname = '') {
        if (!ACLController::checkAccess('Meetings', 'edit', true)) {
            return '';
        }
        require_once('include/time.php');
        global $mod_strings;
        $temp_strings = $mod_strings;
        if (!empty($mod)) {
            global $current_language;
            $mod_strings = return_module_language($current_language, $mod);
        }
        global $app_strings;
        global $app_list_strings;
        global $current_user;
        global $theme;
        // Unimplemented until jscalendar language files are fixed
        // global $current_language;
        // global $default_language;
        // global $cal_codes;

        global $timedate;
        $cal_lang = "en";
        $cal_dateformat = $timedate->get_cal_date_format();

        $lbl_required_symbol = $app_strings['LBL_REQUIRED_SYMBOL'];
        $lbl_date = $mod_strings['LBL_DATE'];
        $lbl_time = $mod_strings['LBL_TIME'];
        $ntc_date_format = $timedate->get_user_date_format();
        $ntc_time_format = '(' . $timedate->get_user_time_format() . ')';

        $user_id = $current_user->id;
        $default_status = $app_list_strings['meeting_status_default'];
        $default_parent_type = $app_list_strings['record_type_default_key'];
        $default_date_start = $timedate->to_display_date(date('Y-m-d'), false);
        $default_time_start = $timedate->to_display_time((date('H:i')), true, false);
        $time_ampm = $timedate->AMPMMenu($prefix, date('H:i'));
        // Unimplemented until jscalendar language files are fixed
        // $cal_lang =(empty($cal_codes[$current_language])) ? $cal_codes[$default_language] : $cal_codes[$current_language];

        $form = <<<EOF
					<input type="hidden" name="${prefix}record" value="">
					<input type="hidden" name="${prefix}status" value="${default_status}">
					<input type="hidden" name="${prefix}parent_type" value="${default_parent_type}">
					<input type="hidden" name="${prefix}assigned_user_id" value='${user_id}'>
					<input type="hidden" name="${prefix}duration_hours" value="1">
					<input type="hidden" name="${prefix}duration_minutes" value="00">
					<p>$lbl_subject<span class="required">$lbl_required_symbol</span><br>
					<input name='${prefix}name' size='25' maxlength='255' type="text"><br>
					$lbl_date&nbsp;<span class="required">$lbl_required_symbol</span>&nbsp;<span class="dateFormat">$ntc_date_format</span><br>
					<input name='${prefix}date_start' id='jscal_field' onblur="parseDate(this, '$cal_dateformat');" type="text" maxlength="10" value="${default_date_start}"> <img src="themes/$theme/images/jscalendar.gif" alt="{$app_strings['LBL_ENTER_DATE']}"  id="jscal_trigger" align="absmiddle"><br>
					$lbl_time&nbsp;<span class="required">$lbl_required_symbol</span>&nbsp;<span class="dateFormat">$ntc_time_format</span><br>
					<input name='${prefix}time_start' type="text" maxlength='5' value="${default_time_start}">{$time_ampm}</p>
					<script type="text/javascript">
					Calendar.setup({
						inputField : "jscal_field", ifFormat : "$cal_dateformat", showsTime : false, button : "jscal_trigger", singleClick : true, step : 1
					});
					</script>
EOF;
        require_once('include/javascript/javascript.php');
        require_once('modules/Meetings/Meeting.php');
        $javascript = new javascript();
        $javascript->setFormName($formname);
        $javascript->setSugarBean(new Meeting());
        $javascript->addRequiredFields($prefix);
        $form .=$javascript->getScript();
        $mod_strings = $temp_strings;
        return $form;
    }

    function getForm($prefix, $mod = 'Meetings') {
        if (!ACLController::checkAccess('Meetings', 'edit', true)) {
            return '';
        }
        if (!empty($mod)) {
            global $current_language;
            $mod_strings = return_module_language($current_language, $mod);
        }else
            global $mod_strings;
        global $app_strings;
        global $app_list_strings;
        $lbl_save_button_title = $app_strings['LBL_SAVE_BUTTON_TITLE'];
        $lbl_save_button_key = $app_strings['LBL_SAVE_BUTTON_KEY'];
        $lbl_save_button_label = $app_strings['LBL_SAVE_BUTTON_LABEL'];


        $the_form = get_left_form_header($mod_strings['LBL_NEW_FORM_TITLE']);
        $the_form .= <<<EOQ


		<form name="${prefix}MeetingSave" onSubmit="return check_form('${prefix}MeetingSave')" method="POST" action="index.php">
			<input type="hidden" name="${prefix}module" value="Meetings">

			<input type="hidden" name="${prefix}action" value="Save">

EOQ;
        $the_form .= $this->getFormBody($prefix, 'Meetings', "${prefix}MeetingSave");
        $the_form .= <<<EOQ
		<p><input title="$lbl_save_button_title" accessKey="$lbl_save_button_key" class="button" type="submit" name="button" value="  $lbl_save_button_label  " ></p>
		</form>
EOQ;

        $the_form .= get_left_form_footer();
        $the_form .= get_validate_record_js();

        return $the_form;
    }

    function handleSave($prefix, $redirect = true, $useRequired = false) {
        require_once('include/TimeDate.php');
        require_once('modules/Meetings/Meeting.php');

        require_once('include/formbase.php');
        global $current_user;

        $focus = new Meeting();
        if ($useRequired && !checkRequired($prefix, array_keys($focus->required_fields))) {
            return null;
        }
        if (isset($_POST['should_remind']) && $_POST['should_remind'] == '0') {
            $_POST['reminder_time'] = -1;
        }
        if (!isset($_POST['reminder_time'])) {
            $_POST['reminder_time'] = $current_user->getPreference('reminder_time');
            if (empty($_POST['reminder_time'])) {
                $_POST['reminder_time'] = -1;
            }
        }

        if (!empty($_POST[$prefix . 'time_hour_start']) && empty($_POST['time_start'])) {
            $_POST['time_start'] = $_POST[$prefix . 'time_hour_start'] . ":" . $_POST[$prefix . 'time_minute_start'];
        }

        if (!empty($_POST[$prefix . 'time_hour_exit']) && empty($_POST['time_exit'])) {
            $_POST['time_exit'] = $_POST[$prefix . 'time_hour_exit'] . ":" . $_POST[$prefix . 'time_minute_exit'];
        }

        if (!empty($_POST[$prefix . 'time_hour_in']) && empty($_POST['time_in'])) {
            $_POST['time_in'] = $_POST[$prefix . 'time_hour_in'] . ":" . $_POST[$prefix . 'time_minute_in'];
        }

        global $timedate;
        if (isset($_POST[$prefix . 'meridiem']) && !empty($_POST[$prefix . 'meridiem'])) {
            $_POST[$prefix . 'time_start'] = $timedate->merge_time_meridiem($_POST[$prefix . 'time_start'], $timedate->get_time_format(true), $_POST[$prefix . 'meridiem']);
            $_POST[$prefix . 'time_exit'] = $timedate->merge_time_meridiem($_POST[$prefix . 'time_exit'], $timedate->get_time_format(true), $_POST[$prefix . 'meridiem']);
            $_POST[$prefix . 'time_in'] = $timedate->merge_time_meridiem($_POST[$prefix . 'time_in'], $timedate->get_time_format(true), $_POST[$prefix . 'meridiem']);
        }

        $focus = populateFromPost($prefix, $focus);
//        $GLOBALS['log']->info("Time exit :".$_POST['time_exit']." vs ".$focus->time_exit);
        if (!ACLController::checkAccess($focus->module_dir, 'edit', $focus->isOwner($current_user->id))) {
            ACLController::displayNoAccess(true);
        }

//	if(!$focus->ACLAccess('Save')) {
//		ACLController::displayNoAccess(true);
//		sugar_cleanup(true);
//	}

        $old_users = array();

        ///////////////////////////////////////////////////////////////////////////
        ////	REMOVE INVITEE RELATIONSHIPS
        if (!empty($_POST['user_invitees'])) {
            $focus->load_relationship('users');
            // this query to preserve accept_status across deletes
            $q = 'SELECT mu.user_id, mu.accept_status FROM meetings_users mu WHERE mu.meeting_id = \'' . $focus->id . '\' AND mu.deleted = 0';
            $r = $focus->db->query($q);
            $acceptStatusUsers = array();
            while ($a = $focus->db->fetchByAssoc($r)) {
                $acceptStatusUsers[$a['user_id']] = $a['accept_status'];
                $old_users[$a['user_id']] = true;
            }
            $focus->users->delete($focus->id);
        }
        if (!empty($_POST['contact_invitees'])) {
            $focus->load_relationship('contacts');
            // this query to preserve accept_status across deletes
            $q = 'SELECT mc.contact_id, mc.accept_status FROM meetings_contacts mc WHERE mc.meeting_id = \'' . $focus->id . '\' AND mc.deleted = 0';
            $r = $focus->db->query($q);
            $acceptStatusContacts = array();
            while ($a = $focus->db->fetchByAssoc($r)) {
                $acceptStatusContacts[$a['contact_id']] = $a['accept_status'];
            }
            $focus->contacts->delete($focus->id);
        }
        ////	END REMOVE
        ///////////////////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////////////////
        ////	REBUILD INVITEE RELATIONSHIPS
        if (!empty($_POST['user_invitees'])) {
            $existing_users = array();
            $_POST['user_invitees'] = preg_replace('/\,$/', '', $_POST['user_invitees']);

            if (!empty($_POST['existing_invitees'])) {
                $existing_users = explode(",", $_POST['existing_invitees']);
            }
            $focus->users_arr = explode(",", $_POST['user_invitees']);
        }

        if (!empty($_POST['contact_invitees'])) {
            $_POST['contact_invitees'] = preg_replace('/\,$/', '', $_POST['contact_invitees']);
            $existing_contacts = array();
            if (!empty($_POST['existing_contact_invitees'])) {
                $existing_contacts = explode(",", $_POST['existing_contact_invitees']);
            }
            $focus->contacts_arr = explode(",", $_POST['contact_invitees']);
        }

        if (!empty($_POST['parent_id']) && $_POST['parent_type'] == 'Contacts') {
            $focus->contacts_arr[] = $_POST['parent_id'];
        }

        //$GLOBALS['log']->debug("Saved record with id of ".$return_id);
        $focus->save(true);
        $return_id = $focus->id;

        if ($_REQUEST['source_info'] == "MeetingRequest") {

            require_once("modules/Meetings/MeetingRequest.php");
            $focusRequest = new MeetingRequest();
            $focusRequest->retrieve($_REQUEST['source_info_id']);
            $focusRequest->deleted = 1;
            $focusRequest->save(FALSE);
        }

        if (!is_array($focus->users_arr)) {
            $focus->users_arr = array();
        }

        $focusGC = new Meeting();
//	$focusGC->copy($focus);
        $focusGC = populateFromPost($prefix, $focusGC);

        //_ppd($acceptStatus);
        foreach ($focus->users_arr as $user_id) {
            if (empty($user_id) || isset($existing_users[$user_id])) {
                continue;
            }

            /*
            if ($focus->assigned_user_id != $user_id && !isset($old_users[$user_id])) {
                $focusGC->assigned_user_id = $user_id;
                unset($focusGC->id);
                $focusGC->save(true);

                //if(!isset($focus->group_calls))
                $focus->load_relationship('group_meetings');
                $focus->group_meetings->add($focusGC->id);
            }
            */

            $focus->load_relationship('users');
            $focus->users->add($user_id);
            // update query to preserve accept_status
            if (isset($acceptStatusUsers[$user_id]) && !empty($acceptStatusUsers[$user_id])) {
                $qU = 'UPDATE meetings_users SET accept_status = \'' . $acceptStatusUsers[$user_id] . '\' ';
                $qU .= 'WHERE deleted = 0 ';
                $qU .= 'AND meeting_id = \'' . $focus->id . '\' ';
                $qU .= 'AND user_id = \'' . $user_id . '\'';
                $focus->db->query($qU);
            }
        }

        if (!is_array($focus->contacts_arr)) {
            $focus->contacts_arr = array();
        }

        foreach ($focus->contacts_arr as $contact_id) {
            if (empty($contact_id) || isset($existing_contacts[$contact_id])) {
                continue;
            }
            if (!is_array($focus->contacts)) {
                $focus->load_relationship('contacts');
            }
            $focus->contacts->add($contact_id);
            // update query to preserve accept_status
            if (isset($acceptStatusContacts[$contact_id]) && !empty($acceptStatusContacts[$contact_id])) {
                $qU = 'UPDATE meetings_contacts SET accept_status = \'' . $acceptStatusContacts[$contact_id] . '\' ';
                $qU .= 'WHERE deleted = 0 ';
                $qU .= 'AND meeting_id = \'' . $focus->id . '\' ';
                $qU .= 'AND contact_id = \'' . $contact_id . '\'';
                $focus->db->query($qU);
            }
        }

        // set organizer to auto-accept
        $focus->set_accept_status($current_user, 'accept');
        ////	END REBUILD INVITEE RELATIONSHIPS
        ///////////////////////////////////////////////////////////////////////////
        ////	END REBUILD INVITEE RELATIONSHIPS
        ///////////////////////////////////////////////////////////////////////////
        $GLOBALS['log']->debug(">>>>>>>>>>>>>>>>Feedback start ");
        //Purpose : providing feedback option for contact persons of meeting
        //[insert contact_id,parent_id(meeting_id),parent_type(Meetings) into feedback_mast table ]
        /*
          $feedback_option_status=checkFeedbackOptionEnabledForUserBranch($current_user);
          if($feedback_option_status) {
          include_once 'modules/Feedback/Feedback.php';
          if($focus->status=='Held') {
          $focus->load_relationship("meetings_feedback");
          $list= $focus->meetings_feedback->get();
          if(count($list) == 0) {
          $ContactObjArray=$focus->get_contacts();
          if($ContactObjArray) {
          foreach ($ContactObjArray as $contact) {
          if($contact->email_opt_out!='on' && $contact->invalid_email!='1') {
          $feedback=new Feedback();
          //                                $feedback = populateFromPost($prefix, $feedback);
          $feedback->contact_id=$contact->id;
          $feedback->save(true);
          $focus->meetings_feedback->add($feedback->id);
          }
          }
          }
          }

          }
          }
          $GLOBALS['log']->debug(">>>>>>>>>>>>>>>Feedback End ");
         */
        //// ASSOC ACTIVITY RECORING
        if (!empty($_REQUEST['isassoc_activity']))
            $focus->saveAssociatedActivity($_REQUEST['followup_for_id']);

        if ($redirect) {
            handleRedirect($return_id, 'Meetings');
        } else {
            return $focus;
        }
    }

}

?>
