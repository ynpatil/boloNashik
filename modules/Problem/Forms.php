<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

/*******************************************************************************
 * Forms for Problem object
 ******************************************************************************/

global $theme;
require_once('themes/'.$theme.'/layout_utils.php');
require_once('log4php/LoggerManager.php');
require_once('XTemplate/xtpl.php');
require_once('include/utils.php');
require_once('include/JSON.php');

$image_path = 'themes/'.$theme.'/images/';

function get_new_record_form(){
 if(!ACLController::checkAccess('Problem', 'edit', true))return '';
 global $app_strings;
 global $mod_strings;
 global $currentModule;
 global $current_user;

 $the_form = get_left_form_header($mod_strings['LBL_QUICK_NEW_PROBLEM']);
 $form = new XTemplate ('modules/Problem/Forms.html');

 $module_select = empty($_REQUEST['module_select']) ? ''
  : $_REQUEST['module_select'];
 $form->assign('mod',    $mod_strings);
 $form->assign('app',    $app_strings);
 $form->assign('module', $currentModule);

 $json = new JSON(JSON_LOOSE_TYPE);
 $popup_request_data = array(
  'call_back_function'  => 'set_return',
  'form_name'           => 'quick_save',
  'field_to_name_array' => array(
   'id'                   => 'assigned_user_id',
   'user_name'            => 'assigned_user_name',
   ),
  );
 $form->assign('encoded_users_popup_request_data', $json->encode($popup_request_data));
 
 $form->parse('main');
 $the_form .= $form->text('main');
 
 require_once('modules/Problem/Problem.php');
 $focus = new Problem();
   
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
 $jscript = $javascript->getScript();

 $the_form .= $jscript . get_left_form_footer();

 return $the_form;
}

/**
 * Create javascript to validate the data entered into a record.
 */
function get_validate_record_js (){
 return '';
}

?>
