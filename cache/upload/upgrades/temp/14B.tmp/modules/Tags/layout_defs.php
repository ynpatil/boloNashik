<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

$layout_defs['Tags']['subpanel_setup']['contacts'] = array
(
  'order'             => 10,
  'module'            => 'Contacts',
  'sort_order'        => 'asc',
  'sort_by'           => 'last_name',
  'subpanel_name'     => 'default',
  'get_subpanel_data' => 'contacts',
  'add_subpanel_data' => 'contact_id',
  'title_key'         => 'LBL_CONTACTS_SUBPANEL_TITLE',

  'top_buttons' => array
  (
    array
    (
      'widget_class' => 'SubPanelTopSelectButton', 
      'mode'         => 'MultiSelect',
    ),
  ),
);

?>
