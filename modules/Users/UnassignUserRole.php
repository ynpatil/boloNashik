<?php

require_once('XTemplate/xtpl.php');
require_once("data/Tracker.php");
require_once("include/utils.php");
require_once("modules/Users/User.php");

global $app_strings;
global $app_list_strings;
global $current_language;
global $current_user;
global $urlPrefix;
global $currentModule;
$current_module_strings = return_module_language($current_language, 'Users');

if (!is_admin($current_user))
    sugar_die("Unauthorized access to administration.");

$xtpl = new XTemplate('modules/Users/UnassignUserRole.html');
$xtpl->assign("MOD", $current_module_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("THEME", $theme);
$xtpl->assign("IMAGE_PATH", $image_path);
$xtpl->assign("RETURN_URL", "&return_module=$currentModule&return_action=DetailView&return_id=" . ((is_object($focus) && !empty($focus->id)) ? $focus->id : ""));
$xtpl->assign("EDIT_INLINE_PNG", get_image($image_path . 'edit_inline', 'align="absmiddle" alt="' . $app_strings['LNK_EDIT'] . '" border="0"'));
$xtpl->assign("DELETE_INLINE_PNG", get_image($image_path . 'delete_inline', 'align="absmiddle" alt="' . $app_strings['LNK_DELETE'] . '" border="0"'));
//[NAME] => Array ( [width] => 30 [label] => LBL_LIST_NAME [link] => 1 [default] => 1 [related_fields] => Array ( [0] => first_name [1] => last_name [2] => salutation ) ) 
$seed = new User();

if (!$_REQUEST['c']) {
    $sql = "select count(u.id)as cnt from users u left join acl_roles_users a on a.user_id is NULL and a.deleted=0 and u.deleted=0";
    $re = $seed->db->query($sql);
    $out = $seed->db->fetchByAssoc($re);
    $c=$out['cnt'];
} else {
    $c = $_REQUEST['c'];
}

if (!$_REQUEST['s']) {
    $s = 0;
} else {
    $s = $_REQUEST['s'];
}
if (!$_REQUEST['e']) {
    $e = 20;
} else {
    
}
$sql = "select u.id,concat(u.first_name,' ',u.last_name) as user_name from users u left join acl_roles_users a on a.user_id is NULL and a.deleted=0 and u.deleted=0 limit $s,$e";
$r = $seed->db->query($sql);
if ($r) {
    while ($row = $seed->db->fetchByAssoc($r)) {
        $users[] = $row;
    }
}
$ML="index.php?module=Users&action=UnassignUserRole&return_module=Users&return_action=index&c=$c";
$SL=$ML."&s=0";



$ps=$s-$e;
$PL=$ML."&s=$ps";

$es=($c-$e)+1;
$EL=$ML."&s=$es";
$l=$s+$e;
$ns=$l+1;
if($ns<=$c){
$info="$s - $l of $ns+";
}else{
$info="$s - $c";    
}
//$ns=$s+1;
$NL=$ML."&s=$l";

if (is_array($users)) {
     $xtpl->assign("INFO", $info);
     if($s!=0){
         
        $xtpl->assign("START_LINK","<a href=\"{$SL}\"  class=\"listViewPaginationLinkS1\">
            <img src=\"themes/Sugar/images/start.gif\" alt=\"Start\" align=\"absmiddle\" border=\"0\" height=\"11\" width=\"13\">&nbsp;Start
        </a>" );
        $xtpl->assign("PREVIOUS_LINK", " <a href=\"{$PL}\" class=\"listViewPaginationLinkS1\">
            <img src=\"themes/Sugar/images/previous.gif\" alt=\"Previous\" align=\"absmiddle\" border=\"0\" height=\"11\" width=\"8\">&nbsp;Previous</a>");
     }
     
     if($ns<=$c){
        $xtpl->assign("NEXT_LINK", "<a href=\"{$NL}\" class=\"listViewPaginationLinkS1\">Next&nbsp;<img src=\"themes/Sugar/images/next.gif\" alt=\"Next\" align=\"absmiddle\" border=\"0\" height=\"11\" width=\"8\"></a>");
        $xtpl->assign("END_LINK", "<a href=\"{$EL}\" class=\"listViewPaginationLinkS1\">End&nbsp;<img src=\"themes/Sugar/images/end.gif\" alt=\"End\" align=\"absmiddle\" border=\"0\" height=\"11\" width=\"13\"></a>");
     }
    $xtpl->parse("main.header");
   
    foreach ($users as $row) {
        $link = "index.php?action=DetailView&module=Users&record=" . $row['id'];
        $xtpl->assign("LINK", $link);
        $xtpl->assign("USER_NAME", $row['user_name']);
        $xtpl->parse("main.row");
    }
}else{
    echo "<b>No Record Found !</b>";
}

$xtpl->parse("main");
$xtpl->out("main");
?>
