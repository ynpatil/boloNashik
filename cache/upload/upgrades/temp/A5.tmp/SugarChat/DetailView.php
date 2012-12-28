<?php
include('modules/SugarChat/NewEntryPoint.php');
/*****************************************************************************
 * The contents of this file are subject to the RECIPROCAL PUBLIC LICENSE
 * Version 1.1 ("License"); You may not use this file except in compliance
 * with the License. You may obtain a copy of the License at
 * http://opensource.org/licenses/rpl.php. Software distributed under the
 * License is distributed on an "AS IS" basis, WITHOUT WARRANTY OF ANY KIND,
 * either express or implied.
 *
 * You may:
 * a) Use and distribute this code exactly as you received without payment or
 *    a royalty or other fee.
 * b) Create extensions for this code, provided that you make the extensions
 *    publicly available and document your modifications clearly.
 * c) Charge for a fee for warranty or support or for accepting liability
 *    obligations for your customers.
 *
 * You may NOT:
 * a) Charge for the use of the original code or extensions, including in
 *    electronic distribution models, such as ASP (Application Service
 *    Provider).
 * b) Charge for the original source code or your extensions other than a
 *    nominal fee to cover distribution costs where such distribution
 *    involves PHYSICAL media.
 * c) Modify or delete any pre-existing copyright notices, change notices,
 *    or License text in the Licensed Software
 * d) Assert any patent claims against the Licensor or Contributors, or
 *    which would in any way restrict the ability of any third party to use the
 *    Licensed Software.
 *
 * You must:
 * a) Document any modifications you make to this code including the nature of
 *    the change, the authors of the change, and the date of the change.
 * b) Make the source code for any extensions you deploy available via an
 *    Electronic Distribution Mechanism such as FTP or HTTP download.
 * c) Notify the licensor of the availability of source code to your extensions
 *    and include instructions on how to acquire the source code and updates.
 * d) Grant Licensor a world-wide, non-exclusive, royalty-free license to use,
 *    reproduce, perform, modify, sublicense, and distribute your extensions.
 *
 * The Original Code is: CommuniCore
 *                       Olavo Farias
 *                       2006-04-7 olavo.farias@gmail.com
 *
 * The Initial Developer of the Original Code is CommuniCore.
 * Portions created by CommuniCore are Copyright (C) 2005 CommuniCore Ltda
 * All Rights Reserved.
 ********************************************************************************/
/*******************************************************************************
 * The detailed view for a simple module
 *******************************************************************************/

  require_once('XTemplate/xtpl.php');
  require_once('data/Tracker.php');
  require_once('include/time.php');
  require_once('modules/SugarChat/SugarChat.php');
  require_once('include/DetailView/DetailView.php');
  
  global $app_strings;
  global $mod_strings;
  global $theme;
  global $current_user;
  
  $GLOBALS['log']->info('Simple module detail view');
  $focus = new SugarChat();


//Only load a record if a record id is given;
//a record id is not given when viewing in layout editor
  $detailView = new DetailView();
  $offset=0;

  if (isset($_REQUEST['offset']) or isset($_REQUEST['record'])) {
   $result = $detailView->processSugarBean("SIMPLE", $focus, $offset);
   if($result == null) {
       sugar_die("Error retrieving record.  You may not be authorized to view this record.");
   }
   $focus=$result;
  }else {
   header("Location: index.php?module=Accounts&action=index");
  }
  echo "\n<p>\n";
  echo get_module_title($mod_strings['LBL_MODULE_NAME'],
   $mod_strings['LBL_MODULE_NAME'] . ': ' . $focus->name, true);
  echo "\n</p>\n";
  
  $theme_path = 'themes/' . $theme . '/';
  $image_path = $theme_path . 'images/';
  
  require_once($theme_path.'layout_utils.php');
  
  $xtpl = new XTemplate('modules/SugarChat/DetailView.html');
  
  ///
  /// Assign the template variables
  ///
  
  $xtpl->assign('MOD', $mod_strings);
  $xtpl->assign('APP', $app_strings);
  if(isset($_REQUEST['return_module'])){
   $xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
  }
  
  if(isset($_REQUEST['return_action'])){
   $xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
  }
  
  if(isset($_REQUEST['return_id'])){
   $xtpl->assign("RETURN_ID", $_REQUEST['return_id']);
  }
$chatfile = md5($focus->name).".txt";
$filename_palaute = "modules/SugarChat/database/".$chatfile;
if (!file_exists($filename_palaute)) {
$fd_palaute = fopen ($filename_palaute , "w");
$fout_palaute = fwrite ($fd_palaute , "<span style=\"color: #FF0000;\">Administrator </span><b>10.10.2006 3:16:28</b>:---------------------------- <br />Topic: ".$focus->name."<br />-----------------------------------<br />" );
fclose($fd_palaute); }
$file_palaute = file($filename_palaute);
$file_palaute  = array_unique($file_palaute);

$fd_palaute = fopen ($filename_palaute , "r");
$fstring_palaute  = fread ($fd_palaute , filesize ($filename_palaute)) ;

