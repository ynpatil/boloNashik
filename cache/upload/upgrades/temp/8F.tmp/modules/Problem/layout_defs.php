<?php
if(empty($GLOBALS['sugarEntry'])) die('Not A Valid Entry Point');
/*******************************************************************************
 * SubPanel layout definition for Problem
 ******************************************************************************/

$layout_defs['Problem'] = array(
    // list of what Subpanels to show in the DetailView
    'subpanel_setup' => array(
        'solutions'  => array(
          'top_buttons'          => array(
        array('widget_class' => 'SubPanelTopSelectButton', 'popup_module' => 'ProblemSolution'),
        array('widget_class' => 'SubPanelTopCreateButton'),
          ),
          'order'               => 10,
          'module'              => 'ProblemSolution',
          'subpanel_name'       => 'default',
          'title_key'           => 'LBL_PROBLEM_SOLUTIONS_SUBPANEL_TITLE',
          'get_subpanel_data'   => 'related_solutions',
        ),
    ),
);
?>
