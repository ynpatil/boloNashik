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
 * $Id: field_arrays.php,v 1.3 2006/06/06 17:58:32 majed Exp $
 * Description:  Contains field arrays that are used for caching
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
$fields_array['Feedback'] = array ('column_fields' => array(
		'id',
		'date_entered',
		'date_modified',
		'parent_id',
                'parent_type',
                'contact_id',
                'comments',
                'rating',
                'send_status',
                'received_status',
                'forward_status',
		'deleted',
	),
        'list_fields' =>  array(
		'id',
	),
    'required_fields' =>  array('id'=>1,),
);

$fields_array['ContactFeedback'] = array ('column_fields' => array(
		'id',
		'date_entered',
		'date_modified',
		'contact_id',
                'email_send_status',
                'no_feedback_flag',
                'token_id',
                'user_id',
		'deleted',
	),
        'list_fields' =>  array(
		'id',
	),
    'required_fields' =>  array('id'=>1,),
);

$fields_array['ContactLastFeedback'] = array ('column_fields' => array(
		'id',
		'date_entered',
		'date_modified',
		//'contact_id',
                'last_feedback_date',
		'deleted',
	),
        'list_fields' =>  array(
		'id',
	),
    'required_fields' =>  array('id'=>1,),
);
?>
