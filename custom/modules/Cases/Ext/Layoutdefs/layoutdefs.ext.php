<?php 
 //WARNING: The contents of this file are auto-generated

 

$layout_defs['Cases']['subpanel_setup']['threads'] =  array(
            'order' => 83,
            'module' => 'Threads',
            'sort_order' => 'asc',
            'sort_by' => 'date_modified',
            'get_subpanel_data' => 'threads',
            'add_subpanel_data' => 'thread_id',
            'subpanel_name' => 'default',
            'title_key' => 'LBL_THREADS_SUBPANEL_TITLE',
            'top_buttons' => array(
                array('widget_class' => 'SubPanelTopCreateButton'),
                array('widget_class' => 'SubPanelTopSelectButton'),
            ),
		);



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