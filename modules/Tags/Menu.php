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
  global $mod_strings;

  $tag = new Tag;

  if($tag->ACLAccess('list'))
  {
    $module_menu[] = array('index.php?module=Tags&action=index', 
                           $mod_strings['LNK_TAG_LIST'], 
                           'TagList');
  }

  if($tag->ACLAccess('edit'))
  {
    $module_menu[] = array('index.php?module=Tags&action=EditView&return_module=Tags&return_action=index', 
                           $mod_strings['LNK_NEW_TAG'],
                           'CreateTag');
  }
?>
