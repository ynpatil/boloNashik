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
* $Id: DBHelper.php,v 1.58 2006/06/23 17:35:14 chris Exp $
* Description: This file is an abstract class and handles the Data base functionality for
* the application. It is called by the DBManager class to generate various sql statements.
*
* All the functions in this class will work with any bean which implements the meta interface.
* Please refer the DBManager documentation for the details.
*
* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
* All Rights Reserved.
* Contributor(s): ______________________________________..
********************************************************************************/


require_once('log4php/LoggerManager.php');

class DBHelper
{

    var $db;
    var $bean ;

	function DBHelper(){

	}


	/**
	* This method genrates sql for create table statement for a bean.
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
	* All Rights Reserved.
	* Contributor(s): ______________________________________..
	*/
	function createTableSQL($bean){

		$tablename = $bean->getTableName();
		$fieldDefs = $bean->getFieldDefinitions();
		$indices = $bean->getIndices();
		return $this->createTableSQLParams($tablename, $fieldDefs, $indices);

	}

	function createTableSQLParams($tablename, $fieldDefs, $indices){
		$columns = $this->columnSQLRep($fieldDefs, false, $tablename);
        $keys = $this->keysSQL($indices);
        if (!empty($keys)) $keys = ",$keys";
 		if(empty($columns)){
 			return false;
 		}
		$sql = "CREATE TABLE $tablename ($columns $keys)";
 		return $sql;
	}


	/**
	* This method genrates sql for insert statement.
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
	* All Rights Reserved..
	* Contributor(s): ______________________________________..
	*/
	function insertSQL($bean){
		// get basic insert

		$sql = "INSERT INTO ".$bean->getTableName();

		// get field definitions
		$fields = $bean->getFieldDefinitions();

		// get column names and values
		$values = array();
		foreach ($fields as $fieldDef)
		{
			if(isset($fieldDef['source']) && $fieldDef['source'] != 'db') continue;
		   $val = $bean->getFieldValue($fieldDef['name']);
		   //handle auto increment values here only need to do this on insert not create
           if(isset($fieldDef['auto_increment']) && $fieldDef['auto_increment']){
           		$values[$fieldDef['name']] = $this->getAutoIncrement($bean->getTableName(), $fieldDef['name']);
           }elseif (isset($bean->$fieldDef['name']))
		   {
		     // need to do some thing about types of values
		     $values[$fieldDef['name']] = $this->massageValue($val, $fieldDef);
		   }
		   else if ($fieldDef['name'] == 'deleted'){
		   	 $values['deleted'] = $val;
		   }
		}

		if (sizeof ($values) == 0) return ""; // no columns set

		// get the entire sql
		$sql .= "(".implode(",", array_keys($values)).") ";
		$sql .= "VALUES(".implode(",", $values).")";
		return $sql;
	}

	/**
	* This method genrates sql for update statement.
	* Updates are based for the row identified by primary key only.
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
	* All Rights Reserved.
	* Contributor(s): ______________________________________..
	*/
	function updateSQL($bean, $where=array()){
		// get basic update

		$sql = "update ".$bean->getTableName();

		// get field definitions


        $primaryField = $bean->getPrimaryFieldDefinition();
        $columns = array();

		// get column names and values
		foreach ($bean->field_defs as $fieldDef)
		{
           $name = $fieldDef['name'];
           if ($name == $primaryField['name']) continue;
           if(isset($bean->$name) && (!isset($fieldDef['source']) || $fieldDef['source'] == 'db')){
			   $val = $bean->getFieldValue($name);


		       // need to do some thing about types of values
		       $val = $this->massageValue($val, $fieldDef);
		       $columns[] = "$name=$val";
           }
		}

		if (sizeof ($columns) == 0) return ""; // no columns set

		$where = $this->updateWhereArray($bean, $where);

		$where = $this->getWhereClause($bean, $where);
		// get the entire sql
		$sql .= " set ".implode(",", $columns);
		$sql .= " $where and deleted=0";
		return $sql;
	}

	/**
	* This method returns a where array so that it has id entry if
	* where is not an array or is empty
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
	* All Rights Reserved.
	* Contributor(s): ______________________________________..
	*/

