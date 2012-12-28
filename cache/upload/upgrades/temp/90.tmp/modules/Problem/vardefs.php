<?php
if(empty($GLOBALS['sugarEntry'])) die('Not A Valid Entry Point');
/*******************************************************************************
 * Table definition file for the Problem object
 ******************************************************************************/

$dictionary['Problem'] = array('audited'=>true,
    'table' => 'problem',
    'fields' => array(
        'id' => array(
            'name'          => 'id',
            'vname'         => 'LBL_ID',
            'required'      => true,
            'type'          => 'id',
            'reportable'    =>false,
        ),
        'date_entered' => array(
            'name'          => 'date_entered',
            'vname'         => 'LBL_DATE_ENTERED',
            'type'          => 'datetime',
            'required'      => true,
        ),
        'date_modified' => array(
            'name'          => 'date_modified',
            'vname'         => 'LBL_DATE_MODIFIED',
            'type'          => 'datetime',
            'required'      => true,
        ),
        'assigned_user_id' => array(
            'name'          => 'assigned_user_id',
            'rname'         => 'user_name',
            'id_name'       => 'assigned_user_id',
            'type'          => 'assigned_user_name',
            'vname'         => 'LBL_ASSIGNED_USER_ID',
            'required'      => false,
            'len'           => 36,
            'dbType'        => 'id',
            'table'         => 'users',
            'isnull'        => false,
            'reportable'    =>true,
        ),
        'modified_user_id' => array(
            'name'          => 'modified_user_id',
            'rname'         => 'user_name',
            'id_name'       => 'modified_user_id',
            'vname'         => 'LBL_MODIFIED_USER_ID',
            'type'          => 'assigned_user_name',
            'table'         => 'users',
            'isnull'        => 'false',
            'dbType'        => 'id',
            'reportable'    =>true,
        ),
        'created_by' => array(
            'name'          => 'created_by',
            'rname'         => 'user_name',
            'id_name'       => 'modified_user_id',
            'vname'         => 'LBL_CREATED_BY',
            'type'          => 'assigned_user_name',
            'table'         => 'users',
            'isnull'        => 'false',
            'dbType'        => 'id',
        ),
        'name' => array(
            'name'          => 'name',
            'vname'         => 'LBL_NAME',
            'required'      => true,
            'dbType'        => 'varchar',
            'type'          => 'name',
            'len'           => 50,
        ),
        'status' => array(
            'name'              => 'status',
            'vname'             => 'LBL_STATUS',
            'type'              => 'enum',
            'required'          => false,
            'options'           => 'problem_status_options',
            'audited'           =>true,
        ),
        'class' => array(
            'name'              => 'class',
            'vname'             => 'LBL_CLASS',
            'type'              => 'enum',
            'required'          => false,
            'options'           => 'problem_class_options',
            'audited'           =>true,
        ),
        'description' => array(
            'name'          => 'description',
            'vname'         => 'LBL_DESCRIPTION',
            'required'      => true,
            'type'          => 'text',
        ),
        'deleted' => array(
            'name'          => 'deleted',
            'vname'         => 'LBL_DELETED',
            'type'          => 'bool',
            'required'      => true,
            'default'       => '0',
        ),  
        'notes' => array (
            'name'          => 'notes',
            'type'          => 'link',
            'relationship'  => 'problem_notes',
            'source'        =>'non-db',
            'vname'         =>'LBL_NOTES',
        ),
        'emails' => array (
            'name'          => 'emails',
            'type'          => 'link',
            'relationship'  => 'problem_emails',
            'source'        =>'non-db',
            'vname'         =>'LBL_EMAILS',
        ),
        'related_solutions' => array (
            'name'          => 'related_solutions',
            'type'          => 'link',
            'relationship'  => 'problem_problemsolutions',
            'source'        =>'non-db',
            'vname'         =>'LBL_PROBLEM_SOLUTIONS',
        ),
        'created_by_link' => array (
            'name'          => 'created_by_link',
            'type'          => 'link',
            'relationship'  => 'problems_created_by',
            'vname'         => 'LBL_CREATED_BY_USER',
            'link_type'     => 'one',
            'module'        =>'Users',
            'bean_name'     =>'User',
            'source'        =>'non-db',
        ),
        'modified_user_link' => array (
            'name'          => 'modified_user_link',
            'type'          => 'link',
            'relationship'  => 'problems_modified_user',
            'vname'         => 'LBL_MODIFIED_BY_USER',
            'link_type'     => 'one',
            'module'        =>'Users',
            'bean_name'     =>'User',
            'source'        =>'non-db',
        ),
        'assigned_user_link' => array (
            'name'          => 'assigned_user_link',
            'type'          => 'link',
            'relationship'  => 'problems_assigned_user',
            'vname'         => 'LBL_ASSIGNED_TO_USER',
            'link_type'     => 'one',
            'module'        => 'Users',
            'bean_name'     => 'User',
            'source'        => 'non-db',
        ),
    ),
    'relationships' => array(
        'problem_notes' => array(
            'lhs_module'=> 'Problem', 'lhs_table'=> 'problem', 'lhs_key' => 'id',
            'rhs_module'=> 'Notes', 'rhs_table'=> 'notes', 'rhs_key' => 'parent_id',    
            'relationship_type'=>'one-to-many', 'relationship_role_column'=>'parent_type',
            'relationship_role_column_value'=>'Problem'),
        'problem_problemsolutions' => array(
            'lhs_module'=> 'Problem',                'lhs_table'=> 'problem',            'lhs_key' => 'id',
            'rhs_module'=> 'ProblemSolution',        'rhs_table'=> 'problem_solution',   'rhs_key' => 'parent_id',   
            'relationship_type'=>'one-to-many'),    
        'problem_emails' => array(
            'lhs_module'=> 'Problem',                'lhs_table'=> 'problem', 'lhs_key' => 'id',
            'rhs_module'=> 'Emails',                 'rhs_table'=> 'emails', 'rhs_key' => 'parent_id',  
            'relationship_type'=>'one-to-many',      'relationship_role_column'=>'parent_type',
            'relationship_role_column_value'=>'Problem'),
'problems_assigned_user' =>
   array('lhs_module'=> 'Users', 'lhs_table'=> 'users', 'lhs_key' => 'id',
   'rhs_module'=> 'Problem', 'rhs_table'=> 'problem', 'rhs_key' => 'assigned_user_id',
   'relationship_type'=>'one-to-many')

   ,'problems_modified_user' =>
   array('lhs_module'=> 'Users', 'lhs_table'=> 'users', 'lhs_key' => 'id',
   'rhs_module'=> 'Problem', 'rhs_table'=> 'problem', 'rhs_key' => 'modified_user_id',
   'relationship_type'=>'one-to-many')

   ,'problems_created_by' =>
   array('lhs_module'=> 'Users', 'lhs_table'=> 'users', 'lhs_key' => 'id',
   'rhs_module'=> 'Problem', 'rhs_table'=> 'problem', 'rhs_key' => 'created_by',
   'relationship_type'=>'one-to-many')
   ),
    'indices' => array(
        array('name'   => 'keywords_index',
              'type'   => 'fulltext',
              'fields' => array('name','description')
        ),
        array('name'   => 'pro_primary',
              'type'   => 'primary',
              'fields' => array('id')
        ),
    ),
);
?>
