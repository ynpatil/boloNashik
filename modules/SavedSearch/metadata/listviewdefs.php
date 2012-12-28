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

 // $Id: listviewdefs.php,v 1.3 2006/08/22 19:47:48 awu Exp $

$listViewDefs['SavedSearch'] = array(
	'NAME' => array(
		'width' => '40%', 		
		'label' => 'LBL_LIST_NAME',
		'link' => true,
		'customCode' => '<a class="listViewTdLinkS1" href="index.php?action=index&module=SavedSearch&saved_search_select={$ID}">{$NAME}</a>'),
	'SEARCH_MODULE' => array(
		'width' => '35%', 
		'label' => 'LBL_LIST_MODULE'), 





	'ASSIGNED_USER_NAME' => array(
		'width' => '10%', 
		'label' => 'LBL_LIST_ASSIGNED_USER')
);
?>
