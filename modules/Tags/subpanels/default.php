<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

$subpanel_layout = array(

  'top_buttons' => array(
    array('widget_class' => 'SubPanelTopCreateButton'),
    array('widget_class' => 'SubPanelTopSelectButton', 'popup_module' => 'Tags'),
  ),

  'where' => '',
  'default_order_by' => 'tags.title',

  'list_fields' => array(

    'title'=>array(
      'name' => 'LBL_TITLE',
      'module' => 'Tags',
      'widget_class' => 'SubPanelDetailViewLink',
      'width' => '75%',
    ),
    'remove_button'=>array(
      'widget_class' => 'SubPanelRemoveButton',
       'module' => 'Tags',
      'width' => '5%',
    ),

  ),
);
?>
