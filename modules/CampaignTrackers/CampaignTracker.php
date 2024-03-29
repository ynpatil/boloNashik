<?php
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
 * $Id: CampaignTracker.php,v 1.6 2006/05/09 23:42:58 jacob Exp $
 * Description: The primary Function of this file is to manage all the data
 * used by other files in this nodule. It should extend the SugarBean which impelments
 * all the basic database operations. Any custom behaviors can be implemented here by
 * implemeting functions available in the SugarBean.
 ********************************************************************************/

  

require_once('data/SugarBean.php'); 
require_once('include/utils.php'); 

class CampaignTracker extends SugarBean {
	/* Foreach instance of the bean you will need to access the fields in the table.
	 * So define a variable for each one of them, the varaible name should be same as the field name
	 * Use this module's vardef file as a reference to create these variables.
	 */
	var $id;
	var $date_entered;
	var $created_by;
	var $date_modified;
	var $modified_by;
	var $deleted;
	var $tracker_key;
	var $tracker_url;
	var $tracker_name;
	var $campaign_id;
	var $campaign_name;
	var $message_url;
	var $is_optout;
	
	/* End field definitions*/

	/* variable $table_name is used by SugarBean and methods in this file to constructs queries
	 * set this variables value to the table associated with this bean.
	 */
	var $table_name = 'campaign_trkrs';

	/*This  variable overrides the object_name variable in SugarBean, wher it has a value of null.*/
	var $object_name = 'CampaignTracker';

	/**/
	var $module_dir = 'CampaignTrackers';

	/* This is a legacy variable, set its value to true for new modules*/
	var $new_schema = true;

	/* $column_fields holds a list of columns that exist in this bean's table. This list is referenced
	 * when fetching or saving data for the bean. As you modify a table you need to keep this up to date.
	 */
	var $column_fields = Array(
			'id'
			,'tracker_key'
			,'tracker_url'
			,'tracker_name'
			,'campaign_id'
	);

	// This is used to retrieve related fields from form posts.
	var $additional_column_fields = Array('campaign_id');
	var $relationship_fields = Array('campaing_id'=>'campaign');

	var $required_fields =  array('tracker_name'=>1,'tracker_url'=>1);

	/*This bean's constructor*/
	function CampaignTracker() {
		parent::SugarBean();



	}

	/* This method should return the summary text which is used to build the bread crumb navigation*/
	/* Generally from this method you would return value of a field that is required and is of type string*/
	function get_summary_text()
	{
		return "$this->tracker_name";
	}


	/* This method is used to generate query for the list form. The base implementation of this method
	 * uses the table_name and list_field varaible to generate the basic query and then  adds the custom field
	 * join and team filter. If you are implementing this function do not forget to consider the additional conditions.
	 */
	function create_list_query($order_by, $where)
	{
		//this object does not support custom fields.
		
   		//Build the select list for the query.
        $query = "SELECT ";
        $query .= " campaign_trkrs.* ";

		//append the WHERE clause to the $query string.
        $query .= " FROM campaign_trkrs ";

		//Append additional filter conditions.
		$where_auto = " (campaign_trkrs.deleted=0)";

		//if the function recevied a where clause append it.
		if($where != "")
			$query .= "where $where AND ".$where_auto;
		else
			$query .= "where ".$where_auto;

		//append the order by clause.
		if($order_by != "")
			$query .= " ORDER BY $order_by";
		else
			$query .= " ORDER BY campaign_trkrs.tracker_name";

		return $query;
	}

	function fill_in_additional_detail_fields() {
		global $sugar_config;
		
		//setup campaign name.
		$query = "SELECT name from campaigns where id = '$this->campaign_id'";
		$result =$this->db->query($query,true," Error filling in additional detail fields: ");

		// Get the id and the name.
		$row = $this->db->fetchByAssoc($result);
		if($row != null) {
			$this->campaign_name=$row['name'];
		}
		
		if (!class_exists('Administration')) {
			require_once('modules/Administration/Administration.php');
		}
		$admin=new Administration();
		$admin->retrieveSettings('massemailer'); //retrieve all admin settings.
		if (isset($admin->settings['massemailer_tracking_entities_location_type']) and $admin->settings['massemailer_tracking_entities_location_type']=='2'  and isset($admin->settings['massemailer_tracking_entities_location']) ) {
			$this->message_url=$admin->settings['massemailer_tracking_entities_location'];
		} else {
			$this->message_url=$sugar_config['site_url'];
		}
		if ($this->is_optout == 1) {
			$this->message_url .= '/removeme.php?'.'identifier={MESSAGE_ID}';
		} else {
			$this->message_url .= '/campaign_tracker.php?track=' . $this->id.'&identifier={MESSAGE_ID}';
		}
	}
}
?>
