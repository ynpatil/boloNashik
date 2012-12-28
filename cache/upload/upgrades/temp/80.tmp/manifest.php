<?PHP

// manifest file for information regarding application of new code
$manifest = array(
    // only install on the following regex sugar versions (if empty, no check)
    'acceptable_sugar_versions' => array(),

    // name of new code
    'name' => 'ZuckerDocs',

    // description of new code
    'description' => 'Document Management and Knowlegde Base',

    // author of new code
    'author' => 'go-mobile IT GmbH',

    // date published
    'published_date' => '2006/06/16',

    // version of code
    'version' => '1.4.5',

    // type of code (valid choices are: full, langpack, module, patch, theme )
    'type' => 'module',

    // icon for displaying in UI (path to graphic contained within zip package)
    'icon' => '',
	
	// is_uninstallable
	'is_uninstallable' => 'true',
);



$installdefs = array(
	'id'=> 'ZuckerDocs',
	'image_dir'=>'<basepath>/images',
	'copy' => array(
						array('from'=> '<basepath>/module/ZuckerDocs',
							  'to'=> 'modules/ZuckerDocs',
							  ),
						array('from'=> '<basepath>/root/download.php',
							  'to'=> 'download.php',
							  ),
						array('from'=> '<basepath>/root/godocs.php',
							  'to'=> 'godocs.php',
							  ),
						array('from'=> '<basepath>/root/godocs_dms.php',
							  'to'=> 'godocs_dms.php',
							  ),
						array('from'=> '<basepath>/root/godocs_notes.php',
							  'to'=> 'godocs_notes.php',
							  ),
					),
	
	'language'=> array(
					array('from'=> '<basepath>/application/app_strings.en_us.lang.php', 
						'to_module'=> 'application',
						'language'=>'en_us'
						),
					array('from'=> '<basepath>/application/app_strings.ge_ge.lang.php', 
						'to_module'=> 'application',
						'language'=>'ge_ge'
						),
					array('from'=> '<basepath>/application/app_strings.es_es.lang.php', 
						'to_module'=> 'application',
						'language'=>'es_es'
						),
					),
					
	'beans'=> array(
			array('module'=> 'ZuckerDocs',
				  'class'=> 'ZuckerDocsDummyBean',
				  'path'=> 'modules/ZuckerDocs/ZuckerDocsDummyBean.php',
				  'tab'=> true,
			),
		),
);
?>
