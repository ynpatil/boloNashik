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



global $current_user;
$dashletData['BirthdaylistDashlet']['searchFields'] = array('date_entered'     => array('default' => ''),
															'assigned_user_id' => array('type'    => 'assigned_user_name', 
                                                                                      'default' => $current_user->name)); 
$dashletData['BirthdaylistDashlet']['columns']['Contacts'] = array('name' => array('width'   => '30', 
                                                                     'label'   => 'LBL_NAME',
                                                                     'link'    => true,
                                                                     'default' => true,
                                                                     'related_fields' => array('first_name', 'last_name')),
                                                     'title' => array('width' => '10',
                                                                      'label' => 'LBL_TITLE',
                                                                           'default' => true),
                                                     'phone_work' => array('width'   => '10',
                                                                           'label'   => 'LBL_OFFICE_PHONE'),
                                                     'phone_home' => array('width' => '10',
                                                                           'label' => 'LBL_HOME_PHONE'),
                                                     'phone_mobile' => array('width' => '10',
                                                                             'label' => 'LBL_MOBILE_PHONE',
                                                                           'default' => true),
                                                     'birthdate' => array('width'   => '15', 
                                                                              'label'   => 'LBL_BIRTHDATE',
                                                                           'default' => true),    

                                                                             );

$dashletData['BirthdaylistDashlet']['columns']['Accounts'] = array('name' => array('width'   => '30', 
                                                                     'label'   => 'LBL_NAME',
                                                                     'link'    => true,
                                                                     'default' => true,
                                                                     'related_fields' => array('name')),
                                                     'phone_office' => array('width'   => '10',
                                                                           'label'   => 'LBL_PHONE','default' => true),
                                                     'anniversary' => array('width'   => '15', 
                                                                              'label'   => 'LBL_ANNIVERSARY',
	                                                                          'default' => true),
                                                                             );

?>
