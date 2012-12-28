<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * Popup Picker
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

// $Id: Popup_picker.php,v 1.28 2006/06/06 17:57:56 majed Exp $

global $theme;

require_once('modules/Contacts/Contact.php');
require_once('modules/Contacts/ContactFormBase.php');
require_once('themes/'.$theme.'/layout_utils.php');

require_once('XTemplate/xtpl.php');
require_once('include/ListView/ListView.php');

$image_path = 'themes/'.$theme.'/images/';

class Popup_Picker
{
	
	
	/*
	 * 
	 */
	function Popup_Picker()
	{
		;
	}
	
	/*
	 * 
	 */
	function _get_where_clause()
	{
		$where = '';
		if(isset($_REQUEST['query']))
		{
			$where_clauses = array();

			append_where_clause($where_clauses, "first_name", "contacts.first_name");
			append_where_clause($where_clauses, "last_name", "contacts.last_name");
			append_where_clause($where_clauses, "account_name", "accounts.name");
			append_where_clause($where_clauses, "account_id", "accounts.id");
		
			$where = generate_where_statement($where_clauses);
		}
		
		return $where;
	}
		
	/**
	 *
	 */
	function process_page_for_email()
	{
		global $theme;
		global $mod_strings;
		global $app_strings;
		global $currentModule;
		global $sugar_version, $sugar_config;
		
		$output_html = '';
		$where = '';
		
		$where = $this->_get_where_clause();
		
		$image_path = 'themes/'.$theme.'/images/';
		
		$formBase = new ContactFormBase();
		if(isset($_REQUEST['doAction']) && $_REQUEST['doAction'] == 'save')
		{
			$formBase->handleSave('', false, true);
		}

		$first_name = empty($_REQUEST['first_name']) ? '' : $_REQUEST['first_name'];
		$last_name = empty($_REQUEST['last_name']) ? '' : $_REQUEST['last_name'];
		$account_name = empty($_REQUEST['account_name']) ? '' : $_REQUEST['account_name'];
		$request_data = empty($_REQUEST['request_data']) ? '' : $_REQUEST['request_data'];
		$hide_clear_button = empty($_REQUEST['hide_clear_button']) ? false : true;
		$lbl_save_button_title = $app_strings['LBL_SAVE_BUTTON_TITLE'];
		$lbl_save_button_key = $app_strings['LBL_SAVE_BUTTON_KEY'];
		$lbl_save_button_label = $app_strings['LBL_SAVE_BUTTON_LABEL'];
		
		// TODO: cleanup the construction of $addform
		$formbody = $formBase->getFormBody('','','EmailEditView');
		$addform = '<table><tr><td nowrap="nowrap" valign="top">'
			.str_replace('<br>', '</td><td nowrap="nowrap" valign="top">&nbsp;', $formbody)
			. '</td></tr></table>'
			. '<input type="hidden" name="action" value="Popup" />';
		$formSave = <<<EOQ
		<input type="submit" name="button" class="button" title="$lbl_save_button_title" accesskey="$lbl_save_button_key" value="  $lbl_save_button_label  " />
		<input type="button" name="button" class="button" title="{$app_strings['LBL_CANCEL_BUTTON_TITLE']}" accesskey="{$app_strings['LBL_CANCEL_BUTTON_KEY']}" value="{$app_strings['LBL_CANCEL_BUTTON_LABEL']}" onclick="toggleDisplay('addform');" />
EOQ;
		$createContact = <<<EOQ
		<input type="button" name="showAdd" class="button" value="{$mod_strings['LNK_NEW_CONTACT']}" onclick="toggleDisplay('addform');" />
EOQ;
		$addformheader = get_form_header($mod_strings['LNK_NEW_CONTACT'], $formSave, false);
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

		$form = new XTemplate('modules/Contacts/Email_picker.html');
		$form->assign('MOD', $mod_strings);
		$form->assign('APP', $app_strings);
		$form->assign('CREATECONTACT', $createContact);
		$form->assign('ADDFORMHEADER', $addformheader);
		$form->assign('ADDFORM', $addform);
		$form->assign('THEME', $theme);
		$form->assign('MODULE_NAME', $currentModule);
		$form->assign('FIRST_NAME', $first_name);
		$form->assign('LAST_NAME', $last_name);
		$form->assign('ACCOUNT_NAME', $account_name);
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
		$seed_bean = new Contact();
		$ListView = new ListView();
		$ListView->show_export_button = false;
		$ListView->process_for_popups = true;
		$ListView->setXTemplate($form);
		$ListView->setHeaderTitle($mod_strings['LBL_LIST_FORM_TITLE']);
		$ListView->setHeaderText($button);
		$ListView->setQuery($where, '', '', 'CONTACT');
		$ListView->setModStrings($mod_strings);

		ob_start();
		$ListView->processListView($seed_bean, 'main', 'CONTACT');
		$output_html .= ob_get_contents();
		ob_end_clean();
				
		$output_html .= get_form_footer();
		$output_html .= insert_popup_footer();
		return $output_html;
	}
	
