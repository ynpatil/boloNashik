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

global $theme;
require_once('modules/Users/User.php');
require_once('themes/'.$theme.'/layout_utils.php');


global $app_strings;
global $mod_strings;

$image_path = "themes/{$theme}/images/";
?>

<script type='text/javascript' language='JavaScript'>
function set_password(form) {
	if (form.is_admin.value == 1 && form.old_password.value == "") {
		alert("<?php echo $mod_strings['ERR_ENTER_OLD_PASSWORD']; ?>");
		return false;
	}
	if (form.new_password.value == "") {
		alert("<?php echo $mod_strings['ERR_ENTER_NEW_PASSWORD']; ?>");
		return false;
	}
	if (form.confirm_new_password.value == "") {
		alert("<?php echo $mod_strings['ERR_ENTER_CONFIRMATION_PASSWORD']; ?>");
		return false;
	}

	if(typeof window.opener.document.DetailView != 'undefined') {
		var openerForm = window.opener.document.DetailView;
		openerForm.return_action.value = 'DetailView';
	} else if(typeof window.opener.document.EditView != 'undefined') {
		var openerForm = window.opener.document.EditView;
		openerForm.return_action.value = 'EditView';
	}

	if (form.new_password.value == form.confirm_new_password.value) {
		if (form.is_admin.value == 1) openerForm.old_password.value = form.old_password.value;
		openerForm.new_password.value = form.new_password.value;
		openerForm.return_module.value = 'Users';
		openerForm.password_change.value = 'true';
		openerForm.return_id.value = openerForm.record.value;
		openerForm.action.value = 'Save';
		openerForm.submit();
		return true;
	}
	else {
		alert("<?php echo $mod_strings['ERR_REENTER_PASSWORDS']; ?>");
		return false;
	}
}
</script>

<?php insert_popup_header($theme); ?>

<form>
<?php echo get_form_header($mod_strings['LBL_CHANGE_PASSWORD'], "", false); ?>
<br>
<table width='100%' cellspacing='0' cellpadding='1' border='0'>
<tr>
<?php if (!is_admin($current_user)) {
	echo "<td width='40%' class='dataLabel'>".$mod_strings['LBL_OLD_PASSWORD']."</td>\n";
	echo "<td width='60%' class='dataField'><input name='old_password' type='password' tabindex='1' ></td>\n";
	echo "<input name='is_admin' type='hidden' value='1'>";
	echo "</tr><tr>\n";
}
else echo "<input name='old_password' type='hidden'><input name='is_admin' type='hidden' value='0'>";
?>
<td width='40%' class='dataLabel'nowrap><?php echo $mod_strings['LBL_NEW_PASSWORD']; ?></td>
<td width='60%' class='dataField'><input name='new_password' type='password' tabindex='1'  ></td>
</tr><tr>
<td width='40%' class='dataLabel' nowrap><?php echo $mod_strings['LBL_CONFIRM_PASSWORD']; ?></td>
<td width='60%' class='dataField'><input name='confirm_new_password' type='password' tabindex='1'  ></td>
</tr><tr>
<td width='40%' class='dataLabel'></td>
<td width='60%' class='dataField'></td>
</td></tr>
</table>
<br>
<table width='100%' cellspacing='0' cellpadding='1' border='0'>
<tr>
<td align='right'><input title='<?php echo $app_strings['LBL_SAVE_BUTTON_TITLE']; ?>' accessKey='<?php echo $app_strings['LBL_SAVE_BUTTON_KEY']; ?>' class='button' LANGUAGE=javascript onclick='if (set_password(this.form)) window.close(); else return false;' type='submit' name='button' value='  <?php echo $app_strings['LBL_SAVE_BUTTON_LABEL']; ?>  '></td>
<td align='left'><input title='<?php echo $app_strings['LBL_CANCEL_BUTTON_TITLE']; ?>' accessKey='<?php echo $app_strings['LBL_CANCEL_BUTTON_KEY']; ?>' class='button' LANGUAGE=javascript onclick='window.close()' type='submit' name='button' value='  <?php echo $app_strings['LBL_CANCEL_BUTTON_LABEL']; ?>  '></td>
</tr>
</form>
</table>
<br>
<script type='text/javascript' language='JavaScript'>
function set_password_form_focus() {
	if (document.forms.length > 0) {
		for (i = 0; i < document.forms.length; i++) {
			for (j = 0; j < document.forms[i].elements.length; j++) {
				var field = document.forms[i].elements[j];
				if ((field.type == "password"))
				{
					field.focus();
					break;
				}
    		}
		}
   	}
}

set_password_form_focus();

</script>
<?php echo get_form_footer(); ?>
<?php insert_popup_footer(); ?>
