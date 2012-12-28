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

global $current_user, $app_strings, $mod_strings;

if(!empty($_REQUEST['id'])) {
    $id = $_REQUEST['id'];
    $dashletDefs = $current_user->getPreference('dashlets', 'home'); // load user's dashlets config
    require_once($dashletDefs[$id]['fileLocation']);

    $dashlet = new $dashletDefs[$id]['className']($id, (isset($dashletDefs[$id]['options']) ? $dashletDefs[$id]['options'] : array()));
    if(!empty($_REQUEST['configure']) && $_REQUEST['configure']) { // save settings
        $dashletDefs[$id]['options'] = $dashlet->saveOptions($_REQUEST);
        $current_user->setPreference('dashlets', $dashletDefs, 0, 'home');    
    } 
    else { // display options
        $json = getJSONobj();
        echo 'result = ' . $json->encode((array('header' => $dashlet->title . ' : ' . $mod_strings['LBL_OPTIONS'],
                                                 'body'  => $dashlet->displayOptions())));

    }
}
else {
    echo '0';
}

?>
