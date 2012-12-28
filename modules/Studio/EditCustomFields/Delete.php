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
// $Id: Delete.php,v 1.1 2006/08/29 04:37:45 majed Exp $

require_once('modules/DynamicFields/DynamicField.php');



if(!empty($_REQUEST['record']))
{
	DynamicField::dropFieldById($_REQUEST['record']);	
}

$return_module = empty($_REQUEST['return_module']) ? 'Studio'
	: $_REQUEST['return_module'];

$return_action = empty($_REQUEST['return_action']) ? 'wizard'
	: $_REQUEST['return_action'];

$return_module_select = empty($_REQUEST['module_name']) ? 0
	: '&wizard=EditCustomFieldsWizard&option=ViewCustomFields';

header("Location: index.php?action=$return_action&module=$return_module$return_module_select");


?>