	function updateWhereArray($bean, $where = array()){
		if (!is_array($where)) $where = array();

		if (sizeof($where) == 0)
		{
		  $fieldDef = $bean->getPrimaryFieldDefinition();
          $primaryColumn = $fieldDef['name'];

          $val = $bean->getFieldValue($fieldDef['name']);
          if ($val != FALSE){
            $where[$primaryColumn] = $val;
          }
		}

		return $where;
	}

    /** This function just returns a where clause without the 'where' key word
     * The clause returned does not have an 'and' at the beginning and the columns
     * are joined by 'and'.
     */
    function getColumnWhereClause($table, $whereArray=array()) {

       foreach ($whereArray as $name => $val)
       {
          $op = "=";
          if (is_array($val)){
            $op = "IN";
            $temp = array();
            foreach ($val as $tval){
                $temp[] = "'$tval'";
            }

            $val = implode(",", $temp);
            $val = "($val)";
          }
          else $val = "'$val'";
          $where[] = " $table.$name $op $val";
       }
       if (is_array($where)) $where = implode(" and ", $where);
       return $where;
    }

	/**
	* This method returns a complete where clause built from the
	* where values specified.
	*/
	function getWhereClause($bean, $whereArray)
	{
	  // get the field

	   // build an array so types of def are mapped by name for faster recovery
	   foreach ($bean->field_defs as $val){
         $types[$val['name']] = $val['type'];
       }

	   $where = " where "; // build basic so we do not need check in loop
       $where .= $this->getColumnWhereClause($bean->getTableName(), $whereArray);

	   return $where;
	}

	/**
	* This method genrates sql for delete statement identified by id.
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
	* All Rights Reserved.
	* Contributor(s): ______________________________________..
	*/
	function deleteSQL($bean, $where){
		$where = $this->updateWhereArray($bean, $where);
		$where = $this->getWhereClause($bean, $where);

		return "update ".$bean->getTableName()." set deleted=1 $where";
	}



	/**
	* This method genrates sql for select statement for any bean identified by id.
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
	* All Rights Reserved.
	* Contributor(s): ______________________________________..
	*/
	function retrieveSQL($bean, $where){
		$where = $this->updateWhereArray($bean, $where);
		$where = $this->getWhereClause($bean, $where);

		return "select * from ".$bean->getTableName()." $where and deleted=0";
	}

    /**
    * This method implements a generic sql for a collection of beans.
    * This array has the value returned by get_class method as the keys and
    * a bean as the value for that key
    * These beans will be joined in the sql by the key attribute of field defs.
    *
    * Cols is an array of columns to be returned with the keys as names of bean as identified by
    * get_class of bean. Values of this array is the array of fieldDefs to be returned for a bean.
    * If an empty array is passed, all columns are selected.
    *
    * Where is an array of values with the keys as names of bean as identified by get_class of bean
    * Each value at the first level is an array of values for that bean identified by name of fields.
    * If we want to pass multiple values for a name, pass it as an array
    * If where is not passed, all the rows will be returned.
    *
    * Currently, this function does not support outer joins.
    *
    * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
    * All Rights Reserved.
    * Contributor(s): ______________________________________..
    */
	function retrieveViewSQL($beans, $cols = array(), $whereClause = array()) {
        $relations = array(); // stores relations between tables as they are discovered

        foreach ($beans as $beanID => $bean) {
            $tableName = $bean->getTableName();
            $beanTables[$beanID] = $tableName;

            $table = "$beanID";
            $tables[$table] = $tableName;
            $aliases[$tableName][] = $table;

            // build part of select for this table
            if (is_array($cols[$beanID]))
                foreach ($cols[$beanID] as $def) $select[] = $table.".".$def['name'];

            // build part of where clause
            if (is_array($whereClause[$beanID])){
                $where[] = $this->getColumnWhereClause($table, $whereClause[$beanID]);
            }
            // initialize so that it can be used properly in form clause generation
            $table_used_in_from[$table] = false;

            $indices = $bean->getIndices();
            foreach ($indices as $index){
                if ($index['type'] == 'foreign') {
                    $relationship[$table][] = array('foreignTable'=> $index['foreignTable']
                                                   ,'foreignColumn'=>$index['foreignField']
                                                   ,'localColumn'=> $index['fields']
                                                   );
                }
            }
            $where[] = " $table.deleted = 0";
        }

        // join these clauses
        $select = (sizeof($select) > 0) ? implode(",", $select) : "*";
        $where = implode(" and ", $where);

        // generate the from clause. Use relations array to generate outer joins
        // all the rest of the tables will be used as a simple from
        // relations table define relations between table1 and table2 through column on table 1
        // table2 is assumed to joing through primaty key called id
        $separator = "";
        foreach ($relations as $table1 => $rightsidearray){
            if ($table_used_in_from[$table1]) continue; // table has been joined

            $from .= $separator." ".$table1;
            $table_used_in_from[$table1] = true;
            foreach ($rightsidearray as $tablearray){
                $table2 = $tablearray['foreignTable']; // get foreign table
                $tableAlias = $aliases[$table2]; // get a list of aliases fo thtis table
                foreach ($tableAlias as $table2) {
                    //choose first alias that does not match
                    // we are doing this because of self joins.
                    // in case of self joins, the same table will bave many aliases.
                    if ($table2 != $table1) break;
                }

                $col = $tablearray['foreingColumn'];
                $name = $tablearray['localColumn'];
                $from .= " LEFT JOIN $table on ($table1.$name = $table2.$col)";
                $table_used_in_from[$table2] = true;
            }
            $separator = ",";
        }

        return " select $select from $from where $where";
    }