fclose($fd_palaute) ;



  $xtpl->assign('PRINT_URL',              "index.php?".$GLOBALS['request_string']);
  $xtpl->assign('THEME',                  $theme);
  $xtpl->assign('GRIDLINE',               $gridline);
  $xtpl->assign('IMAGE_PATH',             $image_path);
  $xtpl->assign('id',                     $focus->id);
  $xtpl->assign('NAME',                   $focus->name);
  $xtpl->assign('assigned_user_name',     $focus->assigned_user_name);
  $xtpl->assign('DESCRIPTION',            nl2br(url2html($focus->description)));
  $xtpl->assign('CHATSTRING',             $fstring_palaute);
//BUILDER: included fields
//BUILDER:END of xtpl 
  
// ADMIN EDIT
  if(is_admin($current_user)
   && $_REQUEST['module'] != 'DynamicLayout'
   && !empty($_SESSION['editinplace']))
  {
   $xtpl->assign('ADMIN_EDIT',
    '<a href="index.php?action=index&module=DynamicLayout&from_action='
    . $_REQUEST['action'] . '&from_module=' . $_REQUEST['module']
    . '&record=' . $_REQUEST['record'] . '">'
    . get_image($image_path . 'EditLayout',
      'border="0" alt="Edit Layout" align="bottom"') . '</a>');
  }
  
  $detailView->processListNavigation($xtpl, "SIMPLE", $offset);
  // adding custom fields
  require_once('modules/DynamicFields/templates/Files/DetailView.php');
  $xtpl->parse('main.open_source');
  $xtpl->parse('main');
  $xtpl->out('main');
  
  $sub_xtpl = $xtpl;
  $old_contents = ob_get_contents();
  ob_end_clean();
  ob_start();
  echo $old_contents;
  
///////////////////////////////////////////////////////////////////////////////
////	SUBPANELS
///////////////////////////////////////////////////////////////////////////////
 /* require_once('include/SubPanel/SubPanelTiles.php');
  $subpanel = new SubPanelTiles($focus, 'SugarChat');
  echo $subpanel->display(); */
  
?>

<?php
if (isset($_POST['submit'])) {
if (!isset($stop)) {$stop = "";}
if (!isset($viesti)) {$viesti = "";}
$pakolliset = @explode(",", $_POST['pakolliset']);
$luku = count($pakolliset)-1;
// post parser 1 ## " ", \r, \t, \n and \f
$palaute =  $_POST['message'];
// post parser 2
$text = preg_replace('/\n/', '<br />', $palaute);
$codes = array(
    "",
	"<span style=\"color: #FF0000;\">",
	"<span style=\"color: #EE0000;\">",
	"<span style=\"color: #CC0000;\">",
	"<span style=\"color: #BB0000;\">",
	"<span style=\"color: #0000FF;\">",
	"<span style=\"color: #0000EE;\">",
	"<span style=\"color: #0000CC;\">",
	"<span style=\"color: #0000BB;\">"
);

shuffle($codes);
$a=0;
$number=1; while(list(, $code) = each($codes)) {
       if ($a>=$number) { break; }
       if ($code == "") { $uce = "";} else { $uce = "</span>";}
	   $usercolor = $code;
       $a++;
}
if (isset($_POST['color'])) { $colox = array ( "start" => "<span style=\"color: ".$_POST['color'].";\">", "end" => "</span>" ); } else { $colox = array ( "start" => "", "end" => "" ); }
if (isset($_POST['weight']) && $_POST['weight'] != "normal") { $weighti = array ( "start" => "<".$_POST['weight'].">", "end" => "</".$_POST['weight'].">" ); } else { $weighti = array ( "start" => "", "end" => "" ); }
$ts = mktime ( date("G"), date("i"), date("s"), date("m"), date("d"), date("y"));
$posttime = date("d.m.Y G:i:s", $ts);

for($n=0;$n<=$luku;$n++) {
   if($pakolliset[$n] != "") {
      $x = $pakolliset[$n];
      if($_POST[$x] == "") {
         $stop = "yes";
      }
   }
}


if($stop == "") {

foreach($_POST as $x => $y) {
  if( ($x == "pakolliset") ){ continue; }
  $viesti .= "$x: $y\n";
}
$user = $current_user->name;
$fd_palaute = fopen ($filename_palaute , "w");
$fcounted_palaute = $fstring_palaute."\n".$usercolor.$user." ".$uce."<b>".$posttime."</b>:\n ---------------------------- <br />\n".$colox['start'].$weighti["start"].$text.$weighti["end"].$colox['end']."\n<br />-----------------------------------<br />\n";
$fout_palaute = fwrite ($fd_palaute , $fcounted_palaute );
fclose($fd_palaute);

//Handle the return location variables
  $return_module = empty($_REQUEST['return_module']) ? 'SugarChat'
   : $_REQUEST['return_module'];

  $return_action = empty($_REQUEST['return_action']) ? 'DetailView'
   : $_REQUEST['return_action'];

  $return_id = empty($_REQUEST['return_id']) ? ''
   : $_REQUEST['return_id'];
   
   $return_record = empty($_REQUEST['return_record']) ? $_GET['record']
   : $_REQUEST['return_record'];
   
   $return_offset = empty($_REQUEST['return_offset']) ? $_GET['offset']
   : $_REQUEST['return_offset'];

   $return_stamp = empty($_REQUEST['return_stamp']) ? $_GET['stamp']
   : $_REQUEST['return_stamp'];

   
$return_location = "index.php?module=$return_module&action=$return_action&record=$return_record&offset=$return_offset&stamp=$return_stamp";
header("Location: $return_location");
} else {

echo "<b>No message!</b>";

}  }

?>
