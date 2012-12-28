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
	'top_buttons' => array(
        array('widget_class' => 'SubPanelTopCreateButton'),
			array('widget_class' => 'SubPanelTopSelectButton', 'popup_module' => 'Users'),
	),

	'where' => '',
	
    'list_fields'=> array(
                 'first_name'=>array(
		 	'usage' => 'query_only',
		),
		'last_name'=>array(
		 	'usage' => 'query_only',
		),

		/*'full_name'=>array(
			'vname' => 'LBL_LIST_NAME',
			'widget_class' => 'SubPanelDetailViewLink',
		 	'module' => 'Users',
	 		'width' => '25%',
		),*/
		'user_name'=>array(
			'vname' => 'LBL_LIST_USER_NAME',
			'width' => '15%',
		),
               'user_profile_type'=>array(
			'vname' => 'LBL_LIST_USER_PROFILE_TYPE',
			'width' => '25%',
                        'usage' => 'query_only',
		),
                'user_profile_type_name'=>array(
                        'name'=>'user_profile_type_id',
			'vname' => 'LBL_LIST_USER_PROFILE_TYPE',
			'width' => '25%',
                        'sortable'=>false,
		),
               'usertype_id'=>array(
                        'name'=>'usertype_id',
                       	'vname' => 'LBL_RESPONSIBILITY_SCOPE',
                        'width' => '25%',
                        'usage' => 'query_only',
		),
                'usertype_name'=>array(
                        'name'=>'usertype_name',
			'vname' => 'LBL_RESPONSIBILITY_SCOPE',
                        'width' => '25%',
                        'sortable'=>false,
		),
               /*'email1'=>array(
			'vname' => 'LBL_LIST_EMAIL',
			'width' => '25%',
		),
		'phone_work'=>array (
			'vname' => 'LBL_LIST_PHONE',
			'width' => '21%',
		),*/
		'remove_button'=>array(
			'widget_class' => 'SubPanelRemoveButton',
		 	'module' => 'Users',
			'width' => '4%',
			'linked_field' => 'users',
			'refresh_page'=>true,			
		),
              /*  'edit_button'=>array(
			'widget_class' => 'SubPanelEditButton',
		 	'module' => 'Users',
			'width' => '4%',
			'linked_field' => 'users',
			'refresh_page'=>true,			
		),*/

	),
);
?>