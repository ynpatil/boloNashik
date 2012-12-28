<?php
if (!defined('sugarEntry') || !sugarEntry)    die('Not A Valid Entry Point');

global $mod_strings, $app_list_strings, $app_strings, $current_user, $import_bean_map;
global $import_file_name;
global $theme;
global $outlook_contacts_field_map;
global $act_contacts_field_map;
global $salesforce_contacts_field_map;
global $outlook_accounts_field_map;
global $act_accounts_field_map;
global $salesforce_accounts_field_map;
global $salesforce_opportunities_field_map;
global $users_field_map;
global $sugar_config;


ini_set("max_execution_time", 360000);

require_once ('data/Tracker.php');
require_once ('modules/Import/ImportMap.php');
require_once ('modules/Import/UsersLastImport.php');
require_once ('modules/Import/parse_utils.php');
require_once ('include/ListView/ListView.php');
require_once ('modules/Import/config.php');
require_once ('include/utils.php');
require_once('modules/Import/Forms.php');
require_once('include/utils.php');



$tmp_file_name = $sugar_config['import_dir'] . "Lead.csv";
$max_lines = -1;
$ret_value = 0;
$has_header = 1;
$delimiter = ",";

if (is_file($tmp_file_name)) {
    $ret_value = parse_import($tmp_file_name, $delimiter, $max_lines, $has_header);

    $rows = $ret_value['rows'];
    $ret_field_count = $ret_value['field_count'];
    $saved_ids = array();
    $firstrow = 0;

    if ($has_header == 1) {
        $firstrow = array_shift($rows);
    }
    //echo "<pre>"; print_r($firstrow);//exit;

    $field_map = $outlook_contacts_field_map;
    foreach ($firstrow as $key => $value) {
        if ($outlook_contacts_field_map[$value]) {
            $import_field_array['colnum' . $key] = $outlook_contacts_field_map[$value];
        } else {
            $import_field_array['colnum' . $key] = "-1";
        }
    }
    // print_r($import_field_array);exit;

    $bean = $import_bean_map['Leads'];
    require_once ("modules/Import/$bean.php");
    $focus = new $bean ();

    //name of duplicate import log file, append it with module and date stamp to insure unique name
    $today = getdate();
    $timeOfDay = $today['mday'] . "-" . $today['mon'] . "-" . $today[year] . "-" . $today['hours'] . "h-" . $today['seconds'] . "s";
    $ImportErrorFile = "cache/import/ImportErrorFile_" . $focus->module_dir . "_" . $timeOfDay . ".csv";
    $ImportLogFile = "cache/import/ImportLog_" . $focus->module_dir . "_" . $timeOfDay . ".log";
    
    log_into_file($ImportLogFile, "=====================IMPORT STARTED : $timeOfDay ================\n");
    
    $importable_fields = array();
    $translated_column_fields = array();
    get_importable_fields($focus, $importable_fields, $translated_column_fields);

//$import_field_array = array('colnum0' => 'login',
//    'colnum1' => 'first_name',
//    'colnum2' => 'last_name',
//    'colnum3' => 'phone_other',
//    'colnum4' => 'phone_mobile',
//    'colnum5' => 'experience',
//    'colnum6' => 'level',
//    'colnum7' => 'email1',
//    'colnum8' => 'primary_address_street',
//    'colnum9' => 'primary_address_city',
//    'colnum10' => '-1',
//    'colnum11' => 'gender',);

    foreach ($import_field_array as $name => $value) {
        // only look for var names that start with "colnum"
        if (strncasecmp($name, "colnum", 6) != 0) {
            continue;
        }
        if ($value == "-1") {
            continue;
        }
        // this value is a user defined field name
        $user_field = $value;
        // pull out the column position for this field name
        $pos = substr($name, 6);
        // make sure we haven't seen this field defined yet
        if (isset($field_to_pos[$user_field])) {
            //show_error_import($mod_strings['LBL_ERROR_MULTIPLE']);
            //exit;
        }
        // match up the "official" field to the user 
        // defined one, and map to columm position: 
        //$translated_column_fields = $mod_list_strings[$list_string_key];

        $module_custom_fields_def = $focus->custom_fields->avail_fields;
        foreach ($module_custom_fields_def as $name => $field_def) {
            if ($name != 'id_c')
                $importable_fields[$field_def['name']] = 1;
        }

        if (isset($importable_fields[$user_field])) {
            // now mark that we've seen this field
            $field_to_pos[$user_field] = $pos;
            $col_pos_to_field[$pos] = $user_field;
        }
    }
    log_into_file($ImportLogFile, "File is deleted [$tmp_file_name]\n");
    if (file_exists($tmp_file_name)) {
        // unlink($tmp_file_name);
    }

    $fieldDefs = $focus->getFieldDefinitions();

    foreach ($rows as $row) {
        //$count = count($row);
        //$not_imported_str = 'id_exists,'.implode(",",$row)."\n";
        $focus = & new $bean ();
        $focus->save_from_post = false;

        $do_save = 1;

        for ($field_count = 0; $field_count < $ret_field_count; $field_count++) {
            if (isset($col_pos_to_field[$field_count])) {
                if (!isset($row[$field_count])) {
                    continue;
                }
                // TODO: add check for user input
                // addslashes, striptags, etc..
                $field = $col_pos_to_field[$field_count];

                // handle _dom based values
                if ($fieldDefs[$field]['type'] == 'enum') {
                    // we found a _dom type value - compare and assign, or drop if not found
                    foreach ($app_list_strings[$fieldDefs[$field]['options']] as $key => $value) {
                        if ((strtolower($row[$field_count]) == strtolower($value)) && ($value != "")) {
                            $row[$field_count] = $value;
                        }
                    }
                }
                $focus->$field = str_replace('"', "", $row[$field_count]);
            }
        }

        $var_def_indexes = $dictionary[$focus->object_name]['indices'];

        // now do any special processing
        // $focus->process_special_fields();

        $focus->get_names_from_full_name();
        $focus->add_create_assigned_user_name();
        $focus->add_salutation();
        $focus->add_lead_status();
        $focus->add_lead_source();
        $focus->add_do_not_call();
        $focus->add_email_opt_out();
        $focus->add_primary_address_streets();
        $focus->add_alt_address_streets();

        $import_city = $focus->primary_address_city;
        $focus->add_primary_address_city();
        #Check into master
        if ($import_city && !$focus->primary_address_city) {
            $badline = implode(",", $row);
            $not_imported_str = $badline . ",$import_city is not found into City master Database \n";
            log_into_file($ImportErrorFile, $not_imported_str);
            continue;
        }

        $import_level = $focus->level;
        $focus->add_level();
        if ($import_level && !$focus->level) {
            $badline = implode(",", $row);
            $not_imported_str = $badline . ",$import_level is not found into Level master Database \n";
            log_into_file($ImportErrorFile, $not_imported_str);
            continue;
        }

        #if the id was specified
        if (isset($focus->id)) {
            $focus->id = convert_id($focus->id);
            // check if it already exists
            $check_bean = & new $bean ();
            $query = "select * from {$check_bean->table_name} WHERE id='{$focus->id}'";
            $GLOBALS['log']->info($query);
            $result = $check_bean->db->query($query) or sugar_die("Error selecting sugarbean: ");
            $dbrow = $check_bean->db->fetchByAssoc($result);
            if (isset($dbrow['id']) && $dbrow['id'] != -1) {
                // if it exists but was deleted, just remove it
                if (isset($dbrow['deleted']) && $dbrow['deleted'] == 1) {
                    $query2 = "delete from {$check_bean->table_name} WHERE id='{$focus->id}'";
                    $GLOBALS['log']->info($query2);
                    $result2 = $check_bean->db->query($query2) or sugar_die("Error deleting existing sugarbean: ");
                } else {
                    $id_exists_count++;
                    $do_save = 0;
                    $badline = implode(",", $row);
                    $not_imported_str = "$badline\n";
                    log_into_file($ImportErrorFile, $not_imported_str);
                    $GLOBALS['log']->info("[IMPORT][ID EXISTS ALREADY]:[" . $not_imported_str . "]");
                    continue;
                }
            }
            // check if the id is too long
            else
            if (strlen($focus->id) > 36) {
                $broken_ids++;
                $do_save = 0;
                $badline = implode(",", $row);
                $not_imported_str = "$badline\n";
                log_into_file($ImportErrorFile, $not_imported_str);
                $GLOBALS['log']->info("[IMPORT][ID TOO LONG]:[" . $not_imported_str . "]");
                continue;
            }
            if ($do_save != 0) {
                // set the flag to force an insert
                $focus->new_with_id = true;
            }
        }

        $no_required = 0;
        foreach ($focus->required_fields as $field => $notused) {
            if (!isset($focus->$field) || $focus->$field == '') {
                $do_save = 0;
                $skip_required_count++;
                $badline = implode(",", $row);
                $not_imported_str = $badline . ",$field is required\n";
                log_into_file($ImportErrorFile, $not_imported_str);
                $GLOBALS['log']->info("[IMPORT][NOT IMPORTED]:[" . $not_imported_str . "]");
                $no_required = 1;
                break;
            }
        }
        // If required fields are not available then log into file with error msg and continue execution 
        if ($no_required == 1) {
            continue;
        }

        // Checking duplicates entry and update with new one.
        //echo "<pre>";print_r($var_def_indexes);exit;
        $isUnique = checkForDupesAndSetID($focus, $row);

         if ($do_save) {
            if (!isset($focus->assigned_user_id) || $focus->assigned_user_id == '') {
                $focus->assigned_user_id = $current_user->id;
            }
            if (!isset($focus->modified_user_id) || $focus->modified_user_id == '') {
                $focus->modified_user_id = $current_user->id;
            }
            $focus->save();
            
            
            array_push($saved_ids, $focus->id);
            $count++;
        }
    }
} else {
    log_into_file($ImportLogFile, "File Lead.csv is not available at location ".$sugar_config['import_dir']."\n");
    //echo "<b>File Lead.csv is not available at location " . $sugar_config['import_dir'] . "</b>";
}

