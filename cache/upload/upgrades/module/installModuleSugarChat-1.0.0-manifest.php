<?PHP
/*****************************************************************************
 * The contents of this file are subject to the RECIPROCAL PUBLIC LICENSE
 * 1.0.0 1.1 ("License"); You may not use this file except in compliance
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
 * The Original Code is: CommuniCore
 *                       Olavo Farias
 *                       2006-04-7 olavo.farias@gmail.com
 *
 * The Initial Developer of the Original Code is CommuniCore.
 * Portions created by CommuniCore are Copyright (C) 2005 CommuniCore Ltda
 * All Rights Reserved.
 ********************************************************************************/
//BUILDER:BEGIN manifest 
$manifest = array(
    'acceptable_sugar_versions' => array (
      'regex_matches' => array (
       0 => "4.5.0",
      ),
    ),
    'acceptable_sugar_flavors' => array (
       0 => 'OS',
    ),
    'name'                      => 'SugarChat',
    'description'               => 'This is chat module for SugarCRM.',
    'author'                    => 'Matti Kiviharju',
    'published_date'            => '2006-10-09',
    'version'                   => '1.0.0',
    'type'                      => 'module',
    'icon'                      => '',
    'is_uninstallable'          => true,
);
//BUILDER:END manifest 
$installdefs = array(
 'id'       => 'SugarChat',
 'image_dir'=>'<basepath>/SugarChat/images',
 'copy'     => array(
                array('from'    => '<basepath>/SugarChat',
                      'to'      => 'modules/SugarChat',
                ),
                array('from'    => '<basepath>/SugarChat/images/CreateSugarChat.gif',
                      'to'      => 'themes/Default/images/CreateSugarChat.gif',
                ),
                array('from'    => '<basepath>/SugarChat/images/SugarChat.gif',
                      'to'      => 'themes/Default/images/SugarChat.gif',
                ),
                array('from'    => '<basepath>/SugarChat/images/SugarChat.gif',
                      'to'      => 'themes/Default/images/SugarChat.gif',
                ),
                array('from'    => '<basepath>/SugarChat/images/Home.gif',
                      'to'      => 'themes/Default/images/Home.gif',
                ),
                array('from'    => '<basepath>/SugarChat/images/Activities.gif',
                      'to'      => 'themes/Default/images/Activities.gif',
                ),
               ),

 
 'language'=> array(
   array('from'     => '<basepath>/application/mod_app_strings.php', 
         'to_module'=> 'application',
         'language' => 'en_us'
   ),
 //BUILDER:START of language 
 //BUILDER:END of language 
 ),

 'beans'=> array(
    array('module'=> 'SugarChat',
          'class' => 'SugarChat',
          'path'  => 'modules/SugarChat/SugarChat.php',
          'tab'   => true,
       )
 ),
 'relationships'=>array(
 //BUILDER:START of relationships 
 //BUILDER:END of relationships 
 ),

);
?>