<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * Layout definition for Users
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

// $Id: layout_defs.php,v 1.11 2006/06/06 17:58:54 majed Exp $

$layout_defs['UserMyTeam'] = array(
	// sets up which panels to show, in which order, and with what linked_fields
	'subpanel_setup' => array(

        'user_myteam' => array(
			'top_buttons' => array(),
			'order' => 10,
			'module' => 'Users',
			'sort_order' => 'asc',
			'sort_by' => 'full_name',
			'subpanel_name' => 'default_myteam',
			'get_subpanel_data' => 'user_myteam',
			'title_key' => 'LBL_MYTEAM_SUBPANEL_TITLE',
		),
	),
);
$layout_defs['UserMyCoreTeam'] = array(
	// sets up which panels to show, in which order, and with what linked_fields
	'subpanel_setup' => array(

        'user_mycoreteam' => array(
			'top_buttons' => array(),
			'order' => 20,
			'module' => 'Users',
			'sort_order' => 'asc',
			'sort_by' => 'full_name',
			'subpanel_name' => 'default_mycoreteam',
			'get_subpanel_data' => 'user_mycoreteam',
			'title_key' => 'LBL_MYCORETEAM_SUBPANEL_TITLE',
		),
	),
);
$layout_defs['UserRoles'] = array(
	// sets up which panels to show, in which order, and with what linked_fields
	'subpanel_setup' => array(
        'aclroles' => array(
			'top_buttons' => array(array('widget_class' => 'SubPanelTopSelectButton', 'popup_module' => 'ACLRoles', 'mode' => 'MultiSelect'),),
			'order' => 30,
			'sort_by' => 'name',
			'sort_order' => 'asc',
			'module' => 'ACLRoles',
			'refresh_page'=>1,
			'subpanel_name' => 'default',
			'get_subpanel_data' => 'aclroles',
			'add_subpanel_data' => 'role_id',
			'title_key' => 'LBL_ROLES_SUBPANEL_TITLE',
		),
	),
);

$layout_defs['UserReportsTo'] = array(
	// sets up which panels to show, in which order, and with what linked_fields
	'subpanel_setup' => array(

        'user_reportsto' => array(
			'top_buttons' => array(array('widget_class' => 'SubPanelTopSelectButton', 'popup_module' => 'Users', 'mode' => 'MultiSelect'),),
			'order' => 40,
			'module' => 'Users',
			'sort_order' => 'asc',
			'sort_by' => 'full_name',
			'subpanel_name' => 'default',
			'get_subpanel_data' => 'user_reportsto',
			'title_key' => 'LBL_REPORTSTO_SUBPANEL_TITLE',
			'refresh_page'=>1,
		),
	),
);

global $current_user;
if(is_admin($current_user)){
	$layout_defs['Users']['subpanel_setup']['aclroles']['subpanel_name'] = 'admin';
	$layout_defs['UserRoles']['subpanel_setup']['aclroles']['subpanel_name'] = 'admin';
	$layout_defs['UserReportsTo']['subpanel_setup']['user_reportsto']['subpanel_name'] = 'default';	
}else{

	$layout_defs['Users']['subpanel_setup']['aclroles']['top_buttons'] = array();
	$layout_defs['UserRoles']['subpanel_setup']['aclroles']['top_buttons'] = array();
	$layout_defs['UserReportsTo']['subpanel_setup']['user_reportsto']['subpanel_name'] = 'default_noedit';
	$layout_defs['UserReportsTo']['subpanel_setup']['user_reportsto']['top_buttons'] = array();
	$layout_defs['UserMyTeam']['subpanel_setup']['user_myteam']['subpanel_name'] = 'default_noedit';		
	$layout_defs['UserMyCoreTeam']['subpanel_setup']['user_mycoreteam']['subpanel_name'] = 'default_noedit';
}
?>
