<?php
include('modules/SugarChat/NewEntryPoint.php');
/*****************************************************************************
 * The contents of this file are subject to the RECIPROCAL PUBLIC LICENSE
 * Version 1.1 ("License"); You may not use this file except in compliance
 * with the License. You may obtain a copy of the License at
 * http://opensource.org/licenses/rpl.php. Software distributed under the
 * License is distributed on an "AS IS" basis, WITHOUT WARRANTY OF ANY KIND,
 * either express or implied.
 *
 * You may:
 * a) Use and distribute this code exactly as you received without payment or
 *    a royalty or other fee.
 * b) Create extensions for this code, provided that you make the extensions
 *    publicly available and document your modifications clearly.
 * c) Charge for a fee for warranty or support or for accepting liability
 *    obligations for your customers.
 *
 * You may NOT:
 * a) Charge for the use of the original code or extensions, including in
 *    electronic distribution models, such as ASP (Application Service
 *    Provider).
 * b) Charge for the original source code or your extensions other than a
 *    nominal fee to cover distribution costs where such distribution
 *    involves PHYSICAL media.
 * c) Modify or delete any pre-existing copyright notices, change notices,
 *    or License text in the Licensed Software
 * d) Assert any patent claims against the Licensor or Contributors, or
 *    which would in any way restrict the ability of any third party to use the
 *    Licensed Software.
 *
 * You must:
 * a) Document any modifications you make to this code including the nature of
 *    the change, the authors of the change, and the date of the change.
 * b) Make the source code for any extensions you deploy available via an
 *    Electronic Distribution Mechanism such as FTP or HTTP download.
 * c) Notify the licensor of the availability of source code to your extensions
 *    and include instructions on how to acquire the source code and updates.
 * d) Grant Licensor a world-wide, non-exclusive, royalty-free license to use,
 *    reproduce, perform, modify, sublicense, and distribute your extensions.
 *
 * The Original Code is: CommuniCore
 *                       Olavo Farias
 *                       2006-04-7 olavo.farias@gmail.com
 *
 * The Initial Developer of the Original Code is CommuniCore.
 * Portions created by CommuniCore are Copyright (C) 2005 CommuniCore Ltda
 * All Rights Reserved.
 ********************************************************************************/
/*******************************************************************************
 * Forms for Simple Module template
 *******************************************************************************/

  global $theme;
  require_once('themes/'.$theme.'/layout_utils.php');
  require_once('log4php/LoggerManager.php');
  require_once('XTemplate/xtpl.php');
  require_once('include/utils.php');
  require_once('include/JSON.php');
  
  $image_path = 'themes/'.$theme.'/images/';
  
  function get_new_record_form(){
   if(!ACLController::checkAccess('SugarChat', 'edit', true))return '';
   global $app_strings;
   global $mod_strings;
   global $currentModule;
   global $current_user;
  
   $the_form = get_left_form_header($mod_strings['LBL_QUICK_NEW_SIMPLE']);
   $form     = new XTemplate ('modules/SugarChat/Forms.html');
  
   $module_select = empty($_REQUEST['module_select']) ? ''
    : $_REQUEST['module_select'];
   $form->assign('mod', $mod_strings);
   $form->assign('app', $app_strings);
   $form->assign('module', $currentModule);
  
   $json = new JSON(JSON_LOOSE_TYPE);
   $popup_request_data = array(
    'call_back_function'  => 'set_return',
    'form_name'           => 'quick_save',
    'field_to_name_array' => array(
     'id'        => 'assigned_user_id',
     'user_name' => 'assigned_user_name',
     ),
   );
   $form->assign('encoded_users_popup_request_data', $json->encode($popup_request_data));   
   $form->parse('main');
   $the_form .= $form->text('main');
  
   require_once('modules/SugarChat/SugarChat.php');
   $focus = new SugarChat();
     
   include('include/QuickSearchDefaults.php');
   $sqs_objects     = array('assigned_user_name' => $qsUser);
   $quicksearch_js  = $qsScripts;
   $quicksearch_js .= '<script type="text/javascript" language="javascript">sqs_objects = ' . $json->encode($sqs_objects) . '</script>';
   echo $quicksearch_js;
  
   require_once('include/javascript/javascript.php');
   $javascript = new javascript();
   $javascript->setFormName('quick_save');
   $javascript->setSugarBean($focus);
   $javascript->addRequiredFields('');
   $javascript->addToValidateBinaryDependency('assigned_user_name', 'alpha', $app_strings['ERR_SQS_NO_MATCH_FIELD'] . $app_strings['LBL_ASSIGNED_TO'], 'false', '', 'assigned_user_id');
   $jscript    = $javascript->getScript();
   $the_form  .= $jscript . get_left_form_footer();
   return $the_form;
  }
  
//Create javascript to validate the data entered into a record.
  function get_validate_record_js ()
  {
   return '';
  }
  
?>
