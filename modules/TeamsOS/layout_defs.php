<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');
/**
 * Layout definition for Opportunities
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
// $Id: layout_defs.php,v 1.30 2006/03/15 20:41:59 ajay Exp $

$layout_defs['TeamsOS'] = array(
    'subpanel_setup' => array(
        'users' => array(
            'order' => 10,
            'module' => 'Users',
            'subpanel_name' => 'default',
            'get_subpanel_data' => 'users',
            'add_subpanel_data' => 'user_id',
            'title_key' => 'LBL_USERS_SUBPANEL_TITLE',
            'top_buttons' => array(
                array(
                    'widget_class' => 'SubPanelTopSelectButton',
                    'mode' => 'MultiSelect'
                )
            )
        ),
        'city' => array(
            'order' => 20,
            'module' => 'CityMaster',
            'sort_order' => 'asc',
            'sort_by' => 'name',
            'subpanel_name' => 'ForTeam',
            'get_subpanel_data' => "city",
            'add_subpanel_data' => 'city_id',
            'refresh_page'=>true,
            'title_key' => 'LBL_CITY_SUBPANEL_TITLE',
            'top_buttons' => array(                
                array(
                    'widget_class' => 'SubPanelTopSelectButton',
                    'mode' => 'MultiSelect'
                ),
            ),
        ),
        'state' => array(
            'order' => 30,
            'module' => 'StateMaster',
            'sort_order' => 'asc',
            'sort_by' => 'name',
            'subpanel_name' => 'ForTeam',
            'get_subpanel_data' => "state",
            'add_subpanel_data' => 'state_id',
            'refresh_page'=>true,
            'title_key' => 'LBL_STATE_SUBPANEL_TITLE',
            'top_buttons' => array(
                array(
                    'widget_class' => 'SubPanelTopSelectButton',
                    'mode' => 'MultiSelect'
                ),
            ),
        ),
        'region' => array(
            'order' => 40,
            'module' => 'RegionMaster',
            'sort_order' => 'asc',
            'sort_by' => 'name',
            'subpanel_name' => 'default',
            'get_subpanel_data' => 'region',
            'refresh_page'=>true,
            'add_subpanel_data' => 'region_id',
            'title_key' => 'LBL_REGION_SUBPANEL_TITLE',
            'top_buttons' => array(
                array(
                    'widget_class' => 'SubPanelTopSelectButton',
                    'mode' => 'MultiSelect'
                ),
            ),
        ),        
        'brands' => array(
            'order' => 50,
            'module' => 'Brands',
            'sort_order' => 'asc',
            'sort_by' => 'name',
            'subpanel_name' => 'ForTeam',
            'get_subpanel_data' => "brand",
            'add_subpanel_data' => 'brand_id',
            'title_key' => 'LBL_BRANDS_SUBPANEL_TITLE',
            'top_buttons' => array(                
                array(
                    'widget_class' => 'SubPanelTopSelectButton',
                    'mode' => 'MultiSelect'
                ),
            ),
        ),
        'language' => array(
            'order' => 60,
            'module' => 'LanguageMaster',
            'sort_order' => 'asc',
            'sort_by' => 'name',
            'subpanel_name' => 'ForTeam',
            'get_subpanel_data' => "language",
            'add_subpanel_data' => 'language_id',
            'refresh_page'=>true,
            'title_key' => 'LBL_LANGUAGE_SUBPANEL_TITLE',
            'top_buttons' => array(
                array(
                    'widget_class' => 'SubPanelTopSelectButton',
                    'mode' => 'MultiSelect'
                ),
            ),
        ),
        'level' => array(
            'order' => 70,
            'module' => 'LevelMaster',
            'sort_order' => 'asc',
            'sort_by' => 'name',
            'subpanel_name' => 'ForTeam',
            'get_subpanel_data' => "level",
            'add_subpanel_data' => 'level_id',
            'refresh_page'=>true,
            'title_key' => 'LBL_LEVEL_SUBPANEL_TITLE',
            'top_buttons' => array(
                array(
                    'widget_class' => 'SubPanelTopSelectButton',
                    'mode' => 'MultiSelect'
                ),
            ),
        ),
        'experience' => array(
            'order' => 90,
            'module' => 'ExperienceMaster',
            'sort_order' => 'asc',
            'sort_by' => 'name',
            'subpanel_name' => 'ForTeam',
            'get_subpanel_data' => "experience",
            'add_subpanel_data' => 'experience_id',
            'refresh_page'=>true,
            'title_key' => 'LBL_EXPERIENCE_SUBPANEL_TITLE',
            'top_buttons' => array(
                array(
                    'widget_class' => 'SubPanelTopSelectButton',
                    'mode' => 'MultiSelect'
                ),
            ),
        ),
    )
);
?>
