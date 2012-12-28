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
// $Id: ForTeams.php,v 1.4 2006/06/14 21:30:37 majed Exp $
$subpanel_layout = array(
//	'top_buttons' => array(
//		array('widget_class' => 'SubPanelTopCreateButton'),
//		array('widget_class' => 'SubPanelTopSelectButton', 'popup_module' => 'IndustryMaster'),
//	),

	'where' => '',
	'list_fields' => array(
		'name'=>array(
	 		'name' => 'name',
	 		'vname' => 'LBL_LIST_FORM_TITLE',
			'widget_class' => 'SubPanelDetailViewLink',
			'width' => '25%',
		),
//                'sector_id_c'=>array(
//	 		'usage' => 'query_only',
//	 	),
//                'sector_id_c_name'=>array(
//	 		'name' => 'sector_id_c_name',
//	 		'module' => 'SectorMaster',
//		 	'target_record_key' => 'sector_id_c',
//		 	'target_module' => 'SectorMaster',
//			//'widget_class' => 'SubPanelDetailViewLink',
//		 	'vname' => 'LBL_SECTOR_AUDIT_DISPLAY',
//			'width' => '25%',
//			'sortable'=>false,
//		),
//                'edit_button'=>array(
//			'widget_class' => 'SubPanelEditButton',
//		 	'module' => 'IndustryMaster',
//			'width' => '5%',
//		),
                  'remove_button'=>array(
                        'widget_class' => 'SubPanelRemoveButton',
		 	'module' => 'RegionMaster',
			'width' => '5%',
                        'refresh_page'=>true,
		),
		'amount_usdollar'=>array(
			'usage'=>'query_only',
		),
	),
);
?>
