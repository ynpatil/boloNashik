<?php
// created: 2012-12-20 13:05:35
$unified_search_modules = array (
  'Accounts' => 
  array (
    'table' => 'accounts',
    'fields' => 
    array (
      'name' => 
      array (
        'vname' => 'LBL_NAME',
        'type' => 'name',
      ),
      'phone_fax' => 
      array (
        'vname' => 'LBL_PHONE_FAX',
        'type' => 'phone',
      ),
      'phone_office' => 
      array (
        'vname' => 'LBL_PHONE_OFFICE',
        'type' => 'phone',
      ),
      'phone_alternate' => 
      array (
        'vname' => 'LBL_PHONE_ALT',
        'type' => 'phone',
      ),
    ),
  ),
  'Brands' => 
  array (
    'table' => 'brands',
    'fields' => 
    array (
      'name' => 
      array (
        'vname' => 'LBL_NAME',
        'type' => 'name',
      ),
    ),
  ),
  'Bugs' => 
  array (
    'table' => 'bugs',
    'fields' => 
    array (
      'bug_number' => 
      array (
        'vname' => 'LBL_NUMBER',
        'type' => 'int',
      ),
      'name' => 
      array (
        'vname' => 'LBL_LIST_SUBJECT',
        'type' => 'name',
      ),
    ),
  ),
  'Calls' => 
  array (
    'table' => 'calls',
    'fields' => 
    array (
      'tokan_no' => 
      array (
        'vname' => 'LBL_TOKEN_NO',
        'type' => NULL,
      ),
    ),
  ),
  'Cases' => 
  array (
    'table' => 'cases',
    'fields' => 
    array (
      'case_number' => 
      array (
        'vname' => 'LBL_NUMBER',
        'type' => 'int',
      ),
      'name' => 
      array (
        'vname' => 'LBL_LIST_SUBJECT',
        'type' => 'name',
      ),
      'account_name' => 
      array (
        'vname' => 'LBL_ACCOUNT_NAME',
        'type' => 'relate',
        'table' => 'accounts',
        'rname' => 'name',
      ),
    ),
  ),
  'Comments' => 
  array (
    'table' => 'comments',
    'fields' => 
    array (
      'name' => 
      array (
        'vname' => 'LBL_SUBJECT',
        'type' => 'varchar',
      ),
    ),
  ),
  'Contacts' => 
  array (
    'table' => 'contacts',
    'fields' => 
    array (
      'first_name' => 
      array (
        'vname' => 'LBL_FIRST_NAME',
        'type' => 'varchar',
      ),
      'last_name' => 
      array (
        'vname' => 'LBL_LAST_NAME',
        'type' => 'varchar',
      ),
      'phone_home' => 
      array (
        'vname' => 'LBL_HOME_PHONE',
        'type' => 'phone',
      ),
      'phone_mobile' => 
      array (
        'vname' => 'LBL_MOBILE_PHONE',
        'type' => 'phone',
      ),
      'phone_work' => 
      array (
        'vname' => 'LBL_OFFICE_PHONE',
        'type' => 'phone',
      ),
      'phone_other' => 
      array (
        'vname' => 'LBL_OTHER_PHONE',
        'type' => 'phone',
      ),
      'phone_fax' => 
      array (
        'vname' => 'LBL_FAX_PHONE',
        'type' => 'phone',
      ),
      'email1' => 
      array (
        'vname' => 'LBL_EMAIL_ADDRESS',
        'type' => 'email',
      ),
      'email2' => 
      array (
        'vname' => 'LBL_OTHER_EMAIL_ADDRESS',
        'type' => 'email',
      ),
      'assistant' => 
      array (
        'vname' => 'LBL_ASSISTANT',
        'type' => 'varchar',
      ),
      'assistant_phone' => 
      array (
        'vname' => 'LBL_ASSISTANT_PHONE',
        'type' => 'phone',
      ),
    ),
  ),
  'Leads' => 
  array (
    'table' => 'leads',
    'fields' => 
    array (
      'first_name' => 
      array (
        'vname' => 'LBL_FIRST_NAME',
        'type' => 'name',
      ),
      'last_name' => 
      array (
        'vname' => 'LBL_LAST_NAME',
        'type' => 'name',
      ),
      'phone_home' => 
      array (
        'vname' => 'LBL_HOME_PHONE',
        'type' => 'phone',
      ),
      'phone_mobile' => 
      array (
        'vname' => 'LBL_MOBILE_PHONE',
        'type' => 'phone',
      ),
      'phone_work' => 
      array (
        'vname' => 'LBL_OFFICE_PHONE',
        'type' => 'phone',
      ),
      'phone_other' => 
      array (
        'vname' => 'LBL_OTHER_PHONE',
        'type' => 'phone',
      ),
      'phone_fax' => 
      array (
        'vname' => 'LBL_FAX_PHONE',
        'type' => 'phone',
      ),
      'email1' => 
      array (
        'vname' => 'LBL_EMAIL_ADDRESS',
        'type' => 'email',
      ),
      'email2' => 
      array (
        'vname' => 'LBL_OTHER_EMAIL_ADDRESS',
        'type' => 'email',
      ),
      'account_name' => 
      array (
        'vname' => 'LBL_ACCOUNT_NAME',
        'type' => 'varchar',
      ),
    ),
  ),
  'Opportunities' => 
  array (
    'table' => 'opportunities',
    'fields' => 
    array (
      'name' => 
      array (
        'vname' => 'LBL_OPPORTUNITY_NAME',
        'type' => 'name',
      ),
      'account_name' => 
      array (
        'vname' => 'LBL_ACCOUNT_NAME',
        'type' => 'relate',
        'table' => 'accounts',
        'rname' => 'name',
      ),
    ),
  ),
  'Project' => 
  array (
    'table' => 'project',
    'fields' => 
    array (
      'name' => 
      array (
        'vname' => 'LBL_NAME',
        'type' => 'name',
      ),
    ),
  ),
  'ProjectTask' => 
  array (
    'table' => 'project_task',
    'fields' => 
    array (
      'name' => 
      array (
        'vname' => 'LBL_NAME',
        'type' => 'name',
      ),
    ),
  ),
  'Reviews' => 
  array (
    'table' => 'reviews',
    'fields' => 
    array (
      'name' => 
      array (
        'vname' => 'LBL_SUBJECT',
        'type' => 'name',
      ),
    ),
  ),
);
?>
