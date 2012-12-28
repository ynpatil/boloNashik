
<?php
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
 * Portions created by SugarCRM are Copyright (C) 2004 SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/
$dictionary['Feedback'] = array('table' => 'feedback_mast',
'audited'=>true, 'unified_search' => true, 'duplicate_merge'=>true,
  'comment' => '',
                               'fields' => array (

  'id' =>
  array (
    'name' => 'id',
    'vname' => 'LBL_ID',
    'type' => 'id',
    'required'=>true,
    'reportable'=>false,
  ),
  'parent_id' =>
  array (
    'name' => 'parent_id',
    'vname' => 'LBL_PARENT_ID',
    'type' => 'id',
     'len'=>'36'  ,
  ),
  'parent_type' =>
  array (
    'name' => 'parent_type',
    'vname' => 'LBL_PARENT_TYPE',
    'type' => 'varchar',
      'len'=>'50'  ,
  ),
   'contact_id' =>
  array (
    'name' => 'contact_id',
    'vname' => 'LBL_CONTACT_ID',
    'type' => 'id',
    'len'=>'36'  ,
  ),
    'comments' =>
  array (
    'name' => 'comments',
    'vname' => 'LBL_COMMENTS',
    'type' => 'text',
  ),
    'rating' =>
  array (
    'name' => 'rating',
    'vname' => 'LBL_RATING',
    'type' => 'varchar',
    'len'=>'50'  ,
  ),
     'email_send_status' =>
  array (
    'name' => 'email_status',
    'vname' => 'LBL_SEND_STATUS',
    'type' => 'boolean',
  ),
     'received_status' =>
  array (
    'name' => 'received_status',
    'vname' => 'LBL_RECEIVED_STATUS',
    'type' => 'boolean',


  ),
   'forward_status' =>
  array (
    'name' => 'forward_status',
    'vname' => 'LBL_FORWARD_STATUS',
    'type' => 'boolean',


  ),
   'deleted' =>
  array (
    'name' => 'deleted',
    'vname' => 'LBL_DELETED',
    'type' => 'bool',
    'required' => 'true',
    'default' => '0',
    'reportable'=>false,
  ),
   'date_entered' =>
  array (
    'name' => 'date_entered',
    'vname' => 'LBL_DATE_ENTERED',
    'type' => 'datetime',
    'required' => 'true',
  ),
  'date_modified' =>
  array (
    'name' => 'date_modified',
    'vname' => 'LBL_DATE_MODIFIED',
    'type' => 'datetime',
    'required' => 'true',
  ),
 

)
  , 'indices' => array (
       array('name' =>'feedbackpk', 'type' =>'primary', 'fields'=>array('id')),
       
    )
);


$dictionary['ContactFeedback'] = array('table' => 'contact_feedback',
'audited'=>true, 'unified_search' => true, 'duplicate_merge'=>true,
  'comment' => '',
                               'fields' => array (

  'id' =>
  array (
    'name' => 'id',
    'vname' => 'LBL_ID',
    'type' => 'id',
    'required'=>true,
    'reportable'=>false,
  ),
   'user_id' =>
  array (
    'name' => 'user_id',
    'vname' => 'LBL_USER_ID',
    'type' => 'id',
    'required'=>true,
    'reportable'=>false,
  ),
  'contact_id' =>
  array (
    'name' => 'contact_id',
    'vname' => 'LBL_CONTACT_ID',
    'type' => 'id',
    'len'=>'36'  ,
  ),
 'token_id' =>
  array (
    'name' => 'token_id',
    'vname' => 'LBL_TOKEN_ID',
    'type' => 'id',
    'required'=>true,
    'reportable'=>false,
  ),
     'email_send_status' =>
  array (
    'name' => 'email_send_status',
    'vname' => 'LBL_SEND_STATUS',
    'type' => 'boolean',
  ),
     'no_feedback_flag' =>
  array (
    'name' => 'no_feedback_flag',
    'vname' => 'LBL_NO_FEEDBACK',
    'type' => 'boolean',
  ),
   'deleted' =>
  array (
    'name' => 'deleted',
    'vname' => 'LBL_DELETED',
    'type' => 'bool',
    'required' => 'true',
    'default' => '0',
    'reportable'=>false,
  ),
   'date_entered' =>
  array (
    'name' => 'date_entered',
    'vname' => 'LBL_DATE_ENTERED',
    'type' => 'datetime',
    'required' => 'true',
  ),
  'date_modified' =>
  array (
    'name' => 'date_modified',
    'vname' => 'LBL_DATE_MODIFIED',
    'type' => 'datetime',
    'required' => 'true',
  ),
)
  , 'indices' => array (
       array('name' =>'contactfeedbackpk', 'type' =>'primary', 'fields'=>array('id')),
    )
);

$dictionary['ContactLastFeedback'] = array('table' => 'contact_last_feedback',
'audited'=>true, 'unified_search' => true, 'duplicate_merge'=>true,
  'comment' => '',
                               'fields' => array (

  'id' =>
  array (
    'name' => 'id',
    'vname' => 'LBL_ID',
    'type' => 'id',
    'required'=>true,
    'reportable'=>false,
  ),
//  'contact_id' =>
//  array (
//    'name' => 'contact_id',
//    'vname' => 'LBL_CONTACT_ID',
//    'type' => 'id',
//    'len'=>'36'  ,
//  ),
   'last_feedback_date' =>
  array (
    'name' => 'last_feedback_date',
    'vname' => 'LBL_LAST_FEEDBACL_DATE',
    'type' => 'date',
    'required' => 'true',
  ),
    'deleted' =>
  array (
    'name' => 'deleted',
    'vname' => 'LBL_DELETED',
    'type' => 'bool',
    'required' => 'true',
    'default' => '0',
    'reportable'=>false,
  ),
   'date_entered' =>
  array (
    'name' => 'date_entered',
    'vname' => 'LBL_DATE_ENTERED',
    'type' => 'datetime',
    'required' => 'true',
  ),
  'date_modified' =>
  array (
    'name' => 'date_modified',
    'vname' => 'LBL_DATE_MODIFIED',
    'type' => 'datetime',
    'required' => 'true',
  ),
)
  , 'indices' => array (
       array('name' =>'contactfeedbackpk', 'type' =>'primary', 'fields'=>array('id')),
    )
);
?>
