<?php

$manifest = array(

  'acceptable_sugar_versions' => array(
     'exact_matches' => array(
     ),
     'regex_matches' => array(
       0 => '4\.5\.1'
     ),
  ),

  'is_uninstallable'          => true,
  'name'                      => 'Tags',
  'description'               => 'A module which allows many-to-many tagging of contacts',
  'author'                    => 'George Neill <gneill@aiminstitute.org>',
  'published_date'            => '2007/03/20',
  'version'                   => '0.1',
  'type'                      => 'module',
  'icon'                      => '',
);

$installdefs = array(
  'id' => 'tags',

  'copy' => array( 
    array(
      'from' => '<basepath>/modules/Tags',
      'to'   => 'modules/Tags',
    ),
  ),

  'language' => array(
    array(
      'from'      => '<basepath>/application/app_list_strings.php', 
      'to_module' => 'application',
      'language'  => 'en_us'
    ),
    array(
      'from'      => '<basepath>/modules/Contacts/en_us.lang.php',
      'to_module' => 'Contacts',
      'language'  =>'en_us'
    ),
  ),

  'beans' => array(
    array(
      'module' => 'Tags',
      'class'  => 'Tag',
      'path'   => 'modules/Tags/Tag.php',
      'tab'    => true,
    ),
  ),

  'relationships' => array(
    array(
      'module'=> 'Contacts',
      'meta_data'=>'<basepath>/relationships/contacts_tagsMetaData.php',
      'module_vardefs'=>'<basepath>/vardefs/contacts_vardefs.php',
      'module_layoutdefs'=>'<basepath>/layoutdefs/contacts_layoutdefs.php'
    ),
  ),

  'pre_uninstall' => array(
    0 => '<basepath>/scripts/pre_uninstall/acl_actions_remove.php',
  ),

  'post_execute' => array(
    0 => '<basepath>/scripts/post_execute/acl_actions_add.php',
  ),
);

?>
