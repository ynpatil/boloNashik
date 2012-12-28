<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

$dictionary['Tags'] = array
(
    'table'   => 'tags', 
    'comment' => 'Tags are ...',

    'fields' => array
    (

      'id' => array
      (
        'name'       => 'id',
        'vname'      => 'LBL_ID',
        'required'   => true,
        'type'       => 'id',
        'reportable' => false,
        'comment'    => 'Unique identifier',
      ),

      'date_entered' => array
      (
        'name'       => 'date_entered',
        'vname'      => 'LBL_DATE_ENTERED',
        'type'       => 'datetime',
        'required'   => true,
        'comment'    => 'Date record created',
      ),

      'created_by' => array
      (
        'name'    => 'created_by',
        'rname'   => 'user_name',
        'id_name' => 'created_by',
        'vname'   => 'LBL_CREATED_BY',
        'type'    => 'assigned_user_name',
        'table'   => 'users',
        'isnull'  => 'false',
        'dbType'  => 'id',
        'comment' => 'User who created record',
      ),

      'created_by_user_name' => array
      (
        'name'   => 'created_by_user_name',
        'source' => 'non-db',
      ),

      'date_modified' => array
      (
        'name'     => 'date_modified',
        'vname'    => 'LBL_DATE_MODIFIED',
        'type'     => 'datetime',
        'required' => true,
        'comment'  => 'Date record last modified',
      ),

      'modified_user_id' => array
      (
        'name'       => 'modified_user_id',
        'rname'      => 'user_name',
        'id_name'    => 'modified_user_id',
        'vname'      => 'LBL_MODIFIED_USER_ID',
        'type'       => 'assigned_user_name',
        'table'      => 'users',
        'isnull'     => 'false',
        'dbType'     => 'id',
        'required'   => true,
        'default'    => '0',
        'reportable' => true,
        'comment'    => 'User ID who last modified record',
      ),

      'modified_by_user_name' => array
      (
        'name'   => 'modified_by_user_name',
        'source' => 'non-db',
      ),
        
      'deleted' => array
      (
        'name'     => 'deleted',
        'vname'    => 'LBL_DELETED',
        'type'     => 'bool',
        'required' => true,
        'default'  => '0',
        'comment'  => 'Record deletion indicator',
      ),  

      'title' => array
      (
        'name'        => 'title',
        'vname'       => 'LBL_TITLE',
        'required'    => true,
        'type'        => 'varchar',
        'len'         => 255,
        'comment'     => 'Tag title',
        'reportable'  => true,
      ),

      'description' => array
      (
        'name'       => 'description',
        'vname'      => 'LBL_DESCRIPTION',
        'required'   => false,
        'type'       => 'text',
        'reportable' => true,
        'comment'    => 'Tag description',
      ),

      'contact_name' => array
      (
        'name'           => 'contact_name',
        'rname'          => 'name',
        'id_name'        => 'contact_id',
        'vname'          => 'LBL_CONTACT_NAME',
        'join_name'      => 'contacts',
        'type'           => 'relate',
        'link'           => 'contacts',
        'table'          => 'contacts',
        'isnull'         => 'true',
        'module'         => 'Contacts',
        'dbType'         => 'varchar',
        'len'            => '255',
        'source'         => 'non-db',
        'unified_search' => true, 
      ),

      'contact_id' => array 
      (
        'name'            => 'contact_id',
        'rname'           => 'id',
        'id_name'         => 'contact_id',
        'vname'           => 'LBL_CONTACT_ID',
        'type'            => 'relate',
        'table'           => 'contacts',
        'isnull'          => 'true',
        'module'          => 'Contacts',
        'dbType'          => 'varchar',
        'len'             => '255',
        'reportable'      => false,
        'source'          => 'non-db',
        'massupdate'      => false,
        'duplicate_merge' => 'disabled',
      ),

      'contacts' => array 
      (
        'name'            => 'contacts',
        'type'            => 'link',
        'relationship'    => 'contacts_tags',
        'link_type'       => 'one',
        'source'          => 'non-db',
        'vname'           => 'LBL_CONTACT',
        'duplicate_merge' => 'disabled',    
      ),
      
      'account_name' => array
      (
        'name'           => 'account_name',
        'rname'          => 'name',
        'id_name'        => 'account_id',
        'vname'          => 'LBL_ACCOUNT_NAME',
        'join_name'      => 'accounts',
        'type'           => 'relate',
        'link'           => 'accounts',
        'table'          => 'accounts',
        'isnull'         => 'true',
        'module'         => 'Accounts',
        'dbType'         => 'varchar',
        'len'            => '255',
        'source'         => 'non-db',
        'unified_search' => true, 
      ),

      'account_id' => array 
      (
        'name'            => 'account_id',
        'rname'           => 'id',
        'id_name'         => 'account_id',
        'vname'           => 'LBL_ACCOUNT_ID',
        'type'            => 'relate',
        'table'           => 'accounts',
        'isnull'          => 'true',
        'module'          => 'Accounts',
        'dbType'          => 'varchar',
        'len'             => '255',
        'reportable'      => false,
        'source'          => 'non-db',
        'massupdate'      => false,
        'duplicate_merge' => 'disabled',
      ),

      'accounts' => array 
      (
        'name'            => 'accounts',
        'type'            => 'link',
        'relationship'    => 'accounts_tags',
        'link_type'       => 'one',
        'source'          => 'non-db',
        'vname'           => 'LBL_ACCOUNT',
        'duplicate_merge' => 'disabled',    
      ),      
    ),

    'indices' => array
    (
      array
      (
        'name'   => 'tag_primary_key_index',
        'type'   => 'primary',
        'fields' => array('id'),
      ),
    ),
);
?>
