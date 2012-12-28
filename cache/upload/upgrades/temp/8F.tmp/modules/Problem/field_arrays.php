<?php
if(empty($GLOBALS['sugarEntry'])) die('Not A Valid Entry Point');
/*********************************************************************************
 * KnowledgeBase module
 ********************************************************************************/
$fields_array['Problem'] = array (
 'column_fields' => array(
		'id',
		'date_entered',
		'date_modified',
		'assigned_user_id',
		'modified_user_id',
		'created_by',
		'name',
		'status',
		'class',
		'description',
		'deleted',
	),
 'list_fields' =>  array(
		'id',
		'assigned_user_id',
		'assigned_user_name',
		'name',
		'status',
		'class',
		'relation_id',
		'relation_name',
		'relation_type',
	),
    'required_fields' =>  array('name'=>1, ),
);
?>
