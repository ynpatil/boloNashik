<?php

/* * *******************************************************************************
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
 * ****************************************************************************** */
/* * *******************************************************************************
 * $Id: utils.php,v 1.348.2.2 2006/09/13 00:00:05 wayne Exp $
 * Description:  Includes generic helper functions used throughout the application.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 * ****************************************************************************** */

require_once('include/utils/external_cache.php');
require_once('include/utils/security_utils.php');

function make_sugar_config(&$sugar_config) {
    /* used to convert non-array config.php file to array format */
    global $admin_export_only;
    global $cache_dir;
    global $calculate_response_time;
    global $create_default_user;
    global $dateFormats;
    global $dbconfig;
    global $dbconfigoption;
    global $default_action;
    global $default_charset;
    global $default_currency_name;
    global $default_currency_symbol;
    global $default_currency_iso4217;
    global $defaultDateFormat;
    global $default_language;
    global $default_module;
    global $default_password;
    global $default_theme;
    global $defaultTimeFormat;
    global $default_user_is_admin;
    global $default_user_name;
    global $disable_export;
    global $disable_persistent_connections;
    global $display_email_template_variable_chooser;
    global $display_inbound_email_buttons;
    global $history_max_viewed;
    global $host_name;
    global $import_dir;
    global $languages;
    global $list_max_entries_per_page;
    global $lock_default_user_name;
    global $log_memory_usage;
    global $requireAccounts;
    global $RSS_CACHE_TIME;
    global $session_dir;
    global $site_URL;
    global $site_url;
    global $sugar_version;
    global $timeFormats;
    global $tmp_dir;
    global $translation_string_prefix;
    global $unique_key;
    global $upload_badext;
    global $upload_dir;
    global $upload_maxsize;
    global $import_max_execution_time;
    global $list_max_entries_per_subpanel;

    // assumes the following variables must be set:
    // $dbconfig, $dbconfigoption, $cache_dir, $import_dir, $session_dir, $site_URL, $tmp_dir, $upload_dir

    $sugar_config = array(
        'admin_export_only' => empty($admin_export_only) ? false : $admin_export_only,
        'export_delimiter' => empty($export_delimiter) ? ',' : $export_delimiter,
        'cache_dir' => empty($cache_dir) ? 'cache/' : $cache_dir,
        'calculate_response_time' => empty($calculate_response_time) ? true : $calculate_response_time,
        'create_default_user' => empty($create_default_user) ? false : $create_default_user,
        'date_formats' => empty($dateFormats) ? array(
            'Y-m-d' => '2006-12-23',
            'd-m-Y' => '23-12-2006',
            'm-d-Y' => '12-23-2006',
            'Y/m/d' => '2006/12/23',
            'd/m/Y' => '23/12/2006',
            'm/d/Y' => '12/23/2006',
            'Y.m.d' => '2006.12.23',
            'd.m.Y' => '23.12.2006',
            'm.d.Y' => '12.23.2006'
                ) : $dateFormats,
        'dbconfig' => $dbconfig, // this must be set!!
        'dbconfigoption' => $dbconfigoption, // this must be set!!
        'default_action' => empty($default_action) ? 'index' : $default_action,
        'default_charset' => empty($default_charset) ? 'UTF-8' : $default_charset,
        'default_currency_name' => empty($default_currency_name) ? 'US Dollar' : $default_currency_name,
        'default_currency_symbol' => empty($default_currency_symbol) ? '$' : $default_currency_symbol,
        'default_currency_iso4217' => empty($default_currency_iso4217) ? '$' : $default_currency_iso4217,
        'default_date_format' => empty($defaultDateFormat) ? 'Y-m-d' : $defaultDateFormat,
        'default_language' => empty($default_language) ? 'en_us' : $default_language,
        'default_module' => empty($default_module) ? 'Home' : $default_module,
        'default_password' => empty($default_password) ? '' : $default_password,
        'default_theme' => empty($default_theme) ? 'Sugar' : $default_theme,
        'default_time_format' => empty($defaultTimeFormat) ? 'H:i' : $defaultTimeFormat,
        'default_user_is_admin' => empty($default_user_is_admin) ? false : $default_user_is_admin,
        'default_user_name' => empty($default_user_name) ? '' : $default_user_name,
        'disable_export' => empty($disable_export) ? false : $disable_export,
        'disable_persistent_connections' => empty($disable_persistent_connections) ? false : $disable_persistent_connections,
        'display_email_template_variable_chooser' => empty($display_email_template_variable_chooser) ? false : $display_email_template_variable_chooser,
        'display_inbound_email_buttons' => empty($display_inbound_email_buttons) ? false : $display_inbound_email_buttons,
        'history_max_viewed' => empty($history_max_viewed) ? 10 : $history_max_viewed,
        'host_name' => empty($host_name) ? 'localhost' : $host_name,
        'import_dir' => $import_dir, // this must be set!!
        'languages' => empty($languages) ? array('en_us' => 'US English') : $languages,
        'list_max_entries_per_page' => empty($list_max_entries_per_page) ? 20 : $list_max_entries_per_page,
        'list_max_entries_per_subpanel' => empty($list_max_entries_per_subpanel) ? 10 : $list_max_entries_per_subpanel,
        'lock_default_user_name' => empty($lock_default_user_name) ? false : $lock_default_user_name,
        'log_memory_usage' => empty($log_memory_usage) ? false : $log_memory_usage,
        'require_accounts' => empty($requireAccounts) ? true : $requireAccounts,
        'rss_cache_time' => empty($RSS_CACHE_TIME) ? '10800' : $RSS_CACHE_TIME,
        'session_dir' => $session_dir, // this must be set!!
        'site_url' => empty($site_URL) ? $site_url : $site_URL, // this must be set!!
        'sugar_version' => empty($sugar_version) ? 'unknown' : $sugar_version,
        'time_formats' => empty($timeFormats) ? array(
            'H:i' => '23:00', 'h:ia' => '11:00pm', 'h:iA' => '11:00PM',
            'H.i' => '23.00', 'h.ia' => '11.00pm', 'h.iA' => '11.00PM') : $timeFormats,
        'tmp_dir' => $tmp_dir, // this must be set!!
        'translation_string_prefix' => empty($translation_string_prefix) ? false : $translation_string_prefix,
        'unique_key' => empty($unique_key) ? md5(create_guid()) : $unique_key,
        'upload_badext' => empty($upload_badext) ? array(
            'php', 'php3', 'php4', 'php5', 'pl', 'cgi', 'py',
            'asp', 'cfm', 'js', 'vbs', 'html', 'htm') : $upload_badext,
        'upload_dir' => $upload_dir, // this must be set!!
        'upload_maxsize' => empty($upload_maxsize) ? 3000000 : $upload_maxsize,
        'import_max_execution_time' => empty($import_max_execution_time) ? 3600 : $import_max_execution_time,
        'lock_homepage' => false,
        'lock_subpanels' => false,
        'max_dashlets_homepage' => 15,
        'dashlet_display_row_options' => array('1', '3', '5', '10'),
        'default_max_tabs' => empty($max_tabs) ? '12' : $max_tabs,
        'default_max_subtabs' => empty($max_subtabs) ? '12' : $max_subtabs,
        'default_subpanel_tabs' => empty($subpanel_tabs) ? true : $subpanel_tabs,
        'default_subpanel_links' => empty($subpanel_links) ? false : $subpanel_links,
        'default_swap_last_viewed' => empty($swap_last_viewed) ? false : $swap_last_viewed,
        'default_swap_shortcuts' => empty($swap_shortcuts) ? false : $swap_shortcuts,
        'default_navigation_paradigm' => empty($navigation_paradigm) ? 'm' : $navigation_paradigm,
        'js_lang_version' => 1
    );
}

function get_sugar_config_defaults() {
    global $locale;
    /**
     * used for getting base values for array style config.php.  used by the
     * installer and to fill in new entries on upgrades.  see also:
     * sugar_config_union
     */
    $sugar_config_defaults = array(
        'admin_export_only' => false,
        'export_delimiter' => ',',
        'calculate_response_time' => true,
        'create_default_user' => false,
        'date_formats' => array(
            'Y-m-d' => '2006-12-23', 'm-d-Y' => '12-23-2006', 'd-m-Y' => '23-12-2006',
            'Y/m/d' => '2006/12/23', 'm/d/Y' => '12/23/2006', 'd/m/Y' => '23/12/2006',
            'Y.m.d' => '2006.12.23', 'd.m.Y' => '23.12.2006', 'm.d.Y' => '12.23.2006',),
        'dbconfigoption' => array(
            'persistent' => true,
            'autofree' => false,
            'debug' => 0,
            'seqname_format' => '%s_seq',
            'portability' => 0,
            'ssl' => false),
        'default_action' => 'index',
        'default_charset' => return_session_value_or_default('default_charset', 'UTF-8'),
        'default_currency_name' => return_session_value_or_default('default_currency_name', 'US Dollar'),
        'default_currency_symbol' => return_session_value_or_default('default_currency_symbol', '$'),
        'default_currency_iso4217' => return_session_value_or_default('default_currency_iso4217', 'USD'),
        'default_date_format' => 'Y-m-d',
        'default_language' => return_session_value_or_default('default_language', 'en_us'),
        'default_module' => 'Home',
        'default_password' => '',
        'default_theme' => return_session_value_or_default('site_default_theme', 'Sugar'),
        'default_time_format' => 'H:i',
        'default_user_is_admin' => false,
        'default_user_name' => '',
        'disable_export' => false,
        'disable_persistent_connections' =>
        return_session_value_or_default('disable_persistent_connections', 'false'),
        'display_email_template_variable_chooser' => false,
        'display_inbound_email_buttons' => false,
        'dump_slow_queries' => false,
        'history_max_viewed' => 10,
        'installer_locked' => true,
        'languages' => array('en_us' => 'US English'),
        'large_scale_test' => false,
        'list_max_entries_per_page' => 20,
        'list_max_entries_per_subpanel' => 10,
        'lock_default_user_name' => false,
        'log_memory_usage' => false,
        'login_nav' => false,
        'require_accounts' => true,
        'rss_cache_time' => return_session_value_or_default('rss_cache_time', '10800'),
        'save_query' => 'all',
        'slow_query_time_msec' => '100',
        'sugarbeet' => true,
        'time_formats' => array(
            'H:i' => '23:00', 'h:ia' => '11:00pm', 'h:iA' => '11:00PM',
            'H.i' => '23.00', 'h.ia' => '11.00pm', 'h.iA' => '11.00PM'),
        'translation_string_prefix' =>
        return_session_value_or_default('translation_string_prefix', false),
        'upload_badext' => array(
            'php', 'php3', 'php4', 'php5', 'pl', 'cgi', 'py',
            'asp', 'cfm', 'js', 'vbs', 'html', 'htm'),
        'upload_maxsize' => 3000000,
        'import_max_execution_time' => 3600,
        'use_php_code_json' => returnPhpJsonStatus(),
        'verify_client_ip' => true,
        'js_custom_version' => '',
        'js_lang_version' => 1,
        'default_number_grouping_seperator' => ',',
        'default_decimal_seperator' => '.',
        'lock_homepage' => false,
        'lock_subpanels' => false,
        'max_dashlets_homepage' => '15',
        'default_max_tabs' => '12',
        'default_max_subtabs' => '12',
        'dashlet_display_row_options' => array('1', '3', '5', '10'),
        'default_subpanel_tabs' => true,
        'default_subpanel_links' => false,
        'default_swap_last_viewed' => false,
        'default_swap_shortcuts' => false,
        'default_navigation_paradigm' => 'm',
    );

    if (!is_object($locale)) {
        if (!class_exists('Localization')) {
            require_once('include/Localization/Localization.php');
        }
        $locale = new Localization();
    }

    $sugar_config_defaults['default_currencies'] = $locale->getDefaultCurrencies();

    $sugar_config_defaults = sugarArrayMerge($locale->getLocaleConfigDefaults(), $sugar_config_defaults);
    return( $sugar_config_defaults );
}

function load_menu($path) {
    global $module_menu;
    require_once($path . 'Menu.php');
    if (file_exists('custom/' . $path . 'Ext/Menus/menu.ext.php')) {
        require_once('custom/' . $path . 'Ext/Menus/menu.ext.php');
    }
    if (file_exists('custom/application/Ext/Menus/menu.ext.php')) {
        require_once('custom/application/Ext/Menus/menu.ext.php');
    }
    return $module_menu;
}

function sugar_config_union($default, $override) {
    // a little different then array_merge and array_merge_recursive.  we want
    // the second array to override the first array if the same value exists,
    // otherwise merge the unique keys.  it handles arrays of arrays recursively
    // might be suitable for a generic array_union
    if (!is_array($override)) {
        $override = array();
    }
    foreach ($default as $key => $value) {
        if (!array_key_exists($key, $override)) {
            $override[$key] = $value;
        } else if (is_array($key)) {
            $override[$key] = sugar_config_union($value, $override[$key]);
        }
    }
    return( $override );
}

function make_not_writable($file) {
    // Returns true if the given file/dir has been made not writable
    $ret_val = false;
    if (is_file($file) || is_dir($file)) {
        if (!is_writable($file)) {
            $ret_val = true;
        } else {
            $original_fileperms = fileperms($file);

            // take away writable permissions
            $new_fileperms = $original_fileperms & ~0x0092;
            @chmod($file, $new_fileperms);

            if (!is_writable($file)) {
                $ret_val = true;
            }
        }
    }
    return $ret_val;
}

/** This function returns the name of the person.
 * It currently returns "first last".  It should not put the space if either name is not available.
 * It should not return errors if either name is not available.
 * If no names are present, it will return ""
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function return_name($row, $first_column, $last_column) {
    $first_name = "";
    $last_name = "";
    $full_name = "";

    if (isset($row[$first_column])) {
        $first_name = stripslashes($row[$first_column]);
    }

    if (isset($row[$last_column])) {
        $last_name = stripslashes($row[$last_column]);
    }

    $full_name = $first_name;

    // If we have a first name and we have a last name
    if ($full_name != "" && $last_name != "") {
        // append a space, then the last name
        $full_name .= " " . $last_name;
    }
    // If we have no first name, but we have a last name
    else if ($last_name != "") {
        // append the last name without the space.
        $full_name .= $last_name;
    }

    return $full_name;
}

function get_languages() {
    global $sugar_config;
    return $sugar_config['languages'];
}

function get_language_display($key) {
    global $sugar_config;
    return $sugar_config['languages'][$key];
}

function get_assigned_user_name($assigned_user_id, $is_group = ' AND is_group=0 ') {
    static $saved_user_list = null;
    $blankvalue = '';

    if (empty($saved_user_list)) {
        $saved_user_list = get_user_array(false, '', '', false, null, $is_group);
    }

    if (isset($saved_user_list[$assigned_user_id])) {
        return $saved_user_list[$assigned_user_id];
    }

    return $blankvalue;
}

/**
 * retrieves the user_name column value (login)
 * @param string id GUID of user
 * @return string
 */
function get_user_name($id) {
    global $db;

    if (empty($db))
        $db = & PearDatabase::getInstance();

    $q = "SELECT user_name FROM users WHERE id='{$id}'";
    $r = $db->query($q);
    $a = $db->fetchByAssoc($r);

    return (empty($a)) ? '' : $a['user_name'];
}

function get_user_hier_array($user_id = "") {
    if (!$user_id) {
        global $current_user;
        $user_id = $current_user->id;
    }

    $hier_array = Array();
    $hier_array[$user_id] = "Self";

    populate_user_hier_array($user_id, $hier_array);
    return $hier_array;
}

