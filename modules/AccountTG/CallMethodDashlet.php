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


/**
 * Used to call a generic method in a dashlet
 */
global $current_user;

$GLOBALS['log']->debug("In AccountTG.CallMethodDashlet");

if(!empty($_REQUEST['id'])) {
    $id = $_REQUEST['id'];
    $requestedMethod = $_REQUEST['method'];
    $dashletDefs = $current_user->getPreference('dashlets', 'accounttg'); // load user's dashlets config
    if(!empty($dashletDefs[$id])) {
        require_once($dashletDefs[$id]['fileLocation']);

        $dashlet = new $dashletDefs[$id]['className']($id,$REQUEST['record'], (isset($dashletDefs[$id]['options']) ? $dashletDefs[$id]['options'] : array()),$_REQUEST['record'],$_REQUEST['return_module']);

        if(method_exists($dashlet, $requestedMethod)) {
            $GLOBALS['log']->debug("Method found :".$requestedMethod);
            echo $dashlet->$requestedMethod();

        }
        else {
            echo 'no method';
        }
    }
}

?>