	function process_page_for_merge()
	{
		global $theme;
		global $mod_strings;
		global $app_strings;
		global $currentModule;
		global $sugar_version, $sugar_config;
		
		$output_html = '';
		$where = '';
		
		$where = $this->_get_where_clause();
		
		$image_path = 'themes/'.$theme.'/images/';
		
		$first_name = empty($_REQUEST['first_name']) ? '' : $_REQUEST['first_name'];
		$last_name = empty($_REQUEST['last_name']) ? '' : $_REQUEST['last_name'];
		$account_name = empty($_REQUEST['account_name']) ? '' : $_REQUEST['account_name'];
		$hide_clear_button = empty($_REQUEST['hide_clear_button']) ? false : true;
		$button  = "<form action='index.php' method='post' name='form' id='form'>\n";
		//START:FOR MULTI-SELECT
		$multi_select=false;
		if (!empty($_REQUEST['mode']) && strtoupper($_REQUEST['mode']) == 'MULTISELECT') {
			$multi_select=true;
			$button .= "<input type='button' name='button' class='button' onclick=\"send_back_selected('Contacts',document.MassUpdate,'mass[]','" .$app_strings['ERR_NOTHING_SELECTED']."');\" title='"
				.$app_strings['LBL_SELECT_BUTTON_TITLE']."' accesskey='"
				.$app_strings['LBL_SELECT_BUTTON_KEY']."' value='  "
				.$app_strings['LBL_SELECT_BUTTON_LABEL']."  ' />\n";
		}
		//END:FOR MULTI-SELECT
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

		$form = new XTemplate('modules/Contacts/Popup_picker.html');
		$form->assign('MOD', $mod_strings);
		$form->assign('APP', $app_strings);
		$form->assign('THEME', $theme);
		$form->assign('MODULE_NAME', $currentModule);
		$form->assign('FIRST_NAME', $first_name);
		$form->assign('LAST_NAME', $last_name);
		$form->assign('ACCOUNT_NAME', $account_name);
		$request_data = empty($_REQUEST['request_data']) ? '' : $_REQUEST['request_data'];
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
		$seed_bean = new Contact();
		$ListView = new ListView();
		$ListView->display_header_and_footer=false;
		$ListView->show_export_button = false;
		$ListView->process_for_popups = true;
		$ListView->setXTemplate($form);
		$ListView->multi_select_popup=$multi_select;
		if ($multi_select) $ListView->xTemplate->assign("TAG_TYPE","SPAN"); else  $ListView->xTemplate->assign("TAG_TYPE","A");
		$ListView->setHeaderTitle($mod_strings['LBL_LIST_FORM_TITLE']);
		$ListView->setQuery($where, '', 'contacts.last_name, contacts.first_name', 'CONTACT');
		$ListView->setModStrings($mod_strings);

		ob_start();
		$output_html .= get_form_header($mod_strings['LBL_LIST_FORM_TITLE'], $button, false);
				//BEGIN ATHENA CUSTOMIZATION - rsmith
			$query = $_REQUEST['select'].' WHERE '.$_REQUEST['where']."'".$_REQUEST['id']."'";
			
			//$response = $seed_bean->process_list_query($_REQUEST['select'], 0, -1, -1, $_REQUEST['where']."'".$_REQUEST['id']."'");
			
			$result = $seed_bean->db->query($query,true,"Error retrieving $seed_bean->object_name list: ");

			$list = Array();
			if(empty($rows_found))
			{
  				$rows_found =  $seed_bean->db->getRowCount($result);
			}
			
			$row_offset = 0;
			global $sugar_config;
			$max_per_page = $sugar_config['list_max_entries_per_page'];

				while(($row = $seed_bean->db->fetchByAssoc($result)) != null)
			    	{
						$seed_bean = new Contact();
						foreach($seed_bean->field_defs as $field=>$value)
						{
							if (isset($row[$field])) 
							{
								$seed_bean->$field = $row[$field];
							}
							else if (isset($row[$seed_bean->table_name .'.'.$field])) 
							{
								$seed_bean->$field = $row[$seed_bean->table_name .'.'.$field];
							}
							else
							{
								$seed_bean->$field = "";
							}	
						}
						$seed_bean->fill_in_additional_list_fields();

						$list[] = $seed_bean;
			    	}
					
			$ListView->processListViewTwo($list, 'main', 'CONTACT');

		//END ATHENA CUSTOMIZATION - rsmith
		$output_html .= ob_get_contents();
		ob_end_clean();
				
		$output_html .= get_form_footer();
		$output_html .= insert_popup_footer();
		return $output_html;		
	}

