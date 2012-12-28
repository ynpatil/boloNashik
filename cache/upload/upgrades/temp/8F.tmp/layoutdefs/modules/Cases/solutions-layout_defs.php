<?php
/*********************************************************************************
 * Definition of SubPanel Solutions for Case object
 ********************************************************************************/
$layout_defs['Cases']['subpanel_setup']['solutions'] = array(
            'top_buttons' => array( array('widget_class' => 'SubPanelTopSelectButton', 
                                          'popup_module' => 'ProblemSolution'), ),
            'order'                     => 500,
            'module'                    => 'ProblemSolution',
            'subpanel_name'             => 'default',
            'override_subpanel_name'    => 'solutions',
            'get_subpanel_data'         => 'solutions',
            'add_subpanel_data'         => 'solution_id',
            'title_key'                 => 'LBL_SOLUTIONS_SUBPANEL_TITLE',
);
?>

