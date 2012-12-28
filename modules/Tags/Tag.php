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

require_once('data/SugarBean.php');
require_once('include/utils.php');

class Tag extends SugarBean 
{
#  var $name = 'Tag';

  var $id;
  var $date_entered;
  var $created_by;
  var $date_modified;
  var $modified_user_id;
  var $deleted;
  var $title;
  var $description;
    
  // non-db fields
  var $created_by_user_name;
  var $modified_by_user_name;


  var $table_name  = 'tags';
  var $object_name = 'Tags';
  var $module_dir  = 'Tags';
  var $new_schema  = true;

  var $column_fields = array('id',
                             'date_entered',
                             'created_by',
                             'date_modified',
                             'modified_user_id',
                             'created_by_user_name',
                             'modified_by_user_name',
                             'deleted',
                             'title',
                             'description');

  function Tag() 
  {
    parent::SugarBean();
  }

  function get_summary_text()
  {
    return "$this->title";
  }

  function bean_implements($interface)
  {
    switch($interface) 
    {
      case 'ACL': return true;
    }

    return false;
  }

  function fill_in_additional_list_fields()
  {
    $this->created_by_user_name  = get_assigned_user_name($this->created_by);
    $this->modified_by_user_name = get_assigned_user_name($this->modified_user_id);
  }

  function fill_in_additional_detail_fields()
  {
    $this->fill_in_additional_list_fields();
  }

  function create_export_query($order_by, $where, $show_deleted = 0)
  {
    return $this->create_list_query($order_by, $where, $show_deleted);
  }
}
?>
