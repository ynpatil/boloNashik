<?PHP
	/* 
		InsideView - Sugar Integration Module Install script.
		Please read Install.txt for Installation instructions and README.txt for further details.
		InsideView Inc.
		We think, you sell
		http://www.insideview.com
	*/
	global $sugar_config; //You can include normal PHP code in this file
	$upload_dir=$sugar_config['uploads_dir']; //as well.
	$manifest = array(
	// only install on the following regex sugar versions (if empty, no check)
	'acceptable_sugar_versions' => array(),
	'name' => 'InsideView for Sugar',
	'description' => 'InsideView for Sugar 1.0',
	'is_uninstallable' => true,
	'author' => 'InsideView',
	'published_date' => '2007/03/30',
	'version' => '1.0',
	'type' => 'module', //Could be module or patch among others
	);
	
	$installdefs = array
	(
		'id'	=> 'InsideView1.0',
		'copy'	=> array(
					array(
						'from' => '<basepath>/scripts/class.insideview.php',
						'to' => 'include/InsideView/class.insideview.php'
						),
					),
	);
?>