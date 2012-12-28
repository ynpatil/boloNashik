<?php

$subpanel_layout = array(

	'top_buttons' => array(
        array('widget_class' => 'SubPanelTopCreateButton'),
        array('widget_class' => 'SubPanelTopSelectButton', 'popup_module' => 'Forums'),
	),

	'where' => '',
	'default_order_by' => 'forums.title',

	'list_fields' => array(
		'title'=>array(
			'vname' => 'LBL_TITLE',
			'widget_class' => 'SubPanelDetailViewLink',
			'width' => '75%',
		),
/*
		'created_by'=> array(
		    'vname' => 'LBL_CREATED_BY',
		    'widget_class' => 'SubPanelDetailViewLink',
		    'width' => '15%',
		),
*/
    'date_modified' => array(
      'vname' => 'LBL_DATE_MODIFIED',
      'width' => '15%',
    ),
		'edit_button'=>array(
			'widget_class' => 'SubPanelEditButton',
		 	'module' => 'Posts',
			'width' => '5%',
		),
		'remove_button'=>array(
			'widget_class' => 'SubPanelRemoveButton',
		 	'module' => 'Posts',
			'width' => '5%',
		),
	),
);
?>
