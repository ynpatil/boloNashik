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

//
// @purpose: ACLAction didn't have a removeActions() this is a wrapper class which adds it.
//
// @note:    I am unsure about the implications on setting the delete flag to '1' on the ACL 
//           when unloading the module (though it makes perfect sense to do so).   
// @note:    The ACLAction class probably needs to be looked at, it's design doesn't make 
//           much sense to me.
//
class ACLActionWrapper extends ACLAction
{
  function ACLActionWrapper()
  {
    parent::ACLAction();
  }

  //
  // Overloaded addActions (becuase I though the logic was wrong)
  // Also, upon install, the ACL isn't being created properly when the module 
  // had been loaded/unloaded more than one time.
  // 

  function addActions($category, $type='module')
  {
    global $ACLActions;
    $db =& PearDatabase::getInstance();

    if(isset($ACLActions[$type]))
    {
      foreach($ACLActions[$type]['actions'] as $action_name =>$action_def)
      {
        $query = "SELECT * FROM " . $this->table_name . " WHERE name='$action_name' AND category = '$category' AND acltype='$type'";
        $result = $db->query($query);

        $row=$db->fetchByAssoc($result);

        if($row != null) // update it!
        {
          $this->id               = $row['id'];
          $this->name             = $row['name'];
          $this->category         = $row['category'];
          $this->aclaccess        = $row['aclaccess'];
          $this->acltype          = $row['acltype'];
          $this->modified_user_id = $row['modified_user_id'];
          $this->created_by       = $row['created_by'];
          $this->deleted          = '0';
        }
        else // create it!
        {
          $this->id               = '';
          $this->name             = $action_name;
          $this->category         = $category;
          $this->aclaccess        = $action_def['default'];
          $this->acltype          = $type;
          $this->modified_user_id = 1;
          $this->created_by       = 1;
        }

        $this->save();
      }
    }
    else
    {
      sugar_die("FAILED TO ADD: $category : $name - TYPE $type NOT DEFINED IN modules/ACLActions/actiondefs.php");
    }
  }
  
  //
  // Created removeActions (well ... because there wasn't one)
  // Also, upon uninstall, the ACL isn't being removed properly.  
  // This is my attempt at fixing it.
  // 

  function removeActions($category, $type='module')
  {
    global $ACLActions;
    $db =& PearDatabase::getInstance();

    if(isset($ACLActions[$type]))
    {
      foreach($ACLActions[$type]['actions'] as $action_name =>$action_def)
      {
        $query = "SELECT * FROM " . $this->table_name . " WHERE name='$action_name' AND category = '$category' AND acltype='$type'";

        $result = $db->query($query);

        $row=$db->fetchByAssoc($result);

        // set delete = '1' -- no removal
        if($row != null) 
        {
          $this->id               = $row['id'];
          $this->name             = $row['name'];
          $this->category         = $row['category'];
          $this->aclaccess        = $row['aclaccess'];
          $this->acltype          = $row['acltype'];
          $this->modified_user_id = $row['modified_user_id'];
          $this->created_by       = $row['created_by'];
          $this->deleted          = '1';
          $this->save();
        }
      }
    }
    else
    {
      sugar_die("FAILED TO REMOVE: $category : $name - TYPE $type NOT DEFINED IN modules/ACLActions/actiondefs.php");
    }
  }
}

?>