	/**
	* This method genrates sql for create index statement for a bean.
	* Non Unique index is created if $unique is set to FALSE
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
	* All Rights Reserved.
	* Contributor(s): ______________________________________..
	*/
	function createIndexSQL($bean, $fields, $name, $unique=TRUE){
		$unique = ($unique) ? "unique" : "";
		$tablename = $bean->getTableName();

		// get column names
		foreach ($fields as $fieldDef) $columns[] = $fieldDef['name'];
		if (sizeof($columns) == 0) return "";
		$columns = implode(",", $columns);

		$sql = "create $unique index $name on $tablename ($columns)";

		return $sql;
	}




    // returns the type of the variable in the field
    function getFieldType($fieldDef)
    {
        // get the type for db type. if that is not set,
        // get it from type. This is done so that
        // we do not have change a lot of existing code
        // and add dbtype where type is being used for some special
        // purposes like referring to foreign table etc.
        if(!empty($fieldDef['dbType']))
       	 	return  $fieldDef['dbType'];
       	if(!empty($fieldDef['dbtype']))
       	 	return  $fieldDef['dbtype'];
        if (!empty($fieldDef['type']))
        	return  $fieldDef['type'];
        if (!empty($fieldDef['Type']))
        	return  $fieldDef['Type'];
		if (!empty($fieldDef['data_type']))
        	return  $fieldDef['data_type'];
        return null;
    }

	/** private function to get sql for a column
	*/
	function oneColumnSQLRep($fieldDef,  $ignoreRequired = false, $table=''){











		$name = $fieldDef['name'];

        $type = $this->getFieldType($fieldDef);

		$colType = $this->getColumnType($type);
        if (( $colType == 'varchar' or $colType == 'char' or $colType == 'varchar2') ) {
            	if( !empty($fieldDef['len']))
            		$colType .= "(".$fieldDef['len'].")";
            	else $colType .= "(255)";

        }
        if($colType == 'int'){
        	//only do below if db type is mysql.  Setting a length on the int will fail for oracle or sql server.
        	if($this->db->dbType == 'mysql'){
        		if( !empty($fieldDef['len']))
            		$colType .= "(".$fieldDef['len'].")";
        	}
        }
        if($colType == 'decimal' || $colType == 'float'){
        	if(!empty($fieldDef	['len'])){
        		if(substr_count($fieldDef['len'], ',') == 1){
            		$colType .= "(".$fieldDef['len'].")";
        		}
        	}
        }

		if (isset($fieldDef['default']) ){
            $default = " DEFAULT '".$fieldDef['default']."'";
		}else if(!isset($default) && $type == 'bool'){
        	$default = " DEFAULT 0 ";
        }else if(!isset($default)){
        	$default = '';
        }

        $auto_increment = '';
        if(!empty($fieldDef['auto_increment']) && $fieldDef['auto_increment']){

        	$auto_increment = $this->setAutoIncrement($table , $fieldDef['name']);
        }
        
        $required = 'NULL';  // MySQL defaults to NULL, SQL Server defaults to NOT NULL -- must specify
        if (!$ignoreRequired and (isset($fieldDef['required']) && $fieldDef['required'])){
			 $required =  "NOT NULL";
        }
        if($name == 'id' && !isset($fieldDef['required'])){
        	$required =  "NOT NULL";
        }

        $rep = "$name $colType $default $required $auto_increment";
        return $rep;
	}

