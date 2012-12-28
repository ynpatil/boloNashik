<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once('modules/SubofficeMaster/Suboffice.php');
require_once('include/formbase.php');

$sugarbean = new Suboffice();
$sugarbean = populateFromPost('', $sugarbean);

if(!$sugarbean->ACLAccess('Save')){
               ACLController::displayNoAccess(true);
               sugar_cleanup(true);
}
$sugarbean->id =$_REQUEST['record'];
$sugarbean->save($GLOBALS['check_notify']);

$return_id = $sugarbean->id;
handleRedirect($return_id,'SubofficeMaster');

?>
