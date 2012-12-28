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

 // $Id: listviewdefs.php,v 1.1.2.1 2006/09/13 00:50:39 jenny Exp $

$listViewDefs['ProjectTask'] = array(
	'ORDER_NUMBER' => array(
		'width' => '5',  
		'label' => 'LBL_LIST_ORDER_NUMBER', 
		'link' => false,
        'default' => true,
        'sortable' => true),
    'NAME' => array(
        'width' => '40',  
        'label' => 'LBL_LIST_NAME', 
        'link' => true,
        'default' => true,
        'sortable' => true),       
    'PARENT_NAME' => array(
        'width' => '25',  
        'label' => 'LBL_PROJECT_NAME', 
        'id'=>'PARENT_ID',
        'link' => true,
        'default' => true,
        'sortable' => true,
        'module'  => 'Project',
        'ACLTag' => 'PROJECT',
        'related_fields' => array('parent_id')),            
    'DATE_DUE' => array(
        'width' => '10',  
        'label' => 'LBL_LIST_DATE_DUE', 
        'default' => true,
        'sortable' => true),            
    'STATUS' => array(
        'width' => '10',  
        'label' => 'LBL_LIST_STATUS', 
        'default' => true,
        'sortable' => true),            
	'ASSIGNED_USER_NAME' => array(
		'width' => '10', 
		'label' => 'LBL_LIST_ASSIGNED_USER_ID',
        'default' => true)
);

?>