function get_user_in_array() {
    global $current_user, $log;
    $current_user_id = $current_user->id;
    //echo "Current user id ".$current_user_id;

    $user_in_array = get_register_value('user_in_array', "user_in_array" . $current_user_id);
    //echo "User in array ".implode(",",$user_in_array);

    if (!$user_in_array) {
        $temp_result = Array();

        //echo "Calling user id <br>";
        $user_array = get_user_hier_array($current_user_id);

        foreach ($user_array as $key => $value) {
            $temp_result[$value] = "'$key'";
        }

        $user_in_array = $temp_result;
        set_register_value('user_in_array', $current_user_id, $temp_result);
    }
    else
        $log->debug("Already present user in array");

    //echo "User in array ".implode(",",$user_in_array);
    return $user_in_array;
}

function getUserMyTeam($user_id = NULL) {
    global $current_user;
    if (!isset($user_id)) {
        $user_id = $current_user->id;
    }

    if (is_admin($current_user))
        return get_user_array(false);

    $db = & PearDatabase::getInstance();

    $query = "SELECT DISTINCT a.id, a.user_name from users a INNER JOIN user_reports b ON b.child_id = a.id AND
			b.parent_id ='$user_id' AND a.deleted=0 AND a.status='Active' AND b.deleted=0";

    $query .= " UNION ALL ";
    $query .= "SELECT DISTINCT a.id, a.user_name from users a where a.reports_to_id ='$user_id' AND a.deleted=0 AND a.status='Active'";

    $GLOBALS['log']->debug("In getUserMyTeam: $query");
    $result = $db->query($query, true, "Error filling in user array: ");
    $temp_result = Array();
    $temp_result[$current_user->id] = $current_user->user_name;

    // Get the id and the name.
    while ($row = $db->fetchByAssoc($result)) {
        $temp_result[$row['id']] = $row['user_name'];
    }
    return $temp_result;
}

function getOtherUserIfAny($user_id = NULL, $module) {

    global $current_user;
    if (!isset($user_id)) {
        $user_id = $current_user->id;
    }

    require_once("modules/Users/Access.php");
    $seed = new Access();

    return $seed->get_access_user_list($user_id, $module);
}

function getWhoHasAccessUserIfAny($user_id = NULL, $module) {

    global $current_user;
    if (!isset($user_id)) {
        $user_id = $current_user->id;
    }

    require_once("modules/Users/Access.php");
    $seed = new Access();

    return $seed->get_who_has_access_user_list($user_id, $module);
}

function getUserMyTeamLevelOne($user_id = NULL) {
    global $current_user;
    if (!isset($user_id)) {
        $user_id = $current_user->id;
    }

    if (is_admin($current_user) || is_supersenior($current_user))
        return get_user_array(false);

    $db = & PearDatabase::getInstance();

    $user_list = getUserMyTeam($user_id);

    $temp_result = Array();

    foreach ($user_list as $key => $value) {
        $query = "SELECT DISTINCT a.id, a.first_name, a.last_name,a.user_name from users a INNER JOIN user_reports b ON b.child_id = a.id AND
		b.parent_id ='$key' AND a.deleted=0 AND a.status='Active' AND b.deleted=0";

        $query .= " UNION ALL ";
        $query .= "SELECT DISTINCT a.id, a.first_name, a.last_name,a.user_name from users a where a.reports_to_id ='$key' AND a.deleted=0 AND a.status='Active'";

        $GLOBALS['log']->debug("In getUserMyTeamNew: $query");
        $result = $db->query($query, true, "Error filling in user array: ");

        // Get the id and the name.
        while ($row = $db->fetchByAssoc($result)) {
            $temp_result[$row['id']] = $row['first_name'] . " " . $row['last_name'];
        }
    }

    $user_list = sugarArrayMerge($temp_result, $user_list);
//	$user_list = $temp_result;
    return $user_list;
}

function populate_user_hier_array($user_id, & $hier_array) {
    if (!$user_id) {
        global $current_user;
        $user_id = $current_user->id;
    }

    $db = & PearDatabase::getInstance();

    $query = "SELECT DISTINCT a.id,a.user_name from users a INNER JOIN user_reports b ON b.parent_id = a.id AND
			b.child_id = '$user_id' AND a.deleted=0 AND b.deleted=0 INNER JOIN users c ON a.id = c.reports_to_id";
    $query .=" UNION ALL ";
    $query .= "SELECT DISTINCT a.id, a.user_name from users a INNER JOIN user_reports b ON b.child_id = a.id AND
			b.parent_id ='$user_id' AND a.deleted=0 AND b.deleted=0 INNER JOIN users c ON a.id = c.reports_to_id";

//	$GLOBALS['log']->debug("In populate_user_hier_array: $query");
    $result = $db->query($query, true, "Error filling in user array: ");
    $temp_result = Array();

    // Get the id and the name.
    while ($row = $db->fetchByAssoc($result)) {
        $temp_result[$row['id']] = $row['user_name'];
        $hier_array[$row['id']] = $row['user_name'];
    }

    //$temp_array = $temphier_array;
    /*
      foreach($temp_result as $key=>$value)
      {
      populate_user_hier_array($key,$hier_array);
      }
     */
}

//TODO Update to use global cache
function get_user_array($add_blank = true, $status = "Active", $assigned_user = "", $use_real_name = false, $user_name_begins = null, $is_group = ' AND is_group=0 ') {
    global $locale;
    global $sugar_config;

    if (empty($locale)) {
        require_once('include/Localization/Localization.php');
        $locale = new Localization();
    }
    global $current_user;

    $user_array = null; //get_register_value('user_array', $add_blank. $status . $assigned_user);

    if (!$user_array) {
        $db = & PearDatabase::getInstance();
        $temp_result = Array();
        // Including deleted users for now.

        $join_statement = "";

//		if(!is_admin($current_user))
//		$join_statement= " INNER JOIN user_reports ON (users.id='$assigned_user' OR (users.id = user_reports.child_id AND (user_reports.parent_id='$current_user->id' OR user_reports.child_id='$current_user->id') AND user_reports.deleted=0))";

        if (empty($status)) {
            $query = "SELECT users.id, first_name, last_name, user_name from users " . $join_statement . " WHERE 1=1" . $is_group;
        } else {
            $query = "SELECT users.id, first_name, last_name, user_name from users " . $join_statement . " WHERE status='$status'" . $is_group;
        }

        if (!empty($user_name_begins)) {
            $query .= " AND user_name LIKE '$user_name_begins%' ";
        }
        if (!empty($assigned_user)) {
            $query .= " OR users.id='$assigned_user'";
        }

        $query = $query . ' AND users.deleted=0 ORDER BY user_name ASC';

        $GLOBALS['log']->debug("get_user_array query: $query");
        $result = $db->query($query, true, "Error filling in user array: ");

        if ($add_blank == true) {
            // Add in a blank row
            $temp_result[''] = '';
        }

        // Get the id and the name.
        while ($row = $db->fetchByAssoc($result)) {
            if ($use_real_name == true || showFullName()) {
                if (isset($row['last_name'])) { // cn: we will ALWAYS have both first_name and last_name (empty value if blank in db)
                    $temp_result[$row['id']] = $locale->getLocaleFormattedName($row['first_name'], $row['last_name']);
                } else {
                    $temp_result[$row['id']] = $row['user_name'];
                }
            } else {
                $temp_result[$row['id']] = $row['user_name'];
            }
        }

        if (count($temp_result) == 0)//added to include self
            $temp_result[$current_user->id] = $current_user->get_summary_text();
        $user_array = $temp_result;
        set_register_value('user_array', $add_blank . $status . $assigned_user, $temp_result);
    }

    return $user_array;
}

function get_user_array_forassign($add_blank = true, $status = "Active", $assigned_user = "", $use_real_name = false, $user_name_begins = null, $is_group = ' AND is_group=0 ') {
    global $locale;
    global $sugar_config;

    if (empty($locale)) {
        require_once('include/Localization/Localization.php');
        $locale = new Localization();
    }
    global $current_user;

    $user_array = null; //sugar_cache_retrieve('user_array_forassign'.$add_blank. $status . $assigned_user.$current_user->id);

    if (!isset($user_array)) {

        $temp_array = getUserMyTeamLevelOne($current_user->id);
        if ($add_blank == true) {
            // Add in a blank row
            $temp_array[''] = '';
        }

        sugar_cache_put('user_array_forassign' . $add_blank . $status . $assigned_user . $current_user->id, $temp_array);
        $user_array = $temp_array;
    }

    return $user_array;
}

//TODO Update to use global cache
function get_admin_user_array($add_blank = true, $status = "Active", $assigned_user = "", $use_real_name = false, $user_name_begins = null, $is_group = ' AND is_group=0 ') {
    global $locale;
    global $sugar_config;

    if (empty($locale)) {
        require_once('include/Localization/Localization.php');
        $locale = new Localization();
    }

    $user_array = get_register_value('admin_user_array', $add_blank . $status . $assigned_user);

    if (!$user_array) {
        $db = & PearDatabase::getInstance();
        $temp_result = Array();
        // Including deleted users for now.
        global $current_user;
        $join_statement = "";

        if (empty($status)) {
            $query = "SELECT users.id, first_name, last_name, user_name from users " . $join_statement . " WHERE 1=1" . $is_group;
        } else {
            $query = "SELECT users.id, first_name, last_name, user_name from users " . $join_statement . " WHERE status='$status'" . $is_group;
        }

        if (!empty($user_name_begins)) {
            $query .= " AND user_name LIKE '$user_name_begins%' ";
        }
        if (!empty($assigned_user)) {
            $query .= " OR users.id='$assigned_user'";
        }
        $query = $query . ' AND is_admin=1 ORDER BY user_name ASC';

        $GLOBALS['log']->debug("get_admin_user_array query: $query");
        $result = $db->query($query, true, "Error filling in user array: ");

        if ($add_blank == true) {
            // Add in a blank row
            $temp_result[''] = '';
        }

        // Get the id and the name.
        while ($row = $db->fetchByAssoc($result)) {
            if ($use_real_name == true || showFullName()) {
                if (isset($row['last_name'])) { // cn: we will ALWAYS have both first_name and last_name (empty value if blank in db)
                    $temp_result[$row['id']] = $locale->getLocaleFormattedName($row['first_name'], $row['last_name']);
                } else {
                    $temp_result[$row['id']] = $row['user_name'];
                }
            } else {
                $temp_result[$row['id']] = $row['user_name'];
            }
        }

        $user_array = $temp_result;
        set_register_value('admin_user_array', $add_blank . $status . $assigned_user, $temp_result);
    }

    return $user_array;
}

function get_table_array($key, $value, $table_name) {
    global $locale;
    global $sugar_config;

    if (empty($locale)) {
        require_once('include/Localization/Localization.php');
        $locale = new Localization();
    }

    $cache_key = 'get_table_array' . $table_name;

    // Check for cached value
    $cache_entry = sugar_cache_retrieve($cache_key);

    if (!$cache_entry) {
        $db = & PearDatabase::getInstance();
        $temp_result = Array();
        // Including deleted users for now.
        $query = "SELECT $key,$value from $table_name WHERE deleted=0";

        $query = $query . " ORDER BY $value ASC";

        $GLOBALS['log']->debug("get_table_array query: $query");
        $result = $db->query($query, true, "Error filling in user array: ");

        // Get the id and the name.
        while ($row = $db->fetchByAssoc($result)) {
            $temp_result[$row[$key]] = $row[$value];
        }

        $cache_entry = $temp_result;
        sugar_cache_put($cache_key, $temp_result);
    }
    else
        $GLOBALS['log']->debug("In get_table_array cache entry found $table_name");

    return $cache_entry;
}

/**
 * uses a different query to return a list of users than get_user_array()
 * @param args string where clause entry
 * @return array Array of Users' details that match passed criteria
 */
function getUserArrayFromFullName($args) {
    global $locale;
    $db = & PearDatabase::getInstance();

    $argArray = array();
    if (strpos($args, " ")) {
        $argArray = explode(" ", $args);
    } else {
        $argArray[] = $args;
    }

    $inClause = '';
    foreach ($argArray as $arg) {
        if (!empty($inClause)) {
            $inClause .= ' OR ';
        }
        if (empty($arg))
            continue;

        $inClause .= "first_name LIKE '{$arg}%' OR last_name LIKE '{$arg}%'";
    }

    $query = "SELECT id, first_name, last_name, user_name FROM users WHERE status='Active' AND deleted=0 AND ";
    $query .= $inClause;
    $query .= " ORDER BY last_name ASC";

    $r = $db->query($query);
    $ret = array();
    while ($a = $db->fetchByAssoc($r)) {
        $ret[$a['id']] = $locale->getLocaleFormattedName($a['first_name'], $a['last_name']);
    }

    return $ret;
}

/**
 *
 * based on user pref then system pref
 */
function showFullName() {
    global $sugar_config;
    global $current_user;

    $sysPref = (isset($sugar_config['use_real_names']) && $sugar_config['use_real_names'] == true) ? true : false;
    $userPref = $current_user->getPreference('use_real_names');

    if ($userPref != null) {
        $bool = ($userPref == 'on') ? true : false;
        return $bool;
    } else {
        return $sysPref;
    }
}

function clean($string, $maxLength) {
    $string = substr($string, 0, $maxLength);
    return escapeshellcmd($string);
}

/**
 * Copy the specified request variable to the member variable of the specified object.
 * Do no copy if the member variable is already set.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function safe_map($request_var, & $focus, $always_copy = false) {
    safe_map_named($request_var, $focus, $request_var, $always_copy);
}

/**
 * Copy the specified request variable to the member variable of the specified object.
 * Do no copy if the member variable is already set.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function safe_map_named($request_var, & $focus, $member_var, $always_copy) {
    if (isset($_REQUEST[$request_var]) && ($always_copy || is_null($focus->$member_var))) {
        $GLOBALS['log']->debug("safe map named called assigning '{$_REQUEST[$request_var]}' to $member_var");
        $focus->$member_var = $_REQUEST[$request_var];
    }
}

/** This function retrieves an application language file and returns the array of strings included in the $app_list_strings var.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 * If you are using the current language, do not call this function unless you are loading it for the first time */
function return_app_list_strings_language($language) {
    global $app_list_strings;
    global $sugar_config;

    $cache_key = 'app_list_strings.' . $language;

    // Check for cached value
    $cache_entry = sugar_cache_retrieve($cache_key);
    if (!empty($cache_entry)) {
        return $cache_entry;
    }

    $default_language = $sugar_config['default_language'];
    $temp_app_list_strings = $app_list_strings;
    $language_used = $language;

    include("include/language/en_us.lang.php");
    $en_app_list_strings = array();
    if ($language_used != $default_language)
        $en_app_list_strings = $app_list_strings;

    include("include/language/$language.lang.php");

    if (file_exists("include/language/$language.lang.override.php")) {
        include("include/language/$language.lang.override.php");
    }

    if (file_exists("include/language/$language.lang.php.override")) {
        include("include/language/$language.lang.php.override");
    }

    if (file_exists("custom/include/language/$language.lang.php")) {
        include("custom/include/language/$language.lang.php");
        $GLOBALS['log']->info("Found custom language file: $language.lang.php");
    }
    if (file_exists("custom/application/Ext/Language/$language.lang.ext.php")) {
        include("custom/application/Ext/Language/$language.lang.ext.php");
        $GLOBALS['log']->info("Found extended language file: $language.lang.ext.php");
    }

    if (!isset($app_list_strings)) {
        $GLOBALS['log']->warn("Unable to find the application language file for language: " . $language);

        require("include/language/$default_language.lang.php");

        if (file_exists("include/language/$default_language.lang.override.php")) {
            include("include/language/$default_language.lang.override.php");
        }

        if (file_exists("include/language/$default_language.lang.php.override")) {
            include("include/language/$default_language.lang.php.override");
        }
        if (file_exists("custom/include/language/$default_language.lang.php")) {
            include("custom/include/language/$default_language.lang.php");
            $GLOBALS['log']->info("Found custom language file: $default_language.lang.php");
        }
        if (file_exists("custom/application/Ext/Language/$default_language.lang.ext.php")) {
            include("custom/application/Ext/Language/$default_language.lang.ext.php");
            $GLOBALS['log']->info("Found extended language file: $default_language.lang.ext.php");
        }
        $language_used = $default_language;
    }

    if (!isset($app_list_strings)) {
        $GLOBALS['log']->fatal("Unable to load the application language file for the selected language($language) or the default language($default_language)");
        return null;
    }

    // cn: bug 6048 - merge en_us with requested language
    $app_list_strings = sugarArrayMerge($en_app_list_strings, $app_list_strings);

    $return_value = $app_list_strings;
    $app_list_strings = $temp_app_list_strings;

    sugar_cache_put($cache_key, $return_value);

    return $return_value;
}

