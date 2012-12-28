<?php
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
 * The Original Code is: C3CRM Team
 *                       http://www.c3crm.com
 *                       2006-5-19 
 *
 * The Initial Developer of the Original Code is C3CRM Team.
 * Portions created by C3CRM are Copyright (C) 2005 C3CRM
 * All Rights Reserved.
 ********************************************************************************/

include_once('config.php');
require_once('log4php/LoggerManager.php');
require_once('include/database/PearDatabase.php');
require_once('data/SugarBean.php');

// Contact is used to store customer information.
class Order extends SugarBean
{
	// Stored fields
   var $id;   
   
   var $lineitemid;   
   
   var $quoteid;   
   
   var $quantity;   
   
   var $productname;   
   
   var $productid;   
   
   var $productnum;   
   
   var $unit_price;   
   
   var $trade_price;   
   
   var $singleTotal;
   
   var $status;
   
   var $deleted;   
   
   var $date_entered;   
   
   var $date_modified;   
   
   var $created_by;
   var $table_name = 'orders';
   var $object_name = 'Order';
   var $module_dir = 'Orders';
   var $new_schema = true;
	
   
   var $column_fields = Array('id'
   ,'lineitemid'
   
		,'quoteid'
   
		,'quantity'
   
		,'productname'
   
		,'productid'
   
		,'productnum'
   
		,'trade_price'
   
		,'unit_price'
   
		,'status'
   
		,'deleted'
   
		,'date_entered'
   
		,'date_modified'
   
		,'created_by'
		);
   

   var $list_fields= array();
   var $required_fields = array();

   function Order()
   {
		parent::SugarBean();
		$this->list_fields = $this->column_fields;
   }

   function get_xtemplate_data() {
		$return_array = array();
		global $current_user;
		foreach($this->column_fields as $field)	{
			$return_array[strtoupper($field)] = $this->$field;
		}
		return $return_array;
	}
	function mark_deletedByQuoteid($quoteid) {
		$return_array = $this->get_full_list("id","quoteid='".$quoteid."'");
		foreach ($return_array as $value) {
			$this->mark_deleted($value->id);
		}
	}
	
	function fill_in_additional_list_fields() {
		$this->fill_in_additional_detail_fields();
		$this->singleTotal = $this->quantity * $this->trade_price;
	}
}

?>
