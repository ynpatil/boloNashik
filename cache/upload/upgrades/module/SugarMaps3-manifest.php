<?PHP
/*****************************************************************************
 * The contents of this file are subject to the RECIPROCAL PUBLIC LICENSE
 * Version 1.1 ("License"); You may not use this file except in compliance
 * with the License. You may obtain a copy of the License at
 * http://opensource.org/licenses/rpl.php. Software distributed under the
 * License is distributed on an "AS IS" basis, WITHOUT WARRANTY OF ANY KIND,
 * either express or implied.
 *
 * You may:
 * a) Use and distribute this code exactly as you received without payment or
 *    a royalty or other fee.
 * b) Create extensions for this code, provided that you make the extensions
 *    publicly available and document your modifications clearly.
 * c) Charge for a fee for warranty or support or for accepting liability
 *    obligations for your customers.
 *
 * You may NOT:
 * a) Charge for the use of the original code or extensions, including in
 *    electronic distribution models, such as ASP (Application Service
 *    Provider).
 * b) Charge for the original source code or your extensions other than a
 *    nominal fee to cover distribution costs where such distribution
 *    involves PHYSICAL media.
 * c) Modify or delete any pre-existing copyright notices, change notices,
 *    or License text in the Licensed Software
 * d) Assert any patent claims against the Licensor or Contributors, or
 *    which would in any way restrict the ability of any third party to use the
 *    Licensed Software.
 *
 * You must:
 * a) Document any modifications you make to this code including the nature of
 *    the change, the authors of the change, and the date of the change.
 * b) Make the source code for any extensions you deploy available via an
 *    Electronic Distribution Mechanism such as FTP or HTTP download.
 * c) Notify the licensor of the availability of source code to your extensions
 *    and include instructions on how to acquire the source code and updates.
 * d) Grant Licensor a world-wide, non-exclusive, royalty-free license to use,
 *    reproduce, perform, modify, sublicense, and distribute your extensions.
 *
* The Original Code is: SugarCubed Extensions
 *                       Kenneth brill
 *                       2006-03-04 ken.brill@gmail.com
 *
 * The Initial Developer of the Original Code is SugarCubed / Kenneth Brill
 * Portions created by Kenneth Brill are Copyright (C) 2005 Kenneth Brill
 * All Rights Reserved.
 ********************************************************************************/
$manifest = array(

	'acceptable_sugar_versions' => array (
		'regex_matches' => array (
			0 => "4\.0\.1.*",
			1 => "4\.2\.0.*",
			2 => "4\.5\.0.*",
		),
	),
	'acceptable_sugar_flavors' => array (
		0 => 'OS',
		1 => 'PRO',
		2 => 'ENT',
	),
	'name' 				=> 'SugarCubed: Google Maps',
	'description' 		=> 'Quick Google Maps & Directions',
	'author' 			=> 'Ken Brill',
	'published_date'	=> '2006/05/05',
	'version' 			=> '3.0a',
	'type' 				=> 'module',
	'icon' 				=> 'include/images/SugarCubed.gif',
	'is_uninstallable' => true,
);

