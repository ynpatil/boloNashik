<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/*********************************************************************************
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
 ********************************************************************************/
/*********************************************************************************
 * $Id: field_assoc.php,v 1.6 2006/06/06 17:57:55 majed Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
$genericAssocFieldsArray = array('assigned_user_id' =>
                                array('table_name' => 'users',
                                    'select_field_name' => 'user_name',
                                    'select_field_join'  => 'id',
                                ),
                                'team_id' =>
                                    array('table_name' => 'teams',
                                    'select_field_name' => 'name',
                                    'select_field_join'  => 'id',
                                  ),
                                  'account_id' =>
                                  array('table_name' => 'accounts',
                                    'select_field_name' => 'name',
                                    'select_field_join'  => 'id',
                                  ), 
                                  'contact_id' =>
                                  array('table_name' => 'contacts',
                                    'select_field_name' => 
                                    		array('first_name',
                                    			  'last_name',
                                    		),
                                    'select_field_join'  => 'id',
                                  ), 
                            );
$moduleAssocFieldsArray = array('Account' =>
                                array(
                                'parent_id' =>
                                    array('table_name' => 'accounts',
                                        'select_field_name' => 'name',
                                        'select_field_join'  => 'id',
                                	    ),
                                'billing_address_city' =>
                                    array('table_name' => 'city_mast',
                                        'select_field_name' => 'name',
                                        'select_field_join'  => 'id',
		                                ),
                                'billing_address_state' =>
                                    array('table_name' => 'state_mast',
                                        'select_field_name' => 'name',
                                        'select_field_join'  => 'id',
		                                ),		                                
                                'billing_address_country' =>
                                    array('table_name' => 'country_mast',
                                        'select_field_name' => 'name',
                                        'select_field_join'  => 'id',
		                                ),
                                'shipping_address_city' =>
                                    array('table_name' => 'city_mast',
                                        'select_field_name' => 'name',
                                        'select_field_join'  => 'id',
		                                ),		                                
                                'shipping_address_state' =>
                                    array('table_name' => 'state_mast',
                                        'select_field_name' => 'name',
                                        'select_field_join'  => 'id',
		                                ),		                                
                                'shipping_address_country' =>
                                    array('table_name' => 'country_mast',
                                        'select_field_name' => 'name',
                                        'select_field_join'  => 'id',
		                                ),
                                'function_id' =>
                                    array('table_name' => 'function_mast',
                                        'select_field_name' => 'name',
                                        'select_field_join'  => 'id',
		                                ),
                                'industry_id' =>
                                    array('table_name' => 'industry_mast',
                                        'select_field_name' => 'name',
                                        'select_field_join'  => 'id',
		                                ),
                                'aor_id' =>
                                    array('table_name' => 'accounts',
                                        'select_field_name' => 'name',
                                        'select_field_join'  => 'id',
                                	    ),
		                                
		                        ),
							'Contact' =>
                                array(
                                'primary_address_city' =>
                                    array('table_name' => 'city_mast',
                                        'select_field_name' => 'name',
                                        'select_field_join'  => 'id',
		                                ),
                                'primary_address_state' =>
                                    array('table_name' => 'state_mast',
                                        'select_field_name' => 'name',
                                        'select_field_join'  => 'id',
		                                ),		                                
                                'primary_address_country' =>
                                    array('table_name' => 'country_mast',
                                        'select_field_name' => 'name',
                                        'select_field_join'  => 'id',
		                                ),
                                'alt_address_city' =>
                                    array('table_name' => 'city_mast',
                                        'select_field_name' => 'name',
                                        'select_field_join'  => 'id',
		                                ),		                                
                                'alt_address_state' =>
                                    array('table_name' => 'state_mast',
                                        'select_field_name' => 'name',
                                        'select_field_join'  => 'id',
		                                ),		                                
                                'alt_address_country' =>
                                    array('table_name' => 'country_mast',
                                        'select_field_name' => 'name',
                                        'select_field_join'  => 'id',
		                                ),
                                'function_id' =>
                                    array('table_name' => 'function_mast',
                                        'select_field_name' => 'name',
                                        'select_field_join'  => 'id',
		                                ),
		                                
		                        ),
		                        
                            );
?>
