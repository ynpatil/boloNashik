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
//om
require_once("include/Dashlets/Dashlet.php");
require_once("modules/AccountMktInfo/AccountMktInfo.php");

class AccountMktInfoDashlet extends Dashlet{

	function inherit()
	{
		$focus = new AccountMktInfo();
		$this->savedText = $focus->inherit($this->key,$this->column,$this->parent_type);
	}

    function getTitle($text) {
        global $image_path, $app_strings, $sugar_config;
		global $currentModule;
		$GLOBALS['log']->debug("Current module :".$currentModule);

        if($this->isConfigurable)
            $additionalTitle = '<table width="100%" cellspacing="0" cellpadding="0" border="0"><tr><td width="99%">' . $text
                               . '</td><td nowrap width="1%"><div style="width: 100%;text-align:right"><a href="#" onclick="SUGAR.accountObjective.configureDashlet(\''
                               . $this->id . '\',\''.$this->key.'\',\''.$currentModule.'\'); return false;" class="chartToolsLink">'
                               . get_image($image_path.'edit','title="Edit Dashlet" alt="Edit Dashlet"  border="0"  align="absmiddle"').'</a> '
                               . '';
        else
            $additionalTitle = '<table width="100%" cellspacing="0" cellpadding="0" border="0"><tr><td width="99%">' . $text
                   . '</td><td nowrap width="1%"><div style="width: 100%;text-align:right">';

        if($this->isRefreshable)
            $additionalTitle .= '<a href="#" onclick="SUGAR.accountObjective.retrieveDashlet(\''
                                . $this->id . '\',null,null,null,\''.$this->key.'\',\''.$currentModule.'\'); return false;"><img width="13" height="13" border="0" align="absmiddle" title="Refresh Dashlet" alt="Refresh Dashlet" src="'
                                . $image_path . 'refresh.gif"/></a> ';

		if($this->owner)
        $additionalTitle .= '<a href="#" onclick="SUGAR.accountObjective.inheritDashlet(\''
                            . $this->id . '\',\''.$this->key.'\',\''.$this->parent_type.'\',\''.$currentModule.'\'); return false;"><img width="13" height="13" border="0" align="absmiddle" title="Inherit" alt="Inherit" src="'
                            . $image_path . 'inherit_dashboard.gif"/></a></div></td></tr></table>';
		else
        $additionalTitle .= '<a href="#" onclick="alert(\'You are not authorized to perform this action\'); return false;">'
        					.'<img width="13" height="13" border="0" align="absmiddle" title="Inherit" alt="Inherit" src="'
                            . $image_path . 'inherit_dashboard.gif"/></a></div></td></tr></table>';
        if(!function_exists('get_form_header')) {
            global $theme;
            require_once('themes/'.$theme.'/layout_utils.php');
        }

        $str = '<div ';
        if(empty($sugar_config['lock_homepage']) || $sugar_config['lock_homepage'] == false) $str .= ' onmouseover="this.style.cursor = \'move\';"';
        $str .= 'id="dashlet_header_' . $this->id . '">' . get_form_header($this->title, $additionalTitle, false) . '</div>';

        return $str;
    }

    /**
     * called to filter out $_REQUEST object when the user submits the configure dropdown
     *
     * @param array $req $_REQUEST
     * @return array filtered options to save
     */
    function saveOptions($req) {
        global $sugar_config, $timedate, $current_user, $theme;
        $options = array();
        $options['title'] = $_REQUEST['title'];
        if(is_numeric($_REQUEST['height'])) {
            if($_REQUEST['height'] > 0 && $_REQUEST['height'] <= 300) $options['height'] = $_REQUEST['height'];
            elseif($_REQUEST['height'] > 300) $options['height'] = '300';
            else $options['height'] = '100';
        }

//        $options['savedText'] = br2nl($this->savedText);
        //$options['savedText'] = $this->savedText;

        return $options;
    }

    /**
     * Used to save text on textarea blur. Accessed via Home/CallMethodDashlet.php
     * This is an example of how to to call a custom method via ajax
     */
    function saveText() {
		$column = $this->column;

		$storedText = $_REQUEST['savedText'];

		if(empty($storedText))
		$storedText = " ";

        if(isset($_REQUEST['record'])) {
			$focus = $this->retrieve($_REQUEST['record']);
			$focus->id = $_REQUEST['record'];
			$focus->parent_type = $_REQUEST['return_module'];
			$focus->$column = $storedText;
			$focus->save($GLOBALS['check_notify']);
			$GLOBALS['log']->debug("Saved object ".$focus->id);
        }

        $json = getJSONobj();
        $storedText = $focus->$column;
        //if(!isset($storedText))
        //$storedText = " ";

        $output = 'result = ' . $json->encode(array('id' => $_REQUEST['id'],
                                       'savedText' => $storedText));

		$GLOBALS['log']->debug("Output :".$output);

		echo $output;
    }
}
?>
