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
require_once('include/Dashlets/AccountTGDashlet.php');
require_once('include/Sugar_Smarty.php');

require_once('modules/AccountTG/AccountTG.php');

class MediaHabitsDashlet extends AccountTGDashlet {
    var $savedText; // users's saved text
    var $height = '100'; // height of the pad
	var $column = 'media_habits';
	var $key;
	var $parent_type;
	var $parent_desc;
	var $owner;

    /**
     * Constructor
     *
     * @global string current language
     * @param guid $id id for the current dashlet (assigned from Home module)
     * @param array $def options saved for this dashlet
     */
    function MediaHabitsDashlet($id,$def,$key=NULL,$parent_type) {
        $this->loadLanguage('MediaHabitsDashlet','modules/AccountTG/Dashlets/'); // load the language strings here

		$this->parent_type = $parent_type;

		if(isset($key))
		{
			$this->key = $key;
			$focus = $this->retrieve($key);
			$column = $this->column;

        	if(!empty($focus))
        	    $this->savedText = $focus->$column;
        	else
        	    $this->savedText = $this->dashletStrings['LBL_DEFAULT_TEXT'];
		}
		else
		$GLOBALS['log']->debug("KEY NOT SET");

		//$GLOBALS['log']->debug("Height SET ".$def['height']);
        if(!empty($def['height'])) // set a default height if none is set
            $this->height = $def['height'];

        parent::AccountTGDashlet($id,$parent_type); // call parent constructor

        $this->isConfigurable = true; // dashlet is configurable
        $this->hasScript = true;  // dashlet has javascript attached to it

        // if no custom title, use default
        if(empty($def['title'])) $this->title = $this->dashletStrings['LBL_TITLE'];
        else $this->title = $def['title'];
    }

	function retrieve($key)
	{
		$focus = new AccountTG();
		$column = $this->column;
		$requestMethod = "getSpecificData";//"retrieve".$column;
		$focus->$requestMethod($key,$column);
		global $current_user;

		//$GLOBALS['log']->debug("Parent type :".$this->parent_type);

		$focus->parent_type = $this->parent_type;
		$this->owner = $focus->isOwner($current_user->id);
		//$GLOBALS['log']->debug("OWNER set :".$focus->isOwner($current_user->id));
		return $focus;
    }

    /**
     * Displays the dashlet
     *
     * @return string html to display dashlet
     */
    function display() {
        $ss = new Sugar_Smarty();
        $ss->assign('savedText', $this->savedText);
        $ss->assign('saving', $this->dashletStrings['LBL_SAVING']);
        $ss->assign('saved', $this->dashletStrings['LBL_SAVED']);
        $ss->assign('id', $this->id);
        $ss->assign('column', $this->column);
        $ss->assign('height', $this->height);
		$ss->assign('record', $this->key);

        $str = $ss->fetch('modules/AccountTG/Dashlets/MediaHabitsDashlet/MediaHabitsDashlet.tpl');
        return parent::display($this->dashletStrings['LBL_DBLCLICK_HELP']) . $str; // return parent::display for title and such
    }

    /**
     * Displays the javascript for the dashlet
     *
     * @return string javascript to use with this dashlet
     */
    function displayScript() {
        $ss = new Sugar_Smarty();
        $ss->assign('saving', $this->dashletStrings['LBL_SAVING']);
        $ss->assign('saved', $this->dashletStrings['LBL_SAVED']);
        $ss->assign('id', $this->id);
		$ss->assign('column', $this->column);
		$ss->assign('record', $this->key);
		$ss->assign('owner', $this->owner);
		$GLOBALS['log']->debug("Is owner :".$this->owner);

        $str = $ss->fetch('modules/AccountTG/Dashlets/MediaHabitsDashlet/MediaHabitsDashletScript.tpl');
        //$GLOBALS['log']->debug("Script :".$str);

        return $str; // return parent::display for title and such
    }

    /**
     * Displays the configuration form for the dashlet
     *
     * @return string html to display form
     */
    function displayOptions() {
        global $app_strings;

        $ss = new Sugar_Smarty();
        $ss->assign('titleLbl', $this->dashletStrings['LBL_CONFIGURE_TITLE']);
        $ss->assign('heightLbl', $this->dashletStrings['LBL_CONFIGURE_HEIGHT']);
        $ss->assign('saveLbl', $app_strings['LBL_SAVE_BUTTON_LABEL']);
        $ss->assign('title', $this->title);
        $ss->assign('height', $this->height);
        $ss->assign('id', $this->id);
        $ss->assign('column', $this->column);
		$ss->assign('record', $this->key);

        return parent::displayOptions() . $ss->fetch('modules/AccountTG/Dashlets/MediaHabitsDashlet/MediaHabitsDashletOptions.tpl');
    }
}

?>
