<?php
require_once('include/formbase.php');
require_once('modules/Users/NewUser.php');
require_once('modules/Users/User.php');
require_once ('include/utils.php');
global $current_user;

$prefix='';
$redirect=true;
$useRequired=false;
$UserObj= new User();
$focus = new NewUser();

    /*   if($UserObj->checkRegisterUserEmailId($_POST['email_id'])) {
            $_SESSION['email_error']="This user already exist";
            $redirect = "index.php?action=Register&module=Users";
            header("Location: {$redirect}");
            exit;
        }
        if($focus->checkRegisterUserApply($_POST['email_id'])) {
            $_SESSION['email_error']="This user request under process ";
            $redirect = "index.php?action=Register&module=Users";
            header("Location: {$redirect}");
            exit;
        }
        if($UserObj->checkReportToEmailId($_POST['reports_to_email_id'])) {
            $_SESSION['email_error']="This Report to email id  not valid";
            $redirect = "index.php?action=Register&module=Users";
            header("Location: {$redirect}");
            exit;
        }*/
        if($_SESSION['captcha']!=$_REQUEST['captcha']){
            $_SESSION['captcha_error']="Please enter valid text";
            $first_name = $_POST['first_name'];
            $last_name = $_POST['last_name'];
            $phone_mobile = $_POST['phone_mobile'];
            $email_id = $_POST['email_id'];
            $reports_to_email_id = $_POST['reports_to_email_id'];
            $redirect = "index.php?action=Register&module=Users&reports_to_email_id=$reports_to_email_id&first_name=$first_name&last_name=$last_name&phone_mobile=$phone_mobile&email_id=$email_id ";
            header("Location: {$redirect}");
            exit;
        }


if($useRequired &&  !checkRequired($prefix, array_keys($focus->required_fields))) {
    return null;
}

$focus = populateFromPost($prefix, $focus);

$check_notify = FALSE;
if (isset($GLOBALS['check_notify'])) {
    $check_notify = $GLOBALS['check_notify'];
}

$focus->save();

$redirect = "index.php?action=Register&module=Users";
$_SESSION['email_error']="You have register successfully and will be intimated once your login is created !";
$_SESSION['captcha_error']='';
header("Location: {$redirect}");
?>