/** This function retrieves an application language file and returns the array of strings included.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 * If you are using the current language, do not call this function unless you are loading it for the first time */
function return_application_language($language) {
    global $app_strings, $sugar_config;

    $cache_key = 'app_strings.' . $language;

    // Check for cached value
    $cache_entry = sugar_cache_retrieve($cache_key);
    if (!empty($cache_entry)) {
        return $cache_entry;
    }

    $temp_app_strings = $app_strings;
    $language_used = $language;
    $default_language = $sugar_config['default_language'];

    // cn: bug 6048 - merge en_us with requested language
    include("include/language/en_us.lang.php");
    $en_app_strings = array();
    if ($language_used != $default_language)
        $en_app_strings = $app_strings;

    if (!empty($language)) {
        include("include/language/$language.lang.php");
    }

    if (file_exists("include/language/$language.lang.override.php")) {
        include("include/language/$language.lang.override.php");
    }
    if (file_exists("include/language/$language.lang.php.override")) {
        include("include/language/$language.lang.php.override");
    }
    if (file_exists("custom/application/Ext/Language/$language.lang.ext.php")) {
        include("custom/application/Ext/Language/$language.lang.ext.php");
        $GLOBALS['log']->info("Found extended language file: $language.lang.ext.php");
    }
    if (file_exists("custom/include/language/$language.lang.php")) {
        include("custom/include/language/$language.lang.php");
        $GLOBALS['log']->info("Found custom language file: $language.lang.php");
    }


    if (!isset($app_strings)) {
        $GLOBALS['log']->warn("Unable to find the application language file for language: " . $language);
        require("include/language/$default_language.lang.php");
        if (file_exists("include/language/$default_language.lang.override.php")) {
            include("include/language/$default_language.lang.override.php");
        }
        if (file_exists("include/language/$default_language.lang.php.override")) {
            include("include/language/$default_language.lang.php.override");
        }

        if (file_exists("custom/application/Ext/Language/$default_language.lang.ext.php")) {
            include("custom/application/Ext/Language/$default_language.lang.ext.php");
            $GLOBALS['log']->info("Found extended language file: $default_language.lang.ext.php");
        }
        $language_used = $default_language;
    }

    if (!isset($app_strings)) {
        $GLOBALS['log']->fatal("Unable to load the application language file for the selected language($language) or the default language($default_language)");
        return null;
    }

    // cn: bug 6048 - merge en_us with requested language
    $app_strings = sugarArrayMerge($en_app_strings, $app_strings);

    // If we are in debug mode for translating, turn on the prefix now!
    if ($sugar_config['translation_string_prefix']) {
        foreach ($app_strings as $entry_key => $entry_value) {
            $app_strings[$entry_key] = $language_used . ' ' . $entry_value;
        }
    }
    if (isset($_SESSION['show_deleted'])) {
        $app_strings['LBL_DELETE_BUTTON'] = $app_strings['LBL_UNDELETE_BUTTON'];
        $app_strings['LBL_DELETE_BUTTON_LABEL'] = $app_strings['LBL_UNDELETE_BUTTON_LABEL'];
        $app_strings['LBL_DELETE_BUTTON_TITLE'] = $app_strings['LBL_UNDELETE_BUTTON_TITLE'];
        $app_strings['LBL_DELETE'] = $app_strings['LBL_UNDELETE'];
    }

    $return_value = $app_strings;
    $app_strings = $temp_app_strings;

    sugar_cache_put($cache_key, $return_value);
    return $return_value;
}

/** This function retrieves a module's language file and returns the array of strings included.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 * If you are in the current module, do not call this function unless you are loading it for the first time */
function return_module_language($language, $module) {
    global $mod_strings;
    global $sugar_config;
    global $currentModule;

    // Jenny - Bug 8119: Need to check if $module is not empty
    if (empty($module)) {
        $stack = debug_backtrace();
        $GLOBALS['log']->warn("Variable module is not in return_module_language " . var_dump($stack, true));
        return array();
    }

    $cache_key = "module_language." . $language . $module;

    // Check for cached value
    $cache_entry = sugar_cache_retrieve($cache_key);
    if (!empty($cache_entry)) {
        return $cache_entry;
    }

    // Store the current mod strings for later
    $temp_mod_strings = $mod_strings;
    $language_used = $language;
    $default_language = $sugar_config['default_language'];

    if (empty($language)) {
        $language = $default_language;
    }
    if (file_exists("modules/$module/language/$language.lang.php")) {
        include("modules/$module/language/$language.lang.php");
    }

    // cn: bug 6351 - include en_us if file langpack not available
    include("modules/$module/language/en_us.lang.php");
    $en_mod_strings = array();
    if ($language_used != $default_language)
        $en_mod_strings = $mod_strings;

    if (file_exists("modules/$module/language/$language.lang.php")) {
        include("modules/$module/language/$language.lang.php");
    }

    if (file_exists("modules/$module/language/$language.lang.override.php")) {
        include("modules/$module/language/$language.lang.override.php");
    }

    if (file_exists("modules/$module/language/$language.lang.php.override")) {
        echo 'Please Change:<br>' .
        "modules/$module/language/$language.lang.php.override" .
        '<br>to<br>' . 'Please Change:<br>' .
        "modules/$module/language/$language.lang.override.php";

        include("modules/$module/language/$language.lang.php.override");
    }

    if (file_exists("custom/modules/$module/Ext/Language/$language.lang.ext.php")) {
        include("custom/modules/$module/Ext/Language/$language.lang.ext.php");
        $GLOBALS['log']->info("Found extended language file: $language.lang.ext.php");
    }

    // include the customized field information
    if (file_exists("custom/modules/$module/language/$language.lang.php")) {
        include("custom/modules/$module/language/$language.lang.php");
    }

    if (!isset($mod_strings)) {
        $GLOBALS['log']->warn("Unable to find the module language file for language: " . $language . " and module: " . $module);
        if (file_exists("modules/$module/language/$default_language.lang.php")) {
            require("modules/$module/language/$default_language.lang.php");
        }
        if (file_exists("modules/$module/language/$default_language.lang.php.override")) {
            include("modules/$module/language/$default_language.lang.php.override");
        }
        if (file_exists("custom/modules/$module/Ext/Language/$default_language.lang.ext.php")) {
            include("custom/modules/$module/Ext/Language/$default_language.lang.ext.php");
            $GLOBALS['log']->info("Found extended language file: $default_language.lang.ext.php");
        }
        $language_used = $default_language;
    }

    // cn: bug 6048 - merge en_us with requested language
    $mod_strings = sugarArrayMerge($en_mod_strings, $mod_strings);

    // if we still don't have a language pack, then log an error
    if (!isset($mod_strings)) {
        $GLOBALS['log']->fatal("Unable to load the module($module) language file for the selected language($language) or the default language($default_language)");
        return array();
    }

    // If we are in debug mode for translating, turn on the prefix now!
    if ($sugar_config['translation_string_prefix']) {
        foreach ($mod_strings as $entry_key => $entry_value) {
            $mod_strings[$entry_key] = $language_used . ' ' . $entry_value;
        }
    }

    $return_value = $mod_strings;
    $mod_strings = $temp_mod_strings;

    sugar_cache_put($cache_key, $return_value);

    return $return_value;
}

/** This function retrieves an application language file and returns the array of strings included in the $mod_list_strings var.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 * If you are using the current language, do not call this function unless you are loading it for the first time */
function return_mod_list_strings_language($language, $module) {
    global $mod_list_strings;
    global $sugar_config;
    global $currentModule;

    $cache_key = "mod_list_str_lang." . $language . $module;

    // Check for cached value
    $cache_entry = sugar_cache_retrieve($cache_key);
    if (!empty($cache_entry)) {
        return $cache_entry;
    }

    $language_used = $language;
    $temp_mod_list_strings = $mod_list_strings;
    $default_language = $sugar_config['default_language'];

    if ($currentModule == $module && isset($mod_list_strings) && $mod_list_strings != null) {
        return $mod_list_strings;
    }

    // cn: bug 6351 - include en_us if file langpack not available
    // cn: bug 6048 - merge en_us with requested language
    include("modules/$module/language/en_us.lang.php");
    $en_mod_list_strings = array();
    if ($language_used != $default_language)
        $en_mod_list_strings = $mod_list_strings;

    if (file_exists("modules/$module/language/$language.lang.php")) {
        include("modules/$module/language/$language.lang.php");
    }

    if (file_exists("modules/$module/language/$language.lang.override.php")) {
        include("modules/$module/language/$language.lang.override.php");
    }

    if (file_exists("modules/$module/language/$language.lang.php.override")) {
        echo 'Please Change:<br>' . "modules/$module/language/$language.lang.php.override" . '<br>to<br>' . 'Please Change:<br>' . "modules/$module/language/$language.lang.override.php";
        include("modules/$module/language/$language.lang.php.override");
    }

    // cn: bug 6048 - merge en_us with requested language
    $mod_list_strings = sugarArrayMerge($en_mod_list_strings, $mod_list_strings);

    // if we still don't have a language pack, then log an error
    if (!isset($mod_list_strings)) {
        $GLOBALS['log']->fatal("Unable to load the application list language file for the selected language($language) or the default language($default_language) for module({$module})");
        return null;
    }

    $return_value = $mod_list_strings;
    $mod_list_strings = $temp_mod_list_strings;

    sugar_cache_put($cache_key, $return_value);
    return $return_value;
}

/** This function retrieves a theme's language file and returns the array of strings included.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function return_theme_language($language, $theme) {
    global $mod_strings, $sugar_config, $currentModule;

    $language_used = $language;
    $default_language = $sugar_config['default_language'];

    include("themes/$theme/language/$current_language.lang.php");
    if (file_exists("themes/$theme/language/$current_language.lang.override.php")) {
        include("themes/$theme/language/$current_language.lang.override.php");
    }
    if (file_exists("themes/$theme/language/$current_language.lang.php.override")) {
        echo 'Please Change:<br>' . "themes/$theme/language/$current_language.lang.php.override" . '<br>to<br>' . 'Please Change:<br>' . "themes/$theme/language/$current_language.lang.override.php";
        include("themes/$theme/language/$current_language.lang.php.override");
    }
    if (!isset($theme_strings)) {
        $GLOBALS['log']->warn("Unable to find the theme file for language: " . $language . " and theme: " . $theme);
        require("themes/$theme/language/$default_language.lang.php");
        $language_used = $default_language;
    }

    if (!isset($theme_strings)) {
        $GLOBALS['log']->fatal("Unable to load the theme($theme) language file for the selected language($language) or the default language($default_language)");
        return null;
    }

    // If we are in debug mode for translating, turn on the prefix now!
    if ($sugar_config['translation_string_prefix']) {
        foreach ($theme_strings as $entry_key => $entry_value) {
            $theme_strings[$entry_key] = $language_used . ' ' . $entry_value;
        }
    }

    return $theme_strings;
}

/** If the session variable is defined and is not equal to "" then return it.  Otherwise, return the default value.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function return_session_value_or_default($varname, $default) {
    if (isset($_SESSION[$varname]) && $_SESSION[$varname] != "") {
        return $_SESSION[$varname];
    }

    return $default;
}

/**
 * Creates an array of where restrictions.  These are used to construct a where SQL statement on the query
 * It looks for the variable in the $_REQUEST array.  If it is set and is not "" it will create a where clause out of it.
 * @param &$where_clauses - The array to append the clause to
 * @param $variable_name - The name of the variable to look for an add to the where clause if found
 * @param $SQL_name - [Optional] If specified, this is the SQL column name that is used.  If not specified, the $variable_name is used as the SQL_name.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function append_where_clause(&$where_clauses, $variable_name, $SQL_name = null) {
    if ($SQL_name == null) {
        $SQL_name = $variable_name;
    }

    if (isset($_REQUEST[$variable_name]) && $_REQUEST[$variable_name] != "") {
        array_push($where_clauses, "$SQL_name like '" . PearDatabase::quote($_REQUEST[$variable_name]) . "%'");
    }
}

/**
 * Generate the appropriate SQL based on the where clauses.
 * @param $where_clauses - An Array of individual where clauses stored as strings
 * @returns string where_clause - The final SQL where clause to be executed.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function generate_where_statement($where_clauses) {
    $where = "";
    foreach ($where_clauses as $clause) {
        if ($where != "")
            $where .= " and ";
        $where .= $clause;
    }

    $GLOBALS['log']->info("Here is the where clause for the list view: $where");
    return $where;
}

/**
 * A temporary method of generating GUIDs of the correct format for our DB.
 * @return String contianing a GUID in the format: aaaaaaaa-bbbb-cccc-dddd-eeeeeeeeeeee
 *
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function create_guid() {
    $microTime = microtime();
    list($a_dec, $a_sec) = explode(" ", $microTime);

    $dec_hex = sprintf("%x", $a_dec * 1000000);
    $sec_hex = sprintf("%x", $a_sec);

    ensure_length($dec_hex, 5);
    ensure_length($sec_hex, 6);

    $guid = "";
    $guid .= $dec_hex;
    $guid .= create_guid_section(3);
    $guid .= '-';
    $guid .= create_guid_section(4);
    $guid .= '-';
    $guid .= create_guid_section(4);
    $guid .= '-';
    $guid .= create_guid_section(4);
    $guid .= '-';
    $guid .= $sec_hex;
    $guid .= create_guid_section(6);

    return $guid;
}

function create_guid_section($characters) {
    $return = "";
    for ($i = 0; $i < $characters; $i++) {
        $return .= sprintf("%x", mt_rand(0, 15));
    }
    return $return;
}

function ensure_length(&$string, $length) {
    $strlen = strlen($string);
    if ($strlen < $length) {
        $string = str_pad($string, $length, "0");
    } else if ($strlen > $length) {
        $string = substr($string, 0, $length);
    }
}

function microtime_diff($a, $b) {
    list($a_dec, $a_sec) = explode(" ", $a);
    list($b_dec, $b_sec) = explode(" ", $b);
    return $b_sec - $a_sec + $b_dec - $a_dec;
}

/**
 * Check if user id belongs to a system admin.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function is_admin($user) {
    if (!empty($user) && ($user->is_admin == '1' || $user->is_admin === 'on')) {
        return true;
    }

    return false;
}

function is_supersenior($user) {

    $rel_name = "aclroles";
    $acl_roles = $user->get_linked_beans($rel_name, 'aclrole');

    if (isset($acl_roles)) {
        foreach ($acl_roles as $role) {


            //$GLOBALS['log']->debug("Super Senior list :".$role->name);

            if ($role->name == 'superseniors')
                return true;
        }
    }
    return false;
}

/**
 * Check if user id belongs to a system super user.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function is_superuser($user) {
    if (!empty($user) && ($user->is_superuser == '1' || $user->is_superuser === 'on')) {
        return true;
    }

    return false;
}

/**
 * Return the display name for a theme if it exists.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function get_theme_display($theme) {
    global $theme_name, $theme_description;
    $temp_theme_name = $theme_name;
    $temp_theme_description = $theme_description;

    if (is_file("./themes/$theme/config.php")) {
        include("./themes/$theme/config.php");
        $return_theme_value = $theme_name;
    } else {
        $return_theme_value = $theme;
    }
    $theme_name = $temp_theme_name;
    $theme_description = $temp_theme_description;

    return $return_theme_value;
}

/**
 * Return an array of directory names.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function get_themes() {
    if ($dir = opendir("./themes")) {
        while (($file = readdir($dir)) !== false) {
            if ($file != ".." && $file != "." && $file != "CVS" && $file != "Attic") {
                if (is_dir("./themes/" . $file)) {
                    if (!($file[0] == '.')) {
                        // set the initial theme name to the filename
                        $name = $file;

                        // if there is a configuration class, load that.
                        if (is_file("./themes/$file/config.php")) {
                            unset($theme_name);
                            unset($version_compatibility);
                            require("./themes/$file/config.php");
                            $name = $theme_name;
                            if (is_file("./themes/$file/header.php") && $version_compatibility >= 2.0) {
                                $filelist[$file] = $name;
                            }
                        }
                    }
                }
            }
        }
        closedir($dir);
    }

    ksort($filelist);
    return $filelist;
}

/**
 * THIS FUNCTION IS DEPRECATED AND SHOULD NOT BE USED; USE get_select_options_with_id()
 * Create HTML to display select options in a dropdown list.  To be used inside
 * of a select statement in a form.
 * param $option_list - the array of strings to that contains the option list
 * param $selected - the string which contains the default value
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function get_select_options($option_list, $selected) {
    return get_select_options_with_id($option_list, $selected);
}

/**
 * Create HTML to display select options in a dropdown list.  To be used inside
 * of a select statement in a form.   This method expects the option list to have keys and values.  The keys are the ids.  The values are the display strings.
 * param $option_list - the array of strings to that contains the option list
 * param $selected - the string which contains the default value
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function get_select_options_with_id($option_list, $selected_key) {
    return get_select_options_with_id_separate_key($option_list, $option_list, $selected_key);
}

/**
 * Create HTML to display select options in a dropdown list.  To be used inside
 * of a select statement in a form.   This method expects the option list to have keys and values.  The keys are the ids.  The values are the display strings.
 * param $label_list - the array of strings to that contains the option list
 * param $key_list - the array of strings to that contains the values list
 * param $selected - the string which contains the default value
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function get_select_options_with_id_separate_key($label_list, $key_list, $selected_key) {
    global $app_strings;
    $select_options = "";

    //for setting null selection values to human readable --None--
    $pattern = "/'0?'></";
    $replacement = "''>" . $app_strings['LBL_NONE'] . "<";

    if (empty($key_list))
        $key_list = array();
    //create the type dropdown domain and set the selected value if $opp value already exists
    foreach ($key_list as $option_key => $option_value) {

        $selected_string = '';
        // the system is evaluating $selected_key == 0 || '' to true.  Be very careful when changing this.  Test all cases.
        // The bug was only happening with one of the users in the drop down.  It was being replaced by none.
        if (($option_key != '' && $selected_key == $option_key) || ($selected_key == '' && $option_key == '') || (is_array($selected_key) && in_array($option_key, $selected_key))) {
            $selected_string = 'selected ';
        }

        $html_value = $option_key;

        $select_options .= "\n<OPTION " . $selected_string . "value='$html_value'>$label_list[$option_key]</OPTION>";
    }
    $select_options = preg_replace($pattern, $replacement, $select_options);
    return $select_options;
}

/**
 * Call this method instead of die().
 * Then we call the die method with the error message that is passed in.
 */
