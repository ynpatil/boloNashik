<?php
$manifest = array (
	'acceptable_sugar_versions' => array (0=>'4.5.2d'),
	'acceptable_sugar_flavors' => array (),
	'name' => 'BirthdaylistDashlet',
	'description' => 'A dashlet that provides the user with a list of upcoming birthdays, sortable by month and day.',
	'author' => 'Kyrre Amanaborg',
	'published_date' => '14.06.2007',
	'version' => '1.1',
	'type' => 'module',
	'icon' => '',
	'is_uninstallable' => true,
);

$installdefs = array( 
	'id' => 'Birthdaylist', 

	'copy' => array(
		array('from'=> '<basepath>/modules/Contacts/Dashlets/BirthdaylistDashlet/',
			  'to'=> 'modules/Contacts/Dashlets/BirthdaylistDashlet',
		),
	),

	'post_execute'=>array(
		0 => '<basepath>/post_install/install_actions.php',
	),
	'post_uninstall'=>array(
		0 => '<basepath>/post_uninstall/uninstall_actions.php',
	),
);

?>
