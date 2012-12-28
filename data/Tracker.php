<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/*********************************************************************************
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
 * Contributor(s): ______________________________________.
 ********************************************************************************/
/*********************************************************************************
 * $Id: Tracker.php,v 1.48 2006/08/15 22:15:07 awu Exp $
 * Description:  Updates entries for the Last Viewed functionality tracking the 
 * last viewed records on a per user basis.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/


include_once('data/SugarBean.php');

/** This class is used to track the recently viewed items on a per user basis.
 * It is intended to be called by each module when rendering the detail form.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
*/
class Tracker extends SugarBean{
    var $table_name = "tracker";
    var $object_name = "tracker";
	var $module_dir = '../data';
    // Tracker table
    var $column_fields = Array(
        "id",
        "user_id",
        "module_name",
        "item_id",
        "item_summary"
    );

    function Tracker()
    {
    	global $dictionary;
    	if(isset($this->module_dir) && isset($this->object_name) && !isset($dictionary[$this->object_name])){
    		require('metadata/trackerMetaData.php');
    	}
        parent::SugarBean();
    }

    /**
     * Add this new item to the tracker table.  If there are too many items (global config for now)
     * then remove the oldest item.  If there is more than one extra item, log an error.
     * If the new item is the same as the most recent item then do not change the list
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
     */
    function track_view($user_id, $module_name, $item_id, $item_summary)
    {
        $this->delete_history($user_id, $item_id);
        
        // Add a new item to the user's list

        if ($this->db->dbType=='oci8') {

        } else {
        	$esc_item_id = $this->db->quote($item_id);
        	$esc_item_summary = $this->db->quote($item_summary);
			$datetime=gmdate("Y-m-d H:i:s");		
        	$query = "INSERT into $this->table_name ( user_id, module_name, item_id, item_summary,date_modified) values ('$user_id', '$module_name', '$esc_item_id', '$esc_item_summary','$datetime')";
        }
		
        $GLOBALS['log']->info("Track Item View: ".$query);

        $this->db->query($query, true);
          
		$this->prune_history($user_id);
    }

    /**
     * param $user_id - The id of the user to retrive the history for
     * param $module_name - Filter the history to only return records from the specified module.  If not specified all records are returned
     * return - return the array of result set rows from the query.  All of the table fields are included
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
     */
    function get_recently_viewed($user_id, $module_name = "")
    {
        $history_max = 10;
        if(!empty($sugar_config['history_max_viewed']))
        {
            $history_max = $sugar_config['history_max_viewed'];
        }
        $query = "SELECT tracker.* from $this->table_name WHERE user_id='$user_id' ORDER BY id DESC";
        $GLOBALS['log']->debug("About to retrieve list: $query");
        $result = $this->db->limitQuery($query,0,$history_max,true);

        $list = Array();
        while($row = $this->db->fetchByAssoc($result, -1, false))
        {
            // If the module was not specified or the module matches the module of the row, add the row to the list
            if($module_name == "" || $row['module_name'] == $module_name)
            {
            	$list[] = $row;
            }
        }
        return $list;
    }

    
    
    /**
     * INTERNAL -- This method cleans out any entry for a record for a user.
     * It is used to remove old occurances of previously viewed items.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
     */
    function delete_history( $user_id, $item_id)
    {
        $query = "DELETE from $this->table_name WHERE user_id='$user_id' and item_id='$item_id'";
       $this->db->query($query, true);
    }
    
    /**
     * INTERNAL -- This method cleans out any entry for a record.  This is intended to delete
     * all occurances of an item that is being deleted from the system.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
     */
    function delete_item_history($item_id)
    {
        $query = "DELETE from $this->table_name WHERE item_id='$item_id'";
       $this->db->query($query, true);
            
    }
    
    /**
     * INTERNAL -- This function will clean out old history records for this user if necessary.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
     */
    function prune_history($user_id)
    {
        global $sugar_config;

        // Check to see if the number of items in the list is now greater than the config max.
        $query = "SELECT count(*) from $this->table_name WHERE user_id='$user_id'";

        $GLOBALS['log']->debug("About to verify history size: $query");

        $count = $this->db->getOne($query);
	
        $GLOBALS['log']->debug("history size: (current, max)($count, {$sugar_config['history_max_viewed']})");
        while($count > $sugar_config['history_max_viewed'])
        {
            // delete the last one.  This assumes that entries are added one at a time.
            // we should never add a bunch of entries
            $query = "SELECT $this->table_name.* from $this->table_name WHERE user_id='$user_id' ORDER BY id ASC";
            
            $GLOBALS['log']->debug("About to try and find oldest item:");
            $result =  $this->db->limitQuery($query,1,1);

            $oldest_item = $this->db->fetchByAssoc($result, -1, false);
            $query = "DELETE from $this->table_name WHERE id='{$oldest_item['id']}'";
            $GLOBALS['log']->debug("About to delete oldest item: ");

            $result = $this->db->query($query, true);
       
                
            $count--;    
        }
    }
}

?>
