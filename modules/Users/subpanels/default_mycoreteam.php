<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * Subpanel Layout definition for Bugs
 *
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
 */

// $Id: default.php,v 1.6 2006/06/06 17:58:54 majed Exp $
$subpanel_layout = array(

	'where' => '',
	
    'list_fields'=> array(

        'first_name'=>array(
		 	'usage' => 'query_only',
		),
		'last_name'=>array(
		 	'usage' => 'query_only',
		),
		'full_name'=>array(
			'vname' => 'LBL_LIST_NAME',
			'widget_class' => 'SubPanelDetailViewLink',
		 	'module' => 'Users',
	 		'width' => '25%',
	 		'sortable' => false,
		),
		'user_name'=>array(
			'vname' => 'LBL_LIST_USER_NAME',
			'width' => '25%',
	 		'sortable' => false,			
		),
		'status'=>array('vname' => 'LBL_LIST_STATUS','width'=>'21%','sortable' => 'false',),
		'email1'=>array(
			'vname' => 'LBL_LIST_EMAIL',
			'width' => '25%',
	 		'sortable' => false,			
		),
		'phone_work'=>array (
			'vname' => 'LBL_LIST_PHONE',
			'width' => '21%',
	 		'sortable' => false,			
		),
	),
);

?>
