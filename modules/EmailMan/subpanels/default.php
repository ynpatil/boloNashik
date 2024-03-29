<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * Subpanel Layout definition for Contacts
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

// $Id: default.php,v 1.7 2006/06/20 19:14:47 awu Exp $
$subpanel_layout = array(
	'top_buttons' => array(
	),

	'where' => '',


	'list_fields' => array(
		'recipient_name'=>array(
			'vname' => 'LBL_LIST_RECIPIENT_NAME',
			'width' => '10%',
			'sortable'=>false,			
		),
		'recipient_email'=>array(
			'vname' => 'LBL_LIST_RECIPIENT_EMAIL',
			'width' => '10%',
			'sortable'=>false,			
		),		
		'message_name' => array(
			'vname' => 'LBL_MARKETING_ID',
			'width' => '10%',
			'sortable'=>false,
		),
		'send_date_time' => array(
			'vname' => 'LBL_LIST_SEND_DATE_TIME',
			'width' => '10%',
			'sortable'=>false,			
		),
		'related_id'=>array(
			'usage'=>'query_only',
		),
		'related_type'=>array(
			'usage'=>'query_only',			
		),
		'marketing_id' => array(
			'usage'=>'query_only',			
		),
	),
);		
?>