function sugar_die($error_message) {
    global $focus;
    sugar_cleanup();
    die($error_message);
}

/**
 * Create javascript to clear values of all elements in a form.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function get_clear_form_js() {
    $the_script = <<<EOQ
<script type="text/javascript" language="JavaScript">
function clear_form(form) {
	var newLoc = 'index.php?action=' + form.action.value + '&module=' + form.module.value + '&query=true&clear_query=true';
	if(typeof(form.advanced) != 'undefined'){
		newLoc += '&advanced=' + form.advanced.value;
	}
	document.location.href= newLoc;
}
</script>
EOQ;

    return $the_script;
}

/**
 * Create javascript to set the cursor focus to specific field in a form
 * when the screen is rendered.  The field name is currently hardcoded into the
 * the function.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function get_set_focus_js() {
//TODO Clint 5/20 - Make this function more generic so that it can take in the target form and field names as variables
    $the_script = <<<EOQ
<script type="text/javascript" language="JavaScript">
<!-- Begin
function set_focus() {
	if (document.forms.length > 0) {
		for (i = 0; i < document.forms.length; i++) {
			for (j = 0; j < document.forms[i].elements.length; j++) {
				var field = document.forms[i].elements[j];
				if ((field.type == "text" || field.type == "textarea" || field.type == "password") &&
						!field.disabled && (field.name == "first_name" || field.name == "name" || field.name == "user_name" || field.name=="document_name")) {
					field.focus();
                    if (field.type == "text") {
                        field.select();
                    }
					break;
	    		}
			}
      	}
   	}
}
//  End -->
</script>
EOQ;

    return $the_script;
}

/**
 * Very cool algorithm for sorting multi-dimensional arrays.  Found at http://us2.php.net/manual/en/function.array-multisort.php
 * Syntax: $new_array = array_csort($array [, 'col1' [, SORT_FLAG [, SORT_FLAG]]]...);
 * Explanation: $array is the array you want to sort, 'col1' is the name of the column
 * you want to sort, SORT_FLAGS are : SORT_ASC, SORT_DESC, SORT_REGULAR, SORT_NUMERIC, SORT_STRING
 * you can repeat the 'col',FLAG,FLAG, as often you want, the highest prioritiy is given to
 * the first - so the array is sorted by the last given column first, then the one before ...
 * Example: $array = array_csort($array,'town','age',SORT_DESC,'name');
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function array_csort() {
    $args = func_get_args();
    $marray = array_shift($args);
    $i = 0;

    $msortline = "return(array_multisort(";
    foreach ($args as $arg) {
        $i++;
        if (is_string($arg)) {
            foreach ($marray as $row) {
                $sortarr[$i][] = $row[$arg];
            }
        } else {
            $sortarr[$i] = $arg;
        }
        $msortline .= "\$sortarr[" . $i . "],";
    }
    $msortline .= "\$marray));";

    eval($msortline);
    return $marray;
}

/**
 * Converts localized date format string to jscalendar format
 * Example: $array = array_csort($array,'town','age',SORT_DESC,'name');
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function parse_calendardate($local_format) {
    preg_match("/\(?([^-]{1})[^-]*-([^-]{1})[^-]*-([^-]{1})[^-]*\)/", $local_format, $matches);
    $calendar_format = "%" . $matches[1] . "-%" . $matches[2] . "-%" . $matches[3];
    return str_replace(array("y", "�", "a", "j"), array("Y", "Y", "Y", "d"), $calendar_format);
}

function translate($string, $mod = '', $selectedValue = '') {
//$test_start = microtime();
//static $mod_strings_results = array();
    if (!empty($mod)) {
        global $current_language;
        $mod_strings = return_module_language($current_language, $mod);
    } else {
        global $mod_strings;
    }

    $returnValue = '';
    global $app_strings, $app_list_strings;

    if (isset($mod_strings[$string]))
        $returnValue = $mod_strings[$string];
    else if (isset($app_strings[$string]))
        $returnValue = $app_strings[$string];
    else if (isset($app_list_strings[$string]))
        $returnValue = $app_list_strings[$string];


//$test_end = microtime();
//
//    $mod_strings_results[$mod] = microtime_diff($test_start,$test_end);
//
//    echo("translate results:");
//    $total_time = 0;
//    $total_strings = 0;
//    foreach($mod_strings_results as $key=>$value)
//    {
//        echo("Module $key \t\t time $value \t\t<br>");
//        $total_time += $value;
//    }
//
//    echo("Total time: $total_time<br>");



    if (empty($returnValue)) {
        return $string;
    }

    if (is_array($returnValue) && !empty($selectedValue) && isset($returnValue[$selectedValue])) {
        return $returnValue[$selectedValue];
    }

    return $returnValue;
}

function add_http($url) {
    if (!eregi("://", $url)) {
        $scheme = "http";
        if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
            $scheme = 'https';
        }

        return "{$scheme}://{$url}";
    }

    return $url;
}

// Designed to take a string passed in the URL as a parameter and clean all "bad" data from it
// The second argument is a string, "filter," which corresponds to a regular expression
function clean_string($str, $filter = "STANDARD") {
    global $sugar_config;

    $filters = Array(
        "STANDARD" => "#[^A-Z0-9\-_\.\@]#i",
        "STANDARDSPACE" => "#[^A-Z0-9\-_\.\@\ ]#i",
        "FILE" => "#[^A-Z0-9\-_\.]#i",
        "NUMBER" => "#[^0-9\-]#i",
        "SQL_COLUMN_LIST" => "#[^A-Z0-9,_\.]#i",
        "PATH_NO_URL" => "#://#i",
        "SAFED_GET" => "#[^A-Z0-9\@\=\&\?\.\/\-_~]#i", /* range of allowed characters in a GET string */
        "UNIFIED_SEARCH" => "/[\x80-\xFF]/", /* cn: bug 3356 - MBCS search strings */
        "AUTO_INCREMENT" => "#[^0-9\-,\ ]#i",
        "ALPHANUM" => "#[^A-Z0-9]#i",
    );

    if (preg_match($filters[$filter], $str)) {
        if (isset($GLOBALS['log']) && is_object($GLOBALS['log'])) {
            $GLOBALS['log']->fatal("SECURITY: bad data passed in; string: {$str}");
        }
        die("Bad data passed in; <a href=\"{$sugar_config['site_url']}\">Return to Home</a>");
    } else {
        return $str;
    }
}

function clean_special_arguments() {
    if (isset($_SERVER['PHP_SELF'])) {
        if (!empty($_SERVER['PHP_SELF']))
            clean_string($_SERVER['PHP_SELF'], 'SAFED_GET');
    }
    if (!empty($_REQUEST) && !empty($_REQUEST['login_theme']))
        clean_string($_REQUEST['login_theme'], "STANDARD");
    if (!empty($_REQUEST) && !empty($_REQUEST['ck_login_theme_20']))
        clean_string($_REQUEST['ck_login_theme_20'], "STANDARD");
    if (!empty($_SESSION) && !empty($_SESSION['authenticated_user_theme']))
        clean_string($_SESSION['authenticated_user_theme'], "STANDARD");
    if (!empty($_REQUEST) && !empty($_REQUEST['module_name']))
        clean_string($_REQUEST['module_name'], "STANDARD");
    if (!empty($_REQUEST) && !empty($_REQUEST['module']))
        clean_string($_REQUEST['module'], "STANDARD");
    if (!empty($_POST) && !empty($_POST['parent_type']))
        clean_string($_POST['parent_type'], "STANDARD");
    if (!empty($_REQUEST) && !empty($_REQUEST['mod_lang']))
        clean_string($_REQUEST['mod_lang'], "STANDARD");
    if (!empty($_SESSION) && !empty($_SESSION['authenticated_user_language']))
        clean_string($_SESSION['authenticated_user_language'], "STANDARD");
    if (!empty($_SESSION) && !empty($_SESSION['dyn_layout_file']))
        clean_string($_SESSION['dyn_layout_file'], "PATH_NO_URL");
    if (!empty($_GET) && !empty($_GET['from']))
        clean_string($_GET['from']);
    if (!empty($_GET) && !empty($_GET['gmto']))
        clean_string($_GET['gmto'], "NUMBER");
    if (!empty($_GET) && !empty($_GET['case_number']))
        clean_string($_GET['case_number'], "AUTO_INCREMENT");
    if (!empty($_GET) && !empty($_GET['bug_number']))
        clean_string($_GET['bug_number'], "AUTO_INCREMENT");
    if (!empty($_GET) && !empty($_GET['quote_num']))
        clean_string($_GET['quote_num'], "AUTO_INCREMENT");
    clean_superglobals('stamp', 'ALPHANUM'); // for vcr controls
    clean_superglobals('offset', 'ALPHANUM');
    clean_superglobals('return_action');
    clean_superglobals('return_module');
    return TRUE;
}

/**
 * cleans the given key in superglobals $_GET, $_POST, $_REQUEST
 */
function clean_superglobals($key, $filter = 'STANDARD') {
    if (isset($_GET[$key]))
        clean_string($_GET[$key], $filter);
    if (isset($_POST[$key]))
        clean_string($_POST[$key], $filter);
    if (isset($_REQUEST[$key]))
        clean_string($_REQUEST[$key], $filter);
}

// Works in conjunction with clean_string() to defeat SQL injection, file inclusion attacks, and XSS
function clean_incoming_data() {
    global $sugar_config;

    if (get_magic_quotes_gpc() == 1) {
        $req = array_map("preprocess_param", $_REQUEST);
        $post = array_map("preprocess_param", $_POST);
        $get = array_map("preprocess_param", $_GET);
    } else {
        $req = array_map("securexss", $_REQUEST);
        $post = array_map("securexss", $_POST);
        $get = array_map("securexss", $_GET);
    }

    // PHP cannot stomp out superglobals reliably
    foreach ($req as $k => $v) {
        clean_string($k, 'STANDARDSPACE');
        $_REQUEST[$k] = $v;
    }

    foreach ($post as $k => $v) {
        clean_string($k, 'STANDARDSPACE');
        $_POST[$k] = $v;
    }

    foreach ($get as $k => $v) {
        clean_string($k, 'STANDARDSPACE');
        $_GET[$k] = $v;
    }

    // Any additional variables that need to be cleaned should be added here
    if (isset($_REQUEST['action']))
        clean_string($_REQUEST['action']);
    if (isset($_REQUEST['module']))
        clean_string($_REQUEST['module']);
    if (isset($_REQUEST['record']))
        clean_string($_REQUEST['record'], 'STANDARDSPACE');
    if (isset($_SESSION['authenticated_user_theme']))
        clean_string($_SESSION['authenticated_user_theme']);
    if (isset($_SESSION['authenticated_user_language']))
        clean_string($_SESSION['authenticated_user_language']);
    if (isset($sugar_config['default_theme']))
        clean_string($sugar_config['default_theme']);
    if (isset($_REQUEST['offset']))
        clean_string($_REQUEST['offset']);
    if (isset($_REQUEST['stamp']))
        clean_string($_REQUEST['stamp']);

    // Clean "offset" and "order_by" parameters in URL
    foreach ($_GET as $key => $val) {
        if (str_end($key, "_offset")) {
            clean_string($_GET[$key], "NUMBER");
        } elseif (str_end($key, "_ORDER_BY")) {
            clean_string($_GET[$key], "SQL_COLUMN_LIST");
        }
    }

    return 0;
}

// Returns TRUE if $str begins with $begin
function str_begin($str, $begin) {
    return (substr($str, 0, strlen($begin)) == $begin);
}

// Returns TRUE if $str ends with $end
function str_end($str, $end) {
    return (substr($str, strlen($str) - strlen($end)) == $end);
}

function securexss($value) {
    $xss_cleanup = array('"' => '&quot;', "'" => '&#039;', '<' => '&lt;', '>' => '&gt;');
    $value = preg_replace('/javascript:/i', 'java script:', $value);
    return str_replace(array_keys($xss_cleanup), array_values($xss_cleanup), $value);
}

function preprocess_param($value) {
    if (is_string($value)) {
        if (get_magic_quotes_gpc() == 1) {
            $value = stripslashes($value);
        }
        $value = securexss($value);
    }
    return $value;
}

if (empty($register))
    $register = array();

function set_register_value($category, $name, $value) {
    global $register;
    if (empty($register[$category]))
        $register[$category] = array();
    $register[$category][$name] = $value;
}

function get_register_value($category, $name) {
    global $register;
    if (empty($register[$category]) || empty($register[$category][$name])) {
        return false;
    }
    return $register[$category][$name];
}

