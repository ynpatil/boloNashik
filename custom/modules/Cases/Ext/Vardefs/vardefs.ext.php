<?php 
 //WARNING: The contents of this file are auto-generated


//BEGIN SADEK
$dictionary['Case']['fields']['threads'] =   array (
    'name' => 'threads',
    'type' => 'link',
    'relationship' => 'cases_threads',
    'module'=>'threads',
    'bean_name'=>'Threads',
    'source'=>'non-db',
    'vname'=>'LBL_THREADS',
);

$dictionary['Case']['relationships']['cases_threads'] = array(
    'lhs_module'=> 'Cases',
    'lhs_table'=> 'cases',
    'lhs_key' => 'id',
    
    'rhs_module'=> 'thread',
    'rhs_table'=> 'threads',
    'rhs_key' => 'id',

    'relationship_type'=>'many-to-many',
	'join_table'=> 'cases_threads',
	'join_key_lhs'=>'case_id',
	'join_key_rhs'=>'thread_id'
);
//END SADEK


/*********************************************************************************
 * New field for Cases
 * pointing to associated solutions
 ********************************************************************************/

$dictionary['Case']['fields']['solutions'] = array (
		'name'         => 'solutions',
		'type'         => 'link',
		'relationship' => 'cases_solutions',
		'source'       =>'non-db',
);



?>