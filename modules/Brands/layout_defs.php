<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * Layout definition for Brands
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
// $Id: layout_defs.php,v 1.66 2006/07/12 00:27:59 awu Exp $

$layout_defs['Brands'] = array(
	// list of what Subpanels to show in the DetailView
	'subpanel_setup' => array(

		'activities' => array(
			'order' => 10,
			'sort_order' => 'desc',
			'sort_by' => 'date_start',
			'title_key' => 'LBL_ACTIVITIES_SUBPANEL_TITLE',
			'type' => 'collection',
			'subpanel_name' => 'activities',   //this values is not associated with a physical file.
			'header_definition_from_subpanel'=> 'meetings',
			'module'=>'Activities',

			'top_buttons' => array(
				array('widget_class' => 'SubPanelTopCreateTaskForBrandButton'),
				array('widget_class' => 'SubPanelTopScheduleMeetingForBrandButton'),
				array('widget_class' => 'SubPanelTopScheduleCallForBrandButton'),
				array('widget_class' => 'SubPanelTopComposeEmailForBrandButton'),
			),

			'collection_list' => array(
				'meetings' => array(
					'module' => 'Meetings',
					'subpanel_name' => 'ForActivities',
					'get_subpanel_data' => 'meetings',
				),
				'tasks' => array(
					'module' => 'Tasks',
					'subpanel_name' => 'ForActivities',
					'get_subpanel_data' => 'tasks',
				),
				'calls' => array(
					'module' => 'Calls',
					'subpanel_name' => 'ForActivities',
					'get_subpanel_data' => 'calls',
				),
			)
		),
		'history' => array(
			'order' => 20,
			'sort_order' => 'desc',
			'sort_by' => 'date_modified',
			'title_key' => 'LBL_HISTORY_SUBPANEL_TITLE',
			'type' => 'collection',
			'subpanel_name' => 'history',   //this values is not associated with a physical file.
			'header_definition_from_subpanel'=> 'meetings',
			'module'=>'History',

			'top_buttons' => array(
			
				array('widget_class' => 'SubPanelTopCreateNoteForBrandButton'),

			),

			'collection_list' => array(
				'meetings' => array(
					'module' => 'Meetings',
					'subpanel_name' => 'ForHistory',
					'get_subpanel_data' => 'meetings',
				),
				'tasks' => array(
					'module' => 'Tasks',
					'subpanel_name' => 'ForHistory',
					'get_subpanel_data' => 'tasks',
				),
				'calls' => array(
					'module' => 'Calls',
					'subpanel_name' => 'ForHistory',
					'get_subpanel_data' => 'calls',
				),

				'notes' => array(
					'module' => 'Notes',
					'subpanel_name' => 'ForHistory',
					'get_subpanel_data' => 'notes',
				),

				'emails' => array(
					'module' => 'Emails',
					'subpanel_name' => 'ForHistory',
					'get_subpanel_data' => 'emails',
				),
			)
		),
		
		'contacts' => array(
			'order' => 30,
			'module' => 'Contacts',
			'sort_order' => 'asc',
			'sort_by' => 'last_name, first_name',
			'subpanel_name' => 'ForBrands',
			'get_subpanel_data' => 'contacts',
			'add_subpanel_data' => 'contact_id',
			'title_key' => 'LBL_CONTACTS_SUBPANEL_TITLE',
			'top_buttons' => array(
				array('widget_class' => 'SubPanelTopSelectButton', 'mode'=>'MultiSelect')
			),

		),
		/*
		'opportunities' => array(
			'order' => 40,
			'module' => 'Opportunities',
			'subpanel_name' => 'ForBrands',
			'sort_order' => 'desc',
			'sort_by' => 'date_closed',
			'get_subpanel_data' => 'opportunities',
			'add_subpanel_data' => 'opportunity_id',
			'title_key' => 'LBL_OPPORTUNITIES_SUBPANEL_TITLE',
			'top_buttons' => array(
				array('widget_class' => 'SubPanelTopButtonQuickCreate'),
				array('widget_class' => 'SubPanelTopSelectButton', 'mode'=>'MultiSelect')
			),
		),
		*/
		'leads' => array(
			'order' => 50,
			'module' => 'Leads',
			'sort_order' => 'asc',
			'sort_by' => 'last_name, first_name',
			'subpanel_name' => 'default',
			'get_subpanel_data' => 'leads',
			'add_subpanel_data' => 'lead_id',
			'title_key' => 'LBL_LEADS_SUBPANEL_TITLE',
			'top_buttons' => array(
//				array('widget_class' => 'SubPanelTopCreateLeadNameButton'),
				array('widget_class' => 'SubPanelTopSelectButton',
					'popup_module' => 'Opportunities',
					'mode' => 'MultiSelect',
				),
			),
		),
		
		/*
		'cases' => array(
			'order' => 60,
			'sort_order' => 'desc',
			'sort_by' => 'case_number',
			'module' => 'Cases',
			'subpanel_name' => 'ForBrands',
			'get_subpanel_data' => 'cases',
			'add_subpanel_data' => 'case_id',
			'title_key' => 'LBL_CASES_SUBPANEL_TITLE',
			'top_buttons' => array(
				array('widget_class' => 'SubPanelTopButtonQuickCreate'),
				array('widget_class' => 'SubPanelTopSelectButton', 'mode'=>'MultiSelect')
			),
		),

		'brands' => array(
			'order' => 90,
			'sort_order' => 'asc',
			'sort_by' => 'name',
			'module' => 'Brands',
			'subpanel_name' => 'default',
			'get_subpanel_data' => 'member_brands',
			'add_subpanel_data' => 'parent_id',
			'title_key' => 'LBL_MEMBER_ORG_SUBPANEL_TITLE',
			'top_buttons' => array(
				array('widget_class' => 'SubPanelTopButtonQuickCreate'),
				array('widget_class' => 'SubPanelTopSelectButton', 'mode'=>'MultiSelect')
			),
		),
		*/
		/*
		'bugs' => array(
			'order' => 100,
			'sort_order' => 'desc',
			'sort_by' => 'bug_number',
			'module' => 'Bugs',
			'subpanel_name' => 'default',
			'get_subpanel_data' => 'bugs',
			'add_subpanel_data' => 'bug_id',
			'title_key' => 'LBL_BUGS_SUBPANEL_TITLE',
			'top_buttons' => array(
				array('widget_class' => 'SubPanelTopButtonQuickCreate'),
				array('widget_class' => 'SubPanelTopSelectButton', 'mode'=>'MultiSelect')
			),
		),
		*/
		/*
		'project' => array(
			'order' => 110,
			'sort_order' => 'asc',
			'sort_by' => 'name',
			'module' => 'Project',
			'subpanel_name' => 'default',
			'get_subpanel_data' => 'project',
			'add_subpanel_data' => 'project_id',
			'title_key' => 'LBL_PROJECTS_SUBPANEL_TITLE',
			'top_buttons' => array(
				array('widget_class' => 'SubPanelTopButtonQuickCreate'),
			),
		),
		*/
	),
);
?>
