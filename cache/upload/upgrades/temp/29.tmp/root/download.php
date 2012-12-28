<?
if(!defined('sugarEntry'))define('sugarEntry', true);
$GLOBALS['sugarEntry'] = true;

if(isset($_REQUEST['PHPSESSID'])) {
	session_id($_REQUEST['PHPSESSID']);
}
require_once('config.php');
if (is_file('config_override.php')) {
	require_once('config_override.php');
}
if (!empty($sugar_config) && !empty($sugar_config['session_dir'])) {
	session_save_path($sugar_config['session_dir']);
}
session_start();

require_once('include/logging.php');
$GLOBALS['log'] = new SimpleLog();
$log =& new SimpleLog();

require_once('modules/Users/User.php');
$current_user = new User();
if(isset($_SESSION['authenticated_user_id'])) {
	$result = $current_user->retrieve($_SESSION['authenticated_user_id']);
	if($result != null) {
		$action = $_REQUEST['action'];
		$module = $_REQUEST['module'];
		require_once('modules/'.$module.'/'.$action.'.php');
	}
}

?>