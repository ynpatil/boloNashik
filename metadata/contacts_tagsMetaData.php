<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');


$dictionary['contacts_tags'] = array(
  'table' => 'contacts_tags',

  'fields' => array(
    array('name'     => 'id', 
          'type'     => 'char', 
          'len'      => '36', 
          'required' => true, 
          'default'  => ''),
 
    array('name'     => 'contact_id', 
          'type'     => 'char', 
          'len'      => '36', 
          'required' => true, 
          'default'  => ''),

    array('name'     => 'tag_id', 
          'type'     => 'char', 
          'len'      => '36', 
          'required' => true, 
          'default'  => ''),

    array('name'     => 'deleted', 
          'type'     => 'tinyint', 
          'len'      => '1', 
          'required' => true, 
          'default'  => '0'),

    array('name'     => 'relationship_type', 
          'type'     => 'varchar', 
          'len'      => '50', 
          'required' => true, 
          'default'  => 'Contacts'),

    array('name' => 'date_modified', 
          'type' => 'datetime'),
  ),

  'relationships' => array(
    'contacts_tags' => array(
      'lhs_module'        => 'Contacts',
      'lhs_table'         => 'contacts',
      'lhs_key'           => 'id',
      'rhs_module'        => 'Tags',
      'rhs_table'         => 'tags',
      'rhs_key'           => 'id',
      'join_table'        => 'contacts_tags',
      'join_key_lhs'      => 'contact_id',
      'join_key_rhs'      => 'tag_id',
      'relationship_type' => 'many-to-many',
    )
  ),

  'indices' => array(
    array(
      'name'   => 'contacts_tagspk',
      'type'   => 'primary',
      'fields' => array('id')
    ),
    array(
      'name'   => 'idx_acc_tag_acc',
      'type'   => 'index',
      'fields' => array('contact_id')
    ),
    array(
      'name'   => 'idx_acc_tag_tag',
      'type'   => 'index',
      'fields' => array('tag_id')
    ),
    array(
      'name'   => 'idx_contacts_tags',
      'type'   => 'alternate_key',

      'fields' =>  array(
        'contact_id',
        'tag_id',
      )
    )
  )
);

?>