	/** private function to get sql for a column
	*/
	function columnSQLRep($fieldDefs, $ignoreRequired = false, $tablename){
		$columns = array();
		if ($this->isFieldArray($fieldDefs)) {

		  foreach ($fieldDefs as $fieldDef)if(!isset($fieldDef['source']) || $fieldDef['source'] == 'db') $columns[] = $this->oneColumnSQLRep($fieldDef,false, $tablename);
		 	 $columns = implode(",", $columns);
		}
		else $columns = $this->oneColumnSQLRep($fieldDefs);

		return $columns;
	}

	//returns the next value for an auto increment
	function getAutoIncrement($table, $field_name){
		return "";
	}
	//either creates an auto increment through queries or returns sql for auto increment that can be appended to the end of column defination (mysql)
	function setAutoIncrement($table, $field_name){
		$this->deleteAutoIncrement($table, $field_name);
		return "";
	}
	//deletes an auto incremnet (for oracle not mysql)
	function deleteAutoIncrement($table, $field_name){

	}

	/**
	* A private function which generates the SQL for changing columns
	*/
	function changeColumnSQL($tablename, $fieldDefs, $action, $ignoreRequired = false){


        if ($this->isFieldArray($fieldDefs)){
          foreach ($fieldDefs as $def) $columns[] = $this->oneColumnSQLRep($def, $ignoreRequired, $tablename);
        } else {
          	$columns[] = $this->oneColumnSQLRep($fieldDefs, $ignoreRequired, $tablename);
        }

        $columns = implode(", column ", $columns);
		$sql = "alter table $tablename $action ($columns)";
		return $sql;
	}

	/**
	* This method generates sql for adding a column to table identified by field def.
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
	* All Rights Reserved.
	* Contributor(s): ______________________________________..
	*/
	function addColumnSQL($tablename, $fieldDefs){
       return $this->changeColumnSQL($tablename, $fieldDefs, 'add');
	}

	/**
	* This method genrates sql for altering old column identified by oldFieldDef to new fieldDef.
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
	* All Rights Reserved.
	* Contributor(s): ______________________________________..
	*/
	function alterColumnSQL($tablename, $newFieldDefs){
       return $this->changeColumnSQL($tablename, $newFieldDefs, 'modify', true);
	}

	/**
	* This method generates sql for dropping a table.
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
	* All Rights Reserved.
	* Contributor(s): ______________________________________..
	*/
	function dropTableSQL($bean){
		return $this->dropTableNameSQL($bean->getTableName());
	}

	function dropTableNameSQL($name){
		return "drop table if exists ".$name;
	}

	/**
	* This method generates sql that deletes a column identified by fieldDef.
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
	* All Rights Reserved.
	* Contributor(s): ______________________________________..
	*/
	function deleteColumnSQL($bean, $fieldDefs){
        if ($this->isFieldArray($fieldDefs)) foreach ($fieldDefs as $fieldDef) $columns[] = $fieldDef['name'];
        else $columns[] = $fieldDefs['name'];
		$columns = implode(", ", $columns);
		$sql = "alter table ".$bean->getTableName()." drop ($columns)";
        return $sql;
	}

	/**
	* This is a private (php does not support it as of 4.x) method.
	* It outputs a correct string for the sql statement according to value
	* This will be overwritten in derived classes.
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
	* All Rights Reserved..
	* Contributor(s): ______________________________________..
	*/
	function massageValue($val, $type){
		return $val;
	}


	/** returns the valid type for a column given the type in fieldDef
	*/

	function getColumnType($type){
       return $type;
	}

    /**
     * function to see if passed array is truely an aray of defitions
     * Such an array may have type as a key but it will point to an array
     * for a true array of definitions an to a col type for a definition only
     */
    function isFieldArray($defArray)
    {
        if (!is_array($defArray)) return false;

        if (array_key_exists('type', $defArray))
        {
          // type key exists. May be an array of defs or a simple definition
          $type = $defArray['type'];
          return is_array($type) ? TRUE : FALSE; // type is not an array => definition else array
        }
        // type does not exist. Must be array of definitions
        return TRUE;
    }

