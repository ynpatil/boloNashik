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
//om
// $Id: layout_defs.php,v 1.20 2006/06/23 00:33:01 wayne Exp $

$layout_defs['Calls'] = array(
    // sets up which panels to show, in which order, and with what linked_fields
    'subpanel_setup' => array(
        'contacts' => array(
            'top_buttons' => array(),
            'order' => 10,
            'module' => 'Contacts',
            'sort_order' => 'asc',
            'sort_by' => 'last_name, first_name',
            'subpanel_name' => 'ForCalls',
            'get_subpanel_data' => 'contacts',
            'title_key' => 'LBL_CONTACTS_SUBPANEL_TITLE',
        ),
        'users' => array(
            'top_buttons' => array(),
            'order' => 20,
            'module' => 'Users',
            'sort_order' => 'asc',
            'sort_by' => 'full_name',
            'subpanel_name' => 'ForCalls',
            'get_subpanel_data' => 'users',
            'title_key' => 'LBL_USERS_SUBPANEL_TITLE',
        ),
        'history' => array(
            'order' => 30,
            'title_key' => 'LBL_HISTORY_SUBPANEL_TITLE',
            'type' => 'collection',
            'subpanel_name' => 'history', //this values is not associated with a physical file.
            'sort_order' => 'desc',
            'sort_by' => 'date_modified',
            'header_definition_from_subpanel' => 'calls',
            'module' => 'History',
            'top_buttons' => array(
                array('widget_class' => 'SubPanelTopCreateNoteButton'),
            ),
            'collection_list' => array(
                'notes' => array(
                    'module' => 'Notes',
                    'subpanel_name' => 'ForHistory',
                    'get_subpanel_data' => 'notes',
                ),
            ),
        ), /* end history subpanel def */

        'activities1' => array(
            'order' => 40,
            'title_key' => 'LBL_ACTIVITIES_SUBPANEL_TITLE',
            'type' => 'collection',
            'subpanel_name' => 'activities1', //this values is not associated with a physical file.
            'sort_order' => 'desc',
            'header_definition_from_subpanel' => 'calls',
            'module' => 'History',
            'top_buttons' => array(
                array('widget_class' => 'SubPanelTopCreateTaskFollowupButton'),
                array('widget_class' => 'SubPanelTopScheduleMeetingFollowupButton'),
                array('widget_class' => 'SubPanelTopScheduleCallFollowupButton'),
                array('widget_class' => 'SubPanelTopComposeEmailFollowupButton'),
                array('widget_class' => 'SubPanelTopCreateReviewFollowupButton'),
                array('widget_class' => 'SubPanelTopCreateCommentFollowupButton'),
            ),
            'collection_list' => array(
                'calls_comments' => array(
                    'module' => 'Comments',
                    'subpanel_name' => 'ForFollowup',
                    'get_subpanel_data' => 'calls_comments',
                ),
                'calls_reviews' => array(
                    'module' => 'Reviews',
                    'subpanel_name' => 'ForFollowup',
                    'get_subpanel_data' => 'calls_reviews',
                ),
                'parent_obj_calls' => array(
                    'module' => 'Calls',
                    'subpanel_name' => 'ForParent',
                    'get_subpanel_data' => 'parent_obj_calls',
                ),
                'group_calls' => array(
                    'module' => 'Calls',
                    'subpanel_name' => 'ForGroupActivity',
                    'get_subpanel_data' => 'group_calls',
                ),
                'parent_obj_calls_meetings' => array(
                    'module' => 'Meetings',
                    'subpanel_name' => 'ForParent',
                    'get_subpanel_data' => 'parent_obj_calls_meetings',
                ),
                'parent_obj_calls_tasks' => array(
                    'module' => 'Tasks',
                    'subpanel_name' => 'ForParent',
                    'get_subpanel_data' => 'parent_obj_calls_tasks',
                ),
                'parent_obj_calls_emails' => array(
                    'module' => 'Emails',
                    'subpanel_name' => 'ForParent',
                    'get_subpanel_data' => 'parent_obj_calls_emails',
                ),
                'calls_calls' => array(
                    'module' => 'Calls',
                    'subpanel_name' => 'ForFollowup',
                    'get_subpanel_data' => 'calls_calls',
                ),
                'calls_meetings' => array(
                    'module' => 'Meetings',
                    'subpanel_name' => 'ForFollowup',
                    'get_subpanel_data' => 'calls_meetings',
                ),
                'calls_tasks' => array(
                    'module' => 'Tasks',
                    'subpanel_name' => 'ForFollowup',
                    'get_subpanel_data' => 'calls_tasks',
                ),
                'calls_emails' => array(
                    'module' => 'Emails',
                    'subpanel_name' => 'ForFollowup',
                    'get_subpanel_data' => 'calls_emails',
                ),
            ),
        ), /* end follow up activities subpanel def */

//        'brands' => array(
//            'order' => 40,
//            'module' => 'Brands',
//            'sort_order' => 'asc',
//            'sort_by' => 'name',
//            'subpanel_name' => 'ForCall',
//            'get_subpanel_data' => "brand",
//            'add_subpanel_data' => 'brand_id',
//            'title_key' => 'LBL_BRANDS_SUBPANEL_TITLE',
//            'top_buttons' => array(
//                array(
//                   'widget_class' => 'SubPanelTopSelectButton',
//                    'mode' => 'MultiSelect'
//                ),
//            ),
//        ),
    ),
);
?>
