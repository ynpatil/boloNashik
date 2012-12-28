<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
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

// $Id: listviewdefs.php,v 1.8 2006/08/22 19:09:18 awu Exp $

$listViewDefs['SAPAccounts'] = array(
	'NAME1' => array(
		'width' => '40', 
		'label' => 'LBL_LIST_ACCOUNT_NAME', 
		'link' => true,
        'default' => true), 
	'ORT01' => array(
		'width' => '10', 
		'label' => 'LBL_LIST_CITY',
        'default' => true 
		),
    'DATE_MODIFIED' => array(
        'width' => '5', 
        'label' => 'LBL_DATE_MODIFIED'),
    'DATE_ENTERED' => array(
        'width' => '5', 
        'label' => 'LBL_DATE_ENTERED'),
    'CREATED_BY_NAME' => array(
        'width' => '10', 
        'label' => 'LBL_CREATED'),
    'ASSIGNED_USER_NAME' => array(
        'width' => '2', 
        'label' => 'LBL_LIST_ASSIGNED_USER',
        'default' => true),
    'MODIFIED_USER_NAME' => array(
        'width' => '2', 
        'label' => 'LBL_MODIFIED')
);
?>