	/**
	 *
	 */
	function process_page()
	{
		global $theme;
		global $mod_strings;
		global $app_strings;
		global $currentModule;
		global $sugar_version, $sugar_config;
		
		$output_html = '';
		$where = '';
		
		$where = $this->_get_where_clause();
		
		$image_path = 'themes/'.$theme.'/images/';
		
		$first_name = empty($_REQUEST['first_name']) ? '' : $_REQUEST['first_name'];
		$last_name = empty($_REQUEST['last_name']) ? '' : $_REQUEST['last_name'];
		$account_name = empty($_REQUEST['account_name']) ? '' : $_REQUEST['account_name'];
		$hide_clear_button = empty($_REQUEST['hide_clear_button']) ? false : true;
		$button  = "<form action='index.php' method='post' name='form' id='form'>\n";
		//START:FOR MULTI-SELECT
		$multi_select=false;
		if (!empty($_REQUEST['mode']) && strtoupper($_REQUEST['mode']) == 'MULTISELECT') {
			$multi_select=true;
			$button .= "<input type='button' name='button' class='button' onclick=\"send_back_selected('Contacts',document.MassUpdate,'mass[]','" .$app_strings['ERR_NOTHING_SELECTED']."');\" title='"
				.$app_strings['LBL_SELECT_BUTTON_TITLE']."' accesskey='"
				.$app_strings['LBL_SELECT_BUTTON_KEY']."' value='  "
				.$app_strings['LBL_SELECT_BUTTON_LABEL']."  ' />\n";
		}
		//END:FOR MULTI-SELECT
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

		$form = new XTemplate('modules/Contacts/Popup_picker.html');
		$form->assign('MOD', $mod_strings);
		$form->assign('APP', $app_strings);
		$form->assign('THEME', $theme);
		$form->assign('MODULE_NAME', $currentModule);
		$form->assign('FIRST_NAME', $first_name);
		$form->assign('LAST_NAME', $last_name);
		$form->assign('ACCOUNT_NAME', $account_name);
		
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
		$seed_bean = new Contact();
		$ListView = new ListView();
		$ListView->display_header_and_footer=false;
		$ListView->show_export_button = false;
		$ListView->process_for_popups = true;
		$ListView->setXTemplate($form);
		$ListView->multi_select_popup=$multi_select;
		if ($multi_select) $ListView->xTemplate->assign("TAG_TYPE","SPAN"); else  $ListView->xTemplate->assign("TAG_TYPE","A");
		$ListView->setHeaderTitle($mod_strings['LBL_LIST_FORM_TITLE']);
		$ListView->setQuery($where, '', 'contacts.last_name, contacts.first_name', 'CONTACT');
		$ListView->setModStrings($mod_strings);

		ob_start();
		$output_html .= get_form_header($mod_strings['LBL_LIST_FORM_TITLE'], $button, false);
		$ListView->processListView($seed_bean, 'main', 'CONTACT');
		$output_html .= ob_get_contents();
		ob_end_clean();
				
		$output_html .= get_form_footer();
		$output_html .= insert_popup_footer();
		return $output_html;
	}

