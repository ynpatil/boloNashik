<?php
if(empty($GLOBALS['sugarEntry'])) die('Not A Valid Entry Point');
/*********************************************************************************
 * 
 ********************************************************************************/
$acldefs['ProblemSolution'] = array (
  'forms' => array (
    'by_name' => array (
      'change_problem'      => array (
        'display_option'    => 'disabled',
        'action_option'     => 'list',
        'app_action'        => 'EditView',
        'module'            => 'Problem',
      ),
      'change_parent' => array (
        'display_option'    => 'disabled',
        'action_option'     => 'list',
        'app_action'        => 'EditView',
        'module'            => 'ProblemSolution',
      ),
    ),
  ),
  'form_names' => array (
    'by_id'                 => 'by_id',
    'by_name'               => 'by_name',
    'DetailView'            => 'DetailView',
    'EditView'              => 'EditView',
  ),
);
?>
