<?PHP

$manifest = array(
    'acceptable_sugar_versions' => array (
        'regex_matches' => array (
            0 => "4.5.0",
        ),
    ),
    'acceptable_sugar_flavors' => array (
        0 => 'OS','PRO',
    ),
    'name'                      => 'KnowledgeBase',
    'description'               => 'Problems & Solutions Management module',
    'author'                    => 'Olavo Farias',
    'published_date'            => '2006/01/26',
    'version'                   => '1.1',
    'type'                      => 'module',
    'icon'                      => '',
    'is_uninstallable'          => true,
);

$installdefs = array(
    'id'=> 'KnowledgeBase',
    'image_dir'=>'<basepath>/images',
    'copy' => array(
                array('from'=> '<basepath>/modules/Problem',
                      'to'=> 'modules/Problem',
                ),
                array('from'=> '<basepath>/modules/ProblemSolution',
                      'to'=> 'modules/ProblemSolution',
                ),
                array('from'=> '<basepath>/modules/Notes',
                      'to'=> 'modules/Notes',
                ),
    ),
    'language'=> array(
        array('from'=> '<basepath>/language/application/kbase-en_us.lang.php',
              'to_module'=> 'application',
              'language'=>'en_us'
        ),
		      array('from'=> '<basepath>/language/modules/Cases/mod_strings_solutions_en_us.php',
			           'to_module'=> 'Cases',
			           'language'=>'en_us'
		),
    ),
    'beans'=> array(
                array('module'=> 'Problem',
                      'class' => 'Problem',
                      'path'  => 'modules/Problem/Problem.php',
                      'tab'   => true,
                      ),
                array('module'=> 'ProblemSolution',
                      'class' => 'Solution',
                      'path'  => 'modules/ProblemSolution/ProblemSolution.php',
                      'tab'   => true,
                      )
    ),
    'relationships'=>array(
     array(
      'module'           => 'Cases',
      'meta_data'        =>'<basepath>/relationships/case_solutionMetaData.php',
      'module_vardefs'   =>'<basepath>/vardefs/modules/Cases/solutions-vardefs.php',
      'module_layoutdefs'=>'<basepath>/layoutdefs/modules/Cases/solutions-layout_defs.php'
     ),
     array(
			   'module'           => 'Problem',
 		   'meta_data'        =>'<basepath>/relationships/problem_auditMetaData.php',
			   'module_vardefs'   =>'<basepath>/vardefs/modules/Problem/problem_auditvardefs.php',
		   ),
     array(
		   	'module'           => 'ProblemSolution',
 	   	'meta_data'        =>'<basepath>/relationships/problemsolution_auditMetaData.php',
			   'module_vardefs'   =>'<basepath>/vardefs/modules/ProblemSolution/problemsolution_auditvardefs.php',
		   ),
    ),
);
?>
