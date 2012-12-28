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
 * EditView for Simple Module template
 *******************************************************************************/

  require_once('XTemplate/xtpl.php');
  require_once('data/Tracker.php');
  require_once('modules/SugarChat/SugarChat.php');
  require_once('include/time.php');
  require_once('include/TimeDate.php');
  require_once('modules/SugarChat/Forms.php');
  require_once('include/JSON.php');
  
///////////////////////////////////////////////////////////////////////////////
////	PREPROCESS BEAN DATA FOR DISPLAY
  $timedate = new TimeDate();
  
  global $app_strings;
  global $app_list_strings;
  global $current_language;
  global $current_user;
  global $sugar_version, $sugar_config;
  
  $focus = new SugarChat();

  if(!empty($_REQUEST['record'])){
      $focus->retrieve($_REQUEST['record']);
  }
   $chatfile = md5($focus->name).".txt";
   $filename_palaute = "modules/SugarChat/database/".$chatfile;
   if (file_exists($filename_palaute)) {
   unlink($filename_palaute);
   }
  echo "\n<p>\n";
  echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME'].": ".$focus->name, true);
  echo "\n</p>\n";
  global $theme;
  $theme_path="themes/".$theme."/";
  $image_path=$theme_path."images/";
  require_once($theme_path.'layout_utils.php');
  
  $GLOBALS['log']->info("Simple detail view");
  
///////////////////////////////////////////////////////////////////////////////
////	XTEMPLATE ASSIGNMENT
  $xtpl=new XTemplate ('modules/SugarChat/EditView.html');
  
//Users Popup
  $json = new JSON(JSON_LOOSE_TYPE);
  $popup_request_data = array(
   'call_back_function'  => 'set_return',
   'form_name'           => 'EditView',
   'field_to_name_array' => array(
    'id'        => 'assigned_user_id',
    'user_name' => 'assigned_user_name',
    ),
   );
  $xtpl->assign('encoded_users_popup_request_data', $json->encode($popup_request_data));
  
///////////////////////////////////////////////////////////////////////////////
////	GENERAL TEMPLATE ASSIGNMENTS
//Assign the template variables
  $xtpl->assign('MOD',  $mod_strings);
  $xtpl->assign('APP',  $app_strings);
  $xtpl->assign('name', $focus->name);
  
  if (empty($focus->assigned_user_id) && empty($focus->id))  $focus->assigned_user_id = $current_user->id;
  if (empty($focus->assigned_name) && empty($focus->id))     $focus->assigned_user_name = $current_user->user_name;
  $xtpl->assign("ASSIGNED_USER_OPTIONS",                     get_select_options_with_id(get_user_array(TRUE, "Active", $focus->assigned_user_id), $focus->assigned_user_id));
  $xtpl->assign("ASSIGNED_USER_NAME",                        $focus->assigned_user_name);
  $xtpl->assign("ASSIGNED_USER_ID",                          $focus->assigned_user_id );
  
  $xtpl->assign('description', $focus->description);
  $change_parent_button = "<input title='".$app_strings['LBL_SELECT_BUTTON_TITLE']
   ."' accessKey='".$app_strings['LBL_SELECT_BUTTON_KEY']
   ."' tabindex='2' type='button' class='button' value='"
   .$app_strings['LBL_SELECT_BUTTON_LABEL']
   ."' name='button' LANGUAGE=javascript onclick='return window.open(\"index.php?module=\"+ document.EditView.parent_type.value + \"&action=Popup&html=Popup_picker&form=TasksEditView\",\"test\",\"width=600,height=400,resizable=1,scrollbars=1\");'>";
  $xtpl->assign("CHANGE_PARENT_BUTTON", $change_parent_button);
  
  if (!empty($_REQUEST['opportunity_name']) && empty($focus->name)) {
   $focus->name = $_REQUEST['opportunity_name'];
  }
  if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
   $focus->id = "";
  }
  
//Linked record ids
  if(isset($_REQUEST['account_id']))     $xtpl->assign("ACCOUNT_ID",     $_REQUEST['account_id']);
  if(isset($_REQUEST['contact_id']))     $xtpl->assign("CONTACT_ID",     $_REQUEST['contact_id']);
  
  if (isset($_REQUEST['return_module'])) $xtpl->assign("RETURN_MODULE",  $_REQUEST['return_module']);
  if (isset($_REQUEST['return_action'])) $xtpl->assign("RETURN_ACTION",  $_REQUEST['return_action']);
  if (isset($_REQUEST['return_id']))     $xtpl->assign("RETURN_ID",      $_REQUEST['return_id']);
  
//Handle Create $module then Cancel
  if (empty($_REQUEST['return_id'])) {
   $xtpl->assign("RETURN_ACTION", 'index');
  }
	///////////////////////////////////////////////////////////////////////////////
	////	QUICKSEARCH CODE
  require_once('include/QuickSearchDefaults.php');
  $sqs_objects     = array('assigned_user_name' => $qsUser,
                           'team_name'          => $qsTeam);
  $quicksearch_js  = $qsScripts;
  $quicksearch_js .= '<script type="text/javascript" language="javascript">sqs_objects = ' . $json->encode($sqs_objects) . '</script>';
  
  $xtpl->assign("JAVASCRIPT", get_set_focus_js().get_validate_record_js() . $quicksearch_js);
	////	END QUICKSEARCH CODE
	///////////////////////////////////////////////////////////////////////////////


  $xtpl->assign("THEME",      $theme);
  $xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "index.php?".$GLOBALS['request_string']);
  $xtpl->assign("ID",         $focus->id);
  $xtpl->assign("NAME",       $focus->name);
//BUILDER: included fields
  $xtpl->assign('CALENDAR_DATEFORMAT', '%Y-%m-%d');  $xtpl->assign('USER_DATEFORMAT', 'YYYY-mm-dd');//BUILDER:END of xtpl 

//Add Custom Fields
  require_once('modules/DynamicFields/templates/Files/EditView.php');
  
///////////////////////////////////////
////	USER ASSIGNMENT
  global $current_user;
  if(is_admin($current_user)
   && $_REQUEST['module'] != 'DynamicLayout'
   && !empty($_SESSION['editinplace']))
  {
   $record = '';
   if(!empty($_REQUEST['record'])){
    $record =  $_REQUEST['record'];
   }
   $xtpl->assign("ADMIN_EDIT","<a href='index.php?action=index&module=DynamicLayout&from_action="
    .$_REQUEST['action'] ."&from_module=".$_REQUEST['module']
    ."&record=".$record. "'>".get_image($image_path
    ."EditLayout","border='0' alt='Edit Layout' align='bottom'")."</a>"); 
  }
  
  $xtpl->parse("main.open_source");
  $xtpl->parse("main");
  $xtpl->out("main");
  require_once('include/javascript/javascript.php');
  $javascript = new javascript();
  $javascript->setFormName('EditView');
  $javascript->setSugarBean($focus);
  $javascript->addAllFields('');
  $javascript->addToValidateBinaryDependency('assigned_user_name', 'alpha', $app_strings['ERR_SQS_NO_MATCH_FIELD'] . $app_strings['LBL_ASSIGNED_TO'], 'false', '', 'assigned_user_id');
  
  echo $javascript->getScript();
  
?>