	/**
	 *
	 */
	function process_page_for_address()
	{
		global $theme;
		global $mod_strings;
		global $app_strings;
		global $currentModule;
		global $sugar_version, $sugar_config;
		
		$output_html = '';
		$where = '';
		
		$where = $this->_get_where_clause();
		
		$image_path = 'themes/'.$theme.'/images/';
		
		$formBase = new ContactFormBase();
		if(isset($_REQUEST['doAction']) && $_REQUEST['doAction'] == 'save')
		{
			$formBase->handleSave('', false, true);
		}

		$first_name = empty($_REQUEST['first_name']) ? '' : $_REQUEST['first_name'];
		$last_name = empty($_REQUEST['last_name']) ? '' : $_REQUEST['last_name'];
		$account_name = empty($_REQUEST['account_name']) ? '' : $_REQUEST['account_name'];
		$request_data = empty($_REQUEST['request_data']) ? '' : $_REQUEST['request_data'];
		$hide_clear_button = empty($_REQUEST['hide_clear_button']) ? false : true;
		$lbl_save_button_title = $app_strings['LBL_SAVE_BUTTON_TITLE'];
		$lbl_save_button_key = $app_strings['LBL_SAVE_BUTTON_KEY'];
		$lbl_save_button_label = $app_strings['LBL_SAVE_BUTTON_LABEL'];
		
		// TODO: cleanup the construction of $addform
		$formbody = $formBase->getFormBody('','','EmailEditView');
		$addform = '<table><tr><td nowrap="nowrap" valign="top">'
			.str_replace('<br>', '</td><td nowrap="nowrap" valign="top">&nbsp;', $formbody)
			. '</td></tr></table>'
			. '<input type="hidden" name="action" value="Popup" />';
		$formSave = <<<EOQ
		<input type="submit" name="button" class="button" title="$lbl_save_button_title" accesskey="$lbl_save_button_key" value="  $lbl_save_button_label  " />
		<input type="button" name="button" class="button" title="{$app_strings['LBL_CANCEL_BUTTON_TITLE']}" accesskey="{$app_strings['LBL_CANCEL_BUTTON_KEY']}" value="{$app_strings['LBL_CANCEL_BUTTON_LABEL']}" onclick="toggleDisplay('addform');" />
EOQ;
		$createContact = <<<EOQ
		<input type="button" name="showAdd" class="button" value="{$mod_strings['LNK_NEW_CONTACT']}" onclick="toggleDisplay('addform');" />
EOQ;
		$addformheader = get_form_header($mod_strings['LNK_NEW_CONTACT'], $formSave, false);
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

		$form = new XTemplate('modules/Contacts/Address_picker.html');
		$form->assign('MOD', $mod_strings);
		$form->assign('APP', $app_strings);
		//$form->assign('CREATECONTACT', $createContact);
		$form->assign('ADDFORMHEADER', $addformheader);
		$form->assign('ADDFORM', $addform);
		$form->assign('THEME', $theme);
		$form->assign('MODULE_NAME', $currentModule);
		$form->assign('FIRST_NAME', $first_name);
		$form->assign('LAST_NAME', $last_name);
		$form->assign('ACCOUNT_NAME', $account_name);
		$form->assign('request_data', $request_data);
		
		// fill in for mass update
		$button = "<input type='hidden' name='module' value='Contacts'><input type='hidden' id='form_action' name='action' value='index'><input type='hidden' name='massupdate' value='true'><input type='hidden' name='delete' value='false'><input type='hidden' name='mass' value='Array'><input type='hidden' name='Update' value='Update'>";
		if(isset($_REQUEST['mass']) && is_array($_REQUEST['mass'])) {
			foreach(array_unique($_REQUEST['mass']) as $record) {
				$button .= "<input style='display: none' checked type='checkbox' name='mass[]' value='$record'>\n";
			}		
		}
		$button .= "<input type='hidden' name='query' value='true'>";
		$button .= "<input type='hidden' name='close_window' value='true'>";
		$button .= "<input type='hidden' name='html' value='change_address'>";
		$button .= "<input type='hidden' name='account_name' value='$account_name'>";
		$button .= "<span style='display: none'><textarea name='primary_address_street'>" . str_replace("&lt;br&gt;", "\n", $_REQUEST["primary_address_street"]) . "</textarea></span>";
		$button .= "<input type='hidden' name='primary_address_city' value='". $_REQUEST["primary_address_city"] ."'>";
		$button .= "<input type='hidden' name='primary_address_state' value='". $_REQUEST["primary_address_state"] ."'>";
		$button .= "<input type='hidden' name='primary_address_postalcode' value='". $_REQUEST["primary_address_postalcode"] ."'>";
		$button .= "<input type='hidden' name='primary_address_country' value='". $_REQUEST["primary_address_country"] ."'>";
		$button .= "<input type='hidden' name='Contacts_CONTACT_offset' value=''>";
		$button .= "<input title='".$mod_strings['LBL_COPY_ADDRESS_CHECKED']."'  class='button' LANGUAGE=javascript type='submit' name='button' value='  ".$mod_strings['LBL_COPY_ADDRESS_CHECKED']."  '>\n";
		$button .= "<input title='".$app_strings['LBL_CANCEL_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_CANCEL_BUTTON_KEY']."' class='button' LANGUAGE=javascript onclick=\"window.close()\" type='submit' name='button' value='  ".$app_strings['LBL_CANCEL_BUTTON_LABEL']."  '>\n";
	
		ob_start();
		insert_popup_header($theme);
		$output_html .= ob_get_contents();
		ob_end_clean();
		
		//$output_html .= get_form_header($mod_strings['LBL_SEARCH_FORM_TITLE'], '', false);
		
		//$form->parse('main.SearchHeader');
		//$output_html .= $form->text('main.SearchHeader');
		
		$output_html .= get_form_footer();
		
		// Reset the sections that are already in the page so that they do not print again later.
		$form->reset('main.SearchHeader');

		// create the listview
		$seed_bean = new Contact();
		$ListView = new ListView();
		$ListView->show_export_button = false;
		//$ListView->process_for_popups = true;
		$ListView->setXTemplate($form);
		$ListView->setHeaderTitle($mod_strings['LBL_LIST_FORM_TITLE']);
		$ListView->setHeaderText($button);
		$ListView->setQuery($where, '', '', 'CONTACT');
		$ListView->setModStrings($mod_strings);
		
		ob_start();
		$ListView->processListViewMulti($seed_bean, 'main', 'CONTACT');
		$output_html .= ob_get_contents();
		ob_end_clean();

		// remove send back links, precheck boxes, and next links.
		$output_html = preg_replace(array('/<a href="#".*;">(.*)<\/a>/Us', '/(<a href=")(.*)offset=(\d+)"(.*">.*(Start|Previous|Next|End).*<\/a>)/Us'), 
											 array('${1}', '${1}#" onclick="save_checks(\'${3}\')" ${4}'), $output_html); 

		$output_html .= '<script>		
		checked_items = Array();
		inputs_array = document.MassUpdate.elements;

		for(wp = 0 ; wp < inputs_array.length; wp++) {
			if(inputs_array[wp].name == "mass[]" && inputs_array[wp].style.display == "none") {
				checked_items.push(inputs_array[wp].value);
			} 
		}
		for(i in checked_items) {
			for(wp = 0 ; wp < inputs_array.length; wp++) {
				if(inputs_array[wp].name == "mass[]" && inputs_array[wp].value == checked_items[i]) {
					inputs_array[wp].checked = true;
				}
			}
		}</script>';
		
		$output_html .= get_form_footer();
		$output_html .= insert_popup_footer();
		return $output_html;
	}
}
?>
