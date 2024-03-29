<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * Layout definition for History
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

// $Id: layout_defs.php,v 1.16 2006/06/12 22:00:08 jacob Exp $

$layout_defs['History'] = array(
	// default subpanel provided by this SugarBean
	'default_subpanel_define' => array(
		'subpanel_title' => 'LBL_DEFAULT_SUBPANEL_TITLE',
		'top_buttons' => array(
			array('widget_class' => 'SubPanelTopCreateNoteButton'),
			array('widget_class' => 'SubPanelTopArchiveEmailButton'),
            array('widget_class' => 'SubPanelTopSummaryButton'),
		),
		
//TODO try and merge with the activities		
		'list_fields' => array(
			'Meetings' => array(
				'columns' => array(
					array(
			 		 	'name' => 'nothing',
						'widget_class' => 'SubPanelIcon',
			 		 	'module' => 'Meetings',
		 		 		'width' => '2%',
					),
					array(
			 		 	'name' => 'name',
			 		 	'vname' => 'LBL_LIST_SUBJECT',
						'widget_class' => 'SubPanelDetailViewLink',
		 		 		'width' => '28%',
					),
					array(
			 		 	'name' => 'status',
			 		 	'vname' => 'LBL_LIST_STATUS',
		 		 		'width' => '10%',
					),
					array(
			 		 	'name' => 'contact_name',
			 		 	'module' => 'Contacts',
						'widget_class' => 'SubPanelDetailViewLink',
			 		 	'target_record_key' => 'contact_id',
			 		 	'target_module' => 'Contacts',
			 		 	'vname' => 'LBL_LIST_CONTACT',
		 		 		'width' => '20%',
					),
					array(
			 		 	'name' => 'parent_name',
			 		 	'module' => 'Meetings',
			 		 	'vname' => 'LBL_LIST_RELATED_TO',
			 		 	'width' => '22%',
					),
					array(
			 		 	'name' => 'date_modified',
			 		 	//'db_alias_to' => 'the_date',
			 		 	'vname' => 'LBL_LIST_LAST_MODIFIED',
		 		 		'width' => '10%',
					),
					array(
			 		 	'name' => 'nothing',
						'widget_class' => 'SubPanelEditButton',
			 		 	'module' => 'Meetings',
		 		 		'width' => '4%',
					),
					array(
			 		 	'name' => 'nothing',
						'widget_class' => 'SubPanelRemoveButton',
						'linked_field' => 'meetings',
			 		 	'module' => 'Meetings',
		 		 		'width' => '4%',
					),
				),
				'where' => "(meetings.status='Held' OR meetings.status='Not Held')",
				'order_by' => 'meetings.date_modified',
			),
			'Emails' => array(
				'columns' => array(
					array(
			 		 	'name' => 'nothing',
						'widget_class' => 'SubPanelIcon',
			 		 	'module' => 'Emails',
		 		 		'width' => '2%',
					),
					array(
			 		 	'name' => 'name',
			 		 	'vname' => 'LBL_LIST_SUBJECT',
						'widget_class' => 'SubPanelDetailViewLink',
		 		 		'width' => '28%',
					),
					array(
			 		 	'name' => 'status',
			 		 	'vname' => 'LBL_LIST_STATUS',
		 		 		'width' => '10%',	
					),
					array(
			 		 	'name' => 'contact_name',
			 		 	'module' => 'Contacts',
						'widget_class' => 'SubPanelDetailViewLink',
			 		 	'target_record_key' => 'contact_id',
			 		 	'target_module' => 'Contacts',
			 		 	'vname' => 'LBL_LIST_CONTACT',
		 		 		'width' => '20%',
					),
					array(
			 		 	'name' => 'parent_name',
			 		 	'module' => 'Emails',
			 		 	'vname' => 'LBL_LIST_RELATED_TO',
			 		 	'width' => '22%',
					),
					array(
			 		 	'name' => 'date_modified',
			 		 	//'db_alias_to' => 'the_date',
			 		 	'vname' => 'LBL_LIST_LAST_MODIFIED',
		 		 		'width' => '10%',
					),
					array(
			 		 	'name' => 'nothing',
						'widget_class' => 'SubPanelEditButton',
			 		 	'module' => 'Emails',
		 		 		'width' => '4%',
					),
					array(
			 		 	'name' => 'nothing',
						'widget_class' => 'SubPanelRemoveButton',
						'linked_field' => 'emails',
			 		 	'module' => 'Emails',
		 		 		'width' => '4%',
					),
				),
				'where' => "(emails.status='sent')",
				'order_by' => 'emails.date_modified',
			),
			'Notes' => array(
				'columns' => array(
					array(
			 		 	'name' => 'nothing',
						'widget_class' => 'SubPanelIcon',
			 		 	'module' => 'Notes',
		 		 		'width' => '2%',
					),
					array(
			 		 	'name' => 'name',
			 		 	'vname' => 'LBL_LIST_SUBJECT',
						'widget_class' => 'SubPanelDetailViewLink',
		 		 		'width' => '28%',
					),
					array( // this column does not exist on 
			 		 	'name' => 'status',
			 		 	'vname' => 'LBL_LIST_STATUS',
		 		 		'width' => '10%',
					),
					array(
			 		 	'name' => 'contact_name',
			 		 	'module' => 'Contacts',
						'widget_class' => 'SubPanelDetailViewLink',
			 		 	'target_record_key' => 'contact_id',
			 		 	'target_module' => 'Contacts',
			 		 	'vname' => 'LBL_LIST_CONTACT',
		 		 		'width' => '20%',
					),
					array(
			 		 	'name' => 'parent_name',
			 		 	'module' => 'Notes',
			 		 	'vname' => 'LBL_LIST_RELATED_TO',
			 		 	'width' => '22%',
					),
					array(
			 		 	'name' => 'date_modified',
			 		 	//'db_alias_to' => 'the_date',
			 		 	'vname' => 'LBL_LIST_LAST_MODIFIED',
		 		 		'width' => '10%',
					),
					array(
			 		 	'name' => 'nothing',
						'widget_class' => 'SubPanelEditButton',
			 		 	'module' => 'Notes',
		 		 		'width' => '4%',
					),
					array(
			 		 	'name' => 'nothing',
						'widget_class' => 'SubPanelRemoveButton',
						'linked_field' => 'notes',
			 		 	'module' => 'Notes',
		 		 		'width' => '4%',
					),
				),
				'where' => '',
				'order_by' => 'notes.date_modified',
			),
			'Tasks' => array(
				'columns' => array(
					array(
			 		 	'name' => 'nothing',
						'widget_class' => 'SubPanelIcon',
			 		 	'module' => 'Tasks',
		 		 		'width' => '2%',
					),
					array(
			 		 	'name' => 'name',
			 		 	'vname' => 'LBL_LIST_SUBJECT',
						'widget_class' => 'SubPanelDetailViewLink',
		 		 		'width' => '28%',
					),
					array(
			 		 	'name' => 'status',
			 		 	'vname' => 'LBL_LIST_STATUS',
		 		 		'width' => '10%',
					),
					array(
			 		 	'name' => 'contact_name',
			 		 	'module' => 'Contacts',
						'widget_class' => 'SubPanelDetailViewLink',
			 		 	'target_record_key' => 'contact_id',
			 		 	'target_module' => 'Contacts',
			 		 	'vname' => 'LBL_LIST_CONTACT',
		 		 		'width' => '20%',
					),
					array(
			 		 	'name' => 'parent_name',
			 		 	'module' => 'Tasks',
			 		 	'vname' => 'LBL_LIST_RELATED_TO',
			 		 	'width' => '22%',
					),
					array(
			 		 	'name' => 'date_modified',
			 		 	//'db_alias_to' => 'the_date',
			 		 	'vname' => 'LBL_LIST_LAST_MODIFIED',
		 		 		'width' => '10%',
					),
					array(
			 		 	'name' => 'nothing',
						'widget_class' => 'SubPanelEditButton',
			 		 	'module' => 'Tasks',
		 		 		'width' => '4%',
					),
					array(
			 		 	'name' => 'nothing',
						'widget_class' => 'SubPanelRemoveButton',
						'linked_field' => 'tasks',
			 		 	'module' => 'Tasks',
		 		 		'width' => '4%',
					),
				),
				'where' => "(tasks.status='Completed' OR tasks.status='Deferred')",
				'order_by' => 'tasks.date_start',
			),
			'Calls' => array(
				'columns' => array(
					array(
			 		 	'name' => 'nothing',
						'widget_class' => 'SubPanelIcon',
			 		 	'module' => 'Calls',
		 		 		'width' => '2%',
					),
					array(
			 		 	'name' => 'name',
			 		 	'vname' => 'LBL_LIST_SUBJECT',
						'widget_class' => 'SubPanelDetailViewLink',
		 		 		'width' => '28%',
					),
					array(
			 		 	'name' => 'status',
			 		 	'vname' => 'LBL_LIST_STATUS',
		 		 		'width' => '10%',
					),
					array(
			 		 	'name' => 'contact_name',
			 		 	'module' => 'Contacts',
						'widget_class' => 'SubPanelDetailViewLink',
			 		 	'target_record_key' => 'contact_id',
			 		 	'target_module' => 'Contacts',
			 		 	'vname' => 'LBL_LIST_CONTACT',
		 		 		'width' => '20%',
					),
					array(
			 		 	'name' => 'parent_name',
			 		 	'module' => 'Meetings',
			 		 	'vname' => 'LBL_LIST_RELATED_TO',
			 		 	'width' => '22%',
					),
					array(
			 		 	'name' => 'date_modified',
			 		 	//'db_alias_to' => 'the_date',
			 		 	'vname' => 'LBL_LIST_LAST_MODIFIED',
		 		 		'width' => '10%',
					),
					array(
			 		 	'name' => 'nothing',
						'widget_class' => 'SubPanelEditButton',
			 		 	'module' => 'Calls',
		 		 		'width' => '4%',
					),
					array(
			 		 	'name' => 'nothing',
						'widget_class' => 'SubPanelRemoveButton',
						'linked_field' => 'calls',
			 		 	'module' => 'Calls',
		 		 		'width' => '4%',
					),
				),
				'where' => "(calls.status='Held' OR calls.status='Not Held')",
				'order_by' => 'calls.date_modified',
			),
		),
	),
);
?>
