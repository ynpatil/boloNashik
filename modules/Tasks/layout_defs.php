<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * Layout definition for Meetings
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

// $Id: layout_defs.php,v 1.23 2006/06/23 00:30:35 wayne Exp $

$layout_defs['Tasks'] = array(
	// list of what Subpanels to show in the DetailView
	'subpanel_setup' => array(
		'activities1' => array(
			'order' => 30,
			'title_key' => 'LBL_ACTIVITIES_SUBPANEL_TITLE',
			'type' => 'collection',
			'subpanel_name' => 'activities1',   //this values is not associated with a physical file.
			'sort_order' => 'desc',
			'header_definition_from_subpanel'=> 'calls',
			'module'=>'History',
			'top_buttons' => array(
				array('widget_class' => 'SubPanelTopCreateTaskFollowupButton'),
				array('widget_class' => 'SubPanelTopScheduleMeetingFollowupButton'),
				array('widget_class' => 'SubPanelTopScheduleCallFollowupButton'),
				array('widget_class' => 'SubPanelTopComposeEmailFollowupButton'),
			),
			'collection_list' => array(
				'parent_obj_tasks' => array(
					'module' => 'Tasks',
					'subpanel_name' => 'ForParent',
					'get_subpanel_data' => 'parent_obj_tasks',
				),

				'parent_obj_tasks_calls' => array(
					'module' => 'Calls',
					'subpanel_name' => 'ForParent',
					'get_subpanel_data' => 'parent_obj_tasks_calls',
				),
				'parent_obj_tasks_emails' => array(
					'module' => 'Emails',
					'subpanel_name' => 'ForParent',
					'get_subpanel_data' => 'parent_obj_tasks_emails',
				),

				'tasks_calls' => array(
					'module' => 'Calls',
					'subpanel_name' => 'ForFollowup',
					'get_subpanel_data' => 'tasks_calls',
				),
				'tasks_tasks' => array(
					'module' => 'Tasks',
					'subpanel_name' => 'ForFollowup',
					'get_subpanel_data' => 'tasks_tasks',
				),
				'tasks_meetings' => array(
					'module' => 'Meetings',
					'subpanel_name' => 'ForFollowup',
					'get_subpanel_data' => 'tasks_meetings',
				),
				'tasks_emails' => array(
					'module' => 'Emails',
					'subpanel_name' => 'ForFollowup',
					'get_subpanel_data' => 'tasks_emails',
				),
			),
		), /* end follow up activities subpanel def */

	),
);
?>
