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

require_once('include/EditView/QuickCreate.php');
require_once('modules/Comments/Comment.php');
require_once('include/javascript/javascript.php');

class CommentsQuickCreate extends QuickCreate {

    var $javascript;

    function process() {
        global $current_user, $timedate, $app_list_strings, $current_language, $mod_strings;
        $mod_strings = return_module_language($current_language, 'Comments');

        parent::process();

        $this->ss->assign("ASSIGNED_USER_ID", $current_user->id);

          if($this->viaAJAX) { // override for ajax call
            $this->ss->assign('saveOnclick', "onclick='if(check_form(\"commentsQuickCreate\")) return SUGAR.subpanelUtils.inlineSave(this.form.id, \"activities\"); else return false;'");
            $this->ss->assign('cancelOnclick', "onclick='return SUGAR.subpanelUtils.cancelCreate(\"subpanel_activities\")';");
        }

        $this->ss->assign('viaAJAX', $this->viaAJAX);

        $this->javascript = new javascript();
        $this->javascript->setFormName('commentsQuickCreate');

        $focus = new Comment();
        $this->javascript->setSugarBean($focus);
        $this->javascript->addAllFields('',$focus->skip_fields);

        $this->ss->assign('additionalScripts', $this->javascript->getScript(false));
    }
}
?>
