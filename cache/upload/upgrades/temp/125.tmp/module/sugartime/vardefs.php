<?php

$dictionary['sugartime'] = array(
	'table' => 'timerecords',
	'fields' => array(
		'id' => array(
			'name' => 'id',
			'vname' => 'LBL_ID',
			'required' => true,
			'type' => 'id',
			'reportable'=>false,
		),
		
		
		'assigned_user_id' => array (
			'name' => 'assigned_user_id',
			'rname' => 'user_name',
			'id_name' => 'assigned_user_id',
			'vname' => 'LBL_ASSIGNED_TO',
			'type' => 'assigned_user_name',
			'reportable'=>true,
			'table' => 'users',
			'isnull' => 'false',
			'dbType' => 'id',
			'len' => 36,
			'audited'=>true,    
			'duplicate_merge'=>'disabled' 
		  ),
		  
		  'assigned_user_name' => array (
			'name' => 'assigned_user_name',
			'vname' => 'LBL_ASSIGNED_TO_NAME',
			'type' => 'relate',
			'reportable'=>false,
			'source'=>'nondb',
			'table' => 'users',
			'id_name' => 'assigned_user_id',
			'module'=>'Users',
			'duplicate_merge'=>'disabled' 
		  ),

		'rdate' => array(
			'name' => 'rdate',
			'vname' => 'LBL_RDATE',
			'type' => 'date',
			'required' => true,
		),
		'start_time' => array(
			'name' => 'start_time',
			'vname' => 'LBL_START_TIME',
			'type' => 'time',
			'required' => true,
		),
		'finish_time' => array(
			'name' => 'finish_time',
			'vname' => 'LBL_FINISH_TIME',
			'type' => 'time',
			'required' => true,
		),
		'downtime' => array(
			'name' => 'downtime',
			'vname' => 'LBL_DOWNTIME',
			'required' => false,
			'type' => 'varchar',
			'len' => 5,
		),
		'overtime' => array(
			'name' => 'overtime',
			'vname' => 'LBL_OVERTIME',
			'required' => false,
			'type' => 'varchar',
			'len' => 5,
		),
		'overtime_hours' => array(
			'name' => 'overtime_hours',
			'vname' => 'LBL_OVERTIMEHOURS',
			'required' => false,
			'type' => 'varchar',
			'len' => 6,
		),
		// Total normal time (Nopt in use yet)
		'ntotal' => array(
			'name' => 'ntotal',
			'vname' => 'LBL_NTOTAL',
			'required' => false,
			'type' => 'varchar',
			'len' => 6,
		),
		'total' => array(
			'name' => 'total',
			'vname' => 'LBL_TOTAL',
			'required' => false,
			'type' => 'varchar',
			'len' => 6,
		),
		'total_hours' => array(
			'name' => 'total_hours',
			'vname' => 'LBL_TOTALHOURS',
			'required' => false,
			'type' => 'varchar',
			'len' => 6,
		),
		'date_modified' => array(
			'name' => 'date_modified',
			'vname' => 'LBL_DATE_MODIFIED',
			'type' => 'datetime',
			'required' => true,
		),
		'deleted' => array(
			'name' => 'deleted',
			'vname' => 'LBL_DELETED',
			'type' => 'bool',
			'required' => true,
			'default' => '0',
		),	
	),
	'indices' => array(
		array('name' =>'time_primary_key_index',
			'type' =>'primary',
			'fields'=>array('id')
			),
	),
);
?>