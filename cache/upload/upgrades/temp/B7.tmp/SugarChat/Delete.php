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
 * Delete functionality for a Simple module
 *******************************************************************************/

  require_once('modules/SugarChat/SugarChat.php');
  require_once('log4php/LoggerManager.php');
  
  $sugarbean = new SugarChat();
  
//Perform the delete if given a record to delete
  if(empty($_REQUEST['record'])){
   $GLOBALS['log']->info('delete called without a record id specified');
  }else{
   $record = $_REQUEST['record'];
   $sugarbean->retrieve($record);
   if(!$sugarbean->ACLAccess('Delete')){
    ACLController::displayNoAccess(true);
    sugar_cleanup(true);
   }
   $GLOBALS['log']->info("deleting record: $record");
   $chatfile = md5($sugarbean->name).".txt";
   $filename_palaute = "modules/SugarChat/database/".$chatfile;
   unlink($filename_palaute);
   $sugarbean->mark_deleted($record);
  }
  
//Handle the return location variables
  $return_module = empty($_REQUEST['return_module']) ? 'SugarChat'
   : $_REQUEST['return_module'];
  
  $return_action = empty($_REQUEST['return_action']) ? 'index'
   : $_REQUEST['return_action'];
  
  $return_id = empty($_REQUEST['return_id']) ? ''
   : $_REQUEST['return_id'];
  
  $return_location = "index.php?module=$return_module&action=$return_action";
  
//Append the return_id if given
  if(!empty($return_id)){
   $return_location .= "&record=$return_id";
  }

//Now that the delete has been performed, return to given location
  header("Location: $return_location");
  
?>
