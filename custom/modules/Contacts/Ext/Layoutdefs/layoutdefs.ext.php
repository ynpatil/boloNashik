<?php 
 //WARNING: The contents of this file are auto-generated

 
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

$layout_defs['Contacts']['subpanel_setup']['tags'] = array(
  'order'             => 140,
  'module'            => 'Tags',
  'sort_order'        => 'asc',
  'sort_by'           => 'date_modified',
  'get_subpanel_data' => 'tags',
  'add_subpanel_data' => 'tag_id',
  'subpanel_name'     => 'default',
  'title_key'         => 'LBL_TAGS_SUBPANEL_TITLE',

  'top_buttons' => array(
    array('widget_class' => 'SubPanelTopSelectButton'),
  )
);



?>