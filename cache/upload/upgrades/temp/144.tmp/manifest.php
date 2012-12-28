<?PHP
/*
***** SugarTime *****
Developed by Paul K. Lynch, Everyday Interactive Networks (ein.com.au)
Mozilla Public License v1.1
*/
$manifest = array(
    'acceptable_sugar_versions' => array(),
    'is_uninstallable' => true,
    'name' => 'SugarTime',
    'description' => 'Timesheet records',
    'author' => 'Paul K. Lynch',
    'published_date' => '2007/01/29',
    'version' => '0.5.2',
    'type' => 'module',
    'icon' => '',
);


$installdefs = array(
	'id'=> 'SugarTime',
	'image_dir'=>'<basepath>/images',
	'copy' => array(
				array('from'=> '<basepath>/module/sugartime',
					  'to'=> 'modules/sugartime',
					  ),
			),

	
	'language'=> array(array('from'=> '<basepath>/application/app_strings.php', 
					'to_module'=> 'application',
					'language'=>'en_us'
					),
					array('from'=> '<basepath>/administration/en_us.timeadmin.php', 
					'to_module'=> 'Administration',
					'language'=>'en_us'
					)
					),
	'administration'=> array(
					// Nothing Yet
					  ),
					
	'beans'=> array(
				array('module'=> 'sugartime',
					  'class'=> 'sugartime',
					  'path'=> 'modules/sugartime/sugartime.php',
					  'tab'=> true,
					  )
					  ),
	'relationships'=>array(
					// Nothing yet
					),
	'custom_fields'=>array(
					// Nothing yet
					),
					  
);
?>