function get_register_values($category) {
    global $register;
    if (empty($register[$category])) {
        return false;
    }
    return $register[$category];
}

function clear_register($category, $name) {
    global $register;
    if (empty($name)) {
        unset($register[$category]);
    } else {
        if (!empty($register[$category]))
            unset($register[$category][$name]);
    }
}

// this function cleans id's when being imported
function convert_id($string) {
    return preg_replace_callback('|[^A-Za-z0-9\-]|', create_function(
                            // single quotes are essential here,
                            // or alternative escape all $ as \$
                            '$matches', 'return ord($matches[0]);'
                    ), $string);
}

function get_image($image, $other_attributes, $width = "", $height = "") {
    static $cached_results = array();

    if (!empty($cached_results[$image])) {
        return $cached_results[$image] . "$other_attributes>";
    }

    global $png_support;

    if ($png_support == false)
        $ext = "gif";
    else
        $ext = "png";
    $out = '';

    if (file_exists($image . '.' . $ext)) {
        $size = getimagesize($image . '.' . $ext);
        if ($width == "") {
            $width = $size[0];
        }
        if ($height == "") {
            $height = $size[1];
        }
        $out = "<img src='$image.$ext' width='" . $width . "' height='" . $height . "' $other_attributes>";
    } else if (substr_count($image, 'themes') > 0) {
        $path = explode('/', $image);
        $path[1] = 'Default';
        $image = implode('/', $path);

        if (file_exists($image . '.' . $ext)) {
            $size = getimagesize($image . '.' . $ext);
            if ($width == "") {
                $width = $size[0];
            }
            if ($height == "") {
                $height = $size[1];
            }
            $out = "<img src='$image.$ext' width='" . $width . "' height='" . $height . "' $other_attributes>";
        }
    }

    // Cache everything but the other attributes....
    $cached_results[$image] = "<img src='$image.$ext' width='" . $width . "' height='" . $height . "' ";

    return $out;
}

function getSQLDate($date_str) {
    if (preg_match('/^(\d{1,2})-(\d{1,2})-(\d{4})$/', $date_str, $match)) {
        if (strlen($match[2]) == 1) {
            $match[2] = "0" . $match[2];
        }
        if (strlen($match[1]) == 1) {
            $match[1] = "0" . $match[1];
        }
        return "{$match[3]}-{$match[1]}-{$match[2]}";
    } else if (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/', $date_str, $match)) {
        if (strlen($match[2]) == 1) {
            $match[2] = "0" . $match[2];
        }
        if (strlen($match[1]) == 1) {
            $match[1] = "0" . $match[1];
        }
        return "{$match[3]}-{$match[1]}-{$match[2]}";
    } else {
        return "";
    }
}

function clone_history(&$db, $from_id, $to_id, $to_type) {

    $old_note_id = null;
    $old_filename = null;
    require_once('include/upload_file.php');
    $tables = array('emails' => 'Email', 'calls' => 'Call', 'meetings' => 'Meeting', 'notes' => 'Note', 'tasks' => 'Task');

    $location = array('Email' => "modules/Emails/Email.php",
        'Call' => "modules/Calls/Call.php",
        'Meeting' => "modules/Meetings/Meeting.php",
        'Note' => "modules/Notes/Note.php",
        'Tasks' => "modules/Tasks/Task.php",
    );


    foreach ($tables as $table => $bean_class) {

        if (!class_exists($bean_class)) {
            require_once($location[$bean_class]);
        }

        $bProcessingNotes = false;
        if ($table == 'notes') {
            $bProcessingNotes = true;
        }

        $query = "SELECT id FROM $table WHERE parent_id='$from_id'";
        $results = $db->query($query);

        while ($row = $db->fetchByAssoc($results)) {

            //retrieve existing record.
            $bean = new $bean_class();
            $bean->retrieve($row['id']);

            //process for new instance.
            if ($bProcessingNotes) {
                $old_note_id = $row['id'];
                $old_filename = $bean->filename;
            }

            $bean->id = null;
            $bean->parent_id = $to_id;
            $bean->parent_type = $to_type;
            if ($to_type == 'Contacts' and in_array('contact_id', $bean->column_fields)) {
                $bean->contact_id = $to_id;
            }

            //save
            $new_id = $bean->save();

            //duplicate the file now. for notes.
            if ($bProcessingNotes && !empty($old_filename)) {
                UploadFile::duplicate_file($old_note_id, $new_id, $old_filename);
            }

            //reset the values needed for attachment duplication.
            $old_note_id = null;
            $old_filename = null;
        }
    }
}

function values_to_keys($array) {
    $new_array = array();
    if (!is_array($array)) {
        return $new_array;
    }
    foreach ($array as $arr) {
        $new_array[$arr] = $arr;
    }
    return $new_array;
}

function clone_relationship(&$db, $tables = array(), $from_column, $from_id, $to_id) {
    foreach ($tables as $table) {
        $query = "SELECT * FROM $table WHERE $from_column='$from_id'";
        $results = $db->query($query);
        while ($row = $db->fetchByAssoc($results)) {
            $query = "INSERT INTO $table ";
            $names = '';
            $values = '';
            $row[$from_column] = $to_id;
            $row['id'] = create_guid();
            foreach ($row as $name => $value) {

                if (empty($names)) {
                    $names .= $name;
                    $values .= "'$value'";
                } else {
                    $names .= ', ' . $name;
                    $values .= ", '$value'";
                }
            }

            $query .= "($names)	VALUES ($values);";
            $db->query($query);
        }
    }
}

function number_empty($value) {
    return empty($value) && $value != '0';
}

function get_bean_select_array($add_blank = true, $bean_name, $display_columns, $where = '', $order_by = '', $blank_is_none = false) {
    global $beanFiles;
    require_once($beanFiles[$bean_name]);
    $focus = new $bean_name();
    $user_array = array();
    $user_array = get_register_value('select_array', $bean_name . $display_columns . $where . $order_by);
    if (!$user_array) {

        $db = & PearDatabase::getInstance();
        $temp_result = Array();
        $query = "SELECT id, {$display_columns} as display from {$focus->table_name} where ";
        if ($where != '') {
            $query .= $where . " AND ";
        }

        $query .= " deleted=0";

        if ($order_by != '') {
            $query .= ' order by ' . $order_by;
        }

        $GLOBALS['log']->debug("get_user_array query: $query");
        $result = $db->query($query, true, "Error filling in user array: ");

        if ($add_blank == true) {
            // Add in a blank row
            if ($blank_is_none == true) { // set 'blank row' to "--None--"
                global $app_strings;
                $temp_result[''] = $app_strings['LBL_NONE'];
            } else {
                $temp_result[''] = '';
            }
        }

        // Get the id and the name.
        while ($row = $db->fetchByAssoc($result)) {
            $temp_result[$row['id']] = $row['display'];
        }

        $user_array = $temp_result;
        set_register_value('select_array', $bean_name . $display_columns . $where . $order_by, $temp_result);
    }

    return $user_array;
}

/**
 *
 *
 * @param unknown_type $listArray
 */
// function parse_list_modules
// searches a list for items in a user's allowed tabs and returns an array that removes unallowed tabs from list
function parse_list_modules(&$listArray) {
    global $modListHeader;
    $returnArray = array();

    foreach ($listArray as $optionName => $optionVal) {
        if (array_key_exists($optionName, $modListHeader)) {
            $returnArray[$optionName] = $optionVal;
        }
        // special case for products
        if (array_key_exists('Products', $modListHeader)) {
            $returnArray['ProductTemplates'] = $listArray['ProductTemplates'];
        }

        // special case for projects
        if (array_key_exists('Project', $modListHeader)) {
            $returnArray['ProjectTask'] = $listArray['ProjectTask'];
        }
    }
    $acldenied = ACLController::disabledModuleList($listArray, false);
    foreach ($acldenied as $denied) {
        unset($returnArray[$denied]);
    }
    asort($returnArray);

    return $returnArray;
}

function display_notice($msg = false) {
    global $error_notice;
    //no error notice - lets just display the error to the user
    if (!isset($error_notice)) {
        echo '<br>' . $msg . '<br>';
    } else {
        $error_notice .= $msg . '<br>';
    }
}

/* checks if it is a number that atleast has the plus at the beggining
 */

function skype_formatted($number) {
    return substr($number, 0, 1) == '+' || substr($number, 0, 2) == '00' || substr($number, 0, 2) == '011';
}

function insert_charset_header() {
    header('Content-Type: text/html; charset=UTF-8');
}

function getCurrentURL() {
    $href = "http:";
    if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
        $href = 'https:';
    }

    $href.= "//" . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'] . '?' . $_SERVER['QUERY_STRING'];
    return $href;
}

function javascript_escape($str) {
    $new_str = '';

    for ($i = 0; $i < strlen($str); $i++) {

        if (ord(substr($str, $i, 1)) == 10) {
            $new_str .= '\n';
        } elseif (ord(substr($str, $i, 1)) == 13) {
            $new_str .= '\r';
        } else {
            $new_str .= $str{$i};
        }
    }

    $new_str = str_replace("'", "\\'", $new_str);

    return $new_str;
}

function js_escape($str, $keep = true) {
    $str = html_entity_decode(str_replace("\\", "", $str), ENT_QUOTES);

    if ($keep) {
        $str = javascript_escape($str);
    } else {
        $str = str_replace("'", " ", $str);
        $str = str_replace('"', " ", $str);
    }

    return $str;

//end function js_escape
}

function br2nl($str) {
    $brs = array('<br>', '<br/>', '<br />');
    $str = str_replace("\r\n", "\n", $str); // make from windows-returns, *nix-returns
    $str = str_replace($brs, "\n", $str); // to retrieve it
    return $str;
}

/**
 * Private helper function for displaying the contents of a given variable.
 * This function is only intended to be used for SugarCRM internal development.
 * The ppd stands for Pre Print Die.
 */
function _ppd($mixed) {
    
}

/**
 * Private helper function for displaying the contents of a given variable in
 * the Logger. This function is only intended to be used for SugarCRM internal
 * development. The pp stands for Pre Print.
 * @param $mixed var to print_r()
 * @param $die boolean end script flow
 * @param $displayStackTrace also show stack trace
 */
function _ppl($mixed, $die = false, $displayStackTrace = false, $loglevel = "fatal") {
    
}

/**
 * private helper function to quickly show the major, direct, field attributes of a given bean.
 * The ppf stands for Pre[formatted] Print Focus [object]
 * @param object bean The focus bean
 */
function _ppf($bean, $die = false) {
    
}

/**
 * Private helper function for displaying the contents of a given variable.
 * This function is only intended to be used for SugarCRM internal development.
 * The pp stands for Pre Print.
 */
function _pp($mixed) {
    
}

/**
 * Private helper function for displaying the contents of a given variable.
 * This function is only intended to be used for SugarCRM internal development.
 * The pp stands for Pre Print Trace.
 */
function _ppt($mixed) {
    
}

/**
 * Private helper function for displaying the contents of a given variable.
 * This function is only intended to be used for SugarCRM internal development.
 * The pp stands for Pre Print Trace Die.
 */
function _pptd($mixed) {
    
}

/**
 * Will check if a given PHP version string is supported (tested on this ver),
 * unsupported (results unknown), or invalid (something will break on this
 * ver).  Do not pass in any pararameter to default to a check against the
 * current environment's PHP version.
 *
 * @return 1 implies supported, 0 implies unsupported, -1 implies invalid
 */
function check_php_version($sys_php_version = '') {
    $sys_php_version = empty($sys_php_version) ? constant('PHP_VERSION') : $sys_php_version;
    // versions below $min_considered_php_version considered invalid by default,
    // versions equal to or above this ver will be considered depending
    // on the rules that follow
    $min_considered_php_version = '4.3.8';

    // only the supported versions,
    // should be mutually exclusive with $invalid_php_versions
    $supported_php_versions = array(
        '4.3.10', '4.3.11',
        '4.4.1', '4.4.2',
        '5.0.1', '5.0.2', '5.0.3', '5.0.4', '5.0.5',
        '5.1.0', '5.1.1', '5.1.2'
    );

    // invalid versions above the $min_considered_php_version,
    // should be mutually exclusive with $supported_php_versions

    $invalid_php_versions = array('5.0.0');

    // default unsupported
    $retval = 0;

    // versions below $min_considered_php_version are invalid
    if (1 == version_compare($sys_php_version, $min_considered_php_version, '<')) {
        $retval = -1;
    }

    // supported version check overrides default unsupported
    foreach ($supported_php_versions as $ver) {
        if (1 == version_compare($sys_php_version, $ver, 'eq')) {
            $retval = 1;
            break;
        }
    }

    // invalid version check overrides default unsupported
    foreach ($invalid_php_versions as $ver) {
        if (1 == version_compare($sys_php_version, $ver, 'eq')) {
            $retval = -1;
            break;
        }
    }

    return $retval;
}

function pre_login_check() {
    global $action, $login_error;
    if (!empty($action) && $action == 'Login') {
        checkLoginUserStatus();

        if (!empty($login_error)) {
            $login_error = htmlentities($login_error);
            $login_error = str_replace(array("&lt;pre&gt;", "&lt;/pre&gt;", "\r\n", "\n"), "<br>", $login_error);
            $_SESSION['login_error'] = $login_error;
            echo '<script>
						function set_focus() {}
						if(document.getElementById("post_error")) {
							document.getElementById("post_error").innerHTML="' . $login_error . '";
							document.getElementById("cant_login").value=1;
							document.getElementById("login_button").disabled = true;
							document.getElementById("user_name").disabled = true;
							//document.getElementById("user_password").disabled = true;
						}
						</script>';
        }
    }
}

function sugar_cleanup($exit = false) {
    if (!empty($GLOBALS['savePreferencesToDB']) && $GLOBALS['savePreferencesToDB']) {
        require_once('modules/UserPreferences/UserPreference.php');
        UserPreference::savePreferencesToDB();
    }
    pre_login_check();
    if (class_exists('PearDatabase')) {
        $db = & PearDatabase::getInstance();
        $db->disconnect();
        if ($exit) {
            exit;
        }
    }
}

/*
  check_logic_hook - checks to see if your custom logic is in the logic file
  if not, it will add it. If the file isn't built yet, it will create the file

  TODO: remove_logic_hook

 */

function check_logic_hook_file($module_name, $event, $action_array) {
    require_once('include/utils/logic_utils.php');
    $add_logic = false;

    if (file_exists("custom/modules/$module_name/logic_hooks.php")) {

        $hook_array = get_hook_array($module_name);

        if (check_existing_element($hook_array, $event, $action_array) == true) {
            //the hook at hand is present, so do nothing
        } else {
            $add_logic = true;

            $logic_count = count($hook_array[$event]);
            if ($action_array[0] == "") {
                $action_array[0] = $logic_count + 1;
            }
            $hook_array[$event][] = $action_array;
        }
        //end if the file exists already
    } else {
        $add_logic = true;
        if ($action_array[0] == "") {
            $action_array[0] = 1;
        }
        $hook_array = array();
        $hook_array[$event][] = $action_array;
        //end if else file exists already
    }
    if ($add_logic == true) {

        //reorder array by element[0]
        //$hook_array = reorder_array($hook_array, $event);
        //!!!Finish this above TODO

        $new_contents = replace_or_add_logic_type($hook_array);
        write_logic_file($module_name, $new_contents);

        //end if add_element is true
    }

//end function check_logic_hook_file
}

function display_stack_trace() {
    $stack = debug_backtrace();
    echo '<br>';
    $first = true;



    foreach ($stack as $item) {
        $file = '';
        if (isset($item['file']))
            $file = $item['file'];
        $class = '';
        if (isset($item['class']))
            $class = $item['class'];
        $line = '';
        if (isset($item['line']))
            $line = $item['line'];
        $function = '';
        if (isset($item['function']))
            $function = $item['function'];
        if (!$first) {
            echo '<font color="black"><b>' . $file . '</b></font>' . '<font color="blue">[L:' . $line . ']</font>' . '<font color="red">(' . $class . ':' . $function . ')</font><br>';
        } else {
            $first = false;
        }
    }
}

