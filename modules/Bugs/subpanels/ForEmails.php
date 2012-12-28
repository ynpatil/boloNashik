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

// $Id: ForEmails.php,v 1.4 2006/08/22 23:38:45 majed Exp $
$subpanel_layout = array(
	'top_buttons' => array(
		array('widget_class' => 'SubPanelTopSelectButton', 'popup_module' => 'Bugs'),
	),

	'where' => '',
	
	

	'list_fields' => array(
		'bug_number'=>array(
	 		'vname' => 'LBL_LIST_NUMBER',
	 		'width' => '5%',
		),
		
		'name'=>array(
	 		'vname' => 'LBL_LIST_SUBJECT',
			'widget_class' => 'SubPanelDetailViewLink',
	 		'width' => '50%',
		),
		'status'=>array(
	 		'vname' => 'LBL_LIST_STATUS',
	 		'width' => '15%',
		),
		'type'=>array(
	 		'vname' => 'LBL_LIST_TYPE',
	 		'width' => '15%',
		),
		'priority'=>array(
	 		'vname' => 'LBL_LIST_PRIORITY',
	 		'width' => '11%',
		),
		'edit_button'=>array(
			'widget_class' => 'SubPanelEditButton',
		 	'module' => 'Bugs',
	 		'width' => '4%',
		),
		'remove_button'=>array(
			'widget_class' => 'SubPanelRemoveButton',
		 	'module' => 'Bugs',
			'width' => '5%',
		),
	),
);

?>
