<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

/*******************************************************************************
 * Subpanel Layout definition for Problems
 ******************************************************************************/

$subpanel_layout = array(
    'top_buttons' => array(
        array('widget_class' => 'SubPanelTopCreateButton'),
        array('widget_class' => 'SubPanelTopSelectButton', 'popup_module' => 'Problem'),
    ),

    'where'                     => '',
    'default_order_by'          => '',
    'fill_in_additional_fields' =>true,
    'list_fields' => array(
        'name'=>array(
            'vname'             => 'LBL_LIST_NAME',
            'widget_class'      => 'SubPanelDetailViewLink',
            'width'             => '35%',
        ),
        'assigned_user_name'=>array(
            'vname'             => 'LBL_LIST_ASSIGNED_USER_ID',
            'widget_class'      => 'SubPanelDetailViewLink',
            'module'            => 'Users',
            'target_record_key' => 'assigned_user_id',
            'target_module'     => 'Users',
            'width'             => '15%',
             'sortable'         =>false, 
        ),
    ),
);

?>
