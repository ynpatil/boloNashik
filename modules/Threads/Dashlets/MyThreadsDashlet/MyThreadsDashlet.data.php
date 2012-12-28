<?php
/*********************************************************************************
 * The contents of this file are subject to the CareBrains Public License
 * Version 1.0 ('License'); You may not use this file except in compliance
 * with the License. You may obtain a copy of the License at
 * http://www.carebrains.co.jp/CPL .
 * Software distributed under the License is distributed on an 'AS IS' basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License
 * for the specific language governing rights and limitations under the
 * License.
 * 
 * The Original Code is CareBrains Open Source.
 * The Initial Developer of the Original Code is CareBrains, Inc.
 * Portions created by CareBrains are Copyright (C) 2005-2006 CareBrains, Inc.
 * All Rights Reserved.
 *
 * The Original Code is: CareBrains Inc.
 * The Initial Developer of the Original Code is CareBrains Inc.
 * Portions created by SugarCRM are Copyright (C) 2004-2006 SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/
global $current_user;

$dashletData['MyThreadsDashlet']['searchFields'] = array('title'         => array('default' => ''),
                                                       'date_entered'     => array('default' => ''));
$dashletData['MyThreadsDashlet']['columns'] = array('title' => array('width'   => '40',
                                                                         'label'   => 'LBL_TITLE',
            	                                                         'link'    => true,
                                                                         'default' => true),
                                                  'recent_post_title' => array('width'   => '40', 
                                                                          'label'   => 'LBL_RECENT_POST_TITLE',                    		                                                  'link'    => true,
                                                                        'default' => true),
                                                  'recent_post_modified_name' => array('width'   => '15', 
                                                                        'label'   => 'LBL_RECENT_POST_MODIFIED_NAME',
                                                                          'default' => true),
                                                 );
?>
