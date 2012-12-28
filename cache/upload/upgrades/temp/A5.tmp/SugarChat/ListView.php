<?php
include('modules/SugarChat/NewEntryPoint.php');
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
 * The Original Code is: CommuniCore
 *                       Olavo Farias
 *                       2006-04-7 olavo.farias@gmail.com
 *
 * The Initial Developer of the Original Code is CommuniCore.
 * Portions created by CommuniCore are Copyright (C) 2005 CommuniCore Ltda
 * All Rights Reserved.
 ********************************************************************************/
/*******************************************************************************
 * Display of ListView for Simple module template
 *******************************************************************************/

  require_once('XTemplate/xtpl.php');
  require_once('themes/' . $theme . '/layout_utils.php');
  require_once('include/ListView/ListView.php');
  require_once('log4php/LoggerManager.php');
  require_once('include/modules.php');
  require_once('modules/SugarChat/SugarChat.php');
  
  global $current_language;
  global $app_strings;
  
  // include('include/QuickSearchDefaults.php');
  echo $qsScripts;
  
  $mod_strings = return_module_language($current_language, 'SugarChat');
  
  if (!isset($where)) $where = '';
  require_once('modules/MySettings/StoreQuery.php');
  $storeQuery = new StoreQuery();
  if($_REQUEST['action'] == 'index'){
   if(!isset($_REQUEST['query'])){
    $storeQuery->loadQuery($currentModule);
    $storeQuery->populateRequest();
   }else{
    $storeQuery->saveFromGet($currentModule); 
   }
  }
  $seedSugarChat = new SugarChat();
  $where_clauses = array();

//Query processing (START)
  if(isset($_REQUEST['query'])){
   // we have a query
   if (isset($_REQUEST['current_user_only'])) $current_user_only = $_REQUEST['current_user_only'];
 //BUILDER:START of query 
 //BUILDER:END of query 
  
  
   if(isset($current_user_only) && $current_user_only != "") array_push($where_clauses, "sugarchat.assigned_user_id='$current_user->id'");

   $seedSugarChat->custom_fields->setWhereClauses($where_clauses);
  
   $where = '';
   foreach($where_clauses as $clause){
    if($where != '')
    $where .= ' AND ';
    $where .= $clause;
   }
   $GLOBALS['log']->info("Here is the where clause for the list view: $where");
  }
//Query processing (END)

  $seed_simple = new SugarChat();
////////////////////////////////////////////////////////////////////////////////
// Search Form Processing (START)
  if(empty($_REQUEST['search_form'])){
   $search_form = new XTemplate('modules/SugarChat/SearchForm.html');
  
// The title label and arrow pointing to the module search form
   $header_text = '';   
   if(is_admin($current_user)   
    && $_REQUEST['module'] != 'DynamicLayout'   
    && !empty($_SESSION['editinplace']))   
   {   
    $header_text = "<a href='index.php?action=index&module=DynamicLayout&from_action=SearchForm&from_module="   
     .$_REQUEST['module'] ."'>"   
     .get_image($image_path."EditLayout","border='0' alt='Edit Layout' align='bottom'")   
     ."</a>";   
   }   
       
   $header = get_form_header($mod_strings['LBL_SEARCH_FORM_TITLE'], $header_text, false);
  
   $search_form->assign('header',     $header);
   $search_form->assign('MOD',        $mod_strings);
   $search_form->assign('APP',        $app_strings);
	  $search_form->assign("IMAGE_PATH", $image_path);
	  $search_form->assign("ADVANCED_SEARCH_PNG", get_image($image_path.'advanced_search','alt="'.$app_strings['LNK_ADVANCED_SEARCH'].'"  border="0"'));
	  $search_form->assign("BASIC_SEARCH_PNG", get_image($image_path.'basic_search','alt="'.$app_strings['LNK_BASIC_SEARCH'].'"  border="0"'));
   $search_form->assign("JAVASCRIPT", get_clear_form_js());
// Needed if date field on SearchForm
   $search_form->assign("CALENDAR_DATEFORMAT", $timedate->get_cal_date_format());
   if(isset($current_user_only)){
    $search_form->assign('CURRENT_USER_ONLY', 'checked="checked"');
   }

 //BUILDER:START of request Bas 
 //BUILDER:END of request Bas 

////////////////////////////////////////////////////////////////////////////////
// Advanced Search Form Processing (START)
  	if (isset($_REQUEST['advanced']) && $_REQUEST['advanced'] == 'true') {
    echo get_form_header($mod_strings['LBL_SEARCH_FORM_TITLE']. $header_text, "", false);

    //BUILDER:START of request Adv 
    //BUILDER:END of request Adv 

		  // adding custom fields:
		  $seedSugarChat->custom_fields->populateXTPL($search_form, 'search' );
		  $search_form->parse("advanced");
		  $search_form->out("advanced");
	  }
// Advanced Search Form Processing (END)
////////////////////////////////////////////////////////////////////////////////
	  else 
	  {
    // Adding custom fields:
    $seedSugarChat->custom_fields->populateXTPL($search_form, 'search' );
    $search_form->parse('main');
    $search_form->out('main');
   }
  }
// Search Form Processing (END)
////////////////////////////////////////////////////////////////////////////////

  $theme_path = "themes/$theme";
  $img_path   = "$theme_path/images";
  
  $listview   = new ListView();
  $listview->initNewXTemplate('modules/SugarChat/ListView.html', $mod_strings);
  $listview->setHeaderTitle($mod_strings['LBL_LIST_FORM_TITLE']);
  
  if(is_admin($current_user)   
   && $_REQUEST['module'] != 'DynamicLayout'   
   && !empty($_SESSION['editinplace']))   
  {   
   $listview->setHeaderText("<a href='index.php?action=index&module=DynamicLayout&from_action=ListView&from_module="   
    .$_REQUEST['module'] ."'>"   
    .get_image($image_path."EditLayout","border='0' alt='Edit Layout' align='bottom'")   
    ."</a>" );   
  }
  
  $listview->setQuery($where, '', 'name', 'SIMPLE');
  
  $listview->setAdditionalDetails();
  $listview->processListView($seed_simple,  'main', 'SIMPLE');

?>
