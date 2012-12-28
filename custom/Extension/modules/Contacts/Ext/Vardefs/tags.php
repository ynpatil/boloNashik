<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

$dictionary['Contact']['fields']['tags'] = array(
  'name'         => 'tags',
  'type'         => 'link',
  'relationship' => 'contacts_tags',
  'module'       => 'tags',
  'bean_name'    => 'Tags',
  'source'       => 'non-db',
  'vname'        => 'LBL_TAGS',
);

$dictionary['Contact']['relationships']['contacts_tags'] = array(
  'lhs_module'        => 'Contacts',
  'lhs_table'         => 'contacts',
  'lhs_key'           => 'id',
  'rhs_module'        => 'tag',
  'rhs_table'         => 'tags',
  'rhs_key'           => 'id',
  'relationship_type' => 'many-to-many',
  'join_table'        => 'contacts_tags',
  'join_key_lhs'      => 'contact_id',
  'join_key_rhs'      => 'tag_id'
);

?>
