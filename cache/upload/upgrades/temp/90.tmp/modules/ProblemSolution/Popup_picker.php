<?php
if(empty($GLOBALS['sugarEntry'])) die('Not A Valid Entry Point');
/**
 * Popup Picker
 */

// $Id: Popup_picker.php,v 1.10.10.1 2006/01/08 04:36:05 majed Exp $

global $theme;

require_once('modules/ProblemSolution/ProblemSolution.php');
require_once('themes/'.$theme.'/layout_utils.php');
require_once('log4php/LoggerManager.php');
require_once('XTemplate/xtpl.php');
require_once('include/ListView/ListView.php');

$image_path = 'themes/'.$theme.'/images/';

class Popup_Picker
{
	function Popup_Picker()
	{
		;
	}
	
	function _get_where_clause()
	{
		$where = '';
		if(isset($_REQUEST['query']))
		{
			$where_clauses = array();
			append_where_clause($where_clauses, "name", "problem_solution.name");
			append_where_clause($where_clauses, "parent_id", "problem_solution.parent_id");
		
			$where = generate_where_statement($where_clauses);
		}
		
		return $where;
	}
	
	function process_page()
	{
		global $theme;
		global $mod_strings;
		global $app_strings;
		global $currentModule;
		
		$output_html = '';
		$where = '';
		
		$where = $this->_get_where_clause();
		
		$image_path = 'themes/'.$theme.'/images/';
		
		$name = empty($_REQUEST['name']) ? '' : $_REQUEST['name'];
		$request_data = empty($_REQUEST['request_data']) ? '' : $_REQUEST['request_data'];
		$hide_clear_button = empty($_REQUEST['hide_clear_button']) ? false : true;

		$button  = "<form action='index.php' method='post' name='form' id='form'>\n";
		if(!$hide_clear_button)
		{
			$button .= "<input type='button' name='button' class='button' onclick=\"send_back('','');\" title='"
				.$app_strings['LBL_CLEAR_BUTTON_TITLE']."' accesskey='"
				.$app_strings['LBL_CLEAR_BUTTON_KEY']."' value='  "
				.$app_strings['LBL_CLEAR_BUTTON_LABEL']."  ' />\n";
		}
		$button .= "<input type='submit' name='button' class='button' onclick=\"window.close();\" title='"
			.$app_strings['LBL_CANCEL_BUTTON_TITLE']."' accesskey='"
			.$app_strings['LBL_CANCEL_BUTTON_KEY']."' value='  "
			.$app_strings['LBL_CANCEL_BUTTON_LABEL']."  ' />\n";
		$button .= "</form>\n";

		$form = new XTemplate('modules/ProblemSolution/Popup_picker.html');
		$form->assign('MOD', $mod_strings);
		$form->assign('APP', $app_strings);
		$form->assign('THEME', $theme);
		$form->assign('MODULE_NAME', $currentModule);
		$form->assign('NAME', $name);
		$form->assign('request_data', $request_data);
		
		ob_start();
		insert_popup_header($theme);
		$output_html .= ob_get_contents();
		ob_end_clean();
		
		$output_html .= get_form_header($mod_strings['LBL_SEARCH_FORM_TITLE'], '', false);
		
		$form->parse('main.SearchHeader');
		$output_html .= $form->text('main.SearchHeader');
		
		$output_html .= get_form_footer();
		
		// Reset the sections that are already in the page so that they do not print again later.
		$form->reset('main.SearchHeader');

		// create the listview
		$seed_bean = new Solution();
		$ListView = new ListView();
		$ListView->show_export_button = false;
		$ListView->process_for_popups = true;
		$ListView->setXTemplate($form);
		$ListView->setHeaderTitle($mod_strings['LBL_LIST_FORM_TITLE']);
		$ListView->setHeaderText($button);
		$ListView->setQuery($where, '', 'name', 'PROBLEM_SOLUTION');
		$ListView->setModStrings($mod_strings);

		ob_start();
		$ListView->processListView($seed_bean, 'main', 'PROBLEM_SOLUTION');
		$output_html .= ob_get_contents();
		ob_end_clean();
				
		$output_html .= get_form_footer();
		$output_html .= insert_popup_footer();
		return $output_html;
	}
} // end of class Popup_Picker
?>
