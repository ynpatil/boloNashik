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
 * $Id: Save.php,v 1.25 2006/06/06 17:57:54 majed Exp $
 * Description:  Saves an Brand record and then redirects the browser to the
 * defined return URL.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 * ****************************************************************************** */

//require_once('modules/Brands/BrandFormBase.php');
//$brandForm = new BrandFormBase();
//$brandForm->handleSave('', true, false);
require_once('modules/Brands/Brand.php');
require_once ('include/utils.php');
require_once('include/formbase.php');

$GLOBALS['log']->debug("Saved record with id of Faq Save");
 
 $focus = new Brand();

if ($useRequired && !checkRequired($prefix, array_keys($focus->required_fields))) {
    return null;
}
$focus = populateFromPost($prefix, $focus);
if (isset($GLOBALS['check_notify'])) {
    $check_notify = $GLOBALS['check_notify'];
} else {
    $check_notify = FALSE;
}
if (!$focus->ACLAccess('Save')) {
    ACLController::displayNoAccess(true);
    sugar_cleanup(true);
}
$GLOBALS['log']->debug("Saved record ::FAQ : Request=>".print_r($_REQUEST,true));
$focus->faq = ($_REQUEST['faq']);
$focus->id = $_REQUEST['record'];
if($focus->id){
    $focus->save($check_notify);
}
header("LOCATION:index.php?record=$focus->id&module=Brands&action=FaqEditView&return_module=Brands&return_action=DetailView");
?>