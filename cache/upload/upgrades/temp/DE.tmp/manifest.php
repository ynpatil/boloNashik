<?PHP

// manifest file for information regarding application of new code
$manifest = array(
    // only install on the following regex sugar versions (if empty, no check)
    'acceptable_sugar_versions' => array(),

    'is_uninstallable'=>true,

    // name of new code
    'name' => 'Forums, Threads, Posts Modules',

    // description of new code
    'description' => 'This module is to allow creating Forums for general discussion, as well as creating threads to link to Accounts, Bugs, Cases, Opportunities, and Projects.',

    // author of new code
    'author' => 'Sadek Baroudi',

    // date published
    'published_date' => '2006/06/08',

    // version of code
    'version' => '4.5.0g',

    // type of code (valid choices are: full, langpack, module, patch, theme )
    'type' => 'module',

    // icon for displaying in UI (path to graphic contained within zip package)
    'icon' => '',
);


$installdefs = array(
	'id' => 'forums',
	'image_dir'=>'<basepath>/images',
	'copy' => array(
				array(
					'from' => '<basepath>/modules/Forums',
					'to'   => 'modules/Forums',
				),
				array(
					'from' => '<basepath>/modules/Threads',
                	'to'   => 'modules/Threads',
				),
				array(
					'from' => '<basepath>/modules/Posts',
					'to'   => 'modules/Posts',
				),
				array(
					'from' => '<basepath>/modules/ForumTopics',
					'to'   => 'modules/ForumTopics',
				),
				
				
				
				
				// BEGIN: all below are to copy the images into the themes image dirs
				//   remove if desired
				array(
				    'from' => '<basepath>/images/Default/images',
				    'to'   => 'themes/Awesome80s/images',
				),
				array(
				    'from' => '<basepath>/images/Default/images',
				    'to'   => 'themes/Default/images',
				),
				array(
				    'from' => '<basepath>/images/Default/images',
				    'to'   => 'themes/FinalFrontier/images',
				),
				array(
				    'from' => '<basepath>/images/Default/images',
				    'to'   => 'themes/GoldenGate/images',
				),
				array(
				    'from' => '<basepath>/images/Default/images',
				    'to'   => 'themes/Links/images',
				),
				array(
				    'from' => '<basepath>/images/Default/images',
				    'to'   => 'themes/Love/images',
				),
				array(
				    'from' => '<basepath>/images/Default/images',
				    'to'   => 'themes/Paradise/images',
				),
				array(
				    'from' => '<basepath>/images/Default/images',
				    'to'   => 'themes/Pipeline/images',
				),
				array(
				    'from' => '<basepath>/images/Default/images',
				    'to'   => 'themes/Retro/images',
				),
				array(
				    'from' => '<basepath>/images/Default/images',
				    'to'   => 'themes/RipCurl/images',
				),
				array(
				    'from' => '<basepath>/images/Default/images',
				    'to'   => 'themes/Shred/images',
				),
				array(
				    'from' => '<basepath>/images/Default/images',
				    'to'   => 'themes/Sugar/images',
				),
				array(
				    'from' => '<basepath>/images/Default/images',
				    'to'   => 'themes/SugarClassic/images',
				),
				array(
				    'from' => '<basepath>/images/Default/images',
				    'to'   => 'themes/SugarLite/images',
				),
				array(
				    'from' => '<basepath>/images/Default/images',
				    'to'   => 'themes/Sunset/images',
				),
				array(
				    'from' => '<basepath>/images/Default/images',
				    'to'   => 'themes/WhiteSands/images',
				),
				// END: all are to copy the images into the themes image dirs
			),

	'language'=> array(
		array(
			'from'=> '<basepath>/application/app_strings.php', 
			'to_module'=> 'application',
			'language'=>'en_us'
		),
		array(
			'from'=> '<basepath>/administration/en_us.forumsadmin.php', 
			'to_module'=> 'Administration',
			'language'=>'en_us'
		),
		array(
			'from' => '<basepath>/modules/Accounts/en_us.lang.php',
			'to_module' => 'Accounts',
			'language' =>'en_us'
		),
		array(
			'from' => '<basepath>/modules/Bugs/en_us.lang.php',
			'to_module' => 'Bugs',
			'language' =>'en_us'
		),
		array(
			'from' => '<basepath>/modules/Cases/en_us.lang.php',
			'to_module' => 'Cases',
			'language' =>'en_us'
		),
		array(
			'from' => '<basepath>/modules/Opportunities/en_us.lang.php',
			'to_module' => 'Opportunities',
			'language' =>'en_us'
		),
		array(
			'from' => '<basepath>/modules/Project/en_us.lang.php',
			'to_module' => 'Project',
			'language' =>'en_us'
		),
	),
	'administration'=> array(
				array(
					'from'=>'<basepath>/administration/forumsadminoption.php',
					'to' => 'modules/Administration/forumsadminoption.php',
				),
	),
	'beans'=> array(
				array(
					'module' => 'Forums',
					'class'  => 'Forum',
					'path'   => 'modules/Forums/Forum.php',
					'tab'    => true,
				),
				array(
					'module' => 'Threads',
					'class'   => 'Thread',
					'path'    => 'modules/Threads/Thread.php',
					'tab'     => false,
				),
				array(
					'module' => 'Posts',
					'class'  => 'Post',
					'path'   => 'modules/Posts/Post.php',
					'tab'    => false,
				),
				array(
					'module' => 'ForumTopics',
					'class'  => 'ForumTopic',
					'path'   => 'modules/ForumTopics/ForumTopic.php',
					'tab'    => false,
				),
	),

    'layoutdefs'=> array(
		/* may need to use this
		array(
			'from' => '<basepath>/layoutdefs/forumslayout_defs.php', 
			'to_module' => 'Forums',
		),
		array(
			'from' => '<basepath>/layoutdefs/threadslayout_defs.php', 
			'to_module' => 'Threads',
		),		
		*/
    ),

	'relationships'=>array(
		array(
			'module'=> 'Accounts',
			'meta_data'=>'<basepath>/relationships/accounts_threadsMetaData.php',
			'module_vardefs'=>'<basepath>/vardefs/accounts_vardefs.php',
			'module_layoutdefs'=>'<basepath>/layoutdefs/accounts_layoutdefs.php'
		),
		array(
			'module'=> 'Bugs',
			'meta_data'=>'<basepath>/relationships/bugs_threadsMetaData.php',
			'module_vardefs'=>'<basepath>/vardefs/bugs_vardefs.php',
			'module_layoutdefs'=>'<basepath>/layoutdefs/bugs_layoutdefs.php'
		),
		array(
			'module'=> 'Cases',
			'meta_data'=>'<basepath>/relationships/cases_threadsMetaData.php',
			'module_vardefs'=>'<basepath>/vardefs/cases_vardefs.php',
			'module_layoutdefs'=>'<basepath>/layoutdefs/cases_layoutdefs.php'
		),
		array(
			'module'=> 'Opportunities',
			'meta_data'=>'<basepath>/relationships/opportunities_threadsMetaData.php',
			'module_vardefs'=>'<basepath>/vardefs/opportunities_vardefs.php',
			'module_layoutdefs'=>'<basepath>/layoutdefs/opportunities_layoutdefs.php'
		),
		array(
			'module'=> 'Project',
			'meta_data'=>'<basepath>/relationships/project_threadsMetaData.php',
			'module_vardefs'=>'<basepath>/vardefs/project_vardefs.php',
			'module_layoutdefs'=>'<basepath>/layoutdefs/project_layoutdefs.php'
		),
	),
	
	'post_execute'=>array(
		0 => '<basepath>/post_install/install_actions.php',
	),
);

?>
