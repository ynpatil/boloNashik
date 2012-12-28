<?php
$dictionary['problem_audit'] = array ( 
 'table' => 'problem_audit'
 , 'fields' => array (
    array('name' =>'id',                  'type' =>'char', 'len'=>'36', 'required'=>true, 'default'=>'')
  , array('name' =>'parent_id',           'type' =>'char', 'len'=>'36')
  , array('name' =>'date_created',        'type' => 'datetime')
  , array('name' =>'created_by',          'type' =>'char', 'len'=>'36')
  , array('name' =>'field_name',          'type' =>'char', 'len'=>'100')
  , array('name' =>'data_type',           'type' =>'char', 'len'=>'100')
  , array('name' =>'before_value_string', 'type' =>'char', 'len'=>'255')
  , array('name' =>'after_value_string',  'type' =>'char', 'len'=>'255')
  , array('name' =>'before_value_text',   'type' =>'text')
  , array('name' =>'after_value_text',    'type' =>'text')
   )
)

?>
