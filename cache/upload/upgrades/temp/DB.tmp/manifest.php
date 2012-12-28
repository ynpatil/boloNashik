<?PHP

// manifest file for information regarding application of new code
$manifest = array(
    // only install on the following regex sugar versions (if empty, no check)
    'acceptable_sugar_versions' => array(),

    //makes module removable.
    'is_uninstallable' => true,

    // name of new code
    'name' => 'Yahoo Maps Dashlet',

    // description of new code
    'description' => 'A dashlet for mapping',

    // author of new code
    'author' => 'Roger R. Smith',

    // date published
    'published_date' => '2006/10/04',

    // version of code
    'version' => '.15',

    // type of code (valid choices are: full, langpack, module, patch, theme )
    'type' => 'module',

    // icon for displaying in UI (path to graphic contained within zip package)
    'icon' => '',
);



$installdefs = array(
	'id'=> 'MapsDashlet',
	'dashlets'=> array(
				array('name'=>'MapsDashlet',
					'from'=>'<basepath>/MapsDashlet',
						
			),
					
	),
					  
);
?>
