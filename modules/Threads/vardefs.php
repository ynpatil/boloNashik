<?php

$dictionary['Threads'] = array(
	'table' => 'threads',
	'fields' => array(
		'id' => array(
			'name' => 'id',
			'vname' => 'LBL_ID',
			'required' => true,
			'type' => 'id',
			'reportable'=>false,
		),
		'date_entered' => array(
			'name' => 'date_entered',
			'vname' => 'LBL_DATE_ENTERED',
			'type' => 'datetime',
			'required' => true,
		),
		'created_by' => array(
			'name' => 'created_by',
			'rname' => 'user_name',
			'id_name' => 'created_by',
			'vname' => 'LBL_CREATED_BY',
			'type' => 'assigned_user_name',
			'table' => 'created_by_users',
			'isnull' => 'false',
			'dbType' => 'id',
		),
		'created_by_user'=>array(
			'name' =>'created_by_user',
			'source'=>'non-db',
		),		
		'date_modified' => array(
			'name' => 'date_modified',
			'vname' => 'LBL_DATE_MODIFIED',
			'type' => 'datetime',
			'required' => true,
		),
		'modified_user_id' => array(
			'name' => 'modified_user_id',
			'rname' => 'user_name',
			'id_name' => 'modified_user_id',
			'vname' => 'LBL_MODIFIED_USER_ID',
			'type' => 'assigned_user_name',
			'table' => 'users',
			'isnull' => 'false',
			'dbType' => 'id',
			'required' => true,
			'default' => '',
			'reportable'=>true,
		),
		'modified_by_user'=>array(
			'name' =>'modified_by_user',
			'source'=>'non-db',
		),
        'postcount'=>array(
            'name' =>'postcount',
            'vname' => 'LBL_POST_COUNT',
            'type' => 'int',
            'default' => '0',
            'len' => 255,
        ),
		'deleted' => array(
			'name' => 'deleted',
			'vname' => 'LBL_DELETED',
			'type' => 'bool',
			'required' => true,
			'default' => '0',
		),	
		'title' => array(
			'name' => 'title',
			'vname' => 'LBL_TITLE',
			'required' => true,
			'type' => 'varchar',
			'len' => 255,
		    'ucformat' => true,
		),
        'description_html' => array(
          'name' => 'description_html',
          'vname' => 'LBL_BODY',
          'type' => 'text',
        ),
		'forum_id' => array(
			'name' => 'forum_id',
			'vname' => 'LBL_FORUM_ID',
			'type' => 'id',
		),
		'forum_name'=>array(
			'name' =>'forum_name',
			'source'=>'non-db',
		),
		'is_sticky' => array(
			'name' => 'is_sticky',
			'vname' => 'LBL_IS_STICKY',
			'type' => 'bool',
			'default' => '0',
		),
		'stickyDisplay'=>array(
			'name' =>'stickyDisplay',
			'source'=>'non-db',
		),


	    'recent_post_title' => array(
	      'name' => 'recent_post_title',
          'source' => 'non-db',
	    ),
	    'recent_post_id' => array(
	      'name' => 'recent_post_id',
          'source' => 'non-db',
	    ),
	    'recent_post_modified_id' => array(
	      'name' => 'recent_post_modified_id',
          'source' => 'non-db',
	    ),	
	    'recent_post_modified_name' => array(
	      'name' => 'recent_post_modified_name',
          'source' => 'non-db',
	    ),
    	
		'view_count' => array(
			'name' => 'view_count',
			'vname' => 'LBL_VIEW_COUNT',
			'type' => 'int',
			'required' => true,
			'default' => 0,
		),
		'accounts' => array (
			'name' => 'accounts',
			'type' => 'link',
			'relationship' => 'accounts_threads',
			'source' => 'non-db',
			'vname' => 'LBL_ACCOUNTS',
		),  
		'bugs' => array (
			'name' => 'bugs',
			'type' => 'link',
			'relationship' => 'bugs_threads',
			'source' => 'non-db',
			'vname' => 'LBL_BUGS',
		),  
		'cases' => array (
			'name' => 'cases',
			'type' => 'link',
			'relationship' => 'cases_threads',
			'source' => 'non-db',
			'vname' => 'LBL_CASES',
		),  
		'opportunities' => array (
			'name' => 'opportunities',
			'type' => 'link',
			'relationship' => 'opportunities_threads',
            'module'=>'opportunities',
            'bean_name'=>'Opportunities',
    		'source' => 'non-db',
			'vname' => 'LBL_OPPORTUNITIES',
		), 
        'project' => array (
            'name' => 'project',
            'type' => 'link',
            'relationship' => 'project_threads',
            'module'=>'project',
            'bean_name'=>'Project',
            'source' => 'non-db',
            'vname' => 'LBL_PROJECT',
        ), 
	),
	
	'indices' => array(
		array('name' =>'thread_primary_key_index',
			'type' =>'primary',
			'fields'=>array('id')
			),
	),
);
?>