    // returns true if the type can be mapped to a valid column tyoe
    function validColumnType($type){
        $coltype = $this->getColumnType($type);
        return ($coltype) ? TRUE : FALSE;
    }

	function save_audit_records(&$bean,&$changes)
	{
		global $current_user;
		$sql = "INSERT INTO ".$bean->get_audit_table_name();

		//get field defs for the audit table.
		require('metadata/audit_templateMetaData.php');
		$fieldDefs = $dictionary['audit']['fields'];

		$values=array();
		$values['id']=$this->massageValue(create_guid(), $fieldDefs['id']);
		$values['parent_id']=$bean->dbManager->helper->massageValue($bean->id, $fieldDefs['parent_id']);
		$values['field_name']=$bean->dbManager->helper->massageValue($changes['field_name'], $fieldDefs['field_name']);
		$values['data_type']=$bean->dbManager->helper->massageValue($changes['data_type'], $fieldDefs['data_type']);
		if ($changes['data_type']=='text') {
			$bean->fetched_row[$changes['field_name']]=$bean->$changes['field_name'];
			$values['before_value_text']=$bean->dbManager->helper->massageValue($changes['before'], $fieldDefs['before_value_text']);
			$values['after_value_text']=$bean->dbManager->helper->massageValue($changes['after'], $fieldDefs['after_value_text']);
		} else {
			$bean->fetched_row[$changes['field_name']]=$bean->$changes['field_name'];
			$values['before_value_string']=$bean->dbManager->helper->massageValue($changes['before'], $fieldDefs['before_value_string']);
			$values['after_value_string']=$bean->dbManager->helper->massageValue($changes['after'], $fieldDefs['after_value_string']);
		}
		$values['date_created']=$bean->dbManager->helper->massageValue(gmdate("Y-m-d H:i:s"), $fieldDefs['date_created']);
		$values['created_by']=$bean->dbManager->helper->massageValue($current_user->id, $fieldDefs['created_by']);

		$sql .= "(".implode(",", array_keys($values)).") ";
		$sql .= "VALUES(".implode(",", $values).")";

		$bean->db->query($sql);
	}

	function save_rfc_records(&$bean,&$changes)
	{
		global $current_user;
		global $app_list_strings;
		$sql = "INSERT INTO ".$bean->get_rfc_table_name();

		//get field defs for the audit table.
		require('metadata/rfc_templateMetaData.php');
		$fieldDefs = $dictionary['audit']['fields'];

		$values=array();
		$values['id']=$this->massageValue(create_guid(), $fieldDefs['id']);
		$values['parent_id']=$bean->dbManager->helper->massageValue($bean->id, $fieldDefs['parent_id']);
		$values['field_name']=$bean->dbManager->helper->massageValue($changes['field_name'], $fieldDefs['field_name']);
		$values['data_type']=$bean->dbManager->helper->massageValue($changes['data_type'], $fieldDefs['data_type']);
		if ($changes['data_type']=='text') {
			$bean->fetched_row[$changes['field_name']]=$bean->$changes['field_name'];
			$values['before_value_text']=$bean->dbManager->helper->massageValue($changes['before'], $fieldDefs['before_value_text']);
			$values['after_value_text']=$bean->dbManager->helper->massageValue($changes['after'], $fieldDefs['after_value_text']);
		} else {
			$bean->fetched_row[$changes['field_name']]=$bean->$changes['field_name'];
			$values['before_value_string']=$bean->dbManager->helper->massageValue($changes['before'], $fieldDefs['before_value_string']);
			$values['after_value_string']=$bean->dbManager->helper->massageValue($changes['after'], $fieldDefs['after_value_string']);
		}
		$values['date_created']=$bean->dbManager->helper->massageValue(gmdate("Y-m-d H:i:s"), $fieldDefs['date_created']);
		$values['created_by']=$bean->dbManager->helper->massageValue($current_user->id, $fieldDefs['created_by']);
		$values['status']=$bean->dbManager->helper->massageValue($app_list_strings['rfc_status_default'], $fieldDefs['status']);

		$sql .= "(".implode(",", array_keys($values)).") ";
		$sql .= "VALUES('".implode("','", $values)."')";

		$bean->db->query($sql);
	}
	
