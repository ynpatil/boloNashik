<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

/*******************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version
 * 1.1.3 ("License"); You may not use this file except in compliance with the
 * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an "AS IS" basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied.  See the License
 * for the specific language governing rights and limitations under the
 * License.
 *
 * All copies of the Covered Code must include on each user interface screen:
 *    (i) the "Powered by SugarCRM" logo and
 *    (ii) the SugarCRM copyright notice
 * in the same form as they appear in the distribution.  See full license for
 * requirements.
 *
 * The Original Code is: SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) 2004-2006 SugarCRM, Inc.;
 * All Rights Reserved.
 *
 * Contributor(s): George Neill <gneill@aiminstitute.org>, 
 *                 AIM Institute <http://www.aiminstitute.org>
 ******************************************************************************/

require_once('modules/Tags/Tag.php');

$tag = new Tag();

if(!$tag->ACLAccess('edit'))
{
  ACLController::displayNoAccess(true);
  sugar_cleanup(true);
}

if(isset($_REQUEST['record']) && !empty($_REQUEST['record']))
{
  $tag->retrieve($_REQUEST['record']);
}

$tag->explicit=0;

foreach($tag->column_fields as $field) 
{
  if(isset($_REQUEST[$field])) 
  {
      $tag->$field=$_REQUEST[$field];    
  }
}

foreach($tag->additional_column_fields as $field) 
{
  if(isset($_REQUEST[$field])) 
  {
    $tag->$field=$_REQUEST[$field];    
  }
}

$tag->save();
    
$rmodule = (!empty($_REQUEST['return_module'])) ? $_REQUEST['return_module'] : "Tags";
$raction = (!empty($_REQUEST['return_action'])) ? $_REQUEST['return_action'] : "DetailView";
$rid     = $tag->id;

header("Location: index.php?action={$raction}&module={$rmodule}&record={$rid}");  

?>
