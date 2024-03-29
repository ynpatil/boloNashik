<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

/*********************************************************************************
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
 ********************************************************************************/

$js_loaded = false;
require_once("include/templates/Template.php");

class TemplateGroupChooser extends Template {
    var $args;
    var $js_loaded = false;
    var $display_hide_tabs = true;
    var $display_third_tabs = false;

    function TemplateGroupChooser() {
    }

    function display() {
        global $mod_strings, $image_path, $js_loaded;
        
        $left_size = (empty($this->args['left_size']) ? '10' : $this->args['left_size']);
        $right_size = (empty($this->args['right_size']) ? '10' : $this->args['right_size']);
        $third_size = (empty($this->args['third_size']) ? '10' : $this->args['third_size']);
        $max_left = (empty($this->args['max_left']) ? '' : $this->args['max_left']);
        
        $str = '';
        if(empty($image_path)) {
            global $theme;
            $image_path = 'themes/' . $theme . '/images/';
        }
        if($js_loaded == false) {
//            $this->template_groups_chooser_js();
            $js_loaded = true;
        }
        if(!isset($this->args['display'])) {
            $table_style = "";
        }
        else {
            $table_style = "display: ".$this->args['display'];
        }

        $str .= "<div id=\"{$this->args['id']}\" style=\"{$table_style}\">";
        if(!empty($this->args['title'])) $str .= "<h4>{$this->args['title']}</h4>";
        $str .= <<<EOQ
        <table cellpadding="0" cellspacing="0" border="0">
        
        <tr>
            <td>&nbsp;</td>
            <td class="dataLabel" id="chooser_{$this->args['left_name']}_text" align="center"><nobr>{$this->args['left_label']}</nobr></td>
EOQ;

        if($this->display_hide_tabs == true) {
           $str .= <<<EOQ
            <td>&nbsp;</td>
            <td class="dataLabel" id="chooser_{$this->args['right_name']}" align="center"><nobr>{$this->args['right_label']}</nobr></td>
EOQ;
        }
        
        if($this->display_third_tabs == true) {
           $str .= <<<EOQ
            <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td class="dataLabel" id="chooser_{$this->args['third_name']}" align="center"><nobr>{$this->args['third_label']}</nobr></td>
EOQ;
        }
        
        $str .= '</tr><tr><td valign="top" style="padding-right: 2px; padding-left: 2px;" align="center">';
        if(!isset($this->args['disable'])) { 
            $str .= "<a onclick=\"return SUGAR.tabChooser.up('{$this->args['left_name']}','{$this->args['left_name']}','{$this->args['right_name']}');\">" .  get_image($image_path.'uparrow_big','border="0" style="margin-bottom: 1px;" alt="Sort"') . "</a><br>
                     <a onclick=\"return SUGAR.tabChooser.down('{$this->args['left_name']}','{$this->args['left_name']}','{$this->args['right_name']}');\">" . get_image($image_path.'downarrow_big','border="0" style="margin-top: 1px;" alt="Sort"') . "</a>";
        }
        
        $str .= <<<EOQ
                </td>    
                <td align="center">
                    <table border="0" cellspacing=0 cellpadding="0" align="center">
                        <tr>
                            <td id="{$this->args['left_name']}_td" align="center">
                            <select id="{$this->args['left_name']}" name="{$this->args['left_name']}[]" size=
EOQ;
        $str .=  '"' . (empty($this->args['left_size']) ? '10' : $this->args['left_size']) . '" multiple="multiple" ' . (isset($this->args['disable']) ?  "DISABLED" : '') . '>';

        foreach($this->args['values_array'][0] as $key=>$value) {
            $str .= "<option value='{$key}'>{$value}</option>";
        }
        $str .= "</select></td>
            </tr>
            </table>
            </td>";
        if ($this->display_hide_tabs == true) {
            $str .= '<td valign="top" style="padding-right: 2px; padding-left: 2px;" align="center">';
            if(!isset($this->args['disable'])) { 
                $str .= "<a onclick=\"return SUGAR.tabChooser.right_to_left('{$this->args['left_name']}','{$this->args['right_name']}', '{$left_size}', '{$right_size}', '{$max_left}');\">" . get_image($image_path.'leftarrow_big','border="0" style="margin-right: 1px;" alt="Sort"') . "</a><a onclick=\"return SUGAR.tabChooser.left_to_right('{$this->args['left_name']}','{$this->args['right_name']}', '{$left_size}', '{$right_size}');\">" . get_image($image_path.'rightarrow_big','border="0" style="margin-left: 1px;" alt="Sort"') . "</a>";
            }
            $str .= "</td>
                <td id=\"{$this->args['right_name']}_td\" align=\"center\">
                <select id=\"{$this->args['right_name']}\" name=\"{$this->args['right_name']}[]\" size=\"" . (empty($this->args['right_size']) ? '10' : $this->args['right_size']) . "\" multiple=\"multiple\" " . (isset($this->args['disable']) ? "DISABLED" : '') . '>';
            foreach($this->args['values_array'][1] as $key=>$value) {
                $str .= "<option value=\"{$key}\">{$value}</option>";
            }
            $str .= "</select></td><td valign=\"top\" style=\"padding-right: 2px; padding-left: 2px;\" align=\"center\">"
                    . "<script>var object_refs = new Object();object_refs['{$this->args['right_name']}'] = document.getElementById('{$this->args['right_name']}');</script>";
         }
         
         if ($this->display_third_tabs == true) {
            $str .= '<td valign="top" style="padding-right: 2px; padding-left: 2px;" align="center">';
            if(!isset($this->args['disable'])) { 
                $str .= "<a onclick=\"return SUGAR.tabChooser.right_to_left('{$this->args['right_name']}','{$this->args['third_name']}', '{$right_size}', '{$third_size}');\">" . get_image($image_path.'leftarrow_big','border="0" style="margin-right: 1px;" alt="Sort"') . "</a><a onclick=\"return SUGAR.tabChooser.left_to_right('{$this->args['right_name']}','{$this->args['third_name']}', '{$right_size}', '{$third_size}');\">" . get_image($image_path.'rightarrow_big','border="0" style="margin-left: 1px;" alt="Sort"') . "</a>";
            }
            $str .= "</td>
                <td id=\"{$this->args['third_name']}_td\" align=\"center\">
                <select id=\"{$this->args['third_name']}\" name=\"{$this->args['third_name']}[]\" size=\"" . (empty($this->args['third_size']) ? '10' : $this->args['third_size']) . "\" multiple=\"multiple\" " . (isset($this->args['disable']) ? "DISABLED" : '') . '>';
            foreach($this->args['values_array'][2] as $key=>$value) {
                $str .= "<option value=\"{$key}\">{$value}</option>";
            }
            $str .= "</select>
                <script>
                    object_refs['{$this->args['third_name']}'] = document.getElementById('{$this->args['third_name']}');
                </script>
                <td valign=\"top\" style=\"padding-right: 2px; padding-left: 2px;\" align=\"center\">
                </td>";
         }
         $str .= "<script>
                object_refs['{$this->args['left_name']}'] = document.getElementById('{$this->args['left_name']}');
                </script></tr>
            </table>";
                
        return $str;
}

    /*
     * All Moved to sugar_3.js in class tabChooser;
     * Please follow style that Dashlet configuration is done.
     */ 
    function template_groups_chooser_js() {
        //return '<script>var object_refs = new Object();</script>';
    }

}

?>