	/*function uses the audit enabled fields array to find fields whose value has changed.
	 * The before and after values are stored in the bean.
	 */
	function getDataChangesForRFC(&$bean) {

		$changed_values=array();
		$audit_fields=$bean->field_defs;
		if (is_array($audit_fields) and count($audit_fields) > 0) {
//			$GLOBALS['log']->debug("DBHelper.getDataChanges ::Fields in bean :".implode(',',$bean->fetched_row));

			foreach ($audit_fields as $field=>$properties) {
				
				if(isset($properties['source']) && $properties['source'] == 'non-db')continue;
				
				if (!empty($bean->fetched_row) && array_key_exists($field, $bean->fetched_row)) {
					$GLOBALS['log']->debug("DBHelper.getDataChangesForRFQ ::Fields in bean :".$field. " exists :".array_key_exists($field, $bean->fetched_row)." value :".$bean->fetched_row[$field]);
					$before_value=$bean->fetched_row[$field];
					$after_value=$bean->$field;
					if (isset($properties['type']))
						$field_type=$properties['type'];
					else {
						if (isset($properties['dbType']))
							$field_type=$properties['dbType'];
						else if(isset($properties['data_type']))
							$field_type=$properties['data_type'];
						else
							$field_type=$properties['dbtype'];
					}

					$GLOBALS['log']->debug("DBHelper.getDataChangesForRFQ ::Field type :".$field_type." before value :".$before_value." after value :".$after_value);

					//if the type and values match, do nothing.
					if (!(emptyValue($before_value,$field_type) && emptyValue($after_value,$field_type))) {
						if (trim($before_value) !== trim($after_value)) {
							if (!(isTypeNumber($field_type) && (trim($before_value)+0) == (trim($after_value)+0))) {
								if (!(isTypeBoolean($field_type) && (getBooleanValue($before_value)== getBooleanValue($after_value)))) {
								$GLOBALS['log']->debug("Changed field :".$field);
										$changed_values[$field]=array('field_name'=>$field,
										'data_type'=>$field_type,
										'before'=>$before_value,
										'after'=>$after_value);
								}
							}
						}
					}
				}
			}
		}

		return $changed_values;
	}

	/*function uses the audit enabled fields array to find fields whose value has changed.
	 * The before and after values are stored in the bean.
	 */
	function getDataChanges(&$bean) {

		$changed_values=array();
		$audit_fields=$bean->getAuditEnabledFieldDefinitions();
		if (is_array($audit_fields) and count($audit_fields) > 0) {
//			$GLOBALS['log']->debug("DBHelper.getDataChanges ::Fields in bean :".implode(',',$bean->fetched_row));

			foreach ($audit_fields as $field=>$properties) {

				if (!empty($bean->fetched_row) && array_key_exists($field, $bean->fetched_row)) {
					$GLOBALS['log']->debug("DBHelper.getDataChanges ::Fields in bean :".$field. " exists :".array_key_exists($field, $bean->fetched_row)." value :".$bean->fetched_row[$field]);
					$before_value=$bean->fetched_row[$field];
					$after_value=$bean->$field;
					if (isset($properties['type']))
						$field_type=$properties['type'];
					else {
						if (isset($properties['dbType']))
							$field_type=$properties['dbType'];
						else if(isset($properties['data_type']))
							$field_type=$properties['data_type'];
						else
							$field_type=$properties['dbtype'];
					}

					$GLOBALS['log']->debug("DBHelper.getDataChanges ::Field type :".$field_type." before value :".$before_value." after value :".$after_value);

					//if the type and values match, do nothing.
					if (!(emptyValue($before_value,$field_type) && emptyValue($after_value,$field_type))) {
						if (trim($before_value) !== trim($after_value)) {
							if (!(isTypeNumber($field_type) && (trim($before_value)+0) == (trim($after_value)+0))) {
								if (!(isTypeBoolean($field_type) && (getBooleanValue($before_value)== getBooleanValue($after_value)))) {
								$GLOBALS['log']->debug("Changed field :".$field);
										$changed_values[$field]=array('field_name'=>$field,
										'data_type'=>$field_type,
										'before'=>$before_value,
										'after'=>$after_value);
								}
							}
						}
					}
				}
			}
		}

		return $changed_values;
	}
	
}
?>