function StackTraceErrorHandler($errno, $errstr, $errfile, $errline, $errcontext) {
    $error_msg = " $errstr occured in <b>$errfile</b> on line $errline [" . date("Y-m-d H:i:s") . ']';
    $halt_script = true;
    switch ($errno) {
        case 2048: return; //depricated we have lots of these ignore them
        case E_USER_NOTICE:
        case E_NOTICE:
            $halt_script = false;
            $type = 'Notice';
            break;
        case E_USER_WARNING:
        case E_COMPILE_WARNING:
        case E_CORE_WARNING:
        case E_WARNING:

            $halt_script = false;
            $type = "Warning";
            break;

        case E_USER_ERROR:
        case E_COMPILE_ERROR:
        case E_CORE_ERROR:
        case E_ERROR:

            $type = "Fatal Error";
            break;

        case E_PARSE:

            $type = "Parse Error";
            break;

        default:
            //don't know what it is might not be so bad
            $halt_script = false;
            $type = "Unknown Error ($errno)";
            break;
    }
    $error_msg = '<b>' . $type . '</b>:' . $error_msg;
    echo $error_msg;
    display_stack_trace();
    if ($halt_script) {
        exit - 1;
    }
}

if (isset($sugar_config['stack_trace_errors']) && $sugar_config['stack_trace_errors']) {

    set_error_handler('StackTraceErrorHandler');
}

function get_sub_cookies($name) {
    $cookies = array();
    if (isset($_COOKIE[$name])) {
        $subs = explode('#', $_COOKIE[$name]);
        foreach ($subs as $cookie) {
            if (!empty($cookie)) {
                $cookie = explode('=', $cookie);

                $cookies[$cookie[0]] = $cookie[1];
            }
        }
    }
    return $cookies;
}

function mark_delete_components($sub_object_array, $run_second_level = false, $sub_sub_array = "") {

    if (!empty($sub_object_array)) {

        foreach ($sub_object_array as $sub_object) {

            //run_second level is set to true if you need to remove sub-sub components
            if ($run_second_level == true) {

                mark_delete_components($sub_object->get_linked_beans($sub_sub_array['rel_field'], $sub_sub_array['rel_module']));

                //end if run_second_level is true
            }
            $sub_object->mark_deleted($sub_object->id);
            //end foreach sub component
        }
        //end if this is not empty
    }

//end function mark_delete_components
}

/**
 * For translating the php.ini memory values into bytes.  e.g. input value of '8M' will return 8388608.
 */
function return_bytes($val) {
    $val = trim($val);
    $last = strtolower($val{strlen($val) - 1});

    switch ($last) {
        // The 'G' modifier is available since PHP 5.1.0
        case 'g':
            $val *= 1024;
        case 'm':
            $val *= 1024;
        case 'k':
            $val *= 1024;
    }

    return $val;
}

/**
 * Adds the href HTML tags around any URL in the $string
 */
function url2html($string) {
    //
    $return_string = preg_replace('/(\w+:\/\/)(\S+)/', ' <a href="\\1\\2" target="_new" class="tabDetailViewDFLink"  style="font-weight: normal;">\\1\\2</a>', $string);
    return $return_string;
}

// End customization by Julian

/**
 * tries to determine whether the Host machine is a Windows machine
 */
function is_windows() {
    if (preg_match('#WIN#', PHP_OS)) {
        return true;
    }
    return false;
}

/**
 * equivalent for windows filesystem for PHP's is_writable()
 * @param string file Full path to the file/dir
 * @return bool true if writable
 */
function is_writable_windows($file) {
    if ($file{strlen($file) - 1} == '/') {
        return is_writable_windows($file . uniqid(mt_rand()) . '.tmp');
    }

    // the assumption here is that Windows has an inherited permissions scheme
    // any file that is a descendant of an unwritable directory will inherit
    // that property and will trigger a failure below.
    if (is_dir($file)) {
        return true;
    }

    $file = str_replace("/", '\\', $file);

    if (file_exists($file)) {
        if (!($f = @fopen($file, 'r+')))
            return false;
        fclose($f);
        return true;
    }

    if (!($f = @fopen($file, 'w')))
        return false;
    fclose($f);
    unlink($file);
    return true;
}

/**
 * best guesses Timezone based on webserver's TZ settings
 */
function lookupTimezone($userOffset = 0) {
    require_once('include/timezone/timezones.php');

    $defaultZones = array('America/New_York' => 1, 'America/Los_Angeles' => 1, 'America/Chicago' => 1, 'America/Denver' => 1, 'America/Anchorage' => 1, 'America/Phoenix' => 1, 'Europe/Amsterdam' => 1, 'Europe/Athens' => 1, 'Europe/London' => 1, 'Australia/Sydney' => 1, 'Australia/Perth' => 1);
    global $timezones;
    $serverOffset = date('Z');
    if (date('I')) {
        $serverOffset -= 3600;
    }
    if (!is_int($userOffset)) {
        return '';
    }
    $gmtOffset = $serverOffset / 60 + $userOffset * 60;
    $selectedZone = ' ';
    foreach ($timezones as $zoneName => $zone) {

        if ($zone['gmtOffset'] == $gmtOffset) {
            $selectedZone = $zoneName;
        }
        if (!empty($defaultZones[$selectedZone])) {
            return $selectedZone;
        }
    }
    return $selectedZone;
}

function convert_module_to_singular($module_array) {
    global $beanList;

    foreach ($module_array as $key => $value) {
        if (!empty($beanList[$value]))
            $module_array[$key] = $beanList[$value];


        if ($value == "Cases")
            $module_array[$key] = "Case";
        if ($key == "projecttask") {
            $module_array['ProjectTask'] = "Project Task";
            unset($module_array[$key]);
        }
    }

    return $module_array;

//end function convert_module_to_singular
}

/*
 * Given the bean_name which may be plural or singular return the singular
 * bean_name. This is important when you need to include files.
 */

function get_singular_bean_name($bean_name) {
    global $beanFiles, $beanList;
    if (array_key_exists($bean_name, $beanList)) {
        return $beanList[$bean_name];
    } else {
        return $bean_name;
    }
}

function get_label($label_tag, $temp_module_strings) {
    global $app_strings;
    if (!empty($temp_module_strings[$label_tag])) {

        $label_name = $temp_module_strings[$label_tag];
    } else {
        if (!empty($app_strings[$label_tag])) {
            $label_name = $app_strings[$label_tag];
        } else {
            $label_name = $label_tag;
        }
    }
    return $label_name;

//end function get_label
}

function search_filter_rel_info(& $focus, $tar_rel_module, $relationship_name) {

    $rel_list = array();

    foreach ($focus->relationship_fields as $rel_key => $rel_value) {
        if ($rel_value == $relationship_name) {
            $temp_bean = get_module_info($tar_rel_module);
            echo $focus->$rel_key;
            $temp_bean->retrieve($focus->$rel_key);
            if ($temp_bean->id != "") {

                $rel_list[] = $temp_bean;
                return $rel_list;
            }
        }
    }

    return $rel_list;

//end function search_filter_rel_info
}

function get_module_info($module_name) {
    global $beanList;
    global $dictionary;

    //Get dictionary and focus data for module
    $vardef_name = $beanList[$module_name];

    if ($vardef_name == "aCase") {
        $class_name = "Case";
    } else {
        $class_name = $vardef_name;
    }
    if (!file_exists('modules/' . $module_name . '/' . $class_name . '.php')) {
        return;
    }

    include_once('modules/' . $module_name . '/' . $class_name . '.php');

    $module_bean = new $vardef_name();
    return $module_bean;
    //end function get_module_table
}

function checkAuthUserStatus() {

    authUserStatus();
}

/**
 * This function returns an array of phpinfo() results that can be parsed and
 * used to figure out what version we run, what modules are compiled in, etc.
 * @param	$level			int		info level constant (1,2,4,8...64);
 * @return	$returnInfo		array	array of info about the PHP environment
 * @author	original by "code at adspeed dot com" Fron php.net
 * @author	customized for Sugar by Chris N.
 */
function getPhpInfo($level = -1) {
    /** 	Name (constant)		Value	Description
      INFO_GENERAL		1		The configuration line, php.ini location, build date, Web Server, System and more.
      INFO_CREDITS		2		PHP Credits. See also phpcredits().
      INFO_CONFIGURATION	4		Current Local and Master values for PHP directives. See also ini_get().
      INFO_MODULES		8		Loaded modules and their respective settings. See also get_loaded_extensions().
      INFO_ENVIRONMENT	16		Environment Variable information that's also available in $_ENV.
      INFO_VARIABLES		32		Shows all predefined variables from EGPCS (Environment, GET, POST, Cookie, Server).
      INFO_LICENSE		64		PHP License information. See also the license FAQ.
      INFO_ALL			-1		Shows all of the above. This is the default value.
     */
    ob_start();
    phpinfo($level);
    $phpinfo = ob_get_contents();
    ob_end_clean();

    $phpinfo = strip_tags($phpinfo, '<h1><h2><th><td>');
    $phpinfo = preg_replace('/<th[^>]*>([^<]+)<\/th>/', "<info>\\1</info>", $phpinfo);
    $phpinfo = preg_replace('/<td[^>]*>([^<]+)<\/td>/', "<info>\\1</info>", $phpinfo);
    $parsedInfo = preg_split('/(<h.?>[^<]+<\/h.>)/', $phpinfo, -1, PREG_SPLIT_DELIM_CAPTURE);
    $match = '';
    $version = '';
    $returnInfo = array();

    if (preg_match('/<h1 class\=\"p\">PHP Version ([^<]+)<\/h1>/', $phpinfo, $version)) {
        $returnInfo['PHP Version'] = $version[1];
    }

    for ($i = 1; $i < count($parsedInfo); $i++) {
        if (preg_match('/<h.>([^<]+)<\/h.>/', $parsedInfo[$i], $match)) {
            $vName = trim($match[1]);
            $parsedInfo2 = explode("\n", $parsedInfo[$i + 1]);

            foreach ($parsedInfo2 AS $vOne) {
                $vPat = '<info>([^<]+)<\/info>';
                $vPat3 = "/$vPat\s*$vPat\s*$vPat/";
                $vPat2 = "/$vPat\s*$vPat/";

                if (preg_match($vPat3, $vOne, $match)) { // 3cols
                    $returnInfo[$vName][trim($match[1])] = array(trim($match[2]), trim($match[3]));
                } elseif (preg_match($vPat2, $vOne, $match)) { // 2cols
                    $returnInfo[$vName][trim($match[1])] = trim($match[2]);
                }
            }
        } elseif (true) {
            
        }
    }

    return $returnInfo;
}

/**
 * This function will take a string that has tokens like {0}, {1} and will replace
 * those tokens with the args provided
 * @param	$format string to format
 * @param	$args args to replace
 * @return	$result a formatted string
 */
function string_format($format, $args) {
    $result = $format;
    for ($i = 0; $i < count($args); $i++) {
        $result = str_replace('{' . $i . '}', $args[$i], $result);
    }
    return $result;
}

/**
 * This function will take a number and system_id and format
 * @param	$num of bean
 * @param	$system_id from system
 * @return	$result a formatted string
 */
function format_number_display($num, $system_id) {
    global $sugar_config;
    if (isset($num) && !empty($num)) {
        if (isset($system_id) && $system_id == 1) {
            return $num;
        } else {
            return sprintf("%d-%d", $num, $system_id);
        }
    }
}

function checkLoginUserStatus() {
    //getLoginUserStatus();
}

/**
 * This function will take a number and system_id and format
 * @param	$url URL containing host to append port
 * @param	$port the port number - if '' is passed, no change to url
 * @return	$resulturl the new URL with the port appended to the host
 */
function appendPortToHost($url, $port) {
    $resulturl = $url;

    // if no port, don't change the url
    if ($port != '') {
        $split = explode("/", $url);
        //check if it starts with http, in case they didn't include that in url
        if (str_begin($url, 'http')) {
            //third index ($split[2]) will be the host
            $split[2] .= ":" . $port;
        } else { // otherwise assumed to start with host name
            //first index ($split[0]) will be the host
            $split[0] .= ":" . $port;
        }

        $resulturl = implode("/", $split);
    }

    return $resulturl;
}

/**
 * Singleton to return JSON object
 * @return	JSON object
 */
function getJSONobj() {
    static $json = null;
    if (!isset($json)) {
        require_once('include/JSON.php');
        $json = new JSON(JSON_LOOSE_TYPE);
    }
    return $json;
}

require_once('include/utils/db_utils.php');
require_once('include/utils/user_utils.php');
//check to see if custom utils exists
if (file_exists('custom/include/custom_utils.php')) {
    include_once('custom/include/custom_utils.php');
}

/**
 * Set default php.ini settings for entry points
 */
function setPhpIniSettings() {
    // zlib module
    if (function_exists('gzclose')) {
        ini_set('zlib.output_compression', 1);
    }
    // mbstring module
    if (function_exists('mb_strlen')) {
        ini_set('mbstring.func_overload', 7);
        ini_set('mbstring.internal_encoding', 'UTF-8');
    }

    // mssql only
    if (ini_get("mssql.charset")) {
        ini_set('mssql.charset', "UTF-8");
    }
}

/**
 * like array_merge() but will handle array elements that are themselves arrays;
 * PHP's version just overwrites the element with the new one.
 * @param array gimp the array whose values will be overloaded
 * @param array dom the array whose values will pwn the gimp's
 * @return array beaten gimp
 */
function sugarArrayMerge($gimp, $dom) {
    if (is_array($gimp) && is_array($dom)) {
        foreach ($dom as $domKey => $domVal) {
            if (array_key_exists($domKey, $gimp)) {
                if (is_array($domVal)) {
                    $gimp[$domKey] = sugarArrayMerge($gimp[$domKey], $dom[$domKey]);
                } else {
                    $gimp[$domKey] = $domVal;
                }
            } else {
                $gimp[$domKey] = $domVal;
            }
        }
    }
    return $gimp;
}

/**
 * finds the correctly working versions of PHP-JSON
 * @return bool True if NOT found or WRONG version
 */
function returnPhpJsonStatus() {
    $goodVersions = array('1.1.1',);

    if (function_exists('json_encode')) {
        $phpInfo = getPhpInfo(8);

        if (!in_array($phpInfo['json']['json version'], $goodVersions)) {
            return true; // bad version found
        } else {
            return false; // all requirements met
        }
    }
    return true; // not found
}

/**
 * returns a 20-char or less string for the Tracker to display in the header
 * @param string name field for a given Object
 * @return string 20-char or less name
 */
function getTrackerSubstring($name) {
    $strlen = function_exists('mb_strlen') ? mb_strlen($name) : strlen($name);

    if ($strlen > 20) {
        $chopped = function_exists('mb_substr') ? mb_substr($name, 0, 15) : substr($name, 0, 15);
    } else {
        $chopped = $name;
    }

    return $chopped;
}