$installdefs = array(
	'id'=> 'SugarMaps',
	'copy' => array(
		array('from'=> '<basepath>/include/images/SugarCubed.gif',
			  'to'=> 'include/images/SugarCubed.gif',
		),
		array('from'=> '<basepath>/include/images/Directions.gif',
			  'to'=> 'themes/FinalFrontier/images/Directions.gif',
		),
		array('from'=> '<basepath>/include/images/Map.gif',
			  'to'=> 'themes/FinalFrontier/images/Map.gif',
		),
		array('from'=> '<basepath>/include/images/Directions.gif',
			  'to'=> 'themes/GoldenGate/images/Directions.gif',
		),
		array('from'=> '<basepath>/include/images/Map.gif',
			  'to'=> 'themes/GoldenGate/images/Map.gif',
		),
		array('from'=> '<basepath>/include/images/Directions.gif',
			  'to'=> 'themes/Links/images/Directions.gif',
		),
		array('from'=> '<basepath>/include/images/Map.gif',
			  'to'=> 'themes/Links/images/Map.gif',
		),
		array('from'=> '<basepath>/include/images/Directions.gif',
			  'to'=> 'themes/Love/images/Directions.gif',
		),
		array('from'=> '<basepath>/include/images/Map.gif',
			  'to'=> 'themes/Love/images/Map.gif',
		),
		array('from'=> '<basepath>/include/images/Directions.gif',
			  'to'=> 'themes/Sunset/images/Directions.gif',
		),
		array('from'=> '<basepath>/include/images/Map.gif',
			  'to'=> 'themes/Sunset/images/Map.gif',
		),
		array('from'=> '<basepath>/include/images/Directions.gif',
			  'to'=> 'themes/Paradise/images/Directions.gif',
		),
		array('from'=> '<basepath>/include/images/Map.gif',
			  'to'=> 'themes/Paradise/images/Map.gif',
		),
		array('from'=> '<basepath>/include/images/Directions.gif',
			  'to'=> 'themes/Pipeline/images/Directions.gif',
		),
		array('from'=> '<basepath>/include/images/Map.gif',
			  'to'=> 'themes/Pipeline/images/Map.gif',
		),
		array('from'=> '<basepath>/include/images/Directions.gif',
			  'to'=> 'themes/Retro/images/Directions.gif',
		),
		array('from'=> '<basepath>/include/images/Map.gif',
			  'to'=> 'themes/Retro/images/Map.gif',
		),
		array('from'=> '<basepath>/include/images/Directions.gif',
			  'to'=> 'themes/RipCurl/images/Directions.gif',
		),
		array('from'=> '<basepath>/include/images/Map.gif',
			  'to'=> 'themes/RipCurl/images/Map.gif',
		),
		array('from'=> '<basepath>/include/images/Directions.gif',
			  'to'=> 'themes/Shred/images/Directions.gif',
		),
		array('from'=> '<basepath>/include/images/Map.gif',
			  'to'=> 'themes/Shred/images/Map.gif',
		),
		array('from'=> '<basepath>/include/images/Directions.gif',
			  'to'=> 'themes/Sugar/images/Directions.gif',
		),
		array('from'=> '<basepath>/include/images/Map.gif',
			  'to'=> 'themes/Sugar/images/Map.gif',
		),
		array('from'=> '<basepath>/include/images/Directions.gif',
			  'to'=> 'themes/SugarClassic/images/Directions.gif',
		),
		array('from'=> '<basepath>/include/images/Map.gif',
			  'to'=> 'themes/SugarClassic/images/Map.gif',
		),
		array('from'=> '<basepath>/include/images/Directions.gif',
			  'to'=> 'themes/SugarLite/images/Directions.gif',
		),
		array('from'=> '<basepath>/include/images/Map.gif',
			  'to'=> 'themes/SugarLite/images/Map.gif',
		),
		array('from'=> '<basepath>/include/images/Directions.gif',
			  'to'=> 'themes/WhiteSands/images/Directions.gif',
		),
		array('from'=> '<basepath>/include/images/Map.gif',
			  'to'=> 'themes/WhiteSands/images/Map.gif',
		),
	),

	'language'=> array(
		array('from'=> '<basepath>/Language/en_us.accounts.php',
			  'to_module'=> 'Accounts',
			  'language'=>'en_us'
		),
		array('from'=> '<basepath>/Language/en_us.contacts.php',
			  'to_module'=> 'Contacts',
			  'language'=>'en_us'
		)
	),

	'menu'=> array(
		array('from'=> '<basepath>/Menus/Accounts/menu.ext.php',
			  'to_module'=> 'Accounts',
		),
		array('from'=> '<basepath>/Menus/Contacts/menu.ext.php',
			  'to_module'=> 'Contacts',
		)
	)

);
?>