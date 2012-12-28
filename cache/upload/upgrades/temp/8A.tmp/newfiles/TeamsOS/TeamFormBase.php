<?PHP

require_once('include/JSON.php');
require_once('include/utils.php');

class TeamFormBase {

function create_team_array(&$bean, $event, $arguments) {
	require_once('modules/TeamsOS/TeamOS.php');
	$seedTeam = new TeamOS();
	global $current_user;
	$_SESSION['team_id'] = $seedTeam->retrieve_team_id($current_user->id);
	if($_REQUEST['action']!='EditView') {
		$bean->assigned_team_id_c=$seedTeam->get_team_name($bean->assigned_team_id_c);
	}
}

function Security_Check(&$bean, $event, $arguments) {
	require_once('modules/TeamsOS/TeamOS.php');
	$seedTeam = new TeamOS();
	global $current_user;
	global $app_strings;
	$my_teams=join(",",$_SESSION['team_id']);
//	echo "Record: " . $_REQUEST['record'] . "<br>";
//	echo "Assigned Team: " . $bean->assigned_team_id_c . "<br>";
//	echo "Session: " . $my_teams . "<br>";
//	die();
	if(!empty($_REQUEST['record']) && !empty($bean->assigned_team_id_c)) {
		if(stristr($my_teams,$bean->assigned_team_id_c)===false && !is_admin($current_user)) {
			sugar_die($app_strings['ERROR_NO_RECORD']);
		}
	}
}

//This function is run by the before_save logic hook installed
//by the pre_install.php script
function add_team_if_needed(&$bean, $event, $arguments)
{
	include_once('modules/TeamsOS/TeamOS.php');
	$teams_focus = new TeamOS;
	global $current_user;
	//if the 'module' does not equal the 'return_module' then this record is being added
	//from a subpanel in a different module and does not have a teams dropdown
	if($_REQUEST['module'] != $_REQUEST['return_module'] &&
	   empty($_REQUEST['assigned_team_id_c']) &&
	   !empty($current_user->default_team_id_c)) {
	   		$GLOBALS['log']->debug("Record saved from Subpanel, team set to ".$current_user->default_team_id_c);
			$bean->assigned_team_id_c = $current_user->default_team_id_c;
	}
	if($_REQUEST['module']=="Users") {
		if(isset($_POST['show_all_teams_c']) &&
		  ($_POST['show_all_teams_c'] == 'on' || $_POST['show_all_teams_c'] == '1')) {
		  		$bean->show_all_teams_c = 1;
		} elseif(empty($_POST['show_all_teams_c'])) {
				$bean->show_all_teams_c = 0;
		}

		if(isset($_POST['create_team'])) {
			//'personal' teams begin with an underline.
			$id = "_" . $_POST['sugar_user_name'];
			if(!$teams_focus->isMember($bean->id,$id)) {
				$teams_focus->name=$id;

				if(isset($_POST['private'])) {
					$teams_focus->private=1;
				} else {
					$teams_focus->private=0;
				}
				$team_id = $teams_focus->save();

				$guid=create_guid();

				//join the team we just created.  There is probably a better way, I'll
				//look into that in the next version
				$sql = "INSERT INTO team_membership (id, team_id, user_id, date_modified, deleted)
				        VALUES('$guid','$team_id','$bean->id','" . date('Y-m-d h:j:s') . "',0)";
				$teams_focus->db->query($sql);

				//If no default team is selected then select the new
				//'personal' team.
				if($_REQUEST['default_team_id_c']=="") {
					$bean->default_team_id_c=$teams_focus->id;
				}
			}
		}

		//check to see if the default team that was chosen has this user as a member
		//If not add this user as a member of that team
		if(!$teams_focus->isMember($bean->id,$bean->default_team_id_c)) {
			$sql = "INSERT INTO team_membership (id, team_id, user_id, date_modified, deleted)
			        VALUES('$guid','$bean->default_team_id_c','$bean->id','" . date('Y-m-d h:j:s') . "',0)";
			$teams_focus->db->query($sql);
			$GLOBALS['log']->debug("user added to default team: " . $teams_focus->get_team_name($bean->default_team_id_c));
		}
	}
}

function getFormBody($prefix, $mod='', $formname=''){
	global $mod_strings;
	global $app_strings;
	$temp_strings = $mod_strings;
	if(!empty($mod)){
		global $current_language;
		$mod_strings = return_module_language($current_language, $mod);
	}
    global $mod_strings;
	global $app_strings;
	global $current_user;
	$lbl_name = $mod_strings['LBL_NAME'];
	$lbl_private = $mod_strings['LBL_PRIVATE'];
	$lbl_required_symbol = $app_strings['LBL_REQUIRED_SYMBOL'];
	$form="";

	$user_id = $current_user->id;
	$form .= "<p>$lbl_name <span class='required'>$lbl_required_symbol</span><br>
			<input name='${prefix}name' type='text' value=''><br>";
	$form .= "<p>$lbl_private<br>
			<input name='${prefix}private' type='checkbox' value='1'><br></p>";
	require_once('include/javascript/javascript.php');
	require_once('modules/TeamsOS/TeamOS.php');
	$javascript = new javascript();
	$javascript->setFormName($formname);
	$javascript->setSugarBean(new TeamOS());
	$javascript->addField('name','true',$prefix);
	$javascript->addRequiredFields($prefix);
	$form .=$javascript->getScript();
	$mod_strings = $temp_strings;
	return $form;

}

function getForm($prefix, $mod='TeamsOS') {

	if(!ACLController::checkAccess('TeamsOS', 'edit', true, '')) {
		return '';
	}
	global $app_strings;
	if(!empty($mod)){
		global $current_language;
		$mod_strings = return_module_language($current_language, $mod);
	} else {
		global $mod_strings;
	}
	$lbl_save_button_title = $app_strings['LBL_SAVE_BUTTON_TITLE'];
	$lbl_save_button_key = $app_strings['LBL_SAVE_BUTTON_KEY'];
	$lbl_save_button_label = $app_strings['LBL_SAVE_BUTTON_LABEL'];


	$the_form = get_left_form_header($mod_strings['LBL_NEW_FORM_TITLE']);
	$the_form .= <<<EOQ
			<form name="${prefix}TeamsSave" onSubmit="return check_form('${prefix}TeamsSave');" method="POST" action="index.php">
				<input type="hidden" name="${prefix}module" value="TeamsOS">
				<input type="hidden" name="${prefix}action" value="Save">
EOQ;
	$the_form .= $this->getFormBody($prefix, $mod, $prefix."TeamsSave");
	$the_form .= <<<EOQ
			<p><input title="$lbl_save_button_title" accessKey="$lbl_save_button_key" class="button" type="submit" name="button" value="  $lbl_save_button_label  " ></p>
			</form>

EOQ;
	$the_form .= get_left_form_footer();
	$the_form .= get_validate_record_js();


	return $the_form;
}

function handleSave($prefix,$redirect=true, $useRequired=false){
	require_once('modules/TeamsOS/TeamOS.php');
	require_once('log4php/LoggerManager.php');
	require_once('include/formbase.php');
	require_once('config.php');

	global $sugar_config;
	global $app_list_strings, $current_user;

	$focus = new TeamOS();
	if($useRequired &&  !checkRequired($prefix, array_keys($focus->required_fields))){
		return null;
	}
	$focus = populateFromPost($prefix, $focus);

	if(!array_key_exists($focus->name,$app_list_strings['teams_array'])) {
		if( !ACLController::checkAccess($focus->module_dir, 'edit', $focus->isOwner($current_user->id))){
			ACLController::displayNoAccess(true);
			sugar_cleanup(true);
		}

		if(!$focus->ACLAccess('Save')){
			ACLController::displayNoAccess(true);
			sugar_cleanup(true);
		}

		if(!isset($_REQUEST['private'])) {
			$focus->private=0;
		} else {
			$focus->private=1;
		}

		$return_id = $focus->save();

		$GLOBALS['log']->debug("Saved team record with id of ".$return_id);

		$this->update_dropdown();
	} else {
		$return_id = $focus->name;
	}
	if($redirect){
		$this->handleRedirect($return_id);
	}else{
		return $focus;
	}
}

function handleRedirect($return_id){
	if(isset($_POST['return_module']) && $_POST['return_module'] != "") $return_module = $_POST['return_module'];
	else $return_module = "TeamsOS";
	if(isset($_POST['return_action']) && $_POST['return_action'] != "") $return_action = $_POST['return_action'];
	else $return_action = "index";
	header("Location: index.php?action=$return_action&module=$return_module&record=$return_id");
}

function update_dropdown() {
	require_once('modules/TeamsOS/TeamOS.php');
	require_once('include/utils.php');
	require_once('cache/custom_fields/custom_fields_def.php');
	require_once('modules/Administration/Common.php');

	global $current_user;
	global $current_language;
	global $mod_strings;

	$focus=new TeamOS();
	$sql="SELECT * FROM " . $focus->table_name . " WHERE deleted=0 ORDER BY name";
	$result=$focus->db->query($sql,true);

	$dropdown_array=array($mod_strings['LBL_NO_TEAM']=>$mod_strings['LBL_NO_TEAM']);
	$dropdown_type = $focus->table_name . "_array";

	while($hash=$focus->db->fetchByAssoc($result)) {
		$id=$hash['id'];
		$dropdown_array[$id]=$hash['name'];
	}

	$contents = return_custom_app_list_strings_file_contents($current_language);

	$new_contents = replace_or_add_dropdown_type($dropdown_type, $dropdown_array, $contents);

    save_custom_app_list_strings_contents($new_contents, $current_language);

}

}
?>