function generate_search_where($field_list = array(), $values = array(), &$bean, $add_custom_fields = false, $module = '') {
    $where_clauses = array();
    $like_char = '%';
    $table_name = $bean->object_name;
    foreach ($field_list[$module] as $field => $parms) {
        if (isset($values[$field]) && $values[$field] != "") {
            $operator = 'like';
            if (!empty($parms['operator'])) {
                $operator = $parms['operator'];
            }
            if (is_array($values[$field])) {
                $operator = 'in';
                $field_value = '';
                foreach ($values[$field] as $key => $val) {
                    if ($val != ' ' and $val != '') {
                        if (!empty($field_value)) {
                            $field_value.=',';
                        }
                        $field_value .= "'" . $GLOBALS['db']->quote($val) . "'";
                    }
                }
            } else {
                $field_value = $GLOBALS['db']->quote($values[$field]);
            }
            //set db_fields array.
            if (!isset($parms['db_field'])) {
                $parms['db_field'] = array($field);
            }
            if (isset($parms['my_items']) and $parms['my_items'] == true) {
                global $current_user;
                $field_value = $GLOBALS['db']->quote($current_user->id);
                $operator = '=';
            }

            $where = '';
            $itr = 0;
            if ($field_value != '') {

                foreach ($parms['db_field'] as $db_field) {
                    if (strstr($db_field, '.') === false) {
                        $db_field = $bean->table_name . "." . $db_field;
                    }
                    if ($GLOBALS['db']->dbType == 'oci8' && isset($parms['query_type']) && $parms['query_type'] == 'case_insensitive') {
                        $db_field = 'upper(' . $db_field . ")";
                        $field_value = strtoupper($field_value);
                    }

                    $itr++;
                    if (!empty($where)) {
                        $where .= " OR ";
                    }
                    switch (strtolower($operator)) {
                        case 'like' :
                            $where .= $db_field . " like '" . $field_value . $like_char . "'";
                            break;
                        case 'in':
                            $where .= $db_field . " in (" . $field_value . ')';
                            break;
                        case '=':
                            $where .= $db_field . " = '" . $field_value . "'";
                            break;
                    }
                }
            }
            if (!empty($where)) {
                if ($itr > 1) {
                    array_push($where_clauses, '( ' . $where . ' )');
                } else {
                    array_push($where_clauses, $where);
                }
            }
        }
    }
    if ($add_custom_fields) {
        require_once('modules/DynamicFields/DynamicField.php');
        $bean->setupCustomFields($module);
        $bean->custom_fields->setWhereClauses($where_clauses);
    }
    return $where_clauses;
}

function add_quotes($str) {
    return "'{$str}'";
}

/**
 * This function will rebuild the config file
 * @param	$sugar_config
 * @param	$sugar_version
 * @return	bool true if successful
 */
function rebuildConfigFile($sugar_config, $sugar_version) {
    // add defaults to missing values of in-memory sugar_config
    $sugar_config = sugarArrayMerge(get_sugar_config_defaults(), $sugar_config);

    // need to override version with default no matter what
    $sugar_config['sugar_version'] = $sugar_version;

    ksort($sugar_config);

    if (write_array_to_file("sugar_config", $sugar_config, "config.php")) {
        return true;
    } else {
        return false;
    }
}

function get_state_details($state_id) {
    global $log;
    $array = get_register_value('state_details', $state_id);
    if (!$array) {
        $temp_result = Array();
        // Including deleted records for now.
        $query = "SELECT state_mast.id state_id, state_mast.name state_description,country_mast.id country_id,country_mast.name country_description ";
        $query.= "from state_mast INNER JOIN state_mast_cstm ON state_mast.id = state_mast_cstm.id_c INNER JOIN country_mast ON state_mast_cstm.country_id_c = country_mast.id where state_mast.id='" . $state_id . "'";
        $log->debug("get_state_details: $query");
        $result = $GLOBALS['db']->query($query, true, "Error filling in user array: ");

        if ($row = $GLOBALS['db']->fetchByAssoc($result)) {
            $temp_result['state_id'] = $row['state_id'];
            $temp_result['country_id'] = $row['country_id'];
            $temp_result['state_description'] = $row['state_description'];
            $temp_result['country_description'] = $row['country_description'];
        }

        $array = $temp_result;
        set_register_value('state_details', $state_id, $temp_result);
    }

    return $array;
}

function get_state_array($add_blank = true) {
    global $log;
    $array = get_register_value('get_state_array', 'get_state_array' . $add_blank);
    if (!$array) {
        $temp_result = Array();
        // Including deleted records for now.
        $query = "SELECT id , name from state_mast where deleted=0";
        $db = & PearDatabase::getInstance();
        $result = $db->query($query, true, "Error filling in user array: ");

        while ($row = $db->fetchByAssoc($result)) {
            $temp_result[$row['id']] = $row['name'];
        }

        if ($add_blank) {
            $temp_result[''] = 'None';
        }

        $array = $temp_result;
        set_register_value('get_state_array', 'get_state_array' . $add_blank, $temp_result);
    }

    return $array;
}

function get_country_array($add_blank = true) {
    global $log;
    $array = get_register_value('get_country_array', 'get_country_array' . $add_blank);
    if (!$array) {
        $temp_result = Array();
        // Including deleted records for now.
        $query = "SELECT id , name from country_mast where deleted=0";
        $db = & PearDatabase::getInstance();
        $result = $db->query($query, true, "Error filling in user array: ");

        while ($row = $db->fetchByAssoc($result)) {
            $temp_result[$row['id']] = $row['name'];
        }

        if ($add_blank) {
            $temp_result[''] = 'None';
        }

        $array = $temp_result;
        set_register_value('get_country_array', 'get_country_array' . $add_blank, $temp_result);
    }

    return $array;
}

function get_city_array($add_blank = true) {
    global $log;
    $array = get_register_value('get_city_array', 'get_city_array' . $add_blank);
    if (!$array) {
        $temp_result = Array();
        // Including deleted records for now.
        $query = "SELECT id , name from city_mast where deleted=0";
        $db = & PearDatabase::getInstance();
        $result = $db->query($query, true, "Error filling in user array: ");
        while ($row = $db->fetchByAssoc($result)) {
            $temp_result[$row['id']] = $row['name'];
        }

        if ($add_blank) {
            $temp_result[''] = 'None';
        }

        $GLOBALS['log']->debug("Implode :" . implode("/", $temp_result));
        $array = $temp_result;
        set_register_value('get_city_array', 'get_city_array' . $add_blank, $temp_result);
    }

    return $array;
}

function get_account_array($add_blank = true) {
    global $log;
    $array = get_register_value('get_account_array', 'get_account_array' . $add_blank);
    if (!$array) {
        $temp_result = Array();
        // Including deleted records for now.
        $query = "SELECT id , name from accounts where deleted=0";
        $db = & PearDatabase::getInstance();
        $result = $db->query($query, true, "Error filling in user array: ");
        while ($row = $db->fetchByAssoc($result)) {
            if (strlen($row['name']) > 20)
                $temp_result[$row['id']] = substr($row['name'], 0, 20) . "...";
            else
                $temp_result[$row['id']] = substr($row['name'], 0, 20);
        }

        if ($add_blank) {
            $temp_result[''] = 'None';
        }

        $GLOBALS['log']->debug("Implode :" . implode("/", $temp_result));
        $array = $temp_result;
        set_register_value('get_account_array', 'get_account_array' . $add_blank, $temp_result);
    }

    return $array;
}

function get_country_details($country_id) {
    global $log;
    $array = get_register_value('country_details', $country_id);
    if (!$array) {
        $temp_result = Array();
        // Including deleted records for now.
        $query = "SELECT id as country_id, name as country_description from country_mast where id='$country_id' and deleted=0";
        $log->debug("get_country_details: $query");
        $result = $GLOBALS['db']->query($query, true, "Error filling in user array: ");

        if ($row = $GLOBALS['db']->fetchByAssoc($result)) {
            $temp_result['country_id'] = $row['country_id'];
            $temp_result['country_description'] = $row['country_description'];
        }

        $array = $temp_result;
        set_register_value('country_details', $country_id, $temp_result);
    }

    return $array;
}

function get_region_details($region_id) {
    global $log;
    $array = get_register_value('region_details', $region_id);
    if (!$array) {
        $temp_result = Array();
        // Including deleted records for now.
        $query = "SELECT id as region_id, name as region_description from region_mast where id='$region_id' and deleted=0";
        $log->debug("get_region_details: $query");
        $result = $GLOBALS['db']->query($query, true, "Error filling in user array: ");

        if ($row = $GLOBALS['db']->fetchByAssoc($result)) {
            $temp_result['region_id'] = $row['region_id'];
            $temp_result['region_description'] = $row['region_description'];
        }

        $array = $temp_result;
        set_register_value('region_details', $country_id, $temp_result);
    }

    return $array;
}

function get_city_details($city_id) {
    global $log;
    $array = get_register_value('city_details', $city_id);
    //echo "present ".$country_array."<br>";
    if (!$array) {
        $temp_result = Array();
        // Including deleted users for now.
        $query = "SELECT city_mast.name as city_description,state_mast.id state_id, state_mast.name state_description,country_mast.id country_id,country_mast.name country_description ";
        $query.= "from city_mast INNER JOIN city_mast_cstm ON city_mast.id = city_mast_cstm.id_c INNER JOIN
		 state_mast ON city_mast_cstm.state_id_c = state_mast.id INNER JOIN state_mast_cstm ON state_mast.id = state_mast_cstm.id_c INNER JOIN country_mast ON state_mast_cstm.country_id_c = country_mast.id where city_mast.id='" . $city_id . "'";
        //echo "Query ".$query;
        $log->debug("get_city_details: $query");
        $result = $GLOBALS['db']->query($query, true, "Error filling in user array: ");

        // Get the id and the name.
        if ($row = $GLOBALS['db']->fetchByAssoc($result)) {
            //echo "Got some data 1 ".$row['state_id'];
            $temp_result['city_id'] = $city_id;
            $temp_result['city_description'] = $row['city_description'];
            $temp_result['state_id'] = $row['state_id'];
            $temp_result['country_id'] = $row['country_id'];
            $temp_result['state_description'] = $row['state_description'];
            $temp_result['country_description'] = $row['country_description'];
        }

        $array = $temp_result;
        set_register_value('city_details', $city_id, $temp_result);
    }

    return $array;
}

function get_working_days($start_date, $end_date, $holidays = array()) {
    $start_ts = strtotime($start_date);
    $end_ts = strtotime($end_date);
    foreach ($holidays as & $holiday) {
        $holiday = strtotime($holiday);
    }
    $working_days = 0;
    $tmp_ts = $start_ts;
    while ($tmp_ts <= $end_ts) {
        $tmp_day = date('D', $tmp_ts);
        if (!($tmp_day == 'Sun') && !($tmp_day == 'Sat') && !in_array($tmp_ts, $holidays)) {
            $working_days++;
        }
        $tmp_ts = strtotime('+1 day', $tmp_ts);
    }
    return $working_days;
}

function checkFeedbackOptionEnabledForUserBranch($current_user) {
    global $log;
    $db = & PearDatabase::getInstance();
    $query = "select feedback_option from branch_mast where id=(select branch_id_c from suboffice_mast_cstm where id_c='$current_user->suboffice_id')";
    $result = $db->query($query, true, "Error filling in user array: ");
    if ($result) {
        if ($row = $db->fetchByAssoc($result)) {
            if ($row['feedback_option'] == '1') {
                return TRUE;
            }
        }
    }
    return FALSE;
}

function getMyLeadTeamFromClause($bean_id, $bean_name, $module_name, $sub_module_name, $field_defs) {
    global $current_user;
    $db = & PearDatabase::getInstance();
    $query .= "SELECT DISTINCT $module_name.id from $module_name left join " . $module_name . "_users on " . $module_name . ".id=" . $module_name . "_users." . $bean_name . "_id  where $module_name.deleted=0 and " . $module_name . "_users.user_id='" . $current_user->id . "' and " . $module_name . "_users.deleted=0 ";
    $GLOBALS['log']->debug("SQL " . $query);

    $result = $db->query($query, true, "Error filling in user array: ");
    if ($result) {
        // Get the id and the name.
        while ($row = $db->fetchByAssoc($result)) {
            $in_entity_ids[] = $row['id'];
        }
    }
    if (isset($field_defs['id'])) {
        return $module_name . ".id IN ('" . implode("','", $in_entity_ids) . "')";
    }
    return;
#$from_clause=" from $module_name left join ".$module_name."_users on ".$module_name.".id=".$module_name."_users.".$bean_name."_id ";
#$GLOBALS['log']->debug("UTILS getMyDealUserFromClause :".$from_clause);
#return $from_clause;
}

function getMyLeadTeamItemsWhereClauseForSearchForm($bean_id, $bean_name, $module_name, $sub_module_name, $field_defs) {
    global $current_user;
    $db = & PearDatabase::getInstance();
    $query .= "SELECT DISTINCT $module_name.id from $module_name left join " . $module_name . "_users on " . $module_name . ".id=" . $module_name . "_users." . $bean_name . "_id  where $module_name.deleted=0 and " . $module_name . "_users.user_id='" . $current_user->id . "' and " . $module_name . "_users.deleted=0 ";
    $GLOBALS['log']->debug("getMyLeadTeamItemsWhereClauseForSearchForm: " . $query);
    $result = $db->query($query, true, "Error filling in user array: ");
    if ($result) {
        // Get the id and the name.
        while ($row = $db->fetchByAssoc($result)) {
            $in_entity_ids[] = $row['id'];
        }
    }
//    if(isset($field_defs['id'])) {
//        return $module_name.".id IN ('".implode("','",$in_entity_ids)."')";
//    }
    if ($in_entity_ids) {
        return "'" . implode("','", $in_entity_ids) . "'";
    }
    return;
}

/*
 *  Purpose : Get all Superior array of user until root user
 *  [Created By Yogesh Patil ] Aug 4 2010
 */

function get_user_all_hier_array() {
    global $current_user, $log;
    $current_user_id = $current_user->id;
    $log->debug("Current user id " . $current_user_id);
    if (!$user_in_array) {
        $temp_result = Array();
        //echo "Calling user id <br>";
        $user_in_array = populate_user_all_hier_array($current_user_id);
        set_register_value('user_in_array', $current_user, $user_in_array);
    }
    else
        $log->debug("Already present user in array");

    return $user_in_array;
}

/*
 *  Purpose : Recursively populate all hierarchy
 *  [Created By Yogesh Patil ] Aug 4 2010
 */

function populate_user_all_hier_array($user_id) {
    global $log;
    $db = & PearDatabase::getInstance();
    $query .= "SELECT DISTINCT a.id,a.user_name from users a INNER JOIN user_reports b ON b.parent_id = a.id AND
    b.child_id = '$user_id' AND a.deleted=0 AND b.deleted=0 INNER JOIN users c ON a.id = c.reports_to_id";
    $query .= " UNION ALL ";
    $query .= "SELECT DISTINCT a.id,a.user_name from users a INNER JOIN users b ON a.id = b.reports_to_id AND
    b.id = '$user_id' AND a.deleted=0 AND b.deleted=0 ";


    $result = $db->query($query, true, "Error filling in user array: ");
    $temp_result = Array();
    if ($result) {
        // Get the id and the name.
        while ($row = $db->fetchByAssoc($result)) {
            $temp_result[$row['user_name']] = $row['id'];
            $result_array = populate_user_all_hier_array($row['id']);
        }
        if ($result_array) {
            //$log->debug("populate_user_all_hier_array :Result Array".print_r($result_array,true));
            $hier_array = array_merge($result_array, $temp_result);
            //$log->debug("populate_user_all_hier_array Result".print_r($hier_array,true));
            return $hier_array;
        } else {
            // $log->debug("populate_user_all_hier_array :Temp Array".print_r($temp_result,true));
            return $temp_result;
        }
    }
}

function getLeadIdsByWhereClause($where) {
    $db = & PearDatabase::getInstance();
    $sql = "SELECT id FROM leads WHERE " . $where . " and (do_not_call!='on') and deleted=0";

    $result = $db->query($sql, true, "Error filling in include/Utils.php =>getLeadIdsByWhereClause");
    while ($row = $db->fetchByAssoc($result)) {
        $lead_ids_array[] = $row['id'];
    }
    if ($lead_ids_array) {
        return $lead_ids_array;
    } else {
        return FALSE;
    }
}

