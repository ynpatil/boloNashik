<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

/*******************************************************************************
 * Subpanel Layout definition for ProblemSolutions
 ******************************************************************************/

$subpanel_layout = array(
    'top_buttons' => array(
        array('widget_class' => 'SubPanelTopSelectButton', 'popup_module' => 'ProblemSolution'),
        array('widget_class' => 'SubPanelTopCreateButton'),
    ),

    'where'             => '',
    'default_order_by'  => '',

    'list_fields' => array(
        'name'=>array(
            'vname'         => 'LBL_LIST_NAME',
            'widget_class'  => 'SubPanelDetailViewLink',
            'width'         => '15%',
        ),
        'status'=>array(
            'vname'         => 'LBL_LIST_STATUS',
            'width'         => '15%',
        ),
        'assigned_user_name'=>array(
            'vname'         => 'LBL_LIST_ASSIGNED_USER_ID',
            'module'        => 'Users',
            'width'         => '15%',
        ),
    ),
);

?>