if (count($saved_ids) > 0) {
    log_into_file($ImportLogFile, "Total CSV file Record: " . count($rows) . "\n");
    log_into_file($ImportLogFile, "Total inserted Record: " . count($saved_ids) . "\n");
    //echo "<br><b>Total inserted Record: " . count($saved_ids) . "</b>";
}


/*
 * This function will search import file for duplicate rows.  It will search the database based on the 
 * indexes selected by the user.  If no entries are selected, then no search is made and all
 * rows are created. or it will update
 * */

function checkForDupesAndSetID(&$focus, $import_row) {
    $sql = "select * from {$focus->table_name} where ";

    if ($focus->email1 && $focus->phone_mobile && $focus->last_name) {
        $sql.=" email1='{$focus->email1}' and phone_mobile='{$focus->phone_mobile}' and last_name='{$focus->last_name}'";
        $result = $focus->db->query($sql);
        $row = $focus->db->fetchByAssoc($result);
    } elseif ($focus->email1 && $focus->phone_mobile) {
        $sql.=" email1='{$focus->email1}' and phone_mobile='{$focus->phone_mobile}'";
        $result = $focus->db->query($sql);
        $row = $focus->db->fetchByAssoc($result);
    }
    if ($row[id]) {
        $focus->id = $row[id];
    }
}

function log_into_file($file_name, $str) {
    $fh = fopen($file_name, 'a+');
    fwrite($fh, $str);
    fclose($fh);
}

?>
