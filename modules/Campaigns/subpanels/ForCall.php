<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');
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
// $Id: default.php,v 1.5 2006/06/06 17:57:56 majed Exp $
$subpanel_layout = array(
    'buttons' => array(
        //array('widget_class' => 'SubPanelTopCreateButton'),
       // array('widget_class' => 'SubPanelTopSelectButton', 'popup_module' => 'Campaigns'),
    ),
    'where' => '',
    'list_fields' => array(
        array(
            'name' => 'name',
           // 'vname' => 'LBL_LIST_CAMPAIGN_NAME',
             'vname' => 'LBL_CAMPAIGN_NAME',
            'widget_class' => 'SubPanelDetailViewLink',
            'width' => '1%',
        ),
        array(
            'name' => 'name',
           'vname' => 'LBL_LIST_CAMPAIGN_NAME',
           //  'vname' => 'LBL_CAMPAIGN_NAME',
            'widget_class' => 'SubPanelDetailViewLink',
            'width' => '34%',
        ),
        array(
            'name' => 'status',
            'vname' => 'LBL_LIST_STATUS',
            'width' => '15%',
        ),
         'start_date' => array(
            'name' => 'start_date',
            'vname' => 'LBL_LIST_START_DATE',
            'width' => '15%',
        ),
        'end_date' => array(
            'name' => 'end_date',
            'vname' => 'LBL_LIST_END_DATE',
            'width' => '15%',
        ),
         'product_name' =>
        array(
            'name' => 'product_name',
            'vname' => 'LBL_LIST_PRODUCT',
            'width' => '15%',
        ),
    ),
);
?>
