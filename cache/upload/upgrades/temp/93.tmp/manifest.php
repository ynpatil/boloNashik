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
 * The Original Code is: AnySoft Informatica
 *                       Marcelo Leite (aka Mr. Milk)
 *                       2005-10-01 mrmilk@anysoft.com.br
 *
 * The Initial Developer of the Original Code is AnySoft Informatica Ltda.
 * Portions created by AnySoft are Copyright (C) 2005 AnySoft Informatica Ltda
 * All Rights Reserved.
 ********************************************************************************/

$manifest = array(

	'acceptable_sugar_versions' => array (
		'regex_matches' => array (
			0 => "4\.5\.0"
		),
	),
	'acceptable_sugar_flavors' => array (
		0 => 'OS', 'PRO', 'ENT'
	),
	'name' 				=> 'CallRooM: Organizational Chart',
	'description' 		=> 'Organizational Chart for Accounts and Users',
	'author' 			=> 'Marcelo Leite and Phillip Cole',
	'published_date'	=> '2006/06/09',
	'version' 			=> '4.5.0',
	'type' 				=> 'module',
	'icon' 				=> '',
	'is_uninstallable' => true,
);
$installdefs = array(

	'id'=> 'CallRooM-OrgChart',

	'copy' => array(

		array('from'=> '<basepath>/newfiles/orgchart.php',
			  'to'=> 'orgchart.php',
		),
		array('from'=> '<basepath>/newfiles/include/images/orgline.png',
			  'to'=> 'include/images/orgline.png',
		),
		array('from'=> '<basepath>/newfiles/include/javascript/yui/dom-0.10.0.js',
			  'to'=> 'include/javascript/yui/dom-0.10.0.js',
		),
		array('from'=> '<basepath>/newfiles/modules/Accounts/DetailView.450.html',
			  'to'=> 'modules/Accounts/DetailView.450.html',
		),
		array('from'=> '<basepath>/newfiles/modules/Contacts/DetailView.450.html',
			  'to'=> 'modules/Contacts/DetailView.450.html',
		),
		array('from'=> '<basepath>/patch/include',
			  'to'=> 'include',
		),
		array('from'=> '<basepath>/patch/modules',
			  'to'=> 'modules',
		),

	),

	'language'=> array(

		array('from'=> '<basepath>/language/application/app_strings_en_gb.php',
			  'to_module'=> 'application',
			  'language'=>'en_gb'
		),
		array('from'=> '<basepath>/language/application/app_strings_en_us.php',
			  'to_module'=> 'application',
			  'language'=>'en_us'
		),
		array('from'=> '<basepath>/language/application/app_strings_ge_ge.php',
			  'to_module'=> 'application',
			  'language'=>'ge_ge'
		),

		array('from'=> '<basepath>/language/modules/Accounts/mod_strings_en_gb.php',
			  'to_module'=> 'Accounts',
			  'language'=>'en_gb'
		),
		array('from'=> '<basepath>/language/modules/Contacts/mod_strings_en_gb.php',
			  'to_module'=> 'Contacts',
			  'language'=>'en_gb'
		),
		array('from'=> '<basepath>/language/modules/Employees/mod_strings_en_gb.php',
			  'to_module'=> 'Employees',
			  'language'=>'en_gb'
		),
		array('from'=> '<basepath>/language/modules/Users/mod_strings_en_gb.php',
			  'to_module'=> 'Users',
			  'language'=>'en_gb'
		),

		array('from'=> '<basepath>/language/modules/Accounts/mod_strings_en_us.php',
			  'to_module'=> 'Accounts',
			  'language'=>'en_us'
		),
		array('from'=> '<basepath>/language/modules/Contacts/mod_strings_en_us.php',
			  'to_module'=> 'Contacts',
			  'language'=>'en_us'
		),
		array('from'=> '<basepath>/language/modules/Employees/mod_strings_en_us.php',
			  'to_module'=> 'Employees',
			  'language'=>'en_us'
		),
		array('from'=> '<basepath>/language/modules/Users/mod_strings_en_us.php',
			  'to_module'=> 'Users',
			  'language'=>'en_us'
		),

		array('from'=> '<basepath>/language/modules/Accounts/mod_strings_ge_ge.php',
			  'to_module'=> 'Accounts',
			  'language'=>'ge_ge'
		),
		array('from'=> '<basepath>/language/modules/Contacts/mod_strings_ge_ge.php',
			  'to_module'=> 'Contacts',
			  'language'=>'ge_ge'
		),
		array('from'=> '<basepath>/language/modules/Employees/mod_strings_ge_ge.php',
			  'to_module'=> 'Employees',
			  'language'=>'ge_ge'
		),
		array('from'=> '<basepath>/language/modules/Users/mod_strings_ge_ge.php',
			  'to_module'=> 'Users',
			  'language'=>'ge_ge'
		),

	),
);
?>
