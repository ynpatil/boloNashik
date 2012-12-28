<?php
$dictionary['TeamOS'] = array(
	'table' => 'teams',
	'fields' => array (
		'id' => array (
			'name' => 'id',
			'vname' => 'LBL_ID',
			'type' => 'id',
			'required'=>true,
		),

		'name' => array (
			'name' => 'name',
			'vname' => 'LBL_NAME',
			'type' => 'char',
			'len' => '255',
			'required'=>true
		),
		
		'private' => array (
			'name' => 'private',
			'vname' => 'LBL_PRIVATE',
			'type' => 'bool',
			'required'=>false
		),
		
		'deleted' => array (
			'name' => 'deleted',
			'vname' => 'LBL_DELETED',
			'type' => 'bool',
			'required'=>false
		),

		'date_entered' => array (
			'name' => 'date_entered',
			'vname' => 'LBL_DATE_ENTERED',
			'type' => 'datetime',
			'required'=>false
		),
			
		'date_modified' => array (
			'name' => 'date_modified',
			'vname' => 'LBL_DATE_MODIFIED',
			'type' => 'datetime',
			'required'=>false
		),
		'users' => array (
			'name' => 'users',
			'type' => 'link',
			'relationship' => 'team_membership',
			'module'=>'Users',
			'bean_name'=>'User',
			'source'=>'non-db',
			'vname'=>'LBL_USERS'
		),
	),
'indices' => array (
       array('name' =>'teamspk', 'type' =>'primary', 'fields'=>array('id'))
		)	
);
?>
