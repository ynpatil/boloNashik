<?PHP
// File is irrelevant

$layout_defs['Threads']['subpanel_setup']['pos '] =  array(
			'order' => 10,
			'module' => 'Posts',
			'get_subpanel_data' => 'posts',
			//'add_subpanel_data' => 'thread_id',
			'subpanel_name' => 'default',
			'title_key' => 'LBL_POSTS_SUBPANEL_TITLE',
			'top_buttons' => array(
				array('widget_class' => 'SubPanelTopSelectButton'),
				array('widget_class' => 'SubPanelTopCreateButton'),
			),
		);



?>
