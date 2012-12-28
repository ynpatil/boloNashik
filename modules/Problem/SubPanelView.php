<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

/********************************************************************************
 * SubPanel deinition for Problem object
 *******************************************************************************/

require_once('XTemplate/xtpl.php');
require_once("data/Tracker.php");
require_once('include/ListView/ListView.php');

global $app_strings;
//we don't want the parent module's string file, but rather the string file specifc to this subpanel
global $current_language;
$current_module_strings = return_module_language($current_language, 'Problem');

global $currentModule;
global $theme;
global $focus;
global $action;

$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

// focus_list is the means of passing data to a SubPanelView.
global $focus_list;

$button  = "<form action='index.php' method='post' name='form' id='form'>\n";
$button .= "<input type='hidden' name='module' value='Problem'>\n";
$button .= "<input type='hidden' name='relation_id' value='$focus->id'>\n";
$button .= "<input type='hidden' name='relation_type' value='$currentModule'>\n";
$button .= "<input type='hidden' name='return_module' value='".$currentModule."'>\n";
$button .= "<input type='hidden' name='return_action' value='".$action."'>\n";
$button .= "<input type='hidden' name='return_id' value='".$focus->id."'>\n";
$button .= "<input type='hidden' name='action'>\n";
if (!empty($focus->object_name) && $focus->object_name == 'Opportunity') {
 	$button .= "<input type='hidden' name='account_id' value='$focus->account_id'>\n";
 	$button .= "<input type='hidden' name='opportunity_name' value='$focus->name'>\n";
}
$button .= "<input title='"
	. $app_strings['LBL_NEW_BUTTON_TITLE']
	. "' accessyKey='".$app_strings['LBL_NEW_BUTTON_KEY']
	. "' class='button' onclick=\"this.form.action.value='EditView'\" type='submit' name='New' value='  "
	. $app_strings['LBL_NEW_BUTTON_LABEL']."  '>\n";

$button .= "</form>\n";

$ListView = new ListView();
$ListView->initNewXTemplate( 'modules/Problem/SubPanelView.html',$current_module_strings);
$ListView->xTemplateAssign("EDIT_INLINE_PNG",
	get_image($image_path.'edit_inline','align="absmiddle" alt="'.$app_strings['LNK_EDIT'].'" border="0"'));
$ListView->xTemplateAssign("RETURN_URL",
	"&return_module=".$currentModule."&return_action=DetailView&return_id=".$focus->id);
$ListView->setHeaderTitle($current_module_strings['LBL_PROBLEM_SUBPANEL_TITLE'] );

$header_text = '';
if(is_admin($current_user)
	&& $_REQUEST['module'] != 'DynamicLayout'
	&& !empty($_SESSION['editinplace']))
{
	$header_text = " <a href='index.php?action=index&module=DynamicLayout&from_action="
		.$_REQUEST['action']
		."&from_module=".$_REQUEST['module'] ."&record="
		.$_REQUEST['record']. "'>"
		.get_image($image_path."EditLayout", "border='0' alt='Edit Layout' align='bottom'")."</a>";
}
$ListView->setHeaderTitle($current_module_strings['LBL_PROBLEM_SUBPANEL_TITLE'] . $header_text);
$ListView->processListView($focus_list, "main", "PROBLEM");

?>
