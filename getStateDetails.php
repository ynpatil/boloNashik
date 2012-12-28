<?php
if(!defined('sugarEntry'))define('sugarEntry', true);
$GLOBALS['sugarEntry'] = true;
require_once('include/entryPoint.php');

header('Content-Type: text/xml; charset=ISO-8859-1');
//echo "Om 2";
$id = $_POST['state_id'];
if(!isset($id))
$id = $_GET['state_id'];
$xml = '<?xml version="1.0" encoding="ISO-8859-1"?>';
$xml .="<root>";
if(isset($id))
{
	require_once("include/utils.php");
	require_once('config.php');
	require_once('sugar_version.php');

	global $sugar_config;

	// check for old (non-array) config format.
	if(empty($sugar_config) && isset($dbconfig['db_host_name']))
	{
	   make_sugar_config($sugar_config);
	}

	if (isset($simple_log))
	{
		$log = new SimpleLog();
	}
	else
	{
		$log =& LoggerManager::getLogger('index');
	}

	$array = get_state_details($id);

	if(isset($array['country_id']))
	$xml.="<country id='".$array['country_id']."' description='".$array['country_description']."'/>";
}

$xml.="</root>";
echo $xml;
?>