function getCityIdByRegionId($region_ids) {

    //$GLOBALS['log']->debug("*********Start getCityIdBYRegionId***********" . print_r($region_id_arr, true));
    if (is_array($region_ids) && count($region_ids) > 0) {
        $region_id_str = "'" . implode("','", $region_ids) . "'";
    } else {
        $region_id_str = "'" . $region_ids . "'";
    }
    //Query for select city id base on region id
    $query = "SELECT city_mast.id as city_id
              FROM state_mast INNER JOIN state_mast_cstm ON state_mast.id = state_mast_cstm.id_c 
                                              INNER JOIN region_mast ON state_mast_cstm.region_id_c = region_mast.id 
                                              INNER JOIN city_mast_cstm ON city_mast_cstm.state_id_c = state_mast.id
                                              INNER JOIN city_mast ON city_mast_cstm.id_c = city_mast.id
                                              ";
    $query .= " WHERE city_mast.deleted=0 AND
                                      state_mast.deleted=0 AND
                                      region_mast.deleted=0 AND";
    $query .= "  region_mast.id in (" . $region_id_str . ")";

    $GLOBALS['log']->debug("Save2 :: getCityIdByRegionId:  query=>" . $query);
    $result = $GLOBALS['db']->query($query, true, "Error filling in user array: ");
    while ($row = $GLOBALS['db']->fetchByAssoc($result)) {
        $city_id_arr[] = $row['city_id'];
    }//End While Loop
    return $city_id_arr;
}

function getCityIdByStateId($state_ids) {

    //$GLOBALS['log']->debug("*********Start getCityIdBYRegionId***********" . print_r($region_id_arr, true));
    if (is_array($state_ids) && count($state_ids) > 0) {
        $state_id_str = "'" . implode("','", $state_ids) . "'";
    } else {
        $state_id_str = "'" . $state_ids . "'";
    }
    //Query for select city id base on region id
    $query = "SELECT city_mast.id as city_id
              FROM state_mast INNER JOIN state_mast_cstm ON state_mast.id = state_mast_cstm.id_c 
                                              INNER JOIN region_mast ON state_mast_cstm.region_id_c = region_mast.id 
                                              INNER JOIN city_mast_cstm ON city_mast_cstm.state_id_c = state_mast.id
                                              INNER JOIN city_mast ON city_mast_cstm.id_c = city_mast.id
                                              ";
    $query .= " WHERE city_mast.deleted=0 AND
                                      state_mast.deleted=0 AND
                                      region_mast.deleted=0 AND";
    $query .= "  state_mast.id in (" . $state_id_str . ")";

    $GLOBALS['log']->debug("Save2 :: getCityIdByStateId:  query=>" . $query);
    $result = $GLOBALS['db']->query($query, true, "Error filling in user array: ");
    while ($row = $GLOBALS['db']->fetchByAssoc($result)) {
        $city_id_arr[] = $row['city_id'];
    }//End While Loop
    return $city_id_arr;
}

function getVendorIdByCityId($CityId) {
    $query = "SELECT team_id FROM team_city WHERE city_id='$CityId' AND deleted='0'";

    $result = $GLOBALS['db']->query($query, true, "Error filling in user array: ");
    while ($row = $GLOBALS['db']->fetchByAssoc($result)) {
        $team_id_arr[] = $row['team_id'];
    }//End While Loop
    return $team_id_arr;
}

function getVendorIdByZoneId($ZoneId) {
    $query = "SELECT team_id FROM team_region WHERE region_id='$ZoneId' AND deleted='0'";
    $result = $GLOBALS['db']->query($query, true, "Error filling in user array: ");
    while ($row = $GLOBALS['db']->fetchByAssoc($result)) {
        $team_id_arr[] = $row['team_id'];
    }//End While Loop
    return $team_id_arr;
}

function getVendorIdByProductId($ProductId) {
    $query = "SELECT team_id FROM team_brand WHERE brand_id='$ProductId' AND deleted='0'";
    $result = $GLOBALS['db']->query($query, true, "Error filling in user array: ");
    while ($row = $GLOBALS['db']->fetchByAssoc($result)) {
        $team_id_arr[] = $row['team_id'];
    }//End While Loop
    return $team_id_arr;
}

function getVendorIdByStateId($StateId) {
    $query = "SELECT team_id FROM team_state WHERE state_id='$StateId' AND deleted='0'";
    $result = $GLOBALS['db']->query($query, true, "Error filling in user array: ");
    while ($row = $GLOBALS['db']->fetchByAssoc($result)) {
        $team_id_arr[] = $row['team_id'];
    }//End While Loop
    return $team_id_arr;
}

function getVendorIdByLanguageId($LanguageId) {
    $query = "SELECT team_id FROM team_language WHERE language_id='$LanguageId' AND deleted='0'";
    $result = $GLOBALS['db']->query($query, true, "Error filling in user array: ");
    while ($row = $GLOBALS['db']->fetchByAssoc($result)) {
        $team_id_arr[] = $row['team_id'];
    }//End While Loop
    return $team_id_arr;
}

function getVendorIdByLevelId($LanguageId) {
    $query = "SELECT team_id FROM team_level WHERE level_id='$LanguageId' AND deleted='0'";
    $result = $GLOBALS['db']->query($query, true, "Error filling in user array: ");
    while ($row = $GLOBALS['db']->fetchByAssoc($result)) {
        $team_id_arr[] = $row['team_id'];
    }//End While Loop
    return $team_id_arr;
}

function getVendorIdByExperienceId($ExperienceId) {
    $query = "SELECT team_id FROM team_experience WHERE experience_id='$ExperienceId' AND deleted='0'";
    $result = $GLOBALS['db']->query($query, true, "Error filling in user array: ");
    while ($row = $GLOBALS['db']->fetchByAssoc($result)) {
        $team_id_arr[] = $row['team_id'];
    }//End While Loop
    return $team_id_arr;
}

// Added By Yogesh
function get_level_array($add_blank = true) {
    global $log;
    $array = get_register_value('get_level_array', 'get_level_array' . $add_blank);
    if (!$array) {
        $temp_result = Array();
        // Including deleted records for now.
        $query = "SELECT id , name from level_mast where deleted=0";
        $db = & PearDatabase::getInstance();
        $result = $db->query($query, true, "Error filling in user array: ");
        while ($row = $db->fetchByAssoc($result)) {
            $temp_result[$row['id']] = $row['name'];
        }

        if ($add_blank) {
            $temp_result[''] = 'None';
        }

        $GLOBALS['log']->debug("Implode :" . implode("/", $temp_result));
        $array = $temp_result;
        set_register_value('get_level_array', 'get_level_array' . $add_blank, $temp_result);
    }

    return $array;
}

//Added By Yogesh
// Return date format : yyyy-mm-dd
function getSQLDate2($date_str) {
    if (preg_match('/^(\d{1,2})-(\d{1,2})-(\d{4})$/', $date_str, $match)) {
        if (strlen($match[2]) == 1) {
            $match[2] = "0" . $match[2];
        }
        if (strlen($match[1]) == 1) {
            $match[1] = "0" . $match[1];
        }
        return "{$match[3]}-{$match[2]}-{$match[1]}";
    } else if (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/', $date_str, $match)) {
        if (strlen($match[2]) == 1) {
            $match[2] = "0" . $match[2];
        }
        if (strlen($match[1]) == 1) {
            $match[1] = "0" . $match[1];
        }
        return "{$match[3]}-{$match[2]}-{$match[1]}";
    } else if (preg_match('/^(\d{1,2}).(\d{1,2}).(\d{4})$/', $date_str, $match)) {
        if (strlen($match[2]) == 1) {
            $match[2] = "0" . $match[2];
        }
        if (strlen($match[1]) == 1) {
            $match[1] = "0" . $match[1];
        }
        return "{$match[3]}-{$match[2]}-{$match[1]}";
    }
    else {
        return "";
    }
}

function getExperienceMinMaxById($exp_id) {
    $query = "SELECT exp_min,exp_max FROM experience_mast WHERE id='$exp_id' AND deleted='0'";
    $result = $GLOBALS['db']->query($query, true, "Error filling in user array: ");
    while ($row = $GLOBALS['db']->fetchByAssoc($result)) {
        $exp_arr['min'] = $row['exp_min'];
        $exp_arr['max'] = $row['exp_max'];
    }//End While Loop
    return $exp_arr;
}

//Added By Pankaj

function get_tokenid() {
    $microTime = microtime();
    list($a_dec, $a_sec) = explode(" ", $microTime);

    $dec_hex = sprintf("%x", $a_dec * 1000000);
    $sec_hex = sprintf("%x", $a_sec);

    ensure_length($dec_hex, 2);
    ensure_length($sec_hex, 2);

    $guid = "";
    $guid .= $dec_hex;
    $guid .= create_guid_section(3);
    //$guid .= '-';
    $guid .= create_guid_section(2);
    //$guid .= '-';
    $guid .= create_guid_section(3);
//    $guid .= '-';
//    $guid .= create_guid_section(4);
//    $guid .= '-';
//    $guid .= $sec_hex;
//    $guid .= create_guid_section(6);

    return $guid;
}

function getCampaignIdByLeasId($LeadId) {
    $query = "SELECT campaign_id FROM campaigns_leads WHERE lead_id='$LeadId' AND deleted='0'";
    $result = $GLOBALS['db']->query($query, true, "Error filling in user array: ");
    while ($row = $GLOBALS['db']->fetchByAssoc($result)) {
        $campaign_id_arr[] = $row['campaign_id'];
    }//End While Loop
    return $campaign_id_arr;
}

function getMyVendorTeamFromClause($bean, $bean_id, $bean_name, $module_name, $sub_module_name, $field_defs, $params) {
    global $current_user;
    $db = & PearDatabase::getInstance();

    $team_member_id = is_team_member($current_user->id);
    if ($team_member_id) {
        $vendor_id = is_vendor($team_member_id);
    } else {
        $vendor_id = is_vendor($current_user->id);
    }

    //echo "<pre>";print_r($custom_join);
    if ((!isset($params['include_custom_fields']) || $params['include_custom_fields']) && isset($bean->custom_fields)) {
        if (!$vendor_id) {
            return ($module_name . "_cstm.assigned_team_id_c is not null and " . $module_name . "_cstm.assigned_team_id_c!='None'");
        } else {
            return $module_name . "_cstm.assigned_team_id_c IN ('$vendor_id')";
        }
    }


    /* $query .= "
      SELECT
      DISTINCT $module_name.id
      FROM
      $module_name left join ".$module_name."_cstm on ".$module_name.".id=".$module_name."_cstm.id_c
      WHERE
      $module_name.deleted=0 and
      ".$module_name."_cstm.assigned_team_id_c='".$vendor_id."'";
      $result = $db->query($query, true, "Error filling in user array: ");
      if($result) {
      // Get the id and the name.
      while($row = $db->fetchByAssoc($result)) {
      $in_entity_ids[] = $row['id'];
      }
      }

      if(isset($in_entity_ids)) {
      return $module_name.".id IN ('".implode("','",$in_entity_ids)."')";
      } */
    return false;
}

function is_vendor($id) {
    if (!$id) {
        return false;
    }

    $db = & PearDatabase::getInstance();
    $query .= "SELECT id from teams where id='" . $id . "' and deleted=0";
    $result = $db->query($query, true, "Error filling in query: ");
    if ($result) {
        $row = $db->fetchByAssoc($result);
        if ($row['id']) {
            return $row['id'];
        }
    }
    return false;
}

/*
 * input : user id as id
 * return : team_id 
 * description: this is function check if user is a team member or not and
 * return team id if true
 */

function is_team_member($id) {
    if (!$id) {
        return false;
    }
    $db = & PearDatabase::getInstance();
    $query .= "SELECT team_id from team_membership where user_id='" . $id . "' and deleted=0";
    $result = $db->query($query, true, "Error filling in user array: ");
    if ($result) {
        $row = $db->fetchByAssoc($result);
        if ($row['team_id']) {
            return $row['team_id'];
        }
    }
    return false;
}

function getleadIdByCampaignId($campaign_id, $vendor_id, $call_status) {

    $query = "SELECT calls.parent_id as lead_id,
                     calls.status as status,
                     leads.login,
                     leads.first_name,
                     leads.last_name,
                     leads.phone_other,
                     leads.phone_mobile,
                     leads.experience,
                     level_mast.name as level,
                     leads.email1 as email,
                     leads.primary_address_street as address,
                     city_mast.name as city,
                     leads.gender
            FROM calls 
                INNER JOIN calls_cstm ON (calls.id = calls_cstm.id_c)
                INNER JOIN leads ON (leads.id = calls.parent_id)
                INNER JOIN city_mast ON (leads.primary_address_city = city_mast.id)
                INNER JOIN level_mast ON (leads.level = level_mast.id)
            WHERE calls.campaign_id='$campaign_id' 
                    AND calls.status='$call_status'
                    AND calls_cstm.assigned_team_id_c = '$vendor_id'
                    AND calls.deleted='0' 
                    AND leads.deleted='0'
                ";
    $result = $GLOBALS['db']->query($query, true, "Error filling in getCallDetails array: ");
    while ($row = $GLOBALS['db']->fetchByAssoc($result)) {
        $lead_arr[] = $row['lead_id'];
    }//End While Loop
    return $lead_arr;
}

function getLeadIdByLoginName($LoginName) {
    if (!$LoginName) {
        return false;
    }
    $db = & PearDatabase::getInstance();
    $query .= "SELECT id from leads where login='" . addslashes(trim($LoginName)) . "' and deleted=0";
    $result = $db->query($query, true, "Error filling in user array: ");
    if ($result) {
        $row = $db->fetchByAssoc($result);
        if ($row['id']) {
            return $row['id'];
        }
    }
    return false;
}

function getLeadIdByMobileNo($MobileNo) {
    if (!$MobileNo) {
        return false;
    }
    $db = & PearDatabase::getInstance();
    $query .= "SELECT id from leads where phone_mobile='" . (trim($MobileNo)) . "' and deleted=0";
    $result = $db->query($query, true, "Error filling in user array: ");
    if ($result) {
        $row = $db->fetchByAssoc($result);
        if ($row['id']) {
            return $row['id'];
        }
    }
    return false;
}

function getBrandIdByBrandName($BrandName) {
    if (!$BrandName) {
        return false;
    }
    $db = & PearDatabase::getInstance();
    $query .= "SELECT id from brands where name='" . addslashes(trim($BrandName)) . "' and deleted=0";
    $result = $db->query($query, true, "Error filling in user array: ");
    if ($result) {
        $row = $db->fetchByAssoc($result);
        if ($row['id']) {
            return $row['id'];
        }
    }
    return false;
}

function getProductIdByLeadId($lead_id) {
    $query = "SELECT brand_id FROM  lead_brand_sold  WHERE lead_id = '$lead_id' AND deleted='0' ";
    $result = $GLOBALS['db']->query($query, true, "Error filling in getCallDetails array: ");
    while ($row = $GLOBALS['db']->fetchByAssoc($result)) {
        $brand_id[] = $row['brand_id'];
    }//End While Loop
    return $brand_id;
}

function create_export_master(&$order_by, &$where, $custom_join = '', $table_name) {
    $query = "SELECT  $table_name.*, users.user_name";
    if ($custom_join) {
        $query .= $custom_join['select'];
    }
    $query .= " FROM $table_name ";
    $query .= " LEFT JOIN users ON $table_name.created_by=users.id ";
    if ($custom_join) {
        $query .= $custom_join['join'];
    }
    $where_auto = " $table_name.deleted=0 ";
    if ($where != "")
        $query .= "where ($where) AND " . $where_auto;
    else
        $query .= "where " . $where_auto;

    if (!empty($order_by))
        $query .= " ORDER BY $order_by";

    return $query;
}

function copy_uploaded_file($source_file,$desc_file){    
    if(!file_exists($source_file)) { return false;}
    
    if (copy($source_file, $desc_file)) {        
        unlink($source_file);
        return true;
    }    
}
?>
