<?php

$dictionary['TeamOS'] = array(
    'table' => 'teams',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'vname' => 'LBL_ID',
            'type' => 'id',
            'required' => true,
        ),
        'name' => array(
            'name' => 'name',
            'vname' => 'LBL_NAME',
            'type' => 'char',
            'len' => '255',
            'required' => true
        ),
        'private' => array(
            'name' => 'private',
            'vname' => 'LBL_PRIVATE',
            'type' => 'bool',
            'required' => false
        ),
        'deleted' => array(
            'name' => 'deleted',
            'vname' => 'LBL_DELETED',
            'type' => 'bool',
            'required' => false
        ),
        'level_id' => array(
            'name' => 'level_id',
            'vname' => 'LBL_LEVEL',
            'type' => 'varchar',
            'len' => '36',
            'comment' => 'Level Id'
        ),
        'level_name' =>
        array(
            'name' => 'level_name',
            'vname' => 'LBL_LEVEL_NAME',
            'type' => 'relate',
            'reportable' => false,
            'source' => 'nondb',
            'table' => 'level_mast',
            'id_name' => 'level_id',
            'module' => 'LevelMaster',
            'duplicate_merge' => 'disabled'
        ),
        'experience_id' => array(
            'name' => 'experience_id',
            'vname' => 'LBL_EXPERIENCE',
            'dbType' => 'varchar',
            'len' => '36',
            'comment' => 'Experience  Id',
        ),
        'experience_name' =>
        array(
            'name' => 'experience_name',
            'vname' => 'LBL_EXPERIENCE_NAME',
            'type' => 'relate',
            'reportable' => false,
            'source' => 'nondb',
            'table' => 'experience_mast',
            'id_name' => 'experience_id',
            'module' => 'ExperienceMaster',
            'duplicate_merge' => 'disabled'
        ),
        'language_id' => array(
            'name' => 'language_id',
            'vname' => 'LBL_LANGUAGE',
            'dbType' => 'varchar',
            'len' => '36',
            'comment' => 'Experience  Id',
        ),
        'language_name' =>
        array(
            'name' => 'language_name',
            'vname' => 'LBL_LANGUAGE_NAME',
            'type' => 'relate',
            'reportable' => false,
            'source' => 'nondb',
            'table' => 'language_mast',
            'id_name' => 'language_id',
            'module' => 'LanguageMaster',
            'duplicate_merge' => 'disabled'
        ),
        'email' => array(
            'name' => 'email',
            'vname' => 'LBL_EMAIL',
            'type' => 'text',
            'dbType' => 'text',
            'len' => '50',
            'required' => true,
            'audited' => true
        ),
        'date_entered' => array(
            'name' => 'date_entered',
            'vname' => 'LBL_DATE_ENTERED',
            'type' => 'datetime',
            'required' => false
        ),
        'date_modified' => array(
            'name' => 'date_modified',
            'vname' => 'LBL_DATE_MODIFIED',
            'type' => 'datetime',
            'required' => false
        ),
        'users' => array(
            'name' => 'users',
            'type' => 'link',
            'relationship' => 'team_membership',
            'module' => 'Users',
            'bean_name' => 'User',
            'source' => 'non-db',
            'vname' => 'LBL_USERS'
        ),
        'brand' =>
        array(
            'name' => 'brand',
            'type' => 'link',
            'relationship' => 'team_brand',
            'source' => 'non-db',
            'module' => 'Brands',
            'bean_name' => 'Brands',
            'source' => 'non-db',
            'vname' => 'LBL_BRAND',
        ),
        'region' =>
        array(
            'name' => 'region',
            'type' => 'link',
            'relationship' => 'team_region',
            'source' => 'non-db',
            'module' => 'RegionMaster',
            'bean_name' => 'Region',
            'source' => 'non-db',
            'vname' => 'LBL_REGION',
        ),
        'city' =>
        array(
            'name' => 'city',
            'type' => 'link',
            'relationship' => 'team_city',
            'source' => 'non-db',
            'module' => 'CityMaster',
            'bean_name' => 'City',
            'source' => 'non-db',
            'vname' => 'LBL_CITY',
        ),
        'state' =>
        array(
            'name' => 'state',
            'type' => 'link',
            'relationship' => 'team_state',
            'source' => 'non-db',
            'module' => 'StateMaster',
            'bean_name' => 'State',
            'source' => 'non-db',
            'vname' => 'LBL_STATE',
        ),
        'language' =>
        array(
            'name' => 'language',
            'type' => 'link',
            'relationship' => 'team_language',
            'source' => 'non-db',
            'module' => 'LanguageMaster',
            'bean_name' => 'Language',
            'source' => 'non-db',
            'vname' => 'LBL_LANGUAGE',
        ),
        'level' =>
        array(
            'name' => 'level',
            'type' => 'link',
            'relationship' => 'team_level',
            'source' => 'non-db',
            'module' => 'LevelMaster',
            'bean_name' => 'Level',
            'source' => 'non-db',
            'vname' => 'LBL_LEVEL',
        ),
        'experience' =>
        array(
            'name' => 'experience',
            'type' => 'link',
            'relationship' => 'team_experience',
            'source' => 'non-db',
            'module' => 'ExperienceMaster',
            'bean_name' => 'Experience',
            'source' => 'non-db',
            'vname' => 'LBL_EXPERIENCE',
        ),
    ),
    'indices' => array(
        array('name' => 'teamspk', 'type' => 'primary', 'fields' => array('id'))
    ),
    'relationships' => array(
        'team_region' => array(
            'lhs_module' => 'TeamsOS',
            'lhs_table' => 'teams',
            'lhs_key' => 'id',
            'rhs_module' => 'RegionMaster',
            'rhs_table' => 'region_mast',
            'rhs_key' => 'id',
            'relationship_type' => 'many-to-many',
            'join_table' => 'team_region',
            'join_key_lhs' => 'team_id',
            'join_key_rhs' => 'region_id',
            'relationship_role_column' => NULL,
            'relationship_role_column_value' => NULL
        ),
        'team_brand' => array(
            'lhs_module' => 'TeamsOS',
            'lhs_table' => 'teams',
            'lhs_key' => 'id',
            'rhs_module' => 'Brands',
            'rhs_table' => 'brands',
            'rhs_key' => 'id',
            'relationship_type' => 'many-to-many',
            'join_table' => 'team_brand',
            'join_key_lhs' => 'team_id',
            'join_key_rhs' => 'brand_id',
            'relationship_role_column' => NULL,
            'relationship_role_column_value' => NULL
        ),
        'team_city' => array(
            'lhs_module' => 'TeamsOS',
            'lhs_table' => 'teams',
            'lhs_key' => 'id',
            'rhs_module' => 'CityMaster',
            'rhs_table' => 'city_mast',
            'rhs_key' => 'id',
            'relationship_type' => 'many-to-many',
            'join_table' => 'team_city',
            'join_key_lhs' => 'team_id',
            'join_key_rhs' => 'city_id',
            'relationship_role_column' => NULL,
            'relationship_role_column_value' => NULL
        ),
        'team_state' => array(
            'lhs_module' => 'TeamsOS',
            'lhs_table' => 'teams',
            'lhs_key' => 'id',
            'rhs_module' => 'StateMaster',
            'rhs_table' => 'state_mast',
            'rhs_key' => 'id',
            'relationship_type' => 'many-to-many',
            'join_table' => 'team_state',
            'join_key_lhs' => 'team_id',
            'join_key_rhs' => 'state_id',
            'relationship_role_column' => NULL,
            'relationship_role_column_value' => NULL
        ),
        'team_language' => array(
            'lhs_module' => 'TeamsOS',
            'lhs_table' => 'teams',
            'lhs_key' => 'id',
            'rhs_module' => 'LanguageMaster',
            'rhs_table' => 'language_mast',
            'rhs_key' => 'id',
            'relationship_type' => 'many-to-many',
            'join_table' => 'team_language',
            'join_key_lhs' => 'team_id',
            'join_key_rhs' => 'language_id',
            'relationship_role_column' => NULL,
            'relationship_role_column_value' => NULL
        ),
        'team_level' => array(
            'lhs_module' => 'TeamsOS',
            'lhs_table' => 'teams',
            'lhs_key' => 'id',
            'rhs_module' => 'LevelMaster',
            'rhs_table' => 'level_mast',
            'rhs_key' => 'id',
            'relationship_type' => 'many-to-many',
            'join_table' => 'team_level',
            'join_key_lhs' => 'team_id',
            'join_key_rhs' => 'level_id',
            'relationship_role_column' => NULL,
            'relationship_role_column_value' => NULL
        ),
        'team_experience' => array(
            'lhs_module' => 'TeamsOS',
            'lhs_table' => 'teams',
            'lhs_key' => 'id',
            'rhs_module' => 'ExperienceMaster',
            'rhs_table' => 'experience_mast',
            'rhs_key' => 'id',
            'relationship_type' => 'many-to-many',
            'join_table' => 'team_experience',
            'join_key_lhs' => 'team_id',
            'join_key_rhs' => 'experience_id',
            'relationship_role_column' => NULL,
            'relationship_role_column_value' => NULL
        ),
    )
);
?>
