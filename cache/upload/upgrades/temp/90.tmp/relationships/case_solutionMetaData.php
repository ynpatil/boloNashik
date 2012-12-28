<?php
/*********************************************************************************
 * Relationship definition for Cases and Solutions
 * Allows to relate specific solutions that solved a Case
 ********************************************************************************/
$dictionary['cases_solutions'] = array (
	'table' => 'cases_solutions',
	'fields' => array (
        array('name' =>'id',            'type' =>'char', 'len'=>'36', 'required'=>true, 'default'=>'')
      , array('name' =>'case_id',       'type' =>'char', 'len'=>'36', 'required'=>true)
      , array('name' =>'solution_id',   'type' =>'char', 'len'=>'36', 'required'=>true)
      , array('name' =>'date_modified', 'type' =>'datetime',          'required'=>true)
      , array('name' =>'deleted',       'type' =>'bool', 'len'=>'1',  'required'=>true, 'default'=>'0')
    ) ,

    'indices' => array (
        array('name' =>'cases_solutionspk', 'type' =>'primary',      'fields'=>array('id'))
      , array('name' =>'idx_case_id',       'type' =>'index',        'fields'=>array('case_id'))
      , array('name' =>'idx_solu_id',       'type' =>'index',        'fields'=>array('solution_id'))
      , array('name' =>'idx_case_solu_ids', 'type'=>'alternate_key', 'fields'=>array('case_id','solution_id'))
     )

 	 , 'relationships' => array (
 	    'cases_solutions' => array(
 	       'lhs_module'=> 'Cases',           'lhs_table'=> 'cases',            'lhs_key' => 'id',
							  'rhs_module'=> 'ProblemSolution', 'rhs_table'=> 'problem_solution', 'rhs_key' => 'id',
							  'relationship_type'=>'many-to-many',
							  'join_table'=> 'cases_solutions', 'join_key_lhs'=>'case_id', 'join_key_rhs'=>'solution_id'))
)
?>
