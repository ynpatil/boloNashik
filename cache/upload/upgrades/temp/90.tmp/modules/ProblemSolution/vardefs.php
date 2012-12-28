<?php
if(empty($GLOBALS['sugarEntry'])) die('Not A Valid Entry Point');
/**
 * Table definition file for the solution table
 */

// $Id: vardefs.php,v 1.35.4.2 2006/01/19 03:02:49 wayne Exp $
$dictionary['ProblemSolution'] = array('audited'=>true,
    'table' => 'problem_solution',
    'fields' => array(
        'id' => array(
            'name'              => 'id',
            'vname'             => 'LBL_ID',
            'required'          => true,
            'type'              => 'id',
            'reportable'        =>false,
        ),
        'date_entered' => array(
            'name'              => 'date_entered',
            'vname'             => 'LBL_DATE_ENTERED',
            'type'              => 'datetime',
            'required'          => true,
        ),
        'date_modified' => array(
            'name'              => 'date_modified',
            'vname'             => 'LBL_DATE_MODIFIED',
            'type'              => 'datetime',
            'required'          => true,
        ),
        'assigned_user_id' => array(
            'name'              => 'assigned_user_id',
            'rname'             => 'user_name',
            'id_name'           => 'assigned_user_id',
            'type'              => 'assigned_user_name',
            'vname'             => 'LBL_ASSIGNED_USER_ID',
            'required'          => false,
            'dbType'            => 'id',
            'table'             => 'users',
            'isnull'            => false,
            'reportable'        =>true,
            'audited'           =>true,
        ),
        'modified_user_id' => array(
            'name'              => 'modified_user_id',
            'rname'             => 'user_name',
            'id_name'           => 'modified_user_id',
            'vname'             => 'LBL_MODIFIED_USER_ID',
            'type'              => 'assigned_user_name',
            'table'             => 'users',
            'isnull'            => 'false',
            'dbType'            => 'id',
            'reportable'        =>true,
        ),
        'created_by' => array(
            'name'              => 'created_by',
            'rname'             => 'user_name',
            'id_name'           => 'modified_user_id',
            'vname'             => 'LBL_CREATED_BY',
            'type'              => 'assigned_user_name',
            'table'             => 'users',
            'isnull'            => 'false',
            'dbType'            => 'id',
            'reportable'        =>true,
        ),
        'name' => array(
            'name'              => 'name',
            'vname'             => 'LBL_NAME',
            'required'          => true,
            'dbType'            => 'varchar',
            'type'              => 'name',
            'len'               => 50,
        ),
        'status' => array(
            'name'              => 'status',
            'vname'             => 'LBL_STATUS',
            'type'              => 'enum',
            'required'          => false,
            'options'           => 'solution_status_options',
            'audited'           =>true,
        ),
        'parent_id' => array(
            'name'              => 'parent_id',
            'vname'             => 'LBL_PARENT_ID',
            'required'          => true,
            'type'              => 'id',
            'reportable'        =>false,
        ),
        'description' => array(
            'name'              => 'description',
            'vname'             => 'LBL_DESCRIPTION',
            'required'          => false,
            'type'              => 'text',
        ),
        'order_number' => array(
            'name'              => 'order_number',
            'vname'             => 'LBL_ORDER_NUMBER',
            'required'          => false,
            'type'              => 'int',
            'default'           => '1',
        ),
        'solution_number' => array(
            'name'              => 'solution_number',
            'vname'             => 'LBL_SOLUTION_NUMBER',
            'required'          => false,
            'type'              => 'int',
        ),
        'depends_on_id' => array(
            'name'              => 'depends_on_id',
            'vname'             => 'LBL_DEPENDS_ON_ID',
            'required'          => false,
            'type'              => 'id',
            'reportable'        =>false,
        ),
        'deleted' => array(
            'name'              => 'deleted',
            'vname'             => 'LBL_DELETED',
            'type'              => 'bool',
            'required'          => true,
            'default'           => '0',
            'reportable'        =>false,
        ),
        'parent_name'=>    array(
            'name'              =>'parent_name',                 
            'rname'             =>'name',
            'id_name'           =>'parent_id',                 
            'vname'             =>'LBL_PARENT_NAME',
            'type'              =>'relate',
            'table'             =>'problem',
            'isnull'            =>'true',
            'module'            =>'Problem',
            'massupdate'        =>false,
            'source'            =>'non-db'
        ),
                
        'notes' => array (
            'name'              => 'notes',
            'type'              => 'link',
            'relationship'      => 'solution_notes',
            'source'            =>'non-db',
            'vname'             =>'LBL_NOTES',
        ),
        'emails' => array (
            'name'              => 'emails',
            'type'              => 'link',
            'relationship'      => 'solution_emails',
            'source'            =>'non-db',
            'vname'             =>'LBL_EMAILS',
        ),
        'cases' => array (
            'name'              => 'cases',
            'type'              => 'link',
            'relationship'      => 'cases_solutions',
            'source'            =>'non-db',
            'vname'             =>'LBL_CASES',
        ),
        'related_solutions' => array (
            'name'              => 'related_solutions',
            'type'              => 'link',
            'relationship'      => 'problem_problemsolutions',
            'source'            =>'non-db',
            'vname'             =>'LBL_LIST_PARENT_NAME',
        ),          
  'created_by_link' => array (
            'name'              => 'created_by_link',
            'type'              => 'link',
            'relationship'      => 'solution_created_by',
            'vname'             => 'LBL_CREATED_BY_USER',
            'link_type'         => 'one',
            'module'            =>'Users',
            'bean_name'         =>'User',
            'source'            =>'non-db',
  ),
  'modified_user_link' => array (
            'name'              => 'modified_user_link',
            'type'              => 'link',
            'relationship'      => 'solution_modified_user',
            'vname'             => 'LBL_MODIFIED_BY_USER',
            'link_type'         => 'one',
            'module'            =>'Users',
            'bean_name'         =>'User',
            'source'            =>'non-db',
  ),
  'problem_name_link' =>  array (
            'name'              => 'problem_name_link',
            'type'              => 'link',
            'relationship'      => 'problem_problem_solutions',
            'vname'             => 'LBL_PROJECT_NAME',
            'link_type'         => 'one',
            'module'            =>'Problem',
            'bean_name'         =>'Problem',
            'source'            =>'non-db',
  ),
  'assigned_user_link' => array (
            'name'              => 'assigned_user_link',
            'type'              => 'link',
            'relationship'      => 'solution_assigned_user',
            'vname'             => 'LBL_ASSIGNED_TO_USER',
            'link_type'         => 'one',
            'module'            =>'Users',
            'bean_name'         =>'User',
            'source'            =>'non-db',
  ),
  'assigned_user_name' => array (
            'name'              => 'assigned_user_name',
            'rname'             => 'user_name',
            'id_name'           => 'assigned_user_id',
            'vname'             => 'LBL_ASSIGNED_USER_NAME',
            'type'              => 'relate',
            'table'             => 'users',
            'module'            => 'Users',
            'dbType'            => 'varchar',
            'link'              =>'users',
            'len'               => '255',
            'source'            =>'non-db',
    ), 
 
    ),
    'indices' => array(
        array(
            'name' =>'problem_solution_primary_key_index',
            'type' =>'primary',
            'fields'=>array('id')
        ),
    ),
    
 'relationships' => array ( 
    'solution_notes' => array('lhs_module'=> 'ProblemSolution', 'lhs_table'=> 'problem_solution', 'lhs_key' => 'id',
                              'rhs_module'=> 'Notes', 'rhs_table'=> 'notes', 'rhs_key' => 'parent_id',  
                              'relationship_type'=>'one-to-many', 'relationship_role_column'=>'parent_type',
                              'relationship_role_column_value'=>'ProblemSolution')  
    ,'solution_emails' => array('lhs_module'=> 'ProblemSolution', 'lhs_table'=> 'problem_solution', 'lhs_key' => 'id',
                              'rhs_module'=> 'Emails', 'rhs_table'=> 'emails', 'rhs_key' => 'parent_id',    
                              'relationship_type'=>'one-to-many', 'relationship_role_column'=>'parent_type',
                              'relationship_role_column_value'=>'ProblemSolution')  

  ,'solution_assigned_user' =>
   array('lhs_module'=> 'Users', 'lhs_table'=> 'users', 'lhs_key' => 'id',
   'rhs_module'=> 'ProblemSolution', 'rhs_table'=> 'problem_solution', 'rhs_key' => 'assigned_user_id',
   'relationship_type'=>'one-to-many')

   ,'solution_modified_user' =>
   array('lhs_module'=> 'Users', 'lhs_table'=> 'users', 'lhs_key' => 'id',
   'rhs_module'=> 'ProblemSolution', 'rhs_table'=> 'problem_solution', 'rhs_key' => 'modified_user_id',
   'relationship_type'=>'one-to-many')

   ,'solution_created_by' =>
   array('lhs_module'=> 'Users', 'lhs_table'=> 'users', 'lhs_key' => 'id',
   'rhs_module'=> 'ProblemSolution', 'rhs_table'=> 'problem_solution', 'rhs_key' => 'created_by',
   'relationship_type'=>'one-to-many')

),
);

?>
