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
 * $Id: Login.php,v 1.71 2006/08/12 18:20:28 chris Exp $
 * Description: TODO:  To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
$theme_path="themes/".$theme."/";
require_once($theme_path.'layout_utils.php');

global $app_language, $sugar_config;
//we don't want the parent module's string file, but rather the string file specifc to this subpanel
global $current_language;
global $timedate;
$current_module_strings = return_module_language($current_language, 'Users');
require_once('modules/Administration/updater_utils.php');

$current_module_strings['VLD_ERROR'] = $GLOBALS['app_strings']["\x4c\x4f\x47\x49\x4e\x5f\x4c\x4f\x47\x4f\x5f\x45\x52\x52\x4f\x52"];


?>
<script type="text/javascript" src="include/javascript/sugar_3.js?s={SUGAR_VERSION}&c={JS_CUSTOM_VERSION}"></script>
<script type="text/javascript" language="JavaScript">
    function ValidationFrm(){

        var ErrMsg = true;
        var phone_no = document.getElementById('phone_mobile').value;
        var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;

        /*if(!document.getElementById('first_name').value){
            document.getElementById('first_name_v').innerHTML='Missing required field: First Name';
            ErrMsg=false;
        }
        if(!document.getElementById('last_name').value){
            document.getElementById('last_name_v').innerHTML='Missing required field: Last Name';
            ErrMsg=false;
        }

        if(!phone_no){
            document.getElementById('phone_mobile_v').innerHTML='Missing required field: Phone No';
            ErrMsg=false;
        }

        if(phone_no){
            if(isNaN(phone_no)){
                document.getElementById('phone_mobile_v').innerHTML='Phone No must be a Numeric value';
                ErrMsg=false;
            }else{            
                if((phone_no.length)!='10'){
                    document.getElementById('phone_mobile_v').innerHTML='Phone No must be a 10 digit';
                    ErrMsg=false;
                }}
        }

        if(!document.getElementById('email_id').value){
            document.getElementById('email_id_v').innerHTML='Missing required field: Email Id';
            ErrMsg=false;
        }
        if (trim(document.getElementById('email_id').value) && (reg.test(document.getElementById('email_id').value) ) == false ) {
            document.getElementById('email_id_v').innerHTML='Please enter proper  Report To Email Id';
            ErrMsg=false;
        }
        if(!document.getElementById('reports_to_email_id').value){
            document.getElementById('reports_to_email_id_v').innerHTML='Missing required field: Email Id';
            ErrMsg=false;
        }
        if (trim(document.getElementById('reports_to_email_id').value) && (reg.test(document.getElementById('reports_to_email_id').value) ) == false ) {
            document.getElementById('reports_to_email_id_v').innerHTML='Please enter proper  Report To Email Id';
            ErrMsg=false;
        }*/
        if(!document.getElementById('captcha-form').value){
            document.getElementById('captcha_form_v').innerHTML='Missing required field: Captcha';
            ErrMsg=false;
        }
        return ErrMsg;
    }

    <!-- Begin
    function set_focus() {
        document.DetailView.first_name.focus();
    }

    function toggleDisplay(id){

        if(this.document.getElementById(id).style.display=='none'){
            this.document.getElementById(id).style.display='inline'
            if(this.document.getElementById(id+"link") != undefined){
                this.document.getElementById(id+"link").style.display='none';
            }
            document['options'].src = '<?php echo $theme_path ?>images/basic_search.gif';
        }else{
            this.document.getElementById(id).style.display='none'
            if(this.document.getElementById(id+"link") != undefined){
                this.document.getElementById(id+"link").style.display='inline';
            }
            document['options'].src = '<?php echo $theme_path ?>images/advanced_search.gif';
        }
    }
    //  End -->
</script>
<style type="text/css">

    .body {
        font-size: 12px;
    }

    .buttonLogin {
        border: 1px solid #444444;
        font-size: 11px;
        color: #ffffff;
        background-color: #666666;
        font-weight: bold;
    }

    table.tabForm td {
        border: none;
    }

    table,td {
    }

    p {
        MARGIN-TOP: 0px;
        MARGIN-BOTTOM: 10px;
    }

    form {
        margin: 0px;
    }

</style><br>
<br>

