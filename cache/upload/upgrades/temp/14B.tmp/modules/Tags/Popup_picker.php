<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

/*******************************************************************************
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
 *
 * Contributor(s): George Neill <gneill@aiminstitute.org>, 
 *                 AIM Institute <http://www.aiminstitute.org>
 ******************************************************************************/

global $theme;

require_once('modules/Tags/Tag.php');
require_once('themes/'.$theme.'/layout_utils.php');
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
      append_where_clause($where_clauses, "name", "tags.title");
    
      $where = generate_where_statement($where_clauses);
    }

    return $where;
  }
  
  function process_page()
  {
    global $theme;
    global $mod_strings;
    global $app_strings;
    
    $output_html = '';
    $where       = '';
    
    $where = $this->_get_where_clause();

    $image_path = 'themes/'.$theme.'/images/';
    
    $name = empty($_REQUEST['name']) ? '' : $_REQUEST['name'];

    $request_data = empty($_REQUEST['request_data']) ? '' 
                            : $_REQUEST['request_data'];

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

    $xtpl = new XTemplate('modules/Tags/Popup_picker.html');
    $xtpl->assign('MOD', $mod_strings);
    $xtpl->assign('APP', $app_strings);
    $xtpl->assign('THEME', $theme);
    $xtpl->assign('NAME', $name);
    $xtpl->assign('request_data', $request_data);
    
    ob_start();
    insert_popup_header($theme);
    $output_html .= ob_get_contents();
    ob_end_clean();
    
    $output_html .= get_form_header($mod_strings['LBL_SEARCH_FORM_TITLE'], '', false);
    
    $xtpl->parse('main.SearchHeader');
    $output_html .= $xtpl->text('main.SearchHeader');
    
    $output_html .= get_form_footer();
    
    // Reset the sections that are already in the page so that they do not print again later.
    $xtpl->reset('main.SearchHeader');

    // create the listview
    $tag = new Tag();

    $ListView = new ListView();
    $ListView->show_export_button = false;
    $ListView->process_for_popups = true;
    $ListView->setXTemplate($xtpl);
    $ListView->setHeaderTitle($mod_strings['LBL_LIST_FORM_TITLE']);
    $ListView->setHeaderText($button);
    $ListView->setQuery($where, '', 'title', 'TAG');


    $ListView->setModStrings($mod_strings);

    ob_start();

    $ListView->processListView($tag, 'main', 'TAG');
    $output_html .= ob_get_contents();
    ob_end_clean();
        
    $output_html .= get_form_footer();
    $output_html .= insert_popup_footer();
    return $output_html;
  }
} // end of class Popup_Picker
?>
