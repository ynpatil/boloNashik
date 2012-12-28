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
/*********************************************************************************
 * $Id: ChangePassword.php,v 1.22 2006/08/22 23:38:45 majed Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
// This file is used for all popups on this module
// The popup_picker.html file is used for generating a list from which to find and choose one instance.

global $theme,$current_user;
require_once('modules/Users/User.php');
require_once('themes/'.$theme.'/layout_utils.php');

global $app_strings;
global $mod_strings;
global $image_path;

$image_path = "themes/{$theme}/images/";
?>

<script type='text/javascript' language='JavaScript'>
    function delete_vacation(id){
        if(typeof window.opener.document.DetailView != 'undefined') {
		var openerForm = window.opener.document.DetailView;
		openerForm.return_action.value = 'DetailView';
	} else if(typeof window.opener.document.EditView != 'undefined') {
		var openerForm = window.opener.document.EditView;
		openerForm.return_action.value = 'EditView';
	}
                //alert("In delete_vacation :"+id);
                openerForm.delete_vacation_id.value = id;
		openerForm.return_module.value = 'Users';
		openerForm.return_id.value = openerForm.record.value;
		openerForm.action.value = 'Save';
		openerForm.submit();
		return true;
    }

function set_vacation_dates(form) {
	
	if (form.date_start.value == "") {
		alert("<?php echo 'Please enter from date'; ?>");
		return false;
	}
	if (form.date_end.value == "") {
		alert("<?php echo 'Please enter to date'; ?>");
		return false;
	}

	if(typeof window.opener.document.DetailView != 'undefined') {
		var openerForm = window.opener.document.DetailView;
		openerForm.return_action.value = 'DetailView';
	} else if(typeof window.opener.document.EditView != 'undefined') {
		var openerForm = window.opener.document.EditView;
		openerForm.return_action.value = 'EditView';
	}

		openerForm.vacation_start_date.value = form.date_start.value;
                openerForm.vacation_end_date.value = form.date_end.value;
		openerForm.return_module.value = 'Users';
		openerForm.return_id.value = openerForm.record.value;
		openerForm.action.value = 'Save';
		openerForm.submit();
		return true;
	
}
</script>

<?php insert_popup_header($theme); ?>

<form>
<?php echo get_form_header($mod_strings['LBL_VACATION'], "", false); ?>
<br>
<table width='100%' cellspacing='0' cellpadding='1' border='0'>
<tr>
<td width='40%' class='dataLabel'nowrap><?php echo $mod_strings['LBL_FROM']; ?></td>
<td width='60%' class='dataField'>
<input name='date_start' id='jscal_field1' onblur="this.blur()" tabindex='1' size='11' maxlength='10' type="text" value=""> <img src="themes/Sugar/images/jscalendar.gif" alt="%d-%m-%Y"  id="jscal_trigger1" align="absmiddle">&nbsp;
</td>
</tr><tr>
<td width='40%' class='dataLabel' nowrap><?php echo $mod_strings['LBL_TO']; ?></td>
<td width='60%' class='dataField'>
<input name='date_end' id='jscal_field2' onblur="this.blur()" tabindex='1' size='11' maxlength='10' type="text" value=""> <img src="themes/Sugar/images/jscalendar.gif" alt="%d-%m-%Y"  id="jscal_trigger2" align="absmiddle">&nbsp;
</td>
</tr><tr>
<td width='40%' class='dataLabel'></td>
<td width='60%' class='dataField'></td>
</td></tr>
</table>
<br>
<table width='100%' cellspacing='0' cellpadding='1' border='0'>
<tr>
<td align='right'><input title='<?php echo $app_strings['LBL_SAVE_BUTTON_TITLE']; ?>' accessKey='<?php echo $app_strings['LBL_SAVE_BUTTON_KEY']; ?>' class='button' LANGUAGE=javascript onclick='if (set_vacation_dates(this.form)) window.close(); else return false;' type='submit' name='button' value='  <?php echo $app_strings['LBL_SAVE_BUTTON_LABEL']; ?>  '></td>
<td align='left'><input title='<?php echo $app_strings['LBL_CANCEL_BUTTON_TITLE']; ?>' accessKey='<?php echo $app_strings['LBL_CANCEL_BUTTON_KEY']; ?>' class='button' LANGUAGE=javascript onclick='window.close()' type='submit' name='button' value='  <?php echo $app_strings['LBL_CANCEL_BUTTON_LABEL']; ?>  '></td>
</tr>
</form>
</table>
<br>
<?php echo get_form_header($mod_strings['LBL_VACATION_HISTORY'], "", false); ?>
<table width='100%' cellspacing='0' cellpadding='1' border='0'>
    <tr>
    <td width='40%' class='dataLabel' nowrap><?php echo $mod_strings['LBL_FROM']; ?></td>
    <td width='40%' class='dataLabel' nowrap><?php echo $mod_strings['LBL_TO']; ?></td>
    <td width='20%' class='dataLabel' nowrap>&nbsp;</td>
    </tr>
<?php
$icon_remove_text = $app_strings['LNK_REMOVE'];
$icon_remove_html = get_image($image_path . 'delete_inline','align="absmiddle" alt="' . $icon_remove_text . '" border="0"');

$list = $current_user->get_vacation($current_user->id);
foreach ($list as $vacation){
echo "<tr><td class='dataField'>".$vacation['start_date']."</td><td class='dataField'>".$vacation['end_date']."</td><td class='dataField'>"
.'<a onclick="if (delete_vacation(\''.$vacation['id'].'\')) window.close();">'.
$icon_remove_html = get_image($image_path . 'delete_inline','align="absmiddle" alt="' . $icon_remove_text . '" border="0"');
echo $icon_remove_text."</a></td></tr>";
}
?>
</table>
<br>
<script type="text/javascript" language="Javascript">
	Calendar.setup ({
		inputField : "jscal_field1", ifFormat : "%d-%m-%Y", showsTime : false, button : "jscal_trigger1", singleClick : true, step : 1
	});

	Calendar.setup ({
		inputField : "jscal_field2", ifFormat : "%d-%m-%Y", showsTime : false, button : "jscal_trigger2", singleClick : true, step : 1
	});

</script>
<?php echo get_form_footer(); ?>
<?php insert_popup_footer(); ?>