<table cellpadding="0" align="center" width="100%" cellspacing="0" border="0">
    <tr>
        <td>

            <table cellpadding="0" width="45%"  cellspacing="0" border="0" align="center">
                <form action="index.php" method="post" name="EditView" id="form" >
                    <tr>
                        <td class="body"  style="padding-bottom: 10px;" ><b><? echo $mod_strings['LBL_LOGIN_WELCOME_TO']; ?></b><br>
                            <IMG src="include/images/sugar_md.png" width="300" height="25" alt="RespForce"></td>
                    </tr>
                    <tr>
                        <td class="tabForm" align="center">

                            <table cellpadding="0" cellspacing="2" border="0" align="center" width="100%">

                                <input type="hidden" name="module" value="Users">
                                <input type="hidden" name="action">
                                <input type="hidden" name="return_module" value="Users">
                                <input type="hidden" name="return_action" value="Login">
                                <input type="hidden" id="status" name="status"  value="6">
                                <!--<input type="hidden" name="login_module" value="<?php //if (isset($_GET['login_module'])) echo $_GET['login_module']; ?>">
                                <input type="hidden" name="login_action" value="<?php    //if (isset($_GET['login_action'])) echo $_GET['login_action']; ?>">
                                <input type="hidden" name="login_record" value="<?php   //if (isset($_GET['login_record'])) echo $_GET['login_record']; ?>">
                                -->
                                <tr>
                                    <td class="required" colspan="2" align="center" width="100%" style="font-size: 12px; padding-bottom: 5px; font-weight: normal;"><?php echo $_SESSION['email_error']; ?></td>
                                </tr>
                                <?php if($_SESSION['captcha_error']){?>
                                <tr>
                                    <td class="required" colspan="2" align="center" width="100%" style="font-size: 12px; padding-bottom: 5px; font-weight: normal;"><?php echo $_SESSION['captcha_error']; ?></td>
                                </tr>
                                <?php }?>
                                <?php if(!$_SESSION['email_error']) {?>
                                <tr>
                                    <td class="tabDetailViewDL"><?php echo $current_module_strings['LBL_FIRST_NAME'] ?><span  class="required"><?php echo $current_module_strings['LBL_REQUIRED_SYMBOL'] ?></span></td>
                                    <td class="tabDetailViewDF"><span sugar='slot1b'><input type="text" tabindex='1'  size='20' id="first_name" name="first_name"  value="<?=$_REQUEST['first_name']?>"></span sugar='slot'><br><span class="required" id="first_name_v"></span></td>
                                </tr>
                                <tr>
                                    <td class="tabDetailViewDL"><?php echo $current_module_strings['LBL_LAST_NAME'] ?><span  class="required"><?php echo $current_module_strings['LBL_REQUIRED_SYMBOL'] ?></span></td>
                                    <td class="tabDetailViewDF"><input type="text" size='20' tabindex='1'  id="last_name" name="last_name"  value="<?=$_REQUEST['last_name']?>"><br><span class="required" id="last_name_v"></span></td>
                                </tr>

                                <tr>
                                    <td class="tabDetailViewDL"><?php echo $current_module_strings['LBL_CONTACT_NUMBER'] ?><span  class="required"><?php echo $current_module_strings['LBL_REQUIRED_SYMBOL'] ?></span></td>
                                    <td class="tabDetailViewDF"><input type="text" size='20' id="phone_mobile" tabindex='1'  name="phone_mobile"  value="<?=$_REQUEST['phone_mobile']?>"><br><span class="required" id="phone_mobile_v"></span></td>
                                </tr>
                                <tr>
                                    <td class="tabDetailViewDL"><?php echo $current_module_strings['LBL_EMAIL_ID'] ?><span  class="required"><?php echo $current_module_strings['LBL_REQUIRED_SYMBOL'] ?></span></td>
                                    <td class="tabDetailViewDF"><input type="text" size='20' id="email_id" tabindex='1'  name="email_id"  value="<?=$_REQUEST['email_id']?>"><br><span class="required" id="email_id_v"></span></td>
                                </tr>
                                <tr>
                                    <td class="tabDetailViewDL"><?php echo $current_module_strings['LBL_REPORT_TO_EMAIL_ID'] ?><span  class="required"><?php echo $current_module_strings['LBL_REQUIRED_SYMBOL'] ?></span></td>
                                    <td class="tabDetailViewDF"><input type="text" size='20' tabindex='1' id="reports_to_email_id" name="reports_to_email_id"  value="<?=$_REQUEST['reports_to_email_id']?>"><br><span class="required" id="reports_to_email_id_v"></span></td>
                                </tr>
                                <tr>
                                    <td class="tabDetailViewDL"><?=$current_module_strings['LBL_CAPTCHA_TEXT'] ?><span  class="required"><?php echo $current_module_strings['LBL_REQUIRED_SYMBOL'] ?></span></td>
                                    <td class="tabDetailViewDF"><input type="text" name="captcha" id="captcha-form" value="" /><br><span class="required" id="captcha_form_v"></span></td>
                                </tr>
                                <tr>
                                    <td class="tabDetailViewDL"></td>
                                    <td class="tabDetailViewDF" ><img src="modules/Users/captcha.php" id="captcha" width="190" height="60" /><br/>
                                        <a href="#" onclick="
                                            document.getElementById('captcha').src='modules/Users/captcha.php?'+Math.random();
                                            document.getElementById('captcha-form').focus();"
                                           id="change-image">Not readable? Change text.</a>                                        
                                        </td>                                    
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td> &nbsp;&nbsp;<input onclick="this.form.action.value='SaveNewUser';return ValidationFrm('EditView');"  tabindex='1'  title="<?php echo $current_module_strings['LBL_SUBMIT_BUTTON_TITLE'] ?>" accessKey="<?php echo $current_module_strings['LBL_SUBMIT_BUTTON_TITLE'] ?>" class="button" onclick="this.form.action.value='SaveNewUser';" type="submit" id="submit_button" name="Submit" value="<?php echo $current_module_strings['LBL_SUBMIT_BUTTON_TITLE']?>"></td>
                                </tr>
                                    <?}?>
                                <tr>
                                    <td align="right" colspan="2"><a href="index.php?action=Login&module=Users"  tabindex='1' class='utilsLink'><?php echo $current_module_strings['LBL_LOGIN_BUTTON_LABEL'] ?></a></td>
                                </tr>
                            </table>                        
                        </td>
                </form>                
                <script type="text/javascript">
                    Calendar.setup ({
                        inputField : "jscal_field", ifFormat : "<?php echo $timedate->get_cal_date_format();?>", showsTime : false, button : "jscal_trigger", singleClick : true, step : 1
                    });
                </script>
    </tr>
</table>
<br>
<br>
