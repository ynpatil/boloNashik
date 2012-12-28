<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

/**
 * Define Form for QuickSave left bar
 */

// $Id: Forms.php,v 1.14.4.1 2006/01/08 04:36:05 majed Exp $

global $theme;
require_once('themes/'.$theme.'/layout_utils.php');
require_once('log4php/LoggerManager.php');
require_once('XTemplate/xtpl.php');
require_once('include/utils.php');
require_once('include/JSON.php');
$image_path = 'themes/'.$theme.'/images/';

function get_new_record_form()
{
 if(!ACLController::checkAccess('ProblemSolution', 'edit', true))return '';
 global $app_strings;
 global $mod_strings;
 global $currentModule;
 global $current_user;

 $the_form = get_left_form_header($mod_strings['LBL_NEW_FORM_TITLE']);
 $form     = new XTemplate ('modules/ProblemSolution/Forms.html');

 $module_select = empty($_REQUEST['module_select']) ? ''
  : $_REQUEST['module_select'];
 $form->assign('mod',    $mod_strings);
 $form->assign('app',    $app_strings);
 $form->assign('module', $currentModule);
 $options = get_select_options_with_id(get_user_array(), $current_user->id);
 $form->assign('ASSIGNED_USER_OPTIONS', $options);

 ///////////////////////////////////////
 ///
 /// SETUP ACCOUNT POPUP
 
 $popup_request_data = array(
  'call_back_function'   => 'set_return',
  'form_name'            => "quick_save",
  'field_to_name_array'  => array(
   'id'                  => 'parent_id',
   'name'                => 'problem_name',
  ),
 );
 
 $json                       = new JSON(JSON_LOOSE_TYPE);
 $encoded_popup_request_data = $json->encode($popup_request_data);
 
 ///////////////////////////////////////
 
 $form->assign('encoded_popup_request_data', $encoded_popup_request_data);
 $form->parse('main');
 $the_form .= $form->text('main');

 require_once('modules/ProblemSolution/ProblemSolution.php');
 $focus = new Solution();

 require_once('include/javascript/javascript.php');
 $javascript = new javascript();
 $javascript->setFormName('quick_save');
 $javascript->setSugarBean($focus);
 $javascript->addRequiredFields('');
 $jscript    = $javascript->getScript();

 $the_form .= $jscript . get_left_form_footer();
 return $the_form;
}

/**
 * Create javascript to validate the data entered into a record.
 */
function get_validate_record_js () {
 return '';
}

?>
