<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * Upgrade the mod_strings format
 *
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
 */

// $Id: clear_chart_cache.php,v 1.6 2006/06/06 17:57:55 majed Exp $

global $sugar_config;

print( "Finding files...<br>" );

$search_dir='cache/';
if (!empty($sugar_config['cache_dir'])) {
	$search_dir=$sugar_config['cache_dir'];
}
  
$all_src_files  = findAllFiles($search_dir.'/xml', array() );

print( "Deleting chart data cache files...<br>" );
foreach( $all_src_files as $src_file ){
	if (preg_match('/\.xml$/',$src_file))
	{
   		print  "deleting: $src_file<BR>" ;
		unlink( "$src_file" );
	}
}
 
include('modules/Versions/ExpectedVersions.php');
require_once('modules/Versions/Version.php');

global $expect_versions;

if (isset($expect_versions['Chart Data Cache'])) {
	$version = new Version();
	$version->retrieve_by_string_fields(array('name'=>'Chart Data Cache'));

	$version->name = $expect_versions['Chart Data Cache']['name'];
	$version->file_version = $expect_versions['Chart Data Cache']['file_version'];
	$version->db_version = $expect_versions['Chart Data Cache']['db_version'];
	$version->save();
}

echo "\n--- DONE ---<br />\n";
?>
