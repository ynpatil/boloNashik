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
 * $Id: Error.php,v 1.17 2006/06/06 17:58:53 majed Exp $
 * Description: TODO:  To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
global $app_strings;
echo "<br><br>";

if(isset($_REQUEST['ie_error']) && $_REQUEST['ie_error'] == 'true') {
	echo '<a href="index.php?module=Users&action=EditView&record='.$_REQUEST['id'].'">'.$mod_strings['ERR_IE_FAILURE1'].'</a><br>';
	echo $mod_strings['ERR_IE_FAILURE2'];
} else {
?>
<span class='error'><?php if (isset($_REQUEST['error_string'])) echo $_REQUEST['error_string']; ?>
<br><br>
<?php echo $app_strings['NTC_CLICK_BACK']; }?>
</span>
